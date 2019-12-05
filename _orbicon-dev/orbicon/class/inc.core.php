<?php
/**
 * General resources: settings, constants and global objects. Extremely useful for module builders
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-04-24
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

	// filesystem class
	require_once DOC_ROOT . '/orbicon/class/file/class.file.php';

	// start logger
	require_once DOC_ROOT . '/orbicon/class/class.logger.php';
	global $orbx_log;
	$orbx_log = new Logger();

	// load settings
	require_once DOC_ROOT . '/orbicon/class/settings.php';

	// load language tools
	require_once DOC_ROOT . '/orbicon/class/language.php';

	// setup MySQL db connection
	require_once DOC_ROOT . '/orbicon/class/class.db.mysql.php';

	global $dbc;
	$dbc = new DBC();
	$dbc->_db->connect();

	// global includes
	require_once DOC_ROOT . '/orbicon/class/inc.global.php';
	require_once DOC_ROOT . '/orbicon/class/inc.orbicon.php';

	// get settings in db
	require_once DOC_ROOT . '/orbicon/class/class.settings.php';
	$settings = new Settings();
	$settings->build_site_settings(true);
	$settings = null;

	// load Orbicon engine
	require_once DOC_ROOT . '/orbicon/class/class.orbicon.php';
	require_once DOC_ROOT . '/orbicon/class/class.orbicon.admin.php';

	global $orbicon_x;
	if(get_is_admin()) {
		$orbicon_x = new OrbiconX_Administration();
	}
	else {
		$orbicon_x = new OrbiconX();
	}

	// load modules
	global $orbx_mod;
	require_once DOC_ROOT . '/orbicon/class/class.module.php';
	$orbx_mod = new Module();

	global $ln, $orbx_ln;
	// include language
	include_once DOC_ROOT . '/orbicon/languages/' . $orbicon_x->ptr . '.php';

	$ln = array_merge($orbx_mod->get_translations(), $ln);

	if(get_is_admin()) {
		$ln = array_merge($ln, $orbx_ln);
	}
	else {
		unset($orbx_ln);
	}

?>