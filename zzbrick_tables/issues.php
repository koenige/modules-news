<?php 

/**
 * news module
 * Issues (editions) of a publication
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2026 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


$zz['title'] = 'Issues';
$zz['table'] = '/*_PREFIX_*/issues';

$zz['fields'][1]['title'] = 'ID';
$zz['fields'][1]['field_name'] = 'issue_id';
$zz['fields'][1]['type'] = 'id';

$zz['fields'][2]['title'] = 'Publication';
$zz['fields'][2]['field_name'] = 'publication_id';
$zz['fields'][2]['type'] = 'select';
$zz['fields'][2]['sql'] = 'SELECT publication_id, publication, identifier
	FROM /*_PREFIX_*/publications
	ORDER BY /*_PREFIX_*/publications.sequence, /*_PREFIX_*/publications.publication';
$zz['fields'][2]['sql_ignore'] = ['identifier'];
$zz['fields'][2]['display_field'] = 'publication';

$zz['fields'][3]['title'] = 'Issue';
$zz['fields'][3]['field_name'] = 'issue';
$zz['fields'][3]['type'] = 'text';
$zz['fields'][3]['typo_cleanup'] = true;
$zz['fields'][3]['link'] = [
	'area' => 'news_issue',
	'fields' => ['identifier']
];

$zz['fields'][4]['title'] = 'Issue short';
$zz['fields'][4]['field_name'] = 'issue_short';
$zz['fields'][4]['type'] = 'text';
$zz['fields'][4]['typo_cleanup'] = true;
$zz['fields'][4]['maxlength'] = 31;
$zz['fields'][4]['hide_in_list'] = true;

$zz['fields'][9]['title'] = 'Identifier';
$zz['fields'][9]['field_name'] = 'identifier';
$zz['fields'][9]['type'] = 'identifier';
$zz['fields'][9]['fields'] = [
	'publication_id[identifier]', 'period_begin{0,4}', 'period_begin{5,2}', 'identifier'
];
$zz['fields'][9]['identifier']['exists'] = '-';
$zz['fields'][9]['identifier']['concat'] = '/';
$zz['fields'][9]['hide_in_list'] = true;

$zz['fields'][5]['title'] = 'Intro';
$zz['fields'][5]['field_name'] = 'intro';
$zz['fields'][5]['type'] = 'memo';
$zz['fields'][5]['format'] = 'markdown';
$zz['fields'][5]['rows'] = 5;
$zz['fields'][5]['hide_in_list'] = true;
$zz['fields'][5]['typo_cleanup'] = true;

$zz['fields'][6]['title'] = 'Article';
$zz['fields'][6]['field_name'] = 'article';
$zz['fields'][6]['type'] = 'memo';
$zz['fields'][6]['format'] = 'markdown';
$zz['fields'][6]['rows'] = 16;
$zz['fields'][6]['hide_in_list'] = true;
$zz['fields'][6]['typo_cleanup'] = true;

$zz['fields'][11]['title'] = 'Period begin';
$zz['fields'][11]['title_tab'] = 'Period';
$zz['fields'][11]['field_name'] = 'period_begin';
$zz['fields'][11]['type'] = 'date';
$zz['fields'][11]['display_field'] = 'duration';
$zz['fields'][11]['list_format'] = 'wrap_date';

$zz['fields'][12]['title'] = 'Period end';
$zz['fields'][12]['field_name'] = 'period_end';
$zz['fields'][12]['type'] = 'date';
$zz['fields'][12]['hide_in_list'] = true;

$zz['fields'][7]['title_tab'] = 'Seq.';
$zz['fields'][7]['field_name'] = 'sequence';
$zz['fields'][7]['type'] = 'number';
$zz['fields'][7]['hide_in_list'] = true;

$zz['fields'][8]['title'] = 'Published?';
$zz['fields'][8]['field_name'] = 'published';
$zz['fields'][8]['type'] = 'select';
$zz['fields'][8]['enum'] = ['yes', 'no'];
$zz['fields'][8]['default'] = 'yes';
$zz['fields'][8]['hide_in_list'] = true;

$zz['fields'][98]['title'] = 'Date published';
$zz['fields'][98]['field_name'] = 'date_published';
$zz['fields'][98]['type'] = 'date';
$zz['fields'][98]['hide_in_list'] = true;

$zz['fields'][99]['field_name'] = 'last_update';
$zz['fields'][99]['type'] = 'timestamp';
$zz['fields'][99]['hide_in_list'] = true;

$zz['sql'] = 'SELECT /*_PREFIX_*/issues.*
		, CONCAT(IFNULL(period_begin, ""), "/", IFNULL(period_end, "")) AS duration
		, /*_PREFIX_*/publications.publication
	FROM /*_PREFIX_*/issues
	LEFT JOIN /*_PREFIX_*/publications USING (publication_id)';
$zz['sqlorder'] = ' ORDER BY /*_PREFIX_*/publications.publication, /*_PREFIX_*/issues.period_begin DESC, /*_PREFIX_*/issues.sequence, /*_PREFIX_*/issues.identifier';
$zz['sql_translate'] = ['issue_id' => 'issues'];

$zz['list']['group'] = 'publication_id';

$zz['filter'][1]['title'] = wrap_text('Publication');
$zz['filter'][1]['identifier'] = 'publication';
$zz['filter'][1]['type'] = 'list';
$zz['filter'][1]['where'] = 'publication_id';
$zz['filter'][1]['sql'] = 'SELECT DISTINCT publication_id
		, publication, issues.identifier
	FROM issues
	LEFT JOIN publications USING (publication_id)
	ORDER BY identifier';

$zz['subtitle']['publication_id']['sql'] = $zz['fields'][2]['sql'];
$zz['subtitle']['publication_id']['var'] = ['publication'];
