<?php 

/**
 * news module
 * Table for events for news articles
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2014, 2018-2019 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


$zz_sub['title'] = 'Articles/Events';
$zz_sub['table'] = 'articles_events';

$zz_sub['fields'][1]['title'] = 'ID';
$zz_sub['fields'][1]['field_name'] = 'article_event_id';
$zz_sub['fields'][1]['type'] = 'id';

$zz_sub['fields'][2]['field_name'] = 'article_id';
$zz_sub['fields'][2]['type'] = 'select';
$zz_sub['fields'][2]['sql'] = 'SELECT article_id, title
	FROM articles
	ORDER BY title';
$zz_sub['fields'][2]['display_field'] = 'title';

$zz_sub['fields'][4]['title_tab'] = 'Seq.';
$zz_sub['fields'][4]['field_name'] = 'sequence';
$zz_sub['fields'][4]['type'] = 'number';
$zz_sub['fields'][4]['auto_value'] = 'increment';
$zz_sub['fields'][4]['def_val_ignore'] = true;

$zz_sub['fields'][3]['field_name'] = 'event_id';
$zz_sub['fields'][3]['type'] = 'select';
$zz_sub['fields'][3]['sql'] = sprintf('SELECT event_id
		, CONCAT(/*_PREFIX_*/events.event, " (", DATE_FORMAT(/*_PREFIX_*/events.date_begin, "%%d.%%m.%%Y")
		, ")") AS event
		, CONCAT("[", event_id, "]") AS _id
	FROM /*_PREFIX_*/events
	WHERE /*_PREFIX_*/events.event_category_id = %d
	ORDER BY date_begin DESC', wrap_category_id('event/event'));
$zz_sub['fields'][3]['display_field'] = 'event';
$zz_sub['fields'][3]['sql_character_set'][1] = 'utf8';
$zz_sub['fields'][3]['sql_character_set'][2] = 'utf8';
$zz_sub['fields'][3]['sql_character_set'][3] = 'latin1';
$zz_sub['fields'][3]['search'] = 'CONCAT(/*_PREFIX_*/events.event, " (", 
	DATE_FORMAT(/*_PREFIX_*/events.date_begin, "%d.%m.%Y"), ")")';
$zz_sub['fields'][3]['character_set'] = 'utf8';

$zz_sub['sql'] = 'SELECT articles_events.*
		, articles.title
		, CONCAT(/*_PREFIX_*/events.event, " (", 
	DATE_FORMAT(/*_PREFIX_*/events.date_begin, "%d.%m.%Y"), ")") AS event
	FROM articles_events
	LEFT JOIN articles USING (article_id)
	LEFT JOIN events USING (event_id)
'; 
$zz_sub['sqlorder'] = ' ORDER BY title, sequence, date_begin';
