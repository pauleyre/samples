<?php

	require_once DOC_ROOT . '/orbicon/modules/peoplering/class/class.prvmailer.php';

	$mailer = new PrivateMailer((int) $_SESSION['user.r']['id']);
	$mailer->set_read($_GET['mail']);

	if(isset($_POST['mail_ok'])) {
		redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&sp=mail');
	}

	if(isset($_POST['mail_reply'])) {
		redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&sp=mail&reply=' . $_GET['mail']);
	}

	if(isset($_POST['mail_delete'])) {
		$mailer->delete(array($_GET['mail']));
		redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&sp=mail');
	}

	$mail = $mailer->get($_GET['mail']);

	if(empty($mail)) {
		$mail = 'N/A';
	}

	$orbicon_x->set_page_title($mail['subject']);

	$js_subject = str_sanitize($mail['subject'], STR_SANITIZE_JAVASCRIPT);
	// these caused problems
	$js_subject = str_replace('"', '', $js_subject);

	$sender = $pr->get_username($mail['mail_from']);
	$sender = $sender['username'];

	$receivers = $mailer->format_mail_to($mail['mail_to']);

	if($mail['attachment']) {
		$atch = '<div class="sent_date"><strong>'._L('pr-attch').':</strong> '.$mailer->format_attch($mail['id']).'</div>';
	}

	$display_content = '
<script type="text/javascript"><!-- // --><![CDATA[

	function delete_mail(){
		return window.confirm(\''._L('delete').' "' . $js_subject . '" ?\');
	}

// ]]></script>
<h1 class="mail_title">'.$mail['subject'].'</h1>
<div class="mail_header">
	<div class="sent_by"><strong>'._L('pr-read-sent-by').':</strong> <a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=user&amp;user='.$sender.'">'.$sender.'</a></div>
	<div class="sent_to"><strong>'._L('pr-read-to').':</strong> '.$receivers.'</div>
	<div class="sent_date"><strong>'._L('pr-read-on').':</strong> '.date('r', $mail['mail_date']).'</div>
	'.$atch.'
</div><br />
<div class="mail_body">'.$mail['body'].'</div>

<form id="mail_actions" method="post" action="">
	<p>
		<input name="mail_ok" id="mail_ok" onclick="javascript: history.go(-1); return false;" value="OK" type="submit" />
	    <input name="mail_reply" id="mail_reply" value="'._L('pr-reply').'" type="submit" />
	    <input name="mail_delete" id="mail_delete" value="'._L('pr-delete').'" type="submit" onclick="javascript: return delete_mail();" />
	<p>
</form>';

?>