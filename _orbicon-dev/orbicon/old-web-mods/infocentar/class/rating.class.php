<?php


class Rating
{

	var $data;
	var $lang;
	var $dbconn;

	function Rating($data_array)
	{
		// * do some setup here
		global $dbc;
		global $orbicon_x;

		$this->lang		= $orbicon_x->ptr;
		$this->data 	= $data_array;
		$this->dbconn	= $dbc->_db;
	}

	function vote()
	{
		// * this function writes new scores into db
		$sql = sprintf('INSERT INTO	orbx_mod_ic_ratings
							(aid, vote, ip_address)
						VALUES
							(%s, %s, %s)',
						$this->dbconn->quote($this->data['answer']),
						$this->dbconn->quote($this->data['voteValue']),
						$this->dbconn->quote(ORBX_CLIENT_IP));

		$this->dbconn->query($sql);

		$this->update_total_rating($this->data['qid'], $this->calculate_total_rating($this->data['qid']));
		
	}

	function get_answer_rating($qid)
	{
		$sql = sprintf('SELECT
							count(id) AS voters,
							(SUM(vote) / count(id)) AS score
						FROM
							orbx_mod_ic_ratings
						WHERE
							aid = %s',
						$this->dbconn->quote($qid));

		$resource = $this->dbconn->query($sql);

		$result = $this->dbconn->fetch_array($resource);

		return $result;
	}

	/**
	 * updates total rating for question. this greatly simplifies the
	 * process of sorting questions by total rating and improves database performance
	 *
	 * @param int $qid
	 * @param int $new_rating
	 */
	function update_total_rating($qid, $new_rating)
	{
		$sql = sprintf('UPDATE 	orbx_mod_ic_question
						SET		total_rating = %s
						WHERE	id = %s',
		$this->dbconn->quote($new_rating), $this->dbconn->quote(intval($qid)));

		return $this->dbconn->query($sql);
	}

	function calculate_total_rating($qid)
	{
		require_once DOC_ROOT.'/orbicon/modules/infocentar/class/question.class.php';

		global $dbc;
		$q = new Question();
		$r = $q->get_answer($qid, 'id');
		$answer = $dbc->_db->fetch_assoc($r);
		$n = 0;

		while($answer) {
			$rating_score = $this->get_answer_rating($answer['id']);
			//$score += ($rating_score != NULL) ? floor($rating_score['score']) : 0;
			$score += ($rating_score != NULL) ? $rating_score['score'] : 0;

			$answer = $dbc->_db->fetch_assoc($r);
			$n ++;
		}

		$score /= $n;
		//$score = floor($score);
		$score = $score;

		return $score;
	}
	
	
}


?>