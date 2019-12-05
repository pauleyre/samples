<?php

require_once 'data/db.php';

/**
 * Enter description here...
 *
 */
class Question
{
	const LOCKED = 1;

	/**
	 * News item id
	 *
	 * @var int
	 */
	private $id;

	/**
	 * News properties (title, text, etc.) Names must equal column names in database
	 *
	 * @var array
	 */
	private $question_properties;

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 */
	function __construct($id = null)
	{
		if($id) {
			$this->setId($id);
		}
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $question_property
	 * @return unknown
	 */
	function __get($question_property)
	{
		return $this->question_properties[$question_property];
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $news_property
	 * @param unknown_type $value
	 */
	function __set($question_property, $value)
	{
		$this->question_properties[$question_property] = $value;
	}


	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @param unknown_type $status
	 * @return unknown
	 */
	function getQuestion($id = null, $status = -1)
	{
		if($id) {
			$this->setId($id);
		}

		$live_sql = '';

		if($status == 0) {
			$live_sql = ' AND (live = 0) ';
		}
		elseif ($status == 1) {
			$live_sql = ' AND (live = 1) ';
		}

		$q = sql_assoc('SELECT * FROM question WHERE (id=%s) '.$live_sql.' LIMIT 1', $this->id);

		$this->setId($q['id']);
		$this->category = $q['category'];
		$this->member_id = $q['member_id'];
		$this->title = $q['title'];
		$this->submited = $q['submited'];
		$this->live_time = $q['live_time'];
		$this->live = $q['live'];
		$this->permalink = $q['permalink'];
		$this->flags = (int) $q['flags'];
		$this->reads = $q['reads'];
		$this->total_as = $q['total_as'];
		$this->has_pic = $q['has_pic'];
		$this->ip = $q['ip'];
		$this->guestname = $q['guestname'];
		$this->has_video = $q['has_video'];
		$this->subject = $q['subject'];

		return $this->id;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @return unknown
	 */
	function setQuestion($id = null)
	{
		if($id) {
			$this->setId($id);
		}

		include_once 'logic/func.permalink.php';
		include_once 'logic/func.utf8.php';

		$this->title = trim($this->title);

		if(!$this->id) {
			return sql_insert('	INSERT INTO 	question
												(category, member_id,
												title, submited,
												live_time, live,
												permalink, ip,
												guestname, subject
												)
								VALUES			(%s, %s,
												%s, UNIX_TIMESTAMP(),
												UNIX_TIMESTAMP(), %s,
												%s, %s,
												%s, %s
												)', array(
										$this->category, $this->member_id,
										utf8html($this->title),
										$this->live,
										permalink($this->title), $_SERVER['REMOTE_ADDR'],
										$this->guestname, $this->subject
										)
							);
		}
		else {
			return sql_update('	UPDATE 	question
								SET 	category=%s, title=%s,
										live_time=UNIX_TIMESTAMP(), live=%s,
										permalink=%s, subject=%s
								WHERE 	(id=%s)', array(
										$this->category, utf8html($this->title),
										$this->live,
										permalink($this->title), $this->subject,
										$this->id)
							);
		}
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $category
	 * @param unknown_type $limit
	 * @param unknown_type $live
	 * @param unknown_type $min_max
	 * @param unknown_type $order_by
	 * @return unknown
	 */
	function getQuestions($category = '', $limit = '', $live = -1, $min_max = null, $order_by = 'live_time', $search_query = '', $total_as = null, $desc_asc = 'DESC', $add_extra=true, $related=false, $just_count=false)
	{
		global $db;

		$sql_case_str = '';

		switch ($order_by) {
			case 'live_time':
			case 'total_as':
			case 'reads':
				// OK
			break;
			case 'last_time_ans':
				$last_ans_time = '(SELECT submited FROM answer WHERE question_id = question.id ORDER BY submited DESC LIMIT 1) AS last_time_ans, ';
			break;
			// reset others to live time
			default: $order_by = 'live_time'; break;
		}

		switch($live) {
			case -1: $sql = ' WHERE ((live = 0) OR (live = 1)) '; break;
			case 0: $sql = ' WHERE (live = 0) '; break;
			case 1: $sql = ' WHERE (live = 1) '; break;
		}

		if($category) {
			$sql .= ' AND (category = ' . $db->quote($category). ')';
		}

		if($limit) {
			$sql_limit = ' LIMIT ' . $limit;
		}

		if($min_max) {
			$sql .= ' AND (live_time >= ' . $db->quote($min_max['min']). ') AND (live_time <= ' . $db->quote($min_max['max']). ') ';
		}

		if(!$add_extra) {
			$sql .= ' AND ((category != \'Dekada\') AND (category != \'Trash, Spam i Vic\')) ';
		}

		if($search_query) {

			// quoted
			if(preg_match_all('/(")(?:\\\1|.)*?\1/is', $search_query, $matches)) {
				$quoted_a = array();
				foreach ($matches[0] as $quoted) {
					$search_query = str_replace($quoted, '', $search_query);
					$quoted_a[] = $quoted;
				}
				$quoted_a = array_map(array($this, '_quoted_search_helper'), $quoted_a);
			}

			include_once 'logic/func.utf8.php';

			$sql_search = array();
			$sql_case = array();
			$specials = explode(' ', $search_query);
			$specials = array_map('trim', $specials);
			$specials = array_diff($specials, array('', NULL));

			if($quoted_a) {
				$specials = array_merge($specials, $quoted_a);
			}

			foreach ($specials as $special) {
				if((strlen($special) > 1)) {
					if (preg_match_all ('/(\\+)((?:[a-z][a-z0-9_:.]*))/is', strtolower($special), $matches)) {

						if($matches[2][0] == 'slika') {
							$sql .= ' AND (has_pic > 0) ';
						}
						elseif($matches[2][0] == 'video') {
							$sql .= ' AND (has_video > 0) ';
						}
						elseif ((substr($matches[2][0], 0, 3) == 'ip:')) {
							$ip = $db->quote(substr($special, 4));
							$sql .= ' AND ((ip = ' . $ip . ') OR (id IN ( SELECT question_id FROM answer WHERE ip = '.$ip.' ))) ';
						}
						elseif ((substr($matches[2][0], 0, 5) == 'tema:')) {
							$sql .= ' AND (UPPER(subject) = UPPER(' . $db->quote(substr($special, 6)) . ')) ';
						}
						else {
							$sql .= ' AND (UPPER(category) LIKE UPPER(' . $db->quote('%' . $matches[2][0] . '%'). '))';
						}
					}
					else if (preg_match_all ('/(\\-)((?:[a-z][a-z0-9_:.]*))/is', strtolower($special), $matches)) {

						if($matches[2][0] == 'slika') {
							$sql .= ' AND (has_pic = 0) ';
						}
						elseif($matches[2][0] == 'video') {
							$sql .= ' AND (has_video = 0) ';
						}
						elseif ((substr($matches[2][0], 0, 5) == 'tema:')) {
							$sql .= ' AND (UPPER(subject) != UPPER(' . $db->quote(substr($special, 6)) . ')) ';
						}
						else {
							$sql .= ' AND (UPPER(category) NOT LIKE UPPER(' . $db->quote('%' . $matches[2][0] . '%'). '))';
						}
					}
					else {
						$single = $db->quote('%' . utf8html($special) . '%');
						$sql_search[] = "((UPPER(title) LIKE UPPER($single)) OR (UPPER(subject) LIKE UPPER($single)) OR (id IN (SELECT question_id FROM answer WHERE UPPER(answer) LIKE UPPER($single))))";
						$sql_case[] = "(CASE WHEN UPPER(title) LIKE UPPER($single) THEN 3 ELSE 0 END)";
						$sql_case[] = "(CASE WHEN UPPER(subject) LIKE UPPER($single) THEN 2 ELSE 0 END)";
						$sql_case[] = "(CASE WHEN id IN (SELECT question_id FROM answer WHERE UPPER(answer) LIKE UPPER($single)) THEN 1 ELSE 0 END)";
					}
				}
			}
		}

		if($sql_search) {
			$sql .= 'AND (' . implode(' OR ', $sql_search) . ')';
			$sql_case_str = ',(' . implode(' + ', $sql_case) . ') AS relevance';
			$order_by = 'relevance';
			//var_dump('SELECT id, title, category, total_as, live_time, has_pic, permalink '.$sql_case_str.' FROM question ' . $sql . ' ORDER BY '.$order_by.' DESC ' . $sql_limit);
		}

		if($total_as) {
			$sql .= ' AND (total_as = 0)';
		}

		if($related) {
			$sql .= ' AND (id != ' . $db->quote($related). ') ';
		}

		if($order_by == 'last_time_ans') {
			$sql .= ' AND (total_as > 0) ';
		}

		if($just_count) {
			return sql_res('SELECT COUNT(id) AS total_qs FROM question ' . $sql);
		}

		return sql_res('SELECT '.$last_ans_time.' id, title, category, total_as, live, live_time, has_pic, has_video, permalink '.$sql_case_str.' FROM question ' . $sql . ' ORDER BY '.$order_by.' '.$desc_asc.' ' . $sql_limit);
	}

	function getId()
	{
		return $this->id;
	}

	function setId($id)
	{
		$this->id = intval($id);
	}

	function delete()
	{
		return sql_res('DELETE FROM question WHERE id = %s', $this->getId());
	}

	function update_reads()
	{
		return sql_update('UPDATE question SET question.reads = question.reads + 1 WHERE id = %s', $this->id);
	}

	function _quoted_search_helper($x)
	{
		return str_replace('"', '', $x);
	}
}

?>