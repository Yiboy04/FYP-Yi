-- Create table for additional car details
CREATE TABLE IF NOT EXISTS car_more_detail (
  car_id INT NOT NULL PRIMARY KEY,
  speaker_brand VARCHAR(255) NULL,
  speaker_quantity INT NULL,
  length_mm INT NULL,
  height_mm INT NULL,
  width_mm INT NULL,
  wheel_base_mm INT NULL,
  turning_circle VARCHAR(50) NULL,
  fuel_consumption DECIMAL(5,2) NULL,
  front_suspension VARCHAR(255) NULL,
  rear_suspension VARCHAR(255) NULL,
  driver_assistance TEXT NULL,
  heated_seat TINYINT(1) NULL,
  cooling_seat TINYINT(1) NULL,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_cmd_car FOREIGN KEY (car_id) REFERENCES cars(car_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;