<?php

/**
 * news module
 * list of years with articles
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2026 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


/**
 * output a list of years with articles
 *
 * @param array $params optional numeric year to highlight
 * @param array $settings
 * @return array
 */
function mod_news_articles_years($params, $settings) {
	if (count($params) > 1) return false;

	$current_year = NULL;
	if ($params && is_numeric($params[0]))
		$current_year = (int) $params[0];

	wrap_include('news', 'functions');
	mf_news_url_placeholder_years_write();
	$data = mf_news_article_years($current_year, $settings);
	if (!$data['years'])
		$data['no_years'] = true;

	$page['status'] = 200;
	$template = $settings['template'] ?? 'articles-years';
	$page['text'] = wrap_template($template, $data);
	return $page;
}
