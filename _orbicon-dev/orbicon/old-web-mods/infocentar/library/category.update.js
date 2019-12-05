function __category_update_list(input, url)
{
	sh_ind();
	var handleSuccess = function(o) {
		if(o.responseText !== undefined) {
		//	alert(o.responseText)
			// * on-screen effect
			yfade('ic_cat_listing');
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