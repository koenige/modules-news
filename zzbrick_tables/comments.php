<?php 

/**
 * Zugzwang Project
 * Table for comments on articles
 *
 * http://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2010-2011, 2014-2015, 2017-2020 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


$zz['title'] = 'Comments';
$zz['table'] = '/*_PREFIX_*/comments';

$zz['fields'][1]['title'] = 'ID';
$zz['fields'][1]['field_name'] = 'comment_id';
$zz['fields'][1]['type'] = 'id';

$zz['fields'][2]['field_name'] = 'main_comment_id';
$zz['fields'][2]['type'] = 'select';
$zz['fields'][2]['sql'] = 'SELECT comment_id, comment, main_comment_id
	FROM /*_PREFIX_*/comments
	ORDER BY comment';
$zz['fields'][2]['display_field'] = 'title';
$zz['fields'][2]['show_hierarchy'] = 'main_comment_id';
$zz['fields'][2]['show_hierarchy_same_table'] = true;
$zz['fields'][2]['display_field'] = 'main_comment';
$zz['fields'][2]['exclude_from_search'] = true;

$zz['fields'][3]['field_name'] = 'article_id';
$zz['fields'][3]['type'] = 'select';
$zz['fields'][3]['sql'] = 'SELECT article_id, title
	FROM articles
	ORDER BY title';
$zz['fields'][3]['display_field'] = 'title';

$zz['fields'][4]['field_name'] = 'comment';
$zz['fields'][4]['type'] = 'memo';
$zz['fields'][4]['format'] = 'markdown';

$zz['fields'][5]['title'] = 'Published?';
$zz['fields'][5]['field_name'] = 'published';
$zz['fields'][5]['type'] = 'select';
$zz['fields'][5]['enum'] = ['yes', 'no'];
$zz['fields'][5]['default'] = 'yes';

$zz['sql'] = 'SELECT DISTINCT /*_PREFIX_*/comments.*
		, /*_PREFIX_*/articles.title
	FROM /*_PREFIX_*/comments
	LEFT JOIN /*_PREFIX_*/articles USING (article_id)
';
$zz['sqlorder'] = ' ORDER BY /*_PREFIX_*/articles.identifier, comment DESC';

$zz['list']['hierarchy']['mother_id_field_name'] = $zz['fields'][2]['field_name'];
$zz['list']['hierarchy']['display_in'] = $zz['fields'][3]['field_name'];
