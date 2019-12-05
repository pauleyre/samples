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
/*<![CDATA[*/
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
			window.open("../_modals/datePicker.html", "", "height=180, width=300, resize=0, top=" + nTop + ", left=" + nLeft);
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

		window.document.getElementById(sRadID).innerHTML = "<strong>" + sStr + "</strong>" + sExtra;
	}

	function AddClient()
	{
		var nTop = Math.floor(screen.height/2-500/2);
		var nLeft = Math.floor(screen.width/2-360/2);
		
		if(window.open) {
			window.open("../_modals/klijent.php", "", "height=500, width=360, resize=0, scrollbars=yes, top=" + nTop + ", left=" + nLeft);
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

		if(sKlijent == "0")
		{
			window.alert("Odaberite klijenta.");
			return false;
		}

		if(window.document.getElementById("sSvrhaPopusta").value == "" && window.document.getElementById("nGlobalPopust").value != "")
		{
			window.alert("Niste upisali svrhu popusta");
			return false;
		}
		return true;
	}

	function humanToTime(year, month, day, hour, minute, sec)
	{
		var humDate = new Date(Date.UTC(year, (stripLeadingZeroes(month)-1), stripLeadingZeroes(day), stripLeadingZeroes(hour), stripLeadingZeroes(minute), stripLeadingZeroes(sec)));

		return (humDate.getTime() / 1000.0);
	}
		
/*]]>*/
</script>
<script src="../_modals/suggest.js" type="text/javascript"></script>
<script src="../_modals/error.js" type="text/javascript"></script>

</head>

<body onload="javascript: SuggestLoad();">
<div id="search_suggest"></div>

<form id="kalkulacija" action="print.php?ftype=<?= $_GET['ftype']; ?>" method="post" onsubmit="javascript: return Validate();">
	<table width="56%" cellpadding="0" cellspacing="0" style="height: 100%;">
		<tr style="font-weight: bold;">
			<td width="14%" height="40"><span class="adresarkat">Vrsta tro&#353;ka</span></td>
			<td width="14%"><span class="adresarkat">Koli&#269;ina (#)</span></td>
			<td width="14%"><span class="adresarkat">Cijena (kn)</span></td>
			<td width="14%"><span class="adresarkat">Ukupno (kn)</span></td>
			<td width="14%"><span class="adresarkat">Popust (%)</span></td>
			<td width="14%"><span class="adresarkat">Rabat (%)</span></td>
			<td width="14%"><span class="adresarkat">Za fakturu</span></td>
		</tr>
	<?php
		$oKalkulacija -> Kalkulacija();
	?>
		<tr>
			<td height="30" colspan="7">Oslobođenje od PDV-a: 
		  <input name="bNoPDV" type="checkbox" id="bNoPDV" value="ok" /></td>
		</tr>
			<?php
		if($_GET['ftype'] == 'racun')
		{
	?>
		<tr>
			<td height="30">Tip:</td>
		    <td height="30" colspan="6">
				<select name="sTipDoc" id="sTipDoc">
					<option value="RAČUN">Ra&#269;un</option>
					<option value="RAČUN ZA PRIMLJENI PREDUJAM">Račun za primljeni predujam</option>					
					<option value="PONUDA">Ponuda</option>
					<option value="PROCJENA">Procjena</option>
					<option value="PREDPONUDA">Predponuda</option>
		  </select>			</td>
		</tr>
		<tr>
			<td height="30">Oznaka:</td>
		    <td height="30" colspan="6"><input style="text-align: right;" onkeyup="error_check2('sBrojQuick', '<?= $_GET['ftype']; ?>');" type="text" id="sBrojQuick" name="sBrojQuick" value="<?= date('/m/Y', time()); ?>" /></td>
		</tr>
				<tr>
			<td height="30">Klijent:</td>
		    <td height="30" colspan="6">
				<select name="sKlijentQuick" id="sKlijentQuick">
					<option id="0" value="0">-- klijent --</option>
					<?= $oKalkulacija -> CompanyDisplayClientsDropDown(); ?>
				</select><input type="button" style="font-weight: bold;" value="+" onclick="javascript: AddClient();" />			</td>
		</tr>
	<?php
		}
	?>
		<tr>
			<td height="30"><a href="javascript: void(0);" onclick="javascript: PickDate('rok_placanja_pre');">Rok pla&#263;anja:</a></td>
		    <td height="30" colspan="6" id="rok_placanja_pre">&nbsp;</td>
		</tr>
		<tr>
			<td>Rok pla&#263;anja (tekst):</td>
		    <td colspan="6"><textarea onclick="CalcAll();" class="opaska" onkeyup="searchSuggest2(this, event);" id="sRokPlacanjaTekst" name="sRokPlacanjaTekst" cols="5" rows="8"></textarea></td>
		</tr>
		<tr>
			<td>Napomena:</td>
		    <td colspan="6"><textarea class="opaska" onkeyup="searchSuggest2(this, event);" id="sPrilog" name="sPrilog" cols="30" rows="5"></textarea></td>
		</tr>
		<tr>
			<td>Svrha popusta:</td>
		    <td colspan="6"><textarea class="opaska" id="sSvrhaPopusta" onkeyup="searchSuggest2(this, event);" name="sSvrhaPopusta" cols="30" rows="5"></textarea></td>
		</tr>
		<tr>
			<td height="30">Popust:</td>
		    <td height="30" colspan="6"><input class="boxkalkulacija" name="nGlobalPopust" size="2" type="text" id="nGlobalPopust" value="" /></td>
		</tr>
		<tr>
			<td height="30">Rabat:</td>
		    <td height="30" colspan="6"><input name="bRabat" type="checkbox" id="bRabat" value="ok" /> <input name="nGlobalRabat" size="2" class="boxkalkulacija" type="text" id="nGlobalRabat" value="" /></td>
		</tr>
		<tr>
			<td height="30">Printer:</td>
		    <td height="30" colspan="6">
				<select name="sPrinter" id="sPrinter">
				<?php
				if(stristr($_SERVER['HTTP_USER_AGENT'], 'win') !== FALSE)
				{
				?>
					<option value="dc3535">DocuColor 3535 (PC)</option>
					<option value="dpn17" selected="selected">DocuPrint N17 (PC)</option>
				<?php
				}
				else
				{
				?>
					<option value="dc3535-mac">DocuColor 3535 (Mac)</option>
					<option value="dpn17-mac" selected="selected">DocuPrint N17 (Mac)</option>
				<?php
				}
				?>
		  </select>			</td>
		</tr>
		<tr>
			<td><a href="javascript: void(0);" onclick="javascript: PickDate('datum_izdavanja');">Datum izdavanja:</a></td>
		    <td colspan="6" id="datum_izdavanja">&nbsp;</td>
		</tr>
		<tr>
			<td height="236">Određena suma je <br>
		  plaćena unaprijed:</td>
		    <td colspan="6"><ol>
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
	        </ol></td>
		</tr>
		<tr>
		  <td height="60" colspan="7" align="right"><input name="bPrint" type="submit" id="bPrint" onclick="CalcAll();" style="font-weight: bold; width: 250px; height: 50px;" value="OFORMI RA&#268;UN" /></td>
		</tr>
  </table>
<input type="hidden" value="<?= $_POST['sTitle']; ?>" id="sTitle" name="sTitle" />
<input type="hidden" value="<?= $_GET['id']; ?>" id="nRN_ID" name="nRN_ID" />
<input name="bPDF" type="hidden" id="bPDF" value="ok" />
<input type="hidden" id="quick_timestamp" name="quick_timestamp"  />
</form>
</body>
</html>