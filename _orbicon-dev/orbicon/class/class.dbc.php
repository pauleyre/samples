<?php
/**
 * Database connector
 *
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconFE
 * @subpackage DBC
 * @version 1.0
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-07-01
 */

class DBC_Core
{
	var $db_link;
	var $is_error;
	var $errmsg = array('Cannot start from DBC_Core');

	function connect($authorization)
	{
		trigger_error($this->errmsg[0]);
	}

	function disconnect()
	{
		trigger_error($this->errmsg[0]);
	}

	function query($query)
	{
		trigger_error($this->errmsg[0]);
	}

	function fetch_array($result, $result_type)
	{
		trigger_error($this->errmsg[0]);
	}

	function free_result($result)
	{
		trigger_error($this->errmsg[0]);
	}

	function quote($var, $link)
	{
		trigger_error($this->errmsg[0]);
	}

	function put_cache($value, $query)
	{
		trigger_error($this->errmsg[0]);
	}

	function get_cache($query)
	{
		trigger_error($this->errmsg[0]);
	}

	function num_rows($result)
	{
		trigger_error($this->errmsg[0]);
	}

	function affected_rows()
	{
		trigger_error($this->errmsg[0]);
	}

	function insert_id()
	{
		trigger_error($this->errmsg[0]);
	}

	function error()
	{
		trigger_error($this->errmsg[0]);
	}

	function fetch_assoc()
	{
		trigger_error($this->errmsg[0]);
	}

	function errno()
	{
		trigger_error($this->errmsg[0]);
	}

	function set_link($link_identifier)
	{
		trigger_error($this->errmsg[0]);
	}

	function get_link()
	{
		trigger_error($this->errmsg[0]);
	}

	function table_exists($table)
	{
		trigger_error($this->errmsg[0]);
	}

	function _log_error_query($query, $error, $errno)
	{
		trigger_error($this->errmsg[0]);
	}

	function fetch_object()
	{
		trigger_error($this->errmsg[0]);
	}

	function now($timestamp = -1)
	{
		trigger_error($this->errmsg[0]);
	}

	function current_timestamp($timestamp = -1)
	{
		trigger_error($this->errmsg[0]);
	}

	function get_version($link = null)
	{
		trigger_error($this->errmsg[0]);
	}
}

class DBC
{
	var $errmsg = array('DBC_Core subclass-string or object required');
	var $_db;

	// PHP 4/5 compat
	function DBC()
	{
		$this->__construct();
	}

	function __construct()
	{
		if(!defined('DB_TYPE')) {
			// default
			define('DB_TYPE', 'MySQL');
		}

		$arg = DB_TYPE;

		if(!function_exists('is_a')) {
			include DOC_ROOT . '/orbicon/3rdParty/php-compat/is_a.php';
		}

		if(is_object($arg) && is_a($arg, 'DBC_Core')) {
			$this->_db = $arg;
		}
		else {
			if(!is_string($arg)) {
				trigger_error($this->errmsg[0]);
			}

			$arg = "DBC_$arg";

			if(!class_exists($arg) ) {
				trigger_error($this->errmsg[0]);
			}

			$this->_db = new $arg;

			// use is_subclass_of(object, string) for compat with older versions
			if(!is_subclass_of($this->_db, 'DBC_Core')) {
				trigger_error($this->errmsg[0]);
			}
		}
	}
}

?>