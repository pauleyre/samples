<?php

	require($_SERVER["DOCUMENT_ROOT"]."/classlib/public/classlib.php");
	session_start();
	require("class.financije.php");
	(object) $oPrint = new Financije;

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!-- saved from url=(0013)about:internet -->
<html>
<head>
	<title>is . račun</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel='stylesheet' href='../gfx/styleis.css'>
	<style type="text/css">
	td {
		color: black !important;
	}
	</style>
</head>
<body>
	<?php

	if($_GET["ftype"] == 'racun')
	{
			$prefix = strtolower(str_replace('Č', 'C', $_POST['sTipDoc']));
			$name = ('racun za primljeni predujam' == $prefix) ? 'rpp' : $_GET['ftype'];

	?>
		<!-- <div style="color: black; text-transform: uppercase; border-bottom: thin solid red;"><input value="RAČUN (PDF)" type="button" style="font-size:large;" onClick="window.location='../radni_nalozi/<?= date("Y/m", time())."/".$name."_".str_replace("/", "-", $_POST["sBrojQuick"]).".pdf"; ?>'" /><br /></div> -->
	<?php
	}
	else
	{
	?>
<?php
}
?>
<!-- <table width="595" style="background-color: transparent;">
<tr>
<td height="161" colspan="3"></td>
</tr>
<tr>
  <td width="121" height="103"></td>
	<td colspan="2" align="left" valign="top"><?= $_SESSION['primatelj']; ?></td>
</tr> -->
	<?php
		$oPrint -> KalkulacijaPrint();
		echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=../radni_nalozi/'.date("Y/m", time())."/".$name."_".str_replace("/", "-", $_POST["sBrojQuick"]).'.pdf">';
	?>
<!-- <tr>
  <td height="92"></td>
	<td height="92" colspan="2"></td>
</tr>
<tr>
  <td height="33"></td>
  <td width="298" height="33" align="right"><strong>Total:</strong></td>
  <td width="160" height="33"><strong><?= number_format($_SESSION["total"], 2, ',', '.'); ?> kn</strong></td>
</tr>
<tr>
  <td colspan="3"></td>
</tr>
<tr>
  <td colspan="3"></td>
</tr>
</table> -->
</body>
</html>