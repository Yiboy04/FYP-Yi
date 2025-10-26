-- Dummy bulk data: 50+ sold cars for premium EU makes (Audi, Volkswagen, BMW, Mercedes)
-- All rows use seller_id=4 and listing_status='sold'.
-- Models strictly from data/makes_models_my.json.
-- Image note: reuses existing 'uploads/ativa(1).webp' as a thumbnail placeholder for all cars.

START TRANSACTION;

SET @seller_id := 4;  -- Ensure this seller exists in your DB

-- AUDI (1-15)
-- 1) Audi A1
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Audi','A1','1.0 TFSI',2018,'1.0',55000,'AT',69000.00,'Gasoline','FWD',3,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note)
VALUES (@car_id,'White',116,'EA211',7,'205/55 R16','205/55 R16',200,'Hatchback','Used','Compact premium.');

-- 2) Audi A3
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Audi','A3','1.4 TFSI',2017,'1.4',72000,'AT',78000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Grey',150,'EA211',7,'225/45 R17','225/45 R17',250,'Sedan','Used','Well kept.');

-- 3) Audi A4
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Audi','A4','2.0 TFSI',2018,'2.0',65000,'AT',112000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Black',190,'EA888',7,'225/50 R17','225/50 R17',320,'Sedan','Used','Virtual cockpit.');

-- 4) Audi A5
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Audi','A5','2.0 TFSI Sportback',2019,'2.0',48000,'AT',168000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Blue',190,'EA888',7,'245/40 R18','245/40 R18',320,'Hatchback','Used','Sportback styling.');

-- 5) Audi A6
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Audi','A6','2.0 TFSI',2017,'2.0',88000,'AT',135000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Silver',190,'EA888',7,'245/45 R18','245/45 R18',320,'Sedan','Used','Executive sedan.');

-- 6) Audi Q2
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Audi','Q2','1.4 TFSI',2018,'1.4',60000,'AT',108000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Red',150,'EA211',7,'215/55 R17','215/55 R17',250,'SUV','Used','Compact SUV.');

-- 7) Audi Q3
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Audi','Q3','1.4 TFSI',2017,'1.4',70000,'AT',98000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Orange',150,'EA211',7,'235/50 R18','235/50 R18',250,'SUV','Used','Practical premium.');

-- 8) Audi Q5
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Audi','Q5','2.0 TFSI Quattro',2019,'2.0',50000,'AT',185000.00,'Gasoline','AWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Blue',252,'EA888',7,'235/60 R18','235/60 R18',370,'SUV','Used','Quattro AWD.');

-- 9) Audi Q7
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Audi','Q7','3.0 TFSI',2017,'3.0',82000,'AT',215000.00,'Gasoline','AWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Grey',333,'EA839',8,'255/55 R19','255/55 R19',440,'SUV','Used','7-seater luxury.');

-- 10) Audi e-tron
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Audi','e-tron','55 quattro',2020,'0.0',38000,'AT',255000.00,'Electric','AWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Black',402,'EV',1,'255/50 R20','255/50 R20',664,'SUV','Used','EV luxury.');

-- 11) Audi A4 (alt)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Audi','A4','1.4 TFSI',2017,'1.4',78000,'AT',92000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'White',150,'EA211',7,'225/50 R17','225/50 R17',250,'Sedan','Used','Clean unit.');

-- 12) Audi Q5 (diesel)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Audi','Q5','2.0 TDI quattro',2018,'2.0',65000,'AT',168000.00,'Diesel','AWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Silver',190,'EA288',7,'235/60 R18','235/60 R18',400,'SUV','Used','Efficient diesel.');

-- 13) Audi A6 (hybrid)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Audi','A6','50 TFSI e',2021,'2.0',22000,'AT',265000.00,'Hybrid','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Blue',299,'EA888+EV',7,'245/45 R19','245/45 R19',450,'Sedan','Used','PHEV.');

-- 14) Audi A3 (ML)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Audi','A3','1.0 TFSI',2019,'1.0',40000,'AT',98000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Yellow',116,'EA211',7,'205/55 R16','205/55 R16',200,'Sedan','Used','Facelift model.');

-- 15) Audi Q3 (newer)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Audi','Q3','1.4 TFSI',2020,'1.4',24000,'AT',148000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Orange',150,'EA211',7,'235/50 R18','235/50 R18',250,'SUV','Used','Low mileage.');

-- VOLKSWAGEN (16-28)
-- 16) Volkswagen Polo
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Volkswagen','Polo','1.6',2016,'1.6',82000,'AT',36000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'White',105,'CFNA',6,'185/60 R15','185/60 R15',153,'Sedan','Used','Solid compact.');

-- 17) Volkswagen Vento
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Volkswagen','Vento','1.6',2017,'1.6',76000,'AT',41000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Silver',105,'CFNA',6,'185/60 R15','185/60 R15',153,'Sedan','Used','Comfortable ride.');

-- 18) Volkswagen Golf
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Volkswagen','Golf','1.4 TSI',2018,'1.4',58000,'DCT',72000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Grey',150,'EA211',7,'225/45 R17','225/45 R17',250,'Hatchback','Used','Turbo hatch.');

-- 19) Volkswagen Jetta
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Volkswagen','Jetta','1.4 TSI',2016,'1.4',90000,'DCT',52000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Black',150,'EA211',7,'205/55 R16','205/55 R16',250,'Sedan','Used','German sedan.');

-- 20) Volkswagen Passat
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Volkswagen','Passat','2.0 TSI',2019,'2.0',48000,'DCT',98000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Blue',220,'EA888',7,'235/45 R18','235/45 R18',350,'Sedan','Used','Business class.');

-- 21) Volkswagen Tiguan
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Volkswagen','Tiguan','1.4 TSI',2018,'1.4',65000,'DCT',88000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'White',150,'EA211',7,'235/55 R18','235/55 R18',250,'SUV','Used','Family SUV.');

-- 22) Volkswagen Touareg
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Volkswagen','Touareg','3.0 TDI',2017,'3.0',98000,'AT',145000.00,'Diesel','AWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Grey',245,'V6 TDI',8,'255/55 R19','255/55 R19',550,'SUV','Used','V6 TDI torque.');

-- 23) Volkswagen Beetle
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Volkswagen','Beetle','1.2 TSI',2016,'1.2',74000,'DCT',65000.00,'Gasoline','FWD',3,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Yellow',105,'EA111',7,'215/55 R17','215/55 R17',175,'Coupe','Used','Iconic shape.');

-- 24) Volkswagen Golf (GTI)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Volkswagen','Golf','2.0 GTI',2017,'2.0',70000,'DCT',118000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Red',220,'EA888',7,'225/40 R18','225/40 R18',350,'Hatchback','Used','GTI hot hatch.');

-- 25) Volkswagen Passat (R-Line)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Volkswagen','Passat','2.0 TSI R-Line',2020,'2.0',42000,'DCT',118000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Grey',220,'EA888',7,'235/45 R18','235/45 R18',350,'Sedan','Used','R-Line pack.');

-- 26) Volkswagen Tiguan (4Motion)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Volkswagen','Tiguan','1.4 TSI 4Motion',2019,'1.4',52000,'DCT',98000.00,'Gasoline','AWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Black',150,'EA211',7,'235/55 R18','235/55 R18',250,'SUV','Used','AWD variant.');

-- 27) Volkswagen Jetta (HL)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Volkswagen','Jetta','1.4 TSI Highline',2016,'1.4',88000,'DCT',56000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'White',150,'EA211',7,'205/55 R16','205/55 R16',250,'Sedan','Used','Highline spec.');

-- 28) Volkswagen Polo (HB)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Volkswagen','Polo','1.6 Hatchback',2016,'1.6',76000,'AT',37000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Blue',105,'CFNA',6,'185/60 R15','185/60 R15',153,'Hatchback','Used','Hatch variant.');

-- BMW (29-47)
-- 29) BMW 1 Series
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'BMW','1 Series','118i',2017,'1.5',68000,'AT',85000.00,'Gasoline','RWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'White',136,'B38',8,'225/45 R17','225/45 R17',220,'Hatchback','Used','Premium compact.');

-- 30) BMW 2 Series
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'BMW','2 Series','218i Gran Coupe',2020,'1.5',35000,'AT',165000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Grey',140,'B38',7,'225/45 R18','225/45 R18',220,'Sedan','Used','Gran Coupe.');

-- 31) BMW 3 Series
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'BMW','3 Series','320i',2018,'2.0',60000,'AT',115000.00,'Gasoline','RWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Black',184,'B48',8,'225/50 R17','225/50 R17',290,'Sedan','Used','Well balanced.');

-- 32) BMW 4 Series
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'BMW','4 Series','430i Gran Coupe',2019,'2.0',45000,'AT',188000.00,'Gasoline','RWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Blue',252,'B48',8,'225/45 R18','225/45 R18',350,'Hatchback','Used','Gran Coupe style.');

-- 33) BMW 5 Series
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'BMW','5 Series','530e',2018,'2.0',62000,'AT',168000.00,'Hybrid','RWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Silver',252,'B48+eDrive',8,'245/45 R18','245/45 R18',420,'Sedan','Used','PHEV sedan.');

-- 34) BMW 7 Series
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'BMW','7 Series','730Li',2017,'2.0',80000,'AT',175000.00,'Gasoline','RWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'White',258,'B48',8,'245/50 R18','245/50 R18',400,'Sedan','Used','Long wheelbase.');

-- 35) BMW X1
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'BMW','X1','sDrive20i',2018,'2.0',52000,'AT',98000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Grey',192,'B48',8,'225/50 R18','225/50 R18',280,'SUV','Used','Compact SAV.');

-- 36) BMW X3
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'BMW','X3','xDrive20i',2019,'2.0',48000,'AT',190000.00,'Gasoline','AWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Blue',184,'B48',8,'245/50 R19','245/50 R19',290,'SUV','Used','xDrive AWD.');

-- 37) BMW X4
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'BMW','X4','xDrive30i',2019,'2.0',42000,'AT',228000.00,'Gasoline','AWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Black',252,'B48',8,'245/45 R20','245/45 R20',350,'SUV','Used','Sport Activity Coupe.');

-- 38) BMW X5
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'BMW','X5','xDrive40i',2020,'3.0',35000,'AT',388000.00,'Gasoline','AWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'White',340,'B58',8,'275/45 R20','275/45 R20',450,'SUV','Used','Luxury SAV.');

-- 39) BMW X6
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'BMW','X6','xDrive35i',2017,'3.0',78000,'AT',245000.00,'Gasoline','AWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Brown',306,'N55',8,'275/45 R20','275/45 R20',400,'SUV','Used','Coupe roofline.');

-- 40) BMW i3 (EV)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'BMW','i3','94Ah',2018,'0.0',42000,'AT',125000.00,'Electric','RWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Silver',170,'EV',1,'175/60 R19','195/50 R19',250,'Hatchback','Used','Electric city car.');

-- 41) BMW i4 (EV)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'BMW','i4','eDrive40',2022,'0.0',15000,'AT',318000.00,'Electric','RWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Blue',340,'EV',1,'245/45 R18','245/45 R18',430,'Sedan','Used','Long-range EV.');

-- 42) BMW iX (EV)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'BMW','iX','xDrive40',2022,'0.0',18000,'AT',415000.00,'Electric','AWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Grey',326,'EV',1,'235/60 R20','235/60 R20',630,'SUV','Used','Next-gen EV.');

-- 43) BMW 3 Series (330e)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'BMW','3 Series','330e M Sport',2020,'2.0',30000,'AT',198000.00,'Hybrid','RWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'White',292,'B48+EV',8,'225/45 R18','225/45 R18',420,'Sedan','Used','PHEV M Sport.');

-- 44) BMW 5 Series (diesel)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'BMW','5 Series','520d',2017,'2.0',90000,'AT',115000.00,'Diesel','RWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Black',190,'B47',8,'245/45 R18','245/45 R18',400,'Sedan','Used','Efficient diesel.');

-- 45) BMW 4 Series (M Sport)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'BMW','4 Series','430i M Sport',2018,'2.0',52000,'AT',175000.00,'Gasoline','RWD',2,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Blue',252,'B48',8,'225/45 R18','225/45 R18',350,'Coupe','Used','M Sport kit.');

-- 46) BMW X1 (sDrive18i)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'BMW','X1','sDrive18i',2017,'1.5',76000,'AT',78000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'White',140,'B38',7,'225/50 R18','225/50 R18',220,'SUV','Used','Entry X1.');

-- 47) BMW 7 Series (740Le)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'BMW','7 Series','740Le',2018,'2.0',58000,'AT',248000.00,'Hybrid','RWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Silver',326,'B48+EV',8,'245/50 R18','245/50 R18',500,'Sedan','Used','Lux PHEV.');

-- MERCEDES (48-66)
-- 48) Mercedes A-Class
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mercedes','A-Class','A200 Progressive',2019,'1.3',38000,'AT',168000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'White',163,'M282',7,'225/45 R17','225/45 R17',250,'Hatchback','Used','MBUX inside.');

-- 49) Mercedes B-Class
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mercedes','B-Class','B200',2018,'1.6',52000,'AT',115000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Silver',156,'M270',7,'225/45 R17','225/45 R17',250,'MPV','Used','Compact MPV.');

-- 50) Mercedes C-Class
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mercedes','C-Class','C200',2018,'2.0',60000,'AT',148000.00,'Gasoline','RWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'White',184,'M274',9,'225/50 R17','225/50 R17',300,'Sedan','Used','Luxury compact.');

-- 51) Mercedes E-Class
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mercedes','E-Class','E250',2017,'2.0',82000,'AT',165000.00,'Gasoline','RWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Black',211,'M274',9,'245/45 R18','245/45 R18',350,'Sedan','Used','Executive classic.');

-- 52) Mercedes S-Class
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mercedes','S-Class','S400h',2016,'3.5',98000,'AT',268000.00,'Hybrid','RWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Silver',306,'Hybrid',7,'245/45 R19','275/40 R19',370,'Sedan','Used','Flagship hybrid.');

-- 53) Mercedes CLA
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mercedes','CLA','CLA200',2019,'1.3',30000,'AT',185000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Red',163,'M282',7,'225/45 R18','225/45 R18',250,'Sedan','Used','Sleek coupe-like.');

-- 54) Mercedes GLA
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mercedes','GLA','GLA200',2018,'1.6',45000,'AT',138000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'White',156,'M270',7,'215/60 R17','215/60 R17',250,'SUV','Used','Compact crossover.');

-- 55) Mercedes GLC
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mercedes','GLC','GLC250',2018,'2.0',60000,'AT',195000.00,'Gasoline','RWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Grey',211,'M274',9,'235/60 R18','235/60 R18',350,'SUV','Used','Premium SUV.');

-- 56) Mercedes GLE
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mercedes','GLE','GLE450',2019,'3.0',42000,'AT',388000.00,'Hybrid','AWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Black',367,'M256+48V',9,'275/50 R20','275/50 R20',500,'SUV','Used','EQ-Boost mild hybrid.');

-- 57) Mercedes GLS
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mercedes','GLS','GLS450',2018,'3.0',65000,'AT',365000.00,'Gasoline','AWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'White',362,'V6',9,'275/50 R20','275/50 R20',500,'SUV','Used','7-seater luxury.');

-- 58) Mercedes Vito
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mercedes','Vito','Vito Tourer',2017,'2.1',98000,'AT',128000.00,'Diesel','RWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Silver',161,'OM651',7,'225/55 R17','225/55 R17',380,'Van','Used','People mover.');

-- 59) Mercedes Viano
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mercedes','Viano','3.0',2016,'3.0',120000,'AT',98000.00,'Diesel','RWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Black',224,'OM642',7,'225/55 R17','225/55 R17',440,'Van','Used','Executive van.');

-- 60) Mercedes C-Class (C300)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mercedes','C-Class','C300 AMG Line',2019,'2.0',38000,'AT',188000.00,'Gasoline','RWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Blue',258,'M264',9,'225/45 R18','225/45 R18',370,'Sedan','Used','AMG Line kit.');

-- 61) Mercedes E-Class (E200)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mercedes','E-Class','E200 Avantgarde',2019,'2.0',45000,'AT',198000.00,'Gasoline','RWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'White',197,'M264',9,'245/45 R18','245/45 R18',320,'Sedan','Used','Avantgarde trim.');

-- 62) Mercedes GLA (new)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mercedes','GLA','GLA250',2020,'2.0',28000,'AT',198000.00,'Gasoline','FWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Red',224,'M260',8,'235/50 R19','235/50 R19',350,'SUV','Used','GLA new gen.');

-- 63) Mercedes A-Class (sedan)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mercedes','A-Class','A250 Sedan AMG Line',2020,'2.0',22000,'AT',205000.00,'Gasoline','FWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Black',224,'M260',8,'225/45 R18','225/45 R18',350,'Sedan','Used','AMG Line sedan.');

-- 64) Mercedes GLC (4MATIC)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mercedes','GLC','GLC300 4MATIC',2020,'2.0',30000,'AT',268000.00,'Gasoline','AWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Grey',258,'M264',9,'235/55 R19','235/55 R19',370,'SUV','Used','4MATIC AWD.');

-- 65) Mercedes GLE (Coupe)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mercedes','GLE','GLE450 Coupe',2020,'3.0',26000,'AT',428000.00,'Hybrid','AWD',5,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'White',367,'M256+48V',9,'275/45 R21','275/45 R21',500,'SUV','Used','Sleek coupe roofline.');

-- 66) Mercedes S-Class (S560e)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES (@seller_id,'Mercedes','S-Class','S560e',2019,'3.0',38000,'AT',488000.00,'Hybrid','RWD',4,'sold');
SET @car_id := LAST_INSERT_ID();
INSERT INTO car_images (car_id,image_path,is_thumbnail) VALUES (@car_id,'uploads/ativa(1).webp',1);
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note) VALUES (@car_id,'Silver',476,'V6+EV',9,'245/45 R19','275/40 R19',700,'Sedan','Used','Lux PHEV flagship.');

COMMIT;
