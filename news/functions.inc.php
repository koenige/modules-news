<?php

/**
 * news module
 * module functions if module is active
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2022 Gustaf Mossakowski
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
				AND articles_categories.type_category_id = %d
			WHERE articles_categories.category_id = %d
			AND published = "yes"
			ORDER BY identifier DESC';
		$sql = sprintf($sql
			, wrap_category_id('publications')
			, $publication_id
		);
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
 * set $page['link'] for article
 *
 * @param array $article
 * @return array
 */
function mf_news_page_link($article) {
	$link = [];
	if (!empty($article['_next_identifier'])) {
		$link['next'][0]['href'] = sprintf('/%s/', $article['_next_identifier']);	
		$link['next'][0]['title'] = $article['_next_title'];
	} else {
		$link['next'][0]['href'] = sprintf('/%s/', dirname($article['identifier']));
		$link['next'][0]['title'] = wrap_text('Overview');
	}
	if (!empty($article['_prev_identifier'])) {
		$link['prev'][0]['href'] = sprintf('/%s/', $article['_prev_identifier']);	
		$link['prev'][0]['title'] = $article['_prev_title'];
	} else {
		$link['prev'][0]['href'] = sprintf('/%s/', dirname($article['identifier']));
		$link['prev'][0]['title'] = wrap_text('Overview');
	}
	return $link;
}
