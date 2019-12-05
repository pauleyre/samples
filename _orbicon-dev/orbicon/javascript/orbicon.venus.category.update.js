	function __venus_cat_update_list(url)
	{
		sh_ind();
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				try {
					$('venus_cat').innerHTML = '<select name="category" id="category" tabindex="1">' +
					o.responseText +
					'</select>';
				} catch (e) {}
			}
			sh_ind();
		}

		var callback =
		{
		  success:handleSuccess,
		  timeout: 15000
		};

		var input = null;
		var new_categories = $('new_venus_category');
		if(new_categories.value) {
			input = new_categories.value;
			new_categories.value = '';
		}
		else if(new_categories.innerText) {
			input = new_categories.innerText;
			new_categories.innerText = '';
		}

		YAHOO.util.Connect.asyncRequest('POST', url, callback, 'new_venus_category=' + input);
	}