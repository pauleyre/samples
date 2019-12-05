<?php
/**
 * file that ajax calls for loading data from database
 * @author Dario Benšić <dario.bensic@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @version 2.04
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-06-27
 */


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


	function my_money_format($nm)
	{
		for($done = strlen($nm); $done > 3; $done -= 3) {
			$returnNum = '.' . substr($nm, ($done - 3), 3) . $returnNum;
		}
		return substr($nm, 0, $done) . $returnNum;
	}

	define('TABLE_MOD_SAVINGS_CALC', 'orbx_mod_sav_calc');
	define ('TABLE_MOD_SAVINGS_CACL_TYPE_OF_SAV', 'orbx_mod_sav_calc_type_of_sav');
    require_once DOC_ROOT . '/orbicon/class/inc.core.php';


// values from javascript function we store in variables
$selected_type_of_savings = $_POST['type_of_savings'];
$selected_national_or_foregin = $_POST['national_or_foregin'];
$selected_currency = $_POST['currency'];
$selected_currency_condition = $_POST['currency_condition'];
$selected_period_of_invest = $_POST['period_of_invest'];
$selected_period_of_invest2 = $_POST['period_of_invest_new'];

if ($selected_period_of_invest2 != '0') {
    $selected_period_of_invest = $selected_period_of_invest2;
}

// variables needed for storing correct translation
	$kamata = _L('kamata');
	$usteda = _L('usteda');
	$result = _L('result');
	$zero_result_message = _L('zero_result_message');
	$invest_message = _L('invest_message');

// If there is no period of invest defined, message will be output. Otherwise,
//code  for selecting values from database and calucating interest and saving will be executed
	global $dbc, $orbicon_x;

	$q = sprintf('	SELECT 		kamatna_stopa
					FROM		orbx_mod_sav_calc
					WHERE 		(language = %s) AND
								(vrsta_stednje = %s) AND
								(kuna_ili_deviza = %s) AND
								(valuta = %s) AND
								(valutna_klauzula = %s) AND
								(rok_orocenja = %s)',
	$dbc->_db->quote($orbicon_x->ptr),
	$dbc->_db->quote($selected_type_of_savings),
	$dbc->_db->quote($selected_national_or_foregin),
	$dbc->_db->quote($selected_currency),
	$dbc->_db->quote($selected_currency_condition),
	$dbc->_db->quote($selected_period_of_invest));
//echo "<!-- $q -->";
	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_assoc($r);

	if(empty($a)) {
		return null;
	}

	if($_POST['type2'] == 'fiksna' && (($selected_type_of_savings == 1) || ($selected_type_of_savings == 6)) ) {
		switch ($selected_period_of_invest) {
			case 1:
				echo 4.5;
				break;
			case 3:
				echo 5.75;
				break;
			case 6:
				echo 6.0;
				break;
			case 12:
				echo 6.5;
				break;
		}
		return '';
	}

	echo $a['kamatna_stopa'];
?>