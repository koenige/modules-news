<?php 

/**
 * news module
 * placeholder function for publication
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2026 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


function mod_news_placeholder_publication($brick) {
	$sql = 'SELECT publication_id, publication, identifier, parameters
			, IF(distribution = "continuous", 1, NULL) AS continuous_publication
			, IF(distribution = "issued", 1, NULL) AS issued_publication
	    FROM publications
	    WHERE identifier = "%s"';
	$sql = sprintf($sql, wrap_db_escape($brick['vars'][1]));
	$brick['data'] = wrap_db_fetch($sql);
	if (!$brick['data']) wrap_quit(404);
	$brick['data'] = wrap_translate($brick['data'], 'publications');

	if ($brick['data']['parameters']) {
		parse_str($brick['data']['parameters'], $brick['data']['parameters']);
		wrap_setting_from_table('publications', $brick['data']['parameters']);
	} else {
		$brick['data']['parameters'] = [];
	}

	wrap_page_meta('breadcrumb_placeholder', [
		'title' => $brick['data']['publication'],
		'url_path' => $brick['data']['identifier']
	]);
	return $brick;
}
