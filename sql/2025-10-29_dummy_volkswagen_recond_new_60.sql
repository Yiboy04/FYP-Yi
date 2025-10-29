-- Dummy bulk data: 60 Volkswagen cars (Reconditioned/New)
-- Models from data/makes_models_my.json (Volkswagen list). Note: spellings per JSON (e.g., Sirocco).
-- All rows use seller_id=5 and listing_status='sold'.
-- Images reuse 'uploads/ativa(1).webp' as a thumbnail placeholder.

START TRANSACTION;

SET @seller_id := 5;  -- consistent seller

-- VOLKSWAGEN (60)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES
-- Golf (0-11) x12
(@seller_id,'Volkswagen','Golf','1.4 TSI',2015,'1.4',78000,'DCT',54500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Volkswagen','Golf','1.4 TSI',2016,'1.4',69000,'DCT',58500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Volkswagen','Golf','1.4 TSI',2017,'1.4',62000,'DCT',62500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Volkswagen','Golf','1.4 TSI R-Line',2018,'1.4',52000,'DCT',70500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Volkswagen','Golf','GTI 2.0',2017,'2.0',54000,'DCT',98500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Volkswagen','Golf','GTI 2.0',2019,'2.0',38000,'DCT',118500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Volkswagen','Golf','R 2.0',2016,'2.0',58000,'DCT',128500.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Volkswagen','Golf','R 2.0',2018,'2.0',42000,'DCT',148500.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Volkswagen','Golf','1.4 TSI',2020,'1.4',28000,'DCT',88500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Volkswagen','Golf','1.4 TSI',2021,'1.4',19000,'DCT',96500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Volkswagen','Golf','GTI 2.0',2022,'2.0',14000,'DCT',168500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Volkswagen','Golf','R 2.0',2023,'2.0',9000,'DCT',198500.00,'Gasoline','AWD',5,'sold'),
-- Passat (12-19) x8
(@seller_id,'Volkswagen','Passat','1.8 TSI',2016,'1.8',78000,'DCT',65500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Passat','2.0 TSI',2017,'2.0',69000,'DCT',75500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Passat','2.0 TSI R-Line',2018,'2.0',59000,'DCT',89500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Passat','2.0 TSI Elegance',2019,'2.0',52000,'DCT',102500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Passat','2.0 TSI Elegance',2020,'2.0',42000,'DCT',115500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Passat','2.0 TSI R-Line',2021,'2.0',32000,'DCT',126500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Passat','2.0 TSI Elegance',2022,'2.0',21000,'DCT',138500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Passat','2.0 TSI R-Line',2023,'2.0',12000,'DCT',148500.00,'Gasoline','FWD',4,'sold'),
-- Polo (20-25) x6
(@seller_id,'Volkswagen','Polo','1.6',2015,'1.6',85000,'AT',35500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Polo','1.6',2016,'1.6',78000,'AT',38500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Polo','1.6',2017,'1.6',72000,'AT',41500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Polo','1.6',2018,'1.6',65000,'AT',45500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Polo','1.6',2019,'1.6',58000,'AT',49500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Polo','1.6',2020,'1.6',42000,'AT',54500.00,'Gasoline','FWD',4,'sold'),
-- Jetta (26-31) x6
(@seller_id,'Volkswagen','Jetta','1.4 TSI',2015,'1.4',82000,'DCT',46500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Jetta','1.4 TSI',2016,'1.4',76000,'DCT',49500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Jetta','1.4 TSI',2017,'1.4',69000,'DCT',54500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Jetta','1.4 TSI Highline',2018,'1.4',62000,'DCT',59500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Jetta','1.4 TSI Highline',2019,'1.4',54000,'DCT',64500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Jetta','1.4 TSI',2020,'1.4',46000,'DCT',69500.00,'Gasoline','FWD',4,'sold'),
-- Vento (32-37) x6
(@seller_id,'Volkswagen','Vento','1.6',2015,'1.6',78000,'AT',34500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Vento','1.6',2016,'1.6',71000,'AT',36500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Vento','1.6',2017,'1.6',65000,'AT',38500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Vento','1.6',2018,'1.6',59000,'AT',41500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Vento','1.6',2019,'1.6',52000,'AT',44500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Vento','1.6',2020,'1.6',45000,'AT',47500.00,'Gasoline','FWD',4,'sold'),
-- Tiguan (38-45) x8
(@seller_id,'Volkswagen','Tiguan','1.4 TSI',2017,'1.4',68000,'DCT',89500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Volkswagen','Tiguan','1.4 TSI',2018,'1.4',59000,'DCT',96500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Volkswagen','Tiguan','1.4 TSI Highline',2019,'1.4',52000,'DCT',108500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Volkswagen','Tiguan','R-Line 2.0',2019,'2.0',48000,'DCT',138500.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Volkswagen','Tiguan','1.4 TSI',2020,'1.4',42000,'DCT',118500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Volkswagen','Tiguan','R-Line 2.0',2020,'2.0',38000,'DCT',148500.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Volkswagen','Tiguan','R-Line 2.0',2021,'2.0',29000,'DCT',158500.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Volkswagen','Tiguan','1.4 TSI',2022,'1.4',21000,'DCT',128500.00,'Gasoline','FWD',5,'sold'),
-- Touareg (46-47) x2
(@seller_id,'Volkswagen','Touareg','3.0 TDI',2016,'3.0',82000,'AT',125000.00,'Diesel','AWD',5,'sold'),
(@seller_id,'Volkswagen','Touareg','3.0 V6',2019,'3.0',52000,'AT',225000.00,'Gasoline','AWD',5,'sold'),
-- Arteon (48-51) x4
(@seller_id,'Volkswagen','Arteon','2.0 TSI R-Line',2019,'2.0',48000,'DCT',158500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Arteon','2.0 TSI R-Line',2020,'2.0',36000,'DCT',178500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Arteon','2.0 TSI R-Line',2021,'2.0',24000,'DCT',198500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Volkswagen','Arteon','2.0 TSI R-Line',2022,'2.0',16000,'DCT',218500.00,'Gasoline','FWD',4,'sold'),
-- Beetle (52-55) x4
(@seller_id,'Volkswagen','Beetle','1.2 TSI',2014,'1.2',82000,'DCT',65500.00,'Gasoline','FWD',2,'sold'),
(@seller_id,'Volkswagen','Beetle','1.2 TSI',2015,'1.2',76000,'DCT',70500.00,'Gasoline','FWD',2,'sold'),
(@seller_id,'Volkswagen','Beetle','1.4 TSI',2016,'1.4',68000,'DCT',75500.00,'Gasoline','FWD',2,'sold'),
(@seller_id,'Volkswagen','Beetle','1.4 TSI',2017,'1.4',62000,'DCT',80500.00,'Gasoline','FWD',2,'sold'),
-- Sirocco (56-59) x4
(@seller_id,'Volkswagen','Sirocco','1.4 TSI',2014,'1.4',82000,'DCT',59500.00,'Gasoline','FWD',2,'sold'),
(@seller_id,'Volkswagen','Sirocco','1.4 TSI',2015,'1.4',76000,'DCT',64500.00,'Gasoline','FWD',2,'sold'),
(@seller_id,'Volkswagen','Sirocco','2.0 TSI',2016,'2.0',68000,'DCT',79500.00,'Gasoline','FWD',2,'sold'),
(@seller_id,'Volkswagen','Sirocco','2.0 TSI',2017,'2.0',62000,'DCT',89500.00,'Gasoline','FWD',2,'sold');

SET @base_vw := LAST_INSERT_ID();

-- 60 thumbnails
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES
(@base_vw+0,'uploads/ativa(1).webp',1),(@base_vw+1,'uploads/ativa(1).webp',1),(@base_vw+2,'uploads/ativa(1).webp',1),(@base_vw+3,'uploads/ativa(1).webp',1),
(@base_vw+4,'uploads/ativa(1).webp',1),(@base_vw+5,'uploads/ativa(1).webp',1),(@base_vw+6,'uploads/ativa(1).webp',1),(@base_vw+7,'uploads/ativa(1).webp',1),
(@base_vw+8,'uploads/ativa(1).webp',1),(@base_vw+9,'uploads/ativa(1).webp',1),(@base_vw+10,'uploads/ativa(1).webp',1),(@base_vw+11,'uploads/ativa(1).webp',1),
(@base_vw+12,'uploads/ativa(1).webp',1),(@base_vw+13,'uploads/ativa(1).webp',1),(@base_vw+14,'uploads/ativa(1).webp',1),(@base_vw+15,'uploads/ativa(1).webp',1),
(@base_vw+16,'uploads/ativa(1).webp',1),(@base_vw+17,'uploads/ativa(1).webp',1),(@base_vw+18,'uploads/ativa(1).webp',1),(@base_vw+19,'uploads/ativa(1).webp',1),
(@base_vw+20,'uploads/ativa(1).webp',1),(@base_vw+21,'uploads/ativa(1).webp',1),(@base_vw+22,'uploads/ativa(1).webp',1),(@base_vw+23,'uploads/ativa(1).webp',1),
(@base_vw+24,'uploads/ativa(1).webp',1),(@base_vw+25,'uploads/ativa(1).webp',1),(@base_vw+26,'uploads/ativa(1).webp',1),(@base_vw+27,'uploads/ativa(1).webp',1),
(@base_vw+28,'uploads/ativa(1).webp',1),(@base_vw+29,'uploads/ativa(1).webp',1),(@base_vw+30,'uploads/ativa(1).webp',1),(@base_vw+31,'uploads/ativa(1).webp',1),
(@base_vw+32,'uploads/ativa(1).webp',1),(@base_vw+33,'uploads/ativa(1).webp',1),(@base_vw+34,'uploads/ativa(1).webp',1),(@base_vw+35,'uploads/ativa(1).webp',1),
(@base_vw+36,'uploads/ativa(1).webp',1),(@base_vw+37,'uploads/ativa(1).webp',1),(@base_vw+38,'uploads/ativa(1).webp',1),(@base_vw+39,'uploads/ativa(1).webp',1),
(@base_vw+40,'uploads/ativa(1).webp',1),(@base_vw+41,'uploads/ativa(1).webp',1),(@base_vw+42,'uploads/ativa(1).webp',1),(@base_vw+43,'uploads/ativa(1).webp',1),
(@base_vw+44,'uploads/ativa(1).webp',1),(@base_vw+45,'uploads/ativa(1).webp',1),(@base_vw+46,'uploads/ativa(1).webp',1),(@base_vw+47,'uploads/ativa(1).webp',1),
(@base_vw+48,'uploads/ativa(1).webp',1),(@base_vw+49,'uploads/ativa(1).webp',1),(@base_vw+50,'uploads/ativa(1).webp',1),(@base_vw+51,'uploads/ativa(1).webp',1),
(@base_vw+52,'uploads/ativa(1).webp',1),(@base_vw+53,'uploads/ativa(1).webp',1),(@base_vw+54,'uploads/ativa(1).webp',1),(@base_vw+55,'uploads/ativa(1).webp',1),
(@base_vw+56,'uploads/ativa(1).webp',1),(@base_vw+57,'uploads/ativa(1).webp',1),(@base_vw+58,'uploads/ativa(1).webp',1),(@base_vw+59,'uploads/ativa(1).webp',1);

-- 60 details with car_condition = 'Reconditioned' or 'New'
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note)
VALUES
-- Golf (0-11)
(@base_vw+0,'White',140,'EA211',7,'205/55 R16','205/55 R16',250,'Hatchback','Reconditioned','Recond 1.4 TSI.'),
(@base_vw+1,'Silver',140,'EA211',7,'205/55 R16','205/55 R16',250,'Hatchback','Reconditioned','Well maintained.'),
(@base_vw+2,'Blue',140,'EA211',7,'205/55 R16','205/55 R16',250,'Hatchback','Reconditioned','Low mileage.'),
(@base_vw+3,'Grey',150,'EA211',7,'225/45 R17','225/45 R17',250,'Hatchback','Reconditioned','R-Line styling.'),
(@base_vw+4,'Red',220,'EA888',7,'225/40 R18','225/40 R18',350,'Hatchback','Reconditioned','GTI recond.'),
(@base_vw+5,'White',245,'EA888',7,'225/40 R18','225/40 R18',370,'Hatchback','Reconditioned','GTI facelift recond.'),
(@base_vw+6,'Blue',300,'EA888',7,'235/35 R19','235/35 R19',380,'Hatchback','Reconditioned','Golf R AWD recond.'),
(@base_vw+7,'Grey',310,'EA888',7,'235/35 R19','235/35 R19',400,'Hatchback','Reconditioned','Golf R facelift recond.'),
(@base_vw+8,'White',150,'EA211',7,'205/55 R16','205/55 R16',250,'Hatchback','New','Brand new MY unit.'),
(@base_vw+9,'Silver',150,'EA211',7,'205/55 R16','205/55 R16',250,'Hatchback','New','Unregistered new.'),
(@base_vw+10,'Red',245,'EA888',7,'235/35 R19','235/35 R19',370,'Hatchback','New','GTI new.'),
(@base_vw+11,'Blue',320,'EA888',7,'235/35 R19','235/35 R19',420,'Hatchback','New','Golf R new.'),
-- Passat (12-19)
(@base_vw+12,'White',180,'EA888',7,'215/55 R17','215/55 R17',250,'Sedan','Reconditioned','1.8 TSI recond.'),
(@base_vw+13,'Grey',220,'EA888',7,'215/55 R17','215/55 R17',350,'Sedan','Reconditioned','2.0 TSI recond.'),
(@base_vw+14,'Blue',220,'EA888',7,'235/45 R18','235/45 R18',350,'Sedan','Reconditioned','R-Line recond.'),
(@base_vw+15,'Black',220,'EA888',7,'235/45 R18','235/45 R18',350,'Sedan','Reconditioned','Elegance recond.'),
(@base_vw+16,'White',220,'EA888',7,'235/45 R18','235/45 R18',350,'Sedan','Reconditioned','Minor facelift.'),
(@base_vw+17,'Silver',220,'EA888',7,'235/45 R18','235/45 R18',350,'Sedan','New','Brand new MY unit.'),
(@base_vw+18,'Grey',220,'EA888',7,'235/45 R18','235/45 R18',350,'Sedan','New','Unregistered new.'),
(@base_vw+19,'Black',220,'EA888',7,'235/45 R18','235/45 R18',350,'Sedan','New','Unregistered new.'),
-- Polo (20-25)
(@base_vw+20,'Red',105,'EA111',6,'185/60 R15','185/60 R15',153,'Sedan','Reconditioned','Polo sedan recond.'),
(@base_vw+21,'White',105,'EA111',6,'185/60 R15','185/60 R15',153,'Sedan','Reconditioned','Well kept.'),
(@base_vw+22,'Silver',105,'EA111',6,'185/60 R15','185/60 R15',153,'Sedan','Reconditioned','Clean unit.'),
(@base_vw+23,'Blue',105,'EA111',6,'185/60 R15','185/60 R15',153,'Sedan','Reconditioned','Service on time.'),
(@base_vw+24,'Grey',105,'EA111',6,'185/60 R15','185/60 R15',153,'Sedan','Reconditioned','Low mileage.'),
(@base_vw+25,'White',105,'EA111',6,'185/60 R15','185/60 R15',153,'Sedan','New','Brand new MY unit.'),
-- Jetta (26-31)
(@base_vw+26,'White',150,'EA211',7,'205/55 R16','205/55 R16',250,'Sedan','Reconditioned','1.4 TSI recond.'),
(@base_vw+27,'Grey',150,'EA211',7,'205/55 R16','205/55 R16',250,'Sedan','Reconditioned','Well maintained.'),
(@base_vw+28,'Blue',150,'EA211',7,'205/55 R16','205/55 R16',250,'Sedan','Reconditioned','Highline recond.'),
(@base_vw+29,'Black',150,'EA211',7,'205/55 R16','205/55 R16',250,'Sedan','Reconditioned','Clean interior.'),
(@base_vw+30,'White',150,'EA211',7,'205/55 R16','205/55 R16',250,'Sedan','Reconditioned','Low mileage.'),
(@base_vw+31,'Silver',150,'EA211',7,'205/55 R16','205/55 R16',250,'Sedan','New','Unregistered new.'),
-- Vento (32-37)
(@base_vw+32,'White',105,'EA111',6,'185/60 R15','185/60 R15',153,'Sedan','Reconditioned','Vento recond.'),
(@base_vw+33,'Silver',105,'EA111',6,'185/60 R15','185/60 R15',153,'Sedan','Reconditioned','Clean unit.'),
(@base_vw+34,'Blue',105,'EA111',6,'185/60 R15','185/60 R15',153,'Sedan','Reconditioned','Service on time.'),
(@base_vw+35,'Grey',105,'EA111',6,'185/60 R15','185/60 R15',153,'Sedan','Reconditioned','Well kept.'),
(@base_vw+36,'White',105,'EA111',6,'185/60 R15','185/60 R15',153,'Sedan','Reconditioned','Low mileage.'),
(@base_vw+37,'Black',105,'EA111',6,'185/60 R15','185/60 R15',153,'Sedan','New','Brand new MY unit.'),
-- Tiguan (38-45)
(@base_vw+38,'White',150,'EA211',7,'215/65 R17','215/65 R17',250,'SUV','Reconditioned','1.4 TSI recond.'),
(@base_vw+39,'Silver',150,'EA211',7,'215/65 R17','215/65 R17',250,'SUV','Reconditioned','Well kept.'),
(@base_vw+40,'Blue',150,'EA211',7,'235/55 R18','235/55 R18',250,'SUV','Reconditioned','Highline recond.'),
(@base_vw+41,'Grey',220,'EA888',7,'235/50 R19','235/50 R19',350,'SUV','Reconditioned','R-Line AWD recond.'),
(@base_vw+42,'White',150,'EA211',7,'235/55 R18','235/55 R18',250,'SUV','Reconditioned','Minor facelift.'),
(@base_vw+43,'Black',220,'EA888',7,'235/50 R19','235/50 R19',350,'SUV','Reconditioned','R-Line facelift.'),
(@base_vw+44,'Grey',220,'EA888',7,'235/50 R19','235/50 R19',350,'SUV','New','Unregistered new.'),
(@base_vw+45,'White',150,'EA211',7,'235/55 R18','235/55 R18',250,'SUV','New','Brand new MY unit.'),
-- Touareg (46-47)
(@base_vw+46,'White',245,'V6 TDI',8,'255/50 R19','255/50 R19',550,'SUV','Reconditioned','3.0 TDI recond.'),
(@base_vw+47,'Grey',340,'V6 TSI',8,'285/45 R20','285/45 R20',450,'SUV','Reconditioned','3.0 V6 recond.'),
-- Arteon (48-51)
(@base_vw+48,'White',280,'EA888',7,'245/45 R18','245/45 R18',350,'Sedan','Reconditioned','R-Line recond.'),
(@base_vw+49,'Grey',280,'EA888',7,'245/45 R18','245/45 R18',350,'Sedan','Reconditioned','Facelift recond.'),
(@base_vw+50,'Blue',280,'EA888',7,'245/45 R18','245/45 R18',350,'Sedan','New','Brand new MY unit.'),
(@base_vw+51,'Black',280,'EA888',7,'245/45 R18','245/45 R18',350,'Sedan','New','Unregistered new.'),
-- Beetle (52-55)
(@base_vw+52,'Yellow',105,'EA111',7,'215/55 R16','215/55 R16',175,'Coupe','Reconditioned','Beetle 1.2 TSI recond.'),
(@base_vw+53,'White',105,'EA111',7,'215/55 R16','215/55 R16',175,'Coupe','Reconditioned','Well kept.'),
(@base_vw+54,'Blue',150,'EA211',7,'225/45 R17','225/45 R17',250,'Coupe','Reconditioned','1.4 TSI recond.'),
(@base_vw+55,'Red',150,'EA211',7,'225/45 R17','225/45 R17',250,'Coupe','New','Unregistered new.'),
-- Sirocco (56-59)
(@base_vw+56,'White',160,'EA211',7,'225/45 R17','225/45 R17',250,'Coupe','Reconditioned','1.4 TSI recond.'),
(@base_vw+57,'Grey',160,'EA211',7,'225/45 R17','225/45 R17',250,'Coupe','Reconditioned','Well maintained.'),
(@base_vw+58,'Blue',210,'EA888',7,'235/40 R18','235/40 R18',280,'Coupe','Reconditioned','2.0 TSI recond.'),
(@base_vw+59,'Black',210,'EA888',7,'235/40 R18','235/40 R18',280,'Coupe','New','Unregistered new.');

COMMIT;
