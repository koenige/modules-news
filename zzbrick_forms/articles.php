<?php 

/**
 * news module
 * form for news articles
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2023, 2026 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


$zz = zzform_include('articles');

if (wrap_package('contacts')) {
	$values['roles_restrict_to'] = 'articles';
	mf_default_categories_restrict($values, 'roles');

	$no = 40;
	foreach ($values['roles'] as $role)
		mf_contacts_contacts_subtable($zz, 'articles', $role, $no++);
}
