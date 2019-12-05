<?php
/**
 * Orbicon main class. Handles language setup, navigation, template parsing, etc.
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package OrbiconFE
 * @subpackage Core
 * @version 1.00
 * @link http://
 * @license http://
 * @since 2006-07-01
 */

class OrbiconX
{
	/**
	 * this is the main path var. it's dynamic and reflects the
	 * language codes in ISO 639-1 (alpha-2 code) or ISO 639-2 (alpha-3 code)
	 * reference list URL - http://www.loc.gov/standards/iso639-2/langcodes.html
	 *
	 * @var string
	 */
	var $ptr;

	/**
	 * Custom feed links
	 *
	 * @var array
	 */
	var $feed_links;

	/**
	 * Custom metatags
	 *
	 * @var array
	 */
	var $metatags;

	/**
	 * Custom breadcrumbs
	 *
	 * @var unknown_type
	 */
	var $custom_breadcrumbs;

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return OrbiconX
	 */
	function OrbiconX()
	{
		$this->__construct();
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 */
	function __construct()
	{
		// setup the current language
		// * very important *
		$this->setup_site_language();
		$this->ptr = $this->get_site_language();
		$this->metatags = array();
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $input
	 * @return bool
	 */
	function get_is_requesting_alt($alt)
	{
		return in_array($alt, array('pdf', 'txt', 'html'));
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $layout
	 * @param string $default
	 * @return string
	 */
	function admin_layout($layout, $default = '')
	{
		// quick exit
		if($layout == '') {
			return '';
		}

		if(get_is_admin()) {
			return $layout;
		}
		return $default;
	}

	/**
	 * return $input if we're in orbicon, else return empty string
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $input
	 * @return string
	 */
	function is_orbicon($input)
	{
		if($input == '') {
			return '';
		}

		if(_get_is_orbicon_uri()) {
			return $input;
		}
		return '';
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return bool
	 */
	function get_preview_mode() {
		return (get_is_admin() && (isset($_GET['preview_mode'])));
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return string
	 */
	function get_boxes_for_parsing()
	{
		// get current zones in a clean array
		$zones_box = array();

		if($_SESSION['current_zone'] !== null) {
			foreach($_SESSION['current_zone'] as $zone) {
				$zones_box[] = $zone['permalink'];
			}
		}

		global $dbc;
		$q = sprintf('	SELECT 		permalink, parent,
									box_zone
						FROM 		'.TABLE_COLUMNS.'
						WHERE 		(menu_name = \'box\') AND
									(language = %s)',
								$dbc->_db->quote($this->ptr));

		$a = $dbc->_db->get_cache($q);
		if($a !== null) {
			return $a;
		}

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		$boxes = array();

		while($a) {
			$index = (!($a['parent'])) ? strtoupper($a['permalink']) : strtoupper($a['parent']);
			$has_parent = (!($a['parent'])) ? false : true;

			// global box, goes everywhere
			if(!($a['box_zone'])) {
				$boxes[$index] = $this->build_box_menu($index, $has_parent);
			}
			// check for proper zone
			else if(in_array($a['box_zone'], $zones_box)) {
				$boxes[$index] = $this->build_box_menu($index, $has_parent);
			}
			// just remove the template mark
			else {
				$boxes[$index] = '';
			}
			$a = $dbc->_db->fetch_assoc($r);
		}

		$dbc->_db->put_cache($boxes, $q);

		return $boxes;
	}

	/**
	 * prepare content if we're syndicating our rss feeds via templates. feeds should be added in template as <!>MYRSS_(feed permalink).  for example http://www.cnn.com/rss.xml is <!>MYRSS_HTTP-WWWCNNCOM-RSSXML
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return string
	 */
	function get_rssfeeds_for_parsing()
	{
		global $orbx_mod;

		if(!$orbx_mod->validate_module('rss')) {
			return null;
		}

		require_once DOC_ROOT . '/orbicon/modules/rss/class.rss.php';
		$my_feeds = array();
		$rss_o = new RSS_Manager;
		$rss_o->load_current_rss();
		require_once DOC_ROOT . '/orbicon/3rdParty/magpierss/rss_fetch.php';

		foreach($rss_o->__my_rss_feeds as $value) {
			$rss = fetch_rss($value);
			$rss_index = 'MYRSS_' . strtoupper(get_permalink($value));

			if(scan_templates('<!>' . $rss_index) >= 1) {
				if($rss->items) {
					$backup_url = parse_url($rss->channel['link']);
					/**
					 * unused?
					 */
					/*$date = strtotime($rss->channel['lastbuilddate']);
					$date = date('d.m.Y.', $date);*/

					$rss->items = array_slice($rss->items, 0, $_SESSION['site_settings']['max_rss_items']);

					foreach($rss->items as $item) {
						$href = (!($item['link'])) ? $item['guid'] : $item['link'];
						$item_url = parse_url($href);

						$href = (!($item_url['host'])) ? $backup_url['scheme'] . '://' . $backup_url['host'] . $href : $href;

						$title = $item['title'];

						$aRSS[] = "<p><a href=\"$href\">$title</a></p>";
					}
					$my_feeds[$rss_index] = (!$aRSS) ? null : implode('', $aRSS);
				}	// empty rss items
			}	// scan templates
			// free memory
			unset($rss_o, $rss, $aRSS);
		} // foreach end
		return $my_feeds;
	}

	/**
	 * this just wraps $value with forward slashes for regexp used in parse_template()
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $value
	 * @return string
	 */
	function __set_regexp_template($value) {
		return "/<!>$value/";
	}

	/**
	 * parse template
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $input
	 * @param array $replace
	 * @param bool $return
	 * @return string
	 */
	function parse_template($input, $replace, $return = false)
	{
		global $orbx_mod;
		if(!ORBX_GZIP) {
			flush();
		}

		if(!is_file($input)) {
			trigger_error('parse_template() expects parameter 1 to be file', E_USER_WARNING);
			return false;
		}

		$input = file_get_contents($input);

		if($_SESSION['site_settings']['minify_html']) {
			$input = min_str($input, true);
		}

		$replace = array_merge($replace, $this->get_boxes_for_parsing());
		$replace = array_merge($replace, $this->get_rssfeeds_for_parsing());

		$for_removal = array_keys($replace);
		$for_replacement = array_values($replace);

		// finally add template mark for removal as well
		$for_removal[] = '<!>';
		$for_replacement[] = '';

		// add regexp to $for_removal
		$for_removal = array_map(array($this, '__set_regexp_template'), $for_removal);

		if($orbx_mod->validate_module('top_search_keywords')) {
			$top_kws = include DOC_ROOT . '/orbicon/modules/top_search_keywords/render.topkwrds.php';

			$for_removal[] = '/<!---->TOP_KEYWORDS/';
			$for_replacement[] = $top_kws;
			$for_removal[] = '/&lt; !----&gt;TOP_KEYWORDS/';
			$for_replacement[] = $top_kws;
			$for_removal[] = '/< !---->TOP_KEYWORDS/';
			$for_replacement[] = $top_kws;
			unset($top_kws);
		}

		$for_removal[] = '/COLUMN_TITLE/';
		$for_replacement[] = $replace['COLUMN_TITLE'];

		$for_removal[] = '/COLUMN_PRINT_LINK/';

		$get = $_GET;
		$get[$orbicon_x->ptr] = "{$get[$orbicon_x->ptr]}/html";

		$for_replacement[] = ORBX_SITE_URL . '/?' . http_build_query($get);

		// release memory
		unset($replace, $get);

		// replace template marks with content
		$input = preg_replace($for_removal, $for_replacement, $input);

		// this content is for search bot so strip tags
		if(get_is_search_engine_bot() /*&& $_SESSION['site_settings']['searcheng_filter']*/) {
			$input = str_sanitize($input, STR_SANITIZE_SEARCHBOT);
		}

		// this content is for validator so strip tags
		// W3C validator hates these
		if(get_is_w3c_validator()) {
			$input = str_sanitize($input, STR_SANITIZE_HTML_VALIDATOR);
		}

		if($return) {
			return $input;
		}
		else {
			echo $input;
			if(!ORBX_GZIP) {
				flush();
			}
		}
		return true;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $group
	 * @param bool $parent
	 * @return unknown
	 */
	function build_box_menu($group, $parent = true)
	{
		global $dbc, $orbx_log;

		$q = '';
		if($parent) {
			$q = sprintf('	SELECT 		*
							FROM 		'.TABLE_COLUMNS.'
							WHERE 		(parent = %s) AND
										(menu_name = \'box\') AND
										(language = %s)
							ORDER BY 	sort', $dbc->_db->quote(get_permalink($group)), $dbc->_db->quote($this->ptr));
		}
		else {
			$q = sprintf('	SELECT 		*
							FROM 		'.TABLE_COLUMNS.'
							WHERE 		(permalink = %s) AND
										(menu_name = \'box\') AND
										(language = %s)
							LIMIT 		1', $dbc->_db->quote(get_permalink($group)), $dbc->_db->quote($this->ptr));
		}

		$box = $dbc->_db->get_cache($q);
		if($box !== null) {
			return $box;
		}

		$r = $dbc->_db->query($q);
		$box = $dbc->_db->fetch_assoc($r);

		while($box) {
			// style
			$box_style = explode(';', $box['box_style']);

			$box_style['border'] = explode(':', $box_style[0]);
			$box_style['border'] = ($box_style['border'] == 'transparent') ? str_replace('#', '', $box_style['border'][1]) : $box_style['border'][1];
			$box_style['border'] = (!$box_style['border']) ? 'transparent' : $box_style['border'];
			$box_style['border'] = ($box_style['border'] == '#') ? '' : 'border:1px solid '.$box_style['border'].';';

			$box_style['background'] = explode(':', $box_style[1]);
			$box_style['background'] = ($box_style['background'] == 'transparent') ? str_replace('#', '', $box_style['background'][1]) : $box_style['background'][1];
			$box_style['background'] = (!$box_style['background']) ? 'transparent' : $box_style['background'];
			$box_style['background'] = ($box_style['background'] == '#') ? '' : 'background-color:'.$box_style['background'].';';

			// this won't work in explorer
			if($box_style['border'] == 'border:1px solid transparent;') {
				$box_style['border'] = '';
			}

			if($box_style['background'] == 'background-color:transparent;') {
				$box_style['background'] = '';
			}

			// content
			$r_ = $dbc->_db->query(sprintf('	SELECT 		content
												FROM 		'.MAGISTER_CONTENTS.'
												WHERE 		(live = 1) AND
															(hidden = 0) AND
															(question_permalink = %s) AND
															(language = %s)
												ORDER BY 	uploader_time',
												$dbc->_db->quote($box['content']), $dbc->_db->quote($this->ptr)));
			$a_ = $dbc->_db->fetch_assoc($r_);

			while($a_) {
				$box['magister_content'] .= $a_['content'];
				$a_ = $dbc->_db->fetch_assoc($r_);
			}
			$dbc->_db->free_result($r_);

			// add admin edit shortcut to magister db
			if($box['magister_content'] && get_is_admin()) {
				$box['magister_content'] = $this->admin_layout('<p id="admin_tool"><a href="'.ORBX_SITE_URL.'/?'.$this->ptr.'=orbicon/magister&amp;read=clanak/'.urlencode($box['content']).'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/edit.png"></a></p>').$box['magister_content'];
			}

			$box_content .= '<div
			style="'.$box_style['border'].' '.$box_style['background'].'"
			id="'.$box['permalink'].'">'.$box['magister_content'].'</div>';

			$box = $dbc->_db->fetch_assoc($r);
		}

		$box_content = min_str($box_content);
		$dbc->_db->put_cache($box_content, $q);

		return $box_content;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return string
	 */
	function get_breadcrumbs()
	{
		// top page doesn't have breadcrumbs
		if(!$_GET[$this->ptr]) {
			return '';
		}

		if(scan_templates('<!>BREADCRUMBS') < 1) {
			return false;
		}

		$home = (!defined('DOMAIN_NAME')) ? '<a href="'.ORBX_SITE_URL.'/?ln='.$this->ptr.'" title="' . ORBX_SITE_URL . '">'.ORBX_SITE_URL.'</a>' : '<a href="'.ORBX_SITE_URL.'/?ln='.$this->ptr.'" title="' . DOMAIN_NAME . '">'.DOMAIN_NAME.'</a>';
		$breadcrumbs[] = $home;

		if($_GET[$this->ptr] == 'sitemap') {
			$breadcrumbs[] = '<span class="active">'._L('sitemap').'</span>';
		}
		else if($_GET[$this->ptr] == 'attila') {
			$breadcrumbs[] = '<span class="active">'._L('search_results').'</span>';
		}
		else if(substr($_GET[$this->ptr], 0, 4) == 'mod.') {

			list($clean, $junk) = explode('/', $_GET[$this->ptr]);

			switch ($clean) {
				case 'mod.e':
					$breadcrumbs = array_merge($breadcrumbs, $this->custom_breadcrumbs);
				break;
				case 'mod.peoplering':
				case 'mod.estate.new':

					if(($_GET['sp'] == 'companies') || ($_GET['sp'] == 'company_details') ||
					($_GET['page'] == 'add') || ($_GET['page'] == 'edit')) {
						$breadcrumbs = array_merge($breadcrumbs, $this->custom_breadcrumbs);
					}

				break;
				case 'mod.estate.l':
					if(isset($_GET['tag'])) {
						$breadcrumbs = array_merge($breadcrumbs, $this->custom_breadcrumbs);
					}
				break;
				case 'mod.forum':
					list($forum, $topic) = explode('/', $_GET[$this->ptr]);
					if($topic) {
						//$breadcrumbs[] = _L('forum');
						//$breadcrumbs = array_merge($breadcrumbs, $this->custom_breadcrumbs);
						$breadcrumbs[] = '<span class="active">'._L('forum').'</span>';
					}
					else {
						//$breadcrumbs[] = _L('forum');
						$breadcrumbs[] = '<span class="active">'._L('forum').'</span>';

					}
				break;
				case 'mod.news-index':
					$breadcrumbs = array($home, '<a href="./?hr=o-nama">O nama</a>', '<span class="active">Press centar</span>');
				break;
				default:
				$breadcrumbs[] = '<span class="active">'._L(substr($_GET[$this->ptr], 4)).'</span>';
				//$breadcrumbs[] = _L(substr($_GET[$this->ptr], 4));
			}
		}
		else {
			global $dbc, $orbx_log, $orbx_mod;

			$permalink_column = ($_SESSION['site_settings']['us_ascii_uris']) ? 'permalink_ascii' : 'permalink';
			$parent_column = ($_SESSION['site_settings']['us_ascii_uris']) ? 'parent_ascii' : 'parent';

			// columns
			$r = $dbc->_db->query(sprintf('	SELECT 	*
											FROM 	'.TABLE_COLUMNS.'
											WHERE 	('.$permalink_column.' = %s) AND
													(language = %s)',
											$dbc->_db->quote($_GET[$this->ptr]),
											$dbc->_db->quote($this->ptr)));

			$a = $dbc->_db->fetch_assoc($r);

			if(!$a['id'] || !$r) {
				$r = $dbc->_db->query(sprintf('	SELECT 		title
												FROM 		'.TABLE_NEWS.'
												WHERE 		('.$permalink_column.' = %s) AND
															(language = %s)',
												$dbc->_db->quote($_GET[$this->ptr]),
												$dbc->_db->quote($this->ptr)));

				$a = $dbc->_db->fetch_assoc($r);
				$last = $a['title'];
			}
			else {
				$last = $a['title'];

				// let's find all parents
				$parents = array();

				while((string) $a['parent'] != '') {

					$r = $dbc->_db->query(sprintf('	SELECT 	title, '.$permalink_column.',
															'.$parent_column.'
													FROM 	'.TABLE_COLUMNS.'
													WHERE 	('.$permalink_column.' = %s) AND
															(language = %s)
													LIMIT 	1',
					$dbc->_db->quote($a[$parent_column]), $dbc->_db->quote($this->ptr)));
					$a = $dbc->_db->fetch_assoc($r);

					$url = ORBX_SITE_URL.'/?'.$this->ptr.'='.$this->urlnormalize($a[$permalink_column]);

					if(($this->ptr != 'hr') && ($orbx_mod->validate_module('estate'))) {
						include_once DOC_ROOT . '/orbicon/modules/estate/menu.trans.php';
						$a['title'] = estate_title_trans($a[$permalink_column]);
					}

					$parents[] = sprintf('<a href="%s" title="%s">%s</a>', $url, $a['title'], $a['title']);
				}
				// we added them in a reverse way, so flip them back for correct display logic
				if(count($parents) > 1) {
					$parents = array_reverse($parents);
				}
				// add to breadcrumbs
				$breadcrumbs = array_merge($breadcrumbs, $parents);
			}

			if(($this->ptr != 'hr') && ($orbx_mod->validate_module('estate'))) {
				include_once DOC_ROOT . '/orbicon/modules/estate/menu.trans.php';
				$last = estate_title_trans(get_permalink($last));
			}

			$last = str_sanitize($last, STR_SANITIZE_INPUT_TEXT_VALUE);
			$breadcrumbs[] = "<span class=\"active\">$last</span>";
		}

		$breadcrumbs = array_remove_empty($breadcrumbs);

		$breadcrumbs = implode(' <span>&gt;</span> ', $breadcrumbs);
		return $breadcrumbs;
	}

	/**
	 * Parse and return URL for sitemap
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $name
	 * @param string $url
	 * @return string
	 */
	function _sitemap_index_item($name, $url, $urlp)
	{
		$url = url($url, $urlp);
		return "<li><a href=\"$url\" title=\"$name\">$name</a></li>";
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return string
	 */
	function generate_sitemap_index()
	{
		global $dbc, $orbx_log;

		$sitemap = '<ol class="orbx_sitemap_index">';

		// types
		$types = array('h', 'v', 'hidden');

		foreach($types as $type) {
			// columns
			$r = $dbc->_db->query(sprintf('	SELECT 		*
											FROM 		'.TABLE_COLUMNS.'
											WHERE 		((parent IS NULL) OR (parent = \'\')) AND
														(menu_name = %s) AND
														(language = %s)
											ORDER BY 	sort', $dbc->_db->quote($type), $dbc->_db->quote($this->ptr)));
			$a = $dbc->_db->fetch_assoc($r);

			while($a) {
				if(!$a['redirect']) {
					$sitemap .= $this->_sitemap_index_item($a['title'], ORBX_SITE_URL.'/?'.$this->ptr.'='.$this->urlnormalize($a['permalink']), ORBX_SITE_URL.'/'.$this->ptr.'/'.$this->urlnormalize($a['permalink']));
				}
				else {
					$sitemap .= $this->_sitemap_index_item($a['title'], $a['redirect'], $a['redirect']);
				}

				$r_ = $dbc->_db->query(sprintf('SELECT 		*
												FROM 		'.TABLE_COLUMNS.'
												WHERE 		(parent = %s) AND
															(menu_name != \'hidden\') AND
															(menu_name != \'box\') AND
															(language = %s)
												ORDER BY 	sort',
												$dbc->_db->quote($a['permalink']), $dbc->_db->quote($this->ptr)));
				$a_c = $dbc->_db->fetch_assoc($r_);

				$sitemap .= '<ul class="orbx_sitemap_next_level">';

				while($a_c) {
					if(!$a_c['redirect']) {
						$sitemap .= $this->_sitemap_index_item($a_c['title'], ORBX_SITE_URL.'/?'.$this->ptr.'='.$this->urlnormalize($a_c['permalink']), ORBX_SITE_URL.'/'.$this->ptr.'/'.$this->urlnormalize($a_c['permalink']));
					}
					else {
						$sitemap .= $this->_sitemap_index_item($a_c['title'], $a_c['redirect'], $a_c['redirect']);
					}
					$a_c = $dbc->_db->fetch_assoc($r_);
				}

				$sitemap .= '</ul>';

				$a = $dbc->_db->fetch_assoc($r);
			}
		}
		$sitemap .= '</ol>';

		return $sitemap;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 */
	function setup_site_language()
	{
		$found = false;
		$installed_lngs = _get_installed_languages();

		// setup language as default language
		$ln = $_SESSION['site_settings']['language'];
		$ln = ($ln == '') ? _get_default_language() : $ln;

		// scan for faulty urls like domain.com/en
		if(!$found && $_REQUEST) {
			foreach($_REQUEST as $key => $value) {
				if(!$found &&
				in_array($key, $installed_lngs) &&
				(strlen($key) <= 3) &&
				(strlen($key) >= 2)) {
					$ln = $key;
					$found = true;
				}
			}
			unset($key, $value);
		}

		$user_choice = trim(@$_REQUEST['ln']);

		// user's choice has highest priority
		if($user_choice &&
		(strlen($user_choice) <= 3) &&
		(strlen($user_choice) >= 2) &&
		in_array($user_choice, $installed_lngs)) {
			$ln = $user_choice;
			$found = true;
		}

		// scan domain url for subdomain url like en.domain.com
		if(!$found) {
			$url = parse_url(ORBX_SITE_URL);
			$url = explode('.', $url['host']);

			foreach($url as $value) {
				if(!$found &&
				in_array($value, $installed_lngs) &&
				$value != 'www' &&
				(strlen($value) <= 3) &&
				(strlen($value) >= 2)) {
					$ln = $value;
					$found = true;
				}
			}
			unset($url, $value);
		}

		$_SESSION['site_settings']['language'] = $ln;
		$this->ptr = $this->get_site_language();
		setlocale(LC_ALL, $this->ptr);
	}

	/**
	 * return current language
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return string
	 */
	function get_site_language()
	{
		$session_ln = trim($_SESSION['site_settings']['language']);

		if($session_ln && (strlen($session_ln) >= 2) && (strlen($session_ln) <= 3)) {
			return $session_ln;
		}
		else if($this->ptr && (strlen($this->ptr) >= 2) && (strlen($this->ptr) <= 3)) {
			return $this->ptr;
		}

		return ORBX_DEFAULT_LANGUAGE;
	}

	/**
	 * return animated infobox
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @todo replace scriptaculous with YUI!
	 * @return string	Formatted infobox
	 */
	function display_orbicon_infobox()
	{
		// no message so don't display anything
		if($_SESSION['orbicon_infobox_msg'] == '') {
			return '';
		}

		// we're gonna display this inside the form
		if($_SESSION['site_settings']['form_feedback_position'] == 'inside') {
			return '';
		}

		// nothing in template
		if(scan_templates('<!>TOP_INFOBOX') < 1) {
			return false;
		}

		$alert_msg = $_SESSION['orbicon_infobox_msg'];
		unset($_SESSION['orbicon_infobox_msg']);

		$slide_js = '
		<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/controler/gzip.server.php?file=/orbicon/3rdParty/scriptaculous/lib/prototype.js&amp;'.ORBX_BUILD.'"></script>
		<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/3rdParty/scriptaculous/src/scriptaculous.js?'.ORBX_BUILD.'"></script>';

		return $slide_js.'
		<div id="orbicon_top_infobox" class="rc">
			<div class="rc_hd">
				<div class="rc_tr"></div>
			</div>
			<div class="rc_bd">
				<div class="rc_mr">
					<div class="infobox_content">
						<p>'.utf8_html_entities($alert_msg).'</p>
					</div>
					<a href="javascript:void(null);" onclick="javascript:Effect.SlideUp(\'orbicon_top_infobox\');" id="ds_close" class="close" title="'._L('close').'">'._L('close').'</a>
				</div>
			</div>
			<div class="rc_ft">
				<div class="rc_br"></div>
			</div>
		</div>';
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @author Alen Novakovic <alen.novakovic@orbitum.net>
	 * @return string
	 */
	function navigation_verti_menu()
	{
		// template mark is missing and we're not in backend
		if((scan_templates('<!>NAVIGATION_V') < 1) && !get_is_admin()) {
			return false;
		}

		// * open menu container
		$column_item = '<div class="orbx_navigation_container" ' . $this->is_orbicon($this->admin_layout('id="orbicon_nav_menu"')).'>
		<ul id="navigation_list">';

		global $dbc, $orbx_log, $orbx_mod;

		$column_permalink = ($_SESSION['site_settings']['us_ascii_uris']) ? 'permalink_ascii' : 'permalink';
		$parent_permalink = ($_SESSION['site_settings']['us_ascii_uris']) ? 'parent_ascii' : 'parent';

		// what should we display? frontend or backend
		$__show_me = (get_is_admin() && isset($_GET['menu'])) ? $_GET['menu'] : 'v';

		$parent = (isset($_GET['parent'])) ? '(parent=' . $dbc->_db->quote($_GET['parent']) . ')' : 'IS NULL';
		$parent = (get_is_admin()) ? $parent : 'IS NULL';

		if($parent == 'IS NULL') {
			$parent = '((parent IS NULL) OR (parent = \'\'))';
		}

		$sql_column = sprintf('
						SELECT 		*
						FROM 		'.TABLE_COLUMNS.'
						WHERE 		'.$parent.' AND
									(menu_name = %s) AND
									(language = %s)
						ORDER BY 	sort',
						$dbc->_db->quote($__show_me),
						$dbc->_db->quote($this->ptr));

		$column = $dbc->_db->get_cache($sql_column);
		if($column !== null) {
			return $column;
		}

		$column_resource = $dbc->_db->query($sql_column);
		$column = $dbc->_db->fetch_assoc($column_resource);

		// level 1 START
		while($column) {

			$subcolumn_sql = sprintf('
								SELECT 		*
								FROM 		'.TABLE_COLUMNS.'
								WHERE 		('.$parent_permalink.'=%s) AND
											(menu_name = %s) AND
											(language = %s)
								ORDER BY 	sort',
								$dbc->_db->quote($column[$column_permalink]),
								$dbc->_db->quote($__show_me),
								$dbc->_db->quote($this->ptr));

			$subcolumn_resource = $dbc->_db->query($subcolumn_sql);
			$subcolumn = $dbc->_db->fetch_assoc($subcolumn_resource);

			// level 2 START
			while($subcolumn) {

				// level 3 START
				if($_SESSION['site_settings']['v_menu_def_display_third']) {
					$trd_sql = sprintf('SELECT 		*
										FROM 		'.TABLE_COLUMNS.'
										WHERE 		('.$parent_permalink.'=%s) AND
													(menu_name = %s) AND
													(language = %s)
										ORDER BY 	sort',
										$dbc->_db->quote($subcolumn[$column_permalink]),
										$dbc->_db->quote($__show_me),
										$dbc->_db->quote($this->ptr));

					$trd_resource = $dbc->_db->query($trd_sql);
					$trd_column = $dbc->_db->fetch_assoc($trd_resource);

						while($trd_column) {

							if($_GET[$this->ptr] == $trd_column[$column_permalink]) {
								$trd_column_opened = $trd_column[$parent_permalink];
							}

							$trd_column_redirect_url = (!$trd_column['redirect']) ? ORBX_SITE_URL.'/?'.$this->ptr.'=' . $this->urlnormalize($trd_column['permalink']) : $trd_column['redirect'];

							if($orbx_mod->validate_module('estate') && !_get_is_orbicon_uri() && ($this->ptr != 'hr')) {
								require_once DOC_ROOT . '/orbicon/modules/estate/menu.trans.php';
								$trd_column['title'] = estate_title_trans($trd_column['permalink']);
							}

							$trd_column_item .= (
											'<li '.
											$this->is_orbicon($this->admin_layout('id="sort_'.$trd_column['permalink'].'"')).
											' class="podrubrike subcolumns_second">'.
											'<a href="'.$trd_column_redirect_url.'" title="'.$trd_column['title'].'"> '.
												$trd_column['title'].
											'</a>'.
											$this->is_orbicon($this->admin_layout('
											<a href="./?delete_col='.$trd_column['permalink'].'" title="'.
											'" onclick="javascript: return false;" onmousedown="'.delete_popup($subcolumn['title']).'"><sup>['._L('delete').']</sup></a>')).
											'</li>');

							$trd_column = $dbc->_db->fetch_assoc($trd_resource);

					$trd_column_redirect_url = ($subcolumn['redirect'] && !$trd_column_item) ? $subcolumn['redirect'] : ORBX_SITE_URL.'/?'.$this->ptr.'=%s' . $this->urlnormalize($subcolumn['permalink']);
					}
				}
				// level 3 END

				// format title
				$subtitle = str_sanitize($subcolumn['title'], STR_SANITIZE_INPUT_TEXT_VALUE);

				if($orbx_mod->validate_module('estate') && !_get_is_orbicon_uri() && ($this->ptr != 'hr')) {
					require_once DOC_ROOT . '/orbicon/modules/estate/menu.trans.php';
					$subtitle = estate_title_trans($subcolumn['permalink']);
				}

				$subcolumn_redirect_url = (!$subcolumn['redirect']) ? ORBX_SITE_URL.'/?'.$this->ptr.'=' . $this->urlnormalize($subcolumn['permalink']) : $subcolumn['redirect'];

				// no subcolumns, we're displaying direct link
				if(!$trd_column_item) {
					$sublink = sprintf('<a href="%s" title="%s">%s</a>', $subcolumn_redirect_url, $subtitle, $subtitle);
				}
				else {
					// depending on our content we're displaying direct or menu-manipulating link
					// * here might be an issue with column_redirect_url & subcolumn_redirect_url MAYBE
					$sublink = ($subcolumn['content']) ? sprintf('<a href="%s" title="%s">%s</a>', $subcolumn_redirect_url, $subtitle, $subtitle) : '<a href="javascript: void(null);" rel="nofollow" onclick="javascript: sh(\'subcolumns_of_'.$subcolumn['permalink'].'\');" title="'.$subtitle.'">'.$subtitle.'</a>';
				}

				$sublink = ($subcolumn['redirect']) ? sprintf('<a href="%s" title="%s">%s</a>', $subcolumn['redirect'], $subtitle, $subtitle) : $sublink;
				$subcss = (!$trd_column_item) ? 'rubrike_pod subcolumns' : 'rubrike_pod subcolumns third_level';

				// set by general setting
				$trd_column_def_display_subcolumns = (!$_SESSION['site_settings']['v_menu_def_display']) ? 'display:none;' : '';

				// find topmost parent START

				if($trd_column_opened == '') {

					$trd_column_opened = $_GET[$this->ptr];
					$trd_column_r_p = $dbc->_db->query(sprintf('
														SELECT 		'.$parent_permalink.'
														FROM 		'.TABLE_COLUMNS.'
														WHERE 		('.$column_permalink.' = %s) AND
																	(language = %s)
														LIMIT 		1',
														$dbc->_db->quote($_GET[$this->ptr]),
														$dbc->_db->quote($this->ptr)));

					$trd_column_a_p = $dbc->_db->fetch_assoc($trd_column_r_p);

					if($trd_column_a_p[$parent_permalink]) {
						$trd_column_opened = $trd_column_a_p[$parent_permalink];
					}

					while((string) $trd_column_a_p[$parent_permalink] != '') {
						$r_p = $dbc->_db->query(sprintf('	SELECT 		'.$parent_permalink.'
															FROM 		'.TABLE_COLUMNS.'
															WHERE 		('.$column_permalink.' = %s) AND
																		(language = %s)
															LIMIT 		1',
															$dbc->_db->quote($trd_column_a_p[$parent_permalink]),
															$dbc->_db->quote($this->ptr)));

						$trd_column_a_p = $dbc->_db->fetch_assoc($trd_column_r_p);

						if($trd_column_a_p[$parent_permalink]) {
							$trd_column_opened = $trd_column_a_p[$parent_permalink];
						}
					}
				}

				// find topmost parent END

				// open parent's menu
				if(!$_SESSION['site_settings']['v_menu_def_display']) {
					$trd_column_def_display_subcolumns = ($subcolumn[$column_permalink] == $trd_column_opened) ? '' : 'display:none;';
				}

				$trd_column_item = (!$trd_column_item) ? '' : '<li id="subcolumns_of_'.$subcolumn['permalink'].'" class="s orbx_subcols_list" style="'.$trd_column_def_display_subcolumns.'"><ul class="subcolumns_list">'.$trd_column_item.'</ul></li>';

				// 1.2.2007, Pavle Gardijan, added on/off status for expand image
				// get it here because we're unsetting it below
				$trd_expand_img = (!$trd_column_item) ? 'expand_off.png' : 'expand.png';

				// we're in backend
				if(strpos($_GET[$this->ptr], 'orbicon') !== false) {
					$sublink = $subcolumn['title'];
					unset($subcss, $trd_column_item);
				}

				// internal pages cannot be expanded
				$trd_expand_column = '<a href="./?'.$this->ptr.'='.$_GET[$this->ptr].'&amp;menu='.$_GET['menu'].'&amp;parent='.$subcolumn['permalink'].'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/'.$trd_expand_img.'" title="'._L('expand').'" /></a>';

				// -> third level ends here

				if($_GET[$this->ptr] == $subcolumn[$column_permalink]) {
					$subcolumn_opened = $subcolumn[$parent_permalink];
				}

				$subcolumn_item .= ('
									<li class="'.$subcss.' top_v_columns"'.$this->is_orbicon($this->admin_layout('id="sort_'.$subcolumn['permalink'].'"')).'>'.
										$sublink.$this->is_orbicon($this->admin_layout('<div>'.$trd_expand_column.'
									<a href="./?'.$this->ptr.'='.$_GET[$this->ptr].'&amp;edit='.$subcolumn['permalink'].'">
										<img src="./orbicon/gfx/gui_icons/edit.png" title="'._L('edit').'" />
									</a>
									<a href="./?'.$this->ptr.'='.$_GET[$this->ptr].'&amp;delete_col='.$subcolumn['permalink'].
								'" title="'._L('delete').'" onclick="javascript:return false;" onmousedown="'.delete_popup($subcolumn['title']).'">
										<img src="./orbicon/gfx/gui_icons/delete.png" alt="'._L('delete').'" title="'._L('delete').'" />
									</a>
									</div>')).'</li>'.$trd_column_item);

				$subcolumn = $dbc->_db->fetch_assoc($subcolumn_resource);
				unset($trd_column_item, $trd_column_redirect_url);
			}

			$column_redirect_url = ($column['redirect'] && !$subcolumn_item) ? $column['redirect'] : ORBX_SITE_URL.'/?'.$this->ptr.'=' . $this->urlnormalize($column['permalink']);

			// format title
			$title = str_sanitize($column['title'], STR_SANITIZE_INPUT_TEXT_VALUE);

			if($orbx_mod->validate_module('estate') && !_get_is_orbicon_uri() && ($this->ptr != 'hr')) {
				require_once DOC_ROOT . '/orbicon/modules/estate/menu.trans.php';
				$title = estate_title_trans($column['permalink']);
			}

			// no subcolumns, we're displaying direct link
			if(!$subcolumn_item) {
				$link = sprintf('<a href="%s" title="%s">%s</a>', $column_redirect_url, $title, $title);
			}
			else {
				// depending on our content we're displaying direct or menu-manipulating link
				// * here might be an issue with column_redirect_url & subcolumn_redirect_url MAYBE
				$link = ($column['content']) ? sprintf('<a href="%s" title="%s">%s</a>', $column_redirect_url, $title, $title) : '<a href="javascript: void(null);" rel="nofollow" onclick="javascript: sh(\'subcolumns_of_'.$column['permalink'].'\');" title="'.$title.'">'.$title.'</a>';
			}

			$link = ($column['redirect']) ? sprintf('<a href="%s" title="%s">%s</a>', $column['redirect'], $title, $title) : $link;
			$css = (!$subcolumn_item) ? 'rubrike columns' : 'rubrike_pod subcolumns';

			// set by general setting
			$def_display_subcolumns = (!$_SESSION['site_settings']['v_menu_def_display']) ? 'display:none;' : '';

			// find topmost parent START

			if(!$subcolumn_opened) {

				$subcolumn_opened = $_GET[$this->ptr];
				$r_p = $dbc->_db->query(sprintf('	SELECT 		'.$parent_permalink.'
													FROM 		'.TABLE_COLUMNS.'
													WHERE 		('.$column_permalink.' = %s) AND
																(language = %s)
													LIMIT 		1',
													$dbc->_db->quote($_GET[$this->ptr]),
													$dbc->_db->quote($this->ptr)));

				$a_p = $dbc->_db->fetch_assoc($r_p);

				if($a_p[$parent_permalink]) {
					$subcolumn_opened = $a_p[$parent_permalink];
				}

				while((string) $a_p[$parent_permalink] != '') {
					$r_p = $dbc->_db->query(sprintf('	SELECT 		'.$parent_permalink.'
														FROM 		'.TABLE_COLUMNS.'
														WHERE 		('.$column_permalink.' = %s) AND
																	(language = %s)
														LIMIT 		1',
														$dbc->_db->quote($a_p[$parent_permalink]),
														$dbc->_db->quote($this->ptr)));

					$a_p = $dbc->_db->fetch_assoc($r_p);

					if((string) $a_p[$parent_permalink] != '') {
						$subcolumn_opened = $a_p[$parent_permalink];
					}
				}
			}

			//*
			#END
			#find topmost parent

			// open parent's menu if we're not using option for always opened menus
			if(!$_SESSION['site_settings']['v_menu_def_display']) {

				$def_display_subcolumns = ($column[$column_permalink] == $subcolumn_opened) ? '' : 'display:none;';
			}

			$subcolumn_item = (!$subcolumn_item) ? '' : '<li id="subcolumns_of_'.$column['permalink'].'" class="s orbx_subcols_list" style="'.$def_display_subcolumns.'"><ul class="subcolumns_list">'.$subcolumn_item.'</ul></li>';

			// 1.2.2007, Pavle Gardijan, added on/off status for expand image
			// get it here because we're unsetting it below
			$expand_img = (!$subcolumn_item) ? 'expand_off.png' : 'expand.png';

			// we're in backend
			if(strpos($_GET[$this->ptr], 'orbicon') !== false) {
				$link = $column['title'];
				unset($css, $subcolumn_item);
			}

			// internal pages cannot be expanded
			$expand_column = '<a href="./?'.$this->ptr.'='.$_GET[$this->ptr].'&amp;menu='.$_GET['menu'].'&amp;parent='.$column['permalink'].'"><img src="./orbicon/gfx/gui_icons/'.$expand_img.'" title="'._L('expand').'" /></a>';
			$column_item .= ('
							<li class="'.$css.' top_v_columns"'.$this->is_orbicon($this->admin_layout('id="sort_'.$column['permalink'].'"')).'>'.
								$link.$this->is_orbicon($this->admin_layout('<div>'.$expand_column.'
							<a href="./?'.$this->ptr.'='.$_GET[$this->ptr].'&amp;edit='.$column['permalink'].'">
								<img src="./orbicon/gfx/gui_icons/edit.png" title="'._L('edit').'" />
							</a>
							<a href="./?'.$this->ptr.'='.$_GET[$this->ptr].'&amp;delete_col='.$column['permalink'].
						'" title="'._L('delete').'" onclick="javascript:return false;" onmousedown="'.delete_popup($column['title']).'">
								<img src="./orbicon/gfx/gui_icons/delete.png" alt="'._L('delete').'" title="'._L('delete').'" />
							</a>
							</div>')).'</li>'.$subcolumn_item);

			// * reset
			unset($column_redirect_url, $subcolumn_item, $link);
			$column = $dbc->_db->fetch_assoc($column_resource);
		}

		$column_item .= '</ul></div>';
		$dbc->_db->put_cache($column_item, $sql_column);

		return $column_item;
	}

	/**
	 * return true if column of $permalink has parent
	 *
	 * @param string $permalink
	 * @since 2007.05.24
	 * @author Alen Novakovic <alen.novakovic@orbitum.net>
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return bool
	 */
	function _menu_check_parent($permalink)
	{
		global $dbc;
		$sql = sprintf('	SELECT 		id
							FROM 		' . TABLE_COLUMNS . '
							WHERE 		(parent = %s)',
						$dbc->_db->quote($permalink));
		$resource = $dbc->_db->query($sql);

		return (bool) ($dbc->_db->num_rows($resource) > 0);
	}

	/**
	 * return horizontal menu (no floating menus)
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return string
	 */
	function navigation_horiz_menu()
	{
		if(scan_templates('<!>NAVIGATION_H') < 1) {
			return false;
		}

		$column_permalink = ($_SESSION['site_settings']['us_ascii_uris']) ? 'permalink_ascii' : 'permalink';
		$parent_permalink = ($_SESSION['site_settings']['us_ascii_uris']) ? 'parent_ascii' : 'parent';

		$rubrike = '<ul class="horizontal_menu">';

		global $dbc, $orbx_log, $orbx_mod;

		// first, determine if we're a column or a subcolumn
		$r_t = $dbc->_db->query(sprintf('	SELECT 	'.$parent_permalink.'
											FROM 	'.TABLE_COLUMNS.'
											WHERE 	('.$column_permalink.' = %s) AND
													(language = %s)
											LIMIT 	1', $dbc->_db->quote($_GET[$this->ptr]), $dbc->_db->quote($this->ptr)));
		$type = $dbc->_db->fetch_assoc($r_t);
		$dbc->_db->free_result($r_t);

		$q = sprintf('	SELECT 		*
						FROM 		'.TABLE_COLUMNS.'
						WHERE 		(('.$parent_permalink.' IS NULL) OR ('.$parent_permalink.' = \'\'))
						 			AND
									(menu_name = \'h\') AND
									(language = %s)
						ORDER BY 	sort', $dbc->_db->quote($this->ptr));

		$rubrika = $dbc->_db->get_cache($q . $_GET[$this->ptr]);
		if($rubrika !== null) {
			return $rubrika;
		}

		$r = $dbc->_db->query($q);
		$rubrika = $dbc->_db->fetch_assoc($r);
		$max_tabs = (is_resource($r)) ? $dbc->_db->num_rows($r) : 0;
		$max_tabs = ($max_tabs < 1) ? 1 : $max_tabs;

		$css_width = intval(100 / $max_tabs);

		$css_extra = (100 - ($css_width * $max_tabs));

		$i = 1;
		$_do_once = false;
		$deep_lookup = false;

		while($rubrika) {
			// last tab gets padding
			$css_width = ($i == $max_tabs) ? ($css_width + $css_extra) : $css_width;
			$css_width = ($css_width > 100) ? 100 : $css_width;

			// subcolumns
			$query = sprintf('	SELECT 		*
								FROM 		'.TABLE_COLUMNS.'
								WHERE 		('.$parent_permalink.'=%s) AND
											(menu_name = \'h\') AND
											(language = %s)
								ORDER BY 	sort',
								$dbc->_db->quote($rubrika[$column_permalink]), $dbc->_db->quote($this->ptr));


			$r_subcolumns = $dbc->_db->query($query);
			$podrubrika = $dbc->_db->fetch_assoc($r_subcolumns);

			while($podrubrika) {
				$redirect_url = (!$podrubrika['redirect']) ? ORBX_SITE_URL.'/?'.$this->ptr.'=' . $this->urlnormalize($podrubrika['permalink']) : $podrubrika['redirect'];

				if($orbx_mod->validate_module('estate') && !_get_is_orbicon_uri() && ($this->ptr != 'hr')) {
					include_once DOC_ROOT . '/orbicon/modules/estate/menu.trans.php';
					$podrubrika['title'] = estate_title_trans($podrubrika['permalink'], 'top');
				}

				$podrubrike[] = (
								'<a href="'.
								$redirect_url.
								'" title="'.
								$podrubrika['title'].
								'"> '.
								$podrubrika['title'].
								'</a>');
				$podrubrika = $dbc->_db->fetch_assoc($r_subcolumns);
			}

			$podrubrike = (count($podrubrike) > 1) ? implode(' | ', $podrubrike) : $podrubrike[0];

			$redirect_url = ($rubrika['redirect']) ? $rubrika['redirect'] : ORBX_SITE_URL.'/?'.$this->ptr.'='.$this->urlnormalize($rubrika['permalink']);

			if($orbx_mod->validate_module('estate') && !_get_is_orbicon_uri() && ($this->ptr != 'hr')) {
				include_once DOC_ROOT . '/orbicon/modules/estate/menu.trans.php';
				$rubrika['title'] = estate_title_trans($rubrika['permalink'], 'top');
			}

			$link = sprintf('<a href="%s" title="%s">%s</a>', $redirect_url, $rubrika['title'], $rubrika['title']);

			// we're on front page
			if(($rubrika[$parent_permalink] == null) && ($_GET[$this->ptr] == '') && !$_do_once && $rubrika['redirect']) {
				// compare url's to see if we're really on front page
				if($_SERVER['REQUEST_URL'] == $rubrika['redirect']) {
					$current_subs = '&nbsp;';
					$current_css = ' h_current_tab';
					$_do_once = true;
				}
			}

			// determine opened top-level menu
			if(
			($rubrika[$column_permalink] == $_GET[$this->ptr]) ||
			($type[$parent_permalink] == $rubrika[$column_permalink]) ||
			(strpos(ORBX_SITE_URL . $_SERVER['REQUEST_URI'], $rubrika['redirect']) !== false)) {
				$current_subs = $podrubrike;
				$current_css = ' h_current_tab';
			}
			else {
				/*
				START
				find topmost parent
				*/

				if(($current_subs == '') &&
				($current_subs != '&nbsp;') &&
				!$deep_lookup) {

					$opened_menu = $_GET[$this->ptr];
					$r_p = $dbc->_db->query(sprintf('		SELECT 	'.$parent_permalink.'
															FROM 	'.TABLE_COLUMNS.'
															WHERE 	('.$column_permalink.' = %s) AND
																	(language = %s)
															LIMIT 	1', $dbc->_db->quote($_GET[$this->ptr]), $dbc->_db->quote($this->ptr)));
					$a_p = $dbc->_db->fetch_assoc($r_p);

					while((string) $a_p[$parent_permalink] != '') {
						$r_p = $dbc->_db->query(sprintf('	SELECT 	'.$parent_permalink.'
															FROM 	'.TABLE_COLUMNS.'
															WHERE 	('.$column_permalink.' = %s) AND
																	(language = %s)
															LIMIT 	1', $dbc->_db->quote($a_p[$parent_permalink]), $dbc->_db->quote($this->ptr)));
						$a_p = $dbc->_db->fetch_assoc($r_p);

						if((string) $a_p[$parent_permalink] != '') {
							$opened_menu = $a_p[$parent_permalink];
						}
					}
				}

				/*
				END
				find topmost parent
				*/

				// open parent's menu
				if($rubrika[$column_permalink] == $opened_menu) {

					$current_subs = $podrubrike;
					$current_css = ' h_current_tab';
					$deep_lookup = true;
				}
			}

			$podrubrike = ($podrubrike == '') ? '&nbsp;' : $podrubrike;

			// add last tab class
			$current_css = ($i == $max_tabs) ? $current_css.' h_menu_tab_last' : $current_css;
			// add first tab class
			$current_css = ($i == 1) ? $current_css.' h_menu_tab_first' : $current_css;

			$rubrike .= ('<li class="h_menu_tab'.$current_css.'">'.
						$link.
						'<input type="hidden" id="sub_col_' . $rubrika['permalink'] . '" value="' . htmlspecialchars($podrubrike) . '" />'.
						'</li>');

			// reset
			$podrubrike = null;
			$current_css = null;
			$rubrika = $dbc->_db->fetch_assoc($r);
			$i ++;
		}

		$current_subs = ($current_subs == '') ? '&nbsp;' : $current_subs;
		$rubrike = '<div class="h_menu_container">' . $rubrike . '</ul></div><div id="h_menu_subcontainer">' . $current_subs . '</div>';

		$dbc->_db->put_cache($rubrike, $q . $_GET[$this->ptr]);
		return $rubrike;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return unknown
	 */
	function __html_metatags_replace()
	{
		$url = parse_url($_SERVER['REQUEST_URL']);
		return array(
			'{$admin_head_tags}' => $this->__admin_head_tags(),
			'{$domain}' => DOMAIN,
			'{$scheme}' => SCHEME,
			'{$server_name}' => $_SERVER['SERVER_NAME'],
			'{$domain_no_www}' => DOMAIN_NO_WWW,
			'{$orbx_ln}' => $this->ptr,
			'{$orbx_site_url}' => ORBX_SITE_URL,
			'{$uri_path}' => ORBX_URI_PATH,
			'{$domain_name}' => DOMAIN_NAME,
			'{$domain_owner}' => DOMAIN_OWNER,
			'{$domain_email}' => DOMAIN_EMAIL,
			'{$domain_desc}' => (($this->metatags['description']) ? $this->metatags['description'] : DOMAIN_DESC),
			'{$domain_custom_meta}' => DOMAIN_CUSTOM_METATAGS,
			'{$domain_keywords}' => DOMAIN_KEYWORDS,
			'{$orbicon_full_name}' => ORBX_FULL_NAME,
			'{$orbx_ajax_id}' => get_ajax_id(),
			'{$get_request}' => $url['query'],
			'{$orbx_frontend}' => ((_get_is_orbicon_uri()) ? 'false' : 'true'),
			'{$year}' => date('Y'),
			'{$user_admin}' => ((get_is_admin()) ? 'true' : 'false'),
			'{$user_member}' => ((get_is_member() ? 'true' : 'false')),
			'{$orbx_build}' => ORBX_BUILD,
			'{$user_name}' => "'{$_SESSION['user.r']['contact_name']}'",
			'{$user_surname}' => "'{$_SESSION['user.r']['contact_surname']}'",
			'{$user_email}' => "'{$_SESSION['user.r']['contact_email']}'",
			'{$user_tel}' => "'{$_SESSION['user.r']['contact_phone']}'",
			'{$user_mob}' => "'{$_SESSION['user.r']['contact_gsm']}'",
			'{$user_address}' => "'{$_SESSION['user.r']['contact_address']}'",
			'{$user_zip}' => "'{$_SESSION['user.r']['contact_zip']}'",
			'{$user_city}' => "'{$_SESSION['user.r']['contact_town_text']}'"



		);
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $keywords
	 * @return unknown
	 */
	function get_html_metatags($keywords)
	{
		if((scan_templates('<!>METATAGS') < 1) && !get_is_admin() && is_file(ORBX_SYS_CONFIG)) {
			return false;
		}

		$meta_replace = array();

		global $orbx_mod;

		// rss newsticker
		if((scan_templates('<!>NWS_TICKER') > 0) && $orbx_mod->validate_module('rss')) {
			include_once DOC_ROOT . '/orbicon/modules/rss/class.rss.php';
			$newsticker = new RSS_Manager;
			$newsticker_content = $newsticker->get_newsticker_content();
			$meta_replace['{$newsticker_content}'] = $newsticker_content;
			unset($newsticker, $newsticker_content);
		}
		else {
			$meta_replace['{$newsticker_content}'] = '';
		}

		if($keywords != null) {
			unset($meta_replace['{$domain_keywords}']);
			$meta_replace['{$domain_keywords}'] = $keywords;
		}

		// these tags aren't useful to major search bots
		if(!get_is_search_engine_bot() /*&& $_SESSION['site_settings']['searcheng_filter']*/) {
			$metatags_robots_exclude = file_get_contents(DOC_ROOT.'/orbicon/templates/meta.robots.exc.html');
			$meta_replace['{$metatags_robots_exclude}'] = $metatags_robots_exclude;

			// site's css / js shouldn't be used within orbicon gui
			if(_get_is_orbicon_uri()) {
				$site_css = '';
				$site_js = '';
			}
			else {
				if(ORBX_GZIP) {
					if(!is_file(DOC_ROOT . '/site/gfx/gzip.server3.php')) {
						$r = fopen(DOC_ROOT . '/site/gfx/gzip.server3.php', 'wb');
						fwrite($r, "<?php include '../../orbicon/controler/gzip.server.php'; ?>");
						fclose($r);
					}
					$site_css = '@import url("' . ORBX_SITE_URL . '/site/gfx/gzip.server3.php?file=/site/gfx/site.css&amp;'.ORBX_BUILD.'");';
					$site_js = '<script type="text/javascript" src="' . ORBX_SITE_URL . '/site/gfx/gzip.server3.php?file=/site/gfx/site.js&amp;'.ORBX_BUILD.'"></script>';
				}
				else {
					$site_css = '@import url("' . ORBX_SITE_URL . '/site/gfx/site.css?'.ORBX_BUILD.'");';
					$site_js = '<script type="text/javascript" src="' . ORBX_SITE_URL . '/site/gfx/site.js?'.ORBX_BUILD.'"></script>';
				}
			}

			// rss should be loaded only if we have news
			$site_rss = ($orbx_mod->validate_module('news')) ? '<link rel="alternate" type="application/rss+xml" title="'.DOMAIN_NAME.'" href="'.ORBX_SITE_URL.'/site/mercury/rss.'.$this->ptr.'.xml" /><link rel="alternate" type="application/rss+xml" title="'.DOMAIN_NAME.' (x)" href="'.ORBX_SITE_URL.'/site/mercury/rss_top.'.$this->ptr.'.xml" />' : '';
			$site_rss .= $this->feed_links;

			// load preview mode site.css if requested
			$site_css = (($this->get_preview_mode()) && ($site_css != '') && ($_GET['preview_mode'] == 'css')) ? '@import url("'.ORBX_SITE_URL.'/site/mercury/pre-site.css");' : $site_css;
			$site_css = ($_GET[$this->ptr] == 'orbicon.setup') ? '' : $site_css;
			$site_css = (is_file(DOC_ROOT . '/site/gfx/site.css')) ? $site_css : '';
			$site_js = ($_GET[$this->ptr] == 'orbicon.setup') ? '' : $site_js;
			$site_js = (is_file(DOC_ROOT . '/site/gfx/site.js')) ? $site_js : '';
			$meta_replace['{$site_css}'] = $site_css;
			$meta_replace['{$site_js}'] = $site_js;
			$meta_replace['{$site_rss}'] = $site_rss;

			$user_metatags = (ORBX_GZIP) ? file_get_contents(DOC_ROOT . '/orbicon/templates/gz.meta.user.html') : file_get_contents(DOC_ROOT . '/orbicon/templates/meta.user.html');
			$meta_replace['{$user_metatags}'] = $user_metatags;
		}
		else {
			$meta_replace['{$metatags_robots_exclude}'] = null;
			$meta_replace['{$site_css}'] = null;
			$meta_replace['{$site_js}'] = null;
			$meta_replace['{$user_metatags}'] = null;
			$meta_replace['{$site_rss}'] = null;
		}

		// zoom text js
		if($_SESSION['site_settings']['text_zoom']) {
			$meta_replace['{$zoom_text_js}'] = '<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/javascript/orbicon.zoom.js?'.ORBX_BUILD.'"></script>';
		}
		else {
			$meta_replace['{$zoom_text_js}'] = '';
		}

		$meta_replace = array_merge($meta_replace, $this->__html_metatags_replace());

		if($this->metatags['keywords']) {
			$meta_replace['{$domain_keywords}'] = $this->metatags['keywords'];
		}

		$metatags = file_get_contents(DOC_ROOT.'/orbicon/templates/meta.general.html');

		$metatags = str_replace(array_keys($meta_replace), array_values($meta_replace), $metatags);
		// bug?!!?
		$metatags = str_replace('{$newsticker_content}', $meta_replace['{$newsticker_content}'], $metatags);
		$metatags = str_replace('{$site_css}', $meta_replace['{$site_css}'], $metatags);
		$metatags = str_replace('{$site_js}', $meta_replace['{$site_js}'], $metatags);

		unset($meta_replace, $metatags_robots_exclude, $user_metatags);

		if($_SESSION['site_settings']['minify_html']) {
			return min_str($metatags, true);
		}

		return $metatags;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return unknown
	 */
	function __admin_head_tags()
	{
		if(!get_is_admin()) {
			return null;
		}

		if(get_is_search_engine_bot()) {
			return null;
		}

		if(_get_is_orbicon_uri()) {
			$admin = (ORBX_GZIP) ? file_get_contents(DOC_ROOT.'/orbicon/templates/gz.meta.admin.html') : file_get_contents(DOC_ROOT.'/orbicon/templates/meta.admin.html');
		}
		else {
			$admin = file_get_contents(DOC_ROOT.'/orbicon/templates/admin.menucss.html');
		}
		return $admin . $this->_yui_calendar_head_tags();
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return unknown
	 */
	function _yui_calendar_head_tags()
	{
		if(!get_is_admin()) {
			return null;
		}

		$x = explode('/', $_GET[$this->ptr]);
		if($x[2] != 'news' && $x[2] != 'polls') {
			return null;
		}

		$multiselect = ($x[2] == 'polls') ? 'true' : 'false';

		$metatags = ($x[2] != 'polls') ? file_get_contents(DOC_ROOT.'/orbicon/templates/meta.yui.cal.html') : file_get_contents(DOC_ROOT.'/orbicon/templates/meta.yui.calcombo.html');

		$metatags = str_replace('{$multiselect}', $multiselect, $metatags);
		return $metatags;
	}

	/**
	 * set a page's title tag. passing null will unset it
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @todo move to template class
	 * @param string $title
	 */
	function set_page_title($title)
	{
		if(is_null($title)) {
			unset($_SESSION['custom_title']);
		}
		else {
			$_SESSION['custom_title'] = $title;
		}
	}

	/**
	 * return custom page title
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @todo move to template class
	 * @return string
	 */
	function get_page_title()
	{
		return $_SESSION['custom_title'];
	}

	/**
	 * Add new feed to column
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $url
	 * @param string $title
	 */
	function add_feed_link($url, $title)
	{
		$this->feed_links .= '<link rel="alternate" type="application/rss+xml" title="'.$title.'" href="'.$url.'" />';
	}

	/**
	 * Set custom metatags
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $name
	 * @param string $content
	 */
	function set_page_metatag($name, $content)
	{
		$this->metatags[$name] = $content;
	}

	/**
	 * Update editor's last location
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param int $editor_id
	 * @param string $url
	 */
	function update_last_location($editor_id, $url)
	{
		global $dbc;

		$q = sprintf('	UPDATE 		'.TABLE_EDITORS.'
						SET 		last_location=%s
						WHERE 		(id=%s)
						LIMIT 		1',
						$dbc->_db->quote($url),
						$dbc->_db->quote($editor_id));
		$dbc->_db->query($q);
	}

	/**
	 * return type of column
	 *
	 * @param string $permalink
	 * @return string
	 */
	function get_column_type($permalink)
	{
		global $dbc;

		$r = $dbc->_db->query(sprintf('	SELECT 		menu_name
										FROM 		'.TABLE_COLUMNS.'
										WHERE 		(permalink = %s) AND
													(language = %s)',
		$dbc->_db->quote($permalink), $dbc->_db->quote($this->ptr)));
		$a = $dbc->_db->fetch_assoc($r);

		return $a['menu_name'];
	}

	function add2breadcrumbs($url)
	{
		$this->custom_breadcrumbs[] = $url;
	}

	/**
	 * return parent permalink column with $permalink
	 *
	 * @param string $permalink
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return string
	 */
	function get_has_parent($permalink)
	{
		global $dbc;
		$sql = sprintf('	SELECT 		parent
							FROM 		' . TABLE_COLUMNS . '
							WHERE 		(permalink = %s)
							LIMIT		1',
						$dbc->_db->quote($permalink));
		$r = $dbc->_db->query($sql);
		$a = $dbc->_db->fetch_assoc($r);

		return $a['parent'];
	}

	function load_column_name($permalink)
	{
		global $dbc;
		$sql = sprintf('	SELECT 		title
							FROM 		' . TABLE_COLUMNS . '
							WHERE 		(permalink = %s)
							LIMIT		1',
						$dbc->_db->quote($permalink));
		$r = $dbc->_db->query($sql);
		$a = $dbc->_db->fetch_assoc($r);

		return $a['title'];
	}

	function urlnormalize($url, $force = false)
	{
		if($_SESSION['site_settings']['us_ascii_uris'] || $force) {

			$url = urldecode($url);

			$chars = array(
			'č' => 'c',
			'ć' => 'c',
			'ž' => 'z',
			'š' => 's',
			'đ' => 'dj',
			'Č' => 'C',
			'Ć' => 'C',
			'Ž' => 'Z',
			'Š' => 'S',
			'Đ' => 'Dj'
			);

			$url = str_replace(array_keys($chars), array_values($chars), $url);
			//$url = strtolower($url);
		}
		return urlencode($url);
	}
}

?>