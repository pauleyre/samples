<?php
/*-----------------------------------------------------------------------*
	Project........:	Orbicon X framework 2
	File...........:	admin.polls.live_date.php
	Version........:	1.1 (22-10-2006)
	Author.........:	Pavle Gardijan (pavle.gardijan@orbitum.net) - Orbitum d.o.o.
	Copyright......:	(c) 2006 Orbitum internet communications d.o.o. (www.orbitum.net)
	Created........:	04-06-2006
	Notes..........:	AJAX date parser for YUI combo calendar used in polls
	Modified.......:	* 1.1 / 22-10-2006 / Pavle Gardijan - fixed bug #8
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


	// core include
	require_once DOC_ROOT . '/orbicon/class/inc.core.php';

	// fix wrong order of dates
	// convert to timestamp and sort them
	function fix_yui_date_order($date)
	{
		if(strpos($date, '+') !== false) {
			$date = substr($date, 0, strpos($date, '+'));
		}
		return strtotime($date);
	}

	$date = explode(',', $_REQUEST['live_date']);
	$date = array_map('fix_yui_date_order', $date);
	sort($date);

	// start
	echo $date[0].'|'.date($_SESSION['site_settings']['date_format'], $date[0]);

	// if set
	if(!empty($date[1])) {
		// separator
		echo '*';
		// end
		echo $date[1].'|'.date($_SESSION['site_settings']['date_format'], $date[1]);
	}

?>