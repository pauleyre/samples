<?php
/**
 * Downloader
 * @author Pavle Gardijan
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-07-01
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

	// setup logger
	require DOC_ROOT . '/orbicon/class/inc.core.php';

	$filename = $_GET['file'];

	// try to decode it
	if(!is_file(DOC_ROOT . '/site/mercury/' . $filename)) {
		$filename = base64_decode($filename);
	}

	$basename = basename($filename);

	if(strpos($basename, '.php') !== false) {
		return false;
	}

	$filename = DOC_ROOT . '/site/mercury/' . $basename;

	// lightttpd can use X-LIGHTTPD-send-file
	if(strpos(strtolower($_SERVER['SERVER_SOFTWARE']), 'lighttpd') !== false) {
		header('Content-Disposition: attachment; filename="' . $basename . '"');
        header('X-LIGHTTPD-send-file: ' . $filename);
        session_write_close();
        exit();
	}

	$mime = get_mime_by_ext(get_extension($basename));

	header('Pragma: public');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Cache-Control: private', false);
	header('Content-Type: '.$mime);
	header('Content-Disposition: attachment; filename=' . $basename . ';');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: '.filesize($filename));

	readfile($filename);

	session_write_close();
	exit();

?>