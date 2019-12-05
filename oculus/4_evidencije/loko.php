<?php
	error_reporting(0);
	if($_GET["action"] == "view")
	{
		require("loko_pregled.php");
	}
	else
	{
	require("class.evidencije.php");
session_start();
	(object) $oLoko = new Evidencije;
	$oLoko -> RemoveLoko();
	$oLoko -> ReportsAddLoko();
	$oLoko -> ReportsLoadLoko();
	$val = (!empty($_POST["loko_datum"])) ? strftime("%d.%m.%Y.", $_POST["loko_datum"]) : "dd.mm.gggg.";

?>

<script type="text/javascript">

	function PickDate()
	{
		var nTop = (screen.width / 2) - 150;
		var nLeft = (screen.height / 2) - 90;
		window.open("_modals/datePicker.html", "", "height=180, width=300, resize=0, top=" + nTop + ", left=" + nLeft);
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

		window.document.getElementById("loko_datum_pr").innerHTML = "<span class=\"txt\"><b>" + sStr + "</b></span><input name=\"loko_datum\" type=\"hidden\" id=\"loko_datum\" value=\"" + sDate + "\" />";
	}

	function humanToTime(year, month, day, hour, minute, sec)
	{
		var humDate = new Date(Date.UTC(year, (stripLeadingZeroes(month)-1), stripLeadingZeroes(day), stripLeadingZeroes(hour), stripLeadingZeroes(minute), stripLeadingZeroes(sec)));

		return (humDate.getTime() / 1000.0);
	}

	function timeToHuman(timestamp, input)
	{
		var theDate = new Date(timestamp * 1000);
		var dateString = theDate.toLocaleString();
		window.document.getElementById(input).innerHTML = "<strong>" + dateString + "</strong><input name=\"loko_datum\" type=\"hidden\" id=\"loko_datum\" value=\"" + timestamp + "\" />";
		
		return dateString;
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
<link href="../gfx/styleis.css" rel="stylesheet" type="text/css" />


<table cellspacing="0" cellpadding="6" border="0">
				<tr valign="middle">
					<td><img src="gfx/empty.gif" width="10" height="1" alt="" border="0"><br></td>
					<td><input name="Button" class="date" onClick="javascript: PickDate();" type="button" value="Datum"></td>
					<td id="loko_datum_pr"><span class="txt"><b><?= $val; ?></b></span><input name="loko_datum" type="hidden" id="loko_datum" value="<?= $_POST["loko_datum"];?>" /></td>
				</tr>
				<tr valign="middle">
					<td><br></td>
					<td><span class="dark10"><b>DESTINACIJA</b></span></td>
					<td><input name="loko_destinacija" type="text" class="boxevidencija" value="<?= $_POST["loko_destinacija"]; ?>" id="loko_destinacija"></td>
				</tr>
				<tr valign="top">
					<td><br></td>
					<td><span class="dark10"><b>SVRHA</b></span></td>
					<td><textarea name="loko_svrha" class="boxevidencijaarea" id="loko_svrha"><?= $_POST["loko_svrha"]; ?></textarea></td>
				</tr>
				<tr valign="middle">
					<td><br></td>
					<td><span class="dark10"><b>VOZILO</b></span></td>
					<td>
						<select id="loko_prijevoz" name="loko_prijevoz" title="" class="boxevidencija">
						<?= $oLoko -> CompanyDisplayVehiclesLokoList(); ?>
						</select><br>
					</td>
				</tr>
				<tr valign="middle">
					<td><br></td>
					<td><span class="dark10"><b>UDALJENOST</b></span></td>
					<td><input name="loko_kmh" type="text" class="boxevidencija" id="loko_kmh"  value="<?= $_POST["loko_kmh"]; ?>"></td>
				</tr>
				<tr valign="middle">
					<td colspan="2"><br></td>
					<td>
						<img src="gfx/empty.gif" width="1" height="5" alt="" border="0"><br>
						<input name="SubmitLoko" class="little" type="submit" id="SubmitLoko" style="padding-left:8px; padding-right:8px;" value="Spremi">
						<br>
						<img src="gfx/empty.gif" width="1" height="16" alt="" border="0"><br>
					</td>
				</tr>
				</table>
		
			<span style="width: 100%;" bgcolor="#CCB1CC" class="edgep1top"><img src="gfx/empty.gif" width="1" height="1" alt="" border="0"><br></span>
<table cellspacing="0" cellpadding="6" border="0">
				<tr valign="bottom">
					<td><img src="gfx/empty.gif" width="8" height="28" alt="" border="0"><br></td>
					<td><span class="dark10"><b>datum</b></span></td>
					<td><span class="dark10"><b>destinacija</b></span></td>
					<td><br></td>
				</tr>
				<?= $oLoko -> ReportsDisplayLoko(); ?>
				<tr>
					<td colspan="4"><img src="gfx/empty.gif" width="1" height="5" alt="" border="0"><br></td>
				</tr>
				</table>		

</body>
</html>
<?php
}
?>