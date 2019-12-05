<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
//error_reporting(0);
	require('class.hf.php');
	define('IN_HF', TRUE);

	(object) $oIndex = new HF;

	$oIndex -> InitiateSession();

	require 'article-editor.php';

?>