<?php

/**
 * Zugzwang Project
 * Output single article
 *
 * http://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2014-2015, 2017-2019 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


function mod_news_article($params) {
	global $zz_setting;
	if (count($params) !== 2) return false;

	if ($zz_setting['local_access']) {
		$published = '(articles.published = "yes" OR articles.published = "no")';
	} else {
		$published = 'articles.published = "yes"';
	}

	$sql = 'SELECT article_id, articles.date, articles.time, articles.identifier
			, articles.abstract, articles.title, direct_link, article
		FROM articles articles
		WHERE %s
		AND identifier = "%s"
		ORDER BY date DESC, time DESC, identifier DESC
	';
	$sql = sprintf($sql, $published, wrap_db_escape(implode('/', $params)));
	$article = wrap_db_fetch($sql);
	if (!$article) return false;
	$article = wrap_translate($article, 'articles');

	if (!empty($zz_setting['news_with_events'])) {
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
			$article['topimage'] = wrap_template('topimage', array_shift($media['images']));
		}
		if ($media['images']) {
			$article['newsgallery'] = wrap_template('newsgallery', $media['images']);
		}
	}

	// prev next
	if (file_exists($zz_setting['custom'].'/zzbrick_request_get/articles.inc.php')) {
		require_once $zz_setting['custom'].'/zzbrick_request_get/articles.inc.php';
		$articles = cms_get_articles();
	} else {
		require_once __DIR__.'/../zzbrick_request_get/articles.inc.php';
		$articles = mod_news_get_articles();
	}
	$article += wrap_get_prevnext_flat($articles, $article['article_id'], false);

	if (!empty($article['_next_identifier'])) {
		$page['link']['next'][0]['href'] = '../../'.$article['_next_identifier'].'/';	
		$page['link']['next'][0]['title'] = $article['_next_title'];
	}
	if (!empty($article['_prev_identifier'])) {
		$page['link']['prev'][0]['href'] = '../../'.$article['_prev_identifier'].'/';	
		$page['link']['prev'][0]['title'] = $article['_prev_title'];
	}
	
	$sql = 'SELECT category_id, category
		FROM articles_categories
		LEFT JOIN categories USING (category_id)
		WHERE article_id = %d
		ORDER BY articles_categories.sequence, path';
	$sql = sprintf($sql, $article['article_id']);
	$article['categories'] = wrap_db_fetch($sql, 'category_id');
	
	$page['title'] = $article['title'];
	$tree = explode('/', $article['identifier']);
	array_pop($tree);
	$i = count($tree);
	foreach ($tree as $path) {
		$url = str_repeat('../', $i);
		$page['breadcrumbs'][] = '<a href="'.$url.'">'.$path.'</a>';
		$i--;
	}
	$page['meta'] = [
		0 => ['property' => 'og:url', 'content' => $zz_setting['host_base'].$zz_setting['request_uri']],
		1 => ['property' => 'og:type', 'content' => 'article'],
		2 => ['property' => 'og:title', 'content' => wrap_html_escape($article['title'])],
		3 => ['property' => 'og:description', 'content' => wrap_html_escape($article['abstract'])]
	];
	if (!empty($main_img)) {
		$page['meta'][] 
			= ['property' => 'og:image', 'content' => $zz_setting['host_base'].$zz_setting['files_path'].'/'.$main_img['filename'].'.'.wrap_get_setting('news_og_image_size').'.'.$main_img['extension'].'?v='.$main_img['version']];
	}
	$page['breadcrumbs'][] = $article['title'];
	$page['text'] = wrap_template('article', $article);
	return $page;
}
