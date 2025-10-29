-- Dummy bulk data: 60 Lexus cars (Mixed conditions: Reconditioned, Used, New)
-- Models from data/makes_models_my.json
-- All rows use seller_id=7 and listing_status='sold'.
-- Thumbnails reuse 'uploads/ativa(1).webp'.

START TRANSACTION;

SET @seller_id := 7;

-- LEXUS (60)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES
-- RX (0-19) x20
(@seller_id,'Lexus','RX','350 Luxury',2016,'3.5',90000,'AT',165000.00,'Petrol','AWD',5,'sold'),
(@seller_id,'Lexus','RX','300 Premium',2017,'2.0',82000,'AT',175000.00,'Petrol','FWD',5,'sold'),
(@seller_id,'Lexus','RX','450h',2018,'3.5',70000,'AT',215000.00,'Hybrid','AWD',5,'sold'),
(@seller_id,'Lexus','RX','300 F Sport',2019,'2.0',55000,'AT',235000.00,'Petrol','AWD',5,'sold'),
(@seller_id,'Lexus','RX','350 Luxury',2020,'3.5',42000,'AT',265000.00,'Petrol','AWD',5,'sold'),
(@seller_id,'Lexus','RX','300 Premium',2021,'2.0',32000,'AT',285000.00,'Petrol','AWD',5,'sold'),
(@seller_id,'Lexus','RX','350 Luxury',2022,'3.5',21000,'AT',315000.00,'Petrol','AWD',5,'sold'),
(@seller_id,'Lexus','RX','350 Luxury',2023,'3.5',12000,'AT',345000.00,'Petrol','AWD',5,'sold'),
(@seller_id,'Lexus','RX','300 Premium',2016,'2.0',90000,'AT',155000.00,'Petrol','FWD',5,'sold'),
(@seller_id,'Lexus','RX','450h',2017,'3.5',82000,'AT',205000.00,'Hybrid','AWD',5,'sold'),
(@seller_id,'Lexus','RX','300 F Sport',2018,'2.0',70000,'AT',225000.00,'Petrol','AWD',5,'sold'),
(@seller_id,'Lexus','RX','350 Luxury',2019,'3.5',56000,'AT',245000.00,'Petrol','AWD',5,'sold'),
(@seller_id,'Lexus','RX','300 Premium',2020,'2.0',43000,'AT',255000.00,'Petrol','AWD',5,'sold'),
(@seller_id,'Lexus','RX','450h',2021,'3.5',33000,'AT',295000.00,'Hybrid','AWD',5,'sold'),
(@seller_id,'Lexus','RX','350 Luxury',2022,'3.5',22000,'AT',315000.00,'Petrol','AWD',5,'sold'),
(@seller_id,'Lexus','RX','350 Luxury',2023,'3.5',13000,'AT',335000.00,'Petrol','AWD',5,'sold'),
(@seller_id,'Lexus','RX','300 Premium',2015,'2.0',98000,'AT',145000.00,'Petrol','FWD',5,'sold'),
(@seller_id,'Lexus','RX','450h',2016,'3.5',90000,'AT',195000.00,'Hybrid','AWD',5,'sold'),
(@seller_id,'Lexus','RX','300 F Sport',2017,'2.0',82000,'AT',215000.00,'Petrol','AWD',5,'sold'),
(@seller_id,'Lexus','RX','350 Luxury',2018,'3.5',70000,'AT',235000.00,'Petrol','AWD',5,'sold'),
-- IS (20-39) x20
(@seller_id,'Lexus','IS','200t',2016,'2.0',90000,'AT',115000.00,'Petrol','RWD',4,'sold'),
(@seller_id,'Lexus','IS','300',2017,'2.0',82000,'AT',125000.00,'Petrol','RWD',4,'sold'),
(@seller_id,'Lexus','IS','300h',2018,'2.5',70000,'AT',145000.00,'Hybrid','RWD',4,'sold'),
(@seller_id,'Lexus','IS','350 F Sport',2019,'3.5',52000,'AT',175000.00,'Petrol','RWD',4,'sold'),
(@seller_id,'Lexus','IS','300',2020,'2.0',42000,'AT',185000.00,'Petrol','RWD',4,'sold'),
(@seller_id,'Lexus','IS','300h',2021,'2.5',32000,'AT',205000.00,'Hybrid','RWD',4,'sold'),
(@seller_id,'Lexus','IS','300',2022,'2.0',21000,'AT',215000.00,'Petrol','RWD',4,'sold'),
(@seller_id,'Lexus','IS','350 F Sport',2023,'3.5',14000,'AT',245000.00,'Petrol','RWD',4,'sold'),
(@seller_id,'Lexus','IS','200t',2015,'2.0',97000,'AT',105000.00,'Petrol','RWD',4,'sold'),
(@seller_id,'Lexus','IS','300',2016,'2.0',90000,'AT',115000.00,'Petrol','RWD',4,'sold'),
(@seller_id,'Lexus','IS','300h',2017,'2.5',82000,'AT',135000.00,'Hybrid','RWD',4,'sold'),
(@seller_id,'Lexus','IS','350 F Sport',2018,'3.5',70000,'AT',165000.00,'Petrol','RWD',4,'sold'),
(@seller_id,'Lexus','IS','300',2019,'2.0',54000,'AT',175000.00,'Petrol','RWD',4,'sold'),
(@seller_id,'Lexus','IS','300h',2020,'2.5',43000,'AT',185000.00,'Hybrid','RWD',4,'sold'),
(@seller_id,'Lexus','IS','300',2021,'2.0',33000,'AT',195000.00,'Petrol','RWD',4,'sold'),
(@seller_id,'Lexus','IS','350 F Sport',2022,'3.5',22000,'AT',225000.00,'Petrol','RWD',4,'sold'),
(@seller_id,'Lexus','IS','300',2023,'2.0',15000,'AT',235000.00,'Petrol','RWD',4,'sold'),
(@seller_id,'Lexus','IS','200t',2014,'2.0',102000,'AT',95000.00,'Petrol','RWD',4,'sold'),
(@seller_id,'Lexus','IS','300',2015,'2.0',96000,'AT',105000.00,'Petrol','RWD',4,'sold'),
(@seller_id,'Lexus','IS','300h',2016,'2.5',90000,'AT',125000.00,'Hybrid','RWD',4,'sold'),
-- ES/UX/NX mix (40-59) x20
(@seller_id,'Lexus','ES','250 Luxury',2017,'2.5',82000,'AT',155000.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Lexus','ES','300h',2018,'2.5',74000,'AT',175000.00,'Hybrid','FWD',4,'sold'),
(@seller_id,'Lexus','UX','200',2019,'2.0',56000,'AT',165000.00,'Petrol','FWD',5,'sold'),
(@seller_id,'Lexus','UX','250h',2020,'2.0',43000,'AT',185000.00,'Hybrid','FWD',5,'sold'),
(@seller_id,'Lexus','NX','200t',2017,'2.0',90000,'AT',145000.00,'Petrol','AWD',5,'sold'),
(@seller_id,'Lexus','NX','300',2018,'2.0',82000,'AT',155000.00,'Petrol','AWD',5,'sold'),
(@seller_id,'Lexus','NX','300h',2019,'2.5',70000,'AT',175000.00,'Hybrid','AWD',5,'sold'),
(@seller_id,'Lexus','ES','250 Luxury',2020,'2.5',42000,'AT',185000.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Lexus','ES','300h',2021,'2.5',32000,'AT',205000.00,'Hybrid','FWD',4,'sold'),
(@seller_id,'Lexus','UX','200',2022,'2.0',21000,'AT',195000.00,'Petrol','FWD',5,'sold'),
(@seller_id,'Lexus','UX','250h',2023,'2.0',13000,'AT',215000.00,'Hybrid','FWD',5,'sold'),
(@seller_id,'Lexus','NX','200t',2015,'2.0',98000,'AT',125000.00,'Petrol','AWD',5,'sold'),
(@seller_id,'Lexus','NX','300',2016,'2.0',90000,'AT',135000.00,'Petrol','AWD',5,'sold'),
(@seller_id,'Lexus','NX','300h',2017,'2.5',82000,'AT',155000.00,'Hybrid','AWD',5,'sold'),
(@seller_id,'Lexus','ES','250 Luxury',2018,'2.5',74000,'AT',165000.00,'Petrol','FWD',4,'sold'),
(@seller_id,'Lexus','ES','300h',2019,'2.5',56000,'AT',175000.00,'Hybrid','FWD',4,'sold'),
(@seller_id,'Lexus','UX','200',2020,'2.0',43000,'AT',185000.00,'Petrol','FWD',5,'sold'),
(@seller_id,'Lexus','UX','250h',2021,'2.0',33000,'AT',195000.00,'Hybrid','FWD',5,'sold'),
(@seller_id,'Lexus','NX','300',2022,'2.0',22000,'AT',205000.00,'Petrol','AWD',5,'sold'),
(@seller_id,'Lexus','NX','300h',2023,'2.5',15000,'AT',235000.00,'Hybrid','AWD',5,'sold');

SET @base_lexus := LAST_INSERT_ID();

-- 60 thumbnails
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES
(@base_lexus+0,'uploads/ativa(1).webp',1),(@base_lexus+1,'uploads/ativa(1).webp',1),(@base_lexus+2,'uploads/ativa(1).webp',1),(@base_lexus+3,'uploads/ativa(1).webp',1),
(@base_lexus+4,'uploads/ativa(1).webp',1),(@base_lexus+5,'uploads/ativa(1).webp',1),(@base_lexus+6,'uploads/ativa(1).webp',1),(@base_lexus+7,'uploads/ativa(1).webp',1),
(@base_lexus+8,'uploads/ativa(1).webp',1),(@base_lexus+9,'uploads/ativa(1).webp',1),(@base_lexus+10,'uploads/ativa(1).webp',1),(@base_lexus+11,'uploads/ativa(1).webp',1),
(@base_lexus+12,'uploads/ativa(1).webp',1),(@base_lexus+13,'uploads/ativa(1).webp',1),(@base_lexus+14,'uploads/ativa(1).webp',1),(@base_lexus+15,'uploads/ativa(1).webp',1),
(@base_lexus+16,'uploads/ativa(1).webp',1),(@base_lexus+17,'uploads/ativa(1).webp',1),(@base_lexus+18,'uploads/ativa(1).webp',1),(@base_lexus+19,'uploads/ativa(1).webp',1),
(@base_lexus+20,'uploads/ativa(1).webp',1),(@base_lexus+21,'uploads/ativa(1).webp',1),(@base_lexus+22,'uploads/ativa(1).webp',1),(@base_lexus+23,'uploads/ativa(1).webp',1),
(@base_lexus+24,'uploads/ativa(1).webp',1),(@base_lexus+25,'uploads/ativa(1).webp',1),(@base_lexus+26,'uploads/ativa(1).webp',1),(@base_lexus+27,'uploads/ativa(1).webp',1),
(@base_lexus+28,'uploads/ativa(1).webp',1),(@base_lexus+29,'uploads/ativa(1).webp',1),(@base_lexus+30,'uploads/ativa(1).webp',1),(@base_lexus+31,'uploads/ativa(1).webp',1),
(@base_lexus+32,'uploads/ativa(1).webp',1),(@base_lexus+33,'uploads/ativa(1).webp',1),(@base_lexus+34,'uploads/ativa(1).webp',1),(@base_lexus+35,'uploads/ativa(1).webp',1),
(@base_lexus+36,'uploads/ativa(1).webp',1),(@base_lexus+37,'uploads/ativa(1).webp',1),(@base_lexus+38,'uploads/ativa(1).webp',1),(@base_lexus+39,'uploads/ativa(1).webp',1),
(@base_lexus+40,'uploads/ativa(1).webp',1),(@base_lexus+41,'uploads/ativa(1).webp',1),(@base_lexus+42,'uploads/ativa(1).webp',1),(@base_lexus+43,'uploads/ativa(1).webp',1),
(@base_lexus+44,'uploads/ativa(1).webp',1),(@base_lexus+45,'uploads/ativa(1).webp',1),(@base_lexus+46,'uploads/ativa(1).webp',1),(@base_lexus+47,'uploads/ativa(1).webp',1),
(@base_lexus+48,'uploads/ativa(1).webp',1),(@base_lexus+49,'uploads/ativa(1).webp',1),(@base_lexus+50,'uploads/ativa(1).webp',1),(@base_lexus+51,'uploads/ativa(1).webp',1),
(@base_lexus+52,'uploads/ativa(1).webp',1),(@base_lexus+53,'uploads/ativa(1).webp',1),(@base_lexus+54,'uploads/ativa(1).webp',1),(@base_lexus+55,'uploads/ativa(1).webp',1),
(@base_lexus+56,'uploads/ativa(1).webp',1),(@base_lexus+57,'uploads/ativa(1).webp',1),(@base_lexus+58,'uploads/ativa(1).webp',1),(@base_lexus+59,'uploads/ativa(1).webp',1);

-- 60 details with mixed car_condition
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note)
VALUES
-- RX (0-19)
(@base_lexus+0,'White',295,'2GR-FKS',8,'235/55 R20','235/55 R20',362,'SUV','Used','Luxury SUV.'),
(@base_lexus+1,'Grey',235,'8AR-FTS',6,'235/60 R18','235/60 R18',350,'SUV','Reconditioned','Premium trim recond.'),
(@base_lexus+2,'Black',308,'2GR-FXS',8,'235/55 R20','235/55 R20',335,'SUV','Used','Hybrid efficiency.'),
(@base_lexus+3,'White',238,'8AR-FTS',6,'235/55 R20','235/55 R20',350,'SUV','Reconditioned','F Sport recond.'),
(@base_lexus+4,'Silver',295,'2GR-FKS',8,'235/55 R20','235/55 R20',362,'SUV','Used','Low mileage.'),
(@base_lexus+5,'Blue',238,'8AR-FTS',6,'235/60 R18','235/60 R18',350,'SUV','Reconditioned','Well kept.'),
(@base_lexus+6,'White',295,'2GR-FKS',8,'235/55 R20','235/55 R20',362,'SUV','New','Unregistered new.'),
(@base_lexus+7,'Grey',235,'8AR-FTS',6,'235/60 R18','235/60 R18',350,'SUV','Used','Clean interior.'),
(@base_lexus+8,'White',308,'2GR-FXS',8,'235/55 R20','235/55 R20',335,'SUV','Reconditioned','Hybrid premium recond.'),
(@base_lexus+9,'Grey',238,'8AR-FTS',6,'235/55 R20','235/55 R20',350,'SUV','Used','Daily drive.'),
(@base_lexus+10,'Silver',238,'8AR-FTS',6,'235/60 R18','235/60 R18',350,'SUV','Reconditioned','New paint.'),
(@base_lexus+11,'Blue',238,'8AR-FTS',6,'235/55 R20','235/55 R20',350,'SUV','Used','Well maintained.'),
(@base_lexus+12,'White',295,'2GR-FKS',8,'235/55 R20','235/55 R20',362,'SUV','Reconditioned','Top spec recond.'),
(@base_lexus+13,'Grey',235,'8AR-FTS',6,'235/60 R18','235/60 R18',350,'SUV','Used','Trusted unit.'),
(@base_lexus+14,'Red',238,'8AR-FTS',6,'235/55 R20','235/55 R20',350,'SUV','Reconditioned','F Sport recond.'),
(@base_lexus+15,'White',295,'2GR-FKS',8,'235/55 R20','235/55 R20',362,'SUV','Used','Low mileage.'),
(@base_lexus+16,'Grey',235,'8AR-FTS',6,'235/60 R18','235/60 R18',350,'SUV','Reconditioned','Like new.'),
(@base_lexus+17,'Black',238,'8AR-FTS',6,'235/55 R20','235/55 R20',350,'SUV','Used','Clean unit.'),
(@base_lexus+18,'White',308,'2GR-FXS',8,'235/55 R20','235/55 R20',335,'SUV','Reconditioned','Hybrid maintained.'),
(@base_lexus+19,'Grey',295,'2GR-FKS',8,'235/55 R20','235/55 R20',362,'SUV','New','Unregistered new.'),
-- IS (20-39)
(@base_lexus+20,'Silver',241,'8AR-FTS',8,'225/45 R17','225/45 R17',350,'Sedan','Used','Sporty sedan.'),
(@base_lexus+21,'White',241,'8AR-FTS',8,'225/45 R17','225/45 R17',350,'Sedan','Reconditioned','Well kept.'),
(@base_lexus+22,'Grey',223,'A25A-FXS',8,'225/45 R17','225/45 R17',221,'Sedan','Used','Hybrid efficiency.'),
(@base_lexus+23,'Blue',311,'2GR-FKS',8,'235/40 R19','235/40 R19',380,'Sedan','Reconditioned','F Sport recond.'),
(@base_lexus+24,'Silver',241,'8AR-FTS',8,'225/45 R17','225/45 R17',350,'Sedan','Used','Low mileage.'),
(@base_lexus+25,'White',223,'A25A-FXS',8,'225/45 R17','225/45 R17',221,'Sedan','Reconditioned','Clean unit.'),
(@base_lexus+26,'Grey',241,'8AR-FTS',8,'225/45 R17','225/45 R17',350,'Sedan','New','Unregistered new.'),
(@base_lexus+27,'Black',241,'8AR-FTS',8,'225/45 R17','225/45 R17',350,'Sedan','Used','Reliable.'),
(@base_lexus+28,'White',241,'8AR-FTS',8,'225/45 R17','225/45 R17',350,'Sedan','Reconditioned','Well kept.'),
(@base_lexus+29,'Grey',241,'8AR-FTS',8,'225/45 R17','225/45 R17',350,'Sedan','Used','Clean interior.'),
(@base_lexus+30,'Silver',241,'8AR-FTS',8,'225/45 R17','225/45 R17',350,'Sedan','Reconditioned','New paint.'),
(@base_lexus+31,'Blue',241,'8AR-FTS',8,'225/45 R17','225/45 R17',350,'Sedan','Used','Daily drive.'),
(@base_lexus+32,'White',241,'8AR-FTS',8,'225/45 R17','225/45 R17',350,'Sedan','Reconditioned','Value buy recond.'),
(@base_lexus+33,'Grey',241,'8AR-FTS',8,'225/45 R17','225/45 R17',350,'Sedan','Used','Trusted unit.'),
(@base_lexus+34,'Red',311,'2GR-FKS',8,'235/40 R19','235/40 R19',380,'Sedan','Reconditioned','F Sport recond.'),
(@base_lexus+35,'White',241,'8AR-FTS',8,'225/45 R17','225/45 R17',350,'Sedan','Used','Low mileage.'),
(@base_lexus+36,'Grey',223,'A25A-FXS',8,'225/45 R17','225/45 R17',221,'Sedan','Reconditioned','Like new.'),
(@base_lexus+37,'Black',241,'8AR-FTS',8,'225/45 R17','225/45 R17',350,'Sedan','Used','Clean unit.'),
(@base_lexus+38,'White',223,'A25A-FXS',8,'225/45 R17','225/45 R17',221,'Sedan','Reconditioned','Hybrid maintained.'),
(@base_lexus+39,'Grey',241,'8AR-FTS',8,'225/45 R17','225/45 R17',350,'Sedan','New','Unregistered new.'),
-- ES/UX/NX mix (40-59)
(@base_lexus+40,'Silver',204,'2AR-FE',6,'215/55 R17','215/55 R17',247,'Sedan','Used','Comfort executive.'),
(@base_lexus+41,'White',215,'A25A-FXS',8,'215/55 R17','215/55 R17',221,'Sedan','Reconditioned','Hybrid comfort.'),
(@base_lexus+42,'Grey',169,'M20A-FKS',10,'215/60 R17','215/60 R17',205,'SUV','Used','Urban crossover.'),
(@base_lexus+43,'Blue',181,'M20A-FXS',10,'215/60 R17','215/60 R17',202,'SUV','Reconditioned','Hybrid UX recond.'),
(@base_lexus+44,'Silver',235,'8AR-FTS',6,'225/60 R18','225/60 R18',350,'SUV','Used','NX turbo.'),
(@base_lexus+45,'White',238,'8AR-FTS',6,'225/60 R18','225/60 R18',350,'SUV','Reconditioned','NX clean recond.'),
(@base_lexus+46,'Grey',197,'A25A-FXS',10,'225/60 R18','225/60 R18',210,'SUV','New','Unregistered new.'),
(@base_lexus+47,'Black',204,'2AR-FE',6,'215/55 R17','215/55 R17',247,'Sedan','Used','Smooth ES.'),
(@base_lexus+48,'White',215,'A25A-FXS',8,'215/55 R17','215/55 R17',221,'Sedan','Reconditioned','ES hybrid recond.'),
(@base_lexus+49,'Grey',169,'M20A-FKS',10,'215/60 R17','215/60 R17',205,'SUV','Used','UX daily.'),
(@base_lexus+50,'Silver',181,'M20A-FXS',10,'215/60 R17','215/60 R17',202,'SUV','Reconditioned','UX hybrid recond.'),
(@base_lexus+51,'Blue',235,'8AR-FTS',6,'225/60 R18','225/60 R18',350,'SUV','Used','NX clean.'),
(@base_lexus+52,'White',238,'8AR-FTS',6,'225/60 R18','225/60 R18',350,'SUV','Reconditioned','NX maintained.'),
(@base_lexus+53,'Grey',197,'A25A-FXS',10,'225/60 R18','225/60 R18',210,'SUV','Used','Latest gen.'),
(@base_lexus+54,'Red',204,'2AR-FE',6,'215/55 R17','215/55 R17',247,'Sedan','Reconditioned','Executive.'),
(@base_lexus+55,'White',215,'A25A-FXS',8,'215/55 R17','215/55 R17',221,'Sedan','Used','Comfort ride.'),
(@base_lexus+56,'Grey',169,'M20A-FKS',10,'215/60 R17','215/60 R17',205,'SUV','Reconditioned','Urban UX.'),
(@base_lexus+57,'Black',181,'M20A-FXS',10,'215/60 R17','215/60 R17',202,'SUV','Used','Hybrid reliable.'),
(@base_lexus+58,'White',235,'8AR-FTS',6,'225/60 R18','225/60 R18',350,'SUV','Reconditioned','NX turbo recond.'),
(@base_lexus+59,'Grey',197,'A25A-FXS',10,'225/60 R18','225/60 R18',210,'SUV','New','Unregistered new.');

COMMIT;
