	var myChangePageHandlerTill = function(type,args,obj)
	{
		var month = invest_till.cfg.getProperty("pagedate").getMonth() + 1;
		if (month.toString().length == 1) {
			month = "0" + month;
		}
		var year = invest_till.cfg.getProperty("pagedate").getFullYear();

		checkPostsForMonthTill(month, year);
	};

	function checkPostsForMonthTill(month,year)
	{
		var handleSuccess = function(o) {
		if(o.responseText !== undefined) {
				if(!empty(o.responseText)) {
					checkPostsCallbackTill(o.responseText);
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


	function checkPostsCallbackTill(dates) {

			dates = dates.split(',');

			for(i = 0; i < dates.length; i++) {
				invest_till.addRenderer(dates[i], myCustomRendererTill);
			}
			invest_till.render();

	};

	var myCustomRendererTill = function(workingDate, cell) {

		var day = workingDate.toString().substr(8,2);
		if (day.substr(0,1) == "0") {
			day = day.substr(1);
		}

		cell.innerHTML = '<div><a href="javascript:void(null);" >' + invest_till.buildDayLabel(workingDate) + "</a></div>";
		YAHOO.util.Dom.addClass(cell, invest_till.Style.CSS_CELL_SELECTABLE);
		return YAHOO.widget.Calendar.STOP_RENDER;
	}