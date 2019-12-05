<?php
/**
 * Mercury uploader
 *
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconFE
 * @subpackage Mercury
 * @version 1.5
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-05-01
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

	require DOC_ROOT . '/orbicon/mercury/class.mercury.php';
	$mercury = new Mercury;

	$valid_upload = get_is_valid_ajax_id($_GET['credentials']);

	if($valid_upload) {
		$uploaded_files = $mercury->add_document();
		echo implode('<br>', $uploaded_files);
	}

	$uploaded_files = array_map('_sync_cache_prepend_path_mercury', $uploaded_files);
	update_sync_cache_list($uploaded_files);
	
?>