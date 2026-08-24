<?php 

/**
 * news module
 * Publications (outlets / series) for articles
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2026 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


$zz['title'] = 'Publications';
$zz['table'] = '/*_PREFIX_*/publications';

$zz['fields'][1]['title'] = 'ID';
$zz['fields'][1]['field_name'] = 'publication_id';
$zz['fields'][1]['type'] = 'id';

$zz['fields'][3]['title'] = 'Publication';
$zz['fields'][3]['field_name'] = 'publication';
$zz['fields'][3]['type'] = 'text';
$zz['fields'][3]['typo_cleanup'] = true;
$zz['fields'][3]['list_prefix'] = '<strong>';
$zz['fields'][3]['list_suffix'] = '</strong>';
$zz['fields'][3]['list_append_next'] = true;
$zz['fields'][3]['link'] = [
	'area' => 'news_publication_internal',
	'fields' => ['identifier']
];

$zz['fields'][6]['title'] = 'Short name';
$zz['fields'][6]['field_name'] = 'publication_short';
$zz['fields'][6]['type'] = 'text';
$zz['fields'][6]['size'] = 24;
$zz['fields'][6]['hide_in_list'] = true;
$zz['fields'][6]['typo_cleanup'] = true;
$zz['fields'][6]['typo_remove_double_spaces'] = true;

$zz['fields'][8]['title'] = 'Description';
$zz['fields'][8]['field_name'] = 'description';
$zz['fields'][8]['type'] = 'memo';
$zz['fields'][8]['rows'] = 4;
$zz['fields'][8]['format'] = 'markdown';
$zz['fields'][8]['list_format'] = 'markdown';
$zz['fields'][8]['typo_cleanup'] = true;

$zz['fields'][4]['title'] = 'Distribution';
$zz['fields'][4]['field_name'] = 'distribution';
$zz['fields'][4]['type'] = 'select';
$zz['fields'][4]['enum'] = ['continuous', 'issued'];
$zz['fields'][4]['default'] = 'continuous';
$zz['fields'][4]['enum_title'] = [
	wrap_text('Continuous publication'),
	wrap_text('Issued publication'),
];
$zz['fields'][4]['show_values_as_list'] = true;

$zz['fields'][10]['field_name'] = 'parameters';
$zz['fields'][10]['type'] = 'parameter';
$zz['fields'][10]['hide_in_list'] = true;

$zz['fields'][7]['title_tab'] = 'Seq.';
$zz['fields'][7]['field_name'] = 'sequence';
$zz['fields'][7]['type'] = 'number';
$zz['fields'][7]['hide_in_list'] = true;

$zz['fields'][9]['title'] = 'Identifier';
$zz['fields'][9]['field_name'] = 'identifier';
$zz['fields'][9]['type'] = 'identifier';
$zz['fields'][9]['fields'] = ['publication', 'identifier'];
$zz['fields'][9]['identifier']['exists'] = '-';
$zz['fields'][9]['identifier']['concat'] = '-';
$zz['fields'][9]['hide_in_list'] = true;

$zz['fields'][99]['field_name'] = 'last_update';
$zz['fields'][99]['type'] = 'timestamp';
$zz['fields'][99]['hide_in_list'] = true;

$zz['sql'] = 'SELECT /*_PREFIX_*/publications.*
	FROM /*_PREFIX_*/publications';
$zz['sqlorder'] = ' ORDER BY /*_PREFIX_*/publications.sequence, /*_PREFIX_*/publications.publication';
$zz['sql_translate'] = ['publication_id' => 'publications'];
