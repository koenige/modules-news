<?php 

/**
 * news module
 * Table for categories for news articles (tagging)
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2010-2011, 2018-2020, 2023 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


$zz['title'] = 'Categories of Articles';
$zz['table'] = '/*_PREFIX_*/articles_categories';

$zz['fields'][1]['title'] = 'ID';
$zz['fields'][1]['field_name'] = 'article_category_id';
$zz['fields'][1]['type'] = 'id';

$zz['fields'][2]['field_name'] = 'article_id';
$zz['fields'][2]['type'] = 'select';
$zz['fields'][2]['sql'] = 'SELECT article_id, date, title
	FROM /*_PREFIX_*/articles';
$zz['fields'][2]['display_field'] = 'title';

$zz['fields'][4]['title'] = 'No.';
$zz['fields'][4]['field_name'] = 'sequence';
$zz['fields'][4]['type'] = 'number';
$zz['fields'][4]['auto_value'] = 'increment';
$zz['fields'][4]['def_val_ignore'] = true;

$zz['fields'][3]['field_name'] = 'category_id';
$zz['fields'][3]['type'] = 'select';
$zz['fields'][3]['sql'] = 'SELECT category_id, category, main_category_id
	FROM /*_PREFIX_*/categories ORDER BY category';
$zz['fields'][3]['display_field'] = 'category';
$zz['fields'][3]['character_set'] = 'utf8';
$zz['fields'][3]['add_details'] = sprintf('categories?filter[maincategory]=%d', wrap_category_id('news'));
$zz['fields'][3]['show_hierarchy'] = 'main_category_id';
$zz['fields'][3]['show_hierarchy_subtree'] = wrap_category_id('news');

$zz['fields'][5]['field_name'] = 'type_category_id';
$zz['fields'][5]['type'] = 'hidden';
$zz['fields'][5]['type_detail'] = 'select';
$zz['fields'][5]['value'] = wrap_category_id('news');
$zz['fields'][5]['hide_in_form'] = true;
$zz['fields'][5]['hide_in_list'] = true;
$zz['fields'][5]['exclude_from_search'] = true;

if (wrap_setting('news_category_properties')) {
	$zz['fields'][6]['field_name'] = 'property';
	$zz['fields'][6]['typo_cleanup'] = true;
}

$zz['fields'][99]['field_name'] = 'last_update';
$zz['fields'][99]['type'] = 'timestamp';
$zz['fields'][99]['hide_in_list'] = true;

$zz['sql'] = 'SELECT /*_PREFIX_*/articles_categories.*
	, /*_PREFIX_*/articles.title
	, /*_PREFIX_*/categories.category
	FROM /*_PREFIX_*/articles_categories
	LEFT JOIN /*_PREFIX_*/articles USING (article_id)
	LEFT JOIN /*_PREFIX_*/categories USING (category_id)
';
$zz['sqlorder'] = ' ORDER BY category, date DESC';
