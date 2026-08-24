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


if (empty($brick['data']['publication_id'])) wrap_quit(404);

$zz = zzform_include('articles');
$zz['where']['publication_id'] = $brick['data']['publication_id'];
$zz['title'] = $brick['data']['publication'];
unset($zz['subtitle']['publication_id']);
unset($zz['add']);

foreach ($zz['fields'] as $no => $field) {
	$identifier = zzform_field_identifier($field);
	if (!$identifier) continue;

	switch ($identifier) {
	case 'issue_id':
		if (empty($brick['data']['issued_publication']))
			unset($zz['fields'][$no]);
		break;
	}
}

$zz['filter'][1]['sql'] = wrap_edit_sql($zz['filter'][1]['sql'],
	'WHERE', sprintf('publication_id = %d', $zz['where']['publication_id'])
);
if (!empty($zz['filter'][2])) {
	$zz['filter'][2]['sql'] = wrap_edit_sql($zz['filter'][2]['sql'],
		'JOIN', 'LEFT JOIN articles USING (article_id)'
	);
	$zz['filter'][2]['sql'] = wrap_edit_sql($zz['filter'][2]['sql'],
		'WHERE', sprintf('publication_id = %d', $zz['where']['publication_id'])
	);
}

if (wrap_package('contacts')) {
	$values['context']['roles'] = 'news_articles';
	mf_default_categories_restrict($values, 'roles');

	$no = 40;
	foreach ($values['roles'] as $role)
		mf_contacts_contacts_subtable($zz, 'articles', $role, $no++);
}
