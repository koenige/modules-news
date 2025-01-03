<?php 

/**
 * news module
 * Table for news articles
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2010-2011, 2014-2015, 2017-2024 Gustaf Mossakowski
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
	'root' => wrap_setting('media_folder'), 
	'webroot' => wrap_setting('files_path'),
	'string1' => '/',
	'field1' => 'filename',
	'string2' => '.',
	'string3' => wrap_setting('media_preview_size'),
	'string4' => '.',
	'extension' => 'thumb_extension',
	'webstring1' => '?v=',
	'webfield1' => 'version'
];
$zz['fields'][18]['path']['extension_missing'] = [
	'string3' => wrap_setting('media_original_filename_extension'),
	'extension' => 'extension'
];
$zz['fields'][18]['hide_in_form'] = true;
$zz['fields'][18]['class'] = 'hidden480';
$zz['fields'][18]['hide_in_list_if_empty'] = true;

$zz['fields'][2]['field_name'] = 'date';
$zz['fields'][2]['type'] = 'date';
$zz['fields'][2]['default'] = date('d.m.Y', time());
$zz['fields'][2]['append_next'] = true;
$zz['fields'][2]['dont_copy'] = true;
$zz['fields'][2]['class'] = 'block640';

$zz['fields'][32]['field_name'] = 'time';
$zz['fields'][32]['type'] = 'time';
$zz['fields'][32]['default'] = date('H:i', time());
$zz['fields'][32]['prefix'] = ' at ';
$zz['fields'][32]['unit'] = 'h';
$zz['fields'][32]['dont_copy'] = true;
$zz['fields'][32]['class'] = 'block640';
$zz['fields'][32]['hide_in_list_if_empty'] = true;

$zz['fields'][33] = []; // date_to

$zz['fields'][3]['title'] = 'Title';
$zz['fields'][3]['field_name'] = 'title';
$zz['fields'][3]['type'] = 'text';
if (is_array(wrap_setting('news_article_path'))) {
	$zz['fields'][3]['link'] = [
		'area' => 'news_article[%s]',
		'area_fields' => ['publication_path'],
		'fields' => ['identifier']
	];
} else {
	$zz['fields'][3]['link'] = [
		'area' => 'news_article',
		'fields' => ['identifier']
	];
}
$zz['fields'][3]['typo_cleanup'] = true;
$zz['fields'][3]['typo_remove_double_spaces'] = true;
$zz['fields'][3]['replace_substrings'] = wrap_setting('replace_substrings');
$zz['fields'][3]['if'][3]['list_prefix'] = '<del>';
$zz['fields'][3]['if'][3]['list_suffix'] = '</del>';

if (wrap_setting('news_subtitle')) {
	$zz['fields'][17]['title'] = 'Subtitle';
	$zz['fields'][17]['field_name'] = 'subtitle';
	$zz['fields'][17]['hide_in_list'] = true;
	$zz['fields'][17]['typo_cleanup'] = true;
	$zz['fields'][17]['replace_substrings'] = wrap_setting('replace_substrings');
	$zz['fields'][17]['if'][6] = [];
}

$zz['fields'][16] = []; // short title

$zz['fields'][4]['title'] = 'Lead';
$zz['fields'][4]['if'][1]['title'] = 'Post';
$zz['fields'][4]['field_name'] = 'abstract';
$zz['fields'][4]['type'] = 'memo';
$zz['fields'][4]['rows'] = 3;
$zz['fields'][4]['format'] = 'markdown';
$zz['fields'][4]['explanation'] = 'Short news, not too long';
$zz['fields'][4]['hide_in_list'] = true;
$zz['fields'][4]['typo_cleanup'] = true;
$zz['fields'][4]['replace_substrings'] = wrap_setting('replace_substrings');

if (in_array('newsletters', wrap_setting('modules'))) {
	$zz['fields'][5]['title'] = 'Newsletter Lead';
	$zz['fields'][5]['field_name'] = 'newsletter_lead';
	$zz['fields'][5]['type'] = 'memo';
	$zz['fields'][5]['rows'] = 3;
	$zz['fields'][5]['format'] = 'markdown';
	$zz['fields'][5]['hide_in_list'] = true;
	$zz['fields'][5]['typo_cleanup'] = true;
	$zz['fields'][5]['replace_substrings'] = wrap_setting('replace_substrings');
	$zz['fields'][5]['if'][2] = [];
}

$zz['fields'][12] = zzform_include('articles-media');
$zz['fields'][12]['title'] = 'Media';
$zz['fields'][12]['type'] = 'subtable';
$zz['fields'][12]['min_records'] = 1;
$zz['fields'][12]['max_records'] = 40;
$zz['fields'][12]['hide_in_list'] = true;
$zz['fields'][12]['form_display'] = 'lines';
$zz['fields'][12]['sql'] .= ' ORDER BY /*_PREFIX_*/articles.date DESC, sequence';
$zz['fields'][12]['fields'][2]['type'] = 'foreign_key';
$zz['fields'][12]['fields'][4]['type'] = 'sequence';
$zz['fields'][12]['if'][7] = [];

$zz['fields'][11]['title'] = 'Published?';
$zz['fields'][11]['field_name'] = 'published';
$zz['fields'][11]['type'] = 'select';
$zz['fields'][11]['enum'] = ['yes', 'no'];
$zz['fields'][11]['default'] = 'yes';
$zz['fields'][11]['class'] = 'hidden640';
$zz['fields'][11]['hide_in_list'] = true;

if (wrap_category_id('news', 'check')) {
	$zz['fields'][13] = zzform_include('articles-categories');
	$zz['fields'][13]['title'] = 'Categories';
	$zz['fields'][13]['type'] = 'subtable';
	$zz['fields'][13]['min_records'] = 1;
	$zz['fields'][13]['max_records'] = 20;
	$zz['fields'][13]['hide_in_list'] = true;
	$zz['fields'][13]['form_display'] = 'lines';
	if (wrap_category_id('publications', 'check'))
		$zz['fields'][13]['sql'] .= ' WHERE /*_PREFIX_*/articles_categories.type_category_id = /*_ID categories news _*/';
	$zz['fields'][13]['sql'] .= ' ORDER BY /*_PREFIX_*/articles.date DESC, sequence';
	$zz['fields'][13]['fields'][2]['type'] = 'foreign_key';
	$zz['fields'][13]['fields'][4]['type'] = 'sequence';
	$zz['fields'][13]['separator'] = true;
	$zz['fields'][13]['if'][4] = [];
	if (!empty($brick['local_settings']['news_category_required']))
		$zz['fields'][13]['min_records_required'] = 1;
}

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
$zz['fields'][6]['typo_cleanup'] = true;
$zz['fields'][6]['replace_substrings'] = wrap_setting('replace_substrings');
$zz['fields'][6]['if'][1] = [];

$zz['fields'][23] = [];

// articles_contacts
$zz['fields'][40] = [];
$zz['fields'][41] = [];
$zz['fields'][42] = [];
$zz['fields'][43] = [];
$zz['fields'][44] = [];
$zz['fields'][45] = [];
$zz['fields'][46] = [];
$zz['fields'][47] = [];
$zz['fields'][48] = [];
$zz['fields'][49] = [];

if (in_array('events', wrap_setting('modules'))) {
	$zz['fields'][22] = zzform_include('articles-events');
	$zz['fields'][22]['title'] = 'Events';
	$zz['fields'][22]['type'] = 'subtable';
	$zz['fields'][22]['min_records'] = 1;
	$zz['fields'][22]['max_records'] = 40;
	$zz['fields'][22]['hide_in_list'] = true;
	$zz['fields'][22]['form_display'] = 'lines';
	$zz['fields'][22]['sql'] .= ' ORDER BY /*_PREFIX_*/articles.date DESC, sequence';
	$zz['fields'][22]['fields'][2]['type'] = 'foreign_key';
	$zz['fields'][22]['fields'][4]['type'] = 'sequence';
}

if (wrap_category_id('publications', 'check')) {
	$zz['fields'][14] = zzform_include('articles-categories');
	$zz['fields'][14]['title'] = 'Publication';
	$zz['fields'][14]['type'] = 'subtable';
	$zz['fields'][14]['table_name'] = 'publications';
	$zz['fields'][14]['min_records'] = 1;
	$zz['fields'][14]['max_records'] = 1;
	$zz['fields'][14]['hide_in_list'] = true;
	$zz['fields'][14]['class'] = 'hidden';
	$zz['fields'][14]['form_display'] = 'lines';
	$zz['fields'][14]['fields'][2]['type'] = 'foreign_key';
	unset($zz['fields'][14]['fields'][3]['add_details']);
	$zz['fields'][14]['fields'][3]['type'] = 'write_once';
	$zz['fields'][14]['fields'][3]['type_detail'] = 'select';
	$zz['fields'][14]['fields'][3]['show_hierarchy_subtree'] = wrap_category_id('publications');
	$zz['fields'][14]['sql'] .= ' WHERE /*_PREFIX_*/articles_categories.type_category_id = /*_ID categories publications _*/
		ORDER BY /*_PREFIX_*/categories.category';
	$zz['fields'][14]['fields'][4]['type'] = 'hidden'; // sequence
	$zz['fields'][14]['fields'][4]['value'] = 1;
	$zz['fields'][14]['fields'][4]['hide_in_form'] = true;
	$zz['fields'][14]['fields'][5]['value'] = wrap_category_id('publications');
}

$zz['fields'][15] = []; // publication issue

$zz['fields'][9]['field_name'] = 'identifier';
$zz['fields'][9]['type'] = 'identifier';
$zz['fields'][9]['fields'] = ['date{0,4}', 'title', 'identifier'];
$zz['fields'][9]['if'][5]['fields'] = ['title', 'identifier'];
$zz['fields'][9]['identifier']['exists'] = '-';
$zz['fields'][9]['identifier']['concat'] = '/';
$zz['fields'][9]['hide_in_list'] = true;

$zz['fields'][99]['field_name'] = 'last_update';
$zz['fields'][99]['type'] = 'timestamp';
$zz['fields'][99]['hide_in_list'] = true;

$zz['fields'][19]['field_name'] = 'article_type';
$zz['fields'][19]['type'] = 'display';
$zz['fields'][19]['exclude_from_search'] = true;
$zz['fields'][19]['class'] = 'hidden';


$zz['sql'] = 'SELECT DISTINCT /*_PREFIX_*/articles.*
		, IF(/*_PREFIX_*/articles.published = "yes", "Published Articles", "Unpublished Articles") AS article_type
		, DATE_FORMAT(/*_PREFIX_*/articles.date, "%Y") AS year
		, /*_PREFIX_*/media.filename
		, /*_PREFIX_*/media.version
		, t_mime.extension AS thumb_extension
		, o_mime.extension
	FROM /*_PREFIX_*/articles
	LEFT JOIN /*_PREFIX_*/articles_media
		ON /*_PREFIX_*/articles_media.article_id = /*_PREFIX_*/articles.article_id
		AND IF((SELECT COUNT(*) FROM /*_PREFIX_*/articles_media am
			WHERE am.article_id = /*_PREFIX_*/articles.article_id
		    AND overview_medium = "yes") = 1, /*_PREFIX_*/articles_media.overview_medium = "yes", /*_PREFIX_*/articles_media.sequence = 1)
	LEFT JOIN /*_PREFIX_*/media
		ON /*_PREFIX_*/articles_media.medium_id = /*_PREFIX_*/media.medium_id
		AND /*_PREFIX_*/media.published = "yes"
	LEFT JOIN /*_PREFIX_*/filetypes o_mime USING (filetype_id)
	LEFT JOIN /*_PREFIX_*/filetypes AS t_mime
		ON /*_PREFIX_*/media.thumb_filetype_id = t_mime.filetype_id
';
if (wrap_category_id('news', 'check')) {
	$zz['sql'] .= ' LEFT JOIN /*_PREFIX_*/articles_categories articles_categories
			ON articles_categories.article_id = /*_PREFIX_*/articles.article_id
			AND articles_categories.type_category_id = /*_ID categories news _*/';
}
if (wrap_category_id('publications', 'check')) {
	$zz['sql'] .= ' LEFT JOIN /*_PREFIX_*/articles_categories publications
			ON /*_PREFIX_*/articles.article_id = publications.article_id
			AND /*_PREFIX_*/publications.type_category_id = /*_ID categories publications _*/
		LEFT JOIN /*_PREFIX_*/categories publication_categories
			ON publication_categories.category_id = publications.category_id';
	$zz['sql'] = wrap_edit_sql($zz['sql']
		, 'SELECT'
		, 'SUBSTRING_INDEX(publication_categories.path, "/", -1) AS publication_path'
	);
}
$zz['sqlorder'] = ' ORDER BY date DESC, time DESC, identifier DESC';

$zz['filter'][1]['sql'] = 'SELECT DISTINCT YEAR(date) AS year_idf
		, YEAR(date) AS year
	FROM articles
	ORDER BY YEAR(date) DESC';
$zz['filter'][1]['title'] = wrap_text('Year');
$zz['filter'][1]['identifier'] = 'year';
$zz['filter'][1]['type'] = 'list';
$zz['filter'][1]['where'] = 'YEAR(/*_PREFIX_*/articles.date)';

$zz['filter'][5] = [];

if (wrap_category_id('news', 'check')) {
	$zz['filter'][2]['sql'] = 'SELECT DISTINCT category_id
			, category
		FROM articles_categories
		LEFT JOIN categories USING (category_id)
		WHERE type_category_id = /*_ID categories news _*/
		ORDER BY category';
	$zz['filter'][2]['title'] = wrap_text('Category');
	$zz['filter'][2]['identifier'] = 'category';
	$zz['filter'][2]['type'] = 'list';
	$zz['filter'][2]['where'] = 'articles_categories.category_id';
}

$zz['filter'][4]['title'] = wrap_text('Published');
$zz['filter'][4]['identifier'] = 'published';
$zz['filter'][4]['type'] = 'list';
$zz['filter'][4]['where'] = 'articles.published';
$zz['filter'][4]['selection']['yes'] = wrap_text('yes');
$zz['filter'][4]['selection']['no'] = wrap_text('no');

$zz['set_redirect'][] = [
	'old' => $zz['fields'][3]['link'],
	'new' => $zz['fields'][3]['link'],
	'field_name' => 'identifier'
];

if (wrap_category_id('publications', 'check')) {
	$hide_category_ids = [];
	if (!empty($brick['local_settings']['news_hide_publication_categories'])) {
		foreach ($brick['local_settings']['news_hide_publication_categories'] as $path)
			$hide_category_ids[] = wrap_category_id($path);
		$zz['sql'] = wrap_edit_sql($zz['sql']
			, 'WHERE', sprintf('publications.category_id NOT IN (%s)', implode(',', $hide_category_ids)));
	}
	
	$sql = 'SELECT category_id as value, category as type, "publications.category_id" AS field_name
		FROM /*_PREFIX_*/categories
		WHERE main_category_id = /*_ID categories publications _*/
		AND category_id NOT in (%s)
		ORDER BY sequence, category';
	$sql = sprintf($sql
		, $hide_category_ids ? implode(',', $hide_category_ids) : 0
	);
	$zz['add'] = wrap_db_fetch($sql, 'category_id', 'numeric');

	$zz['filter'][5]['title'] = wrap_text('Publication');
	$zz['filter'][5]['identifier'] = 'publication';
	$zz['filter'][5]['type'] = 'list';
	$zz['filter'][5]['where'] = 'publications.category_id';
	$zz['filter'][5]['sql'] = sprintf('SELECT DISTINCT category_id
			, category
		FROM articles_categories
		LEFT JOIN categories USING (category_id)
		WHERE type_category_id = /*_ID categories publications_*/
		AND category_id NOT IN (%s)
		ORDER BY category'
		, $hide_category_ids ? implode(',', $hide_category_ids) : 0
	);

	$zz['conditions'][1]['scope'] = 'record';
	$zz['conditions'][1]['where'] = 'publication_categories.parameters LIKE "%&article=0%"';
	$zz['conditions'][1]['add']['sql'] = 'SELECT category_id
		FROM /*_PREFIX_*/categories publication_categories
		WHERE category_id = ';
	$zz['conditions'][1]['add']['key_field_name'] = 'publications.category_id';

	$zz['conditions'][2]['scope'] = 'record';
	$zz['conditions'][2]['where'] = 'publication_categories.parameters LIKE "%&lead=0%"';
	$zz['conditions'][2]['add']['sql'] = 'SELECT category_id
		FROM /*_PREFIX_*/categories publication_categories
		WHERE category_id = ';
	$zz['conditions'][2]['add']['key_field_name'] = 'publications.category_id';

	$zz['conditions'][4]['scope'] = 'record';
	$zz['conditions'][4]['where'] = 'publication_categories.parameters LIKE "%&categories=0%"';
	$zz['conditions'][4]['add']['sql'] = 'SELECT category_id
		FROM /*_PREFIX_*/categories publication_categories
		WHERE category_id = ';
	$zz['conditions'][4]['add']['key_field_name'] = 'publications.category_id';

	$zz['conditions'][5]['scope'] = 'record';
	$zz['conditions'][5]['where'] = 'publication_categories.parameters LIKE "%&identifier_without_year=1%"';
	$zz['conditions'][5]['add']['sql'] = 'SELECT category_id
		FROM /*_PREFIX_*/categories publication_categories
		WHERE category_id = ';
	$zz['conditions'][5]['add']['key_field_name'] = 'publications.category_id';

	$zz['conditions'][6]['scope'] = 'record';
	$zz['conditions'][6]['where'] = 'publication_categories.parameters LIKE "%&subtitle=0%"';
	$zz['conditions'][6]['add']['sql'] = 'SELECT category_id
		FROM /*_PREFIX_*/categories publication_categories
		WHERE category_id = ';
	$zz['conditions'][6]['add']['key_field_name'] = 'publications.category_id';

	$zz['conditions'][7]['scope'] = 'record';
	$zz['conditions'][7]['where'] = 'publication_categories.parameters LIKE "%&media=0%"';
	$zz['conditions'][7]['add']['sql'] = 'SELECT category_id
		FROM /*_PREFIX_*/categories publication_categories
		WHERE category_id = ';
	$zz['conditions'][7]['add']['key_field_name'] = 'publications.category_id';
}

$zz['conditions'][3]['scope'] = 'record';
$zz['conditions'][3]['where'] = '/*_PREFIX_*/articles.published = "no"';

$zz['record']['copy'] = true;
