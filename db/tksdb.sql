-- Adminer 3.7.1 MySQL dump
/*
Copyright 2013 © Matteo Amico
This file is part of Tickets.

Tickets is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation either version 2 of the License, or 
(at your option) any later version.

Tickets is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Tickets.  If not, see <http://www.gnu.org/licenses/>.
*/
SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = '+00:00';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `tksdb`;
CREATE DATABASE `tksdb` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `tksdb`;

DROP TABLE IF EXISTS `tks_filters`;
CREATE TABLE `tks_filters` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `open` tinyint(4) DEFAULT '1',
  `inprogress` tinyint(4) DEFAULT '1',
  `closed` tinyint(4) DEFAULT '1',
  `low` tinyint(4) DEFAULT '1',
  `normal` tinyint(4) DEFAULT '1',
  `high` tinyint(4) DEFAULT '1',
  `per_page` int(11) NOT NULL DEFAULT '50',
  PRIMARY KEY (`ID`),
  KEY `user_id` (`user_id`),
  KEY `created_by` (`created_by`),
  KEY `asigned_to` (`assigned_to`),
  KEY `update_by` (`updated_by`),
  CONSTRAINT `tks_filters_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tks_users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tks_filters_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `tks_users` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `tks_filters_ibfk_3` FOREIGN KEY (`assigned_to`) REFERENCES `tks_users` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `tks_filters_ibfk_4` FOREIGN KEY (`updated_by`) REFERENCES `tks_users` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tks_filters` (`ID`, `user_id`, `created_by`, `assigned_to`, `updated_by`, `open`, `inprogress`, `closed`, `low`, `normal`, `high`, `per_page`) VALUES
(1,	1,	NULL,	NULL,	NULL,	1,	1,	1,	1,	1,	1,	50);

DROP TABLE IF EXISTS `tks_link_tags_tickets`;
CREATE TABLE `tks_link_tags_tickets` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) DEFAULT NULL,
  `ticket_id` int(11) DEFAULT NULL,
  `trash` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `tag_id` (`tag_id`),
  KEY `ticket_id` (`ticket_id`),
  CONSTRAINT `tks_link_tags_tickets_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `tks_tags` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tks_link_tags_tickets_ibfk_2` FOREIGN KEY (`ticket_id`) REFERENCES `tks_tickets` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `tks_tags`;
CREATE TABLE `tks_tags` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `tks_tags_filters`;
CREATE TABLE `tks_tags_filters` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `selected` tinyint(4) NOT NULL DEFAULT '1',
  `trash` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `tag_id` (`tag_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `tks_tags_filters_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `tks_tags` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tks_tags_filters_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tks_users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `tks_tickets`;
CREATE TABLE `tks_tickets` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `creation_date` datetime NOT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `state` tinyint(4) DEFAULT '0',
  `updated_by` int(11) DEFAULT NULL,
  `update_date` datetime NOT NULL,
  `priority` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `update_by` (`updated_by`),
  KEY `created_by` (`created_by`),
  KEY `assigned_to` (`assigned_to`),
  CONSTRAINT `tks_tickets_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `tks_users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tks_tickets_ibfk_4` FOREIGN KEY (`assigned_to`) REFERENCES `tks_users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tks_tickets_ibfk_5` FOREIGN KEY (`updated_by`) REFERENCES `tks_users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `tks_users`;
CREATE TABLE `tks_users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` char(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `level` tinyint(4) NOT NULL DEFAULT '0',
  `state` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

INSERT INTO `tks_users` (`ID`, `name`, `password`, `creation_date`, `level`, `state`) VALUES
(1,	'admin',	'5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8',	'2013-10-29 00:00:00',	0,	1);

-- 2013-11-16 16:56:24
