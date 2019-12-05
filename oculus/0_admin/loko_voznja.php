<?php
	error_reporting(0);

	require("4_evidencije/class.evidencije.php");
	(object) $oLoko = new Evidencije;
?><style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.style1 {
	color: #666600;
	font-weight: bold;
}
-->
</style>
<table width="100%">
	<tr>
	  <td height="50" colspan="5"><span class="style1">&nbsp;&nbsp;&nbsp;&nbsp;LOKO VO&#381NJA </span></td>
  </tr>
	<tr>
		<td height="60" colspan="5"><strong>&nbsp;&nbsp;&nbsp;&nbsp;PREGLED PO ZAPOSLENIKU</strong>
		  <select name="sPregledZap" id="sPregledZap"><?= $oLoko -> DisplayEmployeeDropDown(); ?>
	      </select>
          <input name="bPregled" type="submit" id="bPregled" value="pregled" /></td>
	</tr>
	<tr>
		<td width="20%" height="20"><strong>&nbsp;&nbsp;&nbsp;&nbsp;datum</strong></td>
		<td width="20%"><strong>destinacija</strong></td>
		<td width="20%"><strong>svrha</strong></td>
		<td width="20%"><strong>vozilo</strong></td>
		<td width="20%"><strong>udaljenost</strong></td>
	</tr>
<?= $oGO -> DisplayLokoView(); ?>
</table>
