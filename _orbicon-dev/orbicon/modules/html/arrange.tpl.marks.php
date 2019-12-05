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
	require_once DOC_ROOT . '/orbicon/class/inc.core.php';

	if(!get_is_valid_ajax_id($_REQUEST['credentials'])) {
		echo 'credentials error';
		return;
	}

	$html_dir = DOC_ROOT.'/site/gfx/';
	$html_files = glob($html_dir . '*.html');

	foreach($html_files as $template) {

		$basename = basename($template);

		if((strpos($basename, 'home.html') !== false) || (strpos($basename, 'column.html') !== false)) {
			$content = file_get_contents($template);

			if(strpos($content, '<!>METATAGS') === false) {
				$content = str_replace('</title>', "</title>\n<!>METATAGS\n", $content);
			}

			if(strpos($content, '<!>ADMIN') === false) {
				$content = str_replace('<body>', "<body>\n<!>ADMIN\n", $content);
			}

			if(strpos($content, '<!>TOP_INFOBOX') === false) {
				$content = str_replace('<body>', "<body>\n<!>TOP_INFOBOX\n", $content);
			}

			chmod_unlock($template);

			if(!lock($template)) {
				echo 'lock error';
			}

			$r = fopen($template, 'wb');
			if(!fwrite($r, $content)) {
				echo 'write error';
			}

			fclose($r);

			unlock($template);

			chmod_lock($template);

			if(strpos(file_get_contents($template), '<!>ADMIN') === false) {
				echo 'write error';
			}
		}
	}

	echo 'done';

?>