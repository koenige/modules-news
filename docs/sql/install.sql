/**
 * Zugzwang Project
 * SQL for installation of news module
 *
 * http://www.zugzwang.org/modules/default
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2018-2020 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


CREATE TABLE `articles` (
  `article_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `title` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abstract` text COLLATE utf8mb4_unicode_ci,
  `direct_link` varchar(128) CHARACTER SET latin1 COLLATE latin1_general_cs DEFAULT NULL,
  `article` mediumtext COLLATE utf8mb4_unicode_ci,
  `identifier` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `published` enum('yes','no') CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'yes',
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`article_id`),
  UNIQUE `identifier` (`identifier`),
  KEY `published` (`published`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


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


CREATE TABLE `articles_events` (
  `article_event_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) unsigned NOT NULL,
  `event_id` int(10) unsigned NOT NULL,
  `sequence` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`article_event_id`),
  UNIQUE KEY `article_event` (`article_id`,`event_id`),
  KEY `event_id` (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


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


INSERT INTO `_settings` (`setting_key`, `setting_value`, `explanation`) VALUES ('news_big_image_size', 960, 'size of big image linked to in gallery (only if bigger image is available)');
INSERT INTO `_settings` (`setting_key`, `setting_value`, `explanation`) VALUES ('news_category_path', 'news', 'identifier of `news` category');
INSERT INTO `_settings` (`setting_key`, `setting_value`, `explanation`) VALUES ('news_og_image_size', 480, 'size of image for OpenGraph (Facebook, Twitter, etc.)');
INSERT INTO `_settings` (`setting_key`, `setting_value`, `explanation`) VALUES ('news_topimage_image_size', 480, 'size of image used as the main image for a news item');
INSERT INTO `_settings` (`setting_key`, `setting_value`, `explanation`) VALUES ('news_with_events', 0, 'link news with events? (events module required)');
INSERT INTO `_settings` (`setting_key`, `setting_value`, `explanation`) VALUES ('news_url', '', 'base URL prepended to all news articles');