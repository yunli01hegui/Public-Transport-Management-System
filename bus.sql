DROP DATABASE IF EXISTS `bus_db`;
CREATE DATABASE  IF NOT EXISTS `bus_db` DEFAULT CHARSET 'utf8';
USE `bus_db`;

-- 城市表
CREATE TABLE `cities` (
    `id` BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    -- CONSTRIANT
    PRIMARY KEY(`id`),
    UNIQUE KEY(`name`)
);

-- 距离表
CREATE TABLE `distance` (
    `city_id_1` BIGINT UNSIGNED NOT NULL,
    `city_id_2` BIGINT UNSIGNED NOT NULL,
    `distance` INT UNSIGNED NOT NULL COMMENT 'Kilometers Between Two Cities',
    -- CONSTRIANT
    PRIMARY KEY(`city_id_1`,`city_id_2`)
);

-- 班车车型表
CREATE TABLE `shuttle_types` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `capacity` INT UNSIGNED NOT NULL,
    -- CONSTRIANT
    PRIMARY KEY(`id`),
    UNIQUE KEY(`name`)
);

-- 班车路线表
CREATE TABLE `routes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `from_city_id` BIGINT UNSIGNED NOT NULL,
    `to_city_id` BIGINT UNSIGNED NOT NULL,
    `shuttle_type_id` BIGINT UNSIGNED NOT NULL,
    `price` DECIMAL(10,2) UNSIGNED NOT NULL,
    `departure_time` TIME NOT NULL,
    `duration` INT UNSIGNED NOT NULL COMMENT 'Minutes',
    `valid_to` DATE NULL COMMENT 'If NULL, Valid Forever',
    -- CONSTRIANT
    PRIMARY KEY(`id`),
    FOREIGN KEY(`from_city_id`) REFERENCES `cities`(`id`),
    FOREIGN KEY(`to_city_id`) REFERENCES `cities`(`id`),
    FOREIGN KEY(`shuttle_type_id`) REFERENCES `shuttle_types`(`id`)
);

-- 用户表
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(100) NOT NULL,
    `address` VARCHAR(255) NOT NULL,
    `is_admin` TINYINT(1) NOT NULL DEFAULT 0,
    `remember_token` VARCHAR(255) NULL,
    -- CONSTRIANT
    PRIMARY KEY(`id`),
    UNIQUE KEY(`email`)
);

-- 预定表
CREATE TABLE `bookings` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `route_id` BIGINT UNSIGNED NOT NULL,
    `departure_date` DATE NOT NULL,
    `booking_date` datetime NOT NULL,
    `no_of_seats` INT UNSIGNED NOT NULL,
    `status` ENUM('PENDING','PAID','CANCELED','REJECTED','FINISHED') NOT NULL DEFAULT 'PENDING',
    -- CONSTRIANT
    PRIMARY KEY(`id`),
    FOREIGN KEY(`user_id`) REFERENCES `users`(`id`),
    FOREIGN KEY(`route_id`) REFERENCES `routes`(`id`)
);

-- 预定座位表
CREATE TABLE `bookings_seats` (
    `booking_id` BIGINT UNSIGNED NOT NULL,
    `seat_no` INT UNSIGNED NOT NULL,
    -- CONSTRIANT
    PRIMARY KEY(`booking_id`,`seat_no`),
    FOREIGN KEY(`booking_id`) REFERENCES `bookings`(`id`)
);


INSERT INTO `cities` 
VALUES
(NULL,'City 1'),
(NULL,'City 2'),
(NULL,'City 3')
;

INSERT INTO `distance`
VALUES
(1,2,300),
(1,3,200),
(2,3,280)
;

INSERT INTO `shuttle_types`
VALUES
(NULL,'Mini Bus',8),
(NULL,'Medium Bus',28),
(NULL,'Large Bus',47)
;

INSERT INTO `routes`
(id,from_city_id,to_city_id,shuttle_type_id,price,departure_time,duration)
VALUES
(NULL,1,2,1,130.00,'10:00:00',120),
(NULL,1,2,2,110.00,'09:00:00',135),
(NULL,1,2,3,100.00,'12:30:00',150),
(NULL,1,3,1,135.00,'16:00:00',120),
(NULL,1,3,2,120.00,'18:10:00',135),
(NULL,2,3,2,115.00,'22:00:00',120),
(NULL,2,3,3,108.00,'14:30:00',150),
(NULL,1,2,3,108.00,'20:30:00',150)
;

INSERT INTO `users`
(`id`,`name`,`email`,`password`,`phone`,`address`,`is_admin`,`remember_token`)
VALUES
(1,'Admin','admin@gmail.com','$2y$10$v4cNR5jCLRbF6ndv8lVU1OvHKMb8iHXMpHQ4BHevAJqhA8fFO7vre','555-123-6789','New York',1,null), -- adminPass
(2,'User1','user1@gmail.com','$2y$10$Ys7jOjeQTIr0.qLfUTF7F.012IyW46FiF4uNNoCCL//da/gPiREmi','235-114-9983','Washington DC',0,null), -- user1Pass
(3,'User2','user2@gmail.com','$2y$10$IdPv4D/3oNLpLWrAulufe.ABZrKsab5jcE6nWlf9Po3cgSo1weSi2','515-234-7696','Los Angeles',0,null) -- user2Pass
;

INSERT INTO `bookings`
(`id`,`user_id`,`route_id`,`departure_date`,`booking_date`,`no_of_seats`,`status`)
VALUES
(NULL,2,1,'2022-12-1','2022-11-30 10:00:00',2,'PENDING'),
(NULL,3,2,'2022-12-08','2022-12-05 12:00:00',3,'FINISHED')
;

Insert into `bookings_seats`
(`booking_id`,`seat_no`)
VALUES
(1,1),
(1,2),
(2,1),
(2,2),
(2,3)
;




