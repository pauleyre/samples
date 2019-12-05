<?php

// * top 10 latest questions
$q = new Question();
$c = new Category();

global $dbc;

require_once DOC_ROOT . '/orbicon/modules/infocentar/inc.rating.php';
require_once DOC_ROOT . '/orbicon/class/class.pagination.php';

$_GET['p'] = isset($_GET['p']) ? $_GET['p'] : 1;
$_GET['pp'] = isset($_GET['pp']) ? $_GET['pp'] : $rows_per_page;
$offset = ($_GET['p'] - 1) * $_GET['pp'];
$pagination = new Pagination('p', 'pp');

// add ASC DESC sorting if exist
$ascSortLink = (isset($_GET['rate'])) ? '&amp;rate=' . $_GET['rate'] : '';

// * retreive any number of questions sorted by date desceding, default 10
$question_list = $q->get_unanswered_question($offset, $_GET['pp'], 1);

$total = $dbc->_db->query('SELECT COUNT(id) FROM orbx_mod_ic_question WHERE (state=1)');
$total = $dbc->_db->fetch_array($total);
$total = $total[0];
$pagination->total = $total;
$pagination->split_pages();
$nav .= '<p class="pagination">'. $pagination->construct_page_nav(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.infocentar' . $ascSortLink).'</p>';



if($dbc->_db->num_rows($question_list) > 0) {
	$question = $dbc->_db->fetch_array($question_list);

	// * counter for highlighting
	// moved this to render.frontpage.php
	//$show_on_screen .= '<div class="art_decor_top"></div>';
	while($question){

		$high = ($i % 2 == 0) ? ' highlight_item' : '';

		$user = $dbc->_db->fetch_array($q->get_author_info($question['editor']));

		$id_t = $question['permalink'];

		$tid = (isset($_GET['category'])) ? $question['id'] : $question['qid'];

		$category = $c->get_category($question['category']);
		$cat_name = (isset($_GET['category'])) ? $category['title']  : $question['category'];
		$date = date('d.m.Y', $question['created']);
		$author_name = ($icsetting['alt_author'] == '') ? $user['first_name'].' '.$user['last_name'] : $icsetting['alt_author'];

		$category_line = ($icsetting['category'] == 1) ? '<p>'._L('ic-category').': '.$cat_name.'</p>' : '';
		$date_value = ($icsetting['date_show'] == 1) ? ', ' . $date : '';
		$author_line = ($icsetting['author'] == 1) ? '<p>'._L('ic-ans-author').': '.$author_name . $date_value.'</p>' : '';
		$depart_line = ($icsetting['depart'] == 1) ? '<p>'._L('ic-office').': '. $user['occupation'] . '</p>' : '';

		$show_info = ($icsetting['apply_author_info'] == 0 || $icsetting['apply_author_info'] == 2) ? $category_line.' '.$author_line.' '.$depart_line . ' ' : '';

		// display total answers on these options
		if(($icsetting['answer_privileges'] == 'ar') || ($icsetting['answer_privileges'] == 'arp')) {
				$tot_answ = ' (' . $dbc->_db->num_rows($q->get_answer($tid, 'id')) . ')';
		}

		$show_on_screen .= '
			<div class="question_item'.$high.'">
				<p class="vote_holder">
					<span class="grade">'.$question['total_rating'].'</span>
					' . print_totalrating_stars($question['total_rating']). '
				</p>
				<div class="question_fe">
					<h4 class="question_date">'.date('d.m', $question['live']).'</h4>
					<h3 class="question_link"><a href="?id='.$id_t.'&amp;sp=q&amp;'.$lang.'=mod.infocentar&amp;ref=yes" title="'.$question['title'].'" name="'.$question['title'].'">'.$question['title'].$tot_answ.'</a></h3>
					<div class="author_information">'.
					$show_info. '
					</div>
				</div>
				<div class="cleaner"></div>
			</div>';

		$question = $dbc->_db->fetch_array($question_list);

		$i++;
	}

	$show_on_screen .= '<div class="art_decor_bottom"></div>';
	$show_on_screen .= $nav;
} else {
	if(isset($_GET['category'])){
		$show_on_screen .= '<h3>'._L('ic-msg-noquest-cat').'</h3>';
		$show_on_screen .= $nav;
	} else {
		$show_on_screen = '<h3>'._L('ic-msg-noquest-all').'</h3>';
	}
}

// * searching


?>