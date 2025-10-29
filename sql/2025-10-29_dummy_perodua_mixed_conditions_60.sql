-- Dummy bulk data: 60 Perodua cars (Mixed conditions: Reconditioned, Used, New)
-- Models from data/makes_models_my.json
-- All rows use seller_id=6 and listing_status='sold'.
-- Thumbnails reuse 'uploads/ativa(1).webp'.

START TRANSACTION;

SET @seller_id := 6;

-- PERODUA (60)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES
-- Myvi (0-19) x20
(@seller_id,'Perodua','Myvi','1.3 G',2017,'1.3',86000,'AT',28500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Myvi','1.5 H',2018,'1.5',73000,'AT',34500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Myvi','1.5 AV',2019,'1.5',52000,'AT',41500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Myvi','1.5 AV',2020,'1.5',38000,'AT',47500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Myvi','1.3 G',2021,'1.3',29000,'AT',42500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Myvi','1.5 H',2022,'1.5',18000,'AT',49500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Myvi','1.5 AV',2023,'1.5',12000,'AT',54500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Myvi','1.3 G',2016,'1.3',92000,'AT',25500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Myvi','1.5 H',2017,'1.5',84000,'AT',30500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Myvi','1.5 AV',2018,'1.5',64000,'AT',36500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Myvi','1.5 AV',2019,'1.5',54000,'AT',40500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Myvi','1.3 G',2020,'1.3',40000,'AT',35500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Myvi','1.5 H',2021,'1.5',30000,'AT',42500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Myvi','1.5 AV',2022,'1.5',19000,'AT',49500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Myvi','1.5 AV',2023,'1.5',13000,'AT',53500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Myvi','1.3 G',2015,'1.3',98000,'AT',23500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Myvi','1.5 H',2016,'1.5',90000,'AT',28500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Myvi','1.5 AV',2017,'1.5',78000,'AT',32500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Myvi','1.5 AV',2018,'1.5',66000,'AT',35500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Myvi','1.3 G',2019,'1.3',56000,'AT',31500.00,'Petrol','FWD',4,'sold'),
-- Axia (20-39) x20
(@seller_id,'Perodua','Axia','1.0 G',2017,'1.0',89000,'AT',21500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Axia','1.0 SE',2018,'1.0',76000,'AT',23500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Axia','1.0 AV',2019,'1.0',54000,'AT',26500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Axia','1.0 AV',2020,'1.0',41000,'AT',29500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Axia','1.0 G',2021,'1.0',32000,'AT',31500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Axia','1.0 SE',2022,'1.0',21000,'AT',34500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Axia','1.0 AV',2023,'1.0',14000,'AT',38500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Axia','1.0 G',2016,'1.0',95000,'AT',19500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Axia','1.0 SE',2017,'1.0',87000,'AT',21500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Axia','1.0 AV',2018,'1.0',66000,'AT',23500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Axia','1.0 AV',2019,'1.0',56000,'AT',25500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Axia','1.0 G',2020,'1.0',42000,'AT',27500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Axia','1.0 SE',2021,'1.0',33000,'AT',29500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Axia','1.0 AV',2022,'1.0',22000,'AT',31500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Axia','1.0 AV',2023,'1.0',15000,'AT',34500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Axia','1.0 G',2015,'1.0',99000,'AT',17500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Axia','1.0 SE',2016,'1.0',91000,'AT',19500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Axia','1.0 AV',2017,'1.0',78000,'AT',21500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Axia','1.0 AV',2018,'1.0',67000,'AT',23500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Axia','1.0 G',2019,'1.0',57000,'AT',24500.00,'Petrol','FWD',4,'sold'),
-- Bezza (40-59) x20
(@seller_id,'Perodua','Bezza','1.3 Premium X',2017,'1.3',83000,'AT',25500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Bezza','1.3 Advance',2018,'1.3',71000,'AT',28500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Bezza','1.3 AV',2019,'1.3',55000,'AT',31500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Bezza','1.3 AV',2020,'1.3',42000,'AT',34500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Bezza','1.3 Premium X',2021,'1.3',31000,'AT',36500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Bezza','1.3 Advance',2022,'1.3',20000,'AT',38500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Bezza','1.3 AV',2023,'1.3',13000,'AT',41500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Bezza','1.3 Premium X',2016,'1.3',91000,'AT',23500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Bezza','1.3 Advance',2017,'1.3',84000,'AT',25500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Bezza','1.3 AV',2018,'1.3',66000,'AT',27500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Bezza','1.3 AV',2019,'1.3',56000,'AT',29500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Bezza','1.3 Premium X',2020,'1.3',43000,'AT',30500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Bezza','1.3 Advance',2021,'1.3',32000,'AT',32500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Bezza','1.3 AV',2022,'1.3',21000,'AT',33500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Bezza','1.3 AV',2023,'1.3',14000,'AT',36500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Bezza','1.3 Premium X',2015,'1.3',97000,'AT',20500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Bezza','1.3 Advance',2016,'1.3',90000,'AT',22500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Bezza','1.3 AV',2017,'1.3',78000,'AT',24500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Bezza','1.3 AV',2018,'1.3',66000,'AT',26500.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Perodua','Bezza','1.3 Premium X',2019,'1.3',56000,'AT',27500.00,'Petrol','FWD',4,'sold');

SET @base_perodua := LAST_INSERT_ID();

-- 60 thumbnails
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES
(@base_perodua+0,'uploads/ativa(1).webp',1),(@base_perodua+1,'uploads/ativa(1).webp',1),(@base_perodua+2,'uploads/ativa(1).webp',1),(@base_perodua+3,'uploads/ativa(1).webp',1),
(@base_perodua+4,'uploads/ativa(1).webp',1),(@base_perodua+5,'uploads/ativa(1).webp',1),(@base_perodua+6,'uploads/ativa(1).webp',1),(@base_perodua+7,'uploads/ativa(1).webp',1),
(@base_perodua+8,'uploads/ativa(1).webp',1),(@base_perodua+9,'uploads/ativa(1).webp',1),(@base_perodua+10,'uploads/ativa(1).webp',1),(@base_perodua+11,'uploads/ativa(1).webp',1),
(@base_perodua+12,'uploads/ativa(1).webp',1),(@base_perodua+13,'uploads/ativa(1).webp',1),(@base_perodua+14,'uploads/ativa(1).webp',1),(@base_perodua+15,'uploads/ativa(1).webp',1),
(@base_perodua+16,'uploads/ativa(1).webp',1),(@base_perodua+17,'uploads/ativa(1).webp',1),(@base_perodua+18,'uploads/ativa(1).webp',1),(@base_perodua+19,'uploads/ativa(1).webp',1),
(@base_perodua+20,'uploads/ativa(1).webp',1),(@base_perodua+21,'uploads/ativa(1).webp',1),(@base_perodua+22,'uploads/ativa(1).webp',1),(@base_perodua+23,'uploads/ativa(1).webp',1),
(@base_perodua+24,'uploads/ativa(1).webp',1),(@base_perodua+25,'uploads/ativa(1).webp',1),(@base_perodua+26,'uploads/ativa(1).webp',1),(@base_perodua+27,'uploads/ativa(1).webp',1),
(@base_perodua+28,'uploads/ativa(1).webp',1),(@base_perodua+29,'uploads/ativa(1).webp',1),(@base_perodua+30,'uploads/ativa(1).webp',1),(@base_perodua+31,'uploads/ativa(1).webp',1),
(@base_perodua+32,'uploads/ativa(1).webp',1),(@base_perodua+33,'uploads/ativa(1).webp',1),(@base_perodua+34,'uploads/ativa(1).webp',1),(@base_perodua+35,'uploads/ativa(1).webp',1),
(@base_perodua+36,'uploads/ativa(1).webp',1),(@base_perodua+37,'uploads/ativa(1).webp',1),(@base_perodua+38,'uploads/ativa(1).webp',1),(@base_perodua+39,'uploads/ativa(1).webp',1),
(@base_perodua+40,'uploads/ativa(1).webp',1),(@base_perodua+41,'uploads/ativa(1).webp',1),(@base_perodua+42,'uploads/ativa(1).webp',1),(@base_perodua+43,'uploads/ativa(1).webp',1),
(@base_perodua+44,'uploads/ativa(1).webp',1),(@base_perodua+45,'uploads/ativa(1).webp',1),(@base_perodua+46,'uploads/ativa(1).webp',1),(@base_perodua+47,'uploads/ativa(1).webp',1),
(@base_perodua+48,'uploads/ativa(1).webp',1),(@base_perodua+49,'uploads/ativa(1).webp',1),(@base_perodua+50,'uploads/ativa(1).webp',1),(@base_perodua+51,'uploads/ativa(1).webp',1),
(@base_perodua+52,'uploads/ativa(1).webp',1),(@base_perodua+53,'uploads/ativa(1).webp',1),(@base_perodua+54,'uploads/ativa(1).webp',1),(@base_perodua+55,'uploads/ativa(1).webp',1),
(@base_perodua+56,'uploads/ativa(1).webp',1),(@base_perodua+57,'uploads/ativa(1).webp',1),(@base_perodua+58,'uploads/ativa(1).webp',1),(@base_perodua+59,'uploads/ativa(1).webp',1);

-- 60 details with mixed car_condition
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note)
VALUES
-- Myvi (0-19)
(@base_perodua+0,'White',94,'1NR-VE',4,'185/55 R15','185/55 R15',121,'Hatchback','Used','Popular choice.'),
(@base_perodua+1,'Grey',102,'2NR-VE',4,'185/55 R15','185/55 R15',136,'Hatchback','Reconditioned','Well kept.'),
(@base_perodua+2,'Red',102,'2NR-VE',4,'185/55 R15','185/55 R15',136,'Hatchback','Used','Top variant.'),
(@base_perodua+3,'Blue',102,'2NR-VE',4,'185/55 R15','185/55 R15',136,'Hatchback','Reconditioned','Facelift recond.'),
(@base_perodua+4,'Silver',94,'1NR-VE',4,'185/55 R15','185/55 R15',121,'Hatchback','Used','Low mileage.'),
(@base_perodua+5,'White',102,'2NR-VE',4,'185/55 R15','185/55 R15',136,'Hatchback','Reconditioned','Like new.'),
(@base_perodua+6,'Grey',102,'2NR-VE',4,'185/55 R15','185/55 R15',136,'Hatchback','New','Unregistered new.'),
(@base_perodua+7,'Black',94,'1NR-VE',4,'185/55 R15','185/55 R15',121,'Hatchback','Used','Reliable.'),
(@base_perodua+8,'White',102,'2NR-VE',4,'185/55 R15','185/55 R15',136,'Hatchback','Reconditioned','Well kept.'),
(@base_perodua+9,'Grey',102,'2NR-VE',4,'185/55 R15','185/55 R15',136,'Hatchback','Used','Clean interior.'),
(@base_perodua+10,'Silver',102,'2NR-VE',4,'185/55 R15','185/55 R15',136,'Hatchback','Reconditioned','New paint.'),
(@base_perodua+11,'Blue',102,'2NR-VE',4,'185/55 R15','185/55 R15',136,'Hatchback','Used','Daily drive.'),
(@base_perodua+12,'White',94,'1NR-VE',4,'185/55 R15','185/55 R15',121,'Hatchback','Reconditioned','Value buy recond.'),
(@base_perodua+13,'Grey',102,'2NR-VE',4,'185/55 R15','185/55 R15',136,'Hatchback','Used','Trusted unit.'),
(@base_perodua+14,'Red',102,'2NR-VE',4,'185/55 R15','185/55 R15',136,'Hatchback','Reconditioned','Top spec recond.'),
(@base_perodua+15,'White',94,'1NR-VE',4,'185/55 R15','185/55 R15',121,'Hatchback','Used','Low mileage.'),
(@base_perodua+16,'Grey',102,'2NR-VE',4,'185/55 R15','185/55 R15',136,'Hatchback','Reconditioned','Like new.'),
(@base_perodua+17,'Black',102,'2NR-VE',4,'185/55 R15','185/55 R15',136,'Hatchback','Used','Clean unit.'),
(@base_perodua+18,'White',102,'2NR-VE',4,'185/55 R15','185/55 R15',136,'Hatchback','Reconditioned','Well maintained.'),
(@base_perodua+19,'Grey',94,'1NR-VE',4,'185/55 R15','185/55 R15',121,'Hatchback','New','Unregistered new.'),
-- Axia (20-39)
(@base_perodua+20,'Silver',67,'1KR-VE',4,'175/65 R14','175/65 R14',91,'Hatchback','Used','Economical.'),
(@base_perodua+21,'White',67,'1KR-VE',4,'175/65 R14','175/65 R14',91,'Hatchback','Reconditioned','Well kept.'),
(@base_perodua+22,'Grey',67,'1KR-VE',4,'175/65 R14','175/65 R14',91,'Hatchback','Used','Low mileage.'),
(@base_perodua+23,'Blue',67,'1KR-VE',4,'175/65 R14','175/65 R14',91,'Hatchback','Reconditioned','Clean unit.'),
(@base_perodua+24,'Silver',67,'1KR-VE',4,'175/65 R14','175/65 R14',91,'Hatchback','Used','Daily drive.'),
(@base_perodua+25,'White',67,'1KR-VE',4,'175/65 R14','175/65 R14',91,'Hatchback','Reconditioned','New paint.'),
(@base_perodua+26,'Grey',67,'1KR-VE',4,'175/65 R14','175/65 R14',91,'Hatchback','New','Unregistered new.'),
(@base_perodua+27,'Black',67,'1KR-VE',4,'175/65 R14','175/65 R14',91,'Hatchback','Used','Reliable.'),
(@base_perodua+28,'White',67,'1KR-VE',4,'175/65 R14','175/65 R14',91,'Hatchback','Reconditioned','Well kept.'),
(@base_perodua+29,'Grey',67,'1KR-VE',4,'175/65 R14','175/65 R14',91,'Hatchback','Used','Clean interior.'),
(@base_perodua+30,'Silver',67,'1KR-VE',4,'175/65 R14','175/65 R14',91,'Hatchback','Reconditioned','New paint.'),
(@base_perodua+31,'Blue',67,'1KR-VE',4,'175/65 R14','175/65 R14',91,'Hatchback','Used','Daily drive.'),
(@base_perodua+32,'White',67,'1KR-VE',4,'175/65 R14','175/65 R14',91,'Hatchback','Reconditioned','Value buy recond.'),
(@base_perodua+33,'Grey',67,'1KR-VE',4,'175/65 R14','175/65 R14',91,'Hatchback','Used','Trusted unit.'),
(@base_perodua+34,'Red',67,'1KR-VE',4,'175/65 R14','175/65 R14',91,'Hatchback','Reconditioned','Top spec recond.'),
(@base_perodua+35,'White',67,'1KR-VE',4,'175/65 R14','175/65 R14',91,'Hatchback','Used','Low mileage.'),
(@base_perodua+36,'Grey',67,'1KR-VE',4,'175/65 R14','175/65 R14',91,'Hatchback','Reconditioned','Like new.'),
(@base_perodua+37,'Black',67,'1KR-VE',4,'175/65 R14','175/65 R14',91,'Hatchback','Used','Clean unit.'),
(@base_perodua+38,'White',67,'1KR-VE',4,'175/65 R14','175/65 R14',91,'Hatchback','Reconditioned','Well maintained.'),
(@base_perodua+39,'Grey',67,'1KR-VE',4,'175/65 R14','175/65 R14',91,'Hatchback','New','Unregistered new.'),
-- Bezza (40-59)
(@base_perodua+40,'Silver',94,'1NR-VE',4,'175/65 R14','175/65 R14',121,'Sedan','Used','Practical sedan.'),
(@base_perodua+41,'White',94,'1NR-VE',4,'175/65 R14','175/65 R14',121,'Sedan','Reconditioned','Well kept.'),
(@base_perodua+42,'Grey',94,'1NR-VE',4,'175/65 R14','175/65 R14',121,'Sedan','Used','Low mileage.'),
(@base_perodua+43,'Blue',94,'1NR-VE',4,'175/65 R14','175/65 R14',121,'Sedan','Reconditioned','Clean unit.'),
(@base_perodua+44,'Silver',94,'1NR-VE',4,'175/65 R14','175/65 R14',121,'Sedan','Used','Daily drive.'),
(@base_perodua+45,'White',94,'1NR-VE',4,'175/65 R14','175/65 R14',121,'Sedan','Reconditioned','New paint.'),
(@base_perodua+46,'Grey',94,'1NR-VE',4,'175/65 R14','175/65 R14',121,'Sedan','New','Unregistered new.'),
(@base_perodua+47,'Black',94,'1NR-VE',4,'175/65 R14','175/65 R14',121,'Sedan','Used','Reliable.'),
(@base_perodua+48,'White',94,'1NR-VE',4,'175/65 R14','175/65 R14',121,'Sedan','Reconditioned','Well kept.'),
(@base_perodua+49,'Grey',94,'1NR-VE',4,'175/65 R14','175/65 R14',121,'Sedan','Used','Clean interior.'),
(@base_perodua+50,'Silver',94,'1NR-VE',4,'175/65 R14','175/65 R14',121,'Sedan','Reconditioned','New paint.'),
(@base_perodua+51,'Blue',94,'1NR-VE',4,'175/65 R14','175/65 R14',121,'Sedan','Used','Daily drive.'),
(@base_perodua+52,'White',94,'1NR-VE',4,'175/65 R14','175/65 R14',121,'Sedan','Reconditioned','Value buy recond.'),
(@base_perodua+53,'Grey',94,'1NR-VE',4,'175/65 R14','175/65 R14',121,'Sedan','Used','Trusted unit.'),
(@base_perodua+54,'Red',94,'1NR-VE',4,'175/65 R14','175/65 R14',121,'Sedan','Reconditioned','Top spec recond.'),
(@base_perodua+55,'White',94,'1NR-VE',4,'175/65 R14','175/65 R14',121,'Sedan','Used','Low mileage.'),
(@base_perodua+56,'Grey',94,'1NR-VE',4,'175/65 R14','175/65 R14',121,'Sedan','Reconditioned','Like new.'),
(@base_perodua+57,'Black',94,'1NR-VE',4,'175/65 R14','175/65 R14',121,'Sedan','Used','Clean unit.'),
(@base_perodua+58,'White',94,'1NR-VE',4,'175/65 R14','175/65 R14',121,'Sedan','Reconditioned','Well maintained.'),
(@base_perodua+59,'Grey',94,'1NR-VE',4,'175/65 R14','175/65 R14',121,'Sedan','New','Unregistered new.');

COMMIT;
