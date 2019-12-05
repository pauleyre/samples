<?php


class Stock
{
	var $data;
	var $dbconn;

	function __construct($data_array)
	{
		// * constructor
		$this->data = $data_array;

		// * constructor, redaclaring global db connector
		global $dbc;
		$this->dbconn = $dbc->_db;
	}

	/**
	 * PHP 4
	 *
	 * @param array $data_array
	 * @return Stock
	 */
	function stock($data_array)
	{
		$this->__construct($data_array);
	}

	function get_stock($id)
	{
		$sql = sprintf('SELECT 	*
						FROM	orbx_mod_invest_stock
						WHERE	(id = %s)',
						$this->dbconn->quote($id));

		return $this->dbconn->query($sql);
	}

	function testFund($id)
	{
		$sql = sprintf('SELECT
							*
						FROM
							orbx_mod_invest_stock
						WHERE
							fond = %s
						AND
							DATE(date) = DATE(NOW())',
						$this->dbconn->quote($id));

		$resource = $this->dbconn->query($sql);
		return $this->dbconn->num_rows($resource);
	}

	function get_todays_stock_listing($id)
	{
		$sql = sprintf('SELECT
							*
						FROM
							orbx_mod_invest_stock
						WHERE
							(fond = %s)
						AND
							(DATE(date) = DATE(NOW()))',
						$this->dbconn->quote($id));

		$resource = $this->dbconn->query($sql);
		return $this->dbconn->fetch_array($resource);
	}

	/**
	 * Enter description here...
	 *
	 * @todo replace $sql_get_first_entry with something more predictable
	 * @return unknown
	 */
	function set_stock()
	{
		$this->data['date'] = split('/', $this->data['date1']);
		$this->data['date'] = $this->data['date'][2] .'-'. $this->data['date'][0] .'-'. $this->data['date'][1] . ' 00:00:01';


		$sql = sprintf('INSERT
						INTO
							orbx_mod_invest_stock
						SET
							stock_value = %s,
							date = %s,
							fond = %s',
						$this->dbconn->quote($this->data['stock_value']),
						$this->dbconn->quote($this->data['date']),
						$this->dbconn->quote($this->data['fond']));
		$this->dbconn->query($sql);
	}

	function edit_stock()
	{
		// * this function inserts a new stock value into db
		$sql = sprintf('UPDATE
							orbx_mod_invest_stock
						SET
							stock_value = %s
						WHERE
							id = %s',
						$this->dbconn->quote($this->data['stock_value']),
						$this->dbconn->quote($this->data['id'])
					);

		$this->dbconn->query($sql);
	}


	function get_all_entries($fond = '')
	{
		$fond = ($fond != '') ? ' WHERE s.fond = ' . $this->dbconn->quote($fond) : '' ;

		// * displays all entries from desired fond
		$sql = 'SELECT
					s.*, UNIX_TIMESTAMP(date) AS uDate, f.title, f.currency
				FROM
					orbx_mod_invest_stock s
				LEFT JOIN
					orbx_mod_invest_fond f
				ON
					s.fond = f.id
				'.$fond.'
				ORDER BY
					s.date DESC';

		return $this->dbconn->query($sql);
	}

	function __import($file)
	{
		set_time_limit(0);
		// Pavle Gardijan, 4.6.2007, security checks, moved errors to logger
		if(
		isset($file['import_file']['tmp_name']) &&
		// non-empty
		($file['import_file']['name'] != '') &&
		// really uploaded?
		(is_uploaded_file($file['import_file']['tmp_name'])) &&
		// same filesize
		(filesize($file['import_file']['tmp_name']) == $file['import_file']['size']) &&
		// no errors
		($file['import_file']['error'] == UPLOAD_ERR_OK)
		) {
			global $orbx_log;
			// * upload file
			$up_temp_dir = DOC_ROOT . '/site/mercury/';
			$uploadfile = $up_temp_dir . md5(time()) . 'tmp.csv';



			while(is_file($uploadfile)) {
				$uploadfile = $up_temp_dir . md5(time()) . 'tmp.csv';
			}

			// * let's make sure that folder is writable
			chmod($up_temp_dir, 0777);

			// * quick exit if upload failes
			if(!move_uploaded_file($file['import_file']['tmp_name'], $uploadfile)){
				$orbx_log->ewrite('There has been an error with uploading ' . $uploadfile . ' file', __LINE__, __FUNCTION__);
				return;
			}

			// * quick exit if cannot open a file
			if(!$handle_temp_file = fopen($uploadfile, 'rb')) {
				$orbx_log->ewrite('There has been an error with writing ' . $uploadfile . ' file', __LINE__, __FUNCTION__);
				return;
			}

			// * loop through file rows, format & write input into db
			$csv_data = fgetcsv($handle_temp_file, 10000, ';', "\n");
			while ($csv_data) {

				// * legend : 	$csv_data[0] = date (format),
				// -			$csv_data[1] = days_active,
				// - 			$csv_data[2] = stock_value,
				// - 			$this->data['import_fond'] = fond

				// * rebuild date
				$csv_data[0] = explode('.', $csv_data[0]);
				$csv_data[0] = $csv_data[0][2] .'-'. $csv_data[0][1] .'-'. $csv_data[0][0];

				// * rebuild number
				$csv_data[1] = str_replace(',', '.', $csv_data[1]);

				$sql = sprintf('INSERT INTO
									orbx_mod_invest_stock

									(date,
									stock_value,
									fond)
									VALUES (%s, %s, %s)',
								$this->dbconn->quote($csv_data[0]),
								$this->dbconn->quote($csv_data[1]),
								$this->dbconn->quote($this->data['import_fond']));

				// echo $sql;
				if(!$this->dbconn->query($sql)){
					$orbx_log->ewrite('SQL server throw me a message: <br /><strong>' . mysql_error() . '</strong><br />in query <br />' . $sql, __LINE__, __FUNCTION__);
					return;
				}

				$csv_data = fgetcsv($handle_temp_file, 10000, ';', "\n");
			}	// while end

			// * close handler
			return fclose($handle_temp_file);
		}
	}

	/**
	 * fetch filename of latest graph for $fond
	 *
	 * @param int $fond
	 * @deprecated using fusioncharts
	 * @return string
	 */
	function get_latest_graph($fond)
	{
		$r = $this->get_latest_fond_data($fond);
		$a = $this->dbconn->fetch_array($r);

		return $a['graph'];

	}

	/**
	 * get latest data for fond $fond
	 *
	 * @param int $fond
	 * @return resource
	 */
	function get_latest_fond_data($fond)
	{
		$sql = sprintf('SELECT
							*
						FROM
							orbx_mod_invest_stock
						WHERE
							(fond = %s)
						ORDER BY
							date DESC
						LIMIT 1',
						$this->dbconn->quote($fond));

		$result = $this->dbconn->query($sql);
		return $result;
	}

	function get_latest_info($fond)
	{
		$r = $this->get_latest_fond_data($fond);
		return $this->dbconn->fetch_array($r);
	}

	function get_start_value()
	{
		$sql = sprintf('SELECT
							*
						FROM
							orbx_mod_invest_stock
						WHERE
							(DATE(date) = %s)
						AND
							(fond = %s)',
						$this->dbconn->quote($this->data['from_date']),
						$this->dbconn->quote($this->data['fond']));

		$resource = $this->dbconn->query($sql);
		return $this->dbconn->fetch_assoc($resource);
	}

	function get_finish_value()
	{
		$sql = sprintf('SELECT		*
						FROM		orbx_mod_invest_stock
						WHERE		(DATE(date) = %s)
						AND			(fond = %s)',
						$this->dbconn->quote($this->data['till_date']),
						$this->dbconn->quote($this->data['fond']));

		$resource = $this->dbconn->query($sql);
		return $this->dbconn->fetch_array($resource);
	}

	function get_days_diff()
	{
		$sql = sprintf('SELECT DATEDIFF(DATE(%s), DATE(%s)) AS difer',
						$this->dbconn->quote($this->data['till_date']),
						$this->dbconn->quote($this->data['from_date']));

		$result = $this->dbconn->query($sql);
		$count = $this->dbconn->fetch_array($result);
		return $count['difer'];
	}

	/**
	 * return highest and lowest for fond $fond. if empty returns for all
	 *
	 * @return array
	 */
	function get_date_range($fond = null)
	{
		$filter = ($fond == null) ? '' : ' WHERE (fond = ' . $this->dbconn->quote($fond) . ')';
		$sql = $this->dbconn->query('SELECT MAX(DATE(date)) AS highest, MIN(DATE(date)) AS lowest FROM orbx_mod_invest_stock' . $filter);
		return $this->dbconn->fetch_assoc($sql);
	}

	/**
	 * return date range for use in YUI calendar extension
	 *
	 * @param int $month
	 * @param int $year
	 * @return string
	 * @author Pavle Gardijan
	 * @since 2007-06-14
	 */
	function get_daterange($month, $year, $fond)
	{
		$from = mktime(0, 0, 0, $month, 1, $year);
		// bug #162
		$to = mktime(0, 0, 0, ($month + 1), 1, $year);
		$from = date('Y-m-d', $from);
		$to = date('Y-m-d', $to);

		global $dbc;
		$q = sprintf('	SELECT 		UNIX_TIMESTAMP(date) AS unix_date
						FROM		orbx_mod_invest_stock
						WHERE		(date >= DATE(%s)) AND
									(date <= DATE(%s)) AND
									(fond = %s)
						GROUP BY	unix_date',
						$dbc->_db->quote($from), $dbc->_db->quote($to), $dbc->_db->quote($fond));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$dates[] = date('m/d/Y', $a['unix_date']);
			$a = $dbc->_db->fetch_assoc($r);
		}

		$dates = implode(',', $dates);
		return $dates;
	}

	function checkPostedDate(){
		$sql = sprintf('SELECT * FROM orbx_mod_invest_stock WHERE (date = %s)', $this->data['date']);
	}

	function remove_stock_values($arr)
	{
		foreach($arr as $k=>$v){
			$sql = sprintf('DELETE FROM orbx_mod_invest_stock WHERE (id = %s)', $v);
			$this->dbconn->query($sql);
		}
	}
}

?>