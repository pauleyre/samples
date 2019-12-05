<?php
	error_reporting(0);
	require($_SERVER['DOCUMENT_ROOT'].'/classlib/public/classlib.php');
	session_start();

	require('class.financije.php');
	(object) $oKalkulacija = new Financije;
	$oKalkulacija -> MainRN();	
	error_reporting(0);
?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml" lang="hr" xml:lang="hr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kalkulacija</title>
<link rel='stylesheet' href='../gfx/styleis.css' />

<style type="text/css" media="all">
/*<![CDATA[*/
body {
	background-color: #FDF9ED;
}
td {
	vertical-align: top;
	background-color: #FDF9ED;
}
/*]]>*/
</style>
<script type="text/javascript">
//<![CDATA[
	var sRadID = "";
	var aIDlist;

	function CalcAll()
	{
		var ukupno;
		var kolicina;
		var cijena;
		var popust;
		var rabat;
		var i = 0;

		while(i < <?= SINGLE_JOBS; ?>)
		{
			id = aIDlist[i];

			ukupno = "nUkupno_" + id;
			kolicina = "nKolicina_" + id;
			cijena = "nCijena_" + id;
			popust = "nPopust_" + id;
			rabat = "nRabat_" + id;

			if(window.document.getElementById("sVrstaTroska_" + id).value != "") {
				Ukupno(ukupno, kolicina, cijena, popust, rabat);
			}
			i ++;
		}
	}

	function Ukupno(ukupno, kolicina, cijena, popust, rabat)
	{
		var x = window.document.getElementById(popust).value;
		var y = window.document.getElementById(rabat).value;

		var nPDV = (window.document.getElementById("bNoPDV").checked == true) ? 1.00 : 1.22;
		if((x == null || x == "" || x == 0) && (y == null || y == "" || y == 0)) {
			window.document.getElementById(ukupno).value = (parseFloat(window.document.getElementById(kolicina).value * window.document.getElementById(cijena).value).toFixed(2));
		}
		else
		{
			// popust
			window.document.getElementById(ukupno).value = (window.document.getElementById(kolicina).value * window.document.getElementById(cijena).value);
			var nPopust = window.document.getElementById(ukupno).value / (100 / window.document.getElementById(popust).value);
			window.document.getElementById(ukupno).value = (parseFloat(window.document.getElementById(ukupno).value - nPopust)).toFixed(2);
			// rabat
			var nRabat = window.document.getElementById(ukupno).value / (100 / window.document.getElementById(rabat).value);
			window.document.getElementById(ukupno).value = (parseFloat(window.document.getElementById(ukupno).value - nRabat)).toFixed(2);

			window.document.getElementById("nGlobalPopust").value = x;

			window.document.getElementById("nGlobalRabat").value = y;
			window.document.getElementById("bRabat").checked = true;
		}
		// pdv
		window.document.getElementById(ukupno).value = (parseFloat(window.document.getElementById(ukupno).value * nPDV).toFixed(2));
	}

	function PickDate(sID)
	{
		sRadID = sID;
		var nTop = Math.floor(screen.height/2-180/2);
		var nLeft = Math.floor(screen.width/2-300/2);

		if(window.open) {
			window.open("../_modals/datePicker.html", "", "height=180, width=300, top=" + nTop + ", left=" + nLeft);
		}
	}

	// funkcija koju poziva date picker kako bi postavio izabrani datum
	function ChangeDate(nDay, nMonth, nYear)
	{
		var sStr = "";
		var sDate = nDay + "." + nMonth + "." + nYear + ".";
		if((nDay == 0) && (nMonth == 0) && (nYear == 0)) {
			sStr = "non-stop";
		}
		else {
			sStr = nDay + ". " + nMonth + ". " + nYear;
		}

		var sExtra = (sRadID == "rok_placanja_pre") ? "<input name=\"rok_placanja\" type=\"hidden\" id=\"rok_placanja\" value=\"" + sDate + "\" size=\"30\" />" : "<input name=\"datum_izdavanja\" type=\"hidden\" id=\"datum_izdavanja\" value=\"" + sDate + "\" size=\"30\" />";

		window.document.getElementById(sRadID).innerHTML = "<b>" + sStr + "</b>" + sExtra;
	}

	function AddClient()
	{		
		if(window.open) {
			window.open("../index.php?page=adresar");
		}
	}

	function SelectBox(selement, scheck)
	{
		var elementx = window.document.getElementById(selement);
		var checkx = window.document.getElementById(scheck);

		try
		{
			if(elementx.innerText != "") {
				checkx.checked = true;
			}
			else {
				checkx.checked = false;
			}
		}
		catch(e) {}
		finally
		{
			if(elementx.value != "") {
				checkx.checked = true;
			}
			else {
				checkx.checked = false;
			}
		}
	}

	function Validate()
	{
		var oKlijent = window.document.getElementById("sKlijentQuick");
		var sKlijent = oKlijent.options[oKlijent.selectedIndex].getAttribute("value");

		if(typeof sKlijent == 'undefined' || sKlijent == null)
		{
			window.alert("Odaberite klijenta.");
			oKlijent.focus();
			return false;
		}

		var svrha_popusta_txt = window.document.getElementById("sSvrhaPopusta");

		if(svrha_popusta_txt.value == "" && window.document.getElementById("nGlobalPopust").value != "")
		{
			window.alert("Niste upisali svrhu popusta");
			svrha_popusta_txt.focus();
			return false;
		}
		return true;
	}

	function humanToTime(year, month, day, hour, minute, sec)
	{
		var humDate = new Date(Date.UTC(year, (stripLeadingZeroes(month)-1), stripLeadingZeroes(day), stripLeadingZeroes(hour), stripLeadingZeroes(minute), stripLeadingZeroes(sec)));

		return (humDate.getTime() / 1000.0);
	}
//]]>
</script>
<script src="../_modals/suggest.js" type="text/javascript"></script>
<script src="../_modals/error.js" type="text/javascript"></script>

</head>

<body onload="javascript: SuggestLoad();">
<div id="search_suggest"></div>
<form id="kalkulacija" action="print.php?ftype=<?= $_GET['ftype']; ?>" method="post" onsubmit="javascript: return Validate();">
<fieldset>
<legend style="font-size: 1.5em; font-weight: bold;">KALKULACIJA</legend>
	<table cellpadding="0" cellspacing="2">
		<tr style="font-weight: bold;">
			<td width="300"><span class="adresarkat">Vrsta tro&#353;ka</span></td>
			<td width="100"><span class="adresarkat">Koli&#269;ina (#)</span></td>
			<td width="200"><span class="adresarkat">Cijena (kn)</span></td>
			<td width="200"><span class="adresarkat">Ukupno (kn)</span></td>
			<td width="100"><span class="adresarkat">Popust (%)</span></td>
			<td width="100"><span class="adresarkat">Rabat (%)</span></td>
			<td width="100"><span class="adresarkat">Za fakturu</span></td>
		</tr>
	<?php
		$oKalkulacija -> Kalkulacija();
	?>
	</table>
<fieldset>
<legend><label for="bNoPDV">Oslobođenje od PDV-a</label></legend>
		  <input name="bNoPDV" type="checkbox" id="bNoPDV" value="ok" />
</fieldset>
			<?php
		if($_GET['ftype'] == 'racun')
		{
	?>
<fieldset>
<legend><label for="sTipDoc">Tip</label></legend>
				<select name="sTipDoc" id="sTipDoc">
				<optgroup label="Ra&#269;uni">
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
	<input style="text-align: right;" onkeyup="error_check2('sBrojQuick', '<?= $_GET['ftype']; ?>');" type="text" id="sBrojQuick" name="sBrojQuick" value="<?= date('/m/Y', time()); ?>" />
</fieldset>	
<fieldset>
	<legend><label for="sKlijentQuick">Klijent</label></legend>
	<select name="sKlijentQuick" id="sKlijentQuick">
		<optgroup label="klijent">
		<?= $oKalkulacija -> CompanyDisplayClientsDropDown(); ?>
		</optgroup>
	</select><input type="button" style="font-weight: bold;" value="+" onclick="javascript: AddClient();" />
</fieldset>	

	<?php
		}
	?>
<fieldset>
	<legend>Rok pla&#263;anja</legend>
	<fieldset>
		<legend><a href="javascript: void(0);" onclick="javascript: PickDate('rok_placanja_pre');">Kalendar</a></legend>
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
	<input class="boxkalkulacija" name="nGlobalPopust" size="2" type="text" id="nGlobalPopust" value="" />
</fieldset>
<fieldset style="display:none;">
	<legend>Rabat</legend>
	<input name="bRabat" type="checkbox" id="bRabat" value="ok" /> <input name="nGlobalRabat" size="2" class="boxkalkulacija" type="text" id="nGlobalRabat" value="" />
</fieldset>
<fieldset>
	<legend><a href="javascript: void(0);" onclick="javascript: PickDate('datum_izdavanja');">Datum izdavanja</a></legend>
	<div id="datum_izdavanja">&nbsp;</div>
</fieldset>
<fieldset>
	<legend><label for="extra_discount_txt_1">Određena suma je plaćena unaprijed</label></legend>
	<ol>
	  <li><input id="extra_discount_txt_1" name="extra_discount_txt_1" type="text" class="boxkalkulacija" /> : <input id="extra_discount_money_1" name="extra_discount_money_1" type="text" class="boxkalkulacija" /> kn</li>
	  <li><input id="extra_discount_txt_2" name="extra_discount_txt_2" type="text" class="boxkalkulacija" /> : <input id="extra_discount_money_2" name="extra_discount_money_2" type="text" class="boxkalkulacija" /> kn</li>
	  <li><input id="extra_discount_txt_3" name="extra_discount_txt_3" type="text" class="boxkalkulacija" /> : <input id="extra_discount_money_3" name="extra_discount_money_3" type="text" class="boxkalkulacija" /> kn</li>
	  <li><input id="extra_discount_txt_4" name="extra_discount_txt_4" type="text" class="boxkalkulacija" /> : <input id="extra_discount_money_4" name="extra_discount_money_4" type="text" class="boxkalkulacija" /> kn</li>
	  <li><input id="extra_discount_txt_5" name="extra_discount_txt_5" type="text" class="boxkalkulacija" /> : <input id="extra_discount_money_5" name="extra_discount_money_5" type="text" class="boxkalkulacija" /> kn</li>
	  <li><input id="extra_discount_txt_6" name="extra_discount_txt_6" type="text" class="boxkalkulacija" /> : <input id="extra_discount_money_6" name="extra_discount_money_6" type="text" class="boxkalkulacija" /> kn</li>
	  <li><input id="extra_discount_txt_7" name="extra_discount_txt_7" type="text" class="boxkalkulacija" /> : <input id="extra_discount_money_7" name="extra_discount_money_7" type="text" class="boxkalkulacija" /> kn</li>
	  <li><input id="extra_discount_txt_8" name="extra_discount_txt_8" type="text" class="boxkalkulacija" /> : <input id="extra_discount_money_8" name="extra_discount_money_8" type="text" class="boxkalkulacija" /> kn</li>
	  <li><input id="extra_discount_txt_9" name="extra_discount_txt_9" type="text" class="boxkalkulacija" /> : <input id="extra_discount_money_9" name="extra_discount_money_9" type="text" class="boxkalkulacija" /> kn</li>
	  <li><input id="extra_discount_txt_10" name="extra_discount_txt_10" type="text" class="boxkalkulacija" /> : <input id="extra_discount_money_10" name="extra_discount_money_10" type="text" class="boxkalkulacija" /> kn</li>
	</ol>
</fieldset>
<p>
<button type="submit" name="bPrint" id="bPrint" onclick="CalcAll();" style="font-size: 1.5em;">OFORMI RA&#268;UN &raquo;</button>
</p>
<input type="hidden" value="<?= $_POST['sTitle']; ?>" id="sTitle" name="sTitle" />
<input type="hidden" value="<?= $_GET['id']; ?>" id="nRN_ID" name="nRN_ID" />
<input name="bPDF" type="hidden" id="bPDF" value="ok" />
<input type="hidden" id="quick_timestamp" name="quick_timestamp"  />
</fieldset>
</form>
</body>
</html>