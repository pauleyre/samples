<?php
/**
 * Test for class DirIterator
 * @author Pavle Gardijan
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-05-30
 */
	require_once 'class.diriterator.php';

	$dir = new DirIterator('.', 'dir*');
	$files = $dir->all();

	foreach ($files as $entry) {
		echo "<p>$entry</p>";
	}

?>