<?php 

/**
 * news module
 * form for news articles
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2023 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


$zz = zzform_include('articles');

if (in_array('contacts', wrap_setting('modules'))) {
	// @todo read via categories
	$keys = ['author' => 'Authors', 'description' => 'Organisations'];
	$i = 40;
	foreach ($keys as $key => $title) {
		if (!wrap_category_id('roles/'.$key, 'check')) continue;
		$zz['fields'][$i] = zzform_include('articles-contacts');
		$zz['fields'][$i]['table_name'] = 'articles_contacts_'.$key;
		$zz['fields'][$i]['title'] = $title;
		$zz['fields'][$i]['type'] = 'subtable';
		$zz['fields'][$i]['min_records'] = 1;
		$zz['fields'][$i]['max_records'] = 10; // @todo read via categories
		$zz['fields'][$i]['hide_in_list'] = true;
		$zz['fields'][$i]['form_display'] = 'lines';
		$zz['fields'][$i]['sql'] .= sprintf(' WHERE role_category_id = %d
			ORDER BY /*_PREFIX_*/articles.date DESC, sequence', wrap_category_id('roles/'.$key));
		$zz['fields'][$i]['fields'][2]['type'] = 'foreign_key';
		$zz['fields'][$i]['fields'][4]['type'] = 'hidden';
		$zz['fields'][$i]['fields'][4]['hide_in_form'] = true;
		$zz['fields'][$i]['fields'][4]['value'] = wrap_category_id('roles/'.$key);
		$zz['fields'][$i]['fields'][5]['type'] = 'sequence';
		$i++;
	}
}
