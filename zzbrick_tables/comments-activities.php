<?php 

/**
 * news module
 * Table with comments as activities
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2020 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


$zz['title'] = 'Comments: Activities';
$zz['table'] = 'comments_activities';

$zz['fields'][1]['title'] = 'ID';
$zz['fields'][1]['field_name'] = 'link_activity_id';
$zz['fields'][1]['type'] = 'id';

$zz['fields'][2]['field_name'] = 'comment_id';
$zz['fields'][2]['type'] = 'select';
$zz['fields'][2]['sql'] = 'SELECT comment_id, comment
	FROM comments
	LEFT JOIN articles USING (article_id)
	ORDER BY comment';
$zz['fields'][2]['display_field'] = 'comment';

$zz['fields'][3]['field_name'] = 'activity_id';
$zz['fields'][3]['type'] = 'select';
$zz['fields'][3]['sql'] = 'SELECT activity_id, activity_date, contact, category
	FROM activities
	LEFT JOIN categories
		ON activities.activity_category_id = categories.category_id
	LEFT JOIN participations USING (participation_id)
	LEFT JOIN contacts USING (contact_id)
	ORDER BY contacts.identifier, activity_date';
$zz['fields'][3]['display_field'] = 'activity';
$zz['fields'][3]['search'] = 'CONCAT(contact, " ", category, " ", activity_date)';

$zz['sql'] = 'SELECT comments_activities.*
		, comment
		, CONCAT(contact, " ", category, " ", activity_date) AS activity
	FROM comments_activities
	LEFT JOIN comments USING (comment_id)
	LEFT JOIN activities USING (activity_id)
	LEFT JOIN participations USING (participation_id)
	LEFT JOIN contacts USING (contact_id)
	LEFT JOIN categories
		ON activities.activity_category_id = categories.category_id
';
$zz['sqlorder'] = ' ORDER BY created DESC, subject, comments.sequence';
