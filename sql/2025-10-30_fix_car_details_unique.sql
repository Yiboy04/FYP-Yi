-- Ensure car_details has a single row per car
-- 1) Remove exact duplicate rows (keep the latest by primary key if exists)
-- NOTE: Adjust the temp primary key/unique key names if your schema already differs.

START TRANSACTION;

-- If car_details lacks a primary key, add one temporarily to help de-dup (id autoincrement)
-- This block is safe to run repeatedly: it checks for column existence first.
SET @col_exists := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'car_details' AND COLUMN_NAME = 'id');
SET @sql := IF(@col_exists = 0,
  'ALTER TABLE car_details ADD COLUMN id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Remove duplicates keeping the latest row per car_id
CREATE TEMPORARY TABLE tmp_keep AS
SELECT MAX(id) AS keep_id FROM car_details GROUP BY car_id;
DELETE cd FROM car_details cd
LEFT JOIN tmp_keep k ON cd.id = k.keep_id
WHERE k.keep_id IS NULL;
DROP TEMPORARY TABLE tmp_keep;

-- Add/ensure UNIQUE constraint so future duplicates cannot happen
SET @uniq_exists := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'car_details' AND INDEX_NAME = 'uniq_car_details_car_id');
SET @sql2 := IF(@uniq_exists = 0,
  'ALTER TABLE car_details ADD UNIQUE KEY uniq_car_details_car_id (car_id)',
  'SELECT 1');
PREPARE stmt2 FROM @sql2; EXECUTE stmt2; DEALLOCATE PREPARE stmt2;

COMMIT;
