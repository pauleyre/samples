<?php


require_once DOC_ROOT.'/orbicon/modules/infocentar/class/question.class.php';

$q = new Question;

// * this is pagination stuff
require_once DOC_ROOT . '/orbicon/class/class.pagination.php';
$_GET['p'] = isset($_GET['p']) ? $_GET['p'] : 1;
$_GET['pp'] = isset($_GET['pp']) ? $_GET['pp'] : 8;

$total = $dbc->_db->query('SELECT COUNT(id) FROM orbx_mod_ic_question');
$total = $dbc->_db->fetch_array($total);
$total = $total[0];

$rowsPerPage = $_GET['pp'];
$offset = ($_GET['p'] -1) * $rowsPerPage;
$pagination = new Pagination('p', 'pp');
$pagination->total = $total;
$pagination->split_pages();
$nav .='<p class="pagination">'. $pagination->construct_page_nav(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/infocentar&amp;goto=log').'</p>';

$question_res = $q->get_latest_questions($offset, $rowsPerPage, 0);

$table = '
<table id="ic_log_list">
	<tr>
		<th width="60%">'._L('ic-question').'</th>
		<th width="30%">'._L('ic-stat-last-view').'</th>
		<th width="10%">'._L('ic-stat-hits').'</th>
	</tr>
';

// * list all questions and their respective values
while($question = $dbc->_db->fetch_array($question_res)){

	$log_click = $q->__count_clicks($question['qid']);
	$last_log = $q->__log_last_entry($question['qid']);

	$last_entry = ($last_log['clicked'] == '') ? '&nbsp;' : $last_log['clicked'];

	$table .= '
		<tr>
			<td>'.$question['title'].'</td>
			<td align="center">'.$last_entry.'</td>
			<td align="center">'.$log_click['click_num'].'</td>
		</tr>
	';



}

$table .='</table>';

echo $table;

echo $nav;

?>