/**
 * news module
 * SQL for installation
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2018-2022 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


CREATE TABLE `articles` (
  `article_id` int unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abstract` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `direct_link` varchar(128) CHARACTER SET latin1 COLLATE latin1_general_cs DEFAULT NULL,
  `article` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `identifier` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `published` enum('yes','no') CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'yes',
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`article_id`),
  UNIQUE KEY `identifier` (`identifier`),
  KEY `published` (`published`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `articles_articles` (
  `article_article_id` int unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int unsigned NOT NULL,
  `main_article_id` int unsigned NOT NULL,
  `relation_category_id` int unsigned NOT NULL,
  `sequence` tinyint unsigned NOT NULL DEFAULT '1',
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`article_article_id`),
  UNIQUE KEY `article_id` (`article_id`,`main_article_id`),
  KEY `main_article_id` (`main_article_id`),
  KEY `relation_category_id` (`relation_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'articles', 'article_id', (SELECT DATABASE()), 'articles_articles', 'article_article_id', 'article_id', 'delete');
INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'categories', 'category_id', (SELECT DATABASE()), 'articles_articles', 'article_article_id', 'relation_category_id', 'no-delete');


CREATE TABLE `articles_categories` (
  `article_category_id` int unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int unsigned NOT NULL,
  `category_id` int unsigned NOT NULL,
  `type_category_id` int unsigned NOT NULL,
  `sequence` tinyint NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`article_category_id`),
  UNIQUE KEY `article` (`article_id`,`sequence`,`type_category_id`),
  UNIQUE KEY `category` (`category_id`,`article_id`),
  KEY `type` (`type_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'articles', 'article_id', (SELECT DATABASE()), 'articles_categories', 'article_category_id', 'article_id', 'delete');
INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'categories', 'category_id', (SELECT DATABASE()), 'articles_categories', 'article_category_id', 'category_id', 'no-delete');
INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'categories', 'category_id', (SELECT DATABASE()), 'articles_categories', 'article_category_id', 'type_category_id', 'no-delete');


CREATE TABLE `articles_contacts` (
  `article_contact_id` int unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int unsigned NOT NULL,
  `contact_id` int unsigned NOT NULL,
  `role_category_id` int unsigned NOT NULL,
  `sequence` tinyint unsigned NOT NULL,
  `last_update` timestamp NOT NULL,
  PRIMARY KEY (`article_contact_id`),
  UNIQUE KEY `article_id_contact_id_role_category_id` (`article_id`,`contact_id`,`role_category_id`),
  KEY `contact_id` (`contact_id`),
  KEY `role_category_id` (`role_category_id`),
  KEY `sequence` (`sequence`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'articles', 'article_id', (SELECT DATABASE()), 'articles_contacts', 'article_contact_id', 'article_id', 'delete');
INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'contacts', 'contact_id', (SELECT DATABASE()), 'articles_contacts', 'article_contact_id', 'contact_id', 'no-delete');
INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'categories', 'category_id', (SELECT DATABASE()), 'articles_contacts', 'article_contact_id', 'role_category_id', 'no-delete');


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
  `overview_medium` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`article_medium_id`),
  UNIQUE KEY `medium` (`medium_id`,`article_id`),
  KEY `article` (`article_id`,`sequence`),
  KEY `overview_medium` (`overview_medium`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'articles', 'article_id', (SELECT DATABASE()), 'articles_media', 'article_medium_id', 'article_id', 'no-delete');
INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'media', 'medium_id', (SELECT DATABASE()), 'articles_media', 'article_medium_id', 'medium_id', 'no-delete');


CREATE TABLE `comments` (
  `comment_id` int unsigned NOT NULL AUTO_INCREMENT,
  `main_comment_id` int unsigned NOT NULL,
  `article_id` int unsigned NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `published` enum('yes','no') CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'no',
  PRIMARY KEY (`comment_id`),
  KEY `main_comment_id` (`main_comment_id`),
  KEY `article_id` (`article_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'comments', 'comment_id', (SELECT DATABASE()), 'comments', 'comment_id', 'main_comment_id', 'no-delete');
INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'articles', 'article_id', (SELECT DATABASE()), 'comments', 'comment_id', 'article_id', 'no-delete');


CREATE TABLE `comments_activities` (
  `comment_activity_id` int unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` int unsigned NOT NULL,
  `activity_id` int unsigned NOT NULL,
  PRIMARY KEY (`comment_activity_id`),
  KEY `comment_id_activity_id` (`comment_id`,`activity_id`),
  KEY `activity_id` (`activity_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'comments', 'comment_id', (SELECT DATABASE()), 'comments_activities', 'comment_activity_id', 'comment_id', 'delete');
INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'activities', 'activity_id', (SELECT DATABASE()), 'comments_activities', 'comment_activity_id', 'activity_id', 'no-delete');


-- query SHOW TABLES LIKE 'newsletters' --
ALTER TABLE `articles` ADD `newsletter_lead` text COLLATE 'utf8mb4_unicode_ci' NULL AFTER `abstract`;
