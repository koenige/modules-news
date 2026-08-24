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
	if (!empty($settings['hide_no_archive'])) {
		$news_categories_ids = [];
		foreach (['publications', 'news'] as $path) {
			if (!$category_id = wrap_category_id($path, 'check')) continue;
			$news_categories_ids[] = $category_id;
		}
		if ($news_categories_ids) {
			$sql = 'SELECT article_id
				FROM articles
				JOIN articles_categories USING (article_id)
				JOIN categories USING (category_id)
				WHERE main_category_id IN (%s)
				AND parameters LIKE "%%&no_archive=1%%"';
			$sql = sprintf($sql, implode(',', $news_categories_ids));
			$hidden_article_ids = wrap_db_fetch($sql, 'article_id', 'single value');
			if ($hidden_article_ids)
				$where[] = sprintf('articles.article_id NOT IN (%s)', implode(',', $hidden_article_ids));
		}
	}

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
