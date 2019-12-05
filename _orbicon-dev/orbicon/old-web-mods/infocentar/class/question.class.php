<?php

/**
 * Enter description here...
 *
 */
class Question
{
	/**
	 * Enter description here...
	 *
	 * @var unknown_type
	 */
	var $data;
	/**
	 * Enter description here...
	 *
	 * @var unknown_type
	 */
	var $lang;
	/**
	 * Enter description here...
	 *
	 * @var unknown_type
	 */
	var $dbconn;

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $data_array
	 * @return Question
	 */
	function Question($data_array = null)
	{
		// * do some setup here
		global $dbc;
		global $orbicon_x;

		$this->lang		= $orbicon_x->ptr;
		$this->data 	= $data_array;
		$this->dbconn	= $dbc->_db;
	}

	/**
	 * Enter description here...
	 *
	 */
	function set_new_question()
	{
		// MySQL 5 fix
		$this->data['notify'] = intval($this->data['notify']);
		$this->data['title'] = htmlspecialchars($this->data['title']);
		$this->data['author'] = htmlspecialchars($this->data['author']);
		$this->data['mail'] = htmlspecialchars($this->data['mail']);

		// user selected a category so build correct SQL
		if((int) $this->data['user_category'] > 0) {
			$sql_a = ', category';
			$sql_b = ',' . intval($this->data['user_category']);
		}
		else {
			$sql_a = $sql_b = '';
		}

		// * this function writes new question into db
		$sql = sprintf('	INSERT INTO			orbx_mod_ic_question
												(title, permalink, 
												author, mail, 
												notify, lang '.$sql_a.')
							VALUES				(%s, "", 
												%s, %s, 
												%s, %s '.$sql_b.')',
					$this->dbconn->quote($this->data['title']),
					$this->dbconn->quote($this->data['author']),
					$this->dbconn->quote($this->data['mail']),
					$this->dbconn->quote($this->data['notify']),
					$this->dbconn->quote($this->lang));

		$this->dbconn->query($sql);

		// notify admins
		$this->_notify_admins($this->data['title']);
	}

	function admin_sets_new_question()
	{
		// MySQL 5 fix
		$this->data['mail_answer'] = intval($this->data['mail_answer']);
		$this->data['title'] = htmlspecialchars($this->data['title']);
		$this->data['editor'] = htmlspecialchars($this->data['editor']);


		if(!$this->check_permalink($this->data['title'])){

			return false;
		}

		// * this function writes new question into db
		$sql = sprintf('	INSERT INTO	orbx_mod_ic_question
								(title, category, state, editor, lang, permalink, mail_answer)
							VALUES
								(%s, %s, %s, %s, %s, %s, %s)',
					$this->dbconn->quote($this->data['title']),
					$this->dbconn->quote($this->data['category']),
					$this->dbconn->quote($this->data['state']),
					$this->dbconn->quote($this->data['editor']),
					$this->dbconn->quote($this->lang),
					$this->dbconn->quote(get_permalink($this->data['title'])),
					$this->dbconn->quote($this->data['mail_answer']));

		return $this->dbconn->query($sql);
	}

	function check_permalink($str)
	{
		$sql = sprintf('SELECT * FROM orbx_mod_ic_question WHERE permalink = %s',
						$this->dbconn->quote(get_permalink($str)));

		$resource = $this->dbconn->query($sql);
		$result = $this->dbconn->num_rows($resource);

		if($result > 0){
			return false;
		}

		return true;
	}

	function set_new_answer($id = '')
	{
		$this->data['id'] = ($id == '') ? $this->data['id'] : $id;

		// * this function writes new answer into db
		$sql = sprintf('INSERT INTO		orbx_mod_ic_answer
										(content,
										author,
										question)
										VALUES (%s, %s, %s)',
					$this->dbconn->quote($this->data['content']),
					$this->dbconn->quote($this->data['author_id']),
					$this->dbconn->quote($this->data['id']));

		$this->dbconn->query($sql);

		// * notify submiter if needed
		$this->send_notification($this->data['id']);
	}

	function get_question($id, $state = '')
	{
		$state_def = ($state == '') ? '' : ' AND (state = ' . $this->dbconn->quote($state) . ') AND (mail_answer = 0)';
		// backend / fronted differ
		$table_column = (_get_is_orbicon_uri()) ? 'id' : 'permalink';

		// * this function retrieves question from db
		$sql = sprintf('SELECT		*, UNIX_TIMESTAMP(created) AS created,
									UNIX_TIMESTAMP(live) AS live
						FROM		orbx_mod_ic_question
						WHERE		('.$table_column.' = %s AND lang = %s)' . $state_def,
						$this->dbconn->quote($id),
						$this->dbconn->quote($this->lang));

		return $this->dbconn->query($sql);
	}

	function get_answer($question_id, $custom_column = '')
	{
		global $orbicon_x;
		// backend / fronted differ
		if($custom_column == '') {
			$table_column = (_get_is_orbicon_uri()) ? 'id' : 'permalink';
		}
		else {
			$table_column = $custom_column;
		}

		$sql = sprintf('	SELECT 		*
							FROM 		orbx_mod_ic_answer
							WHERE 		(question =
										(	SELECT 	id
											FROM 	orbx_mod_ic_question
											WHERE 	('.$table_column.' = %s)
										))',
							$this->dbconn->quote($question_id));

		return $this->dbconn->query($sql);
	}

	function edit_question()
	{
		if(!empty($this->data['tag'])){
			// write into tag handler table
			$this->handle_tags();
		}

		// pack tags
		$tags = serialize($this->data['tag']);

		// MySQL 5 fix
		$this->data['mail_answer'] = intval($this->data['mail_answer']);
		$this->data['title'] = htmlspecialchars($this->data['title']);
		$this->data['editor'] = htmlspecialchars($this->data['editor']);

		$this->data['permalink'] = ($this->data['permalink'] == '') ? get_permalink($this->data['title']) : $this->data['permalink'];

		$live_sql = '';
		// we're releasing this question live
		if(empty($this->data['live_time']) && ($this->data['state'] == 1)) {
			$live_sql = ',live=NOW()';
		}

		// * this function update qusetion values
		$sql = sprintf('
						UPDATE 	orbx_mod_ic_question
						SET		title = %s,
								modified = NOW(),
								editor = %s,
								category = %s,
								state = %s,
								permalink = %s,
								tags = %s,
								mail_answer = %s
								'.$live_sql.'
						WHERE	(id = %s)',
					$this->dbconn->quote($this->data['title']),
					$this->dbconn->quote($this->data['editor']),
					$this->dbconn->quote($this->data['category']),
					$this->dbconn->quote($this->data['state']),
					$this->dbconn->quote($this->data['permalink']),
					$this->dbconn->quote($tags),
					$this->dbconn->quote($this->data['mail_answer']),
					$this->dbconn->quote($this->data['id']));

		$this->dbconn->query($sql);
	}

	function edit_answer()
	{
		$this->data['editor'] = htmlspecialchars($this->data['editor']);
		// * this function update answer values
		$sql = sprintf('
						UPDATE 	orbx_mod_ic_answer
						SET		content = %s,
								modified = NOW(),
								editor = %s,
								state = %s
						WHERE	(question = %s)',
					$this->dbconn->quote($this->data['content']),
					$this->dbconn->quote($this->data['editor']),
					$this->dbconn->quote($this->data['state']),
					$this->dbconn->quote($this->data['id']));

		$this->dbconn->query($sql);
	}

	function get_category_questions($category_id, $offset, $rowsPerPage, $state = '')
	{
		$state_def = ($state == '') ? '' : ' AND (state = ' . $this->dbconn->quote($state) . ') AND (mail_answer = 0)';
		$offset = (int) $offset;
		$rowsPerPage = (int) $rowsPerPage;

		// * this function retrieves complete list of questions from
		// requested category
		$sql = sprintf('SELECT		*, UNIX_TIMESTAMP(live) AS live,
									UNIX_TIMESTAMP(created) AS created
						FROM		orbx_mod_ic_question
						WHERE		(category = %s) AND 
									(lang = %s)
						' . $state_def . '
						GROUP BY 	id
						ORDER BY	created DESC
						LIMIT 		'.$offset.', '.$rowsPerPage.'',
						$this->dbconn->quote($category_id),
						$this->dbconn->quote($this->lang));

		return $this->dbconn->query($sql);
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $flag
	 * @param unknown_type $offset
	 * @param unknown_type $rowsPerPage
	 * @return unknown
	 */
	function get_flaged_questions($flag='', $offset, $rowsPerPage)
	{
		$offset = (int) $offset;
		$rowsPerPage = (int) $rowsPerPage;

		$flag_cond = ($flag == '') ? 'AND flag_question IS NOT NULL' : sprintf('AND (flag_question = %s)', $this->dbconn->quote($flag));

		// * this function retrieves complete list of questions from
		// requested category
		$sql = sprintf('SELECT		*, UNIX_TIMESTAMP(live) AS live,
									UNIX_TIMESTAMP(created) AS created
						FROM		orbx_mod_ic_question
						WHERE		lang = %s
						'.$flag_cond.'
						ORDER BY	created DESC
						LIMIT 		%s, %s',
						$this->dbconn->quote($this->lang),
						$this->dbconn->quote($offset),
						$this->dbconn->quote($rowsPerPage));

		return $this->dbconn->query($sql);
	}


	function get_unsorted_questions($offset, $rowsPerPage, $state = '')
	{
		$state_def = ($state == '') ? '' : ' AND (state = ' . $this->dbconn->quote($state) . ') AND (mail_answer = 0)';
		$offset = intval($offset);
		$rowsPerPage = intval($rowsPerPage);

		$sql = sprintf('SELECT		*, UNIX_TIMESTAMP(created) AS created, id AS qid
						FROM		orbx_mod_ic_question
						WHERE		(category = %s) AND 
									(lang = %s)
						' . $state_def . '
						GROUP BY 	qid
						ORDER BY	created DESC
						LIMIT 		'.$offset.', '.$rowsPerPage.'',
						$this->dbconn->quote(0),
						$this->dbconn->quote($this->lang));

		return $this->dbconn->query($sql);
	}

	function get_all_active_uncategorized_questions()
	{
		$sql = sprintf('SELECT 	*
						FROM 	orbx_mod_ic_question
						WHERE  	(state = %s) AND (category = %s)',
						$this->dbconn->quote(1),
						$this->dbconn->quote(0));

		return $this->dbconn->query($sql);
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $qid
	 * @return unknown
	 */
	function has_answer($qid)
	{
		$sql = sprintf('SELECT 		id
						FROM 		orbx_mod_ic_answer
						WHERE 		(question = %s)',
						$this->dbconn->quote($qid));
		$resource = $this->dbconn->query($sql);

		if($this->dbconn->num_rows($resource) != 0){
			return false;
		}
		return true;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $offset
	 * @param unknown_type $rowsPerPage
	 * @return unknown
	 */
	function get_unanswered_question($offset, $rowsPerPage)
	{
		$resource = $this->get_all_active_uncategorized_questions();

		while($result = $this->dbconn->fetch_array($resource)){

			// check every question for an answer
			if($this->has_answer($result['id'])){
				$question .= sprintf('(id = %s)', $result['id']);
			}
		}

		$cond = str_replace(')(', ') OR (', $question);

		$cond = ($cond != '') ? '(' . $cond . ') AND' : '';

		$sql = sprintf('SELECT		*, UNIX_TIMESTAMP(created) AS created, id AS qid
						FROM		orbx_mod_ic_question
						WHERE		'.$cond.' lang = %s
						ORDER BY	created DESC
						LIMIT 		%s, %s',
						$this->dbconn->quote($this->lang),
						$this->dbconn->quote($offset),
						$this->dbconn->quote($rowsPerPage));

		return $this->dbconn->query($sql);

	}


	/**
	 * Enter description here...
	 *
	 * @param unknown_type $tag
	 * @param unknown_type $offset
	 * @param unknown_type $rowsPerPage
	 * @param unknown_type $state
	 * @return unknown
	 */
	function get_tag_questions($tag, $offset, $rowsPerPage, $state = '')
	{
		$state_def = ($state == '') ? '' : ' AND (state = ' . $this->dbconn->quote($state) . ') AND (mail_answer = 0)';

		// * this function retrieves complete list of questions from
		// requested category
		$sql = sprintf('SELECT		*, UNIX_TIMESTAMP(live) AS live
						FROM		orbx_mod_ic_question
						WHERE		(tags LIKE %s)
						' . $state_def . '
						GROUP BY 	id
						ORDER BY	live DESC
						LIMIT 		%s, %s',
						$this->dbconn->quote('%'.$tag.'%'), $this->dbconn->quote($offset), $this->dbconn->quote($rowsPerPage));

		return $this->dbconn->query($sql);
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $offset
	 * @param unknown_type $rowsPerPage
	 * @return unknown
	 */
	function search_questions($offset, $rowsPerPage)
	{
		// * this function preform basic string search
		// * build LIKE statement
		$like_stmt = $this->dbconn->quote('%' . $this->data['search_string'] . '%');

		// * build condition
		$cond_cat = ($this->data['search_cat'] != '') ? ' AND (q.category = ' . $this->dbconn->quote($this->data['search_cat']) . ')' : '' ;
		$cond_term = ($this->data['search_string'] != '') ? ' AND (q.title LIKE ' . $like_stmt . ')' : '';

		$condition = $cond_term . $cond_cat;

		$sql = sprintf('SELECT		q.id AS qid, q.title,
									q.editor, q.permalink,
									q.total_rating,	q.state AS state,
									UNIX_TIMESTAMP(q.created) AS created,
									c.id, c.title AS category,
									a.author
						FROM		orbx_mod_ic_question q

						LEFT JOIN	orbx_mod_ic_answer a ON
									a.question = q.id

						LEFT JOIN	orbx_mod_ic_category c	ON
									q.category = c.id

						WHERE		(q.state = 1) AND
									(c.state = 1) AND
									(q.mail_answer = 0) AND
									(q.lang = %s) AND
									(c.lang = %s)
									%s

						GROUP BY 	qid
						ORDER BY 	created DESC
						LIMIT 		%s, %s',
						$this->dbconn->quote($this->lang),
						$this->dbconn->quote($this->lang),
						$condition, $this->dbconn->quote($offset), $this->dbconn->quote($rowsPerPage));

		return $this->dbconn->query($sql);
	}

	/**
	 * return a list of latest approved questions
	 *
	 * @todo function title is obsolete
	 * @param int $offset
	 * @param int $rowsPerPage
	 * @param int $state
	 * @return string
	 */
	function get_latest_questions($offset, $rows_per_page, $state = '')
	{
		$state_def = ($state == '') ? '' : ' AND (q.state = ' . $this->dbconn->quote($state) . ') AND (c.state = 1) AND (q.mail_answer = 0)';

		if(!isset($_GET['rate'])) {
			$sort_top = ' GROUP BY qid ORDER BY q.live DESC ';
		}
		else {
			$sort_top = ($_GET['rate'] == 'desc') ? ' GROUP BY qid ORDER BY total_rating DESC ' : ' GROUP BY qid ORDER BY total_rating ASC ';
		}

		// * this function retrieves given number of questions chronologily ordered
		$sql = sprintf('SELECT		q.id AS qid, q.title,
									q.editor, q.permalink,
									q.total_rating, q.state AS state,
									UNIX_TIMESTAMP(q.created) AS created,
									UNIX_TIMESTAMP(q.live) as live,
									c.id, c.title AS category,
									a.author
						FROM		orbx_mod_ic_question q
						LEFT JOIN	orbx_mod_ic_answer a
						ON			a.question = q.id
						LEFT JOIN	orbx_mod_ic_category c
						ON			q.category = c.id
						WHERE		(q.lang = %s)
						AND			(c.lang = %s)
						'. $state_def . $sort_top . '
						 LIMIT 		%s, %s',
						$this->dbconn->quote($this->lang),
						$this->dbconn->quote($this->lang),
						$this->dbconn->quote($offset), $this->dbconn->quote($rows_per_page));

		return $this->dbconn->query($sql);
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $state
	 * @param unknown_type $active_only
	 * @return unknown
	 */
	function get_total_questions($state = '', $active_only = false)
	{
		$state_def = ($state == '') ? '' : ' AND (c.state = ' . $this->dbconn->quote($state) . ') AND (mail_answer = 0)';
		$state_def = ($active_only) ? $state_def . ' AND (q.state = 1)' : $state_def;

		// * build query
		$sql = sprintf('SELECT		*
						FROM		orbx_mod_ic_question q
						LEFT JOIN	orbx_mod_ic_category c
						ON 			q.category = c.id
						WHERE		(q.lang = %s)
						AND			(c.lang = %s)
						'.$state_def,
						$this->dbconn->quote($this->lang),
						$this->dbconn->quote($this->lang));

		return $this->dbconn->query($sql);
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $state
	 * @param unknown_type $active_only
	 * @return unknown
	 */
	function get_total_answers($state = '', $active_only = false)
	{
		$state_def = ($state == '') ? '' : ' AND c.state = ' . $this->dbconn->quote($state);
		$state_def = ($active_only) ? $state_def . ' AND (q.state = 1)' : $state_def;

		// * build query
		$sql = sprintf('SELECT		*
						FROM		orbx_mod_ic_answer a
						LEFT JOIN	orbx_mod_ic_question q
						ON			a.question = q.id
						LEFT JOIN	orbx_mod_ic_category c
						ON			q.category = c.id
						WHERE		(q.lang = %s)
						AND			(c.lang = %s)' . $state_def,
						$this->dbconn->quote($this->lang),
						$this->dbconn->quote($this->lang));

		return $this->dbconn->query($sql);

	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @return unknown
	 */
	function get_author_info($id)
	{
		// * get editor info
		$sql = sprintf('SELECT 		*
						FROM 		'.TABLE_EDITORS.'
						WHERE 		(id = %s)',
						$this->dbconn->quote($id));

		return $this->dbconn->query($sql);
	}

	/**
	 * sends email notification
	 *
	 * @todo test this function. $id might be wrong in link below
	 * @param string $id
	 * @return bool
	 */
	function send_notification($id)
	{

		// * get info on notification
		$quest = $this->get_question($id, '1');
		$question = $this->dbconn->fetch_assoc($quest);

		// * if it is said that notif is disabled, exit
		if($question['notify'] == 0 || $question['notify_sent'] == 1){
			return false;
		}

		$to = trim($question['mail']);
		$subject = sprintf(_L('ic-msg-mail-subject'), DOMAIN_NAME);

		$message .= _L('ic-msg-mail-body');
		$message .= '<a href="'. ORBX_SITE_URL .'/?'. $this->lang .'=mod.infocentar&amp;showPage=question&amp;id='. $id .'">
		'. ORBX_SITE_URL .'/?'. $this->lang .'=mod.infocentar&sp=q&id='. $id .'</a>';

		include_once DOC_ROOT . '/orbicon/3rdParty/phpmailer/class.phpmailer.php';

		$mail = new PHPMailer();

		if($_SESSION['site_settings']['smtp_server'] != '') {
			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->Host = $_SESSION['site_settings']['smtp_server']; // SMTP server
			$mail->Port = $_SESSION['site_settings']['smtp_port'];
		}

		$mail->CharSet = 'UTF-8';
		$mail->From = DOMAIN_EMAIL;
		$mail->FromName = utf8_html_entities(DOMAIN_OWNER, true);
		$mail->AddAddress($to);

		$mail->Subject = utf8_html_entities($subject, true);
		$mail->Body = $message;
		$mail->WordWrap = 50;
		$mail->IsHTML(true);

		if(!$mail->Send()) {
			$headers = 'From: ' . DOMAIN_NAME . ' <' . $_SESSION['main_site_email'] . ">\n";
			$headers .= 'Reply-To: ' . DOMAIN_NAME . ' <' . $_SESSION['main_site_email'] . ">\n";
			$headers .= "MIME-Version: 1.0\n";
			$headers .= 'Date: ' . date('r');

			return mail($to, $subject, $message, $headers);
		}

		$mail = null;

		return true;
	}

	/**
	 * click logger
	 *
	 * @param unknown_type $log
	 */
	function __log_click($log) {

		$sql = sprintf('INSERT INTO			orbx_mod_ic_stat
											(qid, clicker,
											clicker_name, vid) 
						VALUES 				(%s, %s, 
											%s, %s)',
						$this->dbconn->quote($log['question']), $this->dbconn->quote($log['clicker']),
						$this->dbconn->quote($log['clicker_name']), $this->dbconn->quote($log['vid']));

		$this->dbconn->query($sql);
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @return unknown
	 */
	function __count_clicks($id)
	{
		$sql = sprintf('SELECT
							count(id) AS click_num
						FROM
							orbx_mod_ic_stat
						WHERE
							(qid = %s)',
						$this->dbconn->quote($id));

		$resource = $this->dbconn->query($sql);

		return $this->dbconn->fetch_array($resource);
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @return unknown
	 */
	function __log_last_entry($id)
	{
		$sql = sprintf('SELECT		MAX(clicked_time) AS clicked
						FROM		orbx_mod_ic_stat
						WHERE		(qid = %s)
						GROUP BY	qid = %s',
						$this->dbconn->quote($id),
						$this->dbconn->quote($id));

		$resource = $this->dbconn->query($sql);

		return $this->dbconn->fetch_array($resource);
	}

	/**
	 * notify admins
	 *
	 * @param unknown_type $str
	 * @return unknown
	 */
	function _notify_admins($str)
	{
		$setting_res = $this->dbconn->query('SELECT	* FROM orbx_mod_ic_settings');
		$setting = $this->dbconn->fetch_array($setting_res);

		// * if it is said that notif is disabled, exit
		if($setting['question_notif'] == 0){
			return false;
		}

		$to = $setting['question_notif_mail'];

		$to_array = explode(',', $setting['question_notif_mail']);

		$subject = sprintf(_L('ic-admin-subject'), DOMAIN_NAME);

		$message .= '<p>'._L('name').': <em>'.$this->data['author'].'</em></p>';
		$message .= '<p>'._L('mail').': <em>'.$this->data['mail'].'</em></p><br />';

		$message .= _L('ic-admin-mail-msg') . '<br />';
		$message .= '<p style="color: #363636;">' . $str . '</p>';

		include_once DOC_ROOT . '/orbicon/3rdParty/phpmailer/class.phpmailer.php';

		$mail = new PHPMailer();

		if($_SESSION['site_settings']['smtp_server'] != '') {
			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->Host = $_SESSION['site_settings']['smtp_server']; // SMTP server
			$mail->Port = $_SESSION['site_settings']['smtp_port'];
		}

		$mail->CharSet = 'UTF-8';
		$mail->From = DOMAIN_EMAIL;
		$mail->FromName = utf8_html_entities(DOMAIN_OWNER, true);
		foreach($to_array as $email) {
			$email = trim($email);
			if(is_email($email)) {
				$mail->AddAddress($email);
			}
		}

		$mail->Subject = utf8_html_entities($subject, true);
		$mail->Body = $message;
		$mail->WordWrap = 50;
		$mail->IsHTML(true);

		if(!$mail->Send()) {
			foreach($to_array as $email) {
				// bug fix, if there is whitespace on the beginning
				// of string returns flase
				$email = trim($email);
				mail($email, $subject, $message, 'Content-Type: text/html; charset=UTF-8');
			}
		}

		$mail = null;

		return true;


	}

	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	function handle_tags()
	{
		// check if it needs to be created
		$del = sprintf('		DELETE FROM 	orbx_mod_ic_tag_handler 
								WHERE 			(qid = %s)',
						$this->dbconn->quote($this->data['id']));

		$this->dbconn->query($del);

		if($this->data['state'] == 1){

			foreach($this->data['tag'] as $key => $val){

				$tag_handler = sprintf('INSERT INTO		orbx_mod_ic_tag_handler
														(tagid, qid) 
										VALUES			(%s, %s)',
										$this->dbconn->quote($key),
										$this->dbconn->quote($this->data['id']));

				$this->dbconn->query($tag_handler);
			}
		}
		return true;
	}

	/**
	 * Enter description here...
	 *
	 */
	function __remove()
	{
		foreach ($this->data['q'] as $k => $v){

			if($v != 'submit_delete'){
				$sql_ans = sprintf('	DELETE 
										FROM 		orbx_mod_ic_answer 
										WHERE 		(question = %s)',
									$this->dbconn->quote($v));

				$sql_q = sprintf('		DELETE 		
										FROM 		orbx_mod_ic_question 
										WHERE 		(id = %s)',
									$this->dbconn->quote($v));

				$this->dbconn->query($sql_ans);
				$this->dbconn->query($sql_q);

			}
		}
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @return unknown
	 */
	function updateSendedToUser($id)
	{
		$sql = sprintf('	UPDATE 		orbx_mod_ic_question 
							SET 		notify_sent = "1" 
							WHERE 		(id = %s)',
						$this->dbconn->quote($id));

		return $this->dbconn->query($sql);
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @return unknown
	 */
	function get_total_question_answers($id = null)
	{
		if($id == null){
			trigger_error('Question ID# should be provided.', E_USER_ERROR);
			return false;
		}

		$sql = sprintf('	SELECT 		* 
							FROM 		orbx_mod_ic_answer 
							WHERE 		(question = %s)',
						$this->dbconn->quote($id));

		$resource = $this->dbconn->query($sql);

		return $this->dbconn->num_rows($resource);
	}

}


?>