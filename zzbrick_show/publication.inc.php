<?php 

/**
 * news module
 * publication header on article and issue forms
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2026 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


/**
 * show publication description and a link to the sibling form
 *
 * @param array $params
 * @param array $settings merged $zz and $ops
 * @param array $ops
 * @return array|false
 */
function mod_news_show_publication($params, $settings, $ops) {
	if (empty($settings['vars']['publication'])) return false;

	$data = $settings['vars']['publication'];
	if (empty($data['identifier'])) return false;

	$data = mod_news_show_publication_links($data);
	if (empty($data['description']) AND empty($data['issues_path']) AND empty($data['articles_path']))
		return false;

	$page['text'] = wrap_template('publication', $data);
	return $page;
}

/**
 * add path to issues or articles form for issued publications
 *
 * @param array $data publication row
 * @return array
 */
function mod_news_show_publication_links($data) {
	if (empty($data['issued_publication'])) return $data;

	if (!empty($data['issues_form'])) {
		$path = wrap_path('news_publication_internal', $data['identifier']);
		if ($path) $data['articles_path'] = $path;
		return $data;
	}

	$path = wrap_path('news_publication_issues_internal', $data['identifier']);
	if ($path) $data['issues_path'] = $path;
	return $data;
}
