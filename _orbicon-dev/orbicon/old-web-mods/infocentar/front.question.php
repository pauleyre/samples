<?php

	// * top 10 latest questions
	$q = new Question();
	$c = new Category();

	$sql = sprintf('	SELECT 		id
						FROM 		orbx_mod_ic_question
						WHERE 		(permalink = %s)',
						$dbc->_db->quote($_GET['id']));

	$query = $dbc->_db->query($sql);
	$res = $dbc->_db->fetch_assoc($query);

	// * log this view
	$log['question'] = $res['id'];
	$log['clicker'] = ORBX_CLIENT_IP;
	$log['clicker_name'] = gethostbyaddr(ORBX_CLIENT_IP);
	$q->__log_click($log);

	$question = $dbc->_db->fetch_array($q->get_question($_GET['id'], 1));

	if($dbc->_db->num_rows($q->get_question($_GET['id'], 1)) > 0) {

		$r_a = $q->get_answer($_GET['id']);
		$answer = $dbc->_db->fetch_assoc($r_a);

		// category
		$category = $c->get_category($question['category']);
		$cat_name = $category['title'];

		// * author info
		$user = $dbc->_db->fetch_array($q->get_author_info($question['editor']));

		$date = date($_SESSION['site_settings']['date_format'], $question['created']);

		// * begin with info
		$author_name = ($icsetting['alt_author'] == '') ? $user['first_name'].' '.$user['last_name'] : $icsetting['alt_author'];

		$category_line = ($icsetting['category'] == 1) ? '<p class="author_info">'._L('ic-category').': '.$cat_name.'</p>' : '';
		$date_value = ($icsetting['date_show'] == 1) ? ', ' . $date : '';
		$author_line = ($icsetting['author'] == 1) ? '<p class="author_info">'._L('ic-ans-author').': '.$author_name . $date_value.'</p>' : '';
		$depart_line = ($icsetting['depart'] == 1) ? '<p class="author_info">'._L('ic-office').': '. $user['occupation'] . '</p>' : '';

		$show_info = ($icsetting['apply_author_info'] == 0 || $icsetting['apply_author_info'] == 3) ? $category_line.' '.$author_line.' '.$depart_line . ' ' : '';

		// include stars
		include_once DOC_ROOT . '/orbicon/modules/infocentar/inc.rating.php';

		$show_on_screen .= '<script type="text/javascript"><!-- // --><![CDATA[
		var ic_scores = new Array();
		ic_scores[0] = \''._L('ic-vote-answer').'\';
		ic_scores[1] = \''._L('ic-vote-1').'\';
		ic_scores[2] = \''._L('ic-vote-2').'\';
		ic_scores[3] = \''._L('ic-vote-3').'\';
		ic_scores[4] = \''._L('ic-vote-4').'\';
		ic_scores[5] = \''._L('ic-vote-5').'\';
		// ]]></script>';

		$show_on_screen .= '<div class="answer question_item">';
		$show_on_screen .= '<h3>'.$question['title'].'</h3><div style="clear:both;"></div>';

		// display this only if we have something
		while($answer) {
			if($answer['content'] != '') {
				$show_on_screen .= '<div class="question_answer">'.$answer['content'] .'</div>';
				$show_on_screen .= '<div class="author_information">'.
					$show_info.
					'<div id="star_holder_'.$answer['id'].'" class="star_holder">'.
					print_stars($answer['id']).
					'</div>';
				if(isset($_GET['ref'])){
					$show_on_screen .=
						'
						<div class="back_btn">
							<a href="'.$_SERVER['HTTP_REFERER'].'" title="'._L('ic-back-front').'">
								'._L('ic-back-front').'
							</a>
						</div>';
				} else {
					$show_on_screen .=
						'
						<div class="back_btn_no_ref">
							<a href="'.ORBX_SITE_URL.'/?'.$lang.'=mod.infocentar" title="'._L('ic-back-front').'">
								'._L('ic-back-front').'
							</a>
						</div>';
				}

				$show_on_screen .=	'
					<div class="cleaner"></div>
				</div>';
			}
			$answer = $dbc->_db->fetch_assoc($r_a);
		}

		// we allowed other users to reply
		if(($icsetting['answer_privileges'] == 'ar') && get_is_member()) {
			$show_on_screen .= include_once DOC_ROOT . '/orbicon/modules/infocentar/front.reply.php';
		}
		else if($icsetting['answer_privileges'] == 'arp') {
			$show_on_screen .= include_once DOC_ROOT . '/orbicon/modules/infocentar/front.reply.php';
		}

		$show_on_screen .= '</div>';
		$show_on_screen .= '<div class="art_decor_bottom"></div>';

		$orbicon_x->set_page_title($question['title']);
	}
	// not found
	else {
		header('HTTP/1.1 404 Not Found', true);
		$_SESSION['cache_status'] = 404;
		$show_on_screen .= '<h3>404 Not Found</h3>';
	}
?>