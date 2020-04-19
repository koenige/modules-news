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
