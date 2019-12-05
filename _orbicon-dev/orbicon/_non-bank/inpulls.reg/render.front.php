<?php

	if(get_is_member() && isset($_POST['orbicon_login'])) {
		redirect(ORBX_SITE_URL);
	}

	if(get_is_member()) {
		return _L('already_logged_in');
	}

	require_once DOC_ROOT . '/orbicon/modules/inpulls/inc.inpulls.php';
	require_once DOC_ROOT . '/orbicon/modules/inpulls.reg/inc.inpulls.reg.php';

	if(isset($_POST['inpulls_reg_submit']) && isset($_POST['orbicon_registration'])) {
		inpulls_reg();
	}

	$url = ORBX_SITE_URL;
	$domain_name = DOMAIN_NAME;
	$lng = $orbicon_x->ptr;

	if($_SESSION['site_settings']['form_feedback_position'] == 'inside') {
		if($_SESSION['orbicon_infobox_msg']) {
			$feedback = '<div class="form_feedback">' . utf8_html_entities($_SESSION['orbicon_infobox_msg']) . '</div>';
			unset($_SESSION['orbicon_infobox_msg']);
		}

		$query = http_build_query($_GET);
		if(is_array($query)) {
			unset($query['submit_form']);
		}

		if($query) {
			$query = '/?' . $query . '&amp;submit_form';
		}
		else {
			$query = '/';
		}

		$submit_url = $url . $query;
	}
	else {
		$submit_url = "$url/?submit_form";
	}

	include_once DOC_ROOT.'/orbicon/modules/forms/class.form.php';
	$form = new Form;
	$doe = $form->get_pring_db_table('pring_doe', false, 'id');
	$counties = $form->get_pring_db_table('pring_counties');
	$countries = $form->get_pring_db_table('pring_countries', false, 'title', 82);

	$terms_of_use = nl2br(file_get_contents(DOC_ROOT . '/site/gfx/terms.txt'));

	return $feedback . '

<h4 id="login_intro">Ukoliko ste se već registrirali, prijavite se. Kliknite <a href="'.$url.'/?'.$lng.'=mod.peoplering&amp;sp=help">ovdje ako ste zaboravili lozinku</a>.</h4>

<form id="login_form" method="post" action="'.$submit_url.'">
<input type="hidden" name="orbicon_login" value="1" />
<fieldset class="fieldset_design"><legend class="legend_design">Prijava</legend>
<p><label for="login_username">Korisničko ime: <span class="red">*</span></label></p>
<p><input type="text" name="login_username" id="login_username"  /></p>
<p><label for="login_password">Lozinka: <span class="red">*</span></label></p>
<p><input type="password" name="login_password" id="login_password"  /></p>
<p><input type="submit" name="submit" id="submit" value="Prijava" /></p>
</fieldset>
</form>

<h4 id="reg_intro">Ukoliko se niste registrirali, pozivamo vas da se <a href="javascript:void(null);" onclick="javascript:sh(\'register_form\'); $(\'username\').focus();" class="reg_invite">registrirate</a> i na taj način u potpunosti iskoristite sve mogućnosti stranice.</h4>

<form class="h" id="register_form" method="post" action="" enctype="multipart/form-data" onsubmit="javascript: return verify_inpulls_reg();">
<input type="hidden" name="orbicon_registration" value="1" />

<p>Polja označena sa <span class="red">*</span> su obavezna.</p>

<a class="big_txt">1. Obavezno ispuniti</a>

<div id="required_fields">

<p><label for="username">Korisničko ime <span class="red">*</span></label></p>
<p><input class="txtfield" type="text" name="username" id="username" onchange="javascript:$(\'imail\').innerHTML = this.value + \'@inpulls.com\'" /></p>

<p class="password"><label for="password">Lozinka <span class="red">*</span></label></p>
<p class="password"><input class="txtfield" type="password" name="password" id="password"  /></p>

<p class="password_v"><label for="password_v">Potvrdite Lozinku <span class="red">*</span></label></p>
<p class="password_v"><input class="txtfield" type="password" name="password_v" id="password_v"  /></p>

<p class="dob"><label for="dob_d">Datum rođenja <span class="red">*</span></label></p>
<p class="dob">
	<select class="small" name="dob_d" id="dob_d"><option value="">&mdash;</option>'.print_select_menu(range(1, 31)).'</select>.
	<select class="small" name="dob_m" id="dob_m"><option value="">&mdash;</option>'.print_select_menu(range(1, 12)).'</select>.
	<select class="small" name="dob_y" id="dob_y"><option value="">&mdash;</option>'.print_select_menu(range(date('Y'), 1901)).'</select>

<p class="sex"><label for="sex">Spol</label> <span class="red">*</span></p>
<p class="sex">
	<input type="radio" name="sex" id="m" value="0" /> <label for="m">Muško</label><br />
	<input type="radio" name="sex" id="f" value="1" /> <label for="f">Žensko</label>
</p>

<p class="mail"><label for="mail">E-mail adresa <span class="red">*</span></label></p>
<p class="mail"><input class="txtfield" type="text" name="mail" id="mail"  /></p>

</div>

<p>
<a href="javascript:void(null);" onclick="javascript:sh(\'optional_fields\');" class="big_txt">2. Opcionalno za ispuniti <span style="font-size:0.8em !important">(klikni ovdje)</span></a>
</p>

<div id="optional_fields" class="h">

<p class="name"><label for="name">Ime</label></p>
<p class="name"><input class="txtfield" type="text" name="name" id="name"  /></p>

<p class="surname"><label for="surname">Prezime</label></p>
<p class="surname"><input class="txtfield" type="text" name="surname" id="surname" /></p>

<p class="avatar"><label for="picture">Vaša slika (do 200px &times; 200px dimenzije)</label></p>
<p class="avatar"><input id="picture" name="picture" type="file" /></p><br/>

</p>

<p class="expertise"><label for="expertise">Zanimanje</label> <span class="red">*</span></p>
<p class="expertise"><input class="txtfield" type="text" name="expertise" id="expertise"  /></p>

<p class="im_here_for"><label for="im_here_for">Ovdje sam jer tražim...</label></p>
<p class="im_here_for">
	<select id="im_here_for" name="im_here_for">
		'.print_select_menu($inpulls_im_here_for, null, true).'
	</select>
</p>

<p class="currently_im"><label for="currently_im">Trenutno sam...</label></p>
<p class="currently_im">
	<select id="currently_im" name="currently_im">
		'.print_select_menu($inpulls_currently_im, null, true).'
	</select>
</p>

<p class="sex_group"><label for="sex_group">Spolno opredjeljenje</label></p>
<p class="sex_group">
	<select id="sex_group" name="sex_group">
		'.print_select_menu($inpulls_sex_group, null, true).'
	</select>
</p>

<p class="more_info"><label for="more_info">Nešto o meni što nećete saznati iz ovih glupih pitanja</label></p>
<p class="more_info"><textarea name="more_info" id="more_info"></textarea></p>

<p class="doe"><label for="doe">Škola</label></p>
<p class="doe">
	<select id="doe" name="doe">
		'.$doe.'
	</select>
</p>

<p class="country"><label for="country">Država</label></p>
<p class="country"><select onchange="javascript: switch_regional_input(this.options[this.selectedIndex].value);" name="country" id="country">'.$countries.'</select></p>

<span id="cro_only">
<p class="county"><label for="county">Regija</label></p>
<p class="county"><select onchange="javascript: switch_towns(this.options[this.selectedIndex].value, \'HR\', \'reg_city_container\', \'city\', \'city\');" name="county" id="county"><option value="">Odaberi regiju</option>'.$counties.'</select></p>

<p class="city"><label for="city">Grad</label></p>
<p class="city"><span id="reg_city_container"><select name="city" id="city"></select></span></p>
</span>

<span id="other_only" class="h">
<p class="city_text"><label for="city_text">Grad</label></p>
<p class="city_text"><input class="txtfield" type="text" name="city_text" id="city_text" /></p>
</span>

<p class="address"><label for="address">Adresa</label></p>
<p class="address"><input class="txtfield" type="text" name="address" id="address"  /></p>

<p class="url"><label for="url">Web stranica</label></p>
<p class="url"><input class="txtfield" type="text" name="url" id="url"  /></p>

<p class="horoscope"><label for="horoscope">Horoskop</label></p>
<p class="horoscope">
	<select id="horoscope" name="horoscope">
		'.print_select_menu($inpulls_horoscope, null, true).'
	</select>
</p>

<p class="inpulls_mail"><input type="checkbox" value="1" name="inpulls_mail" id="inpulls_mail"  /> <label for="inpulls_mail">Želim e-mail <strong id="imail">@inpulls.com</strong></label></p>

</fieldset>
<br />
</div>
<p class="style_displayed_alerts">Pružanjem vaših informacija i registracijom, slažete se sa <a href="javascript:void(null)" onclick="javascript: sh(\'terms_of_use\');">Uvjetima Korištenja</a> inpulls.com-a</p>
<div class="h" id="terms_of_use">'.$terms_of_use.'</div>
<p>&nbsp;</p>
<br />
<p><input type="submit" name="inpulls_reg_submit" id="inpulls_reg_submit" value="Pošalji" /></p>
</form>';

?>