	var __mercury_mini_input = null;
	var __mercury_mini_url = null;
	var __mercury_mini_type = null;
	var __mercury_mini_extra = null;

	function mercury_do_mini_update(permalink, type, extra)
	{
		__mercury_mini_input = permalink;
		__mercury_mini_url = orbx_site_url + "/orbicon/controler/admin.mercury.get_minibrowser.php";
		__mercury_mini_type = type;
		__mercury_mini_extra = extra;
		__mercury_mini_update_list();
	}

	function __mercury_mini_update_list()
	{
		sh_ind();

		if(tinyMCE) {
		//if(oToolbar) {
			var url = orbx_site_url + '/site/mercury/' + __mercury_mini_input;

			if(__mercury_mini_type == 'swf') {
				var xy = __mercury_mini_extra.split(':');
				//if(document.all) {
					__mercury_insert_swf_ie(url, xy[0], xy[1]);
				/*}
				else {
					__mercury_insert_swf(url, xy[0], xy[1]);
				}*/
			}
			else if(__mercury_mini_type == 'flv') {
				var xy = __mercury_mini_extra.split(':');
				// need to convert this value to bool string
				xy[2] = (xy[2] == 0) ? 'false' : 'true';

				__mercury_insert_flv(url, xy[0], xy[1], xy[2]);
			}
			else if(__mercury_mini_type == 'mp3') {
				//if(document.all) {
					__mercury_insert_mp3_ie(url);
				/*}
				else {
					__mercury_insert_mp3(url);
				}*/
			}
			else {
				tinyMCE.execCommand('mceFocus',false,'elm1');

				if(!tinyMCE.get('elm1').selection.getContent()) {
					tinyMCE.execCommand('mceInsertContent', false, '<a href="'+url+'">'+__mercury_mini_input+'<a>', 'elm1');
				}
				else {
					tinyMCE.execCommand('mceInsertLink', false, url);
				}
				//RichTextInsertFileDirect(url);
			}
		}
		sh_ind();
	}

	function __mercury_insert_swf(url, width, height)
	{
		var __flash = encodeURI(url);
		var __flash_no_ext = _mercury_trim_ext(__flash);

		/* WRAPER - Alen */
		var wrap_script = oToolbar.createElement("SCRIPT");
		wrap_script.type = 'text/javascript';

		var js = "AC_FL_RunContent('wmode', 'transparent', 'allowScriptAccess','sameDomain','codebase', 'http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab','width','" + width + "','height','" + height + "','src','" + __flash_no_ext +"','quality','high','pluginspage','http://www.adobe.com/go/getflashplayer','movie','" + __flash_no_ext + "');";

		set_text_content(wrap_script, js);
		wrap_script.innerHTML = js;

		var wrap_noscript = oToolbar.createElement("NOSCRIPT");
		/*****************/

		var object = oToolbar.createElement("OBJECT");
		object.data = __flash;
		object.type = "application/x-shockwave-flash";
		object.width = width;
		object.height = height;

		// set object element
		var object_element = "This content requires the <a href='http://www.adobe.com/go/getflash/' title='Adobe Flash Player'>Adobe Flash Player</a>.\nThis is an object and it represents data that is also available at <a href='"+__flash+"'>" + __flash + '</a>';
		if(object.textContent) {
			object.textContent = object_element;
		}
		else {
			object.innerHTML = object_element;
		}

		var param_movie = oToolbar.createElement("PARAM");
		param_movie.name = "movie";
		param_movie.value = __flash;

		var param_quality = oToolbar.createElement("PARAM");
		param_quality.name = "quality";
		param_quality.value = "high";

		var param_menu = oToolbar.createElement("PARAM");
		param_menu.name = "menu";
		param_menu.value = '0';

		var param_wmode = oToolbar.createElement("PARAM");
		param_wmode.name = "wmode";
		param_wmode.value = 'transparent';

		object.appendChild(param_movie);
		object.appendChild(param_quality);
		object.appendChild(param_menu);
		object.appendChild(param_wmode);

		wrap_noscript.appendChild(object);

		if(window.getSelection) {
			insertNodeAtSelection(__rte_toolbar_win, object);
		}
		else {
			var oDocBody = oToolbar.getElementsByTagName("BODY").item(0);
			oDocBody.appendChild(object);
		}
	}

	function __mercury_insert_mp3(url)
	{
		var __mp3 = encodeURI(url);
		var __mp3_source =  orbx_site_url + '/orbicon/gfx/mp3player.swf';
		var __mp3_no_ext = _mercury_trim_ext(__mp3_source);

		/* WRAPER - Alen */
		var wrap_script = oToolbar.createElement("SCRIPT");
		wrap_script.type = 'text/javascript';

		var js = "AC_FL_RunContent('wmode', 'transparent', 'codebase', 'http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab','width','300','height','20','src','" + __mp3_no_ext +"','quality','high','allowScriptAccess','sameDomain','pluginspage','http://www.adobe.com/go/getflashplayer','movie','" + __mp3_no_ext + "');";
		set_text_content(wrap_script, js);
		wrap_script.innerHTML = js;

		var wrap_noscript = oToolbar.createElement("NOSCRIPT");
		/*****************/

		var object = oToolbar.createElement("OBJECT");
		object.data = __mp3_source;
		object.type = "application/x-shockwave-flash";
		object.width = 300;
		object.height = 20;

		// set object element
		var object_element = "This content requires the <a href=http://www.adobe.com/go/getflash/>Adobe Flash Player</a>.\nThis is an object and it represents data that is also available at " + __mp3;
		if(object.textContent) {
			object.textContent = object_element;
		}
		else {
			object.innerHTML = object_element;
		}

		var param_movie = oToolbar.createElement("PARAM");
		param_movie.name = "movie";
		param_movie.value = orbx_site_url + '/orbicon/gfx/mp3player.swf';

		var param_quality = oToolbar.createElement("PARAM");
		param_quality.name = "quality";
		param_quality.value = "high";

		var param_menu = oToolbar.createElement("PARAM");
		param_menu.name = "menu";
		param_menu.value = 0;

		var param_flashvars = oToolbar.createElement("PARAM");
		param_menu.name = "flashvars";
		param_menu.value = 'file=' + __mp3 + '&autostart=false';

		var param_wmode = oToolbar.createElement("PARAM");
		param_wmode.name = "wmode";
		param_wmode.value = 'transparent';


		object.appendChild(param_movie);
		object.appendChild(param_quality);
		object.appendChild(param_menu);
		object.appendChild(param_flashvars);
		object.appendChild(param_wmode);

		wrap_noscript.appendChild(object);

		if(window.getSelection) {
			insertNodeAtSelection(__rte_toolbar_win, object);
		}
		else {
			var oDocBody = oToolbar.getElementsByTagName("BODY").item(0);
			oDocBody.appendChild(object);
		}
	}

	function __mercury_insert_flv(url, width, height, autoplay)
	{
		var __flv = encodeURI(url);
		var __flv_no_ext = _mercury_trim_ext(__flv);

		// set object element
		var object_element = '<object width="'+width+'" height="'+height+'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0"><embed src="' + orbx_site_url + '/orbicon/gfx/flvplayer.swf?file='+__flv+'&amp;autostart='+autoplay+'" menu="false" allowfullscreen="true" quality="high" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflash" height="' + height + '" width="' + width + '" /><param name="movie" value="' + orbx_site_url + '/orbicon/gfx/flvplayer.swf?file='+__flv+'&autostart='+autoplay+'" /><param name="quality" value="high" /><param name="menu" value="0" /><param name="wmode" value="transparent" /><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="sameDomain" /></object>';

		tinyMCE.execCommand('mceInsertContent', false, object_element);


		//oToolbar.body.innerHTML += object_element;
	}

	function _mercury_trim_ext(filename)
	{
		var basename;
		var f = filename;
		basename = f.substring(0, (f.length - 4));
		return basename;
	}

	function __mercury_insert_swf_ie(url, width, height)
	{
		var __flash = encodeURI(url);
		var __flash_no_ext = _mercury_trim_ext(__flash);

	/*	var wrap_script = "<script type='text/javascript'>AC_FL_RunContent('wmode', 'transparent', 'allowScriptAccess','sameDomain','codebase', 'http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab','width','" + width + "','height','" + height + "','src','" + __flash_no_ext +"','quality','high','pluginspage','http://www.adobe.com/go/getflashplayer','movie','" + __flash_no_ext + "');</script>";*/

		var wrap_noscript = '<object data="'+__flash+'" type="application/x-shockwave-flash" width="'+width+'" height="'+height+'"><param name="movie" value="'+__flash+'" /><param name="quality" value="high" /><param name="menu" value="0" /><param name="wmode" value="transparent" />This content requires the <a href="http://www.adobe.com/go/getflash/" title="Adobe Flash Player">Adobe Flash Player</a>.\nThis is an object and it represents data that is also available at <a href="'+__flash+'">' + __flash + '</a></object>';

		tinyMCE.execCommand('mceInsertRawHTML', false, /*wrap_script + */wrap_noscript);

		//oToolbar.body.innerHTML = oToolbar.body.innerHTML + wrap_script + wrap_noscript;
	}

	function __mercury_insert_mp3_ie(url)
	{
		var __mp3 = encodeURI(url);
		var __mp3_source =  orbx_site_url + '/orbicon/gfx/mp3player.swf';
		var __mp3_no_ext = _mercury_trim_ext(__mp3_source);

		var wrap_script = "<script type='text/javascript'><!-- // --><![CDATA[\nAC_FL_RunContent('wmode', 'transparent', 'allowScriptAccess','sameDomain','codebase', 'http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab','width','300','height','20','allowScriptAccess','sameDomain','flashvars','file='" + __mp3 + "&autostart=false','src','" + __mp3_no_ext +"','quality','high','pluginspage','http://www.adobe.com/go/getflashplayer','movie','" + __mp3_no_ext + "');\n// ]]></script>";

		var wrap_noscript = '<noscript><object data="'+__mp3_source+'" type="application/x-shockwave-flash" width="300" height="20"><param name="movie" value="'+__mp3_source+'" /><param name="flashvars" value="file=' + __mp3 + '&autostart=false" /><param name="quality" value="high" /><param name="menu" value="0" /><param name="wmode" value="transparent" />This content requires the <a href="http://www.adobe.com/go/getflash/" title="Adobe Flash Player">Adobe Flash Player</a>.\nThis is an object and it represents data that is also available at <a href="'+__mp3+'">' + __mp3 + '</a></object></noscript>';

		tinyMCE.execCommand('mceInsertContent', false, wrap_script + wrap_noscript);

		//oToolbar.body.innerHTML = oToolbar.body.innerHTML + wrap_script + wrap_noscript;
	}