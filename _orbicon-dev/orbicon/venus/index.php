<?php
/**
 * Venus index
 *
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconFE
 * @subpackage Venus
 * @version 1.5
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-05-01
 */
	require DOC_ROOT.'/orbicon/venus/class.venus.php';

	$query = explode('/', $_GET['read']);
	$query = (isset($_GET['read'])) ? $query[0] : '';

	$hf = new Venus;

	$crop = false;

	if(isset($_GET['cropimage'])) {
		$hf->crop_image();
		$crop = true;
	}

	if(!$crop) {
		if(isset($_GET['del_cat'])) {
			$hf->delete_category($_GET['del_cat']);
		}

		if(isset($_GET['del_file'])) {
			$hf->delete_file($_GET['del_file']);
		}

		switch($query) {
			case 'expo':	require DOC_ROOT.'/orbicon/venus/expo.php';	break;
			default:		require DOC_ROOT.'/orbicon/venus/index2.php'; break;
		}
	}
?>