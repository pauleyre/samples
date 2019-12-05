<?php

	error_reporting(0);
	require("class.klijent.php");
	(object) $oRN = new Klijent;
	$oRN -> CompanyBuildClientActions();

?>

<script type="text/javascript">

	function PobrisiKlijenta(oAnchor)
	{
		if(window.confirm("Da li ste sigurni?")) {
			window.location = oAnchor.href;
		}
	}

</script>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr bgcolor="#F0FCB2" valign="top">
    <td colspan="3">
		<img src="../gfx/adresar.header-pregled.gif" width="280" height="38" alt="" border="0"><br>		<br>
	</td>
</tr>
<tr bgcolor="#FBDFCE" valign="top">
	<td><img src="../gfx/empty.gif" width="10" height="1" alt="" border="0"><br></td>
    <td>
		<table width="100%" cellspacing="0" cellpadding="6" border="0">
		<tr valign="middle">
			<td width="250"><span class="adresarkat">Tvrtka</span></td>
			<td align="left"><?= $_POST["tvrtka"]; ?></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat">MB</span></td>
			<td align="left"><?= $_POST["mb"]; ?></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat">Kontakt osoba</span></td>
			<td align="left"><?= $_POST["kontakt_osoba"]; ?></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat">Adresa</span></td>
			<td align="left"><?= $_POST["ulica"]; ?></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat">Grad</span></td>
			<td align="left"><?= $_POST["grad"]; ?></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat">Poštanski broj</span></td>
			<td align="left"><?= $_POST["po_broj"]; ?></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat">Država</span></td>
			<td align="left"><?= $_POST["drzava"]; ?></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat">Telefon</span></td>
			<td align="left"><?= $_POST["telefon"]; ?></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat">Fax</span></td>
			<td align="left"><?= $_POST["fax"]; ?></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat">E-mail</span></td>
			<td align="left"><a href="mailto:<?= $_POST["email"]; ?>"><?= $_POST["email"]; ?></a></td>
		</tr>
		<tr valign="top">
			<td><img src="../gfx/empty.gif" width="1" height="40" alt="" border="0"><br></td>
			<td align="right"><input name="Button" type="button" onclick="javascript: print(document);" style="padding-left:8px; padding-right:8px;" value="print"></td>
		</tr>		
	  </table>
	</td>
	<td><img src="../gfx/empty.gif" width="16" height="1" alt="" border="0"><br></td>
</tr>
<tr bgcolor="#FEFAEF">
    <td colspan="3"><img src="../gfx/empty.gif" width="350" height="1" alt="" border="0"><br></td>
</tr>
<tr valign="top">
	<td><img src="../gfx/empty.gif" width="16" height="1" alt="" border="0"><br></td>
    <td>
		<table width="100%" cellspacing="0" cellpadding="6" border="0">
		<tr valign="middle">
			<td>
				<img src="../gfx/empty.gif" width="1" height="12" alt="" border="0"><br>
				<span class="adresarkat">KLIJENT</span><br>
			</td>
			<td align="center"><br></td>
		</tr>
		<tr valign="middle">
			<td colspan="2"><input type="button" value="dodaj novi kontakt" onclick="javascript: window.location = 'klijent.php';" /><br></td>
		</tr>
		<?= $oRN -> CompanyDisplayClients(); ?>
	  </table>
	</td>
	<td><img src="../gfx/empty.gif" width="16" height="1" alt="" border="0"><br></td>
</tr>
</table>