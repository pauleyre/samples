<?php

	/*----- ATTENTION! -------------------------------------------------
	 | Include before wherever DOC_ROOT required.
	 |------------------------------------------------------------------*/
	if(!defined('DOC_ROOT')) {
		// we'll need this info
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

		$request_uri = $_SERVER['REQUEST_URI'];

		$left = str_replace($dir, '', dirname(__FILE__));
		$left = str_replace('\\', '/', $left);
		$left = str_replace($left, '', $request_uri);
		// get rid of query
		$left = explode('?', $left);
		// file or no file?
		$left = (strpos($left[0], '.php')) ? dirname($left[0]) : $left[0];
		// strip forward slash as well
		$left = (substr($left, -1, 1) == '/') ? substr($left, 0, -1) : $left;

		define('ORBX_URI_PATH', $left);
		define('DOC_ROOT', $dir);
		unset($left, $request_uri);
	}
	//----- ATTENTION! ENDS -------------------------------------------------

	// core include
	require DOC_ROOT . '/orbicon/class/inc.core.php';

	// form header
	require DOC_ROOT . '/orbicon/modules/hpb.form/h.hpbform.php';
	require DOC_ROOT . '/orbicon/modules/hpb.form/inc.hpbform.php';

	$inc = '';
	$hpb_form = basename($_REQUEST['pdf']);


	$form_path = DOC_ROOT . '/orbicon/modules/hpb.form/pdf/' . $pdf;

	switch($hpb_form) {

		case TABLE_G_TEKUCI:
			require DOC_ROOT . '/orbicon/modules/hpb.form/form/inc.tekuci.php';
			xml_pdf($_REQUEST['fid']);
		break;

		case TABLE_P_KREDIT:
			require DOC_ROOT . '/orbicon/modules/hpb.form/form/inc.posl_kredit.php';
			xml_pdf($_REQUEST['fid']);
		break;

		case TABLE_P_BRZI:
			require DOC_ROOT . '/orbicon/modules/hpb.form/form/inc.brzi_kredit.php';
			xml_pdf($_REQUEST['fid']);
		break;

		case TABLE_P_ESKONT:
			require DOC_ROOT . '/orbicon/modules/hpb.form/form/inc.eskont_mjenice.php';
			xml_pdf($_REQUEST['fid']);
		break;

		case TABLE_P_AKREDITIV:
			require DOC_ROOT . '/orbicon/modules/hpb.form/form/inc.akreditiv.php';
			xml_pdf($_REQUEST['fid']);
		break;

		case TABLE_G_MC_ZLATNA_OSN:
			require DOC_ROOT . '/orbicon/modules/hpb.form/form/inc.gradj_zlatna.php';
			xml_pdf($_REQUEST['fid']);
		break;

		case TABLE_G_MC_ZLATNA_DOD:
			require DOC_ROOT . '/orbicon/modules/hpb.form/form/inc.gradj_zlatna.php';
			xml_pdf2($_REQUEST['fid']);
		break;

		case TABLE_P_PR1_VB:
			require DOC_ROOT . '/orbicon/modules/hpb.form/form/inc.visa_business.php';
			xml_pdf($_REQUEST['fid']);
		break;

		case TABLE_P_PR2_VB:
			require DOC_ROOT . '/orbicon/modules/hpb.form/form/inc.visa_business.php';
			xml_pdf2($_REQUEST['fid']);
		break;

		case TABLE_G_MC_OSN:
			require DOC_ROOT . '/orbicon/modules/hpb.form/form/inc.gradj_mc.php';
			xml_pdf($_REQUEST['fid']);
		break;

		case TABLE_G_MC_DOD:
			require DOC_ROOT . '/orbicon/modules/hpb.form/form/inc.gradj_mc.php';
			xml_pdf2($_REQUEST['fid']);
		break;

		case TABLE_P_PR1_VBP:
			require DOC_ROOT . '/orbicon/modules/hpb.form/form/inc.visa_bonus_plus.php';
			xml_pdf($_REQUEST['fid']);
		break;

		case TABLE_P_PR2_VBP:
			require DOC_ROOT . '/orbicon/modules/hpb.form/form/inc.visa_bonus_plus.php';
			xml_pdf2($_REQUEST['fid']);
		break;

		case TABLE_G_IB:
			require DOC_ROOT . '/orbicon/modules/hpb.form/form/inc.gradj_ib.php';
			xml_pdf($_REQUEST['fid']);
		break;

		case TABLE_G_OSTALO_IB:
			require DOC_ROOT . '/orbicon/modules/hpb.form/form/inc.gradj_ib_ostalo.php';
			xml_pdf($_REQUEST['fid']);
		break;

		case TABLE_P_VE:
			require DOC_ROOT . '/orbicon/modules/hpb.form/form/inc.visa_electron.php';
			xml_pdf($_REQUEST['fid']);
		break;

		case TABLE_P_PRA_IB:
			require DOC_ROOT . '/orbicon/modules/hpb.form/form/inc.posl_ib.php';
			xml_pdf($_REQUEST['fid']);
		break;

		case TABLE_P_PRB_IB:
			require DOC_ROOT . '/orbicon/modules/hpb.form/form/inc.posl_ib.php';
			xml_pdf2($_REQUEST['fid']);
		break;

		case TABLE_P_GARANCIJA:
			require DOC_ROOT . '/orbicon/modules/hpb.form/form/inc.garancija.php';
			xml_pdf($_REQUEST['fid']);
		break;

		case TABLE_G_SMS:
			require DOC_ROOT . '/orbicon/modules/hpb.form/form/inc.gradj_sms.php';
			xml_pdf($_REQUEST['fid']);
		break;

		case TABLE_G_KREDIT:
			require DOC_ROOT . '/orbicon/modules/hpb.form/form/inc.gradj_kredit.php';
			xml_pdf($_REQUEST['fid']);
		break;

		case TABLE_G_KREDIT_TRAZITELJ:
			require DOC_ROOT . '/orbicon/modules/hpb.form/form/inc.gradj_kredit.php';
			xml_pdf2($_REQUEST['fid']);
		break;

		default:
				echo '<script type="text/javascript">alert(\'Greška: Nepoznati obrazac\')</script>';
				return;

	}

?>