-- Dummy bulk data: 60 Toyota cars (Reconditioned/New)
-- Based on dummy_sold_cars_toyota.sql but car_condition is Reconditioned or New (no "Used").
-- All rows use seller_id=5 and listing_status='sold'.
-- Models strictly from data/makes_models_my.json used previously in Toyota set.
-- Images reuse 'uploads/ativa(1).webp' as a thumbnail placeholder.

START TRANSACTION;

SET @seller_id := 5;  -- keep consistent seller

-- TOYOTA (60)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES
-- 86 (0-4)
(@seller_id,'Toyota','86','2.0',2017,'2.0',65000,'AT',128000.00,'Gasoline','RWD',2,'sold'),
(@seller_id,'Toyota','86','2.0',2018,'2.0',58000,'AT',138000.00,'Gasoline','RWD',2,'sold'),
(@seller_id,'Toyota','86','2.0',2019,'2.0',52000,'AT',148000.00,'Gasoline','RWD',2,'sold'),
(@seller_id,'Toyota','86','2.4',2020,'2.4',42000,'AT',165000.00,'Gasoline','RWD',2,'sold'),
(@seller_id,'Toyota','86','2.4',2021,'2.4',28000,'AT',178000.00,'Gasoline','RWD',2,'sold'),
-- Vios (5-16) x12
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
(@seller_id,'Toyota','Vios','1.5',2023,'1.5',9000,'CVT',78500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Vios','1.5',2023,'1.5',6000,'CVT',79500.00,'Gasoline','FWD',4,'sold'),
-- Supra (17-18)
(@seller_id,'Toyota','Supra','3.0',2020,'3.0',32000,'AT',298000.00,'Gasoline','RWD',2,'sold'),
(@seller_id,'Toyota','Supra','2.0 Turbo',2021,'2.0',24000,'AT',268000.00,'Gasoline','RWD',2,'sold'),
-- Corolla (19-26) x8
(@seller_id,'Toyota','Corolla','1.8',2017,'1.8',69000,'CVT',62500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Corolla','1.8',2018,'1.8',61000,'CVT',70500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Corolla','1.8 Hybrid',2019,'1.8',52000,'CVT',90500.00,'Hybrid','FWD',4,'sold'),
(@seller_id,'Toyota','Corolla','1.8',2020,'1.8',42000,'CVT',98500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Corolla','1.8',2021,'1.8',31000,'CVT',109500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Corolla','1.8 Hybrid',2022,'1.8',21000,'CVT',121500.00,'Hybrid','FWD',4,'sold'),
(@seller_id,'Toyota','Corolla','1.8',2022,'1.8',19000,'CVT',118500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Corolla','1.8',2023,'1.8',9000,'CVT',128500.00,'Gasoline','FWD',4,'sold'),
-- Camry (27-32) x6
(@seller_id,'Toyota','Camry','2.0',2016,'2.0',84000,'AT',80500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Camry','2.5',2017,'2.5',76000,'AT',89500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Camry','2.5',2018,'2.5',68000,'AT',112500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Camry','2.5',2019,'2.5',56000,'AT',127500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Camry','2.5',2020,'2.5',43000,'AT',146500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Toyota','Camry','2.5 Hybrid',2021,'2.5',29000,'AT',176500.00,'Hybrid','FWD',4,'sold'),
-- Hilux (33-40) x8
(@seller_id,'Toyota','Hilux','2.4G',2017,'2.4',92000,'AT',75500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Toyota','Hilux','2.8 Rogue',2018,'2.8',78000,'AT',98500.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Toyota','Hilux','2.4G',2019,'2.4',66000,'AT',90500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Toyota','Hilux','2.8 Rogue',2020,'2.8',54000,'AT',124000.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Toyota','Hilux','2.4G',2021,'2.4',42000,'AT',116000.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Toyota','Hilux','2.8 Rogue',2022,'2.8',31000,'AT',142000.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Toyota','Hilux','2.4G',2022,'2.4',29000,'AT',126000.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Toyota','Hilux','2.8 Rogue',2023,'2.8',21000,'AT',152000.00,'Diesel','4WD',4,'sold'),
-- Yaris (41-46) x6
(@seller_id,'Toyota','Yaris','1.5',2017,'1.5',69000,'CVT',45500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Toyota','Yaris','1.5',2018,'1.5',61000,'CVT',49500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Toyota','Yaris','1.5',2019,'1.5',52000,'CVT',53500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Toyota','Yaris','1.5',2021,'1.5',26000,'CVT',63500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Toyota','Yaris','1.5',2022,'1.5',18000,'CVT',68500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Toyota','Yaris','1.5',2023,'1.5',9000,'CVT',72500.00,'Gasoline','FWD',5,'sold'),
-- Fortuner (47-50) x4
(@seller_id,'Toyota','Fortuner','2.4 VRZ',2018,'2.4',78000,'AT',132000.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Toyota','Fortuner','2.8 VRZ',2020,'2.8',46000,'AT',168500.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Toyota','Fortuner','2.8 VRZ',2022,'2.8',23000,'AT',206500.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Toyota','Fortuner','2.8 VRZ',2023,'2.8',15000,'AT',218500.00,'Diesel','4WD',5,'sold'),
-- RAV4 (51-54) x4
(@seller_id,'Toyota','RAV4','2.5',2019,'2.5',56000,'AT',145000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Toyota','RAV4','2.5 Hybrid',2020,'2.5',42000,'AT',196000.00,'Hybrid','AWD',5,'sold'),
(@seller_id,'Toyota','RAV4','2.5 Hybrid',2021,'2.5',30000,'AT',216000.00,'Hybrid','AWD',5,'sold'),
(@seller_id,'Toyota','RAV4','2.5 Hybrid',2022,'2.5',21000,'AT',228000.00,'Hybrid','AWD',5,'sold'),
-- Harrier (55-56) x2
(@seller_id,'Toyota','Harrier','2.0 Turbo',2019,'2.0',58000,'AT',142000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Toyota','Harrier','2.5 Hybrid',2021,'2.5',29000,'AT',203000.00,'Hybrid','FWD',5,'sold'),
-- Avanza (57-58) x2
(@seller_id,'Toyota','Avanza','1.5',2019,'1.5',68000,'AT',47500.00,'Gasoline','RWD',5,'sold'),
(@seller_id,'Toyota','Avanza','1.5',2021,'1.5',42000,'AT',55500.00,'Gasoline','RWD',5,'sold'),
-- Innova (59) x1
(@seller_id,'Toyota','Innova','2.0',2020,'2.0',56000,'AT',86500.00,'Gasoline','RWD',5,'sold');

SET @base_id := LAST_INSERT_ID();

-- 60 thumbnails
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
(@base_id+56,'uploads/ativa(1).webp',1),(@base_id+57,'uploads/ativa(1).webp',1),(@base_id+58,'uploads/ativa(1).webp',1),(@base_id+59,'uploads/ativa(1).webp',1);

-- 60 details with car_condition = 'Reconditioned' or 'New'
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note)
VALUES
-- 86 (0-4)
(@base_id+0,'Red',200,'FA20',6,'215/45 R17','215/45 R17',205,'Coupe','Reconditioned','Recond unit, sporty coupe.'),
(@base_id+1,'White',205,'FA20',6,'215/45 R17','215/45 R17',205,'Coupe','Reconditioned','Well maintained recond.'),
(@base_id+2,'Black',210,'FA20',6,'215/45 R17','215/45 R17',212,'Coupe','Reconditioned','Fun to drive recond.'),
(@base_id+3,'Blue',228,'FA24',6,'215/45 R17','215/45 R17',250,'Coupe','Reconditioned','Newer 2.4 recond.'),
(@base_id+4,'Grey',235,'FA24',6,'215/45 R17','215/45 R17',250,'Coupe','New','Brand new/unregistered.'),
-- Vios (5-16)
(@base_id+5,'Silver',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Reconditioned','Reliable recond sedan.'),
(@base_id+6,'White',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Reconditioned','Daily runner recond.'),
(@base_id+7,'Grey',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Reconditioned','Clean recond unit.'),
(@base_id+8,'Red',107,'2NR-FE',7,'205/50 R17','205/50 R17',140,'Sedan','Reconditioned','GR-S styling recond.'),
(@base_id+9,'Blue',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Reconditioned','Well kept recond.'),
(@base_id+10,'Black',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Reconditioned','One owner recond.'),
(@base_id+11,'White',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Reconditioned','Service on time recond.'),
(@base_id+12,'Silver',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Reconditioned','Value buy recond.'),
(@base_id+13,'Grey',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Reconditioned','Comfortable recond.'),
(@base_id+14,'White',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','Reconditioned','Low mileage recond.'),
(@base_id+15,'Yellow',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','New','Brand new MY unit.'),
(@base_id+16,'Black',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Sedan','New','Unregistered new.'),
-- Supra (17-18)
(@base_id+17,'Yellow',335,'B58',8,'255/35 R19','275/35 R19',500,'Coupe','Reconditioned','Iconic 3.0 recond.'),
(@base_id+18,'Red',255,'B48',8,'255/35 R19','275/35 R19',400,'Coupe','Reconditioned','Balanced 2.0 recond.'),
-- Corolla (19-26)
(@base_id+19,'White',139,'2ZR-FAE',7,'205/55 R16','205/55 R16',173,'Sedan','Reconditioned','Comfort sedan recond.'),
(@base_id+20,'Silver',139,'2ZR-FAE',7,'205/55 R16','205/55 R16',173,'Sedan','Reconditioned','Well kept recond.'),
(@base_id+21,'Blue',121,'2ZR-FXE',7,'205/55 R16','205/55 R16',142,'Sedan','Reconditioned','Hybrid saver recond.'),
(@base_id+22,'Grey',139,'2ZR-FAE',7,'215/45 R17','215/45 R17',173,'Sedan','Reconditioned','Facelift recond.'),
(@base_id+23,'Black',139,'2ZR-FAE',7,'215/45 R17','215/45 R17',173,'Sedan','Reconditioned','Clean interior recond.'),
(@base_id+24,'White',121,'2ZR-FXE',7,'215/45 R17','215/45 R17',142,'Sedan','Reconditioned','Hybrid new gen recond.'),
(@base_id+25,'Silver',139,'2ZR-FAE',7,'205/55 R16','205/55 R16',173,'Sedan','New','Brand new MY unit.'),
(@base_id+26,'Grey',139,'2ZR-FAE',7,'205/55 R16','205/55 R16',173,'Sedan','New','Unregistered new.'),
-- Camry (27-32)
(@base_id+27,'Silver',165,'6AR-FSE',8,'215/55 R17','215/55 R17',199,'Sedan','Reconditioned','Executive ride recond.'),
(@base_id+28,'Black',181,'2AR-FE',8,'215/55 R17','215/55 R17',235,'Sedan','Reconditioned','Strong 2.5 recond.'),
(@base_id+29,'White',209,'A25A-FKS',8,'235/45 R18','235/45 R18',250,'Sedan','Reconditioned','New platform recond.'),
(@base_id+30,'Grey',209,'A25A-FKS',8,'235/45 R18','235/45 R18',250,'Sedan','Reconditioned','Nice condition recond.'),
(@base_id+31,'Blue',211,'A25A-FXS',8,'235/45 R18','235/45 R18',221,'Sedan','Reconditioned','Hybrid flagship recond.'),
(@base_id+32,'White',209,'A25A-FKS',8,'235/45 R18','235/45 R18',250,'Sedan','New','Brand new MY unit.'),
-- Hilux (33-40)
(@base_id+33,'White',150,'2GD-FTV',6,'265/65 R17','265/65 R17',400,'Pickup','Reconditioned','Workhorse recond.'),
(@base_id+34,'Grey',201,'1GD-FTV',6,'265/65 R17','265/65 R17',500,'Pickup','Reconditioned','Rogue 2.8 recond.'),
(@base_id+35,'Silver',150,'2GD-FTV',6,'265/65 R17','265/65 R17',400,'Pickup','Reconditioned','Trusted truck recond.'),
(@base_id+36,'Black',201,'1GD-FTV',6,'265/65 R17','265/65 R17',500,'Pickup','Reconditioned','Powerful 2.8 recond.'),
(@base_id+37,'White',201,'1GD-FTV',6,'265/65 R17','265/65 R17',500,'Pickup','Reconditioned','Reliable recond.'),
(@base_id+38,'Blue',150,'2GD-FTV',6,'265/65 R17','265/65 R17',400,'Pickup','Reconditioned','Clean unit recond.'),
(@base_id+39,'Silver',150,'2GD-FTV',6,'265/65 R17','265/65 R17',400,'Pickup','New','Brand new MY unit.'),
(@base_id+40,'Black',201,'1GD-FTV',6,'265/65 R17','265/65 R17',500,'Pickup','New','Unregistered new.'),
-- Yaris (41-46)
(@base_id+41,'Yellow',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Hatchback','Reconditioned','Zippy city recond.'),
(@base_id+42,'Red',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Hatchback','Reconditioned','Clean interior recond.'),
(@base_id+43,'Blue',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Hatchback','Reconditioned','Well maintained recond.'),
(@base_id+44,'White',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Hatchback','Reconditioned','Practical hatch recond.'),
(@base_id+45,'Silver',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Hatchback','New','Brand new MY unit.'),
(@base_id+46,'Grey',107,'2NR-FE',7,'185/60 R15','185/60 R15',140,'Hatchback','New','Unregistered new.'),
-- Fortuner (47-50)
(@base_id+47,'White',150,'2GD-FTV',6,'265/60 R18','265/60 R18',400,'SUV','Reconditioned','7-seater 4x4 recond.'),
(@base_id+48,'Red',201,'1GD-FTV',6,'265/60 R18','265/60 R18',500,'SUV','Reconditioned','Top spec recond.'),
(@base_id+49,'Grey',201,'1GD-FTV',6,'265/60 R18','265/60 R18',500,'SUV','Reconditioned','Updated model recond.'),
(@base_id+50,'Black',201,'1GD-FTV',6,'265/60 R18','265/60 R18',500,'SUV','New','Unregistered new.'),
-- RAV4 (51-54)
(@base_id+51,'White',203,'A25A-FKS',8,'235/55 R19','235/55 R19',243,'SUV','Reconditioned','AWD grip recond.'),
(@base_id+52,'Blue',219,'A25A-FXS',8,'235/55 R19','235/55 R19',221,'SUV','Reconditioned','Hybrid efficient recond.'),
(@base_id+53,'Grey',219,'A25A-FXS',8,'235/55 R19','235/55 R19',221,'SUV','Reconditioned','New gen recond.'),
(@base_id+54,'White',219,'A25A-FXS',8,'235/55 R19','235/55 R19',221,'SUV','New','Brand new MY unit.'),
-- Harrier (55-56)
(@base_id+55,'Black',231,'8AR-FTS',8,'235/55 R18','235/55 R18',350,'SUV','Reconditioned','Turbo smooth recond.'),
(@base_id+56,'White',178,'A25A-FXS',8,'235/55 R18','235/55 R18',221,'SUV','New','Hybrid saver new.'),
-- Avanza (57-58)
(@base_id+57,'Silver',105,'2NR-VE',4,'185/65 R15','185/65 R15',136,'MPV','Reconditioned','Practical MPV recond.'),
(@base_id+58,'White',105,'2NR-VE',4,'185/65 R15','185/65 R15',136,'MPV','New','Brand new MY unit.'),
-- Innova (59)
(@base_id+59,'Grey',139,'1TR-FE',6,'205/65 R16','205/65 R16',183,'MPV','New','Unregistered new.');

COMMIT;
