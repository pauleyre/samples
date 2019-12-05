// JavaScript Document

	var __clock_id = 0;
	var oClock = null;
	var oClockSS = null;
	var oTime = null;
	var nHour = null;
	var nMinutes = null;
	var nSeconds = null;

	function ClockUpdate()
	{
		if(__clock_id)
		{
			clearTimeout(__clock_id);
			__clock_id = 0;
		}

		var __cal_day = new getObj("cal_day");
		var aWeekday = new Array("nedjelja", "ponedjeljak", "utorak", "srijeda", "&#269;etvrtak", "petak", "subota");
		oTime = new Date();
		nHour = oTime.getHours();
		nHour = (nHour < 10) ? "0" + nHour : nHour;
		nMinutes = oTime.getMinutes();
		nMinutes = (nMinutes < 10) ? "0" + nMinutes : nMinutes;
		nSeconds = oTime.getSeconds();
		nSeconds = (nSeconds < 10) ? "0" + nSeconds : nSeconds;

		oClock.obj.innerHTML = "<b>" + nHour + ":" + nMinutes + "</b>";
		oClockSS.obj.innerHTML = "<b>:" + nSeconds + "</b>";
		__cal_day.obj.innerHTML = aWeekday[oTime.getDay()];
		__clock_id = setTimeout(ClockUpdate, 1000);
	}

	function ClockStart()
	{
		oClock = new getObj('hh_mm');
		oClockSS = new getObj('ss');
		__clock_id = setTimeout(ClockUpdate, 500);
	}

	function ClockStop()
	{
		if(__clock_id)
		{
			clearTimeout(__clock_id);
			__clock_id = 0;
		}
	}
	
	if(window.attachEvent) {
		window.attachEvent("onload", ClockStart);
		window.attachEvent("onunload", ClockStop);
	}
	else {
		window.addEventListener("load", ClockStart, true);
		window.addEventListener("unload", ClockStop, true);
	}