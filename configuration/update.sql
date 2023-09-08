/**
 * news module
 * SQL updates
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2020-2023 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */

/* 2020-04-18-1 */	ALTER TABLE `articles` ADD `lead` text COLLATE 'utf8mb4_unicode_ci' NULL AFTER `abstract`;
/* 2020-04-25-1 */	INSERT INTO `_settings` (`setting_key`, `setting_value`, `explanation`) VALUES ('news_big_image_size', 800, 'size of big image linked to in gallery (only if bigger image is available)');
/* 2020-04-25-2 */	INSERT INTO `_settings` (`setting_key`, `setting_value`, `explanation`) VALUES ('news_category_path', 'news', 'identifier of `news` category');
/* 2020-05-27-1 */	CREATE TABLE `comments_activities` (`comment_activity_id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `comment_id` int unsigned NOT NULL, `activity_id` int unsigned NOT NULL) ENGINE='MyISAM';
/* 2020-05-27-2 */	ALTER TABLE `comments_activities` ADD INDEX `comment_id_activity_id` (`comment_id`, `activity_id`), ADD INDEX `activity_id` (`activity_id`);
/* 2020-05-27-3 */	INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'comments', 'comment_id', (SELECT DATABASE()), 'comments_activities', 'comment_activity_id', 'comment_id', 'delete');
/* 2020-05-27-4 */	INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'activities', 'activity_id', (SELECT DATABASE()), 'comments_activities', 'comment_activity_id', 'activity_id', 'no-delete');
/* 2020-05-27-5 */	CREATE TABLE `comments` (`comment_id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `main_comment_id` int unsigned NOT NULL, `article_id` int unsigned NOT NULL, `comment` text NOT NULL, `published` enum('yes','no') NOT NULL DEFAULT 'no') ENGINE='MyISAM';
/* 2020-05-27-6 */	ALTER TABLE `comments` ADD INDEX `main_comment_id` (`main_comment_id`), ADD INDEX `article_id` (`article_id`);
/* 2020-05-27-7 */	INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'comments', 'comment_id', (SELECT DATABASE()), 'comments', 'comment_id', 'main_comment_id', 'no-delete');
/* 2020-05-27-8 */	INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'articles', 'article_id', (SELECT DATABASE()), 'comments', 'comment_id', 'article_id', 'no-delete');
/* 2020-07-20-1 */	ALTER TABLE `articles_categories` ADD `type_category_id` int unsigned NOT NULL AFTER `category_id`;
/* 2020-07-20-2 */	ALTER TABLE `articles_categories` ADD UNIQUE `article` (`article_id`, `sequence`, `type_category_id`), ADD INDEX `type` (`type_category_id`), DROP INDEX `article`;
/* 2020-07-20-3 */	INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'categories', 'category_id', (SELECT DATABASE()), 'articles_categories', 'article_category_id', 'type_category_id', 'no-delete');
/* 2020-07-20-4 */	UPDATE articles_categories SET type_category_id = (SELECT category_id FROM categories WHERE (path = 'news' OR parameters LIKE '%&alias=news%'));
/* 2020-11-12-1 */	ALTER TABLE `articles` ADD UNIQUE `identifier` (`identifier`);
/* 2020-12-06-1 */	DELETE FROM `_settings` WHERE setting_key = "news_publications";
/* 2021-01-26-1 */	ALTER TABLE `articles` CHANGE `title` `title` varchar(128) COLLATE 'utf8mb4_unicode_ci' NOT NULL AFTER `date_to`;
/* 2021-02-07-1 */	ALTER TABLE `articles_media` ADD INDEX `article` (`article_id`, `sequence`), DROP INDEX `article`;
/* 2021-02-10-1 */	ALTER TABLE `comments` CHANGE `comment` `comment` text COLLATE 'utf8mb4_unicode_ci' NOT NULL AFTER `article_id`, CHANGE `published` `published` enum('yes','no') COLLATE 'latin1_general_ci' NOT NULL DEFAULT 'no' AFTER `comment`, COLLATE 'utf8mb4_unicode_ci';
/* 2021-02-10-2 */	ALTER TABLE `comments_activities` COLLATE 'utf8mb4_unicode_ci';
/* 2021-11-14-1 */	CREATE TABLE `articles_contacts` (`article_contact_id` int unsigned NOT NULL AUTO_INCREMENT, `article_id` int unsigned NOT NULL, `contact_id` int unsigned NOT NULL, `role_category_id` int unsigned NOT NULL, `sequence` tinyint unsigned NOT NULL, `last_update` timestamp NOT NULL, PRIMARY KEY (`article_contact_id`), UNIQUE KEY `article_id_contact_id_role_category_id` (`article_id`,`contact_id`,`role_category_id`), KEY `contact_id` (`contact_id`), KEY `role_category_id` (`role_category_id`), KEY `sequence` (`sequence`)) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/* 2021-11-14-2 */	INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'articles', 'article_id', (SELECT DATABASE()), 'articles_contacts', 'article_contact_id', 'article_id', 'delete');
/* 2021-11-14-3 */	INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'contacts', 'contact_id', (SELECT DATABASE()), 'articles_contacts', 'article_contact_id', 'contact_id', 'no-delete');
/* 2021-11-14-4 */	INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'categories', 'category_id', (SELECT DATABASE()), 'articles_contacts', 'article_contact_id', 'role_category_id', 'no-delete');
/* 2022-03-11-1 */	CREATE TABLE `articles_articles` (`article_article_id` int unsigned NOT NULL AUTO_INCREMENT, `article_id` int unsigned NOT NULL, `main_article_id` int unsigned NOT NULL, `relation_category_id` int unsigned NOT NULL, `sequence` tinyint unsigned NOT NULL DEFAULT '1', `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`article_article_id`), UNIQUE KEY `article_id` (`article_id`,`main_article_id`), KEY `main_article_id` (`main_article_id`), KEY `relation_category_id` (`relation_category_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/* 2022-03-11-2 */	INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'articles', 'article_id', (SELECT DATABASE()), 'articles_articles', 'article_article_id', 'article_id', 'delete');
/* 2022-03-11-3 */	INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'categories', 'category_id', (SELECT DATABASE()), 'articles_articles', 'article_article_id', 'relation_category_id', 'no-delete');
/* 2022-03-12-1 */	ALTER TABLE `articles` DROP `lead`;
/* 2022-06-12-1 */	ALTER TABLE `articles_articles` CHANGE `last_update` `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `sequence`;
/* 2022-11-14-1 */	ALTER TABLE `articles_media` ADD `overview_medium` enum('yes','no') NOT NULL DEFAULT 'no' AFTER `sequence`;
/* 2022-11-14-2 */	ALTER TABLE `articles_media` ADD INDEX `overview_medium` (`overview_medium`);
/* 2022-11-21-1 */	DELETE FROM `_settings` WHERE `setting_key` = 'news_url';
/* 2022-12-02-1 */	ALTER TABLE `articles` ADD `subtitle` varchar(128) COLLATE 'utf8mb4_unicode_ci' NULL AFTER `title`;
/* 2023-01-01-1 */	DELETE FROM `_settings` WHERE `setting_key` = 'news_category_path';
/* 2023-01-01-2 */	DELETE FROM `_settings` WHERE `setting_key` = 'publications_category_path';
/* 2023-07-15-1 */	DROP TABLE `comments`;
/* 2023-07-15-2 */	DELETE FROM `_relations` WHERE detail_table = 'comments';
/* 2023-07-15-3 */	DROP TABLE `comments_activities`;
/* 2023-07-15-4 */	DELETE FROM `_relations` WHERE detail_table = 'comments_activities';
/* 2023-09-08-1 */	ALTER TABLE `articles_categories` ADD `property` varchar(255) NULL AFTER `category_id`, CHANGE `sequence` `sequence` tinyint unsigned NULL AFTER `type_category_id`;
