<?php
	error_reporting(0);
	require("class.evidencije.php");
session_start();
	(object) $oLoko = new Evidencije;

	$oLoko -> ReportsLoadLoko();
	$val = (!empty($_POST["loko_datum"])) ? strftime("%d.%m.%Y.", $_POST["loko_datum"]) : "N/A";
?>

<table cellspacing="0" cellpadding="6" border="0">
				<tr valign="middle">
					<td><img src="gfx/empty.gif" width="10" height="1" alt="" border="0"><br></td>
					<td><span class="dark10"><b>DATUM</b></span></td>
					<td id="loko_datum_pr"><span class="txt"><b><?= $val; ?></b></span></td>
				</tr>
				<tr valign="middle">
					<td><br></td>
					<td><span class="dark10"><b>DESTINACIJA</b></span></td>
					<td><?= $_POST["loko_destinacija"]; ?></td>
				</tr>
				<tr valign="top">
					<td><br></td>
					<td><span class="dark10"><b>SVRHA</b></span></td>
					<td><?= $_POST["loko_svrha"]; ?></td>
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
					<td><?= $_POST["loko_kmh"]; ?></td>
				</tr>
				<tr valign="middle">
					<td colspan="2"><br></td>
					<td>
						<img src="gfx/empty.gif" width="1" height="5" alt="" border="0"><br>
						<input name="Button" type="button" onclick="javascript: print(document);" style="padding-left:8px; padding-right:8px;" value="print">
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