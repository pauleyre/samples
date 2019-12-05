<?php

/**
 * System/error/debug logger
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007-2008, Pavle Gardijan
 * @package OrbiconFE
 * @version 1.30
 * @link http://
 * @license http://
 * @since 2007-01-19
 */

define('LOG_LEVEL_SYSTEM',	1);
define('LOG_LEVEL_ERROR',	2);
define('LOG_LEVEL_DEBUG',	4);

/**
 * ini path for logs
 *
 */
define('LOGS_INI_PATH', DOC_ROOT . '/site/mercury/logs.ini');
/**
 * max log size in bytes (default 16M = 16777216)
 *
 */
define('LOGS_MAX_FILESIZE', 16777216);

class Logger
{
	var $log_rotator;
	var $log_level;
	var $_logger_initialized;
	var $_sys_fh;
	var $_error_fh;
	var $_debug_fh;
	var $_log_prefix;
	var $_log_debug;
	var $_log_error;
	var $_log_system;
	var $_log_phperror;

	function __construct()
	{
		// the system is not installed yet
		if(!is_dir(DOC_ROOT . '/site')) {
			return false;
		}

		$this->_logger_initialized = 0;

		// set log level
		$this->log_level = 0;

		$this->_log_prefix = 'orbx.';
		$this->_log_debug = $this->_log_prefix . 'debug.log';
		$this->_log_error = $this->_log_prefix .  'error.log';
		$this->_log_system = $this->_log_prefix . 'system.log';
		$this->_log_phperror = $this->_log_prefix . 'php.log';

		$logs_ini_path = LOGS_INI_PATH;

		if(!is_file($logs_ini_path)) {
			$this->create_default_logs_ini($logs_ini_path);
		}

		$ini = parse_ini_file($logs_ini_path);
		$this->log_rotator = trim(strtolower($ini['log_rotator']));
		// check for invalid value
		$this->log_rotator = (($this->log_rotator != 'date') && ($this->log_rotator != 'size')) ? 'date' : $this->log_rotator;

		$this->log_level = ($ini['errors']) ? ($this->log_level | LOG_LEVEL_ERROR) : $this->log_level;
		$this->log_level = ($ini['system']) ? ($this->log_level | LOG_LEVEL_SYSTEM) : $this->log_level;
		$this->log_level = ($ini['debug']) ? ($this->log_level | LOG_LEVEL_DEBUG) : $this->log_level;
		$logs_dir = $ini['path'];
		$log_php = ($ini['php']) ? true : false;

		// merge logs
		if($ini['merge']) {
			$this->_log_debug = $this->_log_prefix . 'all.log';
			$this->_log_error = $this->_log_prefix .  'all.log';
			$this->_log_system = $this->_log_prefix . 'all.log';
		}

		// log rotator date setup
		if($this->log_rotator == 'date') {
			$date_prefix = date('Y-m-d.');
			if($ini['merge']) {
				$this->_log_debug = $date_prefix . $this->_log_prefix . 'all.log';
				$this->_log_error = $date_prefix . $this->_log_prefix .  'all.log';
				$this->_log_system = $date_prefix . $this->_log_prefix . 'all.log';
			}
			else {
				$this->_log_debug = $date_prefix . $this->_log_prefix . 'debug.log';
				$this->_log_error = $date_prefix . $this->_log_prefix .  'error.log';
				$this->_log_system = $date_prefix . $this->_log_prefix . 'system.log';
				$this->_log_phperror = $date_prefix . $this->_log_prefix . 'php.log';
			}
		}

		unset($ini);

		if(!is_dir($logs_dir)) {
			if(mkdir($logs_dir, 0777)) {
				if(!chmod($logs_dir, 0777)) {
					trigger_error('Unable to set read/write permissions [0777] on ' . $logs_dir . ', exiting logger', E_USER_WARNING);
					return false;
				}
			}
			else {
				// perhaps we have a wrong path set? delete logs ini
				unlink($logs_ini_path);
				trigger_error('Unable to create directory ' . $logs_dir . ', exiting logger', E_USER_WARNING);
				return false;
			}
		}

		// logging is disabled, exit
		if(empty($this->log_level)) {
			return false;
		}

		// fix path
		$logs_dir = (substr($logs_dir, -1, 1) == '/') ? $logs_dir : $logs_dir . '/';

		// open file handles
		if($this->log_level & LOG_LEVEL_DEBUG) {

			$this->archive_log($logs_dir . $this->_log_debug);

			$this->_debug_fh = fopen($logs_dir . $this->_log_debug , 'ab');
			if(!$this->_debug_fh) {
				trigger_error('Unable to open/create ' . $logs_dir . $this->_log_debug . ', exiting logger', E_USER_WARNING);
				return false;
			}
			else {
				if(substr(sprintf('%o', fileperms($logs_dir . $this->_log_debug)), -4) != '0666'){
					if(!chmod($logs_dir . $this->_log_debug, 0666)) {
						trigger_error('Unable to set read/write permissions [0666] on ' . $logs_dir . $this->_log_debug . ', exiting logger', E_USER_WARNING);
						return false;
					}
				}
				/* Set a 64k buffer. */
				if(function_exists('stream_set_write_buffer')) {
					stream_set_write_buffer($this->_debug_fh, 65535);
				}
			}
		}
		if($this->log_level & LOG_LEVEL_SYSTEM) {

			$this->archive_log($logs_dir . $this->_log_system);

			$this->_sys_fh = fopen($logs_dir . $this->_log_system, 'ab');
			if(!$this->_sys_fh) {
				trigger_error('Unable to open/create ' . $logs_dir . $this->_log_system . ', exiting logger', E_USER_WARNING);
				return false;
			}
			else {
				if(substr(sprintf('%o', fileperms($logs_dir . $this->_log_system)), -4) != '0666'){
					if(!chmod($logs_dir . $this->_log_system, 0666)) {
						trigger_error('Unable to set read/write permissions [0666] on ' . $logs_dir . $this->_log_system . ', exiting logger', E_USER_WARNING);
						return false;
					}
				}
				/* Set a 64k buffer. */
				if(function_exists('stream_set_write_buffer')) {
					stream_set_write_buffer($this->_sys_fh, 65535);
				}
			}
		}
		if($this->log_level & LOG_LEVEL_ERROR) {

			$this->archive_log($logs_dir . $this->_log_error);

			$this->_error_fh = fopen($logs_dir . $this->_log_error, 'ab');
			if(!$this->_error_fh) {
				trigger_error('Unable to open/create ' . $logs_dir . $this->_log_error . ', exiting logger', E_USER_WARNING);
				return false;
			}
			else {
				// throws a warning, probably because of wrong user
				// added by Alen Novakovic, 22.08.2007
				if(substr(sprintf('%o', fileperms($logs_dir . $this->_log_error)), -4) != '0666'){
					if(!chmod($logs_dir . $this->_log_error, 0666)) {
						trigger_error('Unable to set read/write permissions [0666] on ' . $logs_dir . $this->_log_error . ', exiting logger', E_USER_WARNING);
						return false;
					}
				}
				/* Set a 64k buffer. */
				if(function_exists('stream_set_write_buffer')) {
					stream_set_write_buffer($this->_error_fh, 65535);
				}
				ini_set('log_errors', '1');
				if($log_php) {

					$this->archive_log($logs_dir . $this->_log_phperror);

					// log everything except notices
					error_reporting(E_ALL ^ E_NOTICE);
					ini_set('error_log', $logs_dir . $this->_log_phperror);
				}
			}
		}
		$this->_logger_initialized = 1;
	}

	function Logger()
	{
		$this->__construct();
	}

	// write system messages
	function swrite($msg, $line = 'NULL', $function = 'NULL')
	{
		// logging is disabled, exit
		if(empty($this->log_level) || (($this->log_level & LOG_LEVEL_SYSTEM) == false) || ($this->_logger_initialized == 0)) {
			return false;
		}

		$this->_write($msg, $line, $function, $this->_sys_fh);
	}

	// write error messages
	function ewrite($msg, $line = 'NULL', $function = 'NULL')
	{
		// logging is disabled, exit
		if(empty($this->log_level) || (($this->log_level & LOG_LEVEL_ERROR) == false) || ($this->_logger_initialized == 0)) {
			return false;
		}

		$this->_write($msg, $line, $function, $this->_error_fh);
	}

	// write debug messages
	function dwrite($msg, $line = 'NULL', $function = 'NULL')
	{
		// logging is disabled, exit
		if(empty($this->log_level) || (($this->log_level & LOG_LEVEL_DEBUG) == false) || ($this->_logger_initialized == 0)) {
			return false;
		}

		$this->_write($msg, $line, $function, $this->_debug_fh);
	}

	function _write($msg, $line, $function, $fh)
	{
		$type = get_resource_type($fh);

		if(($type === 'file') || ($type === 'stream')) {
			$i = 0;
			while($i < 10) {
				if(fwrite($fh, $this->_parse_line($msg, $line, $function)) !== false) {
					break;
				}
			}
		}
	}

	function _parse_line($msg, $code_line, $function)
	{
		$line = array();
		$line['ident'] = (isset($_SESSION['user.a']['id'])) ? $_SESSION['user.a']['id'] : '-';
		$line['user'] = (isset($_SERVER['PHP_AUTH_USER'])) ? $_SERVER['PHP_AUTH_USER'] : '-';
		$line['remote'] = $_SERVER['REMOTE_ADDR'];

		// parse offset
		$timezone = (date('Z') / 3600);
		$timezone = ($timezone > 0) ? '+' . $timezone : $timezone;

		$line['date'] = date('d/M/Y:H:i:s T') . $timezone;
		$line['request'] = '"' . $function . ':' . $msg . ' on line ' . $code_line . '"';
		$line['status'] = '-';

		// these are not always available here
		if(function_exists('memory_get_usage') && function_exists('byte_size')) {
			$mem_usage = memory_get_usage();
			$line['bytes'] = byte_size($mem_usage) . '(' . $mem_usage . ')';
		}
		else {
			$line['bytes'] = '-';
		}

		$line = implode(' ', $line);
		return  $line . "\r\n";
	}

	function archive_log($log_filename)
	{
		if($this->log_rotator == 'size') {
			if(@filesize($log_filename) >= LOGS_MAX_FILESIZE) {
				$i = 1;
				$archived_name = $log_filename . '.' . date('Ymd');

				while(is_file($archived_name)) {
					$archived_name = $log_filename . '.' . date('Ymd') . '.' . $i;
					$i ++;
				}

				rename($log_filename, $archived_name);
			}
		}
	}

	function create_default_logs_ini($path)
	{
		$r = fopen($path, 'wb');

		if(!$r) {
			trigger_error('Cannot create ' . $path . ', exiting logger', E_USER_WARNING);
			return false;
		}

		// recreate default setting
		fwrite($r, "[logs]\r\n; enable / disable various logs\r\nerrors=0\r\nsystem=0\r\ndebug=0\r\nphp=0\r\n\r\n[options]\r\n; merge all logs into one log\r\nmerge=0\r\n\r\n; rotate logs by \"date\" or \"size\"\r\nlog_rotator=date\r\n\r\n; for security reasons you should store the logs outside of site's root directory\r\npath=\"".dirname($path) ."/logs\"\r\n");
		return fclose($r);
	}

	function quit()
	{
		ini_set('display_errors', '0');
		ini_set('display_startup_errors', '0');
		ini_set('log_errors', '0');
		error_reporting(0);

		// logging is disabled, exit
		if(empty($this->log_level) || ($this->_logger_initialized == 0)) {
			return false;
		}

		if($this->log_level & LOG_LEVEL_SYSTEM) {
			fclose($this->_sys_fh);
		}

		if($this->log_level & LOG_LEVEL_DEBUG) {
			fclose($this->_debug_fh);
		}

		if($this->log_level & LOG_LEVEL_ERROR) {
			fclose($this->_error_fh);
		}
		$this->_logger_initialized = -1;
	}

	function __destruct()
	{
		// only if we haven't exited yet
		if($this->_logger_initialized > -1) {
			$this->quit();
		}
	}
}

?>