	var _rte_lite_win = null;

	function rte_lite_load()
	{
		var __rte_toolbar = $('rte_lite_content');

		if(empty(__rte_toolbar) || typeof __rte_toolbar != 'object') {
			return false;
		}

		if(__rte_toolbar.contentWindow.document) {
			rte_lite = __rte_toolbar.contentWindow.document;

			_rte_lite_win = __rte_toolbar.contentWindow;

			// * Start up
			if(rte_lite.designMode) {
				rte_lite.designMode = "On";
				if(rte_lite.contentEditable) {
					rte_lite.contentEditable = true;
				}
				try {
					rte_lite.execCommand("2D-Position", true, true);
					rte_lite.execCommand("MultipleSelection", true, true);
				} catch(e) {}
			}
			if(rte_lite.addEventListener) {
				YAHOO.util.Event.addListener(rte_lite, "keypress", rte_lite_keys);
			}
		}
	}

	function rte_lite_bold() {
		rte_lite.execCommand("Bold", false, null);
	}

	function rte_lite_italic() {
		rte_lite.execCommand("Italic", false, null);
	}

	function rte_lite_underline() {
		rte_lite.execCommand("Underline", false, null);
	}

	function rte_lite_strikethrough() {
		rte_lite.execCommand("Strikethrough", false, null);
	}

	// * Insert hyperlink
	function rte_lite_link()
	{
		var _bHyperlink = false;

		try {
			rte_lite.execCommand("CreateLink", true);
			_bHyperlink = true;
		}
		catch(e) {}
		finally {
			if(window.prompt && !_bHyperlink) {
				var sSelected = _rte_lite_win.getSelection();

				if(empty(sSelected) && (sSelected != '0')) {
					window.alert('Selektirajte tekst koji želite pretvoriti u link');
					return;
				}

				// This section of code is used to display "mailto:" or "http://" prefix in alert window when user selects text with a onclick event and transforms section of selected text into link
				var field = sSelected.toString(); // needed for transformation sSelected to string because it is an object
				if((field.search(new RegExp('@', 'gi')) != -1)) {
					var sLinkSource = window.prompt('Enter e-mail...', 'mailto:' + (sSelected));
				}
				else {
					var sLinkSource;

					// http already present
					if((field.search(new RegExp('http://', 'gi')) != -1)) {
						sLinkSource = window.prompt('Enter URL...', sSelected);
					}
					// https already present
					else if((field.search(new RegExp('https://', 'gi')) != -1)) {
						sLinkSource = window.prompt('Enter URL...', sSelected);
					}
					// nothing found, add http by default
					else {
						sLinkSource = window.prompt('Enter URL...', 'http://' + (sSelected));
					}
				}

				if(sLinkSource != null) {
					rte_lite.execCommand("CreateLink", false, encodeURI(sLinkSource));
				}
			}
		}
	}

	// alias
	function rlis(url) {
		rte_lite_insert_smiley(url);
	}

	// * Insert image via url parameter (no GUI)
	function rte_lite_insert_smiley(url)
	{
		if(!empty(url)) {
			_rte_lite_win.focus()
			rte_lite.execCommand("InsertImage", false, encodeURI(url));
		}
	}

	function rte_lite_keys(e)
	{
		var ctrl_pressed;
		var key_pressed;
		var clean_key_pressed;
		var cancel_propagation = false;

		if(e) {
			ctrl_pressed = (e.modifiers) ? (e.modifiers & Event.CONTROL_MASK) : e.ctrlKey;
			clean_key_pressed = (e.which) ? e.which : e.keyCode;
		}

		if(ctrl_pressed) {
			key_pressed = String.fromCharCode(clean_key_pressed);
			key_pressed = key_pressed.toUpperCase();
			cancel_propagation = true;

			// CTRL
			if(ctrl_pressed) {
				switch(key_pressed) {
					//character
					case 'B':	rte_lite_bold();			break;// bold
					case 'U':	rte_lite_underline();		break;// underline
					case 'I':	rte_lite_italic();			break;// italic
					case 'K':	rte_lite_link();	 		break;// hyperlink

					default: 	cancel_propagation = false; break;// don't cancel
				}
			}
			else {
				cancel_propagation = false;// don't cancel
			}

			if(cancel_propagation) {
				if(e.preventDefault) {
					e.preventDefault();
				}
				if(e.stopPropagation) {
					e.stopPropagation();
				}
				else if(e.cancelBubble) {
					e.cancelBubble = true;
				}
				return false;
			}
		}
	}