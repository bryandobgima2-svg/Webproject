USE complexe_saint_joseph;

ALTER TABLE customers
  ADD COLUMN IF NOT EXISTS archived_at DATETIME NULL,
  ADD COLUMN IF NOT EXISTS archived_by VARCHAR(150) NULL,
  ADD COLUMN IF NOT EXISTS archive_reason TEXT NULL;

CREATE TABLE IF NOT EXISTS customer_archive_history (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  customer_id BIGINT UNSIGNED NOT NULL,
  customer_name VARCHAR(150) NOT NULL,
  customer_phone VARCHAR(30) NULL,
  archive_reason TEXT NOT NULL,
  archived_by VARCHAR(150) NOT NULL,
  archived_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_customer_archive_history_customer FOREIGN KEY (customer_id)
    REFERENCES customers (id) ON DELETE RESTRICT ON UPDATE CASCADE,
  INDEX idx_customer_archive_history_date (archived_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE debts
  ADD COLUMN IF NOT EXISTS voided_at DATETIME NULL AFTER archived_at,
  ADD COLUMN IF NOT EXISTS voided_by VARCHAR(150) NULL AFTER voided_at,
  ADD COLUMN IF NOT EXISTS void_reason TEXT NULL AFTER voided_by,
  ADD INDEX IF NOT EXISTS idx_debts_voided (voided_at);

ALTER TABLE payments
  ADD COLUMN IF NOT EXISTS transaction_reference VARCHAR(100) NULL AFTER payment_method;
