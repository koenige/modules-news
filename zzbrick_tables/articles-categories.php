<?php 

/**
 * Zugzwang Project
 * Table for categories for news articles (tagging)
 *
 * http://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2010-2011, 2018 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


$zz_sub['title'] = 'Categories of Articles';
$zz_sub['table'] = '/*_PREFIX_*/articles_categories';

$zz_sub['fields'][1]['title'] = 'ID';
$zz_sub['fields'][1]['field_name'] = 'article_category_id';
$zz_sub['fields'][1]['type'] = 'id';

$zz_sub['fields'][2]['title'] = 'Article';
$zz_sub['fields'][2]['field_name'] = 'article_id';
$zz_sub['fields'][2]['type'] = 'select';
$zz_sub['fields'][2]['sql'] = 'SELECT article_id, date, title
	FROM /*_PREFIX_*/articles';
$zz_sub['fields'][2]['display_field'] = 'title';

$zz_sub['fields'][4]['title'] = 'No.';
$zz_sub['fields'][4]['field_name'] = 'sequence';
$zz_sub['fields'][4]['type'] = 'number';
$zz_sub['fields'][4]['auto_value'] = 'increment';
$zz_sub['fields'][4]['def_val_ignore'] = true;

$zz_sub['fields'][3]['title'] = 'Category';
$zz_sub['fields'][3]['field_name'] = 'category_id';
$zz_sub['fields'][3]['type'] = 'select';
$zz_sub['fields'][3]['sql'] = 'SELECT category_id, category, main_category_id
	FROM /*_PREFIX_*/categories ORDER BY category';
$zz_sub['fields'][3]['display_field'] = 'category';
$zz_sub['fields'][3]['add_details'] = 'categories';
$zz_sub['fields'][3]['show_hierarchy'] = 'main_category_id';
$zz_sub['fields'][3]['show_hierarchy_subtree'] = $zz_setting['category']['news'];

$zz_sub['fields'][20]['title'] = 'Updated';
$zz_sub['fields'][20]['field_name'] = 'last_update';
$zz_sub['fields'][20]['type'] = 'timestamp';
$zz_sub['fields'][20]['hide_in_list'] = true;

$zz_sub['sql'] = 'SELECT /*_PREFIX_*/articles_categories.*
	, /*_PREFIX_*/articles.title
	, category
	FROM /*_PREFIX_*/articles_categories
	LEFT JOIN /*_PREFIX_*/articles USING (article_id)
	LEFT JOIN /*_PREFIX_*/categories USING (category_id)
';
$zz_sub['sqlorder'] = ' ORDER BY category, date DESC';
