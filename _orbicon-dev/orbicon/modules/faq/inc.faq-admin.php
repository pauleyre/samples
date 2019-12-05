<?php

function faq_admin_all()
{
	global $orbicon_x, $dbc;

	$sql_q = '	SELECT		permalink, id, title, live, live_date, submited
				FROM		'.TABLE_FAQ_QUESTION.'
				ORDER BY 	submited DESC';

	$res_q = sql_res($sql_q);
	$a_q = $dbc->_db->fetch_assoc($res_q);
	$i = 0;

	while($a_q) {
		$live_str = ($a_q['live']) ? 'DA' : 'NE';
		$bg = (($i % 2) == 0) ? '#FFF' :'#F0F0EE';

		$str .= '<tr style="background:'.$bg.'">
					<td>' . $live_str . '</td>
					<td><a href="./?qid=' . $a_q['id'] . '&amp;' . $orbicon_x->ptr.'=orbicon/mod/faq">'.$a_q['title'].'</a></td>
					<td>' . date('d.m.Y', $a_q['submited']) . '</td>
					<td>' . ((!$a_q['live_date']) ? 'NE' : date('d.m.Y', $a_q['live_date'])) . '</td>
				</tr>';
		$a_q = $dbc->_db->fetch_assoc($res_q);
		$i ++;
	}

	return "
<table>
	<tr style=\"font-weight:bold\">
		<td>Objavljeno u javnosti</td>
		<td>Pitanje</td>
		<td>Zaprimljeno</td>
		<td>Odgovoreno</td>
	</tr>
	$str
</table>";
}

function faq_admin_post_q($live = 1)
{
	global $orbicon_x;

	if(empty($_GET['qid'])) {
		$sql = '
				INSERT INTO			'.TABLE_FAQ_QUESTION.'
									(title, category,
									poster, poster_id,
									submited, email,
									email_notify, answer,
									live_date, live,
									permalink, lang)
				VALUES				(%s, %s,
									%s, %s,
									UNIX_TIMESTAMP(), %s,
									%s, %s,
									UNIX_TIMESTAMP(), %s,
									%s, %s)';
		return sql_insert($sql, array($_POST['title'], $_POST['category'], $_POST['poster'], $_SESSION['user.r']['id'], $_POST['email'], 0, faq_admin_hyperlink($_POST['answer']), $live, get_permalink($_POST['title']), $orbicon_x->ptr));
	}
	else {
		$sql = '
				UPDATE '.TABLE_FAQ_QUESTION.'
				SET
				title = %s, category = %s,
				poster = %s, email = %s,
				answer = %s, live_date = UNIX_TIMESTAMP(),
				live = %s, permalink = %s,
				lang = %s
				WHERE id = %s';
		return sql_update($sql, array($_POST['title'], $_POST['category'], $_POST['poster'], $_POST['email'], faq_admin_hyperlink($_POST['answer']), $live, get_permalink($_POST['title']), $orbicon_x->ptr, $_GET['qid']));
	}
}

function faq_admin_delete($id, $category)
{
	faq_category_total_dec($category);
	return sql_res('DELETE FROM ' . TABLE_FAQ_QUESTION . ' WHERE id = %s', $id);
}

function faq_admin_email()
{
	include_once DOC_ROOT . '/orbicon/3rdParty/phpmailer/class.phpmailer.php';

	$to = $_POST['email'];
	$subject = 'Odgovor sa HPB d.d. stranice';
	$body = faq_admin_hyperlink(nl2br($_POST['answer']));


	$mail = new PHPMailer();

	if($_SESSION['site_settings']['smtp_server'] != '') {
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->Host = $_SESSION['site_settings']['smtp_server']; // SMTP server
		$mail->Port = $_SESSION['site_settings']['smtp_port'];
	}

	$mail->CharSet = 'UTF-8';
	$mail->From = DOMAIN_EMAIL;
	$mail->FromName = utf8_html_entities(DOMAIN_OWNER, true);
	$mail->AddAddress($to);

	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->WordWrap = 50;
	$mail->IsHTML(true);

	if(!$mail->Send()) {
		$headers = 'From: ' . DOMAIN_NAME . ' <' . DOMAIN_EMAIL . ">\n";
		$headers .= 'Reply-To: ' . DOMAIN_NAME . ' <' . DOMAIN_EMAIL . ">\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= 'Date: ' . date('r');

		return mail($to, $subject, $message, $headers);
	}

	$mail = null;

	return true;
}

function faq_admin_hyperlink($text)
{
	require_once DOC_ROOT . '/orbicon/magister/class.magister.php';
	$m = new Magister();
	$text = $m->hyperlinks_add($text);
	$m = null;
	return $text;
}

?>