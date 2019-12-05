<?php

class ICSettings
{
	var $dbconn;
	var $data;

	function ICSettings($data_param = NULL)
	{
		global $dbc;
		global $orbicon_x;

		$this->data = $data_param;
		$this->dbconn = $dbc->_db;
		$this->lang = $orbicon_x->ptr;

		if($this->data != NULL){
			$this->_update_settings();
		}
	}

	function _update_settings()
	{
		// this is MySQL5 fix ;(
		// Author Alen Novakovic, 13.07.2007.
		$this->data['mail_required'] = intval($this->data['mail_required']);
		$this->data['question_notif'] = intval($this->data['question_notif']);
		$this->data['author'] = intval($this->data['author']);
		$this->data['category'] = intval($this->data['category']);
		$this->data['date_show'] = intval($this->data['date_show']);
		$this->data['depart'] = intval($this->data['depart']);
		$this->data['intro'] = intval($this->data['intro']);
		$this->data['tag_cloud'] = intval($this->data['tag_cloud']);
		$this->data['append_polls'] = intval($this->data['append_polls']);

		$sql = sprintf('UPDATE
							orbx_mod_ic_settings
						SET
							tag_cloud = %s,
							intro = %s,
							answer_privileges = %s,
							intro_text = %s,
							alt_author = %s,
							admin_per_page = %s,
							public_per_page = %s,
							apply_author_info = %s,
							apply_title_info = %s,
							mail_required = %s,
							question_notif = %s,
							question_notif_mail = %s,
							author = %s,
							category = %s,
							date_show = %s,
							depart = %s,
							append_polls = %s
						LIMIT 1',
						$this->dbconn->quote($this->data['tag_cloud']),
						$this->dbconn->quote($this->data['intro']),
						$this->dbconn->quote($this->data['answer_privileges']),
						$this->dbconn->quote($this->data['content_text']),
						$this->dbconn->quote($this->data['alt_author']),
						$this->dbconn->quote($this->data['admin_per_page']),
						$this->dbconn->quote($this->data['public_per_page']),
						$this->dbconn->quote($this->data['apply_author_info']),
						$this->dbconn->quote($this->data['apply_title_info']),
						$this->dbconn->quote($this->data['mail_required']),
						$this->dbconn->quote($this->data['question_notif']),
						$this->dbconn->quote($this->data['question_notif_mail']),
						$this->dbconn->quote($this->data['author']),
						$this->dbconn->quote($this->data['category']),
						$this->dbconn->quote($this->data['date_show']),
						$this->dbconn->quote($this->data['depart']),
						$this->dbconn->quote($this->data['append_polls']));

		$this->dbconn->query($sql);

		return true;
	}

	function _get_settings()
	{
		$sql = 'SELECT * FROM orbx_mod_ic_settings LIMIT 1';

		$resource = $this->dbconn->query($sql);
		$result = $this->dbconn->fetch_array($resource);

		return $result;
	}
}

?>