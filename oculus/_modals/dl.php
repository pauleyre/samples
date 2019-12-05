<?php

	define("DIR_ARHIVA", $_SERVER['DOCUMENT_ROOT']."/is/radni_nalozi/");

	require($_SERVER["DOCUMENT_ROOT"]."/classlib/public/classlib.php");
	error_reporting(0);
	(object) $oDownload = new ClassLib;
	$oDownload -> ForceDownload();
?>