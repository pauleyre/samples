
	function __update_site_editors(url)
	{
		sh_ind();
		var handleSuccess = function(o)
		{
			if(o.responseText !== undefined)
			{
				// * update preview
				$('site_editors_list').innerHTML = o.responseText;
				// * on-screen effect
				yfade('site_editors_list');
				sh_ind();
			}
		}

		var callback =
		{
		  success:handleSuccess,
		  timeout: 15000
		};

		var status = $('status');

		var data = new Array();
		data[0] = 'first_name=' + $('first_name').value;
		data[1] = 'last_name=' + $('last_name').value;
		data[2] = 'pwd=' + $('pwd').value;
		data[3] = 'email=' + $('email').value;
		data[4] = 'mob=' + $('mob').value;
		data[5] = 'tel=' + $('tel').value;
		data[6] = 'occupation=' + $('occupation').value;
		data[7] = 'status=' + status.options[status.selectedIndex].value;
		data[8] = 'notes=' + $('notes').value;
		data[9] = 'id=' + $('id').value;
		data[10] = 'action=' + $('action').value;
		data[11] = 'username=' + $('username').value;
		data[12] = 'old_username=' + $('old_username').value;
		data[13] = 'old_password=' + $('old_password').value;

		data = data.join('&');
		var request = YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
	}

	function delete_site_editor(url, id)
	{
		sh_ind();
		if(!window.confirm("Are you sure you want to remove this employee?")) {
			sh_ind();
			return false;
		}

		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				// * update preview
				var editors_list = $('site_editors_list');
				//alert(o.responseText);
				editors_list.innerHTML = o.responseText;
				// * on-screen effect
				yfade('site_editors_list');
				sh_ind();
			}
		}

		var callback =
		{
		  success:handleSuccess,
		  timeout: 15000
		};

		var data = new Array();
		data[0] = 'id=' + id;
		data[1] = 'action=delete';

		data = data.join('&');
		var request = YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
	}