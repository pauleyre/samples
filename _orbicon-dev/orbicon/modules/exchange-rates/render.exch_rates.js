	function handleSelect(type,args,obj)
	{
		var dates = args[0];
		var date = dates[0];
		var year = date[0], month = date[1], day = date[2];

		update_table(month + '/' + day + '/' + year, true);
		update_calc(month + '/' + day + '/' + year);

		$('current_date_container').innerHTML = day + '.' + month + '.' + year + '.';

		var selMonth = $("selMonth");
		var selDay = $("selDay");
		var selYear = $("selYear");

		selMonth.selectedIndex = month;
		selDay.selectedIndex = day;

		for (var y=0;y<selYear.options.length;y++) {
			if (selYear.options[y].text == year) {
				selYear.selectedIndex = y;
				break;
			}
		}
	}

	function updateCal()
	{
		var selMonth = $("selMonth");
		var selDay = $("selDay");
		var selYear = $("selYear");

		var month = parseInt(selMonth.options[selMonth.selectedIndex].text);
		var day = parseInt(selDay.options[selDay.selectedIndex].value);
		var year = parseInt(selYear.options[selYear.selectedIndex].value);

		if (! isNaN(month) && ! isNaN(day) && ! isNaN(year)) {
			var date = month + "/" + day + "/" + year;

			update_table(date, false);
			update_calc(date);

			orbx_calendar.select(date);
			orbx_calendar.cfg.setProperty("pagedate", month + "/" + year);
			orbx_calendar.render();
		}
	}

	function update_table(date, msg)
	{
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {

				if(!empty(o.responseText)) {
					// * update mini browser container
					$('exch_table_container').innerHTML = o.responseText;
					update_flash(date, 'EUR');
					$('current_valid_date').value = date;
				}
				else {
					if(msg == true) {
						if(__orbicon_ln == 'hr') {
							window.alert('Nema podataka za datum ' + date);
						}
						else {
							window.alert('No data available for date ' + date);
						}
					}
				}
			}
		}

		var callback =
		{
			success:handleSuccess,
			timeout: 15000
		};

		var data = new Array();
		data[0] = 'date=' + date;

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', orbx_site_url + '/orbicon/modules/exchange-rates/xhr.exch_rates.table.php', callback, data);
	}

	function update_calc(date)
	{
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				if(!empty(o.responseText)) {
					// * update mini browser container
					$('calc_container').innerHTML = o.responseText;
				}
			}
		}

		var callback =
		{
			success:handleSuccess,
			timeout: 15000
		};

		var types = $('exch_type');
		var data = new Array();
		data[0] = 'date=' + date;
		data[1] = 'type=' + types.options[types.selectedIndex].value;
		data[2] = 'position=' + __orbicon_get_q;

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', orbx_site_url + '/orbicon/modules/exchange-rates/xhr.exch_rates.calculator.php', callback, data);
	}

	function update_flash(date, rate)
	{
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				if(!empty(o.responseText)) {
					// * update container
					$('exch_flash_container').innerHTML = o.responseText;
				}
			}
		}

		var callback =
		{
			success:handleSuccess,
			timeout: 15000
		};

		var data = new Array();
		data[0] = 'date=' + date;
		data[1] = 'rate=' + rate;

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', orbx_site_url + '/orbicon/modules/exchange-rates/xhr.exch_rates.flash.php', callback, data);
	}

function rpl(invalue){
	return parseFloat(invalue.replace(",","."));
}

function rpl_fancy(invalue){
	return (invalue.replace(/\./g, ''));
}

function FormatNumber(num, decimalNum, bolLeadingZero, bolParens){

	var tmpNum = num;

	tmpNum *= Math.pow(10,decimalNum);

	tmpNum = Math.floor(tmpNum);

	tmpNum /= Math.pow(10,decimalNum);

	var tmpStr = new String(tmpNum);



	if (!bolLeadingZero && (num < 1) && (num > -1) && (num !=0))

	if (num > 0) {
		tmpStr = tmpStr.substring(1,tmpStr.length);
	}

	else {

		tmpStr = "-" + tmpStr.substring(2, tmpStr.length);
	}

	if (bolParens && (num < 0)) {

		tmpStr = "(" + tmpStr.substring(1, tmpStr.length) + ")";
	}

	return tmpStr.replace(".",",");

}

function calc()
{
	var valin = $('valin');
	if(empty(valin.value)) {
		if(__orbicon_ln == 'hr') {
			$('error').innerHTML = 'Unesite početnu vrijednost';
		}
		else {
			$('error').innerHTML = 'Please enter amount';
		}
		valin.focus();
		return false;
	}
	else {
		$('error').innerHTML = '';
	}

	var from = $('CurFrom');
	var to = $('CurTo');
	var result = $('rezultat');

	result.value = formatNumber_exch(FormatNumber([rpl(rpl_fancy(valin.value)) * from.options[from.selectedIndex].value] / to.options[to.selectedIndex].value, 2, false, true));
	return true;
}

function formatNumber_exch(current)
{
	var num = new NumberFormat();
	num.setInputDecimal(',');
	num.setNumber(current);
	num.setPlaces('2', false);
	num.setCurrencyValue('$');
	num.setCurrency(false);
	num.setCurrencyPosition(num.LEFT_OUTSIDE);
	num.setNegativeFormat(num.LEFT_DASH);
	num.setNegativeRed(false);
	num.setSeparators(true, '.', ',');
	return num.toFormatted();
}