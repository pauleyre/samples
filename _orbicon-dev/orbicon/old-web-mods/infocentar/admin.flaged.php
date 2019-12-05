<?php

	// * import class files
	require_once DOC_ROOT.'/orbicon/modules/infocentar/class/question.class.php';
	require_once DOC_ROOT.'/orbicon/modules/infocentar/class/category.class.php';


	// delete questions
	if(isset($_POST['submit_mix'])){

		$tmpQuest = new Question();
		
		if(!empty($_POST['ignore'])){
			$tmpQuest->__unflag($_POST['ignore']);
		}
		
		if(!empty($_POST['delete'])){
			$tmpQuest->flaged_to_delete($_POST['delete']);
		}

	}

?>
<form id="quest_flag_list" method="post" action="">
<table id="question_list">
	<tr>
		<th><?php echo _L('ic-created');?></th>
		<th width="60%"><?php echo _L('ic-question');?></th>
		<th><?php echo _L('ic-ignore');?></th>
		<th><?php if($_REQUEST['f'] == 'miscat') { echo _L('ic-category'); } else {echo _L('ic-delete');};?></th>
		<th><?php echo _L('ic-flags');?></th>
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

		$question_list = $q->get_flaged_questions($_GET['f'], $offset, $rowsPerPage);
		
		$total = $dbc->_db->query('SELECT COUNT(id) FROM orbx_mod_ic_question WHERE category='.$dbc->_db->quote($_GET['category']));
		$total = $dbc->_db->fetch_array($total);
		$total = $total[0];
		$pagination->total = $total;
		$pagination->split_pages();
		
		$nav .='<p class="pagination">'. $pagination->construct_page_nav('?category='.$_GET['category'].'&amp;'.$orbicon_x->ptr.'=orbicon/mod/infocentar').'</p>';


		// * simple counter
		$i = 1;
		$question = $dbc->_db->fetch_assoc($question_list);

		$numOfQuestions = $dbc->_db->num_rows($question_list);

		if($numOfQuestions == 0){
			echo '<tr><td colspan="5">No question is flaged.</td></tr>';
		}

		$i = 1;
		while($question){

			//$id_t = (isset($_GET['category'])) ? $question['id'] : $question['qid'];
			$id_t = $question['id'];

			$category = $c->get_category($question['category']);
			$cat_name = $category['title'];

			$cat_name = (empty($cat_name)) ? _L('unsorted') : $cat_name;

			$dots = (strlen($question['title']) > 60) ? '...' : '';

			$high = ($i & 1) ? ' class="high"' : '';
			
			switch($question['flag_question']){
				
				case 'duplicate' : 	$flag_title = _L('ic-flag-duplicate');
									break;	
				case 'nonsense' : 	$flag_title = _L('ic-flag-nonsense');
									break;	
				case 'spam' : 		$flag_title = _L('ic-flag-spam');
									break;	
				case 'miscat' : 	$flag_title = _L('ic-flag-miscat');
									break;
			}

			echo '

			<tr>
				<td'.$high.' align="center">
					'.date("d.m.Y.", $question['created']).'<br />
					<span style="font-size: 10px;">('.date("H:i:s", $question['created']).')</span>
				</td>
				<td'.$high.'>
					<a href="?'.$orbicon_x->ptr.'=orbicon/mod/infocentar&amp;goto=question&amp;edit=question&amp;id='.$id_t.'">'.substr(utf8_html_entities($question['title']), 0, 60).$dots.'</a>
				</td>
				<td'.$high.' align="center">
					<input type="checkbox" id="ignore[]" name="ignore[]" value="'.$id_t.'" />
				</td>
				<td'.$high.' align="center">';

			if($_REQUEST['f'] == 'miscat'){
				echo $cat_name;
			} else {
				echo '<input type="checkbox" id="delete[]" name="delete[]" value="'.$id_t.'" />';
			}
			
					
			echo '
				</td>
				<td'.$high.' align="center">'.$flag_title.'</td>
			</tr>

			';
			
			$numOfQuestions--;
			$i++;
			$question = $dbc->_db->fetch_assoc($question_list);

		}

	?>
</table>
<p>
	<input type="submit" name="submit_mix" id="submit_mix" value="<?php echo _L('ic-submit_mix');?>" />
</p>
</form>
<?php
	echo $nav;
?>
