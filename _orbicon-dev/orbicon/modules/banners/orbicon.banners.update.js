	function __banners_update(permalink)
	{
		sh_ind();
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				sh_ind();
			}
		}

		var callback =
		{
			success:handleSuccess,
			timeout: 15000
		};

		var url = orbx_site_url + '/orbicon/modules/banners/admin.banners.update.php';
		var data = new Array();
		var zone = $('zone_' + permalink);
		var client = $('client_' + permalink);
		var type = $('type_' + permalink);

		try {
			data[0] = 'permalink=' + permalink;
			data[1] = 'displays=' + $('displays_' + permalink).value;
			data[2] = 'client=' + client.options[client.options.selectedIndex].value;
			data[3] = 'zone=' + zone.options[zone.options.selectedIndex].value;
			data[4] = 'img_url=' + encodeURIComponent($('img_url_' + permalink).value);
			data[5] = 'type=' + type.options[type.options.selectedIndex].value;

			data = data.join('&');
		} catch(e) {}

		var request = YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
	}