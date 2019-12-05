<?php
/**
 * Main runtime (index.php) file
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package OrbiconFE
 * @version 1.00
 * @link http://
 * @license http://
 * @since 2007-07-01
 */

	$bstarttime = explode(' ', microtime());
	$bstarttime = $bstarttime[1] + $bstarttime[0];

	/*----- ATTENTION! -------------------------------------------------
	 | Include before wherever DOC_ROOT required.
	 |------------------------------------------------------------------*/
	if(!defined('DOC_ROOT')) {
		$request_uri = $_SERVER['REQUEST_URI'];

		global $original;
		$left = str_replace(dirname(__FILE__), '', dirname($original));
		$left = str_replace('\\', '/', $left);
		$left = str_replace($left, '', $request_uri);
		// get rid of query
		$left = explode('?', $left);
		// file or no file?
		$left = (strpos($left[0], '.php')) ? dirname($left[0]) : $left[0];
		// strip forward slash as well
		while(substr($left, -1, 1) == '/') {
			$left = (substr($left, -1, 1) == '/') ? substr($left, 0, -1) : $left;
		}

		define('ORBX_URI_PATH', $left);
		define('DOC_ROOT', dirname(__FILE__));
		unset($left, $request_uri, $original);
	}
	//----- ATTENTION! ENDS -------------------------------------------------

	// filesystem class
	require_once DOC_ROOT . '/orbicon/class/file/class.file.php';

	// setup logger
	global $orbx_log;
	require_once DOC_ROOT . '/orbicon/class/class.logger.php';
	$orbx_log = new Logger();

	// load settings
	require_once DOC_ROOT . '/orbicon/class/settings.php';

	// load language tools
	require_once DOC_ROOT . '/orbicon/class/language.php';

	// global include
	require_once DOC_ROOT . '/orbicon/class/inc.global.php';

	// orbicon include
	require_once DOC_ROOT . '/orbicon/class/inc.orbicon.php';

	$_SESSION['page_title'] = null;

	// registering custom shutdown function and error handler
	register_shutdown_function('system_crash_cleanup');
	// don't bother if we don't have this function
	if(function_exists('debug_backtrace')) {
		if(version_compare(phpversion(), '5.0.0', '>=')) {
			set_error_handler('get_detailed_error', E_ERROR | E_USER_ERROR);
		}
		else {
			set_error_handler('get_detailed_error');
		}
	}

	// setup MySQL db connection
	require DOC_ROOT . '/orbicon/class/class.db.mysql.php';

	global $dbc;
	$dbc = new DBC();
	$dbc->_db->connect();

	// get settings in db
	require DOC_ROOT . '/orbicon/class/class.settings.php';
	$settings = new Settings();
	$settings->build_site_settings(true);
	$settings = null;

	/*----- ATTENTION! -------------------------------------------------
	 | Language and content needs to be set in a GET parameter here
	 | if we're using permalinks
	 |------------------------------------------------------------------*/
	if($_SESSION['site_settings']['main_site_permalinks']) {
		$request_uri_c = preg_replace('/' . preg_quote(ORBX_URI_PATH, '/') . '/', '', $_SERVER['REQUEST_URI'], 1);
		$request_uri_c = ($request_uri_c[0] == '/') ? substr($request_uri_c, 1) : $request_uri_c;
		$request_uri_c = explode('/', $request_uri_c);
		$language = array_shift($request_uri_c);
		$request_uri_c = array_remove_empty($request_uri_c);
		// workaround for orbicon backend bug
		if(strpos($language, '?') === false) {
			$request_uri_c = implode('/', $request_uri_c);

			// this fixes bug with non converted unicode chars from folder names
			while((strpos($request_uri_c, '%') !== false)) {
				$request_uri_c = urldecode($request_uri_c);
			}

			$_GET[$language] = $request_uri_c;
		}
		unset($request_uri_c, $language);
	}
	//----- ATTENTION! ENDS -------------------------------------------------

	// load Orbicon X
	global $orbicon_x;
	require DOC_ROOT . '/orbicon/class/class.orbicon.php';
	$orbicon_x = new OrbiconX();

	// load module
	global $orbx_mod;
	require DOC_ROOT . '/orbicon/class/class.module.php';
	$orbx_mod = new Module();

	// include translation file
	include_once DOC_ROOT . '/orbicon/languages/' . $orbicon_x->ptr . '.php';

	global $ln;
	$ln = array_merge($orbx_mod->get_translations(), $ln);

	if(get_is_admin()) {
		global $orbx_ln;
		$ln = array_merge($ln, $orbx_ln);
	}
	else {
		unset($orbx_ln);
	}

	// maintenance mode is on
	if((ORBX_MAINTENANCE_MODE == 1) && 	// option turned on
	!get_is_admin() && 					// we're not an admin
	($_GET[$orbicon_x->ptr] != 'orbicon/authorize')) {	// we're not trying to login

		header('HTTP/1.1 503 Service Unavailable', true);
		header('Retry-After: 3600', true);

		$_SESSION['cache_status'] = 503;

		include_once DOC_ROOT . '/orbicon/controler/admin.maintenance.mode.php';
		exit();
	}

	// main body

	// if this is set to true the switch below will be skipped
	$skip_main_body = false;
	// if this is set to true we'll cache the results of this page
	$using_cache = false;
	// if this is set to true we'll skip caching the results of this page
	//$disable_cache = false;
	$disable_cache = (bool) !$_SESSION['site_settings']['use_cache'];

	// set language header
	header('Content-Language: '.$orbicon_x->ptr, true);

	@$x = explode('/', $_GET[$orbicon_x->ptr]);
	$x = (isset($_GET[$orbicon_x->ptr])) ? trim($x[0]) : 'orbicon_home';

	// check for system file and start installation if not found
	if(!is_file(ORBX_SYS_CONFIG)) {
		$q = trim($_GET[$orbicon_x->ptr]);
		// setup is ok
		if($q != 'orbicon.setup') {

			header('HTTP/1.1 503 Service Unavailable', true);
			header('Retry-After: 3600', true);

			$_SESSION['cache_status'] = 503;

			require_once DOC_ROOT . '/orbicon/controler/setup.welcome.php';
			$orbx_log->swrite('unable to locate configuration file, starting installation...', __LINE__, __FUNCTION__);
			exit();
		}
	}

	// initiate tornado guard
	if($_SESSION['site_settings']['flood_guard']) {
		require_once DOC_ROOT . '/orbicon/class/class.tg.php';
		$tg = new OrbX_TornadoGuard(DOC_ROOT . '/site/mercury/flood/');
	}

	// switch to SSL if enabled
	if(_get_is_orbicon_uri() &&
	(SCHEME !== 'https') &&
	$_SESSION['site_settings']['ssl_orbx']) {
		$orbx_log->swrite('Requesting secure back-end, switching to https', __LINE__, __FUNCTION__);
		redirect('https://' . DOMAIN . $_SERVER['REQUEST_URI']);
	}

	// ping
	if(isset($_GET['ping'])) {
		echo '200 OK';
		$skip_main_body = true;
		$orbx_log->swrite('Received ping request from ' . ORBX_CLIENT_IP, __LINE__, __FUNCTION__);
	}

	// database connection error!
	if(!is_resource($dbc->_db->get_link()) && ($_GET[$orbicon_x->ptr] != 'orbicon.setup')) {
		header('HTTP/1.1 503 Service Unavailable', true);
		header('Retry-After: 3600', true);
		$_SESSION['cache_status'] = 503;
		session_write_close();
		require_once DOC_ROOT . '/orbicon/controler/db_error.php';
		exit();
	}

	// someone submitted a form
	if(isset($_GET['submit_form']) && $orbx_mod->validate_module('forms')) {

		if(isset($_POST['inpulls_reg_submit']) && isset($_POST['orbicon_registration'])) {
			// just skip in case
		}
		else {

			include_once DOC_ROOT . '/orbicon/modules/forms/class.form.php';
			$form = new Form();
			$form->submit_orbiconform();
			$form = null;
			$disable_cache = true;
		}
	}

	// someone submitted inpulls form
	if(isset($_POST['inpulls_reg_submit']) && $orbx_mod->validate_module('inpulls')) {
		$disable_cache = true;
	}

	// this request is invalid, ban client
	if(isset($_GET['tornado_guard']) && $_SESSION['site_settings']['flood_guard']) {
		$tg->tg_update_blacklist();
	}

	// looking for alternate content
	$format = explode('/', $_GET[$orbicon_x->ptr]);
	$last = array_pop($format);

	if($orbicon_x->get_is_requesting_alt($last) &&
	!empty($format[0]) &&
	($format[0] != 'orbicon')) {
		include_once DOC_ROOT . '/orbicon/class/inc.alt.php';
		echo output_page_format($last);
		$skip_main_body = true;
	}

	unset($last, $format);

	if(!$skip_main_body) {
		// custom rules
		if(!empty($_SESSION['site_settings']['tg_rules']) && $_SESSION['site_settings']['flood_guard']) {
			$rules = explode(',', $_SESSION['site_settings']['tg_rules']);

			foreach ($rules as $rule) {
				list($tg_req, $tg_sec) = explode(':', $rule);
				if(isset($tg_req) && isset($tg_sec)) {
					if(!empty($tg_req) && !empty($tg_sec)) {
						$tg->tg_rules[$tg_sec] = $tg_req;
					}
				}
			}

			unset($rules, $rule);
		}

		// default rules
		if(empty($tg->tg_rules) && $_SESSION['site_settings']['flood_guard']) {
			// tornado guard is down here because we don't want to count requests above
			$tg->tg_rules = array (
				10 => 10,		// rule 1 - maximum 10 requests in 10 secs
				60 => 30,		// rule 2 - maximum 30 requests in 60 secs
				300 => 50,		// rule 3 - maximum 50 requests in 300 secs
				3600 => 200		// rule 4 - maximum 200 requests in 3600 secs
			);
		}

		// we failed: display error screen, return 403 and exit
		if(is_file(ORBX_SYS_CONFIG) && ($x != 'exit') && $_SESSION['site_settings']['flood_guard']) {
			$q = trim($_GET[$orbicon_x->ptr]);
			/**
			 * @todo X-Requested-With: XMLHttpRequest should be implemented here but we can't determine if it's forged or real yet?
			 */

			if($tg->tg_get_flood() &&
			!get_is_search_engine_bot() &&
			!get_is_admin() &&
			!get_is_w3c_validator()) {
				// login is ok
				if($q != 'orbicon/authorize') {
					$orbx_log->swrite('flood detected, banning client ' . ORBX_CLIENT_IP, __LINE__, __FUNCTION__);
					header('HTTP/1.1 403 Forbidden', true);
					$_SESSION['cache_status'] = 403;
					require_once DOC_ROOT . '/orbicon/controler/ban.php';
					exit();
				}
			}
		}

		// free memory
		if($_SESSION['site_settings']['flood_guard']) {
			$tg = null;
		}

		// restriced access setting is on
		if($_SESSION['site_settings']['site_restricted_access'] &&
		!get_is_admin() &&
		trim($_GET[$orbicon_x->ptr]) != 'orbicon/authorize') {
			redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/authorize');
		}

		// log statistics
		if($orbx_mod->validate_module('stats')) {
			require_once DOC_ROOT . '/orbicon/modules/stats/class.stats.php';
			$stats = new Statistics();
			$stats->orbicon_session_counter();
		}

		// setup zone
		require_once DOC_ROOT . '/orbicon/class/inc.zone.php';
		setup_zone($x);

		if(get_is_admin()) {
			require_once DOC_ROOT . '/orbicon/class/inc.zone.admin.php';
		}

		// handle zone ssl
		/**
		 * @todo FIX ME for setting site under ssl
		 */
		if(($_SESSION['current_zone'] === null) &&
		(SCHEME != 'http') &&
		!_get_is_orbicon_uri()) {
			redirect('http://' . DOMAIN . $_SERVER['REQUEST_URI']);
		}
		else if($_SESSION['current_zone'] !== null) {
			foreach($_SESSION['current_zone'] as $zone) {

				if($zone['under_ssl'] && (SCHEME != 'https') && !_get_is_orbicon_uri()) {
					redirect('https://' . DOMAIN . $_SERVER['REQUEST_URI']);
				}
				else if(!$zone['under_ssl'] && (SCHEME != 'http') && !_get_is_orbicon_uri()) {
					redirect('http://' . DOMAIN . $_SERVER['REQUEST_URI']);
				}
			}
		}

		// log logging out here before destroying the session
		if($x == 'exit') {
			if($orbx_mod->validate_module('stats') && $_SESSION['user.a']) {
				$stats->log_login_history($_SESSION['user.a'], false);
			}

			// delete user's last location
			if($_SESSION['user.a']) {
				$orbicon_x->update_last_location($_SESSION['user.a']['id'], null);
			}
		}

		// no way
		if((is_file(ORBX_SYS_CONFIG)) && ($x == 'orbicon.setup')) {
			$x = 'orbicon_home';
		}

		// restriced access setting is on
		if(($_SESSION['site_settings']['homepage_redirect'] != '') &&
		($x == 'orbicon_home') &&
		trim($_GET[$orbicon_x->ptr]) != $_SESSION['site_settings']['homepage_redirect']) {
			redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=' . $_SESSION['site_settings']['homepage_redirect']);
		}

		// log online status
		if(get_is_member() && $orbx_mod->validate_module('inpulls')) {
			include_once DOC_ROOT . '/orbicon/modules/inpulls/inc.online.php';
			update_inpulls_online($_SESSION['user.r']['pring_id']);
		}

		if(!$disable_cache) {
			require_once DOC_ROOT .'/orbicon/class/class.cachee.php';
			$cache = new CacheEngine();
			$content = $cache->get_cache($x);
		}
		else {
			$content = null;
		}

		if($content !== null) {

			ob_clean();
			echo $content;
			unset($content);
			$using_cache = true;
			header('X-SysCache: Yes');
		}
		else {
			switch($x) {
				case 'orbicon_home':	$include_page = DOC_ROOT . '/orbicon/controler/home.php';							break;
				case 'exit':			$include_page = DOC_ROOT . '/orbicon/controler/exit.php';							break;
				case 'orbicon':

					// verify ip range access
					$ip_range_verified = true;
					if($_SESSION['site_settings']['restricted_range_from'] != '*' && // all wildcard is ok
				$_SESSION['site_settings']['restricted_range_from'] != '' &&		// empty
				$_SESSION['site_settings']['restricted_range_from'] != '...') {		// three dots
					$range = (empty($_SESSION['site_settings']['restricted_range_to'])) ? $_SESSION['site_settings']['restricted_range_from'] : "{$_SESSION['site_settings']['restricted_range_from']}-{$_SESSION['site_settings']['restricted_range_to']}";
					$ip_range_verified = net_match($range, ORBX_CLIENT_IP);

					if(!$ip_range_verified) {
						$include_page = DOC_ROOT . '/orbicon/controler/home.php';
						$disable_cache = true;
					}
					else {
						$include_page = DOC_ROOT . '/orbicon/controler/orbicon.php';
					}
				}
				else {
					$include_page = DOC_ROOT . '/orbicon/controler/orbicon.php';
				}

				break;
				case 'orbicon.setup':	$include_page = DOC_ROOT . '/orbicon/controler/setup.php';							break;
				default:				$include_page = DOC_ROOT . '/orbicon/controler/column.php';						break;
			}

			include_once $include_page;
		}

		// close db connection
		//$dbc->_db->disconnect();
		//$dbc = null;

		if(function_exists('memory_get_usage')) {
			$mem_usage = memory_get_usage();
			$cache_mark = ($using_cache) ? '(c)' : '';
			$orbx_log->swrite('memory usage' . $cache_mark . ': '  . byte_size($mem_usage) . ' (' . $mem_usage . ' bytes)', __LINE__, __FUNCTION__);
		}
	}

	// log statistics
	if($orbx_mod->validate_module('stats')) {
		require_once DOC_ROOT . '/orbicon/modules/stats/class.stats.php';
		$stats = new Statistics();
		$stats->log_personal_visits();
	}
	// you're no longer needed
	$stats = null;


	if(!$using_cache && !$skip_main_body && !$disable_cache) {
		header('X-SysCache: No');
		$cache->put_cache(remove_utf8bom(ob_get_contents()), $x);
		$cache = null;
	}

	// we're using our own class since ob_gzhandler causes problems
	if(ORBX_GZIP) {
		global $bstarttime;
		// finish the process time
		$bmtime = explode(' ', microtime());
		$btotaltime = ($bmtime[0] + $bmtime[1]) - $bstarttime;

		include_once DOC_ROOT . '/orbicon/class/inc.gzip.http.php';
		do_encode();

		$orbx_log->swrite('processing time ' . $btotaltime . '/' . rounddown($btotaltime, 2) . 's', __LINE__, __FUNCTION__);
	}

	// main body ENDS

	$orbicon_x->set_page_title(null);
	$orbx_log->quit();
	$orbx_log = null;

	// close db connection
	if(is_resource($dbc->_db->get_link())) {
		$dbc->_db->disconnect();
		$dbc = null;
	}

	/*----- ATTENTION! -------------------------------------------------
	 | Don't insert code below here
	 |------------------------------------------------------------------*/
?>