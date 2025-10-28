-- Creates the reports table to store listing reports from users
CREATE TABLE IF NOT EXISTS reports (
  report_id INT AUTO_INCREMENT PRIMARY KEY,
  car_id INT NOT NULL,
  reporter_id INT NULL,
  reporter_role ENUM('buyer','seller','admin','guest') NULL,
  reasons TEXT NULL,
  details TEXT NULL,
  status ENUM('new','reviewed','dismissed','resolved') NOT NULL DEFAULT 'new',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_reports_car (car_id),
  CONSTRAINT fk_reports_car FOREIGN KEY (car_id) REFERENCES cars(car_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;