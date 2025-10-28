ALTER TABLE car_more_detail
  ADD COLUMN IF NOT EXISTS zero_to_hundred_s DECIMAL(5,2) NULL AFTER driver_assistance;