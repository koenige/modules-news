<?php 

/**
 * news module
 * Table for news articles
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2010-2011, 2014-2015, 2017-2024, 2026 Gustaf Mossakowski
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
$zz['fields'][3]['link'] = [
	'area' => 'news_article',
	'fields' => ['identifier']
];
$zz['fields'][3]['typo_cleanup'] = true;
$zz['fields'][3]['typo_remove_double_spaces'] = true;
$zz['fields'][3]['replace_substrings'] = wrap_setting('replace_substrings');
$zz['fields'][3]['if'][3]['list_prefix'] = '<del>';
$zz['fields'][3]['if'][3]['list_suffix'] = '</del>';
$zz['fields'][3]['if'][8]['type'] = 'identifier';
$zz['fields'][3]['if'][8]['fields'] = ['date{8,2}', 'date{5,2}', 'date{0,4}', 'title', 'identifier'];
$zz['fields'][3]['if'][8]['identifier']['exists'] = '-';
$zz['fields'][3]['if'][8]['identifier']['concat'] = '.';
$zz['fields'][3]['if'][8]['hide_in_form'] = true;

if (wrap_setting('news_subtitle')) {
	$zz['fields'][17]['title'] = 'Subtitle';
	$zz['fields'][17]['field_name'] = 'subtitle';
	$zz['fields'][17]['hide_in_list'] = true;
	$zz['fields'][17]['typo_cleanup'] = true;
	$zz['fields'][17]['replace_substrings'] = wrap_setting('replace_substrings');
	$zz['fields'][17]['if'][6] = [];
}

if (wrap_setting('news_title_short')) {
	$zz['fields'][16]['title'] = 'Short title';
	$zz['fields'][16]['field_name'] = 'title_short';
	$zz['fields'][16]['type'] = 'text';
	$zz['fields'][16]['size'] = 24;
	$zz['fields'][16]['explanation'] = 'Key terms only, no year. For breadcrumbs and URL segments.';
	$zz['fields'][16]['hide_in_list'] = true;
	$zz['fields'][16]['typo_cleanup'] = true;
	$zz['fields'][16]['typo_remove_double_spaces'] = true;
	$zz['fields'][16]['if'][8]['hide_in_form'] = true;
	$zz['fields'][16]['if'][9]['hide_in_form'] = true;
}

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

if (wrap_package('newsletters')) {
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

if (wrap_package('events')) {
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
	$zz['fields'][22]['separator'] = true;
}

$zz['fields'][25]['title'] = 'Publication';
$zz['fields'][25]['field_name'] = 'publication_id';
$zz['fields'][25]['type'] = 'select';
$zz['fields'][25]['sql'] = 'SELECT publication_id, publication
	FROM /*_PREFIX_*/publications
	ORDER BY /*_PREFIX_*/publications.sequence, /*_PREFIX_*/publications.publication';
$zz['fields'][25]['display_field'] = 'publication_title';
$zz['fields'][25]['hide_in_list'] = true;
$zz['fields'][25]['placeholder'] = true;
$zz['fields'][25]['search'] = '/*_PREFIX_*/publications.publication';

$zz['fields'][26]['title'] = 'Issue';
$zz['fields'][26]['field_name'] = 'issue_id';
$zz['fields'][26]['type'] = 'select';
$zz['fields'][26]['sql'] = 'SELECT /*_PREFIX_*/issues.issue_id
		, CONCAT(/*_PREFIX_*/publications.publication, ": ", COALESCE(NULLIF(/*_PREFIX_*/issues.issue, ""), /*_PREFIX_*/issues.identifier)) AS issue_label
	FROM /*_PREFIX_*/issues
	LEFT JOIN /*_PREFIX_*/publications USING (publication_id)
	ORDER BY /*_PREFIX_*/publications.publication, /*_PREFIX_*/issues.period_begin DESC, /*_PREFIX_*/issues.sequence, /*_PREFIX_*/issues.identifier';
$zz['fields'][26]['hide_in_list_if_empty'] = true;
$zz['fields'][26]['placeholder'] = true;
$zz['fields'][26]['display_field'] = 'issue_date';
$zz['fields'][26]['list_format'] = 'wrap_date';
$zz['fields'][26]['search'] = 'CONCAT(/*_PREFIX_*/publications.publication, ": ", COALESCE(/*_PREFIX_*/issues.issue, /*_PREFIX_*/issues.identifier))';

$zz['fields'][9]['field_name'] = 'identifier';
$zz['fields'][9]['type'] = 'identifier';
$zz['fields'][9]['fields'] = ['date{0,4}', 'title_short', 'title', 'identifier'];
$zz['fields'][9]['identifier']['ignore_this_if']['title'] = 'title_short';
$zz['fields'][9]['if'][5]['fields'] = ['title_short', 'title', 'identifier'];
$zz['fields'][9]['identifier']['exists'] = '-';
$zz['fields'][9]['identifier']['concat'] = '/';
$zz['fields'][9]['hide_in_list'] = true;
$zz['fields'][9]['if'][8]['identifier']['prefix'] = 'news/';
$zz['fields'][9]['if'][8]['fields'] = ['date'];

$zz['fields'][99]['field_name'] = 'last_update';
$zz['fields'][99]['type'] = 'timestamp';
$zz['fields'][99]['hide_in_list'] = true;

$zz['fields'][19]['title'] = 'Type';
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
		, /*_PREFIX_*/publications.publication AS publication_title
		, /*_PREFIX_*/publications.identifier AS publication_path
		, /*_PREFIX_*/issues.issue AS issue_title
		, /*_PREFIX_*/issues.period_begin AS issue_date
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
	LEFT JOIN /*_PREFIX_*/publications
		ON /*_PREFIX_*/publications.publication_id = /*_PREFIX_*/articles.publication_id
	LEFT JOIN /*_PREFIX_*/issues
		ON /*_PREFIX_*/issues.issue_id = /*_PREFIX_*/articles.issue_id
';
if (wrap_category_id('news', 'check')) {
	$zz['sql'] .= ' LEFT JOIN /*_PREFIX_*/articles_categories articles_categories
			ON articles_categories.article_id = /*_PREFIX_*/articles.article_id
			AND articles_categories.type_category_id = /*_ID categories news _*/';
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

$hide_publication_ids = [];
if (!empty($brick['local_settings']['news_hide_publications'])) {
	foreach ($brick['local_settings']['news_hide_publications'] as $identifier)
		if ($id = wrap_id('publications', $identifier))
			$hide_publication_ids[] = $id;
	$zz['sql'] = wrap_edit_sql($zz['sql'], 'WHERE', sprintf('(/*_PREFIX_*/articles.publication_id IS NULL OR /*_PREFIX_*/articles.publication_id NOT IN (%s))', implode(',', $hide_publication_ids)));
	$zz['fields'][25]['sql'] = wrap_edit_sql($zz['fields'][25]['sql'], 'WHERE', sprintf('publication_id NOT IN (%s)', implode(',', $hide_publication_ids)));
}

$where_hide = $hide_publication_ids ? sprintf(' WHERE publication_id NOT IN (%s)', implode(',', $hide_publication_ids)) : '';

$sql = sprintf('SELECT publication_id AS value
		, publication AS type
		, "publication_id" AS field_name
	FROM /*_PREFIX_*/publications
	%s
	ORDER BY sequence, publication', $where_hide);
$zz['add'] = wrap_db_fetch($sql, 'publication_id', 'numeric');

$zz['filter'][5]['title'] = wrap_text('Publication');
$zz['filter'][5]['identifier'] = 'publication';
$zz['filter'][5]['type'] = 'list';
$zz['filter'][5]['where'] = 'publication_id';
$zz['filter'][5]['sql'] = sprintf('SELECT DISTINCT publication_id, publication, identifier
	FROM publications
	%s
	ORDER BY identifier', $where_hide);

$zz['conditions'][1]['scope'] = 'record';
$zz['conditions'][1]['where'] = 'publications.parameters LIKE "%&news_article=0%"';
$zz['conditions'][1]['add']['sql'] = 'SELECT publication_id
	FROM /*_PREFIX_*/publications
	WHERE publication_id = ';
$zz['conditions'][1]['add']['key_field_name'] = 'publication_id';

$zz['conditions'][2]['scope'] = 'record';
$zz['conditions'][2]['where'] = 'publications.parameters LIKE "%&news_newsletter_lead=0%"';
$zz['conditions'][2]['add']['sql'] = 'SELECT publication_id
	FROM /*_PREFIX_*/publications
	WHERE publication_id = ';
$zz['conditions'][2]['add']['key_field_name'] = 'publication_id';

$zz['conditions'][4]['scope'] = 'record';
$zz['conditions'][4]['where'] = 'publications.parameters LIKE "%&news_categories=0%"';
$zz['conditions'][4]['add']['sql'] = 'SELECT publication_id
	FROM /*_PREFIX_*/publications
	WHERE publication_id = ';
$zz['conditions'][4]['add']['key_field_name'] = 'publication_id';

$zz['conditions'][5]['scope'] = 'record';
$zz['conditions'][5]['where'] = 'publications.parameters LIKE "%&news_identifier_without_year=1%"';
$zz['conditions'][5]['add']['sql'] = 'SELECT publication_id
	FROM /*_PREFIX_*/publications
	WHERE publication_id = ';
$zz['conditions'][5]['add']['key_field_name'] = 'publication_id';

$zz['conditions'][6]['scope'] = 'record';
$zz['conditions'][6]['where'] = 'publications.parameters LIKE "%&news_subtitle=0%"';
$zz['conditions'][6]['add']['sql'] = 'SELECT publication_id
	FROM /*_PREFIX_*/publications
	WHERE publication_id = ';
$zz['conditions'][6]['add']['key_field_name'] = 'publication_id';

$zz['conditions'][7]['scope'] = 'record';
$zz['conditions'][7]['where'] = 'publications.parameters LIKE "%&news_media=0%"';
$zz['conditions'][7]['add']['sql'] = 'SELECT publication_id
	FROM /*_PREFIX_*/publications
	WHERE publication_id = ';
$zz['conditions'][7]['add']['key_field_name'] = 'publication_id';

$zz['conditions'][8]['scope'] = 'record';
$zz['conditions'][8]['where'] = 'publications.parameters LIKE "%&news_short=1%"';
$zz['conditions'][8]['add']['sql'] = 'SELECT publication_id
	FROM /*_PREFIX_*/publications
	WHERE publication_id = ';
$zz['conditions'][8]['add']['key_field_name'] = 'publication_id';

$zz['conditions'][9]['scope'] = 'record';
$zz['conditions'][9]['where'] = 'publications.parameters LIKE "%&news_title_short=0%"';
$zz['conditions'][9]['add']['sql'] = 'SELECT publication_id
	FROM /*_PREFIX_*/publications
	WHERE publication_id = ';
$zz['conditions'][9]['add']['key_field_name'] = 'publication_id';

$zz['conditions'][3]['scope'] = 'record';
$zz['conditions'][3]['where'] = '/*_PREFIX_*/articles.published = "no"';

$zz['record']['copy'] = true;

$zz['subtitle']['publication_id']['sql'] = $zz['fields'][25]['sql'];
$zz['subtitle']['publication_id']['var'] = ['publication'];

$zz['hooks']['after_insert'][] = 'mf_zzwrap_url_placeholder_years';
$zz['hooks']['after_update'][] = 'mf_zzwrap_url_placeholder_years';
$zz['hooks']['after_delete'][] = 'mf_zzwrap_url_placeholder_years';
