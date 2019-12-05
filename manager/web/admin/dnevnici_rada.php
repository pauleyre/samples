<?php
	error_reporting(0);
		//require($_SERVER["DOCUMENT_ROOT"]."/classlib/public/classlib.php");

	require("4_evidencije/class.evidencije.php");
	(object) $oDnevnik = new Evidencije;
?>

<script type="text/javascript">

	var sRadID = "";

	function PickDate(sID)
	{
		sRadID = sID;
		var nTop = Math.floor(screen.height/2-180/2);
		var nLeft = Math.floor(screen.width/2-300/2);

		if(window.open) {
			window.open("_modals/datePicker.html", "", "height=180, width=300, resize=0, top=" + nTop + ", left=" + nLeft);
		}
	}

	// funkcija koju poziva date picker kako bi postavio izabrani datum
	function ChangeDate(nDay, nMonth, nYear)
	{
		var sStr = "";
		var sDate = humanToTime(nYear, nMonth, nDay, 0, 0, 0);
		if((nDay == 0) && (nMonth == 0) && (nYear == 0)) {
			sStr = "non-stop";
		}
		else {
			sStr = nDay + ". " + nMonth + ". " + nYear;
		}

		var sExtra = (sRadID == "sOd_pre") ? "<input name=\"sOd\" type=\"hidden\" id=\"sOd\" value=\"" + sDate + "\" size=\"30\" />" : "<input name=\"sDo\" type=\"hidden\" id=\"sDo\" value=\"" + sDate + "\" size=\"30\" />";

		window.document.getElementById(sRadID).innerHTML = "<a href=\"javascript: void(0);\" onclick=\"javascript: PickDate('"+ sRadID +"');\">" + sStr + "</a>" + sExtra;
	}
	
	function humanToTime(year, month, day, hour, minute, sec)
	{
		var humDate = new Date(Date.UTC(year, (stripLeadingZeroes(month)-1), stripLeadingZeroes(day), stripLeadingZeroes(hour), stripLeadingZeroes(minute), stripLeadingZeroes(sec)));

		return (humDate.getTime() / 1000.0);
	}

	function stripLeadingZeroes(input)
	{
		if((input.length > 1) && (input.substr(0, 1) == "0")) {
			return input.substr(1);
		}
		else {
			return input;
		}
	}
	
	</script>

<fieldset>
<legend><strong>PREGLED PO ZAPOSLENIKU</strong></legend>
<table width="99%">
	<tr>
		<td><strong>zaposlenik</strong></td>
		<td><strong>od</strong></td>
		<td>&nbsp;</td>
		<td><strong>do</strong></td>
		<td>&nbsp;</td>
	    <td>&nbsp;</td>
	</tr>
	<tr>
		<td>
		<select name="sPregledZap" id="sPregledZap">
		  <?= $oDnevnik -> DisplayEmployeeDropDown(); ?>
        </select></td>
		<td id="sOd_pre"><a href="javascript: void(0);" onclick="javascript: PickDate('sOd_pre');">DD.MM.GGGG</a><input name="sOd" type="hidden" id="sOd" value="0" size="30" /></td>
		<td>&nbsp;</td>
		<td id="sDo_pre"><a href="javascript: void(0);" onclick="javascript: PickDate('sDo_pre');">DD.MM.GGGG</a><input name="sDo" type="hidden" id="sDo" value="<?= time(); ?>" size="30" /></td>
		<td>&nbsp;</td>
		<td><input name="bPregled" type="submit" id="bPregled" value="pregled" /></td>
	</tr>
	<tr>
		<td colspan="6">&nbsp;</td>
	</tr>
</table>
</fieldset>
<fieldset>
<legend><strong>TABLICA</strong></legend>
<table width="99%">
<tr>
	<td>Klijent</td>
	<td>Projekt</td>
	<td>Od</td>
	<td>Do</td>
	<td>Total</td>
</tr>
<?= $oDnevnik -> DisplayDnevniciRada(); ?>
</table>
</fieldset>
<fieldset>
	<legend><strong>GRAPH</strong></legend>
	<div style="float: left;"><img style="height: 200px; width: 600px;" title="Graph" src="_modals/class.graph.php?input=<?= implode('|', $_SESSION["zaposlenik_graph"]); ?>" /></div>
	<div style="float: left; padding-left: 1em;">
		<h2 style="padding-bottom: 140px; margin: 0; color: #000000;"><?= $_SESSION["graph_highest"]; ?></h2>
		<h2 style=" color: #000000;">0</h2>
	</div>
</fieldset>