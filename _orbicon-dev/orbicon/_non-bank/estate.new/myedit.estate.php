<?php
/**
 * Edit estate ads
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package OrbiconMOD
 * @subpackage Estate
 * @version 1.0
 * @link http://
 * @license http://
 * @since 2007-10-01
 * @todo Translation
 */

	global $dbc, $orbicon_x;

	include_once DOC_ROOT . '/orbicon/modules/peoplering/class/class.peoplering.php';
	$pr = new Peoplering($_SESSION['user.r']);
	$pr_id = $pr->get_prid_from_rid($_SESSION['user.r']['id']);
	$credits = $pr->get_profile($pr_id);
	$credits = $credits['credits'];

	function estate_gencol_menu($default)
	{
		global $dbc, $orbicon_x;
		$opcije = '';

		$r_p = $dbc->_db->query(sprintf('	SELECT 		*
											FROM 		'.TABLE_COLUMNS.'
											WHERE 		(menu_name = %s) AND
														(language = %s) AND
														((parent = \'\') OR
														(parent IS NULL))
											ORDER BY 	sort', $dbc->_db->quote('v'), $dbc->_db->quote($orbicon_x->ptr)));
		$a_p = $dbc->_db->fetch_assoc($r_p);
		require_once DOC_ROOT . '/orbicon/modules/estate/menu.trans.php';

		while($a_p) {

			if($orbicon_x->ptr != 'hr') {
				$a_p['title'] = estate_title_trans($a_p['permalink']);
			}

			//$opcije .= '<optgroup label="'.$a_p['title'].'">';

			$selected_x = ($default == $a_p['permalink']) ? ' selected="selected"' : '';
			$opcije .= '<option '.$selected_x.' value="'.$a_p['permalink'].'" style="font-style:oblique">'.$a_p['title'].'</option>';

			$r = $dbc->_db->query(sprintf('	SELECT 		*
											FROM 		'.TABLE_COLUMNS.'
											WHERE 		(menu_name = %s) AND
														(language = %s) AND
														(parent = %s)
											ORDER BY 	sort', $dbc->_db->quote('v'), $dbc->_db->quote($orbicon_x->ptr), $dbc->_db->quote($a_p['permalink'])));
			$a = $dbc->_db->fetch_assoc($r);

			while($a) {
				$selected = ($default == $a['permalink']) ? ' selected="selected"' : '';
				if($a['permalink'] != 'plovila') {

					if($orbicon_x->ptr != 'hr') {
						$a['title'] = estate_title_trans($a['permalink']);
					}

					$opcije .= sprintf('<option value="%s"%s>%s</option>', $a['permalink'], $selected, $a['title']);
				}
				$a = $dbc->_db->fetch_assoc($r);
			}

			//$opcije .= '</optgroup>';
			$a_p = $dbc->_db->fetch_assoc($r_p);
		}

		/*$opcije .= '<optgroup label="'._L('e.boats').'"><option value="motorna-vozila">'._L('e.motorboats').'</option>
		<option value="jedrilice">'._L('e.boats2').'</option></optgroup>';*/

		$opcije .= '<option value="plovila">'._L('e.boats').'</option>
		<option value="motorna-vozila">'._L('e.motorboats').'</option>
		<option value="jedrilice">'._L('e.boats2').'</option>';

		return $opcije;
	}

	$orbicon_x->set_page_title(_L('estate_submit_new'));
	$orbicon_x->add2breadcrumbs(_L('estate_submit_new'));

	if(isset($_POST['preview_ad'])) {
		if(isset($_GET['id']) && !$_SESSION['old_estate_ad_id']) {
			$_SESSION['old_estate_ad_id'] = $_GET['id'];
		}

		$new_id = new_estate(true);
		$_SESSION['preview_ad_id'] = $new_id;
		edit_estate_user($new_id, $_SESSION['user.r']['id']);
		redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.estate.new&page=edit&id='.$new_id.'&preview');
	}

	if(isset($_POST['submit'])) {

		$new_id = 0;
		if(isset($_GET['id']) && !isset($_GET['preview'])) {

			delete_previews($_SESSION['user.r']['id']);
			if($_SESSION['old_estate_ad_id']) {
				delete_estate_ad($_SESSION['old_estate_ad_id']);
				$_SESSION['old_estate_ad_id'] = null;
			}

			edit_estate($_GET['id']);
		}
		else {
			$new_id = new_estate();
			edit_estate_user($new_id, $_SESSION['user.r']['id']);
			if($_SESSION['preview_ad_id']) {
				transport_pictures($_SESSION['preview_ad_id'], $new_id);
				$_SESSION['preview_ad_id'] = null;
			}

			delete_previews($_SESSION['user.r']['id']);
			if($_SESSION['old_estate_ad_id']) {
				delete_estate_ad($_SESSION['old_estate_ad_id']);
				$_SESSION['old_estate_ad_id'] = null;
			}

			if(!$_SESSION['user.r']['estate_agency_status']) {
				archive_user_ads($_SESSION['user.r']['id']);
			}
			else {
				switch ($_SESSION['user.r']['estate_agency_level']) {
					case AGENCY_STATUS_15: archive_user_ads($_SESSION['user.r']['id'], 15); break;
					case AGENCY_STATUS_40: archive_user_ads($_SESSION['user.r']['id'], 40); break;
				}
			}


			clear_estate_expired_sponsored_ads();

			include_once DOC_ROOT . '/orbicon/3rdParty/phpmailer/class.phpmailer.php';

			if($_SESSION['user.r']['contact_email']) {
				$info_mail = new PHPMailer();

				$info_mail_url = ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.e&amp;c=' . urlencode($estate_type_p[$_POST['kategorija']]) . '/' . urlencode(get_permalink($_POST['naslov'])) . '/' . $new_id;

				$info_mail_body = 'Pozdrav '.$_SESSION['user.r']['contact_name'].' '.$_SESSION['user.r']['contact_surname'].',<br>
	Foto Nekretnine Vas obavještava da je Vaš oglas uspješno postavljen.<br>
	Za pregled oglasa kliknite na link:<br>
	<a href="'.$info_mail_url.'">'.$info_mail_url.'</a>';

				if($_SESSION['site_settings']['smtp_server'] != '') {
					$info_mail->IsSMTP(); // telling the class to use SMTP
					$info_mail->Host = $_SESSION['site_settings']['smtp_server']; // SMTP server
					$info_mail->Port = $_SESSION['site_settings']['smtp_port'];
				}

				$info_mail->CharSet = 'UTF-8';
				$info_mail->From = trim(DOMAIN_EMAIL);
				$info_mail->FromName = utf8_html_entities(DOMAIN_NAME, true);

				$info_mail->AddAddress($_SESSION['user.r']['contact_email']);

				$info_mail->Subject = utf8_html_entities('Objavljen oglas', true);
				$info_mail->Body = $info_mail_body;
				$info_mail->WordWrap = 50;
				$info_mail->IsHTML(true);

				if(!$info_mail->Send()) {
					mail($_SESSION['user.r']['contact_email'], 'Objavljen oglas', $info_mail_body, 'Content-Type: text/html; charset=UTF-8');
				}

				$info_mail = null;
			}

			global $orbx_mod;
			if($orbx_mod->validate_module('estate.we.search')) {

				require_once DOC_ROOT . '/orbicon/modules/estate.we.search/inc.we.search.php';

				$pic = (is_file($_FILES['slika_1']['tmp_name'])) ? true : false;
				$url = url(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.e&amp;c=' . urlencode($estate_type_p[$_POST['kategorija']]) . '/' . urlencode(get_permalink($_POST['naslov'])) . '/' . $new_id, ORBX_SITE_URL . '/' . urlencode($estate_type_p[$_POST['kategorija']]) . '/' . urlencode(get_permalink($_POST['naslov'])) . '/' . $new_id);

				scan_we_search($_POST['kategorija'], $_POST['ponuda'], $_POST['cijena'], $_POST['povrsina'], $_POST['regija'], $_POST['grad'], $pic, $url);


				if(($_POST['ponuda'] == 2) && $orbx_mod->validate_module('address-book')) {
					include_once DOC_ROOT . '/orbicon/modules/address-book/class.addrbk.php';

					$adrbk = new Address_Book();
					$mails = $adrbk->load_address_book_emails('agencije');
					$adrbk = null;

					include_once DOC_ROOT.'/orbicon/modules/forms/class.form.php';
					$form = new Form;
					$counties = $form->get_pring_db_table('pring_counties', true, 'title', '', true);
					$towns = $form->get_pring_db_table('pring_towns', true);
					$form = null;

					foreach ($mails as $mail) {
						//send_we_search_agency_mail($mail, $counties, $towns);

						send_we_search_agency_mail($mail, $counties, $towns, $_SESSION['user.r']['contact_name'] . ' ' . $_SESSION['user.r']['contact_surname'], $_SESSION['user.r']['contact_phone'], $_SESSION['user.r']['contact_email'], $_POST['kategorija'], $_POST['ponuda'], $_POST['regija'], $_POST['grad'], $_POST['cijena'], $_POST['cijena'], $_POST['povrsina'], $_POST['povrsina']);


					}
				}


			}

			if($_POST['tiskano'] == 1) {

				global $estate_ad_type, $estate_currencies, $estate_type;
				$title = 'Zahtjev za tiskovnom objavom oglasa ID #'.$new_id;

				$mail_body = '
<strong>Tip nekretnine:</strong> '.$estate_type[$_POST['kategorija']].'<br>
<strong>Mjesto:</strong> '.e_get_town_by_id(intval($_POST['grad'])).'<br>
<strong>Naselje:</strong> '.$_POST['naselje'].'<br>
<strong>Vrsta oglasa:</strong> '.$estate_ad_type[$_POST['ponuda']].'<br>
<strong>Površina:</strong> '.$_POST['povrsina'].'m2<br>
<strong>Cijena:</strong> '.number_format($_POST['cijena'], 2, ',', '.').' '.$estate_currencies[$_POST['valuta']].'<br>
<strong>Kontakt telefon:</strong> '.format_phone($_SESSION['user.r']['contact_phone'], $_SESSION['user.r']['contact_phone_a'], $_SESSION['user.r']['contact_phone_b']).'<br>';

				$to = 'neven@foto-nekretnine.hr';
				$from = trim(DOMAIN_EMAIL);

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
				$mail->AddAddress('besplatni@foto-nekretnine.hr');

				$mail->Subject = utf8_html_entities($title, true);
				$mail->Body = $mail_body;
				$mail->WordWrap = 50;
				$mail->IsHTML(true);

				if(!$mail->Send()) {
					mail($to, $title, $mail_body, 'Content-Type: text/html; charset=UTF-8');
				}

				$mail = null;
			}
		}

		// mail sponsored
		if($_POST['sponzorirani'] == 1) {

			$ad_id = isset($_GET['id']) ? $_GET['id'] : $new_id;
			// sponsor ad
			set_estate_ad_sponsor(intval($ad_id), ESTATE_AD_SPONSORED);

			// first submission
			if(!get_sponsored_time($ad_id)) {
				edit_sponsored_time($ad_id, mktime(0, 0, 0, date('m'), date('d') + 14, date('Y')));

				$new_credits = ($_POST['sponsored_category'] != '') ? (650 * 1.22) : (650 * 1.22);
				$pr->change_credits($pr_id, ($credits - $new_credits));
			}

			$title = 'Sponzoriran oglas ID #'.$new_id;

			$mail_body = 'Oglas ID #<strong>'.$new_id.'</strong> je sponzoriran.<br>Korisničko ime: ' . $_SESSION['user.r']['username'] . '<br>Vrijeme: ' . date('r');
			$to = trim(DOMAIN_EMAIL);
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
			$mail->AddAddress('neven@foto-nekretnine.hr');

			$mail->Subject = utf8_html_entities($title, true);
			$mail->Body = $mail_body;
			$mail->WordWrap = 50;
			$mail->IsHTML(true);

			if(!$mail->Send()) {
				mail($to, $title, $mail_body, 'Content-Type: text/html; charset=UTF-8');
			}

			$mail = null;
		}
		else {
			$ad_id = isset($_GET['id']) ? $_GET['id'] : $new_id;
			// sponsor ad
			set_estate_ad_sponsor(intval($ad_id), ESTATE_AD_NONSPONSORED);
		}

		redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.estate.new&page=thx');
	}

	$q = '	SELECT 		*
			FROM 		' . TABLE_ESTATE . '
			WHERE 		(id = ' . $dbc->_db->quote($_GET['id']) . ') AND
						(user_id=' . $dbc->_db->quote($_SESSION['user.r']['id']).')';
	$r = $dbc->_db->query($q);
	$estate = $dbc->_db->fetch_object($r);

	$opcije = estate_gencol_menu($estate->menu);

	include_once DOC_ROOT.'/orbicon/modules/forms/class.form.php';
	$form = new Form;
	$counties = $form->get_pring_db_table('pring_counties', true, 'title', '', true);
	$towns = $form->get_pring_db_table('pring_towns', true, 'title', '', true);
	$form = null;

	$lat = floatval($estate->latitude);
	$lon = floatval($estate->logitude);

	if(DOMAIN_NO_WWW == 'foto-nekretnine.rs') {
		$lat = empty($lat) ? 44.016521 : $lat;
		$lon = empty($lon) ? 21.005859 : $lon;
		$gkey = 'ABQIAAAAlv-tQTaTwI9UJQ8CUFylZBSkb9_cQ_vOV4GjXzIBcBXRJ1SA9xRqD2lJoGpZBdCsgLgm2_KeV5DbLA';
		$city = 'Beograd';
		$city_abr = 'Bg';
	}
	else {
		$lat = empty($lat) ? 45.796255 : $lat;
		$lon = empty($lon) ? 15.954895 : $lon;
		$gkey = 'ABQIAAAAlv-tQTaTwI9UJQ8CUFylZBSkvgFR4CQCkwR0qIUYDWKbZwEeORSOvoH-JrNVbxzEU-2AYcgodQH7OQ';
		$city = 'Zagreb';
		$city_abr = 'Zg';
	}

	list($pic_main_file, $pic_main_desc) = explode(',', $estate->pic_main);
	list($pic2, $pic3, $pic4, $pic5, $pic6) = explode(';', $estate->pics);
	list($pic2_file, $pic2_desc) = explode(',', $pic2);
	list($pic3_file, $pic3_desc) = explode(',', $pic3);
	list($pic4_file, $pic4_desc) = explode(',', $pic4);
	list($pic5_file, $pic5_desc) = explode(',', $pic5);
	list($pic6_file, $pic6_desc) = explode(',', $pic6);

	$zg = /*($estate->county == 2) ?*/ '<tr class="spacing">
                <td><a href="javascript:void(null);" class="tooltip"><em class="zagreb"><span>'.$city_abr.' zone  </span></em></a> <label for="zg">'.$city.'</label></td>
                <td>
                <select id="zg" name="zg" class="select big" disabled="disabled">
                	'.print_select_menu($estate_zagreb_parts, $estate->zg, true).'
                </select></td>
              </tr>' /*: ''*/;

	$sponsored = '<tr>
                <td><label for="sponzorirani">'._L('e.sponsoredad').'</label></td>
                <td>
                	<input type="checkbox" id="sponzorirani" name="sponzorirani" value="1" '.($checked = ($estate->sponsored == 1) ? 'checked="checked"' : '').' /> <label for="sponsored_category">'._L('e.adzone').'</label> <select id="sponsored_category" name="sponsored_category"><option value="" '.($disabled = ($credits < (650.0 * 1.22)) ? 'disabled="disabled"' : '').'>'._L('e.homepage').'</option>'.estate_gencol_menu($estate->sponsored_category).'</select>
                </td>
              </tr>';

	if(($sponsored != '') && ($credits < (650.0 * 1.22))) {
		$sponsored = '<tr><td colspan="2">'.sprintf(_L('e.ifyouwantsponsor'), '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=uvjeti-pla%C4%87anja&amp;no-override" target="_blank">') . '</a></td></tr>';
	}

	if(isset($_GET['preview'])) {
		$url = ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.e&c=' . urlencode($estate_type_p[$estate->category]) . '/' . urlencode($estate->permalink) . '/' . $_GET['id'] . '&preview';

		$js_preview = '<script type="text/javascript">window.open(\''.$url.'\',\'_blank\');</script>';
	}

	return  $js_preview . '
<style type="text/css">/*<![CDATA[*/
	.house, .land, .business, .tourism, .apartment {
		display:none;
	}
/*]]>*/</style>
<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/controler/gzip.server.php?file=/orbicon/modules/estate/estate.js&amp;'.ORBX_BUILD.'"></script>

<div id="user_navigation">
	<a href="'.ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.estate.new" class="ads">'._L('e.allads').'</a>
	<a href="'.ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.peoplering&amp;sp=profile" class="profile">'._L('e.myprofile').'</a>
	<a href="'.ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.peoplering&amp;sp=credits" class="credits">'._L('e.useraccount').'</a>
	<a href="javascript: void(null);" onclick="javascript: __unload();" title="'._L('pr-exit').'" class="signout">'._L('pr-exit').'</a>
</div>

    <form action="" method="post" enctype="multipart/form-data" onsubmit="javascript: return verify_adform();">
      <fieldset>
        <legend>'._L('e.pickcat').'</legend>
        <table id="form_holder">
    	 <tr>
            <td><a href="javascript:void(null);" class="tooltip"><em><span>'._L('e.becreative').'. </span></em></a> <label for="naslov" class="required">'._L('e.adtitle').' <span class="red">*</span></label></td>
            <td><input type="text" id="naslov" name="naslov" class="input_text big" value="'.$estate->title.'" /></td>
          </tr>
          <tr>
            <td><label for="ponuda">'._L('e.adtype').' <span class="red">*</span></label></td>
            <td>
              <select id="ponuda" name="ponuda" class="select mid">
                '.print_select_menu($estate_ad_type, $estate->ad_type, true).'
              </select>
            </td>
          </tr>
          <tr>
            <td><label for="kategorija">'._L('e.type').' <span class="red">*</span></label></td>
            <td>
              <select id="kategorija" name="kategorija" class="mid" onchange="javascript:switch_estate_types(this.options[this.selectedIndex].value);">
				'.print_select_menu($estate_type, $estate->category, true).'
              </select>
            </td>
          </tr>

          <tr>

	          <td><label for="ad_menu">'._L('e.estatetype').' <span class="red">*</span></label></td>
	          <td>
	          <select id="ad_menu" name="ad_menu" class="mid">
               '.$opcije.'
              </select></td>
	      </tr>

		<tr class="spacing house">
                <td><label for="vrsta_kuce">'._L('e.housetype').' <span class="red">*</span></label></td>
                <td>
                  <select id="vrsta_kuce" name="vrsta_kuce" class="select big">
                    '.print_select_menu($estate_house_type, $estate->house_type, true).'
                  </select>
                </td>
              </tr>

              <tr class="spacing business">
                <td><label for="vrsta_prostora">'._L('e.bsntype').' <span class="red">*</span></label></td>
                <td>
                  <select id="vrsta_prostora" name="vrsta_prostora" class="select big">
                  '.print_select_menu($estate_business_type, $estate->business_type, true).'
                  </select>
                </td>
              </tr>

			<tr class="spacing apartment">
                <td><label for="vrsta_stana">'._L('e.aparttypo').' <span class="red">*</span></label></td>
                <td>
                  <select id="vrsta_stana" name="vrsta_stana" class="select mid">
                  '.print_select_menu($estate_apartment_type, $estate->apartment_type, true).'
                  </select>
                </td>
              </tr>

              <tr class="spacing land">
                <td><label for="vrsta_zemljista">'._L('e.landtype').' <span class="red">*</span></label></td>
                <td>
                  <select id="vrsta_zemljista" name="vrsta_zemljista" class="select mid">
                  '.print_select_menu($estate_land_type, $estate->land_type, true).'
                  </select>
                </td>
              </tr>

              <tr>
                <td><a href="javascript:void(null);" class="tooltip"><em><span>'._L('e.pricehelp').'.  </span></em></a> <label for="cijena" class="required">'._L('e.price').'</label></td>
                <td>
                  <input type="text" id="cijena" name="cijena" value="'.$estate->price.'" maxlength="10" class="input_text mid cijena"    />
                  <label for="valuta" class="inline">'._L('e.currency').'</label>
                    <select class="select small" id="valuta" name="valuta">'.print_select_menu($estate_currencies, $estate->currency, true).'</select
                </td>
              </tr>
              <tr>
                <td><a href="javascript:void(null);" class="tooltip"><em><span>'._L('e.msquarehelp').'.</span></em></a> <label for="povrsina" class="required">'._L('e.msquare').' <span class="red">*</span></label></td>
                <td><input type="text" id="povrsina" name="povrsina" class="input_text mid" value="'.$estate->msquare.'" maxlength="6"    /> <span>m<sup>2</sup></span></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><label for="regija" class="required">'._L('e.region').' <span class="red">*</span></label></td>
                <td>
                  <select id="regija" name="regija" class="select big" onchange="javascript: switch_towns(this.options[this.selectedIndex].value, \'HR\', \'grad_container\', \'grad\', \'grad\');">
                    <option value="" class="first-child">Sve regije</option>
                    '.print_select_menu($counties, $estate->county, true).'
                  </select>
                </td>
              </tr>

              <tr class="spacing">
                <td><label for="grad">'._L('e.place').' <span class="red">*</span></label></td>
                <td id="grad_container">
                <select id="grad" name="grad" class="select big">
                	'.print_select_menu($towns, $estate->town, true).'
                </select></td>
              </tr>

              '.$zg.'

              <tr class="spacing">
                <td><label for="naselje">'._L('e.neigh').'</label></td>
                <td><input type="text" id="naselje" name="naselje" class="input_text big" value="'.$estate->neighborhood.'" /> </td>
              </tr>

              <tr class="spacing">
                <td><label for="ulica">'._L('e.street').'</label></td>
                <td><input type="text" id="ulica" name="ulica" class="input_text big" value="'.$estate->street.'" /> </td>
              </tr>
              <tr>
                <td><label for="kucni_broj">'._L('e.housenum').'</label></td>
                <td><input type="text" id="kucni_broj" name="kucni_broj" class="input_text small" value="'.$estate->street_no.'" maxlength="5" /> </td>
              </tr>
              <tr class="h">
                <td><a href="javascript:void(null);" class="tooltip"><em><span>'.sprintf(_L('e.maphelp'), '<strong>( + )</strong>', '<strong>( - )</strong>') . '.</span></em></a> <label for="frmLat">Geografska dužina</label></td>
                <td><input type="text" id="frmLat" name="geo_duzina" class="input_text mid" value="'.$estate->latitude.'" /> <span class="small left_indent_10"><a href="javascript:void(null)" onclick="javascript:sh(\'google_map_container\')">Prikažite nekretninu na karti</a></span></td>
              </tr>
              <tr class="h">
                <td><a href="javascript:void(null);" class="tooltip"><em><span>Za lakše snalaženje na karti, koristite <strong>( + )</strong> za približavanje odnosno <strong>( - )</strong> za udaljavanje. Lokacija je označena kada se pojavi marker plave boje.</span></em></a> <label for="frmLon">Geografska širina</label></td>
                <td><input type="text" id="frmLon" name="geo_sirina" class="input_text mid" value="'.$estate->logitude.'" />  <span class="small left_indent_10"><a href="javascript:void(null)" onclick="javascript:sh(\'google_map_container\')">Prikažite nekretninu na karti</a></span></td>
              </tr>

              <tr>
				<td colspan="2">
				<!--- g maps -->

				<div id="google_map_container">
					<div id="map" style="width: 480px; height: 300px"></div>
					<div id="geo" style="width: 300px;position: absolute;left: 620px;top: 100px;" class="tekst">
					</div>
				</div>

				<script src="http://maps.google.com/maps?file=api&v=2&key='.$gkey.'" type="text/javascript"></script>
<script type="text/javascript"><!-- // --><![CDATA[

	YAHOO.util.Event.addListener(window, "load", function () {switch_estate_types($("kategorija").options[$("kategorija").selectedIndex].value);});

	var setLat = '.$lat.';
	var setLon = '.$lon.';
	var marker = null;

	setIcon();

	if	(argItems("address") != "") {
	myAddress = unescape(argItems("address"));
	document.getElementById("address").value = myAddress;

} else if (argItems("lat") == "" || argItems("lon") == "") {
	placeMarker(setLat, setLon);
	} else {
	var setLat = parseFloat( argItems("lat") );
	var setLon = parseFloat( argItems("lon") );
	setLat = setLat.toFixed(6);
	setLon = setLon.toFixed(6);
	placeMarker(setLat, setLon);
}

YAHOO.util.Event.addListener(window, "load", tags_autocomplete);

//]]></script>

				<!-- g maps  -->
				</td>
			</tr>

              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>


              <tr class="land">
                <td><label for="sirina">'._L('e.width').'</label></td>
                <td><input type="text" id="sirina" name="sirina" class="input_text small" value="'.$estate->width.'" maxlength="6"     /> <span>m</span></td>
              </tr>

              <tr class="land">
                <td><label for="duzina">'._L('e.length').'</label></td>
                <td><input type="text" id="duzina" name="duzina" class="input_text small" value="'.$estate->length.'" maxlength="6"     />  <span>m</span></td>
              </tr>

              <tr class="house business apartment tourism">
                <td><label for="novo_staro">'._L('e.builttype').'</label></td>
                <td>
                  <select id="novo_staro" name="novo_staro" class="select mid">
                  '.print_select_menu($estate_build_type, $estate->build_type, true).'
                  </select>
                </td>
              </tr>
              <tr class="house business apartment tourism">
                <td><label for="godina">'._L('e.built').'</label></td>
                <td><input type="text" id="godina" name="godina" class="input_text small" value="'.$estate->year_built.'"     /> </td>
              </tr>
              <tr class="house business tourism">
                <td><label for="povrsina_okucnice">'._L('e.msquareother').'</label></td>
                <td><input type="text" id="povrsina_okucnice" name="povrsina_okucnice" class="input_text small" value="'.$estate->msquare_backyard.'" maxlength="5"     /> <span>m<sup>2</sup></span></td>
              </tr>
              <tr class="house business apartment tourism">
                <td><a href="javascript:void(null);" class="tooltip"><em><span>'._L('e.roomnumhelp').'</span></em></a> <label for="broj_soba" class="broj">'._L('e.roomnum').'</label></td>
                <td><input type="text" id="broj_soba" name="broj_soba" class="input_text small" value="'.$estate->room_num.'" maxlength="2" /> </td>
              </tr>
              <tr class="house">
                <td><label for="broj_etaza">'._L('e.floornum').'</label></td>
                <td><input type="text" id="broj_etaza" name="broj_etaza" class="input_text small" value="'.$estate->floor_num.'" maxlength="2" /> </td>
              </tr>

 			 <tr class="tourism">
                <td><label for="udaljenost">'._L('e.sea').'</label></td>
                <td><input type="text" id="udaljenost" name="udaljenost" class="input_text small" value="'.$estate->sea_distance.'" maxlength="6"     /> <span>m</span></td>
              </tr>

 			  <tr class="tourism">
                <td><label for="broj_kreveta" class="broj">'._L('e.beds').'</label></td>
                <td><input type="text" id="broj_kreveta" name="broj_kreveta" class="input_text small" value="'.$estate->bed_num.'" maxlength="2"     /> </td>
              </tr>

              <tr class="business apartment tourism">
                <td><label for="kat">'._L('e.floor').'</label></td>
                <td><input type="text" id="kat" name="kat" class="input_text small" value="'.$estate->flat.'" maxlength="2"     /> </td>
              </tr>

              <tr class="business apartment tourism">
                <td><label for="ukupno_katova">'._L('e.totalfloor').'</label></td>
                <td><input type="text" id="ukupno_katova" name="ukupno_katova" class="input_text small" value="'.$estate->flat_num.'" maxlength="2"     /> </td>
              </tr>

              <tr class="apartment">
                <td><label for="broj_kuponica">'._L('e.bathnum').'</label></td>
                <td><input type="text" id="broj_kuponica" name="broj_kuponica" class="input_text small" value="'.$estate->bath_num.'" maxlength="2"     /> </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
               <tr class="house business apartment">
                <td><label for="grijanje">'._L('e.heat').'</label></td>
                <td>
                  <select id="grijanje" name="grijanje" class="select mid">
                    '.print_select_menu($estate_heating_type, $estate->heating, true).'
                  </select>
                </td>
              </tr>
              <tr class="house business apartment">
                <td><label>'._L('e.pubtran').'</label></td>
                <td><label for="bus" class="chck"><input '.($checked = (get_estate_flag($estate->public_transport, ESTATE_PUBLIC_TR_BUS)) ? 'checked="checked"' : '').' type="checkbox" id="bus" name="bus" value="'.ESTATE_PUBLIC_TR_BUS.'" /> '._L('e.bus').'</label><label for="tramvaj" class="chck"><input '.($checked = (get_estate_flag($estate->public_transport, ESTATE_PUBLIC_TR_TRAM)) ? 'checked="checked"' : '').' type="checkbox" id="tramvaj" name="tramvaj" value="'.ESTATE_PUBLIC_TR_TRAM.'" /> '._L('e.tram').'</label> </td>
              </tr>
               <tr>
                <td class="top"><label>'._L('e.equip').'</label></td>
                <td>
                <p class="house business apartment tourism">
                  <label for="telefon" class="chck"><input value="'.ESTATE_EQUIP_PHONE.'" type="checkbox" id="telefon" name="telefon" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_PHONE)) ? 'checked="checked"' : '').' /> '._L('e.phone').'</label>
                  <label for="balkon" class="chck"><input value="'.ESTATE_EQUIP_BALCONY.'" type="checkbox" id="balkon" name="balkon" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_BALCONY)) ? 'checked="checked"' : '').' /> '._L('e.balcony').'</label>
                  <label for="vrt" class="chck"><input value="'.ESTATE_EQUIP_GARDEN.'" type="checkbox" id="vrt" name="vrt" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_GARDEN)) ? 'checked="checked"' : '').' /> '._L('e.garden').'</label><br />
                  <label for="garaza" class="chck"><input value="'.ESTATE_EQUIP_GARAGE.'" type="checkbox" id="garaza" name="garaza" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_GARAGE)) ? 'checked="checked"' : '').' /> '._L('e.garage').'</label>
                  <label for="klima" class="chck"><input value="'.ESTATE_EQUIP_CLIMATE.'" type="checkbox" id="klima" name="klima" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_CLIMATE)) ? 'checked="checked"' : '').' /> '._L('e.climate').'</label><br />
                  <label for="invalidi" class="chck block"><input value="'.ESTATE_EQUIP_INVALIDS.'" type="checkbox" id="invalidi" name="invalidi" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_INVALIDS)) ? 'checked="checked"' : '').' /> '._L('e.invalid').'</label>
				</p>

                  <p class="tourism" id="tourism_equipment">
                  <label for="bazen" class="chck"><input value="'.ESTATE_EQUIP_POOL.'" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_POOL)) ? 'checked="checked"' : '').' type="checkbox" id="bazen" name="bazen" /> '._L('e.pool').'</label>

                  <label for="tv" class="chck"><input value="'.ESTATE_EQUIP_TV.'" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_TV)) ? 'checked="checked"' : '').' type="checkbox" id="tv" name="tv" /> '._L('e.tv').'</label><br />
                  <label for="satelitska" class="chck"><input value="'.ESTATE_EQUIP_SAT_TV.'" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_SAT_TV)) ? 'checked="checked"' : '').' type="checkbox" id="satelitska" name="satelitska" /> '._L('e.sattv').'</label>
                  <label for="internet" class="chck"><input value="'.ESTATE_EQUIP_INTERNET.'" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_INTERNET)) ? 'checked="checked"' : '').' type="checkbox" id="internet" name="internet" /> '._L('e.net').'</label>
                  <label for="tereni" class="chck"><input value="'.ESTATE_EQUIP_SPORT.'" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_SPORT)) ? 'checked="checked"' : '').' type="checkbox" id="tereni" name="tereni" /> '._L('e.sport').'</label><br />
                  <label for="dvorana" class="chck"><input value="'.ESTATE_EQUIP_CONFERENCE.'" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_CONFERENCE)) ? 'checked="checked"' : '').' type="checkbox" id="dvorana" name="dvorana" /> '._L('e.hall').'</label><br />
				</p>

				<p class="land">

					<label for="put" class="chck"><input type="checkbox" id="put" name="put" value="'.ESTATE_EQUIP_LAND_PATH.'" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_LAND_PATH)) ? 'checked="checked"' : '').' /> '._L('e.path').'</label>
                  	<label for="struja" class="chck"><input type="checkbox" id="struja" name="struja" value="'.ESTATE_EQUIP_LAND_POWER.'" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_LAND_POWER)) ? 'checked="checked"' : '').' /> '._L('e.power').'</label>
                  	<label for="voda" class="chck"><input type="checkbox" id="voda" name="voda" value="'.ESTATE_EQUIP_LAND_WATER.'" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_LAND_WATER)) ? 'checked="checked"' : '').' /> '._L('e.water').'</label>
                  	<label for="plin" class="chck"><input type="checkbox" id="plin" name="plin" value="'.ESTATE_EQUIP_LAND_GAS.'" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_LAND_GAS)) ? 'checked="checked"' : '').' /> '._L('e.gas').'</label><br />
                 	 <label for="kanalizacija" class="chck"><input type="checkbox" id="kanalizacija" name="kanalizacija" value="'.ESTATE_EQUIP_LAND_SEWER.'" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_LAND_SEWER)) ? 'checked="checked"' : '').' /> '._L('e.sewer').'</label>
                 	 <label for="telefon_land" class="chck block"><input type="checkbox" id="telefon_land" name="telefon" value="'.ESTATE_EQUIP_PHONE.'" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_PHONE)) ? 'checked="checked"' : '').' /> '._L('e.phone').'</label>
                  	<label for="lokacijska" class="chck block"><input type="checkbox" id="lokacijska" name="lokacijska" value="'.ESTATE_EQUIP_LAND_PAPERS.'" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_LAND_PAPERS)) ? 'checked="checked"' : '').' /> '._L('e.locperm').'</label>

				</p>

                 </td>
              </tr>

              <tr>
                <td><label for="dokumentacija">'._L('e.docs').'</label></td>
                <td>
                  <select id="dokumentacija" name="dokumentacija" class="select mid">
                    '.print_select_menu($estate_docs_type, $estate->docs, true).'
                  </select>
                </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="top"><a href="javascript:void(null);" class="tooltip"><em><span>'._L('e.adtxthelp').'. </span></em></a> <label for="tekst_oglasa" class="oglas">'._L('e.adtxt').'</label></td>
                <td><textarea id="tekst_oglasa" name="tekst_oglasa" class="input_textarea">'.$estate->description.'</textarea> </td>
              </tr>
              <tr>
                <td><a href="javascript:void(null);" class="tooltip"><em><span>'._L('e.taghelp').'.</span></em></a> <label for="tagovi">'._L('e.tags').' <span class="red">*</span></label></td>
                <td><input type="text" id="tagovi" name="tagovi" class="input_text big" value="'.$estate->tags.'" /></td>
              </tr>
              <tr>
                <td colspan="2"><span class="red">Istaknite Vašu ponudu u Foto-nekretnine oglasniku!</span> - <a href="./site/gfx/placeni_99.jpg" target="_blank">PRIMJER</a></td>
              </tr>
              <tr>
                <td><a href="javascript:void(null);" class="tooltip"><em><span>'._L('e.printadhelp').'.</span></em></a> <label for="tiskano">'._L('e.printad2').'</label></td>
                <td>
                	<input type="checkbox" id="tiskano" name="tiskano" value="1" '.($checked = ($estate->publish_print == 1) ? 'checked="checked"' : '').' />
	                <div id="oglasnik_predaja"></div>
                </td>
              </tr>
              '.$sponsored.'

            </table>
        </fieldset>

 <fieldset>
            <legend>'._L('e.sendpicsvid').'</legend>
             <table id="form_holder">
              <tr>
                <td><label for="slika_1">'._L('e.setupmainpic').' '.print_preview_link($pic_main_file).'</label></td>
                <td><input type="file" id="slika_1" name="slika_1" class="file" value="" /></td>
              </tr>
              <tr>
                <td><label for="opis_1">'._L('e.mainpicdesc').'</label></td>
                <td><input type="text" id="opis_1" name="opis_1" class="input_text big" value="'.$pic_main_desc.'" /></td>
              </tr>
               <tr>
                <td></td>
                <td>&nbsp;</td>
              </tr>
               <tr>
                <td><label for="slika_2">'.sprintf(_L('e.addpic'), '2.').' '.print_preview_link($pic2_file).'</label></td>
                <td><input type="file" id="slika_2" name="slika_2" class="file" value="" /></td>
              </tr>
              <tr>
                <td><label for="opis_2">'.sprintf(_L('e.picdesc'), '2.').'</label></td>
                <td><input type="text" id="opis_2" name="opis_2" class="input_text big" value="'.$pic2_desc.'" /></td>
              </tr>
               <tr>
                <td><label for="slika_3">'.sprintf(_L('e.addpic'), '3.').' '.print_preview_link($pic3_file).'</label></td>
                <td><input type="file" id="slika_3" name="slika_3" class="file" value="" /></td>
              </tr>
              <tr>
                <td><label for="opis_3">'.sprintf(_L('e.picdesc'), '3.').'</label></td>
                <td><input type="text" id="opis_3" name="opis_3" class="input_text big" value="'.$pic3_desc.'" /></td>
              </tr>
               <tr>
                <td><label for="slika_4">'.sprintf(_L('e.addpic'), '4.').' '.print_preview_link($pic4_file).'</label></td>
                <td><input type="file" id="slika_4" name="slika_4" class="file" value="" /></td>
              </tr>
              <tr>
                <td><label for="opis_4">'.sprintf(_L('e.picdesc'), '4.').'</label></td>
                <td><input type="text" id="opis_4" name="opis_4" class="input_text big" value="'.$pic4_desc.'" /></td>
              </tr>
              <tr>
                <td><label for="slika_5">'.sprintf(_L('e.addpic'), '5.').' '.print_preview_link($pic5_file).'</label></td>
                <td><input type="file" id="slika_5" name="slika_5" class="file" value="" /></td>
              </tr>
              <tr>
                <td><label for="opis_5">'.sprintf(_L('e.picdesc'), '5.').'</label></td>
                <td><input type="text" id="opis_5" name="opis_5" class="input_text big" value="'.$pic5_desc.'" /></td>
              </tr>
              <tr>
                <td><label for="slika_6">'.sprintf(_L('e.addpic'), '6.').' '.print_preview_link($pic6_file).'</label></td>
                <td><input type="file" id="slika_6" name="slika_6" class="file" value="" /></td>
              </tr>
              <tr>
                <td><label for="opis_6">'.sprintf(_L('e.picdesc'), '6.').'</label></td>
                <td><input type="text" id="opis_6" name="opis_6" class="input_text big" value="'.$pic6_desc.'" /></td>
              </tr>
               <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><a href="javascript:void(null);" class="tooltip"><em><span>'.sprintf(_L('e.maxvidsize'), '<strong>'.byte_size(get_php_ini_bytes(ini_get('post_max_size'))).'</strong>').'.</span></em></a> <label for="video">'._L('e.addvid').'</label></td>
                <td><input type="file" id="video" name="video" class="file" value="" /></td>
              </tr>
              <tr class="no_border">
                <td></td>
                <td><input type="submit" id="submit" name="submit" value="'._L('e.save').'" class="step_bttn" /> <input type="submit" id="preview_ad" name="preview_ad" value="'._L('e.preview').'" class="step_bttn" /></td>
              </tr>
            </table>
        </fieldset>

   </form>';

?>