<?php

class Currency
{


	var $data;
	var $dbconn;

	function Currency($data_array)
	{
		// * constructor
		$this->data = $data_array;

		// * constructor, redaclaring global db connector
		global $dbc;
		$this->dbconn = $dbc->_db;
	}

	function set_currency()
	{
		// * this function sets  new fond into db
		$sql = sprintf('INSERT
						INTO
							orbx_mod_invest_currency
						SET
							title = %s,
							state = %s',
						$this->dbconn->quote($this->data['title']),
						$this->dbconn->quote($this->data['state']));

		$result = $this->dbconn->query($sql);

		return $result;
	}

	function edit_currency()
	{
		// * this function sets  new fond into db
		$sql = sprintf('UPDATE
							orbx_mod_invest_currency
						SET
							title = %s,
							state = %s
						WHERE
							id = %s',
						$this->dbconn->quote($this->data['title']),
						$this->dbconn->quote($this->data['state']),
						$this->dbconn->quote($this->data['currency_id']));

		$result = $this->dbconn->query($sql);

		return $result;
	}

	function get_all_currencies($state = '')
	{
		$state_def = ($state == '') ? '' : ' WHERE state = ' . $state;

		// * this functon fetch a list of currencies
		$sql = 'SELECT * FROM orbx_mod_invest_currency' . $state_def;

		$result = $this->dbconn->query($sql);

		return $result;

	}

	function get_currency($id)
	{
		// * displays all entries from desired fond
		$sql = sprintf('SELECT
							*
						FROM
							orbx_mod_invest_currency
						WHERE
							id = %s',
						$this->dbconn->quote($id));

		$result = $this->dbconn->query($sql);

		return $result;
	}


}

?>