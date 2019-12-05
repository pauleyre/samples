<?php
	require($_SERVER['DOCUMENT_ROOT'].'/classlib/public/classlib.php');

	(string) $sInfo = '';
	(object) $oLogin = new ClassLib;

	if(isset($_POST['lozinka']))
	{
		$oLogin -> DB_Spoji('is');
		(string) $sQuery = sprintf("SELECT id, ime, prezime, status, email FROM zaposlenici WHERE lozinka = %s AND status != 3", $oLogin -> QuoteSmart($_POST['lozinka']));

		$rResult = $oLogin -> DB_Upit($sQuery);
		(array) $aResult = mysql_fetch_array($rResult);

		if(!empty($aResult['id']))
		{
			session_start();
			$_SESSION['zaposlenik_id'] = $aResult['id'];
			$_SESSION['zaposlenik_ime'] = $aResult['ime'];
			$_SESSION['zaposlenik_prezime'] = $aResult['prezime'];
			$_SESSION['zaposlenik_status'] = $aResult['status'];

			switch($aResult['status'])
			{
				case 2: $_SESSION['zaposlenik_status_desc'] = 'Vanjski suradnik'; break;
				case 0: $_SESSION['zaposlenik_status_desc'] = 'Zaposlenik'; break;
				case 1: $_SESSION['zaposlenik_status_desc'] = 'Supervizor (Administrator)'; break;
			}

			$_SESSION['zaposlenik_email'] = $aResult['email'];
			if($_SESSION['login_from'] == 'admin') {
				echo '<meta http-equiv="refresh" content="0; URL=0.php" />';
			}
			else {
				echo '<meta http-equiv="refresh" content="0; URL=index.php" />';
			}
		}
		else {
			$sInfo = '<span class="dark11"><b>KORISNIK NEPOZNAT</b></span>';
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel='stylesheet' href='gfx/styleis.css' />
<title>is . login</title>
<link href="../gfx/styleis.css" rel="stylesheet" type="text/css" />
</head>

<body onload="javascript: window.document.getElementById('lozinka').focus();" style="background-image: url(gfx/login.bck.jpg);" leftmargin="0" bottommargin="0" marginheight="0" marginwidth="0" rightmargin="0" topmargin="0">
<form action="" method="post" name="login" target="_self" id="login">
<img src="gfx/login.header.jpg" width="220" height="106" alt="" border="0" />
<div style="margin-left: 250px; top: 10px; right: 1em; line-height: 2.1em;">
	<h1>
	  <noscript>
	Vaš preglednik <code><?= $_SERVER['HTTP_USER_AGENT']; ?></code> ima <span style="border-bottom: medium solid red;">isključen</span> Javascript. <span style="border-bottom: medium solid red;">Neke od funkcionalnosti internog sustava će biti nedostupne.</span>
	<br />
	<br />
	<br /><br />
	Obratite se svome administratoru ili sami ponovno uključite / isključite Javascript u opcijama preglednika.
	</noscript>
	</h1>
</div>

<img src="gfx/empty.gif" width="1" height="5" alt="" border="0" /><br />

<table width="200" cellspacing="0" cellpadding="0" border="0">
<tr valign="top">
	<td><img src="gfx/empty.gif" width="14" height="1" alt="" border="0" /><br /></td>
	<td>
		<img src="gfx/empty.gif" width="186" height="1" alt="" border="0" /><br />
		<label for="lozinka"><b>Korisnička lozinka</b></label><br />
		<img src="gfx/empty.gif" width="1" height="4" alt="" border="0" /><br />
		<input tabindex="1" name="lozinka" class="boxlogin" id="lozinka" type="password" title="unesite lozinku" />
		<br />
		<img src="gfx/empty.gif" width="1" height="8" alt="" border="0" /><br />
		<input tabindex="2" name="bLogin" class="little" type="submit" id="bLogin" value="Spoji me" />
		<br />
		<img src="gfx/empty.gif" width="1" height="33" alt="" border="0" /><br />
		<?= $sInfo; ?></td>
</tr>
</table>
</form>
</body>
</html>