<?php
/**
 * Frontend rendering
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @subpackage Estate
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-09-10
 */

	/*----- ATTENTION! -------------------------------------------------
	 | Include before wherever DOC_ROOT required.
	 |------------------------------------------------------------------*/
	if(!defined('DOC_ROOT')) {
		// we'll need this info
		$dir = dirname(dirname(__FILE__));

		$file = $dir . '/index.php';
		$license = $dir . '/license.php';
		$found = false;

		while(!$found) {

			$dir = dirname(dirname($file));

			$license = $dir . '/license.php';
			$file = $dir . '/index.php';

			if(is_file($file) && is_file($license)) {
				$found = true;
				break;
			}
		}

		$request_uri = $_SERVER['REQUEST_URI'];

		$left = str_replace($dir, '', dirname(__FILE__));
		$left = str_replace('\\', '/', $left);
		$left = str_replace($left, '', $request_uri);
		// get rid of query
		$left = explode('?', $left);
		// file or no file?
		$left = (strpos($left[0], '.php')) ? dirname($left[0]) : $left[0];
		// strip forward slash as well
		$left = (substr($left, -1, 1) == '/') ? substr($left, 0, -1) : $left;

		define('ORBX_URI_PATH', $left);
		define('DOC_ROOT', $dir);
		unset($left, $request_uri);
	}
	//----- ATTENTION! ENDS -------------------------------------------------

	// core include
	require DOC_ROOT . '/orbicon/class/inc.core.php';

	// estate library
	require DOC_ROOT . '/orbicon/modules/estate/inc.estate.php';

	if(!get_is_member() || !$_REQUEST['user_rid']) {
		echo _L('e.flag_login');
		return;
	}

	if(get_estate_ad_already_flagged($_REQUEST['ad_id'], $_REQUEST['user_rid'], $_REQUEST['type'])) {
		echo _L('e.flag_once');
		return;
	}

	add_estate_user_flag(intval($_REQUEST['ad_id']), $_REQUEST['user_rid'], $_REQUEST['type']);
	echo _L('e.flag_thx');
?>