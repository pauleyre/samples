/**
 * javascript functions of savings calculator module
 * @author Dario Benšić <dario.bensic@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @version 2.04
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-06-27
 */

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




function change_interest(el)
{
	var interest = $('interest');
	var val = rpl(el.options[el.selectedIndex].value);
	interest.value = val;
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

function formatNumber_interest_rate(current)
{
	var num = new NumberFormat();
	num.setInputDecimal('.');
	num.setNumber(current);
	num.setPlaces('2', false);
	num.setCurrencyValue('$');
	num.setCurrency(false);
	num.setCurrencyPosition(num.LEFT_OUTSIDE);
	num.setNegativeFormat(num.LEFT_DASH);
	num.setNegativeRed(false);
	num.setSeparators(true, ',', '.');
	return num.toFormatted();
}

function check_max_years()
{
	var credit = $('credit');
	var max_years = rpl(credit.options[credit.selectedIndex].getAttribute('label'));

	if(max_years > 0) {
		var user_input = $('months');

		if(user_input.value > max_years) {
			user_input.value = max_years;
		}
	}
}

        function change_period(vrsta_stednje,rok_orocenja,kamatna_stopa_lista1) {

            var v_stednje = vrsta_stednje.options[vrsta_stednje.selectedIndex];
            var rok = rok_orocenja.options[rok_orocenja.selectedIndex];
            var k_stop = kamatna_stopa_lista1.options[kamatna_stopa_lista1.selectedIndex];

            if (v_stednje.value == 1) {
                    if (rok.value == 1 && k_stop.value == 1){
                        var ispis_k_stop = kamatna_stopa_lista1.options[kamatna_stopa_lista1.selectedIndex].innerHTML;
                            /*alert(rok.innerHTML);
                            alert(ispis_k_stop);
                            alert(v_stednje.innerHTML);*/
                            alert(ispis_k_stop);
                    }
                    if (rok.value == 3 && k_stop.value == 3){
                        var ispis_k_stop2 = kamatna_stopa_lista1.options[kamatna_stopa_lista1.selectedIndex].innerHTML;
                            alert(rok.innerHTML);
                            alert(ispis_k_stop2);
                            alert(v_stednje.innerHTML);
                            alert(ispis_k_stop2);
                    }
                    if (rok.value == 6 && k_stop.value == 6){
                        var ispis_k_stop = kamatna_stopa_lista1.options[kamatna_stopa_lista1.selectedIndex].innerHTML;
                            alert(ispis_k_stop);
                    }
                    if (rok.value == 12 && k_stop.value == 12){
                        var ispis_k_stop = kamatna_stopa_lista1.options[kamatna_stopa_lista1.selectedIndex].innerHTML;
                            alert(ispis_k_stop);
                    }
                    if (rok.value == 24 && k_stop.value == 24){
                        var ispis_k_stop = kamatna_stopa_lista1.options[kamatna_stopa_lista1.selectedIndex].innerHTML;
                            alert(ispis_k_stop);
                    }
                    if (rok.value == 36 && k_stop.value == 36){
                        var ispis_k_stop = kamatna_stopa_lista1.options[kamatna_stopa_lista1.selectedIndex].innerHTML;
                            alert(ispis_k_stop);
                    }
            }



        }

	    function show_sav_calc()
	    {
			$('sav_calc').style.display = 'block';
			YAHOO.savcalc.container.sav_calc.show();
		}

		YAHOO.namespace("savcalc.container");


		    function init_savcalc() {

			// Define various event handlers for Dialog
			var handleSubmit = function() {
				makeRequest();
			};
			var handleCancel = function() {
				this.cancel();
			};
			var handleSuccess = function(o) {
			};
			var handleFailure = function(o) {
				window.alert("Failure: " + o.status);
			};

			var ln_calculate;
			var ln_cancel;
			switch(__orbicon_ln) {
				case 'hr':
					ln_calculate = 'Izračunaj';
					ln_cancel = 'Zatvori';
				break;
				case 'en':
					ln_calculate = 'Calculate';
					ln_cancel = 'Cancel';
				break;
			}

			// Instantiate the Dialog

			YAHOO.savcalc.container.sav_calc = new YAHOO.widget.Dialog("sav_calc",
																		{
						                                                  zIndex : 101,
																		  fixedcenter : true,
																		  visible : false,
																		  constraintoviewport : true,
																		  buttons : [ { text:ln_calculate, handler:handleSubmit },
																					  { text:ln_cancel, handler:handleCancel }
																					  ]
																		 } );

			// Validate the entries in the form to require that both first and last name are entered

			YAHOO.savcalc.container.sav_calc.validate = function() {
				var data = this.getData();
				if (data.name == "") {
					return false;
				} else {
					return true;
				}
			};

			// Wire up the success and failure handlers

			YAHOO.savcalc.container.sav_calc.callback = { success: handleSuccess,
														 failure: handleFailure };

			// Render the Dialog

			YAHOO.savcalc.container.sav_calc.render();
		}



		YAHOO.util.Event.addListener(window, "load", init_savcalc);




function makeRequest()
{
	var interest_list = $('vrsta_stednje');
	var interest_rate = rpl(interest_list.options[interest_list.selectedIndex].getAttribute('value'));
	var invest_formated = $('orocenje').value;
	var invest = rpl(rpl_fancy(invest_formated));
	var starting_month_of_invest = $('pocetni_mjesec_orocenja');
	var period_of_invest = $('rok_orocenja');
	var period_of_invest2 = $('rok_orocenja2');

	var godina = 36500;
    var d = new Date();

    var god = d.getFullYear();
    if ((god == 2008) || (god == 2012) || (god == 2016) || (god == 2020) || (god == 2024) || (god == 2028)) {
            var godina = 36600;
    }

        	//var day = 365;
            if(starting_month_of_invest.value == 1) {
                var starting_day = 30;
                //alert("Mjesec: "+starting_day);
            }
            if(starting_month_of_invest.value == 2) {
                var starting_day = 28;
                //alert("Mjesec: "+starting_day);
            }
            if(starting_month_of_invest.value == 3) {
                var starting_day = 31;
                //alert("Mjesec: "+starting_day);
            }
            if(starting_month_of_invest.value == 4) {
                var starting_day = 30;
                //alert("Mjesec: "+starting_day);
            }
            if(starting_month_of_invest.value == 5) {
                var starting_day = 31;
                //alert("Mjesec: "+starting_day);
            }
            if(starting_month_of_invest.value == 6) {
                var starting_day = 30;
                //alert("Mjesec: "+starting_day);
            }
            if(starting_month_of_invest.value == 7) {
                var starting_day = 31;
                //alert("Mjesec: "+starting_day);
            }
            if(starting_month_of_invest.value == 8) {
                var starting_day = 31;
                //alert("Mjesec: "+starting_day);
            }
            if(starting_month_of_invest.value == 9) {
                var starting_day = 30;
                //alert("Mjesec: "+starting_day);
            }
            if(starting_month_of_invest.value == 10) {
                var starting_day = 31;
                //alert("Mjesec: "+starting_day);
            }
            if(starting_month_of_invest.value == 11) {
                var starting_day = 30;
                //alert("Mjesec: "+starting_day);
            }
            if(starting_month_of_invest.value == 12) {
                var starting_day = 31;
                //alert("Mjesec: "+starting_day);
            }


        if (godina == 36600) {

            if(starting_month_of_invest.value == 2) {
                var starting_day = 29;
                //alert("Mjesec: "+starting_day);
            }

        }

    	if(period_of_invest.value==1){
    	    var day = starting_day;
    	}

	// this block of code is needed for calculating right number of days for the period of invest for 3 months,
	// depending on which month is first (example; period can start at 7th month, so number must be 31(7th month)+31(8th month)+30(9th month)
	if(period_of_invest.value==3){
	    var s_month = starting_month_of_invest.value;

        	    if(s_month == 1) {
        	       var s_day_1 = 30;
        	       var s_day_2 = 28;
        	       var s_day_3 = 31;
        	       //alert("S_month:"+s_day_1+" "+s_day_2+" "+s_day_3);
        	       var day = s_day_1 + s_day_2 + s_day_3;
        	    }
        	    if(s_month == 2) {
        	       var s_day_1 = 28;
        	       var s_day_2 = 31;
        	       var s_day_3 = 30;
        	       //alert("S_month:"+s_day_1+" "+s_day_2+" "+s_day_3);
        	       var day = s_day_1 + s_day_2 + s_day_3;
        	    }
        	    if(s_month == 3) {
        	       var s_day_1 = 31;
        	       var s_day_2 = 30;
        	       var s_day_3 = 31;
        	       //alert("S_month:"+s_day_1+" "+s_day_2+" "+s_day_3);
        	       var day = s_day_1 + s_day_2 + s_day_3;
        	    }
        	    if(s_month == 4) {
        	       var s_day_1 = 30;
        	       var s_day_2 = 31;
        	       var s_day_3 = 30;
        	       //alert("S_month:"+s_day_1+" "+s_day_2+" "+s_day_3);
        	       var day = s_day_1 + s_day_2 + s_day_3;
        	    }
        	    if(s_month == 5) {
        	       var s_day_1 = 31;
        	       var s_day_2 = 30;
        	       var s_day_3 = 31;
        	       //alert("S_month:"+s_day_1+" "+s_day_2+" "+s_day_3);
        	       var day = s_day_1 + s_day_2 + s_day_3;
        	    }
        	    if(s_month == 6) {
        	       var s_day_1 = 30;
        	       var s_day_2 = 31;
        	       var s_day_3 = 31;
        	       //alert("S_month:"+s_day_1+" "+s_day_2+" "+s_day_3);
        	       var day = s_day_1 + s_day_2 + s_day_3;
        	    }
        	    if(s_month == 7) {
        	       var s_day_1 = 31;
        	       var s_day_2 = 31;
        	       var s_day_3 = 30;
        	       //alert("S_month:"+s_day_1+" "+s_day_2+" "+s_day_3);
        	       var day = s_day_1 + s_day_2 + s_day_3;
        	    }
        	    if(s_month == 8) {
        	       var s_day_1 = 31;
        	       var s_day_2 = 30;
        	       var s_day_3 = 31;
        	       //alert("S_month:"+s_day_1+" "+s_day_2+" "+s_day_3);
        	       var day = s_day_1 + s_day_2 + s_day_3;
        	    }
        	    if(s_month == 9) {
        	       var s_day_1 = 30;
        	       var s_day_2 = 31;
        	       var s_day_3 = 30;
        	       //alert("S_month:"+s_day_1+" "+s_day_2+" "+s_day_3);
        	       var day = s_day_1 + s_day_2 + s_day_3;
        	    }
        	    if(s_month == 10) {
        	       var s_day_1 = 31;
        	       var s_day_2 = 30;
        	       var s_day_3 = 31;
        	       //alert("S_month:"+s_day_1+" "+s_day_2+" "+s_day_3);
        	       var day = s_day_1 + s_day_2 + s_day_3;
        	    }
        	    if(s_month == 11) {
        	       var s_day_1 = 30;
        	       var s_day_2 = 31;
        	       var s_day_3 = 30;
        	       //alert("S_month:"+s_day_1+" "+s_day_2+" "+s_day_3);
        	       var day = s_day_1 + s_day_2 + s_day_3;
        	    }
        	    if(s_month == 12) {
        	       var s_day_1 = 31;
        	       var s_day_2 = 30;
        	       var s_day_3 = 28;
        	       //alert("S_month:"+s_day_1+" "+s_day_2+" "+s_day_3);
        	       var day = s_day_1 + s_day_2 + s_day_3;
        	    }


        	    if (godina == 36600) {

                	    if(s_month == 1) {
                	       var s_day_1 = 30;
                	       var s_day_2 = 29;
                	       var s_day_3 = 31;
                	       //alert("S_month:"+s_day_1+" "+s_day_2+" "+s_day_3);
                	       var day = s_day_1 + s_day_2 + s_day_3;
                	    }
                	    if(s_month == 2) {
                	       var s_day_1 = 29;
                	       var s_day_2 = 31;
                	       var s_day_3 = 30;
                	       //alert("S_month:"+s_day_1+" "+s_day_2+" "+s_day_3);
                	       var day = s_day_1 + s_day_2 + s_day_3;
                	    }
                	    if(s_month == 12) {
                	       var s_day_1 = 31;
                	       var s_day_2 = 30;
                	       var s_day_3 = 29;
                	       //alert("S_month:"+s_day_1+" "+s_day_2+" "+s_day_3);
                	       var day = s_day_1 + s_day_2 + s_day_3;
                	    }


                }


	}

        	// this block of code is needed for calculating right number of days for the period of invest for 6 months,
        	// depending on which month is first
        	if(period_of_invest.value==6){
        	    var s_month = starting_month_of_invest.value;

                	 if(s_month == 1) {
                        var day = 180;
                	 }
                	 if(s_month == 2) {
                        var day = 181;
                	 }
                	 if(s_month == 3) {
                        var day = 184;
                	 }
                	 if(s_month == 4) {
                        var day = 183;
                	 }
                	 if(s_month == 5) {
                        var day = 184;
                	 }
                	 if(s_month == 6) {
                        var day = 183;
                	 }
                	 if(s_month == 7) {
                        var day = 184;
                	 }
                	 if(s_month == 8) {
                        var day = 183;
                	 }
                	 if(s_month == 9) {
                        var day = 180;
                	 }
                	 if(s_month == 10) {
                        var day = 181;
                	 }
                	 if(s_month == 11) {
                        var day = 180;
                	 }
                	 if(s_month == 12) {
                        var day = 181;
                	 }

                    if (godina == 36600) {

                         if(s_month == 1) {
                            var day = 181;
                	     }
                	      if(s_month == 2) {
                            var day = 182;
                	     }
                    	 if(s_month == 9) {
                            var day = 181;
                    	 }
                    	 if(s_month == 10) {
                            var day = 182;
                    	 }
                    	 if(s_month == 11) {
                            var day = 181;
                    	 }
                    	 if(s_month == 12) {
                            var day = 182;
                    	 }

                    }


        	}


        	if(period_of_invest.value==12){
        	    var day = 365;
        	}
        	if(period_of_invest.value==24){
        	    var day = 730;
        	}
        	if(period_of_invest.value==36){
        	    var day = 1095;
        	}


            if (godina == 36600) {
            	if(period_of_invest.value==12){
            	    var day = 366;
            	}
            	if(period_of_invest.value==24){
            	    var day = 731;
            	}
            	if(period_of_invest.value==36){
            	    var day = 1096;
            	}
            }


            if(period_of_invest2.value==12){
        	    var day = 365;
        	}
        	if(period_of_invest2.value==24){
        	    var day = 730;
        	}
        	if(period_of_invest2.value==36){
        	    var day = 1095;
        	}


            if (godina == 36600) {
            	if(period_of_invest2.value==12){
            	    var day = 366;
            	}
            	if(period_of_invest2.value==24){
            	    var day = 731;
            	}
            	if(period_of_invest2.value==36){
            	    var day = 1096;
            	}
            }

	var data = new Array();
	data[0] = 'saving=';
	data[1] = 'interest=';
	data[2] = 'type_of_savings=' + $('vrsta_stednje').value;
	data[3] = 'invest=' + invest;
	data[4] = 'national_or_foregin=' + $('kuna_ili_deviza').value;
	data[5] = 'currency=' + $('valuta').value;
	data[6] = 'currency_condition=' + $('valutna_klauzula').value;
	data[7] = 'period_of_invest=' + $('rok_orocenja').value;
	data[8] = 'starting_month_of_invest=' + $('pocetni_mjesec_orocenja').value;
	data[9] = 'vrsta_stednje=' + interest_rate;
	data[10] ='year=' + godina;
	data[11] ='day=' + day;
	data[12] = 'kamatna_stopa=' + $('kamatna_stopa_proizvoljno2').value;
	data[13] = 'period_of_invest_new=' + $('rok_orocenja2').value;
	data[14] = 'type2=' + $('vrsta_kamatne_stope').options[$('vrsta_kamatne_stope').selectedIndex].value;

	data = data.join('&');

	 var div = $('container_ajax_results');

    var handleSuccess = function(o){
    	if(o.responseText !== undefined){
    		div.innerHTML = o.responseText;
    	}
    };

    var callback =
    {
      success:handleSuccess
    };

    var url = orbx_site_url + "/orbicon/modules/savings_calculator/post.php";
	YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
}

        function get_interest_rate(rok_orocenja) {
            var vrsta_stednje = $('vrsta_stednje');
            var rok_orocenja = $('rok_orocenja');
            var kuna_ili_deviza = $('kuna_ili_deviza');
            var valuta = $('valuta');
            var valutna_klauzula = $('valutna_klauzula');
            var rok_orocenja2 = $('rok_orocenja2');

             var selected_vrsta_stednje = vrsta_stednje.value;
             var selected_rok_orocenja = rok_orocenja.value;
             var selected_kuna_ili_deviza = kuna_ili_deviza.value;
             var selected_valuta = valuta.value;
             var selected_valutna_klauzula = valutna_klauzula.value;
             var selected_rok_orocenja2 = rok_orocenja2.value;
             var selected_type2 = $('vrsta_kamatne_stope').options[$('vrsta_kamatne_stope').selectedIndex].value;


			if((selected_rok_orocenja > 12) && (selected_type2 == 'fiksna') && ((selected_vrsta_stednje == 1) || (selected_vrsta_stednje == 6))) {
				$('savings_error').innerHTML = 'Molimo Vas odaberite ispravne stavke za ovaj tip štednje';
				return;
			}
			else {
				$('savings_error').innerHTML = '';
			}

			if((selected_rok_orocenja > 12) && (selected_type2 == 'fiksna') && ((selected_vrsta_stednje == 4) || (selected_vrsta_stednje == 7)) && (selected_valuta == 'EUR')) {
				$('savings_error').innerHTML = 'Molimo Vas odaberite ispravne stavke za ovaj tip štednje';
				return;
			}
			else {
				$('savings_error').innerHTML = '';
			}


			if(((selected_vrsta_stednje == 4) || (selected_vrsta_stednje == 7)) && ($('valuta').options[$('valuta').selectedIndex].value != 'EUR')) {
                 	$('vrsta_kamatne_stope').options[1].selected = true;
                 	$('vrsta_kamatne_stope').disabled = true;
             }
             else if(selected_vrsta_stednje == 2) {
             	$('vrsta_kamatne_stope').options[0].selected = true;
                $('vrsta_kamatne_stope').disabled = true;
             }
             else {
             	//$('vrsta_kamatne_stope').options[0].selected = true;
             	$('vrsta_kamatne_stope').disabled = false;
             }

                    var data = new Array();
                	data[0] = 'type_of_savings=' + selected_vrsta_stednje;
                	data[1] = 'national_or_foregin=' + selected_kuna_ili_deviza;
                	data[2] = 'currency=' + selected_valuta;
                	data[3] = 'currency_condition=' + selected_valutna_klauzula;
                	data[4] = 'period_of_invest=' + selected_rok_orocenja;
                	data[5] = 'period_of_invest_new=' + selected_rok_orocenja2;
                	data[6] = 'type2=' + selected_type2;

                	data = data.join('&');

                	var div = $('kamatna_stopa_proizvoljno2');

                    var handleSuccess = function(o){
                    	if(o.responseText !== undefined){
                    		div.value = o.responseText;
                    	}
                    };


                    var callback =
                    {
                      success:handleSuccess
                    };

                    var url = orbx_site_url + "/orbicon/modules/savings_calculator/post_onchange.php";
                	YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
        }


        var vrsta_stednje = $('vrsta_stednje');
        function choose(vrsta_stednje)
        {
             var kuna_ili_deviza = $('vrsta_stednje');
             var form_options = document.form_ajax.vrsta_stednje.options[document.form_ajax.vrsta_stednje.selectedIndex];
             $('vrsta_kamatne_stope').disabled = false;

			 if (form_options.value==''){
                 $('kuna_ili_deviza').value = 1;
                 $('kuna_ili_deviza').disabled = true;
                 $('valuta').value = '';
                 $('valuta').disabled = true;
                 $('valutna_klauzula').value = 0;
                 $('valutna_klauzula').disabled = true;

                 $('kamatna_stopa_proizvoljno2').disabled = false;
                 $('kamatna_stopa_proizvoljno2').value = '';

                 $('rok_orocenja').value = 1;
                 $('rok_orocenja').style.display = 'block';
                 $('rok_orocenja2').style.display = 'none';
                 $('rok_orocenja2').value = 0;
                 $('pocetni_mjesec_orocenja').value = 1;
                 $('pocetni_mjesec_orocenja').disabled = false;
            }

             if (form_options.value==1 || form_options.value==8){
                 $('kuna_ili_deviza').value = 1;
                 $('kuna_ili_deviza').disabled = true;
                 $('valuta').value = '';
                 $('valuta').disabled = true;
                 $('valutna_klauzula').value = 0;
                 $('valutna_klauzula').disabled = true;

                 $('kamatna_stopa_proizvoljno2').disabled = true;
                 $('kamatna_stopa_proizvoljno2').value = '';

                 $('rok_orocenja').value = 1;
                 $('rok_orocenja').style.display = 'block';
                 $('rok_orocenja2').style.display = 'none';
                 $('rok_orocenja2').value = 0;
                 $('pocetni_mjesec_orocenja').value = 1;
                 $('pocetni_mjesec_orocenja').disabled = false;
            }
            if (form_options.value==2 || form_options.value==9){

            	if(form_options.value == 2) {
            		$('vrsta_kamatne_stope').disabled = true;
            	}

                 $('kuna_ili_deviza').value = 1;
                 $('kuna_ili_deviza').disabled = true;
                 $('valuta').value = '';
                 $('valuta').disabled = true;
                 $('valutna_klauzula').value = 0;
                 $('valutna_klauzula').disabled = true;

                 $('kamatna_stopa_proizvoljno2').disabled = true;
                 $('kamatna_stopa_proizvoljno2').value = '';

                 $('rok_orocenja').value = 1;
                 $('rok_orocenja').style.display = 'none';
                 $('rok_orocenja2').style.display = 'block';
                 $('rok_orocenja2').value = 12;
                 $('pocetni_mjesec_orocenja').value = 1;
                 $('pocetni_mjesec_orocenja').disabled = true;
            }
            if (form_options.value==3 || form_options.value==10){
                 $('kuna_ili_deviza').value = 1;
                 $('kuna_ili_deviza').disabled = true;
                 $('valuta').disabled = true;
                 $('valuta').value = '';
                 $('valutna_klauzula').value = 1;
                 $('valutna_klauzula').disabled = false;

                 $('kamatna_stopa_proizvoljno2').disabled = true;
                 $('kamatna_stopa_proizvoljno2').value = '';

                 $('rok_orocenja').value = 1;
                 $('rok_orocenja').style.display = 'block';
                 $('rok_orocenja2').style.display = 'none';
                 $('rok_orocenja2').value = 0;
                 $('pocetni_mjesec_orocenja').value = 1;
                 $('pocetni_mjesec_orocenja').disabled = false;
            }
            if (form_options.value==4 || form_options.value==11){
                 $('kuna_ili_deviza').value = 2;
                 $('kuna_ili_deviza').disabled = true;
                 $('valuta').value = 'EUR';
                 $('valuta').disabled = false;
                 $('valutna_klauzula').value = 1;
                 $('valutna_klauzula').disabled = false;

                 $('kamatna_stopa_proizvoljno2').disabled = true;
                 $('kamatna_stopa_proizvoljno2').value = '';

                 $('rok_orocenja').value = 1;
                 $('rok_orocenja').style.display = 'block';
                 $('rok_orocenja2').style.display = 'none';
                 $('rok_orocenja2').value = 0;
                 $('pocetni_mjesec_orocenja').value = 1;
                 $('pocetni_mjesec_orocenja').disabled = false;

                 if((form_options.value == 4) && ($('valuta').options[$('valuta').selectedIndex].value != 'EUR')) {
                 	$('vrsta_kamatne_stope').options[1].selected = true;
                 	$('vrsta_kamatne_stope').disabled = true;
                 }
                 else {
                 	$('vrsta_kamatne_stope').options[0].selected = true;
                 	$('vrsta_kamatne_stope').disabled = false;
                 }

            }
            if (form_options.value==5 || form_options.value==12){
                 $('kuna_ili_deviza').value = 2;
                 $('kuna_ili_deviza').disabled = true;
                 $('valuta').value = 'EUR';
                 $('valuta').disabled = false;

                 if(form_options.value!=5) {
	                 $('valutna_klauzula').value = 1;
    	             $('valutna_klauzula').disabled = false;
    	         }
    	         else {
    	            $('valutna_klauzula').value = 0;
                 	$('valutna_klauzula').disabled = true;
    	         }

                 $('kamatna_stopa_proizvoljno2').disabled = true;
                 $('kamatna_stopa_proizvoljno2').value = '';

                 $('rok_orocenja').value = 1;
                 $('rok_orocenja').style.display = 'none';
                 $('rok_orocenja2').style.display = 'block';
                 $('rok_orocenja2').value = 12;
                 $('pocetni_mjesec_orocenja').value = 1;
                 $('pocetni_mjesec_orocenja').disabled = true;
            }


            if (form_options.value==6 || form_options.value==13){
                 $('kuna_ili_deviza').value = 1;
                 $('kuna_ili_deviza').disabled = true;
                 $('valuta').value = '';
                 $('valuta').disabled = true;
                 $('valutna_klauzula').value = 0;
                 $('valutna_klauzula').disabled = true;

                 $('kamatna_stopa_proizvoljno2').disabled = true;
                 $('kamatna_stopa_proizvoljno2').value = '';

                 $('rok_orocenja').value = 1;
                 $('rok_orocenja').style.display = 'none';
                 $('rok_orocenja2').style.display = 'block';
                 $('rok_orocenja2').value = 12;
                 $('pocetni_mjesec_orocenja').value = 1;
                 $('pocetni_mjesec_orocenja').disabled = true;

            }
            if (form_options.value==7 || form_options.value==14){
                 $('kuna_ili_deviza').value = 2;
                 $('kuna_ili_deviza').disabled = true;
                 $('valuta').value = 'EUR';
                 $('valuta').disabled = false;
                 $('valutna_klauzula').value = 1;
                 $('valutna_klauzula').disabled = false;

                 $('kamatna_stopa_proizvoljno2').disabled = true;
                 $('kamatna_stopa_proizvoljno2').value = '';

                 $('rok_orocenja').value = 1;
                 $('rok_orocenja').style.display = 'none';
                 $('rok_orocenja2').style.display = 'block';
                 $('rok_orocenja2').value = 12;
                 $('pocetni_mjesec_orocenja').value = 1;
                 $('pocetni_mjesec_orocenja').disabled = true;


                 if((form_options.value == 7) && ($('valuta').options[$('valuta').selectedIndex].value != 'EUR')) {
                 	$('vrsta_kamatne_stope').options[1].selected = true;
                 	$('vrsta_kamatne_stope').disabled = true;
                 }
                 else {
                 	$('vrsta_kamatne_stope').options[0].selected = true;
                 	$('vrsta_kamatne_stope').disabled = false;
                 }

            }
                var rok_orocenja = $('rok_orocenja');
                         get_interest_rate(rok_orocenja);


        }


    function on_opening_form()
    {
		var vrsta_stednje = $('vrsta_stednje');
		var form_options = document.form_ajax.vrsta_stednje.options[document.form_ajax.vrsta_stednje.selectedIndex];

		form_options.value=='';
		$('kuna_ili_deviza').value = 1;
		$('kuna_ili_deviza').disabled = true;
		$('valuta').value = '';
		$('valuta').disabled = true;
		$('valutna_klauzula').value = 0;
		$('valutna_klauzula').disabled = true;

		$('kamatna_stopa_proizvoljno2').disabled = false;

		$('rok_orocenja').value = 1;
		$('rok_orocenja').style.display = 'block';
		$('rok_orocenja2').style.display = 'none';
		$('rok_orocenja2').value = 0;
		$('pocetni_mjesec_orocenja').disabled = false;
		$('pocetni_mjesec_orocenja').value = 1;
    }