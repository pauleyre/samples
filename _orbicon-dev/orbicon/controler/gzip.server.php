<?php

/**
 * Server for gzipped content
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-07-04
 * @todo speed optimizations
 */

	ignore_user_abort(true);

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

	new Gzip_Server();
	ignore_user_abort(false);

class Gzip_Server
{
	var $param;

	function gzip_server()
	{
		$this->__construct();
	}

	function __construct()
	{
		$this->param['file'] = DOC_ROOT . $_REQUEST['file'];
		$this->param['type'] = pathinfo($_REQUEST['file']);
		$this->param['type'] = $this->param['type']['extension'];
		$this->check_gzip_use();
		$this->send_headers();
		$this->check_cache();
	}

	function send_headers()
	{
		include_once DOC_ROOT . '/orbicon/class/file/inc.file.php';

		// js is ok
		if(($this->param['type'] == 'js') && is_file(DOC_ROOT . $_REQUEST['file'])) {
			if(!is_file($this->cache_file)) {
				if(!gzip(DOC_ROOT . $_REQUEST['file'])) {
					$this->use_gzip = false;
				}
			}
			header('Content-type: text/javascript; charset: UTF-8');
		}
		// css is ok
		else if(($this->param['type'] == 'css') && is_file(DOC_ROOT . $_REQUEST['file'])) {
			if(!is_file($this->cache_file)) {
				if(!gzip(DOC_ROOT . $_REQUEST['file'])) {
					$this->use_gzip = false;
				}
			}

			header('Content-type: text/css');
		}
		// refuse to serve any other file
		else {
			header('HTTP/1.1 404 Not Found', true);
			$_SESSION['cache_status'] = 404;
			session_write_close();
			exit();
		}
		// Handle proxies
		header('Vary: Accept-Encoding');
		// 14 days util client cache expires cached on disk
		header(sprintf('Expires: %s GMT', gmdate('D, d M Y H:i:s', (time() + 1209600))));

		if($this->use_gzip) {
			header('Content-Encoding: ' . $this->gzip_enc_header);
		}
	}

	function check_gzip_use()
	{
		$deactivate_gzip = false;

		// deactivate gzip for IE version < 7
		/*if(preg_match('/(?:msie )([0-9.]+)/i', $_SERVER['HTTP_USER_AGENT'], $ie)) {
			if($ie[1] < 7) {
				$deactivate_gzip = true;
			}
		}*/

		// Check for gzip header or norton internet security
		if (!$deactivate_gzip &&
		((strpos(@$_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) || isset($_SERVER['---------------'])) &&
		function_exists('ob_gzhandler') &&
		(ini_get('zlib.output_compression') != '1')) {
			$this->gzip_enc_header = (strpos(@$_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false) ? 'x-gzip' : 'gzip';
			$this->use_gzip = true;
			$this->cache_file = $this->param['file'] . '.gz';
		}
		else {
			$this->use_gzip = false;
			$this->cache_file = $this->param['file'];
		}
	}

	function check_cache()
	{
		// if cache file is up to date
		$last_modified = gmdate('D, d M Y H:i:s', filemtime($this->cache_file)).' GMT';

		if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strcasecmp($_SERVER['HTTP_IF_MODIFIED_SINCE'], $last_modified) === 0) {
			header('HTTP/1.1 304 Not Modified', true);
			$_SESSION['cache_status'] = 304;
			header('Last-modified: '.$last_modified);
			header('Cache-Control: Public'); // Tells HTTP 1.1 clients to cache
			header('Pragma:'); // Tells HTTP 1.0 clients to cache
		}
		else {
			header('Last-modified: '.$last_modified);
			header('Cache-Control: Public'); // Tells HTTP 1.1 clients to cache
			header('Pragma:'); // Tells HTTP 1.0 clients to cache
			header('Content-Length: '.filesize($this->cache_file));

			if(!function_exists('file_get_contents')) {
    			include_once DOC_ROOT . '/orbicon/3rdParty/php-compat/file_get_contents.php';
			}

			echo file_get_contents($this->cache_file);
		}
	}
}

?>