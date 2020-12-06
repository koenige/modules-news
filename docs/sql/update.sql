/**
 * Zugzwang Project
 * SQL updates for news module
 *
 * http://www.zugzwang.org/modules/default
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2020 Gustaf Mossakowski
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
/* 2020-12-06-1 */	DELETE FROM `settings` WHERE setting_key = "news_publications";
