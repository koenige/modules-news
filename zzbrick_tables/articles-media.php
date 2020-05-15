<?php 

/**
 * Zugzwang Project
 * Table for media for news articles
 *
 * http://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2010, 2013-2015, 2018-2020 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


$zz_sub['title'] = 'Media in Articles';
$zz_sub['table'] = '/*_PREFIX_*/articles_media';

$zz_sub['fields'][1]['title'] = 'ID';
$zz_sub['fields'][1]['field_name'] = 'article_medium_id';
$zz_sub['fields'][1]['type'] = 'id';

$zz_sub['fields'][5]['title'] = 'Preview';
$zz_sub['fields'][5]['field_name'] = 'image';
$zz_sub['fields'][5]['type'] = 'image';
$zz_sub['fields'][5]['class'] = 'preview';
$zz_sub['fields'][5]['path'] = [
	'root' => $zz_setting['media_folder'], 
	'webroot' => $zz_setting['files_path'],
	'string1' => '/',
	'field1' => 'filename',
	'string2' => '.',
	'string3' => $zz_setting['media_preview_size'],
	'string4' => '.',
	'extension' => 'thumb_extension'
];
$zz_sub['fields'][5]['class'] = 'block480a';

$zz_sub['fields'][4]['title'] = 'No.';
$zz_sub['fields'][4]['field_name'] = 'sequence';
$zz_sub['fields'][4]['type'] = 'sequence';
$zz_sub['fields'][4]['auto_value'] = 'increment';
$zz_sub['fields'][4]['def_val_ignore'] = true;
$zz_sub['fields'][4]['class'] = 'block480a';

$zz_sub['fields'][2]['field_name'] = 'article_id';
$zz_sub['fields'][2]['type'] = 'select';
$zz_sub['fields'][2]['sql'] = 'SELECT article_id, date, title
	FROM /*_PREFIX_*/articles
	ORDER BY date, time, title';
$zz_sub['fields'][2]['display_field'] = 'article';
$zz_sub['fields'][2]['search'] = 'CONCAT(/*_PREFIX_*/articles.date, ": ", /*_PREFIX_*/articles.title)';
$zz_sub['fields'][2]['class'] = 'block480a';

$zz_sub['fields'][3]['title'] = 'Medium';
$zz_sub['fields'][3]['field_name'] = 'medium_id';
$zz_sub['fields'][3]['id_field_name'] = '/*_PREFIX_*/media.medium_id';
$zz_sub['fields'][3]['type'] = 'select';
$zz_sub['fields'][3]['sql'] = sprintf('SELECT /*_PREFIX_*/media.medium_id, folders.title AS folder
		, CONCAT("[", /*_PREFIX_*/media.medium_id, "] ", /*_PREFIX_*/media.title) AS image
	FROM /*_PREFIX_*/media 
	LEFT JOIN /*_PREFIX_*/media folders
		ON /*_PREFIX_*/media.main_medium_id = folders.medium_id
	WHERE /*_PREFIX_*/media.filetype_id != %d
	ORDER BY folders.title, /*_PREFIX_*/media.title', wrap_filetype_id('folder'));
$zz_sub['fields'][3]['sql_character_set'][1] = 'utf8';
$zz_sub['fields'][3]['sql_character_set'][2] = 'utf8';
$zz_sub['fields'][3]['display_field'] = 'image';
$zz_sub['fields'][3]['group'] = 'folder';
$zz_sub['fields'][3]['exclude_from_search'] = true;
$zz_sub['fields'][3]['character_set'] = 'utf8';
$zz_sub['fields'][3]['class'] = 'block480';

$zz_sub['fields'][20]['title'] = 'Updated';
$zz_sub['fields'][20]['field_name'] = 'last_update';
$zz_sub['fields'][20]['type'] = 'timestamp';
$zz_sub['fields'][20]['hide_in_list'] = true;

$zz_sub['sql'] = 'SELECT /*_PREFIX_*/articles_media.*
	, CONCAT(/*_PREFIX_*/articles.date, ": ", /*_PREFIX_*/articles.title) AS article
	, CONCAT("[", /*_PREFIX_*/media.medium_id, "] ", /*_PREFIX_*/media.title) AS image
	, /*_PREFIX_*/media.filename
	, t_mime.extension AS thumb_extension
	FROM /*_PREFIX_*/articles_media
	LEFT JOIN /*_PREFIX_*/articles USING (article_id)
	LEFT JOIN /*_PREFIX_*/media USING (medium_id)
	LEFT JOIN /*_PREFIX_*/filetypes AS o_mime USING (filetype_id)
	LEFT JOIN /*_PREFIX_*/filetypes AS t_mime 
		ON /*_PREFIX_*/media.thumb_filetype_id = t_mime.filetype_id
';
$zz_sub['sqlorder'] = ' ORDER BY /*_PREFIX_*/articles.date DESC, sequence';
