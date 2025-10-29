-- Dummy bulk data: 60 Kia cars (Mixed conditions: Reconditioned, Used, New)
-- Models from data/makes_models_my.json
-- All rows use seller_id=5 and listing_status='sold'.
-- Thumbnails reuse 'uploads/ativa(1).webp'.

START TRANSACTION;

SET @seller_id := 5;

-- KIA (60)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES
-- Cerato (0-7) x8
(@seller_id,'Kia','Cerato','1.6',2016,'1.6',78000,'AT',45500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Kia','Cerato','2.0',2017,'2.0',69000,'AT',52500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Kia','Cerato','1.6',2018,'1.6',62000,'AT',56500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Kia','Cerato','2.0 GT-Line',2019,'2.0',54000,'AT',68500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Kia','Cerato','1.6',2020,'1.6',46000,'AT',70500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Kia','Cerato','1.6',2021,'1.6',32000,'AT',74500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Kia','Cerato','2.0 GT-Line',2022,'2.0',21000,'AT',89500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Kia','Cerato','2.0',2023,'2.0',12000,'AT',96500.00,'Gasoline','FWD',4,'sold'),
-- K5/Optima (8-13) x6
(@seller_id,'Kia','K5','2.0',2016,'2.0',78000,'AT',55500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Kia','Optima','2.0',2017,'2.0',72000,'AT',59500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Kia','K5','2.0',2018,'2.0',65000,'AT',65500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Kia','K5','2.5',2020,'2.5',42000,'AT',108500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Kia','K5','2.5',2021,'2.5',30000,'AT',118500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Kia','K5','2.5',2022,'2.5',21000,'AT',128500.00,'Gasoline','FWD',4,'sold'),
-- Sportage (14-21) x8
(@seller_id,'Kia','Sportage','2.0',2017,'2.0',71000,'AT',68500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Kia','Sportage','2.0',2018,'2.0',64000,'AT',74500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Kia','Sportage','2.0 AWD',2019,'2.0',56000,'AT',82500.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Kia','Sportage','1.6T',2020,'1.6',45000,'DCT',91500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Kia','Sportage','1.6T AWD',2021,'1.6',34000,'DCT',99500.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Kia','Sportage','2.0',2021,'2.0',33000,'AT',96500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Kia','Sportage','1.6T',2022,'1.6',23000,'DCT',108500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Kia','Sportage','1.6T',2023,'1.6',12000,'DCT',118500.00,'Gasoline','FWD',5,'sold'),
-- Sorento (22-27) x6
(@seller_id,'Kia','Sorento','2.4',2017,'2.4',74000,'AT',85500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Kia','Sorento','2.2 CRDi',2018,'2.2',66000,'AT',98500.00,'Diesel','AWD',5,'sold'),
(@seller_id,'Kia','Sorento','2.2 CRDi',2019,'2.2',56000,'AT',112500.00,'Diesel','AWD',5,'sold'),
(@seller_id,'Kia','Sorento','2.5',2020,'2.5',43000,'AT',126500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Kia','Sorento','2.2 CRDi',2021,'2.2',32000,'AT',138500.00,'Diesel','AWD',5,'sold'),
(@seller_id,'Kia','Sorento','2.2 CRDi',2022,'2.2',21000,'AT',148500.00,'Diesel','AWD',5,'sold'),
-- Carnival (28-33) x6
(@seller_id,'Kia','Carnival','2.2 CRDi',2019,'2.2',56000,'AT',118500.00,'Diesel','FWD',5,'sold'),
(@seller_id,'Kia','Carnival','2.2 CRDi',2020,'2.2',49000,'AT',126500.00,'Diesel','FWD',5,'sold'),
(@seller_id,'Kia','Carnival','2.2 CRDi',2021,'2.2',36000,'AT',148500.00,'Diesel','FWD',5,'sold'),
(@seller_id,'Kia','Carnival','2.2 CRDi',2022,'2.2',24000,'AT',168500.00,'Diesel','FWD',5,'sold'),
(@seller_id,'Kia','Carnival','2.2 CRDi',2023,'2.2',16000,'AT',178500.00,'Diesel','FWD',5,'sold'),
(@seller_id,'Kia','Carnival','2.2 CRDi',2023,'2.2',14000,'AT',188500.00,'Diesel','FWD',5,'sold'),
-- Seltos (34-39) x6
(@seller_id,'Kia','Seltos','1.6',2020,'1.6',43000,'AT',88500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Kia','Seltos','1.6',2021,'1.6',33000,'AT',95500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Kia','Seltos','1.6',2021,'1.6',31000,'AT',98500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Kia','Seltos','1.6',2022,'1.6',23000,'AT',102500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Kia','Seltos','1.6',2022,'1.6',21000,'AT',108500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Kia','Seltos','1.6',2023,'1.6',12000,'AT',118500.00,'Gasoline','FWD',5,'sold'),
-- Rio (40-45) x6
(@seller_id,'Kia','Rio','1.4',2016,'1.4',82000,'AT',32500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Kia','Rio','1.4',2017,'1.4',76000,'AT',35500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Kia','Rio','1.4',2018,'1.4',69000,'AT',38500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Kia','Rio','1.4',2019,'1.4',62000,'AT',41500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Kia','Rio','1.4',2020,'1.4',54000,'AT',44500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Kia','Rio','1.4',2021,'1.4',46000,'AT',47500.00,'Gasoline','FWD',4,'sold'),
-- Picanto (46-51) x6
(@seller_id,'Kia','Picanto','1.2',2016,'1.2',78000,'AT',24500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Kia','Picanto','1.2',2017,'1.2',72000,'AT',26500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Kia','Picanto','1.2',2018,'1.2',65000,'AT',29500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Kia','Picanto','1.2',2019,'1.2',58000,'AT',32500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Kia','Picanto','1.2',2020,'1.2',52000,'AT',35500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Kia','Picanto','1.2',2021,'1.2',46000,'AT',38500.00,'Gasoline','FWD',5,'sold'),
-- EV6 (52-59) x8 EV
(@seller_id,'Kia','EV6','RWD',2022,'0.0',14000,'AT',305000.00,'Electric','RWD',5,'sold'),
(@seller_id,'Kia','EV6','AWD',2022,'0.0',12000,'AT',325000.00,'Electric','AWD',5,'sold'),
(@seller_id,'Kia','EV6','RWD',2023,'0.0',9000,'AT',335000.00,'Electric','RWD',5,'sold'),
(@seller_id,'Kia','EV6','AWD',2023,'0.0',8000,'AT',355000.00,'Electric','AWD',5,'sold'),
(@seller_id,'Kia','EV6','RWD',2024,'0.0',6000,'AT',365000.00,'Electric','RWD',5,'sold'),
(@seller_id,'Kia','EV6','AWD',2024,'0.0',5000,'AT',385000.00,'Electric','AWD',5,'sold'),
(@seller_id,'Kia','EV6','GT AWD',2023,'0.0',7000,'AT',425000.00,'Electric','AWD',5,'sold'),
(@seller_id,'Kia','EV6','GT AWD',2024,'0.0',4000,'AT',445000.00,'Electric','AWD',5,'sold');

SET @base_kia := LAST_INSERT_ID();

-- 60 thumbnails
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES
(@base_kia+0,'uploads/ativa(1).webp',1),(@base_kia+1,'uploads/ativa(1).webp',1),(@base_kia+2,'uploads/ativa(1).webp',1),(@base_kia+3,'uploads/ativa(1).webp',1),
(@base_kia+4,'uploads/ativa(1).webp',1),(@base_kia+5,'uploads/ativa(1).webp',1),(@base_kia+6,'uploads/ativa(1).webp',1),(@base_kia+7,'uploads/ativa(1).webp',1),
(@base_kia+8,'uploads/ativa(1).webp',1),(@base_kia+9,'uploads/ativa(1).webp',1),(@base_kia+10,'uploads/ativa(1).webp',1),(@base_kia+11,'uploads/ativa(1).webp',1),
(@base_kia+12,'uploads/ativa(1).webp',1),(@base_kia+13,'uploads/ativa(1).webp',1),(@base_kia+14,'uploads/ativa(1).webp',1),(@base_kia+15,'uploads/ativa(1).webp',1),
(@base_kia+16,'uploads/ativa(1).webp',1),(@base_kia+17,'uploads/ativa(1).webp',1),(@base_kia+18,'uploads/ativa(1).webp',1),(@base_kia+19,'uploads/ativa(1).webp',1),
(@base_kia+20,'uploads/ativa(1).webp',1),(@base_kia+21,'uploads/ativa(1).webp',1),(@base_kia+22,'uploads/ativa(1).webp',1),(@base_kia+23,'uploads/ativa(1).webp',1),
(@base_kia+24,'uploads/ativa(1).webp',1),(@base_kia+25,'uploads/ativa(1).webp',1),(@base_kia+26,'uploads/ativa(1).webp',1),(@base_kia+27,'uploads/ativa(1).webp',1),
(@base_kia+28,'uploads/ativa(1).webp',1),(@base_kia+29,'uploads/ativa(1).webp',1),(@base_kia+30,'uploads/ativa(1).webp',1),(@base_kia+31,'uploads/ativa(1).webp',1),
(@base_kia+32,'uploads/ativa(1).webp',1),(@base_kia+33,'uploads/ativa(1).webp',1),(@base_kia+34,'uploads/ativa(1).webp',1),(@base_kia+35,'uploads/ativa(1).webp',1),
(@base_kia+36,'uploads/ativa(1).webp',1),(@base_kia+37,'uploads/ativa(1).webp',1),(@base_kia+38,'uploads/ativa(1).webp',1),(@base_kia+39,'uploads/ativa(1).webp',1),
(@base_kia+40,'uploads/ativa(1).webp',1),(@base_kia+41,'uploads/ativa(1).webp',1),(@base_kia+42,'uploads/ativa(1).webp',1),(@base_kia+43,'uploads/ativa(1).webp',1),
(@base_kia+44,'uploads/ativa(1).webp',1),(@base_kia+45,'uploads/ativa(1).webp',1),(@base_kia+46,'uploads/ativa(1).webp',1),(@base_kia+47,'uploads/ativa(1).webp',1),
(@base_kia+48,'uploads/ativa(1).webp',1),(@base_kia+49,'uploads/ativa(1).webp',1),(@base_kia+50,'uploads/ativa(1).webp',1),(@base_kia+51,'uploads/ativa(1).webp',1),
(@base_kia+52,'uploads/ativa(1).webp',1),(@base_kia+53,'uploads/ativa(1).webp',1),(@base_kia+54,'uploads/ativa(1).webp',1),(@base_kia+55,'uploads/ativa(1).webp',1),
(@base_kia+56,'uploads/ativa(1).webp',1),(@base_kia+57,'uploads/ativa(1).webp',1),(@base_kia+58,'uploads/ativa(1).webp',1),(@base_kia+59,'uploads/ativa(1).webp',1);

-- 60 details with mixed car_condition
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note)
VALUES
-- Cerato (0-7)
(@base_kia+0,'White',128,'Gamma',6,'205/55 R16','205/55 R16',157,'Sedan','Used','Well kept.'),
(@base_kia+1,'Grey',161,'Nu',6,'215/50 R17','215/50 R17',194,'Sedan','Reconditioned','Clean recond.'),
(@base_kia+2,'Blue',128,'Gamma',6,'205/55 R16','205/55 R16',157,'Sedan','Used','Service on time.'),
(@base_kia+3,'Red',161,'Nu',6,'225/45 R18','225/45 R18',194,'Sedan','Reconditioned','GT-Line recond.'),
(@base_kia+4,'White',128,'Gamma',6,'205/55 R16','205/55 R16',157,'Sedan','Used','Low mileage.'),
(@base_kia+5,'Black',128,'Gamma',6,'205/55 R16','205/55 R16',157,'Sedan','Reconditioned','Well maintained.'),
(@base_kia+6,'Silver',161,'Nu',6,'215/50 R17','215/50 R17',194,'Sedan','New','Brand new MY unit.'),
(@base_kia+7,'Grey',161,'Nu',6,'215/50 R17','215/50 R17',194,'Sedan','New','Unregistered new.'),
-- K5/Optima (8-13)
(@base_kia+8,'White',152,'Nu',6,'215/55 R17','215/55 R17',192,'Sedan','Used','Comfort sedan.'),
(@base_kia+9,'Black',152,'Nu',6,'215/55 R17','215/55 R17',192,'Sedan','Reconditioned','Optima recond.'),
(@base_kia+10,'Silver',152,'Nu',6,'215/55 R17','215/55 R17',192,'Sedan','Used','Value buy.'),
(@base_kia+11,'Grey',191,'Smartstream',8,'235/45 R18','235/45 R18',245,'Sedan','Reconditioned','2.5 recond.'),
(@base_kia+12,'Blue',191,'Smartstream',8,'235/45 R18','235/45 R18',245,'Sedan','Used','Low mileage.'),
(@base_kia+13,'White',191,'Smartstream',8,'235/45 R18','235/45 R18',245,'Sedan','New','Unregistered new.'),
-- Sportage (14-21)
(@base_kia+14,'Silver',155,'Nu',6,'225/60 R17','225/60 R17',192,'SUV','Used','Family SUV.'),
(@base_kia+15,'White',155,'Nu',6,'225/60 R17','225/60 R17',192,'SUV','Reconditioned','Well kept.'),
(@base_kia+16,'Grey',155,'Nu',6,'225/60 R17','225/60 R17',192,'SUV','Used','Clean unit.'),
(@base_kia+17,'Blue',177,'Gamma 1.6T',7,'225/55 R18','225/55 R18',265,'SUV','Reconditioned','AWD recond.'),
(@base_kia+18,'Black',177,'Gamma 1.6T',7,'225/55 R18','225/55 R18',265,'SUV','Used','Low mileage.'),
(@base_kia+19,'White',155,'Nu',6,'225/60 R17','225/60 R17',192,'SUV','Reconditioned','Facelift recond.'),
(@base_kia+20,'Grey',177,'Gamma 1.6T',7,'225/55 R18','225/55 R18',265,'SUV','New','Brand new MY unit.'),
(@base_kia+21,'Blue',177,'Gamma 1.6T',7,'225/55 R18','225/55 R18',265,'SUV','New','Unregistered new.'),
-- Sorento (22-27)
(@base_kia+22,'White',185,'Theta II',6,'235/60 R18','235/60 R18',241,'SUV','Used','7-seater.'),
(@base_kia+23,'Grey',199,'R2.2',8,'235/60 R18','235/60 R18',440,'SUV','Reconditioned','Diesel AWD recond.'),
(@base_kia+24,'Black',199,'R2.2',8,'235/55 R19','235/55 R19',440,'SUV','Used','Powerful torque.'),
(@base_kia+25,'Blue',191,'Smartstream',8,'235/55 R19','235/55 R19',245,'SUV','Reconditioned','Facelift recond.'),
(@base_kia+26,'White',199,'R2.2',8,'235/55 R19','235/55 R19',440,'SUV','Used','Low mileage.'),
(@base_kia+27,'Grey',199,'R2.2',8,'235/55 R19','235/55 R19',440,'SUV','New','Unregistered new.'),
-- Carnival (28-33)
(@base_kia+28,'White',199,'R2.2',8,'235/60 R18','235/60 R18',440,'MPV','Used','Spacious MPV.'),
(@base_kia+29,'Grey',199,'R2.2',8,'235/60 R18','235/60 R18',440,'MPV','Reconditioned','Well kept recond.'),
(@base_kia+30,'Black',199,'R2.2',8,'235/60 R18','235/60 R18',440,'MPV','Used','Low mileage.'),
(@base_kia+31,'Blue',199,'R2.2',8,'235/60 R18','235/60 R18',440,'MPV','Reconditioned','Clean interior.'),
(@base_kia+32,'White',199,'R2.2',8,'235/60 R18','235/60 R18',440,'MPV','New','Brand new MY unit.'),
(@base_kia+33,'Grey',199,'R2.2',8,'235/60 R18','235/60 R18',440,'MPV','New','Unregistered new.'),
-- Seltos (34-39)
(@base_kia+34,'White',121,'Gamma',6,'215/60 R17','215/60 R17',151,'SUV','Used','City SUV.'),
(@base_kia+35,'Grey',121,'Gamma',6,'215/60 R17','215/60 R17',151,'SUV','Reconditioned','Well maintained.'),
(@base_kia+36,'Blue',121,'Gamma',6,'215/60 R17','215/60 R17',151,'SUV','Used','Clean unit.'),
(@base_kia+37,'Red',121,'Gamma',6,'215/60 R17','215/60 R17',151,'SUV','Reconditioned','Newer batch recond.'),
(@base_kia+38,'White',121,'Gamma',6,'215/60 R17','215/60 R17',151,'SUV','New','Brand new MY unit.'),
(@base_kia+39,'Grey',121,'Gamma',6,'215/60 R17','215/60 R17',151,'SUV','New','Unregistered new.'),
-- Rio (40-45)
(@base_kia+40,'Red',100,'Kappa',6,'185/65 R15','185/65 R15',132,'Sedan','Used','Daily runner.'),
(@base_kia+41,'White',100,'Kappa',6,'185/65 R15','185/65 R15',132,'Sedan','Reconditioned','Clean recond.'),
(@base_kia+42,'Grey',100,'Kappa',6,'185/65 R15','185/65 R15',132,'Sedan','Used','Low mileage.'),
(@base_kia+43,'Blue',100,'Kappa',6,'185/65 R15','185/65 R15',132,'Sedan','Reconditioned','Well kept.'),
(@base_kia+44,'White',100,'Kappa',6,'185/65 R15','185/65 R15',132,'Sedan','New','Brand new MY unit.'),
(@base_kia+45,'Grey',100,'Kappa',6,'185/65 R15','185/65 R15',132,'Sedan','New','Unregistered new.'),
-- Picanto (46-51)
(@base_kia+46,'Yellow',83,'Kappa',4,'175/65 R14','175/65 R14',122,'Hatchback','Used','Zippy city car.'),
(@base_kia+47,'White',83,'Kappa',4,'175/65 R14','175/65 R14',122,'Hatchback','Reconditioned','Clean recond.'),
(@base_kia+48,'Blue',83,'Kappa',4,'175/65 R14','175/65 R14',122,'Hatchback','Used','Low mileage.'),
(@base_kia+49,'Red',83,'Kappa',4,'175/65 R14','175/65 R14',122,'Hatchback','Reconditioned','Well kept.'),
(@base_kia+50,'White',83,'Kappa',4,'175/65 R14','175/65 R14',122,'Hatchback','New','Brand new MY unit.'),
(@base_kia+51,'Grey',83,'Kappa',4,'175/65 R14','175/65 R14',122,'Hatchback','New','Unregistered new.'),
-- EV6 (52-59)
(@base_kia+52,'White',229,'e-Drive',1,'235/55 R19','235/55 R19',350,'SUV','Used','Ioniq drivetrain equivalent.'),
(@base_kia+53,'Grey',321,'e-Drive',1,'255/45 R20','255/45 R20',605,'SUV','Reconditioned','AWD recond.'),
(@base_kia+54,'Blue',229,'e-Drive',1,'235/55 R19','235/55 R19',350,'SUV','Used','Clean unit.'),
(@base_kia+55,'Black',321,'e-Drive',1,'255/45 R20','255/45 R20',605,'SUV','Reconditioned','GT AWD recond.'),
(@base_kia+56,'White',229,'e-Drive',1,'235/55 R19','235/55 R19',350,'SUV','New','Brand new MY unit.'),
(@base_kia+57,'Grey',321,'e-Drive',1,'255/45 R20','255/45 R20',605,'SUV','New','Unregistered new.'),
(@base_kia+58,'Blue',576,'e-Drive',1,'255/40 R21','255/40 R21',740,'SUV','New','GT AWD new.'),
(@base_kia+59,'Black',576,'e-Drive',1,'255/40 R21','255/40 R21',740,'SUV','New','GT AWD new.');

COMMIT;
