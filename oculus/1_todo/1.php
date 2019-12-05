<?php
	error_reporting(0);
	require('class.todo.php');
	(object) $oTODO = new TODO;
	$oTODO -> MainTODO();
?>
<!-- <meta http-equiv="Content-Type" content="text/html; charset=windows-1250"> -->

<script type="text/javascript">

	var sRadID = "";
	var sSwitch = "";
	var nID = null;

	function PickDate(sID, sMySwitch, nMyID)
	{
		sRadID = sID;
		sSwitch = sMySwitch;
		nID = nMyID;
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
		var sDate = humanToTime(nYear , nMonth , nDay, 0, 0, 0);
		if((nDay == 0) && (nMonth == 0) && (nYear == 0)) {
			sStr = "non-stop";
		}
		else {
			sStr = nDay + ". " + nMonth + ". " + nYear;
		}

		var sExtra = (sSwitch == "pocetak") ? "<input name=\"sPocetak_"+nID+"\" type=\"hidden\" id=\"sPocetak_"+nID+"\" value=\"" + sDate + "\" size=\"30\" />" : "<input name=\"sZavrsetak_"+nID+"\" type=\"hidden\" id=\"sZavrsetak_"+nID+"\" value=\"" + sDate + "\" size=\"30\" />";

		window.document.getElementById(sRadID).innerHTML = "<a href=\"javascript: void(0);\" class=\"txt\" onclick=\"javascript: PickDate('"+ sRadID +"');\">" + sStr + "</a>" + sExtra;
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

	function DisplayHideTodoList(id)
	{
		var next = 'none';
		var current = document.getElementById(id).style.display;
		if(current == 'none') {
			next = 'block';
		}
		document.getElementById(id).style.display = next;
	}

</script>
<div style="padding-left: 1em;"><input class="little" name="bPotvrdiTodo" type="submit" id="bPotvrdiTodo" style=" padding-left:8px; padding-right:8px;" value="spremi &dArr;" /><img src="gfx/empty.gif" width="500" height="1" /><input class="big" value="Dodaj novi dnevni zadatak +" type="button" onclick="javascript: window.location = 'index.php?page=novi_todo_lite&ftype=rn';" /></div>
<br />
<?php
	$oTODO -> GenerateTODOList();
	$oTODO -> GenerateAdminTODOList();
?><br />
<div style="padding-left: 1em;"><input class="little" name="bPotvrdiTodo" type="submit" id="bPotvrdiTodo2" style=" padding-left:8px; padding-right:8px;" value="spremi &dArr;" /></div>