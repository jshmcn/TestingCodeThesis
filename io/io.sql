CREATE DATABASE io_db;

-- table for users
CREATE TABLE `io_db`.`users`
(
    `id` INT NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(100) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `current_device` VARCHAR(100) NOT NULL,
    `password` VARCHAR(100) NOT NULL,
PRIMARY KEY (`id`)
);

-- table for devices (keys)
CREATE TABLE `io_db`.`devices`
(
    `device_id` INT NOT NULL AUTO_INCREMENT,
    `device_name` VARCHAR(100) NOT NULL,
    `keyx` VARCHAR(100) NOT NULL,
    `owner` VARCHAR(100) NOT NULL,
PRIMARY KEY (`device_id`)
);

-- table for device readings
CREATE TABLE `io_db`.`data_logs`
(
    `data_id` INT NOT NULL AUTO_INCREMENT,
    `device_key` VARCHAR(100) NOT NULL,
    `price_limit` FLOAT NOT NULL DEFAULT 0,
    `total_electric_bill` FLOAT NOT NULL DEFAULT 0 ,
    `uptime` INT NOT NULL DEFAULT 0,
    `total_power_consumption` INT NOT NULL DEFAULT 0,
    `live_power_consumption` INT NOT NULL DEFAULT 0,
    `January` INT NOT NULL DEFAULT 0,
    `February` INT NOT NULL DEFAULT 0,
    `March` INT NOT NULL DEFAULT 0,
    `April` INT NOT NULL DEFAULT 0,
    `May` INT NOT NULL DEFAULT 0,
    `June` INT NOT NULL DEFAULT 0,
    `July` INT NOT NULL DEFAULT 0,
    `August` INT NOT NULL DEFAULT 0,
    `September` INT NOT NULL DEFAULT 0,
    `October` INT NOT NULL DEFAULT 0,
    `November` INT NOT NULL DEFAULT 0,
    `December` INT NOT NULL DEFAULT 0,
PRIMARY KEY (`data_id`)
);
