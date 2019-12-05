
	function rel_search(rel)
	{
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				var related_container = $('related_container');
				try {
					related_container.innerHTML = o.responseText;
				}
				catch(e){}
				finally {
					related_container.value = o.responseText;
				}
			}
		}

		var callback =
		{
			success:handleSuccess,
			timeout: 15000
		};

		rel = encodeURIComponent(rel);

		if(rel != null) {
			var url = orbx_site_url + '/?related';
			var data = new Array();
			data[0] = 'rel=' + rel;

			data = data.join('&');

			YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
		}
	}