<?php 

/**
 * news module
 * Table for events for news articles
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2014, 2018-2019, 2021-2022, 2024 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


$zz['title'] = 'Articles/Events';
$zz['table'] = 'articles_events';

$zz['fields'][1]['title'] = 'ID';
$zz['fields'][1]['field_name'] = 'article_event_id';
$zz['fields'][1]['type'] = 'id';

$zz['fields'][2]['field_name'] = 'article_id';
$zz['fields'][2]['type'] = 'select';
$zz['fields'][2]['sql'] = 'SELECT article_id, title
	FROM articles
	ORDER BY title';
$zz['fields'][2]['display_field'] = 'title';

$zz['fields'][4]['title_tab'] = 'Seq.';
$zz['fields'][4]['field_name'] = 'sequence';
$zz['fields'][4]['type'] = 'number';
$zz['fields'][4]['auto_value'] = 'increment';
$zz['fields'][4]['def_val_ignore'] = true;

$zz['fields'][3]['field_name'] = 'event_id';
$zz['fields'][3]['type'] = 'select';
$zz['fields'][3]['sql'] = sprintf('SELECT event_id
		, CONCAT(/*_PREFIX_*/events.event, " (", DATE_FORMAT(/*_PREFIX_*/events.date_begin, "%s")
		, ")") AS event
		, CONCAT("[", event_id, "]") AS _id
		, identifier
	FROM /*_PREFIX_*/events
	WHERE /*_PREFIX_*/events.event_category_id = /*_ID categories event/event _*/
	ORDER BY date_begin DESC', wrap_placeholder('mysql_date_format'));
$zz['fields'][3]['sql_ignores'][] = 'identifier';
$zz['fields'][3]['display_field'] = 'event';
$zz['fields'][3]['sql_character_set'][1] = 'utf8';
$zz['fields'][3]['sql_character_set'][2] = 'utf8';
$zz['fields'][3]['sql_character_set'][3] = 'latin1';
$zz['fields'][3]['search'] = sprintf('CONCAT(/*_PREFIX_*/events.event, " (", 
	DATE_FORMAT(/*_PREFIX_*/events.date_begin, "%s"), ")")', wrap_placeholder('mysql_date_format'));
$zz['fields'][3]['character_set'] = 'utf8';

$zz['sql'] = sprintf('SELECT articles_events.*
		, articles.title
		, CONCAT(/*_PREFIX_*/events.event, " (", 
	DATE_FORMAT(/*_PREFIX_*/events.date_begin, "%s"), ")") AS event
	FROM articles_events
	LEFT JOIN articles USING (article_id)
	LEFT JOIN events USING (event_id)
', wrap_placeholder('mysql_date_format'));
$zz['sqlorder'] = ' ORDER BY title, sequence, date_begin';
