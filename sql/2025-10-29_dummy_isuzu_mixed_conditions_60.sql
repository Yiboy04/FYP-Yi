-- Dummy bulk data: 60 Isuzu cars (Mixed conditions: Reconditioned, Used, New)
-- Models from data/makes_models_my.json
-- All rows use seller_id=5 and listing_status='sold'.
-- Thumbnails reuse 'uploads/ativa(1).webp'.

START TRANSACTION;

SET @seller_id := 5;

-- ISUZU (60)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES
-- D-Max (0-35) x36
(@seller_id,'Isuzu','D-Max','1.9 BluePower',2016,'1.9',92000,'AT',62500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Isuzu','D-Max','3.0 V-Cross',2017,'3.0',86000,'AT',72500.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Isuzu','D-Max','1.9 BluePower',2018,'1.9',78000,'AT',69500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Isuzu','D-Max','3.0 V-Cross',2018,'3.0',74000,'AT',82500.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Isuzu','D-Max','1.9 BluePower',2019,'1.9',66000,'AT',75500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Isuzu','D-Max','3.0 V-Cross',2019,'3.0',59000,'AT',90500.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Isuzu','D-Max','1.9 BluePower',2020,'1.9',52000,'AT',82500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Isuzu','D-Max','3.0 X-Terrain',2020,'3.0',48000,'AT',112500.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Isuzu','D-Max','1.9 Standard',2021,'1.9',42000,'AT',90500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Isuzu','D-Max','3.0 X-Terrain',2021,'3.0',36000,'AT',126500.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Isuzu','D-Max','1.9 Premium',2022,'1.9',29000,'AT',98500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Isuzu','D-Max','3.0 X-Terrain',2022,'3.0',23000,'AT',136500.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Isuzu','D-Max','1.9 Premium',2023,'1.9',18000,'AT',108500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Isuzu','D-Max','3.0 X-Terrain',2023,'3.0',15000,'AT',146500.00,'Diesel','4WD',4,'sold'),
-- repeat variants to reach x36
(@seller_id,'Isuzu','D-Max','1.9 BluePower',2016,'1.9',90000,'AT',60500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Isuzu','D-Max','3.0 V-Cross',2017,'3.0',84000,'AT',70500.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Isuzu','D-Max','1.9 BluePower',2018,'1.9',76000,'AT',67500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Isuzu','D-Max','3.0 V-Cross',2018,'3.0',72000,'AT',80500.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Isuzu','D-Max','1.9 BluePower',2019,'1.9',64000,'AT',73500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Isuzu','D-Max','3.0 V-Cross',2019,'3.0',57000,'AT',88500.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Isuzu','D-Max','1.9 BluePower',2020,'1.9',50000,'AT',80500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Isuzu','D-Max','3.0 X-Terrain',2020,'3.0',46000,'AT',110500.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Isuzu','D-Max','1.9 Standard',2021,'1.9',40000,'AT',88500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Isuzu','D-Max','3.0 X-Terrain',2021,'3.0',34000,'AT',124500.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Isuzu','D-Max','1.9 Premium',2022,'1.9',28000,'AT',96500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Isuzu','D-Max','3.0 X-Terrain',2022,'3.0',22000,'AT',134500.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Isuzu','D-Max','1.9 Premium',2023,'1.9',17000,'AT',106500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Isuzu','D-Max','3.0 X-Terrain',2023,'3.0',14000,'AT',144500.00,'Diesel','4WD',4,'sold'),
-- more to reach 36
(@seller_id,'Isuzu','D-Max','1.9 Standard',2021,'1.9',39000,'AT',87500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Isuzu','D-Max','1.9 Premium',2022,'1.9',27000,'AT',95500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Isuzu','D-Max','3.0 X-Terrain',2022,'3.0',21000,'AT',133500.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Isuzu','D-Max','1.9 Premium',2023,'1.9',16000,'AT',105500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Isuzu','D-Max','3.0 X-Terrain',2023,'3.0',13000,'AT',143500.00,'Diesel','4WD',4,'sold'),
(@seller_id,'Isuzu','D-Max','1.9 BluePower',2019,'1.9',63000,'AT',72500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Isuzu','D-Max','1.9 Standard',2020,'1.9',51000,'AT',81500.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Isuzu','D-Max','3.0 X-Terrain',2021,'3.0',35000,'AT',125500.00,'Diesel','4WD',4,'sold'),
-- MU-X (36-51) x16
(@seller_id,'Isuzu','MU-X','2.5',2016,'2.5',88000,'AT',82500.00,'Diesel','RWD',5,'sold'),
(@seller_id,'Isuzu','MU-X','3.0',2017,'3.0',82000,'AT',92500.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Isuzu','MU-X','2.5',2018,'2.5',74000,'AT',96500.00,'Diesel','RWD',5,'sold'),
(@seller_id,'Isuzu','MU-X','3.0',2019,'3.0',65000,'AT',112500.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Isuzu','MU-X','3.0',2020,'3.0',56000,'AT',126500.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Isuzu','MU-X','3.0',2021,'3.0',43000,'AT',138500.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Isuzu','MU-X','3.0',2022,'3.0',32000,'AT',148500.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Isuzu','MU-X','3.0',2023,'3.0',21000,'AT',158500.00,'Diesel','4WD',5,'sold'),
-- repeat to x16
(@seller_id,'Isuzu','MU-X','2.5',2016,'2.5',86000,'AT',80500.00,'Diesel','RWD',5,'sold'),
(@seller_id,'Isuzu','MU-X','3.0',2017,'3.0',80000,'AT',90500.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Isuzu','MU-X','2.5',2018,'2.5',72000,'AT',94500.00,'Diesel','RWD',5,'sold'),
(@seller_id,'Isuzu','MU-X','3.0',2019,'3.0',63000,'AT',110500.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Isuzu','MU-X','3.0',2020,'3.0',54000,'AT',124500.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Isuzu','MU-X','3.0',2021,'3.0',42000,'AT',136500.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Isuzu','MU-X','3.0',2022,'3.0',31000,'AT',146500.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Isuzu','MU-X','3.0',2023,'3.0',20000,'AT',156500.00,'Diesel','4WD',5,'sold'),
-- Trooper (52-59) x8
(@seller_id,'Isuzu','Trooper','3.0',2003,'3.0',150000,'AT',23500.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Isuzu','Trooper','3.0',2004,'3.0',145000,'AT',25500.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Isuzu','Trooper','3.0',2005,'3.0',140000,'AT',27500.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Isuzu','Trooper','3.0',2006,'3.0',135000,'AT',29500.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Isuzu','Trooper','3.0',2007,'3.0',130000,'AT',31500.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Isuzu','Trooper','3.0',2008,'3.0',125000,'AT',33500.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Isuzu','Trooper','3.0',2008,'3.0',120000,'AT',35500.00,'Diesel','4WD',5,'sold'),
(@seller_id,'Isuzu','Trooper','3.0',2009,'3.0',115000,'AT',37500.00,'Diesel','4WD',5,'sold');

SET @base_isuzu := LAST_INSERT_ID();

-- 60 thumbnails
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES
(@base_isuzu+0,'uploads/ativa(1).webp',1),(@base_isuzu+1,'uploads/ativa(1).webp',1),(@base_isuzu+2,'uploads/ativa(1).webp',1),(@base_isuzu+3,'uploads/ativa(1).webp',1),
(@base_isuzu+4,'uploads/ativa(1).webp',1),(@base_isuzu+5,'uploads/ativa(1).webp',1),(@base_isuzu+6,'uploads/ativa(1).webp',1),(@base_isuzu+7,'uploads/ativa(1).webp',1),
(@base_isuzu+8,'uploads/ativa(1).webp',1),(@base_isuzu+9,'uploads/ativa(1).webp',1),(@base_isuzu+10,'uploads/ativa(1).webp',1),(@base_isuzu+11,'uploads/ativa(1).webp',1),
(@base_isuzu+12,'uploads/ativa(1).webp',1),(@base_isuzu+13,'uploads/ativa(1).webp',1),(@base_isuzu+14,'uploads/ativa(1).webp',1),(@base_isuzu+15,'uploads/ativa(1).webp',1),
(@base_isuzu+16,'uploads/ativa(1).webp',1),(@base_isuzu+17,'uploads/ativa(1).webp',1),(@base_isuzu+18,'uploads/ativa(1).webp',1),(@base_isuzu+19,'uploads/ativa(1).webp',1),
(@base_isuzu+20,'uploads/ativa(1).webp',1),(@base_isuzu+21,'uploads/ativa(1).webp',1),(@base_isuzu+22,'uploads/ativa(1).webp',1),(@base_isuzu+23,'uploads/ativa(1).webp',1),
(@base_isuzu+24,'uploads/ativa(1).webp',1),(@base_isuzu+25,'uploads/ativa(1).webp',1),(@base_isuzu+26,'uploads/ativa(1).webp',1),(@base_isuzu+27,'uploads/ativa(1).webp',1),
(@base_isuzu+28,'uploads/ativa(1).webp',1),(@base_isuzu+29,'uploads/ativa(1).webp',1),(@base_isuzu+30,'uploads/ativa(1).webp',1),(@base_isuzu+31,'uploads/ativa(1).webp',1),
(@base_isuzu+32,'uploads/ativa(1).webp',1),(@base_isuzu+33,'uploads/ativa(1).webp',1),(@base_isuzu+34,'uploads/ativa(1).webp',1),(@base_isuzu+35,'uploads/ativa(1).webp',1),
(@base_isuzu+36,'uploads/ativa(1).webp',1),(@base_isuzu+37,'uploads/ativa(1).webp',1),(@base_isuzu+38,'uploads/ativa(1).webp',1),(@base_isuzu+39,'uploads/ativa(1).webp',1),
(@base_isuzu+40,'uploads/ativa(1).webp',1),(@base_isuzu+41,'uploads/ativa(1).webp',1),(@base_isuzu+42,'uploads/ativa(1).webp',1),(@base_isuzu+43,'uploads/ativa(1).webp',1),
(@base_isuzu+44,'uploads/ativa(1).webp',1),(@base_isuzu+45,'uploads/ativa(1).webp',1),(@base_isuzu+46,'uploads/ativa(1).webp',1),(@base_isuzu+47,'uploads/ativa(1).webp',1),
(@base_isuzu+48,'uploads/ativa(1).webp',1),(@base_isuzu+49,'uploads/ativa(1).webp',1),(@base_isuzu+50,'uploads/ativa(1).webp',1),(@base_isuzu+51,'uploads/ativa(1).webp',1),
(@base_isuzu+52,'uploads/ativa(1).webp',1),(@base_isuzu+53,'uploads/ativa(1).webp',1),(@base_isuzu+54,'uploads/ativa(1).webp',1),(@base_isuzu+55,'uploads/ativa(1).webp',1),
(@base_isuzu+56,'uploads/ativa(1).webp',1),(@base_isuzu+57,'uploads/ativa(1).webp',1),(@base_isuzu+58,'uploads/ativa(1).webp',1),(@base_isuzu+59,'uploads/ativa(1).webp',1);

-- 60 details with mixed car_condition
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note)
VALUES
-- D-Max (0-35)
(@base_isuzu+0,'White',150,'RZ4E',6,'245/70 R16','245/70 R16',350,'Pickup','Used','Workhorse.'),
(@base_isuzu+1,'Grey',177,'4JJ1',6,'265/60 R18','265/60 R18',380,'Pickup','Reconditioned','V-Cross recond.'),
(@base_isuzu+2,'Black',150,'RZ4E',6,'245/70 R16','245/70 R16',350,'Pickup','Used','Reliable.'),
(@base_isuzu+3,'White',177,'4JJ1',6,'265/60 R18','265/60 R18',380,'Pickup','Reconditioned','4WD recond.'),
(@base_isuzu+4,'Silver',150,'RZ4E',6,'245/70 R16','245/70 R16',350,'Pickup','Used','Low mileage.'),
(@base_isuzu+5,'Blue',177,'4JJ1',6,'265/60 R18','265/60 R18',380,'Pickup','Reconditioned','Well kept.'),
(@base_isuzu+6,'White',150,'RZ4E',6,'245/70 R16','245/70 R16',350,'Pickup','Used','Service on time.'),
(@base_isuzu+7,'Grey',190,'4JJ3',6,'265/60 R18','265/60 R18',450,'Pickup','Reconditioned','X-Terrain recond.'),
(@base_isuzu+8,'Black',150,'RZ4E',6,'245/70 R16','245/70 R16',350,'Pickup','Used','Trusted truck.'),
(@base_isuzu+9,'White',190,'4JJ3',6,'265/60 R18','265/60 R18',450,'Pickup','Reconditioned','Top spec recond.'),
(@base_isuzu+10,'Silver',150,'RZ4E',6,'245/70 R16','245/70 R16',350,'Pickup','Used','Clean unit.'),
(@base_isuzu+11,'Blue',190,'4JJ3',6,'265/60 R18','265/60 R18',450,'Pickup','Reconditioned','AWD recond.'),
(@base_isuzu+12,'White',150,'RZ4E',6,'245/70 R16','245/70 R16',350,'Pickup','Used','Low mileage.'),
(@base_isuzu+13,'Grey',190,'4JJ3',6,'265/60 R18','265/60 R18',450,'Pickup','Reconditioned','Well kept.'),
(@base_isuzu+14,'Black',150,'RZ4E',6,'245/70 R16','245/70 R16',350,'Pickup','Used','Daily runner.'),
(@base_isuzu+15,'White',190,'4JJ3',6,'265/60 R18','265/60 R18',450,'Pickup','Reconditioned','Clean interior.'),
(@base_isuzu+16,'Silver',150,'RZ4E',6,'245/70 R16','245/70 R16',350,'Pickup','Used','Value buy.'),
(@base_isuzu+17,'Blue',190,'4JJ3',6,'265/60 R18','265/60 R18',450,'Pickup','Reconditioned','New gen recond.'),
(@base_isuzu+18,'White',150,'RZ4E',6,'245/70 R16','245/70 R16',350,'Pickup','Used','Well maintained.'),
(@base_isuzu+19,'Grey',190,'4JJ3',6,'265/60 R18','265/60 R18',450,'Pickup','Reconditioned','4WD recond.'),
(@base_isuzu+20,'Black',150,'RZ4E',6,'245/70 R16','245/70 R16',350,'Pickup','Used','Trusted truck.'),
(@base_isuzu+21,'White',190,'4JJ3',6,'265/60 R18','265/60 R18',450,'Pickup','Reconditioned','Top spec recond.'),
(@base_isuzu+22,'Silver',150,'RZ4E',6,'245/70 R16','245/70 R16',350,'Pickup','Used','Clean unit.'),
(@base_isuzu+23,'Blue',190,'4JJ3',6,'265/60 R18','265/60 R18',450,'Pickup','Reconditioned','AWD recond.'),
(@base_isuzu+24,'White',150,'RZ4E',6,'245/70 R16','245/70 R16',350,'Pickup','Used','Low mileage.'),
(@base_isuzu+25,'Grey',190,'4JJ3',6,'265/60 R18','265/60 R18',450,'Pickup','Reconditioned','Well kept.'),
(@base_isuzu+26,'Black',150,'RZ4E',6,'245/70 R16','245/70 R16',350,'Pickup','Used','Daily runner.'),
(@base_isuzu+27,'White',190,'4JJ3',6,'265/60 R18','265/60 R18',450,'Pickup','Reconditioned','Clean interior.'),
(@base_isuzu+28,'Silver',150,'RZ4E',6,'245/70 R16','245/70 R16',350,'Pickup','Used','Value buy.'),
(@base_isuzu+29,'Blue',190,'4JJ3',6,'265/60 R18','265/60 R18',450,'Pickup','Reconditioned','New gen recond.'),
(@base_isuzu+30,'White',150,'RZ4E',6,'245/70 R16','245/70 R16',350,'Pickup','Used','Well maintained.'),
(@base_isuzu+31,'Grey',190,'4JJ3',6,'265/60 R18','265/60 R18',450,'Pickup','Reconditioned','4WD recond.'),
(@base_isuzu+32,'Black',150,'RZ4E',6,'245/70 R16','245/70 R16',350,'Pickup','Used','Trusted truck.'),
(@base_isuzu+33,'White',190,'4JJ3',6,'265/60 R18','265/60 R18',450,'Pickup','Reconditioned','Top spec recond.'),
(@base_isuzu+34,'Silver',150,'RZ4E',6,'245/70 R16','245/70 R16',350,'Pickup','Used','Clean unit.'),
(@base_isuzu+35,'Blue',190,'4JJ3',6,'265/60 R18','265/60 R18',450,'Pickup','New','Unregistered new.'),
-- MU-X (36-51)
(@base_isuzu+36,'White',134,'4JK1',5,'245/70 R16','245/70 R16',320,'SUV','Used','7-seater.'),
(@base_isuzu+37,'Grey',177,'4JJ1',6,'255/60 R18','255/60 R18',380,'SUV','Reconditioned','3.0 recond.'),
(@base_isuzu+38,'Black',134,'4JK1',5,'245/70 R16','245/70 R16',320,'SUV','Used','Reliable.'),
(@base_isuzu+39,'White',177,'4JJ1',6,'255/60 R18','255/60 R18',380,'SUV','Reconditioned','4WD recond.'),
(@base_isuzu+40,'Silver',134,'4JK1',5,'245/70 R16','245/70 R16',320,'SUV','Used','Low mileage.'),
(@base_isuzu+41,'Blue',177,'4JJ1',6,'255/60 R18','255/60 R18',380,'SUV','Reconditioned','Well kept.'),
(@base_isuzu+42,'White',190,'4JJ3',6,'265/60 R18','265/60 R18',450,'SUV','Used','Powerful torque.'),
(@base_isuzu+43,'Grey',190,'4JJ3',6,'265/60 R18','265/60 R18',450,'SUV','Reconditioned','Top spec recond.'),
(@base_isuzu+44,'Black',134,'4JK1',5,'245/70 R16','245/70 R16',320,'SUV','Used','Clean interior.'),
(@base_isuzu+45,'White',177,'4JJ1',6,'255/60 R18','255/60 R18',380,'SUV','Reconditioned','AWD recond.'),
(@base_isuzu+46,'Silver',134,'4JK1',5,'245/70 R16','245/70 R16',320,'SUV','Used','Value buy.'),
(@base_isuzu+47,'Blue',177,'4JJ1',6,'255/60 R18','255/60 R18',380,'SUV','Reconditioned','Facelift recond.'),
(@base_isuzu+48,'White',190,'4JJ3',6,'265/60 R18','265/60 R18',450,'SUV','Used','Well maintained.'),
(@base_isuzu+49,'Grey',190,'4JJ3',6,'265/60 R18','265/60 R18',450,'SUV','Reconditioned','4WD recond.'),
(@base_isuzu+50,'Black',190,'4JJ3',6,'265/60 R18','265/60 R18',450,'SUV','Used','Trusted family SUV.'),
(@base_isuzu+51,'White',190,'4JJ3',6,'265/60 R18','265/60 R18',450,'SUV','New','Unregistered new.'),
-- Trooper (52-59)
(@base_isuzu+52,'Green',130,'4JX1',4,'245/70 R16','245/70 R16',265,'SUV','Used','Classic Trooper.'),
(@base_isuzu+53,'White',130,'4JX1',4,'245/70 R16','245/70 R16',265,'SUV','Reconditioned','Clean recond.'),
(@base_isuzu+54,'Blue',130,'4JX1',4,'245/70 R16','245/70 R16',265,'SUV','Used','Low mileage for age.'),
(@base_isuzu+55,'Grey',130,'4JX1',4,'245/70 R16','245/70 R16',265,'SUV','Reconditioned','Well kept.'),
(@base_isuzu+56,'White',130,'4JX1',4,'245/70 R16','245/70 R16',265,'SUV','Used','Strong body.'),
(@base_isuzu+57,'Grey',130,'4JX1',4,'245/70 R16','245/70 R16',265,'SUV','Reconditioned','New paint.'),
(@base_isuzu+58,'Black',130,'4JX1',4,'245/70 R16','245/70 R16',265,'SUV','Used','Daily driver.'),
(@base_isuzu+59,'Silver',130,'4JX1',4,'245/70 R16','245/70 R16',265,'SUV','New','Unregistered new.');

COMMIT;
