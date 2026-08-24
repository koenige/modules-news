<?php 

/**
 * news module
 * form for issues of a publication
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2026 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


if (empty($brick['data']['publication_id'])) wrap_quit(404);
if (!$brick['data']['issued_publication']) wrap_quit(404, wrap_text('This publication is not split into separate issues.'));

$zz = zzform_include('issues');
$zz['where']['publication_id'] = $brick['data']['publication_id'];
$zz['page']['show'][] = 'publication';
$zz['vars']['publication'] = $brick['data'];
$zz['vars']['publication']['issues_form'] = 1;
