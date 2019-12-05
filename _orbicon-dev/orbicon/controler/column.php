<?php
/**
 * File for rendering columns, modules, etc.
 * @author Pavle Gardijan
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-07-01
 */

	require_once DOC_ROOT.'/orbicon/class/inc.column.php';

	$my_column = load_column(true);

	// redirect if found
	if($my_column['redirect'] && (ORBX_SITE_URL . $_SERVER['REQUEST_URI'] != $my_column['redirect'])) {
		redirect($my_column['redirect']);
	}

	// redirect if
	if($my_column['permalink_ascii'] && $_SESSION['site_settings']['us_ascii_uris'] && ($_GET[$orbicon_x->ptr] != urldecode($my_column['permalink_ascii']))) {
		redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=' . $my_column['permalink_ascii']);
	}

	if($my_column['desc']) {
		$orbicon_x->set_page_metatag('description', $my_column['desc']);
	}

	$linked_content = get_column_content_map($_GET[$orbicon_x->ptr]);
	// shouldn't be empty
	$my_column['lastmod'] = (!$my_column['lastmod']) ? time() : $my_column['lastmod'];

	// alt. content
	require_once DOC_ROOT.'/orbicon/class/inc.alt.php';
	$alt_content = get_page_formats();

	// related content
	require_once DOC_ROOT.'/orbicon/class/class.attila.php';
	$attila = new Attila;
	$rel_content = $attila->get_related($_GET[$orbicon_x->ptr]);
	$attila = null;
	// unset these here since they mess with caching
	if($_GET[$orbicon_x->ptr] != 'attila') {
		unset($_POST['sourceid'], $_GET['q']);
	}

	$column_title = $orbicon_x->get_page_title();
	if($column_title == '') {
		$column_title = $my_column['title'];
	}

	$column_title = str_sanitize($column_title, STR_SANITIZE_INPUT_TEXT_VALUE);
	$_SESSION['page_title'] = $column_title;

	$column_template = array();

	$header_file = (is_file(DOC_ROOT . '/site/gfx/' . $orbicon_x->ptr . '.header.html')) ? $orbicon_x->ptr . '.header.html' : 'header.html';
	$footer_file = (is_file(DOC_ROOT . '/site/gfx/' . $orbicon_x->ptr . '.footer.html')) ? $orbicon_x->ptr . '.footer.html' : 'footer.html';
	$navigation_file = (is_file(DOC_ROOT . '/site/gfx/' . $orbicon_x->ptr . '.navigation.html')) ? $orbicon_x->ptr . '.navigation.html' : 'navigation.html';

	if(is_file(DOC_ROOT . '/site/gfx/' . $header_file)) {
		$column_template = array_merge($column_template, array('HEADER_FILE' => min_str(file_get_contents(DOC_ROOT . '/site/gfx/' . $header_file), true)));
	}

	if(is_file(DOC_ROOT . '/site/gfx/' . $footer_file)) {
		$column_template = array_merge($column_template, array('FOOTER_FILE' => min_str(file_get_contents(DOC_ROOT . '/site/gfx/' . $footer_file), true)));
	}

	if(is_file(DOC_ROOT . '/site/gfx/' . $navigation_file)) {
		$column_template = array_merge($column_template, array('NAVIGATION_FILE' => min_str(file_get_contents(DOC_ROOT . '/site/gfx/' . $navigation_file), true)));
	}

	$get = $_GET;
	$get[$orbicon_x->ptr] = "{$get[$orbicon_x->ptr]}/html";

	$column_template = array_merge($column_template,
	array(
	'ADMIN' => include DOC_ROOT.'/orbicon/templates/admin.menu.php',
	'METATAGS' => $orbicon_x->get_html_metatags($my_column['keywords']),
	'DOMAIN_NAME' => DOMAIN_NAME,
	'DOMAIN_OWNER' => DOMAIN_OWNER,
	'DOMAIN' => DOMAIN,
	'SCHEME' => SCHEME,
	'ORBX_LN' => $orbicon_x->ptr,
	'ORBX_SITE_URL' => ORBX_SITE_URL,
	'ORBX_FULL_NAME' => ORBX_FULL_NAME,
	'NAVIGATION_V' => $orbicon_x->navigation_verti_menu(),
	'NAVIGATION_H' => $orbicon_x->navigation_horiz_menu(),
	'BREADCRUMBS' => $orbicon_x->get_breadcrumbs(),
	'COLUMN_TITLE' => $column_title,
	'COLUMN_CONTENT' => str_replace(array('<!>COLUMN_TITLE', '< !---->COLUMN_TITLE'), $column_title, $my_column['magister_content']),
	'COLUMN_DATE' => date($_SESSION['site_settings']['date_format'], $my_column['lastmod']),
	'NWS_TICKER' => '<div id="ticker"></div>',
	'TOP_INFOBOX' => $orbicon_x->display_orbicon_infobox(),
	'ALT_CONTENT_FORMATS' => $alt_content,
	'RELATED_CONTENT' => $rel_content,
	'LINKED_CONTENT' => $linked_content,
	'COLUMN_PRINT_LINK' => ORBX_SITE_URL . '/?' . http_build_query($get),
	'ORBX_BUILD' => ORBX_BUILD,
	'SEARCH_QUERY' => $_REQUEST['q'],
	'TIMESTAMP' => time(),
	/**
	 * @deprecated check if this is obsolete
	 */
	'USER_EMAIL' => $_SESSION['user.a']['email']
	));

	unset($alt_content, $rel_content, $get);

	// append modules
	global $orbx_mod;

	$column_template = array_merge($column_template, $orbx_mod->get_box_modules());

	// multiple banner types hotfix
	if($orbx_mod->validate_module('banners')) {

		require_once DOC_ROOT.'/orbicon/modules/banners/class.banners.php';
		$banners = new Banners();

		$banners_tpl = array(
			'BNNR_GRP_1' => $banners->banner_ring(BANNER_TYPE_468_X_60),
			'BNNR_GRP_2' => $banners->banner_ring(BANNER_TYPE_187_X_86),
			'BNNR_GRP_3' => $banners->banner_ring(BANNER_TYPE_244_X_86)
		);

		$column_template = array_merge($column_template, $banners_tpl);
		$banners = $banners_tpl = null;
	}

	// try to locate translated template

	if($my_column['template']) {
		$template_file = (is_file(DOC_ROOT . '/site/gfx/' . $orbicon_x->ptr . '.' . $my_column['template'])) ? $orbicon_x->ptr . '.' . $my_column['template'] : $my_column['template'];
	}
	else {
		$template_file = (is_file(DOC_ROOT . '/site/gfx/' . $orbicon_x->ptr . '.column.html')) ? $orbicon_x->ptr . '.column.html' : 'column.html';
	}

	// load in preview mode if requested
	$template_path = ($orbicon_x->get_preview_mode() && ($_GET['preview_mode'] == 'html')) ? DOC_ROOT . '/site/mercury/pre-' . $template_file : DOC_ROOT . '/site/gfx/' . $template_file;

	if($_GET[$orbicon_x->ptr] == 'mod.hpb.form') {
		require_once DOC_ROOT . '/orbicon/modules/hpb.form/h.hpbform.php';
		$template_path = get_hpbform_tpl($_REQUEST['form']);

		if(strpos($template_path, '?') !== false) {
			list($template_path, $fid) = explode('?', $template_path);
		}

		$column_template = array_merge($column_template, array('HPBFORM_FID' => $fid));
	}

	if(!is_file($template_path) || !is_readable($template_path)) {
		$template_path = DOC_ROOT . '/orbicon/controler/notemplate.html';
	}

	$orbicon_x->parse_template($template_path, $column_template);

		// multiple banner types hotfix
	if($orbx_mod->validate_module('userstats') && !get_is_search_engine_bot() && !get_is_w3c_validator() && $my_column['infogroup'] && $_SESSION['user.r']['id']) {

		require_once DOC_ROOT.'/orbicon/modules/userstats/class.userstats.php';
		$userstats = new UserStats($_SESSION['user.r']['id']);

		switch ($my_column['infogroup']) {
			case 'info': $userstats->log_info($_SERVER['REQUEST_URI'], $column_title); break;
			case 'selling': $userstats->log_selling($_SERVER['REQUEST_URI'], $column_title); break;
			case 'misc': $userstats->log_misc($_SERVER['REQUEST_URI'], $column_title); break;
		}

		$userstats = null;
	}

?>