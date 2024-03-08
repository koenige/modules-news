<?php

/**
 * news module
 * Output RSS feed
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2009-2013, 2015-2024 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


/**
 * Gibt RSS-Feed aus
 *
 * @todo LastBuildDate gibt immer aktuelles Datum aus, daher kein Caching
 * möglich
 */
function mod_news_rss($params) {
	// Parameter: keine erlaubt
	if (!empty($params)) return false;
	
	$settings['last'] = wrap_setting('rss_entries');
	$settings['rss'] = true;

	require_once wrap_setting('lib').'/feedcreator/feedcreator.class.php';
	wrap_db_charset('utf8');		// XML in utf8

	//define channel
	$rss = new UniversalFeedCreator();
	$rss->useCached();
	$rss->title = wrap_setting('project');
	$rss->description = wrap_text(wrap_setting('rss_description'));
	$rss->link = wrap_setting('host_base').'/';
	$rss->encoding = 'utf-8';
	$rss->language = wrap_setting('lang');
	$rss->copyright = 'Copyright '.date('Y').' '.wrap_setting('rss_copyright');
	$rss->editor = sprintf('%s (%s)', wrap_setting('rss_editor_mail'), wrap_setting('rss_editor'));
	$rss->webmaster = sprintf('%s (%s)', wrap_setting('rss_webmaster_mail'), wrap_setting('rss_webmaster'));
	$rss->ttl = 60;
	$rss->syndicationURL = wrap_setting('host_base').wrap_setting('request_uri');
	$rss->descriptionHtmlSyndicated = true;

	$image = new FeedImage();
	$image->title = wrap_setting('project');
	$image->width = 32;
	$image->height = 32;
	$image->url = wrap_setting('host_base').'/favicon.png';
	$image->link = wrap_setting('host_base').'/';
	$rss->image = $image;

	$data = brick_request_data('articles', [], $settings);
	if (in_array('events', wrap_setting('modules')) AND wrap_setting('rss_with_events')) {
		wrap_include_files('events/news', 'events');
		$events = mf_events_in_news('rss');
		$data = mf_events_in_news_sort($data, $events);
	}
	if (wrap_setting('rss_fulltext'))
		$data = mod_news_rss_fulltext($data);

	// RSS schreiben
	foreach ($data as $index => $line) {
		if (!is_int($index)) continue;
		//channel items/entries
		$item = new FeedItem();
		// HTML Entities aus Titel entfernen, ist kein character data
		$item->title = html_entity_decode($line['title'], ENT_QUOTES, 'UTF-8');
		if (substr($line['link'], 0, 1) === '/')
			$line['link'] = wrap_setting('host_base').wrap_setting('base').$line['link'];
		if (substr($line['guid'], 0, 1) === '/')
			$line['guid'] = wrap_setting('host_base').wrap_setting('base').$line['guid'];
		$item->link = $line['link'];
		$item->guid = $line['guid'];
		$item->description = markdown($line['text']);
		$item->descriptionHtmlSyndicated = true;
		$item->source = wrap_setting('host_base');
		if (!empty($line['pubDate']))
	    	$item->date = strtotime($line['pubDate']);
	    if (!empty($line['author'])) {
	    	if (is_array($line['author'])) {
	    		$authors = [];
	    		foreach ($line['author'] as $author) {
			    	$authors[] = $author['contact'];
	    		}
	    		$item->author = sprintf('%s (%s)', wrap_setting('rss_editor_mail'), implode(', ', $authors));
	    	} else {
		    	$item->author = sprintf('%s (%s)', wrap_setting('rss_editor_mail'), $line['author']);
		    }
	    }
		$rss->addItem($item);
	}

	// Valid parameters are RSS0.91, RSS1.0, RSS2.0, PIE0.1 (deprecated),
	// MBOX, OPML, ATOM, ATOM1.0, ATOM0.3, HTML, JS
	ob_start();
	$rss->outputFeed('RSS2.0');
	$page['text'] = ob_get_clean();
	$page['content_type'] = 'xml';
	wrap_setting('character_set', 'utf-8');
	return $page;
}

/**
 * format text for RSS output with brick_format()
 * changes some HTML that is good for websites but not so good for RSS
 *
 * @param string $text
 * @param int $id
 * @return string $text
 */
function mf_news_brick2rss_format($text, $id = false) {
	if ($id) {
		// format text with brick_format
		$formatted = brick_format($text, $id);
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
	if ($format = wrap_setting('brick_fulltextformat'))
		$text = $format($text);
	return $text;
}

/**
 * replace relative links with links with host name
 *
 * @param array
 * @return string
 */
function mf_news_brick2rss_links($text) {
	$links = explode(', ', $text[1]);
	foreach ($links as $index => $link) {
		if (substr($link, 0, 1) !== '/') continue;
		$links[$index] = wrap_setting('host_base').$link;
	}
	$links = implode(', ', $links);
	$string = str_replace($text[1], $links, $text[0]);
	return $string;
}

/**
 * output full text per event/article in RSS, formatted as on webpage
 *
 * @param array $articles
 * @return array
 */
function mod_news_rss_fulltext($articles) {
	foreach ($articles as $id => $article) {
		if (!is_int($id)) continue;
		if (isset($article['duration'])) {
			$description = brick_format('%%% request event '.str_replace('/', ' ', $article['identifier']));
		} else {
			$description = brick_format('%%% request article '.str_replace('/', ' ', $article['identifier']));
			preg_match('~</header>(.+)</article>~s', $description['text'], $matches);
			if ($matches)
				$description['text'] = $matches[1];
		}
		$articles[$id]['text'] = mf_news_brick2rss_format($description['text']);
	}
	return $articles;
}
