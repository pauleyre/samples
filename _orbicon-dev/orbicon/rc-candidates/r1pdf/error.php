<?php

	if($_GET['task'] == 'doc_exists' && !empty($_GET['data']))
	{
		(string) $sDoc = str_replace('/', '-', $_GET['data']);
		$time = explode("-", $sDoc);

		$time[1] = str_pad($time[1], 2, "0", STR_PAD_LEFT);

		if(is_file("pdf_bills/$time[2]/$time[1]/$sDoc.pdf")){
			echo 'passed';
		}
	}

?>