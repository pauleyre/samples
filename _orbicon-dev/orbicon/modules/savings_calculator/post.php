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
	define('TABLE_MOD_SAVINGS_CACL_TYPE_OF_SAV', 'orbx_mod_sav_calc_type_of_sav');

    require_once DOC_ROOT . '/orbicon/class/inc.core.php';

/*
echo $_POST['invest'];
echo $_POST['vrsta_stednje'];
echo $_POST['national_or_foregin'];
echo $_POST['currency'];
echo $_POST['currency_condition'];
echo $_POST['period_of_invest'];
echo $_POST['starting_month_of_invest'];
echo $_POST['day'];
*/

// values from javascript function we store in variables
$selected_type_of_savings = $_POST['vrsta_stednje'];
$selected_invest = $_POST['invest'];
$selected_national_or_foregin = $_POST['national_or_foregin'];
$selected_currency = $_POST['currency'];
$selected_currency_condition = $_POST['currency_condition'];
$selected_period_of_invest = $_POST['period_of_invest'];
$selected_starting_month_of_invest = $_POST['starting_month_of_invest'];
$year = $_POST['year'];
$day = $_POST['day'];
$kamatna_stopa = $_POST['kamatna_stopa'];
$kamatna_stopa2 = @$_POST['kamatna_stopa2'];
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
if($_POST['invest'] != '0.00') {

		global $dbc, $orbicon_x;

	if(empty($kamatna_stopa)) {

		$q = sprintf('	SELECT 		kamatna_stopa
						FROM		orbx_mod_sav_calc
						WHERE 		(language = %s) AND
									(vrsta_stednje = %s) AND
									(kuna_ili_deviza = %s) AND
									(valuta = %s) AND
									(valutna_klauzula = %s) AND
									(rok_orocenja = %s)',
		$dbc->_db->quote($orbicon_x->ptr), $dbc->_db->quote($selected_type_of_savings),
		$dbc->_db->quote($selected_national_or_foregin), $dbc->_db->quote($selected_currency),
		$dbc->_db->quote($selected_currency_condition), $dbc->_db->quote($selected_period_of_invest));

		                //var_dump($q);

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		if(empty($a)) {
		    echo $zero_result_message;
			return null;
		}

		if($a) {
			/*$menu .= "
			<div>{$a['vrsta_stednje']}</div>
			<div>{$a['kuna_ili_deviza']}</div>
			<div>{$a['valuta']}</div>
			<div>{$a['valutna_klauzula']}</div>
			<div>{$a['rok_orocenja']}</div>*/
			//$menu .= "<div>{$a['kamatna_stopa']}</div>";
			$selected_interest_rate = $a['kamatna_stopa'];
			//$a = $dbc->_db->fetch_array($r);
			//echo $menu;


			if($_POST['type2'] == 'fiksna' && (($selected_type_of_savings == 1) || ($selected_type_of_savings == 6)) ) {
				switch ($selected_period_of_invest) {
					case 1:
						$selected_interest_rate = 4.5;
						break;
					case 3:
						$selected_interest_rate = 5.75;
						break;
					case 6:
						$selected_interest_rate = 6.0;
						break;
					case 12:
						$selected_interest_rate = 6.5;
						break;
					default:
						$selected_interest_rate = 0;
				}
			}


		}

			$interest_result_inicial = ($selected_invest * $day * $selected_interest_rate / $year);

	}	else {

	        $interest_result_inicial = ($selected_invest * $day * $kamatna_stopa / $year);

	}

        	//echo '<div align="center"><p>'.$result.'</p></div>';




        	 	//echo '<p>'.$kamata.': '.rounddown($interest_result, 2) . '&#37;</p>'; // for different number format for interest rate, remove line with function my_money_format
        	//$interest_result = str_replace('.', ',', rounddown($interest_result_inicial, 2));
        	//$interest_result = str_replace('.,', ',', my_money_format($interest_result));

        	$interest_result = number_format($interest_result_inicial, 2, ',', '.');

        	//$interest_result = str_replace('.,', ',', my_money_format($interest_result));

        	$saving = ($selected_invest + $interest_result_inicial);

        	//$saving = str_replace('.', ',', rounddown($saving, 2));
        	//$saving = str_replace('.,', ',', my_money_format($saving));

        	$saving = number_format($saving, 2, ',', '.');

        	if ($selected_national_or_foregin == '1') {
        	   echo $kamata.':<br /><input type="text" readonly="readonly" value="'.$interest_result.' kn"><br />' ;
        	   echo '<br />'.$usteda.':<br /> <input type="text" readonly="readonly" value="'.$saving.' kn">';
        	}
        	if ($selected_national_or_foregin == '2' && $selected_currency == 'EUR') {
        	   echo $kamata.':<br /><input type="text" readonly="readonly" value="'.$interest_result.' EUR"><br />' ;
        	   echo '<br />'.$usteda.':<br /> <input type="text" readonly="readonly" value="'.$saving.' EUR">';
        	}
        	if ($selected_national_or_foregin == '2' && $selected_currency == 'CHF') {
        	   echo $kamata.':<br /><input type="text" readonly="readonly" value="'.$interest_result.' CHF"><br />' ;
        	   echo '<br />'.$usteda.':<br /> <input type="text" readonly="readonly" value="'.$saving.' CHF">';
        	}
        	if ($selected_national_or_foregin == '2' && $selected_currency == 'USD') {
        	   echo $kamata.':<br /><input type="text" readonly="readonly" value="'.$interest_result.' USD"><br />' ;
        	   echo '<br />'.$usteda.':<br /> <input type="text" readonly="readonly" value="'.$saving.' USD">';
        	}

        	//$saving = rounddown($saving_inicial, 2);
        	/*$saving = str_replace('.', ',', my_money_format($saving));
        	$saving = str_replace(',', '', $saving);
        	$saving = my_money_format($saving);
        	$saving = rounddown($saving, 2);*/

        	//echo '<br />'.$usteda.': '.$saving.'</p>';

} else {
	echo $invest_message;
}
?>