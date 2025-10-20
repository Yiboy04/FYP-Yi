-- Migration: Rename wheel_size -> front_wheel_size, add rear_wheel_size, torque, car_type
-- Run this in MySQL/phpMyAdmin before using the new fields

ALTER TABLE `car_details`
  CHANGE COLUMN `wheel_size` `front_wheel_size` VARCHAR(255) NULL DEFAULT NULL,
  ADD COLUMN `rear_wheel_size` VARCHAR(255) NULL DEFAULT NULL AFTER `front_wheel_size`,
  ADD COLUMN `torque` VARCHAR(255) NULL DEFAULT NULL AFTER `rear_wheel_size`,
  ADD COLUMN `car_type` ENUM('Sedan','SUV','Pickup','Coupe','Hatchback','Wagon','Convertible','Van','MPV','Crossover','Sports','Other') NULL DEFAULT NULL AFTER `torque`;
