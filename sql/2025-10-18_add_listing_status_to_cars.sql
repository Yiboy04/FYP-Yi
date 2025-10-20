-- Migration: Add listing_status to cars
-- Values: open (default), sold, considering

ALTER TABLE `cars`
  ADD COLUMN `listing_status` ENUM('open','sold','considering') NOT NULL DEFAULT 'open' AFTER `doors`;

-- Optional: set existing rows to 'open' explicitly (not required if NOT NULL DEFAULT applied)
-- UPDATE cars SET listing_status='open' WHERE listing_status IS NULL;
