	var myChangePageHandler = function(type,args,obj)
	{
		var month = orbx_calendar.cfg.getProperty("pagedate").getMonth() + 1;
		if (month.toString().length == 1) {
			month = "0" + month;
		}
		var year = orbx_calendar.cfg.getProperty("pagedate").getFullYear();

		checkPostsForMonth(month, year);
	};

	function checkPostsForMonth(month,year)
	{
		var handleSuccess = function(o) {
		if(o.responseText !== undefined) {
				if(!empty(o.responseText)) {
					checkPostsCallback(o.responseText);
				}
			}
		}

		var callback =
		{
			success:handleSuccess,
			timeout: 15000
		};

		var data = new Array();
		data[0] = 'month=' + month;
		data[1] = 'year=' + year;

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', orbx_site_url + '/orbicon/modules/exchange-rates/xhr.exch_rates.caldates.php', callback, data);
	};


	function checkPostsCallback(dates) {

			dates = dates.split(',');

			for(i = 0; i < dates.length; i++) {
				orbx_calendar.addRenderer(dates[i], myCustomRenderer);
			}
			orbx_calendar.render();

	};

	var myCustomRenderer = function(workingDate, cell) {

		var day = workingDate.toString().substr(8,2);
		if (day.substr(0,1) == "0") {
			day = day.substr(1);
		}

		cell.innerHTML = '<div><a href="javascript:void(null);" >' + orbx_calendar.buildDayLabel(workingDate) + '</a></div>';
		YAHOO.util.Dom.addClass(cell, orbx_calendar.Style.CSS_CELL_SELECTABLE);
		return YAHOO.widget.Calendar.STOP_RENDER;
	}