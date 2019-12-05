<?php
/**
 * front end of savings calculator module
 * @author Dario Benšić <dario.bensic@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @version 2.04
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-06-27
 */

	$url = ORBX_SITE_URL;
	$orbx_build = ORBX_BUILD;
	include_once DOC_ROOT . '/orbicon/modules/savings_calculator/inc.savings_calculator.php';

    $vrsta_stednje = _L('vrsta_stednje');
	$orocenje = _L('orocenje');
	$kuna_ili_deviza = _L('kuna_ili_deviza');
	$valuta = _L('valuta');
	$rok_orocenja = _L('rok_orocenja');
	$valutna_klauzula = _L('valutna_klauzula');
	$save = _L('save');
	$calculate = _L('calculate');
	$kamata = _L('kamata');
	$usteda = _L('usteda');
	$pocetni_mjesec_orocenja = _L('pocetni_mjesec_orocenja');
	$month = _L('month');
	$result = _L('result');
	$interest_rate_to_insert = _L('kamatna_stopa');
	$user_interest_rate_saving= _L('user_interest_rate_saving');

	$valutna_klauzula_lista = valutna_klauzula_lista_frontend();
	$kuna_ili_deviza_lista = kuna_ili_deviza_lista();
	$valutna_lista = valutna_lista();
	$rok_orocenja_lista = rok_orocenja_lista_front();
	$vrsta_stednje_lista = vrsta_stednje_lista();
	$pocetni_mjesec_orocenja_lista = pocetni_mjesec_orocenja_lista();
	$rok_orocenja_lista_2 = rok_orocenja_lista_front2();


return <<<TXT
<!-- calc -->
<script type="text/javascript">
YAHOO.util.Event.addListener(window,'load',on_opening_form);
</script>
<div id="form_container">
  <pre id="address"></pre>
	<form method="post" action="" name="form_ajax" id="form_ajax">
	<table>
		<tbody>
			<tr>
				<td><label for="vrsta_stednje">{$vrsta_stednje}</label></td>
				<td colspan="3"><select id="vrsta_stednje" name="vrsta_stednje" onchange="choose(vrsta_stednje);"><option value="">{$user_interest_rate_saving}</option>{$vrsta_stednje_lista}</select></td>
			</tr>
			<tr>
				<td><label for="orocenje">{$orocenje}</label></td>
				<td colspan="3"><input onblur="javascript: this.value = formatNumber_new(this.value);" onchange="javascript: this.value = formatNumber_new(this.value);" type="text" id="orocenje" name="orocenje" value="0.00" /></td>
			</tr>
			<tr>
				<td><label for="valutna_klauzula">{$valutna_klauzula}</label></td>
				<td colspan="3"><select id="valutna_klauzula" name="valutna_klauzula" onchange="get_interest_rate(rok_orocenja);">{$valutna_klauzula_lista}</select></td>
			</tr>
			<tr>
				<td><label for="kuna_ili_deviza">{$kuna_ili_deviza}</label></td>
				<td><select id="kuna_ili_deviza" name="kuna_ili_deviza" onchange="get_interest_rate(rok_orocenja);"><option></option>{$kuna_ili_deviza_lista}</select></td>
				<td align="right" class="valuta"><label for="valuta">{$valuta}</label></td>
				<td align="right"><select id="valuta" name="valuta" onchange="get_interest_rate(rok_orocenja);"><option></option>{$valutna_lista}</select></td>
			</tr>
			<tr>
				<td><label for="rok_orocenja">{$rok_orocenja}</label></td>
				<td colspan="3"><select id="rok_orocenja" name="rok_orocenja" onchange="get_interest_rate(rok_orocenja);">{$rok_orocenja_lista}</select><select id="rok_orocenja2" name="rok_orocenja2" onchange="get_interest_rate(rok_orocenja);">{$rok_orocenja_lista_2}</select></td>
			</tr>
			<tr>
				<td><label for="pocetni_mjesec_orocenja">{$pocetni_mjesec_orocenja}</label></td>
				<td colspan="3"><select id="pocetni_mjesec_orocenja" name="pocetni_mjesec_orocenja">{$pocetni_mjesec_orocenja_lista}</select> {$month}</td>
			</tr>
			<tr>
				<td><label for="kamatna_stopa_proizvoljno1" id="kamatna_stopa_proizvoljno1">{$interest_rate_to_insert}</label></td>
				<td colspan="3"><input type="text" id="kamatna_stopa_proizvoljno2" name="kamatna_stopa_proizvoljno2" onblur="javascript: this.value = formatNumber_interest_rate(this.value);" onchange="javascript: this.value = formatNumber_interest_rate(this.value);" />

				<select name="vrsta_kamatne_stope" id="vrsta_kamatne_stope" onchange="get_interest_rate(rok_orocenja);">
					<option value="proizvoljna">promjenjiva</option>
					<option value="fiksna">fiksna</option>
				</select>

				</td>
			</tr>
			<tr>
                <td valign="top"><label>{$result}</label></td>
				<td colspan="3"><div id="container_ajax_results">
				Kamata:<br/>
				<input type="text" value="0,00 kn" readonly="readonly"/>
				<br/>
				<br/>
				Ušteda:<br/>
				<input type="text" value="0,00 kn" readonly="readonly"/>
				</div></td>
			</tr>
		</tbody>
	</table>
	<p id="savings_error">&nbsp;</p>
	</form>
</div>
TXT;
?>