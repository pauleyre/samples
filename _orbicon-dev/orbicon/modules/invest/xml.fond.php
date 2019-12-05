<?php
/**
 * Prints XML file for use with FusionCharts
 * @author Pavle Gardijan
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-05-26
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

	// include resources
	require_once DOC_ROOT . '/orbicon/class/inc.core.php';
	require_once DOC_ROOT . '/orbicon/modules/invest/class/chart.class.php';

	// * call fond class
	require_once DOC_ROOT . '/orbicon/modules/invest/class/stock.class.php';
	require_once DOC_ROOT . '/orbicon/modules/invest/class/fond.class.php';

	$fond = $_REQUEST['fond'];

	// output to browser
	header('Content-Type: text/xml', true);

	$f = new Fond();
	$s = new Stock();
	$info = $s->get_latest_info($fond);

	$r = $f->get_fond($info['fond']);
	$a = $dbc->_db->fetch_assoc($r);

	echo '<?xml version="1.0" encoding="utf-8"?>
<data>
    <ident></ident>
    <name>HPB '.$a['title'].'</name>
    <description></description>
    <type></type>
    <date>'.$info['date'].'</date>
    <value>'.$info['stock_value'].'</value>
    <last_change>'.$info['date'].' 16:00:00</last_change>
    <total_assets_value>'.$info['stock_value'].'</total_assets_value>
</data>';

?>