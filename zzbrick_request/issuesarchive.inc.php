<?php

/**
 * news module
 * Issues archive grouped by year (same publication)
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/news
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2026 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


/**
 * Publication issues grouped by archive year (descending): 
 * year header + unordered list + links.
 *
 * @param array $params
 * @return array|false
 */
function mod_news_issuesarchive($params) {
	if (count($params) !== 1) return false;

	$sql = 'SELECT /*_PREFIX_*/issues.issue_id
			, /*_PREFIX_*/issues.identifier
			, /*_PREFIX_*/issues.issue
			, YEAR(COALESCE(
				/*_PREFIX_*/issues.period_begin
				, /*_PREFIX_*/issues.period_end
			)) AS archive_year
		FROM /*_PREFIX_*/issues
		INNER JOIN /*_PREFIX_*/publications USING (publication_id)
		WHERE /*_PREFIX_*/publications.identifier = "%s"';
	if (!wrap_access('news_preview')) {
		$sql .= ' AND /*_PREFIX_*/issues.published = "yes"';
	}
	$sql .= ' ORDER BY archive_year DESC, /*_PREFIX_*/issues.period_begin DESC
			, /*_PREFIX_*/issues.sequence DESC, /*_PREFIX_*/issues.issue_id DESC';
	$sql = sprintf($sql, wrap_db_escape($params[0]));
	$issues = wrap_db_fetch($sql, ['archive_year', 'issue_id'], 'list archive_year issues');
	if (!$issues) $issues['no_issues'] = true;
	$page['text'] = wrap_template('issues-archive', $issues);
	return $page;
}
