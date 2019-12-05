<?php

	
	switch($_GET['do']){

		case 'newfond':		include_once DOC_ROOT.'/orbicon/modules/invest/forms/form.fond.php';
							break;
		case 'fondvalue':	include_once DOC_ROOT.'/orbicon/modules/invest/forms/form.fvalue.php';
							break;
		default: 			include_once DOC_ROOT.'/orbicon/modules/invest/admin.overview.php';
							break;
	
	}

?>

