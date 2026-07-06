<?php

/**
 * news module
 * Database hooks for zzform
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2026 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


/**
 * update url_placeholders with article years after article change
 *
 * @param array $ops
 * @return void
 */
function mf_news_url_placeholder_years($ops) {
	foreach ($ops['return'] as $index => $table) {
		if ($table['table'] !== 'articles') continue;
		if ($table['action'] === 'nothing') continue;
		wrap_include('news', 'functions');
		mf_news_url_placeholder_years_write();
		return;
	}
}
