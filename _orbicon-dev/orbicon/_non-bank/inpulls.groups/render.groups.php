<?php

global $dbc;

require_once DOC_ROOT . '/orbicon/modules/inpulls.groups/inc.groups.php';
include_once DOC_ROOT . '/orbicon/modules/peoplering/class/class.peoplering.php';

if (isset($_GET['group'])) {

	if(isset($_GET['config']) && isset($_POST['submit'])) {
		if($_GET['group'] != '') {
			edit_group(get_grp_id_from_permalink($_GET['group']));
		}
		else {
			$id = new_group();
			redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.inpulls.groups&group=' . get_grp_permalink_from_id($id) . '&config');
		}
	}

	$group = get_group($_GET['group']);
	$group = $dbc->_db->fetch_object($group);

	if(isset($_GET['config'])) {

		if(!get_is_member()) {
			return '<div id="grp_no_auth">Morate biti prijavljeni da bi osnovali grupu. <a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.reg">Prijavite se</a></div>';
		}

		if(($_GET['group'] != '') && !get_grp_is_owner($_SESSION['user.r']['id'], get_grp_id_from_permalink($_GET['group']))) {
			return '<div id="grp_no_auth">Samo vlasnik može ažurirati svoju grupu</div>';
		}

		$form = '
	<form method="post" action="" enctype="multipart/form-data">
	<input type="hidden" id="id" name="id" value="'.$group->id.'" />

	<p>
		<label for="title">Naziv grupe</label><br />
		<input maxlength="255" type="text" id="title" name="title" value="'.$group->title.'" />
	</p>
	<p>
		<label for="intro_txt">Opis grupe</label><br />
		<textarea name="intro_txt" id="intro_txt">'.$group->intro_txt.'</textarea>
	</p>
	<p>
		<label for="intro_gfx">Slika (uz opis grupe)</label><br />
		<input type="file" id="intro_gfx" name="intro_gfx" />
	</p>
	<p>
		<label for="members_gfx">Oznaka članstva (do 50px &times; 50px)</label><br />
		<input type="file" id="members_gfx" name="members_gfx" />
	</p>
	<p>
		<input value="1" type="checkbox" id="disable_new" name="disable_new" '.($checked = ($group->disable_new_users) ? ' checked="checked"' : '').' /> <label for="disable_new">Onemogući primanje novih članova</label>
	</p>
	<p>
		<input value="1" type="checkbox" id="auth" name="auth" '.($checked = ($group->require_auth_new_users) ? ' checked="checked"' : '').' /> <label for="auth">Novi članovi čekaju autorizaciju osnivača grupe</label>
	</p>

	<input id="submit" name="submit" type="submit" value="Spremi" />

	</form>';

		$all_members = '<table class="all_grp_members" style="width:100%">
		<tr class="all_grp_members_header">
			<td>Ime i prezime</td>
			<td>Datum učlanjenja</td>
			<td>Broj poruka</td>
			<td>Status</td>
			<td>Blokiraj</td>
			<td>Ukloni</td>
		</tr>';

		if(isset($_GET['block'])) {
			set_grp_member_status(INPULLS_GRP_MEMBER_DISABLED, $_GET['block'], $group->id);
			modify_grp_activity(-3, $group->id);
		}

		if(isset($_GET['unblock'])) {
			set_grp_member_status(INPULLS_GRP_MEMBER_LIVE, $_GET['unblock'], $group->id);
			modify_grp_activity(3, $group->id);
		}

		if(isset($_GET['golive'])) {
			set_grp_member_status(INPULLS_GRP_MEMBER_LIVE, $_GET['golive'], $group->id);
		}

		if(isset($_GET['gowait'])) {
			set_grp_member_status(INPULLS_GRP_MEMBER_WAITING, $_GET['gowait'], $group->id);
		}

		if(isset($_GET['delete'])) {
			leave_group($_GET['delete'], $group->id);
			modify_grp_activity(-5, $group->id);
		}

		if(isset($_GET['block']) || isset($_GET['unblock']) || isset($_GET['golive']) || isset($_GET['gowait']) || isset($_GET['delete'])) {
			redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.groups&group='.urlencode($group->permalink).'&config');
		}

		$members = get_grp_all_members($group->id);
		$member = $dbc->_db->fetch_object($members);

		include_once DOC_ROOT . '/orbicon/modules/forum/class.forum.php';
		$forum = new Forum($_GET['group']);

		$pr = new PeopleRing();

		while ($member) {

			$profile = $pr->get_profile($pr->get_prid_from_rid($member->user_reg_id));

			$block_link = ($member->status == INPULLS_GRP_MEMBER_DISABLED) ? '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.groups&amp;group='.urlencode($group->permalink).'&amp;config&amp;unblock='.$member->user_reg_id.'">Odblokiraj</a>' : '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.groups&amp;group='.urlencode($group->permalink).'&amp;config&amp;block='.$member->user_reg_id.'">Blokiraj</a>';
			$status_link = ($member->status == INPULLS_GRP_MEMBER_WAITING) ? '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.groups&amp;group='.urlencode($group->permalink).'&amp;config&amp;golive='.$member->user_reg_id.'">Odobri</a>' : '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.groups&amp;group='.urlencode($group->permalink).'&amp;config&amp;gowait='.$member->user_reg_id.'">Stavi na čekanje</a>';

			$username = $pr->get_username($member->user_reg_id);
			$username = $username['username'];

			$display_name = (empty($profile['contact_name'])) ? $username : $profile['contact_name'] . ' ' . $profile['contact_surname'];

			$all_members .= '<tr>
				<td><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.profile&amp;user='.$username.'">' . $display_name . '</a></td>
				<td>'.date($_SESSION['site_settings']['date_format'], $member->member_since).'</td>
				<td>'.$forum->get_total_user_msgs($member->user_reg_id).'</td>
				<td>'.$status_link.'</td>
				<td>'.$block_link.'</td>
				<td><a onmousedown="' . delete_popup($profile['contact_name'] . ' ' . $profile['contact_surname']) . '" onclick="javascript:return false;" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.groups&amp;group='.urlencode($group->permalink).'&amp;config&amp;delete='.$member->user_reg_id.'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/delete.png" /></a></td>
			</tr>';

			$member = $dbc->_db->fetch_object($members);
		}

		$forum = null;
		$pr = null;

		$all_members .= '</table>';

		return '<div id="grp_config">' . $form . $all_members . '</div>';
	}
	else {

		if($group) {

			if(isset($_GET['join'])) {

				if(!get_is_member()) {
					return '<div id="grp_no_auth">Morate biti prijavljeni da bi se učlanili u grupu. <a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.reg">Prijavite se</a></div>';
				}
				else {
					$new = new_group_member($_SESSION['user.r']['id'], $group->id);
					if($new == -1) {
						return 'Ova grupa trenutno ne prima nove članove';
					}
					else {
						modify_grp_activity(5, $group->id);

						if(!$group->require_auth_new_users) {
							return 'Dobrodošli u grupu &quot;'.$group->title.'&quot;. Možete odmah početi surfati po <a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.forum&amp;forum='.urlencode($group->permalink).'">forumu grupe</a> i upoznati ostale članove';
						}
						else {
							return 'Dobrodošli u grupu &quot;'.$group->title.'&quot;. Nakon što vlasnik grupe odobri vaš zahtjev za članstvom, moći ćete surfati po <a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.forum&amp;forum='.urlencode($group->permalink).'">forumu grupe</a> i upoznati ostale članove';
						}

					}
				}
			}
			elseif (isset($_GET['leave'])) {
				if(!get_is_member()) {
					return '<div id="grp_no_auth">Morate biti prijavljeni da bi se isčlanili iz grupe. <a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.reg">Prijavite se</a></div>';
				}
				else {
					leave_group($_SESSION['user.r']['id'], $group->id);
					modify_grp_activity(-5, $group->id);
				}
			}

			$orbicon_x->set_page_title($group->title);

			$intro_gfx = '';

			if($group->intro_gfx) {
				$intro_gfx = '<img src="'.ORBX_SITE_URL.'/" style="float:right" alt="'.$group->title.'" title="'.$group->title.'" />';
			}

			if(!get_grp_is_owner($_SESSION['user.r']['id'], $group->id)) {
				if(get_grp_is_member($_SESSION['user.r']['id'], $group->id)) {
					$link = '<a class="leave_group" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.groups&amp;group='.urlencode($group->permalink).'&amp;leave">Isčlani se iz grupe</a>';
				}
				else {
					$link = '<a class="join_group" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.groups&amp;group='.urlencode($group->permalink).'&amp;join">Učlani se u grupu</a>';
				}
			}

			$pr = new PeopleRing();
			$profile = $pr->get_profile($pr->get_prid_from_rid($group->owner_id));

			$username = $pr->get_username($group->owner_id);
			$username = $username['username'];

			$display_name = (empty($profile['contact_name'])) ? $username : $profile['contact_name'] . ' ' . $profile['contact_surname'];

			if(get_grp_is_owner($_SESSION['user.r']['id'], $group->id)) {
				$owner_link = '<a class="edit_group" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.groups&amp;group='.urlencode($group->permalink).'&amp;config">Uredi grupu</a>';
			}
			else {
				$owner_link = '';
			}

			$activity = get_grp_activity($group->id);

			if($activity < 0) {
				$activity = '<span class="red">'.$activity.'%</span>';
			}
			else {
				$activity = '<span class="green">'.$activity.'%</span>';
			}

			$img = DOC_ROOT . '/site/venus/' . $group->intro_gfx;
			list($width, $height, $img_type, $attr) = getimagesize($img);
			$width = ($width > 480) ? 480 : $width;

			$img = is_file($img) ? '<img class="grp_intro_img" style="width: '.$width.'px; float:left" src="'.ORBX_SITE_URL.'/site/venus/'.$group->intro_gfx.'" />' : '';

			$badge = DOC_ROOT . '/site/venus/' . $group->members_gfx;
			$badge = is_file($badge) ? '<li><strong>Oznaka članstva:</strong> <img src="'.ORBX_SITE_URL.'/site/venus/'.$group->members_gfx.'" /></li>' : '';


			return '
			<div id="group_container">
			<div class="grp_intro_txt">'.$img . '<div style="clear:both"></div><p>' . nl2br(utf8_html_entities($group->intro_txt)).'</p></div>
			<ul>
				<li><strong>Vlasnik grupe:</strong> <a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.profile&amp;user='.$username.'">'.$display_name.'</a></li>
				<li><strong>Članova:</strong> '.get_grp_total_live_members($group->id).'</li>
				<li><strong>Aktivnost:</strong> '.$activity.'</li>
				<li><strong>Datum osnivanja:</strong> '.date($_SESSION['site_settings']['date_format'], $group->live_from).'</li>
				'.$badge.'
			<ul>
			<div style="clear:both"></div>
			<p>
				'.$link.' <a class="group_forum" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.forum&amp;forum='.urlencode($group->permalink).'">Forum grupe</a> '.$owner_link.' <a class="all_groups" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.groups">Sve grupe</a>
			</p>
			</div>';
		}
		else {
			redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.inpulls.groups');
		}
	}
}
else {

	$owns_group = get_user_group_id($_SESSION['user.r']['id']);

	if($owns_group) {
		$link = '<a class="edit_group" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.groups&amp;group='.get_grp_permalink_from_id($owns_group).'&amp;config">Uredi svoju grupu</a>';
	}
	else {
		$link = '<a class="new_group" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.groups&amp;group&amp;config">Osnuj  grupu</a>';
	}

	$all_groups = '
	<form id="group_filter" method="get" action="">
	<input name="'.$orbicon_x->ptr.'" value="'.$_GET[$orbicon_x->ptr].'" type="hidden" />

		<div>'.$link.' <label for="sort">Poredaj po</label>
		<select id="sort" name="sort">
			<!--  <option value="active_most" '.($selected = ($_GET['sort'] == 'active_most') ? ' selected="selected"' : '').'>Aktivnosti: Prvo najaktivniji</option>
			<option value="active_least"'.($selected = ($_GET['sort'] == 'active_least') ? ' selected="selected"' : '').'>Aktivnosti: Prvo najmanje aktivni</option> -->
			<option value="title_az"'.($selected = ($_GET['sort'] == 'title_az') ? ' selected="selected"' : '').'>Nazivu: A-Z</option>
			<option value="title_za"'.($selected = ($_GET['sort'] == 'title_za') ? ' selected="selected"' : '').'>Nazivu: Z-A</option>
			<!-- <option value="members_most"'.($selected = ($_GET['sort'] == 'members_most') ? ' selected="selected"' : '').'>Broju članova: Prvo najbrojniji</option>
			<option value="members_less"'.($selected = ($_GET['sort'] == 'members_less') ? ' selected="selected"' : '').'>Broju članova: Prvo najmanje brojni</option> -->
			<option value="newest"'.($selected = ($_GET['sort'] == 'newest') ? ' selected="selected"' : '').'>Datumu osnivanja: Prvo najnoviji</option>
			<option value="oldest"'.($selected = ($_GET['sort'] == 'oldest') ? ' selected="selected"' : '').'>Datumu osnivanja: Prvo najstariji</option>
		</select> <input id="submit" value="&gt;&gt;" name="submit" type="submit" />
		</div>
	</form>
	<br />
	<table style="width:100%">
		<tr class="groups_header">
			<td>Grupa</td>
			<td>Članovi</td>
			<td>Forum</td>
			<td>Aktivnost</td>
		</tr>';

	if(get_is_member()) {
		$my_groups_ids = array();

		$my_owner_group = get_group(get_grp_permalink_from_id(get_user_group_id($_SESSION['user.r']['id'])));
		$my_owner_group = $dbc->_db->fetch_object($my_owner_group);

		if($my_owner_group) {
			$my_groups_ids[] = $my_owner_group->id;
			$all_groups .= '<tr style="background-color:#99ff99">
				<td><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.groups&amp;group='.urlencode($my_owner_group->permalink).'">'.$my_owner_group->title.'</a></td>
				<td class="grp_tot_members">'.get_grp_total_live_members($my_owner_group->id).'</td>
				<td><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.forum&amp;forum='.urlencode($my_owner_group->permalink).'">Forum '.$my_owner_group->title.'</a></td>
				<td>'.get_grp_activity($my_owner_group->id).'%</td>
			</tr>';
		}

		$my_membership_groups = get_user_groups($_SESSION['user.r']['id']);
		$my_membership_group = $dbc->_db->fetch_object($my_membership_groups);

		while ($my_membership_group) {

			if(!in_array($my_membership_group->id, $my_groups_ids)) {
				$my_groups_ids[] = $my_membership_group->id;
				$all_groups .= '<tr style="background-color:#ffff99">
					<td><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.groups&amp;group='.urlencode($my_membership_group->permalink).'">'.$my_membership_group->title.'</a></td>
					<td class="grp_tot_members">'.get_grp_total_live_members($my_membership_group->id).'</td>
					<td><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.forum&amp;forum='.urlencode($my_membership_group->permalink).'">Forum '.$my_membership_group->title.'</a></td>
					<td>'.get_grp_activity($my_membership_group->id).'%</td>
				</tr>';
			}

			$my_membership_group = $dbc->_db->fetch_object($my_membership_groups);
		}
	}

	if(!isset($_GET['p'])) {
		$unset_below = true;
	}

	$_GET['pp'] = (isset($_GET['pp'])) ? $_GET['pp'] : 20;
	$_GET['p'] = (isset($_GET['p'])) ? $_GET['p'] : 1;

	require_once DOC_ROOT . '/orbicon/class/class.pagination.php';
	$pagination = new Pagination('p', 'pp');

	$read = $dbc->_db->query('	SELECT 		COUNT(id) AS numrows
								FROM 		'.TABLE_INPULLS_GROUPS.'
								WHERE 		(live = 1)');
	$row = $dbc->_db->fetch_assoc($read);

	$pagination->total = $row['numrows'];
	$pagination->split_pages();

	switch ($_GET['sort']) {
		/*case 'active_most': $sort_by = ' activity ASC '; break;
		case 'active_least': $sort_by = ' activity DESC '; break;*/
		case 'title_az': $sort_by = ' title ASC '; break;
		case 'title_za': $sort_by = ' title DESC '; break;
		case 'members_most': $sort_by = ''; break;
		case 'members_less': $sort_by = ''; break;
		case 'newest': $sort_by = ' live_from ASC '; break;
		case 'oldest': $sort_by = ' live_from DESC '; break;
		default: /*$sort_by = ' activity ASC ';*/ $sort_by = ' title ASC '; break;
	}

	$groups = get_group('', ' ORDER BY ' . $sort_by, true);
	$group = $dbc->_db->fetch_object($groups);

	while ($group) {

		if(!in_array($group->id, $my_groups_ids)) {
			$all_groups .= '<tr>
				<td><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.inpulls.groups&amp;group='.urlencode($group->permalink).'">'.$group->title.'</a></td>
				<td class="grp_tot_members">'.get_grp_total_live_members($group->id).'</td>
				<td><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.forum&amp;forum='.urlencode($group->permalink).'">Forum '.$group->title.'</a></td>
				<td>'.get_grp_activity($group->id).'%</td>
			</tr>';
		}

		$group = $dbc->_db->fetch_object($groups);
	}

	$all_groups .= '</table>';

	$all_groups .= $pagination->construct_page_nav(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$_GET[$orbicon_x->ptr]);

    // this invalidates caching, clean up from memory
	if($unset_below) {
		unset($_GET['p'], $_GET['pp']);
	}

	return "<div id=\"all_groups\">$all_groups</div>";
}

?>