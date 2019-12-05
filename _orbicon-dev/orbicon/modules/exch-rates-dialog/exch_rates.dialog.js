function save_exch_rates_dialog()
{
	h('chooseCurrency');

	var handleSuccess = function(o) {
		if(o.responseText !== undefined) {
			if(!empty(o.responseText)) {
				// * update mini browser container
				$('exch_summary_container').innerHTML = o.responseText;
			}
		}
	}

	var callback =
	{
		success:handleSuccess,
		timeout: 15000
	};

	var str = '';

	if($('unit_AUD').checked) {
		str += 'AUD,';
	}
	if($('unit_CAD').checked) {
		str += 'CAD,';
	}
	if($('unit_CHF').checked) {
		str += 'CHF,';
	}
	if($('unit_CZK').checked) {
		str += 'CZK,';
	}
	if($('unit_DKK').checked) {
		str += 'DKK,';
	}
	if($('unit_EUR').checked) {
		str += 'EUR,';
	}
	if($('unit_GBP').checked) {
		str += 'GBP,';
	}
	if($('unit_HUF').checked) {
		str += 'HUF,';
	}
	if($('unit_JPY').checked) {
		str += 'JPY,';
	}
	if($('unit_NOK').checked) {
		str += 'NOK,';
	}
	if($('unit_PLN').checked) {
		str += 'PLN,';
	}
	if($('unit_SEK').checked) {
		str += 'SEK,';
	}
	/*if($('unit_SKK').checked) {
		str += 'SKK,';
	}*/
	if($('unit_USD').checked) {
		str += 'USD,';
	}

	YAHOO.util.Cookie.set("user_currencies", str);

	YAHOO.util.Connect.asyncRequest('POST', orbx_site_url + '/orbicon/modules/exch-rates-summary/xhr.exch_rates.summary.php', callback);
}

function exch_checkbox_status()
{
	var inputs = $('currencyBox').getElementsByTagName('INPUT');
	var total = 0;

	// * disable all submit inputs
	if(!empty(inputs)) {
		for(i = 0; i < inputs.length; i++) {
			if((inputs[i].type == 'checkbox') && (inputs[i].checked)) {
				total ++;
			}
		}
	}

	for(i = 0; i < inputs.length; i++) {
		if(inputs[i].type == 'checkbox' && !inputs[i].checked) {
			if(total >= 5) {
				inputs[i].disabled = true;
			}
			else if(total < 5) {
				inputs[i].disabled = false;
			}
		}
	}
}


YAHOO.util.Event.addListener(window, 'load', function() {YAHOO.util.Event.addListener(['unit_AUD', 'unit_CAD', 'unit_CZK', 'unit_DKK', 'unit_HUF', 'unit_JPY', 'unit_NOK', /*'unit_SKK',*/ 'unit_SEK', 'unit_CHF', 'unit_GBP', 'unit_USD', 'unit_EUR', 'unit_PLN'], 'click', exch_checkbox_status);});