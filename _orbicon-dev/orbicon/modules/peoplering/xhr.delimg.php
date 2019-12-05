<?php
/**
 * Delete images
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package SystemMOD
 * @subpackage Peoplering
 * @version 1.00
 * @link http://
 * @license http://
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

	if($_SESSION['user.r']) {
		// includes
		include_once DOC_ROOT . '/orbicon/modules/peoplering/class/class.peoplering.php';
		$img = $_REQUEST['img'];
		$user = strtolower($_REQUEST['user']);

		$pr = new Peoplering($_SESSION['user.r']);
		$username = $pr->get_username($_SESSION['user.r']['id']);
		$username = strtolower($username['username']);
		$pr = null;

		if(($user == $username) && get_is_member()) {

			require_once DOC_ROOT.'/orbicon/venus/class.venus.php';
			$venus = new Venus();
			$db_img = $venus->get_image($img);

			if($db_img['category'] == "pring_u_$user")  {
				$venus->delete_file($img);
			}

			$venus = null;
		}
		else {
			echo _L('pr-gall-nodel');
		}
	}
	else {
		echo _L('pr-needlogin');
	}

?>