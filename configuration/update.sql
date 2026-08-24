/**
 * news module
 * SQL updates
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2020-2024, 2026 Gustaf Mossakowski
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
/* 2023-10-08-1 */	ALTER TABLE `articles_contacts` ADD `role` varchar(255) NULL AFTER `role_category_id`;
/* 2024-04-07-1 */	UPDATE _settings SET setting_key = 'news_book_path' WHERE setting_key = 'news_books_path';
/* 2026-03-12-1 */	DELETE FROM _settings WHERE setting_key = 'news_article_path';
/* 2026-03-12-2 */	DELETE FROM _settings WHERE setting_key = 'news_articles_path';
/* 2026-03-12-3 */	DELETE FROM _settings WHERE setting_key = 'news_book_path';
/* 2026-03-17-1 */	DELETE FROM _settings WHERE setting_key = 'news_topimage_image_size';
/* 2026-07-06-1 */	INSERT INTO categories (`category`, `description`, `main_category_id`, `path`, `parameters`, `sequence`, `last_update`) VALUES ('Default news overview', NULL, /*_ID categories tags _*/, 'tags/default-articles-overview', '&alias=tags/default-articles-overview', NULL, NOW());
/* 2026-08-14-1 */	UPDATE categories SET parameters = REPLACE(parameters, '&articles=1', '&use_for[news_articles]=1') WHERE parameters LIKE '%&articles=1%';
/* 2026-08-14-2 */	UPDATE categories SET parameters = REPLACE(parameters, 'if[articles][', 'if[news_articles][') WHERE parameters LIKE '%if[articles][%';
/* 2026-08-23-1 */	CREATE TABLE `publications` (`publication_id` int unsigned NOT NULL AUTO_INCREMENT, `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, `identifier` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL, `distribution` enum('continuous','issued') CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'continuous', `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci, `sequence` tinyint unsigned DEFAULT NULL, `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`publication_id`), UNIQUE KEY `identifier` (`identifier`), KEY `sequence` (`sequence`)) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/* 2026-08-23-2 */	CREATE TABLE `issues` (`issue_id` int unsigned NOT NULL AUTO_INCREMENT, `publication_id` int unsigned NOT NULL, `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL, `identifier` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL, `intro` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci, `article` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci, `period_begin` date DEFAULT NULL, `period_end` date DEFAULT NULL, `sequence` tinyint unsigned DEFAULT NULL, `published` enum('yes','no') CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'yes', `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`issue_id`), UNIQUE KEY `publication_identifier` (`publication_id`,`identifier`), KEY `publication_id` (`publication_id`), KEY `period_begin` (`period_begin`)) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/* 2026-08-23-3 */	INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'publications', 'publication_id', (SELECT DATABASE()), 'issues', 'issue_id', 'publication_id', 'delete');
/* 2026-08-23-4 */	INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'publications', 'publication_id', (SELECT DATABASE()), 'articles', 'article_id', 'publication_id', 'no-delete');
/* 2026-08-23-5 */	INSERT INTO _relations (`master_db`, `master_table`, `master_field`, `detail_db`, `detail_table`, `detail_id_field`, `detail_field`, `delete`) VALUES ((SELECT DATABASE()), 'issues', 'issue_id', (SELECT DATABASE()), 'articles', 'article_id', 'issue_id', 'no-delete');
/* 2026-08-23-6 */	ALTER TABLE `articles` ADD `publication_id` int unsigned NULL AFTER `published`, ADD `issue_id` int unsigned NULL AFTER `publication_id`, ADD INDEX `publication_id` (`publication_id`), ADD INDEX `issue_id` (`issue_id`);
/* 2026-08-23-7 */	ALTER TABLE `publications` ADD `parameters` varchar(750) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `sequence`;
/* 2026-08-23-8 */	INSERT INTO `publications` (`title`, `identifier`, `distribution`, `description`, `sequence`, `parameters`) SELECT c.`category`, SUBSTRING_INDEX(SUBSTRING(c.`path`, CHAR_LENGTH(pub.`path`) + 2), '/', 1) COLLATE latin1_general_cs, IF(c.`parameters` LIKE '%issue=1%', 'issued', 'continuous'), c.`description`, c.`sequence`, c.`parameters` FROM `categories` c INNER JOIN (SELECT `path` FROM `categories` WHERE `path` = 'publications' OR `parameters` LIKE '%&alias=publications%' ORDER BY `path` = 'publications' DESC LIMIT 1) pub ON c.`path` LIKE CONCAT(pub.`path`, '/%') WHERE CHAR_LENGTH(SUBSTRING_INDEX(SUBSTRING(c.`path`, CHAR_LENGTH(pub.`path`) + 2), '/', 1)) > 0 AND NOT EXISTS (SELECT 1 FROM `publications` ex WHERE ex.`identifier` = (SUBSTRING_INDEX(SUBSTRING(c.`path`, CHAR_LENGTH(pub.`path`) + 2), '/', 1) COLLATE latin1_general_cs));
/* 2026-08-23-9 */	ALTER TABLE `publications` ADD `title_short` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `title`;
/* 2026-08-23-10 */	UPDATE `publications` p INNER JOIN `categories` c ON (SUBSTRING_INDEX(SUBSTRING(c.`path`, (SELECT CHAR_LENGTH(`path`) FROM `categories` WHERE `path` = 'publications' OR `parameters` LIKE '%&alias=publications%' ORDER BY `path` = 'publications' DESC LIMIT 1) + 2), '/', 1) COLLATE latin1_general_cs) = p.`identifier` AND c.`path` LIKE CONCAT((SELECT `path` FROM `categories` WHERE `path` = 'publications' OR `parameters` LIKE '%&alias=publications%' ORDER BY `path` = 'publications' DESC LIMIT 1), '/%') SET p.`title_short` = c.`category_short`;
/* 2026-08-23-11 */	UPDATE `articles` a INNER JOIN `articles_categories` ac ON ac.`article_id` = a.`article_id` INNER JOIN `categories` c ON c.`category_id` = ac.`category_id` INNER JOIN (SELECT `category_id`, `path` FROM `categories` WHERE `path` = 'publications' OR `parameters` LIKE '%&alias=publications%' ORDER BY `path` = 'publications' DESC LIMIT 1) pubroot ON ac.`type_category_id` = pubroot.`category_id` INNER JOIN `publications` p ON p.`identifier` = SUBSTRING_INDEX(SUBSTRING(c.`path`, CHAR_LENGTH(pubroot.`path`) + 2), '/', 1) COLLATE latin1_general_cs SET a.`publication_id` = p.`publication_id`;
/* 2026-08-23-12 */	ALTER TABLE `publications` CHANGE `title` `publication` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
/* 2026-08-23-13 */	ALTER TABLE `publications` CHANGE `title_short` `publication_short` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;
/* 2026-08-23-14 */	ALTER TABLE `issues` CHANGE `title` `issue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;
/* 2026-08-23-15 */	ALTER TABLE `issues` ADD `issue_short` varchar(31) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `issue`, ADD `date_published` date NULL AFTER `published`;
/* 2026-08-24-1 */	DELETE FROM articles_categories WHERE type_category_id = /*_ID categories publications _*/;
/* 2026-08-24-2 */	DELETE FROM categories WHERE main_category_id = /*_ID categories publications _*/;
/* 2026-08-24-3 */	DELETE FROM categories WHERE category_id = /*_ID categories publications _*/;
/* 2026-08-24-4 */	UPDATE webpages SET content = REPLACE(content, 'news_hide_publication_categories[]=publications/', 'news_hide_publications[]=') WHERE content LIKE '%news_hide_publication_categories[]=publications/%';
/* 2026-08-24-5 */	ALTER TABLE `articles` ADD `title_short` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `subtitle`;
/* 2026-08-24-6 */	UPDATE categories SET parameters = REPLACE(parameters, '&articles_description=1', '') WHERE parameters LIKE '%&articles_description=1%';
/* 2026-08-24-7 */	UPDATE categories SET parameters = REPLACE(parameters, '&articles_author=1', '') WHERE parameters LIKE '%&articles_author=1%';
/* 2026-08-24-8 */	UPDATE publications SET `parameters` = REPLACE(`parameters`, '&article=', '&news_article=') WHERE `parameters` LIKE '%&article=%';
/* 2026-08-24-9 */	UPDATE publications SET `parameters` = REPLACE(`parameters`, '&lead=', '&news_newsletter_lead=') WHERE `parameters` LIKE '%&lead=%';
/* 2026-08-24-10 */	UPDATE publications SET `parameters` = REPLACE(`parameters`, '&categories=', '&news_categories=') WHERE `parameters` LIKE '%&categories=%';
/* 2026-08-24-11 */	UPDATE publications SET `parameters` = REPLACE(`parameters`, '&identifier_without_year=', '&news_identifier_without_year=') WHERE `parameters` LIKE '%&identifier_without_year=%';
/* 2026-08-24-12 */	UPDATE publications SET `parameters` = REPLACE(`parameters`, '&subtitle=', '&news_subtitle=') WHERE `parameters` LIKE '%&subtitle=%';
/* 2026-08-24-13 */	UPDATE publications SET `parameters` = REPLACE(`parameters`, '&media=', '&news_media=') WHERE `parameters` LIKE '%&media=%';
