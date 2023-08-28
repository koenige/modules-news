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
 * set $page['link'] for single item
 *
 * @param array $data (_next_identifier, _next_title, _prev_identifier, _prev_title,
 *		_main_identifier or identifier, _main_title)
 * @param string $path
 * @param string $path_overview
 * @return array
 */
function wrap_page_links($data, $path, $path_overview) {
	$link = [];
	if (!empty($data['_next_identifier'])) {
		$link['next'][0]['href'] = wrap_path($path, $data['_next_identifier']);	
		$link['next'][0]['title'] = $data['_next_title'];
	} else {
		$link['next'][0]['href'] = wrap_path($path_overview, $data['_main_identifier'] ?? dirname($data['identifier']));
		$link['next'][0]['title'] = $data['_main_title'] ?? wrap_text('Overview');
	}
	if (!empty($data['_prev_identifier'])) {
		$link['prev'][0]['href'] = wrap_path($path, $data['_prev_identifier']);	
		$link['prev'][0]['title'] = $data['_prev_title'];
	} else {
		$link['prev'][0]['href'] = wrap_path($path_overview, $data['_main_identifier'] ?? dirname($data['identifier']));
		$link['prev'][0]['title'] = $data['_main_title'] ?? wrap_text('Overview');
	}
	return $link;
}
