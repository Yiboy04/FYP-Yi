-- Dummy data: 5x Perodua Ativa with two images each and optional details
-- Adjust @seller_id to an existing seller in your DB before running.
-- Ensure these files exist (relative to your web root):
--   uploads/ativa(1).webp
--   uploads/ativa(2).webp

START TRANSACTION;

SET @seller_id := 2;  -- TODO: change to a valid sellers.id

-- 1) Perodua Ativa (AV)
INSERT INTO cars
  (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES
  (@seller_id, 'Perodua', 'Ativa', '1.0 Turbo AV', 2022, '1.0', 15000, 'CVT', 68000.00, 'Gasoline', 'FWD', 5, 'open');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES
  (@car_id, 'uploads/ativa(1).webp', 1),
  (@car_id, 'uploads/ativa(2).webp', 0);
INSERT INTO car_details
  (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES
  (@car_id, 'Red', 98, 'KR-VET', 7, '205/55 R17', '205/55 R17', 140, 'SUV', 'Used', 'Demo Ativa AV.');

-- 2) Perodua Ativa (X)
INSERT INTO cars
  (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES
  (@seller_id, 'Perodua', 'Ativa', '1.0 Turbo X', 2021, '1.0', 28000, 'CVT', 62000.00, 'Gasoline', 'FWD', 5, 'open');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES
  (@car_id, 'uploads/ativa(1).webp', 1),
  (@car_id, 'uploads/ativa(2).webp', 0);
INSERT INTO car_details
  (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES
  (@car_id, 'Blue', 98, 'KR-VET', 7, '205/60 R16', '205/60 R16', 140, 'SUV', 'Reconditioned', 'Well-kept unit.');

-- 3) Perodua Ativa (H)
INSERT INTO cars
  (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES
  (@seller_id, 'Perodua', 'Ativa', '1.0 Turbo H', 2023, '1.0', 9000, 'CVT', 73500.00, 'Gasoline', 'FWD', 5, 'open');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES
  (@car_id, 'uploads/ativa(1).webp', 1),
  (@car_id, 'uploads/ativa(2).webp', 0);
INSERT INTO car_details
  (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES
  (@car_id, 'White', 98, 'KR-VET', 7, '205/55 R17', '205/55 R17', 140, 'SUV', 'New', 'Low mileage, warranty balance.');

-- 4) Perodua Ativa (SE)
INSERT INTO cars
  (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES
  (@seller_id, 'Perodua', 'Ativa', '1.0 Turbo SE', 2020, '1.0', 42000, 'CVT', 58500.00, 'Gasoline', 'FWD', 5, 'open');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES
  (@car_id, 'uploads/ativa(1).webp', 1),
  (@car_id, 'uploads/ativa(2).webp', 0);
INSERT INTO car_details
  (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES
  (@car_id, 'Grey', 98, 'KR-VET', 7, '205/60 R16', '205/60 R16', 140, 'SUV', 'Used', 'Value buy.');

-- 5) Perodua Ativa (S)
INSERT INTO cars
  (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES
  (@seller_id, 'Perodua', 'Ativa', '1.0 Turbo S', 2024, '1.0', 3000, 'CVT', 76800.00, 'Gasoline', 'FWD', 5, 'open');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES
  (@car_id, 'uploads/ativa(1).webp', 1),
  (@car_id, 'uploads/ativa(2).webp', 0);
INSERT INTO car_details
  (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES
  (@car_id, 'Black', 98, 'KR-VET', 7, '205/55 R17', '205/55 R17', 140, 'SUV', 'New', 'Latest spec, demo car.');

COMMIT;
