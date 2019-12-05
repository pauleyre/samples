<?php

	if(get_is_member()) {
		return _L('already_logged_in');
	}

	$url = ORBX_SITE_URL;
	$domain_name = DOMAIN_NAME;
	$lng = $orbicon_x->ptr;

	if(function_exists('imagecopyresampled') && $_SESSION['site_settings']['use_captcha']) {
		$captcha_txt = _L('captcha_nfo');
		$captcha = <<<CAPTCHA
<fieldset class="fieldset_design">
	<legend class="legend_design"><label for="contact_squestion"><span class="red">*</span> Zaštitno pitanje</label></legend>
	<label for="contact_squestion">
		<div style="overflow:auto">
			<img src="{$url}/orbicon/controler/get_captcha_image.php" alt="{$captcha_txt}" title="{$captcha_txt}" />
		</div>
	</label><br />
	<input type="text" id="contact_squestion" name="contact_squestion" />
</fieldset>
CAPTCHA;
	}

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

	if(isset($_GET['simple'])) {
		$simple = '.fax, .gsm, .title_comp, .mb, .url {display:none}';
		$terms_of_use = nl2br(file_get_contents(DOC_ROOT . '/site/gfx/terms.txt'));
	}
	elseif (isset($_GET['full'])) {
		$full = '.title_comp, .mb, .url {display:block;}';
		$terms_of_use = nl2br(file_get_contents(DOC_ROOT . '/site/gfx/terms.txt'));
	}
	else {
		$full = '.title_comp, .mb, .url {display:none;}';
	}

	return <<<TXT
<style type="text/css">/*<![CDATA[*/
{$simple}
{$full}
/*]]>*/</style>
<script type="text/javascript"><!-- // --><![CDATA[

	function checkEmptyField()
	{
		var el = $('mail');
		var newsl = $('newsletter');

		if((newsl.checked==true)&&(empty(el.value))) {
			alert('Molimo vas unesite svoju e-mail adresu ili deselektirajte newsletter');
			el.focus();
			return false;
		}

		return true;
	}

// ]]></script>

{$feedback}

<h4 id="login_intro">Ukoliko ste se već registrirali, prijavite se. Kliknite <a href="{$url}/?{$lng}=mod.peoplering&amp;sp=help">ovdje ako ste zaboravili lozinku</a>.</h4>

<form id="login_form" method="post" action="{$submit_url}">
<script type="text/javascript"><!-- // --><![CDATA[
	document.write('<input type="hidden" id="as_clear" name="as_clear" value="1" />');	
// ]]></script>

<fieldset class="fieldset_design"><legend class="legend_design">Prijava</legend>
<p><label for="login_username">Korisničko ime: <span class="red">*</span></label></p>
<p><input type="text" name="login_username" id="login_username"  /></p>
<p><label for="login_password">Lozinka: <span class="red">*</span></label></p>
<p><input type="password" name="login_password" id="login_password"  /></p>
<p>
	{$captcha}
</p>
<p><input type="submit" name="submit" id="submit" value="Prijava" /></p>
</fieldset>
</form>

<h4 id="reg_intro">Ukoliko se niste registrirali, pozivamo vas da se <a href="javascript:void(null);" onclick="javascript:sh('register_form');">registrirate</a> i na taj način u potpunosti iskoristite sve mogućnosti ovog dijela stranice.</h4>

<form class="h" id="register_form" method="post" action="{$submit_url}" onsubmit="javascript: return checkEmptyField();">

<script type="text/javascript"><!-- // --><![CDATA[
	document.write('<input type="hidden" id="as_clear" name="as_clear" value="1" />');	
// ]]></script>

<input type="hidden" name="orbicon_registration" value="1" />
<div>
<fieldset class="fieldset_design"><legend class="legend_design">Osnovni podaci</legend>
<p class="name"><label for="name">Ime <span class="red">*</span></label></p>
<p class="name"><input type="text" name="name" id="name"  /></p>

<p class="surname"><label for="surname">Prezime <span class="red">*</span></label></p>
<p class="surname"><input type="text" name="surname" id="surname" /></p>

<p class="office"><label for="office">Redakcija</label></p>
<p class="office"><input type="text" name="office" id="office"  /></p>

<p class="position"><label for="position">Funkcija</label></p>
<p class="position"><input type="text" name="position" id="position"  /></p>

<p class="expertise"><label for="expertise">Područje koje pratite</label></p>
<p class="expertise"><input type="text" name="expertise" id="expertise"  /></p>

<p><label for="username">Korisničko ime <span class="red">*</span></label></p>
<p><input type="text" name="username" id="username"  /></p>

<p class="password"><label for="password">Lozinka <span class="red">*</span></label></p>
<p class="password"><input type="password" name="password" id="password"  /></p>

<p class="password_v"><label for="password_v">Potvrdite Lozinku <span class="red">*</span></label></p>
<p class="password_v"><input type="password" name="password_v" id="password_v"  /></p>

</fieldset>
<br />
<fieldset class="fieldset_design"><legend class="legend_design">Kontakt podaci</legend>

<p class="title_comp"><label for="title_comp">Naziv tvrtke</label></p>
<p class="title_comp"><input type="text" name="title_comp" id="title_comp"  /></p>

<p class="address"><label for="address">Adresa</label></p>
<p class="address"><input type="text" name="address" id="address"  /></p>

<p class="zip"><label for="zip">Poštanski broj</label></p>
<p class="zip"><input type="text" name="zip" id="zip"  /></p>

<p class="city"><label for="city">Grad</label></p>
<p class="city"><input type="text" name="city" id="city"  /></p>

<p class="mb"><label for="mb">Matični broj</label></p>
<p class="mb"><input type="text" name="mb" id="mb"  /></p>

<p class="phone"><label for="phone">Telefon <span class="red">*</span></label></p>
<p class="phone">
	<input type="text" name="phone_a" id="phone_a"  />
	<input type="text" name="phone_b" id="phone_b"  />
	<input type="text" name="phone" id="phone"  />
</p>

<p class="fax"><label for="fax">Fax</label></p>
<p class="fax"><input type="text" name="fax" id="fax"  /></p>

<p class="gsm"><label for="gsm">Mobilni telefon</label></p>
<p class="gsm"><input type="text" name="gsm" id="gsm"  /></p>

<p class="url"><label for="url">Web stranica</label></p>
<p class="url"><input type="text" name="url" id="url"  /></p>

<p class="mail"><label for="mail">E-mail adresa</label></p>
<p class="mail"><input type="text" name="mail" id="mail"  /></p>
</fieldset>
<br />
<p class="style_displayed_alerts">Poštujemo vašu privatnost i vrijeme. Ne dijelimo vaše povjerljive informacije s drugim strankama. Pretplatnici na e-mail objave će samo zaprimati obavijesti o našim proizvodima i događajima.</p>
<p>&nbsp;<p/>
<p><input class="check" type="checkbox" value="1" name="newsletter" id="newsletter" /> <label for="newsletter">Želim primati objave e-mailom</label></p>
<br />
	{$captcha}
<br />
<p class="style_displayed_alerts">Pružanjem vaših informacija i registracijom, slažete se sa <a href="javascript:void(null)" onclick="javascript: sh('terms_of_use');">Uvjetima Korištenja</a> ove stranice.</p>
<div class="h" id="terms_of_use">{$terms_of_use}</div>
<p>&nbsp;</p>
<p><input type="submit" name="submit" id="submit" value="Pošalji" /></p>
<br />
</div>
</form>
TXT;

?>