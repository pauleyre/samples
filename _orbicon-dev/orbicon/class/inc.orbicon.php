<?php
/**
 * Include library for System
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package SystemFE
 * @subpackage Core
 * @version 1.00
 * @link http://
 * @license http://
 * @since 2006-07-01
 */

	/**
	 * return true if current user is a registered member
	 *
	 * @return bool
	 */
	function get_is_member()
	{
		return (bool) ($_SESSION['user_authorized'] && !empty($_SESSION['user.r']['id']));
	}

	/**
	 * return true if current user is a system administrator
	 *
	 * @return bool
	 */
	function get_is_admin()
	{
		return (bool) (isset($_SESSION['authorized']) && !empty($_SESSION['user.a']['id']));
	}

	/**
	 * scans templates for $needle, speeds up the loading
	 *
	 * @param string $needle
	 * @return int
	 */
	function scan_templates($needle)
	{
		$needle_found = false;

		$d = dir(DOC_ROOT . '/site/gfx/');

		if(!$d) {
			trigger_error('Could not open directory ' . DOC_ROOT . '/site/gfx/', E_USER_WARNING);
			return false;
		}

		$entry = $d->read();

		while($entry !== false) {
			if(get_extension($entry) == 'html') {
				$mtime += filemtime(DOC_ROOT . '/site/gfx/' . $entry);
				$templates[] = $entry;
			}
			$entry = $d->read();
		}
		$d->close();
		unset($d);

		// we have a cached match, return and exit here
		if($_SESSION['orbicon_scan_template'][$needle][0] == $mtime) {
			return $_SESSION['orbicon_scan_template'][$needle][1];
		}

		foreach($templates as $template) {
			$content = file_get_contents(DOC_ROOT . '/site/gfx/' . $template);
			if(strpos($content, $needle) !== false) {
				$needle_found += 1;
			}
		}
		// release memory
		unset($content);

		// let's do some caching to speed up things even more
		$_SESSION['orbicon_scan_template'][$needle] = array($mtime, intval($needle_found));
		return intval($needle_found);
	}

	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	function _get_default_language()
	{
		global $dbc;
		$q = '	SELECT 	value
				FROM 	'.TABLE_SETTINGS.'
				WHERE 	(setting = \'main_site_def_lng\')
				LIMIT 	1';

		$a = $dbc->_db->get_cache($q);
		if($a === null) {
			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);
			$dbc->_db->put_cache($a, $q);
		}

		$ln = ((string) $a['value'] === '') ? ORBX_DEFAULT_LANGUAGE : $a['value'];
		return $ln;
	}

	function _get_is_orbicon_uri()
	{
		global $orbicon_x;
		$orbicon = explode('/', $_GET[$orbicon_x->ptr]);

		if($orbicon[0] == 'orbicon') {
			return true;
		}
		return false;
	}

	/**
	 * Added from PHP5 In Practice, Alen Novakovic-13/11/06
	 * ---------------------------
	 *
	 * Clears MySQL and HTML cache, closes connection with database
	 * sends e-mail to support team.
	 */
	// Create a global variable that keeps the good/bad status of this script.
	// Preset it to false. Assume the worstcase unless we say otherwise.
	$_system_crash_status = true;

	function system_crash_cleanup()
	{
		global $_system_crash_status, $dbc;
		if($_system_crash_status === false) {

			trigger_error('System crash detected', E_USER_WARNING);

			// clean session shutdown
			session_write_close();

			// check if mysql connection exists
			if(is_resource($dbc->db_link)) {
				$dbc->_db->disconnect();
				$dbc = null;
			}

			// unlink MySQL cache files
			$to_unlink_mysql = glob(DOC_ROOT . '/site/mercury/{sqlc~}*', GLOB_BRACE);
			foreach($to_unlink_mysql as $filename) {
				unlink($filename);
			}
			unset($to_unlink_mysql);

			// unlink HTML cache files
			$to_unlink_html = glob(DOC_ROOT . '/site/mercury/{orbxc~}*', GLOB_BRACE);
			foreach($to_unlink_html as $filename) {
				unlink($filename);
			}
			unset($to_unlink_html);

			// remove file locks
			$to_unlink_dir = glob(DOC_ROOT . '/site/mercury/*{.dirlock}', GLOB_BRACE);
			foreach($to_unlink_dir as $dirname) {
				rmdir($dirname);
			}
			unset($to_unlink_dir);
		}
	}

	/**
	 * returns an array of currently installed languages
	 *
	 * @return array
	 */
	function _get_installed_languages()
	{
		// this cannot be empty
		if((string) $_SESSION['site_settings']['installed_languages'] === '') {
			global $orbx_log;
			$orbx_log->ewrite('installed languages are unavailable. defaulting to '.ORBX_DEFAULT_LANGUAGE, __LINE__, __FUNCTION__);
		}

		// user-installed languages
		$installed = explode('|', $_SESSION['site_settings']['installed_languages']);
		// default language
		$installed[] = ORBX_DEFAULT_LANGUAGE;
		return array_unique($installed);
	}

	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	function get_ajax_id()
	{
		global $dbc;
		$r = $dbc->_db->query(sprintf('	SELECT 		username, pwd
										FROM 		'.TABLE_EDITORS.'
										WHERE 		(status != %s) AND
													(id = %s)',
													$dbc->_db->quote(ORBX_USER_STATUS_EX_USER),
													$dbc->_db->quote($_SESSION['user.a']['id'])));
		$a = $dbc->_db->fetch_assoc($r);

		$admin_username = sprintf('%u', adler32($a['username'] . ORBX_UNIQUE_ID));
		$admin_pwd = sprintf('%u', adler32($a['pwd'] . ORBX_UNIQUE_ID));

		return $admin_username.':'.$admin_pwd;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $request
	 * @return unknown
	 */
	function get_is_valid_ajax_id($request)
	{
		if(get_is_admin()) {
			return true;
		}

		if(strpos($request, ':') === false) {
			return false;
		}

		global $dbc;
		$r = $dbc->_db->query(sprintf('	SELECT 		username, pwd
										FROM 		'.TABLE_EDITORS.'
										WHERE 		(status != %s)',
													$dbc->_db->quote(ORBX_USER_STATUS_EX_USER)));
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$admin_username = sprintf('%u', adler32($a['username'] . ORBX_UNIQUE_ID));
			$admin_pwd = sprintf('%u', adler32($a['pwd'] . ORBX_UNIQUE_ID));

			if($admin_username . ':' . $admin_pwd === $request) {
				return true;
			}
			$a = $dbc->_db->fetch_assoc($r);
		}

		return false;
	}

	// used for mass upload path corrections
	function _sync_cache_prepend_path_mercury($file)
	{
		return DOC_ROOT . '/site/mercury/' . $file;
	}

	function _sync_cache_prepend_path_venus($file)
	{
		return DOC_ROOT . '/site/venus/' . $file;
	}

	function _sync_cache_prepend_path_venus_thumbs($file)
	{
		return DOC_ROOT . '/site/venus/thumbs/t-' . $file;
	}

	function _sync_cache_prepend_path_gfx($file)
	{
		return DOC_ROOT . '/site/gfx/' . $file;
	}

	function update_sync_cache_list($filepath)
	{
		// no module, exit. i didn't use $orbx_mod here because i'm not sure about it's global availability at all times
		if(!is_file(DOC_ROOT . '/orbicon/modules/synchronization/mod.ini')) {
			trigger_error('Synchronization module not available', E_USER_NOTICE);
			return false;
		}

		$list = DOC_ROOT . '/site/mercury/sync.cache.log';
		if(!is_file($list)) {
			create_empty_file($list);
			chmod_unlock($list);
		}

		// load current files
		$files = file_get_contents($list);
		$files = explode("\r\n", $files);
		// add new file(s)
		if(is_array($filepath)) {
			$files = array_merge($files, $filepath);
		}
		else if(is_string($filepath)) {
			$files[] = $filepath;
		}
		// remove duplicates
		$files = array_unique($files);
		$files = array_remove_empty($files);
		// convert to string
		$files = implode("\r\n", $files);

		$r = fopen($list, 'wb');
		fwrite($r, $files);
		fclose($r);
		return true;
	}

	/**
	 * return JavaScript window for delete confirm. special usage for onmousedown or onclick
	 *
	 */
	function delete_popup($what)
	{
		$what = str_sanitize($what, STR_SANITIZE_JAVASCRIPT);
		// these caused problems
		$what = str_replace('"', '', $what);

		return 'javascript:if(window.confirm(\''._L('delete').' &quot;' . $what . '&quot;?\')) {redirect(this.href);}';
	}

	/**
	 * Return URL depending on permalink settings
	 *
	 * Example:
	 * <code>
	 * echo url('http://orbitum.net/?en=modules', 'http://orbitum.net/en/modules');
	 * </code>
	 *
	 * @param string $url	Normal URL
	 * @param string $urlp	Permalink URL
	 */
	function url($url, $urlp)
	{
		if($_SESSION['site_settings']['main_site_permalinks']) {
			return $urlp;
		}

		return $url;
    }

    /**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param int $user_rid
	 * @return int
	 */
	function ban_user($user_rid, $ban = 1)
	{
		global $dbc;

		$q = sprintf('	UPDATE 		'.TABLE_REG_USERS.'
						SET			banned = %s
						WHERE 		(id=%s)',
						$dbc->_db->quote($ban),
						$dbc->_db->quote($user_rid));
		$dbc->_db->query($q);

		return $dbc->_db->affected_rows();
	}

?>