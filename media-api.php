<?php
declare(strict_types=1);

const MAX_VIDEO_BYTES = 536870912;
const MAX_COVER_BYTES = 5242880;

header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, no-store');

function respond(array $data, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function storageDirectory(): string
{
    return __DIR__ . DIRECTORY_SEPARATOR . 'storage';
}

function ensureStorage(): void
{
    $directory = storageDirectory();
    if (!is_dir($directory) && !mkdir($directory, 0750, true) && !is_dir($directory)) {
        throw new RuntimeException('Could not create the protected storage folder.');
    }
}

function catalogLock(int $mode): mixed
{
    ensureStorage();
    $lock = fopen(storageDirectory() . DIRECTORY_SEPARATOR . 'catalog.lock', 'c');
    if ($lock === false || !flock($lock, $mode)) {
        throw new RuntimeException('Could not lock the media catalog.');
    }
    return $lock;
}

function readCatalog(): array
{
    $path = storageDirectory() . DIRECTORY_SEPARATOR . 'catalog.json';
    if (!is_file($path)) {
        return [];
    }
    $contents = file_get_contents($path);
    if ($contents === false) {
        throw new RuntimeException('Could not read the media catalog.');
    }
    $catalog = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
    if (!is_array($catalog) || !array_is_list($catalog)) {
        throw new RuntimeException('The media catalog has an invalid format.');
    }
    return $catalog;
}

function writeCatalog(array $catalog): void
{
    $encoded = json_encode($catalog, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
    if (file_put_contents(storageDirectory() . DIRECTORY_SEPARATOR . 'catalog.json', $encoded, LOCK_EX) === false) {
        throw new RuntimeException('Could not save the media catalog.');
    }
}

function getMedia(array $catalog, string $id): array
{
    if (!preg_match('/^[a-f0-9]{32}$/', $id)) {
        respond(['error' => 'Invalid media id.'], 404);
    }
    foreach ($catalog as $item) {
        if (is_array($item) && ($item['id'] ?? '') === $id) {
            return $item;
        }
    }
    respond(['error' => 'Title not found.'], 404);
}

function videoTypes(): array
{
    return [
        'mp4' => ['video/mp4'], 'm4v' => ['video/mp4', 'video/x-m4v'],
        'webm' => ['video/webm'], 'ogv' => ['video/ogg'], 'ogg' => ['video/ogg'],
        'mov' => ['video/quicktime'], 'mkv' => ['video/x-matroska'],
        'avi' => ['video/x-msvideo', 'video/avi'], 'mpg' => ['video/mpeg'],
        'mpeg' => ['video/mpeg'],
    ];
}

function textLength(string $value): int
{
    $length = preg_match_all('/./us', $value, $matches);
    return $length === false ? strlen($value) : $length;
}

function isValidUtf8(string $value): bool
{
    return preg_match('//u', $value) === 1;
}

function detectVideoMime(string $path, string $extension): ?string
{
    $header = file_get_contents($path, false, null, 0, 65536);
    if ($header === false) {
        return null;
    }
    if (strlen($header) >= 12 && substr($header, 4, 4) === 'ftyp'
        && in_array($extension, ['mp4', 'm4v', 'mov'], true)) {
        return $extension === 'mov' ? 'video/quicktime' : 'video/mp4';
    }
    if (str_starts_with($header, "\x1A\x45\xDF\xA3") && in_array($extension, ['webm', 'mkv'], true)) {
        return $extension === 'webm' ? 'video/webm' : 'video/x-matroska';
    }
    if (str_starts_with($header, 'OggS') && in_array($extension, ['ogg', 'ogv'], true)) {
        return 'video/ogg';
    }
    if (strlen($header) >= 12 && substr($header, 0, 4) === 'RIFF' && substr($header, 8, 4) === 'AVI '
        && $extension === 'avi') {
        return 'video/x-msvideo';
    }
    if (in_array($extension, ['mpg', 'mpeg'], true)
        && (str_contains($header, "\x00\x00\x01\xB3") || str_contains($header, "\x00\x00\x01\xBA"))) {
        return 'video/mpeg';
    }
    return null;
}

function uploadedFile(array $files, string $name, int $maxBytes, array $allowedTypes): array
{
    $file = $files[$name] ?? null;
    if (!is_array($file) || !isset($file['error'], $file['tmp_name'], $file['size'], $file['name'])) {
        respond(['error' => "Select a valid {$name} file."], 422);
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $message = $file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE
            ? 'The file is larger than the PHP server upload limit. Increase upload_max_filesize and post_max_size in php.ini.'
            : 'The upload did not complete. Check the server upload settings and try again.';
        respond(['error' => $message], 422);
    }
    if (!is_uploaded_file($file['tmp_name']) || $file['size'] < 1 || $file['size'] > $maxBytes) {
        respond(['error' => 'The file is empty or exceeds the application size limit.'], 422);
    }
    $extension = strtolower(pathinfo(basename((string)$file['name']), PATHINFO_EXTENSION));
    if (!isset($allowedTypes[$extension])) {
        respond(['error' => 'This file type is not supported.'], 415);
    }
    if ($name === 'video') {
        $mime = detectVideoMime($file['tmp_name'], $extension);
    } else {
        $imageInfo = @getimagesize($file['tmp_name']);
        $mime = $imageInfo === false ? null : $imageInfo['mime'];
    }
    if (!is_string($mime) || !in_array($mime, $allowedTypes[$extension], true)) {
        respond(['error' => 'The file content does not match a supported media type.'], 415);
    }
    return [$file, $extension, $mime];
}

function streamFile(string $path, string $mime, string $filename, bool $download): never
{
    if (!is_file($path) || !is_readable($path)) {
        respond(['error' => 'The stored media file is missing or unavailable.'], 404);
    }
    $size = filesize($path);
    if ($size === false) {
        respond(['error' => 'Could not read the media file size.'], 500);
    }
    $start = 0;
    $end = max(0, $size - 1);
    $status = 200;
    $range = $_SERVER['HTTP_RANGE'] ?? '';
    if ($range !== '') {
        if (!preg_match('/^bytes=(\d*)-(\d*)$/', $range, $matches) || $size === 0) {
            header("Content-Range: bytes */{$size}");
            respond(['error' => 'Invalid byte range.'], 416);
        }
        if ($matches[1] === '') {
            $suffix = (int)$matches[2];
            if ($suffix < 1) {
                header("Content-Range: bytes */{$size}");
                respond(['error' => 'Invalid byte range.'], 416);
            }
            $start = max(0, $size - $suffix);
        } else {
            $start = (int)$matches[1];
            if ($matches[2] !== '') {
                $end = (int)$matches[2];
            }
        }
        if ($start >= $size || $end < $start) {
            header("Content-Range: bytes */{$size}");
            respond(['error' => 'Requested byte range is not satisfiable.'], 416);
        }
        $end = min($end, $size - 1);
        $status = 206;
    }

    http_response_code($status);
    header('Content-Type: ' . $mime);
    header('Accept-Ranges: bytes');
    header('Content-Length: ' . ($end - $start + 1));
    header('Content-Disposition: ' . ($download ? 'attachment' : 'inline') . '; filename="' . addcslashes($filename, '"\\') . '"; filename*=UTF-8\'\'' . rawurlencode($filename));
    if ($status === 206) {
        header("Content-Range: bytes {$start}-{$end}/{$size}");
    }
    $handle = fopen($path, 'rb');
    if ($handle === false || fseek($handle, $start) !== 0) {
        respond(['error' => 'Could not open the media file.'], 500);
    }
    $remaining = $end - $start + 1;
    while ($remaining > 0 && !feof($handle)) {
        $chunk = fread($handle, min(1048576, $remaining));
        if ($chunk === false || $chunk === '') {
            break;
        }
        echo $chunk;
        $remaining -= strlen($chunk);
    }
    fclose($handle);
    exit;
}

function filePathFor(array $item, string $field): string
{
    $name = $item[$field] ?? '';
    if (!is_string($name) || !preg_match('/^[a-f0-9]{32}\.[a-z0-9]+$/', $name)) {
        respond(['error' => 'Invalid stored media reference.'], 500);
    }
    return storageDirectory() . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . $name;
}

try {
    $action = $_GET['action'] ?? '';
    if ($action === 'list' && $_SERVER['REQUEST_METHOD'] === 'GET') {
        $lock = catalogLock(LOCK_SH);
        try {
            $catalog = readCatalog();
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
        usort($catalog, static fn(array $a, array $b): int => strcmp((string)($b['createdAt'] ?? ''), (string)($a['createdAt'] ?? '')));
        $items = array_map(static function (array $item): array {
            $id = (string)($item['id'] ?? '');
            $item['streamUrl'] = 'media-api.php?action=stream&id=' . rawurlencode($id);
            $item['downloadUrl'] = 'media-api.php?action=download&id=' . rawurlencode($id);
            $item['posterUrl'] = !empty($item['cover']) ? 'media-api.php?action=poster&id=' . rawurlencode($id) : '';
            unset($item['video'], $item['cover']);
            return $item;
        }, $catalog);
        respond(['items' => $items]);
    }

    if (in_array($action, ['stream', 'download', 'poster'], true) && $_SERVER['REQUEST_METHOD'] === 'GET') {
        $lock = catalogLock(LOCK_SH);
        try {
            $item = getMedia(readCatalog(), (string)($_GET['id'] ?? ''));
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
        if ($action === 'poster') {
            if (empty($item['cover'])) {
                respond(['error' => 'This title has no cover image.'], 404);
            }
            $cover = filePathFor($item, 'cover');
            $extension = strtolower(pathinfo($cover, PATHINFO_EXTENSION));
            $imageMimes = ['jpg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];
            if (!isset($imageMimes[$extension])) {
                respond(['error' => 'Invalid cover image.'], 500);
            }
            streamFile($cover, $imageMimes[$extension], basename($cover), false);
        }
        $video = filePathFor($item, 'video');
        $extension = strtolower(pathinfo($video, PATHINFO_EXTENSION));
        $types = videoTypes();
        if (!isset($types[$extension])) {
            respond(['error' => 'Invalid video file.'], 500);
        }
        $title = preg_replace('/[^A-Za-z0-9._-]+/', '_', (string)($item['title'] ?? 'video'));
        $filename = trim((string)$title, '._-') ?: 'video';
        streamFile($video, $types[$extension][0], $filename . '.' . $extension, $action === 'download');
    }

    if ($action !== 'upload' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
        respond(['error' => 'Unknown action or unsupported request method.'], 405);
    }
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    if ($origin !== '' && strtolower((string)parse_url($origin, PHP_URL_HOST)) !== strtolower((string)($_SERVER['SERVER_NAME'] ?? ''))) {
        respond(['error' => 'Cross-origin uploads are not allowed.'], 403);
    }
    $contentLength = (int)($_SERVER['CONTENT_LENGTH'] ?? 0);
    if ($contentLength > MAX_VIDEO_BYTES + MAX_COVER_BYTES + 1048576) {
        respond(['error' => 'The upload exceeds the 512 MB application limit.'], 413);
    }
    if ($contentLength > 0 && empty($_POST) && empty($_FILES)) {
        respond(['error' => 'The request exceeds the PHP server upload limit. Increase post_max_size and upload_max_filesize in php.ini.'], 413);
    }

    $title = trim((string)($_POST['title'] ?? ''));
    $type = (string)($_POST['type'] ?? '');
    $yearValue = trim((string)($_POST['year'] ?? ''));
    $genre = trim((string)($_POST['genre'] ?? ''));
    $episode = trim((string)($_POST['episode'] ?? ''));
    $description = trim((string)($_POST['description'] ?? ''));
    if (!isValidUtf8($title) || !isValidUtf8($genre) || !isValidUtf8($episode) || !isValidUtf8($description)
        || $title === '' || textLength($title) > 120 || !in_array($type, ['movie', 'series', 'anime'], true)
        || textLength($genre) > 60 || textLength($episode) > 80 || textLength($description) > 2000) {
        respond(['error' => 'Check the title details and make sure each field is within its length limit.'], 422);
    }
    $year = null;
    if ($yearValue !== '') {
        if (!ctype_digit($yearValue) || (int)$yearValue < 1888 || (int)$yearValue > 2100) {
            respond(['error' => 'Enter a valid release year between 1888 and 2100.'], 422);
        }
        $year = (int)$yearValue;
    }
    [$videoUpload, $videoExtension, $videoMime] = uploadedFile($_FILES, 'video', MAX_VIDEO_BYTES, videoTypes());
    $coverUpload = null;
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] !== UPLOAD_ERR_NO_FILE) {
        [$coverUpload, $coverExtension, $coverMime] = uploadedFile($_FILES, 'cover', MAX_COVER_BYTES, [
            'jpg' => ['image/jpeg'], 'jpeg' => ['image/jpeg'], 'png' => ['image/png'], 'webp' => ['image/webp'],
        ]);
        $imageInfo = @getimagesize($coverUpload['tmp_name']);
        if ($imageInfo === false || $imageInfo['mime'] !== $coverMime) {
            respond(['error' => 'The cover file is not a valid JPG, PNG, or WebP image.'], 415);
        }
        $coverExtension = $coverExtension === 'jpeg' ? 'jpg' : $coverExtension;
    }

    $mediaDirectory = storageDirectory() . DIRECTORY_SEPARATOR . 'media';
    ensureStorage();
    if (!is_dir($mediaDirectory) && !mkdir($mediaDirectory, 0750, true) && !is_dir($mediaDirectory)) {
        throw new RuntimeException('Could not create the protected media folder.');
    }
    $id = bin2hex(random_bytes(16));
    $videoName = $id . '.' . $videoExtension;
    $videoPath = $mediaDirectory . DIRECTORY_SEPARATOR . $videoName;
    $coverName = $coverUpload === null ? null : $id . '.' . $coverExtension;
    $coverPath = $coverName === null ? null : $mediaDirectory . DIRECTORY_SEPARATOR . $coverName;
    if (!move_uploaded_file($videoUpload['tmp_name'], $videoPath)) {
        throw new RuntimeException('Could not save the uploaded video.');
    }
    if ($coverUpload !== null && !move_uploaded_file($coverUpload['tmp_name'], (string)$coverPath)) {
        unlink($videoPath);
        throw new RuntimeException('Could not save the uploaded cover image.');
    }

    $item = [
        'id' => $id, 'title' => $title, 'type' => $type, 'year' => $year,
        'genre' => $genre, 'episode' => $episode, 'description' => $description,
        'video' => $videoName, 'cover' => $coverName, 'mime' => $videoMime,
        'size' => (int)$videoUpload['size'], 'createdAt' => date(DATE_ATOM),
    ];
    $lock = catalogLock(LOCK_EX);
    try {
        $catalog = readCatalog();
        $catalog[] = $item;
        writeCatalog($catalog);
    } catch (Throwable $error) {
        unlink($videoPath);
        if ($coverPath !== null && is_file($coverPath)) {
            unlink($coverPath);
        }
        throw $error;
    } finally {
        flock($lock, LOCK_UN);
        fclose($lock);
    }
    respond(['ok' => true, 'item' => $item], 201);
} catch (Throwable $error) {
    error_log('NovaFlix media API error: ' . $error->getMessage());
    respond(['error' => 'The server could not complete this request. Check the PHP error log and storage permissions.'], 500);
}
