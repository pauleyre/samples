<?php
/*-----------------------------------------------------------------------*
	Project........:	Orbicon X framework 2
	File...........:
	Version........:	1.0 (22/10/2006)
	Author.........:	Pavle Gardijan (pavle.gardijan@orbitum.net) - Orbitum d.o.o.
	Copyright......:	(c) 2006 Orbitum internet communications d.o.o. (www.orbitum.net)
	Created........:	01/07/2006
	Notes..........:
	Modified.......:
*-----------------------------------------------------------------------*/

	function clean_user_request()
	{
		$match = null;

		// Save some memory.. (since we don't use these anyway.)
		unset($GLOBALS['HTTP_POST_VARS'], $GLOBALS['HTTP_POST_VARS'], $GLOBALS['HTTP_POST_FILES'], $GLOBALS['HTTP_POST_FILES']);

		// These keys shouldn't be set...ever.
		if(isset($_REQUEST['GLOBALS']) || isset($_COOKIE['GLOBALS'])) {
			trigger_error('Invalid request', E_USER_ERROR);
		}

		// Same goes for numeric keys.
		foreach(array_merge(array_keys($_REQUEST), array_keys($_FILES)) as $key) {
			if(is_numeric($key)) {
				trigger_error('Invalid request', E_USER_ERROR);
			}
		}

		// Protect against register_globals
		// This must be done before any globals are set by the code
		if(ini_get('register_globals')) {
			if(isset($_REQUEST['GLOBALS'])) {
				trigger_error('Invalid request', E_USER_ERROR);
			}

			$superglob = array(
				'GLOBALS',
				'_SERVER',
				'HTTP_SERVER_VARS',
				'_GET',
				'HTTP_GET_VARS',
				'_POST',
				'HTTP_POST_VARS',
				'_COOKIE',
				'HTTP_COOKIE_VARS',
				'_FILES',
				'HTTP_POST_FILES',
				'_ENV',
				'HTTP_ENV_VARS',
				'_REQUEST',
				'_SESSION',
				'HTTP_SESSION_VARS'
			);
			foreach($_REQUEST as $k => $v) {
				if(in_array($k, $superglob)) {
					header('HTTP/1.x 500 Internal Server Error');
					trigger_error('Invalid request', E_USER_ERROR);
				}
				unset($GLOBALS[$k]);
			}
		}

		// Numeric keys in cookies are less of a problem. Just unset those.
		foreach($_COOKIE as $key => $value) {
			if(is_numeric($key)) {
				unset($_COOKIE[$key]);
			}
		}
		unset($value);

		// Get the correct query string. It may be in an environment variable...
		if(!isset($_SERVER['QUERY_STRING'])) {
			$_SERVER['QUERY_STRING'] = getenv('QUERY_STRING');
		}

		// Are we going to need to parse the ; out?
		if((strpos(@ini_get('arg_separator.input'), ';') === false || @version_compare(PHP_VERSION, '4.2.0') == -1) && !empty($_SERVER['QUERY_STRING'])) {
			// Get rid of the old one!  You don't know where it's been!
			$_GET = array();

			// Was this redirected?  If so, get the REDIRECT_QUERY_STRING.
			$_SERVER['QUERY_STRING'] = urldecode(substr($_SERVER['QUERY_STRING'], 0, 5) == 'url=/' ? $_SERVER['REDIRECT_QUERY_STRING'] : $_SERVER['QUERY_STRING']);

			// Replace ';' with '&' and '&something&' with '&something=&'.  (this is done for compatibility...)
			parse_str(preg_replace('/&(\w+)(?=&|$)/', '&$1=', strtr($_SERVER['QUERY_STRING'], array(';?' => '&', ';' => '&'))), $_GET);
		}
		else if(strpos(ini_get('arg_separator.input'), ';') !== false) {
			$_GET = urldecode__recursive($_GET);

			/*if(get_magic_quotes_gpc() != 0) {
				$_GET = stripslashes__recursive($_GET);
			}*/

			// Search engines will send action=profile%3Bu=1, which confuses PHP.
			foreach($_GET as $k => $v) {
				if(is_string($v) && strpos($k, ';') !== false) {
					$temp = explode(';', $v);
					$_GET[$k] = $temp[0];

					for($i = 1, $n = count($temp); $i < $n; $i++) {
						@list($key, $val) = @explode('=', $temp[$i], 2);
						if(!isset($_GET[$key])) {
							$_GET[$key] = $val;
						}
					}
				}

				// This helps a lot with integration!
				if($k[0] == '?') {
					$_GET[substr($k, 1)] = $v;
					unset($_GET[$k]);
				}
			}
		}

		// we don't need magic quotes. caused lots of trouble
		if(get_magic_quotes_gpc() != 0) {
			$_POST = stripslashes__recursive($_POST);
			$_GET = stripslashes__recursive($_GET);
			$_COOKIE = stripslashes__recursive($_COOKIE);
		}

		// Add entities to GET.  This is kinda like the slashes on everything else.
		$_GET = htmlspecialchars__recursive($_GET);

		// Take care of the server variables.
		$_SERVER = addslashes__recursive($_SERVER);

		// Let's not depend on the ini settings... why even have COOKIE in there, anyway?
		$_REQUEST = $_POST + $_GET;

		// get url
		// * paths
		$scheme = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? 'https' : 'http';
		// * setup the domain
		$domain = (isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
		$scripturl = "$scheme://$domain/index.php";

		// Make sure we know the URL of the current request.
		if(empty($_SERVER['REQUEST_URI'])) {
			$_SERVER['REQUEST_URL'] = $scripturl.(!empty($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : '');
		}
		else if(preg_match('~^([^/]+//[^/]+)~', $scripturl, $match) == 1) {
			$_SERVER['REQUEST_URL'] = $match[1] . $_SERVER['REQUEST_URI'];
		}
		else {
			$_SERVER['REQUEST_URL'] = $_SERVER['REQUEST_URI'];
		}

		// And make sure HTTP_USER_AGENT is set.
		$_SERVER['HTTP_USER_AGENT'] = (isset($_SERVER['HTTP_USER_AGENT'])) ? htmlspecialchars(stripslashes($_SERVER['HTTP_USER_AGENT']), ENT_QUOTES) : '';
	}

	/**
	 * Adds slashes to the array/variable. Uses two underscores to guard against overloading.
	 *
	 * @param mixed $var
	 * @return mixed
	 */
	function addslashes__recursive($var, $level = 0)
	{
		if(!is_array($var)) {
			return addslashes($var);
		}

		// Reindex the array with slashes.
		$new_var = array();

		// Add slashes to every element, even the indexes!
		foreach($var as $k => $v) {
			$new_var[addslashes($k)] = ($level > 25) ? null : addslashes__recursive($v, $level + 1);
		}

		return $new_var;
	}

	/**
	 * Adds html entities to the array/variable.  Uses two underscores to guard against overloading.
	 *
	 * @param mixed $var
	 * @return mixed
	 */
	function htmlspecialchars__recursive($var, $level = 0)
	{
		if(!is_array($var)) {
			return htmlspecialchars($var, ENT_QUOTES);
		}

		// Add the htmlspecialchars to every element.
		foreach ($var as $k => $v) {
			$var[$k] = ($level > 25) ? null : htmlspecialchars__recursive($v, $level + 1);
		}

		return $var;
	}

	/**
	 * Removes url stuff from the array/variable.  Uses two underscores to guard against overloading
	 *
	 * @param mixed $var
	 * @return mixed
	 */
	function urldecode__recursive($var, $level = 0)
	{
		if(!is_array($var)) {
			return urldecode($var);
		}

		// Reindex the array...
		$new_var = array();

		// Add the htmlspecialchars to every element.
		foreach ($var as $k => $v) {
			$new_var[urldecode($k)] = ($level > 25) ? null : urldecode__recursive($v, $level + 1);
		}

		return $new_var;
	}

	/**
	 * Strips the slashes off any array or variable.  Two underscores for the normal reason.
	 *
	 * @param mixed $var
	 * @return mixed
	 */
	function stripslashes__recursive($var, $level = 0)
	{
		if(!is_array($var)) {
			return stripslashes($var);
		}

		// Reindex the array without slashes, this time.
		$new_var = array();

		// Strip the slashes from every element.
		foreach($var as $k => $v) {
			$new_var[stripslashes($k)] = ($level > 25) ? null : stripslashes__recursive($v, $level + 1);
		}

		return $new_var;
	}

?>