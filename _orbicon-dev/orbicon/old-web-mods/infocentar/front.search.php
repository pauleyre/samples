<?php
require_once DOC_ROOT . '/orbicon/modules/infocentar/inc.rating.php';

// * if search is activated
if(isset($_GET['submit_search']) || isset($_GET['search_string'])) {

		// log statistics if found
		global $orbx_mod;
		if($orbx_mod->validate_module('stats') && $_SESSION['site_settings']['stats_attila']) {
			include_once DOC_ROOT . '/orbicon/modules/stats/class.stats.php';
			$stats = new Statistics();
			$stats->log_attila_search_keywords($_GET['search_string']);
			$stats = null;
		}

	$_GET['p'] = isset($_GET['p']) ? intval($_GET['p']) : 1;
	$_GET['pp'] = isset($_GET['pp']) ? intval($_GET['pp']) : $rows_per_page;

	// add ASC DESC sorting if exist
	$ascSortLink = (isset($_GET['rate'])) ? '&amp;rate=' . $_GET['rate'] : '';

	$search_obj = new Question($_GET);
	$search_res = $search_obj->search_questions((($_GET['p'] -1) * $_GET['pp']), $_GET['pp']);
	// if our search query is empty, pagination should be calculated differently
	if($_GET['search_string'] == '') {
		$total = $dbc->_db->query('SELECT COUNT(id) FROM orbx_mod_ic_question WHERE (state=1)');
		$total = $dbc->_db->fetch_array($total);
		$total = $total[0];
	}
	else {
		$total = $dbc->_db->num_rows($search_res);
	}

	if($dbc->_db->num_rows($search_res) > 0){

		$i = 0;

		$search = $dbc->_db->fetch_array($search_res);
		while($search) {

			$high = ($i % 2 == 0) ? ' highlight_item' : '';

			// * author info
			$user = $dbc->_db->fetch_array($search_obj->get_author_info($search['editor']));

			$date = date($_SESSION['site_settings']['date_format'] . ' - h:m:i', $search['created']);

			// * begin with info
			$author_name = ($icsetting['alt_author'] == '') ? $user['first_name'].' '.$user['last_name'] : $icsetting['alt_author'];

			$category_line = ($icsetting['category'] == 1) ? '<p>'._L('ic-category').': '.$search['category'].'</p>' : '';
			$date_value = ($icsetting['date_show'] == 1) ? ', ' . $date : '';
			$author_line = ($icsetting['author'] == 1) ? '<p>'._L('ic-ans-author').': '.$author_name . $date_value.'</p>' : '';
			$depart_line = ($icsetting['depart'] == 1) ? '<p>'._L('ic-office').': '. $user['occupation'] . '</p>' : '';

		$show_info = ($icsetting['apply_author_info'] == 0 || $icsetting['apply_author_info'] == 2) ? $category_line.' '.$author_line.' '.$depart_line . ' ' : '';

			$show_on_screen .= '
				<div class="question_item'.$high.'">
					<p class="vote_holder">
						<span class="grade">'.$search['total_rating'].'</span>
						' . print_totalrating_stars($search['total_rating']). '
					</p>
					<div class="question_fe">
						<h3><a href="?'.$lang.'=mod.infocentar&amp;sp=q&amp;id='.$search['permalink'].'&amp;ref=yes" title="'.$search['title'].'" name="'.$search['title'].'">'.$search['title'].'</a></h3>
						<div class="author_information">'.$show_info.'</div>
					</div>
					<div class="cleaner"></div>
				</div>
			';
			$i++;
			$search = $dbc->_db->fetch_array($search_res);
		}

		// pagination
		require_once DOC_ROOT . '/orbicon/class/class.pagination.php';

		$pagination = new Pagination('p', 'pp');

		$pagination->total = $total;
		$pagination->split_pages();
		$nav .= '<p class="pagination">'. $pagination->construct_page_nav(ORBX_SITE_URL . '/?'.$orbicon_x->ptr.'=mod.infocentar&sp=search&submit_search&search_string=' . $_GET['search_string'] . $ascSortLink).'</p>';
		$show_on_screen .= '<div class="art_decor_bottom"></div>';
		$show_on_screen .= $nav;


	}
	else {
		$show_on_screen = '<p>'._L('ic-msg-search-fail').'</p>';
	}
}

?>