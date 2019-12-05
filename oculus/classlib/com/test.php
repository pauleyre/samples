<?php
	require("class.com.word.php");

	(object) $oWord = new MSWord;
	$oWord -> MSWord_NewDocument();
	$oWord -> MSWord_WriteText("huber je mogao biti dobra firma");
	$oWord -> MSWord_SaveAs("c:\\test.doc");
	$oWord -> MSWord_Close();
	$oWord -> MSWord_Quit();

?>