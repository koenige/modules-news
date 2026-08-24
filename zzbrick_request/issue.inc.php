<?php

/**
 * news module
 * Output single publication issue (edition)
 *
 * Example: %%% request issue PUBLICATION * %%%
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2026 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


function mod_news_issue($params, $settings = [], $data = []) {
	if (!$params) return false;
	
	$publication_identifier = array_shift($params);
	if (!$params) return false;

	$sql = 'SELECT /*_PREFIX_*/issues.*
			, /*_PREFIX_*/publications.publication
			, /*_PREFIX_*/publications.identifier AS publication_identifier
		FROM /*_PREFIX_*/issues
		INNER JOIN /*_PREFIX_*/publications USING (publication_id)
		WHERE /*_PREFIX_*/publications.identifier = "%s"
		AND /*_PREFIX_*/issues.identifier = "%s/%s"';
	$sql = sprintf(
		$sql,
		wrap_db_escape($publication_identifier),
		wrap_db_escape($publication_identifier),
		wrap_db_escape(implode('/', $params))
	);
	$issue = wrap_db_fetch($sql);
	if (!$issue) return false;
	if (!wrap_access('news_preview') && $issue['published'] !== 'yes') {
		wrap_quit(410, wrap_text('This post is no longer published.'));
	}

	$media = [];
	brick_request_links($issue['intro'], $media, 'sequence');
	brick_request_links($issue['article'], $media, 'sequence');

	$page['title'] = $issue['issue'];
	$page['breadcrumbs'] = mod_news_issue_breadcrumbs($issue);
	$page['opengraph'] = [
		'og:type' => 'article',
		'og:title' => wrap_html_escape(strip_tags($issue['issue'])),
	];
	if (!empty($issue['issue_short'])) {
		$page['opengraph']['og:description'] = wrap_html_escape(strip_tags($issue['issue_short']));
	} elseif (!empty($issue['intro'])) {
		$page['opengraph']['og:description'] = wrap_html_escape(trim(strip_tags(markdown($issue['intro']))));
	}
	if (!empty($issue['date_published'])) {
		$page['opengraph']['article:published_time'] = $issue['date_published'];
	}
	if (!empty($issue['last_update'])) {
		$page['opengraph']['article:modified_time'] = $issue['last_update'];
	}
	if ($issue['published'] !== 'yes') {
		$page['extra']['class'] = 'unpublished';
	}

	if ($issue_hooks = wrap_functions(wrap_include('news'), 'issue_hook'))
		return end($issue_hooks)['function']($issue, $page);

	$page['dont_show_h1'] = true;
	$page['text'] = wrap_template('issue', $issue);
	return $page;
}

/**
 * @param array $issue
 * @return array
 */
function mod_news_issue_breadcrumbs($issue) {
	$parts = explode('/', wrap_brick('parameter'));
	array_pop($parts);

	$path_prefix = wrap_path_placeholder(wrap_page_field('identifier'), '*');
	if (substr_count($path_prefix, '*') === 1) {
		$path_prefix = substr($path_prefix, 0, strpos($path_prefix, '*'));
	} else {
		$path_prefix = '';
	}

	$breadcrumbs = [];
	$breadcrumb_url = wrap_setting('base').$path_prefix;
	foreach ($parts as $segment) {
		if (!wrap_setting('news_issue_breadcrumb_year') && preg_match('/^\d{4}$/', $segment)) {
 		   continue;
		}
		$breadcrumb_url .= '/'.$segment;
		$breadcrumbs[] = ['title' => $segment, 'url_path' => $breadcrumb_url.'/'];
	}
	if (wrap_setting('news_issue_breadcrumb_period'))
		$breadcrumbs[]['title'] = wrap_text('Issue %s', ['values' => [wrap_date($issue['period_begin'])]]);
	else
		$breadcrumbs[]['title'] = $issue['issue'];
	return $breadcrumbs;
}
