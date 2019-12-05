// javascript base64 encoder/decoder

function js_base64(string, action, element)
{
	if(action == 'encode')
    {
		// * check for built-in
		if(window.btoa) {
			element.innerHTML = btoa(encodeURIComponent(string));
		}
		// * fall back to ajax/php combo
		else {
			var handleSuccess = function(o) {
				if(o.responseText !== undefined) {
					// ie workaround for return undefined value
					element.innerHTML = o.responseText;
				}
			}

			var callback =
			{
				success:handleSuccess,
				timeout: 15000
			};

			var url = orbx_site_url + '/orbicon/controler/base64.convert.php';

			var data = new Array();
			data[0] = 'input=' + encodeURIComponent(string);
			data[1] = 'action=encode';

			data = data.join('&');

			YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
		}
    }
    else if(action == 'decode')
    {
		// * check for built-in
		if(window.atob) {
			element.innerHTML = atob(string);
		}
		// * fall back to ajax/php combo
		else
		{
			var handleSuccess = function(o) {
				if(o.responseText !== undefined) {
					// ie workaround for return undefined value
					element.innerHTML = o.responseText;
				}
			}

			var callback =
			{
				success:handleSuccess,
				timeout: 15000
			};

			var url = orbx_site_url + '/orbicon/controler/base64.convert.php';

			var data = new Array();
			data[0] = 'input=' + encodeURIComponent(string);
			data[1] = 'action=decode';

			data = data.join('&');

			YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
		}
	}
}