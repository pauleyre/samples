<?php
/*-----------------------------------------------------------------------*
	Project........:	Orbicon X framework 2
	File...........:
	Version........:	1.0 (22/10/2006)
	Author.........:	Pavle Gardijan (pavle.gardijan@orbitum.net) - Orbitum d.o.o.
	Copyright......:	(c) 2006 Orbitum internet communications d.o.o. (www.orbitum.net)
	Created........:	01/07/2006
	Notes..........:
	Modified.......:
*-----------------------------------------------------------------------*/
	require_once DOC_ROOT.'/orbicon/modules/polls/class.polls.admin.php';

	$polls = new Poll_Admin;
	$polls->delete_poll();
	$polls->save_poll();
	$my_poll = $polls->load_poll();
	$is_survey = $polls->_is_survey($_GET['edit']);

	$start_date = (empty($my_poll['start_date'])) ? time() : $my_poll['start_date'];
	$end_date = (empty($my_poll['end_date'])) ? time() : $my_poll['end_date'];

?>
<form id="polls_form" name="polls_form" action="" method="post" onsubmit="javascript: return verify_title('poll_title');">
<input name="save_poll" type="submit" id="save_poll" value="<?php echo _L('save'); ?>" />
<input <?php if(!isset($_GET['edit'])) {echo 'disabled="disabled"';} ?> type="button" value="<?php echo _L('add_new') ?>" onclick="javascript: redirect('<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/polls'; ?>');"  />

<input id="poll_start_date" name="poll_start_date" type="hidden" value="<?php echo $start_date; ?>" />
<input id="poll_end_date" name="poll_end_date" type="hidden" value="<?php echo $end_date; ?>" />

<input id="poll_zone" name="poll_zone" type="hidden" value="<?php echo $my_poll['zone']; ?>" />
<input id="locked_view" name="locked_view" type="hidden" value="<?php echo $my_poll['locked_view']; ?>" />

<div>
<table width="100%">
		 <tr>
            <td colspan="2" style="vertical-align:top;">
			<p><label for="poll_title"><?php echo _L('title'); ?></label><br />
                <input name="poll_title" type="text" id="poll_title" style="width:50em; padding: 3px;" value="<?php echo $my_poll['title']; ?>" />
            </p>

			<fieldset style="padding-left:20px;"><legend><label for="show_survey"><?php echo _L('survey_questions'); ?></label> <input <?php if($is_survey) { echo 'checked="checked"'; } ?> id="show_survey" type="checkbox" onclick="sh('survey_questions_ol');" /></legend>

			<ol style="padding-left:1em;" class="<?php if($is_survey) { echo 's"'; } else { echo 'h'; } ?>" id="survey_questions_ol">
			<?php

				$survey_results = array();
				$i = 1;

				while($i <= $_SESSION['site_settings']['max_poll_options']) {
					if(isset($_GET['edit'])) {
						$q = sprintf('	SELECT 	title
										FROM 	'.TABLE_SURVEY_QUESTIONS.'
										WHERE 	(option_id = %s) AND
												(poll_permalink = %s)
										LIMIT 	1', $dbc->_db->quote($i), $dbc->_db->quote($_GET['edit']));
						$r = $dbc->_db->query($q);
						$a = $dbc->_db->fetch_assoc($r);
						$poll_options[$i] = $a['title'];
					}
			?>
				<li>
					<input maxlength="100" type="text" style="width:50em; padding: 3px;" name="survey_question_<?php echo $i; ?>" id="survey_question_<?php echo $i; ?>" value="<?php echo $poll_options[$i]; ?>" />
				</li>
			<?php
					$i ++;
				}

			?>
			</ol>
			</fieldset>

			<fieldset style="padding-left:20px;">
			<legend><?php echo _L('poll_choices'); ?></legend>
			<ol style="padding-left:1em;">
			<?php

				$i = 1;
				while($i <= $_SESSION['site_settings']['max_poll_options']) {
					if(isset($_GET['edit'])) {
						$q = sprintf('	SELECT 	id, title
										FROM 	'.TABLE_POLL_OPTIONS.'
										WHERE 	(option_id = %s) AND
												(poll_permalink = %s)
										LIMIT 	1', $dbc->_db->quote($i), $dbc->_db->quote($_GET['edit']));
						$r = $dbc->_db->query($q);
						$a = $dbc->_db->fetch_assoc($r);
						$poll_options[$i] = $a['title'];

						// poll results
						if(!$is_survey) {
							// get votes
							$r_results = $dbc->_db->query(sprintf('
														SELECT 		votes
														FROM 		'.TABLE_POLLS_RESULTS.'
														WHERE 		(parent_option_id = %s)
														LIMIT 		1',
														$dbc->_db->quote($a['id'])));
							$a_results = $dbc->_db->fetch_assoc($r_results);
						}
					}

					$item_attr = ($is_survey) ? 'disabled="disabled"' : '';
					$value = ($is_survey) ? _L('none') : $a_results['votes'];

			?>
				<li>
					<input type="text" style="width:40em; padding: 3px;" name="poll_option_<?php echo $i; ?>" id="poll_option_<?php echo $i; ?>" value="<?php echo $poll_options[$i]; ?>" />
					<input style="width: 10em; padding:3px;" <?php echo $item_attr; ?> type="text" id="poll_results_<?php echo $i; ?>" name="poll_results_<?php echo $i; ?>" value="<?php echo $value; ?>" />
				</li>
			<?php
					$i ++;
				}

			?>
			</ol>
			</fieldset>
			</td>
            <td></td>
    </tr>
</table>
<div style="clear: both;"></div>
</div>
<input name="save_poll" type="submit" id="save_poll2" value="<?php echo _L('save'); ?>" />
<input <?php if(!isset($_GET['edit'])) {echo 'disabled="disabled"';} ?> type="button" value="<?php echo _L('add_new') ?>" onclick="javascript: redirect('<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/polls'; ?>');"  />
</form>