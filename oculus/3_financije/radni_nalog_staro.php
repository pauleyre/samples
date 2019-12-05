<?php
	error_reporting(0);
	require("class.financije.php");
	(object) $oRN = new Financije;
	$oRN -> LoadRNOldVersion();

	$sDropDown = "";
	switch($_GET["ftype"])
	{
		case "pr":
			$sDropDown = "predračuni";
			$sDropDownTitl = "KREIRAJ NOVI PREDRAČUN";			
			$sNew = "Kreiraj novi predračun?";
			$sLoad = "Da li ste sigurni da želite učitati novi predračun?";
		break;
		case "procjena":
			$sDropDown = "procjene";
			$sDropDownTitl = "KREIRAJ NOVU PROCJENU";
			$sNew = "Kreiraj novu procjenu?";
			$sLoad = "Da li ste sigurni da želite učitati novu procjenu?";
		break;
		case "ponuda":
			$sDropDown = "ponude";
			$sDropDownTitl = "KREIRAJ NOVU PONUDU";			
			$sNew = "Kreiraj novu ponudu?";
			$sLoad = "Da li ste sigurni da želite učitati novu ponudu?";		
		break;
		default:
			$sDropDown = "radni nalozi";
			$sDropDownTitl = "KREIRAJ NOVI RADNI NALOG";
			$sNew = "Kreiraj novi radni nalog?";
			$sLoad = "Da li ste sigurni da želite učitati novi radni nalog?";
		break;
	}

?>
<script type="text/javascript">

	var sRadID = "";
	var sSwitch = "";
	var nID = null;

	function ShowExtraOrder() {
		window.document.getElementById("sOtherDesc").disabled = false;
	}

	function HideExtraOrder() {
		window.document.getElementById("sOtherDesc").disabled = true;
	}

	function LoadRNOldVersion(sFType, id)
	{
		var oRadniNalozi = window.document.getElementById("stare_verzije");
		var nRN = oRadniNalozi.options[oRadniNalozi.selectedIndex].value;

			if(nRN == "newest") {
				window.location = "index.php?page=fin&ftype=" + sFType + "&id=" + id;
			}
			else  {
				window.location = "index.php?page=fin&ftype=" + sFType + "&id=" + id + "&ver=" + nRN;
			}
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

	function LoadRN(sFType)
	{
		var oRadniNalozi = window.document.getElementById("radni_nalozi");
		var nRN = oRadniNalozi.options[oRadniNalozi.selectedIndex].value;

		var sPitanje = (nRN == "new") ? "<?= $sNew; ?>" : "<?= $sLoad; ?>";

		if(window.confirm(sPitanje))
		{
			if(nRN == "new") {
				window.location = "index.php?page=fin&ftype=" + sFType;
			}
			else 
			{
				if(nRN != "none") {
					window.location = "index.php?page=fin&ftype=" + sFType + "&id=" + nRN;
				}
			}
		}	
	}

</script>

<table width="500px" border="0">
	<tr>
		<td colspan="6"><a href="index.php?page=novi_todo&amp;ftype=rn">TODO</a> | <a href="index.php?page=fin&amp;ftype=rn">RADNI NALOG</a> | <a href="index.php?page=fin&amp;ftype=pr">PREDRAČUN</a> | <a href="index.php?page=fin&amp;ftype=procjena">PROCJENA</a> | <a href="index.php?page=fin&amp;ftype=ponuda">PONUDA</a></td>
	</tr>
	<tr>
		<td colspan="6">
		<select id="radni_nalozi" name="radni_nalozi" onchange="javascript: LoadRN('<?= $_GET["ftype"]; ?>');">
			<option id="none" value="none">-- <?= $sDropDown; ?> --</option>
			<option id="new" value="new"><?= $sDropDownTitl; ?></option>
			<?php $oRN -> DisplayRN(); ?>
		</select></td>
	</tr>
	<?php
	if($_POST["nStatus"] == 1)
	{
	?>
	<tr>
		<td colspan="6" style="color: red;">Ovaj radni nalog je označen kao zatvoren.</td>
	</tr>
	<?php
	}
	?>
	<tr>
		<td><strong>KLIJENT</strong></td>
		<td>
			<select name="sKlijent" id="sKlijent">
				<option id="0" value="0">-- klijenti --</option>
				<?= $oRN -> CompanyDisplayClientsDropDown(); ?>
			</select>
			<input type="button" style="font-weight: bold;" value="+" onclick="javascript: AddClient();" />
		</td>
		<td><strong>PROJEKT</strong></td>
		<td><?= $_POST["sProjekt"]; ?></td>
		<td><strong>BROJ</strong></td>
		<td><?= $_POST["nBroj"]; ?></td>
	</tr>
	<tr>
		<td><strong>NARUČENO</strong></td>
		<td><input name="nOrderType" type="radio" value="mail" onclick="javascript: HideExtraOrder();" <?php if($_POST["nOrderType"] == "mail") echo " checked=\"checked\""; ?> />mail</td>
		<td><strong>VODITELJ PROJEKTA / SUPERVIZOR</strong></td>
		<td><?= $_POST["sVoditelj"]; ?></td>
		<td><strong>ROK</strong></td>
		<td><strong><?= strftime("%d.%m.%Y", $_POST["sRok"]); ?></strong></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input name="nOrderType" type="radio" value="contract" onclick="javascript: HideExtraOrder();" <?php if($_POST["nOrderType"] == "contract") echo " checked=\"checked\""; ?> />ugovor</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><strong>VERZIJA</strong></td>
		<td><input type="hidden" value="<?= $_POST["sVerzija"]; ?>" id="sVerzija" name="sVerzija" />
<select id="stare_verzije" name="stare_verzije" onchange="javascript: LoadRNOldVersion('<?= $_GET["ftype"]; ?>', '<?= $_GET["id"]; ?>');">
<?= $oRN -> GetVersionDropDown(); ?>
</select></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input name="nOrderType" type="radio" value="phone" onclick="javascript: HideExtraOrder();" <?php if($_POST["nOrderType"] == "phone") echo " checked=\"checked\""; ?> />telefon</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input name="nOrderType" type="radio" value="other" onclick="javascript: ShowExtraOrder();" <?php if($_POST["nOrderType"] == "other") echo " checked=\"checked\""; ?> />drugo</td>
		<td colspan="4"><?= $_POST["sOtherDesc"]; ?></td>
	</tr>
	<tr>
		<td colspan="6"><strong>OPIS PROJEKTA</strong></td>
	</tr>
	<tr>
		<td colspan="6"><?= $_POST["sOpis"]; ?></td>
	</tr>
	<tr>
		<td colspan="6">&nbsp;</td>
	</tr>
</table>
<table>
	<tr>
		<td colspan="5"><strong>POJEDINI POSLOVI</strong></td>
	</tr>
	<tr>
		<td><strong>IME</strong></td>
		<td><strong>OPIS</strong></td>
		<td><strong>ROK POČ.</strong></td>
		<td><strong>ROK ZAV.</strong></td>
		<td><strong>TOTAL</strong></td>
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
		<td>
			<select name="sZaposlenikPojedino<?= $i; ?>" id="sZaposlenikPojedino<?= $i; ?>">
				<option id="0" value="0">-- zaposlenici --</option>
				<?= $oRN -> CompanyDisplayEmployeesDropDown($i); ?>
			</select>
		</td>
		<td><?= $_POST["sOpisPojedino$i"]; ?></td>
		<td><?= $sTextPocetakRok; ?></td>
		<td><?= $sTextKrajRok; ?></td>
		<td><?= $_POST["nTotalPojedino$i"]; ?></td>
	</tr>
	<?php
			$i ++;
		}
	?>
	<tr>
		<td colspan="5">&nbsp;</td>
	</tr>
</table>