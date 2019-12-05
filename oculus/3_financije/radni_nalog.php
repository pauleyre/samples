<?php
	error_reporting(0);
	require("class.financije.php");
	(object) $oRN = new Financije;
	$oRN -> MainRN();

	$sDropDown = '';
	switch($_GET["ftype"])
	{
		case "pr":
			$sDropDown = "predračuni";
			$sDropDownTitl = "KREIRAJ NOVI PREDRAČUN";			
			$sNew = "Kreiraj novi predračun?";
			$sLoad = "Da li ste sigurni da želite učitati novi predračun?";
			$_SESSION["bread"] = "<a href=\"index.php?page=fin&amp;ftype=rn\" class=\"bread\"><b>financije</b></a><strong>  ::  predračuni</strong>";
		break;
		case "procjena":
			$sDropDown = "procjene";
			$sDropDownTitl = "KREIRAJ NOVU PROCJENU";
			$sNew = "Kreiraj novu procjenu?";
			$sLoad = "Da li ste sigurni da želite učitati novu procjenu?";
			$_SESSION["bread"] = "<a href=\"index.php?page=fin&amp;ftype=rn\" class=\"bread\"><b>financije</b></a><strong>  ::  procjene</strong>";
		break;
		case "ponuda":
			$sDropDown = "ponude";
			$sDropDownTitl = "KREIRAJ NOVU PONUDU";			
			$sNew = "Kreiraj novu ponudu?";
			$sLoad = "Da li ste sigurni da želite učitati novu ponudu?";
			$_SESSION["bread"] = "<a href=\"index.php?page=fin&amp;ftype=rn\" class=\"bread\"><b>financije</b></a><strong>  ::  ponude</strong>";			
		break;
		default:
			$sDropDown = "radni nalozi";
			$sDropDownTitl = "KREIRAJ NOVI RADNI NALOG";
			$sNew = "Kreiraj novi radni nalog?";
			$sLoad = "Da li ste sigurni da želite učitati novi radni nalog?";
			$_SESSION["bread"] = "<a href=\"index.php?page=fin&amp;ftype=rn\" class=\"bread\"><b>financije</b></a><strong>  ::  radni nalozi</strong>";
		break;
	}

	require("_modals/rte/class.rich_text_editor.php");

	(object) $oRTE = new RichTextEditor;
	echo $oRTE -> RichTextGetResources();

?>
<script>

	var sRadID = "";
	var sSwitch = "";
	var nID = null;
	var __other_desc = null;

	function ShowExtraOrder() {
		__other_desc = new getObj('sOtherDesc');
		__other_desc.obj.disabled = false;
		__other_desc.obj.focus();
	}

	function HideExtraOrder() {
		__other_desc = new getObj('sOtherDesc');
		__other_desc.obj.disabled = true;
	}

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

	function LoadRNOldVersion(sFType, id)
	{
		var __url = '';
		var oRadniNalozi =  new getObj("stare_verzije");
		var nRN = oRadniNalozi.obj.options[oRadniNalozi.obj.selectedIndex].value;

		if(nRN == "newest") {
			__url = "index.php?page=fin&ftype=" + sFType + "&id=" + id;
		}
		else  {
			__url = "index.php?page=fin&ftype=" + sFType + "&id=" + id + "&ver=" + nRN;
		}
		window.location = __url;
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
		var oRadniNalozi = new getObj("radni_nalozi");
		var nRN = oRadniNalozi.obj.options[oRadniNalozi.obj.selectedIndex].value;

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
<script src="_modals/error.js"></script>
<input name="radni_nalog_opis" type="hidden" id="radni_nalog_opis" />
<table width="100%" border="0">
	<tr>
		<td height="40" colspan="6"><table width="98%" border="0">
  <tr>
    <td width="4%" align="center"><a href="index.php?page=pdf_docs"><img src="gfx/get-pdf-doc.gif" title="get PDF docs" width="16" height="16" border="0" /></a></td>
    <td><a href="index.php?page=pdf_docs"><strong>Pregled PDF dokumentacije</strong></a></td>
  </tr>
</table>
</td>
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
		<td colspan="6" style="text-transform: uppercase; border-bottom: thin solid red;"><a onclick="javascript: window.location = '_modals/dl.php?data=<?= base64_encode($_GET["ftype"]."_".$_GET["id"].".pdf"); ?>';" href="javascript: void(null);"><img src="gfx/pdf_logo.gif" style="border: thin dotted #00CC00;" /></a> <strong>Ovaj radni nalog je označen kao zatvoren.</strong></td>
	</tr>
	<?php
	}
	?>
	<tr>
		<td><label for="sKlijent"><strong>KLIJENT</strong></label></td>
		<td>
			<select name="sKlijent" id="sKlijent">
				<option id="0" value="0">-- klijenti --</option>
				<?= $oRN -> CompanyDisplayClientsDropDown(); ?>
			</select>
			<input type="button" style="font-weight: bold;" value="+" onclick="javascript: window.location = '?page=adresar'" />
		</td>
		<td><label for="sProjekt"><strong>PROJEKT</strong></label></td>
		<td><input class="boxkalkulacija" name="sProjekt" type="text" autocomplete="off" onkeyup="searchSuggest(this, event);" id="sProjekt" value="<?= $_POST["sProjekt"]; ?>" /></td>
		<td><label for="nBroj"><strong>BROJ</strong></label></td>
		<td><input class="boxkalkulacija" onkeyup="error_check('nBroj', '<?= $_GET["ftype"]; ?>');" name="nBroj" type="text" id="nBroj" style="text-align: center;" value="<?= $_POST["nBroj"]; ?>" /></td>
	</tr>
	<tr>
		<td><strong>NARUČENO</strong></td>
		<td><input name="nOrderType" type="radio" value="mail" onclick="javascript: HideExtraOrder();" <?php if($_POST["nOrderType"] == "mail") echo " checked=\"checked\""; ?> />mail</td>
		<td><label for="sVoditelj"><strong>VODITELJ PROJEKTA / SUPERVIZOR</strong></label></td>
		<td><input name="sVoditelj" class="boxkalkulacija" type="text" id="sVoditelj" onkeyup="searchSuggest(this, event);" autocomplete="off" value="<?= $_POST["sVoditelj"]; ?>" /></td>
		<td><strong><a href="javascript: void(0);" class="txt" onclick="javascript: PickDate('preview_rok', 'rok', 0);">ROK</a></strong></td>
		<td id="preview_rok"><strong><?= strftime("%d.%m.%Y", $_POST["sRok"]); ?></strong></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input name="nOrderType" type="radio" value="contract" onclick="javascript: HideExtraOrder();" <?php if($_POST["nOrderType"] == "contract") echo " checked=\"checked\""; ?> />ugovor</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><label for="sVerzija"><strong>VERZIJA</strong></label></td>
		<td><input type="hidden" value="<?= $_POST["sVerzija"]; ?>" id="sVerzija" name="sVerzija" />
<select id="stare_verzije" name="stare_verzije" onchange="javascript: LoadRNOldVersion('<?= $_GET["ftype"]; ?>', '<?= $_GET["id"]; ?>');">
<?= $oRN -> GetVersionDropDown(); ?>
</select>
</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input name="nOrderType" type="radio" value="phone" onclick="javascript: HideExtraOrder();" <?php if($_POST["nOrderType"] == "phone") echo " checked=\"checked\""; ?> />telefon</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><label for="project_status"><strong>STATUS</strong></label></td>
		<td>
		<select id="project_status" name="project_status">
			<option value="0" <?php if($_POST["nStatus"] == 0) echo 'selected'; ?>>Otvoren</option>
			<option value="1" <?php if($_POST["nStatus"] == 1) echo 'selected'; ?>>Zatvoren</option>
		</select></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input name="nOrderType" type="radio" value="other" onclick="javascript: ShowExtraOrder();" <?php if($_POST["nOrderType"] == "other") echo " checked=\"checked\""; ?> />drugo</td>
		<td colspan="4"><input class="boxkalkulacija" name="sOtherDesc" type="text" id="sOtherDesc" size="50" value="<?= $_POST["sOtherDesc"]; ?>" <?php if($_POST["nOrderType"] != "other") echo "disabled=\"disabled\""; ?> /></td>
	</tr>
	<tr>
		<td colspan="6">
			<fieldset>
				<legend><strong>OPIS PROJEKTA</strong></legend>
				<?php include('_modals/rte/rte_components/toolbar.php'); ?>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td colspan="6">
		<fieldset>
			<legend><strong>PROJEKTNA DOKUMENTACIJA</strong></legend>
<?php

if(!empty($_GET['id']))
{


		$useApplet=0;
		
		$user_agent =$_SERVER['HTTP_USER_AGENT'];

	   
		if(stristr($user_agent,'konqueror') || stristr($user_agent,"macintosh") || stristr($user_agent,"opera"))
		{ 		
			$useApplet=1;
			echo '<applet name="Rad Upload Plus"
					archive="http://'.$_SERVER['SERVER_NAME'].'/_modals/rad/dndplus.jar"
					code="com.radinks.dnd.DNDAppletPlus"
					 MAYSCRIPT="yes"
					 id="rup_applet">';
		}
		else
		{			   
			if(strstr($user_agent,"MSIE")) { 
				echo '<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93"
					id="rup" name="rup"
					codebase="http://java.sun.com/products/plugin/autodl/jinstall-1_4_1-windows-i586.cab#version=1,4,1">';
					
			} else {
				echo '<object type="application/x-java-applet;version=1.4.1"
					 id="rup" name="rup">';
			} 
			echo '	<param name="archive" value="http://'.$_SERVER['SERVER_NAME'].'/_modals/rad/dndplus.jar">
				<param name="code" value="com.radinks.dnd.DNDAppletPlus" />
				<param name="name" value="Rad Upload Plus" />';
				
		}		?>
   		
		
		   		<param name="browse" value="1" />
		<param name="browse_button" value="1" />
   		<param name="max_upload" value="8192" />
   		<!-- size in kilobytes (takes effect only in Rad Upload Plus) -->
		<param name="message" value="http://corp.orbitum.net/_modals/upload_screens/upload.projektna_dokumentacija.php?ftype=<?=$_GET['ftype'];?>&amp;id=<?=$_GET['id'];?>" />
		<!-- edit the above line to customize the welcome message displayed. example
		value='http://www.radinks.com/upload/init.html' -->
		<param name="url" value="http://corp.orbitum.net/_modals/upload_screens/upload.projektna_dokumentacija.php?ftype=<?=$_GET['ftype'];?>&amp;id=<?=$_GET['id'];?>&amp;my_id=<?=$_SESSION['zaposlenik_id'];?>" />
		<!-- you can pass additional parameters by adding them to the url-->
		<!-- to upload to an ftp server instead of a web server, please specify a url
			 in the following format:
				ftp://username:password@ftp.myserver.com
			 replacing username, password and ftp.myserver.com with corresponding entries for your site -->
<?php
		if(isset($_SERVER['PHP_AUTH_USER']))
		{
			printf('<param name="chap" value="%s" />',
			base64_encode($_SERVER['PHP_AUTH_USER'].':'.$_SERVER['PHP_AUTH_PW']));
		}
		
		if($useApplet == 1) {
			echo '</applet>';
		}
		else {
			echo '</object>';
		}
}
else
{
	echo 'Prije pridruživanja dokumentacije morate spremiti radni nalog.';
}
?>
		</fieldset>
		</td>
	</tr>
</table>
<fieldset>
<legend><strong>POJEDINI POSLOVI</strong></legend>
<table width="100%">
	<tr>
		<td><strong>IME</strong></td>
		<td><strong>OPIS</strong></td>
		<td><strong>ROK POČ.</strong></td>
		<td><strong>ROK ZAV.</strong></td>
		<td><strong>TOTAL</strong></td>
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
		<td>
			<select name="sZaposlenikPojedino<?= $i; ?>" id="sZaposlenikPojedino<?= $i; ?>">
				<option id="0" value="0">-- zaposlenici --</option>
				<?= $oRN -> CompanyDisplayEmployeesDropDown($i); ?>
			</select>
		</td>
		<td width="50%"><input class="boxkalkulacija" onkeyup="searchSuggest(this, event);" autocomplete="off" name="sOpisPojedino<?= $i; ?>" type="text" id="sOpisPojedino<?= $i; ?>" value="<?= $_POST["sOpisPojedino$i"]; ?>" style="width: 99%;" /></td>
		<td id="pocetak_rok_pre<?= $i; ?>"><a href="javascript: void(0);" class="txt" onclick="javascript: PickDate('pocetak_rok_pre<?= $i; ?>', 'pocetak', <?= $i; ?>);"><?= $sTextPocetakRok; ?></a><input name="sPocetakRok<?= $i; ?>" type="hidden" id="sPocetakRok<?= $i; ?>" value="<?= $_POST["sPocetakRok$i"]; ?>" /></td>
		<td id="zavrsetak_rok_pre<?= $i; ?>"><a href="javascript: void(0);" class="txt" onclick="javascript: PickDate('zavrsetak_rok_pre<?= $i; ?>', 'zavrsetak', <?= $i; ?>);"><?= $sTextKrajRok; ?></a><input name="sZavrsetakRok<?= $i; ?>" type="hidden" id="sZavrsetakRok<?= $i; ?>" value="<?= $_POST["sZavrsetakRok$i"]; ?>" /></td>
		<td><input class="boxkalkulacija" name="nTotalPojedino<?= $i; ?>" size="2" type="text" id="nTotalPojedino<?= $i; ?>" value="<?= $_POST["nTotalPojedino$i"]; ?>" /></td>
	</tr>
	<?php
			$i ++;
		}
	?>
	<tr>
		<td colspan="5">&nbsp;</td>
	</tr>
</table>
</fieldset>
<fieldset>
<legend><strong>OBAVLJENI POVRATNI POSLOVI</strong></legend>
<table width="100%">
	<tr>
		<td colspan="6">&nbsp;</td>
	</tr>
	<tr>
		<td><strong>ZAPOSLENIK</strong></td>
		<td><strong>POSAO</strong></td>
		<td><strong>POČEO / LA</strong></td>
		<td><strong>ZAVRŠIO / LA</strong></td>
		<td><strong>TOTAL</strong></td>
		<td><strong>OPASKA</strong></td>
	</tr>
	<?= $oRN -> GenerateSingleJobListFinished(); ?>
</table>
</fieldset>
<input class="big" name="bSave" type="submit" id="bSave" value="DOPUNA RADNOG NALOGA &darr;" />
<input class="big" name="bKalkulacija" type="button" id="bKalkulacija" onclick="javascript: window.open('3_financije/kalkulacija.php?id=<?= $_GET["id"]; ?>&amp;ftype=<?= $_GET["ftype"]; ?>');" value="KALKULACIJA" />