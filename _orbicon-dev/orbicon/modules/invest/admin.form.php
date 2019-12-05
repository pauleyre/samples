<?php


switch($_GET['type']){

	case 'fond':		include_once DOC_ROOT.'/orbicon/modules/invest/forms/form.fond.php';
						break;
	case 'fondValue':	include_once DOC_ROOT.'/orbicon/modules/invest/forms/form.fond.php';
						break;

}


?>