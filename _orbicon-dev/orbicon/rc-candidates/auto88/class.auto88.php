<?php

/**
 * Auto88 Update Manager. Performs file and SQL updates from remote server
 *
 * <code>
 *
 * ; version.orbicon.ini example
 *
 * [2.0.3]
 * dir = 2.0.3/ or 2.0.3
 * files_lst = base64_encode
 * (
 * class.orbicon.php:ADLER32:orbicon/class/class.orbicon.php\n
 * class.form.php:ADLER32:orbicon/class/class.form.php\n
 * index.php:ADLER32:\n
 * );
 * sql = first.sql:second.sql:third.sql
 * [2.0.1]
 * etc.
 * [2.0.0]
 * etc.
 *
 * </code>
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan, Laniste 10A, 10000 Zagreb, Croatia
 * @package Codex
 * @version 1.0
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-11-01
 */

	/*----- ATTENTION! -------------------------------------------------
	 | Include before wherever DOC_ROOT required.
	 |------------------------------------------------------------------*/
	if(!defined('DOC_ROOT')) {
		// we'll need this info
		$dir = dirname(dirname(__FILE__));

		$file = $dir . '/index.php';
		$license = $dir . '/license.php';
		$found = false;

		while(!$found) {

			$dir = dirname(dirname($file));

			$license = $dir . '/license.php';
			$file = $dir . '/index.php';

			if(is_file($file) && is_file($license)) {
				$found = true;
				break;
			}
		}

		$request_uri = $_SERVER['REQUEST_URI'];

		$left = str_replace($dir, '', dirname(__FILE__));
		$left = str_replace('\\', '/', $left);
		$left = str_replace($left, '', $request_uri);
		// get rid of query
		$left = explode('?', $left);
		// file or no file?
		$left = (strpos($left[0], '.php')) ? dirname($left[0]) : $left[0];
		// strip forward slash as well
		$left = (substr($left, -1, 1) == '/') ? substr($left, 0, -1) : $left;

		define('ORBX_URI_PATH', $left);
		define('DOC_ROOT', $dir);
		unset($left, $request_uri);
	}
	//----- ATTENTION! ENDS -------------------------------------------------

	// filesystem class
	require_once DOC_ROOT . '/orbicon/class/file/class.file.php';

	// start logger
	require_once DOC_ROOT . '/orbicon/class/class.logger.php';
	global $orbx_log;
	$orbx_log = new Logger();

	require_once DOC_ROOT . '/orbicon/class/inc.global.php';

	// ini file index names
	define('VERSION_KEY_FILE_LIST', 'file_lst');
	define('VERSION_KEY_SERVER_DIR', 'dir');
	define('VERSION_KEY_SQL', 'sql');

class Auto88
{
	/**
	 * current version
	 *
	 * @var string
	 */
	var $my_version;
	var $server_versions;
	var $server_version_dir;
	/**
	 * public update address
	 *
	 * @var string
	 */
	var $update_baseuri;
	/**
	 * name of file with version info
	 *
	 * @var string
	 */
	var $update_info_filename;
	/**
	 * filename with MD5 hash of version file
	 *
	 * @var string
	 */
	var $update_info_filename_md5;
	/**
	 * available retry attempts for each file
	 *
	 * @var array
	 */
	var $retry_attempts;
	/**
	 * Snoopy instance used for update
	 *
	 * @var object
	 */
	var $update_agent;
	var $rollback_dirname;
	var $rollback_dir;
	var $rollback_info;
	var $update_feedback;
	var $update_status;
	var $update_config;

	/**
	 * PHP 4 compatibility
	 *
	 */
	function Auto88($version)
	{
		$this->__construct($version);
	}

	/**
	 * initial startup and update initialization
	 *
	 * @return bool
	 */
	function __construct($version)
	{
		ignore_user_abort(true);
		set_time_limit(0);

		$this->my_version = $version;
		$this->update_status = 0;
		$this->update_feedback[] = date('r');
		$this->update_feedback[] = $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . ' ' . $_SERVER['SERVER_SOFTWARE'];
		$this->update_feedback[] = '<span class="blue">Starting...</span>';

		$this->update_baseuri = 'http://update.orbitum.net/orbicon/';
		$this->update_info_filename = 'version.orbicon.ini';
		$this->update_info_filename_md5 = $this->update_info_filename . '.md5';

		$this->update_feedback[] = 'Connecting to <strong>' . $this->update_baseuri . '</strong>...';

		// create agent. we'll probably need it
		include_once DOC_ROOT . '/orbicon/3rdParty/snoopy/Snoopy.class.php';
		$this->update_agent = new Snoopy();

		$server_ini = $this->_update_get_contents($this->update_baseuri . $this->update_info_filename);
		$server_ini_hash = $this->_update_get_contents($this->update_baseuri . $this->update_info_filename_md5);
		$server_ini_hash_db = $this->_update_get_contents($this->update_baseuri . '?md5');

		// if we don't have these hash matches something's seriously wrong here
		$my_hash = md5(adler32($server_ini));
		$server_hash = ($server_ini_hash === $server_ini_hash_db) ? $server_ini_hash_db : '';

		if($my_hash !== $server_hash) {
			$this->update_feedback[] = '<span class="red">Corrupted version information...</span>';
			ignore_user_abort(false);
			return false;
		}

		// create rollback dir
		$this->rollback_dirname = adler32(time().uniqid());
		$this->rollback_dir = DOC_ROOT . '/site/mercury/' . $this->rollback_dirname;

		while(is_dir($this->rollback_dir)) {
			$this->rollback_dirname = adler32(time().uniqid());
			$this->rollback_dir = DOC_ROOT . '/site/mercury/' . $this->rollback_dirname;
		}

		// create rollback directory
		mkdir($this->rollback_dir, 0777);
		chmod($this->rollback_dir, 0777);

		$cached_ini = 'remote.' . $this->update_info_filename;
		$r = fopen($this->rollback_dir . '/' . $cached_ini, 'wb');
		fwrite($r, $server_ini);
		fclose($r);

		// get versions
		$this->server_versions = parse_ini_file($this->rollback_dir . '/' . $cached_ini, true);
		krsort($this->server_versions);
		unset($server_ini);
		unlink($this->rollback_dir . '/' . $cached_ini);

		if(empty($this->server_versions)) {
			$this->update_feedback[] = '<span class="red">Failed getting version information from <strong>' . $this->update_baseuri . '</strong>...</span>';
			ignore_user_abort(false);
			return false;
		}

		foreach($this->server_versions as $key => $value) {

			if($this->get_update_available($key) === true) {
				$this->server_version_dir = (substr($value[VERSION_KEY_SERVER_DIR], -1, 1) == '/') ? $value[VERSION_KEY_SERVER_DIR] : $value[VERSION_KEY_SERVER_DIR].'/';
				$this->update_feedback[] = 'Updating files...';
				$files_update_task = $this->do_file_lst_update(base64_decode($value[VERSION_KEY_FILE_LIST]));

				if(!empty($value[VERSION_KEY_SQL])) {
					$this->update_feedback[] = 'Updating database...';
					$this->do_mysql_update($value[VERSION_KEY_SQL]);
				}
				else {
					$this->update_feedback[] = 'Skipping database update. No updates found...';
				}

				if(($files_update_task === true) && ($this->update_status > 0)) {
					$this->update_feedback[] = '<span class="blue">Congratulations! Update to version <strong>' . $key . '</strong> sucessfully finished...</span>';
				}

				break;
			}
		}

		ignore_user_abort(false);
		return true;
	}

	/**
	 * clean up rollback files if we successfully updated
	 *
	 * @return bool
	 */
	function __destruct()
	{
		if($this->update_status === 1) {
			$d = dir($this->rollback_dir);

			if(!$d) {
				return false;
			}

			$entry = $d->read();

			while($entry !== false) {
				$entry = basename($entry);
				if(strchr($entry, 'bk.') === 0) {
					unlink($this->rollback_dir . '/' . $entry);
				}
				$entry = $d->read();
			}

			$d->close();
			unset($d, $entry);
			rmdir($this->rollback_dir);
		}
		return true;
	}

	/**
	 * compare versions and return true if we have a newer ones
	 *
	 * @param string $server_version
	 * @return bool
	 */
	function get_update_available($server_version)
	{
		if(version_compare($this->my_version, $server_version, '<')) {
			$this->update_feedback[] = '<span class="green">Found newer version <strong>' . $server_version . '</strong>...</span>';
			return true;
		}
		$this->update_feedback[] = 'Skipping version <strong>' . $server_version . '</strong>...';
		return false;
	}

	/**
	 * do update
	 *
	 * @param array $files_to_update_lst
	 * @return bool
	 */
	function do_file_lst_update($files_to_update_lst)
	{
		//$files_to_update = $this->_update_get_contents($files_to_update_lst);
		$files_to_update = explode("\n", $files_to_update_lst);
		$files_to_update = array_map('trim', $files_to_update);
		$files_to_update = array_remove_empty($files_to_update);

		foreach($files_to_update as $value) {
			if(!empty($value)) {
				// parse file update info
				$value = explode(':', $value);
				(string) $source = $this->update_baseuri . $this->server_version_dir . $value[0];
				(string) $crc = $value[1];
				(string) $target = $value[2];
				unset($value);

				$this->update_feedback[] = 'Updating file <strong>' . $target . '</strong>...';

				// set retry times
				$this->_set_retry_attempts(base64_encode($source), 3);
				$file = $this->update_file($source, $crc, $target);
				// something went wrong, quit
				if(!$file) {
					return false;
				}
			} // if end
		} // foreach end
		$this->update_status = 1;
		return true;
	}

	/**
	 * update $target_filename from $source_filename
	 *
	 * @param string $source_filename
	 * @param string $source_crc
	 * @param string $target_filename
	 * @return bool
	 */
	function update_file($source_filename, $source_crc, $target_filename)
	{
		// get source contents
		$contents = $this->_update_get_contents($source_filename);
		$retry_file_id = base64_encode($source_filename);

		// we failed: retry or quit
		if($contents === false) {

			// we can retry
			if($this->_get_retry_attempts($retry_file_id) > 0) {
				// reduce retry attempts by one
				$this->_reduce_retry_attempts($retry_file_id);
				// try again
				$this->update_file($source_filename, $source_crc, $target_filename);
			}
			// we can't retry, cancel update
			else {
				$rollback = $this->update_rollback();
				if($rollback) {
					$this->update_feedback[] = '<span class="red">Update failed. Performed system rollback...</span>';
				}
				else {
					$this->update_feedback[] = '<span class="red">Failed while performing system rollback from directory <strong>' . $this->rollback_dirname . '</strong>. This is a critical error...</span>';
				}
				return false;
			}
		}

		// crc is ok, go for it
		if(adler32($contents) == $source_crc) {
			$this->do_update_file($contents, $target_filename);
		}
		// crc failed, try again
		else {
			$this->update_feedback[] = '<span class="red">CRC for file '.$source_filename.' failed...</span>';

			if($this->_get_retry_attempts($retry_file_id) > 0) {
				// reduce retry attempts by one
				$this->_reduce_retry_attempts($retry_file_id);
				// try again
				$this->update_file($source_filename, $source_crc, $target_filename);
			}
		}
		return true;
	}

	function do_update_file($contents, $filename)
	{
		$this->add_file_to_rollback($filename);

		chmod_unlock($filename);
		$r = fopen($filename, 'wb');
		fwrite($r, $contents);
		unset($contents);
		fclose($r);
		chmod_lock($filename);
	}

	function do_mysql_update($sql_filelist)
	{
		if((string) $sql_filelist === '') {
			return false;
		}

		require DOC_ROOT . '/site/mercury/orbicon.system.php';

		if(DB_PERMACONN) {
			$link = mysql_pconnect(DB_HOST, DB_USER, base64_decode(DB_PASS), MYSQL_CLIENT_COMPRESS);
		}
		else {
			$link = mysql_connect(DB_HOST, DB_USER, base64_decode(DB_PASS), false, MYSQL_CLIENT_COMPRESS);
		}

		if(!is_resource($link)) {
			$this->update_feedback[] = '<span class="red">Could not connect to MySQL host...</span>';
			return false;
		}

		if(!mysql_select_db(DB_NAME, $link)) {
			$this->update_feedback[] = '<span class="red">MySQL error ('.mysql_errno($link).'): '.mysql_error($link).'</span>';
			mysql_close($link);
			return false;
		}

		$sql_filelist = explode(':', $sql_filelist);
		$sql_filelist = array_map('trim', $sql_filelist);
		$sql_filelist = array_remove_empty($sql_filelist);

		foreach($sql_filelist as $value) {
			$value = $this->update_baseuri.$this->server_version_dir.$value;
			$this->_update_execute_mysql_dump($value, $link);
		}

		mysql_close($link);
		return true;
	}

	/**
	 * add $filename to rollback list
	 *
	 * @param string $filename
	 */
	function add_file_to_rollback($filename)
	{
		$backup_filename = $this->rollback_dir . '/bk.' . basename($filename);
		$filename = DOC_ROOT . '/' . $filename;

		if(copy($filename, $backup_filename)) {
			$this->rollback_info[] = array(
				'path' => $filename,
				'backup' => $backup_filename
			);
		}
	}

	/**
	 * perform update rollback
	 *
	 * @return bool
	 */
	function update_rollback()
	{
		foreach($this->rollback_info as $value) {
			copy($value['backup'], $value['path']);
			clearstatcache();
			if(!is_file($value['path'])) {
				return false;
			}
		} // foreach end
		unset($this->rollback_info);
		return true;
	}

	function _update_get_contents($filename)
	{
		$this->update_feedback[] = 'Fetching <strong>'.$filename.'</strong>...';

		$contents = file_get_contents($filename);

		// we failed, go for it Snoopy
		if(!$contents) {
			$this->update_agent->fetch($filename);
			$contents = $this->update_agent->results;
		}
		return $contents;
	}

	/**
	 * reduce retry attempts by one
	 *
	 * @param int $file_id
	 */
	function _reduce_retry_attempts($file_id)
	{
		$this->retry_attempts[$file_id] -= 1;
	}

	/**
	 * get retry attempts
	 *
	 * @param int $file_id
	 * @return int
	 */
	function _get_retry_attempts($file_id)
	{
		return (int) $this->retry_attempts[$file_id];
	}

	/**
	 * get retry attempts
	 *
	 * @param int $file_id
	 * @param int $attempts
	 */
	function _set_retry_attempts($file_id, $attempts)
	{
		$this->retry_attempts[$file_id] = $attempts;
	}

	/**
	 * simple SQL dump import. not suitable for general use
	 *
	 * @param string $filename
	 * @param resource $link
	 */
	function _update_execute_mysql_dump($filename, $link)
	{
		$sql_filename = basename($filename);
		$this->update_feedback[] = 'Writing '.$sql_filename.' to MySQL database...';

		$content = explode("\n", $this->_update_get_contents($filename));
		$buffer = '';

		foreach($content as $sql_line) {
			$tsl = trim($sql_line);
			if(($sql_line != '')
			&& ($tsl[0].$tsl[1] !== '--')		// comment
			&& ($tsl[0] !== '#'))				// comment
			{
				$buffer .= $sql_line;
				// default delimiter ; found
				if(preg_match('/;\s*$/', $sql_line)) {
					// execute query
					$buffer = trim($buffer);
					while(substr($buffer, -1, 1) == ';') {
						$buffer = (substr($buffer, -1, 1) == ';') ? substr($buffer, 0, -1) : $buffer;
					}

					$r = mysql_query($buffer, $link);
					if(!$r) {
						$this->update_feedback[] = '<span class="red">MySQL error (' . mysql_errno($link) . '): ' . mysql_error($link) . '</span>';
					}
					//reset query
					$buffer = null;
				}
			}	// if end
		}	// foreach end
		unset($content, $sql_line);
		$this->update_feedback[] = 'Finished writing <strong>' . $sql_filename . '</strong> to MySQL database...';
	}

	/**
	 * return formatted update feedback from log
	 *
	 * @return string
	 */
	function get_log()
	{
		return implode('<br />', $this->update_feedback);
	}
}

/**
 * Patch maker class
 *
 *
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconTOOLS
 * @version 1.0
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-11-05
 */

class Version_Ini
{
	var $versions;
	var $ini_filename;

	function version_ini($ini)
	{
		$this->__construct($ini);
	}

	function __construct($ini)
	{
		$this->ini_filename = $ini;
		$this->versions = parse_ini_file($this->ini_filename, true);
		//var_dump($this->versions);
	}

	function add_version($version, $dir)
	{
		$new_version[$version] = array(
			VERSION_KEY_SERVER_DIR => $dir
		);
		$this->versions = array_merge($new_version, $this->versions);

		unset($new_version);
	}

	function add_version_file($version, $source_filename, $target_filename)
	{
		if((string) $this->versions[$version][VERSION_KEY_SERVER_DIR] === '') {
			return false;
		}

		$new_file_entry['basename'] = trim(basename($source_filename));
		$new_file_entry['crc'] = adler32(file_get_contents($source_filename));
		$new_file_entry['target_filename'] = trim($target_filename);

		// decode current file list
		(string) $file_lst = $this->versions[$version][VERSION_KEY_FILE_LIST];
		$file_lst = ($file_lst === '') ? $file_lst : base64_decode($file_lst);
		// add new file
		$file_lst .= implode(':', $new_file_entry)."\n";
		// encode
		$file_lst = base64_encode($file_lst);
		$this->versions[$version][VERSION_KEY_FILE_LIST] = $file_lst;
		unset($file_lst, $new_file_entry);
		return true;
	}

	function remove_version_file($version, $source_filename)
	{
		// decode current file list
		(string) $file_lst = $this->versions[$version][VERSION_KEY_FILE_LIST];
		if($file_lst === '') {
			return false;
		}

		$file_lst = explode("\n", base64_decode($file_lst));

		foreach($file_lst as $key => $value) {
			// file found, remove it
			if(strchr($value, $source_filename . ':') === 0) {
				unset($file_lst[$key]);
			}
		}
		$file_lst = implode("\n", $file_lst);

		// encode
		$file_lst = base64_encode($file_lst);
		$this->versions[$version][VERSION_KEY_FILE_LIST] = $file_lst;
		unset($file_lst);
		return true;
	}

	function add_version_sql($version, $source_sql)
	{
		// dir is not set, exit
		if((string) $this->versions[$version][VERSION_KEY_SERVER_DIR] === '') {
			return false;
		}

		$this->versions[$version][VERSION_KEY_SQL] = $source_sql;
		return true;
	}

	function remove_version_sql($version)
	{
		// get current sql
		(string) $sql = $this->versions[$version][VERSION_KEY_SQL];
		if($sql === '') {
			return false;
		}

		$this->versions[$version][VERSION_KEY_SQL] = '';
		return true;
	}

	function parse_version_ini()
	{
		$ini = '; program versions'."\n";
		$this->versions = array_remove_empty($this->versions);
		krsort($this->versions);

		foreach($this->versions as $key => $value) {
			$ini .= '[' . $key . ']'. "\n";													// version
			$ini .= VERSION_KEY_SERVER_DIR . ' = "' . $value[VERSION_KEY_SERVER_DIR] . "\"\n";	// server version dir
			$ini .= VERSION_KEY_FILE_LIST . ' = "' . $value[VERSION_KEY_FILE_LIST] . "\"\n";		// files list
			$ini .= VERSION_KEY_SQL . ' = "' . $value[VERSION_KEY_SQL] . "\"\n\n";					// sql filename

			unset($this->versions[$key]);
		}
		unset($this->versions);

		$hash = md5(adler32($ini));

		// ini
		if(!is_file($this->ini_filename)) {
			create_empty_file($this->ini_filename);
		}

		chmod_unlock($this->ini_filename);
		$r = fopen($this->ini_filename, 'wb');
		/* Set a 64k buffer. */
		if(function_exists('stream_set_write_buffer')) {
			stream_set_write_buffer($r, 65535);
		}
		fwrite($r, $ini);
		unset($ini);
		fclose($r);
		chmod_lock($this->ini_filename);

		// hash
		$hash_filename = $this->ini_filename.'.md5';
		if(!is_file($hash_filename)) {
			create_empty_file($hash_filename);
		}

		chmod_unlock($hash_filename);
		$r = fopen($hash_filename, 'wb');
		/* Set a 64k buffer. */
		if(function_exists('stream_set_write_buffer')) {
			stream_set_write_buffer($r, 65535);
		}
		fwrite($r, $hash);
		unset($hash, $hash_filename);
		fclose($r);
		chmod_lock($hash_filename);
		return true;
	}
}

?>