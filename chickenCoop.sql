-- DATABASE: chicken_coop
CREATE DATABASE IF NOT EXISTS chicken_coop;
USE chicken_coop;

-- TABLE: system_state
CREATE TABLE IF NOT EXISTS system_state (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fan_status ENUM('ON','OFF') NOT NULL DEFAULT 'OFF',
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert initial fan status
INSERT INTO system_state (fan_status) VALUES ('OFF');

-- TABLE: sensor_logs
CREATE TABLE IF NOT EXISTS sensor_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    temperature DECIMAL(5,2) NOT NULL,
    humidity DECIMAL(5,2) NOT NULL,
    fan_status ENUM('ON','OFF') NOT NULL,
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Example: initial log entry
INSERT INTO sensor_logs (temperature, humidity, fan_status)
VALUES (30, 65, 'OFF');