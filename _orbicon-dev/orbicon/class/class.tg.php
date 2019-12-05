<?php

/**
 * Flood guard, bad bot trap
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @version 1.29
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-08-01
 */

class OrbX_TornadoGuard
{
	/**
	 * The directory where log files will be saved. Must have permissions to write.
	 *
	 * @var string
	 */
	var $tg_logs_path;

	/**
	 * IP address of current connection. REMOTE_ADDR will be used by default.
	 *
	 * @var string
	 */
	var $tg_ip_addr;

	/**
	 * An associative array of [$interval=>$limit] format, where $limit is the
	 * number of possible requests during $interval seconds.
	 *
	 * @var array
	 */
	var $tg_rules;

	/**
	 * The name of the cron file. Must begin with dot. Default filename is '.time'.
	 *
	 * @var string
	 */
	var $tg_cron_file;

	/**
	 * Cron execution interval in seconds. 1800 secs (30 mins) by default.
	 *
	 * @var int
	 */
	var $tg_cron_interval;

	/**
	 * After how many of seconds to consider a file as old? By default the files
	 * will consider as old after 7200 secs (2 hours).
	 *
	 * @var int
	 */
	var $tg_logs_timeout;
	//
	/**
	 * bad bot blacklist log filename
	 *
	 * @var string
	 */
	var $tg_blacklist;

	/**
	 * PHP 4 compatibility
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @param string $logs_path
	 */
	function OrbX_TornadoGuard($logs_path)
	{
		return $this->__construct($logs_path);
	}

	/**
	 * starts tornado guard
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @param string $logs_path
	 * @return bool
	 */
	function __construct($logs_path)
	{
		global $orbx_log;
		// error
		if(!is_dir($logs_path)) {
			$orbx_log->dwrite('unable to find ' . $logs_path, __LINE__, __FUNCTION__);
			if(!mkdir($logs_path, 0777)) {
				$orbx_log->ewrite('unable to create ' . $logs_path, __LINE__, __FUNCTION__);
				return false;
			}
		}

		// windows path?
		if(strpos($logs_path, '\\') !== false) {
			$logs_path = str_replace('\\', '/', $logs_path);
		}
		// add trailing slash
		if(substr($logs_path, -1) != '/') {
			$logs_path .= '/';
		}

		$this->tg_logs_path = $logs_path;
		$this->tg_ip_addr = ORBX_CLIENT_IP;
		$this->tg_rules = array();
		$this->tg_cron_file = '.time';
		$this->tg_cron_interval = 1800;  // 30 minutes
		$this->tg_logs_timeout = 7200;  // 2 hours
		$this->tg_blacklist = $this->tg_get_blacklist_filename();
		return true;
	}

	function tg_get_blacklist_filename()
	{
		return DOC_ROOT . '/site/mercury/tg_bot.log';
	}

	/**
	 * Used to check flooding. Generally this function acts as private method
	 * and will be called internally by public methods. However, it can be called
	 * directly when storing logs in db.
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @param array $info ($interval=>$time, $interval=>$count array)
	 * @param array $rules (custom rules)
	 * @return bool
	 */
	function tg_rule_check(&$info, $rules = null)
	{
		global $orbx_log;
		$flood = false;
		$rules = ($rules === null) ? $this->tg_rules : $rules;

		foreach($rules as $interval => $limit) {
			if(!isset($info[$interval])) {
				$info[$interval]['time'] = time();
				$info[$interval]['count'] = 0;
			}

			$info[$interval]['count'] += 1;

			if((time() - $info[$interval]['time']) > $interval) {
				$info[$interval]['count'] = 1;
				$info[$interval]['time'] = time();
			}

			if($info[$interval]['count'] > $limit) {
				$info[$interval]['time'] = time();
				$flood = true;
			}

			$orbx_log->dwrite('#tg '.$info[$interval]['count'] . '  ' . $info[$interval]['time'], __LINE__, __FUNCTION__);
		}  // end foreach
		return $flood;
	}

	/**
	 * checks flooding. Must be called after setting up all necessary properties.
	 * returns true if flood detected, otherwise false
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @return bool
	 */
	function tg_get_flood()
	{
		// fast check for blacklisted bot
		if($this->tg_scan_blacklist()) {
			return true;
		}

		// check whitelist
		if($this->check_iplist($_SESSION['site_settings']['tg_whitelist'])) {
			return false;
		}

		// check blacklist
		if($this->check_iplist($_SESSION['site_settings']['tg_blacklist'])) {
			return true;
		}

		global $orbx_log;

		$this->tg_get_cron();

		$path = $this->tg_logs_path . $this->tg_ip_addr;

		$f = fopen($path, 'a+');
		/* Set a 64k buffer. */
		if(function_exists('stream_set_write_buffer')) {
			stream_set_write_buffer($f, 65535);
		}

		if(!$f) {
			$orbx_log->ewrite('log file access error. check permissions to write', __LINE__, __FUNCTION__);
		}

		flock($f, LOCK_EX);

		$info = fread($f, filesize($path) + 10);
		$info = unserialize($info);

		$result = $this->tg_rule_check($info);

		ftruncate($f, 0);
		fwrite($f, serialize($info));
		fflush($f);

		flock($f, LOCK_UN);

		fclose($f);

		return $result;
	}

  	/**
  	 * Checks the cron file and calls CronJob() to delete old entries from logs directory if
  	 * the time-out is reached.
  	 *
  	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
  	 * @return bool
  	 */
	function tg_get_cron()
	{
		global $orbx_log;

		if($this->tg_cron_file[0] !== '.') {
			$orbx_log->ewrite('the name of cron file must begin with a dot', __LINE__, __FUNCTION__);
			return false;
		}

		$path = $this->tg_logs_path.$this->tg_cron_file;

		$f = fopen($path, 'a+');
		/* Set a 64k buffer. */
		if(function_exists('stream_set_write_buffer')) {
			stream_set_write_buffer($f, 65535);
		}

		if(!$f) {
			$orbx_log->ewrite('cron file access error. check permissions to write', __LINE__, __FUNCTION__);
			return false;
		}

		flock($f, LOCK_EX);

		$last_cron = fread($f, filesize($path) + 10);
		$last_cron = abs(intval($last_cron));

		if((time() - $last_cron) > $this->tg_cron_interval) {
			$this->__tg_run_cron_job();
			$last_cron = time();
		}

		ftruncate($f, 0);
		fwrite($f, $last_cron);
		fflush($f);

		flock($f, LOCK_UN);

		fclose($f);
		return true;
	}

	/**
	 * deletes all old files from logs directory, except the files starting with dot.
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @return unknown
	 */
	function __tg_run_cron_job()
	{
		$path = $this->tg_logs_path;
		$d = dir($this->tg_logs_path);

		if($d === false) {
			global $orbx_log;
			$orbx_log->ewrite('could not obtain dir handle', __LINE__, __FUNCTION__);
			return false;
		}

		$fname = $d->read();

		clearstatcache();

		while($fname !== false) {
			// skip files starting with dot
			if($fname[0] === '.') {
				// next file
				$fname = $d->read();
				continue;
			}

			$full_path = $path.$fname;

			if((time() - filemtime($full_path)) > $this->tg_logs_timeout) {
				unlink($full_path);
			}
			// next file
			$fname = $d->read();
		}
		// close handle
		$d->close();
		unset($d);
		return true;
	}

	/**
	 * captures bot
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 */
	function tg_update_blacklist()
	{
		// if we're not already listed..
		if($this->tg_scan_blacklist() == false) {
			// update blacklist log
			chmod_unlock($this->tg_blacklist);
			$r = fopen($this->tg_blacklist, 'a+b');
			/* Set a 64k buffer. */
			if(function_exists('stream_set_write_buffer')) {
				stream_set_write_buffer($r, 65535);
			}
			fwrite($r, $this->tg_ip_addr.' - ['.time()."] \"{$_SERVER['REQUEST_METHOD']} {$_SERVER['REQUEST_URI']} {$_SERVER['SERVER_PROTOCOL']}\" {$_SERVER['HTTP_REFERER']} {$_SERVER['HTTP_USER_AGENT']}\n");
			fclose($r);
			chmod_lock($this->tg_blacklist);
		}
	}

	/**
	 * scans log file for bad bots
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @return bool
	 */
	function tg_scan_blacklist()
	{
		$is_bad_bot = 0;

		$this->__tg_make_log();

		// scan blacklist log
		$buffer = file_get_contents($this->tg_blacklist);
		if(strpos($buffer, $this->tg_ip_addr) !== false) {
			$is_bad_bot ++;
		}

		// * badbot, reject it
		if($is_bad_bot > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 */
	function __tg_make_log()
	{
		if(!is_file($this->tg_blacklist)) {
			create_empty_file($this->tg_blacklist);
		}
	}

	/**
	 * checks a list of IP addresses
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @param string $list
	 * @return bool
	 */
	function check_iplist($list)
	{
		if(!empty($list)) {

			$whitelist = explode(',', $list);

			foreach ($whitelist as $ip) {
				$ip = trim($ip);

				// we have network range
				if(strpos($ip, '-') !== false) {
					if(network_match($this->tg_ip_addr, $ip)) {
						return true;
					}
				}
				// a single ip address
				else {
					if($ip == $this->tg_ip_addr) {
						return true;
					}
				}
			}
		}

		return false;
	}
}

?>