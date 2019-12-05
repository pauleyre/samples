<?php
/**
 * File for rendering homepage
 * @author Pavle Gardijan
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-07-01
 */

	$orbicon_x_template = array();

	$header_file = (is_file(DOC_ROOT . '/site/gfx/' . $orbicon_x->ptr . '.header.html')) ? $orbicon_x->ptr . '.header.html' : 'header.html';
	$footer_file = (is_file(DOC_ROOT . '/site/gfx/' . $orbicon_x->ptr . '.footer.html')) ? $orbicon_x->ptr . '.footer.html' : 'footer.html';
	$navigation_file = (is_file(DOC_ROOT . '/site/gfx/' . $orbicon_x->ptr . '.navigation.html')) ? $orbicon_x->ptr . '.navigation.html' : 'navigation.html';

	if(is_file(DOC_ROOT . '/site/gfx/' . $header_file)) {
		$orbicon_x_template = array_merge($orbicon_x_template, array('HEADER_FILE' => min_str(file_get_contents(DOC_ROOT . '/site/gfx/' . $header_file), true)));
	}

	if(is_file(DOC_ROOT . '/site/gfx/' . $footer_file)) {
		$orbicon_x_template = array_merge($orbicon_x_template, array('FOOTER_FILE' => min_str(file_get_contents(DOC_ROOT . '/site/gfx/' . $footer_file), true)));
	}

	if(is_file(DOC_ROOT . '/site/gfx/' . $navigation_file)) {
		$orbicon_x_template = array_merge($orbicon_x_template, array('NAVIGATION_FILE' => min_str(file_get_contents(DOC_ROOT . '/site/gfx/' . $navigation_file), true)));
	}

	$orbicon_x_template = array_merge($orbicon_x_template,
	array(
	'ADMIN' => include DOC_ROOT.'/orbicon/templates/admin.menu.php',
	'METATAGS' => $orbicon_x->get_html_metatags($_SESSION['site_settings']['main_site_keywords']),
	'DOMAIN_OWNER' => DOMAIN_OWNER,
	'DOMAIN_NAME' => DOMAIN_NAME,
	'DOMAIN' => DOMAIN,
	'SCHEME' => SCHEME,
	'ORBX_LN' => $orbicon_x->ptr,
	'ORBX_SITE_URL' => ORBX_SITE_URL,
	'ORBX_FULL_NAME' => ORBX_FULL_NAME,
	'NAVIGATION_V' => $orbicon_x->navigation_verti_menu(),
	'NAVIGATION_H' => $orbicon_x->navigation_horiz_menu(),
	'NWS_TICKER' => '<div id="ticker"></div>',
	'TOP_INFOBOX' => $orbicon_x->display_orbicon_infobox(),
	'ORBX_BUILD' => ORBX_BUILD,
	'SEARCH_QUERY' => $_REQUEST['q'],
	'TIMESTAMP' => time(),
	/**
	 * @deprecated check if this is obsolete
	 */
	'USER_EMAIL' => $_SESSION['user.a']['email']
	));

	// append modules
	global $orbx_mod;
	$orbicon_x_template = array_merge($orbicon_x_template, $orbx_mod->get_box_modules());

	// multiple banner types hotfix
	if($orbx_mod->validate_module('banners')) {

		require_once DOC_ROOT . '/orbicon/modules/banners/class.banners.php';
		$banners = new Banners();

		$banners_tpl = array(
			'BNNR_GRP_1' => $banners->banner_ring(BANNER_TYPE_468_X_60),
			'BNNR_GRP_2' => $banners->banner_ring(BANNER_TYPE_187_X_86),
			'BNNR_GRP_3' => $banners->banner_ring(BANNER_TYPE_244_X_86)
		);

		$orbicon_x_template = array_merge($orbicon_x_template, $banners_tpl);
		$banners = $banners_tpl = null;
	}

	// try to locate translated template
	$template_file = (is_file(DOC_ROOT . '/site/gfx/' . $orbicon_x->ptr . '.home.html')) ? $orbicon_x->ptr . '.home.html' : 'home.html';

	// load in preview mode if requested
	$template_path = ($orbicon_x->get_preview_mode() && ($_GET['preview_mode'] == 'html')) ? DOC_ROOT.'/site/mercury/pre-' . $template_file : DOC_ROOT.'/site/gfx/' . $template_file;

	if(!is_file($template_path) || !is_readable($template_path)) {
		$template_path = DOC_ROOT . '/orbicon/controler/notemplate.html';
	}

	$orbicon_x->parse_template($template_path, $orbicon_x_template);
?>