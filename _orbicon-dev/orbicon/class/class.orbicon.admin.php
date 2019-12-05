<?php
/**
 * Orbicon administration main class
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package SystemFE
 * @subpackage Core
 * @version 1.00
 * @link http://
 * @license http://
 * @since 2006-07-01
 *
 * @todo cleanup access bits
 */

// tabs
define('ORBX_ACCESS_CONTENT', 		1);
define('ORBX_ACCESS_DB', 			2);
define('ORBX_ACCESS_DYNAMIC', 		4);
define('ORBX_ACCESS_TOOLS', 		8);
define('ORBX_ACCESS_CRM', 			16);
define('ORBX_ACCESS_SETTINGS', 		32);
define('ORBX_ACCESS_SYSTEM', 		64);

// content
define('ORBX_ACCESS_COLUMNS', 		1);
define('ORBX_ACCESS_NEWSBOARD', 	2);
define('ORBX_ACCESS_NEWS', 			4);
define('ORBX_ACCESS_NEWS_SCHEME', 	8);
define('ORBX_ACCESS_NEWS_CAT', 		16);
define('ORBX_ACCESS_BANNERS', 		32);
define('ORBX_ACCESS_ADRBK', 		64);

// db
define('ORBX_ACCESS_MAGISTER', 		1);
define('ORBX_ACCESS_VENUS', 		2);
define('ORBX_ACCESS_MERCURY', 		4);

// dynamic
define('ORBX_ACCESS_RSS', 			1);
define('ORBX_ACCESS_POLLS', 		2);

// tools
define('ORBX_ACCESS_ZONES', 		1);
define('ORBX_ACCESS_GFXDIR', 		2);
define('ORBX_ACCESS_PHP', 			4);
define('ORBX_ACCESS_TPLWIZ', 		8);
define('ORBX_ACCESS_HTML',	 		16);
define('ORBX_ACCESS_CSS', 			32);
define('ORBX_ACCESS_JS', 			64);

// crm
define('ORBX_ACCESS_NEWSLETTER', 	1);
define('ORBX_ACCESS_PRING', 		2);
define('ORBX_ACCESS_EMAILS', 		4);
define('ORBX_ACCESS_CONTACT', 		8);

// settings
define('ORBX_ACCESS_INFO', 			1);
define('ORBX_ACCESS_ADMINS', 		2);
define('ORBX_ACCESS_STATS', 		4);
define('ORBX_ACCESS_PRIVILEGES', 	8);

// system
define('ORBX_ACCESS_ADV_SETTINGS', 	1);
define('ORBX_ACCESS_ROBOTS', 		2);
define('ORBX_ACCESS_AUDIT', 		4);
define('ORBX_ACCESS_SQL_DB', 		8);
define('ORBX_ACCESS_TG_EDIT', 		16);
define('ORBX_ACCESS_SYNC', 			32);
define('ORBX_ACCESS_UPDATE_CENTER', 64);

require_once DOC_ROOT . '/orbicon/class/class.orbicon.php';

class OrbiconX_Administration extends OrbiconX
{
	var $_priv_tabs;
	var $_priv_tools;
	var $_priv_modules;
	var $_priv_news;
	var $_priv_db;
	var $_priv_settings;
	var $_priv_adv;

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @return OrbiconX_Administration
	 */
	function OrbiconX_Administration()
	{
		$this->orbiconx();
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @param unknown_type $input
	 * @param unknown_type $id
	 * @return unknown
	 */
	function get_permalink_exists($input, $id = null)
	{
		// Bug WEB-9 START
		$org_input = $input;
		$input = trim($input);
		$leftover = str_replace($input, '', $org_input);
		$leftover = preg_replace('/\s+/', ' ', $leftover);
		if($leftover == ' ') {
			return 0;
		}
		// Bug WEB-9 END

		$permalink = get_permalink($input);

		// reserved names
		if($this->get_is_reserved_permalink($permalink)) {
			return 1;
		}

		global $dbc;
		$id_check_sql = ($id !== null) ? sprintf(' AND (id = %s)', $dbc->_db->quote($id)) : '';

		$q = sprintf('
						SELECT 	id
						FROM 	'.TABLE_COLUMNS.'
						WHERE 	(permalink=%s) AND
								(language=%s)
								'.$id_check_sql.'
						LIMIT 	1',
						$dbc->_db->quote($permalink), $dbc->_db->quote($this->ptr));
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		// news
		if(empty($a['id'])) {
			$q = sprintf('
							SELECT 	id
							FROM 	'.TABLE_NEWS.'
							WHERE 	(permalink=%s) AND
									(language =%s)
									'.$id_check_sql.'
							LIMIT 	1',
							$dbc->_db->quote($permalink), $dbc->_db->quote($this->ptr));
			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);
		}

		// forms
		if(empty($a['id'])) {
			$q = sprintf('
							SELECT 	id
							FROM 	'.TABLE_FORMS.'
							WHERE 	(permalink=%s) AND
									(language=%s)
									'.$id_check_sql.'
							LIMIT 	1',
							$dbc->_db->quote($permalink), $dbc->_db->quote($this->ptr));
			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);
		}

		// news categories
		if(empty($a['id'])) {
			$q = sprintf('	SELECT 	id
							FROM 	'.TABLE_NEWS_CAT.'
							WHERE 	(permalink=%s) AND
									(language=%s)
									'.$id_check_sql.'
							LIMIT 	1',
							$dbc->_db->quote($permalink), $dbc->_db->quote($this->ptr));
			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);
		}

		$a['id'] = (empty($a['id'])) ? 0 : $a['id'];
		$a['id'] = intval($a['id']);

		return $a['id'];
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @return unknown
	 */
	function __orbicon_get_language_links()
	{
		$links = array();
		$installed = _get_installed_languages();
		$_all_languages = array_merge($this->get_supported_languages_iso_639_1(), $this->get_supported_languages_iso_639_2());

		foreach($installed as $value) {

			$url_get = $_GET;
			$ln_get = $_GET[$this->ptr];
			unset($url_get[$this->ptr]);
			$url_get[$value] = $ln_get;
			$url_get = http_build_query($url_get);
			$url = ORBX_SITE_URL . '/?' . $url_get;

			$title = $_all_languages[$value]['en'];
			$links[] = sprintf('<a href="%s" title="%s">%s</a>', $url, $title, $title);
		}

		$links = implode('</li><li>', $links);

		return "<li>$links</li>";
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @param unknown_type $installed_lngs
	 * @return unknown
	 */
	function build_languages_menu($installed_lngs)
	{
		if(!is_array($installed_lngs)) {
			$installed_lngs = array($installed_lngs);
		}

		// iso-639-1
		$options = '<optgroup label="ISO-639-1">';
		$all_languages = $this->get_supported_languages_iso_639_1();

		foreach($all_languages as $key => $value) {
			$color = (is_file(DOC_ROOT . '/orbicon/languages/' . $key . '.php')) ? 'green' : 'red';
			$value['en'] = (strlen($value['en']) > 50) ? substr($value['en'], 0, 50) . '...' : $value['en'];

			if(!in_array($key, $installed_lngs)) {
				$options .= sprintf('<option style="color:%s;" value="%s">[%s] %s</option>', $color, $key, $key, $value['en']);
			}
			else {
				$selected .= sprintf('<option style="color:%s;" value="%s">[%s] %s</option>', $color, $key, $key, $value['en']);
			}
		}
		unset($all_languages, $key, $value);

		$options .= '</optgroup>';
		// iso-639-2
		$options .= '<optgroup label="ISO-639-2">';

		$all_languages = $this->get_supported_languages_iso_639_2();

		foreach($all_languages as $key => $value) {
			$color = (is_file(DOC_ROOT.'/orbicon/languages/' . $key . '.php')) ? 'green' : 'red';
			$value['en'] = (strlen($value['en']) > 50) ? substr($value['en'], 0, 50) . '...' : $value['en'];

			if(!in_array($key, $installed_lngs)) {
				$options .= sprintf('<option style="color:%s;" value="%s">[%s] %s</option>', $color, $key, $key, $value['en']);
			}
			else {
				$selected .= sprintf('<option style="color:%s;" value="%s">[%s] %s</option>', $color, $key, $key, $value['en']);
			}
		}

		unset($all_languages, $key, $value);

		$options .= '</optgroup>';

		return array($options, $selected);
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @return unknown
	 */
	function get_supported_languages_iso_639_1()
	{
		global $dbc;

		$q = '	SELECT 		*
				FROM 		'.TABLE_SYS_ISO639_1_CODES.'
				ORDER BY 	iso_code';

		$a = $dbc->_db->get_cache($q, true);
		if($a !== null) {
			return $a;
		}

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$iso_639_1_codes[$a['iso_code']] = array('en' => $a['en'], 'fr' => $a['fr']);
			$a = $dbc->_db->fetch_assoc($r);
		}

		$dbc->_db->free_result($r);

		$dbc->_db->put_cache($iso_639_1_codes, $q, true);

		return $iso_639_1_codes;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @return unknown
	 */
	function get_supported_languages_iso_639_2()
	{
		global $dbc;

		$q = '	SELECT 		*
				FROM 		'.TABLE_SYS_ISO639_2_CODES.'
				ORDER BY 	iso_code';

		$a = $dbc->_db->get_cache($q, true);
		if($a !== null) {
			return $a;
		}

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$iso_639_2_codes[$a['iso_code']] = array('en' => $a['en'], 'fr' => $a['fr']);
			$a = $dbc->_db->fetch_assoc($r);
		}

		$dbc->_db->free_result($r);

		$dbc->_db->put_cache($iso_639_2_codes, $q, true);

		return $iso_639_2_codes;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @return unknown
	 */
	function _get_reserved_permalinks()
	{
		return array(
			'orbicon_home',
			'column',
			'authorize',
			'exit',
			'orbicon',
			'orbicon.captcha',
			'sitemap',
			'attila',
			'orbicon.setup'
		);
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @param string $input
	 * @return bool
	 */
	function get_is_reserved_permalink($input)
	{
		$input = trim(strtolower($input));

		if(substr($input, 0, 4) == 'mod.') {
			return true;
		}

		return in_array($input, $this->_get_reserved_permalinks());
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 */
	function add_column()
	{
		$cols = explode(',', $_POST['new_column_name']);

		foreach ($cols as $new_column) {
			$new_column = trim($new_column);

			if(isset($_POST['add_column']) && !empty($new_column)) {
				global $dbc;

				$menu = (isset($_GET['menu'])) ? $_GET['menu'] : 'v';

				$permalink = get_permalink($new_column);

				$_check_permalink = $this->get_permalink_exists($permalink);

				if(!empty($_check_permalink)) {
					if($_POST['column_list'] != 'orbicon_new_parent') {
						$permalink = "$permalink-({$_POST['column_list']})";
					}
					else {
						echo '<script type="text/javascript">window.alert(\''._L('permalink_conflict').'\');</script>';
						$permalink = $permalink . adler32(time() . $permalink);
					}
				}

				$new_column = utf8_html_entities($new_column);

				if($_POST['column_list'] == 'orbicon_new_parent') {
					$q = sprintf('	INSERT INTO 	'.TABLE_COLUMNS.'
													(title, content,
													permalink, menu_name,
													language)
									VALUES 			(%s, %s,
													%s, %s,
													%s)',
					$dbc->_db->quote($new_column), $dbc->_db->quote($permalink),
					$dbc->_db->quote($permalink), $dbc->_db->quote($menu),
					$dbc->_db->quote($this->ptr));
				}
				else {
					$q = sprintf('	INSERT INTO 	'.TABLE_COLUMNS.'
													(title, content,
													parent, permalink,
													menu_name, language)
									VALUES 			(%s, %s,
													%s, %s,
													%s, %s)',
					$dbc->_db->quote($new_column), $dbc->_db->quote($permalink),
					$dbc->_db->quote($_POST['column_list']), $dbc->_db->quote($permalink),
					$dbc->_db->quote($menu), $dbc->_db->quote($this->ptr));
				}
				$dbc->_db->query($q);
			}
		}
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @return unknown
	 */
	function save_navigation_sort_order()
	{
		// check ajax id
		$valid_request = get_is_valid_ajax_id($_REQUEST['credentials']);

		if($valid_request !== true) {
			trigger_error('Invalid AJAX ID', E_USER_WARNING);
			return false;
		}

		ignore_user_abort(true);
		global $dbc;
		$i = 0;

		if(!empty($_REQUEST['navigation_list'])) {
			foreach($_REQUEST['navigation_list'] as $value) {
				$q = sprintf('	UPDATE 		%s
								SET 		sort=%s
								WHERE 		(permalink=%s) AND
											(language = %s)', TABLE_COLUMNS, $i, $dbc->_db->quote(str_replace('sort_', '', $value)), $dbc->_db->quote($this->ptr));
				$r = $dbc->_db->query($q);

				if($r) {
					$i++;
				}
			}
		}

		ignore_user_abort(false);
		return true;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @param unknown_type $column
	 * @return unknown
	 */
	function delete_column($column)
	{
		if($column == '') {
			trigger_error('delete_column() expects parameter 1 to be non-empty', E_USER_WARNING);
			return false;
		}

		$a = sql_assoc('	SELECT id FROM '.TABLE_COLUMNS.'
							WHERE 	(permalink=%s) AND
									(language=%s)
							LIMIT 	1', array($column, $this->ptr));

		return sql_res('	DELETE
							FROM 	'.TABLE_COLUMNS.'
							WHERE 	(id = %s)
							LIMIT 	1', $a['id']);

		/*$dbc->_db->query(sprintf('	DELETE
									FROM 	'.TABLE_COLUMNS.'
									WHERE 	(parent=%s) AND
											(language=%s)',
		$dbc->_db->quote($column), $dbc->_db->quote($this->ptr)));*/
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @param unknown_type $flag
	 * @return unknown
	 */
	function get_can_access_tab($flag)
	{
		if((int) $_SESSION['user.a']['status'] === ORBX_USER_STATUS_SYSADMIN) {
			return true;
		}

		$group = $this->load_privilege($_SESSION['user.a']['status']);
		return (bool) ($this->get_is_privilege_set($group['tabs'], $flag) === true);
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @todo cleanup this function
	 * @param string $section
	 * @param string $module
	 * @return bool
	 */
	function get_can_access_section($section, $module = null)
	{
		// whitelist
		if(($section === '') || 			// desktop
		($section === 'helpdesk') ||		// helpdesk
		($section === 'about') ||			// about
		($section === 'intro')) {			// intro
			return true;
		}

		if((int) $_SESSION['user.a']['status'] === ORBX_USER_STATUS_SYSADMIN) {
			return true;
		}

		$group = $this->load_privilege($_SESSION['user.a']['status']);

		if($section == 'mod') {
			$accessible_mods = explode('|', $group['modules']);

			return (bool) (in_array(trim($module), $accessible_mods));
		}

		$flag = 0;

		switch($section) {

			case 'columns': $flag = ORBX_ACCESS_COLUMNS; $type = 'content'; break;
			case 'news':  $flag = ORBX_ACCESS_NEWS; $type = 'content'; break;
			case 'news_grid':  $flag = ORBX_ACCESS_NEWS_SCHEME; $type = 'content'; break;
			case 'news_category': $flag = ORBX_ACCESS_NEWS_CAT; $type = 'content'; break;
			case 'banner': $flag = ORBX_ACCESS_BANNERS; $type = 'content'; break;
			case 'newsboard': $flag = ORBX_ACCESS_NEWSBOARD; $type = 'content'; break;

			case 'magister': $flag = ORBX_ACCESS_MAGISTER; $type = 'db'; break;
			case 'venus': $flag = ORBX_ACCESS_VENUS; $type = 'db'; break;
			case 'mercury': $flag = ORBX_ACCESS_MERCURY; $type = 'db'; break;

			case 'polls': $flag = ORBX_ACCESS_POLLS; $type = 'dynamic'; break;
			case 'rss': $flag = ORBX_ACCESS_RSS; $type = 'dynamic'; break;

			case 'gfxdir': $flag = ORBX_ACCESS_GFXDIR; $type='tools'; break;
			case 'html': $flag = ORBX_ACCESS_HTML; $type = 'tools'; break;
			case 'css': $flag = ORBX_ACCESS_CSS; $type = 'tools'; break;
			case 'javascript': $flag = ORBX_ACCESS_JS; $type = 'tools'; break;
			case 'php': $flag = ORBX_ACCESS_PHP; $type = 'tools'; break;
			case 'zones': $flag = ORBX_ACCESS_ZONES; $type = 'tools'; break;
			case 'tplwiz': $flag = ORBX_ACCESS_TPLWIZ; $type = 'tools'; break;

			case 'peoplering': $flag = ORBX_ACCESS_PRING; $type = 'crm'; break;
			case 'contact': $flag = ORBX_ACCESS_CONTACT; $type = 'crm'; break;
			case 'newsletter': $flag = ORBX_ACCESS_NEWSLETTER; $type = 'crm'; break;
			case 'adrbk': $flag = ORBX_ACCESS_EMAILS; $type = 'crm'; break;

			case 'www': $flag = ORBX_ACCESS_INFO; $type = 'settings'; break;
			case 'editors': $flag = ORBX_ACCESS_ADMINS; $type = 'settings'; break;
			case 'stats': $flag = ORBX_ACCESS_STATS; $type = 'settings'; break;
			case 'privileges':  $flag = ORBX_ACCESS_PRIVILEGES; $type = 'settings'; break;

			case 'sync': $flag = ORBX_ACCESS_SYNC; $type = 'system'; break;
			case 'robotstxt': $flag = ORBX_ACCESS_ROBOTS; $type = 'system'; break;
			case 'loginhistory': $flag = ORBX_ACCESS_AUDIT; $type = 'system'; break;
			case 'sql_db': $flag = ORBX_ACCESS_SQL_DB; $type = 'system'; break;
			case 'tg_editor': $flag = ORBX_ACCESS_TG_EDIT; $type='system'; break;
			case 'advanced': $flag = ORBX_ACCESS_ADV_SETTINGS; $type='system'; break;
			case 'update': $flag = ORBX_ACCESS_UPDATE_CENTER; $type='system'; break;

			default: $flag = null; break;
		}

		if(empty($flag)) {
			return false;
		}

		return (bool) ($this->get_is_privilege_set($group[$type], $flag) === true);
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @param unknown_type $bit
	 * @param unknown_type $flag
	 * @return unknown
	 */
	function get_is_privilege_set($bit, $flag)
	{
		if($bit & $flag) {
			return true;
		}
		return false;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @param unknown_type $bit
	 * @param unknown_type $flag
	 * @return unknown
	 */
	function check_privilege_option($bit, $flag)
	{
		if($this->get_is_privilege_set($bit, $flag) === true) {
			return 'checked="checked"';
		}
		return '';
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @return unknown
	 */
	function get_privileges_array()
	{
		global $dbc;
		$q = '	SELECT 		*
				FROM 		'.TABLE_PRIVILEGES.'
				ORDER BY 	permalink';
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$adrbks[] = $a;
			$a = $dbc->_db->fetch_assoc($r);
		}

		return $adrbks;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 */
	function delete_privilege()
	{
		if(isset($_GET['del'])) {
			global $dbc, $orbicon_x;
			$q = sprintf('	DELETE
							FROM 	'.TABLE_PRIVILEGES.'
							WHERE 	(permalink=%s)
							LIMIT 	1', $dbc->_db->quote($_GET['del']));
			$dbc->_db->query($q);

			redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/privileges');
		}
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @param unknown_type $privilege
	 * @return unknown
	 */
	function load_privilege($privilege = '')
	{
		$privilege = ($privilege === '') ? $_GET['edit'] : $privilege;

		if($privilege !== '') {
			global $dbc;
			$q = sprintf('	SELECT 		*
							FROM 		'.TABLE_PRIVILEGES.'
							WHERE 		(permalink=%s)
							LIMIT 		1', $dbc->_db->quote($privilege));
			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);

			return $a;
		}

		trigger_error('load_privilege() expects parameter 1 to be non-empty', E_USER_WARNING);
		return false;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @return unknown
	 */
	function save_privilege()
	{
		if(isset($_POST['save_privilege'])) {

			global $dbc, $orbicon_x;

			$title = trim($_POST['privilege_title']);

			if(!empty($_POST['orbicon_list_selected'])) {
				$_POST['orbicon_list_selected'] = array_remove_empty($_POST['orbicon_list_selected']);
				$modules = implode('|', $_POST['orbicon_list_selected']);
			}

			// we don't allow editing of internal system groups
			$_sys_group = (int) $_GET['edit'];

			if(empty($title) ||
			($_sys_group === ORBX_USER_STATUS_EX_USER) ||
			($_sys_group === ORBX_USER_STATUS_USER) ||
			($_sys_group === ORBX_USER_STATUS_ADMIN) ||
			($_sys_group === ORBX_USER_STATUS_SYSADMIN)) {
				return false;
			}

			$permalink = get_permalink($title);
			$title = utf8_html_entities($title);

			$this->_priv_tabs = ((int) $_POST['flag_tab_content'] | (int) $_POST['flag_tab_db'] | (int) $_POST['flag_tab_dynamic'] | (int) $_POST['flag_tab_tools'] | (int) $_POST['flag_tab_crm'] | (int) $_POST['flag_tab_sett'] | (int) $_POST['flag_tab_adv']);

			$this->_priv_content = ((int) $_POST['flag_col'] | (int) $_POST['flag_newsboard'] | (int) $_POST['flag_news'] | (int) $_POST['flag_news_scheme'] | (int) $_POST['flag_news_cat'] | (int) $_POST['flag_banners']);

			$this->_priv_db = ((int) $_POST['flag_magister'] | (int) $_POST['flag_venus'] | (int) $_POST['flag_mercury']);

			$this->_priv_dynamic = ((int) $_POST['flag_poll'] | (int) $_POST['flag_rss']);

			$this->_priv_tools = ((int) $_POST['flag_gfxdir'] | (int) $_POST['flag_tplwiz'] | (int) $_POST['flag_zone'] | (int) $_POST['flag_html'] | (int) $_POST['flag_css'] | (int) $_POST['flag_js'] | (int) $_POST['flag_php']);

			$this->_priv_crm = ((int) $_POST['flag_nwsltr'] | (int) $_POST['flag_pring'] | (int) $_POST['flag_contacts'] | (int) $_POST['flag_emails']);

			$this->_priv_settings = ((int) $_POST['flag_info'] | (int) $_POST['flag_admins'] | (int) $_POST['flag_stats'] | (int) $_POST['flag_privileges']);

			$this->_priv_system = ((int) $_POST['flag_adv_settings'] | (int) $_POST['flag_sync'] | (int) $_POST['flag_updatec'] | (int) $_POST['flag_robots'] | (int) $_POST['flag_audit'] | (int) $_POST['flag_sql'] | (int) $_POST['flag_tg']);

			if(!isset($_GET['edit'])) {
				$q = sprintf('	INSERT INTO 	'.TABLE_PRIVILEGES.'
												(group_name, permalink,
												tabs, content,
												db, dynamic,
												tools, crm,
												settings, system,
												modules)
								VALUES 			(%s, %s,
												%s, %s,
												%s, %s,
												%s, %s,
												%s, %s,
												%s)',
									$dbc->_db->quote($title), $dbc->_db->quote($permalink),
									$dbc->_db->quote($this->_priv_tabs), $dbc->_db->quote($this->_priv_content),
									$dbc->_db->quote($this->_priv_db), $dbc->_db->quote($this->_priv_dynamic),
									$dbc->_db->quote($this->_priv_tools), $dbc->_db->quote($this->_priv_crm),
									$dbc->_db->quote($this->_priv_settings), $dbc->_db->quote($this->_priv_system),
									$dbc->_db->quote($modules));
			}
			else {
				$q = sprintf('	UPDATE '.TABLE_PRIVILEGES.'
								SET group_name = %s, permalink = %s,
								tabs = %s, content = %s,
								db = %s, dynamic = %s,
								tools = %s, crm = %s,
								settings = %s, system = %s,
								modules = %s
								WHERE (permalink = %s)
								LIMIT 1',
								$dbc->_db->quote($title), $dbc->_db->quote($permalink),
								$dbc->_db->quote($this->_priv_tabs), $dbc->_db->quote($this->_priv_content),
								$dbc->_db->quote($this->_priv_db), $dbc->_db->quote($this->_priv_dynamic),
								$dbc->_db->quote($this->_priv_tools), $dbc->_db->quote($this->_priv_crm),
								$dbc->_db->quote($this->_priv_settings), $dbc->_db->quote($this->_priv_system),
								$dbc->_db->quote($modules),
								$dbc->_db->quote($_GET['edit']));
			}

			$dbc->_db->query($q);

			redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/privileges&edit=' . urlencode($permalink));
		}
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 */
	function save_desktop()
	{
		global $dbc;
		$data = $_REQUEST['data'];

		$data = explode('#', $data);

		foreach($data as $icon) {

			$icon = explode(':', $icon);
			list($icon_id, $icon_x, $icon_y, $icon_owner) = $icon;
			unset($icon);

			// we should stay within the visual grid
			$icon_x = (intval($icon_x) < 75) ? '75px' : $icon_x;
			$icon_y = (intval($icon_y) < 0) ? '0px' : $icon_y;

			$q = sprintf('	UPDATE 		'.TABLE_DESKTOP.'
							SET 		x=%s, y=%s
							WHERE 		(icon_id=%s) AND
										(owner_id=%s)
							LIMIT 		1',
							$dbc->_db->quote($icon_x), $dbc->_db->quote($icon_y),
							$dbc->_db->quote($icon_id), $dbc->_db->quote($icon_owner));
			$dbc->_db->query($q);
		}
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @param unknown_type $id
	 * @param unknown_type $owner_id
	 */
	function add_desktop_icon($id, $owner_id)
	{
		global $dbc;

		// check for duplicates
		$q = sprintf('	SELECT 		id
						FROM 		'.TABLE_DESKTOP.'
						WHERE 		(icon_id = %s) AND
									(owner_id = %s)
						LIMIT 		1',
						$dbc->_db->quote($id), $dbc->_db->quote($owner_id)
						);

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		// doesn't exist
		if(empty($a['id'])) {
			$q = sprintf('	INSERT INTO 	'.TABLE_DESKTOP.'
											(icon_id, x,
											y, owner_id)
							VALUES 			(%s, %s,
											%s, %s)',
			$dbc->_db->quote($id), $dbc->_db->quote('75px'),
			$dbc->_db->quote('0px'), $dbc->_db->quote($owner_id));

			$dbc->_db->query($q);
		}
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @param unknown_type $icon_id
	 * @return unknown
	 */
	function print_icon_manager($icon_id)
	{
		if($icon_id == 'about') {
			return '';
		}

		global $dbc, $orbicon_x, $orbx_mod;
		$q = sprintf('	SELECT 	id
						FROM 	'.TABLE_DESKTOP.'
						WHERE 	(icon_id = %s) AND
								(owner_id = %s)
						LIMIT 	1',
						$dbc->_db->quote($icon_id), $dbc->_db->quote($_SESSION['user.a']['id']));
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		include DOC_ROOT . '/orbicon/controler/admin.desktop.icons.php';
		$my_icon = $orbx_desktop_icons[$icon_id];

		if(empty($my_icon)) {
			$orbx_desktop_icons = array_merge($orbx_desktop_icons, $orbx_mod->get_module_icon($icon_id, ORBX_MOD_ICON_MEDIUM));
			//$my_icon['page_gfx'] = $orbx_desktop_icons[$icon_id];
			$my_icon_path = DOC_ROOT . '/orbicon/modules/'.$icon_id.'/gfx/' . basename($my_icon['page_gfx']);
		}
		else {
			$my_icon_path = DOC_ROOT . '/orbicon/gfx/desktop_icons/' . $my_icon['page_gfx'];
			//$my_icon['page_gfx'] = ORBX_SITE_URL . '/orbicon/gfx/desktop_icons/' . $my_icon['page_gfx'];
		}

		// there's no point here
		if(!is_file($my_icon_path)) {
			return '';
		}

		$icon_status = (empty($a['id'])) ? 'action_stop.gif' : 'icon_accept.gif';
		$icon_action = (empty($a['id'])) ? 'add' : 'remove';

		/*return '<div id="orbx_icon_container">
					<div id="orbx_icon_manager" style="background-image:url(\'' . $my_icon['page_gfx']. '\');">
						<a href="javascript:void(null);" onclick="javascript:orbx_icon_handler(\''.$icon_id.'\', '.intval($_SESSION['user.a']['id']).', \''.$icon_action.'\');"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/desktop_icons/' . $icon_status . '" />
						</a>
					</div>
				</div>';*/
		return '<div id="orbx_icon_container">
					<a href="javascript:void(null);" onclick="javascript:orbx_icon_handler(\''.$icon_id.'\', '.intval($_SESSION['user.a']['id']).', \''.$icon_action.'\');"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/desktop_icons/' . $icon_status . '" /></a>
				</div>';

	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @param unknown_type $id
	 * @param unknown_type $owner_id
	 */
	function remove_desktop_icon($id, $owner_id)
	{
		global $dbc;

		$q = sprintf('	DELETE FROM		'.TABLE_DESKTOP.'
						WHERE 			(icon_id = %s) AND
										(owner_id = %s)
						LIMIT			1',
						$dbc->_db->quote($id),
						$dbc->_db->quote($owner_id));
		$dbc->_db->query($q);
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @return unknown
	 */
	function get_current_wallpaper()
	{
		global $dbc;
		// select current wallpaper
		$q = sprintf('
					SELECT 	id, image
					FROM 	'.TABLE_DESKTOP_WALLPAPER.'
					WHERE 	(owner_id = %s)
					LIMIT 	1',
					$dbc->_db->quote($_SESSION['user.a']['id']));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);
		return $a;
	}

	/**
	 * deletes user's wallpaper
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 */
	function reset_wallpaper()
	{
		global $dbc;

		$a = $this->get_current_wallpaper();

		if(!empty($a['id'])) {

			$image = DOC_ROOT . '/site/venus/' . $a['image'];

			if(is_file($image)) {
				unlink($image);
			}

			$q = sprintf('	DELETE FROM		'.TABLE_DESKTOP_WALLPAPER.'
								WHERE 			(owner_id = %s)
								LIMIT			1',
								$dbc->_db->quote($_SESSION['user.a']['id']));
			$dbc->_db->query($q);
		}
	}

	/**
	 * handles addition of new wallpaper for user
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 */
	function add_wallpaper()
	{
		if(isset($_POST['add_wallpaper'])) {

			// security checks
			if(
			// non-empty
			($_FILES['wallpaper']['name'] != '') &&
			// really uploaded?
			(is_uploaded_file($_FILES['wallpaper']['tmp_name'])) &&
			// same filesize
			(filesize($_FILES['wallpaper']['tmp_name']) == $_FILES['wallpaper']['size']) &&
			// no errors
			($_FILES['wallpaper']['error'] == UPLOAD_ERR_OK)
			) {

				global $dbc;

				// delete old wallpaper
				$this->reset_wallpaper();

				$image_filename = basename($_FILES['wallpaper']['name']);

				// seed for PHP < 4.2.0
				srand((float) microtime() * 10000000);

				// find available filename
				$image = DOC_ROOT . '/site/venus/' . rand(1, 999) . $image_filename;

				while(is_file($image)) {
					$image = DOC_ROOT . '/site/venus/' . rand(1, 999) . $image_filename;
				}

				$move = move_uploaded_file($_FILES['wallpaper']['tmp_name'], $image);
				chmod_lock($image);

				// move uploaded image
				if($move) {
					// insert into db
					$q = sprintf('	INSERT INTO 	'.TABLE_DESKTOP_WALLPAPER.'
													(image, owner_id)
									VALUES 			(%s, %s)',
					$dbc->_db->quote(basename($image)), $dbc->_db->quote($_SESSION['user.a']['id']));
					$dbc->_db->query($q);
				}
			}
		}
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @return unknown
	 */
	function print_desktop()
	{
		$desktop_js = '';
		$desktop_icons = '';
		global $dbc, $orbx_mod;

		if((int) $_SESSION['user.a']['first_login'] === 0) {
			$this->add_desktop_icon('wwwroot', $_SESSION['user.a']['id']);

			$q = sprintf('	UPDATE 		'.TABLE_EDITORS.'
							SET 		first_login=1
							WHERE 		(id=%s)
							LIMIT 		1',
							$dbc->_db->quote($_SESSION['user.a']['id']));
			$dbc->_db->query($q);
			// must be set here!
			$_SESSION['user.a']['first_login'] = 1;

			// open tutorial
			redirect(ORBX_SITE_URL . '/?' . $this->ptr . '=orbicon/intro');
		}

		$q = sprintf('	SELECT 	*
						FROM 	'.TABLE_DESKTOP.'
						WHERE 	(owner_id = %s)',
						$dbc->_db->quote($_SESSION['user.a']['id']));
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		include DOC_ROOT . '/orbicon/controler/admin.desktop.icons.php';

		while($a) {

			if(empty($orbx_desktop_icons[$a['icon_id']])) {
				$mod_ico = $orbx_mod->get_module_icon($a['icon_id'], ORBX_MOD_ICON_BIG);
				$my_icon['gfx'] = $mod_ico[$a['icon_id']];
				$my_icon['title'] = _L($a['icon_id']);
				$my_icon['href'] = ORBX_SITE_URL.'/?'.$this->ptr.'=orbicon/mod/' . $a['icon_id'];
			}
			else {
				$my_icon = $orbx_desktop_icons[$a['icon_id']];
				$my_icon['gfx'] = ORBX_SITE_URL.'/orbicon/gfx/desktop_icons/'.$my_icon['gfx'];
			}

			$desktop_icons .= '
			<a href="'.$my_icon['href'].'" onclick="javascript: return false;" ondblclick="javascript: redirect(this.href);" >
				<div id="'.$a['icon_id'].'" class="desktop_icon" onmouseover="javascript: YAHOO.util.Dom.setStyle(this, \'opacity\', 1);" onmouseout="javascript:YAHOO.util.Dom.setStyle(this, \'opacity\', .75);" style="top:'.$a['x'].'; left:'.$a['y'].';position:absolute;background-image:url('.$my_icon['gfx'].');">
					<p>'.$my_icon['title'].'</p>
				</div>
			</a>';

			$up_max = intval($a['x']) - 75;

			// replace so we don't invalidate javascript
			$js_icon_id = str_replace(array('.', '-'), '_', $a['icon_id']);

			$desktop_js .= '
var dd_'.$js_icon_id.' = new YAHOO.util.DD("'.$a['icon_id'].'");
dd_'.$js_icon_id.'.setXConstraint(1000, 1000, 25);
dd_'.$js_icon_id.'.setYConstraint('.$up_max.', 1000, 25);
dd_'.$js_icon_id.'.endDrag = save_desktop;';

			$a = $dbc->_db->fetch_assoc($r);
		}

		return array('icons' => $desktop_icons, 'js' => $desktop_js);
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @param string $type
	 */
	function export_language_file($type = 'txt')
	{
		if(isset($_POST['export'])) {

			global $orbx_mod;

			include DOC_ROOT . '/orbicon/languages/' . $_POST['export_file'] . '.php';

			$translation_template = '';

			$ln = array_merge($orbx_mod->get_translations(), $ln, $orbx_ln);

			// TXT
			if($type == 'txt') {
				foreach($ln as $key => $value) {
					$translation_template .= strtoupper($key) . ' : ' . str_repeat(' ', (35 - strlen($key))) . $value . "\r\n";
				}

				$txt = DOC_ROOT . '/site/mercury/' . $_POST['export_file'] . '.txt';
			}
			// CSV
			/*else if($type == 'csv') {
				$translation_template .= 'ID,TEXT' . "\r\n";

				foreach($ln as $key => $value) {
					$translation_template .= strtoupper($key) . ',"' . $value . "\"\r\n";
				}

				$txt = DOC_ROOT . '/site/mercury/' . $_POST['export_file'] . '.csv';
			}*/

			create_empty_file($txt);

			$r = fopen($txt, 'wb');
			/* Set a 64k buffer. */
			if(function_exists('stream_set_write_buffer')) {
				stream_set_write_buffer($r, 65535);
			}
			fwrite($r, $translation_template);
			fclose($r);

			redirect(ORBX_SITE_URL.'/orbicon/controler/force_dl.php?file=' . base64_encode(basename($txt)));
		}
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 */
	function import_language_file()
	{
		if(isset($_POST['import'])) {

			// security checks
			if(
			// non-empty
			($_FILES['import_file']['name'] != '') &&
			// really uploaded?
			(is_uploaded_file($_FILES['import_file']['tmp_name'])) &&
			// same filesize
			(filesize($_FILES['import_file']['tmp_name']) == $_FILES['import_file']['size']) &&
			// no errors
			($_FILES['import_file']['error'] == UPLOAD_ERR_OK)
			) {

				$translation = file($_FILES['import_file']['tmp_name']);
				$filename = DOC_ROOT . '/orbicon/languages/' . $_POST['import_lng'] . '.php';

				create_empty_file($filename);

				$array_name = '$ln';
				$trans = '';

				foreach($translation as $line) {
					$line = trim($line);

					if(!empty($line)) {
						$line = explode(':', $line);
						$key = trim(strtolower($line[0]));
						$value = trim($line[1]);

						if($key == strtolower('**DO_NOT_EDIT_OR_REMOVE_THIS_LINE**')) {
							$array_name = '$orbx_ln';
						}

						$trans .= "\t" . $array_name . '[\'' . $key . '\']=\'' . addslashes($value) . "';\n";
					}
				}

				$trans = '<?php' . "\n" . $trans . '?>';

				$r = fopen($filename, 'wb');
				/* Set a 64k buffer. */
				if(function_exists('stream_set_write_buffer')) {
					stream_set_write_buffer($r, 65535);
				}
				fwrite($r, $trans);
				fclose($r);
			}
		}
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @return unknown
	 */
	function rewrite_db_uris()
	{
		global $dbc, $orbx_log;
		$orbx_log->dwrite('starting rewrite of site URIs', __LINE__, __FUNCTION__);

		// receiver hosts should avoid this

		$q = sprintf('	SELECT 	value
						FROM  	'.TABLE_SETTINGS.'
						WHERE 	(setting = %s)',
						$dbc->_db->quote('syncm_type'));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		if($a['value'] == SYNC_MANAGER_TYPE_RECEIVER) {
			$orbx_log->dwrite('skipping rewrite of site URIs. found receiver host', __LINE__, __FUNCTION__);
			return false;
		}

		// search for links matching pattern : href="OLD_URL and replace it with : href="NEW_URL

		$q = sprintf('	SELECT 	id, content
						FROM 	'.MAGISTER_CONTENTS.'
						WHERE 	((content LIKE %s) OR
								(content LIKE %s))
						LIMIT 	1',
						$dbc->_db->quote('%href="' . ORBX_INTEGRITY_URI . '%'),
						$dbc->_db->quote('%src="' . ORBX_INTEGRITY_URI . '%'));
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$orbx_log->dwrite('updating content links for #id ' . $a['id'], __LINE__, __FUNCTION__);
			// update links
			$a['content'] = str_replace(
				array(	'href="' . ORBX_INTEGRITY_URI,		// normal links
						'src="' . ORBX_INTEGRITY_URI,		// javascript and embed
						'value="' . ORBX_INTEGRITY_URI,		// flash
						'data="' . ORBX_INTEGRITY_URI),		// object

				array(	'href="' . ORBX_SITE_URL,
						'src="' . ORBX_SITE_URL,
						'value="' . ORBX_SITE_URL,
						'data="' . ORBX_SITE_URL),

						$a['content']
			);

			$_q = sprintf('	UPDATE 		'.MAGISTER_CONTENTS.'
							SET 		content=%s
							WHERE 		(id=%s)
							LIMIT 		1',
							$dbc->_db->quote($a['content']), $dbc->_db->quote($a['id']));
			$dbc->_db->query($_q);

			$a = $dbc->_db->fetch_assoc($r);
		}

		// write down new sys config
		$orbx_log->dwrite('finished updating content', __LINE__, __FUNCTION__);
		$orbx_log->dwrite('updating system config', __LINE__, __FUNCTION__);
		$sys_config = file_get_contents(ORBX_SYS_CONFIG);
		$sys_config = str_replace(
						'define(\'ORBX_INTEGRITY_URI\', \''.ORBX_INTEGRITY_URI.'\');',
						'define(\'ORBX_INTEGRITY_URI\', \''.str_replace('www.', '', ORBX_SITE_URL).'\');',
						$sys_config);

		// set to writable
		if(!chmod_unlock(ORBX_SYS_CONFIG)) {
			return false;
		}

		// lock
		if(!lock(ORBX_SYS_CONFIG)) {
			return false;
		}

		// write and close file
		$r = fopen(ORBX_SYS_CONFIG, 'wb');

		if(!$r) {
			$orbx_log->dwrite('could not open ' . ORBX_SYS_CONFIG, __LINE__, __FUNCTION__);
		}

		/* Set a 64k buffer. */
		if(function_exists('stream_set_write_buffer')) {
			stream_set_write_buffer($r, 65535);
		}

		if(fwrite($r, $sys_config)) {
			fclose($r);
			// set to read only
			if(!chmod_lock(ORBX_SYS_CONFIG)) {
				// unlock
				unlock(ORBX_SYS_CONFIG);
				return false;
			}
			unset($sys_config, $r);
		}
		// unlock
		unlock(ORBX_SYS_CONFIG);

		// adjust logs.ini
		// $contents = file_get_contents(LOGS_INI_PATH);
		/**
		 * @todo path="/var/www/html/orbicon-x/site/mercury";
		 */

		$orbx_log->dwrite('finished rewrite of site URIs', __LINE__, __FUNCTION__);
		return true;
	}

	/**
	 * Return editor's last location
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @param int $editor_id
	 */
	/*function get_last_location($editor_id)
	{
		global $dbc;

		$q = sprintf('	SELECT		last_location
						FROM 		'.TABLE_EDITORS.'
						WHERE 		(id=%s)
						LIMIT 		1',
						$dbc->_db->quote($editor_id));
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);
		return $a['last_location'];
	}*/


	/**
	 * Format list of editors present on location url
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @param string $url
	 * @return string
	 */
	function print_whos_here($url)
	{
		global $dbc;

		$q = sprintf('	SELECT		id, first_name, last_name
						FROM 		'.TABLE_EDITORS.'
						WHERE 		(last_location=%s)',
						$dbc->_db->quote(http_build_query($url)));
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);
		$list = array();

		while($a) {

			$list[] = '<a href="'.ORBX_SITE_URL.'/?'.$this->ptr.'=orbicon/editors&amp;action=edit&amp;id='.$a['id'].'">'.$a['first_name'].' '.$a['last_name'].'</a>';

			$a = $dbc->_db->fetch_assoc($r);
		}

		$list = implode(', ', $list);
		return $list;
	}
}

?>