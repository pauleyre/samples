	var __magister_mini_input = null;
	var __magister_mini_url = null;
	var __magister_column_type = null;

	function magister_do_mini_update(permalink, source_el)
	{
		__magister_mini_input = permalink;
		__magister_mini_url = orbx_site_url + "/?" + __orbicon_ln +"=orbicon&ajax_text_db&action=txt";
		__magister_mini_update_list();

		// update news/newsletter title if empty
		try {
			var news_title = $('news_title');
			var newsletter_title = $("newsletter_title");

			if(!empty(news_title)) {
				news_title.value = source_el.innerHTML;
				/* permalink preview*/
				__news_get_preview_url(orbx_site_url + '/orbicon/controler/admin.news.preview_url.php');
			}
			else if(!empty(newsletter_title)) {
				newsletter_title.value = source_el.innerHTML;
			}

		} catch(e) {}
	}

	function __magister_mini_update_list()
	{
		var handleSuccess = function(o) {
			if(o.responseText !== undefined)
			{
				// * update preview div
				var magister_content = $('news_content');
				try {
					magister_content.innerHTML = o.responseText;
					// add preview and intro doubleclick event
					var target_url = orbx_site_url + '/?' + __orbicon_ln +'=orbicon/magister&read=clanak/' + __magister_mini_input;
					if((__magister_column_type == null) || (__magister_column_type == 'default')) {
						YAHOO.util.Event.addListener([magister_content, $('news_intro')],"dblclick",function () {redirect(target_url);});
					}
				} catch(e) {}
				// * update input
				var content_input = $('content_text');
				try {
					content_input.value = __magister_mini_input;
				} catch(e) {}
				// * on-screen effect
				yfade('news_content');
				//__magister_mini_update_intro();
				sh_ind();
			}
		}

		var callback =
		{
			success:handleSuccess
		};

		if(!empty(__magister_mini_input)) {
			sh_ind();
			YAHOO.util.Connect.asyncRequest('POST', __magister_mini_url, callback, 'permalink=' + __magister_mini_input + '&column_type=' + __magister_column_type);
		}
	}

	function __magister_mini_update_intro()
	{
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				var magister_content = $('news_intro_list');
				try {
					magister_content.innerHTML = o.responseText;
					yfade('news_intro_list');
				} catch(e) {}
			}
		}

		var callback =
		{
		  success:handleSuccess,
		  timeout: 15000
		};

		if(!empty(__magister_mini_input)) {
			YAHOO.util.Connect.asyncRequest('POST', __magister_mini_url, callback, 'intro_permalink=' + __magister_mini_input);
		}
	}

	function __change_intro_text(input, id)
	{
		var intro = $('news_intro');

		if(!empty(intro)) {
			var intro_input = $('intro_text');

			js_base64(input, 'decode', intro);
			intro_input.value = id;
			yfade('news_intro');
		}
		else {
			window.alert('Please select the full text');
		}
	}