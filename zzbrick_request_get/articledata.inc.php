<?php 

/**
 * news module
 * get article data per ID
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2020-2023 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


/**
 * get article data per ID, pre-sorted
 * existing data is appended to article data
 *
 * @param array $data
 * @param array $settings (optional)
 * @param string $id_field_name (optional, if key does not equal event_id)
 * @param string $lang_field_name (optional, if not current language shall be used)
 * @return array
 */
function mod_news_get_articledata($data, $settings = [], $id_field_name = '', $lang_field_name = '') {
	if (!$data) return $data;
	require_once wrap_setting('core').'/data.inc.php';

	$ids = wrap_data_ids($data, $id_field_name);
	$langs = wrap_data_langs($data, $lang_field_name);

	$sql = 'SELECT article_id
			, articles.date, articles.time, articles.identifier
			, articles.abstract, articles.title, articles.subtitle
			, direct_link
			, article
			, DATE_FORMAT(articles.last_update, "%%a, %%d %%b %%Y %%H:%%i:%%s") AS pubDate
			, DATE_FORMAT(articles.last_update, "%%Y-%%m-%%dT%%H:%%i:%%s") AS modified
			, IF (published = "yes", 1, NULL) AS published
			%s
		FROM articles
		WHERE articles.article_id IN (%s)
		ORDER BY FIELD(articles.article_id, %s)';
	$sql = sprintf($sql
		, !empty($settings['extra_fields']) ? ','.implode(',', $settings['extra_fields']) : ''
		, implode(',', $ids), implode(',', $ids)
	);
	$articledata = wrap_db_fetch($sql, 'article_id');
	foreach ($articledata as $article_id => $article) {
		$articledata[$article_id]['guid'] = 
		$articledata[$article_id]['link'] = wrap_path('news_article', $article['identifier']);
	}
	foreach ($langs as $lang) {
		$articles[$lang] = wrap_translate($articledata, 'articles', '', true, $lang);
		$articles[$lang] = wrap_translate($articles[$lang], 'categories', 'event_id', true, $lang);
		foreach (array_keys($articles[$lang]) as $article_id) {
			$articles[$lang][$article_id][$lang] = true;
		}
	}

	// media
	$articles = wrap_data_media($articles, $ids, $langs, 'articles', 'article');

	// categories
	$article_categories = [
		'news' => 'categories',
		'publications' => 'publications'
	];
	foreach ($article_categories as $category => $path) {
		if (!wrap_category_id($category, 'check')) continue;
		$sql = 'SELECT article_category_id, article_id, category_id, category
				, REPLACE(SUBSTRING_INDEX(path, "/", -1), "-", "_") AS path_fragment
			FROM articles_categories
			LEFT JOIN categories USING (category_id)
			WHERE article_id IN (%s)
			AND type_category_id = %d
			AND (ISNULL(parameters) OR parameters NOT LIKE "%%&hidden=1%%")
			ORDER by articles_categories.sequence, categories.sequence, category';
		$sql = sprintf($sql, implode(',', $ids), wrap_category_id($category));
		$categorydata = wrap_db_fetch($sql, 'article_category_id');
		foreach ($langs as $lang) {
			$categories[$lang] = wrap_translate($categorydata, $path, 'category_id', true, $lang);
		}
		foreach ($categories as $lang => $categories_per_lang) {
			foreach ($categories_per_lang as $article_category_id => $category) {
				$articles[$lang][$category['article_id']][$category['path_fragment']] = true;
				$articles[$lang][$category['article_id']][$path][$article_category_id] = $category; 
			}
		}
	}
	foreach ($articles as $lang => $articles_per_lang) {
		foreach ($articles_per_lang as $article_id => $article) {
			if (empty($article['publications'])) continue;
			$publication = reset($article['publications']);
			$link = wrap_path('news_article['.$publication['path_fragment'].']', $article['identifier'], false);
			if ($link) {
				$articles[$lang][$article_id]['guid'] = 
				$articles[$lang][$article_id]['link'] = $link;
			}
		}
	}

	// contacts
	if (in_array('contacts', wrap_setting('modules'))) {
		$sql = 'SELECT article_contact_id, article_id, contact_id, contact
				, SUBSTRING_INDEX(categories.path, "/", -1) AS role
			FROM articles_contacts
			LEFT JOIN contacts USING (contact_id)
			LEFT JOIN categories
				ON articles_contacts.role_category_id = categories.category_id
			WHERE article_id IN (%s)
			ORDER BY articles_contacts.sequence, contacts.identifier';
		$sql = sprintf($sql, implode(',', $ids));
		$contactdata = wrap_db_fetch($sql, 'article_contact_id');
		foreach ($langs as $lang) {
			$contacts[$lang] = wrap_translate($contactdata, 'contacts', 'contact_id', true, $lang);
		}
		foreach ($contacts as $lang => $contacts_per_lang) {
			foreach ($contacts_per_lang as $article_contact_id => $contact) {
				$articles[$lang][$contact['article_id']][$contact['role']][$contact['article_contact_id']] = $contact;
			}
		}
	}

	$data = wrap_data_merge($data, $articles, $id_field_name, $lang_field_name);
	return $data;
}	
