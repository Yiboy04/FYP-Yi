ALTER TABLE car_more_detail
  ADD COLUMN IF NOT EXISTS top_speed_kmh INT NULL AFTER zero_to_hundred_s,
  ADD COLUMN IF NOT EXISTS other_features TEXT NULL AFTER cooling_seat;