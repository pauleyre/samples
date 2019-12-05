<?php

	error_reporting(0);
	require("class.zaposlenik.php");
	(object) $oRN = new Zaposlenik;
	$oRN -> SaveZaposlenik();
	$oRN -> CompanyBuildEmployeeActions();

?>

<script type="text/javascript">

	var sRadID = "";

	function EmployeesDeleteEmployee(oAnchor)
	{
		if(window.confirm("Jeste li sigurni da želite ukloniti ovog zaposlenika?")) {
			window.location = oAnchor.href;
		}
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
		var sDate = humanToTime(nYear, nMonth, nDay, 0, 0, 0);
		if((nDay == 0) && (nMonth == 0) && (nYear == 0)) {
			sStr = "non-stop";
		}
		else {
			sStr = nDay + ". " + nMonth + ". " + nYear;
		}

		var sExtra = (sRadID == "pocetak_rada_pre") ? "<input name=\"pocetak_rada\" type=\"hidden\" id=\"pocetak_rada\" value=\"" + sDate + "\" size=\"30\" />" : "<input name=\"kraj_rada\" type=\"hidden\" id=\"kraj_rada\" value=\"" + sDate + "\" size=\"30\" />";

		window.document.getElementById(sRadID).innerHTML = "<strong>" + sStr + "</strong>" + sExtra;
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

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr bgcolor="#F0FCB2" valign="middle">
    <td height="50" colspan="3">
		<img  src="../gfx/zaposlenici.header.gif" border="0" /><br>
    </td>
</tr>
<tr bgcolor="#FBDFCE" valign="top">
	<td><img src="../gfx/empty.gif" width="10" height="1" alt="" border="0"><br></td>
    <td>
		<table width="100%" cellspacing="0" cellpadding="6" border="0">
		<tr valign="middle">
			<td width="250"><label for="ime" class="adresarkat">Ime</label></td>
		  <td align="left"><input name="ime" type="text" class="adresar1" id="ime" value="<?= $_POST["ime"]; ?>" size="55"></td>
		</tr>
		<tr valign="middle">
			<td><label for="prezime" class="adresarkat">Prezime</label></td>
		  <td align="left"><input name="prezime" type="text" class="adresar2" id="prezime" value="<?= $_POST["prezime"]; ?>" size="55"></td>
		</tr>
		<tr valign="middle">
			<td><label for="lozinka" class="adresarkat">Lozinka</label></td>
		  <td align="left"><input name="lozinka" type="text" class="adresar1" id="lozinka" value="<?= $_POST["lozinka"]; ?>" size="55"></td>
		</tr>
		<tr valign="middle">
			<td><label for="email" class="adresarkat">E-mail</label></td>
		  	<td align="left"><input name="email" type="text" class="adresar2" id="email" value="<?= $_POST["email"]; ?>" size="55"></td>
		</tr>
		<tr valign="middle">
			<td><label for="mob" class="adresarkat">Mobitel</label></td>
		  	<td align="left"><input name="mob" type="text" class="adresar1" id="mob" value="<?= $_POST["mob"]; ?>" size="55"></td>
		</tr>
		<tr valign="middle">
			<td><label for="tel" class="adresarkat">Telefon</label></td>
		  	<td align="left"><input name="tel" type="text" class="adresar2" id="tel" value="<?= $_POST["tel"]; ?>" size="55"></td>
		</tr>
		<tr valign="middle">
			<td><label for="zanimanje" class="adresarkat">Zanimanje</label></td>
		  <td align="left"><input name="zanimanje" type="text" class="adresar1" id="zanimanje" value="<?= $_POST["zanimanje"]; ?>" size="55"></td>
		</tr>
		<tr valign="middle">
			<td><label for="placa" class="adresarkat">Plaća</label></td>
		  <td align="left"><input name="placa" type="text" class="adresar2" id="placa" value="<?= $_POST["placa"]; ?>" size="55"></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat"><a href="javascript: void(0);" class="txt" onclick="javascript: PickDate('pocetak_rada_pre');">Početak rada</a></span></td>
			<td id="pocetak_rada_pre" align="left"><strong><?=  strftime("%d. %m. %Y", $_POST["pocetak_rada"]); ?></strong>
		  <input name="pocetak_rada" type="hidden" id="pocetak_rada" value="<?= $_POST[""]; ?>" /></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat"><a href="javascript: void(0);" class="txt" onclick="javascript: PickDate('kraj_rada_pre');">Kraj rada</a></span></td>
			<td id="kraj_rada_pre" align="left"><strong><?=  strftime("%d. %m. %Y", $_POST["kraj_rada"]); ?></strong>
		  <input name="kraj_rada" type="hidden" id="kraj_rada" value="<?= $_POST["kraj_rada"]; ?>" /></td>		</tr>
		<tr valign="middle">
			<td><label for="status" class="adresarkat">Status</label></td>
			<td align="left"><select name="status" id="status">
      		<option value="3" <?php if($_POST["status"] == 3) echo "selected"; ?>>Bivši suradnik / zaposlenik</option>			
      		<option value="2" <?php if($_POST["status"] == 2) echo "selected"; ?>>Vanjski suradnik</option>			
      		<option value="0" <?php if($_POST["status"] == 0) echo "selected"; ?>>Zaposlenik</option>
      		<option value="1" <?php if($_POST["status"] == 1) echo "selected"; ?>>Supervizor (Administrator)</option>
    	</select></td>
		</tr>
		<tr valign="middle">
			<td><label for="opaska" class="adresarkat">Opaska</label></td>
		  <td align="left"><textarea name="opaska" id="opaska" cols="55" rows="7"><?= $_POST["opaska"]; ?></textarea></td>
		</tr>
		<tr valign="top">
			<td><img src="../gfx/empty.gif" width="1" height="40" alt="" border="0"><br></td>
			<td align="right"><input name="bSave" type="submit" id="bSave" style="padding-left:8px; padding-right:8px;" value="spremi"></td>
		</tr>
	  </table>
	</td>
	<td><img src="../gfx/empty.gif" width="16" height="1" alt="" border="0"><br></td>
</tr>
<tr bgcolor="#FBDFCE">
    <td colspan="3"><img src="../gfx/empty.gif" width="350" height="1" alt="" border="0"><br></td>
</tr>
<tr valign="top">
	<td><img src="../gfx/empty.gif" width="16" height="1" alt="" border="0"><br></td>
    <td>
		<table width="100%" cellspacing="0" cellpadding="6" border="0">
		<tr valign="middle">
			<td>
				<img src="../gfx/empty.gif" width="1" height="12" alt="" border="0"><br>
				<span class="adresarkat">ZAPOSLENIK</span><br>
			</td>
			<td align="center"><br></td>
		</tr>
				<tr valign="middle">
			<td colspan="2"><input type="button" value="dodaj novog zaposlenika" onclick="javascript: window.location = '?page=zaposlenici';" /><br></td>
		</tr>
		<?= $oRN -> CompanyDisplayEmployees(); ?>
	  </table>
	</td>
	<td><img src="../gfx/empty.gif" width="16" height="1" alt="" border="0"><br></td>
</tr>
</table>