<?php

/**
 * We search main include
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package OrbiconMOD
 * @subpackage EstateWeSearch
 * @version 1.0
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-11-07
 * @todo Translation
 */

/**
 * SQL table name
 *
 */
define('TABLE_ESTATE_WE_SEARCH', 'orbx_mod_estate_we_search');

/**
 * Add new we search entry
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @return int
 */
function new_we_search()
{
	global $dbc, $orbx_mod;

	$q = sprintf('
				INSERT INTO 	'.TABLE_ESTATE_WE_SEARCH.'
								(name, email,
								category, ad_type,
								price_from, price_to,
								county,
								msquare_from, msquare_to,
								town, pics_only)
				VALUES			(%s, %s,
								%s, %s,
								%s, %s,
								%s,
								%s, %s,
								%s, %s)',
	$dbc->_db->quote($_POST['ko_ime']), $dbc->_db->quote($_POST['ko_email']),
	$dbc->_db->quote($_POST['ko_kategorija']), $dbc->_db->quote($_POST['ko_ponuda']),
	$dbc->_db->quote($_POST['ko_cijena_od']), $dbc->_db->quote($_POST['ko_cijena_do']),
	$dbc->_db->quote($_POST['ko_regija']),
	$dbc->_db->quote($_POST['ko_povrsina_od']), $dbc->_db->quote($_POST['ko_povrsina_do']),
	$dbc->_db->quote($_POST['ko_naselje']), $dbc->_db->quote($_POST['ko_sa_slikom']));

	$dbc->_db->query($q);
	$new_id = $dbc->_db->insert_id();

	if($_POST['agencies'] && $orbx_mod->validate_module('address-book')) {
		include_once DOC_ROOT . '/orbicon/modules/address-book/class.addrbk.php';

		include_once DOC_ROOT.'/orbicon/modules/forms/class.form.php';
		$form = new Form;
		$counties = $form->get_pring_db_table('pring_counties', true, 'title', '', true);
		$towns = $form->get_pring_db_table('pring_towns', true);
		$form = null;

		$agency_name = get_permalink($counties[$_POST['ko_regija']]);

		$adrbk = new Address_Book();
		$mails = $adrbk->load_address_book_emails($agency_name);
		$adrbk = null;

		foreach ($mails as $mail) {
			//send_we_search_agency_mail($mail, $counties, $towns);
			
			send_we_search_agency_mail($mail, $counties, $towns, $_POST['ko_ime'], $_POST['ko_phone'], $_POST['ko_email'], $_POST['ko_kategorija'], $_POST['ko_ponuda'], $_POST['ko_regija'], $_POST['ko_naselje'], $_POST['ko_cijena_od'], $_POST['ko_cijena_do'], $_POST['ko_povrsina_od'], $_POST['ko_povrsina_do']);
			
			
		}
	}

	
	$mail_body = '<p>Zahvaljujemo Vam na povjerenju koje ste nam ukazali  Vašim zahtjevom za opcijom - Mi tražimo za Vas!</p>
<div>Mnogo posjetitelja u potragu za nekretninom upravo počinju sa našim portalom <a href="http://www.foto-nekretnine.hr/" target="_blank">www.foto-nekretnine.hr</a>, opcijom - Mi tražimo za Vas!.<br />
Privucite veću pažnju agencija, jednostavnim klikom na opciju - Proslijedi upit agencijama.<br />
Omogućujete da Vam se za pomoć obrate najrelevantnije agencije za nekretnine sa područja na kojem tragate za nekretninom.</div><br />
<div>Neka traganje za nekretninom postane zadovoljstvo, a ne noćna mora.</div>
<br>
<b>Vaš:</b><br>
<img src="'.ORBX_SITE_URL.'/site/venus/20071025-logo_fn-gif-bdad.gif" />';
	
	$to = trim($_POST['ko_email']);
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

	$mail->Subject = utf8_html_entities('Mi tražimo za Vas', true);
	$mail->Body = $mail_body;
	$mail->WordWrap = 50;
	$mail->IsHTML(true);

	if(!$mail->Send()) {
		mail($to, 'Mi tražimo za Vas', $mail_body, 'Content-Type: text/html; charset=UTF-8');
	}

	$mail = null;
	
	
	return $new_id;
}

/**
 * do a scan
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @return int
 */
function scan_we_search($category, $ad_type, $price, $msquare, $county, $town, $picture, $ad_url)
{
	global $dbc;

	$q = '	SELECT 	id, email
			FROM 	'.TABLE_ESTATE_WE_SEARCH;

	if($category != '') {
		$q .= sprintf(' WHERE (category = %s) ', $dbc->_db->quote($category));
	}

	if($ad_type != '') {
		$q .= sprintf(' AND (ad_type = %s) ', $dbc->_db->quote($ad_type));
	}

	/*if($price != '') {
		$q .= sprintf(' AND (((price_from >= %s) AND (price_to <= %s)) OR ((price_from = 0.00) AND (price_to = 0.00))) ', $dbc->_db->quote($price), $dbc->_db->quote($price));
	}

	if($msquare != '') {
		$q .= sprintf(' AND (((msquare_from >= %s) AND (msquare_to <= %s)) OR ((msquare_from = 0.00) AND (msquare_to = 0.00))) ', $dbc->_db->quote($msquare), $dbc->_db->quote($msquare));
	}*/

	if($county != '') {
		$q .= sprintf(' AND (county = %s) ', $dbc->_db->quote($county));
	}

	if($town != '') {
		$q .= sprintf(' AND ((town = %s) OR (town = \'\')) ', $dbc->_db->quote($town));
	}

	if($picture) {
		$q .= ' AND (pics_only = 1) ';
	}

	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_assoc($r);

	while ($a) {
		send_we_search_mail($ad_url, $a['id'], $a['email']);
		$a = $dbc->_db->fetch_assoc($r);
	}
}

function send_we_search_mail($ad_url, $user_id, $email)
{
	global $orbicon_x;
	$title = 'Foto Nekretnine - ' . _L('e.wesearch');
	$mail_body = _L('e.regards') . ',<br>
<br>'.sprintf(_L('e.msgsentws'), ORBX_SITE_URL).'.<br>
<br>
'._L('e.wsfoundad').':<br>
<a href="'.$ad_url.'">'.$ad_url.'</a><br>
<br>
'._L('e.clicklink').'.<br>
<br>'.sprintf(_L('e.wsunsubmsg'), '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.estate.we.search&amp;unsub='.$user_id.'">').'</a><br>
<br>
<strong>Foto Nekretnine</strong><br>
<br>
<img src="'.ORBX_SITE_URL.'/site/venus/20071025-logo_fn-gif-bdad.gif" />';

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
function delete_estate_we_search($id)
{
	global $dbc;

	$dbc->_db->query(sprintf('	DELETE FROM 	' . TABLE_ESTATE_WE_SEARCH . '
								WHERE 			(id=%s)
								LIMIT 			1', $dbc->_db->quote($id)));
	return $dbc->_db->affected_rows();
}

function send_we_search_agency_mail($email, $counties, $towns, $name, $phone, $mail, $category, $adtype, $reqion, $place, $price_from, $price_to, $msq_from, $msq_to)
{
	global $orbicon_x, $estate_type, $estate_ad_type;

	$to = trim($email);
	$from = 'tomislav.turudic@foto-nekretnine.hr';
	$title = 'Upit sa Foto Nekretnine';

	$mail_body = _L('e.regards') . ',<br>
<br>'._L('e.weagenctxt').'<br>
<br>
'._L('e.namesurname').': '.$name.'<br>
'._L('e.phone').': '.$phone.'<br>
'._L('e.email').': '.$mail.'<br>
---------------------------------------------------------<br>
'._L('e.type').': '.$estate_type[$category].'<br>
'._L('e.wsadtype').': '.$estate_ad_type[$adtype].'<br>
'._L('e.region').': '.$counties[$reqion].'<br>
'._L('e.place').': '.$towns[$place].'<br>
'._L('e.price').': '.$price_from.' - '.$price_to.'<br>
'._L('e.msquare').': '.$msq_from.' - '.$msq_to.'<br>
<br>
<strong>Vaš FOTO NEKRETNINE oglasnik</strong><br>
<br>
<a href="'.ORBX_SITE_URL.'"><img src="'.ORBX_SITE_URL.'/site/venus/20071025-logo_fn-gif-bdad.gif" /></a><br>
<br><br>
Tomislav Turudic<br>
III Vrbik 9,<br>
Tel: 01/ 619 82 36<br>
tomislav.turudic@foto-nekretnine.hr<br>
www.foto-nekretnine.hr<br>
';

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

?>