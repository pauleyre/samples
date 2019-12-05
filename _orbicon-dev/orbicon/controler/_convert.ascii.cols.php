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

	require_once DOC_ROOT . '/orbicon/class/inc.core.php';

	$r = $dbc->_db->query('SELECT * FROM '.TABLE_NEWS);
	$a = $dbc->_db->fetch_assoc($r);
	
	while($a) {
		
		$ascii_col = $orbicon_x->urlnormalize($a['permalink'], true);
		//$ascii_parent = $orbicon_x->urlnormalize($a['parent']);
		
		$q = sprintf('
						UPDATE 	'.TABLE_NEWS.'
						SET 	permalink_ascii=%s
						WHERE 	(id=%s)', 
		$dbc->_db->quote($ascii_col), 
		//$dbc->_db->quote($ascii_parent),
		$dbc->_db->quote($a['id']));
		
		$dbc->_db->query($q);
		
		$a = $dbc->_db->fetch_assoc($r);
	}

?>