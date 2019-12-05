<?php
/**
 * Patch maker
 *
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconTOOLS
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-08-30
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
		$left = (substr($left, -1, 1) === '/') ? substr($left, 0, -1) : $left;

		define('ORBX_URI_PATH', $left);
		define('DOC_ROOT', $dir);
		unset($left, $request_uri);
	}
	//----- ATTENTION! ENDS -------------------------------------------------

	// start logger
	require DOC_ROOT . '/orbicon/class/inc.core.php';

	require DOC_ROOT . '/orbicon/lib/auto88/class.auto88.php';

	if(isset($_POST['make'])) {
		$ini = new Version_Ini(DOC_ROOT . '/site/mercury/version.orbicon.ini');
		$ini->add_version($_POST['ver'], DOC_ROOT . '/' . $_POST['ver']);

		$file = $_POST['source'];
		$max = count($file);

		for($i = 0; $i < $max ; $i++) {
			if(!empty($_POST['source'][$i])) {
				$ini->add_version_file($_POST['ver'], DOC_ROOT . '/' . $_POST['source'][$i], $_POST['target'][$i]);
			}
		}
		$ini->parse_version_ini();
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>makePatch</title>
</head>

<body>
<form method="post">
	version<br />
	<input type="text" id="ver" name="ver" /> <br />

	<fieldset>
	<legend>Files</legend>

		source : <input type="text" name="source[]" /> target : <input type="text" name="target[]" /><br />
		source : <input type="text" name="source[]" /> target : <input type="text" name="target[]" /><br />
		source : <input type="text" name="source[]" /> target : <input type="text" name="target[]" /><br />
		source : <input type="text" name="source[]" /> target : <input type="text" name="target[]" /><br />
		source : <input type="text" name="source[]" /> target : <input type="text" name="target[]" /><br />
		source : <input type="text" name="source[]" /> target : <input type="text" name="target[]" /><br />

	</fieldset>

	<input type="submit" id="make" name="make" />
</form>

</body>
</html>
