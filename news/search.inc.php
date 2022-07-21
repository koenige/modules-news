<?php

/**
 * news module
 * search functions
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2020, 2022 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


function mf_news_search($q) {
	$where_sql = '(title LIKE "%%%s%%" OR abstract LIKE "%%%s%%"
		OR article LIKE "%%%s%%")';
	$where = [];
	foreach ($q as $string) {
		$where[] = sprintf($where_sql, $string, $string, $string);
	}

	$data['news'] = [];
	$sql = 'SELECT category_id, category
			, SUBSTRING_INDEX(path, "/", -1) AS path
		FROM categories
		LEFT JOIN articles_categories USING (category_id)
		WHERE articles_categories.type_category_id = %d';
	$sql = sprintf($sql, wrap_category_id('publications'));
	$publications = wrap_db_fetch($sql, 'category_id');
	foreach ($publications as $publication) {
		$data['news'][$publication['path']]['publication'] = $publication['category'];
	}

	$sql = 'SELECT articles.article_id, date, title, abstract, identifier
				, category AS publication
				, SUBSTRING_INDEX(path, "/", -1) AS path
		FROM articles
		LEFT JOIN articles_categories
			ON articles_categories.article_id = articles.article_id
			AND articles_categories.type_category_id = %d
		LEFT JOIN categories USING (category_id)
		WHERE %s
		AND published = "yes"
		ORDER BY categories.sequence, date DESC, time DESC, title';
	$sql = sprintf($sql
		, wrap_category_id('publications')
		, implode(' AND ', $where)
	);
	$articles = wrap_db_fetch($sql, 'article_id');
	$articles = mf_news_media($articles);
	foreach ($articles as $article_id => $article) {
		$data['news'][$article['path']]['articles'][$article_id] = $article;
	}
	$data['news'] = array_values($data['news']);
	return $data;
}

function mf_news_media($articles) {
	if (!$articles) return [];
	$media = wrap_get_media(array_keys($articles), 'articles', 'article');
	foreach ($media as $id => $files) {
		$articles[$id] += $files;
	}
	return $articles;
}