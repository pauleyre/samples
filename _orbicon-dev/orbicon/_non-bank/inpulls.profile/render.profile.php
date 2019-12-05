<?php
/**
 * Profile details
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package OrbiconMOD
 * @subpackage Inpulls
 * @version 1.0
 * @link http://www.inpulls.com
 * @license http://
 * @since 2007-10-01
 * @todo Translation
 */

	// non members begone
	if(!get_is_member()) {
		redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.peoplering&sp=user&user=' . $_GET['user']);
	}

	require_once DOC_ROOT . '/orbicon/modules/inpulls/inc.inpulls.php';

	global $dbc;

	$q = sprintf('	SELECT 		pring_contact_id
					FROM 		'.TABLE_REG_USERS.'
					WHERE		(username = %s) AND
								(banned = 0)
					LIMIT 		1', $dbc->_db->quote($_GET['user']));

	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_assoc($r);

	include_once DOC_ROOT . '/orbicon/modules/peoplering/class/class.peoplering.php';
	$pr = new Peoplering($a['pring_contact_id']);
	$pr_user = $pr->get_profile($a['pring_contact_id']);

	$iprofile = get_iprofile_from_pring($a['pring_contact_id']);

	if(!$r || !$iprofile) {
		$pr_user['contact_name'] = '404 Not Found';
		header('HTTP/1.1 404 Not Found', true);
		$_SESSION['cache_status'] = 404;
	}

	update_profile_views(intval($iprofile->id));

	$lat = floatval($iprofile->latitude);
	$lon = floatval($iprofile->logitude);

	$lat = empty($lat) ? 45.796255 : $lat;
	$lon = empty($lon) ? 15.954895 : $lon;

	$class_map = (($lat == 45.796255) && ($lon == 15.954895)) ? '.map {text-decoration:line-through;}' : '';

	$profile_rid = $pr->get_rid_from_prid($a['pring_contact_id']);

	$username = $pr->get_username($profile_rid);
	$username = $username['username'];
	$page_title = ($pr_user['contact_name']) ? $pr_user['contact_name'] . ' ' . $pr_user['contact_surname'] : $username;
	$orbicon_x->set_page_title($page_title);
	$orbicon_x->set_page_metatag('keywords', $iprofile->keywords);
	$orbicon_x->set_page_metatag('description', $iprofile->more_info);

	$picture = $pr_user['picture'];

	if(is_file(DOC_ROOT . '/site/venus/' . $picture)) {
		$picture = ORBX_SITE_URL.'/site/venus/' . $picture;
	}
	else {
		$picture = ORBX_SITE_URL.'/orbicon/modules/peoplering/gfx/unknownUser.gif';
	}

	if($iprofile->video && is_file(DOC_ROOT . '/site/mercury/' . $iprofile->video)) {
		include_once DOC_ROOT . '/orbicon/class/inc.mmedia.php';
		$video = get_flv_player($iprofile->video, 390, 304);
	}
	else {
		$video = 'Korisnik nema video';
		$class_video = '.video {text-decoration:line-through;}';
	}

	// pictures START

	$r_img = $dbc->_db->query(sprintf('		SELECT 		*
											FROM 		'.VENUS_IMAGES.'
											WHERE 		(category = %s)
											ORDER BY 	last_modified DESC
											LIMIT 		6',
	$dbc->_db->quote("pring_u_$username")));

	$a_img = $dbc->_db->fetch_assoc($r_img);

	if(is_file(DOC_ROOT . '/site/venus/' . $a_img['permalink'])) {
		$pic_main_thumb = '
		<div class="screen">
			<img src="'.ORBX_SITE_URL.'/site/venus/'.$a_img['permalink'].'" alt="Galerija slika" class="main" />
		</div>
		<div id="opis_slike">'.$pic_main_desc.'</div>';
		$pdf_image = DOC_ROOT . '/site/venus/' . $a_img['permalink'];
	}

	$i = 1;
	$pics_thumbs = '';
	$pics = explode(';', $estate->pics);
	$pics = array_merge(array($estate->pic_main), $pics);

	while ($a_img) {
		if(is_file(DOC_ROOT . '/site/venus/' . $a_img['permalink'])) {

			if(is_file(DOC_ROOT . '/site/venus/thumbs/t-' . $a_img['permalink'])) {
				$thumb = ORBX_SITE_URL.'/site/venus/thumbs/t-'.$a_img['permalink'];
			}
			else {
				$thumb = ORBX_SITE_URL.'/site/venus/'.$a_img['permalink'];
			}

			$pic_desc = $a_img['description'];

			$pics_thumbs .= '<a href="'.ORBX_SITE_URL.'/site/venus/'.$a_img['permalink'].'" rel="lightbox[gallery]" title="'.$pic_desc.'"><img id="image' . $i . '" src="'.$thumb.'" alt="'.$pic_desc.'" class="thumb" title="'.$pic_desc.'" /></a>';
		}

		$i ++;
		$a_img = $dbc->_db->fetch_assoc($r_img);
	}

	if($pics_thumbs == '') {
		$pics_thumbs = 'Korisnik nema fotoalbum';
		$class_gallery = '.gallery {text-decoration:line-through;}';

	}
	else {
		$pics_thumbs .= '<div style="clear:both"></div><p id="view_all_photos"><a href="' . ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=gallery&amp;user='.$username.'" title="">Pogledaj cijeli fotoalbum</a></p>';
	}

	// pictures END

	// comments START

	if(isset($_POST['comment_submit'])) {
		add_new_comment($_POST['content'], $_SESSION['user.r']['id'], $profile_rid);
	}

	$comments = print_comments($profile_rid, $pr);

	if(!$comments) {
		$class_comments = '.comments {text-decoration:line-through;}';
		$comments = 'Korisnik nema komentara';
	}

	$comment_form = '<br>
	<a href="javascript:void(null)" onclick="javascript:sh(\'form_comments\');rte_lite_load();__rte_lite_attach();">Napiši komentar</a><br><br>

	<style type="text/css"><!--

#form_forum input[type="text"],
#form_contact .input-text {
	width:99%;
}
#form_forum img {
	vertical-align:bottom;
}
#rte_lite_content {
	height: 250px !important;
}
--></style>
<script type="text/javascript"><!-- // --><![CDATA[

	function __rte_lite_attach() {
		YAHOO.util.Event.addListener($(\'form_comments\'), "submit", function () {$(\'content\').value = rte_lite.body.innerHTML;});
	}

// ]]></script>
<form id="form_comments" action="" method="post" class="h">
<script type="text/javascript"><!-- // --><![CDATA[
	document.write(\'<input type="hidden" id="content" name="content" />\');
// ]]></script>
			<table style="width: 100%;">
		<tr>
			<td style="vertical-align:top;">

				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/smile.png" alt=":)" title=":)" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/dunno.png" alt=":/" title=":/" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/wink.png" alt=";)" title=";)" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/veryhappy.png" alt=":D" title=":D" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/sad.png" alt=":(" title=":(" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/serious.png" alt=":|" title=":|" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/tongue.png" alt=":P" title=":P" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/yelling.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/zipped.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/angel.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/badhairday.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/cool.png" alt="8)" title="8)" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/crying.png" alt=":\')" title=":\')" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/embarrassed.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/evil.png" alt=">:)" title=">:)" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/huh.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/lmao.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/nerd.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/oooh.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/retard.png" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/sarcastic.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/sleepy.png" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/teeth.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/beer.png" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/gift.png" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/love.png" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/cd.png" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/note.png" /></a>

			</td>
			<td style="vertical-align:top;">
			<a href="javascript:void(null);" onclick="javascript:rte_lite_bold();"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/text_bold.png" alt="text_bold.png" title="Bold (CTRL + B)" /></a>
			<a href="javascript:void(null);" onclick="javascript:rte_lite_italic();"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/text_italic.png" alt="text_italic.png" title="Italic (CTRL + I)" /></a>
			<a href="javascript:void(null);" onclick="javascript:rte_lite_underline();"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/text_underline.png" alt="text_underline.png" title="Underline (CTRL + U)" /></a>
			<a href="javascript:void(null);" onclick="javascript:rte_lite_strikethrough();"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/text_strikethrough.png" alt="text_strikethrough.png" title="Strikethrough" /></a>
			<a href="javascript:void(null);" onclick="javascript:rte_lite_link();"><img src="'.ORBX_SITE_URL.'/orbicon/rte/rte_buttons/link.gif" alt="link.gif" title="Link (CTRL + K)" /></a>
			<br />

<script type="text/javascript"><!-- // --><![CDATA[
	document.write(\'<iframe id="rte_lite_content" class="input-text"></iframe>\');
// ]]></script>
			<noscript>
				<div style="width: 99%;"><textarea name="content" style="width: 100%; height: 250px;"></textarea></div>
			</noscript>
			<input id="comment_submit" type="submit" name="comment_submit" value="'._L('submit').'">
			</td>
		</tr>
			</table>
			</form>'
	. $comments;

	// comments END

	// add admin edit shortcut to magister db
	if(get_is_admin()) {
		$edit_shortcut = $orbicon_x->admin_layout('<p><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/peoplering&amp;sp=profile&amp;id='.$pr_user['id'].'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/edit.png" /></a></p>');
	}
	elseif (get_is_member() && ($pr_user['id'] == $_SESSION['user.r']['pring_contact_id'])) {
		$edit_shortcut = '<p><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/edit.png" /></a></p>';
	}

	$orbicon_x->add2breadcrumbs($pr_user['contact_name']);
	$pdf_html = $pdf_contact = array();

	include_once DOC_ROOT.'/orbicon/modules/forms/class.form.php';
	$form = new Form();
	$counties = $form->get_pring_db_table('pring_counties', true);
	$countries = $form->get_pring_db_table('pring_countries', true);
	$form = null;

	$town = ($pr_user['contact_city']) ? get_town_by_id(intval($pr_user['contact_city'])) : $pr_user['contact_town_text'];

	if(!$town) {
		$town = 'Ne zna se...';
	}

	$age = get_age($pr_user['contact_dob']);
	if(!$age) {
		$age = '???';
	}

	$js_name = str_sanitize($_GET['user'], STR_SANITIZE_JAVASCRIPT);
	$js_name = addslashes(str_replace('"', '', $js_name));

	add_profile_viewer($iprofile->pring_id, $_SESSION['user.r']['username'] . '*!**!*' . $_SESSION['user.r']['contact_name'] . ' ' . $_SESSION['user.r']['contact_surname']);

	$profile_details = $edit_shortcut . '
	<style type="text/css">h1, #breadcrumbs { width: 700px;  }</style>
	<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/modules/peoplering/library/functions.js?'.ORBX_BUILD.'"></script>
    <div id="advert">';

	$profile_details .= $display_content;

	$profile_details.= '

	<ul id="toolbar2">
  		<li class="sendmsg"><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=mail&amp;to='.$_GET['user'].'">'._L('pr-send-msg').'</a></li>
  		<li class="addcontacts"><a href="javascript:void(null);" onclick="javascript:add2contacts(\'' . $js_name . '\');">'._L('pr-add-contacts').'</a></li>
  		<li class="friends"><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=friends&amp;user='.$_GET['user'].'">'._L('pr-view-friends').'</a></li>
	</ul>

	<ul id="toolbar">
  		<li class="save"><a href="javascript:void(null);" onclick="javascript:fav_profile('.$iprofile->id.', \'add\');" title="Spremi korisnika">Spremi korisnika</a></li>
  		<li class="send2friend"><a href="javascript:void(null);" onclick="javascript:show_send2friend();">Pošalji prijatelju</a></li>
  		<li class="pdf"><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$_GET[$orbicon_x->ptr].'&amp;pdf&amp;user='.$_GET['user'].'">Ispiši profil (PDF)</a></li>
  		<li class="cezar"><a id="showcezar" href="javascript:void(null)">Stisni '.($msg = ($pr_user['contact_sex']) ? 'joj' : 'mu').' cezara</a></li>
	</ul>

	<ul id="info_teaser">
        <li><strong>Mjesto: </strong>'.$town.'</li>
	    <li><strong>Godine (spol): </strong>'.$age . ' ' . ($sex = (!$pr_user['contact_sex']) ? '(M)' : '(Ž)') . '</li>
    	<li><strong>Traži: </strong>'.$inpulls_im_here_for[$iprofile->im_here_for].'</li>
  </ul>';

	$profile_details .= '
      <div id="info_part">
        <ul class="info_list">
          <li style="text-align:center"><img src="'.$picture.'" style="width:200px" /></li>
           <li id="broj_oglasa"><strong>Korisnički broj: </strong>'.$iprofile->pring_id.'</li>
           <li><strong>Status: </strong>'.($online = inpulls_is_online($iprofile->pring_id) ? '<span class="green">Online</span>' : '<span class="red">Offline</span>').'</li>
            <li><strong>Član od: </strong>'.date($_SESSION['site_settings']['date_format'], $iprofile->registered).'</li>';

	$pdf_html[] = '<strong>Korisnički broj: </strong>'.$iprofile->pring_id;

	if($pr_user['contact_country']) {
		$country = get_country_by_id(intval($pr_user['contact_country']));

		if(intval($pr_user['contact_country']) == 82) {
			$country['title'] = 'Hrvatska';
		}

  		$profile_details.= '<li><strong>Zemlja: </strong><img src="./orbicon/gfx/flag_icons/'.$country['domain_ext'].'.gif" alt="'.$country['title'].'" title="'.$country['title'].'" style="border: 1px solid #E1E1E1; padding: 2px;" /> '.$country['title'].'</li>';
  		$pdf_html[] = '<strong>Zemlja: </strong>'.$country['title'];
	}

	if($pr_user['contact_region']) {
  		$profile_details.= '<li><strong>Županija: </strong>'.$counties[$pr_user['contact_region']].'</li>';
  		$pdf_html[] = '<strong>Županija: </strong>'.$counties[$pr_user['contact_region']];
	}

	if($town) {
  		$profile_details.= '<li><strong>Mjesto: </strong>'.$town.'</li>';
  		$pdf_html[] = '<strong>Mjesto: </strong>'.$town;
	}

  	if($pr_user['contact_address']) {
  		$profile_details.= '<li><strong>Adresa: </strong>'.$pr_user['contact_address'].'</li>';
  		$pdf_html[] = '<strong>Adresa: </strong>'.$pr_user['contact_address'];
    }

	if($pr_user['contact_url']) {
		$url = (strpos('http://', $pr_user['contact_url']) === false) ? "http://{$pr_user['contact_url']}" : $pr_user['contact_url'];
		$url = (strpos('@', $url) !== false) ? 'mailto:' . $pr_user['contact_url'] : $url;
		$url_outer = '<a href="'.$url.'" rel="nofollow">'.$pr_user['contact_url'].'</a>';
		$profile_details.= '<li><strong>WWW: </strong>'.$url_outer.'</li>';
		$pdf_html[] = '<strong>WWW: </strong>'.$url;
	}

	if($iprofile->im_here_for) {
		$profile_details.= '<li><strong>Ovdje tražim: </strong>'.$inpulls_im_here_for[$iprofile->im_here_for].'</li>';
		$pdf_html[] = '<strong>Ovdje tražim: </strong>'.$inpulls_im_here_for[$iprofile->im_here_for];
	}

	if($iprofile->currently_im) {
		$profile_details.= '<li><strong>Trenutno sam: </strong>'.$inpulls_currently_im[$iprofile->currently_im].'</li>';
	  	$pdf_html[] = '<strong>Trenutno sam: </strong>'.$inpulls_currently_im[$iprofile->currently_im];
	}

	if($iprofile->sex_group) {
		$profile_details.= '<li><strong>Spolno opredjeljenje: </strong>'.$inpulls_sex_group[$iprofile->sex_group].'</li>';
	  	$pdf_html[] = '<strong>Spolno opredjeljenje: </strong>'.$inpulls_sex_group[$iprofile->sex_group];
	}

	if($iprofile->life_moto) {
		$profile_details.= '<li><strong>Životni moto: </strong>'.$iprofile->life_moto.'</li>';
	  	$pdf_html[] = '<strong>Životni moto: </strong>'.$iprofile->life_moto;
	}

	if($iprofile->im_proud_of) {
		$profile_details.= '<li><strong>Ponosim se: </strong>'.$iprofile->im_proud_of.'</li>';
	  	$pdf_html[] = '<strong>Ponosim se: </strong>'.$iprofile->im_proud_of;
	}

	if($iprofile->life_hero) {
		$profile_details.= '<li><strong>Životni uzor: </strong>'.$iprofile->life_hero.'</li>';
	  	$pdf_html[] = '<strong>Životni uzor: </strong>'.$iprofile->life_hero;
	}

	if($iprofile->activities) {
		$profile_details.= '<li><strong>Aktivnosti: </strong>'.$iprofile->activities.'</li>';
	  	$pdf_html[] = '<strong>Aktivnosti: </strong>'.$iprofile->activities;
	}

	if($iprofile->hobby) {
		$profile_details.= '<li><strong>Hobi: </strong>'.$iprofile->hobby.'</li>';
	  	$pdf_html[] = '<strong>Hobi: </strong>'.$iprofile->hobby;
	}

	if($iprofile->horoscope) {
		$profile_details.= '<li><strong>Horoskop: </strong><a href="'.ORBX_SITE_URL.'/?horoscope='.$iprofile->horoscope.'&amp;poredak=online&amp;submit_bp=1"><img src="'.ORBX_SITE_URL.'/orbicon/modules/inpulls/gfx/horoscope/'.$inpulls_horoscope_gfx[$iprofile->horoscope].'" alt="'.$inpulls_horoscope[$iprofile->horoscope].'" title="'.$inpulls_horoscope[$iprofile->horoscope].'" />'.$inpulls_horoscope[$iprofile->horoscope].'</a></li>';
	  	$pdf_html[] = '<strong>Horoskop: </strong>'.$inpulls_horoscope[$iprofile->horoscope];
	}

	if($iprofile->eye_color) {
		$profile_details.= '<li><strong>Boja očiju: </strong>'.$iprofile->eye_color.'</li>';
	  	$pdf_html[] = '<strong>Boja očiju: </strong>'.$iprofile->eye_color;
	}

      if($iprofile->hair_color) {
      		$profile_details.= '<li><strong>Boja kose: </strong>'.$iprofile->hair_color.'</li>';
      		$pdf_html[] = '<strong>Boja kose: </strong>'.$iprofile->hair_color;
      }

      if($iprofile->what_attracts_you_most) {
      		$profile_details.= '<li><strong>Što te najviše privlači kod druge osobe? </strong>'.$iprofile->what_attracts_you_most.'</li>';
      		$pdf_html[] = '<strong>Što te najviše privlači kod druge osobe? </strong>'.$iprofile->what_attracts_you_most;
      }

      if($iprofile->best_physical_feature) {
      		$profile_details.= '<li><strong>Tvoja najbolja fizička osobina: </strong>'.$iprofile->best_physical_feature.'</li>';
      		$pdf_html[] = '<strong>Tvoja najbolja fizička osobina: </strong>'.$iprofile->best_physical_feature;
      }

      if($iprofile->hair_length) {
      		$profile_details.= '<li><strong>Duljina kose: </strong>'.$iprofile->hair_length.'</li>';
      		$pdf_html[] = '<strong>Duljina kose: </strong>'.$iprofile->hair_length;
      }

      if($iprofile->height && ($iprofile->height != 0.00)) {
      		$profile_details.= '<li><strong>Visina: </strong>' . $iprofile->height . ' m</li>';
      		$pdf_html[] = '<strong>Visina: </strong>'.$iprofile->height  . ' m';
      }

      if($iprofile->weight) {
      		$profile_details.= '<li><strong>Težina: </strong>'.$iprofile->weight.' kg</li>';
      		$pdf_html[] = '<strong>Težina: </strong>'.$iprofile->weight.' kg';
      }

      if($iprofile->tattoo_piercings) {
      		$profile_details.= '<li><strong>Tetovaže / piercing: </strong>'.$iprofile->tattoo_piercings.'</li>';
      		$pdf_html[] = '<strong>Tetovaže / piercing: </strong>'.$iprofile->tattoo_piercings;
      }

       if($iprofile->smoker) {
      		$profile_details.= '<li><strong>Cigarete: </strong>'.$inpulls_smoker[$iprofile->smoker].'</li>';
      		$pdf_html[] = '<strong>Cigarete: </strong>'.$inpulls_smoker[$iprofile->smoker];
      }

      if(!$pr_user['contact_sex']) {

      	  if($iprofile->treat_girls) {
      		$profile_details.= '<li><strong>Djevojku tretiram: </strong>'.$inpulls_treat_girls[$iprofile->treat_girls].'</li>';
      		$pdf_html[] = '<strong>Djevojku tretiram: </strong>'.$inpulls_treat_girls[$iprofile->treat_girls];
	      }

	        if($iprofile->had_girls) {
	      		$profile_details.= '<li><strong>Imao sam dosad: </strong>'.$inpulls_had_girls[$iprofile->had_girls].'</li>';
	      		$pdf_html[] = '<strong>Imao sam dosad: </strong>'.$inpulls_had_girls[$iprofile->had_girls];
	      }

	        if($iprofile->crazy_thing_for_girls) {
	      		$profile_details.= '<li><strong>Najluđe što sam dosad (ili što bi) napravio za djevojku? </strong>'.$iprofile->crazy_thing_for_girls.'</li>';
	      		$pdf_html[] = '<strong>Najluđe što sam dosad (ili što bi) napravio za djevojku? </strong>'.$iprofile->crazy_thing_for_girls;
	      }

	        if($iprofile->shopping_with_girl) {
	      		$profile_details.= '<li><strong>Jesi spreman ići sa djevojkom u &quot;kratki&quot; shopping da te lijepo zamoli? 5 puta tjedno? </strong>'.$inpulls_go_shopping[$iprofile->shopping_with_girl].'</li>';
	      		$pdf_html[] = '<strong>Jesi spreman ići sa djevojkom u &quot;kratki&quot; shopping da te lijepo zamoli? 5 puta tjedno? </strong>'.$inpulls_go_shopping[$iprofile->shopping_with_girl];
	      }

	        if($iprofile->monthly_income) {
	      		$profile_details.= '<li><strong>Mjesečna primanja: </strong>'.$iprofile->monthly_income.'</li>';
	      		$pdf_html[] = '<strong>Mjesečna primanja: </strong>'.$iprofile->monthly_income;
	      }

	        if($iprofile->special_skills) {
	      		$profile_details.= '<li><strong>Dodatne vještine kojima se ponosiš: </strong>'.$inpulls_special_skills[$iprofile->special_skills].'</li>';
	      		$pdf_html[] = '<strong>Dodatne vještine kojima se ponosiš: </strong>'.$inpulls_special_skills[$iprofile->special_skills];
	      }

	        if($iprofile->you_in_future) {
	      		$profile_details.= '<li><strong>Kakvim se vidiš u budućnosti? </strong>'.$inpulls_see_in_future[$iprofile->you_in_future].'</li>';
	      		$pdf_html[] = '<strong>Kakvim se vidiš u budućnosti? </strong>'.$inpulls_see_in_future[$iprofile->you_in_future];
	      }

	        if($iprofile->message_for_future_girl) {
	      		$profile_details.= '<li><strong>Poruka za potencijalnu djevojku: </strong>'.$iprofile->message_for_future_girl.'</li>';
	      		$pdf_html[] = '<strong>Poruka za potencijalnu djevojku: </strong>'.$iprofile->message_for_future_girl;
	      }


      }
      elseif ($pr_user['contact_sex']) {

      	if($iprofile->when_i_was_little) {
	      		$profile_details.= '<li><strong>Kad sam bila mala htjela sam biti: </strong>'.$inpulls_when_i_was_little[$iprofile->when_i_was_little].'</li>';
	      		$pdf_html[] = '<strong>Kad sam bila mala htjela sam biti: </strong>'.$inpulls_when_i_was_little[$iprofile->when_i_was_little];
	      }
	      if($iprofile->all_day) {
	      		$profile_details.= '<li><strong>Da mogu po cijele dane samo bi: </strong>'.$inpulls_if_i_could[$iprofile->all_day].'</li>';
	      		$pdf_html[] = '<strong>Da mogu po cijele dane samo bi: </strong>'.$inpulls_if_i_could[$iprofile->all_day];
	      }

	      if($iprofile->from_boyfriend) {
	      		$profile_details.= '<li><strong>Od dečka očekujem: </strong>'.$inpulls_from_boyfriend[$iprofile->from_boyfriend].'</li>';
	      		$pdf_html[] = '<strong>Od dečka očekujem: </strong>'.$inpulls_from_boyfriend[$iprofile->from_boyfriend];
	      }

	      if($iprofile->monthly_income) {
	      		$profile_details.= '<li><strong>Mjesečna primanja: </strong>'.$iprofile->monthly_income.'</li>';
	      		$pdf_html[] = '<strong>Mjesečna primanja: </strong>'.$iprofile->monthly_income;
	      }

	      if($iprofile->special_skills_girls) {
	      		$profile_details.= '<li><strong>Dodatne vještine zbog kojih se ponosim: </strong>'.$inpulls_special_skills_girl[$iprofile->special_skills_girls].'</li>';
	      		$pdf_html[] = '<strong>Dodatne vještine zbog kojih se ponosim: </strong>'.$inpulls_special_skills_girl[$iprofile->special_skills_girls];
	      }

      	 if($iprofile->message_for_future_boy) {
	      		$profile_details.= '<li><strong>Poruka za potencijalnog dečka: </strong>'.$iprofile->message_for_future_boy.'</li>';
	      		$pdf_html[] = '<strong>Poruka za potencijalnog dečka: </strong>'.$iprofile->message_for_future_boy;
	      }
      }




	if($iprofile->heritage) {
		$profile_details.= '<li><strong>Od kud su tvoji? </strong>'.$iprofile->heritage.'</li>';
	  	$pdf_html[] = '<strong>Od kud su tvoji? </strong>'.$iprofile->heritage;
	}

	if($iprofile->favorite_food) {
		$profile_details.= '<li><strong>Omiljena hrana: </strong>'.$iprofile->favorite_food.'</li>';
	  	$pdf_html[] = '<strong>Omiljena hrana: </strong>'.$iprofile->favorite_food;
	}

	if($iprofile->favorite_book) {
		$profile_details.= '<li><strong>Omiljena knjiga: </strong>'.$iprofile->favorite_book.'</li>';
	  	$pdf_html[] = '<strong>Omiljena knjiga: </strong>'.$iprofile->favorite_book;
	}

	if($iprofile->favorite_movie) {
		$profile_details.= '<li><strong>Omiljeni film: </strong>'.$iprofile->favorite_movie.'</li>';
	  	$pdf_html[] = '<strong>Omiljeni film: </strong>'.$iprofile->favorite_movie;
	}

	if($iprofile->favorite_actor) {
		$profile_details.= '<li><strong>Omiljeni glumac/ica: </strong>'.$iprofile->favorite_actor.'</li>';
	  	$pdf_html[] = '<strong>Omiljeni glumac/ica: </strong>'.$iprofile->favorite_actor;
	}

	if($iprofile->favorite_band) {
		$profile_details.= '<li><strong>Omiljeni band: </strong>'.$iprofile->favorite_band.'</li>';
	  	$pdf_html[] = '<strong>Omiljeni band: </strong>'.$iprofile->favorite_band;
	}


	if($iprofile->favorite_song) {
		$profile_details.= '<li><strong>Omiljena pjesma: </strong>'.$iprofile->favorite_song.'</li>';
	  	$pdf_html[] = '<strong>Omiljena pjesma: </strong>'.$iprofile->favorite_song;
	}



		if($iprofile->music) {

          $profile_details .= '
          <li><strong>Glazba: </strong><br />

<span><input type="checkbox" name="music_1" id="music_1" value="1" '.($checked = (get_inpulls_flag($iprofile->music, 1)) ? 'checked="checked"' : '').' /> <label for="music_1">'.$inpulls_music[1].'</label></span>
<span><input type="checkbox" name="music_2" id="music_2" value="2" '.($checked = (get_inpulls_flag($iprofile->music, 2)) ? 'checked="checked"' : '').'/> <label for="music_2">'.$inpulls_music[2].'</label></span>
<span><input type="checkbox" name="music_3" id="music_3" value="4" '.($checked = (get_inpulls_flag($iprofile->music, 4)) ? 'checked="checked"' : '').'/> <label for="music_3">'.$inpulls_music[4].'</label></span>
<span><input type="checkbox" name="music_4" id="music_4" value="8" '.($checked = (get_inpulls_flag($iprofile->music, 8)) ? 'checked="checked"' : '').'/> <label for="music_4">'.$inpulls_music[8].'</label></span>
<span><input type="checkbox" name="music_5" id="music_5" value="16" '.($checked = (get_inpulls_flag($iprofile->music, 16)) ? 'checked="checked"' : '').'/> <label for="music_5">'.$inpulls_music[16].'</label></span>
<span><input type="checkbox" name="music_6" id="music_6" value="32" '.($checked = (get_inpulls_flag($iprofile->music, 32)) ? 'checked="checked"' : '').'/> <label for="music_6">'.$inpulls_music[32].'</label></span><br />
<span><input type="checkbox" name="music_7" id="music_7" value="64" '.($checked = (get_inpulls_flag($iprofile->music, 64)) ? 'checked="checked"' : '').'/> <label for="music_7">'.$inpulls_music[64].'</label></span>
<span><input type="checkbox" name="music_8" id="music_8" value="128" '.($checked = (get_inpulls_flag($iprofile->music, 128)) ? 'checked="checked"' : '').'/> <label for="music_8">'.$inpulls_music[128].'</label></span>
<span><input type="checkbox" name="music_9" id="music_9" value="256" '.($checked = (get_inpulls_flag($iprofile->music, 256)) ? 'checked="checked"' : '').'/> <label for="music_9">'.$inpulls_music[256].'</label></span>
<span><input type="checkbox" name="music_10" id="music_10" value="512" '.($checked = (get_inpulls_flag($iprofile->music, 512)) ? 'checked="checked"' : '').'/> <label for="music_10">'.$inpulls_music[512].'</label></span>
<span><input type="checkbox" name="music_11" id="music_11" value="1024" '.($checked = (get_inpulls_flag($iprofile->music, 1024)) ? 'checked="checked"' : '').'/> <label for="music_11">'.$inpulls_music[1024].'</label></span>';


				$profile_details .= '</li>';
		}

			if($iprofile->favorite_drinks) {
	      $profile_details .= '<li class="tagovi"><strong>Omiljena cuga: </strong><br />

<span><input type="checkbox" id="drink_1" name="drink_1" value="1" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 1)) ? 'checked="checked"' : '').' /> <label for="drink_1">'.$inpulls_drinks[1].'</label></span>
<span><input type="checkbox" id="drink_2" name="drink_2" value="2" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 2)) ? 'checked="checked"' : '').'/> <label for="drink_2">'.$inpulls_drinks[2].'</label></span>
<span><input type="checkbox" id="drink_3" name="drink_3" value="4" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 4)) ? 'checked="checked"' : '').'/> <label for="drink_3">'.$inpulls_drinks[4].'</label></span>
<span><input type="checkbox" id="drink_4" name="drink_4" value="8" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 8)) ? 'checked="checked"' : '').'/> <label for="drink_4">'.$inpulls_drinks[8].'</label></span>
<span><input type="checkbox" id="drink_5" name="drink_5" value="16" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 16)) ? 'checked="checked"' : '').'/> <label for="drink_5">'.$inpulls_drinks[16].'</label></span>
<span><input type="checkbox" id="drink_6" name="drink_6" value="32" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 32)) ? 'checked="checked"' : '').'/> <label for="drink_6">'.$inpulls_drinks[32].'</label></span>
<span><input type="checkbox" id="drink_7" name="drink_7" value="64" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 64)) ? 'checked="checked"' : '').'/> <label for="drink_7">'.$inpulls_drinks[64].'</label></span>
<span><input type="checkbox" id="drink_8" name="drink_8" value="128" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 128)) ? 'checked="checked"' : '').'/> <label for="drink_8">'.$inpulls_drinks[128].'</label></span><br />
<span><input type="checkbox" id="drink_9" name="drink_9" value="256" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 256)) ? 'checked="checked"' : '').'/> <label for="drink_9">'.$inpulls_drinks[256].'</label></span>
<span><input type="checkbox" id="drink_10" name="drink_10" value="512" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 512)) ? 'checked="checked"' : '').'/> <label for="drink_10">'.$inpulls_drinks[512].'</label></span>
<span><input type="checkbox" id="drink_11" name="drink_11" value="1024" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 1024)) ? 'checked="checked"' : '').'/> <label for="drink_11">'.$inpulls_drinks[1024].'</label></span>
<span><input type="checkbox" id="drink_12" name="drink_12" value="2048" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 2048)) ? 'checked="checked"' : '').'/> <label for="drink_12">'.$inpulls_drinks[2048].'</label></span>
</li>';
		}

		include_once DOC_ROOT . '/orbicon/modules/inpulls.groups/inc.groups.php';
		$rid = $profile_rid;
		$usr_grps = print_users_groups($rid);

	      if($usr_grps) {
	      		$profile_details.= '<li><strong>Članstvo u grupama: </strong>'.$usr_grps.'</li>';
	      		$pdf_html[] = '<strong>Članstvo u grupama: </strong>'.$usr_grps;
	      }

		if($iprofile->tags) {
	      $profile_details .= '<li class="tagovi"><strong>Tagovi (ključne riječi): </strong><br /> '.print_inpulls_tag_cloud(0, $iprofile->id).'</li>';
		}

		if($iprofile->more_info) {
		$profile_details.= '<li><strong>Nešto o meni što nećete saznati iz ovih glupih pitanja: </strong>'.nl2br($iprofile->more_info).'</li>';
	  	$pdf_html[] = '<strong>Nešto o meni što nećete saznati iz ovih glupih pitanja: </strong>'.nl2br($iprofile->more_info);
	}

	if($iprofile->last_profile_viewers) {
		$profile_details.= '<li><strong>Korisnici koji su zadnji pogledali ovaj profil: </strong>'.render_last_profile_viewers($iprofile->pring_id).'</li>';
	}

          $profile_details .= '</ul>';


		        $profile_details .= '<dl id="info_text">
          <dt></dt>
          <dd></dd>
        </dl>';

          $profile_details .= '
        <ul class="info_list"></ul>
        <div class="clear"></div>
      </div>
      <!-- galerija i karta -->
      <div id="screen">

      <link rel="stylesheet" type="text/css" href="'.ORBX_SITE_URL.'/orbicon/3rdParty/yui/build/tabview/assets/tabview.css?'.ORBX_BUILD.'" />
<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/controler/gzip.server.php?file=/orbicon/3rdParty/yui/build/tabview/tabview-min.js&amp;'.ORBX_BUILD.'"></script>
<script type="text/javascript"><!-- // --><![CDATA[

	new YAHOO.widget.TabView("media_placeholder");

// ]]></script>
<style type="text/css">
' . $class_gallery . $class_map . $class_video . $class_comments . '
</style>
  <div id="media_placeholder" class="yui-navset">
    <ul class="yui-nav">
 	    <li class="selected"><a href="#komentari"><em class="comments">Komentari</em></a></li>
        <li><a href="#fotogalerija"><em class="gallery">Foto</em></a></li>
        <li><a href="#video"><em class="video">Video</em></a></li>
        <li><a href="#karta"><em class="map">Gdje sam?</em></a></li>
    </ul>
    <div class="yui-content">

    <div id="komentari">'.$comment_form.'</div>

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

		<div id="video">
			<p>
	        '.$video.'
	        </p>
        </div>

<div id="karta">

        	<!--- g maps -->

			<div id="google_map_container">
				<div id="map" style="width: 390px; height: 390px"></div>
			</div>
		</div>

			<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/controler/gzip.server.php?file=/orbicon/modules/inpulls/inpulls.js&amp;'.ORBX_BUILD.'"></script>
        	<script src="http://maps.google.com/maps?file=api&v=2&key=ABQIAAAAlv-tQTaTwI9UJQ8CUFylZBRzttjvWDbkBfQsi2JalZVQQoKWgxSukfkSuB3SA83xZtYVZtg2DfHYtw" type="text/javascript"></script>
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
	 </div>
    </div>

 </div>
      <div class="ad_views">Profil je pogledan '.($iprofile->views + 1).' puta od '.date($_SESSION['site_settings']['date_format'], $iprofile->registered).'</div>
      <div class="clr"></div>
    </div>

<script type="text/javascript">
YAHOO.namespace("cezar.container");

function initcezar() {

	// Define various event handlers for Dialog
	var handleSubmit = function() {
		this.submit();
	};
	var handleCancel = function() {
		this.cancel();
	};
	var handleSuccess = function(o) {
		/*var response = o.responseText;
		response = response.split("<!")[0];
		document.getElementById("resp").innerHTML = response;*/
	};
	var handleFailure = function(o) {
		alert("Submission failed: " + o.status);
	};

	// Instantiate the Dialog
	YAHOO.cezar.container.dialog = new YAHOO.widget.Dialog("dialog",
																{ width : "300px",
																  fixedcenter : true,
																  visible : false,
																  constraintoviewport : true,
																  buttons : [ { text:"Stisni", handler:handleSubmit },
																			  { text:"Odustani", handler:handleCancel } ]
																 } );

	// Validate the entries in the form to require that both first and last name are entered
	YAHOO.cezar.container.dialog.validate = function() {
		var data = this.getData();
		if (data.cezar_reason == "") {
			alert("Morate napisati razlog");
			$("cezar_reason").focus();
			return false;
		} else {
			return true;
		}
	};

	// Wire up the success and failure handlers
	YAHOO.cezar.container.dialog.callback = { success: handleSuccess,
												 failure: handleFailure };

	// Render the Dialog
	YAHOO.cezar.container.dialog.render();

	YAHOO.util.Event.addListener("showcezar", "click", YAHOO.cezar.container.dialog.show, YAHOO.cezar.container.dialog, true);
}

YAHOO.util.Event.onDOMReady(initcezar);
</script>

<div id="dialog">
	<div class="hd">Zar i ti sine Brute?</div>
	<div class="bd">
		<form method="post" action="'.ORBX_SITE_URL.'/orbicon/modules/inpulls/xhr.cesar.php">
			<input type="hidden" id="user_connpoint" name="user_connpoint" value="'.base64_encode($pr_user['contact_email']).'" />
			<input type="hidden" id="user_rid" name="user_rid" value="'.$profile_rid.'" />
			<input type="hidden" id="mobber_rid" name="mobber_rid" value="'.$_SESSION['user.r']['id'].'" />
			<div class="clear"></div>
			<label for="cezar_reason">Napiši razlog zašto ćeš stisnut cezara korisniku?</label><input type="text" id="cezar_reason" name="cezar_reason" value="" />
			<p class="what_is_cesar"><a href="javascript:void(null)" onclick="javascript:sh(\'cesar_help\')"><img src="'.ORBX_SITE_URL.'/site/gfx/images/info.gif" /> Što je Stisni cezara?</a><br/>
			<span class="h" id="cesar_help">U maniri Bruta i senatorskih konspiratora u uroti protiv Cezara, naša usluga "Stisni cezara" omogućava vam da <strong>zauvijek izbacite korisnika sa stranice</strong>. Svaki korisnik može samo jednom stisnuti cezara istom korisniku.<br/>
			Ako dotični korisnik skupi određen broj "cezara", zauvijek je izbačen sa Inpulls.com-a odlukom njegovih korisnika. Popis svih razloga koje ste naveli se šalje korisniku na e-mail kako bi vidio gdje je progriješio/-la.</span></p>
			<div class="clear"></div>
		</form>
	</div>
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
		$pdf->SetTitle($page_title);

		//initialize document
		$pdf->AliasNbPages();

		$pdf->AddPage();

		$pdf->Image(DOC_ROOT . '/site/gfx/print_bianco.png', 0, 0, 210, 300);

		$pdf->SetFont('vera', 'B', 16);
		$pdf->writeHTMLCell(0, 0, 0, 40, substr($page_title, 0, 52));

		$pdf->SetFont('vera', '', 10);
		$pdf->writeHTMLCell(0, 0, 27, 57, $town);
		$pdf->writeHTMLCell(0, 0, 113, 57, $age . ' ' . ($sex = ($pr_user['contact_sex'] == 0) ? '(M)' : '(Ž)'));
		$pdf->writeHTMLCell(0, 0, 170, 57, $inpulls_im_here_for[$iprofile->im_here_for]);

		$picture = $pr_user['picture'];

		if(is_file(DOC_ROOT . '/site/venus/thumbs/t-' . $picture)) {
			$picture = ORBX_SITE_URL . '/site/venus/thumbs/t-' . $picture;
		}
		elseif (is_file(DOC_ROOT . '/site/venus/' . $picture)) {
			$picture = ORBX_SITE_URL . '/site/venus/' . $picture;
		}
		else {
			$picture = ORBX_SITE_URL . '/orbicon/modules/peoplering/gfx/unknownUser.png';
		}

		$pdf_image = $picture;

		$pic_main_type = getimagesize($pdf_image);
		if(($pic_main_type[2] == IMAGETYPE_JPEG) ||
		($pic_main_type[2] == IMAGETYPE_PNG)) {
			$pdf->Image($pdf_image, 91, 72, 100);
		}

		//$pdf->writeHTMLCell(0, 0, 36, 72, $iprofile->pring_id);

		$pdf_html = '<br>'.implode('<br>', $pdf_html);
		$pdf->writeHTMLCell(0, 0, 7, 72, $pdf_html);

		$pdf->SetXY(8, 182);
		$pdf->MultiCell(0, 4, '');

		$pdf_contact = '<br>' . implode('<br>', $pdf_contact);
		$pdf->writeHTMLCell(0, 0, 7, 235, $pdf_contact);

		//Close and output PDF document
		$pdf->Output("inpulls-$username.pdf", 'I');
		session_write_close();
		exit();
    }
    else {
		return $profile_details;
    }

?>