-- 360 media tables within existing `fyp` database
-- Creates optional 360 tables in the same DB as your main app.

USE `fyp`;

-- One optional 360 set per car (by external reference car_id). Not every car has one.
-- We keep car_id as an external reference (no cross-DB FK). If you prefer multiple
-- sets per car (e.g., different days), drop the UNIQUE KEY on car_id.
CREATE TABLE IF NOT EXISTS `car_360_set` (
  `set_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `car_id` INT UNSIGNED NOT NULL,
  `source_car_db` VARCHAR(64) NOT NULL DEFAULT 'fyp',
  `title` VARCHAR(128) NULL,
  `notes` TEXT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`set_id`),
  UNIQUE KEY `u_car` (`car_id`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Exterior frames for a set. Frame index is the order in rotation (0..N-1)
CREATE TABLE IF NOT EXISTS `car_360_exterior_images` (
  `image_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `set_id` INT UNSIGNED NOT NULL,
  `frame_index` INT UNSIGNED NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`image_id`),
  KEY `idx_set_frame` (`set_id`,`frame_index`),
  CONSTRAINT `fk_ext_set` FOREIGN KEY (`set_id`) REFERENCES `car_360_set`(`set_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Interior frames for a set. Same structure, separate table as requested.
CREATE TABLE IF NOT EXISTS `car_360_interior_images` (
  `image_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `set_id` INT UNSIGNED NOT NULL,
  `frame_index` INT UNSIGNED NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`image_id`),
  KEY `idx_set_frame` (`set_id`,`frame_index`),
  CONSTRAINT `fk_int_set` FOREIGN KEY (`set_id`) REFERENCES `car_360_set`(`set_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Notes:
-- - Store image_path as a web-served relative path (e.g., 'uploads/360/exterior/supra_0001.jpg').
-- - Each car typically holds 25–40 frames for a smooth spin; the schema allows any count.
-- - If a car has no 360 set, there will be no row in car_360_set for that car_id (NULL by absence).
-- - Keep files under a dedicated folder like htdocs/FYP/uploads/360/{exterior|interior}/.
