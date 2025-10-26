-- Dummy bulk data: 50 sold Mercedes-Benz cars
-- All rows use seller_id=5 and listing_status='sold'.
-- Images reuse 'uploads/ativa(1).webp' as a thumbnail placeholder.
-- Skewed distribution example:
--   C200=10, GLC300=8, E200=7, A200=5, GLA200=5, C300=4, CLA250=3, GLE450=3, S450=2, E220d=2, C220d=1

START TRANSACTION;

SET @seller_id := 5;  -- Ensure this seller exists in your DB

-- MERCEDES-BENZ (50)
INSERT INTO cars (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status)
VALUES
-- C200 (1-10)
(@seller_id,'Mercedes-Benz','C200','Avantgarde',2016,'2.0',82000,'AT',148000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'Mercedes-Benz','C200','AMG Line',2017,'2.0',76000,'AT',158000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'Mercedes-Benz','C200','Avantgarde',2018,'1.5',63000,'AT',178000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'Mercedes-Benz','C200','AMG Line',2018,'1.5',61000,'AT',182000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'Mercedes-Benz','C200','AMG Line',2019,'1.5',52000,'AT',198000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'Mercedes-Benz','C200','Avantgarde',2019,'1.5',50000,'AT',192000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'Mercedes-Benz','C200','AMG Line',2020,'1.5',38000,'AT',208000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'Mercedes-Benz','C200','AMG Line',2021,'1.5',26000,'AT',228000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'Mercedes-Benz','C200','AMG Line',2022,'1.5',18000,'AT',248000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'Mercedes-Benz','C200','Avantgarde',2022,'1.5',17000,'AT',242000.00,'Gasoline','RWD',4,'sold'),
-- GLC300 (11-18)
(@seller_id,'Mercedes-Benz','GLC300','AMG Line',2017,'2.0',78000,'AT',228000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Mercedes-Benz','GLC300','AMG Line',2018,'2.0',69000,'AT',248000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Mercedes-Benz','GLC300','AMG Line',2019,'2.0',59000,'AT',278000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Mercedes-Benz','GLC300','AMG Line',2019,'2.0',56500,'AT',272000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Mercedes-Benz','GLC300','AMG Line',2020,'2.0',45000,'AT',305000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Mercedes-Benz','GLC300','AMG Line',2021,'2.0',32000,'AT',328000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Mercedes-Benz','GLC300','AMG Line',2021,'2.0',30000,'AT',322000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Mercedes-Benz','GLC300','AMG Line',2022,'2.0',21000,'AT',348000.00,'Gasoline','AWD',5,'sold'),
-- E200 (19-25)
(@seller_id,'Mercedes-Benz','E200','Avantgarde',2017,'2.0',82000,'AT',208000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'Mercedes-Benz','E200','Exclusive',2018,'2.0',74000,'AT',228000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'Mercedes-Benz','E200','Avantgarde',2019,'2.0',62000,'AT',258000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'Mercedes-Benz','E200','AMG Line',2019,'2.0',58500,'AT',268000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'Mercedes-Benz','E200','AMG Line',2020,'2.0',47000,'AT',298000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'Mercedes-Benz','E200','AMG Line',2021,'2.0',35000,'AT',318000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'Mercedes-Benz','E200','AMG Line',2022,'2.0',22000,'AT',348000.00,'Gasoline','RWD',4,'sold'),
-- A200 (26-30)
(@seller_id,'Mercedes-Benz','A200','Progressive Line',2019,'1.3',54000,'AT',145000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mercedes-Benz','A200','AMG Line',2019,'1.3',52000,'AT',152000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mercedes-Benz','A200','AMG Line',2020,'1.3',41000,'AT',165000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mercedes-Benz','A200','AMG Line',2021,'1.3',29000,'AT',178000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mercedes-Benz','A200','AMG Line',2022,'1.3',19000,'AT',192000.00,'Gasoline','FWD',5,'sold'),
-- GLA200 (31-35)
(@seller_id,'Mercedes-Benz','GLA200','Progressive Line',2018,'1.6',68000,'AT',158000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mercedes-Benz','GLA200','AMG Line',2019,'1.6',59000,'AT',178000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mercedes-Benz','GLA200','AMG Line',2020,'1.3',47000,'AT',198000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mercedes-Benz','GLA200','AMG Line',2021,'1.3',33000,'AT',214000.00,'Gasoline','FWD',5,'sold'),
(@seller_id,'Mercedes-Benz','GLA200','AMG Line',2022,'1.3',22000,'AT',228000.00,'Gasoline','FWD',5,'sold'),
-- C300 (36-39)
(@seller_id,'Mercedes-Benz','C300','AMG Line',2017,'2.0',72000,'AT',178000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'Mercedes-Benz','C300','AMG Line',2018,'2.0',64000,'AT',198000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'Mercedes-Benz','C300','AMG Line',2019,'2.0',56000,'AT',218000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'Mercedes-Benz','C300','AMG Line',2020,'2.0',42000,'AT',238000.00,'Gasoline','RWD',4,'sold'),
-- CLA250 (40-42)
(@seller_id,'Mercedes-Benz','CLA250','AMG Line',2018,'2.0',62000,'AT',185000.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Mercedes-Benz','CLA250','AMG Line',2019,'2.0',54000,'AT',205000.00,'Gasoline','FWD',4,'sold'),
(@seller_id,'Mercedes-Benz','CLA250','AMG Line',2020,'2.0',42000,'AT',225000.00,'Gasoline','FWD',4,'sold'),
-- GLE450 (43-45)
(@seller_id,'Mercedes-Benz','GLE450','AMG Line',2019,'3.0',56000,'AT',398000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Mercedes-Benz','GLE450','AMG Line',2020,'3.0',44000,'AT',448000.00,'Gasoline','AWD',5,'sold'),
(@seller_id,'Mercedes-Benz','GLE450','AMG Line',2021,'3.0',32000,'AT',498000.00,'Gasoline','AWD',5,'sold'),
-- S450 (46-47)
(@seller_id,'Mercedes-Benz','S450','Exclusive',2018,'3.0',72000,'AT',498000.00,'Gasoline','RWD',4,'sold'),
(@seller_id,'Mercedes-Benz','S450','AMG Line',2019,'3.0',58000,'AT',558000.00,'Gasoline','RWD',4,'sold'),
-- E220d (48-49)
(@seller_id,'Mercedes-Benz','E220d','Avantgarde',2018,'2.0',78000,'AT',218000.00,'Diesel','RWD',4,'sold'),
(@seller_id,'Mercedes-Benz','E220d','AMG Line',2019,'2.0',65000,'AT',238000.00,'Diesel','RWD',4,'sold'),
-- C220d (50)
(@seller_id,'Mercedes-Benz','C220d','AMG Line',2018,'2.0',74000,'AT',168000.00,'Diesel','RWD',4,'sold');

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
-- C200
(@base_id+0,'Silver',184,'M274',9,'225/50 R17','225/50 R17',300,'Sedan','Used','Comfortable and efficient.'),
(@base_id+1,'White',184,'M274',9,'225/50 R17','225/50 R17',300,'Sedan','Used','Well maintained.'),
(@base_id+2,'Grey',181,'M264',9,'225/45 R18','225/45 R18',280,'Sedan','Used','Facelift model.'),
(@base_id+3,'Black',184,'M264',9,'225/45 R18','225/45 R18',300,'Sedan','Used','AMG Line trim.'),
(@base_id+4,'Blue',184,'M264',9,'225/45 R18','225/45 R18',300,'Sedan','Used','Low mileage.'),
(@base_id+5,'White',181,'M264',9,'225/45 R18','225/45 R18',280,'Sedan','Used','Clean interior.'),
(@base_id+6,'Grey',184,'M264',9,'225/45 R18','225/45 R18',300,'Sedan','Used','Smooth drive.'),
(@base_id+7,'Black',184,'M264',9,'225/45 R18','225/45 R18',300,'Sedan','Used','1 owner.'),
(@base_id+8,'Silver',184,'M264',9,'225/45 R18','225/45 R18',300,'Sedan','Used','Service on time.'),
(@base_id+9,'White',184,'M264',9,'225/45 R18','225/45 R18',300,'Sedan','Used','Reliable unit.'),
-- GLC300
(@base_id+10,'White',255,'M274',9,'235/55 R19','235/55 R19',370,'SUV','Used','AWD confidence.'),
(@base_id+11,'Black',255,'M274',9,'235/55 R19','235/55 R19',370,'SUV','Used','AMG Line pack.'),
(@base_id+12,'Grey',258,'M264',9,'235/55 R19','235/55 R19',370,'SUV','Used','Updated model.'),
(@base_id+13,'Silver',258,'M264',9,'235/55 R19','235/55 R19',370,'SUV','Used','Well kept.'),
(@base_id+14,'Blue',258,'M264',9,'235/55 R19','235/55 R19',370,'SUV','Used','Low mileage.'),
(@base_id+15,'White',258,'M264',9,'235/55 R19','235/55 R19',370,'SUV','Used','Clean interior.'),
(@base_id+16,'Grey',258,'M264',9,'235/55 R19','235/55 R19',370,'SUV','Used','Nice condition.'),
(@base_id+17,'Black',258,'M264',9,'235/55 R19','235/55 R19',370,'SUV','Used','Top spec.'),
-- E200
(@base_id+18,'Silver',197,'M274',9,'245/45 R18','245/45 R18',320,'Sedan','Used','Executive comfort.'),
(@base_id+19,'White',197,'M274',9,'245/45 R18','245/45 R18',320,'Sedan','Used','Clean unit.'),
(@base_id+20,'Grey',197,'M274',9,'245/45 R18','245/45 R18',320,'Sedan','Used','Well maintained.'),
(@base_id+21,'Black',197,'M274',9,'245/45 R18','245/45 R18',320,'Sedan','Used','Facelift.'),
(@base_id+22,'Blue',197,'M264',9,'245/45 R18','245/45 R18',320,'Sedan','Used','Nice drive.'),
(@base_id+23,'White',197,'M264',9,'245/45 R18','245/45 R18',320,'Sedan','Used','Updated tech.'),
(@base_id+24,'Grey',197,'M264',9,'245/45 R18','245/45 R18',320,'Sedan','Used','Low mileage.'),
-- A200
(@base_id+25,'Red',163,'M282',7,'225/45 R17','225/45 R17',250,'Hatchback','Used','Zippy hatch.'),
(@base_id+26,'White',163,'M282',7,'225/45 R17','225/45 R17',250,'Hatchback','Used','Clean unit.'),
(@base_id+27,'Black',163,'M282',7,'225/45 R17','225/45 R17',250,'Hatchback','Used','AMG Line.'),
(@base_id+28,'Blue',163,'M282',7,'225/45 R17','225/45 R17',250,'Hatchback','Used','Well kept.'),
(@base_id+29,'Grey',163,'M282',7,'225/45 R17','225/45 R17',250,'Hatchback','Used','Low mileage.'),
-- GLA200
(@base_id+30,'White',163,'M282',7,'225/50 R18','225/50 R18',250,'SUV','Used','Compact SUV.'),
(@base_id+31,'Silver',163,'M270',7,'225/50 R18','225/50 R18',250,'SUV','Used','Practical size.'),
(@base_id+32,'Grey',163,'M282',7,'225/50 R18','225/50 R18',250,'SUV','Used','Clean interior.'),
(@base_id+33,'Black',163,'M282',7,'225/50 R18','225/50 R18',250,'SUV','Used','AMG Line.'),
(@base_id+34,'Blue',163,'M282',7,'225/50 R18','225/50 R18',250,'SUV','Used','Value buy.'),
-- C300
(@base_id+35,'White',255,'M274',9,'225/45 R18','225/45 R18',370,'Sedan','Used','Punchy 2.0.'),
(@base_id+36,'Grey',255,'M274',9,'225/45 R18','225/45 R18',370,'Sedan','Used','Well kept.'),
(@base_id+37,'Black',258,'M264',9,'225/45 R18','225/45 R18',370,'Sedan','Used','Facelift turbo.'),
(@base_id+38,'Blue',258,'M264',9,'225/45 R18','225/45 R18',370,'Sedan','Used','Low mileage.'),
-- CLA250
(@base_id+39,'Red',224,'M270',7,'225/45 R18','225/45 R18',350,'Coupe','Used','Sleek 4-door.'),
(@base_id+40,'White',221,'M260',7,'225/45 R18','225/45 R18',350,'Coupe','Used','AMG Line.'),
(@base_id+41,'Black',221,'M260',7,'225/45 R18','225/45 R18',350,'Coupe','Used','Sporty drive.'),
-- GLE450
(@base_id+42,'White',362,'M256',9,'275/45 R20','275/45 R20',500,'SUV','Used','Refined 6-cyl.'),
(@base_id+43,'Black',362,'M256',9,'275/45 R20','275/45 R20',500,'SUV','Used','Luxurious.'),
(@base_id+44,'Grey',362,'M256',9,'275/45 R20','275/45 R20',500,'SUV','Used','Well maintained.'),
-- S450
(@base_id+45,'Black',362,'M256',9,'245/45 R19','245/45 R19',500,'Sedan','Used','Flagship comfort.'),
(@base_id+46,'White',362,'M256',9,'245/45 R19','245/45 R19',500,'Sedan','Used','Executive spec.'),
-- E220d
(@base_id+47,'Silver',194,'OM654',9,'245/45 R18','245/45 R18',400,'Sedan','Used','Diesel efficiency.'),
(@base_id+48,'Grey',194,'OM654',9,'245/45 R18','245/45 R18',400,'Sedan','Used','Strong torque.'),
-- C220d
(@base_id+49,'White',191,'OM654',9,'225/50 R17','225/50 R17',400,'Sedan','Used','Economical diesel.');

COMMIT;
