<?php

/**
 * news module
 * Output articles as list
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2014-2023 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


function mod_news_articles($params, $settings) {
	$data = brick_request_data('articles', $params, $settings);
	if (!empty($settings['events_in_news']) AND in_array('events', wrap_setting('modules'))) {
		wrap_include_files('events/news', 'events');
		$events = mf_events_in_news();
		// show at least one date if there is one
		if (!$data and !$events) $events = mf_events_in_news('latest');
		$data = mf_events_in_news_sort($data, $events);
	}
	if (!$data AND $params AND !is_numeric($params[0])) {
		$data = [];
		$data['category_not_found'] = true;
		$data['titles'] = [];
		$data['count'] = 0;
	}
	
	if (empty($settings['hide_title'])) {
		$page['title'][] = !empty($settings['title_prefix'])
			? $settings['title_prefix'] : wrap_text('News Articles');
	}
	
	$has = [];
	foreach ($data['titles'] as $area => $title) {
		$has[$area] = true;
		switch ($area) {
		case 'category':
			$sql = 'SELECT category FROM categories WHERE category_id = %d';
			$sql = sprintf($sql, wrap_category_id($title));
			$title = wrap_db_fetch($sql, '', 'single value');
			break;
		case 'year':
			break;
		case 'month':
			break;
		default:
			break;
		}
		$page['breadcrumbs'][]['title'] = $title;
		if (empty($settings['hide_title'])) {
			$page['title'][] = $title;
		}
	}
	if (!empty($page['title']))
		$page['title'] = implode(' &middot; ', $page['title']);

	if (!$data['count']) {
		foreach (array_keys($has) as $area) {
			$data['no_news_'.$area] = true;
		}
		if (!$params)
			$data['no_news_current'] = true;
		$page['status'] = 404;
	} else {
		// set 200 explicitly, if in conjunction with other request script that throws 404
		$page['status'] = 200;
	}

	if (!empty($settings['hide_categories']))
		foreach (array_keys($data) as $id) {
			if (!is_numeric($id)) continue;
			unset($data[$id]['categories']);
		}

	if (!empty($page['title'])) $data['h1'] = $page['title'];
	$page['text'] = wrap_template($settings['template'] ?? 'articles', $data);
	return $page;
}
