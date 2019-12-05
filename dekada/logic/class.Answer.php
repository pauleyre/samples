<?php



/**
 * Enter description here...
 *
 */
class Answer
{
	const EXPERT = 1;
	/**
	 * News item id
	 *
	 * @var int
	 */
	private $id;

	/**
	 * Statistic counter
	 *
	 * @var int
	 */
	private static $invocation = 0;

	/**
	 * News properties (title, text, etc.) Names must equal column names in database
	 *
	 * @var array
	 */
	private $answer_properties;

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 */
	function __construct($id = null)
	{
		if($id) {
			$this->id = intval($id);
		}

		$this->invocation ++;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $question_property
	 * @return unknown
	 */
	function __get($answer_property)
	{
		return $this->answer_properties[$answer_property];
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $news_property
	 * @param unknown_type $value
	 */
	function __set($answer_property, $value)
	{
		$this->answer_properties[$answer_property] = $value;
	}


	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @param unknown_type $status
	 * @return unknown
	 */
	function getAnswer($id = null, $status = -1)
	{
		if($id) {
			$this->id = intval($id);
		}

		$live_sql = '';

		if($status == 0) {
			$live_sql = ' AND (live = 0) ';
		}
		elseif ($status == 1) {
			$live_sql = ' AND (live = 1) ';
		}

		$q = sql_assoc('SELECT * FROM answer WHERE (id=%s) '.$live_sql.' LIMIT 1', $this->id);

		$this->id = $q['id'];
		$this->question_id = $q['question_id'];
		$this->answer = $q['answer'];
		$this->member_id = $q['member_id'];
		$this->submited = $q['submited'];
		$this->flags = (int) $q['flags'];
		$this->guestname = $q['guestname'];
		$this->score = $q['score'];

		return $this->id;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @return unknown
	 */
	function setAnswer($id = null)
	{
		if($id) {
			$this->id = intval($id);
		}

		include_once 'logic/func.utf8.php';

		if(!$this->id) {

			if(!$this->duplicateAnswer($this->answer, $_SERVER['REMOTE_ADDR'])) {

				if($this->hasPic($this->answer)) {
					$this->update_has_pic($this->question_id);
				}

				if($this->hasVideo($this->answer)) {
					$this->update_has_video($this->question_id);
				}

				return sql_insert('	INSERT INTO 	answer
													(question_id, answer,
													member_id, submited,
													ip, guestname
													)
									VALUES			(%s, %s,
													%s, UNIX_TIMESTAMP(),
													%s, %s
													)', array(
											$this->question_id, utf8html($this->answer),
											$this->member_id,
											$_SERVER['REMOTE_ADDR'], $this->guestname
											)
								);
			}
		}
		else {
			return sql_update('	UPDATE 	answer
								SET 	answer=%s, flags=%s
								WHERE 	(id=%s)', array(
										utf8html($this->answer), $this->flags,
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
	function getAnswers($qid, $limit = '')
	{
		global $db;

		if($limit) {
			$sql_limit = ' LIMIT ' . $limit;
		}

		return sql_res('SELECT * FROM answer WHERE (question_id=%s) ORDER BY submited ASC ' . $sql_limit, $qid);
	}

	function hasPic($text = '')
	{
		if(!$text) {
			$text = $this->answer;
		}

		return (stripos($text, '<img') !== false);
	}

	function hasVideo($text = '')
	{
		if(!$text) {
			$text = $this->answer;
		}

		return (stripos($text, '<object') !== false);
	}

	function update_has_pic($qid, $inc_dec = '+')
	{
		return sql_update('	UPDATE 	question
							SET 	has_pic=has_pic'.$inc_dec.'1
							WHERE 	(id=%s)', $qid
						);
	}

	function update_has_video($qid, $inc_dec = '+')
	{
		return sql_update('	UPDATE 	question
							SET 	has_video=has_video'.$inc_dec.'1
							WHERE 	(id=%s)', $qid
						);
	}

	function update_total_as($qid)
	{
		return sql_update('	UPDATE 	question
							SET 	total_as=(SELECT COUNT(id) FROM answer WHERE question_id = %s)
							WHERE 	(id=%s)', array($qid, $qid)
						);
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
		return sql_res('DELETE FROM answer WHERE id = %s', $this->getId());
	}

	function duplicateAnswer($answer, $ip)
	{
		return sql_assoc('SELECT id FROM answer WHERE (answer=%s) AND (ip=%s) LIMIT 1', array($answer, $ip));
	}
}

?>