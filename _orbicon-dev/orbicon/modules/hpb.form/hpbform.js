
	function render_confirmation_tab(add_remove, max)
	{
		if(add_remove) {
			YAHOO.util.Get.css(orbx_site_url + '/orbicon/modules/hpb.form/gfx/hpbform_print.css');
		}
		else {
			YAHOO.util.Get.purge();
		}

/*		var text_inputs = $('tab'+tab_num).getElementsByTagName('INPUT');

		// * disable all submit inputs
		if(!empty(text_inputs)) {
			for(i = 0; i < text_inputs.length; i++) {
				if(text_inputs[i].type == 'text' && !text_inputs[i].disabled) {
					text_inputs[i].disabled = true;
				}
			}
		}
*/

	}

	function toggle_form_step(max, tab_num, label_num)
	{
		var i = 1;
		for(i = 1; i <= max; i ++) {
			var selectedlayer = $('tab'+tab_num);
			var buttons = $('buttons_'+i)

			if(i == label_num) {
				YAHOO.util.Dom.addClass('tab'+i+'_label', 'active');
			}
			else {
				YAHOO.util.Dom.removeClass('tab'+i+'_label', 'active');
			}

			if(i == tab_num) {
				selectedlayer.style.display = 'block';
				buttons.style.display = 'block';
			}
			else {
				selectedlayer.style.display = 'none';
				buttons.style.display = 'none';
			}
		}
	}

	function display_tt()
	{
		if(!disable_tt) {
			tt_txt(this.getAttribute('tt'));
		}
	}

	function hide_tt()
	{
		if(!disable_tt) {
			tt_txt('');
		}
	}

	function append_tt()
	{
		var text_inputs = $('regEntry').getElementsByTagName('INPUT');

		// * disable all submit inputs
		if(!empty(text_inputs)) {
			for(i = 0; i < text_inputs.length; i++) {
				if(text_inputs[i].type == 'text' && !empty(text_inputs[i].getAttribute('tt'))) {
					YAHOO.util.Event.addListener(text_inputs[i], "focus", display_tt);
					YAHOO.util.Event.addListener(text_inputs[i], "blur", hide_tt);
				}
			}
		}
	}

	function tt_txt(string)
	{
		$('floatingBox').innerHTML = '<p>' + string + '</p>';
	}

	function fillform()
	{
		try {
		var name = new Array("ime", "ime_prezime", 'ime_osn', 'ko_ime_prezime', 'ime_kor', 'ime_prezime_ovlastene_osobe');
		var surname = new Array("prezime", "ime_prezime", 'prezime_osn', 'ko_ime_prezime', 'prezime_kor', 'ime_prezime_ovlastene_osobe');
		var email = new Array("mail", "email", 'ko_email', 'kontakt_email');
		var tel = new Array("tel", "telefon", 'tel2', 'ko_tel', 'kontakt_tel');
		var mob = new Array("mob", "mobitel", 'ko_mob');
		var address = new Array("ulica", "adresa", "ulica_ko", 'adresa_korespondencija', 'dopisna_adresa', 'ko_ulica', 'ulica_stanovanja', 'kontakt_ulica', 'address2');
		var city = new Array("grad", "mjesto", 'mjesto_ko', 'mjesto_korespondencija', 'mjesto_dopisnog', 'mjesto2', 'ko_mjesto', 'mjesto_stanovanja', 'kontakt_mjesto', 'city2');
		var zip = new Array("zip", 'zip_ko', 'zip_korespondencija', 'zip2', 'ko_zip', 'zip_stanovanja', 'kontakt_zip');

		var i = 0;

		for(i = 0; i < name.length; i++) {
			if(!empty($(name[i]))) {
				$(name[i]).value = _user_name;
			}
		}

		i = 0;
		for(i = 0; i < surname.length; i++) {
			if(!empty($(surname[i]))) {
				if(!empty($(surname[i]).value)) {
					if($(surname[i]).value != _user_surname)
						$(surname[i]).value += ' ' + _user_surname;
				}
				else {
					$(surname[i]).value = _user_surname;
				}
			}
		}

		i = 0;
		for(i = 0; i < email.length; i++) {
			if(!empty($(email[i])))
				$(email[i]).value = _user_email;
		}

		i = 0;
		for(i = 0; i < tel.length; i++) {
			if(!empty($(tel[i])))
				$(tel[i]).value = _user_tel;
		}

		i = 0;
		for(i = 0; i < mob.length; i++) {
			if(!empty($(mob[i])))
				$(mob[i]).value = _user_mob;
		}

		i = 0;
		for(i = 0; i < address.length; i++) {
			if(!empty($(address[i])))
				$(address[i]).value = _user_address;
		}

		i = 0;
		for(i = 0; i < city.length; i++) {
			if(!empty($(city[i])))
				$(city[i]).value = _user_city;
		}

		i = 0;
		for(i = 0; i < zip.length; i++) {
			if(!empty($(zip[i])))
				$(zip[i]).value = _user_zip;
		}

		}
		catch(e){}

	}

	YAHOO.util.Event.addListener(window, "load", fillform);