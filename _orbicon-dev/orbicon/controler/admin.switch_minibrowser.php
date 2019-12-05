<?php
/*-----------------------------------------------------------------------*
	Project........:	Orbicon X framework 2
	File...........:
	Version........:	1.0 (22/10/2006)
	Author.........:	Pavle Gardijan (pavle.gardijan@orbitum.net) - Orbitum d.o.o.
	Copyright......:	(c) 2006 Orbitum internet communications d.o.o. (www.orbitum.net)
	Created........:	01/07/2006
	Notes..........:
	Modified.......:
*-----------------------------------------------------------------------*/
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

	// core
	require_once DOC_ROOT . '/orbicon/class/inc.core.php';

	require_once DOC_ROOT . '/orbicon/class/class.orbicon.php';
	require_once DOC_ROOT . '/orbicon/class/class.orbicon.admin.php';

	$orbicon_x = new OrbiconX_Administration;

	$mb = null;
	$request = $_REQUEST['mini_browser'];
	$category = $_REQUEST['category'];
	$browseable = intval($_REQUEST['browseable']);
	$start = intval($_REQUEST['start']);
	$search = trim($_REQUEST['search']);

	if($request == 'magister') {

		require_once DOC_ROOT . '/orbicon/magister/class.magister.php';

		$mb = new Magister;

		if(!$browseable) {
			if(empty($category)) {
				echo $mb->get_mini_browser_categories();
			}
			else {
				echo $mb->get_mini_browser_texts($category, $start, $search, 1);
			}
		}
		else if($browseable) {
			echo $mb -> get_category_menu();
		}
	}
	else if($request == 'mercury') {
		require_once DOC_ROOT . '/orbicon/mercury/class.mercury.php';
		$mb = new Mercury;

		if(!$browseable) {
			if(empty($category)) {
				echo $mb->get_mini_browser_categories();
			}
			else {
				echo $mb->get_mini_browser_files($category, $start, $search);
			}
		}
		else if($browseable) {
			echo $mb -> get_category_menu();
		}
	}
	else if($request == 'venus') {
		require_once DOC_ROOT . '/orbicon/venus/class.venus.php';
		$mb = new Venus;

		if(!$browseable) {
			if(empty($category)) {
				echo $mb->get_mini_browser_categories();
			}
			else {
				echo $mb->get_mini_browser_images($category, $start, $search);
			}
		}
		else if($browseable) {
			echo $mb -> get_category_menu();
		}
	}

?>