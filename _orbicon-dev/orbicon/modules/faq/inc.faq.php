<?php

define('TABLE_FAQ_CATEGORY', 'orbx_mod_faq_category');
define('TABLE_FAQ_QUESTION', 'orbx_mod_faq_question');

function faq_get_all_categories($result = false)
{
	global $orbicon_x, $dbc;
	// * retrieves complete list of active categories
	$sql = 'SELECT		*
			FROM		'.TABLE_FAQ_CATEGORY.'
			WHERE		(lang = %s)
			ORDER BY 	title';

	$res = sql_res($sql, $orbicon_x->ptr);

	if($result) {
		return $res;
	}

	$cat = '';
	$a = $dbc->_db->fetch_assoc($res);

	while($a) {
		// ('.$a['total_qs'].')
		$cat .= '<dd><a href="./?'.$a['permalink'].'&amp;'.$orbicon_x->ptr.'=mod.faq&amp;cid='.$a['id'].'">'.$a['title'].'</a></dd>';
		$a = $dbc->_db->fetch_assoc($res);
	}

	return $cat;
}

function faq_last_five()
{
	global $orbicon_x, $dbc;

	$cat_res = faq_get_all_categories(true);
	$str = '';
	$a_cat = $dbc->_db->fetch_assoc($cat_res);

	while($a_cat) {

		$str .= '<dl class="ic_list">
		<dt><strong>'.$a_cat['title'].'</strong></dt>';

		$sql_q = '	SELECT		permalink, id, title
					FROM		'.TABLE_FAQ_QUESTION.'
					WHERE		(lang = %s) AND
								(category = %s) AND
								(live = 1)
					ORDER BY 	live_date DESC
					LIMIT		5';

		$res_q = sql_res($sql_q, array($orbicon_x->ptr, $a_cat['id']));
		$a_q = $dbc->_db->fetch_assoc($res_q);

		while($a_q) {
			$str .= '<dd><a href="./?'. $a_q['permalink'] . '&amp;qid=' . $a_q['id'] . '&amp;' . $orbicon_x->ptr.'=mod.faq">'.$a_q['title'].'</a></dd>';
			$a_q = $dbc->_db->fetch_assoc($res_q);
		}

		$str .= '<dd class="more"><a href="./?'.$a_cat['permalink'].'&amp;'.$orbicon_x->ptr.'=mod.faq&amp;cid='.$a_cat['id'].'">Pogledaj sva pitanja</a></dd>
		</dl>';

		$a_cat = $dbc->_db->fetch_assoc($cat_res);
	}

	return $str;
}

function faq_all($category, $dt = false)
{
	global $orbicon_x, $dbc;

	$sql_q = '	SELECT		permalink, id, title
				FROM		'.TABLE_FAQ_QUESTION.'
				WHERE		(lang = %s) AND
							(category = %s) AND
							(live = 1)
				ORDER BY 	live_date';

	$res_q = sql_res($sql_q, array($orbicon_x->ptr, $category));
	$a_q = $dbc->_db->fetch_assoc($res_q);

	while($a_q) {
		if($dt) {
			$str .= '<dd><a href="./?'. $a_q['permalink'] . '&amp;qid=' . $a_q['id'] . '&amp;' . $orbicon_x->ptr.'=mod.faq">'.$a_q['title'].'</a></dd>';
		}
		else {
			$str .= '<li><a href="./?'. $a_q['permalink'] . '&amp;qid=' . $a_q['id'] . '&amp;' . $orbicon_x->ptr.'=mod.faq">'.$a_q['title'].'</a></li>';
		}
		$a_q = $dbc->_db->fetch_assoc($res_q);
	}

	return $str;
}

function faq_get_category_title($id)
{
	global $orbicon_x, $dbc;

	$sql_q = '	SELECT		title
				FROM		'.TABLE_FAQ_CATEGORY.'
				WHERE		(id = %s)
				LIMIT		1';

	$a = sql_assoc($sql_q, $id);
	return $a['title'];
}

function faq_get_q($id, $all = false, $live = 1)
{
	global $orbicon_x, $dbc;

	$what = ($all) ? '*' : 'title, answer, category';

	switch ($live) {
		case -1: $live_sql = '(live = 0) OR (live = 1)'; break;
		case 0: $live_sql = 'live = 0'; break;
		case 1: $live_sql = 'live = 1'; break;
	}

	$sql_q = '	SELECT		'.$what.'
				FROM		'.TABLE_FAQ_QUESTION.'
				WHERE		(id = %s) AND
							('.$live_sql.')
				LIMIT		1';

	return sql_assoc($sql_q, $id);
}

function faq_optionlist_categories($default = '')
{
	global $orbicon_x, $dbc;
	$cat_res = faq_get_all_categories(true);
	$str = '';
	$a_cat = $dbc->_db->fetch_assoc($cat_res);

	while($a_cat) {
		$selected = ($default == $a_cat['id']) ? ' selected="selected"' : '';
		$str .= '<option '.$selected.' value="'.$a_cat['id'].'">'.$a_cat['title'].'</option>';
		$a_cat = $dbc->_db->fetch_assoc($cat_res);
	}

	return $str;
}

function faq_post_q()
{
	/*$sql = '	INSERT INTO			'.TABLE_FAQ_QUESTION.'
									(title, category,
									poster, poster_id,
									submited, email,
									email_notify)
				VALUES				(%s, %s,
									%s, %s,
									UNIX_TIMESTAMP(), %s,
									%s)';

	return sql_insert($sql, array($_POST['title'], $_POST['category'], $_POST['poster'], $_SESSION['user.r']['id'], $_POST['email'], $_POST['email_notify']));*/


	$from = ($_POST['email']) ? $_POST['email'] : $_SESSION['site_settings']['main_site_email'];
	$from_name = ($_POST['poster']) ? $_POST['poster'] : $_SESSION['site_settings']['main_site_title'];
	$mail_notify = ($_POST['email_notify']) ? 'Da' : 'Ne';

	include_once DOC_ROOT . '/orbicon/3rdParty/phpmailer/class.phpmailer.php';

	$mail = new PHPMailer();

	if($_SESSION['site_settings']['smtp_server'] != '') {
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->Host = $_SESSION['site_settings']['smtp_server']; // SMTP server
		$mail->Port = $_SESSION['site_settings']['smtp_port'];
	}

	$mail->CharSet = 'UTF-8';
	$mail->From = $from;
	$mail->FromName = utf8_html_entities($from_name, true);
	$mail->AddAddress($_SESSION['site_settings']['main_site_email']);

	$mail->Subject = 'Novi upit sa www.hpb.hr';
	$mail->Body = "
	<strong>Upit:</strong> {$_POST['title']}<br>
	<strong>Kategorija:</strong> ".faq_get_category_title($_POST['category'])."<br>
	<strong>Ime i prezime:</strong> {$_POST['poster']}<br>
	<strong>Kontakt mail:</strong> {$_POST['email']}<br>
	<strong>Želi odgovor putem maila:</strong> {$mail_notify}<br>
	";
	$mail->WordWrap = 50;
	$mail->IsHTML(true);

	return $mail->Send();

}

function faq_search($query)
{
	global $orbicon_x, $dbc;

	$sql_qp = '	SELECT		permalink, id, title
				FROM		'.TABLE_FAQ_QUESTION.'
				WHERE		(lang = %s) AND
							(live = 1) AND
							(permalink = %s)
				LIMIT		1';

	$res_qp = sql_res($sql_qp, array($orbicon_x->ptr, get_permalink($query)));
	$a_qp = $dbc->_db->fetch_assoc($res_qp);

	if($a_qp['id']) {
		redirect(ORBX_SITE_URL . '/?'. $a_qp['permalink'] . '&amp;qid=' . $a_qp['id'] . '&amp;' . $orbicon_x->ptr.'=mod.faq');
	}

	$sql_q = '	SELECT		permalink, id, title
				FROM		'.TABLE_FAQ_QUESTION.'
				WHERE		(lang = %s) AND
							(live = 1) AND
							((title LIKE %s) OR
							(answer LIKE %s))
				ORDER BY 	live_date';

	$res_q = sql_res($sql_q, array($orbicon_x->ptr, "%$query%", "%$query%"));
	$a_q = $dbc->_db->fetch_assoc($res_q);

	if(!$a_q) {
		$str .= '<div id="faq_no_results"><p>Pretraga nije pronašla niti jedno pitanje s traženim pojmom <b>'.$_GET['q'].'</b>.</p><p style="margin-top: 1em;">Predlažemo Vam:</p><ul><li>Provjerite jesu li sve riječi ispravno napisane.</li><li>Pokušajte upotrijebiti druge ključne riječi.</li><li>Pokušajte upotrijebiti općenite ključne riječi.</li></ul></div>';
	}

	while($a_q) {
		$str .= '<li><a href="./?'. $a_q['permalink'] . '&amp;qid=' . $a_q['id'] . '&amp;' . $orbicon_x->ptr.'=mod.faq">'.$a_q['title'].'</a></li>';
		$a_q = $dbc->_db->fetch_assoc($res_q);
	}

	return "<ul class=\"ic_list\">$str</ul>";
}

function faq_category_total_inc($id)
{
	return sql_update('UPDATE '.TABLE_FAQ_CATEGORY.' SET total_qs = total_qs + 1 WHERE (id = %s)', $id);
}

function faq_category_total_dec($id)
{
	return sql_update('UPDATE '.TABLE_FAQ_CATEGORY.' SET total_qs = total_qs - 1 WHERE (id = %s)', $id);
}

?>