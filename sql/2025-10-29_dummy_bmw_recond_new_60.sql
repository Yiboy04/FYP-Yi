-- Dummy bulk data: 60 BMW cars (Reconditioned/New)
-- Models from data/makes_models_my.json (BMW list).
-- All rows use seller_id=5 and listing_status='sold'.
-- Images reuse 'uploads/ativa(1).webp' as a thumbnail placeholder.

START TRANSACTION;

SET @seller_id := 5;  -- consistent seller

-- BMW (60)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES
-- 3 Series (0-11) x12
(@seller_id,'BMW','3 Series','320i',2016,'2.0',78000,'AT',89500.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'BMW','3 Series','320i',2017,'2.0',65000,'AT',96500.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'BMW','3 Series','330i M Sport',2018,'2.0',58000,'AT',128000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'BMW','3 Series','330e Plug-in',2018,'2.0',52000,'AT',135000.00,'Hybrid','RWD',4,'sold'),
(@seller_id,'BMW','3 Series','320i Sport',2019,'2.0',42000,'AT',148000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'BMW','3 Series','330i M Sport',2019,'2.0',39000,'AT',158000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'BMW','3 Series','320i',2020,'2.0',32000,'AT',168000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'BMW','3 Series','330e Plug-in',2020,'2.0',29000,'AT',178000.00,'Hybrid','RWD',4,'sold'),
(@seller_id,'BMW','3 Series','320i',2021,'2.0',21000,'AT',188000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'BMW','3 Series','330i M Sport',2021,'2.0',19000,'AT',208000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'BMW','3 Series','320i',2022,'2.0',14000,'AT',218000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'BMW','3 Series','330e Plug-in',2022,'2.0',12000,'AT',228000.00,'Hybrid','RWD',4,'sold'),
-- 5 Series (12-19) x8
(@seller_id,'BMW','5 Series','520i Luxury',2016,'2.0',82000,'AT',115000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'BMW','5 Series','520d',2017,'2.0',72000,'AT',125000.00,'Diesel','RWD',4,'sold'),
(@seller_id,'BMW','5 Series','530e Plug-in',2018,'2.0',56000,'AT',168000.00,'Hybrid','RWD',4,'sold'),
(@seller_id,'BMW','5 Series','530i M Sport',2019,'2.0',46000,'AT',198000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'BMW','5 Series','530e Plug-in',2020,'2.0',35000,'AT',218000.00,'Hybrid','RWD',4,'sold'),
(@seller_id,'BMW','5 Series','520i Luxury',2020,'2.0',30000,'AT',205000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'BMW','5 Series','530e Plug-in',2021,'2.0',23000,'AT',235000.00,'Hybrid','RWD',4,'sold'),
(@seller_id,'BMW','5 Series','530i M Sport',2022,'2.0',15000,'AT',255000.00,'Gasoline','RWD',4,'sold'),
-- 1 Series (20-23) x4
(@seller_id,'BMW','1 Series','118i',2017,'1.5',61000,'AT',86500.00,'Gasoline','RWD',5,'sold'),
(@seller_id,'BMW','1 Series','118i',2018,'1.5',54000,'AT',92500.00,'Gasoline','RWD',5,'sold'),
(@seller_id,'BMW','1 Series','118i',2019,'1.5',43000,'AT',98500.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'BMW','1 Series','M135i xDrive',2020,'2.0',28000,'AT',178000.00,'Gasoline','AWD',5,'sold'),
-- 2 Series (24-27) x4
(@seller_id,'BMW','2 Series','218i Gran Coupe',2020,'1.5',26000,'AT',155000.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'BMW','2 Series','218i Gran Coupe',2021,'1.5',18000,'AT',168000.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'BMW','2 Series','220i Coupe',2018,'2.0',48000,'AT',135000.00,'Gasoline','RWD',2,'sold'),
(@seller_id,'BMW','2 Series','M235i xDrive',2020,'2.0',22000,'AT',198000.00,'Gasoline','AWD',2,'sold'),
-- 4 Series (28-31) x4
(@seller_id,'BMW','4 Series','420i Coupe',2017,'2.0',59000,'AT',138000.00,'Gasoline','RWD',2,'sold'),
(@seller_id,'BMW','4 Series','430i Coupe',2019,'2.0',41000,'AT',178000.00,'Gasoline','RWD',2,'sold'),
(@seller_id,'BMW','4 Series','420i Gran Coupe',2020,'2.0',28000,'AT',188000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'BMW','4 Series','430i M Sport',2021,'2.0',20000,'AT',228000.00,'Gasoline','RWD',2,'sold'),
-- 7 Series (32-33) x2
(@seller_id,'BMW','7 Series','730Li',2016,'2.0',72000,'AT',178000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'BMW','7 Series','740Le',2018,'2.0',56000,'AT',228000.00,'Hybrid','RWD',4,'sold'),
-- X1 (34-37) x4
(@seller_id,'BMW','X1','sDrive18i',2017,'1.5',67000,'AT',105000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'BMW','X1','sDrive18i',2018,'1.5',59000,'AT',115000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'BMW','X1','sDrive20i',2019,'2.0',43000,'AT',135000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'BMW','X1','sDrive20i',2020,'2.0',29000,'AT',155000.00,'Gasoline','FWD',5,'sold'),
-- X3 (38-43) x6
(@seller_id,'BMW','X3','xDrive20i',2017,'2.0',74000,'AT',138000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'BMW','X3','xDrive20d',2018,'2.0',61000,'AT',158000.00,'Diesel','AWD',5,'sold'),
(@seller_id,'BMW','X3','xDrive30i',2019,'2.0',48000,'AT',188000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'BMW','X3','xDrive30i',2020,'2.0',36000,'AT',208000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'BMW','X3','xDrive30e',2021,'2.0',24000,'AT',238000.00,'Hybrid','AWD',5,'sold'),
(@seller_id,'BMW','X3','xDrive30i',2022,'2.0',16000,'AT',258000.00,'Gasoline','AWD',5,'sold'),
-- X5 (44-49) x6
(@seller_id,'BMW','X5','xDrive30d',2016,'3.0',78000,'AT',168000.00,'Diesel','AWD',5,'sold'),
(@seller_id,'BMW','X5','xDrive40e',2017,'2.0',72000,'AT',185000.00,'Hybrid','AWD',5,'sold'),
(@seller_id,'BMW','X5','xDrive40i',2019,'3.0',46000,'AT',298000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'BMW','X5','xDrive45e',2020,'3.0',35000,'AT',338000.00,'Hybrid','AWD',5,'sold'),
(@seller_id,'BMW','X5','xDrive40i',2021,'3.0',24000,'AT',358000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'BMW','X5','xDrive45e',2022,'3.0',17000,'AT',388000.00,'Hybrid','AWD',5,'sold'),
-- X6 (50-51) x2
(@seller_id,'BMW','X6','xDrive35i',2017,'3.0',69000,'AT',228000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'BMW','X6','xDrive40i',2020,'3.0',32000,'AT',358000.00,'Gasoline','AWD',5,'sold'),
-- X7 (52-53) x2
(@seller_id,'BMW','X7','xDrive40i',2020,'3.0',30000,'AT',458000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'BMW','X7','xDrive40i',2021,'3.0',22000,'AT',488000.00,'Gasoline','AWD',5,'sold'),
-- i3 (54-55) x2 (EV)
(@seller_id,'BMW','i3','94Ah',2017,'0.0',52000,'AT',108000.00,'Electric','RWD',4,'sold'),
(@seller_id,'BMW','i3','120Ah',2019,'0.0',36000,'AT',138000.00,'Electric','RWD',4,'sold'),
-- i4 (56-57) x2 (EV)
(@seller_id,'BMW','i4','eDrive40',2022,'0.0',14000,'AT',355000.00,'Electric','RWD',4,'sold'),
(@seller_id,'BMW','i4','M50 xDrive',2022,'0.0',12000,'AT',385000.00,'Electric','AWD',4,'sold'),
-- iX (58-59) x2 (EV)
(@seller_id,'BMW','iX','xDrive40',2022,'0.0',12000,'AT',395000.00,'Electric','AWD',5,'sold'),
(@seller_id,'BMW','iX','xDrive50',2023,'0.0',8000,'AT',465000.00,'Electric','AWD',5,'sold');

SET @base_bmw := LAST_INSERT_ID();

-- 60 thumbnails
INSERT INTO car_images (car_id, image_path, is_thumbnail) VALUES
(@base_bmw+0,'uploads/ativa(1).webp',1),(@base_bmw+1,'uploads/ativa(1).webp',1),(@base_bmw+2,'uploads/ativa(1).webp',1),(@base_bmw+3,'uploads/ativa(1).webp',1),
(@base_bmw+4,'uploads/ativa(1).webp',1),(@base_bmw+5,'uploads/ativa(1).webp',1),(@base_bmw+6,'uploads/ativa(1).webp',1),(@base_bmw+7,'uploads/ativa(1).webp',1),
(@base_bmw+8,'uploads/ativa(1).webp',1),(@base_bmw+9,'uploads/ativa(1).webp',1),(@base_bmw+10,'uploads/ativa(1).webp',1),(@base_bmw+11,'uploads/ativa(1).webp',1),
(@base_bmw+12,'uploads/ativa(1).webp',1),(@base_bmw+13,'uploads/ativa(1).webp',1),(@base_bmw+14,'uploads/ativa(1).webp',1),(@base_bmw+15,'uploads/ativa(1).webp',1),
(@base_bmw+16,'uploads/ativa(1).webp',1),(@base_bmw+17,'uploads/ativa(1).webp',1),(@base_bmw+18,'uploads/ativa(1).webp',1),(@base_bmw+19,'uploads/ativa(1).webp',1),
(@base_bmw+20,'uploads/ativa(1).webp',1),(@base_bmw+21,'uploads/ativa(1).webp',1),(@base_bmw+22,'uploads/ativa(1).webp',1),(@base_bmw+23,'uploads/ativa(1).webp',1),
(@base_bmw+24,'uploads/ativa(1).webp',1),(@base_bmw+25,'uploads/ativa(1).webp',1),(@base_bmw+26,'uploads/ativa(1).webp',1),(@base_bmw+27,'uploads/ativa(1).webp',1),
(@base_bmw+28,'uploads/ativa(1).webp',1),(@base_bmw+29,'uploads/ativa(1).webp',1),(@base_bmw+30,'uploads/ativa(1).webp',1),(@base_bmw+31,'uploads/ativa(1).webp',1),
(@base_bmw+32,'uploads/ativa(1).webp',1),(@base_bmw+33,'uploads/ativa(1).webp',1),(@base_bmw+34,'uploads/ativa(1).webp',1),(@base_bmw+35,'uploads/ativa(1).webp',1),
(@base_bmw+36,'uploads/ativa(1).webp',1),(@base_bmw+37,'uploads/ativa(1).webp',1),(@base_bmw+38,'uploads/ativa(1).webp',1),(@base_bmw+39,'uploads/ativa(1).webp',1),
(@base_bmw+40,'uploads/ativa(1).webp',1),(@base_bmw+41,'uploads/ativa(1).webp',1),(@base_bmw+42,'uploads/ativa(1).webp',1),(@base_bmw+43,'uploads/ativa(1).webp',1),
(@base_bmw+44,'uploads/ativa(1).webp',1),(@base_bmw+45,'uploads/ativa(1).webp',1),(@base_bmw+46,'uploads/ativa(1).webp',1),(@base_bmw+47,'uploads/ativa(1).webp',1),
(@base_bmw+48,'uploads/ativa(1).webp',1),(@base_bmw+49,'uploads/ativa(1).webp',1),(@base_bmw+50,'uploads/ativa(1).webp',1),(@base_bmw+51,'uploads/ativa(1).webp',1),
(@base_bmw+52,'uploads/ativa(1).webp',1),(@base_bmw+53,'uploads/ativa(1).webp',1),(@base_bmw+54,'uploads/ativa(1).webp',1),(@base_bmw+55,'uploads/ativa(1).webp',1),
(@base_bmw+56,'uploads/ativa(1).webp',1),(@base_bmw+57,'uploads/ativa(1).webp',1),(@base_bmw+58,'uploads/ativa(1).webp',1),(@base_bmw+59,'uploads/ativa(1).webp',1);

-- 60 details with car_condition = 'Reconditioned' or 'New'
INSERT INTO car_details (car_id,color,horsepower,engine_code,gear_numbers,front_wheel_size,rear_wheel_size,torque,car_type,car_condition,seller_note)
VALUES
-- 3 Series (0-11)
(@base_bmw+0,'White',184,'B48',8,'225/50 R17','225/50 R17',300,'Sedan','Reconditioned','Recond 320i, clean unit.'),
(@base_bmw+1,'Black',184,'B48',8,'225/50 R17','225/50 R17',300,'Sedan','Reconditioned','Service on time.'),
(@base_bmw+2,'Blue',258,'B48',8,'225/45 R18','255/40 R18',400,'Sedan','Reconditioned','M Sport package.'),
(@base_bmw+3,'Grey',252,'B48 PHEV',8,'225/45 R18','255/40 R18',420,'Sedan','Reconditioned','330e plug-in recond.'),
(@base_bmw+4,'Silver',184,'B48',8,'225/50 R17','225/50 R17',300,'Sedan','Reconditioned','Low mileage recond.'),
(@base_bmw+5,'White',258,'B48',8,'225/45 R18','255/40 R18',400,'Sedan','Reconditioned','Well-kept.'),
(@base_bmw+6,'Grey',184,'B48',8,'225/50 R17','225/50 R17',300,'Sedan','New','Brand new MY unit.'),
(@base_bmw+7,'Black',252,'B48 PHEV',8,'225/45 R18','255/40 R18',420,'Sedan','New','Unregistered new.'),
(@base_bmw+8,'White',184,'B48',8,'225/50 R17','225/50 R17',300,'Sedan','Reconditioned','Comfort spec recond.'),
(@base_bmw+9,'Blue',258,'B48',8,'225/45 R18','255/40 R18',400,'Sedan','Reconditioned','M Sport recond.'),
(@base_bmw+10,'Grey',184,'B48',8,'225/50 R17','225/50 R17',300,'Sedan','New','Brand new MY unit.'),
(@base_bmw+11,'Black',252,'B48 PHEV',8,'225/45 R18','255/40 R18',420,'Sedan','New','Unregistered new.'),
-- 5 Series (12-19)
(@base_bmw+12,'White',184,'B48',8,'245/45 R18','245/45 R18',290,'Sedan','Reconditioned','520i Luxury recond.'),
(@base_bmw+13,'Grey',190,'B47',8,'245/45 R18','245/45 R18',400,'Sedan','Reconditioned','520d efficient.'),
(@base_bmw+14,'Blue',252,'B48 PHEV',8,'245/40 R19','275/35 R19',420,'Sedan','Reconditioned','530e plug-in recond.'),
(@base_bmw+15,'Black',252,'B48',8,'245/40 R19','275/35 R19',400,'Sedan','Reconditioned','530i M Sport recond.'),
(@base_bmw+16,'White',292,'B58 PHEV',8,'245/40 R19','275/35 R19',450,'Sedan','Reconditioned','530e facelift.'),
(@base_bmw+17,'Silver',184,'B48',8,'245/45 R18','245/45 R18',290,'Sedan','New','Brand new MY unit.'),
(@base_bmw+18,'Grey',292,'B58 PHEV',8,'245/40 R19','275/35 R19',450,'Sedan','New','Unregistered new.'),
(@base_bmw+19,'Black',252,'B48',8,'245/40 R19','275/35 R19',400,'Sedan','New','Unregistered new.'),
-- 1 Series (20-23)
(@base_bmw+20,'Blue',136,'B38',7,'225/45 R17','225/45 R17',220,'Hatchback','Reconditioned','118i recond.'),
(@base_bmw+21,'White',136,'B38',7,'225/45 R17','225/45 R17',220,'Hatchback','Reconditioned','Clean interior.'),
(@base_bmw+22,'Grey',136,'B38',7,'225/45 R17','225/45 R17',220,'Hatchback','Reconditioned','F40 gen FWD.'),
(@base_bmw+23,'Black',306,'B48',8,'235/40 R18','235/40 R18',450,'Hatchback','New','Unregistered new.'),
-- 2 Series (24-27)
(@base_bmw+24,'White',140,'B38',7,'225/40 R18','225/40 R18',220,'Sedan','Reconditioned','Gran Coupe recond.'),
(@base_bmw+25,'Grey',140,'B38',7,'225/40 R18','225/40 R18',220,'Sedan','New','Brand new MY unit.'),
(@base_bmw+26,'Blue',184,'B48',8,'225/45 R18','245/40 R18',300,'Coupe','Reconditioned','220i coupe recond.'),
(@base_bmw+27,'Black',306,'B48',8,'235/40 R18','255/35 R18',450,'Coupe','New','Unregistered new.'),
-- 4 Series (28-31)
(@base_bmw+28,'White',184,'B48',8,'225/45 R18','255/40 R18',300,'Coupe','Reconditioned','420i coupe recond.'),
(@base_bmw+29,'Grey',258,'B48',8,'225/45 R18','255/40 R18',400,'Coupe','Reconditioned','430i coupe recond.'),
(@base_bmw+30,'Blue',184,'B48',8,'225/45 R18','255/40 R18',300,'Sedan','Reconditioned','Gran Coupe recond.'),
(@base_bmw+31,'Black',258,'B48',8,'225/45 R18','255/40 R18',400,'Coupe','New','Unregistered new.'),
-- 7 Series (32-33)
(@base_bmw+32,'Black',258,'B48',8,'245/45 R19','275/40 R19',400,'Sedan','Reconditioned','730Li recond.'),
(@base_bmw+33,'White',326,'B48 PHEV',8,'245/45 R19','275/40 R19',500,'Sedan','Reconditioned','740Le plug-in recond.'),
-- X1 (34-37)
(@base_bmw+34,'White',140,'B38',7,'225/50 R18','225/50 R18',220,'SUV','Reconditioned','sDrive18i recond.'),
(@base_bmw+35,'Grey',140,'B38',7,'225/50 R18','225/50 R18',220,'SUV','Reconditioned','Well maintained.'),
(@base_bmw+36,'Blue',184,'B48',8,'225/50 R18','225/50 R18',280,'SUV','Reconditioned','sDrive20i recond.'),
(@base_bmw+37,'Black',184,'B48',8,'225/50 R18','225/50 R18',280,'SUV','New','Brand new MY unit.'),
-- X3 (38-43)
(@base_bmw+38,'White',184,'B48',8,'245/50 R19','245/50 R19',300,'SUV','Reconditioned','xDrive20i recond.'),
(@base_bmw+39,'Grey',190,'B47',8,'245/50 R19','245/50 R19',400,'SUV','Reconditioned','xDrive20d recond.'),
(@base_bmw+40,'Blue',252,'B48',8,'245/45 R20','275/40 R20',350,'SUV','Reconditioned','xDrive30i recond.'),
(@base_bmw+41,'Black',252,'B48',8,'245/45 R20','275/40 R20',350,'SUV','Reconditioned','Facelift recond.'),
(@base_bmw+42,'White',292,'B58 PHEV',8,'245/45 R20','275/40 R20',450,'SUV','Reconditioned','xDrive30e recond.'),
(@base_bmw+43,'Grey',252,'B48',8,'245/45 R20','275/40 R20',350,'SUV','New','Unregistered new.'),
-- X5 (44-49)
(@base_bmw+44,'Black',258,'B57',8,'255/50 R19','285/45 R19',560,'SUV','Reconditioned','xDrive30d recond.'),
(@base_bmw+45,'White',313,'B48 PHEV',8,'255/50 R19','285/45 R19',450,'SUV','Reconditioned','xDrive40e recond.'),
(@base_bmw+46,'Grey',340,'B58',8,'275/45 R20','305/40 R20',450,'SUV','Reconditioned','xDrive40i recond.'),
(@base_bmw+47,'Blue',394,'B58 PHEV',8,'275/45 R20','305/40 R20',600,'SUV','Reconditioned','xDrive45e recond.'),
(@base_bmw+48,'White',340,'B58',8,'275/45 R20','305/40 R20',450,'SUV','New','Brand new MY unit.'),
(@base_bmw+49,'Black',394,'B58 PHEV',8,'275/45 R20','305/40 R20',600,'SUV','New','Unregistered new.'),
-- X6 (50-51)
(@base_bmw+50,'White',306,'B58',8,'275/45 R20','305/40 R20',450,'SUV','Reconditioned','xDrive35i recond.'),
(@base_bmw+51,'Grey',340,'B58',8,'275/45 R20','305/40 R20',450,'SUV','New','Unregistered new.'),
-- X7 (52-53)
(@base_bmw+52,'Black',340,'B58',8,'285/45 R21','315/40 R21',450,'SUV','Reconditioned','xDrive40i recond.'),
(@base_bmw+53,'White',340,'B58',8,'285/45 R21','315/40 R21',450,'SUV','New','Unregistered new.'),
-- i3 (54-55)
(@base_bmw+54,'White',170,'eDrive',1,'175/60 R19','195/50 R19',250,'Hatchback','Reconditioned','i3 94Ah recond.'),
(@base_bmw+55,'Grey',184,'eDrive',1,'175/60 R19','195/50 R19',270,'Hatchback','New','Unregistered new.'),
-- i4 (56-57)
(@base_bmw+56,'Blue',340,'eDrive',1,'245/45 R19','255/40 R19',430,'Sedan','Reconditioned','i4 eDrive40 recond.'),
(@base_bmw+57,'Black',544,'M eDrive',1,'245/45 R19','255/40 R19',795,'Sedan','New','Unregistered new.'),
-- iX (58-59)
(@base_bmw+58,'White',326,'eDrive',1,'235/60 R20','255/55 R20',630,'SUV','Reconditioned','iX xDrive40 recond.'),
(@base_bmw+59,'Grey',523,'eDrive',1,'255/50 R21','275/45 R21',765,'SUV','New','Unregistered new.');

COMMIT;
