<?php
/**
 * core settings for our app
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @version 1.30
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-07-01
 */

	// Test for PHP bug which breaks PHP 5.0.x on 64-bit
	$php50x_test = str_replace('a', 'b', array(-1 => -1));
	if(!isset($php50x_test[-1])) {
		trigger_error('PHP 5.0.x is buggy on your 64-bit system; you must upgrade to PHP 5.1.x or higher. See http://bugs.php.net/bug.php?id=34879 for more details', E_USER_ERROR);
		exit();
	}

	// we don't want this
	ini_set('allow_url_fopen', 0);

	// overload with multibyte if available

	// test for missing mbstring extension
	/*if (!extension_loaded('mbstring')) {
		$prefix = (PHP_SHLIB_SUFFIX == 'dll') ? 'php_' : '';
		dl($prefix . 'mbstring.' . PHP_SHLIB_SUFFIX);
	}

	if(extension_loaded('mbstring')) {
		$mb_overload = intval(ini_get('mbstring.func_overload'));
		if($mb_overload != 7) {
			ini_set('mbstring.func_overload', '7');
		}
		unset($mb_overload);
	}
	else {
		//$orbx_log->ewrite('Multibyte extension (http://php.net/mbstring) is unavailable. If you want to properly support UTF8 and similar encodings, install it', __LINE__, __FUNCTION__);
	}*/

	// we don't use these
	ini_set('register_globals', '0');
	$long_arrays = ini_get('register_long_arrays');
	if(($long_arrays != '0') && ($long_arrays != '')) {
		ini_set('register_long_arrays', '0');
	}
	unset($long_arrays);
	set_magic_quotes_runtime(0);
	ini_set('magic_quotes_gpc', '0');

	// get client ip
	require_once DOC_ROOT . '/orbicon/lib/ip/inc.ip.php';

	sanitize_remote_addr();
	define('ORBX_CLIENT_IP', $_SERVER['REMOTE_ADDR']);
	//$debug_switch = valid_public_ip(ORBX_CLIENT_IP) ? false : true;

	// clean up request variables
	require_once DOC_ROOT . '/orbicon/class/inc.request_cleaner.php';
	clean_user_request();

	define('ORBX_MAGIC', '0xB00FB00F');
	define('ORBX_DEBUG', /*$debug_switch*/false);
	define('ORBX_USER_AGENT', $_SERVER['HTTP_USER_AGENT']);
	define('DOC_3RD_PARTY', DOC_ROOT . '/orbicon/3rdParty');

	// handle debug
	if(ORBX_DEBUG) {
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL ^ E_NOTICE);
	}
	else {
		ini_set('display_errors', '0');
		ini_set('display_startup_errors', '0');
		// 13/2/2007 Pavle Gardijan - this blocked php logger from working
		//error_reporting(0);
	}

	// Check on any hacking attempts.
	if(isset($_REQUEST['GLOBALS']) || isset($_COOKIE['GLOBALS'])) {
		trigger_error('Invalid request variable.', E_USER_ERROR);
	}

	// Determine if this is using WAP, WAP2, or imode.  Technically, we should check that wap comes before application/xhtml or text/html, but this doesn't work in practice as much as it should.
	if (isset($_SERVER['HTTP_ACCEPT']) &&
	strpos($_SERVER['HTTP_ACCEPT'], 'application/vnd.wap.xhtml+xml') !== false) {
		$_REQUEST['wap2'] = 1;
	}
	elseif (isset($_SERVER['HTTP_ACCEPT']) &&
	strpos($_SERVER['HTTP_ACCEPT'], 'text/vnd.wap.wml') !== false) {
		if ((strpos($_SERVER['HTTP_USER_AGENT'], 'DoCoMo/') !== false) || (strpos($_SERVER['HTTP_USER_AGENT'], 'portalmmm/') !== false)) {
			$_REQUEST['imode'] = 1;
		}
		else {
			$_REQUEST['wap'] = 1;
		}
	}

	if (!defined('ORBX_WIRELESS')) {
		define('ORBX_WIRELESS', (isset($_REQUEST['wap']) || isset($_REQUEST['wap2']) || isset($_REQUEST['imode'])));
	}

	$use_gzip = false;

	if((ini_get('zlib.output_compression') != '1') 			// zlib output turned off?
	&& (ini_get('output_handler') != 'ob_gzhandler')		// not turned on already?
	&& (version_compare(PHP_VERSION, '4.2.0') != -1)) {		// ok PHP version
		// browser supports it?
		if(strpos(@$_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {
			$orbx_log->dwrite('using gzip for compression', __LINE__, __FUNCTION__);
			$use_gzip = true;
		}
	}

	// Some settings and headers are different for wireless protocols.
	if (ORBX_WIRELESS) {
		define('ORBX_WIRELESS_PROTOCOL', isset($_REQUEST['wap']) ? 'wap' : (isset($_REQUEST['wap2']) ? 'wap2' : (isset($_REQUEST['imode']) ? 'imode' : '')));

		// Some cellphones can't handle output compression...
		$use_gzip = false;

		// Wireless protocol header.
		if (ORBX_WIRELESS_PROTOCOL == 'wap') {
			header('Content-Type: text/vnd.wap.wml');
		}
	}

	ob_start();

	// we will use gzip
	define('ORBX_GZIP', $use_gzip);

	require_once DOC_ROOT . '/orbicon/class/class.version.php';
	$orbicon_info = new Version;

	define('ORBX_FULL_NAME', $orbicon_info->product_name);
	define('ORBX_BUILD', $orbicon_info->product_release_level . '.' . $orbicon_info->product_development_level);
	define('ORBX_AUTHORS', $orbicon_info->product_author);
	define('ORBX_SUPPORT_EMAIL', $orbicon_info->product_support_email);
	define('ORBX_DEFAULT_LANGUAGE', 'en');
	define('ORBX_UNIQUE_ID', 'Ju?hG&F0yh9?=/6*GVfd-d8u6f86hp');

	unset($orbicon_info, $use_gzip);

	// user status
	define('ORBX_USER_STATUS_EX_USER', 	1);
	define('ORBX_USER_STATUS_USER', 	2);
	define('ORBX_USER_STATUS_ADMIN', 	3);
	define('ORBX_USER_STATUS_SYSADMIN', 4);

	// * paths
	$scheme = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? 'https' : 'http';
	define('SCHEME', $scheme);

	// * setup the domain
	$domain = (isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
	define('DOMAIN', $domain);
	define('DOMAIN_NO_WWW', str_replace('www.', '', DOMAIN));
	define('ORBX_SYS_CONFIG', DOC_ROOT . '/site/mercury/orbicon.system.php');
	if(is_file(DOC_ROOT . '/site/mercury/ORBX_URI_PATH')) {
		define('ORBX_SITE_URL', SCHEME . '://' . DOMAIN . file_get_contents(DOC_ROOT . '/site/mercury/ORBX_URI_PATH'));
	}
	else {
		define('ORBX_SITE_URL', SCHEME . '://' . DOMAIN . ORBX_URI_PATH);
	}

	// include sys file
	if(is_file(ORBX_SYS_CONFIG)) {
		include_once ORBX_SYS_CONFIG;
	}

	// * db tables
	define('TABLE_EDITORS', 'orbicon_editors');
	define('TABLE_NEWS', 'orbicon_news');
	define('TABLE_COLUMNS', 'orbicon_column');
	define('TABLE_SETTINGS', 'orbicon_settings');
	define('TABLE_STATISTICS', 'orbicon_stats');
	define('TABLE_POLL', 'orbicon_poll');
	define('TABLE_POLL_IP', 'orbicon_poll_ip');
	define('TABLE_POLL_OPTIONS', 'orbicon_poll_options');
	define('TABLE_ZONES', 'orbicon_zone');
	define('TABLE_ADRBKS', 'orbicon_adrbk');
	define('TABLE_EMAILS', 'orbicon_emails');
	define('TABLE_FORMS', 'orbicon_forms');
	define('TABLE_NEWS_CAT', 'orbicon_news_category');
	define('TABLE_BANNERS', 'orbicon_banners');
	define('TABLE_SESSION', 'orbicon_sessions');
	define('TABLE_SYS_ISO639_1_CODES', 'orbicon_sys_iso_639_1_codes');
	define('TABLE_SYS_ISO639_2_CODES', 'orbicon_sys_iso_639_2_codes');
	define('TABLE_PRIVILEGES', 'orbicon_privileges');
	define('TABLE_COUNTRIES', 'orbicon_countries');
	define('TABLE_DESKTOP', 'orbx_desktop');
	define('TABLE_DESKTOP_RSS', 'orbx_desktop_rss');
	define('TABLE_DESKTOP_WALLPAPER', 'orbx_desktop_wallpaper');
	define('TABLE_MIME_TYPES', 'orbx_mime_types');
	define('TABLE_SYNC_SERVERS', 'orbx_sync_servers');
	define('TABLE_SYNC_SERVERS_PROPS', 'orbx_sync_servers_props');
	define('TABLE_REG_USERS', 'orbicon_reg_members');
	define('TABLE_SURVEY_QUESTIONS', 'orbicon_survey_questions');
	define('TABLE_KWLINKS', 'orbx_mod_kwlinks');
	define('TABLE_HTML_CACHE', 'orbx_html_cache');

	// * magister db
	define('MAGISTER_TITLES', 'magister_articles');
	define('MAGISTER_CONTENTS', 'magister_answers');
	define('MAGISTER_CATEGORIES', 'magister_categories');
	define('MAGISTER_TITLES_BCK', 'magister_articles_bck');
	define('MAGISTER_CONTENTS_BCK', 'magister_answers_bck');
	define('MAGISTER_CATEGORIES_BCK', 'magister_categories_bck');

	// * venus db
	define('VENUS_IMAGES', 'venus_images');
	define('VENUS_CATEGORIES', 'venus_categories');

	// * mercury db
	define('MERCURY_FILES', 'mercury_files');
	define('MERCURY_CATEGORIES', 'mercury_categories');
	define('MERCURY_COMMENTS', 'mercury_comments');

	// sync
	define('SYNC_MANAGER_TYPE_NONE', 0);
	define('SYNC_MANAGER_TYPE_RECEIVER', 1);
	define('SYNC_MANAGER_TYPE_REPOSITORY', 2);

	// Attempt to change a few PHP settings
	ini_set('session.use_cookies', true);
	ini_set('session.use_only_cookies', false);
	ini_set('url_rewriter.tags', '');
	ini_set('session.use_trans_sid', false);
	ini_set('arg_separator.output', '&amp;');

	// 3.5.2007 Pavle Gardijan - this was intended for www subdomains but caused
	// problems on other subdomain
	/*if((ORBX_DEBUG === false) && ($debug_switch === false)) {
		ini_set('session.cookie_domain', 'www.' . DOMAIN_NO_WWW);
	}*/

	// setup cookie path for sessions
	// leave trailing slash
	// 18.2.2007 Pavle Gardijan - this caused random logouts
	//ini_set('session.cookie_path', ORBX_URI_PATH . '/');

	// Attempt to end the already-started session.
	if(ini_get('session.auto_start')) {
		session_write_close();
	}

	// This is here to stop people from using bad junky PHPSESSIDs.
	if(isset($_REQUEST[session_name()]) && (preg_match('~^[A-Za-z0-9]{16,32}$~', $_REQUEST[session_name()]) == 0) && !isset($_COOKIE[session_name()])) {

		// seed for PHP < 4.2.0
		srand((float) microtime() * 10000000);

		$sess_id = md5(md5('orbx_sess_' . time()) . rand());
		$_REQUEST[session_name()] = $sess_id;
		$_GET[session_name()] = $sess_id;
		$_POST[session_name()] = $sess_id;
	}

	if((int) ini_get('session.gc_maxlifetime') <= 1200) {
		ini_set('session.gc_maxlifetime', '1200');
	}

	session_cache_limiter('private_no_expire, must-revalidate');

	if(session_id() == '') {
		$orbx_log->swrite('starting session', __LINE__, __FUNCTION__);
		session_start();

		// While PHP 4.1.x should use $_SESSION, it seems to need this to do it right
		if(version_compare(PHP_VERSION, '4.2.0') == -1) {
			$HTTP_SESSION_VARS['php_412_bugfix'] = true;
		}
	}

	// * session fixation check
	if(!isset($_SESSION['orbx_session_started'])) {

		if(!function_exists('session_regenerate_id')) {
			include DOC_ROOT . '/orbicon/lib/php-compat/session_regenerate_id.php';
		}

		session_regenerate_id();
		$_SESSION['orbx_session_started'] = true;
		$orbx_log->swrite('session fixation check done', __LINE__, __FUNCTION__);
	}

/*	$hash = md5(ORBX_USER_AGENT . @$_SERVER['HTTP_ACCEPT_CHARSET'] . DOC_ROOT . session_id() . ORBX_CLIENT_IP);

	// * session hijack protection
	if(isset($_SESSION['orbx_virtual_id_card'])) {
		if($_SESSION['orbx_virtual_id_card'] != $hash) {
			$orbx_log->ewrite('Session hijack', __LINE__, __FUNCTION__);
			session_destroy();
			header('Location: ' . ORBX_SITE_URL);
			trigger_error('Session hijack', E_USER_ERROR);
			exit();
		}
	}
	else {
		$_SESSION['orbx_virtual_id_card'] = $hash;
	}

	unset($hash);*/

	if(defined('ORBX_WIRELESS_PROTOCOL') && (ORBX_WIRELESS_PROTOCOL != 'wap')) {
		header('Content-Type: text/html; charset=UTF-8', true);
	}
	header('X-Powered-By: ' . ORBX_FULL_NAME, true);

	// caching will require this
	$_SESSION['cache_status'] = 200;

?>