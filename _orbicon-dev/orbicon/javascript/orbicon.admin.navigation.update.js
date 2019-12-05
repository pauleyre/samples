	function __navigation_update_list(input, url)
	{
		sh_ind();
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				// * on-screen effect
				yfade('navigation_list');
				sh_ind();
			}
		}

		var callback =
		{
		  success:handleSuccess,
		  timeout: 15000
		};

		YAHOO.util.Connect.asyncRequest('POST', url, callback, input);
	}