<?php 

/**
 * news module
 * get article data
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2020-2023 Gustaf Mossakowski
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
	$news_categories = ['publications', 'news'];
	$news_categories_ids = [];
	foreach ($news_categories as $index => $path) {
		if (!$category_id = wrap_category_id($path, 'check')) {
			unset($news_categories[$index]);
			continue;
		}
		$news_categories_ids[] = $category_id; 
	}
	
	// titles
	$titles = [];

	// conditions
	$where = [];
	$join = [];
	if (!empty($settings['months'])) {
		$where[] = sprintf('DATEDIFF(CURDATE(), date) < %d * 30', $settings['months']);
	}
	if (empty($_SESSION['logged_in'])) {
		$where[] = 'articles.published = "yes"';
		$where[] = 'date <= CURDATE() AND (ISNULL(date_to) OR date_to >= CURDATE())';
	}
	if ($params) {
		$param = array_shift($params);
		
		// check: is it a category?
		$i = 0;
		while (!is_numeric($param)) {
			$i++;
			if ($i > 2) break;
			foreach ($news_categories as $index => $path) {
				if (!$category_id = wrap_category_id(sprintf('%s/%s', $path, $param), 'check')) continue;
				$join[] = sprintf(' LEFT JOIN articles_categories ac_%s
					ON articles.article_id = ac_%s.article_id
					AND ac_%s.type_category_id = %d
					LEFT JOIN categories categories_%s
						ON categories_%s.category_id = ac_%s.category_id
				', $path, $path, $path, wrap_category_id($path), $path, $path, $path);
				$where[] = sprintf('ac_%s.category_id = %d', $path, $category_id);
				$titles['category'] = $path.'/'.$param;
				$param = array_shift($params); // allow another parameter
				
				$sql = 'SELECT parameters
					FROM /*_PREFIX_*/categories
					WHERE category_id = %d';
				$sql = sprintf($sql, $category_id);
				$news_category_parameters = wrap_db_fetch($sql, '', 'single value');
				wrap_module_parameters('news', $news_category_parameters);
				break;
			}
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
	if (!empty($settings['hide_no_archive']) AND $news_categories) {
		// hide_no_archive can be set per news category or publication
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
	$ids = wrap_db_fetch($sql, 'article_id');

	require_once wrap_setting('modules_dir').'/news/zzbrick_request_get/articledata.inc.php';
	$articles = mod_news_get_articledata($ids);
	$articles['count'] = count($articles);
	$articles['titles'] = $titles;
	return $articles;
}
