<?php

/**
 * news module
 * formatting functions
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2026 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


/**
 * do not interpret hashtags as headings
 *
 * @param string $text
 * @return string
 */
function mf_news_markdown_article($text) {
	if (!$text) return '';
	// #hashtag at line start → not a heading
	$text = preg_replace('/(^|\n)#(\p{L}[\p{L}\p{N}_]*)/u', '$1\\#$2', $text);
	$html = markdown($text);
	// Article body must not contain h1 (title is already h1)
	$html = preg_replace('~<h1(\s[^>]*)?>(.*?)</h1>~si', '<p$1>$2</p>', $html);
	return $html;
}
