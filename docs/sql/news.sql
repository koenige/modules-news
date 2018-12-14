SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `article_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `title` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abstract` text COLLATE utf8mb4_unicode_ci,
  `direct_link` varchar(128) CHARACTER SET latin1 COLLATE latin1_general_cs DEFAULT NULL,
  `article` mediumtext COLLATE utf8mb4_unicode_ci,
  `identifier` varchar(42) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `published` enum('yes','no') CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'yes',
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`article_id`),
  KEY `published` (`published`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `articles_categories`;
CREATE TABLE `articles_categories` (
  `article_category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`article_category_id`),
  UNIQUE KEY `article` (`article_id`,`sequence`),
  UNIQUE KEY `category` (`category_id`,`article_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `articles_events`;
CREATE TABLE `articles_events` (
  `article_event_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) unsigned NOT NULL,
  `event_id` int(10) unsigned NOT NULL,
  `sequence` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`article_event_id`),
  UNIQUE KEY `article_event` (`article_id`,`event_id`),
  KEY `event_id` (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `articles_media`;
CREATE TABLE `articles_media` (
  `article_medium_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) unsigned NOT NULL,
  `medium_id` int(10) unsigned NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`article_medium_id`),
  UNIQUE KEY `article` (`article_id`,`sequence`),
  UNIQUE KEY `medium` (`medium_id`,`article_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
