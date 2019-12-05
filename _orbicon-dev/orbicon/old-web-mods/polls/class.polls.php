<?php
/**
 * Class for polls and survey handling
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package OrbiconMOD
 * @version 2.0
 * @link http://
 * @license http://
 * @since 2006-07-01
 * @subpackage Polls
 */

/**
 * table name for survey results
 *
 */
define('TABLE_SURVEY_RESULTS', 'orbicon_survey_results');
/**
 * table name for poll results
 *
 */
define('TABLE_POLLS_RESULTS', 'orbx_mod_polls_results');

class Poll
{
	var $display_control_links;

	function poll()
	{
		$this->__construct();
	}

	function __construct()
	{
		$this->display_control_links = false;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $poll_data
	 * @return unknown
	 */
	function _get_poll_display($poll_data)
	{
		$poll = '';
		if(!empty($poll_data['permalink'])) {
			// title
			$poll .= '<div id="poll_inner_'.$poll_data['permalink'].'">';

			if(isset($_COOKIE[$this->get_id_from_permalink($poll_data['permalink'])])) {
				if($poll_data['locked_view'] == 1) {
					$poll .= $this->get_poll_vote_options($poll_data['permalink'], $poll_data['title']);
				}
				else if($_SESSION['site_settings']['poll_after_vote'] == 'options') {
					return $this->get_poll_vote_options($poll_data['permalink'], $poll_data['title']);
				}
				else {
					$poll .= $this->get_poll_results($poll_data['permalink'], $poll_data['title']);
				}
			}
			else {
				$poll .= $this->get_poll_vote_options($poll_data['permalink'], $poll_data['title']);
			}
			$poll .= '</div>';
		}
		return $poll;
	}

	/**
	 * return polls
	 *
	 * @return string
	 */
	function get_poll()
	{
		global $dbc, $orbicon_x, $orbx_log, $orbx_mod;

		// zone polls first
		if(!empty($_SESSION['current_zone'])) {
			foreach($_SESSION['current_zone'] as $value) {
				$q = sprintf('	SELECT 		title, permalink,
											zone, end_date,
											locked_view
								FROM 		'.TABLE_POLL.'
								WHERE 		(end_date > UNIX_TIMESTAMP()) AND
											(zone = %s) AND
											(language = %s)
								ORDER BY 	start_date DESC',
								$dbc->_db->quote($value['permalink']), $dbc->_db->quote($orbicon_x->ptr));

				$r = $dbc->_db->query($q);

				if((int) $dbc->_db->num_rows($r) === 0) {
					$q = sprintf('	SELECT 		title, permalink,
												zone, end_date,
												locked_view
									FROM 		'.TABLE_POLL.'
									WHERE 		(end_date > UNIX_TIMESTAMP()) AND
												(zone = %s) AND
												(language = %s)
									ORDER BY 	end_date DESC
									LIMIT 		1',
									$dbc->_db->quote($value['permalink']), $dbc->_db->quote($orbicon_x->ptr));

					$r = $dbc->_db->query($q);
				}

				$a = $dbc->_db->fetch_assoc($r);

				while($a) {
					$poll .= $this->_get_poll_display($a);
					$a = $dbc->_db->fetch_assoc($r);
				}

				// one-time only
				$q = sprintf('	SELECT 		title, permalink,
											zone, locked_view
								FROM 		'.TABLE_POLL.'
								WHERE 		(end_date = 0) AND
											(start_date <= UNIX_TIMESTAMP()) AND
											(zone = %s) AND
											(language = %s)
								ORDER BY 	start_date DESC
								LIMIT		1',
								$dbc->_db->quote($value['permalink']), $dbc->_db->quote($orbicon_x->ptr));

				$r = $dbc->_db->query($q);
				$a = $dbc->_db->fetch_assoc($r);

				if($a) {
					$poll .= $this->_get_poll_display($a);
				}
			}
		}
		else {
			trigger_error('Current website zone is not set', E_USER_NOTICE);
		}


		// global polls last
		$q = sprintf('	SELECT 	title, permalink,
								zone, locked_view
						FROM 	'.TABLE_POLL.'
						WHERE 	(end_date > UNIX_TIMESTAMP()) AND
								((zone = \'all\') OR (zone= \'\')) AND
								(language = %s)
						ORDER BY start_date DESC', $dbc->_db->quote($orbicon_x->ptr));
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$poll .= $this->_get_poll_display($a);
			$a = $dbc->_db->fetch_assoc($r);
		}

		// global one-time only
		$q = sprintf('	SELECT 		title, permalink,
									zone, locked_view
						FROM 		'.TABLE_POLL.'
						WHERE 		(end_date = 0) AND
									(start_date <= UNIX_TIMESTAMP()) AND
									((zone = \'all\') OR (zone= \'\')) AND
									(language = %s)
						ORDER BY 	start_date DESC
						LIMIT		1',
						$dbc->_db->quote($orbicon_x->ptr));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		if($a) {
			$poll .= $this->_get_poll_display($a);
		}

		if($this->display_control_links) {
			if($orbx_mod->validate_module('past-polls')) {
				$past_polls_link = url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.past-polls', ORBX_SITE_URL.'/'.$orbicon_x->ptr.'/mod.past-polls');
				$past_polls_link = '<div class="poll_ft"><a id="poll_past_polls" href="'.$past_polls_link.'">'._L('past_polls').'</a></div>';
				$poll .= $past_polls_link;
			}
		}

		return $poll;
	}

	// poll vote options
	function get_poll_vote_options($poll_permalink, $poll_title = '', $force_disabled = false)
	{
		global $dbc, $orbicon_x;

		// are we a survey?
		if($this->_is_survey($poll_permalink)) {
			return $this->get_survey($poll_permalink, $poll_title, $force_disabled);
		}

		// title
		$poll_title = ($poll_title == '') ? $this->get_poll_title($poll_permalink) : $poll_title;

		$q = sprintf('	SELECT 		title, option_id
						FROM 		'.TABLE_POLL_OPTIONS.'
						WHERE 		(poll_permalink = %s) AND
									(title != \'\')
						ORDER BY 	option_id', $dbc->_db->quote($poll_permalink));
		$r = $dbc->_db->query($q);
		$a_ = $dbc->_db->fetch_assoc($r);

		if($force_disabled) {
			$disabled = 'disabled="disabled"';
		}
		else {
			$disabled = (($this->_is_locked($poll_permalink) == 1) && isset($_COOKIE[$this->get_id_from_permalink($poll_permalink)])) ? 'disabled="disabled"' : '';
		}

		while($a_) {
			$poll .= '<div class="poll_vote_option"><input '.$disabled.' id="poll_opt_'.$a_['option_id'].'" name="poll_'.$poll_permalink.'" type="radio" value="'.$a_['option_id'].'" />
									<label for="poll_opt_'.$a_['option_id'].'" style="font-size: 11px;"> '.$a_['title'].'</label></div>';

			$a_ = $dbc->_db->fetch_assoc($r);
		}
		//buttons
		if(!isset($_COOKIE[$this->get_id_from_permalink($poll_permalink)])) {

			$results_link = ($this->_is_locked($poll_permalink)) ? '' : '<a id="poll_view_results" href="javascript:void(null);" onclick="javascript:__poll_view_results(\''.$poll_permalink.'\', 1);">'._L('results').'</a>';

			$poll .= '<div class="poll_ft"><a class="poll_cast_vote" href="javascript:void(null);" onclick="javascript:__poll_cast_vote(\''.$poll_permalink.'\');">'._L('vote').'</a>&nbsp;' . $results_link . '</div>';
		}

		return '<p class="poll_title">'.$poll_title.'</p><form action="" id="poll_form_'.$poll_permalink.'" name="poll_form_'.$poll_permalink.'"><div class="poll_bd">'.$poll.'</div></form>';
	}

	/**
	 * return poll results for $poll_permalink
	 *
	 * @param string $poll_permalink
	 * @param string $poll_title
	 * @return string
	 */
	function get_poll_results($poll_permalink, $poll_title = '')
	{
		global $dbc, $orbicon_x, $orbx_mod;

		$mod_params = $orbx_mod->load_info('polls');

		// surveys exit here
		if($this->_is_survey($poll_permalink)) {
			return $this->get_survey_results($poll_permalink, $poll_title);
		}

		$poll_title = ($poll_title == '') ? $this->get_poll_title($poll_permalink) : $poll_title;

		$votes = 0;

		$r = $dbc->_db->query(sprintf('
										SELECT 		id, title
										FROM 		'.TABLE_POLL_OPTIONS.'
										WHERE 		(poll_permalink = %s) AND
													(title != \'\')
										ORDER BY 	option_id
										LIMIT 		%s',
										$dbc->_db->quote($poll_permalink), $dbc->_db->quote($_SESSION['site_settings']['max_poll_options'])));
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {

			// get votes
			$r_results = $dbc->_db->query(sprintf('
										SELECT 		votes
										FROM 		'.TABLE_POLLS_RESULTS.'
										WHERE 		(parent_option_id = %s)
										LIMIT 		1',
										$dbc->_db->quote($a['id'])));
			$a_results = $dbc->_db->fetch_assoc($r_results);

			$votes += $a_results['votes'];
			// merge info and append
			$option[] = array_merge($a, $a_results);
			$a = $dbc->_db->fetch_assoc($r);
		}

		$results = '<table>';

		foreach($option as $value) {

			// this might not be neccessary
			//$votes = ($votes < 1) ? 1 : $votes;
			if(!isset($value['votes'])) {
				$value['votes'] = 0;
			}
			$voted_in_percent = rounddown(($value['votes'] / $votes) * 100);
			$voted_value = $voted_in_percent . ' %';

			$width = rounddown($voted_in_percent);
			$width = ($width < 1) ? 1 : $width;
			$width = ($width > 100) ? 100 : $width;
			// explorer can't handle 100% width without going wild
			$width = ($width == 100) ? 90 : $width;

			$voted_value = ($_SESSION['site_settings']['poll_votes_display'] == 'percent') ? $voted_value : $value['votes'];

			$results .= '<tr>
				<td class="orbx_poll_item_title">'.$value['title'].'</td>
				<td class="orbx_poll_item_bar"><img src="' . ORBX_SITE_URL . '/orbicon/gfx/' . $mod_params['poll']['bar'] . '" style="width:' . $width . '%;" alt="' . $value['votes'] . '" title="'.$value['votes'].'" />' . $voted_value . '</td></tr>';
		}

		$results .= '<tr><td colspan="2" class="answered_by">' . sprintf(_L('poll_answered_by'), $votes) . '</td></tr>';
		$results .= '</table>';

		if($this->display_control_links) {
			$vote_link = (isset($_COOKIE[$this->get_id_from_permalink($poll_permalink)])) ? '' : '<a id="poll_view_vote_options" class="poll_vote" href="javascript:void(null);" onclick="javascript:__poll_view_vote(\''.$poll_permalink.'\');">&laquo; ' . _L('vote') . '</a> ';
			$results .= '<div class="poll_ft">' . $vote_link . '</div><hr size="1" class="hr_style" />';
		}

		return '<p class="poll_title">'.$poll_title.'</p><form action="" id="poll_form_'.$poll_permalink.'" name="poll_form_'.$poll_permalink.'">'.$results.'</form>';
	}

	/**
	 * casts user's poll vote and returns results
	 *
	 * @return string	formatted HTML
	 */
	function cast_poll_vote()
	{
		global $dbc, $orbx_log, $orbicon_x;

		$poll_permalink = $_POST['poll'];
		$poll_option = $_POST['poll_option'];
		if(!setcookie($this->get_id_from_permalink($poll_permalink), time(), (time() + 9999999), '/', '.' . DOMAIN_NO_WWW)) {
			trigger_error('Could not set cookie "' . $poll_permalink . '"', E_USER_NOTICE);
		}

		// get id
		$r = $dbc->_db->query(sprintf('	SELECT 		id
										FROM 		'.TABLE_POLL_OPTIONS.'
										WHERE 		(poll_permalink = %s) AND
													(option_id = %s)
										LIMIT 		1', $dbc->_db->quote($poll_permalink), $dbc->_db->quote($poll_option)));
		$option = $dbc->_db->fetch_assoc($r);

		// get current votes
		$r = $dbc->_db->query(sprintf('	SELECT 		votes
										FROM 		'.TABLE_POLLS_RESULTS.'
										WHERE 		(parent_option_id = %s)
										LIMIT 		1', $dbc->_db->quote($option['id'])));
		$votes = $dbc->_db->fetch_assoc($r);

		$votes = $votes['votes'];
		$votes += 1;

		$q = sprintf('	UPDATE 	'.TABLE_POLLS_RESULTS.'
						SET 	votes=%s
						WHERE 	(parent_option_id=%s)',
					$dbc->_db->quote($votes), $dbc->_db->quote($option['id']));

		$dbc->_db->query($q);

		// this doesn't get synced so we have to have an additional check

		// check if update status
		$q_c = sprintf('	SELECT 	votes
							FROM 	'.TABLE_POLLS_RESULTS.'
							WHERE	(parent_option_id=%s)
							LIMIT 	1',
							$dbc->_db->quote($option['id']));

		$r_c = $dbc->_db->query($q_c);
		$a_c = $dbc->_db->fetch_array($r_c);

		// UPDATE failed, try with INSERT
		if($a_c['votes'] === null) {
			$q = sprintf('	INSERT INTO 	'.TABLE_POLLS_RESULTS.'
											(parent_option_id, votes)
							VALUES 			(%s, %s)',
							$dbc->_db->quote($option['id']), $dbc->_db->quote($votes));
			$dbc->_db->query($q);
		}

		// are we a locked poll?

		if($this->_is_locked($poll_permalink) == 1) {
			return $this->get_poll_vote_options($poll_permalink, '', true);
		}
		else if($_SESSION['site_settings']['poll_after_vote'] == 'options') {
			return $this->get_poll_vote_options($poll_permalink);
		}

		return $this->get_poll_results($poll_permalink);
	}

	/**
	 * return expired polls list
	 *
	 * @return string
	 */
	function get_past_polls()
	{
		global $dbc, $orbicon_x;

		$q = sprintf('	SELECT 		title, permalink
						FROM 		'.TABLE_POLL.'
						WHERE 		(end_date < UNIX_TIMESTAMP()) AND
									(locked_view = 0) AND
									(language = %s)
						ORDER BY 	end_date DESC',
						$dbc->_db->quote($orbicon_x->ptr));

		$a = $dbc->_db->get_cache($q);
		if($a !== null) {
			return $a;
		}

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$polls .= '<li><a onclick="javascript:__poll_view_results(\''.$a['permalink'].'\', 0);" href="javascript:void(null);">'.$a['title'].'</a></li>';
			$a = $dbc->_db->fetch_assoc($r);
		}

		$polls = "<ol id=\"past_polls_list\">$polls</ol>";
		$polls = (empty($polls)) ? _L('no_past_polls') : '<div id="past_polls_preview"></div>' . $polls;

		$dbc->_db->put_cache($polls, $q);

		return $polls;
	}

	/**
	 * Return survey with $permalink
	 *
	 * @param string $permalink
	 * @param string $poll_title
	 * @param bool $force_disabled
	 * @return string
	 */
	function get_survey($permalink, $poll_title = '', $force_disabled = false)
	{
		global $dbc;

		$poll_title = ($poll_title == '') ? $this->get_poll_title($permalink) : $poll_title;

		// get answers
		$r_columns = $dbc->_db->query(sprintf('	SELECT 		*
												FROM 		'.TABLE_POLL_OPTIONS.'
												WHERE 		(poll_permalink = %s) AND
															(title != \'\')
												ORDER BY 	option_id
												LIMIT 		%s', $dbc->_db->quote($permalink), $dbc->_db->quote($_SESSION['site_settings']['max_poll_options'])));
		// get questions
		$r_rows = $dbc->_db->query(sprintf('	SELECT 		*
												FROM 		'.TABLE_SURVEY_QUESTIONS.'
												WHERE 		(poll_permalink = %s) AND
															(title != \'\')
												ORDER BY 	option_id
												LIMIT 		%s', $dbc->_db->quote($permalink), $dbc->_db->quote($_SESSION['site_settings']['max_poll_options'])));

		$a_columns = $dbc->_db->fetch_assoc($r_columns);
		$a_rows = $dbc->_db->fetch_assoc($r_rows);

		// get answers options
		// WTF ->
		while($a_columns) {
				$questions .= '<option value="' . $a_columns['option_id'] . '" id="poll_opt_'.$a_columns['option_id'].'" name="poll_'.$permalink.'" type="radio" value="'.$a_columns['option_id'].'">' . $a_columns['title']  . '</option>';

			$a_columns = $dbc->_db->fetch_assoc($r_columns);
		}

		// build survey container
		$survey = '<table class="survey_table"><tbody><tr><td>'._L('survey_q').'</td><td>'._L('survey_a').'</td></tr>';

		if($force_disabled == true) {
			$disabled = 'disabled="disabled"';
		}
		else {
			$disabled = (($this->_is_locked($permalink) == 1) && isset($_COOKIE[$this->get_id_from_permalink($permalink)])) ? 'disabled="disabled"' : '';
		}
		// build questions
		while($a_rows) {
			$survey .= '<tr><td class="survey_q"><label for="survey_q_' . $a_rows['id'] .'">' . $a_rows['title'] . '</label></td><td class="survey_a"><select ' . $disabled . ' id="survey_q_' . $a_rows['id'] .'" name="survey_q_' . $a_rows['id'] .'"><option value="">&mdash;</option>' . $questions . '</select></td></tr>';

			$a_rows = $dbc->_db->fetch_assoc($r_rows);
		}

		// free memory
		unset($questions);

		// close container
		$survey .= '</tbody></table>';

		//buttons
		if(!isset($_COOKIE[$this->get_id_from_permalink($permalink)])) {

			// locked polls don't allow results preview
			$results_link = ($this->_is_locked($permalink) == 1) ? '' : ' <a id="poll_view_results" href="javascript:void(null);" onclick="javascript:__poll_view_results(\''.$permalink.'\', 1);">'._L('results').'</a>';

			$survey .= '<a id="poll_cast_vote" href="javascript:void(null);" onclick="javascript:__survey_cast_vote(\''.$permalink.'\', \''._L('survey_answer_all').'\');">'._L('vote').'</a>&nbsp;' . $results_link;

			unset($results_link);
		}

		// return everything
		return '<p class="poll_title">'.$poll_title.'</p><form action="" id="poll_form_'.$permalink.'" name="poll_form_'.$permalink.'">'.$survey.'</form>';
	}

	/**
	 * return title for poll with $poll_permalink
	 *
	 * @param string $poll_permalink
	 * @return bool
	 */
	function get_poll_title($poll_permalink)
	{
		if($poll_permalink == '') {
			trigger_error('Poll permalink required', E_USER_WARNING);
			return false;
		}

		global $dbc, $orbicon_x;

		// get title
		$q_t = sprintf('SELECT 	title
						FROM 	'.TABLE_POLL.'
						WHERE 	(permalink = %s) AND
								(language = %s)
						LIMIT 	1',
						$dbc->_db->quote($poll_permalink),
						$dbc->_db->quote($orbicon_x->ptr)
					);
		$r_t = $dbc->_db->query($q_t);
		$a_t = $dbc->_db->fetch_assoc($r_t);
		return $a_t['title'];
	}

	/**
	 * casts user's votes for survey and returns survey results
	 *
	 * @return string
	 */
	function cast_survey_vote()
	{
		global $dbc, $orbx_log, $orbicon_x;

		$poll_permalink = $_REQUEST['poll'];
		$poll_options = explode('*', $_REQUEST['poll_option']);

		if(!setcookie($this->get_id_from_permalink($poll_permalink), time(), (time() + 9999999), '/', '.' . DOMAIN_NO_WWW)) {
			trigger_error('Could not set cookie "' . $poll_permalink . '"', E_USER_NOTICE);
		}

		foreach($poll_options as $poll_option_el) {

			list($poll_option, $option_id) = explode('=', str_replace('survey_q_', '', $poll_option_el));

			$r = $dbc->_db->query(sprintf('	SELECT 		votes
											FROM 		'.TABLE_SURVEY_RESULTS.'
											WHERE 		(poll_permalink = %s) AND
														(question_id = %s) AND
														(option_id = %s)
											LIMIT 		1',
			$dbc->_db->quote($poll_permalink),
			$dbc->_db->quote($poll_option),
			$dbc->_db->quote($option_id)));

			$votes = $dbc->_db->fetch_assoc($r);
			$votes = $votes['votes'];
			$votes += 1;

			$q = sprintf('	UPDATE 		'.TABLE_SURVEY_RESULTS.'
							SET			votes=%s
							WHERE 		(poll_permalink=%s) AND
										(question_id = %s) AND
										(option_id = %s)',
						$dbc->_db->quote($votes),
						$dbc->_db->quote($poll_permalink),
						$dbc->_db->quote($poll_option),
						$dbc->_db->quote($option_id));

			$dbc->_db->query($q);

			// this doesn't get synced so we have to have an additional check

			// check if update status
			$q_c = sprintf('	SELECT 	votes
								FROM 	'.TABLE_SURVEY_RESULTS.'
								WHERE 	(poll_permalink=%s) AND
										(question_id = %s) AND
										(option_id = %s)
								LIMIT 	1',
								$dbc->_db->quote($poll_permalink),
								$dbc->_db->quote($poll_option),
								$dbc->_db->quote($option_id));

			$r_c = $dbc->_db->query($q_c);
			$a_c = $dbc->_db->fetch_array($r_c);

			// UPDATE failed, try with INSERT
			if($a_c['votes'] === null) {

				$q = sprintf('	INSERT INTO 	'.TABLE_SURVEY_RESULTS.'
												(question_id, option_id,
												poll_permalink, votes)
								VALUES 			(%s, %s,
												%s, %s)',
							$dbc->_db->quote($poll_option), $dbc->_db->quote($option_id),
							$dbc->_db->quote($poll_permalink), $dbc->_db->quote($votes));

				$dbc->_db->query($q);
			}
		}

		if($this->_is_locked($poll_permalink) == 1) {
			return $this->get_poll_vote_options($poll_permalink, '', true);
		}
		else if ($_SESSION['site_settings']['poll_after_vote'] == 'options') {
			return $this->get_poll_vote_options($poll_permalink);
		}

		return $this->get_poll_results($poll_permalink);
	}

	/**
	 * return survey results
	 *
	 * @param string $poll_permalink
	 * @param string $poll_title
	 * @return string
	 */
	function get_survey_results($poll_permalink, $poll_title = '')
	{
		global $dbc, $orbicon_x, $orbx_mod;

		$poll_title = ($poll_title == '') ? $this->get_poll_title($poll_permalink) : $poll_title;

		$r_q = $dbc->_db->query(sprintf('
										SELECT 		id, title
										FROM 		'.TABLE_SURVEY_QUESTIONS.'
										WHERE 		(poll_permalink = %s) AND
													(title != \'\')
										ORDER BY 	option_id
										LIMIT 		%s',
										$dbc->_db->quote($poll_permalink), $dbc->_db->quote($_SESSION['site_settings']['max_poll_options'])));

		$a_q = $dbc->_db->fetch_assoc($r_q);

		while($a_q) {

			$results .= '<p class="poll_title">'.$a_q['title'].'</p><table class="survey_results_table">';

			$r = $dbc->_db->query(sprintf('
											SELECT 		votes, option_id
											FROM 		'.TABLE_SURVEY_RESULTS.'
											WHERE 		(poll_permalink = %s) AND
														(question_id = %s)
											ORDER BY 	option_id',
											$dbc->_db->quote($poll_permalink), $dbc->_db->quote($a_q['id']), $dbc->_db->quote($_SESSION['site_settings']['max_poll_options'])));
			$a = $dbc->_db->fetch_assoc($r);

			while($a) {
				$votes += $a['votes'];
				$option[] = $a;
				$a = $dbc->_db->fetch_assoc($r);
			}

			$total_votes += $votes;

			unset($a);

			foreach($option as $value) {
				//-- ADDED by Alen: Percentage ---------------------------------
				$votes = ($votes < 1) ? 1 : $votes;
				$voted_in_percent = rounddown(($value['votes'] / $votes) * 100);
				$voted_value = $voted_in_percent . ' %';

				$width = rounddown($voted_in_percent);
				$width = ($width < 1) ? 1 : $width;
				$width = ($width > 100) ? 100 : $width;
				// explorer can't handle 100% width without going wild
				$width = ($width == 100) ? 90 : $width;

				$r_opt = $dbc->_db->query(sprintf('
											SELECT 		title
											FROM 		'.TABLE_POLL_OPTIONS.'
											WHERE 		(poll_permalink = %s) AND
														(option_id = %s)
											LIMIT		1',
											$dbc->_db->quote($poll_permalink), $dbc->_db->quote($value['option_id'])));
				$a_opt = $dbc->_db->fetch_assoc($r_opt);

				if(!empty($a_opt['title'])) {

					$voted_value = ($_SESSION['site_settings']['poll_votes_display'] == 'percent') ? $voted_value : $value['votes'];

					$results .= '<tr>
						<td class="orbx_poll_item_title">'.$a_opt['title'].'</td>
						<td class="orbx_poll_item_bar"><img src="' . ORBX_SITE_URL . '/orbicon/gfx/poll_bar.gif" style="width:'.$width.'%;" alt="'.$value['votes'].'" title="'.$value['votes'].'" />'.$voted_value.'</td></tr>';
				}
			}
			//-- ADDED by Alen: Percentage END ------------------------------

			$results .= '</table>';
			$a_q = $dbc->_db->fetch_assoc($r_q);

			// clear this array
			unset($option, $votes);
			$total_survey_questions += 1;
		}

		if($this->display_control_links === true) {
			$vote_link = (isset($_COOKIE[$this->get_id_from_permalink($poll_permalink)])) ? '' : '<a id="poll_view_vote_options" href="javascript:void(null);" onclick="javascript:__poll_view_vote(\'' . $poll_permalink . '\');">&laquo; '._L('vote').'</a> ';
			$results .= '<div class="poll_ft">' . $vote_link . '</div>';
		}

		return '<p class="poll_title">' . $poll_title . '</p><form action="" id="poll_form_' . $poll_permalink . '" name="poll_form_' . $poll_permalink . '">' . $results . '<br /><span>' . sprintf(_L('poll_answered_by'), intval($total_votes / $total_survey_questions)) . '</span></form>';
	}

	/**
	 * return true if poll with $permalink is locked
	 *
	 * @param string $permalink
	 * @return bool
	 */
	function _is_locked($permalink)
	{
		global $dbc, $orbicon_x;
		$r = $dbc->_db->query(sprintf('
						SELECT 		locked_view
						FROM 		'.TABLE_POLL.'
						WHERE 		(permalink = %s) AND
									(language = %s)
						LIMIT 		1',
						$dbc->_db->quote($permalink), $dbc->_db->quote($orbicon_x->ptr)));
		$a = $dbc->_db->fetch_assoc($r);
		unset($r);
		return intval($a['locked_view']);
	}

	/**
	 * return true if poll with $permalink is a survey
	 *
	 * @param string $permalink
	 * @return bool
	 */
	function _is_survey($permalink)
	{
		global $dbc;
		$r = $dbc->_db->query(sprintf('	SELECT 		title
										FROM 		'.TABLE_SURVEY_QUESTIONS.'
										WHERE 		(poll_permalink = %s) AND
													(option_id = 1)
										LIMIT		1', $dbc->_db->quote($permalink)));

		$a = $dbc->_db->fetch_array($r);

		if($a['title'] == '') {
			return false;
		}

		return true;
	}

	/**
	 * Enter description here...
	 *
	 * @param string $permalink
	 * @param bool $only_id
	 * @return string
	 */
	function get_id_from_permalink($permalink, $only_id = false)
	{
		global $dbc;
		$q = sprintf('	SELECT 		id
						FROM 		'.TABLE_POLL.'
						WHERE 		(permalink = %s)
						LIMIT 		1',
						$dbc->_db->quote($permalink));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		if($only_id) {
			return $a['id'];
		}

		return 'poll_cookie_' . $a['id'];
	}
}

?>