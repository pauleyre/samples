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

	// start logger
	require DOC_ROOT . '/orbicon/class/inc.core.php';

	function add_document_gfxdir()
	{
		$files = array();

		if(!empty($_FILES['userfile']['name'][0])) {
			$path = DOC_ROOT.'/site/mercury/daily_report.pdf';
			move_uploaded_file($_FILES['userfile']['tmp_name'][0], $path);
			chmod_lock($path);
			$files[] = 'daily_report.pdf';
		}
		return $files;
	}

	$valid_upload = get_is_valid_ajax_id($_GET['credentials']);

	if($valid_upload === true) {
		$uploaded_files = add_document_gfxdir();
		echo implode('<br>', $uploaded_files);
	}

	$uploaded_files = array_map('_sync_cache_prepend_path_mercury', $uploaded_files);
	update_sync_cache_list($uploaded_files);
?>