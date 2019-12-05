<?php
/**
 * Render for news index
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-07-08
 */

	// initate it before require_once below
	global $newsindex_rows, $orbx_mod;
	$newsindex_rows = array();

	require_once DOC_ROOT . '/orbicon/modules/news-index/inc.news.index.php';
	require_once DOC_ROOT . '/orbicon/class/class.pagination.php';

	if(!isset($_GET['p'])) {
		$unset_below = true;
	}

	$_GET['pp'] = (isset($_GET['pp'])) ? $_GET['pp'] : 5;
	$_GET['p'] = (isset($_GET['p'])) ? $_GET['p'] : 1;

	// determine year to display
	$user_year = intval($_REQUEST['year']);
	$year = (!empty($user_year)) ? $user_year : date('Y', time());

	$qyear = (empty($user_year)) ? '' : "&year=$user_year";

	// add subscription form here if we haven't done so already
	$news_subs = '';
	/*if($orbx_mod->validate_module('news-alerts') && (scan_templates('<!>NWSALERTS_SUBS') < 1)) {
		$news_subs = include_once DOC_ROOT . '/orbicon/modules/news-alerts/render.frontend.php';
	}*/
	$newsindex = generate_archived_list() . get_news_index_categories($year) . $news_subs;

	$pagination = new Pagination('p', 'pp');
	$pagination->total = max($newsindex_rows);
	$pagination->split_pages();

	// this invalidates caching, clean up from memory
	if($unset_below) {
		unset($_GET['p'], $_GET['pp']);
	}

	return $newsindex . '<br />' .
	$pagination->construct_page_nav(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.news-index' . $qyear);

?>