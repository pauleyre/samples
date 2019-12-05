<?php
/**
 * Install AJAX
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-07-01
 */

	ignore_user_abort(true);
	set_time_limit(0);

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

	require DOC_ROOT . '/orbicon/class/inc.core.php';

	require DOC_ROOT.'/orbicon/class/class.install.php';

	// orbicon installed - exit
	if(defined('ORBX_INSTALL_TYPE')) {
		return false;
	}

	$setup_handler = new Orbicon_Install;
	$step = intval($_REQUEST['step']);

	// ftp chmod
	if($step == 1) {
		$ftp = array(
			'host' => $_REQUEST['ftp_host'],
			'username' => $_REQUEST['ftp_username'],
			'password' => $_REQUEST['ftp_pwd'],
			'rootdir' => $_REQUEST['ftp_rootdir'],
			'type' => $_REQUEST['ftp_type']
		);

		$ftp = array_map('trim', $ftp);
		$status = $setup_handler->install_make_writable_ftp($ftp);
	}
	// mysql
	else if($step == 2) {
		$mysql = array(
			'host' => $_REQUEST['mysql_host'],
			'username' => $_REQUEST['mysql_username'],
			'password' => $_REQUEST['mysql_pwd'],
			'db' => $_REQUEST['mysql_dbname'],
			'perma' => 0
		);

		$sysadmin = array(
			'username' => strtolower($_REQUEST['sysadmin_username']),
			'password' => $_REQUEST['sysadmin_pwd']
		);

		$mysql = array_map('trim', $mysql);
		$sysadmin = array_map('trim', $sysadmin);

		$status = $setup_handler->install_mysql_data($mysql, $sysadmin);
	}
	// chmod
	else {
		$status = $setup_handler->install_make_writable();
	}
	echo '<input type="hidden" value="'.intval($status).'" id="__setup_status" />';
	echo implode('<br />', $setup_handler->install_log);
	ignore_user_abort(false);
?>