<?php

require 'logic/class.Client.php';
require 'logic/class.Project.php';
require 'logic/class.Todo.php';
require 'logic/func.calc.php';


if(isset($_POST['submit'])) {
	print_pdf(new Project($_GET['id']));
}

?>

<script src=web/js/calc.js></script>

<form action="" method="post" onsubmit="return Validate()">

<table width="100%">
	<tr style="font-weight: bold;">
		<td width="300">Vrsta troška</td>
		<td width="100">Količina (br.)</td>
		<td width="200">Cijena (kn)</td>
		<td width="200">Ukupno (kn)</td>
		<td width="100">Popust (%)</td>
		<td width="100">Rabat (%)</td>
		<td width="100">Za fakturu</td>
	</tr>

<?php

	if(isset($_GET['id'])) {

		$i = 1;
		$script = '<script>aIDlist = new Array();';

		$t = new Todo();
		$r = $t->getTodos($_GET['id']);
		$todos = $db->fetch_assoc($r);

		while($todos) {

			$script .= 'aIDlist['. ($i - 1) . "] = $i;";
			$desc = (isset($_POST['sVrstaTroska_'.$i])) ? $_POST['sVrstaTroska_'.$i] : $todos['description'];
			$total_h = (isset($_POST['nKolicina_'.$i])) ? $_POST['nKolicina_'.$i] : $todos['total_hours'];

			echo '<tr>
				<td><textarea onblur="CheckForBilling(this, \'bZaFakturu_'.$i.'\')" onkeyup="searchSuggest2(this, event); CheckForBilling(this, \'bZaFakturu_'.$i.'\')" rows=5 name="sVrstaTroska_'.$i.'">'.$desc.'</textarea></td>
				<td><input name="nKolicina_'.$i.'" type=text id="nKolicina_'.$i.'" value="'.$total_h.'" size=4 onblur="CalcAll()"></td>
				<td><input onkeyup="searchSuggest2(this, event);" name="nCijena_'.$i.'" type="text" id="nCijena_'.$i.'" value="'.$_POST['nCijena_' . $i].'" onblur="CalcAll()"> kn</td>
				<td><input name="nUkupno_'.$i.'" type=text id="nUkupno_'.$i.'" value="'.$_POST['nUkupno_' . $i].'" onblur="CalcAll()"> kn</td>
				<td><input size=2 name="nPopust_'.$i.'" type=text id="nPopust_'.$i.'" value="'.$_POST['nPopust_' . $i].'"  onblur="CalcAll();"></td>
				<td><input size=2 name="nRabat_'.$i.'" type=text id="nRabat_'.$i.'" value="'.$_POST['nRabat_' . $i].'"  onblur="CalcAll();"></td>
				<td><input '.((isset($_POST['bZaFakturu_'.$i])) ? 'checked' : '').' name="bZaFakturu_'.$i.'" type=checkbox id="bZaFakturu_'.$i.'" value=1><input type=hidden name=count_entries[] value='.$i.'></td>
				</tr>';

			$todos = $db->fetch_assoc($r);
			$i ++;
		}

		$script .= '</script>';

	}
	else {

		$i = 1;
		$script = '<script>aIDlist = new Array();';

		while($i <= 10) {

			$script .= 'aIDlist['. ($i - 1) . "] = $i;";
			$desc = (isset($_POST['sVrstaTroska_'.$i])) ? $_POST['sVrstaTroska_'.$i] : $todos['description'];
			$total_h = (isset($_POST['nKolicina_'.$i])) ? $_POST['nKolicina_'.$i] : $todos['total_hours'];

			echo '<tr>
				<td><textarea onblur="CheckForBilling(this, \'bZaFakturu_'.$i.'\')" onkeyup="searchSuggest2(this, event); CheckForBilling(this, \'bZaFakturu_'.$i.'\')" rows=5 name="sVrstaTroska_'.$i.'">'.$desc.'</textarea></td>
				<td><input name="nKolicina_'.$i.'" type=text id="nKolicina_'.$i.'" value="'.$total_h.'" size=4 onblur="CalcAll()"></td>
				<td><input onkeyup="searchSuggest2(this, event);" name="nCijena_'.$i.'" type="text" id="nCijena_'.$i.'" value="'.$_POST['nCijena_' . $i].'" onblur="CalcAll()"> kn</td>
				<td><input name="nUkupno_'.$i.'" type=text id="nUkupno_'.$i.'" value="'.$_POST['nUkupno_' . $i].'" onblur="CalcAll()"> kn</td>
				<td><input size=2 name="nPopust_'.$i.'" type=text id="nPopust_'.$i.'" value="'.$_POST['nPopust_' . $i].'"  onblur="CalcAll();"></td>
				<td><input size=2 name="nRabat_'.$i.'" type=text id="nRabat_'.$i.'" value="'.$_POST['nRabat_' . $i].'"  onblur="CalcAll();"></td>
				<td><input name="bZaFakturu_'.$i.'" type=checkbox id="bZaFakturu_'.$i.'" value=1><input type=hidden name=count_entries[] value='.$i.'></td>
				</tr>';


			$i ++;
		}
		$script .= '</script>';

	}

?>

</table>
<?php echo $script ?>


<label for="bNoPDV">Oslobođenje od PDV-a</label></legend> <input name="bNoPDV" type="checkbox" id="bNoPDV" value=1 />

<?php
	if(!isset($_GET['id'])) {
?>
<fieldset>
<legend><label for="sTipDoc">Tip</label></legend>
<select name="sTipDoc" id="sTipDoc">
	<optgroup label="Računi">
		<option value="RAČUN">Ra&#269;un</option>
		<option value="RAČUN ZA PRIMLJENI PREDUJAM">Račun za primljeni predujam</option>
	</optgroup>
	<option value="PONUDA">Ponuda</option>
	<option value="PROCJENA">Procjena</option>
	<option value="PREDPONUDA">Predponuda</option>
</select>
</fieldset>

<fieldset>
	<legend><label for="sBrojQuick">Oznaka</label></legend>
	<input style="text-align: right;" onkeyup="error_check2('sBrojQuick', '<?php echo $_GET['ftype']; ?>');" type="text" id="sBrojQuick" name="sBrojQuick" value="<?= date('/n/Y', time()); ?>" />
</fieldset>

<fieldset>
	<legend><label for="client_id_q">Klijent</label></legend>
	<select name="client_id_q" id="client_id_q">

<?php

$c = new Client();
$clientRes = $c->getClients();
$clients = $db->fetch_assoc($clientRes);

while($clients) {

	$selected = ($clients['id'] == $p->client_id) ? 'selected' : '';

	echo "<option $selected value={$clients['id']}>{$clients['company_name']}</option>";
	$clients = $db->fetch_assoc($clientRes);
}

?>

</select>

</fieldset>

<?php
	}
?>
<fieldset>
	<legend>Rok pla&#263;anja</legend>
	<fieldset>
		<legend><a href="javascript:;" onclick="#">Kalendar</a></legend>
		<div id="rok_placanja_pre">&nbsp;</div>
	</fieldset>
	<fieldset>
		<legend><label for="sRokPlacanjaTekst">Tekst</label></legend>
		<textarea onclick="CalcAll();" class="opaska" onkeyup="searchSuggest2(this, event);" id="sRokPlacanjaTekst" name="sRokPlacanjaTekst" cols="5" rows="8"></textarea>
	</fieldset>
</fieldset>
<fieldset>
	<legend><label for="sPrilog">Napomena</label></legend>
	<textarea class="opaska" onkeyup="searchSuggest2(this, event);" id="sPrilog" name="sPrilog" cols="30" rows="5"></textarea>
</fieldset>
<fieldset>
	<legend><label for="sSvrhaPopusta">Svrha popusta</label></legend>
	<textarea class="opaska" id="sSvrhaPopusta" onkeyup="searchSuggest2(this, event);" name="sSvrhaPopusta" cols="30" rows="5"></textarea>
</fieldset>
<fieldset style="display:none;">
	<legend>Popust</legend>
	<input name="nGlobalPopust" size="2" type="text" id="nGlobalPopust" value="" />
</fieldset>
<fieldset style="display:none;">
	<legend>Rabat</legend>
	<input name="bRabat" type="checkbox" id="bRabat" value="ok" /> <input name="nGlobalRabat" size="2" type="text" id="nGlobalRabat" value="" />
</fieldset>
<fieldset>
	<legend><a href="javascript: void(0);" onclick="javascript: PickDate('datum_izdavanja');">Datum izdavanja</a></legend>
	<div id="datum_izdavanja">&nbsp;</div>
</fieldset>
<fieldset>
	<legend><label for="extra_discount_txt_1">Određena suma je plaćena unaprijed</label></legend>
	<ol>
	  <li><input id="extra_discount_txt_1" name="extra_discount_txt_1" type="text" /> : <input id="extra_discount_money_1" name="extra_discount_money_1" type="text" /> kn</li>
	  <li><input id="extra_discount_txt_2" name="extra_discount_txt_2" type="text" /> : <input id="extra_discount_money_2" name="extra_discount_money_2" type="text" /> kn</li>
	  <li><input id="extra_discount_txt_3" name="extra_discount_txt_3" type="text" /> : <input id="extra_discount_money_3" name="extra_discount_money_3" type="text" /> kn</li>
	  <li><input id="extra_discount_txt_4" name="extra_discount_txt_4" type="text" /> : <input id="extra_discount_money_4" name="extra_discount_money_4" type="text" /> kn</li>
	  <li><input id="extra_discount_txt_5" name="extra_discount_txt_5" type="text" /> : <input id="extra_discount_money_5" name="extra_discount_money_5" type="text" /> kn</li>
	  <li><input id="extra_discount_txt_6" name="extra_discount_txt_6" type="text" /> : <input id="extra_discount_money_6" name="extra_discount_money_6" type="text" /> kn</li>
	  <li><input id="extra_discount_txt_7" name="extra_discount_txt_7" type="text" /> : <input id="extra_discount_money_7" name="extra_discount_money_7" type="text" /> kn</li>
	  <li><input id="extra_discount_txt_8" name="extra_discount_txt_8" type="text" /> : <input id="extra_discount_money_8" name="extra_discount_money_8" type="text" /> kn</li>
	  <li><input id="extra_discount_txt_9" name="extra_discount_txt_9" type="text" /> : <input id="extra_discount_money_9" name="extra_discount_money_9" type="text" /> kn</li>
	  <li><input id="extra_discount_txt_10" name="extra_discount_txt_10" type="text" /> : <input id="extra_discount_money_10" name="extra_discount_money_10" type="text" /> kn</li>
	</ol>
</fieldset>
<p>
<button type="submit" name="submit" onclick="CalcAll();">OFORMI RAČUN &raquo;</button>
</p>



</form>

<script>SuggestLoad()</script>