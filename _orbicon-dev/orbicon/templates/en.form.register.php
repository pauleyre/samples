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
	<legend class="legend_design"><label for="contact_squestion">Security question</label> <span class="red">*</span></legend>
	<label for="contact_squestion">
		<div style="overflow:auto">
			<img src="{$url}/orbicon/controler/get_captcha_image.php" alt="{$captcha_txt}" title="{$captcha_txt}" />
		</div>
	</label><br />
	<input type="text" id="contact_squestion" name="contact_squestion"   />
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
			alert('Please type in your e-mail address or uncheck newsletter');
			el.focus();
			return false;
		}

		return true;
	}

// ]]></script>

{$feedback}

<h4 id="login_intro">If you already registered, please authorize yourself using the form below. Click <a href="{$url}/?{$lng}=mod.peoplering&amp;sp=help">here if you forgot your password</a>.</h4>

<form id="login_form" method="post" action="{$submit_url}">

<script type="text/javascript"><!-- // --><![CDATA[
	document.write('<input type="hidden" id="as_clear" name="as_clear" value="1" />');	
// ]]></script>

<fieldset class="fieldset_design"><legend class="legend_design">Login</legend>
<p><label for="login_username">Username: <span class="red">*</span></label></p>
<p><input type="text" name="login_username" id="login_username" /></p>
<p><label for="login_password">Password: <span class="red">*</span></label></p>
<p><input type="password" name="login_password" id="login_password" /></p>
<p>
	{$captcha}
</p>
<p><input type="submit" name="submit" id="submit" value="Login" /></p>
</fieldset>
</form>

<h4 id="reg_intro">If you have not registered so far, we invite you to do so <a href="javascript:void(null);" onclick="javascript:sh('register_form');">here</a> to fully experience this website's content.</h4>

<form class="h" id="register_form" method="post" action="{$submit_url}" onsubmit="javascript: return checkEmptyField();">

<script type="text/javascript"><!-- // --><![CDATA[
	document.write('<input type="hidden" id="as_clear" name="as_clear" value="1" />');	
// ]]></script>

<input type="hidden" name="orbicon_registration" value="1" />
<div>
<fieldset class="fieldset_design"><legend class="legend_design">Basic information</legend>
<p class="name"><label for="name">First name</label></p>
<p class="name"><input type="text" name="name" id="name" /></p>

<p class="surname"><label for="surname">Last name</label></p>
<p class="surname"><input type="text" name="surname" id="surname" /></p>

<p class="office"><label for="office">Office</label></p>
<p class="office"><input type="text" name="office" id="office" /></p>

<p class="position"><label for="position">Position</label></p>
<p class="position"><input type="text" name="position" id="position" /></p>

<p class="expertise"><label for="expertise">Expertise</label></p>
<p class="expertise"><input type="text" name="expertise" id="expertise" /></p>

<p class="username"><label for="username">Username <span class="red">*</span></label></p>
<p class="username"><input type="text" name="username" id="username" /></p>

<p class="password"><label for="password">Password <span class="red">*</span></label></p>
<p class="password"><input type="password" name="password" id="password" /></p>

<p class="password_v"><label for="password_v">Verify Password <span class="red">*</span></label></p>
<p class="password_v"><input type="password" name="password_v" id="password_v" /></p>

</fieldset>
<br />
<fieldset class="fieldset_design"><legend class="legend_design">Contact information</legend>
<p class="address"><label for="address">Address</label></p>
<p class="address"><input type="text" name="address" id="address" /></p>

<p class="zip"><label for="zip">Zip code</label></p>
<p class="zip"><input type="text" name="zip" id="zip" /></p>

<p class="city"><label for="city">City</label></p>
<p class="city"><input type="text" name="city" id="city" /></p>

<p class="phone"><label for="phone">Phone (work)</label></p>
<p class="phone"><input type="text" name="phone" id="phone" /></p>

<p class="fax"><label for="fax">Fax</label></p>
<p class="fax"><input type="text" name="fax" id="fax" /></p>

<p class="gsm"><label for="gsm">Mobile</label></p>
<p class="gsm"><input type="text" name="gsm" id="gsm" /></p>

<p class="mail"><label for="mail">E-mail address</label></p>
<p class="mail"><input type="text" name="mail" id="mail" /></p>
</fieldset>
<br />
<p class="style_displayed_alerts">We respect your privacy and time. We do not share your information with any 3rd-party and only send you news or announcements about our products and events if you check this checkbox: </p>
<p>&nbsp;<p/>
<p><input class="check" type="checkbox" value="1" name="newsletter" id="newsletter" /> <label for="newsletter">Yes, I want to receive information from this web site (Newsletter).</label></p>
<br />
	{$captcha}
<br />
<p class="style_displayed_alerts">By providing your information and registering, you are agreeing to the this site owner's <a href="{$url}/site/gfx/terms_of_use.html">Terms of Use</a></p>
<p>&nbsp;</p>
<p><input type="submit" name="submit" id="submit" value="Submit" /></p>
<br />
</div>
</form>
TXT;

?>