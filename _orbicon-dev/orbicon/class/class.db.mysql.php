<?php
/**
 * MySQL database connector
 *
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconFE
 * @subpackage DBC
 * @version 1.5a
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2005-10-04
 */

require_once DOC_ROOT . '/orbicon/class/class.dbc.php';

/**
 * TTL for SQL cache files
 *
 */
define('ORBX_SQL_CACHE_TIMEOUT', 900);

class DBC_MySQL extends DBC_Core
{
	var $db_link;
	var $is_error;

	function __construct()
	{
		$this->is_error = false;
	}

	function dbc_mysql()
	{
		$this->__construct();
	}

	function connect($authorization = null)
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

		if(is_resource($this->db_link)) {
			$this->disconnect();
		}

		if(is_array($authorization)) {
			if(defined('MYSQL_CLIENT_COMPRESS')) {
				if(DB_PERMACONN == 1) {
					$this->db_link = mysql_pconnect(DB_HOST, $authorization[0], base64_decode($authorization[1]), MYSQL_CLIENT_COMPRESS);
				}
				else {
					$this->db_link = mysql_connect(DB_HOST, $authorization[0], base64_decode($authorization[1]), true, MYSQL_CLIENT_COMPRESS);
				}
			}
			else {
				if(DB_PERMACONN == 1) {
					$this->db_link = mysql_pconnect(DB_HOST, $authorization[0], base64_decode($authorization[1]));
				}
				else {
					$this->db_link = mysql_connect(DB_HOST, $authorization[0], base64_decode($authorization[1]));
				}
			}
		}
		else if(defined('DB_HOST')) {
			if(defined('MYSQL_CLIENT_COMPRESS')) {
				if(DB_PERMACONN == 1) {
					$this->db_link = mysql_pconnect(DB_HOST, DB_USER, base64_decode(DB_PASS), MYSQL_CLIENT_COMPRESS);
				}
				else {
					$this->db_link = mysql_connect(DB_HOST, DB_USER, base64_decode(DB_PASS), true, MYSQL_CLIENT_COMPRESS);
				}
			}
			else {
				if(DB_PERMACONN == 1) {
					$this->db_link = mysql_pconnect(DB_HOST, DB_USER, base64_decode(DB_PASS));
				}
				else {
					$this->db_link = mysql_connect(DB_HOST, DB_USER, base64_decode(DB_PASS));
				}
			}
		}

		if(!is_resource($this->db_link) || !$this->db_link) {
			$this->disconnect();
		}

		if(is_array($authorization)) {
			if(!mysql_select_db($authorization[2], $this->db_link)) {
				$this->disconnect();
			}
		}
		else {
			if(defined('DB_NAME')) {
				if(!mysql_select_db(DB_NAME, $this->db_link)) {
					$this->disconnect();
				}
			}
		}
	}

	// perform query
	function query($query)
	{
		$bstarttime = explode(' ', microtime());
		$bstarttime = ($bstarttime[1] + $bstarttime[0]);

		$query = trim($query);
		// quick exit
		if(($query == '') || !is_resource($this->db_link)) {
			return false;
		}

		$r = mysql_query($query, $this->db_link);
		if(($r === false) && ($this->errno() > 0)) {
			$this->_log_error_query($query, $this->error(), $this->errno());
			return $r;
		}

		// finish the process time
		$bmtime = explode(' ', microtime());
		$btotaltime = rounddown((($bmtime[0] + $bmtime[1]) - $bstarttime), 2);

		if(($btotaltime >= 1.00) && $_SESSION['site_settings']['log_slow_sql']) {
			$this->_log_error_query($query, "Slow query: $btotaltime/s", ceil($btotaltime));
		}

		return $r;
	}

	function fetch_array($result, $result_type = MYSQL_BOTH)
	{
		if(is_resource($result)) {
			$array = mysql_fetch_array($result, $result_type);

			if($array) {
				return $array;
			}
			else if($this->num_rows($result) > 0) {
				mysql_data_seek($result, 0);
				return false;
			}
			else {
				return false;
			}
		}
		return false;
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
		return mysql_affected_rows($this->db_link);
	}

	// close connection if open
	function disconnect()
	{
		if(is_resource($this->db_link)) {
			mysql_close($this->db_link);
		}
		unset($this->db_link);
	}

	function insert_id()
	{
		return mysql_insert_id($this->db_link);
	}

	function error()
	{
		if(is_resource($this->db_link)) {
			return mysql_error($this->db_link);
		}

		return mysql_error();
	}

	function fetch_assoc($result)
	{
		return mysql_fetch_assoc($result);
	}

	function errno()
	{
		if($this->db_link) {
			return mysql_errno($this->db_link);
		}

		return mysql_errno();
	}

	function set_link($link_identifier)
	{
		$this->db_link = $link_identifier;
	}

	function get_link()
	{
		return $this->db_link;
	}

	// mysql escape for input $var with optional $link
	function quote($var, $link = null)
	{
		// use default link
		$link = ($link === null) ? $this->db_link : $link;
		// reset if not a resource
		$link = (is_resource($link)) ? $link : $this->db_link;

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
			if(function_exists('mysql_real_escape_string') && (is_resource($link))) {
				return '\''.mysql_real_escape_string($var, $link).'\'';
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
	function _log_error_query($query, $error, $errno)
	{
		// we have an error
		$this->is_error = true;

		$new_link = (version_compare(PHP_VERSION, '4.2.0', '>=')) ? true : false;
		if(DB_PERMACONN == 1) {
			$link = mysql_pconnect(DB_HOST, DB_USER, base64_decode(DB_PASS));
		}
		else {
			$link = mysql_connect(DB_HOST, DB_USER, base64_decode(DB_PASS), $new_link);
		}

		mysql_select_db(DB_NAME, $link);
		mysql_query(sprintf('	INSERT
								INTO 	orbx_error_sql
										(query, error,
										errno, time)
								VALUES 	(%s, %s,
										%s, %s)',
								$this->quote($query, $link), $this->quote($error),
								$this->quote($errno), time()),
							$link);
		mysql_close($link);
	}

	/**
	 * Fetch object
	 *
	 * @param resource $result
	 * @return object
	 */
	function fetch_object($result)
	{
		if(is_resource($this->db_link)) {
			return mysql_fetch_object($result);
		}
		return false;
	}

	/**
	 * Alias for current_timestamp
	 *
	 * @param int $timestamp
	 * @return int
	 */
	function now($timestamp = -1)
	{
		if(!is_int($timestamp)) {
			trigger_error('now() expects parameter 1 to be integer, '.gettype($timestamp).' given', E_USER_WARNING);
			return false;
		}

		// sanity check
		if($timestamp < -1) {
			trigger_error('now() expects parameter 1 to be greater than -1, ' . $timestamp . ' given', E_USER_WARNING);
			return false;
		}

		return $this->current_timestamp($timestamp);
	}

	/**
	 * Return SQL timestamp from UNIX timestamp. Defaults to current timestamp
	 *
	 * @param int $timestamp
	 * @return int
	 */
	function current_timestamp($timestamp = -1)
	{
		if(!is_int($timestamp)) {
			trigger_error('current_timestamp() expects parameter 1 to be integer, '.gettype($timestamp).' given', E_USER_WARNING);
			return false;
		}

		// sanity check
		if($timestamp < -1) {
			trigger_error('current_timestamp() expects parameter 1 to be greater than -1, ' . $timestamp . ' given', E_USER_WARNING);
			return false;
		}

		$timestamp = ($timestamp == -1) ? time() : $timestamp;

		return date('Y-m-d H:i:s');
	}

	/**
	 * check if table exists
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @param string $table
	 * @return bool
	 */
	function table_exists($table)
	{
		if($table == '') {
			trigger_error('table_exists() expects parameter 1 to be non-empty', E_USER_WARNING);
			return false;
		}

		$r = $this->query("SELECT 1 FROM `$table` LIMIT 1");
		if($r) {
			return true;
		}

		return false;
	}

	function get_version($link = null)
	{
		$link = ($link == null) ? $this->db_link : $link;

		if(!is_resource($link)) {
			trigger_error('get_version() expects parameter 1 to be resource, '.gettype($link).' given', E_USER_WARNING);
			return false;
		}

		$current_ver = (mysql_get_server_info($link) < mysql_get_client_info()) ? mysql_get_server_info($link) : mysql_get_client_info();
		return preg_replace('~\-.+?$~', '', $current_ver);
	}


	// --------------------------------------------------------------------------------------

	// mysql cache engine

	function _get_cache_filename($query)
	{
		global $orbicon_x;

		if(!is_dir(DOC_ROOT . '/site/mercury/sql_cache/'.date('Y'))) {
			mkdir(DOC_ROOT . '/site/mercury/sql_cache/'.date('Y'), 0777);
		}
		/*if(!is_dir(DOC_ROOT . '/site/mercury/sql_cache/'.date('Y/m'))) {
			mkdir(DOC_ROOT . '/site/mercury/sql_cache/'.date('Y/m'), 0777);
		}
		if(!is_dir(DOC_ROOT . '/site/mercury/sql_cache/'.date('Y/m/d'))) {
			mkdir(DOC_ROOT . '/site/mercury/sql_cache/'.date('Y/m/d'), 0777);
		}*/

		return DOC_ROOT . '/site/mercury/sql_cache/'.date('Y/').'sqlc~' . str_pad(sprintf('%u', adler32($query . $orbicon_x->ptr)), 10, '0', STR_PAD_LEFT);
	}

	function _get_is_not_select_query($query, $force_caching)
	{
		global $orbx_log;

		if($force_caching) {
			$orbx_log->dwrite('forced caching for SQL query', __LINE__, __FUNCTION__);
			return true;
		}

		$orbx_log->dwrite('checking SQL query' .
		'/select:' . (int) (strpos($query, 'SELECT ') === 0) .
		'/password:' . (int) (strpos(strtolower($query), 'password') === false) .
		'/attila:' . (int) (strpos($query, 'orbicon_column_orbicon attila') === false) .
		'/member:' . (int) (get_is_member() === false) .
		'/admin:' . (int) (get_is_admin() === false)
		, __LINE__, __FUNCTION__);

		return (
			(strpos($query, 'SELECT ') === 0) && 							// must be a SELECT query
			(strpos(strtolower($query), 'password') === false) &&			// no passwords
			(strpos($query, 'orbicon_column_orbicon attila') === false)	&&	// we won't cache atilla SQL, HTML cache will do that for us
			(get_is_member() === false)	&&									// members don't get cached files
			(get_is_admin() === false)										// admins don't get cached files
		);
	}

	/**
	 * cache SQL query
	 *
	 * @param mixed $value
	 * @param string $query
	 * @param bool $force_caching
	 * @return bool
	 */
	function put_cache($value, $query, $force_caching = false)
	{
		//if(((string) $value == '') || ((string) $query == '')) {
			return false;
		//}

		global $orbx_log;

		// caching won't occur if we have an error
		if($this->is_error) {
			$orbx_log->ewrite('SQL error detected during runtime. Skipping caching for SQL query', __LINE__, __FUNCTION__);
			return false;
		}

		clearstatcache();
		if(!is_file(ORBX_SYS_CONFIG)) {
			return false;
		}

		// cache this
		if($this->_get_is_not_select_query($query, $force_caching)) {
			$cachefilename = $this->_get_cache_filename($query);

			$orbx_log->dwrite('putting "' . $cachefilename . '" for SQL query', __LINE__, __FUNCTION__);

			if(!lock($cachefilename)) {
				return false;
			}

			$fp = fopen($cachefilename, 'wb');
			/* Set a 64k buffer. */
			if(function_exists('stream_set_write_buffer')) {
				stream_set_write_buffer($fp, 65535);
			}
			if(!fwrite($fp, serialize($value))) {
				$orbx_log->ewrite('failed to create cache file "' . $cachefilename . '" for SQL query', __LINE__, __FUNCTION__);
			}
			unset($value, $query);

			unlock($cachefilename);

			return fclose($fp);
		}
		return false;
	}

	/**
	 * return cached data or NULL
	 *
	 * @param string $query
	 * @param bool $force_caching
	 * @return mixed
	 */
	function get_cache($query, $force_caching = false)
	{
		//if((string) $query == '') {
			return null;
		//}

		if(_get_is_orbicon_uri()) {
			return null;
		}

		// lookup for cached query
		if($this->_get_is_not_select_query($query, $force_caching)) {

			$file = $this->_get_cache_filename($query);

			clearstatcache();
			if(is_file($file)) {
				// too old...
				if((time() - filemtime($file)) > ORBX_SQL_CACHE_TIMEOUT) {
					return null;
				}

				// too small
				if(filesize($file) < 1) {
					return null;
				}

				$buffer = file_get_contents($file);

				if(!$buffer) {
					return null;
				}

				global $orbx_log;
				$orbx_log->dwrite('fetching "' . $file . '" for SQL query ', __LINE__, __FUNCTION__);
				unset($file);

				// return cached data
				return unserialize($buffer);
			}
		}
		return null;
	}
}

	/**
	 * Execute query and return resource
	 *
	 * @param string $query
	 * @param array $vars
	 * @return array
	 */
	function sql_res($query, $vars = null)
	{
		global $dbc;

		if(is_null($vars)) {
			return $dbc->_db->query($query);
		}

		if(is_array($vars)) {
			$vars = array_map(array($dbc->_db, 'quote'), $vars);
		}
		else if(isset($vars) && !is_array($vars)) {
			$vars = array($dbc->_db->quote($vars));
		}

		return $dbc->_db->query(vsprintf($query, $vars));
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
		global $dbc;

		return $dbc->_db->fetch_assoc(sql_res($query, $vars));
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
		global $dbc;

		sql_res($query, $vars);
		return $dbc->_db->insert_id();
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
		global $dbc;

		sql_res($query, $vars);
		return $dbc->_db->affected_rows();
	}

?>