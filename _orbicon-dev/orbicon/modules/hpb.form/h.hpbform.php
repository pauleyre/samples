<?php

define('TABLE_OPUNOMOCENIK', 'hpb_forms_opunomocenik');
define('TABLE_HPB_RACUN', 'hpb_forms_podaci_racun');
define('TABLE_HPB_FORMS', 'hpb_forms');
define('TABLE_HPB_REGISTRATION', 'registracija');

define('TABLE_G_KREDIT', 'hpb_form_gradj_kredit');
define('TABLE_G_KREDIT_TRAZITELJ', 'hpb_form_gradj_kredit_trazitelj');
define('TABLE_G_LJETO', 'hpb_form_gradj_ljeto_hpb');
define('TABLE_G_MC_DOD', 'hpb_form_gradj_mc_dodatni');
define('TABLE_G_MC_ZLATNA_DOD', 'hpb_form_gradj_mc_zlatna_dodatni');
define('TABLE_G_MC_ZLATNA_OSN', 'hpb_form_gradj_mc_zlatna_osnovni');
define('TABLE_G_MC_OSN', 'hpb_form_gradj_mc_osnovni');
define('TABLE_G_OROCEN', 'hpb_form_gradj_orocen_depozit');
define('TABLE_G_OSTALO_IB', 'hpb_form_gradj_ostali_zahtjevi_ib');
define('TABLE_G_TEKUCI', 'hpb_form_gradj_otvaranje_tekuceg');
define('TABLE_G_RACUN', 'hpb_form_gradj_podaci_racun');
define('TABLE_G_POLJO', 'hpb_form_gradj_poljoprivrednici');
define('TABLE_G_PREKO', 'hpb_form_gradj_povecanje_prekoracenja');
define('TABLE_G_IB', 'hpb_form_gradj_pristupnica_ib');
define('TABLE_G_RODILJ', 'hpb_form_gradj_rodiljne_naknade');
define('TABLE_G_SMS', 'hpb_form_gradj_sms');
define('TABLE_G_STUD', 'hpb_form_gradj_studenti');
define('TABLE_G_TRAJNI', 'hpb_form_gradj_trajni_nalog');
define('TABLE_G_USTUP', 'hpb_form_gradj_ustupanje_radi_osiguranja');
define('TABLE_G_ZAPLJENA_PRIMANJA', 'hpb_form_gradj_zapljena_primanja');
define('TABLE_G_ZAPLJENA_RACUNA', 'hpb_form_gradj_zapljena_racuna');

define('TABLE_I_KUPNJA', 'hpb_form_invest_kupnja_udjela');
define('TABLE_I_PRIJENOS', 'hpb_form_invest_prijenos_udjela');
define('TABLE_I_PRODAJA', 'hpb_form_invest_prodaja_udjela');

define('TABLE_N_PROCJENA', 'hpb_form_nekretnine_procjena');

define('TABLE_P_AKREDITIV', 'hpb_form_posl_akreditiv');
define('TABLE_P_ESKONT', 'hpb_form_posl_eskont_mjenica');
define('TABLE_P_GARANCIJA', 'hpb_form_posl_garancija');
define('TABLE_P_IMOVINA', 'hpb_form_posl_imovina');
define('TABLE_P_IZJAVA_POV', 'hpb_form_posl_izjava_povezanosti');
define('TABLE_P_KREDIT', 'hpb_form_posl_kredit');
define('TABLE_P_OBVEZE', 'hpb_form_posl_obveze_dobavljacima');
define('TABLE_P_PLAN_PR', 'hpb_form_posl_plan_prihoda_rashoda');
define('TABLE_P_POTP_KARTON', 'hpb_form_posl_potpisni_karton');
define('TABLE_P_POTRAZ', 'hpb_form_posl_potrazivanja_kupci');
define('TABLE_P_PR1_VBP', 'hpb_form_posl_pr1_visa_bonus_plus');
define('TABLE_P_PR1_VB', 'hpb_form_posl_pr1_visa_business');
define('TABLE_P_PR2_VBP', 'hpb_form_posl_pr2_visa_bonus_plus');
define('TABLE_P_PR2_VB', 'hpb_form_posl_pr2_visa_business');
define('TABLE_P_PRA_IB', 'hpb_form_posl_pr_a_ib');
define('TABLE_P_PRB_IB', 'hpb_form_posl_pr_b_ib');
define('TABLE_P_VE', 'hpb_form_posl_pr_visa_electron');
define('TABLE_P_PLATNI', 'hpb_form_posl_racun_platni_promet');
define('TABLE_P_KRED_ZAD1', 'hpb_form_posl_stanje_kreditne_zaduzenosti_1');
define('TABLE_P_KRED_ZAD2', 'hpb_form_posl_stanje_kreditne_zaduzenosti_2');
define('TABLE_P_TRANS_RAC', 'hpb_form_posl_trans_racun');
define('TABLE_P_BRZI', 'hpb_form_posl_zahtjev_brzi_kredit');

function get_hpbform_tpl($form, $loc_only = false)
{
	global $orbx_mod;
	$basename = 'tpl.html';
	$tpl = '';
	$inc = '';
	$loc = '';

	switch($form) {

		case TABLE_G_TEKUCI:
			$tpl = 'zahtjev_za_otvaranje_tekuceg_racuna';
			$inc = 'inc.tekuci.php';
			$loc = 'Zahtjev za otvaranje tekućeg računa u Hrvatskoj poštanskoj banci d.d.';
		break;

		case TABLE_P_KREDIT:
			$tpl = 'posl_zahtjev_za_kredit';
			$inc = 'inc.posl_kredit.php';
			$loc = 'Zahtjev za kredit';
		break;

		case TABLE_P_BRZI:
			$tpl = 'brzi-kredit';
			$inc = 'inc.brzi_kredit.php';
			$loc = 'Zahtjev za BRZI kredit';
		break;

		case TABLE_P_ESKONT:
			$tpl = 'zahtjev_za_eskont_mjenica';
			$inc = 'inc.eskont_mjenice.php';
			$loc = 'Zahtjev za eskont mjenica';
		break;

		case TABLE_P_AKREDITIV:
			$tpl = 'zahtjev_za_akreditiv';
			$inc = 'inc.akreditiv.php';
			$loc = 'Zahtjev za akreditiv';
		break;

		case TABLE_G_MC_ZLATNA_OSN:
			$tpl = 'gradj_zlatna_mc_kartica';
			$inc = 'inc.gradj_zlatna.php';
			$loc = 'Pristupnica za osnovnog korisnika zlatne Mastercard kartice';
		break;

		case TABLE_P_PR1_VB:
			$tpl = 'visa_business';
			$inc = 'inc.visa_business.php';
			$loc = 'Pristupnica Visa business kartica 1';
		break;

		case TABLE_G_KREDIT:
			$tpl = 'gradj_kredit';
			$inc = 'inc.gradj_kredit.php';
			$loc = 'Zahtjev za kredit';
		break;

		case TABLE_G_MC_OSN:
			$tpl = 'gradj_mc';
			$inc = 'inc.gradj_mc.php';
			$loc = 'Pristupnica za osnovnog korisnika MasterCard kartice';
		break;

		case TABLE_HPB_REGISTRATION:
			$tpl = 'registracija';
			$inc = 'inc.reg.php';
			$loc = 'Registracija';
		break;

		case TABLE_P_PR1_VBP:
			$tpl = 'visa_bonus_plus';
			$inc = 'inc.visa_bonus_plus.php';
			$loc = 'Pristupnica Visa Bonus plus kartica 1';
		break;

		case TABLE_G_IB:
			$tpl = 'gradj_ib';
			$inc = 'inc.gradj_ib.php';
			$loc = 'HPB Internet bankarstvo za građanstvo';
		break;

		case TABLE_G_OSTALO_IB:
			$tpl = 'gradj_ib_ostalo';
			$inc = 'inc.gradj_ib_ostalo.php';
			$loc = 'Ostali zahtjevi vezani uz HPB Internet bankarstvo za građanstvo';
		break;

		case TABLE_P_VE:
			$tpl = 'visa_electron';
			$inc = 'inc.visa_electron.php';
			$loc = 'Pristupnica Visa Business electron kartica';
		break;

		case TABLE_P_PRA_IB:
			$tpl = 'posl_ib';
			$inc = 'inc.posl_ib.php';
			$loc = 'Pristupnica za korištenje HPB Internet bankarstva za poslovne subjekte (A - Poslovni subjekt)';
		break;

		case TABLE_G_SMS:
			$tpl = 'gradj_sms';
			$inc = 'inc.gradj_sms.php';
			$loc = 'Pristupnica za uporabu HPB SMS usluga';
		break;

		case TABLE_P_GARANCIJA:
			$tpl = 'zahtjev_za_garanciju';
			$inc = 'inc.garancija.php';
			$loc = 'Zahtjev za garanciju';
		break;

		// localization only

		case TABLE_P_PRB_IB:
			$loc = 'Pristupnica za korištenje HPB Internet bankarstva za poslovne subjekte (B - Poslovni subjekt)';
		break;

		case TABLE_G_KREDIT_TRAZITELJ:
			$loc = 'Za tražitelja kredita (Obrazac B)';
		break;

		case TABLE_G_MC_DOD:
			$loc = 'Pristupnica za dodatnog korisnika Mastercard kartice';
		break;

		case TABLE_G_MC_ZLATNA_DOD:
			$loc = 'Pristupnica za dodatnog korisnika zlatne Mastercard kartice';
		break;

		case TABLE_P_PR2_VB:
			$loc = 'Pristupnica Visa business kartica 2';
		break;

		case TABLE_P_PR2_VBP:
			$loc = 'Pristupnica Visa Bonus plus kartica 2';
		break;

	}

	if($loc_only) {
		return $loc;
	}

	if(isset($_POST['submit'])) {
		require DOC_ROOT . '/orbicon/modules/hpb.form/inc.hpbform.php';
		require DOC_ROOT . '/orbicon/modules/hpb.form/form/' . $inc;
		$form_id = submit_hpbform();

		if($orbx_mod->validate_module('userstats') && !get_is_search_engine_bot() && !get_is_w3c_validator() && $_SESSION['user.r']['id']) {
			require_once DOC_ROOT.'/orbicon/modules/userstats/class.userstats.php';
			$userstats = new UserStats($_SESSION['user.r']['id']);
			$userstats->log_finale($_SERVER['REQUEST_URI'], $tpl);
			$userstats = null;
		}
	}

	if(isset($_REQUEST['fin'])) {
		$basename = 'fin.html' . '?' . $form_id;
	}

	return DOC_ROOT . '/orbicon/modules/hpb.form/html/' . $tpl . '/' . $basename;
}

?>