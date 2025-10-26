-- Dummy bulk data: 70 sold Toyota cars
-- All rows use seller_id=4 and listing_status='sold'.
-- Models strictly from data/makes_models_my.json.
-- Images reuse 'uploads/ativa(1).webp' as a thumbnail placeholder.

START TRANSACTION;

SET @seller_id := 5;  -- Ensure this seller exists in your DB

-- TOYOTA (70)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES
-- Vios (1-7)
(@seller_id,'Toyota','Vios','1.5 Dual VVT-i',2016,'1.5',74000,'CVT',42000.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Vios','1.5 Dual VVT-i',2017,'1.5',62000,'CVT',45500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Vios','1.5 Dual VVT-i',2018,'1.5',56000,'CVT',49500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Vios','1.5 GR-S',2020,'1.5',32000,'CVT',68500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Vios','1.5',2019,'1.5',42000,'CVT',54500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Vios','1.5',2021,'1.5',22000,'CVT',72500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Vios','1.5',2022,'1.5',18000,'CVT',77500.00,'Gasoline','FWD',4,'sold'),
-- Yaris (8-14)
(@seller_id,'Toyota','Yaris','1.5',2016,'1.5',71000,'CVT',42500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Toyota','Yaris','1.5',2017,'1.5',61000,'CVT',46500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Toyota','Yaris','1.5',2018,'1.5',54000,'CVT',50500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Toyota','Yaris','1.5',2019,'1.5',46000,'CVT',54500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Toyota','Yaris','1.5',2020,'1.5',32000,'CVT',58500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Toyota','Yaris','1.5',2021,'1.5',23000,'CVT',64500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Toyota','Yaris','1.5',2022,'1.5',17000,'CVT',69500.00,'Gasoline','FWD',5,'sold'),
-- Corolla (15-21)
(@seller_id,'Toyota','Corolla','1.8',2016,'1.8',78000,'CVT',59500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Corolla','1.8',2017,'1.8',72000,'CVT',63500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Corolla','1.8',2018,'1.8',56000,'CVT',71500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Corolla','1.8',2019,'1.8',48000,'CVT',79500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Corolla','1.8 Hybrid',2020,'1.8',36000,'CVT',98500.00,'Hybrid','FWD',4,'sold'),
(@seller_id,'Toyota','Corolla','1.8',2021,'1.8',25000,'CVT',109000.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Corolla','1.8 Hybrid',2022,'1.8',19000,'CVT',122000.00,'Hybrid','FWD',4,'sold'),
-- Camry (22-28)
(@seller_id,'Toyota','Camry','2.0',2015,'2.0',88000,'AT',72500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Camry','2.5',2016,'2.5',82000,'AT',82500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Camry','2.0',2017,'2.0',76000,'AT',90500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Camry','2.5',2018,'2.5',68000,'AT',112000.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Camry','2.5',2019,'2.5',58000,'AT',128000.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Camry','2.5',2020,'2.5',41000,'AT',145000.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Camry','2.5 Hybrid',2021,'2.5',26000,'AT',178000.00,'Hybrid','FWD',4,'sold'),
-- Hilux (29-35)
(@seller_id,'Toyota','Hilux','2.4G',2016,'2.4',98000,'AT',78500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Toyota','Hilux','2.8 Rogue',2017,'2.8',88000,'AT',94500.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Toyota','Hilux','2.4G',2018,'2.4',76000,'AT',90500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Toyota','Hilux','2.8 Rogue',2019,'2.8',64000,'AT',115000.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Toyota','Hilux','2.8 Rogue',2020,'2.8',52000,'AT',128000.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Toyota','Hilux','2.4G',2021,'2.4',42000,'AT',118000.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Toyota','Hilux','2.8 Rogue',2022,'2.8',30000,'AT',145000.00,'Diesel','4WD',4,'sold'),
-- Fortuner (36-42)
(@seller_id,'Toyota','Fortuner','2.4 VRZ',2016,'2.4',82000,'AT',118000.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Toyota','Fortuner','2.4 VRZ',2017,'2.4',76000,'AT',125000.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Toyota','Fortuner','2.4 VRZ',2018,'2.4',69000,'AT',135000.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Toyota','Fortuner','2.7 SRZ',2019,'2.7',56000,'AT',145000.00,'Gasoline','4WD',5,'sold'),
(@seller_id,'Toyota','Fortuner','2.8 VRZ',2020,'2.8',45000,'AT',168000.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Toyota','Fortuner','2.8 VRZ',2021,'2.8',32000,'AT',188000.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Toyota','Fortuner','2.8 VRZ',2022,'2.8',22000,'AT',208000.00,'Diesel','4WD',5,'sold'),
-- Avanza (43-49)
(@seller_id,'Toyota','Avanza','1.5',2016,'1.5',88000,'AT',38500.00,'Gasoline','RWD',5,'sold'),
(@seller_id,'Toyota','Avanza','1.5',2017,'1.5',82000,'AT',41500.00,'Gasoline','RWD',5,'sold'),
(@seller_id,'Toyota','Avanza','1.5',2018,'1.5',76000,'AT',44500.00,'Gasoline','RWD',5,'sold'),
(@seller_id,'Toyota','Avanza','1.5',2019,'1.5',68000,'AT',48500.00,'Gasoline','RWD',5,'sold'),
(@seller_id,'Toyota','Avanza','1.5',2020,'1.5',54000,'AT',52500.00,'Gasoline','RWD',5,'sold'),
(@seller_id,'Toyota','Avanza','1.5',2021,'1.5',42000,'AT',56500.00,'Gasoline','RWD',5,'sold'),
(@seller_id,'Toyota','Avanza','1.5',2022,'1.5',32000,'AT',61500.00,'Gasoline','RWD',5,'sold'),
-- Innova (50-56)
(@seller_id,'Toyota','Innova','2.0',2016,'2.0',92000,'AT',62500.00,'Gasoline','RWD',5,'sold'),
(@seller_id,'Toyota','Innova','2.0',2017,'2.0',86000,'AT',65500.00,'Gasoline','RWD',5,'sold'),
(@seller_id,'Toyota','Innova','2.0',2018,'2.0',78000,'AT',72500.00,'Gasoline','RWD',5,'sold'),
(@seller_id,'Toyota','Innova','2.0',2019,'2.0',69000,'AT',79500.00,'Gasoline','RWD',5,'sold'),
(@seller_id,'Toyota','Innova','2.0',2020,'2.0',56000,'AT',84500.00,'Gasoline','RWD',5,'sold'),
(@seller_id,'Toyota','Innova','2.0',2021,'2.0',43000,'AT',90500.00,'Gasoline','RWD',5,'sold'),
(@seller_id,'Toyota','Innova','2.0',2022,'2.0',30000,'AT',98500.00,'Gasoline','RWD',5,'sold'),
-- Harrier (57-63)
(@seller_id,'Toyota','Harrier','2.0 Turbo',2016,'2.0',82000,'AT',115000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Toyota','Harrier','2.0 Turbo',2017,'2.0',76000,'AT',125000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Toyota','Harrier','2.0 Turbo',2018,'2.0',68000,'AT',135000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Toyota','Harrier','2.0 Turbo',2019,'2.0',56000,'AT',148000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Toyota','Harrier','2.5 Hybrid',2020,'2.5',42000,'AT',188000.00,'Hybrid','FWD',5,'sold'),
(@seller_id,'Toyota','Harrier','2.5 Hybrid',2021,'2.5',30000,'AT',205000.00,'Hybrid','FWD',5,'sold'),
(@seller_id,'Toyota','Harrier','2.5 Hybrid',2022,'2.5',21000,'AT',218000.00,'Hybrid','FWD',5,'sold'),
-- RAV4 (64-70)
(@seller_id,'Toyota','RAV4','2.5',2016,'2.5',78000,'AT',108000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Toyota','RAV4','2.5',2017,'2.5',72000,'AT',118000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Toyota','RAV4','2.5',2018,'2.5',64000,'AT',132000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Toyota','RAV4','2.5',2019,'2.5',56000,'AT',148000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Toyota','RAV4','2.5 Hybrid',2020,'2.5',42000,'AT',198000.00,'Hybrid','AWD',5,'sold'),
(@seller_id,'Toyota','RAV4','2.5 Hybrid',2021,'2.5',30000,'AT',218000.00,'Hybrid','AWD',5,'sold'),
(@seller_id,'Toyota','RAV4','2.5 Hybrid',2022,'2.5',18000,'AT',235000.00,'Hybrid','AWD',5,'sold');

SET @base_id := LAST_INSERT_ID();

-- 70 thumbnails
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
(@base_id+48,'uploads/ativa(1).webp',1),(@base_id+49,'uploads/ativa(1).webp',1),(@base_id+50,'uploads/ativa(1).webp',1),(@base_id+51,'uploads/ativa(1).webp',1),
(@base_id+52,'uploads/ativa(1).webp',1),(@base_id+53,'uploads/ativa(1).webp',1),(@base_id+54,'uploads/ativa(1).webp',1),(@base_id+55,'uploads/ativa(1).webp',1),
(@base_id+56,'uploads/ativa(1).webp',1),(@base_id+57,'uploads/ativa(1).webp',1),(@base_id+58,'uploads/ativa(1).webp',1),(@base_id+59,'uploads/ativa(1).webp',1),
(@base_id+60,'uploads/ativa(1).webp',1),(@base_id+61,'uploads/ativa(1).webp',1),(@base_id+62,'uploads/ativa(1).webp',1),(@base_id+63,'uploads/ativa(1).webp',1),
(@base_id+64,'uploads/ativa(1).webp',1),(@base_id+65,'uploads/ativa(1).webp',1),(@base_id+66,'uploads/ativa(1).webp',1),(@base_id+67,'uploads/ativa(1).webp',1),
(@base_id+68,'uploads/ativa(1).webp',1),(@base_id+69,'uploads/ativa(1).webp',1);

-- 70 details
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note)
VALUES
-- Vios
(@base_id+0,'Silver',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Used','Reliable sedan.'),
(@base_id+1,'White',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Used','Low mileage.'),
(@base_id+2,'Grey',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Used','Well kept.'),
(@base_id+3,'Red',107,'2NR-FE',7,'205/50 R17','205/50 R17',140,'Sedan','Used','GR-S styling.'),
(@base_id+4,'Blue',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Used','Clean unit.'),
(@base_id+5,'Black',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Used','1 owner.'),
(@base_id+6,'White',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Used','Service on time.'),
-- Yaris
(@base_id+7,'Yellow',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Hatchback','Used','Zippy city car.'),
(@base_id+8,'Red',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Hatchback','Used','Clean interior.'),
(@base_id+9,'Blue',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Hatchback','Used','Low mileage.'),
(@base_id+10,'White',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Hatchback','Used','Well maintained.'),
(@base_id+11,'Grey',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Hatchback','Used','Nice drive.'),
(@base_id+12,'Black',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Hatchback','Used','Compact hatch.'),
(@base_id+13,'White',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Hatchback','Used','Practical.'),
-- Corolla
(@base_id+14,'White',139,'2ZR-FAE',7,'205/55 R16','205/55 R16',173,'Sedan','Used','Comfort sedan.'),
(@base_id+15,'Silver',139,'2ZR-FAE',7,'205/55 R16','205/55 R16',173,'Sedan','Used','One owner.'),
(@base_id+16,'Grey',139,'2ZR-FAE',7,'205/55 R16','205/55 R16',173,'Sedan','Used','Well kept.'),
(@base_id+17,'Blue',139,'2ZR-FAE',7,'205/55 R16','205/55 R16',173,'Sedan','Used','Clean interior.'),
(@base_id+18,'White',121,'2ZR-FXE',7,'205/55 R16','205/55 R16',142,'Sedan','Used','Hybrid saver.'),
(@base_id+19,'Black',139,'2ZR-FAE',7,'215/45 R17','215/45 R17',173,'Sedan','Used','Facelift.'),
(@base_id+20,'Grey',121,'2ZR-FXE',7,'215/45 R17','215/45 R17',142,'Sedan','Used','Hybrid new gen.'),
-- Camry
(@base_id+21,'Silver',165,'6AR-FSE',8,'215/55 R17','215/55 R17',199,'Sedan','Used','Executive ride.'),
(@base_id+22,'Black',181,'2AR-FE',8,'215/55 R17','215/55 R17',235,'Sedan','Used','Strong 2.5.'),
(@base_id+23,'White',165,'6AR-FSE',8,'215/55 R17','215/55 R17',199,'Sedan','Used','Comfort spec.'),
(@base_id+24,'Grey',209,'A25A-FKS',8,'235/45 R18','235/45 R18',250,'Sedan','Used','New platform.'),
(@base_id+25,'Blue',209,'A25A-FKS',8,'235/45 R18','235/45 R18',250,'Sedan','Used','Low mileage.'),
(@base_id+26,'White',209,'A25A-FKS',8,'235/45 R18','235/45 R18',250,'Sedan','Used','Well kept.'),
(@base_id+27,'Grey',211,'A25A-FXS',8,'235/45 R18','235/45 R18',221,'Sedan','Used','Hybrid flagship.'),
-- Hilux
(@base_id+28,'White',150,'2GD-FTV',6,'265/65 R17','265/65 R17',400,'Pickup','Used','Workhorse.'),
(@base_id+29,'Grey',201,'1GD-FTV',6,'265/65 R17','265/65 R17',500,'Pickup','Used','Rogue 2.8.'),
(@base_id+30,'Silver',150,'2GD-FTV',6,'265/65 R17','265/65 R17',400,'Pickup','Used','Trusted truck.'),
(@base_id+31,'Black',201,'1GD-FTV',6,'265/65 R17','265/65 R17',500,'Pickup','Used','Powerful 2.8.'),
(@base_id+32,'White',201,'1GD-FTV',6,'265/65 R17','265/65 R17',500,'Pickup','Used','Reliable.'),
(@base_id+33,'Blue',150,'2GD-FTV',6,'265/65 R17','265/65 R17',400,'Pickup','Used','Clean unit.'),
(@base_id+34,'Grey',201,'1GD-FTV',6,'265/65 R17','265/65 R17',500,'Pickup','Used','Low mileage.'),
-- Fortuner
(@base_id+35,'White',150,'2GD-FTV',6,'265/60 R18','265/60 R18',400,'SUV','Used','7-seater 4x4.'),
(@base_id+36,'Silver',150,'2GD-FTV',6,'265/60 R18','265/60 R18',400,'SUV','Used','Well kept.'),
(@base_id+37,'Grey',150,'2GD-FTV',6,'265/60 R18','265/60 R18',400,'SUV','Used','Family SUV.'),
(@base_id+38,'Black',164,'2TR-FE',6,'265/60 R18','265/60 R18',245,'SUV','Used','Petrol variant.'),
(@base_id+39,'White',201,'1GD-FTV',6,'265/60 R18','265/60 R18',500,'SUV','Used','Updated model.'),
(@base_id+40,'Red',201,'1GD-FTV',6,'265/60 R18','265/60 R18',500,'SUV','Used','Nice condition.'),
(@base_id+41,'Grey',201,'1GD-FTV',6,'265/60 R18','265/60 R18',500,'SUV','Used','Top spec.'),
-- Avanza
(@base_id+42,'Silver',105,'2NR-VE',4,'185/65 R15','185/65 R15',136,'MPV','Used','Practical MPV.'),
(@base_id+43,'White',105,'2NR-VE',4,'185/65 R15','185/65 R15',136,'MPV','Used','One owner.'),
(@base_id+44,'Grey',105,'2NR-VE',4,'185/65 R15','185/65 R15',136,'MPV','Used','Clean interior.'),
(@base_id+45,'Black',105,'2NR-VE',4,'185/65 R15','185/65 R15',136,'MPV','Used','Low mileage.'),
(@base_id+46,'Blue',105,'2NR-VE',4,'185/65 R15','185/65 R15',136,'MPV','Used','Well kept.'),
(@base_id+47,'White',105,'2NR-VE',4,'185/65 R15','185/65 R15',136,'MPV','Used','7-seater.'),
(@base_id+48,'Grey',105,'2NR-VE',4,'185/65 R15','185/65 R15',136,'MPV','Used','Value buy.'),
-- Innova
(@base_id+49,'Grey',139,'1TR-FE',6,'205/65 R16','205/65 R16',183,'MPV','Used','Comfortable.'),
(@base_id+50,'White',139,'1TR-FE',6,'205/65 R16','205/65 R16',183,'MPV','Used','Family MPV.'),
(@base_id+51,'Black',139,'1TR-FE',6,'205/65 R16','205/65 R16',183,'MPV','Used','Nice condition.'),
(@base_id+52,'Silver',139,'1TR-FE',6,'205/65 R16','205/65 R16',183,'MPV','Used','Reliable.'),
(@base_id+53,'Blue',139,'1TR-FE',6,'205/65 R16','205/65 R16',183,'MPV','Used','Clean unit.'),
(@base_id+54,'Grey',139,'1TR-FE',6,'205/65 R16','205/65 R16',183,'MPV','Used','Low mileage.'),
(@base_id+55,'White',139,'1TR-FE',6,'205/65 R16','205/65 R16',183,'MPV','Used','Well kept.'),
-- Harrier
(@base_id+56,'White',231,'8AR-FTS',8,'235/55 R18','235/55 R18',350,'SUV','Used','Turbo smooth.'),
(@base_id+57,'Black',231,'8AR-FTS',8,'235/55 R18','235/55 R18',350,'SUV','Used','Well maintained.'),
(@base_id+58,'Grey',231,'8AR-FTS',8,'235/55 R18','235/55 R18',350,'SUV','Used','Clean interior.'),
(@base_id+59,'Silver',231,'8AR-FTS',8,'235/55 R18','235/55 R18',350,'SUV','Used','Nice drive.'),
(@base_id+60,'White',178,'A25A-FXS',8,'235/55 R18','235/55 R18',221,'SUV','Used','Hybrid saver.'),
(@base_id+61,'Grey',178,'A25A-FXS',8,'235/55 R18','235/55 R18',221,'SUV','Used','e-Four ready.'),
(@base_id+62,'Black',178,'A25A-FXS',8,'235/55 R18','235/55 R18',221,'SUV','Used','Top spec.'),
-- RAV4
(@base_id+63,'White',203,'A25A-FKS',8,'235/55 R19','235/55 R19',243,'SUV','Used','AWD grip.'),
(@base_id+64,'Blue',203,'A25A-FKS',8,'235/55 R19','235/55 R19',243,'SUV','Used','Low mileage.'),
(@base_id+65,'Grey',203,'A25A-FKS',8,'235/55 R19','235/55 R19',243,'SUV','Used','Clean unit.'),
(@base_id+66,'Black',203,'A25A-FKS',8,'235/55 R19','235/55 R19',243,'SUV','Used','Facelift.'),
(@base_id+67,'White',219,'A25A-FXS',8,'235/55 R19','235/55 R19',221,'SUV','Used','Hybrid efficient.'),
(@base_id+68,'Silver',219,'A25A-FXS',8,'235/55 R19','235/55 R19',221,'SUV','Used','Well kept.'),
(@base_id+69,'Grey',219,'A25A-FXS',8,'235/55 R19','235/55 R19',221,'SUV','Used','New gen.');

COMMIT;

-- Extra batch: 50 more Toyota cars with skewed model distribution
-- Distribution example: 86=5, Vios=10, Supra=2, remaining across Corolla/Camry/Hilux/Yaris/Fortuner/RAV4/Harrier/Avanza/Innova

START TRANSACTION;

SET @seller_id := 5;  -- keep consistent seller

-- TOYOTA EXTRA (50)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES
-- 86 (1-5)
(@seller_id,'Toyota','86','2.0',2017,'2.0',65000,'AT',128000.00,'Gasoline','RWD',2,'sold'),
(@seller_id,'Toyota','86','2.0',2018,'2.0',58000,'AT',138000.00,'Gasoline','RWD',2,'sold'),
(@seller_id,'Toyota','86','2.0',2019,'2.0',52000,'AT',148000.00,'Gasoline','RWD',2,'sold'),
(@seller_id,'Toyota','86','2.4',2020,'2.4',42000,'AT',165000.00,'Gasoline','RWD',2,'sold'),
(@seller_id,'Toyota','86','2.4',2021,'2.4',28000,'AT',178000.00,'Gasoline','RWD',2,'sold'),
-- Vios (6-15)
(@seller_id,'Toyota','Vios','1.5 Dual VVT-i',2015,'1.5',82000,'CVT',39500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Vios','1.5 Dual VVT-i',2016,'1.5',76000,'CVT',42500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Vios','1.5',2017,'1.5',68000,'CVT',45500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Vios','1.5',2018,'1.5',59000,'CVT',49500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Vios','1.5 GR-S',2019,'1.5',47000,'CVT',56500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Vios','1.5',2020,'1.5',35000,'CVT',62500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Vios','1.5',2020,'1.5',33000,'CVT',61500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Vios','1.5',2021,'1.5',24000,'CVT',69500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Vios','1.5',2022,'1.5',17000,'CVT',74500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Vios','1.5',2022,'1.5',16000,'CVT',75500.00,'Gasoline','FWD',4,'sold'),
-- Supra (16-17)
(@seller_id,'Toyota','Supra','3.0',2020,'3.0',32000,'AT',298000.00,'Gasoline','RWD',2,'sold'),
(@seller_id,'Toyota','Supra','2.0 Turbo',2021,'2.0',24000,'AT',268000.00,'Gasoline','RWD',2,'sold'),
-- Corolla (18-23)
(@seller_id,'Toyota','Corolla','1.8',2017,'1.8',69000,'CVT',62500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Corolla','1.8',2018,'1.8',61000,'CVT',70500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Corolla','1.8 Hybrid',2019,'1.8',52000,'CVT',90500.00,'Hybrid','FWD',4,'sold'),
(@seller_id,'Toyota','Corolla','1.8',2020,'1.8',42000,'CVT',98500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Corolla','1.8',2021,'1.8',31000,'CVT',109500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Corolla','1.8 Hybrid',2022,'1.8',21000,'CVT',121500.00,'Hybrid','FWD',4,'sold'),
-- Camry (24-29)
(@seller_id,'Toyota','Camry','2.0',2016,'2.0',84000,'AT',80500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Camry','2.5',2017,'2.5',76000,'AT',89500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Camry','2.5',2018,'2.5',68000,'AT',112500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Camry','2.5',2019,'2.5',56000,'AT',127500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Camry','2.5',2020,'2.5',43000,'AT',146500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Camry','2.5 Hybrid',2021,'2.5',29000,'AT',176500.00,'Hybrid','FWD',4,'sold'),
-- Hilux (30-35)
(@seller_id,'Toyota','Hilux','2.4G',2017,'2.4',92000,'AT',75500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Toyota','Hilux','2.8 Rogue',2018,'2.8',78000,'AT',98500.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Toyota','Hilux','2.4G',2019,'2.4',66000,'AT',90500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Toyota','Hilux','2.8 Rogue',2020,'2.8',54000,'AT',124000.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Toyota','Hilux','2.4G',2021,'2.4',42000,'AT',116000.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Toyota','Hilux','2.8 Rogue',2022,'2.8',31000,'AT',142000.00,'Diesel','4WD',4,'sold'),
-- Yaris (36-39)
(@seller_id,'Toyota','Yaris','1.5',2017,'1.5',69000,'CVT',45500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Toyota','Yaris','1.5',2018,'1.5',61000,'CVT',49500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Toyota','Yaris','1.5',2019,'1.5',52000,'CVT',53500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Toyota','Yaris','1.5',2021,'1.5',26000,'CVT',63500.00,'Gasoline','FWD',5,'sold'),
-- Fortuner (40-42)
(@seller_id,'Toyota','Fortuner','2.4 VRZ',2018,'2.4',78000,'AT',132000.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Toyota','Fortuner','2.8 VRZ',2020,'2.8',46000,'AT',168500.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Toyota','Fortuner','2.8 VRZ',2022,'2.8',23000,'AT',206500.00,'Diesel','4WD',5,'sold'),
-- RAV4 (43-45)
(@seller_id,'Toyota','RAV4','2.5',2019,'2.5',56000,'AT',145000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Toyota','RAV4','2.5 Hybrid',2020,'2.5',42000,'AT',196000.00,'Hybrid','AWD',5,'sold'),
(@seller_id,'Toyota','RAV4','2.5 Hybrid',2021,'2.5',30000,'AT',216000.00,'Hybrid','AWD',5,'sold'),
-- Harrier (46-47)
(@seller_id,'Toyota','Harrier','2.0 Turbo',2019,'2.0',58000,'AT',142000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Toyota','Harrier','2.5 Hybrid',2021,'2.5',29000,'AT',203000.00,'Hybrid','FWD',5,'sold'),
-- Avanza (48-49)
(@seller_id,'Toyota','Avanza','1.5',2019,'1.5',68000,'AT',47500.00,'Gasoline','RWD',5,'sold'),
(@seller_id,'Toyota','Avanza','1.5',2021,'1.5',42000,'AT',55500.00,'Gasoline','RWD',5,'sold'),
-- Innova (50)
(@seller_id,'Toyota','Innova','2.0',2020,'2.0',56000,'AT',86500.00,'Gasoline','RWD',5,'sold');

SET @base_id2 := LAST_INSERT_ID();

-- 50 thumbnails
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES
(@base_id2+0,'uploads/ativa(1).webp',1),(@base_id2+1,'uploads/ativa(1).webp',1),(@base_id2+2,'uploads/ativa(1).webp',1),(@base_id2+3,'uploads/ativa(1).webp',1),
(@base_id2+4,'uploads/ativa(1).webp',1),(@base_id2+5,'uploads/ativa(1).webp',1),(@base_id2+6,'uploads/ativa(1).webp',1),(@base_id2+7,'uploads/ativa(1).webp',1),
(@base_id2+8,'uploads/ativa(1).webp',1),(@base_id2+9,'uploads/ativa(1).webp',1),(@base_id2+10,'uploads/ativa(1).webp',1),(@base_id2+11,'uploads/ativa(1).webp',1),
(@base_id2+12,'uploads/ativa(1).webp',1),(@base_id2+13,'uploads/ativa(1).webp',1),(@base_id2+14,'uploads/ativa(1).webp',1),(@base_id2+15,'uploads/ativa(1).webp',1),
(@base_id2+16,'uploads/ativa(1).webp',1),(@base_id2+17,'uploads/ativa(1).webp',1),(@base_id2+18,'uploads/ativa(1).webp',1),(@base_id2+19,'uploads/ativa(1).webp',1),
(@base_id2+20,'uploads/ativa(1).webp',1),(@base_id2+21,'uploads/ativa(1).webp',1),(@base_id2+22,'uploads/ativa(1).webp',1),(@base_id2+23,'uploads/ativa(1).webp',1),
(@base_id2+24,'uploads/ativa(1).webp',1),(@base_id2+25,'uploads/ativa(1).webp',1),(@base_id2+26,'uploads/ativa(1).webp',1),(@base_id2+27,'uploads/ativa(1).webp',1),
(@base_id2+28,'uploads/ativa(1).webp',1),(@base_id2+29,'uploads/ativa(1).webp',1),(@base_id2+30,'uploads/ativa(1).webp',1),(@base_id2+31,'uploads/ativa(1).webp',1),
(@base_id2+32,'uploads/ativa(1).webp',1),(@base_id2+33,'uploads/ativa(1).webp',1),(@base_id2+34,'uploads/ativa(1).webp',1),(@base_id2+35,'uploads/ativa(1).webp',1),
(@base_id2+36,'uploads/ativa(1).webp',1),(@base_id2+37,'uploads/ativa(1).webp',1),(@base_id2+38,'uploads/ativa(1).webp',1),(@base_id2+39,'uploads/ativa(1).webp',1),
(@base_id2+40,'uploads/ativa(1).webp',1),(@base_id2+41,'uploads/ativa(1).webp',1),(@base_id2+42,'uploads/ativa(1).webp',1),(@base_id2+43,'uploads/ativa(1).webp',1),
(@base_id2+44,'uploads/ativa(1).webp',1),(@base_id2+45,'uploads/ativa(1).webp',1),(@base_id2+46,'uploads/ativa(1).webp',1),(@base_id2+47,'uploads/ativa(1).webp',1),
(@base_id2+48,'uploads/ativa(1).webp',1),(@base_id2+49,'uploads/ativa(1).webp',1);

-- 50 details
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note)
VALUES
-- 86
(@base_id2+0,'Red',200,'FA20',6,'215/45 R17','215/45 R17',205,'Coupe','Used','Sporty coupe.'),
(@base_id2+1,'White',205,'FA20',6,'215/45 R17','215/45 R17',205,'Coupe','Used','Well maintained.'),
(@base_id2+2,'Black',210,'FA20',6,'215/45 R17','215/45 R17',212,'Coupe','Used','Fun to drive.'),
(@base_id2+3,'Blue',228,'FA24',6,'215/45 R17','215/45 R17',250,'Coupe','Used','Newer 2.4.'),
(@base_id2+4,'Grey',235,'FA24',6,'215/45 R17','215/45 R17',250,'Coupe','Used','Low mileage.'),
-- Vios
(@base_id2+5,'Silver',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Used','Reliable sedan.'),
(@base_id2+6,'White',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Used','Daily runner.'),
(@base_id2+7,'Grey',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Used','Clean unit.'),
(@base_id2+8,'Red',107,'2NR-FE',7,'205/50 R17','205/50 R17',140,'Sedan','Used','GR-S styling.'),
(@base_id2+9,'Blue',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Used','Well kept.'),
(@base_id2+10,'Black',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Used','One owner.'),
(@base_id2+11,'White',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Used','Service on time.'),
(@base_id2+12,'Silver',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Used','Value buy.'),
(@base_id2+13,'Grey',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Used','Comfortable.'),
(@base_id2+14,'White',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Used','Low mileage.'),
-- Supra
(@base_id2+15,'Yellow',335,'B58',8,'255/35 R19','275/35 R19',500,'Coupe','Used','Iconic 3.0.'),
(@base_id2+16,'Red',255,'B48',8,'255/35 R19','275/35 R19',400,'Coupe','Used','Balanced 2.0.'),
-- Corolla
(@base_id2+17,'White',139,'2ZR-FAE',7,'205/55 R16','205/55 R16',173,'Sedan','Used','Comfort sedan.'),
(@base_id2+18,'Silver',139,'2ZR-FAE',7,'205/55 R16','205/55 R16',173,'Sedan','Used','Well kept.'),
(@base_id2+19,'Blue',121,'2ZR-FXE',7,'205/55 R16','205/55 R16',142,'Sedan','Used','Hybrid saver.'),
(@base_id2+20,'Grey',139,'2ZR-FAE',7,'215/45 R17','215/45 R17',173,'Sedan','Used','Facelift.'),
(@base_id2+21,'Black',139,'2ZR-FAE',7,'215/45 R17','215/45 R17',173,'Sedan','Used','Clean interior.'),
(@base_id2+22,'White',121,'2ZR-FXE',7,'215/45 R17','215/45 R17',142,'Sedan','Used','Hybrid new gen.'),
-- Camry
(@base_id2+23,'Silver',165,'6AR-FSE',8,'215/55 R17','215/55 R17',199,'Sedan','Used','Executive ride.'),
(@base_id2+24,'Black',181,'2AR-FE',8,'215/55 R17','215/55 R17',235,'Sedan','Used','Strong 2.5.'),
(@base_id2+25,'White',209,'A25A-FKS',8,'235/45 R18','235/45 R18',250,'Sedan','Used','New platform.'),
(@base_id2+26,'Grey',209,'A25A-FKS',8,'235/45 R18','235/45 R18',250,'Sedan','Used','Nice condition.'),
(@base_id2+27,'Blue',211,'A25A-FXS',8,'235/45 R18','235/45 R18',221,'Sedan','Used','Hybrid flagship.'),
(@base_id2+28,'White',209,'A25A-FKS',8,'235/45 R18','235/45 R18',250,'Sedan','Used','Low mileage.'),
-- Hilux
(@base_id2+29,'White',150,'2GD-FTV',6,'265/65 R17','265/65 R17',400,'Pickup','Used','Workhorse.'),
(@base_id2+30,'Grey',201,'1GD-FTV',6,'265/65 R17','265/65 R17',500,'Pickup','Used','Rogue 2.8.'),
(@base_id2+31,'Silver',150,'2GD-FTV',6,'265/65 R17','265/65 R17',400,'Pickup','Used','Trusted truck.'),
(@base_id2+32,'Black',201,'1GD-FTV',6,'265/65 R17','265/65 R17',500,'Pickup','Used','Powerful 2.8.'),
(@base_id2+33,'White',201,'1GD-FTV',6,'265/65 R17','265/65 R17',500,'Pickup','Used','Reliable.'),
(@base_id2+34,'Blue',150,'2GD-FTV',6,'265/65 R17','265/65 R17',400,'Pickup','Used','Clean unit.'),
-- Yaris
(@base_id2+35,'Yellow',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Hatchback','Used','Zippy city car.'),
(@base_id2+36,'Red',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Hatchback','Used','Clean interior.'),
(@base_id2+37,'Blue',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Hatchback','Used','Well maintained.'),
(@base_id2+38,'White',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Hatchback','Used','Practical hatch.'),
-- Fortuner
(@base_id2+39,'White',150,'2GD-FTV',6,'265/60 R18','265/60 R18',400,'SUV','Used','7-seater 4x4.'),
(@base_id2+40,'Red',201,'1GD-FTV',6,'265/60 R18','265/60 R18',500,'SUV','Used','Top spec.'),
(@base_id2+41,'Grey',201,'1GD-FTV',6,'265/60 R18','265/60 R18',500,'SUV','Used','Updated model.'),
-- RAV4
(@base_id2+42,'White',203,'A25A-FKS',8,'235/55 R19','235/55 R19',243,'SUV','Used','AWD grip.'),
(@base_id2+43,'Blue',219,'A25A-FXS',8,'235/55 R19','235/55 R19',221,'SUV','Used','Hybrid efficient.'),
(@base_id2+44,'Grey',219,'A25A-FXS',8,'235/55 R19','235/55 R19',221,'SUV','Used','New gen.'),
-- Harrier
(@base_id2+45,'Black',231,'8AR-FTS',8,'235/55 R18','235/55 R18',350,'SUV','Used','Turbo smooth.'),
(@base_id2+46,'White',178,'A25A-FXS',8,'235/55 R18','235/55 R18',221,'SUV','Used','Hybrid saver.'),
-- Avanza
(@base_id2+47,'Silver',105,'2NR-VE',4,'185/65 R15','185/65 R15',136,'MPV','Used','Practical MPV.'),
(@base_id2+48,'White',105,'2NR-VE',4,'185/65 R15','185/65 R15',136,'MPV','Used','Family MPV.'),
-- Innova
(@base_id2+49,'Grey',139,'1TR-FE',6,'205/65 R16','205/65 R16',183,'MPV','Used','Comfortable.');

COMMIT;
