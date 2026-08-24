<?php

/**
 * news module
 * search functions
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2020, 2022-2026 Gustaf Mossakowski
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
	$sql = 'SELECT publication_id, publication, identifier
		FROM publications';
	$publications = wrap_db_fetch($sql, 'publication_id');
	$publications = wrap_translate($publications, 'publications');
	foreach ($publications as $publication) {
		$data['news'][$publication['identifier']]['publication'] = $publication['publication'];
		$data['news'][$publication['identifier']]['publication_identifier'] = $publication['identifier'];
	}

	$sql = 'SELECT articles.article_id, date, title, abstract, identifier
			, publication
			, publications.identifier AS publication_identifier
		FROM articles
		LEFT JOIN publications USING (publication_id)
		WHERE %s
		AND published = "yes"
		ORDER BY publications.sequence, publications.identifier, date DESC, time DESC, title';
	$sql = sprintf($sql, implode(' AND ', $where));
	$articles = wrap_db_fetch($sql, 'article_id');
	$articles = mf_news_media($articles);
	foreach ($articles as $article_id => $article) {
		$article['link'] = wrap_path('news_article', $article['identifier']);
		$data['news'][$article['publication_identifier']]['articles'][$article_id] = $article;
	}
	$data['news'] = array_values($data['news']);
	return $data;
}

function mf_news_media($articles) {
	if (!$articles) return [];
	$media = wrap_media(array_keys($articles), 'articles');
	foreach ($media as $id => $files) {
		$articles[$id] += $files;
	}
	return $articles;
}
