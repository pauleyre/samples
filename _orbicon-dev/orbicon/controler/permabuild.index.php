<?php

/**
 * Template for index.php files that are copied in true permalinks enviroment
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-07-01
 */

	// Look for index.php and license.php ...

	// we'll need this info
	$original = __FILE__;

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

	require $file;

?>