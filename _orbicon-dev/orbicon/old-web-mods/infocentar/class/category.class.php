<?php


class Category
{

	var $data;
	var $lang;
	var $dbconn;

	function Category($data_array)
	{
		// * do some setup here
		global $dbc;
		global $orbicon_x;

		$this->lang		= $orbicon_x->ptr;
		$this->data 	= $data_array;
		$this->dbconn	= $dbc->_db;
		// convert entities
		$this->data['desc'] = utf8_html_entities(htmlspecialchars($this->data['desc']));
		$this->data['title'] = utf8_html_entities(htmlspecialchars($this->data['title']));
	}

	function set_category()
	{
		// * MySQL 5 correction
		$this->data['lottery'] = ($this->data['lottery'] == '') ? 0 : $this->data['lottery'];

		// * this function writes new question into db
		$sql = sprintf('	INSERT INTO	orbx_mod_ic_category
								(title, author, lang, state, description)
							VALUES
								(%s, %s, %s, %s, %s)',
					$this->dbconn->quote($this->data['title']),
					$this->dbconn->quote($this->data['author']),
					$this->dbconn->quote($this->lang),
					$this->dbconn->quote($this->data['state']),
					$this->dbconn->quote($this->data['desc']));

		$this->dbconn->query($sql);
	}

	function get_category($id)
	{
		// * this function retrieves category info
		$sql = sprintf('	SELECT		*
							FROM		orbx_mod_ic_category
							WHERE		(id = %s)',
					$this->dbconn->quote($id));

		$resource = $this->dbconn->query($sql);

		return $this->dbconn->fetch_array($resource);

	}

	function edit_category()
	{
		// * MySQL 5 correction
		$this->data['lottery'] = ($this->data['lottery'] == '') ? 0 : $this->data['lottery'];
		
		// * this function update qusetion values
		$sql = sprintf('
						UPDATE 	orbx_mod_ic_category
						SET		title = %s, 
								state = %s, 
								description = %s
						WHERE	(id = %s)',
					$this->dbconn->quote($this->data['title']),
					$this->dbconn->quote($this->data['state']),
					$this->dbconn->quote($this->data['desc']),
					$this->dbconn->quote($this->data['id']));

		$this->dbconn->query($sql);
	}

	function get_items_num($id, $state = '')
	{
		$state_def = ($state == '') ? '' : ' AND (state = ' . $state  . ') AND (mail_answer = 0)';
		// * this function retrieves number of items inside category
		$sql = sprintf('SELECT		count(id) AS total
						FROM		orbx_mod_ic_question
						WHERE		(category = %s AND lang = %s)' . $state_def,
						$this->dbconn->quote($id),
						$this->dbconn->quote($this->lang));

		$resource = $this->dbconn->query($sql);

		return $this->dbconn->fetch_assoc($resource);
	}

	function get_all_categories($state = '')
	{
		$state_def = ($state == '') ? '' : ' AND (state = ' . $state . ') ';

		// * retrieves complete list of active categories
		$sql = sprintf('SELECT		*
						FROM		orbx_mod_ic_category
						WHERE		(lang = %s)' . $state_def . '
						ORDER BY 	sortnum, title',
						$this->dbconn->quote($this->lang));

		return $this->dbconn->query($sql);
	}
	
	function remove_category($id)
	{
		// * first put all questions under this category into Recycle bin
		$recycle = sprintf('	UPDATE
									orbx_mod_ic_question
								SET
									category = 0
								WHERE
									category = %s',
								$this->dbconn->quote($id));

		$this->dbconn->query($recycle);

		// * delete category
		$sql = sprintf('DELETE FROM orbx_mod_ic_category WHERE id = %s',
						$this->dbconn->quote($id));

		return $this->dbconn->query($sql);
	}

	function updateSortNum($arr)
	{
		foreach($arr as $k=>$v){
			if($v != ''){
				$sql = sprintf('UPDATE orbx_mod_ic_category SET sortnum = %s WHERE id = %s; ',
								$this->dbconn->quote($k),
								$this->dbconn->quote($v));
				$this->dbconn->query($sql);
			}
		}
		return true;
	}
}

?>