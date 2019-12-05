<?php

	// * import class files
	require_once DOC_ROOT.'/orbicon/modules/infocentar/class/question.class.php';
	require_once DOC_ROOT.'/orbicon/modules/infocentar/class/category.class.php';


	// delete questions
	if(isset($_POST['submit_delete']) && isset($_POST['q'])){

		$delQuest = new Question($_POST);
		$delQuest->__remove();

	}

	if($_GET['edit'] == 'question' || isset($_GET['new'])){

		// * include edit question mode
		include DOC_ROOT.'/orbicon/modules/infocentar/form/form.question.php';

	} else {

		echo '
			<p>
				<a href="?goto=question&amp;new=true&amp;'.$orbicon_x->ptr.'=orbicon/mod/infocentar" title="'._L('ic-new-question').'" title="'._L('ic-new-question').'">'._L('ic-new-question').'</a>
</p>
		';



?>
<form id="quest_form_list" method="post" action="">
<table id="question_list">
	<tr>
		<th><?php echo _L('ic-created');?></th>
		<th width="50%"><?php echo _L('ic-question');?></th>
		<th><?php echo _L('ic-category');?></th>
		<th><?php echo _L('ic-answered');?></th>
		<th><?php echo _L('ic-state');?></th>
	</tr>
	<?php


		// * create object question $q & category $c

		$q = new Question();
		$c = new Category();

		require_once DOC_ROOT . '/orbicon/class/class.pagination.php';
		$_GET['p'] = isset($_GET['p']) ? $_GET['p'] : 1;
		$_GET['pp'] = isset($_GET['pp']) ? $_GET['pp'] : $icsetting['admin_per_page'];
		$rowsPerPage = $_GET['pp'];
		$offset = ($_GET['p'] -1) * $rowsPerPage;
		$pagination = new Pagination('p', 'pp');

		if(isset($_GET['category'])){
			$question_list = $q->get_category_questions($_GET['category'], $offset, $rowsPerPage);
			$total = $dbc->_db->query('SELECT COUNT(id) FROM orbx_mod_ic_question WHERE category='.$dbc->_db->quote($_GET['category']));
			$total = $dbc->_db->fetch_array($total);
			$total = $total[0];
			$pagination->total = $total;
			$pagination->split_pages();
			$nav .='<p class="pagination">'. $pagination->construct_page_nav('?category='.$_GET['category'].'&amp;'.$orbicon_x->ptr.'=orbicon/mod/infocentar').'</p>';

		} else {
			// * reterive any number of questions sorted by date desceding, default 10
			$question_list = $q->get_unsorted_questions($offset, $rowsPerPage);
			$total = $dbc->_db->query('SELECT COUNT(id) FROM orbx_mod_ic_question WHERE category="0"');
			$total = $dbc->_db->fetch_array($total);
			$total = $total[0];
			$pagination->total = $total;
			$pagination->split_pages();
			$nav .='<p class="pagination">'. $pagination->construct_page_nav(ORBX_SITE_URL . '/?'.$orbicon_x->ptr.'=orbicon/mod/infocentar').'</p>';
		}

		// * simple counter
		$i = 1;
		$question = $dbc->_db->fetch_assoc($question_list);
		
		$numOfQuestions = $dbc->_db->num_rows($question_list);

		if($numOfQuestions == 0){

			$question_list = $q->get_unsorted_questions(0, $offset, $rowsPerPage);
			$total = $dbc->_db->query('SELECT COUNT(id) FROM orbx_mod_ic_question WHERE category='.$dbc->_db->quote(0));
			$total = $dbc->_db->fetch_array($total);
			$total = $total[0];
			$numOfQuestions = $total;
			$pagination->total = $total;
			$pagination->split_pages();
			$nav ='<p class="pagination">'. $pagination->construct_page_nav('?category='.$_GET['category'].'&amp;'.$orbicon_x->ptr.'=orbicon/mod/infocentar').'</p>';

			$question = $dbc->_db->fetch_assoc($question_list);
		}

		$i = 1;
		while($question){

			//$id_t = (isset($_GET['category'])) ? $question['id'] : $question['qid'];
			$id_t = $question['id'];

			// * get answer status
			$answer = $q->get_answer($id_t);
			$answer = $dbc->_db->fetch_assoc($answer);
			$answer = $answer['content'];
			
			$answered = (strip_tags($answer) == '') ? _L('ic-ans-no') : _L('ic-ans-yes');


			$category = $c->get_category($question['category']);
			$cat_name = (isset($_GET['category'])) ? $category['title']  : $question['category'];

			$cat_name = (empty($cat_name)) ? _L('unsorted') : $cat_name;

			$live = ($question['state'] == 0) ? _L('ic-inactive') : _L('ic-active');

			$dots = (strlen($question['title']) > 60) ? '...' : '';

			$high = ($i & 1) ? ' class="high"' : '';

			echo '

			<tr>
				<td'.$high.' align="center">
					'.date("d.m.Y.", $question['created']).'<br />
					<span style="font-size: 10px;">('.date("H:i:s", $question['created']).')</span>
				</td>
				<td'.$high.'>
					<input type="checkbox" id="q[]" name="q[]" value="'.$id_t.'" />
					<a href="?'.$orbicon_x->ptr.'=orbicon/mod/infocentar&amp;goto=question&amp;edit=question&amp;id='.$id_t.'">'.substr(utf8_html_entities($question['title']), 0, 60).$dots.'</a>
				</td>
				<td'.$high.' align="center">'.$cat_name.'</td>
				<td'.$high.' align="center">'.$answered.'</td>
				<td'.$high.' align="center">'.$live.'</td>
			</tr>

			';
			/*echo '
			<tr>
				<td colspan="5">
					<p>'.utf8_html_entities($question['title']).'</p>
					<div>
						<span>'._L('ic-created').': <em>'.date("d.m.Y. H:m:i", $question['created']).'</em></span>
						<span>'._L('ic-category').': <em>'.$cat_name.'</em></span>
						<span>'._L('ic-answered').': <em>'.$answered.'</em></span>
						<span>'._L('ic-state').': <em>'.$live.'</em></span>
					</div>
				</td>
			</tr>
			';*/

			$numOfQuestions--;
			$i++;
			$question = $dbc->_db->fetch_assoc($question_list);

		}

	?>
</table>
<p>
	<input type="submit" name="submit_delete" id="submit_delete" value="<?php echo _L('ic-delete-select');?>" />
</p>
</form>
<?php
	echo $nav;
	}
?>
