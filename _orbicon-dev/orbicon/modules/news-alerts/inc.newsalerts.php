<?php

/**
 * Enter description here...
 *
 */
define('TABLE_NEWSALERTS_INFO', 'orbx_mod_newsalerts');
/**
 * Enter description here...
 *
 */
define('TABLE_NEWSALERTS_SUBS', 'orbx_mod_newsalerts_subs');


	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	function build_newsalerts()
	{
		global $orbicon_x, $dbc;

		$r = $dbc->_db->query(sprintf('	SELECT 		*
										FROM 		'.TABLE_NEWS.'
										WHERE 		(language = %s)
										ORDER BY 	date DESC, permalink',

											$dbc->_db->quote($orbicon_x->ptr)));

		$table = '<table width="100%"><thead>
	<tr style="border-bottom: 1px solid #cccccc;">
		<th>'._L('send').'</th>
		<th>'._L('title').'</th>
		<th>'._L('created_date').'</th>
		<th>'._L('publish_date').'</th>
		<th>'._L('category').'</th>
		<th>'._L('published').'</th>
		<th>'._L('last_sent').'</th>
	</tr></thead><tbody>';

		$i = 0;

		$a = $dbc->_db->fetch_assoc($r);

		while($a) {

			$news_item_info = get_newsalert_info($a['id']);
			$last_sent = empty($news_item_info['last_sent']) ? _L('none') : date($_SESSION['site_settings']['date_format'] . ' H:i:s', $news_item_info['last_sent']);

			$style = (($i % 2) == 0) ? 'style="background:#eeeeee;"' : '';
			$status_img = ($a['live'] == 1) ? 'accept.png' : 'cancel.png';

			$table .= '
			<tr '.$style.'>
				<td><select id="send_' . $a['id'] . '" name="send_' . $a['id'] . '"><option value="0" selected="selected">'._L('no').'</option><option value="1">'._L('yes').'</option></select></td>
				<td><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/news&amp;edit='.$a['permalink'].'">'.$a['title'].'</a></td>
				<td>'.date($_SESSION['site_settings']['date_format'] . ' H:i:s', $a['created']).'</td>
				<td>'.date($_SESSION['site_settings']['date_format'] . ' H:i:s', $a['date']).'</td>
				<td>'.$a['category'].'</td>
				<td><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/'.$status_img.'" /></td>
				<td>'.$last_sent.'</td>
			</tr>';
			$a = $dbc->_db->fetch_assoc($r);
			$i ++;
		}

		$table .= '</tbody></table>';
		return $table;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $news_id
	 * @return unknown
	 */
	function get_newsalert_info($news_id)
	{
		global $dbc;

		$r = $dbc->_db->query(sprintf('	SELECT 		*
										FROM 		'.TABLE_NEWSALERTS_INFO.'
										WHERE 		(news_id = %s)',
										$dbc->_db->quote($news_id)));
		return $dbc->_db->fetch_assoc($r);
	}

	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	function verify_newsalerts()
	{
		set_time_limit(0);

		global $dbc, $orbicon_x, $subscribers;
		$r = $dbc->_db->query(sprintf('	SELECT 		id, intro, permalink, title, date
										FROM 		'.TABLE_NEWS.'
										WHERE 		(language = %s)',
										$dbc->_db->quote($orbicon_x->ptr)));
		$a = $dbc->_db->fetch_assoc($r);

		$subscribers = load_subscribers();

		if(empty($subscribers)) {
			trigger_error('No subscribers found', E_USER_WARNING);
			return false;
		}

		while($a) {

			if($_POST['send_' . $a['id']] == 1) {
				send_newsalert($a['intro'], $a['permalink'], $a['title'], $a['date']);
				update_newsalert_info($a['id']);
			}

			$a = $dbc->_db->fetch_assoc($r);
		}

		unset($subscribers);
		return true;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $intro_id
	 * @param unknown_type $permalink
	 * @param unknown_type $title
	 * @param unknown_type $date
	 */
	function send_newsalert($intro_id, $permalink, $title, $date)
	{
		global $subscribers, $dbc, $orbicon_x;

		$orbicon_full_name = ORBX_FULL_NAME;

		$r_ = $dbc->_db->query(sprintf('	SELECT 		content
											FROM 		'.MAGISTER_CONTENTS.'
											WHERE 		(live = 1) AND
														(hidden = 0) AND
														(id = %s) AND
														(language = %s)',
											$dbc->_db->quote($intro_id), $dbc->_db->quote($orbicon_x->ptr)));
		$a_ = $dbc->_db->fetch_array($r_);
		$dbc->_db->free_result($r_);
		$msg = stripslashes('<h3><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$permalink.'">' . $title . '</a></h3><h4>' . date($_SESSION['site_settings']['date_format'], $date) . '</h4><br />' . $a_['content'].'<p><a href="'.ORBX_SITE_URL.'/orbicon/modules/news-alerts/unsub.php?email=__REPLACE_ME_WITH_EMAIL__">'._L('unsubscribe_news_alerts').'</a></p>');
		unset($a_);

		$mail_body = <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta name="generator" content="{$orbicon_full_name}">
</head>
<body bgcolor="#ffffff" text="#000000">
{$msg}
</body></html>
EOF;

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
		$mail->Subject = utf8_html_entities($title, true);
		$mail->WordWrap = 50;
		$mail->IsHTML(true);

		foreach($subscribers as $email) {

			$mail->AddAddress($email);

			$mail_body = str_replace('__REPLACE_ME_WITH_EMAIL__', base64_encode($email), $mail_body);
			$mail->Body = $mail_body;
			$mail->Send();
			$mail->ClearAddresses();
		}
	}

	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	function load_subscribers()
	{
		global $dbc;
		// get emails
		$q = 	'	SELECT 	email
					FROM 	'.TABLE_NEWSALERTS_SUBS;

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$emails[] = $a['email'];
			$a = $dbc->_db->fetch_assoc($r);
		}

		return $emails;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 */
	function update_newsalert_info($id)
	{
		global $dbc;
		$q = sprintf('	UPDATE 	'.TABLE_NEWSALERTS_INFO.'
						SET 	last_sent = %s
						WHERE 	(news_id = %s)
						LIMIT 	1',
						$dbc->_db->quote(time()), $dbc->_db->quote($id));
		$dbc->_db->query($q);

		// check if update status
		$q_c = sprintf('	SELECT 	last_sent
							FROM 	'.TABLE_NEWSALERTS_INFO.'
							WHERE 	(news_id = %s)
							LIMIT 	1', $dbc->_db->quote($id));
		$r_c = $dbc->_db->query($q_c);
		$a_c = $dbc->_db->fetch_assoc($r_c);

		// UPDATE failed, try with INSERT
		if(($a_c['last_sent'] === null)) {
			$q_new = sprintf('INSERT INTO '.TABLE_NEWSALERTS_INFO.' (news_id, last_sent) VALUES (%s, %s)',
											$dbc->_db->quote($id), $dbc->_db->quote(time()));

			$dbc->_db->query($q_new);
		}
	}

	/**
	 * Remove email from subscriptions
	 *
	 * @param string $email
	 */
	function unsubscribe_news_subs($email)
	{
		if(!is_string($email)) {
			trigger_error('unsubscribe_news_subs() expects parameter 1 to be string, '.gettype($email).' given', E_USER_WARNING);
			return false;
		}

		$email = trim($email);

		if(!is_email($email)) {
			trigger_error('unsubscribe_news_subs() expects parameter 1 to be email', E_USER_WARNING);
			return false;
		}

		global $dbc;
		// delete email from db
		$q = 	sprintf('	DELETE FROM 	'.TABLE_NEWSALERTS_SUBS . '
							WHERE 			(email = %s)', $dbc->_db->quote($email));

		$dbc->_db->query($q);

		redirect(ORBX_SITE_URL . '/?submit_form&unsub=ok');
	}

	/**
	 * Add email to subscriptions
	 *
	 * @param string $email
	 * @return bool
	 */
	function subscribe_news_subs($email)
	{
		if(!is_string($email)) {
			trigger_error('unsubscribe_news_subs() expects parameter 1 to be string, '.gettype($email).' given', E_USER_WARNING);
			return false;
		}

		$email = trim($email);

		if(is_email($email)) {

			global $dbc;

			$q = sprintf('	SELECT 		id
							FROM 		'.TABLE_NEWSALERTS_SUBS.'
							WHERE 		(email = %s)
							LIMIT 		1', $dbc->_db->quote($email));
			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);

			// email doesn't exist, add it to db
			if(empty($a['id'])) {
				$q_new = sprintf('	INSERT INTO 	'.TABLE_NEWSALERTS_SUBS.'
													(email, ip,
													time)
									VALUES 			(%s, %s,
													%s)',
						$dbc->_db->quote($email), $dbc->_db->quote(ORBX_CLIENT_IP),
						$dbc->_db->quote(time()));

				$dbc->_db->query($q_new);
				return true;
			}
		}

		trigger_error('subscribe_news_subs() expects parameter 1 to be email', E_USER_WARNING);
		return false;
	}

?>