<?php

//ini_set('display_errors', 1);
//	error_reporting(E_ALL);
error_reporting(0);
	//session_start();
	require('class.klijent.php');
	(object) $oRN = new Klijent;
	$oRN -> SaveKlijent();
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
		<img src="../gfx/adresar.header.gif" width="280" height="38" alt="" border="0"><br>		<br>
	</td>
</tr>
<tr bgcolor="#FBDFCE" valign="top">
	<td><img src="../gfx/empty.gif" width="10" height="1" alt="" border="0"><br></td>
    <td>
		<table width="100%" cellspacing="0" cellpadding="6" border="0">
		<tr valign="middle">
			<td width="250"><label for="tvrtka" class="adresarkat">Tvrtka</label></td>
		  <td align="left"><input name="tvrtka" type="text" class="adresar1" id="tvrtka" value="<?= $_POST["tvrtka"]; ?>" size="55"></td>
		</tr>
		<tr valign="middle">
			<td><label for="mb" class="adresarkat">MB</label></td>
		  <td align="left"><input name="mb" type="text" class="adresar2" id="mb" value="<?= $_POST["mb"]; ?>" size="55"></td>
		</tr>
		<tr valign="middle">
			<td><label for="kontakt_osoba" class="adresarkat">Kontakt osoba</label></td>
		  <td align="left"><input name="kontakt_osoba" type="text" class="adresar1" id="kontakt_osoba" value="<?= $_POST["kontakt_osoba"]; ?>" size="55"></td>
		</tr>
		<tr valign="middle">
			<td><label for="ulica" class="adresarkat">Adresa</label></td>
		  <td align="left"><input name="ulica" type="text" class="adresar2" id="ulica" value="<?= $_POST["ulica"]; ?>" size="55"></td>
		</tr>
		<tr valign="middle">
			<td><label for="grad" class="adresarkat">Grad</label></td>
		  <td align="left"><input name="grad" type="text" class="adresar1" id="grad" value="<?= $_POST["grad"]; ?>" size="55"></td>
		</tr>
		<tr valign="middle">
			<td><label for="po_broj" class="adresarkat">Poštanski broj</label></td>
		  <td align="left"><input name="po_broj" type="text" class="adresar2" id="po_broj" value="<?= $_POST["po_broj"]; ?>" size="55"></td>
		</tr>
		<tr valign="middle">
			<td><label for="drzava" class="adresarkat">Država</label></td>
		  <td align="left"><input name="drzava" type="text" class="adresar1" id="drzava" value="<?= $_POST["drzava"]; ?>" size="55"></td>
		</tr>
		<tr valign="middle">
			<td><label for="telefon" class="adresarkat">Telefon</label></td>
		  <td align="left"><input name="telefon" type="text" class="adresar2" id="telefon" value="<?= $_POST['telefon']; ?>" size="55"></td>
		</tr>
		<tr valign="middle">
			<td><label for="fax" class="adresarkat">Fax</label></td>
		  <td align="left"><input name="fax" type="text" class="adresar1" id="fax" value="<?= $_POST['fax']; ?>" size="40"></td>
		</tr>
		<tr valign="middle">
			<td><label for="email" class="adresarkat">E-mail</label></td>
		  <td align="left"><input name="email" type="text" class="adresar2" id="email" value="<?= $_POST['email']; ?>" size="40"></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat">Dodao / la</span></td>
			<td align="left"><?= $_POST["dodao"]; ?></td>
		</tr>
		<tr valign="middle">
			<td><span class="adresarkat">Posljednje korekcije</span></td>
			<td align="left"><?= $_POST["zadnji_editirao"]; ?></td>
		</tr>
		<tr valign="top">
			<td><img src="../gfx/empty.gif" width="1" height="40" alt="" border="0"><br></td>
			<td align="right"><input name="bSave" type="submit" id="bSave" style="padding-left:8px; padding-right:8px;" value="spremi"></td>
		</tr>
	  </table>
	</td>
	<td><img src="../gfx/empty.gif" width="16" height="1" alt="" border="0"><br></td>
</tr>
<tr bgcolor="#FEFAEF">
    <td colspan="3"><img src="../gfx/empty.gif" width="400" height="1" alt="" border="0"><br></td>
</tr>
<tr valign="top">
	<td><img src="../gfx/empty.gif" width="16" height="1" alt="" border="0"><br></td>
    <td>
		<table width="100%" cellspacing="0" cellpadding="6" border="0">
		<tr valign="middle">
			<td bgcolor="FDF9ED">
				<img src="../gfx/empty.gif" width="1" height="12" alt="" border="0"><br>
				<span class="adresarkat">KLIJENT</span><br>
		  </td>
		  <td align="center" bgcolor="FDF9ED"><br></td>
		</tr>
		<tr valign="middle">
			<td colspan="2"><input type="button" value="dodaj novi kontakt" onclick="javascript: window.location = '?page=adresar';" /><br></td>
		</tr>
		<?= $oRN -> CompanyDisplayClients(); ?>
	  </table>
	</td>
	<td><img src="../gfx/empty.gif" width="16" height="1" alt="" border="0"><br></td>
</tr>
</table>