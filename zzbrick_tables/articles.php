<?php 

/**
 * Zugzwang Project
 * Table for news articles
 *
 * http://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2010-2011, 2014-2015, 2017-2019 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


$zz['title'] = 'Articles';
$zz['table'] = '/*_PREFIX_*/articles';

$zz['fields'][1]['title'] = 'ID';
$zz['fields'][1]['field_name'] = 'article_id';
$zz['fields'][1]['type'] = 'id';

$zz['fields'][18]['title'] = 'Image';
$zz['fields'][18]['field_name'] = '';
$zz['fields'][18]['type'] = 'image';
$zz['fields'][18]['path'] = [
	'root' => $zz_setting['media_folder'], 
	'webroot' => '/files',
	'string1' => '/',
	'field1' => 'filename',
	'string2' => '.',
	'string3' => $zz_setting['media_preview_size'],
	'string4' => '.',
	'extension' => 'thumb_extension'
];
$zz['fields'][18]['hide_in_form'] = true;

$zz['fields'][2]['field_name'] = 'date';
$zz['fields'][2]['type'] = 'date';
$zz['fields'][2]['default'] = date('d.m.Y', time());
$zz['fields'][2]['append_next'] = true;
$zz['fields'][2]['dont_copy'] = true;

$zz['fields'][32]['field_name'] = 'time';
$zz['fields'][32]['type'] = 'time';
$zz['fields'][32]['default'] = date('H:i', time());
$zz['fields'][32]['prefix'] = ' at ';
$zz['fields'][32]['unit'] = 'h';
$zz['fields'][32]['dont_copy'] = true;

$zz['fields'][3]['title'] = 'Title';
$zz['fields'][3]['field_name'] = 'title';
$zz['fields'][3]['type'] = 'text';
$zz['fields'][3]['link'] = [
	'string1' => '/',
	'field1' => 'identifier',
	'string2' => '/'
];

$zz['fields'][4]['title'] = 'News';
$zz['fields'][4]['field_name'] = 'abstract';
$zz['fields'][4]['type'] = 'memo';
$zz['fields'][4]['rows'] = 3;
$zz['fields'][4]['format'] = 'markdown';
$zz['fields'][4]['explanation'] = 'Short news, 140 characters max.';
$zz['fields'][4]['hide_in_list'] = true;

$zz['fields'][12] = zzform_include_table('articles-media');
$zz['fields'][12]['title'] = 'Media';
$zz['fields'][12]['type'] = 'subtable';
$zz['fields'][12]['min_records'] = 1;
$zz['fields'][12]['max_records'] = 40;
$zz['fields'][12]['hide_in_list'] = true;
$zz['fields'][12]['form_display'] = 'lines';
$zz['fields'][12]['sql'] .= ' ORDER BY /*_PREFIX_*/articles.date DESC, sequence';
$zz['fields'][12]['fields'][2]['type'] = 'foreign_key';

$zz['fields'][11]['title'] = 'Published?';
$zz['fields'][11]['field_name'] = 'published';
$zz['fields'][11]['type'] = 'select';
$zz['fields'][11]['enum'] = ['yes', 'no'];
$zz['fields'][11]['default'] = 'yes';

$zz['fields'][13] = zzform_include_table('articles-categories');
$zz['fields'][13]['title'] = 'Categories';
$zz['fields'][13]['type'] = 'subtable';
$zz['fields'][13]['min_records'] = 1;
$zz['fields'][13]['max_records'] = 20;
$zz['fields'][13]['hide_in_list'] = true;
$zz['fields'][13]['form_display'] = 'lines';
$zz['fields'][13]['sql'] .= ' ORDER BY /*_PREFIX_*/articles.date DESC, sequence';
$zz['fields'][13]['fields'][2]['type'] = 'foreign_key';
$zz['fields'][13]['separator'] = true;

/*
$zz['fields'][5]['title'] = 'Ext. Link';
$zz['fields'][5]['field_name'] = 'direct_link';
$zz['fields'][5]['type'] = 'url';
$zz['fields'][5]['hide_in_list'] = true;
$zz['fields'][5]['explanation'] = 'Link to another website to read on.';
*/

$zz['fields'][6]['separator'] = 'text <h4 style="padding: 0 0 0 .5em;">Optional Separate Article</h4>';

$zz['fields'][6]['title'] = 'Article';
$zz['fields'][6]['field_name'] = 'article';
$zz['fields'][6]['type'] = 'memo';
$zz['fields'][6]['hide_in_list'] = true;
$zz['fields'][6]['rows'] = 20;
$zz['fields'][6]['format'] = 'markdown';
$zz['fields'][6]['separator'] = true;

$zz['fields'][23] = false;

$zz['fields'][22] = zzform_include_table('articles-events');
$zz['fields'][22]['title'] = 'Events';
$zz['fields'][22]['type'] = 'subtable';
$zz['fields'][22]['min_records'] = 0;
$zz['fields'][22]['hide_in_list'] = true;
$zz['fields'][22]['form_display'] = 'lines';
$zz['fields'][22]['sql'] .= ' ORDER BY /*_PREFIX_*/articles.date DESC, sequence';
$zz['fields'][22]['fields'][2]['type'] = 'foreign_key';

$zz['fields'][9]['field_name'] = 'identifier';
$zz['fields'][9]['type'] = 'identifier';
$zz['fields'][9]['fields'] = ['date{0,4}', 'title'];
$zz['fields'][9]['conf_identifier']['exists'] = '-';
$zz['fields'][9]['conf_identifier']['concat'] = '/';
$zz['fields'][9]['hide_in_list'] = true;

$zz['fields'][20]['title'] = 'Updated';
$zz['fields'][20]['field_name'] = 'last_update';
$zz['fields'][20]['type'] = 'timestamp';
$zz['fields'][20]['hide_in_list'] = true;

$zz['fields'][19]['field_name'] = 'article_type';
$zz['fields'][19]['type'] = 'display';
$zz['fields'][19]['exclude_from_search'] = true;
$zz['fields'][19]['class'] = 'hidden';


$zz['sql'] = 'SELECT DISTINCT /*_PREFIX_*/articles.*
	, IF(/*_PREFIX_*/articles.published = "yes", "Published Articles", "Unpublished Articles") AS article_type
	, DATE_FORMAT(/*_PREFIX_*/articles.date, "%Y") AS year
	, filename
	, t_mime.extension AS thumb_extension
	FROM /*_PREFIX_*/articles
	LEFT JOIN /*_PREFIX_*/articles_categories USING (article_id)
	LEFT JOIN /*_PREFIX_*/articles_media
		ON /*_PREFIX_*/articles_media.article_id = /*_PREFIX_*/articles.article_id
		AND /*_PREFIX_*/articles_media.sequence = 1
	LEFT JOIN /*_PREFIX_*/media
		ON /*_PREFIX_*/articles_media.medium_id = /*_PREFIX_*/media.medium_id
		AND /*_PREFIX_*/media.published = "yes"
	LEFT JOIN /*_PREFIX_*/filetypes AS t_mime
		ON /*_PREFIX_*/media.thumb_filetype_id = t_mime.filetype_id
';
$zz['sqlorder'] = ' ORDER BY date DESC, time DESC, identifier DESC';

$zz['filter'][1]['sql'] = 'SELECT DISTINCT YEAR(date) AS year_idf
		, YEAR(date) AS year
	FROM articles
	ORDER BY YEAR(date) DESC';
$zz['filter'][1]['title'] = wrap_text('Year');
$zz['filter'][1]['identifier'] = 'year';
$zz['filter'][1]['type'] = 'list';
$zz['filter'][1]['where'] = 'YEAR(/*_PREFIX_*/articles.date)';

$zz['filter'][2]['sql'] = sprintf('SELECT DISTINCT category_id
		, category
	FROM articles_categories
	LEFT JOIN categories USING (category_id)
	UNION SELECT "NULL" AS category_id, "%s" AS category
	ORDER BY category', wrap_text('– none –'));
$zz['filter'][2]['title'] = wrap_text('Category');
$zz['filter'][2]['identifier'] = 'category';
$zz['filter'][2]['type'] = 'list';
$zz['filter'][2]['where'] = '/*_PREFIX_*/articles_categories.category_id';

$zz['filter'][4]['title'] = wrap_text('Published');
$zz['filter'][4]['identifier'] = 'published';
$zz['filter'][4]['type'] = 'list';
$zz['filter'][4]['where'] = 'articles.published';
$zz['filter'][4]['selection']['yes'] = wrap_text('yes');
$zz['filter'][4]['selection']['no'] = wrap_text('no');

$zz['set_redirect'][] = [
	'old' => $zz_setting['news_url'].'/%s/',
	'new' => $zz_setting['news_url'].'/%s/',
	'field_name' => 'identifier'
];

$zz_conf['copy'] = true;
