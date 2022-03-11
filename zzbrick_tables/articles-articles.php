<?php 

/**
 * news module
 * Table for articles related to other articles
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2022 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


$zz['title'] = 'Linked Articles';
$zz['table'] = '/*_PREFIX_*/articles_articles';

$zz['fields'][1]['title'] = 'ID';
$zz['fields'][1]['field_name'] = 'article_article_id';
$zz['fields'][1]['type'] = 'id';

$zz['fields'][6]['title_tab'] = 'No.';
$zz['fields'][6]['field_name'] = 'sequence';
$zz['fields'][6]['type'] = 'number';
$zz['fields'][6]['default'] = 1;
$zz['fields'][6]['auto_value'] = 'increment';
$zz['fields'][6]['def_val_ignore'] = true;
$zz['fields'][6]['placeholder'] = 'No.';
$zz['fields'][6]['for_action_ignore'] = true;

$zz['fields'][2]['title'] = 'Article';
$zz['fields'][2]['field_name'] = 'article_id';
$zz['fields'][2]['type'] = 'select';
$zz['fields'][2]['sql'] = 'SELECT article_id, title, identifier
	FROM /*_PREFIX_*/articles
	ORDER BY identifier';
$zz['fields'][2]['display_field'] = 'title';
$zz['fields'][2]['search'] = 'articles.title';
$zz['fields'][2]['placeholder'] = true;
$zz['fields'][2]['select_dont_force_single_value'] = true;
$zz['fields'][2]['select_empty_no_add'] = true;

$zz['fields'][4]['title'] = 'Relation';
$zz['fields'][4]['field_name'] = 'relation_category_id';
$zz['fields'][4]['key_field_name'] = 'category_id';
$zz['fields'][4]['type'] = 'select';
$zz['fields'][4]['type_detail'] = 'select';
$zz['fields'][4]['sql'] = sprintf('SELECT category_id, category
	FROM /*_PREFIX_*/categories
	WHERE main_category_id = %d',
	wrap_category_id('relation')
);
$zz['fields'][4]['sql_translate'] = ['category_id' => 'categories'];
$zz['fields'][4]['display_field'] = 'category';
$zz['fields'][4]['character_set'] = 'utf8';
$zz['fields'][4]['for_action_ignore'] = true;
$zz['fields'][4]['if']['where']['hide_in_form'] = true;
$zz['fields'][4]['if']['where']['hide_in_list'] = true;

$zz['fields'][3]['title'] = 'Main Article';
$zz['fields'][3]['field_name'] = 'main_article_id';
$zz['fields'][3]['key_field_name'] = 'article_id';
$zz['fields'][3]['type'] = 'select';
$zz['fields'][3]['type_detail'] = 'select';
$zz['fields'][3]['sql'] = 'SELECT article_id, title, identifier
	FROM /*_PREFIX_*/articles
	ORDER BY identifier';
$zz['fields'][3]['display_field'] = 'main_title';
$zz['fields'][3]['search'] = 'main_articles.title';
$zz['fields'][3]['character_set'] = 'utf8';
$zz['fields'][3]['select_dont_force_single_value'] = true;
$zz['fields'][3]['select_empty_no_add'] = true;
$zz['fields'][3]['not_identical_with'] = 'article_id';
$zz['fields'][3]['if']['where']['hide_in_form'] = true;
$zz['fields'][3]['if']['where']['hide_in_list'] = true;

$zz['fields'][99]['field_name'] = 'last_update';
$zz['fields'][99]['type'] = 'timestamp';
$zz['fields'][99]['hide_in_list'] = true;

$zz['sql'] = 'SELECT /*_PREFIX_*/articles_articles.*
		, /*_PREFIX_*/articles.title
		, /*_PREFIX_*/categories.category
		, main_articles.title AS main_title
	FROM /*_PREFIX_*/articles_articles
	LEFT JOIN /*_PREFIX_*/articles USING (article_id)
	LEFT JOIN /*_PREFIX_*/articles main_articles
		ON /*_PREFIX_*/articles_articles.main_article_id = main_articles.article_id
	LEFT JOIN /*_PREFIX_*/categories
		ON /*_PREFIX_*/categories.category_id = /*_PREFIX_*/articles_articles.relation_category_id
';
$zz['sql_association'] = 'SELECT /*_PREFIX_*/articles_articles.*
		, main_articles.title AS main_article, /*_PREFIX_*/articles.title
		, /*_PREFIX_*/categories.category
	FROM /*_PREFIX_*/articles_articles
	LEFT JOIN /*_PREFIX_*/articles main_articles USING (article_id)
	LEFT JOIN /*_PREFIX_*/articles 
		ON /*_PREFIX_*/articles_articles.main_article = articles.article_id
	LEFT JOIN /*_PREFIX_*/categories
		ON /*_PREFIX_*/categories.category_id = /*_PREFIX_*/articles_articles.relation_category_id
';
$zz['sqlorder'] = ' ORDER BY /*_PREFIX_*/articles.identifier, sequence, main_articles.identifier';
