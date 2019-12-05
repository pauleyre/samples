<?php

	global $orbicon_x;
	$enable_captcha = false;
	$question = $dbc->_db->fetch_array($q->get_question($_GET['id'], 1));
	$question = $question['id'];

	if(function_exists('imagecopyresampled') && $enable_captcha) {
		$captcha = '
	<fieldset>
		<legend class="legend_design"><label for="contact_squestion"><span class="red">*</span> Security question</label></legend>
		<label for="job_squestion"><div style="overflow:auto"><img src="'.ORBX_SITE_URL.'/orbicon/controler/get_captcha_image.php" alt="'._L('captcha_nfo').'" title="'._L('captcha_nfo').'" /></div><br /></label>
		<input type="text" id="contact_squestion" name="contact_squestion" />
	</fieldset>';
	}

	$reply = '
<style type="text/css">/*<![CDATA[*/

#form_contact input[type="text"],
#form_contact .input-text {
	width:99%;
}
#form_contact img {
	vertical-align:bottom;
}
#rte_lite_content {
	height: 250px !important;
}
/*]]>*/</style>
<script type="text/javascript"><!-- // --><![CDATA[

	YAHOO.util.Event.addListener(window, "load", rte_lite_load);
	YAHOO.util.Event.addListener(window, "load", __rte_lite_attach);

	function __rte_lite_attach() {
		YAHOO.util.Event.addListener(new getObj("form_contact").obj, "submit", function () {new getObj("answer").obj.value = rte_lite.body.innerHTML;});
	}

// ]]></script>
<div style="clear:both;"></div>
<fieldset>
<legend>Answer to this question</legend>
<form id="form_contact" method="post" action="">
<input type="hidden" id="qid" name="qid" value="'.$question.'" />
<input type="hidden" id="author_id" name="author_id" value="'.$_SESSION['user']['id'].'" />
<script type="text/javascript"><!-- // --><![CDATA[
	document.write(\'<input type="hidden" id="answer" name="answer" />\');
// ]]></script>
<p>Fields with red asterisk <span class="red">*</span> are required</p>
	<table>
		<tr>
			<td style="height:30px; width:15%;"><label for="name">First name and last name</label></td>
			<td><input class="input-text" tabindex="1" name="name" type="text" id="name" value="'.$_SESSION['user']['first_name'].'" /></td>
		</tr>
		<tr>
			<td  style="height:30px;"><label for="email">E-mail</label></td>
			<td><input class="input-text" tabindex="2" name="email" type="text" id="email" value="'.$_SESSION['user']['email'].'" /></td>
		</tr>
		<tr>
			<td style="height:30px; vertical-align:top;"><label for="rte_lite_content">Message <span class="red">*</span></label></td>
			<td>Formatting : <a href="javascript:void(null);" onclick="javascript:rte_lite_bold();"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/text_bold.png" alt="text_bold.png" title="Bold (CTRL + B)" /></a> <a href="javascript:void(null);" onclick="javascript:rte_lite_italic();"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/text_italic.png" alt="text_italic.png" title="Italic (CTRL + I)" /></a> <a href="javascript:void(null);" onclick="javascript:rte_lite_underline();"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/text_underline.png" alt="text_underline.png" title="Underline (CTRL + U)" /></a> <a href="javascript:void(null);" onclick="javascript:rte_lite_strikethrough();"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/text_strikethrough.png" alt="text_strikethrough.png" title="Strikethrough" /></a><br />
<script type="text/javascript"><!-- // --><![CDATA[
	document.write(\'<iframe id="rte_lite_content" class="input-text"></iframe>\');
// ]]></script>
			<noscript>
				<div style="width: 99%;"><textarea name="answer" style="width: 100%; height: 250px;"></textarea></div>
			</noscript>
			</td>
		</tr>
		<tr>
			<td style="height:30px; vertical-align:top;" colspan="2">
			'.$captcha.'
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input tabindex="5" name="post_answer" type="submit" id="post_answer" value="Send" /></td>
		</tr>
	</table>
</form>
</fieldset>';

	return $reply;
?>