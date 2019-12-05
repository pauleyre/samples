<?php
/**
 * Estate ad details
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package SystemMOD
 * @subpackage Estate
 * @version 1.0
 * @link http://
 * @license http://
 * @since 2007-10-01
 * @todo Translation
 */

	require_once DOC_ROOT . '/orbicon/modules/estate/inc.estate.php';

	list($category, $permalink, $entry_id) = explode('/', $_GET['c']);
	global $dbc;

	if(isset($_GET['preview'])) {
		$q = sprintf('	SELECT 		*
						FROM 		'.TABLE_ESTATE.'
						WHERE		(permalink = %s) AND
									(id = %s)
						LIMIT 		1', $dbc->_db->quote($permalink), $dbc->_db->quote($entry_id));
	}
	else {
		$q = sprintf('	SELECT 		*
						FROM 		'.TABLE_ESTATE.'
						WHERE		(permalink = %s) AND
									(id = %s) AND
									(status = '.ESTATE_AD_LIVE.')
						LIMIT 		1', $dbc->_db->quote($permalink), $dbc->_db->quote($entry_id));
	}
//echo "<!-- $q -->";
	$r = $dbc->_db->query($q);

	$estate = $dbc->_db->fetch_object($r);

	if(!$r || !$estate) {
		$estate->title = '404 Not Found';
		header('HTTP/1.1 404 Not Found', true);
		$_SESSION['cache_status'] = 404;
	}

	$lat = floatval($estate->latitude);
	$lon = floatval($estate->logitude);

	if(DOMAIN_NO_WWW == 'foto-nekretnine.rs') {
		$lat = empty($lat) ? 44.016521 : $lat;
		$lon = empty($lon) ? 21.005859 : $lon;
		$class_map = (($lat == 44.016521) && ($lon == 21.005859)) ? '.map {text-decoration:line-through;}' : '';
		$gkey = 'ABQIAAAAlv-tQTaTwI9UJQ8CUFylZBSkb9_cQ_vOV4GjXzIBcBXRJ1SA9xRqD2lJoGpZBdCsgLgm2_KeV5DbLA';
	}
	else {
		$lat = empty($lat) ? 45.796255 : $lat;
		$lon = empty($lon) ? 15.954895 : $lon;
		$class_map = (($lat == 45.796255) && ($lon == 15.954895)) ? '.map {text-decoration:line-through;}' : '';
		$gkey = 'ABQIAAAAlv-tQTaTwI9UJQ8CUFylZBSkvgFR4CQCkwR0qIUYDWKbZwEeORSOvoH-JrNVbxzEU-2AYcgodQH7OQ';
	}

	if(($orbicon_x->ptr != 'hr')) {
		$category_title = str_replace('-', ' ', ucfirst($category));
	}
	else {
		$category_title = get_column_title($category);
	}

	$orbicon_x->set_page_title($estate->title . ' | ' . $category_title);
	if($orbicon_x->ptr == 'hr') {
		$orbicon_x->set_page_metatag('keywords', $estate->keywords);
		$orbicon_x->set_page_metatag('description', $estate->description);
	}
	else {
		$orbicon_x->set_page_metatag('keywords', $estate->title . ' , ' . $category_title);
		$orbicon_x->set_page_metatag('description', $estate->title . ' | ' . $category_title);
	}

	// flags
	$flag_high = count_estate_ad_flags($estate->id, ESTATE_USER_COMM_PRICE_HIGH);
	$flag_low = count_estate_ad_flags($estate->id, ESTATE_USER_COMM_PRICE_LOW);
	$flag_exprd = count_estate_ad_flags($estate->id, ESTATE_USER_COMM_EXPIRED);
	$flags = '<div class="ad_flags"><h3>'._L('e.userflags').'</h3>';

	$flags .= '<p class="high">'.sprintf(_L('e.userpricehigh'), $flag_high).' <a href="javascript:void(null);" onclick="javascript:flag_ad('.$estate->id.', '.intval($_SESSION['user.r']['id']).', '.ESTATE_USER_COMM_PRICE_HIGH.');">'._L('e.flagit').'</a></p>';

	$flags .= '<p class="low">'.sprintf(_L('e.userpricelow'), $flag_low).' <a href="javascript:void(null);" onclick="javascript:flag_ad('.$estate->id.', '.intval($_SESSION['user.r']['id']).', '.ESTATE_USER_COMM_PRICE_LOW.');">'._L('e.flagit').'</a></p>';

	$flags .= '<p class="expired">'.sprintf(_L('e.useradexpired'), $flag_exprd).' <a href="javascript:void(null);" onclick="javascript:flag_ad('.$estate->id.', '.intval($_SESSION['user.r']['id']).', '.ESTATE_USER_COMM_EXPIRED.');">'._L('e.flagit').'</a></p>';

	$flags .= '</div>';

	// flags END

	if($estate->video_embed) {
		$video = $estate->video_embed;
	}
	elseif($estate->video && is_file(DOC_ROOT . '/site/mercury/' . $estate->video)) {
		include_once DOC_ROOT . '/orbicon/class/inc.mmedia.php';
		$video = get_flv_player($estate->video);
	}
	else {
		$video = _L('e.novid');
		$class_video = '.video {text-decoration:line-through;}';
	}

	list($pic_main_name, $pic_main_desc) = explode(',', $estate->pic_main);

	$old_dir_path = DOC_ROOT . '/site/venus/old/' . $estate->user_id . '/oglasi/' . $estate->id;

	if(!is_file(DOC_ROOT . '/site/venus/' . $pic_main_name)) {
		require_once DOC_ROOT . '/orbicon/class/diriterator/class.diriterator.php';
		$dir = new DirIterator($old_dir_path, '*');
		$files = $dir->files();
		$dir = null;
	}

	if(is_file(DOC_ROOT . '/site/venus/' . $pic_main_name)) {
		$pic_main_thumb = '
		<div class="screen">
			<img src="'.ORBX_SITE_URL.'/site/venus/'.$pic_main_name.'" alt="'._L('picgal').'" class="main" />
		</div>
		<div id="opis_slike">'.$pic_main_desc.'</div>';
		$pdf_image = DOC_ROOT . '/site/venus/' . $pic_main_name;
	}
	else {
		if(is_file($old_dir_path . '/' . $files[0])) {
			$pic_main_thumb = '
		<div class="screen">
			<img src="'.ORBX_SITE_URL.'/site/venus/old/' . $estate->user_id . '/oglasi/' . $estate->id . '/' . basename($files[0]) . '" alt="Galerija slika" class="main" />
		</div>
		<div id="opis_slike">&nbsp;</div>';
			$pdf_image = $old_dir_path . '/' . $files[0];
		}
	}

	$i = 1;
	$pics_thumbs = '';
	$pics = explode(';', $estate->pics);
	$pics = array_merge(array($estate->pic_main), $pics);

	foreach ($pics as $pic) {
		list($pic_path, $pic_desc) = explode(',', $pic);
		if(is_file(DOC_ROOT . '/site/venus/' . $pic_path)) {

			if(is_file(DOC_ROOT . '/site/venus/thumbs/t-' . $pic_path)) {
				$thumb = ORBX_SITE_URL.'/site/venus/thumbs/t-'.$pic_path;
			}
			else {
				$thumb = ORBX_SITE_URL.'/site/venus/'.$pic_path;
			}

			$pics_thumbs .= '<a href="'.ORBX_SITE_URL.'/site/venus/'.$pic_path.'" rel="lightbox[gallery]" title="'.$pic_desc.'"><img id="image' . $i . '" src="'.$thumb.'" alt="'.$pic_desc.'" class="thumb" title="'.$pic_desc.'" /></a>';
		}

		$i ++;
	}

	// try with old db
	if($pics_thumbs == '') {
		$pics = $files;

		foreach ($pics as $pic)	{
			if(is_file($old_dir_path .'/' . $pic)) {
				$basename_pic = basename($pic);
				$pics_thumbs .= '<a href="'.ORBX_SITE_URL.'/site/venus/old/' . $estate->user_id . '/oglasi/' . $estate->id . '/' . $basename_pic . '" rel="lightbox[gallery]" title="'.$pic_desc.'"><img id="image' . $i . '" src="'.ORBX_SITE_URL.'/site/venus/old/' . $estate->user_id . '/oglasi/' . $estate->id . '/' . $basename_pic . '" alt="'.$pic_desc.'" class="thumb" title="'.$basename_pic.'" /></a>';
			}
			$i ++;
		}
	}

	if($pics_thumbs == '') {
		$pics_thumbs = _L('e.nopicgal');
		$class_gallery = '.gallery {text-decoration:line-through;}';
	}

	// add admin edit shortcut to magister db
	if(get_is_admin()) {
		$edit_shortcut = $orbicon_x->admin_layout('<p><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/estate&amp;page=add&amp;id='.$estate->id.'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/edit.png" /></a></p>');
	}
	elseif (get_is_member() && ($estate->user_id == $_SESSION['user.r']['id'])) {
		$edit_shortcut = '<p><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.estate.new&amp;page=add&amp;id='.$estate->id.'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/edit.png" /></a></p>';
	}

	// load user
	include_once DOC_ROOT . '/orbicon/modules/peoplering/class/class.peoplering.php';
	$pr = new Peoplering();
	$user = $pr->get_profile($pr->get_prid_from_rid($estate->user_id));
	$logo = $pr->get_company($pr->get_prid_from_rid($estate->user_id));
	$logo = $logo['logo'];

	if(is_file(DOC_ROOT . '/site/venus/' . $logo)) {
		$logo = '<img title="'.$user['contact_name'].' '.$user['contact_surname'].'" alt="'.$user['contact_name'].' '.$user['contact_surname'].'" class="ko_logo" src="'.ORBX_SITE_URL.'/site/venus/'.$logo.'" /><br />';
	}

	$me = $pr->get_profile($pr->get_prid_from_rid($_SESSION['user.r']['id']));

	// send email

	if(isset($_REQUEST['send'])) {
		$title = _L('e.msgfrom') . ' ' . ORBX_SITE_URL;
		$mail_body = _L('e.regards') . ',<br>'._L('e.wwwquery').' <a href="'.ORBX_SITE_URL.'">'.ORBX_SITE_URL.'</a><br>';

		$mail_body .= '<strong>'._L('e.contact').':</strong> '. $_REQUEST['ko_ime'] . '<br />';

		if($_REQUEST['ko_cijena']) {
			$mail_body .= '<strong>'._L('e.barterprice').':</strong> '. $_REQUEST['ko_cijena'] . '<br />';
		}

		$mail_body .= '<strong>'._L('e.phone').':</strong> '. $_REQUEST['ko_tel'] . '<br />';
		$mail_body .= '<strong>'._L('e.msg').':</strong> ' . $_REQUEST['ko_poruka'] . '<br>';
		$mail_body .= '<strong>'._L('e.ad').':</strong> <a href="' . ORBX_SITE_URL . $_SERVER['REQUEST_URI'] . '">' . ORBX_SITE_URL . $_SERVER['REQUEST_URI'] . '</a><br>';
		$mail_body .= '<p><a href="'.ORBX_SITE_URL.'" title=""><img alt="'.ORBX_SITE_URL.'" title="'.ORBX_SITE_URL.'" src="'.ORBX_SITE_URL . '/site/venus/20071025-logo_fn-gif-bdad.gif" /></a></p>';
		$to = trim($user['contact_email']);
		$from = trim($_REQUEST['ko_email']);

		include_once DOC_ROOT . '/orbicon/3rdParty/phpmailer/class.phpmailer.php';

		$mail = new PHPMailer();

		if($_SESSION['site_settings']['smtp_server'] != '') {
			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->Host = $_SESSION['site_settings']['smtp_server']; // SMTP server
			$mail->Port = $_SESSION['site_settings']['smtp_port'];
		}

		$mail->CharSet = 'UTF-8';
		$mail->From = $from;
		$mail->FromName = utf8_html_entities($_REQUEST['ko_ime'], true);

		$mail->AddAddress($to);

		$mail->Subject = utf8_html_entities($title, true);
		$mail->Body = $mail_body;
		$mail->WordWrap = 50;
		$mail->IsHTML(true);

		if(!$mail->Send()) {
			mail($to, $title, $mail_body, 'Content-Type: text/html; charset=UTF-8');
		}

		$mail = null;
		$display_content = '<div class="estate_mail_sent">'._L('e.mailsent').'.<br />'._L('e.thx').'.</div>';
	}

	$url = url(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=' . $estate->menu, ORBX_SITE_URL . '/' . $orbicon_x->ptr . '/' . $estate->menu);
	$orbicon_x->add2breadcrumbs('<a href="'.$url.'">'.get_column_title($estate->menu).'</a>');
	$orbicon_x->add2breadcrumbs($estate->title);
	$pdf_html = $pdf_contact = array();

	include_once DOC_ROOT.'/orbicon/modules/forms/class.form.php';
	$form = new Form;
	$counties = $form->get_pring_db_table('pring_counties', true);
	$form = null;

	$town = (is_numeric($estate->town)) ? e_get_town_by_id(intval($estate->town)) : $estate->town;

	if(empty($estate->views)) {
		$ad_views = 0;

		$entry_v = $_SERVER['REQUEST_URL'];
		$entry_v = trim(str_replace(ORBX_SITE_URL, '', $entry_v));
		$entry_v = urlencode($entry_v);
		$r_v = $dbc->_db->query(sprintf('	SELECT 		COUNT(id) AS total
											FROM 		' . TABLE_STATISTICS . '
											WHERE 		(entry = %s) AND
														(type = \'content\')', $dbc->_db->quote($entry_v)));
		$a_v = $dbc->_db->fetch_assoc($r_v);
		$ad_views = intval($a_v['total']);

		// update first time
		$q_v2 = sprintf('	UPDATE 		'.TABLE_ESTATE.'
							SET			views = %s
							WHERE 		(id=%s)',
						$dbc->_db->quote($ad_views), $dbc->_db->quote($estate->id));
		$dbc->_db->query($q_v2);
	}
	else {
		update_estate_ad_views(intval($estate->id));
		$ad_views = ($estate->views + 1);
	}

	$estate_details = $edit_shortcut . '
	<style type="text/css">h1, #breadcrumbs { width: 650px;  } #main_content { width: 647px; }</style>
    <div id="advert">';

	if(isset($_GET['preview'])) {
		$estate_details .= '<div class="preview_msg"><strong>'._L('e.adpreview').'</strong><br /><br />
'._L('e.haschange').'<br />
'._L('e.nochange').'<br />
		<!--[if IE]>
		<input type="button" value="'._L('e.closewin').'" onclick="javascript: window.close();" /></div>
		<![endif]-->';
	}

	$estate_details .= $display_content;

	if(($estate->category != 6) && ($estate->category != 7)) {

		$price = (empty($estate->price) || ($estate->price == 0.0)) ? _L('e.onreq') : number_format($estate->price, 2, ',', '.').' '.$estate_currencies[$estate->currency];

		$estate_details.= '
		<ul id="toolbar">
      		<li class="save"><a href="javascript:void(null);" onclick="javascript:fav_ad('.$estate->id.', \'add\');" title="'._L('e.savead').'">'._L('e.savead').'</a></li>
      		<li class="send2friend"><a href="javascript:void(null);" onclick="javascript:show_send2friend();">'._L('e.send2friend').'</a></li>
      		<li class="pdf"><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$_GET[$orbicon_x->ptr].'&amp;pdf&amp;c='.$_GET['c'].'">'._L('e.printad').'</a></li>
		</ul>

		<ul id="info_teaser">
	        <li><strong>'._L('e.place').': </strong>'.$town.'</li>
    	    <li><strong>'._L('e.msquare').': </strong>'.$estate->msquare.' m<sup>2</sup></li>
        	<li><strong>'._L('e.price').': </strong>'.$price.'</li>
      </ul>
      ';
	}

	$estate_details .= '
      <div id="info_part">
        <ul class="info_list">
          <li id="broj_oglasa"><strong>'._L('e.adnum').': </strong>'.$estate->id.'</li>
          <li><strong>'._L('e.adtype').': </strong>'.$estate_ad_type[$estate->ad_type].'</li>';

	if($estate->county) {
  		$estate_details.= '<li><strong>'._L('e.region').': </strong>'.$counties[$estate->county].'</li>';
  		$pdf_html[] = '<strong>'._L('e.region').': </strong>'.$counties[$estate->county];
	}

	if($estate->town) {
  		$estate_details.= '<li><strong>'._L('e.place').': </strong>'.$town.'</li>';
  		$pdf_html[] = '<strong>'._L('e.place').': </strong>'.$town;
	}

  	if($estate->neighborhood) {
  		$estate_details.= '<li><strong>'._L('e.neigh').': </strong>'.$estate->neighborhood.'</li>';
  		$pdf_html[] = '<strong>'._L('e.neigh').': </strong>'.$estate->neighborhood;

    }

	if($estate->street) {
		$estate_details.= '<li><strong>'._L('e.street').': </strong>'.$estate->street.' '.$estate->street_no.'</li>';
		$pdf_html[] = '<strong>'._L('e.street').': </strong>'.$estate->street.' '.$estate->street_no;
	}

	if($estate->apartment_type) {
		$estate_details.= '<li><strong>'._L('e.aparttypo').': </strong>'.$estate_apartment_type[$estate->apartment_type].'</li>';
		$pdf_html[] = '<strong>'._L('e.aparttypo').': </strong>'.$estate_apartment_type[$estate->apartment_type];
	}

	if($estate->house_type) {
		$estate_details.= '<li><strong>'._L('e.housetype').': </strong>'.$estate_house_type[$estate->house_type].'</li>';
		$pdf_html[] = '<strong>'._L('e.housetype').': </strong>'.$estate_house_type[$estate->house_type];
	}

      if($estate->business_type) {
      		$estate_details.= '<li><strong>'._L('e.bsntype').': </strong>'.$estate_business_type[$estate->business_type].'</li>';
 	  		$pdf_html[] = '<strong>'._L('e.bsntype').': </strong>'.$estate_business_type[$estate->business_type];
      }

      if($estate->land_type) {
      		$estate_details.= '<li><strong>'._L('e.landtype').': </strong>'.$estate_land_type[$estate->land_type].'</li>';
 	  		$pdf_html[] = '<strong>'._L('e.landtype').': </strong>'.$estate_land_type[$estate->land_type];
      }

      if($estate->width) {
      		$estate_details.= '<li><strong>'._L('e.width').': </strong>'.$estate->width.' m</li>';
      		  		$pdf_html[] = '<strong>'._L('e.width').': </strong>'.$estate->width.' m';

      }

      if($estate->length) {
      		$estate_details.= '<li><strong>'._L('e.length').': </strong>'.$estate->length.' m</li>';
      		  		$pdf_html[] = '<strong>'._L('e.length').': </strong>'.$estate->length.' m';

      }

      if($estate->msquare_backyard) {
      		$estate_details.= '<li><strong>'._L('e.msquareother').': </strong>'.$estate->msquare_backyard.' m<sup>2</sup></li>';
      		$pdf_html[] = '<strong>'._L('e.msquareother').': </strong>'.$estate->msquare_backyard.' m<sup>2</sup>';

      }

      if($estate->room_num) {
      		$estate_details.= '<li><strong>'._L('e.roomnum').': </strong>'.$estate->room_num.'</li>';
      		$pdf_html[] = '<strong>'._L('e.roomnum').': </strong>'.$estate->room_num;
      }

      if($estate->floor_num) {
      		$estate_details.= '<li><strong>'._L('e.floornum').': </strong>'.$estate->floor_num.'</li>';
      		$pdf_html[] = '<strong>'._L('e.floornum').': </strong>'.$estate->floor_num;
      }

      if($estate->sea_distance) {
      		$estate_details.= '<li><strong>'._L('e.sea').': </strong>'.$estate->sea_distance.' m</li>';
      		$pdf_html[] = '<strong>'._L('e.sea').': </strong>'.$estate->sea_distance.' m';
      }

      if($estate->bath_num) {
      		$estate_details.= '<li><strong>'._L('e.bathnum').': </strong>'.$estate->bath_num.'</li>';
      		  		$pdf_html[] = '<strong>'._L('e.bathnum').': </strong>'.$estate->bath_num;

      }

      if($estate->flat) {
      		$estate_details.= '<li><strong>'._L('e.floor').': </strong>'.$estate->flat.'</li>';
      		$pdf_html[] = '<strong>'._L('e.floor').': </strong>'.$estate->flat;
      }

      if($estate->flat_num) {
      		$estate_details.= '<li><strong>'._L('e.totalfloor').': </strong>'.$estate->flat_num.'</li>';
      		$pdf_html[] = '<strong>'._L('e.totalfloor').': </strong>'.$estate->flat_num;
      }

      if($estate->bed_num) {
      		$estate_details.= '<li><strong>'._L('e.beds').': </strong>'.$estate->bed_num.'</li>';
      		$pdf_html[] = '<strong>'._L('e.beds').': </strong>'.$estate->bed_num;
      }

      if($estate->year_built) {
      		$estate_details.= '<li><strong>'._L('e.built').': </strong>'.$estate->year_built.'.</li>';
      		$pdf_html[] = '<strong>'._L('e.built').': </strong>'.$estate->year_built;
      }

      if($estate->build_type && ($estate->category != 6) && ($estate->category != 7)) {
      		$estate_details.= '<li><strong>'._L('e.builttype').': </strong>'.$estate_build_type[$estate->build_type].'</li>';
      		$pdf_html[] = '<strong>'._L('e.builttype').': </strong>'.$estate_build_type[$estate->build_type];
      }

      if($estate->heating) {
      		$estate_details.= '<li><strong>'._L('e.heat').': </strong>'.$estate_heating_type[$estate->heating].'</li>';
      		$pdf_html[] = '<strong>'._L('e.heat').': </strong>'.$estate_heating_type[$estate->heating];
      }

		if($estate->docs && ($estate->category != 6) && ($estate->category != 7)) {
				$estate_details .= '<li><strong>'._L('e.docs').': </strong>'.$estate_docs_type[$estate->docs].'</li>';
				$pdf_html[] = '<strong>'._L('e.docs').': </strong>'.$estate_docs_type[$estate->docs];
		}

		if($estate->equipment  && ($estate->category != 6) && ($estate->category != 7)) {

          $estate_details .= '
          <li><strong>'._L('e.equip').': </strong><br />

         <label for="telefon" class="chck"><input value="'. ESTATE_EQUIP_PHONE.'" type="checkbox" id="telefon" name="telefon" '. ($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_PHONE)) ? 'checked="checked"' : '').' /> '._L('e.phone').'</label>
                  <label for="balkon" class="chck"><input value="'. ESTATE_EQUIP_BALCONY.'" type="checkbox" id="balkon" name="balkon" '. ($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_BALCONY)) ? 'checked="checked"' : '').' /> '._L('e.balcony').'</label>
                  <label for="vrt" class="chck"><input value="'. ESTATE_EQUIP_GARDEN.'" type="checkbox" id="vrt" name="vrt" '. ($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_GARDEN)) ? 'checked="checked"' : '').' /> '._L('e.garden').'</label><br />
                  <label for="garaza" class="chck"><input value="'. ESTATE_EQUIP_GARAGE.'" type="checkbox" id="garaza" name="garaza" '. ($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_GARAGE)) ? 'checked="checked"' : '').' /> '._L('e.garage').'</label>
                  <label for="klima" class="chck"><input value="'. ESTATE_EQUIP_CLIMATE.'" type="checkbox" id="klima" name="klima" '. ($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_CLIMATE)) ? 'checked="checked"' : '').' /> '._L('e.climate').'</label><br />
                  <label for="invalidi" class="chck block"><input value="'. ESTATE_EQUIP_INVALIDS.'" type="checkbox" id="invalidi" name="invalidi" '. ($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_INVALIDS)) ? 'checked="checked"' : '').' /> '._L('e.invalid').'</label>';

          if($estate->category == 4) {

                  $estate_details .= '<p>
                  <label for="bazen" class="chck"><input value="'. ESTATE_EQUIP_POOL.'" '. ($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_POOL)) ? 'checked="checked"' : '').' type="checkbox" id="bazen" name="bazen" /> '._L('e.pool').'</label>

                  <label for="tv" class="chck"><input value="'. ESTATE_EQUIP_TV.'" '. ($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_TV)) ? 'checked="checked"' : '').' type="checkbox" id="tv" name="tv" /> '._L('e.tv').'</label><br />
                  <label for="satelitska" class="chck"><input value="'. ESTATE_EQUIP_SAT_TV.'" '. ($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_SAT_TV)) ? 'checked="checked"' : '').' type="checkbox" id="satelitska" name="satelitska" /> '._L('e.sattv').'</label>
                  <label for="internet" class="chck"><input value="'. ESTATE_EQUIP_INTERNET.'" '. ($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_INTERNET)) ? 'checked="checked"' : '').' type="checkbox" id="internet" name="internet" /> '._L('e.net').'</label>
                  <label for="tereni" class="chck"><input value="'. ESTATE_EQUIP_SPORT.'" '. ($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_SPORT)) ? 'checked="checked"' : '').' type="checkbox" id="tereni" name="tereni" /> '._L('e.sport').'</label><br />
                  <label for="dvorana" class="chck"><input value="'. ESTATE_EQUIP_CONFERENCE.'" '. ($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_CONFERENCE)) ? 'checked="checked"' : '').' type="checkbox" id="dvorana" name="dvorana" /> '._L('e.hall').'</label><br />
				</p>';
				}

				if($estate->category == 3) {

				$estate_details .= '<p>

					<label for="put" class="chck"><input type="checkbox" id="put" name="put" value="'.ESTATE_EQUIP_LAND_PATH.'" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_LAND_PATH)) ? 'checked="checked"' : '').' /> '._L('e.path').'</label>
                  	<label for="struja" class="chck"><input type="checkbox" id="struja" name="struja" value="'.ESTATE_EQUIP_LAND_POWER.'" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_LAND_POWER)) ? 'checked="checked"' : '').' /> '._L('e.power').'</label>
                  	<label for="voda" class="chck"><input type="checkbox" id="voda" name="voda" value="'.ESTATE_EQUIP_LAND_WATER.'" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_LAND_WATER)) ? 'checked="checked"' : '').' /> '._L('e.water').'</label>
                  	<label for="plin" class="chck"><input type="checkbox" id="plin" name="plin" value="'.ESTATE_EQUIP_LAND_GAS.'" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_LAND_GAS)) ? 'checked="checked"' : '').' /> '._L('e.gas').'</label><br />
                 	 <label for="kanalizacija" class="chck"><input type="checkbox" id="kanalizacija" name="kanalizacija" value="'.ESTATE_EQUIP_LAND_SEWER.'" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_LAND_SEWER)) ? 'checked="checked"' : '').' /> '._L('e.sewer').'</label>
                 	 <label for="telefon_land" class="chck block"><input type="checkbox" id="telefon_land" name="telefon" value="'.ESTATE_EQUIP_PHONE.'" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_PHONE)) ? 'checked="checked"' : '').' /> '._L('e.phone').'</label>
                  	<label for="lokacijska" class="chck block"><input type="checkbox" id="lokacijska" name="lokacijska" value="'.ESTATE_EQUIP_LAND_PAPERS.'" '.($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_LAND_PAPERS)) ? 'checked="checked"' : '').' /> '._L('e.locperm').'</label>

				</p>';
				}

				$estate_details .= '</li>';
		}

				if($estate->tags) {
		          $estate_details .= '<li class="tagovi"><strong>'._L('e.tags').': </strong><br /> '.print_estate_tag_cloud(0, $estate->id).'</li>';
				}

          $estate_details .= '
        </ul>
        <dl id="info_text">
          <dt><strong>'._L('e.adtext').':</strong></dt>
          <dd>'.nl2br($estate->description).'</dd>
        </dl>

        <ul class="info_list">

        </ul>
      </div>
      <!-- galerija i karta -->
      <div id="screen">

      <link rel="stylesheet" type="text/css" href="'.ORBX_SITE_URL.'/orbicon/3rdParty/yui/build/tabview/assets/tabview.css?'.ORBX_BUILD.'" />
<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/controler/gzip.server.php?file=/orbicon/3rdParty/yui/build/tabview/tabview-min.js&amp;'.ORBX_BUILD.'"></script>
<script type="text/javascript"><!-- // --><![CDATA[

	new YAHOO.widget.TabView("media_placeholder");

// ]]></script>
<style type="text/css">
' . $class_gallery . $class_map . $class_video . '
</style>

  <div id="media_placeholder" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#fotogalerija"><em class="gallery">'._L('e.photogallery').'</em></a></li>
        <li><a href="#karta"><em class="map">'._L('e.lookmap').'</em></a></li>
        <li><a href="#video"><em class="video">'._L('e.video').'</em></a></li>
    </ul>
    <div class="yui-content">

    <div id="fotogalerija" class="clr">

    <script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/controler/gzip.server.php?file=/orbicon/3rdParty/scriptaculous/lib/prototype.js&amp;'.ORBX_BUILD.'"></script>
<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/3rdParty/scriptaculous/src/scriptaculous.js?'.ORBX_BUILD.'"></script>
	<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/3rdParty/lightbox/js/lightbox.js?'.ORBX_BUILD.'"></script>
    <link rel="stylesheet" type="text/css" href="'.ORBX_SITE_URL.'/orbicon/3rdParty/lightbox/css/lightbox.css?'.ORBX_BUILD.'" />

          '.$pic_main_thumb.'
          <p>
            '.$pics_thumbs.'
          </p>
        </div>
        <div id="karta">

        	<!--- g maps -->

			<div id="google_map_container">
				<div id="map" style="width: 390px; height: 390px"></div>
				<div id="geo" style="width: 300px;position: absolute;left: 620px;top: 100px;" class="tekst">
				</div>
			</div>

			<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/controler/gzip.server.php?file=/orbicon/modules/estate/estate.js&amp;'.ORBX_BUILD.'"></script>
        	<script src="http://maps.google.com/maps?file=api&v=2&key='.$gkey.'" type="text/javascript"></script>
<script type="text/javascript"><!-- // --><![CDATA[

function googlemap_setup()
{
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
}

	YAHOO.util.Event.addListener(window, "load", googlemap_setup);

//]]></script>

				<!-- g maps  -->
        </div>
        <div id="video">
		<p>
        '.$video.'
        </p>

        </div>
	 </div>
    </div>

 </div>
      <div class="clr"></div>
      <h2>'._L('e.contactuser').'</h2>

      <div id="advertiser_info">
        <ul>
          <li>

          '.$logo.'

          '.$user['contact_name'].' '.$user['contact_surname'].'</li>';

          $pdf_contact[] = '<strong>'._L('e.advertiser').': </strong>'.$user['contact_name'].' '.$user['contact_surname'];

			if($user['contact_email']) {
          		$estate_details .= '<li><strong>'._L('e.email').': </strong><a href="mailto:'.$user['contact_email'].'">'.$user['contact_email'].'</a></li>';
          		$pdf_contact[] = '<strong>'._L('e.email').': </strong><a href="mailto:'.$user['contact_email'].'">'.$user['contact_email'].'</a>';
			}

			if($user['contact_phone']) {
          		$estate_details .= '<li><strong>'._L('e.phone').': </strong>'.format_phone($user['contact_phone'], $user['contact_phone_a'], $user['contact_phone_b']).'</li>';
          		$pdf_contact[] = '<strong>'._L('e.phone').': </strong>'.format_phone($user['contact_phone'], $user['contact_phone_a'], $user['contact_phone_b']);
			}

			if($user['contact_url']) {
				$user['contact_url'] = (strpos($user['contact_url'], 'http://') === false) ? 'http://' . $user['contact_url'] : $user['contact_url'];

          		$estate_details .= '<li><strong>'._L('e.www').': </strong><a target="_blank" href="'.$user['contact_url'].'">'.$user['contact_url'].'</a></li>';
          		$pdf_contact[] = '<strong>'._L('e.www').': </strong><a target="_blank" href="'.$user['contact_url'].'">'.$user['contact_url'].'</a>';
			}

        $estate_details .= '</ul>
        <ul>';

        if($user['contact_address']) {
          		$estate_details .= '<li><strong>'._L('e.address').': </strong>'.$user['contact_address'].'</li>';
          		$pdf_contact[] = '<strong>'._L('e.address').': </strong>'.$user['contact_address'];
        }
          if($user['contact_city']) {
          		$estate_details .= '<li><strong>'._L('e.zip').': </strong>'.$user['contact_zip'].' '.$user['contact_city'].'</li>';
          		$pdf_contact[] = '<strong>'._L('e.zip').': </strong>'.$user['contact_zip'].' '.$user['contact_city'];
          }
          if($user['contact_gsm']) {
          		$estate_details .= '<li><strong>'._L('e.otherphone').': </strong>'.format_phone($user['contact_gsm']).' </li>';
          		$pdf_contact[] = '<strong>'._L('e.otherphone').': </strong>'.format_phone($user['contact_gsm']);
          }
          if($user['contact_fax']) {
          		$estate_details .= '<li><strong>'._L('e.fax').': </strong>'.$user['contact_fax'].' </li>';
          		$pdf_contact[] = '<strong>'._L('e.fax').': </strong>'.$user['contact_fax'];
          }

        $estate_details .= '<ul>
      </div>

      <form action="" method="post" id="posalji_poruku" onsubmit="javascript: return verify_mailform();">
        <fieldset>
          <legend>'._L('e.contactuser_b').'</legend>
          <p>'.sprintf(_L('e.asteriskfields'), '<span>*</span>').'.</p>
          <label for="ko_ime">'._L('e.namesurname').' <span>*</span></label>
          <input type="text" id="ko_ime" name="ko_ime" class="input_text" value="'.$me['contact_name'].' '.$me['contact_surname'].'" /><br />
          <label for="ko_email">'._L('e.email').' <span>*</span></label>
          <input type="text" id="ko_email" name="ko_email" class="input_text" value="'.$me['contact_email'].'" /><br />
          <label for="ko_tel">'._L('e.phone').' <span>*</span></label>
          <input type="text" id="ko_tel" name="ko_tel" class="input_text" value="'.format_phone($me['contact_phone'], $me['contact_phone_a'], $me['contact_phone_b']).'" /><br />
          <label for="ko_cijena">'._L('e.barterprice').'</label>
          <input type="text" id="ko_cijena" name="ko_cijena" class="input_text small" /><br />
          <label for="ko_poruka">'._L('e.msg').' <span>*</span></label>
          <textarea id="ko_poruka" name="ko_poruka"></textarea><br />
          <input '.($disabled = (isset($_GET['preview'])) ? ' disabled="disabled"' : '').' type="submit" id="send" name="send" value="'._L('e.send').'" class="bttn" />
        </fieldset>
      </form>
       <div class="clr"></div>
       '.$flags.'
       <div class="clr"></div>
      <div class="ad_views">'.sprintf(_L('e.adviews'), $ad_views, date($_SESSION['site_settings']['date_format'], $estate->submited)).'</div>
      <div class="clr"></div>
      <h3>'._L('e.asseenontv').' <a href="'.ORBX_SITE_URL.'">'.str_replace('http://', '', ORBX_SITE_URL).'</a></h3>
    </div>';

    if(isset($_GET['pdf'])) {

		require_once DOC_ROOT.'/orbicon/3rdParty/tcpdf/config/lang/eng.php';
		require_once DOC_ROOT.'/orbicon/3rdParty/tcpdf/tcpdf.php';

		//create new PDF document (document units are set by default to millimeters)
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);

		//set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor
		$pdf->setLanguageArray($l); //set language items
		// add page header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		$pdf->SetAuthor(DOMAIN_OWNER);
		$pdf->SetCreator(ORBX_FULL_NAME);
		$pdf->SetCompression(true);
		$pdf->SetTitle($estate->title);

		//initialize document
		$pdf->AliasNbPages();

		$pdf->AddPage();

		$pdf->Image(DOC_ROOT . '/site/gfx/print_bianco.png', 0, 0, 210, 300);

		$pdf->SetFont('vera', 'B', 16);
		$pdf->writeHTMLCell(0, 0, 0, 40, substr($estate->title, 0, 52));

		$price = (empty($estate->price) || ($estate->price == 0.0)) ? _L('e.onreq') : number_format($estate->price, 2, ',', '.').' '.$estate_currencies[$estate->currency];

		$pdf->SetFont('vera', '', 10);
		$pdf->writeHTMLCell(0, 0, 27, 57, $town);
		$pdf->writeHTMLCell(0, 0, 113, 57, $estate->msquare . ' m<sup>2</sup>');
		$pdf->writeHTMLCell(0, 0, 170, 57, $price);

		$pic_main_type = getimagesize($pdf_image);
		if(($pic_main_type[2] == IMAGETYPE_JPEG) ||
		($pic_main_type[2] == IMAGETYPE_PNG)) {
			$pdf->Image($pdf_image, 91, 72, 100);
		}

		$pdf->writeHTMLCell(0, 0, 36, 72, $estate->id);

		$pdf_html = '<br>'.implode('<br>', $pdf_html);
		$pdf->writeHTMLCell(0, 0, 7, 72, $pdf_html);

		$pdf->SetXY(8, 182);
		$pdf->MultiCell(0, 4, $estate->description);

		$pdf_contact = '<br>' . implode('<br>', $pdf_contact);
		$pdf->writeHTMLCell(0, 0, 7, 235, $pdf_contact);

		//Close and output PDF document
		ob_clean();
		$pdf->Output("$estate->permalink.pdf", 'I');
		session_write_close();
		exit();
    }
    else {
		return $estate_details;
    }

?>