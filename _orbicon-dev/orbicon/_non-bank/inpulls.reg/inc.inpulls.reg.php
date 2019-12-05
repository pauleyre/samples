<?php

function inpulls_reg()
{
	global $dbc, $orbx_log, $orbx_mod, $orbicon_x;
	$email = trim($_POST['mail']);
	$addr = ($_POST['address']);
	$city = ($_POST['city']);
	$username = $_POST['username'];
	$password = $_POST['password'];
	$password_v = $_POST['password_v'];
	$dob = strtotime($_POST['dob_m']. '/' . $_POST['dob_d'] . '/' . $_POST['dob_y']);
	$doe = $_POST['doe'];
	$expertise = $_POST['expertise'];
	$sex = $_POST['sex'];
	$im_here_for = $_POST['im_here_for'];
	$currently_im = $_POST['currently_im'];
	$sex_group = $_POST['sex_group'];
	$more_info = $_POST['more_info'];
	$url = $_POST['url'];
	$horoscope = $_POST['horoscope'];
	$birthday = (!empty($dob)) ? date('dm', $dob) : '';
	$country = $_POST['country'];
	$county = $_POST['county'];
	$city_text = $_POST['city_text'];

	$check_pass = true;

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

	if(empty($dob)) {
		$check_pass = false;
		$_SESSION['orbicon_infobox_msg'] .= 'Unesite datum rođenja.<br />';
	}

	/*$whitelist = array();

	if(((strpos($email, '@hotmail') !== false) || (strpos($email, '@yahoo') !== false)) && !in_array($email, $whitelist)) {
		$check_pass = false;
		$_SESSION['orbicon_infobox_msg'] .= 'Žao nam je ali smo zbog mnogobrojnih spammera morali otežati registraciju korisnicima koji koriste <strong>Yahoo</strong> ili <strong>Hotmail</strong> adrese.<br/>Molimo Vas unesite neki drugi e-mail ili ako ga nemate, <a href="./?hr=kontakt">javite nam se ovdje</a> i navedite za koju Yahoo ili Hotmail adresu želite dopuštenje za registraciju.<br />';
	}*/

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

	if($check_pass) {
		$_SESSION['orbicon_infobox_msg'] = _L('msg_reg_ok');

		global $orbx_mod;
		if($orbx_mod->validate_module('peoplering')) {
			$query = '	INSERT INTO pring_contact (
						contact_name, contact_surname,
						contact_expertise, contact_address,
						contact_city, contact_dob,
						contact_email, contact_url,
						contact_sex, contact_region,
						contact_country, contact_town_text,
						registered)
						VALUES (
	' . $dbc->_db->quote($_POST['name']) . ', ' . $dbc->_db->quote($_POST['surname']) . ',
	' . $dbc->_db->quote($expertise) . ', ' . $dbc->_db->quote($addr) . ',
	' . $dbc->_db->quote($city) . ', ' . $dbc->_db->quote($dob) . ',
	' . $dbc->_db->quote($email) . ', ' . $dbc->_db->quote($url) . ',
	' . $dbc->_db->quote($sex) . ', ' . $dbc->_db->quote($county) . ',
	' . $dbc->_db->quote($country) . ', ' . $dbc->_db->quote($city_text) . ',
	' . $dbc->_db->quote(time()) . ')';

			$dbc->_db->query($query);
			$last_active_id = $dbc->_db->insert_id();

			// * Insert initial values into cv table
			$cvname = $dbc->_db->quote($_POST['name'] . ' ' . $_POST['surname']);

			$cv_init = sprintf('INSERT INTO 	pring_cvs
												(contact_id, cvname,
												doe)
								VALUES			(%s, %s,
												%s)',
								$dbc->_db->quote($last_active_id), $cvname,
								$doe);

			$dbc->_db->query($cv_init);

			// * -------------------------

			// insert initial data into pring company table

			$company_init = sprintf('	INSERT INTO 	pring_company
														(contact)
										VALUES 			(%s)',
								$dbc->_db->quote($last_active_id));

			$dbc->_db->query($company_init);
		}

		include_once DOC_ROOT . '/orbicon/3rdParty/phpmailer/class.phpmailer.php';

		// inpulls fields start
		inpulls_reg_iprofile_insert_2($last_active_id, $im_here_for, $currently_im, $sex_group, $more_info, $_POST['inpulls_mail'], $horoscope, $birthday);

		$insert_ok = inpulls_reg_scan_success($last_active_id);

		if(!$insert_ok) {
			inpulls_reg_iprofile_insert_2($last_active_id, $im_here_for, $currently_im, $sex_group, $more_info, $_POST['inpulls_mail'], $horoscope, $birthday);

			$insert_ok = inpulls_reg_scan_success($last_active_id);

			if(!$insert_ok) {

				$mailerr = new PHPMailer();

				if($_SESSION['site_settings']['smtp_server'] != '') {
					$mailerr->IsSMTP(); // telling the class to use SMTP
					$mailerr->Host = $_SESSION['site_settings']['smtp_server']; // SMTP server
					$mailerr->Port = $_SESSION['site_settings']['smtp_port'];
				}

				$mailerr->CharSet = 'UTF-8';
				$mailerr->From = utf8_html_entities(DOMAIN_EMAIL, true);
				$mailerr->FromName = utf8_html_entities(DOMAIN_OWNER, true);
				$mailerr->AddAddress('pavle.gardijan@gmail.com');

				$mailerr->Subject = 'reg.error';
				$mailerr->Body = 'ID:'.$last_active_id;
				$mailerr->WordWrap = 50;
				$mailerr->IsHTML(true);

				if(!$mailerr->Send()) {
					mail('pavle.gardijan@gmail.com', 'reg.error', 'ID:'.$last_active_id, 'Content-Type: text/html; charset=UTF-8');
				}

				$mailerr = null;

			}
		}

		// inpulls fields end

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
			$reg_logo = '<center><a href="'.ORBX_SITE_URL.'" title="'.ORBX_SITE_URL.'"><img alt="'.ORBX_SITE_URL.'" title="'.ORBX_SITE_URL.'" src="'.ORBX_SITE_URL . '/site/gfx/reg_logo.gif" /></a></center>';
		}

		$mail = new PHPMailer();

		$subject = utf8_html_entities('**' . DOMAIN . ' / ' . _L('forms-registration').'**', true);
		$body = 'Dobro došli na INPULLS!<br>
Na INPULLSU možete:<br><br>
* <a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering">Ispuniti svoj profil</a><br>
* <a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=mail">Dopisivati se</a><br>
* <a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=gallery">Objaviti slike</a><br>
* <a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=gallery">Objaviti video</a><br>
* <a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.groups">Osnovati grupu</a><br>
* <a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.forum">Pisati na forumu</a><br>
* Izbaciti nekoga sa stranice<br>
* itd.<br><br>

Uživajte i ako zapnete negdje obratite se našem ljubaznom osoblju za <a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=kontakt">pomoć</a><br><br>

Ovo su vaši podaci za prijavu:<br>
***********************<br>
<b>'._L('forms-username')."</b>: $username<br>
<b>"._L('forms-password').":</b> $password<br>
***********************<br>
<br>
--<br>
<a href=\"".ORBX_SITE_URL."\">INPULLS, za mlade od mladih</a><br>
$reg_logo";

		if($_SESSION['site_settings']['smtp_server'] != '') {
			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->Host = $_SESSION['site_settings']['smtp_server']; // SMTP server
			$mail->Port = $_SESSION['site_settings']['smtp_port'];
		}

		$mail->CharSet = 'UTF-8';
		$mail->From = utf8_html_entities(DOMAIN_EMAIL, true);
		$mail->FromName = utf8_html_entities(DOMAIN_OWNER, true);
		$mail->AddAddress($email);

		$mail->Subject = $subject;
		$mail->Body = $body;
		$mail->WordWrap = 50;
		$mail->IsHTML(true);

		if(!$mail->Send()) {
			$orbx_log->ewrite('PHPMailer failed, switching to mail', __LINE__, __FUNCTION__);
			mail($email, $subject, $body, 'Content-Type: text/html; charset=UTF-8');
		}

		$mail = null;

		global $orbx_mod;
		if($orbx_mod->validate_module('inpulls.we.search')) {

			require_once DOC_ROOT . '/orbicon/modules/inpulls.we.search/inc.we.search.php';

			$pic = (is_file($_FILES['picture']['tmp_name'])) ? true : false;
			$url = ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.peoplering&amp;sp=user&amp;user=' . $username;

			scan_inpulls_we_search($im_here_for, $sex_group, get_age($dob), $horoscope, $county, $city, $pic, $url);
		}

		// upload picture last so it doesn't interfere with more important procedures above
		if($orbx_mod->validate_module('peoplering')) {
			// picture
			if(validate_upload($_FILES['picture']['tmp_name'], $_FILES['picture']['name'], $_FILES['picture']['size'], $_FILES['picture']['error'])) {

				require_once DOC_ROOT . '/orbicon/venus/class.venus.php';
				$venus = new Venus();
				$file = $venus->_insert_image_to_db($_FILES['picture']['name'], $_FILES['picture']['tmp_name'], 'pring_avatar');
				inpulls_reg_img_size_fix($file);
				$venus = null;

				$sql = sprintf('
							UPDATE	pring_contact
							SET		picture = %s
							WHERE	(id = %s)',
							$dbc->_db->quote($file),
							$dbc->_db->quote($last_active_id));

				$dbc->_db->query($sql);
			}
		}
	}
	else {
		if($_SESSION['orbicon_infobox_msg']) {
			$_SESSION['orbicon_infobox_msg'] .= '<a href="'.$_SERVER['HTTP_REFERER'].'" onclick="javascript:window.history.back();return false;">'._L('go_back_try_again').'.</a>';
		}
	}
}

/**
 * Resize image if larger than 200Kb
 *
 * @param string $file
 * @param object $venus
 * @access private
 */
function inpulls_reg_img_size_fix($file)
{
	$file = DOC_ROOT . '/site/venus/' . $file;
	$w = getimagesize($file);
	$w = $w[0];

	if($w > 200) {
		exec('mogrify -resize 200x ' . $file);
		update_sync_cache_list($file);
	}
}

/**
 * Enter description here...
 *
 * @param unknown_type $last_active_id
 * @param unknown_type $im_here_for
 * @param unknown_type $currently_im
 * @param unknown_type $sex_group
 * @param unknown_type $more_info
 * @param unknown_type $inpulls_mail
 * @param unknown_type $horoscope
 * @param unknown_type $birthday
 */
function inpulls_reg_iprofile_insert_2($last_active_id, $im_here_for, $currently_im, $sex_group, $more_info, $inpulls_mail, $horoscope, $birthday)
{
	global $dbc, $orbx_log;

	$id = inpulls_reg_iprofile_insert($last_active_id);

	$q = sprintf('	UPDATE 		'.TABLE_INPULLS_PROFILE.'
					SET			im_here_for = %s,
								currently_im = %s, sex_group  = %s,
								more_info = %s, registered  = UNIX_TIMESTAMP(),
								inpulls_mail = %s, horoscope = %s,
								birthday = %s
					WHERE		(id=%s)
					LIMIT		1',
						$dbc->_db->quote($im_here_for),
						$dbc->_db->quote($currently_im), $dbc->_db->quote($sex_group),
						$dbc->_db->quote($more_info),
						$dbc->_db->quote($inpulls_mail), $dbc->_db->quote($horoscope),
						$dbc->_db->quote($birthday), $dbc->_db->quote($id));

	$dbc->_db->query($q);

	$orbx_log->ewrite($q);
}

?>