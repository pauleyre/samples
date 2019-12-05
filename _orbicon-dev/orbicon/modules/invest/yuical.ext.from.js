	function update_calendars()
	{
		var menu = $('fond');
		var min = menu.options[menu.selectedIndex].getAttribute('min');
		var max = menu.options[menu.selectedIndex].getAttribute('max');
		var current = max.split('/');
		current = current[0] + '/' + current[2];

		invest_from.cfg.setProperty("pagedate", current);
		invest_from.cfg.setProperty("mindate", min);
		invest_from.cfg.setProperty("maxdate", max);

		invest_till.cfg.setProperty("pagedate", current);
		invest_till.cfg.setProperty("mindate", min);
		invest_till.cfg.setProperty("maxdate", max);

		invest_from.addMonthRenderer(1, invest_from.renderBodyCellRestricted);
		invest_from.addMonthRenderer(2, invest_from.renderBodyCellRestricted);
		invest_from.addMonthRenderer(3, invest_from.renderBodyCellRestricted);
		invest_from.addMonthRenderer(4, invest_from.renderBodyCellRestricted);
		invest_from.addMonthRenderer(5, invest_from.renderBodyCellRestricted);
		invest_from.addMonthRenderer(6, invest_from.renderBodyCellRestricted);
		invest_from.addMonthRenderer(7, invest_from.renderBodyCellRestricted);
		invest_from.addMonthRenderer(8, invest_from.renderBodyCellRestricted);
		invest_from.addMonthRenderer(9, invest_from.renderBodyCellRestricted);
		invest_from.addMonthRenderer(10, invest_from.renderBodyCellRestricted);
		invest_from.addMonthRenderer(11, invest_from.renderBodyCellRestricted);
		invest_from.addMonthRenderer(12, invest_from.renderBodyCellRestricted);

		invest_till.addMonthRenderer(1, invest_till.renderBodyCellRestricted);
		invest_till.addMonthRenderer(2, invest_till.renderBodyCellRestricted);
		invest_till.addMonthRenderer(3, invest_till.renderBodyCellRestricted);
		invest_till.addMonthRenderer(4, invest_till.renderBodyCellRestricted);
		invest_till.addMonthRenderer(5, invest_till.renderBodyCellRestricted);
		invest_till.addMonthRenderer(6, invest_till.renderBodyCellRestricted);
		invest_till.addMonthRenderer(7, invest_till.renderBodyCellRestricted);
		invest_till.addMonthRenderer(8, invest_till.renderBodyCellRestricted);
		invest_till.addMonthRenderer(9, invest_till.renderBodyCellRestricted);
		invest_till.addMonthRenderer(10, invest_till.renderBodyCellRestricted);
		invest_till.addMonthRenderer(11, invest_till.renderBodyCellRestricted);
		invest_till.addMonthRenderer(12, invest_till.renderBodyCellRestricted);

		invest_from.render();
		invest_till.render();

		myChangePageHandlerFrom();
		myChangePageHandlerTill();
	}

	var myChangePageHandlerFrom = function(type,args,obj)
	{
		var month = invest_from.cfg.getProperty("pagedate").getMonth() + 1;
		if (month.toString().length == 1) {
			month = "0" + month;
		}
		var year = invest_from.cfg.getProperty("pagedate").getFullYear();

		checkPostsForMonthFrom(month, year);
	};

	function checkPostsForMonthFrom(month,year)
	{
		var handleSuccess = function(o) {
		if(o.responseText !== undefined) {
				if(!empty(o.responseText)) {
					checkPostsCallbackFrom(o.responseText);
				}
			}
		}

		var callback =
		{
			success:handleSuccess,
			timeout: 15000
		};

		var menu = $('fond');
		var data = new Array();
		data[0] = 'month=' + month;
		data[1] = 'year=' + year;
		data[2] = 'fond=' + menu.options[menu.selectedIndex].value;

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', orbx_site_url + '/orbicon/modules/invest/xhr.invest.caldates.php', callback, data);
	};


	function checkPostsCallbackFrom(dates) {

			dates = dates.split(',');

			for(i = 0; i < dates.length; i++) {
				invest_from.addRenderer(dates[i], myCustomRendererFrom);
			}
			invest_from.render();

	};

	var myCustomRendererFrom = function(workingDate, cell) {

		var day = workingDate.toString().substr(8,2);
		if (day.substr(0,1) == "0") {
			day = day.substr(1);
		}

		cell.innerHTML = '<div><a href="javascript:void(null);" >' + invest_from.buildDayLabel(workingDate) + "</a></div>";
		YAHOO.util.Dom.addClass(cell, invest_from.Style.CSS_CELL_SELECTABLE);
		return YAHOO.widget.Calendar.STOP_RENDER;
	}