	function handleSelect_admin(type,args,obj)
	{
		var dates = args[0];
		var date = dates[0];
		var year = date[0], month = date[1], day = date[2];
		update_table_admin(month + '/' + day + '/' + year, true);

		var selMonth = $("selMonth");
		var selDay = $("selDay");
		var selYear = $("selYear");

		selMonth.selectedIndex = month;
		selDay.selectedIndex = day;

		for (var y=0;y<selYear.options.length;y++) {
			if (selYear.options[y].text == year) {
				selYear.selectedIndex = y;
				break;
			}
		}
	}

	function updateCal_admin()
	{
		var selMonth = $("selMonth");
		var selDay = $("selDay");
		var selYear = $("selYear");

		var month = parseInt(selMonth.options[selMonth.selectedIndex].text);
		var day = parseInt(selDay.options[selDay.selectedIndex].value);
		var year = parseInt(selYear.options[selYear.selectedIndex].value);

		if (! isNaN(month) && ! isNaN(day) && ! isNaN(year)) {

			var date = month + "/" + day + "/" + year;

			update_table_admin(date, false);

			orbx_calendar.select(date);
			orbx_calendar.cfg.setProperty("pagedate", month + "/" + year);
			orbx_calendar.render();
		}
	}

	function update_table_admin(date, msg)
	{
		sh_ind();

		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				if(!empty(o.responseText)) {
					// * update mini browser container
					$('exch_table_container').innerHTML = o.responseText;
				}
				else {
					if(msg == true) {
						window.alert('No table for date ' + date);
					}
				}
				sh_ind();
			}
		}

		var callback =
		{
			success:handleSuccess,
			timeout: 15000
		};

		var data = new Array();
		data[0] = 'date=' + date;

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', orbx_site_url + '/orbicon/modules/exchange-rates/xhr.exch_rates.table.php', callback, data);
	}