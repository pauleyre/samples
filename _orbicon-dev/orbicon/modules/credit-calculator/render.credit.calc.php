<?php

	$url = ORBX_SITE_URL;
	$orbx_build = ORBX_BUILD;
	include_once DOC_ROOT . '/orbicon/modules/credit-calculator/inc.credit.calc.php';
	$credit_list = get_credit_menu();

	$interest_rate = _L('interest_rate');
	$credit = _L('credit');
	$total = _L('credit_total');
	$mnths = _L('credit_months');
	$anuitet = _L('anuitet');

return <<<TXT
<!-- calc -->
<table>
	<tbody>
		<tr>
			<td><label for="credit">{$credit}</label></td>
			<td><select id="credit" name="credit" onblur="javascript: change_interest(this);" onchange="javascript: change_interest(this);"><option value="0">&mdash;{$credit}&mdash;</option>{$credit_list}</select></td>
		</tr>
		<tr>
			<td><label for="total">{$total}</label></td>
			<td><input onblur="javascript: this.value = formatNumber_new(this.value);" onchange="javascript: this.value = formatNumber_new(this.value);" type="text" id="total" name="total" value="" /></td>
		</tr>
		<tr>
			<td><label for="months">{$mnths}</label></td>
			<td><input type="text" value="" id="months" name="months" onchange="javascript: check_max_years();" /></td>
		</tr>
		<tr>
			<td><label for="interest">{$interest_rate}</label></td>
			<td><input type="text" value="" id="interest" name="interest" /></td>
		</tr>
		<tr>
			<td><label for="anuitet">{$anuitet}</label></td>
			<td><input type="text" value="" id="anuitet" name="anuitet" readonly="readonly" /></td>
		</tr>
	</tbody>
</table>
<p id="error_cred" style="color:#cc0000">&nbsp;</p>
<!-- calc -->
TXT;

?>