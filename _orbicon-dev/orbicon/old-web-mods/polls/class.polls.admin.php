<?php
/**
 * Class for polls and survey handling
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @version 2.0
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-07-01
 * @subpackage Polls
 */

require_once DOC_ROOT . '/orbicon/modules/polls/class.polls.php';

class Poll_Admin extends Poll
{
	function load_poll()
	{
		if(isset($_GET['edit'])) {
			global $dbc, $orbicon_x;
			$q = sprintf('	SELECT 		*
							FROM 		'.TABLE_POLL.'
							WHERE 		(permalink=%s) AND
										(language = %s)
							LIMIT 		1',
							$dbc->_db->quote($_GET['edit']),
							$dbc->_db->quote($orbicon_x->ptr));

			$a = $dbc->_db->get_cache($q);
			if($a !== null) {
				return $a;
			}

			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);

			$dbc->_db->put_cache($a, $q);
			return $a;
		}
	}

	function save_poll()
	{
		if(isset($_POST['save_poll'])) {
			$title = trim($_POST['poll_title']);

			if($title == '') {
				return false;
			}

			$permalink = get_permalink($title);
			$title = utf8_html_entities($title);
			$date_start = $_POST['poll_start_date'];
			$date_end = $_POST['poll_end_date'];
			$zone = $_POST['poll_zone'];
			$zone = ($zone == '') ? 'all' : $zone;
			$locked_view = $_POST['locked_view'];

			global $dbc, $orbicon_x;

			if(isset($_GET['edit'])) {
				$q = sprintf('	UPDATE '.TABLE_POLL.'
								SET		title=%s, permalink=%s,
										start_date=%s, end_date=%s,
										zone=%s, locked_view = %s
								WHERE 	(permalink=%s) AND
										(language=%s)',
					$dbc->_db->quote($title), $dbc->_db->quote($permalink),
					$dbc->_db->quote($date_start), $dbc->_db->quote($date_end),
					$dbc->_db->quote($zone), $dbc->_db->quote($locked_view),
					$dbc->_db->quote($_GET['edit']), $dbc->_db->quote($orbicon_x->ptr));
			}
			else {
				$q = sprintf('	INSERT INTO 	'.TABLE_POLL.' (
												title, permalink,
												start_date, end_date,
												zone, locked_view,
												language)
								VALUES (		%s, %s,
												%s, %s,
												%s, %s,
												%s)',
					$dbc->_db->quote($title), $dbc->_db->quote($permalink),
					$dbc->_db->quote($date_start), $dbc->_db->quote($date_end),
					$dbc->_db->quote($zone), $dbc->_db->quote($locked_view),
					$dbc->_db->quote($orbicon_x->ptr));
			}

			// update relations
			if($_GET['edit'] != $permalink) {
				// for normal poll options
				$q_o = sprintf('	UPDATE 		'.TABLE_POLL_OPTIONS.'
									SET			poll_permalink=%s
									WHERE 		(poll_permalink=%s)',
									$dbc->_db->quote($permalink), $dbc->_db->quote($_GET['edit']));
				$dbc->_db->query($q_o);

				// for survey options
				$q_o = sprintf('	UPDATE 		'.TABLE_SURVEY_RESULTS.'
									SET			poll_permalink=%s
									WHERE 		(poll_permalink=%s)',
									$dbc->_db->quote($permalink), $dbc->_db->quote($_GET['edit']));
				$dbc->_db->query($q_o);

				// for survey questions
				$q_o = sprintf('	UPDATE 		'.TABLE_SURVEY_QUESTIONS.'
									SET			poll_permalink=%s
									WHERE 		(poll_permalink=%s)',
									$dbc->_db->quote($permalink), $dbc->_db->quote($_GET['edit']));
				$dbc->_db->query($q_o);
			}

			$dbc->_db->query($q);

			$this->save_survey_questions($permalink);
			$this->save_poll_options($permalink);

			redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/polls&edit=' . urlencode($permalink));
		}
	}

	function save_poll_options($poll_permalink)
	{
		global $dbc;

		$i = 1;

		if(isset($_GET['edit'])) {
			while($i <= $_SESSION['site_settings']['max_poll_options']) {
				$title = utf8_html_entities($_POST['poll_option_'.$i]);
				$new_results = intval($_POST['poll_results_'.$i]);

				$q = sprintf('	UPDATE 	' . TABLE_POLL_OPTIONS . '
								SET		title=%s
								WHERE 	(poll_permalink=%s) AND
										(option_id = %s)',
					$dbc->_db->quote($title),
					$dbc->_db->quote($poll_permalink),
					$dbc->_db->quote($i));
				$dbc->_db->query($q);

				// update results (poll only)
				if(!$this->_is_survey($poll_permalink)) {
					// get id
					$r = $dbc->_db->query(sprintf('	SELECT 		id
													FROM 		'.TABLE_POLL_OPTIONS.'
													WHERE 		(poll_permalink = %s) AND
																(option_id = %s)
													LIMIT 		1',
					$dbc->_db->quote($poll_permalink),
					$dbc->_db->quote($i)));
					$option = $dbc->_db->fetch_assoc($r);

					$q = sprintf('	UPDATE 	'.TABLE_POLLS_RESULTS.'
									SET 	votes=%s
									WHERE 	(parent_option_id=%s)',
					$dbc->_db->quote($new_results),
					$dbc->_db->quote($option['id']));

					$dbc->_db->query($q);
				}

				$i ++;
			}
		}
		else {
			while($i <= $_SESSION['site_settings']['max_poll_options']) {
				$title = utf8_html_entities($_POST['poll_option_'.$i]);
				$q = sprintf('	INSERT INTO 	'.TABLE_POLL_OPTIONS.'
												(title, poll_permalink,
												option_id)
								VALUES 			(%s, %s,
												%s)',
								$dbc->_db->quote($title), $dbc->_db->quote($poll_permalink),
								$dbc->_db->quote($i));
				$dbc->_db->query($q);
				// fetch id
				$new_id = $dbc->_db->insert_id();
				// prepare votes
				$q = sprintf('	INSERT INTO 	'.TABLE_POLLS_RESULTS.'
												(parent_option_id)
								VALUES 			(%s)',
								$dbc->_db->quote($new_id));
				$dbc->_db->query($q);

				$i ++;
			}
		}
	}

	// TABLE_SURVEY_QUESTIONS

	function save_survey_questions($poll_permalink)
	{
		global $dbc;

		$i = 1;

		if(isset($_GET['edit'])) {
			while($i <= $_SESSION['site_settings']['max_poll_options']) {

				$title = utf8_html_entities($_POST['survey_question_'.$i]);

				$q = sprintf('	UPDATE 		'.TABLE_SURVEY_QUESTIONS.'
								SET			title=%s
								WHERE 		(poll_permalink=%s) AND
											(option_id = %s)',
					$dbc->_db->quote($title),
					$dbc->_db->quote($poll_permalink),
					$dbc->_db->quote($i));

				$dbc->_db->query($q);

				$i ++;
			}
		}
		else {
			while($i <= $_SESSION['site_settings']['max_poll_options']) {

				$title = utf8_html_entities($_POST['survey_question_'.$i]);

				$q = sprintf('	INSERT INTO 	'.TABLE_SURVEY_QUESTIONS.'
												(title, poll_permalink,
												option_id)
								VALUES 			(%s, %s, %s)',
				$dbc->_db->quote($title), $dbc->_db->quote($poll_permalink),
				$dbc->_db->quote($i));

				$dbc->_db->query($q);

				$this->_build_survey_results_table($poll_permalink, $dbc->_db->insert_id());
				$i ++;
			}
		}
	}

	// one time setup for survey questions an their results
	function _build_survey_results_table($poll_permalink, $survey_question_id)
	{
		global $dbc;

		$i = 1;

		while($i <= $_SESSION['site_settings']['max_poll_options']) {
			$q = sprintf('	INSERT INTO 		'.TABLE_SURVEY_RESULTS.'
												(question_id, option_id,
												poll_permalink)
							VALUES 				(%s, %s,
												%s)',
							$dbc->_db->quote($survey_question_id), $dbc->_db->quote($i),
							$dbc->_db->quote($poll_permalink));

			$dbc->_db->query($q);
			$i ++;
		}
	}

	function build_polls_items()
	{
		global $dbc, $orbicon_x;

		if(empty($_REQUEST['poll_items_sort_by']) || ($_REQUEST['poll_items_sort_by'] == 'date')) {
			$r = $dbc->_db->query(sprintf('		SELECT 		*
												FROM 		'.TABLE_POLL.'
												WHERE 		(language = %s)
												ORDER BY 	start_date DESC', $dbc->_db->quote($orbicon_x->ptr)));
		}
		else if($_REQUEST['poll_items_sort_by'] == 'alpha') {
			$r = $dbc->_db->query(sprintf('		SELECT 		*
												FROM 		'.TABLE_POLL.'
												WHERE 		(language = %s)
												ORDER BY 	permalink ASC', $dbc->_db->quote($orbicon_x->ptr)));
		}
		else if($_REQUEST['poll_items_sort_by'] == 'zone') {
			$r = $dbc->_db->query(sprintf('		SELECT 		*
												FROM 		'.TABLE_POLL.'
												WHERE 		(language = %s)
												ORDER BY 	zone ASC', $dbc->_db->quote($orbicon_x->ptr)));
		}
		echo '<table><thead>
	<tr>
		<th>'._L('title').'</th>
		<th>'._L('published').'</th>
		<th>'._L('poll_date_range').'</th>
		<th>'._L('zone').'</th>
		<th>'._L('results').'</th>
		<th>'._L('delete').'</th>
	</tr></thead><tbody>';

		$found_oneshot_active = false;
		$i = 0;
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$style = (($i % 2) == 0) ? 'style="background:#eeeeee;"' : '';
			$status_img = ($a['end_date'] > time()) ? 'accept.png' : 'cancel.png';

			// special cases for one-shot polls
			if($a['end_date'] == 0) {

				// this will improve perfomance, since we found our only active one others are inactive - no need for SQL queries
				if(!$found_oneshot_active) {
					// global one-time only
					$q_ = sprintf('	SELECT 		id
									FROM 		'.TABLE_POLL.'
									WHERE 		(end_date = 0) AND
												(start_date <= UNIX_TIMESTAMP()) AND
												(language = %s)
									ORDER BY 	start_date DESC
									LIMIT		1',
									$dbc->_db->quote($orbicon_x->ptr));

					$r_ = $dbc->_db->query($q_);
					$a_ = $dbc->_db->fetch_assoc($r_);

					// this one is active
					if($a_['id'] == $a['id']) {
						$status_img = 'accept.png';
						$found_oneshot_active = true;
					}
					else {
						$status_img = 'cancel.png';
					}
				}
				else {
					$status_img = 'cancel.png';
				}
			}

			$date = (empty($a['end_date'])) ? date($_SESSION['site_settings']['date_format'], $a['start_date']) . ' +' : date($_SESSION['site_settings']['date_format'], $a['start_date']).' / '.date($_SESSION['site_settings']['date_format'], $a['end_date']);

			echo '
			<tr '.$style.'>
				<td><a href="' . ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/polls&amp;edit='.$a['permalink'].'">'.$a['title'] . '</a></td>
				<td><img src="' . ORBX_SITE_URL.'/orbicon/gfx/gui_icons/'.$status_img.'" /></td>
				<td>'.$date.'</td>
				<td>'.$a['zone'] . '</td>
				<td><a onclick="javascript:__poll_view_results(\'' . $a['permalink'] . '\');" href="javascript:void(null);"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/chart_bar.png" /></a></td>
				<td><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/polls&amp;delete_poll='.$a['permalink'].'" onclick="javascript:return false;" onmousedown="'.delete_popup(htmlspecialchars($a['title'])).'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/delete.png" alt="'._L('delete').'" title="'._L('delete').'" /></a></td>
			</tr>';

			$a = $dbc->_db->fetch_assoc($r);
			$i ++;
		}

		echo '</tbody></table>';
	}

	// delete poll
	function delete_poll()
	{
		if(isset($_GET['delete_poll']) && (get_is_admin() === true)) {
			global $dbc, $orbicon_x;
			$dbc->_db->query(sprintf('	DELETE
										FROM 		'.TABLE_POLL.'
										WHERE 		(permalink = %s) AND
													(language = %s)
										LIMIT 		1',
			$dbc->_db->quote($_GET['delete_poll']),
			$dbc->_db->quote($orbicon_x->ptr)));

			// delete options
			$dbc->_db->query(sprintf('	DELETE
										FROM 		'.TABLE_POLL_OPTIONS.'
										WHERE 		(poll_permalink = %s)',
			$dbc->_db->quote($_GET['delete_poll'])));

			// delete survey results
			$r = $dbc->_db->query(sprintf('	DELETE
											FROM 		'.TABLE_SURVEY_RESULTS.'
											WHERE 		(poll_permalink = %s)', $dbc->_db->quote($_GET['delete_poll'])));

			// refresh
			redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/polls');
		}
	}
}

?>