
	function __update_news_items_list(url, value)
	{
		sh_ind();
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				// * update preview
				$('news_items_table').innerHTML = o.responseText;
				// * on-screen effect
				yfade('news_items_table');
				sh_ind();
			}
		}

		var callback =
		{
			success:handleSuccess,
			timeout: 15000
		};

		var data = new Array();
		data[0] = 'news_items_sort_by=' + value;
		data[1] = 'orbx_ajax_id=' + _orbx_ajax_id;

		data = data.join('&');
		YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
	}

	function __news_get_preview_url(url)
	{
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				$('news_url_preview').innerHTML = o.responseText;
			}
		}

		var callback =
		{
		  success:handleSuccess,
		  timeout: 5000
		};

		YAHOO.util.Connect.asyncRequest('POST', url, callback, 'news_title=' + encodeURIComponent($('news_title').value));
	}

	function __news_update_live_date(url, selected_dates)
	{
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				var __dates = o.responseText.split('|');
				$('live_date').value = __dates[0];
				$('live_date_preview').innerHTML = __dates[1];

				yfade('live_date_preview');
			}
		}

		var callback =
		{
			success:handleSuccess,
			timeout: 15000
		};

		// UTC fix
		var xx = orbx_calendar.getSelectedDates()[0];
		selected_dates = (xx.getMonth() + 1) + '/' + xx.getDate() + '/' + xx.getFullYear();

		YAHOO.util.Connect.asyncRequest('POST', url, callback, 'live_date=' + encodeURIComponent(selected_dates));
	}