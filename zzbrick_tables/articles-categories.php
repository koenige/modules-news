<?php 

/**
 * Zugzwang Project
 * Table for categories for news articles (tagging)
 *
 * http://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2010-2011, 2018-2020 Gustaf Mossakowski
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
$zz_sub['fields'][3]['character_set'] = 'utf8';
$zz_sub['fields'][3]['add_details'] = sprintf('categories?filter[maincategory]=%d', wrap_category_id('news'));
$zz_sub['fields'][3]['show_hierarchy'] = 'main_category_id';
$zz_sub['fields'][3]['show_hierarchy_subtree'] = wrap_category_id('news');

$zz_sub['fields'][5]['field_name'] = 'type_category_id';
$zz_sub['fields'][5]['type'] = 'hidden';
$zz_sub['fields'][5]['type_detail'] = 'select';
$zz_sub['fields'][5]['value'] = wrap_category_id('news');
$zz_sub['fields'][5]['hide_in_form'] = true;
$zz_sub['fields'][5]['hide_in_list'] = true;
$zz_sub['fields'][5]['exclude_from_search'] = true;

$zz_sub['fields'][20]['title'] = 'Updated';
$zz_sub['fields'][20]['field_name'] = 'last_update';
$zz_sub['fields'][20]['type'] = 'timestamp';
$zz_sub['fields'][20]['hide_in_list'] = true;

$zz_sub['sql'] = 'SELECT /*_PREFIX_*/articles_categories.*
	, /*_PREFIX_*/articles.title
	, /*_PREFIX_*/categories.category
	FROM /*_PREFIX_*/articles_categories
	LEFT JOIN /*_PREFIX_*/articles USING (article_id)
	LEFT JOIN /*_PREFIX_*/categories USING (category_id)
';
$zz_sub['sqlorder'] = ' ORDER BY category, date DESC';
