-- Create database and ensure bluebird_user exists for localhost
CREATE DATABASE IF NOT EXISTS `bluebirdhotel`;

-- Create a user specifically for localhost and give privileges on the bluebirdhotel DB
CREATE USER IF NOT EXISTS 'bluebird_user'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON `bluebirdhotel`.* TO 'bluebird_user'@'localhost';
FLUSH PRIVILEGES;

-- Optional: if you want to also create the wildcard-host user as in the original dump
CREATE USER IF NOT EXISTS 'bluebird_user'@'%' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON `bluebirdhotel`.* TO 'bluebird_user'@'%';
FLUSH PRIVILEGES;

-- Optional: import the rest of the schema/data from the project's bluebirdhotel.sql
-- SOURCE C:/xampp/htdocs/Hotel-Management-System-main/bluebirdhotel.sql;
