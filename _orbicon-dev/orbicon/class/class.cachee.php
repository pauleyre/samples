<?php
/**
 * Cache Engine class
 *
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconFE
 * @subpackage Cache_HTML
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-07-01
 */

define('ORBX_HTML_CACHE_TIMEOUT', 900);

class CacheEngine
{
	/**
	 * generate HTML cache filename for $request
	 *
	 * @param string $request
	 * @return string
	 */
	function _get_cache_filename($request)
	{
		global $orbicon_x, $orbx_log;
		$key = sprintf('%u', adler32(DOMAIN_NO_WWW . $_SERVER['REQUEST_URI'] . serialize(array_merge($_POST, $_GET)) . '-ORBICON-' . $orbicon_x->ptr . $request . intval(ORBX_GZIP)));
		$key = str_pad($key, 10, '0', STR_PAD_LEFT);
		//$orbx_log->dwrite('serialized:' . $key . ':' . serialize(array_merge($_POST, $_GET)) );

		return /*DOC_ROOT . '/site/mercury/orbxc~' .*/ $key;
	}

	function _get_is_nonsensitive_request($request, $force_caching)
	{
		global $orbx_log;

		if($force_caching) {
			$orbx_log->dwrite('forced caching for HTTP request ' . $request, __LINE__, __FUNCTION__);
			return true;
		}

		$s = 'checking HTTP request ' . $request .
		'::/member:' . (int) (get_is_member() === false) .
		'::/admin:' . (int) (get_is_admin() === false) .
		'/searchbot:' . (int) (get_is_search_engine_bot() === false) .
		'/phpsessid:' . (int) (isset($_GET['PHPSESSID']) === false) .
		'/w3c:' . (int) (get_is_w3c_validator() === false);

		$orbx_log->dwrite($s, __LINE__, __FUNCTION__);
		//echo $s;

		return (bool) (
			(get_is_member() === false) &&
			(get_is_admin() === false) &&
			(get_is_search_engine_bot() === false) &&
			(isset($_GET['PHPSESSID']) === false) &&
			(get_is_w3c_validator() === false)
		);
	}

	/**
	 * cache HTML page
	 *
	 * @param string $value
	 * @param string $request
	 * @param bool $force_caching
	 * @return bool
	 */
	function put_cache($value, $request, $force_caching = false)
	{
		if(($value == '') || ($request == '')) {
			return false;
		}

		global $orbx_log;

		// caching won't occur if we have an error
		if($dbc->_db->is_error) {
			$orbx_log->ewrite('SQL error detected in memory. skipping caching for HTTP request ' . $request, __LINE__, __FUNCTION__);
			return false;
		}

		//clearstatcache();
		if(!is_file(ORBX_SYS_CONFIG)) {
			$orbx_log->dwrite('system not installed. bailing out for HTTP request ' . $request, __LINE__, __FUNCTION__);
			return false;
		}

		$request = ($request == 'attila') ? $request . $_GET['q'] : $request;

		// cache this
		if($this->_get_is_nonsensitive_request($request, $force_caching) === true) {
			$cachefilename = $this->_get_cache_filename($request);
			$orbx_log->dwrite('putting "' . $cachefilename . '" for HTTP request ' . $request, __LINE__, __FUNCTION__);

			/*if(!lock($cachefilename)) {
				return false;
			}*/

			//$fp = fopen($cachefilename, 'wb');
			/* Set a 64k buffer. */
			/*if(function_exists('stream_set_write_buffer')) {
				stream_set_write_buffer($fp, 65535);
			}
			if(!fwrite($fp, $value)) {
				$orbx_log->ewrite('failed to create cache file "' . $cachefilename . '" for HTTP request ' . $request, __LINE__, __FUNCTION__);
			}*/
			// write down header information
			//else {

				if((intval($_SESSION['cache_status']) != 200)/* && lock("$cachefilename.h")*/) {

					$status_codes = array(

						200 => '200 OK',
						205 => '205 Reset Content',
						301 => '301 Moved Permanently',
						304 => '304 Not Modified',
						307 => '307 Temporary Redirect',
						400 => '400 Bad Request',
						401 => '401 Unauthorized',
						403 => '403 Forbidden',
						404 => '404 Not Found',
						405 => '405 Method Not Allowed',
						406 => '406 Not Acceptable',
						500 => '500 Internal Server Error',
						501 => '501 Not Implemented',
						503 => '503 Service Unavailable',
						// this one should handle errors
						0 => '200 OK'

					);

					// write down the header info
					$header = 'HTTP/1.1 ' . $status_codes[intval($_SESSION['cache_status'])];

					/*$fc = fopen("$cachefilename.h", 'wb');
					fwrite($fc, $header);
					fclose($fc);
					unlock("$cachefilename.h");*/
					unset(/*$header, */$status_codes);
				}

				global $dbc;

				$a_c = sql_assoc('	SELECT 		id, time
							FROM 		'. TABLE_HTML_CACHE. '
							WHERE 		(hash = %s)
							LIMIT 		1', $cachefilename);
				if($a_c['id']) {
					if(((time() - $a_c['time']) > ORBX_HTML_CACHE_TIMEOUT)) {
						sql_update('	UPDATE 	'.TABLE_HTML_CACHE.'
										SET 	html = COMPRESS(%s), header = %s,
												time = UNIX_TIMESTAMP(), rev = (rev + 1)
										WHERE 	(hash = %s)',
												array($value, $header, $cachefilename));
					}
				}
				else {
						sql_insert('	INSERT INTO 	'.TABLE_HTML_CACHE.'
													(hash, html,
													header, time,
													rev)
									VALUES 			(%s, COMPRESS(%s),
													%s, UNIX_TIMESTAMP(),
													1)',
					array($cachefilename, $value, $header));
				}

				// delete old caches
				if(date('G') == 4) {
					if(stripos(DOMAIN_NO_WWW, 'foto-nekretine') !== false) {
						sql_res('DELETE FROM ' . TABLE_STATISTICS );
					}
					sql_res('	DELETE FROM ' . TABLE_HTML_CACHE .'
								WHERE 			(rev = 1) AND
												((UNIX_TIMESTAMP() - time) > '.(ORBX_HTML_CACHE_TIMEOUT * 2).')');
				}

			//}
			unset($value, $request);

			//unlock($cachefilename);

			/*header('Last-Modified: '.gmdate('D, d M Y H:i:s T', filemtime($cachefilename)));
			header('Expires: '.gmdate('D, d M Y H:i:s T', filemtime($cachefilename) + ORBX_HTML_CACHE_TIMEOUT));*/

			//return fclose($fp);
			return true;
		}
		else {
			$orbx_log->dwrite('sensitive request. bailing out for HTTP request ' . $request, __LINE__, __FUNCTION__);
		}
		return false;
	}

	/**
	 * return cached data or null
	 *
	 * @param string $request
	 * @param bool $force_caching
	 * @return mixed
	 */
	function get_cache($request, $force_caching = false)
	{
		global $orbx_log, $dbc;
		if($request == '') {
			$orbx_log->dwrite('empty HTTP request. bailing out...', __LINE__, __FUNCTION__);
			return null;
		}

		$request = ($request == 'attila') ? $request . $_GET['q'] : $request;

		// lookup for cached query
		if($this->_get_is_nonsensitive_request($request, $force_caching) === true) {
			$file = $this->_get_cache_filename($request);
			//clearstatcache();

			/*header('Last-Modified: '.gmdate('D, d M Y H:i:s T', filemtime($file)));
			header('Expires: '.gmdate('D, d M Y H:i:s T', filemtime($file) + ORBX_HTML_CACHE_TIMEOUT));

			$hash = basename($file);
			if(function_exists('getallheaders')) {
				$headers = getallheaders();
			}
			else {
				$headers = emu_getallheaders();
			}
			if(isset($headers['If-None-Match']) && ($headers['If-None-Match'] == '"'.$hash.'"')) {
				header('HTTP/1.1 304 Not Modified');
				$_SESSION['cache_status'] = 304;
				$orbx_log->swrite('found a copy in browser cache. exiting...', __LINE__, __FUNCTION__);
				exit();
			}
			header("ETag: \"$hash\"");*/

			$a = sql_assoc('	SELECT 		UNCOMPRESS(html) as html, header
								FROM 		' . TABLE_HTML_CACHE . '
								WHERE 		((UNIX_TIMESTAMP() - time) <= ' . ORBX_HTML_CACHE_TIMEOUT . ') AND
											(hash = %s) AND
											(LENGTH(html) > 0)
								LIMIT 		1', $file);

			if($a['header']) {
				header($a['header'], true);
			}

			return $a['html'];

			/*if(is_file($file)) {
				// too old...
				if((time() - filemtime($file)) > ORBX_HTML_CACHE_TIMEOUT) {
					$orbx_log->dwrite($file . ' cache is too old', __LINE__, __FUNCTION__);
					return null;
				}

				// too small
				if(filesize($file) < 1) {
					$orbx_log->dwrite($file . ' cache file is empty', __LINE__, __FUNCTION__);
					return null;
				}

				// read contents
				$buffer = file_get_contents($file);
				if($buffer === false) {
					return null;
				}

				$orbx_log->dwrite('fetching "' . $file . '" for HTTP request ' . $request, __LINE__, __FUNCTION__);

				if(is_file("$file.h")) {
					header(file_get_contents("$file.h"), true);
				}

				unset($file);

				// return cached data
				return $buffer;
			}
			else {
				// this error message is misleading
				//$orbx_log->ewrite($file . ' is not a file', __LINE__, __FUNCTION__);
			}*/
		}
		else {
			$orbx_log->dwrite('sensitive request. bailing out for HTTP request ' . $request, __LINE__, __FUNCTION__);
		}
		return null;
	}

	function _cache_cleanup()
	{
		$size = 0;
		global $orbx_log;
		$orbx_log->dwrite('starting SQL cache files cleanup', __LINE__, __FUNCTION__);

		clearstatcache();
		// unlink MySQL cache files
		/*$to_unlink_mysql = glob(DOC_ROOT . '/site/mercury/sql_cache/{sqlc~}*', GLOB_BRACE);
		foreach($to_unlink_mysql as $filename){
			if((time() - filemtime($filename)) > ORBX_SQL_CACHE_TIMEOUT) {
				$orbx_log->dwrite('cleaning up file' . $filename, __LINE__, __FUNCTION__);
				$size += filesize($filename);
				if(!unlink($filename)) {
					$orbx_log->ewrite('unable to remove ' . $filename, __LINE__, __FUNCTION__);
				}
			}
		}
		unset($to_unlink_mysql);*/

		// unlink HTML cache files
		/*$orbx_log->dwrite('starting HTML cache files cleanup', __LINE__, __FUNCTION__);

		$to_unlink_html = glob(DOC_ROOT . '/site/mercury/{orbxc~}*', GLOB_BRACE);
		foreach($to_unlink_html as $filename) {
			if((time() - filemtime($filename)) > ORBX_HTML_CACHE_TIMEOUT) {
				$size += filesize($filename);
				if(!unlink($filename)) {
					$orbx_log->ewrite('unable to remove ' . $filename, __LINE__, __FUNCTION__);
				}
			}
		}
		unset($to_unlink_html);*/

		// unlink old sync folders
		$orbx_log->dwrite('starting syncm_* directories cleanup', __LINE__, __FUNCTION__);

		$to_unlink_syncm = glob(DOC_ROOT . '/site/mercury/{syncm_}*', GLOB_BRACE);
		foreach($to_unlink_syncm as $directory) {
			if(is_dir($directory)) {
				if(!rmdir($directory)) {
					$orbx_log->ewrite('unable to remove ' . $directory, __LINE__, __FUNCTION__);
				}
			}
			else if(is_file($directory)) {

				$orbx_log->dwrite('cleaning up file' . $directory, __LINE__, __FUNCTION__);
				$size += filesize($directory);

				unlink($directory);
			}
		}
		unset($to_unlink_syncm);

		// unlink old log files and backups
		$to_unlink_logs = glob(DOC_ROOT . '/site/mercury/logs/*{orbx}*{log}', GLOB_BRACE);
		$to_unlink_bck = glob(DOC_ROOT . '/site/mercury/bck/*{.bk}', GLOB_BRACE);
		$to_unlink_bck_gfx = glob(DOC_ROOT . '/site/gfx/bck/*{.bk}', GLOB_BRACE);
		$to_unlink_bck_venus = glob(DOC_ROOT . '/site/venus/bck/*{.bk}', GLOB_BRACE);
		$to_unlink_bck_venus_t = glob(DOC_ROOT . '/site/venus/thumbs/bck/*{.bk}', GLOB_BRACE);
		$to_unlink_logs = array_merge($to_unlink_logs, $to_unlink_bck);
		$to_unlink_logs = array_merge($to_unlink_logs, $to_unlink_bck_gfx);
		$to_unlink_logs = array_merge($to_unlink_logs, $to_unlink_bck_venus);
		$to_unlink_logs = array_merge($to_unlink_logs, $to_unlink_bck_venus_t);

		foreach($to_unlink_logs as $filename) {
			// delete those older than 8 days (691200 seconds)
			if((time() - filemtime($filename)) > 691200) {
				$orbx_log->dwrite('cleaning up file' . $filename, __LINE__, __FUNCTION__);
				$size += filesize($filename);
				if(!unlink($filename)) {
					$orbx_log->ewrite('unable to remove ' . $filename, __LINE__, __FUNCTION__);
				}
			}
		}
		unset($to_unlink_logs);

		return $size;
	}
}

?>