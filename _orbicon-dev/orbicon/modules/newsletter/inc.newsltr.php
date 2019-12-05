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

	function send_newsletter()
	{
		if(isset($_POST['send_newsletter'])) {
			global $dbc, $orbicon_x;

			$pause = intval($_POST['newsletter_server_pause']);
			$content = $_POST['content_text'];
			$title = $_POST['newsletter_title'];
			$adrbk = $_POST['newsletter_adrbk'];

			$orbicon_full_name = ORBX_FULL_NAME;

			$r_ = $dbc->_db->query(sprintf('	SELECT 		content
												FROM 		'.MAGISTER_CONTENTS.'
												WHERE 		(live = 1) AND
															(hidden = 0) AND
															(question_permalink = %s) AND
															(language = %s)
												ORDER BY 	uploader_time',
												$dbc->_db->quote($content), $dbc->_db->quote($orbicon_x->ptr)));
			$a_ = $dbc->_db->fetch_assoc($r_);

			while($a_) {
				$msg .= $a_['content'];
				$a_ = $dbc->_db->fetch_assoc($r_);
			}
			$dbc->_db->free_result($r_);
			$msg = stripslashes($msg);

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

			// get emails
			$q = sprintf('SELECT column_list
							FROM '.TABLE_ADRBKS.'
							WHERE (permalink = %s)
							LIMIT 1', $dbc->_db->quote($adrbk));

			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_array($r);

			$emails = explode('|', $a['column_list']);

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
			foreach($emails as $email) {
				if(is_email($email)) {
					$mail->AddAddress($email);
				}
			}

			$mail->Subject = utf8_html_entities($title, true);
			$mail->Body = $mail_body;
			$mail->WordWrap = 50;
			$mail->IsHTML(true);

			if(!$mail->Send()) {

				$mail_header = "MIME-Version: 1.0\n";
				$mail_header .= "Content-Type: text/html; charset=utf-8\n";
				$mail_header .= "Content-Transfer-Encoding: 7bit\n";
				$mail_header .= 'From: '.DOMAIN_OWNER.' <'.DOMAIN_EMAIL.">\n";
				$mail_header .= 'Reply-To: '.DOMAIN_OWNER.' <'.DOMAIN_EMAIL.">\n";
				$mail_header .= 'Date: '.date('r')."\n";

				// format and mail
				foreach($emails as $email) {
					$email = trim($email);
					mail($email, $title, $mail_body, $mail_header);
					// pause
					if($pause > 0) {
						usleep($pause);
					}
				}
			}

			$mail = null;
		}
	}

?>