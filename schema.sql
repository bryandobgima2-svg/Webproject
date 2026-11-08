CREATE DATABASE IF NOT EXISTS complexe_saint_joseph
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE complexe_saint_joseph;

CREATE TABLE IF NOT EXISTS customers (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(150) NOT NULL,
  phone VARCHAR(30) NULL,
  notes TEXT NULL,
  archived_at DATETIME NULL,
  archived_by VARCHAR(150) NULL,
  archive_reason TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  INDEX idx_customers_name (name),
  INDEX idx_customers_phone (phone)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS debt_reset_history (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  sector ENUM('Snack', 'Cave', 'Tous') NOT NULL,
  total_debt DECIMAL(14,2) NOT NULL,
  debt_count INT UNSIGNED NOT NULL,
  performed_by VARCHAR(150) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS debts (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  customer_id BIGINT UNSIGNED NOT NULL,
  sector ENUM('Snack', 'Cave') NOT NULL,
  description VARCHAR(255) NOT NULL,
  quantity DECIMAL(12,3) NOT NULL DEFAULT 1,
  amount DECIMAL(14,2) NOT NULL,
  debt_date DATE NOT NULL,
  due_date DATE NOT NULL,
  notes TEXT NULL,
  archived_at DATETIME NULL,
  voided_at DATETIME NULL,
  voided_by VARCHAR(150) NULL,
  void_reason TEXT NULL,
  reset_id BIGINT UNSIGNED NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_debts_customer FOREIGN KEY (customer_id)
    REFERENCES customers (id) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_debts_reset FOREIGN KEY (reset_id)
    REFERENCES debt_reset_history (id) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT chk_debt_quantity_positive CHECK (quantity > 0),
  CONSTRAINT chk_debt_amount_positive CHECK (amount > 0),
  INDEX idx_debts_sector_date (sector, debt_date),
  INDEX idx_debts_due_date (due_date),
  INDEX idx_debts_archived (archived_at),
  INDEX idx_debts_voided (voided_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS payments (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  debt_id BIGINT UNSIGNED NOT NULL,
  amount DECIMAL(14,2) NOT NULL,
  payment_date DATETIME NOT NULL,
  payment_method VARCHAR(40) NOT NULL,
  transaction_reference VARCHAR(100) NULL,
  notes TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_payments_debt FOREIGN KEY (debt_id)
    REFERENCES debts (id) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT chk_payment_amount_positive CHECK (amount > 0),
  INDEX idx_payments_debt_date (debt_id, payment_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS debt_change_history (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  debt_id BIGINT UNSIGNED NOT NULL,
  old_values LONGTEXT NOT NULL,
  new_values LONGTEXT NOT NULL,
  changed_by VARCHAR(150) NOT NULL,
  changed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_debt_change_history_debt FOREIGN KEY (debt_id)
    REFERENCES debts (id) ON DELETE RESTRICT ON UPDATE CASCADE,
  INDEX idx_debt_change_history_debt_date (debt_id, changed_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS customer_archive_history (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  customer_id BIGINT UNSIGNED NOT NULL,
  customer_name VARCHAR(150) NOT NULL,
  customer_phone VARCHAR(30) NULL,
  archive_reason TEXT NOT NULL,
  archived_by VARCHAR(150) NOT NULL,
  archived_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_customer_archive_history_customer FOREIGN KEY (customer_id)
    REFERENCES customers (id) ON DELETE RESTRICT ON UPDATE CASCADE,
  INDEX idx_customer_archive_history_date (archived_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;