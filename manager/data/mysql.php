<?php

class DB
{
	private $link;
	private $error_link;
	private $is_error;
	private $host;
	private $username;
	private $password;
	private $db_name;

	function __construct($host, $username, $password, $db_name)
	{
		// test for missing mysql extension
		if (!extension_loaded('mysql')) {
			$prefix = (PHP_SHLIB_SUFFIX == 'dll') ? 'php_' : '';
			dl($prefix . 'mysql.' . PHP_SHLIB_SUFFIX);
		}

		// fail here
		if (!function_exists('mysql_connect')) {
			trigger_error("MySQL functions missing, have you compiled PHP with the --with-mysql option?", E_USER_ERROR);
			exit();
		}

		$this->is_error = false;
		$this->host = $host;
		$this->username = $username;
		$this->password = $password;
		$this->db_name = $db_name;

		$this->link = $this->connect($this->host, $this->username, $this->password, $this->db_name);
	}

	function __destruct()
	{
		$this->disconnect($this->link);
		$this->disconnect($this->error_link);
	}

	function connect($host, $username, $password, $db_name, $new_link = false)
	{
		if(defined('MYSQL_CLIENT_COMPRESS')) {
			$link = mysql_connect($host, $username, $password, true, MYSQL_CLIENT_COMPRESS);
		}
		else {
			$link = mysql_connect($host, $username, $password, $new_link);
		}

		if(!is_resource($link) || !$link) {
			$this->disconnect($link);
		}

		if(!mysql_select_db($db_name, $link)) {
			$this->disconnect($link);
		}

		return $link;
	}

	// perform query
	function query($query)
	{
		var_dump($query);
		/*$bstarttime = explode(' ', microtime());
		$bstarttime = ($bstarttime[1] + $bstarttime[0]);*/

		// quick exit
		if(($query == '') || !is_resource($this->link)) {
			return false;
		}

		$r = mysql_query($query, $this->link);

		// finish the process time
		/*$bmtime = explode(' ', microtime());
		$btotaltime = round((($bmtime[0] + $bmtime[1]) - $bstarttime), 2);

		if($btotaltime >= 1.00) {
			$this->log_error($query, "Slow query: $btotaltime/s", ceil($btotaltime));
		}*/

		if(($r === false) && ($this->errno() > 0)) {
			$this->log_error($query, $this->error(), $this->errno());
			return $r;
		}

		return $r;
	}

	function free_result($result)
	{
		return mysql_free_result($result);
	}

	function num_rows($result)
	{
		return mysql_num_rows($result);
	}

	// return affected rows
	function affected_rows()
	{
		return mysql_affected_rows($this->link);
	}

	// close connection if open
	function disconnect($link)
	{
		if($link) {
			return mysql_close($link);
		}

		return false;
	}

	function insert_id()
	{
		return mysql_insert_id($this->link);
	}

	function error()
	{
		if(is_resource($this->link)) {
			return mysql_error($this->link);
		}

		return mysql_error();
	}

	function fetch_assoc($result)
	{
		return mysql_fetch_assoc($result);
	}

	function errno()
	{
		if($this->link) {
			return mysql_errno($this->link);
		}

		return mysql_errno();
	}

	// mysql escape for input $var with optional $link
	function quote($var)
	{
		// escape array elements
		if(is_array($var)) {
			return array_map(array($this, 'quote'), $var);
		}

		// strip magic quotes
		if(get_magic_quotes_gpc()) {
			$var = stripslashes($var);
		}

		if(!is_numeric($var)) {
			// try real escape first
			if(function_exists('mysql_real_escape_string') && (is_resource($this->link))) {
				return '\''.mysql_real_escape_string($var, $this->link).'\'';
			}
			// try escape next
			else if(function_exists('mysql_escape_string')) {
				return '\''.mysql_escape_string($var).'\'';
			}
			// try add slashes last
			else {
				return addslashes($var);
			}
		}
		// return numeric value
		return $var;
	}

	/**
	 * writes faulty log to database table
	 *
	 * @param string $query
	 */
	function log_error($query, $error, $errno)
	{
		// we have an error
		$this->is_error = true;

		$this->error_link = $this->connect($this->host, $this->username, $this->password, $this->db_name, true);

		sql_insert('	INSERT
						INTO 	sql_error_log
								(query, error,
								errno, time)
						VALUES 	(%s, %s,
								%s, UNIX_TIMESTAMP())', array($query, $error, $errno));
		$this->disconnect($this->error_link);
	}
}

	/**
	 * Execute SELECT query and return associative array
	 *
	 * @param string $query
	 * @param array $vars
	 * @return array
	 */
	function sql_res($query, $vars = null)
	{
		global $db;

		if(is_null($vars)) {
			return $db->query($query);
		}

		if(is_array($vars)) {
			$vars = array_map(array($db, 'quote'), $vars);
		}
		else if(isset($vars) && !is_array($vars)) {
			$vars = array($db->quote($vars));
		}

		return $db->query(vsprintf($query, $vars));
	}

	/**
	 * Execute SELECT query and return associative array
	 *
	 * @param string $query
	 * @param array $vars
	 * @return array
	 */
	function sql_assoc($query, $vars = null)
	{
		global $db;

		return $db->fetch_assoc(sql_res($query, $vars));
	}

	/**
	 * Execute INSERT query and return insert ID
	 *
	 * @param string $query
	 * @param array $vars
	 * @return array
	 */
	function sql_insert($query, $vars = null)
	{
		global $db;

		sql_res($query, $vars);
		return $db->insert_id();
	}

	/**
	 * Execute UPDATE query and return number of affected rows
	 *
	 * @param string $query
	 * @param array $vars
	 * @return int
	 */
	function sql_update($query, $vars = null)
	{
		global $db;

		sql_res($query, $vars);
		return $db->affected_rows();
	}

?>