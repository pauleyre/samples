<?php
/**
 * Class for form handling
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package SystemMOD
 * @subpackage Forms
 * @version 1.00
 * @link http://
 * @license http://
 * @since 2007-09-10
 */

class Form
{
	function build_forms($columns)
	{
		global $dbc;

		// address books
		$r = $dbc->_db->query('	SELECT 		title, permalink
								FROM 		'.TABLE_ADRBKS.'
								ORDER BY 	permalink');
		if($r) {
			$a = $dbc->_db->fetch_assoc($r);
			$opcije .= '<optgroup label="'._L('adr_books').'">';

			while($a) {
				if(!in_array($a['permalink'], $columns)) {
					$opcije .= sprintf('<option value="%s">%s</option>', $a['permalink'], $a['title']);
				}
				else {
					$selected .= sprintf('<option value="%s">%s</option>', $a['permalink'], $a['title']);
				}
				$a = $dbc->_db->fetch_assoc($r);
			}

			$opcije .= '</optgroup>';
		}

		return array($opcije, $selected);
	}

	function save_form()
	{
		if(isset($_POST['save_form'])) {
			global $dbc, $orbicon_x;
			// * first, delete all
			$permalink = $_GET['edit'];
			$template = $_POST['contact_templates'];

			$title = trim($_POST['form_title']);

			if(empty($title)) {
				trigger_error('save_form() expects form title to be non-empty', E_USER_WARNING);
				return false;
			}

			$permalink = get_permalink($title);
			$title = utf8_html_entities($title);

			if(!empty($_POST['orbicon_list_selected'])) {
				$_POST['orbicon_list_selected'] = array_remove_empty($_POST['orbicon_list_selected']);
				$adrbks = implode('|', $_POST['orbicon_list_selected']);
			}

			if(!isset($_GET['edit'])) {
				$q = sprintf('INSERT INTO 	'.TABLE_FORMS.'
											(title, permalink,
											template, adrbks,
											linked_text, language,
											msg_type, redirect)
								VALUES		(%s, %s,
											%s, %s,
											%s, %s,
											%s, %s)',
										$dbc->_db->quote($title), $dbc->_db->quote($permalink),
										$dbc->_db->quote($template), $dbc->_db->quote($adrbks),
										$dbc->_db->quote($_POST['content_text']), $dbc->_db->quote($orbicon_x->ptr),
										$dbc->_db->quote($_POST['msg_type']), $dbc->_db->quote($_POST['redirect_url']));
			}
			else {
				$q = sprintf('	UPDATE 	'.TABLE_FORMS.'
								SET 	title = %s, permalink = %s,
										template = %s, adrbks = %s,
										linked_text = %s, msg_type = %s,
										redirect = %s
								WHERE 	(permalink = %s) AND
										(language = %s)',
							$dbc->_db->quote($title), $dbc->_db->quote($permalink), $dbc->_db->quote($template), $dbc->_db->quote($adrbks), $dbc->_db->quote($_POST['content_text']), $dbc->_db->quote($_POST['msg_type']), $dbc->_db->quote($_POST['redirect_url']), $dbc->_db->quote($_GET['edit']), $dbc->_db->quote($orbicon_x->ptr));
			}
			$dbc->_db->query($q);

			redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/forms&edit='.urlencode($permalink));
		}
	}

	function delete_form()
	{
		if(isset($_GET['delete_form']) && get_is_admin()) {
			global $dbc, $orbicon_x;
			$dbc->_db->query(sprintf('	DELETE FROM 	'.TABLE_FORMS.'
										WHERE 			(permalink = %s) AND
														(language = %s)
										LIMIT 			1', $dbc->_db->quote($_GET['delete_form']), $dbc->_db->quote($orbicon_x->ptr)));

			redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/forms');
		}
	}

	function load_form()
	{
		if(isset($_GET['edit'])) {
			global $dbc, $orbicon_x;
			$q = sprintf('	SELECT 		*
							FROM 		'.TABLE_FORMS.'
							WHERE 		(permalink=%s) AND
										(language = %s)
							LIMIT 		1', $dbc->_db->quote($_GET['edit']), $dbc->_db->quote($orbicon_x->ptr));
			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);

			return $a;
		}
		return null;
	}

	function test_form($permalink)
	{
		global $dbc, $orbicon_x;

		$form_name = $this->get_form_name($permalink);

		$sql = sprintf('SELECT	*
						FROM 	' . TABLE_FORMS . '
						WHERE 	(permalink=%s) AND
								(language = %s)
						LIMIT 	1',
						$dbc->_db->quote($form_name['content']),
						$dbc->_db->quote($orbicon_x->ptr));

		$resource = $dbc->_db->query($sql);
		$result = $dbc->_db->fetch_assoc($resource);

		return $result;
	}

	function get_form_name($permalink)
	{
		global $dbc, $orbicon_x;

		// first get form name from column
		$sql = sprintf('SELECT		content
						FROM		'.TABLE_COLUMNS.'
						WHERE		(permalink = %s) AND
									(language = %s)',
							$dbc->_db->quote($permalink),
							$dbc->_db->quote($orbicon_x->ptr));

		$resource = $dbc->_db->query($sql);
		$result = $dbc->_db->fetch_assoc($resource);

		return $result;
	}

	function get_form_array()
	{
		global $dbc, $orbicon_x;
		$q = sprintf('	SELECT 		*
						FROM 		'.TABLE_FORMS.'
						WHERE 		(language = %s)
						ORDER BY 	permalink', $dbc->_db->quote($orbicon_x->ptr));
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$forms[] = $a;
			$a = $dbc->_db->fetch_assoc($r);
		}

		return $forms;
	}

	// handle forms
	function submit_orbiconform()
	{
		global $dbc, $orbicon_x, $orbx_log, $orbx_mod;

		// antispam check
		if($_SESSION['site_settings']['antispam_check'] && !isset($_POST['as_clear'])) {
			return;
		}

		// clean up old messages
		unset($_SESSION['orbicon_infobox_msg']);

		// add email to db
		if(isset($_POST['add2newsletter'])) {
			// this will trigger inserting into db located below
			$_POST['newsletter'] = 1;
			$email = trim($_POST['email']);
		}

		// display info that we removed user
		if($orbx_mod->validate_module('news-alerts') && isset($_GET['unsub'])) {
			if($_GET['unsub'] == 'ok') {
				$_SESSION['orbicon_infobox_msg'] = _L('youre_unsubscribed').'.';
			}
		}

		if(isset($_POST['login_username'])) {
			/* login action */
			$check_pass = true;
			$secure_question_pass = false;
			$username = $_POST['login_username'];
			$password = $_POST['login_password'];

			// check captcha and also check for GD2 library
			if(function_exists('imagecopyresampled') && $_SESSION['site_settings']['use_captcha']) {
				// we'll need captcha
				include_once DOC_ROOT . '/orbicon/class/inc.captcha.php';

				if(!check_captcha($_POST['contact_squestion'])) {
					$secure_question_pass = false;
					$_SESSION['orbicon_infobox_msg'] .= _L('wrong_sec_q').'.<br />';
				}
				else {
					$secure_question_pass = true;
				}
			}
			else {
				$secure_question_pass = true;
			}

			if(empty($username)) {
				$check_pass = false;
				$_SESSION['orbicon_infobox_msg'] .= _L('empty_username').'.<br />';
			}

			if(empty($password)) {
				$check_pass = false;
				$_SESSION['orbicon_infobox_msg'] .= _L('empty_password').'.<br />';
			}

			if(strpos($password, $username) !== false) {
				$check_pass = false;
				$_SESSION['orbicon_infobox_msg'] .= _L('pwd_user_mismatch').'.<br />';
			}

			if($check_pass && $secure_question_pass) {

				// Check for press user
				$q = '	SELECT 	*
						FROM 	'.TABLE_REG_USERS.'
						WHERE 	((username = ' . $dbc->_db->quote($username) . ' ) OR
								(email = '. $dbc->_db->quote($username) .')) AND
								(pwd = PASSWORD(' . $dbc->_db->quote($password) . ')) AND
								(banned = 0)
						LIMIT	1';

				$r = $dbc->_db->query($q);
				$a = $dbc->_db->fetch_assoc($r);

				if(!empty($a['id'])) {
					// setting session variables for authenticated user
					$_SESSION['user_authorized'] = true;

					// fetch PRING data
					if($orbx_mod->validate_module('peoplering')) {
						$q2 = '	SELECT 	*
								FROM 	pring_contact
								WHERE 	(id = ' . $dbc->_db->quote($a['pring_contact_id']) . ')
								LIMIT	1';

						$r2 = $dbc->_db->query($q2);
						$a2 = $dbc->_db->fetch_assoc($r2);

						$_SESSION['user.r'] = $a2;
						// this caused lots of troubles
						$_SESSION['user.r']['id'] = $a['id'];
						$_SESSION['user.r']['pring_id'] = $a['pring_contact_id'];
						$_SESSION['user.r']['username'] = $username;
						$orbx_log->dwrite('peoplering user id #' . $_SESSION['user.r']['id'] . ' logged in', __LINE__, __FUNCTION__);
					}
					else {
						$_SESSION['user.r'] = $a;
						$_SESSION['user.r']['username'] = $username;
						$orbx_log->dwrite('registered user id #' . $_SESSION['user.r']['id'] . ' logged in', __LINE__, __FUNCTION__);
					}

					$_SESSION['orbicon_infobox_msg'] = _L('logged_in').'.';
					// security measure
					unset($_SESSION['username'], $_SESSION['pwd']);

					if(in_array(DOMAIN_NO_WWW, array('hpb.hr', 'wwwadmin', 'wwwtest', 'localhost'))) {
						if($_SESSION['user.r']['bank_status'] == 'gradj') {
							redirect(ORBX_SITE_URL . '/?hr=gra%C4%91anstvo');
						}
						else if($_SESSION['user.r']['bank_status'] == 'posl_m') {
							redirect(ORBX_SITE_URL . '/?hr=malo-i-srednje-poduzetni%C5%A1tvo');
						}
						else if($_SESSION['user.r']['bank_status'] == 'posl_v') {
							redirect(ORBX_SITE_URL . '/?hr=velike-tvrtke');
						}
					}
				}
				// no user found
				else {
					$_SESSION['orbicon_infobox_msg'] .= _L('wrong_user').'.<br />';

					if($_SESSION['orbicon_infobox_msg']) {
						$_SESSION['orbicon_infobox_msg'] .= '<a href="'.$_SERVER['HTTP_REFERER'].'" onclick="javascript:window.history.back();return false;">'._L('go_back_try_again').'.</a>';
					}
				}
				/* login ends*/
			}
			else {
				if($_SESSION['orbicon_infobox_msg']) {
					$_SESSION['orbicon_infobox_msg'] .= '<a href="'.$_SERVER['HTTP_REFERER'].'" onclick="javascript:window.history.back();return false;">'._L('go_back_try_again').'.</a>';
				}
			}

			return true;
		}

		// determine from where we're coming
		if($_SESSION['site_settings']['form_feedback_position'] == 'inside') {
			$refer = $_GET[$orbicon_x->ptr];
		}
		else {
			$refer = explode('?'.$orbicon_x->ptr.'=', $_SERVER['HTTP_REFERER']);

			if(empty($refer[1])) {
				$refer = explode($orbicon_x->ptr.'/', $_SERVER['HTTP_REFERER']);
			}

			$refer = trim($refer[1], '/'); // BUG FIX: mising '/'
		}

		$secure_question_pass = true;
		$check_pass = true;

		// find form
		$q = sprintf('		SELECT 		content
							FROM 		'.TABLE_COLUMNS.'
							WHERE 		(type = \'form\') AND
										(permalink = %s) AND
										(language = %s)
							LIMIT 		1', $dbc->_db->quote($refer), $dbc->_db->quote($orbicon_x->ptr));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		// find address book
		$q = sprintf('	SELECT 		template, adrbks,
									redirect
						FROM 		'.TABLE_FORMS.'
						WHERE 		(permalink = %s) AND
									(language = %s)
						LIMIT 		1', $dbc->_db->quote($a['content']), $dbc->_db->quote($orbicon_x->ptr));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		$adrbks = explode('|', $a['adrbks']);

		$template = $a['template'];
		$redirect = $a['redirect'];

		// we'll need captcha
		include_once DOC_ROOT.'/orbicon/class/inc.captcha.php';

		// this shouldn't suppose to happen
		if(empty($template)) {
			if(isset($_POST['orbicon_registration'])) {
				$template = 'register';
			}
		}

		// contact form
		if($template == 'contact') {

			$msg = strip_tags($_POST['msg'], '<b><strong><u><i><font><em><span><br><p>');
			$name = $_POST['name'];
			$email = trim($_POST['email']);
			$available = $_POST['time'];
			$phone = $_POST['phone'];
			$mail = $_POST['mail'];
			$contact_back_email = ($_POST['contact_back_email'] == 'email') ? _L('email') . " ($email)<br />" : '';
			$contact_back_phone = ($_POST['contact_back_phone'] == 'phone') ? _L('phone') . " ($phone)<br />" : '';
			$contact_back_mail = ($_POST['contact_back_mail'] == 'mail') ? _L('mail') . " ($mail)<br />" : '';

			$mail_title = (isset($_POST['type'])) ? _L($_POST['type']) : _L('forms-press');

			$mail_body = '<b>'._L('forms-name').":</b> $name<br />";
			$mail_body .= '<b>'._L('forms-mail').":</b> $email<br />";
			$mail_body .= '<b>'._L('forms-msg').":</b> $msg<br />";
			$mail_body .= '<b>'._L('forms-avail').":</b> $available<br />";
			$mail_body .= '<b>'._L('forms-contact-opt').":</b><br />$contact_back_email $contact_back_phone $contact_back_mail";

			$mail_body .= '<b>'._L('forms-newsletter').':</b> ';
			if($_POST['newsletter'] == 1){
				$mail_body .= _L('forms-in-news').'<br />';
			}
			else {
				$mail_body .= _L('forms-out-news').'<br />';
			}

			if(empty($msg)) {
				$check_pass = false;
				$_SESSION['orbicon_infobox_msg'] = _L('no_msg').'.<br />';
			}

			if($_SESSION['site_settings']['use_captcha']) {
				if(!check_captcha($_POST['contact_squestion']) && (function_exists('imagecopyresampled'))) {
					$secure_question_pass = false;
					$_SESSION['orbicon_infobox_msg'] .= _L('wrong_sec_q').'.<br />';
				}
			}
			else {
				$secure_question_pass = true;
			}

			if($check_pass && $secure_question_pass) {
				$_SESSION['orbicon_infobox_msg'] = _L('msg_ok');
			}
			else {
				if($_SESSION['orbicon_infobox_msg']) {
					$_SESSION['orbicon_infobox_msg'] .= '<a href="'.$_SERVER['HTTP_REFERER'].'" onclick="javascript:window.history.back();return false;">'._L('go_back_try_again').'.</a>';
				}
			}
		}
		// register form
		else if($template == 'register') {

			$name = ($_POST['name']) . ' ' . trim($_POST['surname']);
			$email = trim($_POST['mail']);
			$office = ($_POST['office']);
			$addr = ($_POST['address']);
			$zip = ($_POST['zip']);
			$city = ($_POST['city']);
			$phone = ($_POST['phone']);
			$phone_a = ($_POST['phone_a']);
			$phone_b = ($_POST['phone_b']);
			$fax = ($_POST['fax']);
			$gsm = ($_POST['gsm']);
			$username = $_POST['username'];
			$password = $_POST['password'];
			$password_v = $_POST['password_v'];

			$mail_body = '<b>'._L('forms-name').":</b> $name <$email><br />";
			$mail_body .= '<b>'._L('forms-office').":</b> $office, $addr, $zip $city<br />";
			$mail_body .= '<b>'._L('forms-office-phone').":</b> $phone<br />";
			$mail_body .= '<b>'._L('forms-fax').":</b> $fax<br />";
			$mail_body .= '<b>'._L('forms-gsm').":</b> $gsm<br />";

			$mail_body .= '<b>'._L('forms-newsletter').':</b> ';
			if($_POST['newsletter']){
				$mail_body .= _L('forms-in-news').'<br />';
			}
			else {
				$mail_body .= _L('forms-out-news').'<br />';
			}

			$mail_title = _L('forms-new-reg');

			if(function_exists('imagecopyresampled') && $_SESSION['site_settings']['use_captcha']) {
				if(!check_captcha($_POST['contact_squestion'])) {
					$secure_question_pass = false;
					$_SESSION['orbicon_infobox_msg'] .= _L('wrong_sec_q').'.<br />';
				}
				else {
					$secure_question_pass = true;
				}
			}
			else {
				$secure_question_pass = true;
			}

			if(empty($username)) {
				$check_pass = false;
				$_SESSION['orbicon_infobox_msg'] .= _L('empty_username').'.<br />';
			}

			if(empty($password)) {
				$check_pass = false;
				$_SESSION['orbicon_infobox_msg'] .= _L('empty_password').'.<br />';
			}

			if($password != $password_v) {
				$check_pass = false;
				$_SESSION['orbicon_infobox_msg'] .= _L('pwd_mismatch').'.<br />';
			}

			if(strlen($username) < 3) {
				$check_pass = false;
				$_SESSION['orbicon_infobox_msg'] .= _L('short_username').'.<br />';
			}

			if(strlen($password) < 3) {
				$check_pass = false;
				$_SESSION['orbicon_infobox_msg'] .= _L('short_pwd').'.<br />';
			}

			if(strpos($password, $username) !== false) {
				$check_pass = false;
				$_SESSION['orbicon_infobox_msg'] .= _L('pwd_user_mismatch').'.<br />';
			}

			if(strpos($password, $email) !== false) {
				$check_pass = false;
				$_SESSION['orbicon_infobox_msg'] .= _L('pwd_user_mismatch').'.<br />';
			}

			if(empty($name)) {
				$check_pass = false;
				$_SESSION['orbicon_infobox_msg'] .= _L('name_empty').'.<br />';
			}

			// phone is required for estate module
			if(empty($phone) && $orbx_mod->validate_module('estate')) {
				$check_pass = false;
				$_SESSION['orbicon_infobox_msg'] .= _L('phone_empty').'.<br />';
			}

			// check for duplicate usernames
			$q_d = sprintf('	SELECT 		id
								FROM 		'.TABLE_REG_USERS.'
								WHERE 		(username = %s)
								LIMIT		1', $dbc->_db->quote($username));

			$r_d = $dbc->_db->query($q_d);
			$a_d = $dbc->_db->fetch_assoc($r_d);

			if(!empty($a_d['id'])) {
				$check_pass = false;
				$_SESSION['orbicon_infobox_msg'] .= _L('username_exists').'.<br />';
			}

			if($check_pass && $secure_question_pass) {
				$_SESSION['orbicon_infobox_msg'] = _L('msg_reg_ok');

				global $orbx_mod;
				if($orbx_mod->validate_module('peoplering')) {
					$query = '	INSERT INTO pring_contact (
															contact_name, contact_surname,
															contact_office, contact_position,
															contact_expertise, contact_address,
															contact_zip, contact_city,
															contact_phone, contact_fax,
															contact_gsm, contact_email,
															contact_subscription, contact_phone_a,
															contact_phone_b, registered
														)
								VALUES (
															' . $dbc->_db->quote($_POST['name']) . ', ' . $dbc->_db->quote($_POST['surname']) . ',
															' . $dbc->_db->quote($_POST['office']) . ', ' . $dbc->_db->quote($_POST['position']) . ',
															' . $dbc->_db->quote($_POST['expertise']) . ', ' . $dbc->_db->quote($_POST['address']) . ',
															' . $dbc->_db->quote($_POST['zip']) . ', ' . $dbc->_db->quote($_POST['city']) . ',
															' . $dbc->_db->quote($_POST['phone']) . ', ' . $dbc->_db->quote($_POST['fax']) . ',
															' . $dbc->_db->quote($_POST['gsm']) . ', ' . $dbc->_db->quote($email) . ',
															' . $dbc->_db->quote($_POST['newsletter']) . ', ' . $dbc->_db->quote($_POST['phone_a']) . ', ' . $dbc->_db->quote($_POST['phone_b']) . ',
															'.$dbc->_db->quote(time()).')';

				$dbc->_db->query($query);
				$last_active_id = $dbc->_db->insert_id();

				// * Insert initial values into cv table
				$cvname = $dbc->_db->quote($_POST['name'].' '.$_POST['surname']);

				$cv_init = sprintf('INSERT INTO 	pring_cvs
													(contact_id, cvname)
									VALUES 			(%s, %s)',
									$last_active_id, $cvname);

				$dbc->_db->query($cv_init);
				// * -------------------------

				// insert initial data into pring company table

				$company_init = sprintf('	INSERT INTO 	pring_company
															(contact, title,
															mb, url,
															phone, phone_a,
															phone_b)
											VALUES			(%s, %s,
															%s, %s,
															%s, %s,
															%s)',
									$last_active_id, $dbc->_db->quote($_POST['title_comp']),
									$dbc->_db->quote($_POST['mb']), $dbc->_db->quote($_POST['url']),
									$dbc->_db->quote($_POST['phone']), $dbc->_db->quote($_POST['phone_a']),
									$dbc->_db->quote($_POST['phone_b']));

				$dbc->_db->query($company_init);
				}

				$query = sprintf('	INSERT INTO 	'.TABLE_REG_USERS.'
													(username, pwd,
													pring_contact_id)
									VALUES 			(%s, PASSWORD(%s),
													%s)',
						$dbc->_db->quote($username), $dbc->_db->quote($password),
						$dbc->_db->quote($last_active_id));

				$dbc->_db->query($query);

				$reg_logo = '';
				if(is_file(DOC_ROOT . '/site/gfx/reg_logo.gif')) {
					$reg_logo = '<center><a href="'.ORBX_SITE_URL.'"><img src="'.ORBX_SITE_URL . '/site/gfx/reg_logo.gif" /></a></center>';
				}

				include_once DOC_ROOT . '/orbicon/3rdParty/phpmailer/class.phpmailer.php';

				$mail = new PHPMailer();

				if($_SESSION['site_settings']['smtp_server'] != '') {
					$mail->IsSMTP(); // telling the class to use SMTP
					$mail->Host = $_SESSION['site_settings']['smtp_server']; // SMTP server
					$mail->Port = $_SESSION['site_settings']['smtp_port'];
				}

				$mail->CharSet = 'UTF-8';
				$mail->From = utf8_html_entities(DOMAIN_EMAIL, true);
				$mail->FromName = utf8_html_entities(DOMAIN_OWNER, true);
				$mail->AddAddress($email);

				$mail->Subject = utf8_html_entities(DOMAIN . ' / ' . _L('forms-registration'), true);
				$mail->Body = '<b>'._L('forms-username')."</b>: $username<br /><b>"._L('forms-password').":</b> $password<br /><br /><i>".ORBX_SITE_URL."</i>$reg_logo";
				$mail->WordWrap = 50;
				$mail->IsHTML(true);

				if(!$mail->Send()) {

					$orbx_log->ewrite('PHPMailer failed, switching to mail', __LINE__, __FUNCTION__);
					mail($email, DOMAIN . ' / ' . _L('forms-registration'), '<b>'._L('forms-username')."</b>: $username<br /><b>"._L('forms-password').":</b> $password<br /><br /><i>".ORBX_SITE_URL."</i>$reg_logo", 'Content-Type: text/html; charset=UTF-8');
				}

				$mail = null;
			}
			else {
				if($_SESSION['orbicon_infobox_msg']) {
					$_SESSION['orbicon_infobox_msg'] .= '<a href="'.$_SERVER['HTTP_REFERER'].'" onclick="javascript:window.history.back();return false;">'._L('go_back_try_again').'.</a>';
				}
			}
		}
		// cv form
		else if($template == 'job') {

			$mail_body = '<b>'._L('forms-name').":</b> {$_POST['name']} {$_POST['surname']} <{$_POST['contactemail']}><br />";
			$mail_body .= '<b>'._L('forms-cv-title').":</b><br /> {$_POST['cvname']}<br />{$_POST['address']}, {$_POST['zip']} {$_POST['city']}";
			$mail_body .= '<b>'._L('forms-contact-mail').":</b> {$_POST['contactemail']}<br />";
			$mail_body .= '<b>'._L('forms-phone').":</b> {$_POST['contactphone']}<br />";
			$mail_body .= '<b>'._L('forms-address').":</b> {$_POST['address']}<br />";
			$mail_body .= '<b>'._L('forms-city').":</b> {$_POST['city']}<br />";
			$mail_body .= '<b>'._L('forms-zip').":</b> {$_POST['zip']}<br />";
			$mail_body .= '<b>'._L('forms-url').":</b> {$_POST['contactweb']}<br />";
			$mail_body .= '<b>'._L('forms-dob').":</b> {$_POST['yob']}<br />";
			$mail_body .= '<b>'._L('forms-county').":</b> {$_POST['county']}<br />";
			$mail_body .= '<b>'._L('forms-pob').":</b> {$_POST['placeofbirth']}<br />";
			$mail_body .= '<b>'._L('forms-cob').":</b> {$_POST['countryofbirth']}<br />";
			$mail_body .= '<b>'._L('forms-country').":</b> {$_POST['country']}<br />";
			$mail_body .= '<b>'._L('forms-fax').":</b> {$_POST['contactfax']}<br />";
			$mail_body .= '<b>'._L('forms-doe').":</b> {$_POST['doe']}<br />";
			$mail_body .= '<b>'._L('forms-jobs').":</b> {$_POST['pastjobs']}<br />";
			$mail_body .= '<b>'._L('forms-manager-skils').":</b> {$_POST['gotmanagerskills']}<br />";
			$mail_body .= '<b>'._L('forms-yoe').":</b> {$_POST['yoe']}<br />";
			$mail_body .= '<b>'._L('forms-eng').":</b> {$_POST['eng']}<br />";
			$mail_body .= '<b>'._L('forms-ger').":</b> {$_POST['ger']}<br />";
			$mail_body .= '<b>'._L('forms-fra').":</b> {$_POST['fra']}<br />";
			$mail_body .= '<b>'._L('forms-ita').":</b> {$_POST['ita']}<br />";
			$mail_body .= '<b>'._L('forms-rest').":</b> {$_POST['otheractive']}<br />";
			$mail_body .= '<b>'._L('forms-rest-pasive').":</b> {$_POST['otherpassive']}<br />";
			$mail_body .= '<b>'._L('forms-dl').":</b> {$_POST['dlic']}<br />";
			$mail_body .= '<b>'._L('forms-dl-extra').":</b> {$_POST['dlicmore']}<br />";
			$mail_body .= '<b>'._L('forms-complement').":</b> {$_POST['complementary']}<br />";
			$mail_body .= '<b>'._L('forms-capabil').":</b> {$_POST['capabilities']}<br />";
			$mail_body .= '<b>'._L('forms-achiev').":</b> {$_POST['achievements']}<br />";
			$mail_body .= '<b>'._L('forms-other').":</b> {$_POST['rest']}<br />";

			$mail_body .= '<b>'._L('forms-newsletter').':</b> ';
			if($_POST['newsletter'] == 1) {
				$mail_body .= _L('forms-in-news').'<br />';
				// store it in this variable name for later usage
				$email = trim($_POST['contactemail']);
			}
			else {
				$mail_body .= _L('forms-out-news').'<br />';
			}

			$mail_title = '[CV] ' . $_POST['name'] . ' ' . $_POST['surname'] . '<' . $_POST['cvname'] . '>';

			if(empty($_POST['cvcategory'])) {
				$check_pass = false;
				$_SESSION['orbicon_infobox_msg'] .= _L('cv_form_nocat').'.<br />';
			}

			if(empty($_POST['cvname'])) {
				$check_pass = false;
				$_SESSION['orbicon_infobox_msg'] .= _L('cv_form_nocat').'.<br />';
			}

			if(empty($_POST['name'])) {
				$check_pass = false;
				$_SESSION['orbicon_infobox_msg'] .= _L('cv_form_noname').'.<br />';
			}

			if(empty($_POST['surname'])) {
				$check_pass = false;
				$_SESSION['orbicon_infobox_msg'] .= _L('cv_form_nolast').'.<br />';
			}

			if(empty($_POST['contactemail'])) {
				$check_pass = false;
				$_SESSION['orbicon_infobox_msg'] .= _L('cv_form_noemail').'.<br />';
			}

			if(function_exists('imagecopyresampled') && $_SESSION['site_settings']['use_captcha']) {
				if(!check_captcha($_POST['job_squestion'])) {
					$secure_question_pass = false;
					$_SESSION['orbicon_infobox_msg'] .= _L('wrong_sec_q').'.<br />';
				}
				else {
					$secure_question_pass = true;
				}
			}
			else {
				$secure_question_pass = true;
			}

			if($check_pass && $secure_question_pass) {
				$_SESSION['orbicon_infobox_msg'] = _L('cv_form_ok');

				// Added username & password, by Alen 23.02.07

				$dbc->_db->query('
									INSERT INTO pring_contact (
															contact_name, contact_surname,
															contact_phone, contact_email,
															contact_address, contact_city,
															contact_zip, contact_url,
															contact_fax, contact_gsm,
															registered
														)
									VALUES
														(
															' . $dbc->_db->quote($_POST['name']) . ', ' . $dbc->_db->quote($_POST['surname']) . ',
															' . $dbc->_db->quote($_POST['contactphone']) . ', ' . $dbc->_db->quote($_POST['contactemail']) . ',
															' . $dbc->_db->quote($_POST['address']) . ', ' . $dbc->_db->quote($_POST['city']) . ',
															' . $dbc->_db->quote($_POST['zip']) . ', ' . $dbc->_db->quote($_POST['contactweb']) . ',
															' . $dbc->_db->quote($_POST['contactfax']) . ', ' . $dbc->_db->quote($_POST['contactgsm']) . ',
															' . $dbc->_db->quote(time()) . '
														)

								');

				$last_active_id = $dbc->_db->insert_id();


				$query = '			INSERT INTO '.TABLE_REG_USERS.' (
															username, pwd,
															pring_contact_id
														)
									VALUES
														(
															' . $dbc->_db->quote($_POST['username']) . ', PASSWORD(' . $dbc->_db->quote($_POST['password']) . '),
															' . $dbc->_db->quote($last_active_id) . '
														)

								';

				$dbc->_db->query($query);

				$q = '
									INSERT INTO pring_cvs (
														cvname, county,
														placeofbirth, countryofbirth,
														country, contactfax,
														doe, education,
														pastjobs, gotmanagerskills,
														yoe, eng,
														ger, fre,
														ita, otheractive,
														otherpassive, dlic,
														dlicmore, complementary,
														capabilities, achievements,
														rest, contact_id
													)
									VALUES
													(
														' . $dbc->_db->quote($_POST['cv_title']) . ', ' . $dbc->_db->quote(serialize($_POST['county'])) . ',
														' . $dbc->_db->quote($_POST['placeofbirth']) . ', ' . $dbc->_db->quote($_POST['countryofbirth']) . ',
														' . $dbc->_db->quote($_POST['country']) . ', ' . $dbc->_db->quote($_POST['contactfax']) . ',
														' . $dbc->_db->quote($_POST['doe']) . ', ' . $dbc->_db->quote($_POST['education']) . ',
														' . $dbc->_db->quote($_POST['pastjobs']) . ', ' . $dbc->_db->quote($_POST['gotmanagerskills']) . ',
														' . $dbc->_db->quote($_POST['yoe']) . ', ' . $dbc->_db->quote($_POST['eng']) . ',
														' . $dbc->_db->quote($_POST['ger']) . ', ' . $dbc->_db->quote($_POST['fra']) . ',
														' . $dbc->_db->quote($_POST['ita']) . ', ' . $dbc->_db->quote($_POST['otheractive']) . ',
														' . $dbc->_db->quote($_POST['otherpassive']) . ', ' . $dbc->_db->quote($_POST['dlic']) . ',
														' . $dbc->_db->quote($_POST['dlicmore']) . ', ' . $dbc->_db->quote($_POST['complementary']) . ',
														' . $dbc->_db->quote($_POST['capabilities']) . ', ' . $dbc->_db->quote($_POST['achievements']) . ',
														' . $dbc->_db->quote($_POST['rest']) . ', ' . $dbc->_db->quote($last_active_id) . '
													)
								';

				$dbc->_db->query($q);
			}
			else {
				if($_SESSION['orbicon_infobox_msg']) {
					$_SESSION['orbicon_infobox_msg'] .= '<a href="'.$_SERVER['HTTP_REFERER'].'" onclick="javascript:window.history.back();return false;">'._L('go_back_try_again').'.</a>';
				}
			}
		}

		// add if we want a newsletter
		if($_POST['newsletter'] == 1) {
			if(is_email($email)) {
				$q = sprintf('	SELECT 		id
								FROM 		'.TABLE_EMAILS.'
								WHERE 		(email = %s)
								LIMIT 		1', $dbc->_db->quote($email));
				$r = $dbc->_db->query($q);
				$a = $dbc->_db->fetch_assoc($r);
				// email doesn't exist, add it to db
				if(empty($a['id'])) {
					$dbc->_db->query(sprintf('INSERT INTO '.TABLE_EMAILS.' (email) VALUES (%s)', $dbc->_db->quote($email)));
				}
			}
		}

		if($secure_question_pass && $check_pass) {
			if(!empty($adrbks)) {

				foreach($adrbks as $value) {
					// get emails
					$q = sprintf('	SELECT 		column_list
									FROM 		'.TABLE_ADRBKS.'
									WHERE 		(permalink = %s)
									LIMIT 		1',
									$dbc->_db->quote($value));

					$r = $dbc->_db->query($q);
					$a = $dbc->_db->fetch_assoc($r);

					$emails = explode('|', $a['column_list']);

					if(!defined('XML_HTMLSAX3')) {
						define('XML_HTMLSAX3', DOC_ROOT.'/orbicon/3rdParty/safehtml/classes/');
					}
					include_once XML_HTMLSAX3.'safehtml.php';
					$safehtml =& new safehtml();

					$mail_body = $safehtml->parse($mail_body);
					unset($safehtml);

					include_once DOC_ROOT . '/orbicon/3rdParty/phpmailer/class.phpmailer.php';

					$mail = new PHPMailer();

					if($_SESSION['site_settings']['smtp_server'] != '') {
						$mail->IsSMTP(); // telling the class to use SMTP
						$mail->Host = $_SESSION['site_settings']['smtp_server']; // SMTP server
						$mail->Port = $_SESSION['site_settings']['smtp_port'];
					}

					$mail->CharSet = 'UTF-8';
					$mail->From = utf8_html_entities(DOMAIN_EMAIL, true);
					$mail->FromName = utf8_html_entities(DOMAIN_OWNER, true);
					foreach($emails as $email) {
						$mail->AddAddress($email);
					}

					$mail->Subject = utf8_html_entities(DOMAIN . ' / ' . $mail_title, true);
					$mail->Body = $mail_body;
					$mail->WordWrap = 50;
					$mail->IsHTML(true);

					if(!$mail->Send()) {
						$orbx_log->ewrite('PHPMailer failed, switching to mail', __LINE__, __FUNCTION__);
						foreach($emails as $email) {
							$email = trim($email);
							mail($email, DOMAIN . ' / ' . $mail_title, $mail_body, 'Content-Type: text/html; charset=UTF-8');
						}
					}

					$mail = null;
				}
			}

			if($redirect != '') {
				redirect($redirect);
			}
		}
	}

	// Added by Alen Novakovic, 13/11/06
	// Function set_submited_form() set session variable
	// of submited form
	function set_submited_form()
	{
		$_SESSION['validsubmit'] = true;
		return;
	}

	// Now we create function that checks if the form is
	// already submited
	function get_submited_form()
	{
		if(!isset($_SESSION['validsubmit']) || $_SESSION['validsubmit'] === true){
			// form is submited or invalid
			return true;
		} else {
			return false;
		}
	}

	function get_pring_db_table($table, $return_arr = false, $order_by = 'title', $default = '', $ignore_lng = false)
	{
		global $dbc, $orbicon_x;

		if(!$ignore_lng) {
			// pring countries table is language ignorant for now
			$language_sql = (($table == 'pring_countries') || ($table == 'pring_towns')) ? '' : 'WHERE (lang = '. $dbc->_db->quote($orbicon_x->ptr).')';
		}

		if($table == 'pring_towns') {
			$q = '	SELECT 		id, town AS title
					FROM 		'.$table.'
					'.$language_sql.'
					ORDER BY	town';
		}
		else {
			$q = '	SELECT 		id, title
					FROM 		'.$table.'
					'.$language_sql.'
					ORDER BY	' . $order_by;
		}

		if($return_arr) {
			$menu = array();

			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);

			while($a) {
				$menu[$a['id']] = $a['title'];
				$a = $dbc->_db->fetch_assoc($r);
			}
		}
		else {
			$menu = '';
			$a = $dbc->_db->get_cache($q);
			if($a !== null) {
				return $a;
			}

			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);

			while($a) {
				$selected = ($default == $a['id']) ? ' selected="selected"' : '';
				$menu .= '<option value="' . $a['id'] . '" '.$selected.'>' . $a['title'] . '</option>';
				$a = $dbc->_db->fetch_assoc($r);
			}

			$dbc->_db->put_cache($menu, $q);
		}

		return $menu;
	}
}

?>