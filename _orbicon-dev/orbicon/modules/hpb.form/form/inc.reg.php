<?php

	function submit_hpbform()
	{
		return save_reg();
	}

	function save_reg()
	{
		global $orbx_log;

		if(isset($_POST['submit'])) {
			$check_pass = true;

			// check for duplicate usernames
			$email_chk = sql_assoc('SELECT 		id
									FROM 		pring_contact
									WHERE 		(contact_email = %s)
									LIMIT		1', $_POST['email']);
			if(!empty($email_chk['id'])) {
				$check_pass = false;
				$_SESSION['orbicon_infobox_msg'] .= 'E-mail adresa je već registrirana<br />';
			}

			if($check_pass) {

				$id = sql_insert('INSERT INTO pring_contact (
						contact_name, contact_surname,
						contact_address, contact_city,
						contact_email, bank_status,
						contact_zip, contact_phone,
						registered)
						VALUES (
						%s, %s,
						%s, %s,
						%s, %s,
						%s, %s,
						UNIX_TIMESTAMP()
						)', array($_POST['ime'], $_POST['prezime'],
						$_POST['ulica_ko'], $_POST['mjesto_ko'],
						$_POST['email'], $_POST['status'],
						$_POST['zip_ko'], $_POST['tel']));

				if($id) {
					sql_insert('INSERT INTO 	pring_cvs
												(contact_id)
								VALUES			(%s)', $id);

					// insert initial data into pring company table
					sql_insert('INSERT INTO 	pring_company
												(contact, title,
												address, zip,
												city, phone,
												fax, mb)
								VALUES 			(%s, %s,
												%s, %s,
												%s, %s,
												%s, %s)', array($id, $_POST['c_ime'],
												$_POST['c_ulica_ko'], $_POST['c_zip_ko'],
												$_POST['c_mjesto_ko'], $_POST['c_tel'],
												$_POST['c_fax'], $_POST['c_mb']));

					include_once DOC_ROOT . '/orbicon/3rdParty/phpmailer/class.phpmailer.php';

					// inpulls fields end

					sql_insert('	INSERT INTO 	'.TABLE_REG_USERS.'
													(username, pwd,
													pring_contact_id, email)
									VALUES 			(%s, PASSWORD(%s),
													%s, %s)',
									array('', $_POST['password'],
									$id, $_POST['email']));

					$reg_logo = '';
					if(is_file(DOC_ROOT . '/site/gfx/reg_logo.gif')) {
						$reg_logo = '<center><a href="'.ORBX_SITE_URL.'" title="'.ORBX_SITE_URL.'"><img alt="'.ORBX_SITE_URL.'" title="'.ORBX_SITE_URL.'" src="'.ORBX_SITE_URL . '/site/gfx/reg_logo.gif" /></a></center>';
					}

					$mail = new PHPMailer();

					$subject = utf8_html_entities('**' . DOMAIN . ' / Registracija**', true);
					$body = "Dobro došli na HPB<br>

			Ovo su vaši podaci za prijavu:<br>
			***********************<br>
			<b>E-mail</b>: {$_POST['email']}<br>
			<b>Lozinka:</b> {$_POST['password']}<br>
			***********************<br>
			<br>
			--<br>
			<a href=\"".ORBX_SITE_URL."\">HPB, Moja banka</a><br>
			$reg_logo";

					if($_SESSION['site_settings']['smtp_server'] != '') {
						$mail->IsSMTP(); // telling the class to use SMTP
						$mail->Host = $_SESSION['site_settings']['smtp_server']; // SMTP server
						$mail->Port = $_SESSION['site_settings']['smtp_port'];
					}

					$mail->CharSet = 'UTF-8';
					$mail->From = utf8_html_entities(DOMAIN_EMAIL, true);
					$mail->FromName = utf8_html_entities(DOMAIN_OWNER, true);
					$mail->AddAddress($_POST['email']);

					$mail->Subject = $subject;
					$mail->Body = $body;
					$mail->WordWrap = 50;
					$mail->IsHTML(true);

					if(!$mail->Send()) {
						$orbx_log->ewrite('PHPMailer failed, switching to mail', __LINE__, __FUNCTION__);
						mail($_POST['email'], $subject, $body, 'Content-Type: text/html; charset=UTF-8');
					}

					$mail = null;
				}
			}
		}
	}

?>