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
  `article_id` int unsigned NOT NULL AUTO_INCREMENT,
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
  `article_category_id` int unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int unsigned NOT NULL,
  `category_id` int unsigned NOT NULL,
  `sequence` tinyint NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`article_category_id`),
  UNIQUE KEY `article` (`article_id`,`sequence`),
  UNIQUE KEY `category` (`category_id`,`article_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'articles', 'article_id', (SELECT DATABASE()), 'articles_categories', 'article_category_id', 'article_id', 'delete');
INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'categories', 'category_id', (SELECT DATABASE()), 'articles_categories', 'article_category_id', 'category_id', 'no-delete');
INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'categories', 'category_id', (SELECT DATABASE()), 'articles_categories', 'article_category_id', 'type_category_id', 'no-delete');

CREATE TABLE `articles_events` (
  `article_event_id` int unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int unsigned NOT NULL,
  `event_id` int unsigned NOT NULL,
  `sequence` tinyint unsigned NOT NULL,
  PRIMARY KEY (`article_event_id`),
  UNIQUE KEY `article_event` (`article_id`,`event_id`),
  KEY `event_id` (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'articles', 'article_id', (SELECT DATABASE()), 'articles_events', 'article_event_id', 'article_id', 'no-delete');
INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'events', 'event_id', (SELECT DATABASE()), 'articles_events', 'article_event_id', 'event_id', 'no-delete');

CREATE TABLE `articles_media` (
  `article_medium_id` int unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int unsigned NOT NULL,
  `medium_id` int unsigned NOT NULL,
  `sequence` tinyint NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`article_medium_id`),
  UNIQUE KEY `article` (`article_id`,`sequence`),
  UNIQUE KEY `medium` (`medium_id`,`article_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'articles', 'article_id', (SELECT DATABASE()), 'articles_media', 'article_medium_id', 'article_id', 'no-delete');
INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'media', 'medium_id', (SELECT DATABASE()), 'articles_media', 'article_medium_id', 'medium_id', 'no-delete');


INSERT INTO `_settings` (`setting_key`, `setting_value`, `explanation`) VALUES ('news_big_image_size', 960, 'size of big image linked to in gallery (only if bigger image is available)');
INSERT INTO `_settings` (`setting_key`, `setting_value`, `explanation`) VALUES ('news_category_path', 'news', 'identifier of `news` category');
INSERT INTO `_settings` (`setting_key`, `setting_value`, `explanation`) VALUES ('news_og_image_size', 480, 'size of image for OpenGraph (Facebook, Twitter, etc.)');
INSERT INTO `_settings` (`setting_key`, `setting_value`, `explanation`) VALUES ('news_topimage_image_size', 480, 'size of image used as the main image for a news item');
INSERT INTO `_settings` (`setting_key`, `setting_value`, `explanation`) VALUES ('news_with_events', 0, 'link news with events? (events module required)');
INSERT INTO `_settings` (`setting_key`, `setting_value`, `explanation`) VALUES ('news_url', '', 'base URL prepended to all news articles');


CREATE TABLE `comments_activities` (
  `comment_activity_id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `comment_id` int unsigned NOT NULL,
  `activity_id` int unsigned NOT NULL
) ENGINE='MyISAM';

ALTER TABLE `comments_activities`
ADD INDEX `comment_id_activity_id` (`comment_id`, `activity_id`),
ADD INDEX `activity_id` (`activity_id`);

INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ("chesstech", "comments", "comment_id", "chesstech", "comments_activities", "comment_activity_id", "comment_id", "delete");
INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ("chesstech", "activities", "activity_id", "chesstech", "comments_activities", "comment_activity_id", "activity_id", "no-delete");


CREATE TABLE `comments` (
  `comment_id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `main_comment_id` int unsigned NOT NULL,
  `article_id` int unsigned NOT NULL,
  `comment` text NOT NULL,
  `published` enum('yes','no') NOT NULL DEFAULT 'no'
) ENGINE='MyISAM';

ALTER TABLE `comments`
ADD INDEX `main_comment_id` (`main_comment_id`),
ADD INDEX `article_id` (`article_id`);

INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ("chesstech", "comments", "comment_id", "chesstech", "comments", "comment_id", "main_comment_id", "no-delete");
INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ("chesstech", "articles", "article_id", "chesstech", "comments", "comment_id", "article_id", "no-delete");
