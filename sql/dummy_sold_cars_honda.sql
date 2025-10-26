-- Dummy bulk data: 70 sold Honda cars
-- All rows use seller_id=4 and listing_status='sold'.
-- Models strictly from data/makes_models_my.json.
-- Images reuse 'uploads/ativa(1).webp' as a thumbnail placeholder.

START TRANSACTION;

SET @seller_id := 5;  -- Ensure this seller exists in your DB

-- HONDA (70)
-- Insert 70 Honda cars (7 per model across 10 models)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES
-- City (1-7)
(@seller_id,'Honda','City','1.5 i-VTEC',2016,'1.5',68000,'CVT',46000.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Honda','City','1.5 i-VTEC',2017,'1.5',52000,'CVT',48500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Honda','City','1.5 i-VTEC',2018,'1.5',49000,'CVT',50500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Honda','City','1.5 i-VTEC',2019,'1.5',41000,'CVT',54500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Honda','City','1.5 i-VTEC',2020,'1.5',30000,'CVT',58000.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Honda','City','RS 1.5 i-VTEC',2021,'1.5',22000,'CVT',63500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Honda','City','RS 1.5 e:HEV',2022,'1.5',18000,'CVT',74500.00,'Hybrid','FWD',4,'sold'),
-- Civic (8-14)
(@seller_id,'Honda','Civic','1.8 i-VTEC',2016,'1.8',72000,'AT',78000.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Honda','Civic','1.5 VTEC Turbo',2017,'1.5',65000,'AT',92500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Honda','Civic','1.5 VTEC Turbo',2018,'1.5',59000,'AT',98500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Honda','Civic','1.5 VTEC Turbo',2019,'1.5',48000,'AT',108000.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Honda','Civic','1.5 VTEC Turbo',2020,'1.5',33000,'AT',118000.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Honda','Civic','RS 1.5 VTEC Turbo',2021,'1.5',21000,'AT',132000.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Honda','Civic','e:HEV RS',2022,'2.0',15000,'AT',168000.00,'Hybrid','FWD',4,'sold'),
-- Accord (15-21)
(@seller_id,'Honda','Accord','2.0 i-VTEC',2015,'2.0',92000,'AT',72000.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Honda','Accord','2.4 i-VTEC',2016,'2.4',85000,'AT',79500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Honda','Accord','2.0 i-VTEC',2017,'2.0',78000,'AT',88500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Honda','Accord','1.5 VTEC Turbo',2018,'1.5',62000,'AT',115000.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Honda','Accord','1.5 VTEC Turbo',2019,'1.5',52000,'AT',128000.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Honda','Accord','1.5 VTEC Turbo',2020,'1.5',36000,'AT',145000.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Honda','Accord','e:HEV',2021,'2.0',24000,'AT',178000.00,'Hybrid','FWD',4,'sold'),
-- Jazz (22-28)
(@seller_id,'Honda','Jazz','1.5 i-VTEC',2015,'1.5',85000,'CVT',38000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','Jazz','1.5 i-VTEC',2016,'1.5',73000,'CVT',40500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','Jazz','1.5 i-VTEC',2017,'1.5',64000,'CVT',43500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','Jazz','1.5 i-VTEC',2018,'1.5',52000,'CVT',46500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','Jazz','1.5 i-VTEC',2019,'1.5',43000,'CVT',50500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','Jazz','1.5 i-VTEC',2020,'1.5',29000,'CVT',54500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','Jazz','Hybrid',2021,'1.5',21000,'CVT',62500.00,'Hybrid','FWD',5,'sold'),
-- HR-V (29-35)
(@seller_id,'Honda','HR-V','1.8 i-VTEC',2016,'1.8',78000,'CVT',73000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','HR-V','1.8 i-VTEC',2017,'1.8',69000,'CVT',78500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','HR-V','1.8 i-VTEC',2018,'1.8',62000,'CVT',84500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','HR-V','RS 1.8 i-VTEC',2019,'1.8',52000,'CVT',93500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','HR-V','1.5 VTEC Turbo',2020,'1.5',38000,'CVT',118000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','HR-V','e:HEV RS',2021,'1.5',26000,'CVT',138000.00,'Hybrid','FWD',5,'sold'),
(@seller_id,'Honda','HR-V','e:HEV',2022,'1.5',18000,'CVT',146000.00,'Hybrid','FWD',5,'sold'),
-- CR-V (36-42)
(@seller_id,'Honda','CR-V','2.0 i-VTEC',2016,'2.0',88000,'AT',88000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','CR-V','2.4 i-VTEC',2017,'2.4',82000,'AT',98000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','CR-V','1.5 VTEC Turbo',2018,'1.5',68000,'AT',125000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','CR-V','1.5 VTEC Turbo',2019,'1.5',56000,'AT',135000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','CR-V','1.5 VTEC Turbo',2020,'1.5',42000,'AT',145000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','CR-V','Black Edition',2021,'1.5',28000,'AT',165000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','CR-V','e:HEV',2022,'2.0',20000,'AT',198000.00,'Hybrid','FWD',5,'sold'),
-- BR-V (43-49)
(@seller_id,'Honda','BR-V','1.5 i-VTEC',2017,'1.5',82000,'CVT',58500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','BR-V','1.5 i-VTEC',2018,'1.5',75000,'CVT',61500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','BR-V','1.5 i-VTEC',2019,'1.5',61000,'CVT',65500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','BR-V','1.5 i-VTEC',2020,'1.5',50000,'CVT',69500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','BR-V','1.5 i-VTEC',2021,'1.5',36000,'CVT',73500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','BR-V','1.5 i-VTEC',2022,'1.5',24000,'CVT',78500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','BR-V','1.5 i-VTEC',2016,'1.5',88000,'CVT',56500.00,'Gasoline','FWD',5,'sold'),
-- Odyssey (50-56)
(@seller_id,'Honda','Odyssey','2.4 i-VTEC',2016,'2.4',90000,'AT',82000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','Odyssey','2.4 i-VTEC',2017,'2.4',82000,'AT',89500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','Odyssey','2.4 i-VTEC',2018,'2.4',76000,'AT',96500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','Odyssey','2.4 i-VTEC',2019,'2.4',64000,'AT',105000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','Odyssey','2.4 i-VTEC',2020,'2.4',52000,'AT',118000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','Odyssey','Absolute 2.4',2021,'2.4',38000,'AT',135000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','Odyssey','e:HEV',2022,'2.0',22000,'AT',168000.00,'Hybrid','FWD',5,'sold'),
-- Freed (57-63)
(@seller_id,'Honda','Freed','1.5 i-VTEC',2015,'1.5',96000,'CVT',52000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','Freed','1.5 i-VTEC',2016,'1.5',88000,'CVT',56000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','Freed','1.5 i-VTEC',2017,'1.5',80000,'CVT',59500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','Freed','1.5 i-VTEC',2018,'1.5',72000,'CVT',63500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','Freed','1.5 i-VTEC',2019,'1.5',62000,'CVT',67500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Honda','Freed','Hybrid',2020,'1.5',48000,'CVT',73500.00,'Hybrid','FWD',5,'sold'),
(@seller_id,'Honda','Freed','Hybrid',2021,'1.5',36000,'CVT',78500.00,'Hybrid','FWD',5,'sold'),
-- Prelude (64-70)
(@seller_id,'Honda','Prelude','2.2 VTEC',1998,'2.2',145000,'AT',52000.00,'Gasoline','FWD',2,'sold'),
(@seller_id,'Honda','Prelude','2.2 VTEC',1999,'2.2',138000,'AT',54000.00,'Gasoline','FWD',2,'sold'),
(@seller_id,'Honda','Prelude','2.2 VTEC',2000,'2.2',132000,'AT',56000.00,'Gasoline','FWD',2,'sold'),
(@seller_id,'Honda','Prelude','2.2 VTEC',2001,'2.2',128000,'AT',58500.00,'Gasoline','FWD',2,'sold'),
(@seller_id,'Honda','Prelude','2.2 VTEC',2001,'2.2',123000,'MT',60500.00,'Gasoline','FWD',2,'sold'),
(@seller_id,'Honda','Prelude','2.2 VTEC',2000,'2.2',136000,'MT',57500.00,'Gasoline','FWD',2,'sold'),
(@seller_id,'Honda','Prelude','2.2 VTEC',1999,'2.2',142000,'AT',53500.00,'Gasoline','FWD',2,'sold');

SET @base_id := LAST_INSERT_ID();

-- 70 thumbnails
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES
(@base_id+0,'uploads/ativa(1).webp',1),(@base_id+1,'uploads/ativa(1).webp',1),(@base_id+2,'uploads/ativa(1).webp',1),
(@base_id+3,'uploads/ativa(1).webp',1),(@base_id+4,'uploads/ativa(1).webp',1),(@base_id+5,'uploads/ativa(1).webp',1),
(@base_id+6,'uploads/ativa(1).webp',1),(@base_id+7,'uploads/ativa(1).webp',1),(@base_id+8,'uploads/ativa(1).webp',1),
(@base_id+9,'uploads/ativa(1).webp',1),(@base_id+10,'uploads/ativa(1).webp',1),(@base_id+11,'uploads/ativa(1).webp',1),
(@base_id+12,'uploads/ativa(1).webp',1),(@base_id+13,'uploads/ativa(1).webp',1),(@base_id+14,'uploads/ativa(1).webp',1),
(@base_id+15,'uploads/ativa(1).webp',1),(@base_id+16,'uploads/ativa(1).webp',1),(@base_id+17,'uploads/ativa(1).webp',1),
(@base_id+18,'uploads/ativa(1).webp',1),(@base_id+19,'uploads/ativa(1).webp',1),(@base_id+20,'uploads/ativa(1).webp',1),
(@base_id+21,'uploads/ativa(1).webp',1),(@base_id+22,'uploads/ativa(1).webp',1),(@base_id+23,'uploads/ativa(1).webp',1),
(@base_id+24,'uploads/ativa(1).webp',1),(@base_id+25,'uploads/ativa(1).webp',1),(@base_id+26,'uploads/ativa(1).webp',1),
(@base_id+27,'uploads/ativa(1).webp',1),(@base_id+28,'uploads/ativa(1).webp',1),(@base_id+29,'uploads/ativa(1).webp',1),
(@base_id+30,'uploads/ativa(1).webp',1),(@base_id+31,'uploads/ativa(1).webp',1),(@base_id+32,'uploads/ativa(1).webp',1),
(@base_id+33,'uploads/ativa(1).webp',1),(@base_id+34,'uploads/ativa(1).webp',1),(@base_id+35,'uploads/ativa(1).webp',1),
(@base_id+36,'uploads/ativa(1).webp',1),(@base_id+37,'uploads/ativa(1).webp',1),(@base_id+38,'uploads/ativa(1).webp',1),
(@base_id+39,'uploads/ativa(1).webp',1),(@base_id+40,'uploads/ativa(1).webp',1),(@base_id+41,'uploads/ativa(1).webp',1),
(@base_id+42,'uploads/ativa(1).webp',1),(@base_id+43,'uploads/ativa(1).webp',1),(@base_id+44,'uploads/ativa(1).webp',1),
(@base_id+45,'uploads/ativa(1).webp',1),(@base_id+46,'uploads/ativa(1).webp',1),(@base_id+47,'uploads/ativa(1).webp',1),
(@base_id+48,'uploads/ativa(1).webp',1),(@base_id+49,'uploads/ativa(1).webp',1),(@base_id+50,'uploads/ativa(1).webp',1),
(@base_id+51,'uploads/ativa(1).webp',1),(@base_id+52,'uploads/ativa(1).webp',1),(@base_id+53,'uploads/ativa(1).webp',1),
(@base_id+54,'uploads/ativa(1).webp',1),(@base_id+55,'uploads/ativa(1).webp',1),(@base_id+56,'uploads/ativa(1).webp',1),
(@base_id+57,'uploads/ativa(1).webp',1),(@base_id+58,'uploads/ativa(1).webp',1),(@base_id+59,'uploads/ativa(1).webp',1),
(@base_id+60,'uploads/ativa(1).webp',1),(@base_id+61,'uploads/ativa(1).webp',1),(@base_id+62,'uploads/ativa(1).webp',1),
(@base_id+63,'uploads/ativa(1).webp',1),(@base_id+64,'uploads/ativa(1).webp',1),(@base_id+65,'uploads/ativa(1).webp',1),
(@base_id+66,'uploads/ativa(1).webp',1),(@base_id+67,'uploads/ativa(1).webp',1),(@base_id+68,'uploads/ativa(1).webp',1),
(@base_id+69,'uploads/ativa(1).webp',1);

-- 70 details (keep plausible specs)
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note)
VALUES
-- City 1-7
(@base_id+0,'Silver',120,'L15B',7,'185/55 R16','185/55 R16',145,'Sedan','Used','Well kept City.'),
(@base_id+1,'Grey',120,'L15B',7,'185/55 R16','185/55 R16',145,'Sedan','Used','One owner.'),
(@base_id+2,'White',120,'L15B',7,'185/55 R16','185/55 R16',145,'Sedan','Used','Service on time.'),
(@base_id+3,'Blue',120,'L15B',7,'185/55 R16','185/55 R16',145,'Sedan','Used','Low mileage.'),
(@base_id+4,'Red',120,'L15B',7,'185/55 R16','185/55 R16',145,'Sedan','Used','Clean interior.'),
(@base_id+5,'Black',121,'L15B',7,'185/55 R16','185/55 R16',145,'Sedan','Used','RS trim.'),
(@base_id+6,'White',109,'i-MMD',7,'185/55 R16','185/55 R16',253,'Sedan','Used','Hybrid e:HEV.'),
-- Civic 8-14
(@base_id+7,'White',141,'R18Z',7,'215/50 R17','215/50 R17',174,'Sedan','Used','Reliable 1.8 NA.'),
(@base_id+8,'Grey',173,'L15B7',7,'215/50 R17','215/50 R17',220,'Sedan','Used','Turbo punch.'),
(@base_id+9,'Black',173,'L15B7',7,'215/50 R17','215/50 R17',220,'Sedan','Used','Well maintained.'),
(@base_id+10,'Blue',173,'L15B7',7,'215/50 R17','215/50 R17',220,'Sedan','Used','Facelift model.'),
(@base_id+11,'White',173,'L15B7',7,'215/50 R17','215/50 R17',220,'Sedan','Used','Low mileage.'),
(@base_id+12,'Red',182,'L15B7',7,'235/40 R18','235/40 R18',240,'Sedan','Used','RS package.'),
(@base_id+13,'Grey',184,'e:HEV',7,'235/40 R18','235/40 R18',315,'Sedan','Used','Hybrid flagship.'),
-- Accord 15-21
(@base_id+14,'Silver',155,'R20A',8,'225/50 R17','225/50 R17',190,'Sedan','Used','Comfort ride.'),
(@base_id+15,'Black',175,'K24W',8,'225/50 R17','225/50 R17',226,'Sedan','Used','Powerful 2.4.'),
(@base_id+16,'White',155,'R20A',8,'225/50 R17','225/50 R17',190,'Sedan','Used','Executive sedan.'),
(@base_id+17,'Grey',190,'L15B',8,'235/45 R18','235/45 R18',260,'Sedan','Used','Turbo smooth.'),
(@base_id+18,'Blue',190,'L15B',8,'235/45 R18','235/45 R18',260,'Sedan','Used','New gen.'),
(@base_id+19,'White',190,'L15B',8,'235/45 R18','235/45 R18',260,'Sedan','Used','Well kept.'),
(@base_id+20,'Black',215,'i-MMD',8,'235/45 R18','235/45 R18',315,'Sedan','Used','Hybrid top spec.'),
-- Jazz 22-28
(@base_id+21,'Yellow',118,'L15B',7,'185/55 R16','185/55 R16',145,'Hatchback','Used','Zippy hatch.'),
(@base_id+22,'Red',118,'L15B',7,'185/55 R16','185/55 R16',145,'Hatchback','Used','Compact city car.'),
(@base_id+23,'Blue',118,'L15B',7,'185/55 R16','185/55 R16',145,'Hatchback','Used','Easy to park.'),
(@base_id+24,'White',118,'L15B',7,'185/55 R16','185/55 R16',145,'Hatchback','Used','Service history.'),
(@base_id+25,'Grey',118,'L15B',7,'185/55 R16','185/55 R16',145,'Hatchback','Used','Daily driver.'),
(@base_id+26,'Silver',118,'L15B',7,'185/55 R16','185/55 R16',145,'Hatchback','Used','One owner.'),
(@base_id+27,'White',109,'i-MMD',7,'185/55 R16','185/55 R16',253,'Hatchback','Used','Hybrid unit.'),
-- HR-V 29-35
(@base_id+28,'White',142,'R18Z',7,'215/55 R17','215/55 R17',172,'SUV','Used','Practical SUV.'),
(@base_id+29,'Grey',142,'R18Z',7,'215/55 R17','215/55 R17',172,'SUV','Used','Clean interior.'),
(@base_id+30,'Black',142,'R18Z',7,'215/55 R17','215/55 R17',172,'SUV','Used','Well kept.'),
(@base_id+31,'Blue',142,'R18Z',7,'215/55 R17','215/55 R17',172,'SUV','Used','RS trim.'),
(@base_id+32,'White',177,'L15B7',7,'225/50 R18','225/50 R18',240,'SUV','Used','Turbo variant.'),
(@base_id+33,'Red',131,'i-MMD',7,'225/50 R18','225/50 R18',253,'SUV','Used','e:HEV RS.'),
(@base_id+34,'Silver',131,'i-MMD',7,'225/50 R18','225/50 R18',253,'SUV','Used','Efficient hybrid.'),
-- CR-V 36-42
(@base_id+35,'Silver',155,'R20A',8,'225/65 R17','225/65 R17',190,'SUV','Used','Spacious.'),
(@base_id+36,'White',190,'K24',8,'225/65 R17','225/65 R17',240,'SUV','Used','Strong 2.4L.'),
(@base_id+37,'Grey',190,'L15B7',8,'235/60 R18','235/60 R18',243,'SUV','Used','Turbo comfort.'),
(@base_id+38,'Blue',190,'L15B7',8,'235/60 R18','235/60 R18',243,'SUV','Used','Well maintained.'),
(@base_id+39,'White',190,'L15B7',8,'235/60 R18','235/60 R18',243,'SUV','Used','Low mileage.'),
(@base_id+40,'Black',190,'L15B7',8,'235/60 R18','235/60 R18',243,'SUV','Used','Black Edition.'),
(@base_id+41,'Grey',184,'i-MMD',8,'235/60 R18','235/60 R18',315,'SUV','Used','Hybrid new gen.'),
-- BR-V 43-49
(@base_id+42,'White',120,'L15Z',7,'195/60 R16','195/60 R16',145,'SUV','Used','7-seater.'),
(@base_id+43,'Silver',120,'L15Z',7,'195/60 R16','195/60 R16',145,'SUV','Used','Family car.'),
(@base_id+44,'Grey',120,'L15Z',7,'195/60 R16','195/60 R16',145,'SUV','Used','Practical.'),
(@base_id+45,'Black',120,'L15Z',7,'195/60 R16','195/60 R16',145,'SUV','Used','Reliable.'),
(@base_id+46,'Blue',120,'L15Z',7,'195/60 R16','195/60 R16',145,'SUV','Used','Clean unit.'),
(@base_id+47,'White',120,'L15Z',7,'195/60 R16','195/60 R16',145,'SUV','Used','Low mileage.'),
(@base_id+48,'Red',120,'L15Z',7,'195/60 R16','195/60 R16',145,'SUV','Used','Good condition.'),
-- Odyssey 50-56
(@base_id+49,'Grey',175,'K24W',8,'225/55 R17','225/55 R17',226,'MPV','Used','Comfortable MPV.'),
(@base_id+50,'White',175,'K24W',8,'225/55 R17','225/55 R17',226,'MPV','Used','Power door.'),
(@base_id+51,'Black',175,'K24W',8,'225/55 R17','225/55 R17',226,'MPV','Used','7-seater.'),
(@base_id+52,'Silver',175,'K24W',8,'225/55 R17','225/55 R17',226,'MPV','Used','Good family car.'),
(@base_id+53,'Blue',175,'K24W',8,'225/55 R17','225/55 R17',226,'MPV','Used','Clean interior.'),
(@base_id+54,'White',180,'K24W',8,'225/55 R17','225/55 R17',226,'MPV','Used','Absolute trim.'),
(@base_id+55,'Grey',181,'i-MMD',8,'225/55 R17','225/55 R17',315,'MPV','Used','Hybrid variant.'),
-- Freed 57-63
(@base_id+56,'Silver',117,'L15B',7,'185/65 R15','185/65 R15',146,'MPV','Used','Compact MPV.'),
(@base_id+57,'White',117,'L15B',7,'185/65 R15','185/65 R15',146,'MPV','Used','Well maintained.'),
(@base_id+58,'Grey',117,'L15B',7,'185/65 R15','185/65 R15',146,'MPV','Used','One owner.'),
(@base_id+59,'Black',117,'L15B',7,'185/65 R15','185/65 R15',146,'MPV','Used','View to believe.'),
(@base_id+60,'Blue',117,'L15B',7,'185/65 R15','185/65 R15',146,'MPV','Used','Accident free.'),
(@base_id+61,'White',109,'i-MMD',7,'185/65 R15','185/65 R15',253,'MPV','Used','Hybrid.'),
(@base_id+62,'Grey',109,'i-MMD',7,'185/65 R15','185/65 R15',253,'MPV','Used','Fuel saver.'),
-- Prelude 64-70
(@base_id+63,'Red',197,'H22A',5,'205/50 R16','205/50 R16',212,'Coupe','Used','Classic icon.'),
(@base_id+64,'Black',197,'H22A',5,'205/50 R16','205/50 R16',212,'Coupe','Used','Rare unit.'),
(@base_id+65,'White',197,'H22A',5,'205/50 R16','205/50 R16',212,'Coupe','Used','Collector car.'),
(@base_id+66,'Blue',197,'H22A',5,'205/50 R16','205/50 R16',212,'Coupe','Used','Well preserved.'),
(@base_id+67,'Yellow',197,'H22A',5,'205/50 R16','205/50 R16',212,'Coupe','Used','Manual fun.'),
(@base_id+68,'Grey',197,'H22A',5,'205/50 R16','205/50 R16',212,'Coupe','Used','Weekend drive.'),
(@base_id+69,'Silver',197,'H22A',5,'205/50 R16','205/50 R16',212,'Coupe','Used','Enthusiast owned.');

COMMIT;
