-- Equipsets updates:
-- CREATE DATABASE Equipsets;
-- within Equipsets...

-- DROP TABLE IF EXISTS `user_sets`;
CREATE TABLE `user_sets` (
  `userid` int(10) unsigned NOT NULL,
  `setid` int(10) unsigned NOT NULL,
  `setname` varchar(25) NOT NULL,
  `mlvl` tinyint(2) unsigned NOT NULL DEFAULT 0,
  `slvl` tinyint(2) unsigned NOT NULL DEFAULT 0,
  `mjob` tinyint(2) unsigned NOT NULL DEFAULT 0,
  `sjob` tinyint(2) unsigned NOT NULL DEFAULT 0,
  `equipment` varchar(255) NOT NULL DEFAULT 0,
  PRIMARY KEY (`setid`)
) DEFAULT CHARSET=utf8mb4 CHECKSUM=1;

-- DROP TABLE IF EXISTS `user_chars`;
CREATE TABLE `user_chars` (
  `userid` int(10) unsigned NOT NULL,
  `charid` int(10) unsigned NOT NULL,
  `charname` varchar(25) NOT NULL,
  `race` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `merits` varchar(255) NOT NULL DEFAULT 0,
  PRIMARY KEY (`charid`)
) DEFAULT CHARSET=utf8mb4 CHECKSUM=1;