function pring_mail_autocomplete()
{
	// create autocomplete container if we haven't got one already
	if(empty($("pring_mail_container"))) {
		var _body = window.document.getElementsByTagName("BODY").item(0);
		var _a = window.document.createElement("DIV");
		_a.id = 'pring_mail_container';
		_body.appendChild(_a);
	}

	var url = orbx_site_url + '/orbicon/modules/peoplering/xhr.user_contacts.php';

	// Instantiate one XHR DataSource and define schema as an array:
	//     ["Record Delimiter",
	//     "Field Delimiter"]
	var oACDS = new YAHOO.widget.DS_XHR(url, ["\n", "\t"]);
	oACDS.responseType = YAHOO.widget.DS_XHR.TYPE_FLAT;
	oACDS.maxCacheEntries = 20;
	oACDS.allowBrowserAutocomplete = false;

	// Instantiate AutoComplete
	var oAutoComp = new YAHOO.widget.AutoComplete('to','pring_mail_container', oACDS);
	oAutoComp.queryDelay = 0;
	oAutoComp.useIFrame = true;
	oAutoComp.animVert = false;
	oAutoComp.delimChar = ',';

	oAutoComp.doBeforeExpandContainer = function(oTextbox, oContainer, sQuery, aResults) {
		var pos = YAHOO.util.Dom.getXY(oTextbox);
		pos[1] += YAHOO.util.Dom.get(oTextbox).offsetHeight;
		YAHOO.util.Dom.setXY(oContainer,pos);

		// set width
		oContainer.style.width = __get_element_width('to') + 'px';

		return true;
	};
}

YAHOO.util.Event.addListener(window, "load", pring_mail_autocomplete);