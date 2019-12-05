<?php

	$url = ORBX_SITE_URL;
	$domain_name = DOMAIN_NAME;
	$lng = $orbicon_x->ptr;

	include_once DOC_ROOT.'/orbicon/modules/forms/class.form.php';
	$form = new Form;

	if(function_exists('imagecopyresampled') && $_SESSION['site_settings']['use_captcha']) {
		$captcha_txt = _L('captcha_nfo');
		$captcha = <<<CAPTCHA
	<fieldset>
		<legend class="legend_design"><label for="contact_squestion"> Zaštitno pitanje</label> <span class="red">*</span></legend>
		<label for="job_squestion"><div style="overflow:auto"><img src="{$url}/orbicon/controler/get_captcha_image.php" alt="{$captcha_txt}" title="{$captcha_txt}" /></div><br /></label>
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

	$test = $form->test_form($_GET[$lng]);

	$type_msg = ($test['msg_type'] == 0) ? '' : '
		<tr>
			<td style="height:30px;"><label for="type">Sažetak</label></td>
			<td>
				<select id="type" name="type">
					<option value="suggestion">Sugestija</option>
					<option value="remark">Primjedba</option>
					<option value="compliment">Pohvala</option>
				</select>
			</td>
		</tr>
	';

	return <<<TXT
<script type="text/javascript"><!-- // --><![CDATA[

	YAHOO.util.Event.addListener(window, "load", rte_lite_load);
	YAHOO.util.Event.addListener(window, "load", __rte_lite_attach);

	function __rte_lite_attach() {
		YAHOO.util.Event.addListener($('form_contact'), "submit", function () { $('msg').value = rte_lite.body.innerHTML;});
	}

	function checkEmptyField()
	{
		var el = $('email');
		var newsl = $('newsletter');

		if((newsl.checked==true)&&(empty(el.value))) {
				alert('Molimo vas unesite svoju e-mail adresu ili deselektirajte newsletter');
				el.focus();
				return false;
		}

		return true;
	}

// ]]></script>
<style type="text/css">/*<![CDATA[*/

#form_contact input[type="text"], #form_contact .input-text {
	width:99%;
}
#form_contact img {
	vertical-align:bottom;
}
#rte_lite_content {
	height: 250px !important;
}
/*]]>*/</style>
<form id="form_contact" method="post" action="{$submit_url}" onsubmit="javascript: return checkEmptyField();">
<script type="text/javascript"><!-- // --><![CDATA[
	document.write('<input type="hidden" id="msg" name="msg" />');
	document.write('<input type="hidden" id="as_clear" name="as_clear" value="1" />');
// ]]></script>

{$feedback}

<p>Obavezno ispunite polja uz čiji se naslov nalazi crvena zvjezdica <span class="red">*</span></p>
	<table>
		<tr>
			<td  style="height:30px; width:30%;"><label for="name">Ime i Prezime</label></td>
			<td><input class="input-text" tabindex="1" name="name" type="text" id="name" /></td>
		</tr>
		<tr>
			<td style="height:30px;"><label for="email">E-mail</label></td>
			<td><input class="input-text" tabindex="2" name="email" type="text" id="email" /></td>
		</tr>
		{$type_msg}
		<tr>
			<td style="height:30px; vertical-align:top;"><label for="rte_lite_content">Poruka</label> <span class="red">*</span></td>
			<td>Formatiranje : <a href="javascript:void(null);" onclick="javascript:rte_lite_bold();"><img src="{$url}/orbicon/gfx/gui_icons/text_bold.png" alt="text_bold.png" title="Bold (CTRL + B)" /></a> <a href="javascript:void(null);" onclick="javascript:rte_lite_italic();"><img src="{$url}/orbicon/gfx/gui_icons/text_italic.png" alt="text_italic.png" title="Italic (CTRL + I)" /></a> <a href="javascript:void(null);" onclick="javascript:rte_lite_underline();"><img src="{$url}/orbicon/gfx/gui_icons/text_underline.png" alt="text_underline.png" title="Underline (CTRL + U)" /></a> <a href="javascript:void(null);" onclick="javascript:rte_lite_strikethrough();"><img src="{$url}/orbicon/gfx/gui_icons/text_strikethrough.png" alt="text_strikethrough.png" title="Strikethrough" /></a><br />
<script type="text/javascript"><!-- // --><![CDATA[
	document.write('<iframe id="rte_lite_content" class="input-text"></iframe>');
// ]]></script>
			<noscript>
				<div style="width: 99%;"><textarea onkeyup="ag(this)" name="msg" style="width: 100%; height: 250px;"></textarea></div>
			</noscript>
			</td>
		</tr>
		<tr>
			<td style="height:30px; vertical-align:top;"><label for="#">Kontaktirajte me</label></td>
			<td>
					<input checked="checked" name="contact_back_email" value="email" type="checkbox" id="contact_back_email" /> <label for="contact_back_email">e-mailom</label><br />

					<input name="contact_back_phone" value="phone" type="checkbox" id="contact_back_phone" /> <label for="contact_back_phone">telefonom</label>, <label for="phone">na broj</label> <input name="phone" id="phone" maxlength="20" type="text" class="input-text" /><br />
						<input name="contact_back_mail" value="mail" id="contact_back_mail" type="checkbox" /> <label for="contact_back_mail">poštom,</label> <label for="mail">na adresu</label> <input type="text" id="mail" name="mail" class="input-text" />
				</td>
			</tr>
			<tr>
				<td><label for="time">Dostupan sam</label></td>
				<td style="height:30px;">
					<select name="time" id="time">
						<optgroup label="&mdash; odaberite vremenski period &mdash;">
							<option value="morning">ujutro</option>
							<option value="afternoon">poslijepodne</option>
							<option value="night">navečer</option>
							<option value="any">u bilo koje vrijeme</option>
						</optgroup>
					</select>
				</td>
			</tr>
		<tr>
			<td style="height:30px;"><label for="newsletter">Želim primati newsletter</label></td>
			<td><input tabindex="4" name="newsletter" type="checkbox" id="newsletter" value="1" /></td>
		</tr>
		<tr>
			<td style="height:30px; vertical-align:top;" colspan="2">
			{$captcha}
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input tabindex="5" name="Submit" type="submit" id="Submit" value="Pošalji &raquo;" /></td>
		</tr>
	</table>
</form>
TXT;

?>