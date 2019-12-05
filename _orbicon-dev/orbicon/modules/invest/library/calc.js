function rpl(invalue)
{
	return parseFloat(invalue.replace(',', '.'));
}

function rpl_fancy(invalue)
{
	return (invalue.replace(/\./g, ''));
}

function FormatNumber(num, decimalNum, bolLeadingZero, bolParens)
{
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
		tmpStr = "-" + tmpStr.substring(2,tmpStr.length);
	}

	if (bolParens && (num < 0)) {

		tmpStr = "(" + tmpStr.substring(1,tmpStr.length) + ")";
	}

	return tmpStr.replace(".",",");
}

function credit_calc()
{
	var anuitet = $('anuitet');

	var total = $('total');
	var months = $('months');
	var interest = $('interest');

	if(empty(total.value)) {
		if(__orbicon_ln == 'hr') {
			window.alert('Molimo popunite polje');
		}
		else {
			window.alert('Please enter amount');
		}
		total.focus();
		return false;
	}

	if(empty(months.value)) {
		if(__orbicon_ln == 'hr') {
			window.alert('Molimo popunite polje');
		}
		else {
			window.alert('Please enter amount');
		}
		months.focus();
		return false;
	}

	if(empty(interest.value)) {
		if(__orbicon_ln == 'hr') {
			window.alert('Molimo popunite polje');
		}
		else {
			window.alert('Please enter amount');
		}
		interest.focus();
		return false;
	}

	var h6 = (interest.value / 100) + 1;
	var h7 = Math.pow(h6, (1/12));
	var mnth = rpl(months.value);
	var tot = rpl(rpl_fancy(total.value));

	var calc = (Math.pow(h7, mnth)*h7-1)/(Math.pow(h7,mnth)-1)*tot-tot;
	calc = formatNumber_new(FormatNumber(calc, 2, false, false));

	anuitet.value = calc;
	return true;
}

function change_interest(el)
{
	$('interest').value = rpl(el.options[el.selectedIndex].value);
}

function formatNumber_new(current)
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

function check_max_years()
{
	var credit = $('credit');
	var max_years = rpl(credit.options[credit.selectedIndex].getAttribute('accept'));

	if(max_years > 0) {
		var user_input = $('months');

		if(user_input.value > max_years) {
			user_input.value = max_years;
		}
	}
}