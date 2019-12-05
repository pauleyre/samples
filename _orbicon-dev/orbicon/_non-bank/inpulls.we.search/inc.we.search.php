<?php

/**
 * We search main include
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package OrbiconMOD
 * @subpackage InpullsWeSearch
 * @version 1.0
 * @link http://www.inpulls.com
 * @license http://
 * @since 2007-11-07
 * @todo Translation
 */

/**
 * SQL table name
 *
 */
define('TABLE_INPULLS_WE_SEARCH', 'orbx_mod_inpulls_we_search');

/**
 * Add new we search entry
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @return int
 */
function new_inpulls_we_search()
{
	global $dbc;

	$q = sprintf('
				INSERT INTO 	'.TABLE_INPULLS_WE_SEARCH.'
								(name, email,
								person_looking_for, sex_group,
								years_from, years_to,
								county, horoscope,
								town, pics_only)
				VALUES			(%s, %s,
								%s, %s,
								%s, %s,
								%s, %s,
								%s, %s)',
	$dbc->_db->quote($_POST['ko_ime']), $dbc->_db->quote($_POST['ko_email']),
	$dbc->_db->quote($_POST['ko_ovdje_trazi']), $dbc->_db->quote($_POST['ko_sex_grupa']),
	$dbc->_db->quote($_POST['ko_god_od']), $dbc->_db->quote($_POST['ko_god_do']),
	$dbc->_db->quote($_POST['ko_regija']), $dbc->_db->quote($_POST['ko_horoskop']),
	$dbc->_db->quote($_POST['ko_naselje']), $dbc->_db->quote($_POST['ko_sa_slikom']));

	$dbc->_db->query($q);
	$new_id = $dbc->_db->insert_id();

	return $new_id;
}

/**
 * Add new we search entry
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @return int
 */
function scan_inpulls_we_search($person_looking_for, $sex_group, $years, $horoscope, $county, $town, $picture, $profile_url)
{
	global $dbc;

	$q = '	SELECT 	id, email
			FROM 	'.TABLE_INPULLS_WE_SEARCH;

	if($person_looking_for) {
		$q .= sprintf(' WHERE (person_looking_for = %s) ', $dbc->_db->quote($person_looking_for));
	}

	if($sex_group) {
		$q .= sprintf(' AND (sex_group = %s) ', $dbc->_db->quote($sex_group));
	}

	if($years) {
		$q .= sprintf(' AND
		(
			(
				(years_from >= %s) AND (years_to <= %s)
			)
			OR
			(
				(years_from = 0) AND (years_to = 0)
			)
		) ', $dbc->_db->quote($years), $dbc->_db->quote($years));
	}

	if($horoscope) {
		$q .= sprintf(' AND (horoscope = %s) ', $dbc->_db->quote($horoscope));
	}

	if($county) {
		$q .= sprintf(' AND (county = %s) ', $dbc->_db->quote($county));
	}

	if($town) {
		$q .= sprintf(' AND ((town = %s) OR (town = \'\')) ', $dbc->_db->quote($town));
	}

	if($picture) {
		$q .= ' AND (pics_only = 1) ';
	}

	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_assoc($r);

	while ($a) {
		send_inpulls_we_search_mail($profile_url, $a['id'], $a['email']);
		$a = $dbc->_db->fetch_assoc($r);
	}
}

function send_inpulls_we_search_mail($profile_url, $user_id, $email)
{
	global $orbicon_x;
	$title = 'inpulls.com - Kupidova istraga';
	$mail_body = 'Poštovani,<br>
<br>
ovo je poruka je poslana sa web-stranice '.ORBX_SITE_URL.' na temelju vašeg upita preko "Kupidova istraga" servisa.<br>
<br>
Vaš upit je pronašao sljedećeg novog korisnika:<br>
<a href="'.$profile_url.'">'.$profile_url.'</a><br>
<br>
Kliknite na gore naveden link ili ga kopirajte u svoj Internet preglednik (Explorer, Firefox..).<br>
<br>
Ukoliko više ne želite korisiti uslugu "Kupidova istraga", kliknite <a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.we.search&amp;unsub='.$user_id.'">ovdje</a><br>
Ako ste se više puta prijavili za uslugu "Kupidova istraga", morat ćete zasebno odjavljivati svaku ili nas kontaktirajte.<br>
<br>
<strong>inpulls.com</strong><br>
<br>
<img src="'.ORBX_SITE_URL.'/site/gfx/reg_logo.gif" alt="'.ORBX_SITE_URL.'" title="'.ORBX_SITE_URL.'" />';

	$to = trim($email);
	$from = trim(DOMAIN_EMAIL);

	include_once DOC_ROOT . '/orbicon/3rdParty/phpmailer/class.phpmailer.php';

	$mail = new PHPMailer();

	if($_SESSION['site_settings']['smtp_server'] != '') {
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->Host = $_SESSION['site_settings']['smtp_server']; // SMTP server
		$mail->Port = $_SESSION['site_settings']['smtp_port'];
	}

	$mail->CharSet = 'UTF-8';
	$mail->From = $from;
	$mail->FromName = utf8_html_entities(DOMAIN_NAME, true);

	$mail->AddAddress($to);

	$mail->Subject = utf8_html_entities($title, true);
	$mail->Body = $mail_body;
	$mail->WordWrap = 50;
	$mail->IsHTML(true);

	if(!$mail->Send()) {
		mail($to, $title, $mail_body, 'Content-Type: text/html; charset=UTF-8');
	}

	$mail = null;
}

/**
 * Delete we search
 *
 * @param int $id
 * @return int
 */
function delete_inpulls_we_search($id)
{
	global $dbc;

	$dbc->_db->query(sprintf('	DELETE FROM 	' . TABLE_INPULLS_WE_SEARCH . '
								WHERE 			(id=%s)
								LIMIT 			1', $dbc->_db->quote($id)));
	return $dbc->_db->affected_rows();
}

?>