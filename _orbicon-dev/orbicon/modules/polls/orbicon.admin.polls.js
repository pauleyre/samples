	var __polls_list_window = true;

	function __update_polls_items_list(url, value)
	{
		sh_ind();
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				// * update preview
				$('polls_items_table').innerHTML = o.responseText;
				// * on-screen effect
				yfade('polls_items_table');
				sh_ind();
			}
		}

		var callback =
		{
			success:handleSuccess,
			timeout: 15000
		};

		YAHOO.util.Connect.asyncRequest('POST', url, callback, 'poll_items_sort_by=' + value);
	}

	function __polls_update_live_date(url, selected_dates)
	{
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				var __two_dates = o.responseText.split('*');
				// start
				var __dates = __two_dates[0].split('|');
				$('poll_start_date').value = __dates[0];
				$('live_date_preview_start').innerHTML = __dates[1];

				yfade('live_date_preview_start');

				// end
				if(__two_dates[1] != undefined) {
					var __dates = __two_dates[1].split('|');
					$('poll_end_date').value = __dates[0];
					$('live_date_preview_end').innerHTML = __dates[1];
				}
				else {
					$('live_date_preview_end').innerHTML = '+';
					$('poll_end_date').value = 0;
				}

				yfade('live_date_preview_end');
			}
		}

		var callback =
		{
		  success:handleSuccess,
		  timeout: 15000
		};

		// UTC fix
		var xx = orbx_dual_cal.getSelectedDates()[0];
		selected_dates = (xx.getMonth() + 1) + '/' + xx.getDate() + '/' + xx.getFullYear();

		// this is a feature, we selected start date but not end meaning it will expire upon start of next poll
		try {
			var xx2 = orbx_dual_cal.getSelectedDates()[1];
			selected_dates2 = (xx2.getMonth() + 1) + '/' + xx2.getDate() + '/' + xx2.getFullYear();

			selected_dates = selected_dates + ',' + selected_dates2;
		}
		catch (e) {}

		YAHOO.util.Connect.asyncRequest('POST', url, callback, 'live_date=' + encodeURIComponent(selected_dates));
	}