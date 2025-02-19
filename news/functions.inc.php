<?php

/**
 * news module
 * module functions if module is active
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2022-2024 Gustaf Mossakowski
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
