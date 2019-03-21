<?php

/**
 * Zugzwang Project
 * Output articles as list
 *
 * http://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2014-2019 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


function mod_news_articles($params, $settings) {
	global $zz_setting;

	if (count($params) > 1) wrap_quit(404);

	if (file_exists($zz_setting['custom'].'/zzbrick_request_get/articles.inc.php')) {
		require $zz_setting['custom'].'/zzbrick_request_get/articles.inc.php';
		$data = cms_get_articles($params, $settings);
	} else {
		require __DIR__.'/../zzbrick_request_get/articles.inc.php';
		$data = mod_news_get_articles($params, $settings);
	}

	if ($params AND is_numeric($params[0])) {
		// overview year
		if (empty($settings['hide_title'])) {
			$page['title'] = wrap_text('News Articles').' '.$params[0];
		}
		if (!$data) {
			$data['no_news_year'] = true;
			// events?
			if (!empty($zz_setting['news_with_events'])) {
				$sql = 'SELECT COUNT(event_id) FROM events WHERE YEAR(date_begin) = %d';
				$sql = sprintf($sql, $params[0]);
				$events = wrap_db_fetch($sql);
			} else {
				$events = [];
			}
			if (!$events) {
				$page['status'] = 404;
			}
		}
		$page['breadcrumbs'][] = end($params);
	} elseif ($params AND $params[0] !== 'homepage') {
		// category overview
		if (!$data) {
			$data['no_news_category'] = true;
			$page['status'] = 404;
		}
		$sql = 'SELECT category_id, category
			FROM categories WHERE path = "%s/%s"';
		$sql = sprintf($sql, $zz_setting['news_category_path'], wrap_db_escape($params[0]));
		$category = wrap_db_fetch($sql);
		if ($category) {
			if (empty($settings['hide_title'])) {
				$page['title'] = wrap_text('News Articles').' '.$category['category'];
			}
			$page['breadcrumbs'][] = $category['category'];
		} else {
			if (empty($settings['hide_title'])) {
				$page['title'] = wrap_text('News Articles').' '.wrap_text('unknown');
			}
		}
	} else {
		// individual view, e. g. homepage
		if (empty($settings['hide_title'])) {
			$page['title'] = wrap_text('News Articles');
		}
		if (!$data) {
			$data['no_news_current'] = true;
		}
	}

	if (!empty($page['title'])) $data['h1'] = $page['title'];
	$page['text'] = wrap_template('articles', $data);
	return $page;
}