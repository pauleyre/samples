<?php

	global $orbx_mod, $orbicon_x;
	if(get_is_member() && $orbx_mod->validate_module('inpulls.profile')) {
		redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.inpulls.profile&user=' . $_GET['user']);
	}

	$user = $_GET['user'];

	$uid = $pr->get_id_from_username($user);
	$content = $pr->get_profile($pr->get_prid_from_rid($uid));

	$picture = $content['picture'];

	if(is_file(DOC_ROOT . '/site/venus/' . $picture)) {
		$picture = ORBX_SITE_URL.'/site/venus/' . $picture;
	}
	else {
		$picture = ORBX_SITE_URL.'/orbicon/modules/peoplering/gfx/unknownUser.gif';
	}

	$js_name = str_sanitize($user, STR_SANITIZE_JAVASCRIPT);
	$js_name = addslashes(str_replace('"', '', $js_name));

	if(get_is_member() && !$content['private']) {
		$public_navigation = '<ul id="pr_menu">
				<li><a href="'.$url_site.'/?'.$lang.'=mod.peoplering&amp;sp=user&amp;user='.$user.'&amp;public=profile" title="'._L('pr-profile').'">'._L('pr-profile').'</a></li>
				<li><a href="'.$url_site.'/?'.$lang.'=mod.peoplering&amp;sp=user&amp;user='.$user.'&amp;public=cv" title="'._L('pr-cv').'">'._L('pr-cv').'</a></li>
				<li><a href="'.$url_site.'/?'.$lang.'=mod.peoplering&amp;sp=user&amp;user='.$user.'&amp;public=company" title="'._L('pr-comp-info').'">'._L('pr-comp-info').'</a></li>
			</ul>';
	}

	$public_html = '';

	switch ($_GET['public']) {
		case 'cv': $public_html = 'public.cv.php'; break;
		case 'profile': $public_html = 'public.profile.php'; break;
		case 'company': $public_html = 'public.company.php'; break;
	}

	$display_name = ($content['contact_name'] != '') ? $content['contact_name'] . ' ' . $content['contact_surname'] : $user;

	$orbicon_x->set_page_title($display_name);

	$display_content = '
<table style="width:100%" class="pr_nonregistered_view">
	<tr>
		<td class="first_col" style="width:33%"><a href="'.url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=user&amp;user='.$user, ORBX_SITE_URL . '/~' . $user).'"><img style="width:200px" src="' . $picture . '" alt="'.$user.'" title="'.$user.'" /></a></td>
		<td class="sec_col" style="width:33%">'._L('pr-name').': <strong>' . $display_name . '</strong></td>
		<td class="third_col">
			<a class="sendmsg" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=mail&amp;to='.$user.'">'._L('pr-send-msg').'</a><br />
			<a class="add2contacts" href="javascript:void(null);" onclick="javascript:add2contacts(\'' . $js_name . '\');">'._L('pr-add-contacts').'</a><br />
			<a class="viewfriends" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=friends&amp;user='.$user.'">'._L('pr-view-friends').'</a><br />
		</td>
	</tr>
</table>' . $public_navigation;

	if($public_html != '') {
		$display_content .= '<div class="cleaner"></div>';
		$display_content .= include DOC_ROOT . '/orbicon/modules/peoplering/public/' . $public_html;
	}

	if($orbx_mod->validate_module('inpulls')) {
		$display_content .= '<br/>Detaljan profil sa svim podacima, komentarima, fotoalbumom, videom i kartom mogu vidjeti samo registrirani korisnici. <a href="./?'.$orbicon_x->ptr.'=mod.inpulls.reg">Registriraj se</a>';
	}

?>