<?php

	error_reporting(0);
	require("class.zaposlenik.php");
	(object) $oRN = new Zaposlenik;
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
<tr bgcolor="#F0FCB2" valign="top">
    <td colspan="3"><img  src="../gfx/zaposlenici-pregled.header.gif" border="0" /><br>      <br>
	</td>
</tr>
<tr bgcolor="#FBDFCE" valign="top">
	<td><img src="../gfx/empty.gif" width="10" height="1" alt="" border="0"><br></td>
    <td>
		<table width="100%" cellspacing="0" cellpadding="6" border="0">
		<tr valign="middle">
			<td width="250"><span class="adresarkat">Ime</span></td>
			<td align="left"><?= $_POST["ime"]; ?></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat">Prezime</span></td>
			<td align="left"><?= $_POST["prezime"]; ?></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat">Lozinka</span></td>
			<td align="left"><?= $_POST["lozinka"]; ?></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat">E-mail</span></td>
			<td align="left"><a href="mailto:<?= $_POST["email"]; ?>"><?= $_POST["email"]; ?></a></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat">Mobitel</span></td>
			<td align="left"><?= $_POST["mob"]; ?></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat">Telefon</span></td>
			<td align="left"><?= $_POST["tel"]; ?></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat">Zanimanje</span></td>
			<td align="left"><?= $_POST["zanimanje"]; ?></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat">Plaća</span></td>
			<td align="left"><?= $_POST["placa"]; ?></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat">Početak rada</span></td>
			<td id="pocetak_rada_pre" align="left"><strong><?=  strftime("%d. %m. %Y", $_POST["pocetak_rada"]); ?></strong></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat">Kraj rada</span></td>
			<td id="kraj_rada_pre" align="left"><strong><?=  strftime("%d. %m. %Y", $_POST["kraj_rada"]); ?></strong></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat">Status</span></td>
			<td align="left"><select>
      		<option <?php if($_POST["status"] == 3) echo "selected"; ?>>Bivši suradnik / zaposlenik</option>			
      		<option <?php if($_POST["status"] == 2) echo "selected"; ?>>Vanjski suradnik</option>			
      		<option <?php if($_POST["status"] == 0) echo "selected"; ?>>Zaposlenik</option>
      		<option <?php if($_POST["status"] == 1) echo "selected"; ?>>Supervizor (Administrator)</option>
    	</select></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat">Opaska</span></td>
			<td align="left"><?= $_POST["opaska"]; ?></td>
		</tr>
		<tr valign="top">
			<td><img src="../gfx/empty.gif" width="1" height="40" alt="" border="0"><br></td>
			<td align="right"><input name="Button" type="button" style="padding-left:8px; padding-right:8px;" value="print" onclick="javascript: print(document);"></td>
		</tr>
	  </table>
	</td>
	<td><img src="../gfx/empty.gif" width="16" height="1" alt="" border="0"><br></td>
</tr>
<tr bgcolor="#F0FCB2">
    <td colspan="3"><img src="../gfx/empty.gif" width="350" height="1" alt="" border="0"><br></td>
</tr>
<tr valign="top">
	<td><img src="../gfx/empty.gif" width="16" height="1" alt="" border="0"><br></td>
    <td>
		<table width="100%" cellspacing="0" cellpadding="6" border="0">
		<tr valign="middle">
			<td>
				<img src="../gfx/empty.gif" width="1" height="12" alt="" border="0"><br>
				<span class="adresarkat">ZAPOSLENICI</span><br>
			</td>
			<td align="center"><br></td>
		</tr>
				<tr valign="middle">
			<td colspan="2"><input type="button" value="dodaj novog zaposlenika" onclick="javascript: window.location = 'zaposlenik.php';" /><br></td>
		</tr>
		<?= $oRN -> CompanyDisplayEmployees(); ?>
	  </table>
	</td>
	<td><img src="../gfx/empty.gif" width="16" height="1" alt="" border="0"><br></td>
</tr>
</table>