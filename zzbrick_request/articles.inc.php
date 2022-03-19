<?php

/**
 * news module
 * Output articles as list
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2014-2022 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


function mod_news_articles($params, $settings) {
	global $zz_setting;

	$data = brick_request_data('articles', $params, $settings);
	if (!empty($settings['events_in_news']) AND in_array('events', $zz_setting['modules'])) {
		wrap_include_files('events/news', 'events');
		$events = mf_events_in_news();
		// show at least one date if there is one
		if (!$data and !$events) $events = mf_events_in_news('latest');
		$data = mf_events_in_news_sort($data, $events);
	}

	$title_prefix = wrap_text('News Articles');
	if (!empty($settings['title_prefix'])) $title_prefix = $settings['title_prefix'];

	if ($params AND is_numeric(end($params))) {
		// overview year
		if (empty($settings['hide_title'])) {
			$page['title'] = $title_prefix.' '.end($params);
		}
		if (!$data) {
			$data['no_news_year'] = true;
			// events?
			if (!empty(wrap_get_setting('news_with_events'))) {
				$sql = 'SELECT COUNT(event_id) FROM events WHERE YEAR(date_begin) = %d';
				$sql = sprintf($sql, end($params));
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
		$type = !empty($settings['type']) ? $settings['type'] : 'news';
		$sql = 'SELECT category_id, category
			FROM categories WHERE path = "%s/%s"';
		$sql = sprintf($sql, wrap_get_setting($type.'_category_path'), wrap_db_escape($params[0]));
		$category = wrap_db_fetch($sql);
		if (!$category) {
			$data['category_not_found'] = true;
			$page['breadcrumbs'][] = wrap_text('Not Found');
			$page['text'] = wrap_template('articles', $data);
			return $page;
		}
		if (empty($settings['hide_title'])) {
			$page['title'] = $title_prefix.' '.$category['category'];
		}
		$page['breadcrumbs'][] = $category['category'];
	} else {
		// individual view, e. g. homepage
		if (empty($settings['hide_title'])) {
			$page['title'] = wrap_text('News Articles');
		}
		if (!$data) {
			$data['no_news_current'] = true;
		}
	}
	
	if (!empty($settings['hide_categories']))
		foreach ($data as $id => $line)
			unset($data[$id]['categories']);

	if (!empty($page['title'])) $data['h1'] = $page['title'];
	$page['text'] = wrap_template('articles', $data);
	return $page;
}
