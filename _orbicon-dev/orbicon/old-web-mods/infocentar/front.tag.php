<?php

$q = new Question();
$c = new Category();

global $dbc;

require_once DOC_ROOT . '/orbicon/modules/infocentar/inc.rating.php';
require_once DOC_ROOT . '/orbicon/class/class.pagination.php';

// add ASC DESC sorting if exist
$ascSortLink = (isset($_GET['rate'])) ? '&amp;rate=' . $_GET['rate'] : '';


$_GET['p'] = isset($_GET['p']) ? $_GET['p'] : 1;
$_GET['pp'] = isset($_GET['pp']) ? $_GET['pp'] : $icsetting['public_per_page'];
$rowsPerPage = $_GET['pp'];
$offset = ($_GET['p'] -1) * $rowsPerPage;
$pagination = new Pagination('p', 'pp');

if(isset($_GET['tag'])){
	$question_list = $q->get_tag_questions($_GET['tag'], $offset, $rowsPerPage, 1);
	$total = $dbc->_db->query('	SELECT
									COUNT(id)
								FROM
									orbx_mod_ic_question
								WHERE
									(tags LIKE '.$dbc->_db->quote('%'.$_GET['tag'].'%').')
								AND
									(state=1)');

	$total = $dbc->_db->fetch_array($total);
	$total = $total[0];
	$pagination->total = $total;
	$pagination->split_pages();
	$nav .='<p class="pagination">'. $pagination->construct_page_nav(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.infocentar&sp=tag&amp;tag='.$_GET['tag'] . $ascSortLink).'</p>';

}

if($dbc->_db->num_rows($question_list) > 0) {
	$question = $dbc->_db->fetch_array($question_list);

	// * counter for highlighting

	while($question){

		$tags = unserialize($question['tags']);

			$high = ($i%2 == 0) ? ' highlight_item' : '';

			$user = $dbc->_db->fetch_array($q->get_author_info($question['editor']));

			$id_t = /*(isset($_GET['category'])) ? $question['id'] : */$question['permalink']/*$question['qid']*/;

			$category = $c->get_category($question['category']);
			$cat_name = (isset($_GET['category'])) ? $category['title']  : $question['category'];
			$date = date($_SESSION['site_settings']['date_format'], $question['created']);
			$author_name = ($icsetting['alt_author'] == '') ? $user['first_name'].' '.$user['last_name'] : $icsetting['alt_author'];

			$category_line = ($icsetting['category'] == 1) ? '<p>'._L('ic-category').': '.$cat_name.'</p>' : '';
			$date_value = ($icsetting['date_show'] == 1) ? ', ' . $date : '';
			$author_line = ($icsetting['author'] == 1) ? '<p>'._L('ic-ans-author').': '.$author_name . $date_value.'</p>' : '';
			$depart_line = ($icsetting['depart'] == 1) ? '<p>'._L('ic-office').': '. $user['occupation'] . '</p>' : '';

			$show_info = ($icsetting['apply_author_info'] == 0 || $icsetting['apply_author_info'] == 2) ? $category_line.' '.$author_line.' '.$depart_line . ' ' : '';

			$show_on_screen .= '
				<div class="question_item'.$high.'">
					<p class="vote_holder">
						<span class="grade">'.$question['total_rating'].'</span>
						' . print_totalrating_stars($question['total_rating']). '
					</p>
					<div class="question_fe">
						<h3><a href="?id='.$id_t.'&amp;sp=q&amp;'.$lang.'=mod.infocentar&amp;ref=yes" title="'.$question['title'].'" name="'.$question['title'].'">'.$question['title'].'</a></h3>
						<div class="author_information">'.$show_info.'</div>
					</div>
					<div class="cleaner"></div>
				</div>
			';

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