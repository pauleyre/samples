<?php

$display .= '
<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/modules/invest/library/calc.js?'.ORBX_BUILD.'"></script>
<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/modules/invest/NumberFormat154.js?'.ORBX_BUILD.'"></script>
<form name="calculator" id="calculator" method="post" action="">
<div style="float: left; width: 30%;">
<p>
	<label for="fond">'._L('invest-lbl-fond').'</label><br />
	<select name="fond" id="fond" onchange="javascript: update_calendars();">
';

		$fonds = new Fond;
		$my_Stock = new Stock;

		$tmp_fond_res = $fonds->get_all_fonds(1);
		while($fond = $dbc->_db->fetch_assoc($tmp_fond_res)){

			$selected = (($_GET['fond'] == $fond['id']) || ($_POST['fond'] == $fond['id'])) ? ' selected="selected"' : '';

			$range = $my_Stock->get_date_range($fond['id']);
			$min = $range['lowest'];
			$max = $range['highest'];

			// * reformat db values from yyyy-mm-dd into mm/dd/yyyy
			$min = explode('-', $min);
			$max = explode('-', $max);

			$min = $min[1] . '/' . $min[2] . '/' . $min[0];
			$max = $max[1] . '/' . $max[2] . '/' . $max[0];

			$short_title = str_replace(array(' Fond', ' Fund'), '', $fond['title']);

			if(strtolower($orbicon_x->ptr) == 'en') {
				if($short_title == 'Obveznički') {
					$short_title = 'Bond';
				}
				elseif ($short_title == 'Dionički') {
					$short_title = 'Equity';
				}
				elseif ($short_title == 'Novčani') {
					$short_title = 'Money market';
				}
			}

			$display .= "<option max=\"$max\" min=\"$min\" value=\"{$fond['id']}\"$selected>$short_title</option>";

		}

		//echo $min. '-' .$max;

		unset($my_Stock);

$display .= '
	</select>
</p>
<br />
<p>
	<label for="amount">'._L('invest-lbl-amount').'</label><br />
	<input type="text" name="amount" id="amount" value="'.$_POST['amount'].'" onblur="javascript: this.value = formatNumber_new(this.value);" onchange="javascript: this.value = formatNumber_new(this.value);" />
</p>
<br />

<p>
	<input type="submit" name="submit_calculation" id="submit_calculation" value="'._L('invest-lbl-calculate').'" />
</p>
</div>
<div style="float: left; width: 65%;">
<div class="split_view">
	<p>'._L('invest-lbl-fdate').'</p>
	<div id="from_date"></div>
	<input type="hidden" name="from_d" id="from_d" />
</div>
<div class="split_view">
	<p>'._L('invest-lbl-tdate').'</p>
	<div id="till_date"></div>
	<input type="hidden" name="till_d" id="till_d" />
</div>
<div class="cleaner"></div>
</div>
<div class="cleaner"></div>

</form>

';

// * do calculation & display result
if(isset($_POST['submit_calculation'])){

	// actual currency
	$fond_res = $fonds->get_fond($_POST['fond']);
	$fond = $fonds->dbconn->fetch_array($fond_res);

	$min_amount = $fond['min_entry'];
	$entry_fee 	= ($fond['entry_fee'] == '') ? NULL : $fond['entry_fee'] / 100;

	$amount = $_POST['amount'];
	$amount = str_replace(array('.', ','), array('', '.'), $amount);


	// * check for inputs
	if($amount == ''){
		$display .= '<p class="error"><strong>'._L('invest-lbl-warning').':</strong> '._L('invest-lbl-false-amount').'</p>';
	} else if ($_POST['from_d'] == '' || $_POST['till_d'] == ''){
		$display .= '<p class="error"><strong>'._L('invest-lbl-warning').':</strong> '._L('invest-lbl-false-date').'</p>';
	} else if ($amount < $min_amount){
		$display .= '<p class="error"><strong>'._L('invest-lbl-warning').':</strong> '._L('invest-lbl-min-amount').' <strong>'.$min_amount.'</strong></p>';
	} else {

		// subtract entry fee
		$amount = ($entry_fee != null) ? $amount - ($entry_fee * $amount) : $amount;


		$from_date = explode('/', $_POST['from_d']);
		$till_date = explode('/', $_POST['till_d']);



		$from_date[0] = str_pad($from_date[0], 2, '0', STR_PAD_LEFT);
		$till_date[0] = str_pad($till_date[0], 2, '0', STR_PAD_LEFT);

		$from_date[1] = str_pad($from_date[1], 2, '0', STR_PAD_LEFT);
		$till_date[1] = str_pad($till_date[1], 2, '0', STR_PAD_LEFT);

		// * sql prepared date
		$data['from_date'] = "$from_date[2]-$from_date[0]-$from_date[1]";
		$data['till_date'] = "$till_date[2]-$till_date[0]-$till_date[1]";

		// * format for croatian view f reading
		$show_start_date = "$from_date[1].$from_date[0].$from_date[2]";
		$show_finish_date = "$till_date[1].$till_date[0].$till_date[2]";

		$data['amount'] = $amount;
		$data['fond'] = $_POST['fond'];

		// * get info from db
		$s = new Stock($data);
		$f = new Fond($data);
		$c = new Currency;

		$currency = $c->get_currency($fond['currency']);
		$currency = $dbc->_db->fetch_assoc($currency);

		$start_info = $s->get_start_value();
		$finish_info = $s->get_finish_value();

		$start_info['stock_value'] = round($start_info['stock_value'], 2);
		$finish_info['stock_value'] = round($finish_info['stock_value'], 2);

		// * broj dana drzanja
		$day_count = $s->get_days_diff();

		// * porast vrijednosti udjela -> currency
		$uphold = $finish_info['stock_value'] - $start_info['stock_value'];

		// * prinos za razdoblje drzanja -> %
		$holding_profit = (($finish_info['stock_value'] / $start_info['stock_value']) - 1) * 100;

		// * step 1 - base
		$base = (100 + ($holding_profit)) / 100;

		// * step 2 - exponent
		$exp = 365 / $day_count;

		// * step 3 - calculate exponent
		$exp_result = pow($base, $exp);

		// * prinos za razdoblje drzanja na godisnjoj bazi -> %
		$annual_holding_profit = ($exp_result - 1) * 100;

		// * broj kupljenih udjela
		$bought_shares = $amount / $start_info['stock_value'];

		// * Vrijednost kupljenih udjela na pocetku razdoblja
		$total_buy_price = $bought_shares * $start_info['stock_value'];

		// * Vrijednost kupljenih udjela na kraju razdoblja
		$total_sell_price = $bought_shares * $finish_info['stock_value'];

		// * dobit
		$profit = $bought_shares * $uphold;

		$display .= '
		<div id="report">
		<p>'._L('invest-report-asset').': <span class="report_title">'.number_format($finish_info['stock_value'], 2,',', '.').'</span> '._L('invest-report-ondate').' <span class="report_title">'.$show_finish_date.'</span></p>
		<p>'._L('invest-report-calc').' <span class="report_title">'.$show_start_date.'</span> - <span class="report_title">'.$show_finish_date.'</span></p>

		<h2 class="info_label">'._L('invest-report-info-label').'</h2>

		<table id="report_info" width="100%">
			<tr>
				<th colspan="2">'._L('invest-report-asset-title').'</th>
			</tr>
			<tr>
				<td width="70%">'._L('invest-report-asset-beg').'</td>
				<td width="30%" class="value_column">'.number_format($start_info['stock_value'], 2,',', '.').' '.$currency['title'].'</td>
			</tr>
			<tr class="high">
				<td>'._L('invest-report-asset-end').'</td>
				<td class="value_column">'.number_format($finish_info['stock_value'], 2,',', '.').' '.$currency['title'].'</td>
			</tr>
			<tr>
				<td>'._L('invest-report-asset-growth').'</td>
				<td class="value_column">'.number_format(round($uphold, 2), 2,',', '.').' '.$currency['title'].'</td>
			</tr>
			<tr>
				<th colspan="2">'._L('invest-report-yield-title').'</th>
			</tr>
			<tr>
				<td>'._L('invest-report-yield-investment').'</td>
				<td class="value_column">'.$day_count.'</td>
			</tr>
			<tr class="high">
				<td>'._L('invest-report-yield-period').'</td>
				<td class="value_column">'.number_format(round($holding_profit, 2), 2,',', '.').'%</td>
			</tr>
			<tr>
				<td>'._L('invest-report-yield-annual').'</td>
				<td class="value_column">'.number_format(sprintf("%01.2f", $annual_holding_profit), 2,',', '.').'%</td>
			</tr>
			<tr>
				<th colspan="2">'._L('invest-report-payed-title').'</th>
			</tr>
			<tr>
				<td>'._L('invest-report-payed-num').'</td>
				<td class="value_column">'.number_format(round($bought_shares, 2), 2,',', '.').'</td>
			</tr>
			<tr class="high">
				<td>'._L('invest-report-payed-init').'</td>
				<td class="value_column">'.number_format(round($total_buy_price, 2), 2,',', '.').' '.$currency['title'].'</td>
			</tr>
			<tr>
				<td>'._L('invest-report-payed-final').'</td>
				<td class="value_column">'.number_format(round($total_sell_price, 2), 2,',', '.').' '.$currency['title'].'</td>
			</tr>
			<tr class="high">
				<td>'._L('invest-report-payed-return').'</td>
				<td class="value_column">'.number_format(round($profit, 2), 2,',', '.').' '.$currency['title'].'</td>
			</tr>
		</table>

		</div>';
	}
}

?>