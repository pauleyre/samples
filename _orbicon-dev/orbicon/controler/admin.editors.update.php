<?php
/*---------------------------------------------------------------------------------------*

	  _|_|              _|        _|                                	_|      _|
	_|    _|  _|  _|_|  _|_|_|          _|_|_|    _|_|    _|_|_|    	  _|  _|
	_|    _|  _|_|      _|    _|  _|  _|        _|    _|  _|    _|  	    _|
	_|    _|  _|        _|    _|  _|  _|        _|    _|  _|    _|  	  _|  _|
	  _|_|    _|        _|_|_|    _|    _|_|_|    _|_|    _|    _|  	_|      _|



	@Package:	Orbicon X framework 2
	@Version:	1.0 (22/10/2006)
	@Author:	Name surname (email) - Orbitum d.o.o.
	@Copyright:	(c) 2006 Orbitum internet communications d.o.o. (www.orbitum.net)
	@Created:	dd/mm/yyyy
	Notes:
	Modified:

	Description
	-----------

	Put some code description in here!

*----------------------------------------------------------------------------------------*/

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

	require_once DOC_ROOT . '/orbicon/class/class.orbicon.admin.php';

	$orbicon_x = new OrbiconX_Administration;

	require_once DOC_ROOT . '/orbicon/class/inc.orbxeditors.php';

	save_site_editor();
	delete_site_editor();
	echo display_site_editors();
?>