<?php

	$user = $_GET['user'];

	$id = $pr->get_id_from_username($user);

	$content = $pr->get_profile($pr->get_prid_from_rid($id));

	$display_name = ($content['contact_name'] != '') ? $content['contact_name'] . ' ' . $content['contact_surname'] : $user;

	$display_content .= '<h1 class="pr_friends_public"><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=user&amp;user='.$user.'">'.$display_name.'</a> - '._L('pr-my-friends').'</h1><table style="text-align:left;width:100%;" cellpadding="0"><tr>';

	require_once DOC_ROOT.'/orbicon/modules/peoplering/class/class.user_contacts.php';

	$uc = new User_Contacts($id);

	$i = 1;

	if($uc->contacts->members) {
		foreach ($uc->contacts->members as $friend) {

			$friend_data = $pr->get_profile($pr->get_id_from_username($friend));

			$picture = $pr->get_picture($pr->get_prid_from_rid($pr->get_id_from_username($friend)));

			if(is_file(DOC_ROOT . '/site/venus/thumbs/t-' . $picture)) {
				$picture = ORBX_SITE_URL.'/site/venus/thumbs/t-' . $picture;
			}
			elseif(is_file(DOC_ROOT . '/site/venus/' . $picture)) {
				$picture = ORBX_SITE_URL.'/site/venus/' . $picture;
			}
			else {
				$picture = ORBX_SITE_URL.'/orbicon/modules/peoplering/gfx/unknownUser.gif';
			}

			$js_name = str_sanitize($friend, STR_SANITIZE_JAVASCRIPT);
			$js_name = addslashes(str_replace('"', '', $js_name));

			$display_content .= '
				<td style="border: 1px solid #E8E8E8; width: 20%; vertical-align:top;">
					<table width="100%" class="friend_table">
						<tr>
							<td colspan="2" style="text-align:center;"><a href="'.url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=user&amp;user='.$friend, ORBX_SITE_URL . '/~' . $friend).'">'.$friend.'<br /><img class="friend_avatar" src="' . $picture . '" alt="'.$friend.'" title="'.$friend.'" /></a></td>
						</tr>
						<tr>
							<td style="font-size: 90%;">
								<a class="sendmsg" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=mail&amp;to='.$friend.'">'._L('pr-send-msg').'</a><br />
								<a class="add2contacts" href="javascript:void(null);" onclick="javascript:add2contacts(\'' . $js_name . '\')">'._L('pr-add-contacts').'</a><br />
								<a class="viewfriends" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=friends&amp;user='.$friend.'">'._L('pr-view-friends').'</a><br />
							</td>
						</tr>
					</table>';

			$if_third_end_it = ($i % 3);

			if($if_third_end_it == 0){
				$display_content .= '</td></tr><tr>';
			}
			else {
				$display_content .= '</td>';
			}

			$i++;
		}
	}
	else {
		$display_content .= '<td class="no_friends_picked"><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=user&amp;user='.$user.'">'.$display_name.'</a> '._L('pr-unpicked-friend').'</td>';
	}

	$display_content .= '</tr></table>';

?>
