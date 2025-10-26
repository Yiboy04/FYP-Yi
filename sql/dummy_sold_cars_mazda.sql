-- Dummy bulk data: 50 sold Mazda cars
-- All rows use seller_id=4 and listing_status='sold'.
-- Models strictly from data/makes_models_my.json.
-- Images reuse 'uploads/ativa(1).webp' as a thumbnail placeholder.

START TRANSACTION;

SET @seller_id := 4;  -- Ensure this seller exists in your DB

-- MAZDA (50)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES
-- Mazda2 (1-5)
(@seller_id,'Mazda','2','1.5 Skyactiv-G High',2016,'1.5',65000,'AT',42500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mazda','2','1.5 Skyactiv-G High',2017,'1.5',58000,'AT',45500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mazda','2','1.5 Skyactiv-G High Plus',2018,'1.5',52000,'AT',49500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mazda','2','1.5 Skyactiv-G High Plus',2019,'1.5',43000,'AT',55500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mazda','2','1.5 Skyactiv-G',2020,'1.5',35000,'AT',59500.00,'Gasoline','FWD',5,'sold'),
-- Mazda3 (6-10)
(@seller_id,'Mazda','3','1.5 Skyactiv-G',2016,'1.5',75000,'AT',56500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mazda','3','2.0 Skyactiv-G',2017,'2.0',69000,'AT',66500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mazda','3','2.0 Skyactiv-G',2019,'2.0',52000,'AT',86500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mazda','3','2.0 Skyactiv-G',2020,'2.0',38000,'AT',99500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mazda','3','2.0 Skyactiv-X',2021,'2.0',26000,'AT',118000.00,'Gasoline','FWD',5,'sold'),
-- Mazda6 (11-15)
(@seller_id,'Mazda','6','2.0 Skyactiv-G',2016,'2.0',82000,'AT',68500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Mazda','6','2.5 Skyactiv-G',2017,'2.5',76000,'AT',79500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Mazda','6','2.5 Skyactiv-G',2018,'2.5',64000,'AT',99500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Mazda','6','2.5 Skyactiv-G',2019,'2.5',52000,'AT',118000.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Mazda','6','2.2 Skyactiv-D',2020,'2.2',42000,'AT',128000.00,'Diesel','FWD',4,'sold'),
-- CX-3 (16-20)
(@seller_id,'Mazda','CX-3','2.0 Skyactiv-G',2016,'2.0',70000,'AT',69500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mazda','CX-3','2.0 Skyactiv-G',2017,'2.0',62000,'AT',75500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mazda','CX-3','2.0 Skyactiv-G',2018,'2.0',54000,'AT',82500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mazda','CX-3','2.0 Skyactiv-G',2019,'2.0',46000,'AT',90500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mazda','CX-3','2.0 Skyactiv-G',2020,'2.0',38000,'AT',98500.00,'Gasoline','FWD',5,'sold'),
-- CX-5 (21-25)
(@seller_id,'Mazda','CX-5','2.0 Skyactiv-G',2016,'2.0',78000,'AT',79500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mazda','CX-5','2.5 Skyactiv-G',2017,'2.5',72000,'AT',93500.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Mazda','CX-5','2.2 Skyactiv-D',2018,'2.2',64000,'AT',112000.00,'Diesel','AWD',5,'sold'),
(@seller_id,'Mazda','CX-5','2.5 Skyactiv-G',2019,'2.5',56000,'AT',125000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Mazda','CX-5','2.5 Skyactiv-G',2020,'2.5',42000,'AT',139000.00,'Gasoline','AWD',5,'sold'),
-- CX-8 (26-30)
(@seller_id,'Mazda','CX-8','2.5 Skyactiv-G',2018,'2.5',62000,'AT',138000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mazda','CX-8','2.5 Skyactiv-G',2019,'2.5',54000,'AT',148000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mazda','CX-8','2.2 Skyactiv-D',2020,'2.2',46000,'AT',168000.00,'Diesel','AWD',5,'sold'),
(@seller_id,'Mazda','CX-8','2.2 Skyactiv-D',2021,'2.2',36000,'AT',185000.00,'Diesel','AWD',5,'sold'),
(@seller_id,'Mazda','CX-8','2.5 Skyactiv-G',2022,'2.5',24000,'AT',198000.00,'Gasoline','FWD',5,'sold'),
-- CX-30 (31-35)
(@seller_id,'Mazda','CX-30','2.0 Skyactiv-G',2019,'2.0',38000,'AT',108000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mazda','CX-30','2.0 Skyactiv-G',2020,'2.0',30000,'AT',118000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mazda','CX-30','2.0 Skyactiv-G',2021,'2.0',22000,'AT',128000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mazda','CX-30','2.0 Skyactiv-G',2022,'2.0',16000,'AT',138000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mazda','CX-30','2.0 Skyactiv-G',2022,'2.0',12000,'AT',142000.00,'Gasoline','AWD',5,'sold'),
-- CX-9 (36-40)
(@seller_id,'Mazda','CX-9','2.5T Skyactiv-G',2017,'2.5',68000,'AT',135000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Mazda','CX-9','2.5T Skyactiv-G',2018,'2.5',61000,'AT',145000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Mazda','CX-9','2.5T Skyactiv-G',2019,'2.5',52000,'AT',165000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Mazda','CX-9','2.5T Skyactiv-G',2020,'2.5',42000,'AT',178000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Mazda','CX-9','2.5T Skyactiv-G',2021,'2.5',28000,'AT',195000.00,'Gasoline','AWD',5,'sold'),
-- BT-50 (41-45)
(@seller_id,'Mazda','BT-50','1.9 High-Rider',2016,'1.9',98000,'AT',65500.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Mazda','BT-50','1.9 High-Rider',2017,'1.9',90000,'AT',68500.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Mazda','BT-50','1.9 High-Rider',2018,'1.9',82000,'AT',72500.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Mazda','BT-50','1.9 High-Rider',2019,'1.9',72000,'AT',79500.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Mazda','BT-50','1.9 High-Rider',2020,'1.9',62000,'AT',85500.00,'Diesel','4WD',4,'sold'),
-- MX-5 (46-50)
(@seller_id,'Mazda','MX-5','2.0 RF',2016,'2.0',52000,'AT',115000.00,'Gasoline','RWD',2,'sold'),
(@seller_id,'Mazda','MX-5','2.0 RF',2017,'2.0',48000,'AT',125000.00,'Gasoline','RWD',2,'sold'),
(@seller_id,'Mazda','MX-5','2.0 RF',2018,'2.0',42000,'AT',138000.00,'Gasoline','RWD',2,'sold'),
(@seller_id,'Mazda','MX-5','2.0 RF',2019,'2.0',36000,'AT',148000.00,'Gasoline','RWD',2,'sold'),
(@seller_id,'Mazda','MX-5','2.0 RF',2020,'2.0',28000,'AT',165000.00,'Gasoline','RWD',2,'sold');

SET @base_id := LAST_INSERT_ID();

-- 50 thumbnails
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES
(@base_id+0,'uploads/ativa(1).webp',1),(@base_id+1,'uploads/ativa(1).webp',1),(@base_id+2,'uploads/ativa(1).webp',1),(@base_id+3,'uploads/ativa(1).webp',1),
(@base_id+4,'uploads/ativa(1).webp',1),(@base_id+5,'uploads/ativa(1).webp',1),(@base_id+6,'uploads/ativa(1).webp',1),(@base_id+7,'uploads/ativa(1).webp',1),
(@base_id+8,'uploads/ativa(1).webp',1),(@base_id+9,'uploads/ativa(1).webp',1),(@base_id+10,'uploads/ativa(1).webp',1),(@base_id+11,'uploads/ativa(1).webp',1),
(@base_id+12,'uploads/ativa(1).webp',1),(@base_id+13,'uploads/ativa(1).webp',1),(@base_id+14,'uploads/ativa(1).webp',1),(@base_id+15,'uploads/ativa(1).webp',1),
(@base_id+16,'uploads/ativa(1).webp',1),(@base_id+17,'uploads/ativa(1).webp',1),(@base_id+18,'uploads/ativa(1).webp',1),(@base_id+19,'uploads/ativa(1).webp',1),
(@base_id+20,'uploads/ativa(1).webp',1),(@base_id+21,'uploads/ativa(1).webp',1),(@base_id+22,'uploads/ativa(1).webp',1),(@base_id+23,'uploads/ativa(1).webp',1),
(@base_id+24,'uploads/ativa(1).webp',1),(@base_id+25,'uploads/ativa(1).webp',1),(@base_id+26,'uploads/ativa(1).webp',1),(@base_id+27,'uploads/ativa(1).webp',1),
(@base_id+28,'uploads/ativa(1).webp',1),(@base_id+29,'uploads/ativa(1).webp',1),(@base_id+30,'uploads/ativa(1).webp',1),(@base_id+31,'uploads/ativa(1).webp',1),
(@base_id+32,'uploads/ativa(1).webp',1),(@base_id+33,'uploads/ativa(1).webp',1),(@base_id+34,'uploads/ativa(1).webp',1),(@base_id+35,'uploads/ativa(1).webp',1),
(@base_id+36,'uploads/ativa(1).webp',1),(@base_id+37,'uploads/ativa(1).webp',1),(@base_id+38,'uploads/ativa(1).webp',1),(@base_id+39,'uploads/ativa(1).webp',1),
(@base_id+40,'uploads/ativa(1).webp',1),(@base_id+41,'uploads/ativa(1).webp',1),(@base_id+42,'uploads/ativa(1).webp',1),(@base_id+43,'uploads/ativa(1).webp',1),
(@base_id+44,'uploads/ativa(1).webp',1),(@base_id+45,'uploads/ativa(1).webp',1),(@base_id+46,'uploads/ativa(1).webp',1),(@base_id+47,'uploads/ativa(1).webp',1),
(@base_id+48,'uploads/ativa(1).webp',1),(@base_id+49,'uploads/ativa(1).webp',1);

-- 50 details
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note)
VALUES
-- Mazda2
(@base_id+0,'Red',114,'P5-VPS',6,'185/60 R16','185/60 R16',148,'Hatchback','Used','Zippy supermini.'),
(@base_id+1,'White',114,'P5-VPS',6,'185/60 R16','185/60 R16',148,'Hatchback','Used','Low mileage.'),
(@base_id+2,'Grey',114,'P5-VPS',6,'185/60 R16','185/60 R16',148,'Hatchback','Used','Well kept.'),
(@base_id+3,'Blue',114,'P5-VPS',6,'185/60 R16','185/60 R16',148,'Hatchback','Used','High Plus.'),
(@base_id+4,'Silver',114,'P5-VPS',6,'185/60 R16','185/60 R16',148,'Hatchback','Used','Clean unit.'),
-- Mazda3
(@base_id+5,'Red',118,'P5-VPS',6,'215/45 R18','215/45 R18',150,'Hatchback','Used','Mazda3 1.5.'),
(@base_id+6,'Grey',162,'PE-VPS',6,'215/45 R18','215/45 R18',213,'Hatchback','Used','2.0 lively.'),
(@base_id+7,'White',162,'PE-VPS',6,'215/45 R18','215/45 R18',213,'Sedan','Used','BP gen.'),
(@base_id+8,'Blue',162,'PE-VPS',6,'215/45 R18','215/45 R18',213,'Hatchback','Used','Nice drive.'),
(@base_id+9,'Black',178,'SPCCI',6,'215/45 R18','215/45 R18',224,'Sedan','Used','Skyactiv-X.'),
-- Mazda6
(@base_id+10,'White',162,'PE-VPS',6,'225/55 R17','225/55 R17',213,'Sedan','Used','Comfort cruiser.'),
(@base_id+11,'Red',192,'PY-VPS',6,'225/45 R19','225/45 R19',258,'Sedan','Used','Luxury feel.'),
(@base_id+12,'Grey',192,'PY-VPS',6,'225/45 R19','225/45 R19',258,'Sedan','Used','Facelift.'),
(@base_id+13,'Blue',192,'PY-VPS',6,'225/45 R19','225/45 R19',258,'Sedan','Used','Well kept.'),
(@base_id+14,'Silver',173,'SH-VPTS',6,'225/55 R17','225/55 R17',420,'Sedan','Used','Diesel punch.'),
-- CX-3
(@base_id+15,'Red',154,'PE-VPS',6,'215/50 R18','215/50 R18',204,'SUV','Used','Sporty compact.'),
(@base_id+16,'White',154,'PE-VPS',6,'215/50 R18','215/50 R18',204,'SUV','Used','Nice spec.'),
(@base_id+17,'Grey',154,'PE-VPS',6,'215/50 R18','215/50 R18',204,'SUV','Used','Clean interior.'),
(@base_id+18,'Blue',154,'PE-VPS',6,'215/50 R18','215/50 R18',204,'SUV','Used','Low mileage.'),
(@base_id+19,'Black',154,'PE-VPS',6,'215/50 R18','215/50 R18',204,'SUV','Used','Well kept.'),
-- CX-5
(@base_id+20,'White',162,'PE-VPS',6,'225/65 R17','225/65 R17',213,'SUV','Used','Popular SUV.'),
(@base_id+21,'Grey',192,'PY-VPS',6,'225/55 R19','225/55 R19',258,'SUV','Used','Strong 2.5.'),
(@base_id+22,'Blue',173,'SH-VPTS',6,'225/55 R19','225/55 R19',420,'SUV','Used','Diesel torque.'),
(@base_id+23,'Red',192,'PY-VPS',6,'225/55 R19','225/55 R19',258,'SUV','Used','AWD grip.'),
(@base_id+24,'Black',192,'PY-VPS',6,'225/55 R19','225/55 R19',258,'SUV','Used','Well kept.'),
-- CX-8
(@base_id+25,'White',192,'PY-VPS',6,'225/55 R19','225/55 R19',258,'SUV','Used','7-seater.'),
(@base_id+26,'Grey',192,'PY-VPS',6,'225/55 R19','225/55 R19',258,'SUV','Used','Family car.'),
(@base_id+27,'Black',188,'SH-VPTS',6,'225/55 R19','225/55 R19',450,'SUV','Used','Diesel AWD.'),
(@base_id+28,'Red',188,'SH-VPTS',6,'225/55 R19','225/55 R19',450,'SUV','Used','Low mileage.'),
(@base_id+29,'Blue',192,'PY-VPS',6,'225/55 R19','225/55 R19',258,'SUV','Used','Nice condition.'),
-- CX-30
(@base_id+30,'Red',162,'PE-VPS',6,'215/55 R18','215/55 R18',213,'SUV','Used','Sharp looks.'),
(@base_id+31,'White',162,'PE-VPS',6,'215/55 R18','215/55 R18',213,'SUV','Used','Well kept.'),
(@base_id+32,'Grey',162,'PE-VPS',6,'215/55 R18','215/55 R18',213,'SUV','Used','Clean interior.'),
(@base_id+33,'Blue',162,'PE-VPS',6,'215/55 R18','215/55 R18',213,'SUV','Used','Low mileage.'),
(@base_id+34,'Black',162,'PE-VPS',6,'215/55 R18','215/55 R18',213,'SUV','Used','One owner.'),
-- CX-9
(@base_id+35,'White',228,'PY-VPTS',6,'255/50 R20','255/50 R20',420,'SUV','Used','Turbo torque.'),
(@base_id+36,'Grey',228,'PY-VPTS',6,'255/50 R20','255/50 R20',420,'SUV','Used','AWD flagship.'),
(@base_id+37,'Black',228,'PY-VPTS',6,'255/50 R20','255/50 R20',420,'SUV','Used','Well kept.'),
(@base_id+38,'Red',228,'PY-VPTS',6,'255/50 R20','255/50 R20',420,'SUV','Used','Luxury 7-seater.'),
(@base_id+39,'Blue',228,'PY-VPTS',6,'255/50 R20','255/50 R20',420,'SUV','Used','Low mileage.'),
-- BT-50
(@base_id+40,'White',150,'RZ4E',6,'265/65 R17','265/65 R17',350,'Pickup','Used','Tough 4x4.'),
(@base_id+41,'Silver',150,'RZ4E',6,'265/65 R17','265/65 R17',350,'Pickup','Used','New shape.'),
(@base_id+42,'Grey',150,'RZ4E',6,'265/65 R17','265/65 R17',350,'Pickup','Used','Clean unit.'),
(@base_id+43,'Blue',150,'RZ4E',6,'265/65 R17','265/65 R17',350,'Pickup','Used','Low mileage.'),
(@base_id+44,'Black',150,'RZ4E',6,'265/65 R17','265/65 R17',350,'Pickup','Used','Well kept.'),
-- MX-5
(@base_id+45,'Red',181,'PE-VPS',6,'205/45 R17','205/45 R17',205,'Convertible','Used','Fun roadster.'),
(@base_id+46,'White',181,'PE-VPS',6,'205/45 R17','205/45 R17',205,'Convertible','Used','RF roof.'),
(@base_id+47,'Grey',181,'PE-VPS',6,'205/45 R17','205/45 R17',205,'Convertible','Used','Well kept.'),
(@base_id+48,'Blue',181,'PE-VPS',6,'205/45 R17','205/45 R17',205,'Convertible','Used','Low mileage.'),
(@base_id+49,'Black',181,'PE-VPS',6,'205/45 R17','205/45 R17',205,'Convertible','Used','One owner.');

COMMIT;
