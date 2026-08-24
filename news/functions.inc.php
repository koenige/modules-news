<?php

/**
 * news module
 * module functions if module is active
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2022-2026 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


/**
 * years with published articles for archive navigation
 *
 * @param int|null $current_year highlight this year in the list
 * @param array $settings request settings (e.g. hide_no_archive)
 * @return array with key years
 */
function mf_news_article_years($current_year = NULL, $settings = []) {
	$where = [];
	if (!wrap_access('news_preview')) {
		$where[] = 'articles.published = "yes"';
		$where[] = 'date <= CURDATE()';
	}
	$hidden_article_ids = mf_news_hidden_article_ids($settings);
	if ($hidden_article_ids)
		$where[] = sprintf('articles.article_id NOT IN (%s)', implode(',', $hidden_article_ids));

	$sql = 'SELECT DISTINCT YEAR(date) AS year
		FROM articles
		%s
		ORDER BY year DESC';
	$sql = sprintf($sql, $where ? 'WHERE '.implode(' AND ', $where) : '');
	$rows = wrap_db_fetch($sql, 'year');

	$years = [];
	foreach ($rows as $year => $row) {
		$item = [
			'year' => (int) $year,
			'link' => (int) $year,
		];
		if ($current_year !== NULL AND (int) $year === (int) $current_year)
			$item['current_year'] = true;
		$years[$year] = $item;
	}
	return ['years' => $years];
}

/**
 * find article IDs that are hidden from search or archive
 * no_archive can be set per news category or publication
 *
 * @param array $settings
 * @return array
 */
function mf_news_hidden_article_ids($settings) {
	if (empty($settings['hide_no_archive'])) return [];
	if (!wrap_category_id('news', 'check')) return [];

	$sql = 'SELECT article_id
		FROM articles
		LEFT JOIN articles_categories USING (article_id)
		LEFT JOIN categories
			ON articles_categories.category_id = categories.category_id
			AND categories.main_category_id = /*_ID categories news _*/
		LEFT JOIN publications USING (publication_id)
		WHERE categories.parameters LIKE "%&no_archive=1%"
		OR publications.parameters LIKE "%&no_archive=1%"';
	return wrap_db_fetch($sql, 'article_id', 'single value');
}
