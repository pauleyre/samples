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
<fieldset>
<legend>DODJELA VANPROJEKTNIH DNEVNIH ZADATAKA</legend>
<table>
		<tr>
		<td width="150"><strong>Ime</strong></td>
		<td width="450"><strong>Dnevni zadatak</strong></td>
		<td width="100" style="padding-left: 1em;"><strong>Rok poč.</strong></td>
		<td width="100"><strong>rok zav.</strong></td>
	</tr>
	<?php

		(int) $i = 0;
		(string) $sFinished = "";

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
		<td><select name="sZaposlenikPojedino<?= $i; ?>" id="sZaposlenikPojedino<?= $i; ?>">
          <option id="0" value="0">-- zaposlenici --</option>
          <?= $oRN -> CompanyDisplayEmployeesDropDown($i); ?>
        </select><input name="nTotalPojedino<?= $i; ?>" type="hidden" id="nTotalPojedino<?= $i; ?>" size="2" /></td>
		<td><input class="boxkalkulacija" name="sOpisPojedino<?= $i; ?>" type="text" id="sOpisPojedino<?= $i; ?>" style="width: 99%;" /></td>
		<td  style="padding-left: 1em;" id="pocetak_rok_pre<?= $i; ?>"><a href="javascript: void(null);" class="txt" onclick="javascript: PickDate('pocetak_rok_pre<?= $i; ?>', 'pocetak', <?= $i; ?>);"><?= $sTextPocetakRok; ?></a><input name="sPocetakRok<?= $i; ?>" type="hidden" id="sPocetakRok<?= $i; ?>" value="<?= $_POST["sPocetakRok$i"]; ?>" /></td>
		<td id="zavrsetak_rok_pre<?= $i; ?>"><a href="javascript: void(null);" class="txt" onclick="javascript: PickDate('zavrsetak_rok_pre<?= $i; ?>', 'zavrsetak', <?= $i; ?>);"><?= $sTextKrajRok; ?></a><input name="sZavrsetakRok<?= $i; ?>" type="hidden" id="sZavrsetakRok<?= $i; ?>" value="<?= $_POST["sZavrsetakRok$i"]; ?>" /></td>
	</tr>
	<?php
			$i ++;
		}
	?>
</table>
<input class="little" id="SaveTodo" name="SaveTodo" type="submit" value="spremi" />
</fieldset>
<fieldset>
<legend>NEIZVRŠENI DNEVNI ZADACI</legend>
<table>
	<tr>
		<td><strong>Zaposlenik</strong></td>
		<td><strong>Dnevni zadatak</strong></td>
		<td><strong>Rok zav.</strong></td>
		<td><strong>Total</strong></td>
		<td><strong>Opaska</strong></td>
	</tr>
	<tr>
		<td colspan="5"><?php $oRN -> GenerateTodoList(0); ?></td>
	</tr>
</table>
</fieldset>
<fieldset>
<legend>IZVRŠENI DNEVNI ZADACI <em>(zadnjih <?= SINGLE_JOBS; ?>)</em></legend>
<table>
	<tr>
		<td colspan="5"><span class="dark10"><strong></span></td>
	</tr>
	<tr>
		<td><strong>Zaposlenik</strong></td>
		<td><strong>Dnevni zadatak</strong></td>
		<td><strong>Zav.</strong></td>
		<td><strong>Total</strong></td>
		<td><strong>Opaska</strong></td>
	</tr>
	<tr>
		<td colspan="5"><?php $oRN -> GenerateTodoList(1); ?></td>
	</tr>
</table>
</fieldset>
