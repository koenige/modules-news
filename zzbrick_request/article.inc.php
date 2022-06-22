<?php

/**
 * news module
 * Output single article
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2014-2015, 2017-2022 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


function mod_news_article($params) {
	global $zz_setting;
	if (count($params) === 1 AND is_numeric($params[0])) {
		return brick_format('%%% request articles '.$params[0].' %%%');
	}
	if (count($params) !== 2) return false;

	$where[] = sprintf('identifier = "%s"', wrap_db_escape(implode('/', $params)));
	if (empty($_SESSION['logged_in'])) {
		$where[] = 'articles.published = "yes"';
	}

	$sql = 'SELECT article_id
		FROM articles
		WHERE %s
		ORDER BY date DESC, time DESC, identifier DESC';
	$sql = sprintf($sql, implode(' AND ', $where));
	$article_id = wrap_db_fetch($sql, '', 'single value');
	if (!$article_id) return false;

	$articles = brick_request_data('articles');
	if (empty($articles[$article_id])) return false;
	$article = $articles[$article_id];
	
	// article in other languages?
	$sql = 'SELECT language_id, iso_639_1, iso_639_1 AS language_base
			, "%s" AS identifier
			, IF("%s" = iso_639_1, 1, NULL) AS current
		FROM _translations_varchar
		JOIN _translationfields USING (translationfield_id)
		LEFT JOIN languages USING (language_id)
		WHERE db_name = (SELECT DATABASE())
		AND table_name = "articles"
		AND field_name = "title"
		AND field_type = "varchar"
		AND _translations_varchar.field_id = %d';
	$sql = sprintf($sql
		, $article['identifier']
		, $zz_setting['lang']
		, $article['article_id']
	);
	$article['languages'] = wrap_db_fetch($sql, 'language_id');
	if ($article['languages'])
		$article['languages'][] = [
			'identifier' => $article['identifier'],
			'iso_639_1' => $zz_setting['default_source_language'],
			'current' => $zz_setting['default_source_language'] === $zz_setting['lang'] ? 1 : NULL
		];

	if (!empty(wrap_get_setting('news_with_events'))) {
		$sql = 'SELECT event_id, event
				, CONCAT(date_begin, IFNULL(CONCAT("/", date_end), "")) AS duration
				, TIME_FORMAT(time_begin, "%%H.%%i") AS time_begin
				, TIME_FORMAT(time_end, "%%H.%%i") AS time_end
				, identifier
			FROM events
			LEFT JOIN articles_events USING (event_id)
			WHERE published = "yes"
			AND article_id = %d
			ORDER BY date_begin, time_begin';
		$sql = sprintf($sql, $article['article_id']);
		$article['events'] = wrap_db_fetch($sql, 'event_id');
	}

	$media = wrap_get_media($article['article_id'], 'articles', 'article');
	if (!empty($media['links'])) {
		$article['links'] = wrap_template('filelinks', $media['links']);
	}
	if (!empty($media['images'])) {
		$first_img = key($media['images']);
		$main_img = $media['images'][$first_img];
	}
	brick_request_links($article['article'], $media, 'sequence');

	if (!empty($media['images'])) {
		if (key($media['images']) === $first_img) {
			// main image only if first image was not set manually
			$media['images'][$first_img]['path'] = $zz_setting['news_topimage_size'];
			$article['topimage'] = brick_request_link($media, ['image', $main_img['sequence']], 'sequence');
		}
		if ($media['images']) {
			$article['newsgallery'] = wrap_template('newsgallery', $media['images']);
		}
	}
	$article['videos'] = !empty($media['videos']) ? $media['videos'] : [];

	// prev next
	$article += wrap_get_prevnext_flat($articles, $article['article_id'], false);

	if (!empty($article['_next_identifier'])) {
		$page['link']['next'][0]['href'] = '../../'.$article['_next_identifier'].'/';	
		$page['link']['next'][0]['title'] = $article['_next_title'];
	}
	if (!empty($article['_prev_identifier'])) {
		$page['link']['prev'][0]['href'] = '../../'.$article['_prev_identifier'].'/';	
		$page['link']['prev'][0]['title'] = $article['_prev_title'];
	}
	
	$sql = 'SELECT categories.category_id, categories.category
			, types.category_id AS type_category_id
			, types.category AS type
			, SUBSTRING_INDEX(categories.path, "/", -1) AS path
			, categories.parameters
		FROM articles_categories
		LEFT JOIN categories USING (category_id)
		LEFT JOIN categories types
			ON types.category_id = articles_categories.type_category_id
		WHERE article_id = %d
		ORDER BY articles_categories.sequence, categories.path';
	$sql = sprintf($sql, $article['article_id']);
	$article['categories'] = wrap_db_fetch($sql, 'category_id');
	$article['categories'] = wrap_translate($article['categories'], 'categories');
	// following translation probably never necessary, therefore inactive
	// $article['categories'] = wrap_translate($article['categories'], ['type' => 'categories.category'], 'type_category_id');
	foreach ($article['categories'] as $category_id => $category) {
		if (wrap_category_id('publications', 'check')
			AND $category['type_category_id'] === wrap_category_id('publications')) {
			$article['publication'] = $category['category'];
			if ($category['parameters']) {
				parse_str($category['parameters'], $parameters);
				foreach ($parameters as $key => $value) {
					$article['setting_'.$key] = $value;
				}
			}
			unset($article['categories'][$category_id]);
		}
	}
	
	$page['title'] = $article['title'];
	$tree = explode('/', $article['identifier']);
	array_pop($tree);
	$i = count($tree);
	foreach ($tree as $path) {
		$url = str_repeat('../', $i);
		$page['breadcrumbs'][] = '<a href="'.$url.'">'.$path.'</a>';
		$i--;
	}
	$page['opengraph'] = [
		'og:type' => 'article',
		'og:title' => wrap_html_escape(strip_tags($article['title'])),
		'og:description' => wrap_html_escape(trim(strip_tags(markdown($article['abstract'])))),
		'article:published_time' => $article['date'].($article['time'] ? 'T'.$article['time'] : ''),
		'article:modified_time' => $article['modified']
	];
	if (!empty($article['author'])) {
		$page['opengraph']['article:author'] = [];
		foreach ($article['author'] as $author)
			$page['opengraph']['article:author'][] = $author['contact'];
	}
	if (!empty($article['publication'])) {
		$page['opengraph']['article:section'] = $article['publication'];
	}
	if (!empty($article['categories'])) {
		$page['opengraph']['article:tags'] = [];
		foreach ($article['categories'] as $category)
			$page['opengraph']['article:tags'][] = $category['category'];
	}
	if (!empty($main_img) AND function_exists('mf_media_opengraph_image')) {
		$page['opengraph'] += mf_media_opengraph_image($main_img, wrap_get_setting('news_og_image_size'));
	}
	if (empty($article['wrap_source_language'])) {
		// no translation
		if (!empty($zz_setting['default_source_language']))
			if ($zz_setting['default_source_language'] !== $zz_setting['lang']) {
				$page['meta'][] = ['name' => 'robots', 'content' => 'noindex'];
			}
	}
	$page['breadcrumbs'][] = $article['title'];
	$page['text'] = wrap_template('article', $article);
	if (in_array('magnificpopup', $zz_setting['modules']))
		$page['extra']['magnific_popup'] = true;
	return $page;
}
