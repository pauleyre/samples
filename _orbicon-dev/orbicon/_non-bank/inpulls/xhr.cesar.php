<?php
/**
 * Frontend rendering
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package OrbiconMOD
 * @subpackage Inpulls
 * @version 1.00
 * @link http://www.inpulls.com
 * @license http://
 * @since 2007-12-09
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

	// inpulls library
	require DOC_ROOT . '/orbicon/modules/inpulls/inc.inpulls.php';

	$user_rid = $_REQUEST['user_rid'];
	$mobber_rid = $_REQUEST['mobber_rid'];
	$reason = trim($_REQUEST['cezar_reason']);
	$user_email = base64_decode($_REQUEST['user_connpoint']);

	if($reason) {
		$cesar = add_cesar($user_rid, $mobber_rid, $reason);

		if($cesar) {
			echo 'Još jedan cezar za ovog korisnika!';
		}
		else {
			echo 'Već ste stisnuli cezara ovom korisniku. Samo jednom!';
		}
	}
	else {
		echo 'Niste napisali razlog';
	}

	if(check_cesar_limit($user_rid)) {
		send_cesar_ban_email($user_rid, $user_email);
	}
?>