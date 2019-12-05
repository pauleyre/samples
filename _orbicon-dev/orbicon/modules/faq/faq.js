function faq_ac()
{
	// create autocomplete container if we haven't got one already
	if(empty($("faq_search_container"))) {
		var body = window.document.getElementsByTagName("BODY").item(0);
		var a = window.document.createElement("DIV");
		a.id = 'faq_search_container';
		body.appendChild(a);
	}

	var url = orbx_site_url + '/orbicon/modules/faq/xhr.ac.php';

	var oACDS = new YAHOO.widget.DS_XHR(url, ["\n", "\t"]);
	oACDS.responseType = YAHOO.widget.DS_XHR.TYPE_FLAT;
	oACDS.maxCacheEntries = 20;

	// Instantiate AutoComplete
	var oAutoComp = new YAHOO.widget.AutoComplete('search_string','faq_search_container', oACDS);
	oAutoComp.queryDelay = 0;
	oAutoComp.animVert = false;
	oAutoComp.minQueryLength = 3;
	oAutoComp.allowBrowserAutocomplete = false;
	oAutoComp.autoHighlight = false;

	oAutoComp.doBeforeExpandContainer = function(oTextbox, oContainer, sQuery, aResults) {
		var pos = YAHOO.util.Dom.getXY(oTextbox);
		pos[1] += YAHOO.util.Dom.get(oTextbox).offsetHeight;
		YAHOO.util.Dom.setXY(oContainer,pos);

		// set width
		oContainer.style.width = __get_element_width('search_string') + 'px';

		return true;
	};

	// This function returns markup that bolds the original query
	oAutoComp.resultTypeList = false;
	oAutoComp.formatResult = function(oResultData, sQuery, sResultMatch) {

		var str = sResultMatch.replace(new RegExp(sQuery, "g"), '<b>' + sQuery + '</b>');
		str = str.replace(new RegExp(sQuery.toUpperCase(), "g"), '<b>' + sQuery.toUpperCase() + '</b>');
		str = str.replace(new RegExp(sQuery.toLowerCase(), "g"), '<b>' + sQuery.toLowerCase() + '</b>');
		str = str.replace(new RegExp(sQuery.ucFirst(), "g"), '<b>' + sQuery.ucFirst() + '</b>');

		//str = (str.length > 100) ? str.substr(0, 100) + '...' : str;

		return str;
	};
}

YAHOO.util.Event.addListener(window, "load", faq_ac);

String.prototype.ucFirst = function () {
    return this.substr(0,1).toUpperCase() + this.substr(1,this.length);
};


function faq_validate()
{
	if(empty($('email').value) && $('email_notify').checked) {
		$('faq_error').innerHTML = 'Molimo navedite e-mail adresu';
		$('email').focus();
		return false;
	}

	if(empty($('title').value)) {
		$('faq_error').innerHTML = 'Molimo navedite upit';
		$('title').focus();
		return false;
	}


	$('faq_error').innerHTML = '&nbps;';
	return true;
}
