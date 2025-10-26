-- Dummy bulk data: 60+ sold cars across various makes/models in MY dataset
-- All rows use seller_id=4 and listing_status='sold'.
-- Image note: reuses existing 'uploads/ativa(1).webp' as a thumbnail placeholder for all cars.
-- Adjust values as needed.

START TRANSACTION;

SET @seller_id := 4;  -- Ensure this seller exists in your DB

-- 1) Perodua Myvi
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Perodua','Myvi','1.5 AV',2021,'1.5',23000,'AT',52000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note)
VALUES (@car_id,'Red',102,'2NR-VE',4,'185/55 R15','185/55 R15',137,'Hatchback','Used','Popular choice, one owner.');

-- 2) Perodua Axia
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Perodua','Axia','1.0 SE',2020,'1.0',35000,'AT',32000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note)
VALUES (@car_id,'Blue',67,'1KR-VE',4,'175/65 R14','175/65 R14',91,'Hatchback','Used','Fuel saver city car.');

-- 3) Perodua Bezza
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Perodua','Bezza','1.3 X',2019,'1.3',48000,'AT',36000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Silver',95,'1NR-VE',4,'175/65 R14','175/65 R14',121,'Sedan','Used','Well maintained.');

-- 4) Perodua Alza
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Perodua','Alza','1.5 SE',2018,'1.5',65000,'AT',43000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'White',103,'3SZ-VE',4,'185/55 R15','185/55 R15',136,'MPV','Used','7-seater family MPV.');

-- 5) Perodua Ativa
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Perodua','Ativa','1.0 Turbo AV',2022,'1.0',18000,'CVT',69000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Black',98,'KR-VET',7,'205/55 R17','205/55 R17',140,'SUV','Used','Top spec AV.');

-- 6) Proton Saga
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Proton','Saga','1.3 Premium',2021,'1.3',22000,'AT',37000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Grey',95,'S4PH',4,'185/55 R15','185/55 R15',120,'Sedan','Used','Low mileage.');

-- 7) Proton Persona
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Proton','Persona','1.6 Premium',2020,'1.6',40000,'CVT',43000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Maroon',109,'S4PH',7,'195/55 R15','195/55 R15',150,'Sedan','Used','Spacious sedan.');

-- 8) Proton Iriz
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Proton','Iriz','1.6 Executive',2019,'1.6',50000,'CVT',38000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Green',109,'S4PH',7,'195/55 R15','195/55 R15',150,'Hatchback','Used','Nippy hatch.');

-- 9) Proton X50
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Proton','X50','1.5T Flagship',2022,'1.5',15000,'DCT',95000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Red',177,'BMA-1.5T',7,'215/55 R18','215/55 R18',255,'SUV','Used','ADAS equipped.');

-- 10) Proton X70
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Proton','X70','1.8 TGDI Premium',2021,'1.8',30000,'AT',98000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Brown',184,'1.8 TGDI',6,'225/55 R19','225/55 R19',300,'SUV','Used','Premium spec.');

-- 11) Toyota Vios
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Toyota','Vios','1.5 G',2019,'1.5',52000,'CVT',62000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Silver',107,'2NR-FE',7,'195/50 R16','195/50 R16',140,'Sedan','Used','Reliable sedan.');

-- 12) Toyota Yaris
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Toyota','Yaris','1.5 G',2020,'1.5',28000,'CVT',65000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Yellow',107,'2NR-FE',7,'195/50 R16','195/50 R16',140,'Hatchback','Used','Sporty color.');

-- 13) Toyota Corolla
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Toyota','Corolla','1.8 E',2018,'1.8',60000,'AT',78000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'White',139,'2ZR-FE',6,'205/55 R16','205/55 R16',173,'Sedan','Used','Comfortable ride.');

-- 14) Toyota Camry
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Toyota','Camry','2.5 V',2019,'2.5',45000,'AT',138000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Black',181,'A25A-FKS',8,'235/45 R18','235/45 R18',235,'Sedan','Used','Executive sedan.');

-- 15) Toyota Hilux
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Toyota','Hilux','2.8 Rogue',2021,'2.8',38000,'AT',148000.00,'Diesel','4WD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Grey',201,'1GD-FTV',6,'265/60 R18','265/60 R18',500,'Pickup','Used','Tough and capable.');

-- 16) Honda City
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Honda','City','1.5 V',2021,'1.5',20000,'CVT',76000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Grey',121,'L15B',7,'185/55 R16','185/55 R16',145,'Sedan','Used','Fuel efficient.');

-- 17) Honda Civic
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Honda','Civic','1.5 TC',2018,'1.5',70000,'CVT',92000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Blue',173,'L15B7',7,'215/50 R17','215/50 R17',220,'Sedan','Used','Turbo power.');

-- 18) Honda HR-V
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Honda','HR-V','1.8 V',2019,'1.8',50000,'CVT',98000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'White',141,'R18Z1',7,'215/55 R17','215/55 R17',172,'SUV','Used','Practical crossover.');

-- 19) Honda CR-V
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Honda','CR-V','1.5 TC-P',2020,'1.5',42000,'CVT',145000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Brown',193,'L15BE',7,'235/60 R18','235/60 R18',243,'SUV','Used','Top spec TC-P.');

-- 20) Honda Jazz
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Honda','Jazz','1.5 V',2017,'1.5',78000,'CVT',52000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Orange',120,'L15Z1',7,'185/55 R16','185/55 R16',145,'Hatchback','Used','Versatile seats.');

-- 21) Nissan Almera
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Nissan','Almera','1.0 Turbo VLP',2021,'1.0',30000,'CVT',72000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Silver',100,'HRA0',7,'195/55 R16','195/55 R16',152,'Sedan','Used','N18 turbo model.');

-- 22) Nissan X-Trail
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Nissan','X-Trail','2.0 2WD',2018,'2.0',65000,'CVT',88000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'White',144,'MR20DE',7,'225/65 R17','225/65 R17',200,'SUV','Used','7-seater.');

-- 23) Mazda Mazda2
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mazda','Mazda2','1.5 High',2019,'1.5',40000,'AT',62000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Red',114,'P5-VPS',6,'185/60 R16','185/60 R16',148,'Hatchback','Used','Skyactiv drive.');

-- 24) Mazda Mazda3
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mazda','Mazda3','2.0 High',2020,'2.0',28000,'AT',118000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Grey',162,'PE-VPS',6,'215/45 R18','215/45 R18',213,'Sedan','Used','Premium interior.');

-- 25) Mazda CX-5
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mazda','CX-5','2.5 High',2019,'2.5',52000,'AT',130000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Blue',194,'PY-VPS',6,'225/65 R17','225/65 R17',257,'SUV','Used','Popular SUV.');

-- 26) Mitsubishi Triton
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mitsubishi','Triton','2.4 Adventure X',2020,'2.4',45000,'AT',115000.00,'Diesel','4WD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Orange',181,'4N15',6,'265/60 R18','265/60 R18',430,'Pickup','Used','High spec.');

-- 27) Mitsubishi ASX
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mitsubishi','ASX','2.0 2WD',2018,'2.0',68000,'CVT',68000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'White',150,'4B11',7,'215/60 R17','215/60 R17',197,'SUV','Used','Compact SUV.');

-- 28) Mitsubishi Outlander
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mitsubishi','Outlander','2.0 2WD',2017,'2.0',90000,'CVT',78000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Black',145,'4B11',7,'225/55 R18','225/55 R18',196,'SUV','Used','7 seats.');

-- 29) Subaru XV (AWD)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Subaru','XV','2.0 i-P',2019,'2.0',55000,'CVT',98000.00,'Gasoline','AWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Orange',156,'FB20',7,'225/55 R18','225/55 R18',196,'SUV','Used','Symmetrical AWD.');

-- 30) Subaru Forester (AWD)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Subaru','Forester','2.0 i-S',2020,'2.0',38000,'CVT',128000.00,'Gasoline','AWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Green',156,'FB20',7,'225/55 R18','225/55 R18',196,'SUV','Used','i-S EyeSight.');

-- 31) Kia Picanto
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Kia','Picanto','1.2 GT-Line',2019,'1.2',40000,'AT',42000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Red',84,'Kappa',4,'185/55 R15','185/55 R15',122,'Hatchback','Used','Small and zippy.');

-- 32) Kia Cerato
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Kia','Cerato','1.6 EX',2018,'1.6',65000,'AT',53000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Silver',128,'Gamma',6,'205/55 R16','205/55 R16',157,'Sedan','Used','Comfortable.');

-- 33) Kia Sportage
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Kia','Sportage','2.0 EX',2017,'2.0',80000,'AT',70000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Black',155,'Nu 2.0',6,'225/60 R17','225/60 R17',192,'SUV','Used','SUV value.');

-- 34) Hyundai Elantra
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Hyundai','Elantra','1.6 Executive',2019,'1.6',50000,'AT',60000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Blue',128,'Gamma',6,'205/55 R16','205/55 R16',157,'Sedan','Used','Sharp looks.');

-- 35) Hyundai Tucson
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Hyundai','Tucson','2.0 Elegance',2018,'2.0',68000,'AT',78000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Grey',155,'Nu 2.0',6,'225/60 R17','225/60 R17',192,'SUV','Used','Family SUV.');

-- 36) Volkswagen Polo
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Volkswagen','Polo','1.6',2016,'1.6',85000,'AT',38000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'White',105,'CFNA',6,'185/60 R15','185/60 R15',153,'Sedan','Used','German compact.');

-- 37) Volkswagen Golf
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Volkswagen','Golf','1.4 TSI',2017,'1.4',70000,'DCT',68000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Grey',150,'EA211',7,'225/45 R17','225/45 R17',250,'Hatchback','Used','Turbo hatch.');

-- 38) Volkswagen Passat
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Volkswagen','Passat','2.0 TSI',2018,'2.0',60000,'DCT',98000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Black',220,'EA888',7,'235/45 R18','235/45 R18',350,'Sedan','Used','Business class.');

-- 39) BMW 3 Series
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'BMW','3 Series','320i',2017,'2.0',85000,'AT',98000.00,'Gasoline','RWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'White',184,'B48',8,'225/50 R17','225/50 R17',290,'Sedan','Used','Driver’s car.');

-- 40) BMW 5 Series
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'BMW','5 Series','530e',2018,'2.0',65000,'AT',165000.00,'Hybrid','RWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Grey',252,'B48+eDrive',8,'245/45 R18','245/45 R18',420,'Sedan','Used','Plug-in hybrid.');

-- 41) BMW X3
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'BMW','X3','xDrive20i',2019,'2.0',50000,'AT',190000.00,'Gasoline','AWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Blue',184,'B48',8,'245/50 R19','245/50 R19',290,'SUV','Used','xDrive AWD.');

-- 42) Mercedes C-Class
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mercedes','C-Class','C200',2017,'2.0',78000,'AT',135000.00,'Gasoline','RWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Silver',184,'M274',9,'225/50 R17','225/50 R17',300,'Sedan','Used','Luxury compact.');

-- 43) Mercedes E-Class
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mercedes','E-Class','E250',2016,'2.0',90000,'AT',150000.00,'Gasoline','RWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Black',211,'M274',9,'245/45 R18','245/45 R18',350,'Sedan','Used','Executive classic.');

-- 44) Mercedes GLC
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mercedes','GLC','GLC250',2018,'2.0',60000,'AT',195000.00,'Gasoline','RWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'White',211,'M274',9,'235/60 R18','235/60 R18',350,'SUV','Used','Premium SUV.');

-- 45) Audi A4
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Audi','A4','2.0 TFSI',2017,'2.0',85000,'AT',105000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Grey',190,'EA888',7,'225/50 R17','225/50 R17',320,'Sedan','Used','Virtual cockpit.');

-- 46) Audi Q5
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Audi','Q5','2.0 TFSI Quattro',2018,'2.0',65000,'AT',185000.00,'Gasoline','AWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Blue',252,'EA888',7,'235/60 R18','235/60 R18',370,'SUV','Used','Quattro AWD.');

-- 47) Volvo S60
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Volvo','S60','T5',2016,'2.0',90000,'AT',88000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'White',240,'Drive-E',8,'235/45 R18','235/45 R18',350,'Sedan','Used','Safety first.');

-- 48) Volvo XC60
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Volvo','XC60','T6',2018,'2.0',60000,'AT',215000.00,'Hybrid','AWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Grey',316,'T6 PHEV',8,'235/55 R19','235/55 R19',400,'SUV','Used','PHEV variant.');

-- 49) Lexus IS
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Lexus','IS','IS300',2017,'2.0',70000,'AT',135000.00,'Gasoline','RWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Black',241,'8AR-FTS',8,'225/45 R17','225/45 R17',350,'Sedan','Used','Sporty luxury.');

-- 50) Lexus RX
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Lexus','RX','RX350',2016,'3.5',95000,'AT',168000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Silver',296,'2GR-FKS',8,'235/55 R20','235/55 R20',362,'SUV','Used','Comfortable cruiser.');

-- 51) Ford Ranger
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Ford','Ranger','2.0 Biturbo Wildtrak',2020,'2.0',40000,'AT',158000.00,'Diesel','4WD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Orange',210,'EcoBlue',10,'265/60 R18','265/60 R18',500,'Pickup','Used','Wildtrak package.');

-- 52) Ford Focus
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Ford','Focus','1.5 EcoBoost',2016,'1.5',95000,'AT',42000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Blue',180,'Dragon',6,'215/50 R17','215/50 R17',240,'Sedan','Used','Euro handling.');

-- 53) Isuzu D-Max
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Isuzu','D-Max','3.0 V-Cross',2019,'3.0',70000,'AT',120000.00,'Diesel','4WD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'White',177,'4JJ1',6,'255/65 R17','255/65 R17',380,'Pickup','Used','Workhorse.');

-- 54) Peugeot 208
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Peugeot','208','1.2 PureTech',2017,'1.2',80000,'AT',38000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Red',110,'EB2',6,'195/55 R16','195/55 R16',205,'Hatchback','Used','French flair.');

-- 55) Peugeot 3008
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Peugeot','3008','1.6 THP',2018,'1.6',65000,'AT',95000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Grey',165,'EP6FDT',6,'225/55 R18','225/55 R18',240,'SUV','Used','i-Cockpit.');

-- 56) Toyota Innova
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Toyota','Innova','2.0 G',2017,'2.0',90000,'AT',78000.00,'Gasoline','RWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Brown',139,'1TR-FE',6,'205/65 R16','205/65 R16',183,'MPV','Used','7/8-seater MPV.');

-- 57) Toyota Fortuner
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Toyota','Fortuner','2.7 SRZ',2018,'2.7',70000,'AT',150000.00,'Gasoline','RWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Black',166,'2TR-FE',6,'265/60 R18','265/60 R18',245,'SUV','Used','Rugged SUV.');

-- 58) Honda BR-V
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Honda','BR-V','1.5 V',2019,'1.5',60000,'CVT',72000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'White',120,'L15Z1',7,'195/60 R16','195/60 R16',145,'MPV','Used','7-seater crossover.');

-- 59) Nissan Serena
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Nissan','Serena','S-Hybrid',2018,'2.0',80000,'CVT',88000.00,'Hybrid','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Silver',150,'MR20DD+Hybrid',7,'195/65 R15','195/65 R15',200,'MPV','Used','Family MPV hybrid.');

-- 60) Mazda CX-8
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mazda','CX-8','2.5 High',2020,'2.5',35000,'AT',175000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Blue',192,'PY-VPS',6,'225/60 R18','225/60 R18',257,'SUV','Used','3-row SUV.');

-- 61) Proton X90
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Proton','X90','1.5T Flagship',2023,'1.5',12000,'DCT',158000.00,'Hybrid','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Grey',190,'1.5 TGDI+48V',7,'235/50 R20','235/50 R20',300,'SUV','Used','Mild-hybrid.');

-- 62) Honda Accord
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Honda','Accord','1.5 TC-P',2019,'1.5',45000,'CVT',142000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id, color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, car_condition, seller_note)
VALUES (@car_id,'Black',190,'L15BE',7,'235/45 R18','235/45 R18',243,'Sedan','Used','Flagship sedan.');

COMMIT;
