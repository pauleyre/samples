// drop down link selector
	function InsertLinkDirect(el)
	{
		var permalink = el.options[el.selectedIndex].getAttribute("value");

		if(!empty(permalink)) {
			var url = orbx_site_url + '/?' + __orbicon_ln + '=' + encodeURIComponent(permalink);
			tinyMCE.execCommand('mceFocus',false,'elm1');
			tinyMCE.execCommand('mceInsertLink', false, url, 'elm1');
		}
	}


	function CleanUpHTML()
	{
		var dom = tinyMCE.getInstanceById('elm1').contentWindow.document;

		dom.execCommand("RemoveFormat", false, null);
		
		var el = null;
		var intLoop = 0;
		/* clean up word content */
		// remove all class and style attributes

		var all_e = (dom.all) ? dom.all : dom.getElementsByTagName('*');

		for(intLoop = 0; intLoop < all_e.length; intLoop++) {
			el = all_e[intLoop];
			el.removeAttribute('class', '', 0);
			el.removeAttribute('style', '', 0);
			el.removeAttribute('className', '', 0);
			// noticed this when pasted from word
			el.removeAttribute('clear', '', 0);
		}

		// remove all xml prefixes and smarttags
		var html = tinyMCE.get('elm1').getContent();

		RegExp.global = true;
		RegExp.multiline = true;

		// all class attributes
		html = html.replace(/(<[^>]+)[ \t\r\n]+class=[^ \t\r\n|>]*([^>]*>)/gi, "$1 $2");

		// all style attributes
		html = html.replace(/(<[^>]+)[ \t\r\n]+style=[^ \t\r\n|>]*([^>]*>)/gi, "$1 $2");

		// all className attributes
		html = html.replace(/(<[^>]+)[ \t\r\n]+className=[^ \t\r\n|>]*([^>]*>)/gi, "$1 $2");

		// all clear attributes
		html = html.replace(/(<[^>]+)[ \t\r\n]+clear=[^ \t\r\n|>]*([^>]*>)/gi, "$1 $2");

		// all lang attributes
		html = html.replace(/(<[^>]+)[ \t\r\n]+lang=[^ \t\r\n|>]*([^>]*>)/gi, "$1 $2");

		// empty style		
		html = html.replace(/(<[^>]+)[ \t\r\n]+style=""([^>]*>)/gi, "$1$2");


		// xml tags		
		html = html.replace(new RegExp('/<?xml[^>]*>/', 'gi'), "");
		html = html.replace(new RegExp('/<xml[^>]*>/', 'gi'), "");
		html = html.replace(/<\?xml[^>]*>/gi, "");
		html = html.replace(/<xml[^>]*>/gi, "");
		html = html.replace(/<\?[^>]*\?>/gi, "")
		

		// all del and ins tags
		html = html.replace(/<del[^>]*>.*<\/del>/gi, "");
		html = html.replace(/<ins[^>]*>(.*)<\/ins>/gi, "$1");

		// language
		html = html.replace(/(<[^>]+)[ \t\r\n]+lang=[^ \t\r\n|>]*([^>]*>)/gi, "$1 $2");

		// Mso* class attributes
		html = html.replace(/(<[^>]+)[ \t\r\n]+class=Mso[^ \t\r\n>]*([^>]*>)/gi, "$1 $2");
		html = html.replace(/(<[^>]+)[ \t\r\n]+class="Mso[^ \t\r\n>"]*"([^>]*>)/gi, "$1 $2");

		// mso-* style attributes
		html = html.replace(/(<[^>]+[ \t\r\n]+style="[^"]*)[; \t\r\n]*mso-[^:]+:[^;"]+;([^"]*"[^>]*>)/gi, "$1$2");
		html = html.replace(/(<[^>]+[ \t\r\n]+style="[^"]*)[; \t\r\n]*mso-[^:]+:[^;"]+("[^>]*>)/gi, "$1$2");
		html = html.replace(/(<[^>]+[ \t\r\n]+style=")mso-[^:]+:[^;"]+;([^"]*"[^>]*>)/gi, "$1$2");
		html = html.replace(/(<[^>]+[ \t\r\n]+style=")mso-[^:]+:[^;"]+("[^>]*>)/gi, "$1$2");

		// mso-* style attributes in single-quotes inside mso conditionals
		html = html.replace(/(<[^>]+[ \t\r\n]+style='[^']*)[; \t\r\n]*mso-[^:]+:[^;']+;([^']*'[^>]*>)/gi, "$1$2");
		html = html.replace(/(<[^>]+[ \t\r\n]+style='[^']*)[; \t\r\n]*mso-[^:]+:[^;']+('[^>]*>)/gi, "$1$2");
		html = html.replace(/(<[^>]+[ \t\r\n]+style=')mso-[^:]+:[^;']+;([^']*'[^>]*>)/gi, "$1$2");
		html = html.replace(/(<[^>]+[ \t\r\n]+style=')mso-[^:]+:[^;']+('[^>]*>)/gi, "$1$2");

		// [if *] ... [endif]
		while (true) {
			var ifend = html.indexOf("<![endif]-->");
			var ifstart = html.substring(0,ifend).lastIndexOf("<!--[if");
			if ((ifend < 0) || (ifstart < 0)) break;
			html = html.substring(0,ifstart) + html.substring(ifend+12);
		}
		while (true) {
			var ifend = html.indexOf("[endif]");
			var ifstart = html.substring(0,ifend).lastIndexOf("[if");
			if ((ifend < 0) || (ifstart < 0)) break;
			html = html.substring(0,ifstart) + html.substring(ifend+7);
		}

		// double and empty span tags and span tags without attributes
		html = html.replace(/<span *><span *>([^<]*)<\/span><\/span>/gi, "<span>$1</span>");
		html = html.replace(/<span><\/span>/gi, "");
		html = html.replace(/<span[^>]*><\/span>/gi, "");
		html = html.replace(/<span *>([^<]*)<\/span>/gi, "$1");

		// double and empty font tags and font tags without attributes
		html = html.replace(/<font><font>([^<]*)<\/font><\/font>/gi, "<font>$1</font>");
		html = html.replace(/<font[^>]*><\/font>/gi, "");
		html = html.replace(/<font>([^<]*)<\/font>/gi, "$1");

		html = html.replace(/<p[^>]+>&nbsp;<\/p>/gi, "");
		html = html.replace(/<p[^>]+><\/p>/gi, "");
		html = html.replace(/<div[^>]+>&nbsp;<\/div>/gi, "");
		html = html.replace(/<div[^>]+><\/div>/gi, "");

		// Crossed P and SPAN tags
		html = html.replace(/<p([ \t\r\n]+[^<]*)?><span([ \t\r\n]+[^<]*)?>([^<]*)<\/p><\/span>/gi, "<p $1><span $2>$3</span></p>");
		html = html.replace(/<span([ \t\r\n]+[^<]*)?><p([ \t\r\n]+[^<]*)?>([^<]*)<\/span><\/p>/gi, "<p $1><span $2>$3</span></p>");

		html = html.replace(new RegExp('<o:[pP]><\/o:[pP]>', 'g'), ''); // Remove all instances of
		//html = html.replace(new RegExp('<[pP]><\/[pP]>', 'g'), ''); // delete all empty paragraph tags
		//html = html.replace(new RegExp('<[pP]>&nbsp;<\/[pP]>', 'g'), ''); // delete all empty paragraph tags
		//html = html.replace(new RegExp('<[spanSPAN]><\/[spanSPAN]>', 'g'), ''); // delete all empty span tags
		html = html.replace(new RegExp('<[spanSPAN]>&nbsp;<\/[spanSPAN]>', 'g'), ''); // delete all empty span tags
		html = html.replace(new RegExp('&nbsp;', 'gi'), ' '); // replace all nbsps
		html = html.replace(new RegExp('\n', 'gi'), ' ');
		html = html.replace(new RegExp('\r', 'gi'), '');

		// remove comments since they are visible sometimes in ie
		html = html.replace(new RegExp('<!--([^>]+)-->', 'gi'), '');
		// this one's just for IE
		html = html.replace(new RegExp('&lt;!--([^>]+)--&gt;', 'gi'), '');


		tinyMCE.execCommand('mceSetContent', false, html, 'elm1');
	}
	
	function NewDocument()
	{
		$('current_edit').value = '';
		tinyMCE.execCommand('mceSetContent', false, '');
	}