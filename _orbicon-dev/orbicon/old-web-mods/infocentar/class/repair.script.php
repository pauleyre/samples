<?php


class Repair
{
	var $dbconn;

	function Repair($action = 'CLEAR_TAGS')
	{
		global $dbc;

		$this->dbconn = $dbc->_db;

		switch($action)
		{
			default: $this->removeUnusedTags();
		}
	}

	function removeUnusedTags()
	{

		$q_res = $this->dbconn->query('SELECT qid FROM orbx_mod_ic_tag_handler');

		while($t_question = $this->dbconn->fetch_array($q_res)){

			$sql = sprintf('SELECT * FROM orbx_mod_ic_question WHERE id = %s',
							$this->dbconn->quote($t_question['qid']));

			$resource = $this->dbconn->query($sql);

			if($this->dbconn->num_rows($resource) == 0){
				$del = sprintf('DELETE FROM orbx_mod_ic_tag_handler WHERE qid = %s',
								$this->dbconn->quote($t_question['qid']));
				$this->dbconn->query($del);
				echo 'Deleted record #'.$t_question['qid'].' from tags DB!<br>';
			}

		}

	}
}


?>