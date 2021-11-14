<?php 

/**
 * news module
 * get article data
 *
 * Part of »Zugzwang Project«
 * http://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2020-2021 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


/**
 * get article data
 *
 * @param array $params
 * @param array $settings
 * @return array
 */
function mod_news_get_articles($params, $settings = []) {
	global $zz_setting;
	if (count($params) > 1) return false;

	if (wrap_category_id('news', 'check')) {
		$news_category_id = wrap_category_id('news');
	} else {
		$news_category_id = false;
	}

	$where = [];
	$limit = '';
	if ($zz_setting['local_access'] OR !empty($_SESSION['logged_in'])) {
		$where[] = '(articles.published = "yes" OR articles.published = "no")';
	} else {
		$where[] = 'articles.published = "yes"';
	}
	
	if ($news_category_id) {
		$join = sprintf(' LEFT JOIN articles_categories
			ON articles.article_id = articles_categories.article_id
			AND articles_categories.type_category_id = %d
			LEFT JOIN categories USING (category_id)
		', $news_category_id);
	} else {
		$join = '';
	}

	// Articles
	$sql = 'SELECT articles.article_id
		FROM articles
		%s
		WHERE date <= CURDATE() AND (ISNULL(date_to) OR date_to >= CURDATE())
		%s
		ORDER BY date DESC, time DESC, identifier DESC
		%s
	';
	$sql = sprintf($sql
		, $join
		, ($where ? ' AND '.implode(' AND ', $where) : '')
		, $limit
	);
	$ids = wrap_db_fetch($sql, 'article_id');

	require_once $zz_setting['modules_dir'].'/news/zzbrick_request_get/articledata.inc.php';
	$articles = mod_news_get_articledata($ids);

	$i = 0;
	foreach (array_keys($articles) as $id) {
		$i++;
		$articles[$id]['article_no'] = $i;
	}

	return $articles;
}