-- Equipsets updates:
-- usage from within sql folder: mysql -u -p db < dbequipsets.sql

-- CREATE DATABASE Equipsets;
-- DROP TABLE IF EXISTS `user_sets`;
-- DROP TABLE IF EXISTS `user_chars`;
-- DROP TABLE IF EXISTS `sets`;

CREATE TABLE `user_sets` (
  `userid` int(10) unsigned NOT NULL,
  `usersetid` int NOT NULL AUTO_INCREMENT,
  `mlvl` tinyint(2) unsigned NOT NULL DEFAULT 0,
  `slvl` tinyint(2) unsigned NOT NULL DEFAULT 0,
  `mjob` tinyint(2) unsigned NOT NULL DEFAULT 0,
  `sjob` tinyint(2) unsigned NOT NULL DEFAULT 0,
  `equipment` varchar(255) NOT NULL DEFAULT 0,
  `setname` varchar(25) NOT NULL,
  PRIMARY KEY (`usersetid`)
) DEFAULT CHARSET=utf8mb4 CHECKSUM=1;


CREATE TABLE `user_chars` (
  `userid` int(10) unsigned NOT NULL,
  `charid` int NOT NULL AUTO_INCREMENT,
  `charname` varchar(25) NOT NULL,
  `race` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `merits` varchar(255) NOT NULL DEFAULT 0,
  `def` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`charid`)
) DEFAULT CHARSET=utf8mb4 CHECKSUM=1;

-- CREATE TABLE `sets` (
--   `usersetid` int NOT NULL AUTO_INCREMENT,
--   `equipment` varchar(255) NOT NULL DEFAULT 0,
--   `setname` varchar(25) NOT NULL,
--   PRIMARY KEY (`setid`)
-- ) DEFAULT CHARSET=utf8mb4 CHECKSUM=1;




-- ALTER TABLE user_sets
-- add `equipment` varchar(255) NOT NULL DEFAULT 0
-- add `setname` varchar(25) NOT NULL;

