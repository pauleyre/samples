<?php

	define('TABLE_MOD_EXCH_RATES', 'orbx_mod_exch_rates');

	global $exch_rates_delimiter;
	$exch_rates_delimiter = ',';
	
	global $exch_rates_date_delimiter;
	$exch_rates_date_delimiter = '/';

	global $exch_rates_float_delimiter;
	$exch_rates_float_delimiter = '.';	
	
	global $exch_rates_supported;
	$exch_rates_supported = array(
		'AUD' => array('name' => _L('australia'), 'flag' => 'au.gif'),
		'CAD' => array('name' => _L('canada'), 'flag' => 'ca.gif'),
		'CZK' => array('name' => _L('czech_republic'), 'flag' => 'cz.gif'),
		'DKK' => array('name' => _L('denmark'), 'flag' => 'dk.gif'),
		'HUF' => array('name' => _L('hungary'), 'flag' => 'hu.gif'),
		'JPY' => array('name' => _L('japan'), 'flag' => 'jp.gif'),
		'NOK' => array('name' => _L('norway'), 'flag' => 'no.gif'),
		'SKK' => array('name' => _L('slovaquia'), 'flag' => 'sk.gif'),
		'SEK' => array('name' => _L('sweden'), 'flag' => 'se.gif'),
		'CHF' => array('name' => _L('switzerland'),'flag' => 'ch.gif'),
		'GBP' => array('name' => _L('britain'),'flag' => 'gb.gif'),
		'USD' => array('name' => _L('usa'),	'flag' => 'us.gif'),
		'EUR' => array('name' => _L('emu'), 'flag' => 'europeanunion.gif'),
		'PLN' => array('name' => _L('poland'), 'flag' => 'pl.gif'),
		'SIT' => array('name' => _L('slovenia'), 'flag' => 'si.gif')
	);

	// convert from yui format
	function _convert_calendar_date($my_date)
	{
		$date = explode('.', $my_date);

		$date[0] = strtotime($date[0]);

		return $date[0];
	}

	function save_exch_rates()
	{
		if(
			is_uploaded_file($_FILES['csv']['tmp_name']) && // is uploaded
			($_FILES['csv']['name'] != '') &&	// filename ok
			(filesize($_FILES['csv']['tmp_name']) == $_FILES['csv']['size']) &&	// size ok
			($_FILES['csv']['error'] == UPLOAD_ERR_OK)	// no errors
		) {
			global $dbc, $orbicon_x, $exch_rates_supported, $exch_rates_delimiter, $exch_rates_date_delimiter, $exch_rates_float_delimiter;

			
			$exch_rates = file($_FILES['csv']['tmp_name']);

			// convert keys for check below
			$exch_rates_supported_keys = array_keys($exch_rates_supported);

			$_delete_once = false;

			foreach($exch_rates as $line_index => $rate) {
				
				$rate = str_replace('"', '', $rate);
				$rate = explode($exch_rates_delimiter, trim($rate));

				// currency code is a bit tricky
				$code = $rate[2];
				$code = explode(' ', $code);
				$code = $code[1];

				$unit = $rate[3];
				$buying_1 = str_replace($exch_rates_float_delimiter, ',', $rate[4]);
				$buying_2 = str_replace($exch_rates_float_delimiter, ',', $rate[5]);
				$middle_rate = str_replace($exch_rates_float_delimiter, ',', $rate[6]);
				$selling_1 = str_replace($exch_rates_float_delimiter, ',', $rate[7]);
				$selling_2 = str_replace($exch_rates_float_delimiter, ',', $rate[8]);

				// date is tricky as well
				$date = $rate[9];
				$date = str_replace('"', '', $date);
				$date = explode($exch_rates_date_delimiter, $date);
				// good old american date format
				//$date = $date[1].'/'.$date[0].'/'.$date[2];
				$date = $date[0].'/'.$date[1].'/'.$date[2];
				// our server thinks it's GMT -2 ?!
				$date = strtotime($date.' 02:00:00');

				if(($_delete_once === false) && ($line_index > 0)) {
					// we might have duplicates so delete those first
					$q = sprintf('	DELETE
									FROM 		'.TABLE_MOD_EXCH_RATES.'
									WHERE 		(valid_date = %s)', $dbc->_db->quote($date));
					$dbc->_db->query($q);
					$_delete_once = true;
				}

				if(in_array($code, $exch_rates_supported_keys)) {

					$q = sprintf('	INSERT
									INTO 		'.TABLE_MOD_EXCH_RATES.'
												(code, unit,
												buying_1, buying_2,
												middle_rate, selling_1,
												selling_2, valid_date)
									VALUES 		(%s, %s,
												%s, %s,
												%s, %s,
												%s, %s)',
						$dbc->_db->quote($code), $dbc->_db->quote($unit),
						$dbc->_db->quote($buying_1), $dbc->_db->quote($buying_2),
						$dbc->_db->quote($middle_rate), $dbc->_db->quote($selling_1),
						$dbc->_db->quote($selling_2), $dbc->_db->quote($date));

					$dbc->_db->query($q);
				}
			}

			redirect(ORBX_SITE_URL . '/?'. $orbicon_x->ptr . '=orbicon/mod/exchange-rates&edit=' . $date);
		}
	}

	function get_first_exch_date($restrict = true)
	{
		global $dbc;

		if($restrict) {
			$q = sprintf('	SELECT 		valid_date
							FROM		'.TABLE_MOD_EXCH_RATES.'
							WHERE		(valid_date <= %s)
							ORDER BY 	valid_date ASC
							LIMIT 		1',
							$dbc->_db->quote(time()));
		}
		else {
			$q = '	SELECT 		valid_date
					FROM		'.TABLE_MOD_EXCH_RATES.'
					ORDER BY 	valid_date ASC
					LIMIT 		1';
		}

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		return $a['valid_date'];
	}

	function get_last_exch_date($restrict = true)
	{
		global $dbc;

		if($restrict) {
			$q = sprintf('	SELECT 		valid_date
							FROM		'.TABLE_MOD_EXCH_RATES.'
							WHERE		(valid_date <= %s)
							ORDER BY 	valid_date DESC
							LIMIT 		1',
							$dbc->_db->quote(time()));

		}
		else {
			$q = 	'	SELECT 		valid_date
						FROM		'.TABLE_MOD_EXCH_RATES.'
						ORDER BY 	valid_date DESC
						LIMIT 		1';
		}

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		return $a['valid_date'];
	}

	function print_last_exch_list($restrict = true)
	{
		$date = get_last_exch_date($restrict);
		return print_exchange_list($date, true);
	}

	function print_exchange_list($date, $ret = false)
	{
		if(empty($date)) {
			return NULL;
		}

		global $dbc, $exch_rates_supported;
		$q = sprintf('	SELECT 		*
						FROM		'.TABLE_MOD_EXCH_RATES.'
						WHERE 		(valid_date = %s)
						ORDER BY 	id', $dbc->_db->quote($date));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		if(empty($a)) {
			return NULL;
		}
//<h3>'._L('exch_valid_from').': <span>'.date($_SESSION['site_settings']['date_format'], $date).'</span></h3>
		$table = '

		<table>
			<tr>
				<th>'._L('country').'</th>
				<th>'._L('unit_code').'</th>
				<th>'._L('exch_amount').'</th>
				<th>'._L('buying_1').'</th>
				<th>'._L('buying_2').'</th>
				<th>'._L('middle').'</th>
				<th>'._L('selling_1').'</th>
				<th>'._L('selling_2').'</th>
			</tr>';

		$i = 1;

		while($a) {

			$style = (($i % 2) == 0) ? ' style="background:#eeeeee;"' : '';

			// fetch country flag and name
			$flag = $exch_rates_supported[$a['code']]['flag'];
			$country = $exch_rates_supported[$a['code']]['name'];

			$a['buying_1'] = rounddown(str_replace(',', '.', $a['buying_1']), 6);
			$a['buying_2'] = rounddown(str_replace(',', '.', $a['buying_2']), 6);
			$a['middle_rate'] = rounddown(str_replace(',', '.', $a['middle_rate']), 6);
			$a['selling_1'] = rounddown(str_replace(',', '.', $a['selling_1']), 6);
			$a['selling_2'] = rounddown(str_replace(',', '.', $a['selling_2']), 6);

			$a['buying_1'] = str_replace('.', ',', $a['buying_1']);
			$a['buying_2'] = str_replace('.', ',', $a['buying_2']);
			$a['middle_rate'] = str_replace('.', ',', $a['middle_rate']);
			$a['selling_1'] = str_replace('.', ',', $a['selling_1']);
			$a['selling_2'] = str_replace('.', ',', $a['selling_2']);

			$a['buying_1'] .= '000000';
			$a['buying_2'] .= '000000';
			$a['middle_rate'] .= '000000';
			$a['selling_1'] .= '000000';
			$a['selling_2'] .= '000000';

			$a['buying_1'] = explode(',', $a['buying_1']);
			$a['buying_2'] = explode(',', $a['buying_2']);
			$a['middle_rate'] = explode(',', $a['middle_rate']);
			$a['selling_1'] = explode(',', $a['selling_1']);
			$a['selling_2'] = explode(',', $a['selling_2']);

			$a['buying_1'] = $a['buying_1'][0] . ',' . substr($a['buying_1'][1], 0, 6);
			$a['buying_2'] = $a['buying_2'][0] . ',' . substr($a['buying_2'][1], 0, 6);
			$a['middle_rate'] = $a['middle_rate'][0] . ',' . substr($a['middle_rate'][1], 0, 6);
			$a['selling_1'] = $a['selling_1'][0] . ',' . substr($a['selling_1'][1], 0, 6);
			$a['selling_2'] = $a['selling_2'][0] . ',' . substr($a['selling_2'][1], 0, 6);

			$table .= '<tr '.$style.'>
			<td><img src="'.ORBX_SITE_URL.'/orbicon/modules/exchange-rates/gfx/flags/'.$flag.'" alt="'.$country.'" title="'.$country.'" /><br />'.$country.'</td>
			<td>'.$a['code'].'</td>
			<td>'.$a['unit'].'</td>
			<td>'.$a['buying_1'].'</td>
			<td>'.$a['buying_2'].'</td>
			<td>'.$a['middle_rate'].'</td>
			<td>'.$a['selling_1'].'</td>
			<td>'.$a['selling_2'].'</td>
			</tr>';

			$a = $dbc->_db->fetch_assoc($r);
			$i ++;
		}

		$table .= '</table>';
		if($ret == true) {
			return $table;
		}
		echo $table;
	}

	function print_exch_list_calc($date, $select = null, $type = 'middle_rate')
	{
		if(empty($date)) {
			return null;
		}

		global $dbc, $exch_rates_supported;

		$menu = '';
		$q = sprintf('	SELECT 		code, '.$type.'
						FROM		'.TABLE_MOD_EXCH_RATES.'
						WHERE 		(valid_date = %s)
						ORDER BY 	id', $dbc->_db->quote($date));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		if(empty($a)) {
			return null;
		}

		while($a) {
			$selected = ($a['code'] == $select) ? 'selected="selected"' : '';
			$a[$type] = str_replace(',', '.', $a[$type]);
			$menu .= '<option value="'.$a[$type].'" '.$selected.'>'.$a['code'].'</option>';
			$a = $dbc->_db->fetch_assoc($r);
		}

		return $menu;
	}

	function print_last_exch_menus()
	{
		$date = get_last_exch_date();
		return print_exch_list_menus($date, true);
	}

	function print_exch_list_menus($date, $ret = false, $type = 'middle_rate')
	{
		$options = '';
		$menu_a = print_exch_list_calc($date, null, $type);
		$menu_b = print_exch_list_calc($date, 'EUR', $type);

		$types = array(
			'middle_rate' => _L('middle'),
			'buying_2' => _L('buying_2'),
			'selling_1' => _L('selling_1')
		);

		foreach($types as $type2 => $lng) {
			$selected = ($type == $type2) ? 'selected="selected"' : '';
			$options .= '<option value="'.$type2.'" '.$selected.'>'.$lng.'</option>';
		}

		$menu = '<table><tbody>
			<tr>
				<td><label for="exch_type">'._L('exchange-rates').':</label></td>
				<td colspan="3">
					<select style="width:150px;overflow:hidden;" id="exch_type" name="exch_type" onchange="javascript:update_calc($(\'current_valid_date\').value);">
						'.$options.'
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="valin">'._L('start_value').':</label></td>
				<td><input id="valin" name="valin" onchange="javascript:$(\'rezultat\').value = \'\'; this.value = formatNumber_exch(this.value);" type="text" /></td>
				<td><label for="CurFrom">'._L('start_currency').':</label></td>
				<td>
					<select name="CurFrom" id="CurFrom">
						<option value="1.00" selected="selected">KN</option>
						'.$menu_a.'
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="rezultat">'._L('calculation').':</label></td>
				<td><input id="rezultat" name="rezultat" type="text" readonly="readonly" /></td>
				<td><label for="CurTo">'._L('target_currency').':</label></td>
				<td>
					<select name="CurTo" id="CurTo">
						<option value="1.00">KN</option>
						'.$menu_b.'
					</select>
				</td>
			</tr>
	</tbody></table><br /><span>'._L('exch_valid_from').': '.date($_SESSION['site_settings']['date_format'], $date).'</span>';

		if($ret == true) {
			return $menu;
		}
		echo $menu;
	}

	function print_chart_xml($rate, $date)
	{
		if(empty($date)) {
			return NULL;
		}

		$xml = $categories = array();


		global $dbc;
		$q = sprintf('	SELECT 		middle_rate, valid_date
						FROM		'.TABLE_MOD_EXCH_RATES.'
						WHERE 		(valid_date <= %s) AND
									(code = %s)
						ORDER BY 	valid_date DESC
						LIMIT		22', $dbc->_db->quote($date), $dbc->_db->quote($rate));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);
		$values = array();

		while($a) {

			$a['middle_rate'] = rounddown(str_replace(',', '.', $a['middle_rate']), 6);
			$a['middle_rate'] = str_replace('.', ',', $a['middle_rate']);
			$a['middle_rate'] .= '000000';
			$a['middle_rate'] = explode(',', $a['middle_rate']);
			$middle_rate = $a['middle_rate'][0] . '.' . substr($a['middle_rate'][1], 0, 6);

			$date = date('d.m', $a['valid_date']);
			$values[] = $middle_rate;

			// save to array using date as index so we can sort them properly later
			//$xml[$a['valid_date']] .= "<set label='$date' value='$middle_rate' />";

			if(!isset($xml[$a['valid_date']])) {
				$xml[$a['valid_date']] = '';
			}

			if(!isset($categories[$a['valid_date']])) {
				$categories[$a['valid_date']] = '';
			}

			/*$xml[$a['valid_date']] .= "<set value='$middle_rate' />";
			$categories[$a['valid_date']] .= "<category label='$date' />";*/
			//$categories[$a['valid_date']] .= "<category label='' />";

			$xml[$a['valid_date']] .= "<set showName='0' value='$middle_rate' hoverText='" . date($_SESSION['site_settings']['date_format'], $a['valid_date']) . "' name='$date' />";

			$a = $dbc->_db->fetch_assoc($r);
		}

		// duh, we must reverse them back
		ksort($xml);
		ksort($categories);
		// create string
		$xml = implode('', $xml);
		$categories = implode('', $categories);
		// get min/max
		sort($values);
		$min_val = array_shift($values);
		$max_val = array_pop($values);

		/*$xml = "<chart caption='"._L('middle_movement')." $rate/HRK' yAxisMinValue='$min_val' yAxisMaxValue='$max_val' xAxisName='' yAxisName='' numberPrefix='' showNames='1' showValues='0' rotateNames='0' showColumnShadow='1' animation='1' showAlternateHGridColor='1' AlternateHGridColor='ff5904' divLineColor='cc0000' divLineAlpha='20' alternateHGridAlpha='5' canvasBorderColor='666666' baseFontColor='666666' lineColor='cc0000' lineAlpha='85' labelDisplay='ROTATE' slantLabels='1'><categories>$categories</categories><dataset color='cc0000' anchorBorderColor='cc0000' anchorBgColor='cc0000'>$xml</dataset></chart>";*/

		$xml = "<graph formatNumberScale='0' formatNumber='1' caption='"._L('middle_movement')." $rate/HRK' yAxisMinValue='$min_val' yAxisMaxValue='$max_val' showValues='0' animation='1' showAlternateHGridColor='1' AlternateHGridColor='f6f6f6' divLineColor='e2e3e4' divLineAlpha='50' alternateHGridAlpha='100' canvasBorderColor='b5b8b9' canvasBorderThickness='1' baseFontColor='686868' lineColor='d11119' lineThickness='3' bgColor='ffffff' toolTipBgColor='f4f4f4' toolTipBorderColor='e2e2e2' chartTopMargin='25'>$xml</graph>";

		// free some memory
		unset($categories, $values, $min_val, $max_val, $middle_rate);
		// cleanup from all sort of breaks. we need a oneliner
		$xml = str_sanitize($xml, STR_SANITIZE_XML);
		return $xml;
	}

	function print_flash_graph($rate, $date)
	{
		global $orbx_mod;
		$cfg = $orbx_mod->load_info('exchange-rates');

		$xml = print_chart_xml($rate, $date);
		echo '<object
				type="application/x-shockwave-flash"
				data="' . ORBX_SITE_URL . '/orbicon/modules/exchange-rates/gfx/'. $cfg['chart']['flash'] .'"
				height="200"
				width="530">
					<param name="movie" value="'.ORBX_SITE_URL.'/orbicon/modules/exchange-rates/gfx/' . $cfg['chart']['flash'] . '" />
					<param name="quality" value="high" />
					<param name="menu" value="0" />
					<param name="wmode" value="transparent" />
					<param name="flashvars" value="dataXML=' . $xml . '&chartWidth=530&chartHeight=200" />
			</object>';
	}

	function get_exch_rate_summary()
	{
		global $orbx_mod, $dbc, $exch_rates_supported;
		$cfg = $orbx_mod->load_info('exch-rates-summary');

		$types = array(
			'middle_rate' => _L('middle'),
			'buying_2' => _L('buying_2'),
			'selling_1' => _L('selling_1')
		);

		$last = get_last_exch_date();

		$currencies = explode(',', $cfg['display']['currencies']);

		foreach($currencies as $currency) {

			$q = sprintf('	SELECT 		middle_rate, buying_2, selling_1
							FROM		'.TABLE_MOD_EXCH_RATES.'
							WHERE 		(valid_date = %s) AND
										(code = %s)
							LIMIT		1', $dbc->_db->quote($last), $dbc->_db->quote($currency));

			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);

			$flag = $exch_rates_supported[$currency]['flag'];

			$a['buying_2'] = rounddown(str_replace(',', '.', $a['buying_2']), 6);
			$a['middle_rate'] = rounddown(str_replace(',', '.', $a['middle_rate']), 6);
			$a['selling_1'] = rounddown(str_replace(',', '.', $a['selling_1']), 6);

			$a['buying_2'] = str_replace('.', ',', $a['buying_2']);
			$a['middle_rate'] = str_replace('.', ',', $a['middle_rate']);
			$a['selling_1'] = str_replace('.', ',', $a['selling_1']);

			$a['buying_2'] .= '000000';
			$a['middle_rate'] .= '000000';
			$a['selling_1'] .= '000000';

			$a['buying_2'] = explode(',', $a['buying_2']);
			$a['middle_rate'] = explode(',', $a['middle_rate']);
			$a['selling_1'] = explode(',', $a['selling_1']);

			$a['buying_2'] = $a['buying_2'][0] . ',' . substr($a['buying_2'][1], 0, 6);
			$a['middle_rate'] = $a['middle_rate'][0] . ',' . substr($a['middle_rate'][1], 0, 6);
			$a['selling_1'] = $a['selling_1'][0] . ',' . substr($a['selling_1'][1], 0, 6);

			$table .= '<tr><td class="exch_rates_smry_bd"><img src="' . ORBX_SITE_URL. "/orbicon/modules/exchange-rates/gfx/flags/$flag\" alt=\"$currency\" title=\"$currency\" /></td><td class=\"exch_rates_smry_bd\">{$a['buying_2']}</td><td class=\"exch_rates_smry_bd\">{$a['middle_rate']}</td><td class=\"exch_rates_smry_bd\">{$a['selling_1']}</td></tr>";
		}

		/* removed
		<p>' . $types[$cfg['display']['rate']] .' ' ._L('for'). ' <strong>' . date($_SESSION['site_settings']['date_format'], $last) . '</strong></p>*/

		return '
			<div id="exch_rates_summary">
			  	<table id="exch_rates_smry_table">
				    <tbody>
					    <tr>
					    	<th class="exch_rates_smry_hd"></th>
					    	<th class="exch_rates_smry_hd">'._L('ers_buying').'</th>
					    	<th class="exch_rates_smry_hd">'._L('ers_middle').'</th>
					    	<th class="exch_rates_smry_hd">'._L('ers_selling').'</th>
					    </tr>
				    	'.$table.'
				  	</tbody>
				</table>
			</div>';
	}

	function get_daterange($month, $year)
	{
		$from = mktime(0, 0, 0, $month, 1, $year);
		// bug #162
		$to = mktime(0, 0, 0, ($month + 1), 1, $year);

		global $dbc;
		$q = sprintf('	SELECT 		valid_date
						FROM		'.TABLE_MOD_EXCH_RATES.'
						WHERE		(valid_date >= %s) AND
									(valid_date <= %s)
						GROUP BY	valid_date',
						$dbc->_db->quote($from), $dbc->_db->quote($to));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$dates[] = date('m/d/Y', $a['valid_date']);
			$a = $dbc->_db->fetch_assoc($r);
		}

		$dates = implode(',', $dates);

		return $dates;
	}

?>