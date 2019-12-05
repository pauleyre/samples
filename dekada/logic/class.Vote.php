<?php



/**
 * Enter description here...
 *
 */
class Vote
{
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
	private $vote_properties;

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
	function __get($vote_property)
	{
		return $this->vote_properties[$vote_property];
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $news_property
	 * @param unknown_type $value
	 */
	function __set($vote_property, $value)
	{
		$this->vote_properties[$vote_property] = $value;
	}


	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @param unknown_type $status
	 * @return unknown
	 */
	function getVote($id = null)
	{
		if($id) {
			$this->id = intval($id);
		}

		$q = sql_assoc('SELECT * FROM vote WHERE (id=%s) LIMIT 1', $this->id);

		$this->id = $q['id'];
		$this->answer_id = $q['answer_id'];
		$this->user_id = $q['user_id'];
		$this->vote = $q['vote'];
		$this->submited = $q['submited'];
		$this->ip = $q['ip'];
		$this->flags = (int) $q['flags'];

		return $this->id;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @return unknown
	 */
	function setVote($id = null)
	{
		if($id) {
			$this->id = intval($id);
		}

		include_once 'logic/func.utf8.php';

		if(!$this->id) {
		
				if($this->vote == 'OK') {
					$this->updateScore($this->answer_id, '+1');
				}
				else if($this->vote == 'NOK') {
					$this->updateScore($this->answer_id, '-1');
				}
		
				return sql_insert('	INSERT INTO 	vote
													(
													answer_id, user_id,
													vote, submited,
													ip, flags
													)
									VALUES			(%s, %s,
													%s, UNIX_TIMESTAMP(),
													%s, %s
													)', array(
											$this->answer_id, $this->user_id,
											$this->vote,
											$_SERVER['REMOTE_ADDR'], $this->flags
											)
								);
		}
		else {
			return sql_update('	UPDATE 	vote
								SET 	answer_id=%s, user_id=%s,
										vote=%s, flags=%s
								WHERE 	(id=%s)', array(
										$this->answer_id, $this->user_id,
										$this->vote, $this->flags,
										$this->id)
							);
		}
	}

	function updateScore($aid, $score)
	{
		return sql_update('UPDATE answer SET score = (score ' . $score . ') WHERE (id = %s)', array($aid));
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
		return sql_res('DELETE FROM vote WHERE id = %s', $this->getId());
	}

	function alreadyVoted($aid, $uid)
	{
		return sql_assoc('SELECT id FROM vote WHERE (answer_id=%s) AND (user_id=%s) LIMIT 1', array($aid, $uid));
	}

}

?>