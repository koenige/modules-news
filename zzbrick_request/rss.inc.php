<?php

/**
 * news module
 * Output RSS feed
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2009-2013, 2015-2021 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


/**
 * Gibt RSS-Feed aus
 *
 * @todo LastBuildDate gibt immer aktuelles Datum aus, daher kein Caching
 * möglich
 */
function mod_news_rss($parameter) {
	global $zz_setting;
	global $zz_conf;
	// Parameter: keine erlaubt
	if (!empty($parameter)) return false;

	$settings = wrap_get_setting('rss*');
	if (empty($settings['rss_copyright'])) $settings['rss_copyright'] = wrap_get_setting('project');
	if (empty($settings['rss_editor'])) $settings['rss_editor'] = wrap_get_setting('project');
	if (empty($settings['rss_editor_mail'])) $settings['rss_editor_mail'] = $zz_conf['error_mail_from'];
	if (empty($settings['rss_webmaster'])) $settings['rss_webmaster'] = wrap_get_setting('project');
	if (empty($settings['rss_webmaster_mail'])) $settings['rss_webmaster_mail'] = $zz_conf['error_mail_from'];
	
	require_once $zz_setting['lib'].'/feedcreator/feedcreator.class.php';
	wrap_db_query('SET NAMES utf8');		// XML in utf8

	//define channel
	$rss = new UniversalFeedCreator();
	$rss->useCached();
	$rss->title = wrap_get_setting('project');
	$rss->description = wrap_text($settings['rss_description']);
	$rss->link = $zz_setting['host_base'].'/';
	$rss->encoding = 'utf-8';
	$rss->language = $zz_setting['lang'];
	$rss->copyright = 'Copyright '.date('Y').' '.$settings['rss_copyright'];
	$rss->editor = sprintf('%s (%s)', $settings['rss_editor_mail'], $settings['rss_editor']);
	$rss->webmaster = sprintf('%s (%s)', $settings['rss_webmaster_mail'], $settings['rss_webmaster']);
	$rss->ttl = 60;
	$rss->syndicationURL = $zz_setting['host_base'].$zz_setting['request_uri'];
	$rss->descriptionHtmlSyndicated = true;

	$image = new FeedImage();
	$image->title = wrap_get_setting('project');
	$image->width = 32;
	$image->height = 32;
	$image->url = $zz_setting['host_base'].'/favicon.png';
	$image->link = $zz_setting['host_base'].'/';
	$rss->image = $image;

	$settings['rss'] = true;
	require_once $zz_setting['custom'].'/zzbrick_request_get/articles.inc.php';
	$data = cms_get_articles($parameter, $settings);

	// RSS schreiben
	foreach ($data as $line) {
		//channel items/entries
		$item = new FeedItem();
		// HTML Entities aus Titel entfernen, ist kein character data
		$item->title = html_entity_decode($line['title'], ENT_QUOTES, 'UTF-8');
		if (substr($line['link'], 0, 1) === '/')
			$line['link'] = $zz_setting['host_base'].$zz_setting['base'].$line['link'];
		if (substr($line['guid'], 0, 1) === '/')
			$line['guid'] = $zz_setting['host_base'].$zz_setting['base'].$line['guid'];
		$item->link = $line['link'];
		$item->guid = $line['guid'];
		$item->description = markdown($line['text']);
		$item->descriptionHtmlSyndicated = true;
		$item->source = $zz_setting['host_base'];
		if (!empty($line['pubDate']))
	    	$item->date = strtotime($line['pubDate']);
	    if (!empty($line['author'])) {
	    	$item->author = sprintf('%s (%s)', $settings['rss_editor_mail'], $line['author']);
	    }
		$rss->addItem($item);
	}

	// Valid parameters are RSS0.91, RSS1.0, RSS2.0, PIE0.1 (deprecated),
	// MBOX, OPML, ATOM, ATOM1.0, ATOM0.3, HTML, JS
	ob_start();
	$rss->outputFeed('RSS2.0');
	$page['text'] = ob_get_clean();
	$page['content_type'] = 'xml';
	$zz_conf['character_set'] = 'utf-8';
	return $page;
}

/**
 * format text for RSS output with brick_format()
 * changes some HTML that is good for websites but not so good for RSS
 *
 * @param string $text
 * @param int $id
 * @global array $zz_setting;
 * @return string $text
 */
function mf_news_brick2rss_format($text, $id = false) {
	global $zz_setting;

	if ($id) {
		// format text with brick_format
		$formatted = brick_format($text, $id, $zz_setting);
		// check if we have some text
		if (!$formatted['text']) return false;
		$text = $formatted['text'];
		unset($formatted);
	}
	// set relative links to absolute links, we are not on a webpage anymore
	$text = preg_replace_callback('/srcset=["\'](.+?)["\']/', 'mf_news_brick2rss_links', $text);
	$text = preg_replace_callback('/src=["\'](.+?)["\']/', 'mf_news_brick2rss_links', $text);
	$text = preg_replace_callback('/href=["\'](.+?)["\']/', 'mf_news_brick2rss_links', $text);
	// remove headings, looks better without them
	$headings = ['h1', 'h2', 'h3', 'h4'];
	foreach ($headings as $h) {
		$text = str_replace('<'.$h, '<p><strong', $text);
		$text = str_replace('</'.$h.'>', '</strong></p>', $text);
	}
	// format with fulltext function
	if (!empty($zz_setting['brick_fulltextformat']))
		$text = $zz_setting['brick_fulltextformat']($text);
	return $text;
}

/**
 * replace relative links with links with host name
 *
 * @param array
 * @return string
 */
function mf_news_brick2rss_links($text) {
	global $zz_setting;

	$links = explode(', ', $text[1]);
	foreach ($links as $index => $link) {
		if (substr($link, 0, 1) !== '/') continue;
		$links[$index] = $zz_setting['host_base'].$link;
	}
	$links = implode(', ', $links);
	$string = str_replace($text[1], $links, $text[0]);
	return $string;
}
