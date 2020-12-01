<?php 

/**
 * news module
 * get article data per ID
 *
 * Part of »Zugzwang Project«
 * http://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2020 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


/**
 * get article data per ID, pre-sorted
 * existing data is appended to article data
 *
 * @param array $data
 * @param array $settings
 * @param string $id_field_name (optional, if key does not equal event_id)
 * @param string $lang_field_name (optional, if not current language shall be used)
 * @return array
 */
function mod_news_get_articledata($data, $settings = [], $id_field_name = '', $lang_field_name = '') {
	if (!$data) return $data;
	global $zz_setting;
	require_once $zz_setting['core'].'/data.inc.php';

	$ids = wrap_data_ids($data, $id_field_name);
	$langs = wrap_data_langs($data, $id_field_name);

	$sql = 'SELECT article_id
			, articles.date, articles.time, articles.identifier
			, articles.abstract, articles.lead, articles.title
			, direct_link
			, article
			, DATE_FORMAT(articles.last_update, "%%a, %%d %%b %%Y %%H:%%i:%%s") AS pubDate
			, CONCAT("%s/", identifier, "/") AS guid
			, CONCAT("%s/", identifier, "/") AS link
		FROM articles
		WHERE articles.article_id IN (%s)
		ORDER BY FIELD(articles.article_id, %s)';
	$sql = sprintf($sql
		, wrap_get_setting('news_url')
		, wrap_get_setting('news_url')
		, implode(',', $ids), implode(',', $ids)
	);
	$articledata = wrap_db_fetch($sql, 'article_id');
	foreach ($langs as $lang) {
		$articles[$lang] = wrap_translate($articledata, 'articles', '', true, $lang);
		$articles[$lang] = wrap_translate($articles[$lang], 'categories', 'event_id', true, $lang);
	}

	// media
	$articles = wrap_data_media($articles, $ids, $langs, 'articles', 'article');

	// categories
	$sql = 'SELECT article_category_id, article_id, category_id, category
		FROM articles_categories
		LEFT JOIN categories USING (category_id)
		WHERE article_id = %d
		ORDER by categories.sequence, category';
	$sql = sprintf($sql, implode(',', $ids));
	$categorydata = wrap_db_fetch($sql, 'article_category_id');
	foreach ($langs as $lang) {
		$categories[$lang] = wrap_translate($categorydata, 'categories', 'category_id', true, $lang);
	}
	foreach ($categories as $lang => $categories_per_lang) {
		foreach ($categories_per_lang as $article_category_id => $category) {
			$articles[$lang][$category['article_id']]['categories'][$article_category_id] = $category; 
		}
	}

	$data = wrap_data_merge($data, $articles, $id_field_name, $lang_field_name);
	return $data;
}	
