<?php

	$tot_quest = $dbc->_db->num_rows($qs->get_total_questions(1, true));

	$summary = '<table id="summary_table"><tr>';

	$summary.= '<td>'. _L('ic-total-quest') . ': ' . $tot_quest . '</td>';

	if(($icsetting['answer_privileges'] == 'ar') || $icsetting['answer_privileges'] == 'arp') {
		$tot_answ = $dbc->_db->num_rows($qs->get_total_answers(1, true));
		$summary.= '<td>'. _L('ic-total-answ') . ': ' . $tot_answ . '</td>';
	}

	$summary.= '<td>'. _L('ic-top-list') . ': <a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.infocentar&amp;rate=asc" title="'._L('ic-worst_rated').'"><img src="'.ORBX_SITE_URL.'/orbicon/modules/infocentar/gfx/hand_down.gif" alt="'._L('ic-worst_rated').'" title="'._L('ic-worst_rated').'" /></a>
		<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.infocentar&amp;rate=desc" title="'._L('ic-best_rated').'"><img src="'.ORBX_SITE_URL.'/orbicon/modules/infocentar/gfx/hand_up.gif" alt="'._L('ic-best_rated').'" title="'._L('ic-best_rated').'" /></a></td>';

	$select_none = '';
	$select_five = '';
	$select_ten = '';
	$select_twenty = '';

	// check for selection, if any..

	$current_pages = isset($_POST['ic_show_per_page']) ? intval($_POST['ic_show_per_page']) : $_COOKIE['ic_show_per_page'];

	switch($current_pages) {
		case 5:		$select_five = ' selected="selected"';
					break;
		case 10:	$select_ten = ' selected="selected"';
					break;
		case 20:	$select_twenty = ' selected="selected"';
					break;
		default:	$select_none = ' selected="selected"';
					break;
	}

	$current_pages = ($current_pages < 1) ? 20 : $current_pages;
	$current_pages = ($current_pages > 50) ? 50 : $current_pages;

	$summary.= '
			<td>
				<form id="form_per_page" method="post" action="">
					<label for="ic_show_per_page">'. _L('ic-show-quest') . '</label>
					<select id="ic_show_per_page" name="ic_show_per_page" onchange="javascript: submit();">
						<option value="'.$icsetting['public_per_page'].'" '.$select_none.'>auto</option>
						<option value="5"'.$select_five.'>5</option>
						<option value="10"'.$select_ten.'>10</option>
						<option value="20"'.$select_twenty.'>20</option>
					</select>
				</form>
			</td>';

	$summary.= '</tr></table>';

?>