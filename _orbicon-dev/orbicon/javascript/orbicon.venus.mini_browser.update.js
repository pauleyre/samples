	var __venus_mini_input = null;
	var __venus_mini_url = null;

	function __venus_mini_update_list()
	{
		sh_ind();
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				//if(oToolbar) {
				if(tinyMCE) {
					var url = orbx_site_url + '/site/venus/' + __venus_mini_input;
					//RichTextInsertImageDirect(url);
					tinyMCE.execCommand('mceInsertContent',false,'<img src="'+url+'" />');
				}
				sh_ind();
			}
		}

		var callback =
		{
			success:handleSuccess,
			timeout: 15000
		};

		var venus_content = $('news_image');
		if(typeof venus_content == 'object' && !empty(venus_content)) {
			venus_content.innerHTML = '<div style="overflow:auto;"><img src="' + orbx_site_url + '/site/venus/' + __venus_mini_input + '" /></div>';

			YAHOO.util.Event.addListener(venus_content,"dblclick",function () {redirect(orbx_site_url + '/?' + __orbicon_ln +'=orbicon/venus&read=expo/' + __venus_mini_input);});

			// * update input
			var img_input = $('news_img');
			img_input.value = __venus_mini_input;
			// * on-screen effect
			yfade('news_image');

			sh_ind();
		}
		//else if(oToolbar) {
		else if(tinyMCE) {
			YAHOO.util.Connect.asyncRequest('POST', __venus_mini_url, callback, 'permalink=' + __venus_mini_input);
		}
	}

	function venus_do_mini_update(permalink)
	{
		__venus_mini_input = permalink;
		__venus_mini_url = orbx_site_url + '?' + __orbicon_ln + "=orbicon&ajax_img_db&action=img";
		__venus_mini_update_list();
	}