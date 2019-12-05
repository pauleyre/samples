<?php

/**
 * Enter description here...
 *
 */
class Fond
{
	/**
	 * fond data 
	 *
	 * @var array
	 */
	
	var $data;
	/**
	 * DB connection
	 *
	 * @var resource
	 */
	var $dbconn;

	function Fond($data_array)
	{
		// * constructor
		$this->data = $data_array;

		// * constructor, redaclaring global db connector
		global $dbc;
		$this->dbconn = $dbc->_db;
	}

	function set_fond()
	{
		// * MySQL 5 fix
		$this->data['frontpage'] = ($this->data['frontpage'] != '') ? $this->data['frontpage'] : 0;
		
		// * check for decimal sign
		$this->data['entry_fee'] = (strpos($this->data['entry_fee'], ',') === false) ? $this->data['entry_fee']: str_replace(',', '.', $this->data['entry_fee']);

		// * this function sets  new fond into db
		$sql = sprintf('INSERT INTO		orbx_mod_invest_fond
						SET				title = %s,
										currency = %s,
										min_entry = %s,
										entry_fee = %s,
										frontpage = %s,
										state = %s',
						$this->dbconn->quote($this->data['title']),
						$this->dbconn->quote($this->data['currency']),
						$this->dbconn->quote($this->data['min_entry']),
						$this->dbconn->quote($this->data['entry_fee']),
						$this->dbconn->quote($this->data['frontpage']),
						$this->dbconn->quote($this->data['state']));

		return $this->dbconn->query($sql);
	}

	function edit_fond()
	{
		// * MySQL 5 fix
		$this->data['frontpage'] = ($this->data['frontpage'] != '') ? $this->data['frontpage'] : 0;

		// * this function sets  new fond into db
		$sql = sprintf('UPDATE	orbx_mod_invest_fond
						SET		title = %s,
								currency = %s,
								min_entry = %s,
								entry_fee = %s,
								frontpage = %s,
								state = %s
						WHERE	(id = %s)',
						$this->dbconn->quote($this->data['title']),
						$this->dbconn->quote($this->data['currency']),
						$this->dbconn->quote($this->data['min_entry']),
						$this->dbconn->quote($this->data['entry_fee']),
						$this->dbconn->quote($this->data['frontpage']),
						$this->dbconn->quote($this->data['state']),
						$this->dbconn->quote($this->data['id']));

		$this->dbconn->query($sql);
	}

	function get_all_fonds($state = '')
	{
		$state_def = ($state == '') ? '' : ' WHERE (state = ' . $state . ')';

		// * displays all entries from desired fond
		$sql = 'SELECT * FROM orbx_mod_invest_fond ' . $state_def . ' ORDER BY sortnum';
		
		return $this->dbconn->query($sql);
	}

	function get_all_frontpage_fonds($state = '')
	{
		$state_def = ($state == '') ? '' : ' AND state = ' . $state;

		// * displays all entries from desired fond
		$sql = sprintf('SELECT 		* 
						FROM 		orbx_mod_invest_fond 
						WHERE 		(frontpage = 1) %s 
						ORDER BY 	sortnum',
						$state_def);
		return $this->dbconn->query($sql);
	}

	function get_fond($id)
	{
		// * displays all entries from desired fond
		$sql = sprintf('SELECT	*
						FROM	orbx_mod_invest_fond
						WHERE	(id = %s)',
						$this->dbconn->quote($id));

		return $this->dbconn->query($sql);
	}

	function __export_fond_data()
	{
		$get_array = $this->dbconn->query('SELECT * FROM orbx_mod_invest_fond');
		while($array = $this->dbconn->fetch_array($get_array)){

			// * build query strings
			$query.= 'INSERT INTO orbx_mod_invest_fond (id, title, currency, state)
						VALUES ('.$array['id'].', \''.$array['title'].'\', \''.$array['currency'].'\', '.$array['state'].');<br />';
		}

		return $query;
	}

	function update_sorting($arr)
	{
		foreach($arr as $k=>$v){
			if($v != ''){
				$sql = sprintf('UPDATE orbx_mod_invest_fond SET sortnum = %s WHERE id = %s; ',
								$this->dbconn->quote($k),
								$this->dbconn->quote($v));
				
				$this->dbconn->query($sql);
			}
		}
		return true;
	}

}


?>