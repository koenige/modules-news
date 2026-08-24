<?php 

/**
 * news module
 * get article data
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2020-2026 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


/**
 * get article data
 *
 * @param array $params
 * @param array $settings
 * @return array
 */
function mod_news_get_articles($params = [], $settings = []) {
	// news categories
	$news_categories_ids = [];
	if ($category_id = wrap_category_id('news', 'check'))
		$news_categories_ids[] = $category_id;
	
	// titles
	$titles = [];

	// conditions
	$where = [];
	$join = [];
	if (!empty($settings['months'])) {
		$where[] = sprintf('DATEDIFF(CURDATE(), date) < %d * 30', $settings['months']);
	}
	if (!wrap_access('news_preview')) {
		$where[] = 'articles.published = "yes"';
		$where[] = 'date <= CURDATE() AND (ISNULL(date_to) OR date_to >= CURDATE())';
	}
	if ($params) {
		$param = array_shift($params);
		if ($publication_id = wrap_id('publications', $param)) {
			$where[] = sprintf('publication_id = %d', $publication_id);
			$param = array_shift($params);
		}
		
		// check: is it a category?
		$i = 0;
		while (!is_numeric($param)) {
			$i++;
			if ($i > 2) break;
			
			if (!$category_id = wrap_category_id(sprintf('news/%s', $param), 'check')) continue;
			$join[] = ' LEFT JOIN articles_categories
				ON articles.article_id = articles_categories.article_id
				AND articles_categories.type_category_id = /*_ID categories news _*/
				LEFT JOIN categories
					ON articles_categories.category_id = categories.category_id
			';
			$where[] = sprintf('articles_categories.category_id = %d', $category_id);
			$titles['category'] = 'news/'.$param;
			$param = array_shift($params); // allow another parameter
			
			$sql = 'SELECT parameters
				FROM /*_PREFIX_*/categories
				WHERE category_id = %d';
			$sql = sprintf($sql, $category_id);
			$news_category_parameters = wrap_db_fetch($sql, '', 'single value');
			wrap_setting_from_table('news', $news_category_parameters);
			break;
		}
		if (is_numeric($param)) {
			$where[] = sprintf('YEAR(date) = %d', $param);
			$titles['year'] = $param;
			$param = array_shift($params); // allow another parameter
		}
		if (is_numeric($param)) {
			$where[] = sprintf('MONTH(date) = %d', $param);
			$titles['month'] = $param;
			$param = array_shift($params); // allow another parameter
		}
		if ($params) return false; // wrong parameter count, illegal parameters
	}
	$hidden_article_ids = mf_news_hidden_article_ids($settings);
	if ($hidden_article_ids)
		$where[] = sprintf('articles.article_id NOT IN (%s)', implode(',', $hidden_article_ids));

	// Articles
	$sql = 'SELECT articles.article_id
		FROM articles
		%s
		%s
		ORDER BY date DESC, time DESC, identifier DESC
		%s
	';
	$sql = sprintf($sql
		, implode("\n", $join)
		, $where ? 'WHERE '.implode(' AND ', $where) : ''
		, !empty($settings['last']) ? sprintf('LIMIT 0, %d', $settings['last']) : ''
	);
	$articles = wrap_db_fetch($sql, 'article_id');

	wrap_include('data', 'zzwrap');
	$articles = wrap_data('articles', $articles);
	$articles['count'] = count($articles);
	$articles['titles'] = $titles;
	return $articles;
}
