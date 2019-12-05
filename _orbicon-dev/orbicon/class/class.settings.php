<?php
/**
 * Settings class
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @subpackage Settings
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-07-01
 * @todo standardize similar function calls to improve performance and reduce the code
 */

/**
 * display PDF links bit
 *
 */
define('ORBX_CONTENT_PROP_ALT_PDF',			1);
/**
 * display text links bit
 *
 */
define('ORBX_CONTENT_PROP_ALT_TXT',			2);
/**
 * display simple HTML links bit
 *
 */
define('ORBX_CONTENT_PROP_ALT_HTML',		4);
/**
 * display print links bit
 *
 */
define('ORBX_CONTENT_PROP_PRINT_LINK',		8);

class Settings
{
	function save_site_settings()
	{
		if(isset($_POST['save_settings'])) {
			global $dbc, $orbicon_x, $orbx_mod;

			// these setting are language dependant and have additional SQL
			$lang_specific_setting = $this->get_lng_spec_setting();

			if(!empty($_POST['orbicon_list_selected'])) {
				$_POST['orbicon_list_selected'] = array_remove_empty($_POST['orbicon_list_selected']);
				$languages = implode('|', $_POST['orbicon_list_selected']);
			}

			$settings = array(
				'main_site_title' => utf8_html_entities($_POST['main_site_title']),
				'main_site_owner' => utf8_html_entities($_POST['main_site_owner']),
				'main_site_email' => $_POST['main_site_email'],
				'main_site_desc' => utf8_html_entities($_POST['main_site_desc']),
				'main_site_keywords' => utf8_html_entities(keyword_generator($_POST['main_site_keywords'], false)),
				'installed_languages' => $languages,
				'main_site_def_lng' => $_POST['main_site_def_lng'],
				'main_site_metatags' => $_POST['main_site_metatags'],
				'date_format' => $_SESSION['site_settings']['date_format']
				);

			foreach($settings as $key => $value) {
				// add extra SQL for language dependant settings
				$language_sql = (in_array($key, $lang_specific_setting)) ? ' AND (language = '.$dbc->_db->quote($orbicon_x->ptr).')' : '';
				$q = sprintf('	UPDATE 	'.TABLE_SETTINGS.'
								SET 	value = %s
								WHERE 	(setting = %s)'.$language_sql,
											$dbc->_db->quote($value), $dbc->_db->quote($key));

				$dbc->_db->query($q);
				// check if update status
				$q_c = sprintf('	SELECT 	value
									FROM 	'.TABLE_SETTINGS.'
									WHERE 	(setting = %s)'.$language_sql.'
									LIMIT 	1', $dbc->_db->quote($key));
				$r_c = $dbc->_db->query($q_c);
				$a_c = $dbc->_db->fetch_assoc($r_c);

				// UPDATE failed, try with INSERT
				if(($a_c['value'] === null) && ($language_sql != '')) {
					$q_new = sprintf('INSERT INTO '.TABLE_SETTINGS.' (value, setting, language) VALUES (%s, %s, %s)',
													$dbc->_db->quote($value), $dbc->_db->quote($key), $dbc->_db->quote($orbicon_x->ptr));
					$dbc->_db->query($q_new);
				}
			}

			// alter db tables if we changed default language
			// keep this list updated
			if($_SESSION['site_settings']['main_site_def_lng'] != $settings['main_site_def_lng']) {
				$alter_tables = array(
					MAGISTER_TITLES,
					MAGISTER_CONTENTS,
					MAGISTER_CATEGORIES,
					TABLE_COLUMNS,
					TABLE_SETTINGS,
					TABLE_ZONES
					);

				if($orbx_mod->validate_module('news')) {
					$alter_tables[] = TABLE_NEWS;
					$alter_tables[] = TABLE_NEWS_CAT;
				}

				if($orbx_mod->validate_module('polls')) {
					$alter_tables[] = TABLE_POLL;
				}

				if($orbx_mod->validate_module('banners')) {
					$alter_tables[] = TABLE_BANNERS;
				}

				if($orbx_mod->validate_module('forms')) {
					$alter_tables[] = TABLE_FORMS;
				}

				foreach($alter_tables as $value) {
					$q_alter = sprintf('	ALTER TABLE '.$value.'
											CHANGE language language CHAR( 3 ) NOT NULL DEFAULT %s', $dbc->_db->quote($settings['main_site_def_lng']));
					$dbc->_db->query($q_alter);
				}
			}

			$this -> build_dc_rdf($settings);

			redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/www');
		}
	}

	/**
	 * save advanced site settings
	 *
	 */
	function save_site_adv_settings()
	{
		if(isset($_POST['save_settings'])) {
			$this->update_sys_file();

			global $dbc, $orbicon_x, $orbx_mod;

			// these setting are language dependant and have additional SQL
			$lang_specific_setting = $this->get_lng_spec_setting();

			// generate IP from
			$restricted_range_from = "{$_POST['restricted_range_f0']}.{$_POST['restricted_range_f1']}.{$_POST['restricted_range_f2']}.{$_POST['restricted_range_f3']}";
			// reset to wildcardonly
			$restricted_range_from = ($restricted_range_from == '*.*.*.*') ? '*' : $restricted_range_from;
			$restricted_range_from = (!empty($_POST['restricted_range_f_mask'])) ? "$restricted_range_from/{$_POST['restricted_range_f_mask']}" : $restricted_range_from;

			// generate IP to
			$restricted_range_to = "{$_POST['restricted_range_t0']}.{$_POST['restricted_range_t1']}.{$_POST['restricted_range_t2']}.{$_POST['restricted_range_t3']}";
			// reset to wildcard only
			$restricted_range_to = ($restricted_range_to == '*.*.*.*') ? '*' : $restricted_range_to;
			$restricted_range_to = (!empty($_POST['restricted_range_t_mask'])) ? "$restricted_range_to/{$_POST['restricted_range_t_mask']}" : $restricted_range_to;

			$settings = array(
				'max_rss_items' => intval($_POST['max_rss_items']),
				'rss_type' => $_POST['rss_type'],
				'main_site_permalinks' => intval($_POST['main_site_permalinks']),
				'news_img_default_xy' => intval($_POST['news_img_default_xy']),
				'flv_player_def_w' => intval($_POST['flv_player_def_w']),
				'flv_player_def_h' => intval($_POST['flv_player_def_h']),
				'smtp_server' => $_POST['smtp_server'],
				'smtp_port' => intval($_POST['smtp_port']),
				'language_subdomains' => intval($_POST['language_subdomains']),
				'v_menu_def_display' => intval($_POST['v_menu_def_display']),
				'news_archive_summary_items' => intval($_POST['news_archive_summary_items']),
				'site_restricted_access' => intval($_POST['site_restricted_access']),
				'max_poll_options' => intval($_POST['max_poll_options']),
				'flv_player_autoplay' => intval($_POST['flv_player_autoplay']),
				'video_gallery_show_date' => intval($_POST['video_gallery_show_date']),
				'text_zoom' => intval($_POST['text_zoom']),
				'poll_votes_display' => $_POST['poll_votes_display'],
				'ssl_orbx' => intval($_POST['ssl_orbx']),
				'float_horiz_menu' => intval($_POST['float_horiz_menu']),
				'v_menu_def_display_third' => intval($_POST['v_menu_def_display_third']),
				'homepage_redirect' => $_POST['homepage_redirect'],
				'date_format' => $_POST['date_format'],
				'restricted_range_from' => $restricted_range_from,
				'restricted_range_to' => $restricted_range_to,
				'poll_after_vote' => $_POST['poll_after_vote'],
				'use_captcha' => intval($_POST['use_captcha']),
				'minify_html' => intval($_POST['minify_html']),
				'override_module' => $_POST['override_module'],
				'inword_search' => intval($_POST['inword_search']),
				'form_feedback_position' => $_POST['form_feedback_position'],
				'log_slow_sql' => $_POST['log_slow_sql'],
				'us_ascii_uris' => $_POST['us_ascii_uris'],
				'antispam_check' => $_POST['antispam_check'],
				'use_cache' => $_POST['use_cache'],
				'searcheng_filter' => $_POST['searcheng_filter'],
				'flood_guard' => $_POST['flood_guard'],
				'sync_dirs' => $_POST['sync_dirs']


				);

			$settings = array_map('trim', $settings);

			foreach($settings as $key => $value) {

				// add extra SQL for language dependant settings
				$language_sql = (in_array($key, $lang_specific_setting)) ? ' AND (language = '.$dbc->_db->quote($orbicon_x->ptr).')' : '';

				$q = sprintf('	UPDATE 	'.TABLE_SETTINGS.'
								SET 	value = %s
								WHERE 	(setting = %s) '.$language_sql.'
								LIMIT 	1',
								$dbc->_db->quote($value), $dbc->_db->quote($key));
				$dbc->_db->query($q);

				// check if update status
				$q_c = sprintf('	SELECT 	value
									FROM 	'.TABLE_SETTINGS.'
									WHERE 	(setting = %s) '.$language_sql.'
									LIMIT 	1', $dbc->_db->quote($key));
				$r_c = $dbc->_db->query($q_c);
				$a_c = $dbc->_db->fetch_assoc($r_c);

				// UPDATE failed, try with INSERT
				if(($a_c['value'] === null)) {
					if($language_sql != '') {
						$q_new = sprintf('	INSERT INTO 	'.TABLE_SETTINGS.'
															(value, setting, language)
											VALUES 			(%s, %s, %s)',
											$dbc->_db->quote($value), $dbc->_db->quote($key),
											$dbc->_db->quote($orbicon_x->ptr));
					}
					else {
						$q_new = sprintf('	INSERT INTO 	'.TABLE_SETTINGS.'
															(value, setting)
											VALUES 			(%s, %s)',
											$dbc->_db->quote($value), $dbc->_db->quote($key));
					}

					$dbc->_db->query($q_new);
				}
			}

			// rebuild RSS files if we changed the format
			if($_SESSION['site_settings']['rss_type'] != $settings['rss_type']) {
				if($orbx_mod->validate_module('rss')) {
					include_once DOC_ROOT . '/orbicon/modules/rss/class.rss.php';
					$rss = new RSS_Manager;
					if($settings['rss_type'] == 'rss2') {
						$rss->build_news_rss();
						$rss->build_news_rss(true);
					}
					else if($settings['rss_type'] == 'rdf') {
						$rss->build_news_rdf();
						$rss->build_news_rdf(true);
					}
					unset($rss);
				}
			}

			// reload
			redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/advanced');
		}
	}

	/**
	 * returns an array with all orbicon settings as valus
	 * keep this list updated
	 *
	 * @return array
	 */
	function fetch_orbicon_settings()
	{
		$settings = array(
			'main_site_title',
			'main_site_owner',
			'main_site_email',
			'main_site_desc',
			'main_site_keywords',
			'news_grid_rows',
			'news_grid_columns',
			'news_category_grid_rows',
			'news_category_grid_columns',
			'rss_feeds',
			'max_rss_items',
			'rss_type',
			'main_site_permalinks',
			'installed_languages',
			'main_site_def_lng',
			'news_img_default_xy',
			'flv_player_def_w',
			'flv_player_def_h',
			'video_gallery_show_date',
			'smtp_server',
			'smtp_port',
			'language_subdomains',
			'v_menu_def_display',
			'news_archive_summary_items',
			'main_site_metatags',
			'site_restricted_access',
			'max_poll_options',
			'flv_player_autoplay',
			'text_zoom',
			'poll_votes_display',
			'syncm_server',
			'syncm_type',
			'ssl_orbx',
			'news_properties',
			'float_horiz_menu',
			'v_menu_def_display_third',
			'homepage_redirect',
			'date_format',
			'restricted_range_from',
			'restricted_range_to',
			'poll_after_vote',
			'tg_whitelist',
			'tg_blacklist',
			'tg_rules',
			'show_last_news_from',
			'use_captcha',
			'minify_html',
			'stats_sess',
			'stats_ip',
			'stats_content',
			'stats_refer',
			'stats_country',
			'stats_keyword',
			'stats_hourly',
			'override_module',
			'stats_attila',
			'inword_search',
			'form_feedback_position',
			'log_slow_sql',
			'us_ascii_uris',
			'antispam_check',
			'use_cache',
			'searcheng_filter',
			'flood_guard',
			'sync_dirs'
		);
		return $settings;
	}

	// language specific settings
	// keep this list updated
	function get_lng_spec_setting()
	{
		return array(
			'main_site_title',
			'main_site_owner',
			'main_site_desc',
			'main_site_keywords',
			'date_format'
			);
	}

	function build_site_settings($const = false)
	{
		global $dbc, $orbicon_x;

		$lang_specific_setting = $this->get_lng_spec_setting();

		$ln = $orbicon_x->ptr;
		$ln = ($ln == '') ? _get_default_language() : $ln;

		$q = '	SELECT setting, value, language
				FROM ' . TABLE_SETTINGS;
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {

			if($const) {
				if(in_array($a['setting'], $lang_specific_setting) && ($ln == $a['language'])) {
					$_SESSION['site_settings'][$a['setting']] = $a['value'];
				}
				else {
					if(!isset($_SESSION['site_settings'][$a['setting']])) {
						$_SESSION['site_settings'][$a['setting']] = $a['value'];
					}
				}
			}
			else {
				if(in_array($a['setting'], $lang_specific_setting) && ($ln == $a['language'])) {
					$_POST[$a['setting']] = $a['value'];
				}
				else {
					if(!isset($_POST[$a['setting']])) {
						$_POST[$a['setting']] = $a['value'];
					}
				}
			}

			$a = $dbc->_db->fetch_assoc($r);
		}

		//var_dump($_SESSION['site_settings']);

		/*$settings = $this->fetch_orbicon_settings();
		$lang_specific_setting = $this->get_lng_spec_setting();

		$ln = $orbicon_x->ptr;
		$ln = ($ln == '') ? _get_default_language() : $ln;

		foreach($settings as $value) {
			// these setting are language dependant and have additional SQL
			$language_sql = (in_array($value, $lang_specific_setting)) ? ' AND (language = '.$dbc->_db->quote($ln).')' : '';

			$q = sprintf('	SELECT 	value
							FROM 	'.TABLE_SETTINGS.'
							WHERE 	(setting = %s)'.$language_sql.'
							LIMIT 	1', $dbc->_db->quote($value));

			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);

			if($const) {
				unset($_SESSION['site_settings'][$value]);
				$_SESSION['site_settings'][$value] = $a['value'];
			}
			else {
				unset($_POST[$value]);
				$_POST[$value] = $a['value'];
			}
		}*/

		// free memory
		unset($settings);

		// apply mail settings
		// server
		if($_SESSION['site_settings']['smtp_server'] != '') {
			ini_set('SMTP', trim($_SESSION['site_settings']['smtp_server']));
		}
		// port
		if(!empty($_SESSION['site_settings']['smtp_port'])) {
			ini_set('smtp_port', intval($_SESSION['site_settings']['smtp_port']));
		}

		if($const) {
			if(!defined('DOMAIN_NAME')) {
				define('DOMAIN_NAME', $_SESSION['site_settings']['main_site_title']);
			}
			if(!defined('DOMAIN_OWNER')) {
				define('DOMAIN_OWNER', $_SESSION['site_settings']['main_site_owner']);
			}
			if(!defined('DOMAIN_EMAIL')) {
				define('DOMAIN_EMAIL', $_SESSION['site_settings']['main_site_email']);
			}
			if(!defined('DOMAIN_DESC')) {
				define('DOMAIN_DESC', $_SESSION['site_settings']['main_site_desc']);
			}
			if(!defined('DOMAIN_KEYWORDS')) {
				define('DOMAIN_KEYWORDS', $_SESSION['site_settings']['main_site_keywords']);
			}
			if(!defined('DOMAIN_CUSTOM_METATAGS')) {
				define('DOMAIN_CUSTOM_METATAGS', $_SESSION['site_settings']['main_site_metatags']);
			}
		}
	}

	/**
	 * @todo move this to module
	 *
	 */
	function save_news_category_scheme()
	{
		if(isset($_POST['save_scheme'])) {
			global $dbc, $orbicon_x;

			if(empty($_POST['new_news_cat'])) {
				$q = '	SELECT 		*
						FROM 		'.TABLE_SETTINGS;
				$r = $dbc->_db->query($q);
				$a = $dbc->_db->fetch_assoc($r);

				$settings = array(
					'news_category_grid_rows' => intval($_POST['news_rows']),
					'news_category_grid_columns' => intval($_POST['news_columns'])
					);

				if(empty($a) || !$r) {
					foreach($settings as $key => $value) {
						$q = sprintf('	INSERT
										INTO 		'.TABLE_SETTINGS.' (
													setting, value)
										VALUES (	%s, %s)',
															$dbc->_db->quote($key), $dbc->_db->quote($value));
						$dbc->_db->query($q);
					}
				}
				else {
					foreach($settings as $key => $value) {
						$q = sprintf('	UPDATE 		'.TABLE_SETTINGS.'
										SET 		value = %s
										WHERE 		(setting = %s)',
													$dbc->_db->quote($value), $dbc->_db->quote($key));
						$dbc->_db->query($q);
					}
				}
			}
			else {
				$title = $_POST['new_news_cat'];
				$permalink = get_permalink($title);
				$title = utf8_html_entities($title);
				$rows = intval($_POST['news_rows']);
				$columns = intval($_POST['news_columns']);

				if(!isset($_GET['edit_news_cat'])) {
					$q = sprintf('	INSERT
									INTO 	'.TABLE_NEWS_CAT.'
											(title, permalink,
											language, scheme_rows,
											scheme_columns)
									VALUES 	(%s, %s,
											%s, %s,
											%s)',
									$dbc->_db->quote($title), $dbc->_db->quote($permalink),
									$dbc->_db->quote($orbicon_x->ptr), $dbc->_db->quote($rows),
									$dbc->_db->quote($columns));
					$dbc->_db->query($q);
				}
				else {
					$q = sprintf('	UPDATE 		'.TABLE_NEWS_CAT.'
									SET			title = %s, permalink = %s,
												scheme_rows = %s, scheme_columns = %s
									WHERE 		(permalink = %s) AND
												(language = %s)',
								$dbc->_db->quote($title), $dbc->_db->quote($permalink),
								$dbc->_db->quote($rows), $dbc->_db->quote($columns),
								$dbc->_db->quote($_GET['edit_news_cat']),
								$dbc->_db->quote($orbicon_x->ptr));

					$dbc->_db->query($q);

					$q_news = sprintf('		UPDATE 	'.TABLE_NEWS.'
											SET 	category = %s
											WHERE 	(category = %s) AND
													(language = %s)',
									$dbc->_db->quote($permalink), $dbc->_db->quote($_GET['edit_news_cat']), $dbc->_db->quote($orbicon_x->ptr));

					$dbc->_db->query($q_news);

					redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/news-category&edit_news_cat=' . urlencode($permalink));
				}
			}
		}
	}

	/**
	 * @todo move this to module
	 *
	 */
	function save_sync_settings()
	{
		if(isset($_POST['save_syncm'])) {
			global $dbc;

			$q = '	SELECT 		*
					FROM 		'.TABLE_SETTINGS.'
					WHERE 		(setting=\'syncm_type\')';
			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);

			$settings = array(
				'syncm_type' => $_POST['syncm_type']
				);

			if(empty($a) || !$r) {
				foreach($settings as $key => $value) {
					$q = sprintf('	INSERT
									INTO 	'.TABLE_SETTINGS.'
											(setting, value)
									VALUES 	(%s, %s)',
									$dbc->_db->quote($key), $dbc->_db->quote($value));
					$dbc->_db->query($q);
				}
			}
			else {
				foreach($settings as $key => $value) {
					$q = sprintf('	UPDATE 	'.TABLE_SETTINGS.'
									SET 	value = %s
									WHERE 	(setting = %s)',
												$dbc->_db->quote($value), $dbc->_db->quote($key));
					$dbc->_db->query($q);
				}
			}
		}
	}

	/**
	 * @todo move this to module
	 *
	 */
	function save_news_properties()
	{
		if(isset($_POST['save_news_prop'])) {
			global $dbc;

			$q = '	SELECT 		*
					FROM 		'.TABLE_SETTINGS.'
					WHERE 		(setting=\'news_properties\')';
			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);

			$settings = array(
				'news_properties' => (int) $_POST['outsource_print'] | (int) $_POST['outsource_pdf'] | (int) $_POST['outsource_txt'] | (int) $_POST['outsource_html'],
				'show_last_news_from' => $_POST['show_last_news_from']
				);

			if(empty($a) || !$r) {
				foreach($settings as $key => $value) {
					$q = sprintf('	INSERT
									INTO 	'.TABLE_SETTINGS.'
											(setting, value)
									VALUES 	(%s, %s)',
									$dbc->_db->quote($key), $dbc->_db->quote($value));
					$dbc->_db->query($q);
				}
			}
			else {
				foreach($settings as $key => $value) {
					$q = sprintf('	UPDATE 	'.TABLE_SETTINGS.'
									SET 	value = %s
									WHERE 	(setting = %s)',
									$dbc->_db->quote($value), $dbc->_db->quote($key));
					$dbc->_db->query($q);
				}
			}
		}
	}

	/**
	 * @todo move this to module
	 *
	 */
	function get_news_property_set($bit, $flag)
	{
		if($bit & $flag) {
			return true;
		}
		return false;
	}

	function save_tg_settings()
	{
		if(isset($_POST['save_tg_lists'])) {
			global $dbc;

			$q = '	SELECT 		*
					FROM 		'.TABLE_SETTINGS.'
					WHERE 		(setting=\'tg_whitelist\')';
			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);

			$tg_rules = array(
				$_POST['tg_req_1'] . ':' . $_POST['tg_sec_1'],
				$_POST['tg_req_2'] . ':' . $_POST['tg_sec_2'],
				$_POST['tg_req_3'] . ':' . $_POST['tg_sec_3'],
				$_POST['tg_req_4'] . ':' . $_POST['tg_sec_4'],
				$_POST['tg_req_5'] . ':' . $_POST['tg_sec_5']
			);

			$tg_rules = implode(',', $tg_rules);

			$settings = array(
				'tg_whitelist' => $_POST['ip_whitelist'],
				'tg_blacklist' => $_POST['ip_blacklist'],
				'tg_rules' => $tg_rules
				);

			if(empty($a) || !$r) {
				foreach($settings as $key => $value) {
					$q = sprintf('	INSERT
									INTO 	'.TABLE_SETTINGS.'
											(setting, value)
									VALUES 	(%s, %s)',
									$dbc->_db->quote($key), $dbc->_db->quote($value));
					$dbc->_db->query($q);
				}
			}
			else {
				foreach($settings as $key => $value) {
					$q = sprintf('	UPDATE 	'.TABLE_SETTINGS.'
									SET 	value = %s
									WHERE 	(setting = %s)',
												$dbc->_db->quote($value), $dbc->_db->quote($key));
					$dbc->_db->query($q);
				}
			}
		}
	}

	function build_dc_rdf($settings)
	{
		$dc_rdf_filename = 'Overview-about.rdf';
		chmod_unlock(DOC_ROOT.'/site/mercury/'.$dc_rdf_filename);
		$r = fopen(DOC_ROOT.'/site/mercury/'.$dc_rdf_filename, 'wb');
		chmod_unlock(DOC_ROOT.'/site/mercury/'.$dc_rdf_filename);

		$rdf = '<?xml version="1.0"?>
<!DOCTYPE rdf:RDF PUBLIC "-//DUBLIN CORE//DCMES DTD 2002/07/31//EN"
	"http://dublincore.org/documents/2002/07/31/dcmes-xml/dcmes-xml-dtd.dtd">
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
	xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
	xmlns:dc="http://purl.org/dc/elements/1.1/">
	<rdf:Description rdf:about="'.ORBX_SITE_URL.'/">
		<dc:subject>'.$settings['main_site_keywords'].'</dc:subject>
		<dc:description>'.$settings['main_site_desc'].'</dc:description>
		<dc:title>'.$settings['main_site_title'].'</dc:title>
		<dc:date>'.date('Y-m-d', time()).'</dc:date>
		<dc:format>text/html</dc:format>
		<dc:language>hr</dc:language>
		<dc:creator>'.$settings['main_site_owner'].'</dc:creator>
		<dc:publisher>'.$settings['main_site_owner'].' - '.ORBX_SITE_URL.'/</dc:publisher>
		<dc:rights rdf:resource="http://www.w3.org/Consortium/Legal/copyright-documents"/>
		<dc:type>Text</dc:type>
		<dc:identifier>'.ORBX_SITE_URL.'/</dc:identifier>
		<rdfs:seeAlso rdf:resource="'.ORBX_SITE_URL.'/site/mercury/rss.xml"/>
	</rdf:Description>
</rdf:RDF>';

		unset($settings);
		/* Set a 64k buffer. */
		if(function_exists('stream_set_write_buffer')) {
			stream_set_write_buffer($r, 65535);
		}
		fwrite($r, utf8_html_entities($rdf));
		fclose($r);
		unset($rdf);
	}

	function update_sys_file()
	{
		if(!get_is_admin()) {
			return false;
		}

		// set to writable
		if(!chmod_unlock(ORBX_SYS_CONFIG)) {
			return false;
		}

		// lock
		if(!lock(ORBX_SYS_CONFIG)) {
			return false;
		}

		// this is fine, we have configuration file
		clearstatcache();
		if(is_file(ORBX_SYS_CONFIG)) {
			$password = trim($_POST['mysql_pass']);
			$password_v = trim($_POST['mysql_pass_v']);

			if(($password !== $password_v) || ($password == '')) {
				$password = DB_PASS;
			}
			else {
				$password = base64_encode($password);
			}

			$ftp_password = trim($_POST['ftp_pass']);
			$ftp_password_v = trim($_POST['ftp_pass_v']);

			if(($ftp_password !== $ftp_password_v) || ($ftp_password == '')) {
				$ftp_password = FTP_PASS;
			}
			else {
				$ftp_password = base64_encode($ftp_password);
			}

			// format config data
			$sys_config = '<?php'."\n";
			// mysql
			$sys_config .= '/* mysql */'."\n";
			$sys_config .= 'define(\'DB_TYPE\', \'MySQL\');'."\n";
			$sys_config .= 'define(\'DB_HOST\', \''.$_POST['mysql_host'].'\');'."\n";
			$sys_config .= 'define(\'DB_NAME\', \''.$_POST['mysql_db'].'\');'."\n";
			$sys_config .= 'define(\'DB_USER\', \''.$_POST['mysql_username'].'\');'."\n";
			$sys_config .= 'define(\'DB_PASS\', \''.$password.'\');'."\n";
			$sys_config .= 'define(\'DB_PERMACONN\', \''.$_POST['mysql_perma'].'\');'."\n";

			// ftp
			$sys_config .= '/* ftp */'."\n";
			$sys_config .= 'define(\'FTP_HOST\', \''.$_POST['ftp_host'].'\');'."\n";
			$sys_config .= 'define(\'FTP_ROOTDIR\', \''.$_POST['ftp_rootdir'].'\');'."\n";
			$sys_config .= 'define(\'FTP_USER\', \''.$_POST['ftp_username'].'\');'."\n";
			$sys_config .= 'define(\'FTP_PASS\', \''.$ftp_password.'\');'."\n";
			$sys_config .= 'define(\'FTP_TYPE\', \''.$_POST['ftp_type'].'\');'."\n";

			// system constants
			$sys_config .= '/* system */'."\n";
			$sys_config .= 'define(\'ORBX_MAINTENANCE_MODE\', '.intval($_POST['maintenance_mode']).');'."\n";
			$sys_config .= 'define(\'ORBX_INSTALL_TYPE\', '.ORBX_INSTALL_TYPE.');'."\n";
			$sys_config .= 'define(\'ORBX_INSTALL_TIME\', '.ORBX_INSTALL_TIME.');'."\n";
			$sys_config .= 'define(\'ORBX_INTEGRITY_URI\', \''.ORBX_INTEGRITY_URI.'\');'."\n";
			$sys_config .= '?>';
			// write and close file
			$sys_h = fopen(ORBX_SYS_CONFIG, 'wb');
			/* Set a 64k buffer. */
			if(function_exists('stream_set_write_buffer')) {
				stream_set_write_buffer($sys_h, 65535);
			}

			if(fwrite($sys_h, $sys_config) !== false) {
				fclose($sys_h);
				// set to read only
				if(!chmod_lock(ORBX_SYS_CONFIG)) {
					// unlock
					unlock(ORBX_SYS_CONFIG);
					return false;
				}
				unset($sys_config, $sys_h);
			}
		}
		// unlock
		unlock(ORBX_SYS_CONFIG);
		return true;
	}
}

?>