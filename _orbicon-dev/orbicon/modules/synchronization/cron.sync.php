<?php
/**
 * Exit from system
 * @author Pavle Gardijan <pavle.gardijan@hpb.hr>
 * @copyright Copyright (c) 2008, HPB d.d.
 * @package SystemFE
 * @version 1.00
 * @link http://
 * @license http://
 * @since 2006-07-01
 */
	ignore_user_abort(true);

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

	// core includes
	require_once DOC_ROOT . '/orbicon/class/inc.core.php';
	
	// create agent. we'll probably need it
	include_once DOC_ROOT . '/orbicon/3rdParty/snoopy/Snoopy.class.php';

	set_time_limit(0);
		
	$sync_master = new Snoopy;
	
	// authorize START
	$formvars = array();
	$formvars['action'] = 'authorize';
	$formvars['log'] = 'syncron';
	$formvars['pwd'] = 'Xd4pp6BnmmUZTEQ';
	$formvars['submit'] = '1';
	
	$sync_master->submit('http://wwwadmin/?hr=orbicon/authorize', $formvars);

	unset($formvars);

	// authorize END

	sleep(5);

	// sync START

	$formvars = array();
	$formvars['sync_server_id_12'] = 'ok';
	$formvars['sync_server_id_13'] = 'ok';
	$formvars['do_sync'] = '1';

	$sync_master->submit('http://wwwadmin/?hr=orbicon/mod/synchronization', $formvars);

	sleep(30);
	
	$sync_master->submit('http://wwwadmin/?hr=orbicon/mod/synchronization', $formvars);

	sleep(30);
	
	unset($formvars);

	// sync END

	// logout START

	$sync_master->fetch('http://wwwadmin/?hr=exit');

	// logout END
	
?>