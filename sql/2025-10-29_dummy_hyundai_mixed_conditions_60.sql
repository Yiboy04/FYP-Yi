-- Dummy bulk data: 60 Hyundai cars (Mixed conditions: Reconditioned, Used, New)
-- Models from data/makes_models_my.json
-- All rows use seller_id=5 and listing_status='sold'.
-- Thumbnails reuse 'uploads/ativa(1).webp'.

START TRANSACTION;

SET @seller_id := 5;  -- consistent seller

-- HYUNDAI (60)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES
-- Elantra (0-7) x8
(@seller_id,'Hyundai','Elantra','1.6',2016,'1.6',75000,'AT',45500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Hyundai','Elantra','2.0',2017,'2.0',68000,'AT',50500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Hyundai','Elantra','1.6 Premium',2018,'1.6',61000,'AT',55500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Hyundai','Elantra','Sport 1.6T',2019,'1.6',52000,'DCT',62500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Hyundai','Elantra','2.0',2020,'2.0',43000,'AT',68500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Hyundai','Elantra','1.6 Premium',2021,'1.6',32000,'AT',72500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Hyundai','Elantra','1.6',2022,'1.6',21000,'AT',75500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Hyundai','Elantra','2.0',2023,'2.0',12000,'AT',88500.00,'Gasoline','FWD',4,'sold'),
-- Sonata (8-13) x6
(@seller_id,'Hyundai','Sonata','2.0',2016,'2.0',78000,'AT',56500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Hyundai','Sonata','2.4',2017,'2.4',71000,'AT',61500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Hyundai','Sonata','2.0 Premium',2018,'2.0',59000,'AT',67500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Hyundai','Sonata','2.5',2020,'2.5',42000,'AT',98500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Hyundai','Sonata','2.5',2021,'2.5',30000,'AT',108500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Hyundai','Sonata','2.5',2022,'2.5',19000,'AT',118500.00,'Gasoline','FWD',4,'sold'),
-- Tucson (14-21) x8
(@seller_id,'Hyundai','Tucson','2.0',2017,'2.0',69000,'AT',68500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Hyundai','Tucson','1.6T',2018,'1.6',61000,'DCT',76500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Hyundai','Tucson','1.6T AWD',2019,'1.6',52000,'DCT',89500.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Hyundai','Tucson','2.0',2020,'2.0',43000,'AT',90500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Hyundai','Tucson','1.6T',2021,'1.6',32000,'DCT',98500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Hyundai','Tucson','1.6T AWD',2022,'1.6',21000,'DCT',108500.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Hyundai','Tucson','2.0',2022,'2.0',19000,'AT',102500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Hyundai','Tucson','1.6T',2023,'1.6',11000,'DCT',118500.00,'Gasoline','FWD',5,'sold'),
-- Santa Fe (22-27) x6
(@seller_id,'Hyundai','Santa Fe','2.4',2017,'2.4',72000,'AT',88500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Hyundai','Santa Fe','2.2 CRDi',2018,'2.2',65000,'AT',95500.00,'Diesel','AWD',5,'sold'),
(@seller_id,'Hyundai','Santa Fe','2.2 CRDi',2019,'2.2',56000,'AT',112500.00,'Diesel','AWD',5,'sold'),
(@seller_id,'Hyundai','Santa Fe','2.4',2020,'2.4',43000,'AT',118500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Hyundai','Santa Fe','2.2 CRDi',2021,'2.2',32000,'AT',136500.00,'Diesel','AWD',5,'sold'),
(@seller_id,'Hyundai','Santa Fe','2.2 CRDi',2022,'2.2',21000,'AT',148500.00,'Diesel','AWD',5,'sold'),
-- Kona (28-33) x6
(@seller_id,'Hyundai','Kona','1.6T',2019,'1.6',52000,'DCT',87500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Hyundai','Kona','1.6T',2020,'1.6',43000,'DCT',92500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Hyundai','Kona','1.6T',2021,'1.6',32000,'DCT',98500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Hyundai','Kona','1.6T AWD',2021,'1.6',30000,'DCT',106500.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Hyundai','Kona','1.6T',2022,'1.6',21000,'DCT',108500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Hyundai','Kona','1.6T',2023,'1.6',11000,'DCT',118500.00,'Gasoline','FWD',5,'sold'),
-- i30 (34-39) x6
(@seller_id,'Hyundai','i30','1.6',2016,'1.6',72000,'AT',45500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Hyundai','i30','2.0',2017,'2.0',65000,'AT',51500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Hyundai','i30','1.6',2018,'1.6',58000,'AT',55500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Hyundai','i30','N-Line',2019,'2.0',48000,'AT',75500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Hyundai','i30','1.6',2020,'1.6',43000,'AT',60500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Hyundai','i30','N-Line',2021,'2.0',30000,'AT',82500.00,'Gasoline','FWD',5,'sold'),
-- Accent (40-45) x6
(@seller_id,'Hyundai','Accent','1.4',2016,'1.4',85000,'AT',32500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Hyundai','Accent','1.4',2017,'1.4',78000,'AT',34500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Hyundai','Accent','1.6',2018,'1.6',69000,'AT',38500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Hyundai','Accent','1.6',2019,'1.6',61000,'AT',41500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Hyundai','Accent','1.6',2020,'1.6',52000,'AT',45500.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Hyundai','Accent','1.6',2021,'1.6',42000,'AT',49500.00,'Gasoline','FWD',4,'sold'),
-- Staria (46-49) x4
(@seller_id,'Hyundai','Staria','2.2 CRDi',2021,'2.2',28000,'AT',168500.00,'Diesel','FWD',5,'sold'),
(@seller_id,'Hyundai','Staria','2.2 CRDi',2022,'2.2',19000,'AT',178500.00,'Diesel','FWD',5,'sold'),
(@seller_id,'Hyundai','Staria','2.2 CRDi AWD',2022,'2.2',17000,'AT',188500.00,'Diesel','AWD',5,'sold'),
(@seller_id,'Hyundai','Staria','2.2 CRDi AWD',2023,'2.2',11000,'AT',198500.00,'Diesel','AWD',5,'sold'),
-- Ioniq 5 (50-55) x6 EV
(@seller_id,'Hyundai','Ioniq 5','RWD',2022,'0.0',15000,'AT',235000.00,'Electric','RWD',5,'sold'),
(@seller_id,'Hyundai','Ioniq 5','AWD',2022,'0.0',13000,'AT',255000.00,'Electric','AWD',5,'sold'),
(@seller_id,'Hyundai','Ioniq 5','RWD',2023,'0.0',9000,'AT',265000.00,'Electric','RWD',5,'sold'),
(@seller_id,'Hyundai','Ioniq 5','AWD',2023,'0.0',8000,'AT',285000.00,'Electric','AWD',5,'sold'),
(@seller_id,'Hyundai','Ioniq 5','RWD',2024,'0.0',6000,'AT',295000.00,'Electric','RWD',5,'sold'),
(@seller_id,'Hyundai','Ioniq 5','AWD',2024,'0.0',4000,'AT',315000.00,'Electric','AWD',5,'sold'),
-- Ioniq 6 (56-59) x4 EV
(@seller_id,'Hyundai','Ioniq 6','RWD',2023,'0.0',8000,'AT',315000.00,'Electric','RWD',4,'sold'),
(@seller_id,'Hyundai','Ioniq 6','AWD',2023,'0.0',7000,'AT',335000.00,'Electric','AWD',4,'sold'),
(@seller_id,'Hyundai','Ioniq 6','RWD',2024,'0.0',5000,'AT',345000.00,'Electric','RWD',4,'sold'),
(@seller_id,'Hyundai','Ioniq 6','AWD',2024,'0.0',3000,'AT',365000.00,'Electric','AWD',4,'sold');

SET @base_hyundai := LAST_INSERT_ID();

-- 60 thumbnails
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES
(@base_hyundai+0,'uploads/ativa(1).webp',1),(@base_hyundai+1,'uploads/ativa(1).webp',1),(@base_hyundai+2,'uploads/ativa(1).webp',1),(@base_hyundai+3,'uploads/ativa(1).webp',1),
(@base_hyundai+4,'uploads/ativa(1).webp',1),(@base_hyundai+5,'uploads/ativa(1).webp',1),(@base_hyundai+6,'uploads/ativa(1).webp',1),(@base_hyundai+7,'uploads/ativa(1).webp',1),
(@base_hyundai+8,'uploads/ativa(1).webp',1),(@base_hyundai+9,'uploads/ativa(1).webp',1),(@base_hyundai+10,'uploads/ativa(1).webp',1),(@base_hyundai+11,'uploads/ativa(1).webp',1),
(@base_hyundai+12,'uploads/ativa(1).webp',1),(@base_hyundai+13,'uploads/ativa(1).webp',1),(@base_hyundai+14,'uploads/ativa(1).webp',1),(@base_hyundai+15,'uploads/ativa(1).webp',1),
(@base_hyundai+16,'uploads/ativa(1).webp',1),(@base_hyundai+17,'uploads/ativa(1).webp',1),(@base_hyundai+18,'uploads/ativa(1).webp',1),(@base_hyundai+19,'uploads/ativa(1).webp',1),
(@base_hyundai+20,'uploads/ativa(1).webp',1),(@base_hyundai+21,'uploads/ativa(1).webp',1),(@base_hyundai+22,'uploads/ativa(1).webp',1),(@base_hyundai+23,'uploads/ativa(1).webp',1),
(@base_hyundai+24,'uploads/ativa(1).webp',1),(@base_hyundai+25,'uploads/ativa(1).webp',1),(@base_hyundai+26,'uploads/ativa(1).webp',1),(@base_hyundai+27,'uploads/ativa(1).webp',1),
(@base_hyundai+28,'uploads/ativa(1).webp',1),(@base_hyundai+29,'uploads/ativa(1).webp',1),(@base_hyundai+30,'uploads/ativa(1).webp',1),(@base_hyundai+31,'uploads/ativa(1).webp',1),
(@base_hyundai+32,'uploads/ativa(1).webp',1),(@base_hyundai+33,'uploads/ativa(1).webp',1),(@base_hyundai+34,'uploads/ativa(1).webp',1),(@base_hyundai+35,'uploads/ativa(1).webp',1),
(@base_hyundai+36,'uploads/ativa(1).webp',1),(@base_hyundai+37,'uploads/ativa(1).webp',1),(@base_hyundai+38,'uploads/ativa(1).webp',1),(@base_hyundai+39,'uploads/ativa(1).webp',1),
(@base_hyundai+40,'uploads/ativa(1).webp',1),(@base_hyundai+41,'uploads/ativa(1).webp',1),(@base_hyundai+42,'uploads/ativa(1).webp',1),(@base_hyundai+43,'uploads/ativa(1).webp',1),
(@base_hyundai+44,'uploads/ativa(1).webp',1),(@base_hyundai+45,'uploads/ativa(1).webp',1),(@base_hyundai+46,'uploads/ativa(1).webp',1),(@base_hyundai+47,'uploads/ativa(1).webp',1),
(@base_hyundai+48,'uploads/ativa(1).webp',1),(@base_hyundai+49,'uploads/ativa(1).webp',1),(@base_hyundai+50,'uploads/ativa(1).webp',1),(@base_hyundai+51,'uploads/ativa(1).webp',1),
(@base_hyundai+52,'uploads/ativa(1).webp',1),(@base_hyundai+53,'uploads/ativa(1).webp',1),(@base_hyundai+54,'uploads/ativa(1).webp',1),(@base_hyundai+55,'uploads/ativa(1).webp',1),
(@base_hyundai+56,'uploads/ativa(1).webp',1),(@base_hyundai+57,'uploads/ativa(1).webp',1),(@base_hyundai+58,'uploads/ativa(1).webp',1),(@base_hyundai+59,'uploads/ativa(1).webp',1);

-- 60 details with car_condition mixed
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note)
VALUES
-- Elantra (0-7)
(@base_hyundai+0,'White',128,'Gamma',6,'205/55 R16','205/55 R16',155,'Sedan','Used','Well kept, single owner.'),
(@base_hyundai+1,'Grey',150,'Nu',6,'215/50 R17','215/50 R17',180,'Sedan','Reconditioned','Recond, clean interior.'),
(@base_hyundai+2,'Blue',128,'Gamma',6,'205/55 R16','205/55 R16',155,'Sedan','Used','Service on time.'),
(@base_hyundai+3,'Red',201,'Gamma 1.6T',7,'225/45 R17','225/45 R17',265,'Sedan','Reconditioned','Sporty 1.6T recond.'),
(@base_hyundai+4,'White',150,'Nu',6,'215/50 R17','215/50 R17',180,'Sedan','Used','Low mileage.'),
(@base_hyundai+5,'Black',128,'Gamma',6,'205/55 R16','205/55 R16',155,'Sedan','Reconditioned','Premium trim recond.'),
(@base_hyundai+6,'Silver',128,'Gamma',6,'205/55 R16','205/55 R16',155,'Sedan','New','Brand new MY unit.'),
(@base_hyundai+7,'Grey',150,'Nu',6,'215/50 R17','215/50 R17',180,'Sedan','New','Unregistered new.'),
-- Sonata (8-13)
(@base_hyundai+8,'White',155,'Nu',6,'215/55 R17','215/55 R17',196,'Sedan','Used','Comfort cruiser.'),
(@base_hyundai+9,'Black',185,'Theta II',6,'215/55 R17','215/55 R17',241,'Sedan','Reconditioned','2.4L recond.'),
(@base_hyundai+10,'Silver',155,'Nu',6,'215/55 R17','215/55 R17',196,'Sedan','Used','Value buy.'),
(@base_hyundai+11,'Grey',191,'Smartstream',8,'235/45 R18','235/45 R18',245,'Sedan','Reconditioned','2.5 new gen recond.'),
(@base_hyundai+12,'Blue',191,'Smartstream',8,'235/45 R18','235/45 R18',245,'Sedan','Used','Well maintained.'),
(@base_hyundai+13,'White',191,'Smartstream',8,'235/45 R18','235/45 R18',245,'Sedan','New','Brand new MY unit.'),
-- Tucson (14-21)
(@base_hyundai+14,'Silver',155,'Nu',6,'225/60 R17','225/60 R17',192,'SUV','Used','Family SUV.'),
(@base_hyundai+15,'White',177,'Gamma 1.6T',7,'225/55 R18','225/55 R18',265,'SUV','Reconditioned','1.6T recond.'),
(@base_hyundai+16,'Grey',177,'Gamma 1.6T',7,'225/55 R18','225/55 R18',265,'SUV','Used','AWD grip.'),
(@base_hyundai+17,'Blue',155,'Nu',6,'225/60 R17','225/60 R17',192,'SUV','Reconditioned','Facelift recond.'),
(@base_hyundai+18,'Black',177,'Gamma 1.6T',7,'225/55 R18','225/55 R18',265,'SUV','Used','Low mileage.'),
(@base_hyundai+19,'White',177,'Gamma 1.6T',7,'235/55 R19','235/55 R19',265,'SUV','Reconditioned','AWD recond.'),
(@base_hyundai+20,'Grey',155,'Nu',6,'225/60 R17','225/60 R17',192,'SUV','New','Brand new MY unit.'),
(@base_hyundai+21,'Blue',177,'Gamma 1.6T',7,'225/55 R18','225/55 R18',265,'SUV','New','Unregistered new.'),
-- Santa Fe (22-27)
(@base_hyundai+22,'White',185,'Theta II',6,'235/60 R18','235/60 R18',241,'SUV','Used','7-seater.'),
(@base_hyundai+23,'Grey',200,'R2.2',8,'235/60 R18','235/60 R18',440,'SUV','Reconditioned','Diesel AWD recond.'),
(@base_hyundai+24,'Black',200,'R2.2',8,'235/55 R19','235/55 R19',440,'SUV','Used','Powerful torque.'),
(@base_hyundai+25,'Blue',185,'Theta II',6,'235/60 R18','235/60 R18',241,'SUV','Reconditioned','Facelift recond.'),
(@base_hyundai+26,'White',200,'R2.2',8,'235/55 R19','235/55 R19',440,'SUV','Used','Low mileage.'),
(@base_hyundai+27,'Grey',200,'R2.2',8,'235/55 R19','235/55 R19',440,'SUV','New','Unregistered new.'),
-- Kona (28-33)
(@base_hyundai+28,'Red',177,'Gamma 1.6T',7,'215/55 R17','215/55 R17',265,'SUV','Used','Zippy urban.'),
(@base_hyundai+29,'White',177,'Gamma 1.6T',7,'215/55 R17','215/55 R17',265,'SUV','Reconditioned','Well kept recond.'),
(@base_hyundai+30,'Grey',177,'Gamma 1.6T',7,'215/55 R17','215/55 R17',265,'SUV','Used','Service on time.'),
(@base_hyundai+31,'Blue',177,'Gamma 1.6T',7,'225/45 R18','225/45 R18',265,'SUV','Reconditioned','AWD recond.'),
(@base_hyundai+32,'White',177,'Gamma 1.6T',7,'215/55 R17','215/55 R17',265,'SUV','New','Brand new MY unit.'),
(@base_hyundai+33,'Grey',177,'Gamma 1.6T',7,'215/55 R17','215/55 R17',265,'SUV','New','Unregistered new.'),
-- i30 (34-39)
(@base_hyundai+34,'White',128,'Gamma',6,'205/55 R16','205/55 R16',155,'Hatchback','Used','Clean interior.'),
(@base_hyundai+35,'Grey',150,'Nu',6,'215/50 R17','215/50 R17',180,'Hatchback','Reconditioned','Well maintained recond.'),
(@base_hyundai+36,'Blue',128,'Gamma',6,'205/55 R16','205/55 R16',155,'Hatchback','Used','Value buy.'),
(@base_hyundai+37,'Red',201,'Theta 2.0',6,'225/45 R18','225/45 R18',265,'Hatchback','Reconditioned','N-Line recond.'),
(@base_hyundai+38,'White',128,'Gamma',6,'205/55 R16','205/55 R16',155,'Hatchback','New','Brand new MY unit.'),
(@base_hyundai+39,'Grey',201,'Theta 2.0',6,'225/45 R18','225/45 R18',265,'Hatchback','New','Unregistered new.'),
-- Accent (40-45)
(@base_hyundai+40,'Silver',100,'Kappa',6,'185/65 R15','185/65 R15',132,'Sedan','Used','Daily runner.'),
(@base_hyundai+41,'White',100,'Kappa',6,'185/65 R15','185/65 R15',132,'Sedan','Reconditioned','Clean recond.'),
(@base_hyundai+42,'Grey',121,'Gamma',6,'195/55 R16','195/55 R16',154,'Sedan','Used','Low mileage.'),
(@base_hyundai+43,'Blue',121,'Gamma',6,'195/55 R16','195/55 R16',154,'Sedan','Reconditioned','Well kept.'),
(@base_hyundai+44,'White',121,'Gamma',6,'195/55 R16','195/55 R16',154,'Sedan','New','Brand new MY unit.'),
(@base_hyundai+45,'Grey',121,'Gamma',6,'195/55 R16','195/55 R16',154,'Sedan','New','Unregistered new.'),
-- Staria (46-49)
(@base_hyundai+46,'White',199,'R2.2',8,'235/60 R18','235/60 R18',430,'MPV','Reconditioned','Spacious MPV recond.'),
(@base_hyundai+47,'Grey',199,'R2.2',8,'235/60 R18','235/60 R18',430,'MPV','Used','Family MPV.'),
(@base_hyundai+48,'Black',199,'R2.2',8,'235/60 R18','235/60 R18',430,'MPV','Used','Low mileage.'),
(@base_hyundai+49,'White',199,'R2.2',8,'235/60 R18','235/60 R18',430,'MPV','New','Unregistered new.'),
-- Ioniq 5 (50-55)
(@base_hyundai+50,'White',225,'e-Drive',1,'235/55 R19','235/55 R19',350,'SUV','Reconditioned','Ioniq 5 RWD recond.'),
(@base_hyundai+51,'Grey',320,'e-Drive',1,'255/45 R20','255/45 R20',605,'SUV','Reconditioned','AWD recond.'),
(@base_hyundai+52,'Blue',225,'e-Drive',1,'235/55 R19','235/55 R19',350,'SUV','Used','Clean interior.'),
(@base_hyundai+53,'Black',320,'e-Drive',1,'255/45 R20','255/45 R20',605,'SUV','Used','Low mileage.'),
(@base_hyundai+54,'White',225,'e-Drive',1,'235/55 R19','235/55 R19',350,'SUV','New','Brand new MY unit.'),
(@base_hyundai+55,'Grey',320,'e-Drive',1,'255/45 R20','255/45 R20',605,'SUV','New','Unregistered new.'),
-- Ioniq 6 (56-59)
(@base_hyundai+56,'White',225,'e-Drive',1,'245/45 R19','245/45 R19',350,'Sedan','Reconditioned','Ioniq 6 RWD recond.'),
(@base_hyundai+57,'Grey',325,'e-Drive',1,'245/45 R19','245/45 R19',605,'Sedan','Reconditioned','AWD recond.'),
(@base_hyundai+58,'Blue',225,'e-Drive',1,'245/45 R19','245/45 R19',350,'Sedan','New','Brand new MY unit.'),
(@base_hyundai+59,'Black',325,'e-Drive',1,'245/45 R19','245/45 R19',605,'Sedan','New','Unregistered new.');

COMMIT;
