<?php
	error_reporting(0);
		//require($_SERVER["DOCUMENT_ROOT"]."/classlib/public/classlib.php");

	require("4_evidencije/class.evidencije.php");
	(object) $oGO = new Evidencije;
$oGO -> SaveBolovanja();
?>

<script type="text/javascript">

	var sRadID = "";

	function PickDate(sID)
	{
		sRadID = sID;
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
		var sDate = humanToTime(nYear, nMonth, nDay, 0, 0, 0);
		if((nDay == 0) && (nMonth == 0) && (nYear == 0)) {
			sStr = "non-stop";
		}
		else {
			sStr = nDay + ". " + nMonth + ". " + nYear;
		}

		var sExtra = (sRadID == "od_pre") ? "<input name=\"sOd\" type=\"hidden\" id=\"sOd\" value=\"" + sDate + "\" size=\"30\" />" : "<input name=\"sDo\" type=\"hidden\" id=\"sDo\" value=\"" + sDate + "\" size=\"30\" />";

		window.document.getElementById(sRadID).innerHTML = "<a href=\"javascript: void(0);\" onclick=\"javascript: PickDate('"+ sRadID +"');\">" + sStr + "</a>" + sExtra;
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

<table>
	<tr>
		<td colspan="3"><strong>NOVI UNOS</strong>&nbsp;<input name="bSave" type="submit" id="bSave" value="spremi" /></td>
	</tr>
	<tr>
		<td><strong>zaposlenik</strong></td>
		<td><strong>od</strong></td>
		<td><strong>do</strong></td>
	</tr>
	<tr>
		<td><select name="sUnosZap" id="sUnosZap"><?= $oGO -> DisplayEmployeeDropDown(); ?>
		</select></td>
		<td id="od_pre"><a href="javascript: void(0);" onclick="javascript: PickDate('od_pre');">DD.MM.GGGG</a><strong><?= $_POST["sOd"]; ?></strong></td>
		<td id="do_pre"><a href="javascript: void(0);" onclick="javascript: PickDate('do_pre');">DD.MM.GGGG</a><strong><?= $_POST["sDo"]; ?></strong></td>
	</tr>
</table>
<hr />
<table>
	<tr>
		<td colspan="5"><strong>PREGLED PO ZAPOSLENIKU</strong>
		  <select name="sPregledZap" id="sPregledZap"><?= $oGO -> DisplayEmployeeDropDown(); ?>
	      </select>
          <input name="bPregled" type="submit" id="bPregled" value="pregled" />
</td>
	</tr>
	<tr>
		<td><strong>od</strong></td>
		<td><strong>do</strong></td>
		<td><strong>dani</strong></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
<?= $oGO -> DisplayBolovanja(); ?>
</table>
<div>
	<div style="float: left;"><img style="height: 200px; width: 400px;" title="Graph" src="_modals/class.graph.php?input=<?= implode('|', $_SESSION["bolovanja_graph"]); ?>" /></div>
	<div style="float: left; padding-left: 1em;">
		<h2 style="padding-bottom: 140px; margin: 0; color: #000000;"><?= $_SESSION["graph_highest_bol"]; ?></h2>
		<h2 style=" color: #000000;">0</h2>
	</div>
</div>