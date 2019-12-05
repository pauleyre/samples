<?php
	error_reporting(0);
	require("class.financije.php");
	(object) $oRN = new Financije;
	$oRN -> SaveTodoJobList();

?>
<script type="text/javascript">
	function PickDate(sID, sMySwitch, nMyID)
	{
		sRadID = sID;
		sSwitch = sMySwitch;
		nID = nMyID;
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
		var sDate = humanToTime(nYear , nMonth , nDay, 0, 0, 0);
		if((nDay == 0) && (nMonth == 0) && (nYear == 0)) {
			sStr = "non-stop";
		}
		else {
			sStr = nDay + ". " + nMonth + ". " + nYear;
		}

		var sExtra = "";

		if(sSwitch == "rok") {
			sExtra = "<input name=\"sRok\" type=\"hidden\" id=\"sRok\" value=\"" + sDate + "\" size=\"30\" />"
		}
		else {
			sExtra = (sSwitch == "pocetak") ? "<input name=\"sPocetakRok"+nID+"\" type=\"hidden\" id=\"sPocetakRok"+nID+"\" value=\"" + sDate + "\" size=\"30\" />" : "<input name=\"sZavrsetakRok"+nID+"\" type=\"hidden\" id=\"sZavrsetakRok"+nID+"\" value=\"" + sDate + "\" size=\"30\" />";
		}

		window.document.getElementById(sRadID).innerHTML = "<a href=\"javascript: void(0);\" class=\"txt\" onclick=\"javascript: PickDate('"+ sRadID +"', '"+sSwitch+"', "+nID+");\">" + sStr + "</a>" + sExtra;
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

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding-left: 1em;">
	<tr>
		<td width="75%" height="50" style="padding-left: 1em;"><strong>DNEVNI ZADATAK - vanprojektni</strong></td>
		<td style="padding-left: 1em;"><strong>ROK POČ.</strong></td>
		<td><strong>ROK ZAV.</strong></td>
		<td></td>
	</tr>
	<?php

		(int) $i = 0;
		(string) $sFinished = '';

		while($i < SINGLE_JOBS)
		{
			$_POST["sZaposlenikPojedino$i"] = isset($_POST["sZaposlenikPojedino$i"]) ? $_POST["sZaposlenikPojedino$i"] : NULL;
			$_POST["sOpisPojedino$i"] = isset($_POST["sOpisPojedino$i"]) ? $_POST["sOpisPojedino$i"] : NULL;
			$_POST["sPocetakRok$i"] = isset($_POST["sPocetakRok$i"]) ? $_POST["sPocetakRok$i"] : NULL;
			$_POST["sZavrsetakRok$i"] = isset($_POST["sZavrsetakRok$i"]) ? $_POST["sZavrsetakRok$i"] : NULL;
			$_POST["nTotalPojedino$i"] = isset($_POST["nTotalPojedino$i"]) ? $_POST["nTotalPojedino$i"] : NULL;
			$sTextPocetakRok = isset($_POST["sPocetakRok$i"]) ? strftime("%d.%m.%Y", $_POST["sPocetakRok$i"]) : "dd.mm.gggg";
			$sTextKrajRok = isset($_POST["sZavrsetakRok$i"]) ? strftime("%d.%m.%Y", $_POST["sZavrsetakRok$i"]) : "dd.mm.gggg";

			$sFinished = ($_POST["sStatus$i"] == 1) ? "style=\"background-color: green;\"" : "";

	?>
	<tr valign="top" <?= $sFinished; ?>>
		<td style="padding-left: 1em;" height="30" align="center">
		<input type="hidden" name="sZaposlenikPojedino<?= $i; ?>" id="sZaposlenikPojedino<?= $i; ?>" value="<?= $_SESSION['zaposlenik_id']; ?>" />	
	  <input class="boxkalkulacija" name="sOpisPojedino<?= $i; ?>" type="text" id="sOpisPojedino<?= $i; ?>" style="width: 99%;" /></td>
		<td id="pocetak_rok_pre<?= $i; ?>" style="padding-left: 1em;"><a href="javascript: void(0);" class="txt" onclick="javascript: PickDate('pocetak_rok_pre<?= $i; ?>', 'pocetak', <?= $i; ?>);"><?= $sTextPocetakRok; ?></a><input name="sPocetakRok<?= $i; ?>" type="hidden" id="sPocetakRok<?= $i; ?>" value="<?= $_POST["sPocetakRok$i"]; ?>" /></td>
		<td id="zavrsetak_rok_pre<?= $i; ?>"><a href="javascript: void(0);" class="txt" onclick="javascript: PickDate('zavrsetak_rok_pre<?= $i; ?>', 'zavrsetak', <?= $i; ?>);"><?= $sTextKrajRok; ?></a><input name="sZavrsetakRok<?= $i; ?>" type="hidden" id="sZavrsetakRok<?= $i; ?>" value="<?= $_POST["sZavrsetakRok$i"]; ?>" /></td>
		<td><input name="nTotalPojedino<?= $i; ?>" type="hidden" id="nTotalPojedino<?= $i; ?>" size="2" /></td>
	</tr>
	<?php
			$i ++;
		}
	?>
	<tr>
		<td colspan="5">&nbsp;</td>
	</tr>	
	<tr>
		<td colspan="5" style="padding-left: 1em;"><input class="little" id="SaveTodo" name="SaveTodo" type="submit" value="spremi &dArr;" /></td>
	</tr>
</table>