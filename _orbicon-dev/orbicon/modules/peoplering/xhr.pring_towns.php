<?php
/**
 * For autocomplete of user contacts
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @subpackage Peoplering
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
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

	$county = intval($_REQUEST['county']);
	$country_code = trim($_REQUEST['country_code']);

	if($county == 1) {
		$county_sql = '';
	}
	else {
		$county_sql = sprintf(' (county = %s) AND ', $dbc->_db->quote($county));
	}

	/*$q = sprintf('	SELECT 	id, town
					FROM	pring_towns
					WHERE	'.$county_sql.'
							(country = %s) AND
							(lang = %s)
					ORDER BY town',
							$dbc->_db->quote($country_code),
							$dbc->_db->quote($orbicon_x->ptr));*/
	$q = sprintf('	SELECT 	id, town
					FROM	pring_towns
					WHERE	'.$county_sql.'
							(country = %s)
					ORDER BY town',
							$dbc->_db->quote($country_code));

	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_assoc($r);
	$menu = '';

	while ($a) {
		$menu .= '<option value="'.$a['id'].'">'.$a['town'].'</option>';
		$a = $dbc->_db->fetch_assoc($r);
	}

	echo $menu;
?>