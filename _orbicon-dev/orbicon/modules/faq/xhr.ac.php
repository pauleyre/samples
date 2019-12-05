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

	// start logger
	require DOC_ROOT . '/orbicon/class/inc.core.php';

	// * suggest
	(string) $current_query = $_REQUEST['query'];
	$current_query = trim($current_query);

	if($current_query != '') {
		global $dbc, $orbicon_x, $orbx_mod;

		$r = sql_res('SELECT 	title
						FROM 	orbx_mod_faq_question
						WHERE 	(title LIKE %s)	AND
							 	(live = 1) AND
							 	(email_notify = 0)
						LIMIT 	10',
						"%$current_query%");

		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$suggestions[] = trim(utf8_html_entities($a['title'], true));
			$a = $dbc->_db->fetch_assoc($r);
		}
	}

	// make it unique
	if(count($suggestions) > 1) {
		$suggestions = array_unique($suggestions);
	}

	if(empty($suggestions)) {
		return '';
	}

	echo implode("\n", $suggestions);

?>