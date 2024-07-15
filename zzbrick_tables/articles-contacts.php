<?php 

/**
 * news module
 * Table definition for contacts per article
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2021, 2023-2024 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


$zz['title'] = 'Articles/Contacts';
$zz['table'] = '/*_PREFIX_*/articles_contacts';

$zz['fields'][1]['title'] = 'ID';
$zz['fields'][1]['field_name'] = 'article_contact_id';
$zz['fields'][1]['type'] = 'id';

$zz['fields'][2]['field_name'] = 'article_id';
$zz['fields'][2]['type'] = 'select';
$zz['fields'][2]['sql'] = 'SELECT article_id, date, title
	FROM /*_PREFIX_*/articles';
$zz['fields'][2]['display_field'] = 'title';

$zz['fields'][5]['title'] = 'No.';
$zz['fields'][5]['field_name'] = 'sequence';
$zz['fields'][5]['type'] = 'number';
$zz['fields'][5]['auto_value'] = 'increment';
$zz['fields'][5]['def_val_ignore'] = true;

$zz['fields'][3]['field_name'] = 'contact_id';
$zz['fields'][3]['type'] = 'select';
$zz['fields'][3]['sql'] = 'SELECT contact_id, contact, identifier
	FROM /*_PREFIX_*/contacts
	ORDER BY identifier';
$zz['fields'][3]['display_field'] = 'contact';
$zz['fields'][3]['add_details'] = 'contacts';

$zz['fields'][4]['title'] = 'Category';
$zz['fields'][4]['field_name'] = 'role_category_id';
$zz['fields'][4]['type'] = 'select';
$zz['fields'][4]['sql'] = 'SELECT category_id, category, main_category_id
	FROM /*_PREFIX_*/categories ORDER BY category';
$zz['fields'][4]['display_field'] = 'category';
$zz['fields'][4]['character_set'] = 'utf8';
$zz['fields'][4]['add_details'] = sprintf('categories?filter[maincategory]=%d', wrap_category_id('roles'));
$zz['fields'][4]['show_hierarchy'] = 'main_category_id';
$zz['fields'][4]['show_hierarchy_subtree'] = wrap_category_id('roles');
$zz['fields'][4]['def_val_ignore'] = true;

$zz['fields'][6]['field_name'] = 'role';
$zz['fields'][6]['type'] = 'text';
$zz['fields'][6]['size'] = 18;
$zz['fields'][6]['hide_in_list_if_empty'] = true;
$zz['fields'][6]['hide_in_form'] = true;

$zz['fields'][99]['field_name'] = 'last_update';
$zz['fields'][99]['type'] = 'timestamp';
$zz['fields'][99]['hide_in_list'] = true;


$zz['sql'] = 'SELECT /*_PREFIX_*/articles_contacts.*
		, /*_PREFIX_*/articles.title
		, /*_PREFIX_*/contacts.contact
		, /*_PREFIX_*/categories.category
	FROM /*_PREFIX_*/articles_contacts
	LEFT JOIN /*_PREFIX_*/articles USING (article_id)
	LEFT JOIN /*_PREFIX_*/contacts USING (contact_id)
	LEFT JOIN /*_PREFIX_*/categories
		ON /*_PREFIX_*/categories.category_id = /*_PREFIX_*/articles_contacts.role_category_id
';
$zz['sqlorder'] = ' ORDER BY category, date DESC';


$zz['subselect']['sql'] = 'SELECT /*_PREFIX_*/articles.article_id
		, contact
		, contacts.identifier
	FROM /*_PREFIX_*/articles
	LEFT JOIN /*_PREFIX_*/articles_contacts USING (article_id)
	LEFT JOIN /*_PREFIX_*/contacts USING (contact_id)
	LEFT JOIN /*_PREFIX_*/persons USING (contact_id)
	ORDER BY last_name, first_name, contact';
$zz['subselect']['sql_ignore'][] = 'identifier';
$zz['subselect']['concat_rows'] = ', ';
$zz['unless']['export_mode']['subselect']['field_link'][0] = [
	'area' => 'contacts_profile[person]',
	'fields' => ['identifier']
];
