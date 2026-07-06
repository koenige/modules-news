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
 * get _prev, _next-keys for article
 *
 * @param array $article
 * @return array
 */
function mf_news_prev_next($article) {
	if (!empty($article['publications'])) {
		$publication_id = reset($article['publications']);
		$publication_id = $publication_id['category_id'];
		$sql = 'SELECT articles.article_id, title, identifier
			FROM articles
			LEFT JOIN articles_categories
				ON articles_categories.article_id = articles.article_id
				AND articles_categories.type_category_id = /*_ID categories publications _*/
			WHERE articles_categories.category_id = %d
			AND published = "yes"
			ORDER BY identifier DESC';
		$sql = sprintf($sql, $publication_id);
	} else {
		$sql = 'SELECT articles.article_id, title, identifier
			FROM articles
			WHERE published = "yes"
			ORDER BY identifier DESC';
	}
	$articles = wrap_db_fetch($sql, 'article_id');
	$article += wrap_get_prevnext_flat($articles, $article['article_id'], false);
	return $article;
}

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

/**
 * years for url_placeholders[year] from articles and events
 *
 * @return array
 */
function mf_news_url_placeholder_year_list() {
	$years = [];

	$sql = 'SELECT DISTINCT YEAR(date) AS year
		FROM /*_PREFIX_*/articles
		ORDER BY year';
	$article_years = wrap_db_fetch($sql, '_dummy_', 'single value');
	if ($article_years)
		$years = array_merge($years, $article_years);

	if (wrap_package('events')) {
		$sql = 'SELECT DISTINCT YEAR(IFNULL(date_begin, date_end)) AS year
			FROM /*_PREFIX_*/events
			ORDER BY year';
		$event_years = wrap_db_fetch($sql, '_dummy_', 'single value');
		if ($event_years)
			$years = array_merge($years, $event_years);
	}
	if (!$years) return [];

	$years = array_unique(array_map('intval', $years));
	$offset = (int) wrap_setting('news_url_placeholder_year_future_offset');
	if (wrap_package('events')) {
		$events_offset = wrap_setting('events_url_placeholder_year_future_offset');
		if ($events_offset !== NULL && $events_offset !== '')
			$offset = max($offset, (int) $events_offset);
	}

	$max_year = max(max($years), (int) date('Y')) + $offset;
	return range((int) min($years), $max_year);
}

/**
 * write url_placeholders[year] from articles and events
 *
 * @return void
 */
function mf_news_url_placeholder_years_write() {
	$all_years = mf_news_url_placeholder_year_list();
	if (!$all_years) return;

	$existing = wrap_setting('url_placeholders[year]');
	if ($existing == $all_years) return;

	wrap_setting_write('url_placeholders[year]', '['.implode(', ', $all_years).']');
}
