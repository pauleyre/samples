<?php
/**
 * Text DB index
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @subpackage Magister
 * @version 1.30
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-07-01
 */
	require DOC_ROOT . '/orbicon/magister/class.magister.php';

	$hf = new Magister;
	if(isset($_GET['del_cat'])) {
		$hf->delete_category($_GET['del_cat']);
	}

	$query = explode('/', $_GET['read']);
	$query = (isset($_GET['read'])) ? $query[0] : '';

	switch($query) {
		case 'kategorija':	require DOC_ROOT . '/orbicon/magister/category.php'; break;
		default:			require DOC_ROOT . '/orbicon/magister/article.php'; break;
	}

?>