
	function switch_mini_browser(type, category, browseable, start)
	{
		sh_ind();

		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				// * update mini browser container
				$('mini_browser_container').innerHTML = o.responseText;
				sh_ind();
			}
		}

		var callback =
		{
			success:handleSuccess,
			timeout: 15000
		};

		var data = new Array();
		data[0] = 'mini_browser=' + type;
		data[1] = 'category=' + category;
		data[2] = 'browseable=' + browseable;
		data[3] = 'start=' + start;
		// this is required for redirection
		data[4] = __orbicon_get_q;

		try {
			var search_input = $('minibrowser_search');
			if((typeof search_input.value == 'string') && !empty(search_input.value)) {
				data[5] = 'search=' + search_input.value;
			}
		} catch(e) {}

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', orbx_site_url + '/orbicon/controler/admin.switch_minibrowser.php', callback, data);
	}

	function get_enter_pressed(evt)
	{
		var CR = 13; // Carriage Return
		var charCode;

		if(evt.which) {
			charCode = evt.which;
		}
		else {
			charCode = evt.keyCode;
		}

		if(charCode == CR) {
			return true;
		}
		return false;
	}