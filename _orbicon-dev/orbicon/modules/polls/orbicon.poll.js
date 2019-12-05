
	function __poll_cast_vote(_orbx_poll)
	{
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				// * update preview
				$('poll_inner_' + _orbx_poll).innerHTML = o.responseText;
			}
		}

		var callback =
		{
			success:handleSuccess,
			timeout: 15000
		};

		var selected = getCheckedValue(document.forms['poll_form_' + _orbx_poll].elements['poll_' + _orbx_poll]);

		if(!empty(selected)) {
			var url = orbx_site_url + '/orbicon/modules/polls/poll.actions.php';
			var data = new Array();
			data[0] = 'poll=' + _orbx_poll;
			data[1] = 'poll_option=' + selected;
			data[2] = 'castvote=castvote';

			data = data.join('&');

			YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
		}
	}

	function __poll_view_results(_orbx_poll, show_controls)
	{
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				// * update preview
				try {
					$('poll_inner_' + _orbx_poll).innerHTML = o.responseText;
				}
				catch(e) {
					$('past_polls_preview').innerHTML = o.responseText;
				}
			}
		}

		var callback =
		{
			success:handleSuccess
		};

		var url = orbx_site_url + '/orbicon/modules/polls/poll.actions.php';
		var data = new Array();
		data[0] = 'poll=' + _orbx_poll;
		data[1] = 'show_controls=' + show_controls;
		data[2] = 'viewresults=viewresults';

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
	}

	function __poll_view_vote(_orbx_poll)
	{
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				// * update preview
				$('poll_inner_' + _orbx_poll).innerHTML = o.responseText;
			}
		}

		var callback =
		{
			success:handleSuccess
		};

		var url = orbx_site_url + '/orbicon/modules/polls/poll.actions.php';
		var data = new Array();
		data[0] = 'poll=' + _orbx_poll;
		data[1] = 'viewpolls=viewpolls';

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
	}

	function __survey_cast_vote(_orbx_poll, msg)
	{
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				// * update preview
				$('poll_inner_' + _orbx_poll).innerHTML = o.responseText;
			}
		}

		var callback =
		{
			success:handleSuccess,
			timeout: 15000
		};

		var i;
		var selected = new Array();
		var menu;
		var value;
		var selects = $('poll_form_' + _orbx_poll).getElementsByTagName('select');

		for(i = 0; i < selects.length; i++) {
			try {
				menu = selects[i];
				value = menu.options[menu.selectedIndex].value;

				if(typeof menu == 'object' && empty(value)) {
					window.alert(msg);
					return false;
				}
				selected[i] = menu.id + '=' + menu.options[menu.selectedIndex].value;
			} catch (e) {}
		}

		selected = selected.join('*');

		if(!empty(selected)) {
			var url = orbx_site_url + '/orbicon/modules/polls/poll.actions.php';
			var data = new Array();
			data[0] = 'poll=' + _orbx_poll;
			data[1] = 'poll_option=' + selected;
			data[2] = 'castvote_survey=castvote_survey';

			data = data.join('&');

			YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
		}
	}