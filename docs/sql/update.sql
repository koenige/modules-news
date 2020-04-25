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

