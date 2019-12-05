// JavaScript Document

	var __clock_id = 0;
	var _orbx_clock = null;
	var _orbx_clock_ss = null;
	var _orbx_clock_time = null;
	var _orbx_clock_hour = null;
	var _orbx_clock_min = null;
	var _orbx_clock_sec = null;

	function _clock_update()
	{
		try {
			if(!empty(__clock_id)) {
				clearTimeout(__clock_id);
				__clock_id = 0;
			}

			_orbx_clock_time = new Date();
			_orbx_clock_hour = _orbx_clock_time.getHours();
			_orbx_clock_hour = (_orbx_clock_hour < 10) ? "0" + _orbx_clock_hour : _orbx_clock_hour;
			_orbx_clock_min = _orbx_clock_time.getMinutes();
			_orbx_clock_min = (_orbx_clock_min < 10) ? "0" + _orbx_clock_min : _orbx_clock_min;
			_orbx_clock_sec = _orbx_clock_time.getSeconds();
			_orbx_clock_sec = (_orbx_clock_sec < 10) ? "0" + _orbx_clock_sec : _orbx_clock_sec;

			_orbx_clock.innerHTML = "<strong>" + _orbx_clock_hour + ":" + _orbx_clock_min + "</strong>";
			_orbx_clock_ss.innerHTML = "<strong>:" + _orbx_clock_sec + "</strong>";
			__clock_id = setTimeout(_clock_update, 1000);
		} catch(e) {}
	}

	function _clock_start()
	{
		try {
			_orbx_clock = $('orbx_clock_hh_mm');
			_orbx_clock_ss = $('orbx_clock_ss');
			__clock_id = setTimeout(_clock_update, 500);
		} catch(e) {}
	}

	function _clock_stop()
	{
		try {
			if(!empty(__clock_id)) {
				clearTimeout(__clock_id);
				__clock_id = 0;
			}
		} catch(e) {}
	}

YAHOO.util.Event.addListener(window, 'load', _clock_start);
YAHOO.util.Event.addListener(window, 'unload', _clock_stop);