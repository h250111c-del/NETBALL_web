CREATE DATABASE IF NOT EXISTS dolphin_netball
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE dolphin_netball;

CREATE TABLE IF NOT EXISTS submissions (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(254) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  reason ENUM('General Inquiry', 'Join', 'Sponsorship') NOT NULL,
  message TEXT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_submissions_email (email),
  KEY idx_submissions_created_at (created_at)
) ENGINE=InnoDB
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;