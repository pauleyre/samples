<?php

	global $orbx_mod;
	if(!$orbx_mod->validate_module('peoplering')) {
		return '';
	}

	require_once DOC_ROOT . '/orbicon/modules/peoplering/class/class.prvmailer.php';

	$mailer = new PrivateMailer((int) $_SESSION['user.r']['id']);

	$total_inbox_ur = $mailer->count_mails(PR_MAILBOX_INBOX, PR_MAIL_UNREAD);
	$total_sent_ur = $mailer->count_mails(PR_MAILBOX_SENT, PR_MAIL_UNREAD);
	$total_trash_ur = $mailer->count_mails(PR_MAILBOX_TRASH, PR_MAIL_UNREAD);

	$total_new = ($total_inbox_ur + $total_sent_ur + $total_trash_ur);

	$style = ($total_new > 0) ? 'style="background-color:#72FE00"' : '';

	$username = ($_SESSION['user.r']['contact_name'] != '') ? $_SESSION['user.r']['contact_name'] . ' ' . $_SESSION['user.r']['contact_surname'] : $_SESSION['user.r']['username'];

	return sprintf(_L('login-box-msg') . '<span id="loginbox_msg_num">, <a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=mail" '.$style.'>' . _L('login-box-msg-num') . '</a></span>', $username, $total_new) . ' <a href="javascript: void(null);" onclick="javascript: __unload();" title="'._L('pr-exit').'">'._L('pr-exit').'</a>';

?>