/**
 * news module
 * SQL queries for core, page, auth and database IDs
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2026 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


-- ids_publications --
SELECT identifier, publication_id FROM /*_PREFIX_*/publications ORDER BY identifier;

-- ids-aliases_publications --
SELECT publication_id, parameters FROM /*_PREFIX_*/publications WHERE parameters LIKE '%alias=%';
