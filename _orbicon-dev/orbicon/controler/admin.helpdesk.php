<?php
/*-----------------------------------------------------------------------*
	Project........:	Orbicon X framework 2
	File...........:
	Version........:	1.0 (22/10/2006)
	Author.........:	Pavle Gardijan (pavle.gardijan@orbitum.net) - Orbitum d.o.o.
	Copyright......:	(c) 2006 Orbitum internet communications d.o.o. (www.orbitum.net)
	Created........:	01/07/2006
	Notes..........:
	Modified.......:
*-----------------------------------------------------------------------*/

	if(isset($_POST['send'])) {

		include_once DOC_ROOT . '/orbicon/3rdParty/phpmailer/class.phpmailer.php';

		$mail = new PHPMailer();

		if($_SESSION['site_settings']['smtp_server'] != '') {
			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->Host = $_SESSION['site_settings']['smtp_server']; // SMTP server
			$mail->Port = $_SESSION['site_settings']['smtp_port'];
		}

		$mail->CharSet = 'UTF-8';
		$mail->From = DOMAIN_EMAIL;
		$mail->FromName = utf8_html_entities(DOMAIN_OWNER, true);
		$mail->AddAddress(ORBX_SUPPORT_EMAIL);

		$mail->Subject = 'Email @ ' . DOMAIN;
		$mail->Body = $_POST['email'];
		$mail->WordWrap = 50;
		$mail->IsHTML(true);

		if(!$mail->Send()) {

			mail(ORBX_SUPPORT_EMAIL, 'Email @ ' . DOMAIN, $_POST['email'], 'Content-Type: text/html; charset=UTF-8');
		}

		$mail = null;
	}

?>

<script type="text/javascript"><!-- // --><![CDATA[

	YAHOO.util.Event.addListener(window, "load", rte_lite_load);
	YAHOO.util.Event.addListener(window, "load", __rte_lite_attach);

	function __rte_lite_attach() {
		YAHOO.util.Event.addListener($('form_helpdesk'), "submit", function () {$('email').value = rte_lite.body.innerHTML;});
	}

// ]]></script>
<style type="text/css">/*<![CDATA[*/

	#rte_lite_content {
		background:#ffffff;
		width:99%;
	}

/*]]>*/</style>
<form id="form_helpdesk" method="post" action="">
	<script type="text/javascript"><!-- // --><![CDATA[
		document.write('<input type="hidden" id="email" name="email" />');
	// ]]></script>
	<p><label for="email"><?php echo _L('formatting'); ?> : </label>
	<a href="javascript:void(null);" onclick="javascript:rte_lite_bold();">
		<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/text_bold.png" alt="text_bold.png" title="Bold (CTRL + B)" />
	</a>
	<a href="javascript:void(null);" onclick="javascript:rte_lite_italic();">
		<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/text_italic.png" alt="text_italic.png" title="Italic (CTRL + I)" />
	</a>
	<a href="javascript:void(null);" onclick="javascript:rte_lite_underline();">
		<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/text_underline.png" alt="text_underline.png" title="Underline (CTRL + U)" />
	</a>
	<a href="javascript:void(null);" onclick="javascript:rte_lite_strikethrough();">
		<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/text_strikethrough.png" alt="text_strikethrough.png" title="Strikethrough" />
	</a><br />
	<script type="text/javascript"><!-- // --><![CDATA[
		document.write('<iframe id="rte_lite_content"></iframe>');
	// ]]></script>
		<noscript>
			<textarea id="email" name="email"></textarea>
		</noscript><br />
		<p><input id="send" name="send" type="submit" value="<?php echo _L('send_msg'); ?>" /></p>
	</p>
</form>
 <div style="height: 1%;"></div>