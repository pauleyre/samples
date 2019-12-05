<?php

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

	// include resources
	require_once DOC_ROOT . '/orbicon/class/inc.core.php';
	require_once DOC_ROOT . '/orbicon/modules/invest/class/stock.class.php';

	$stock = new Stock;
	$latest_graph = $stock->get_latest_graph($_REQUEST['fond']);
	unset($stock);
	$latest_graph = DOC_ROOT . '/site/venus/invest/' . $latest_graph;

	// replace with default graph if it doesn't exist
	if(!is_file($latest_graph)) {
		$latest_graph = DOC_ROOT . '/orbicon/modules/invest/gfx/no_graph.png';
	}

	$img_info = getimagesize($latest_graph);

	switch($img_info[2]) {
		case IMAGETYPE_GIF: 		$ext = 'GIF'; break;
		case IMAGETYPE_JPEG: 		$ext = 'JPG'; break;
		case IMAGETYPE_PNG: 		$ext = 'PNG'; break;
		case IMAGETYPE_SWF: 		$ext = 'SWF'; break;
		case IMAGETYPE_PSD: 		$ext = 'PSD'; break;
		case IMAGETYPE_BMP: 		$ext = 'BMP'; break;
		case IMAGETYPE_TIFF_II:		$ext = 'TIFF'; break; // (intel byte order)
		case IMAGETYPE_TIFF_MM: 	$ext = 'TIFF'; break; // (motorola byte order)
		case IMAGETYPE_JPC: 		$ext = 'JPC'; break;
		case IMAGETYPE_JP2: 		$ext = 'JP2'; break;
		case IMAGETYPE_JPX: 		$ext = 'JPX'; break;
		case IMAGETYPE_JB2: 		$ext = 'JB2'; break;
		case IMAGETYPE_SWC: 		$ext = 'SWC'; break;
		case IMAGETYPE_IFF: 		$ext = 'IFF'; break;
		case IMAGETYPE_WBMP: 		$ext = 'WBMP'; break;
		case IMAGETYPE_XBM: 		$ext = 'XBM'; break;
		default: 					$ext = str_replace('.', '', strrchr($image, '.'));
	}

	$ext = strtolower($ext);

	switch($ext) {
		case 'jpeg': $ext = 'jpg'; break;
		case 'tiff': $ext = 'tif'; break;
	}

	$mime = get_mime_by_ext($ext);

	/* output to browser*/
	header('Content-Type: ' . $mime);
	if(!strpos(strtolower(ORBX_USER_AGENT), 'msie') === false) {
		header('HTTP/1.x 205 OK', true);
	}
	else {
		header('HTTP/1.x 200 OK', true);
	}

	header('Pragma: no-cache');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	header('Cache-Control: no-cache, cachehack=' . time());
	header('Cache-Control: no-store, must-revalidate');
	header('Cache-Control: post-check=-1, pre-check=-1', false);
	// output to browser

	echo file_get_contents($latest_graph);
?>