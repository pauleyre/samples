<?php
/**
 * Library for columns
 * @author Pavle Gardijan <pavle.gardijan@gmaik.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package SystemFE
 * @version 1.5
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-10-22
 */

	define('COLUMN_TYPE_NEWS', 		1);
	define('COLUMN_TYPE_FORM', 		2);
	define('COLUMN_TYPE_COLUMN', 	4);
	define('COLUMN_TYPE_MODULE', 	8);
	define('COLUMN_TYPE_GALLERY', 	16);
	define('COLUMN_TYPE_VIDEO', 	32);
//	define('COLUMN_TYPE_NEWS', 		64);

	/**
	 * loads column data
	 *
	 *
	 * @param bool $force_load
	 * @return array
	 */
	function load_column($force_load = false)
	{
		global $dbc, $orbicon_x, $orbx_mod;

		$spec_column = ($orbicon_x->ptr == 'hr') ? 'special_right_col.html' : "{$orbicon_x->ptr}.special_right_col.html";

		$ok_override = 0;

		if(isset($_GET['edit']) || $force_load) {
			$permalink = ($force_load) ? $_GET[$orbicon_x->ptr] : $_GET['edit'];

			if($force_load) {
				$permalink = explode('/', $permalink);
				$permalink = $permalink[0];

				$a = $dbc->_db->get_cache('SELECT orbicon_column_orbicon '.$permalink);
				if($a !== null) {
					return $a;
				}
			}

			include_once DOC_ROOT . '/orbicon/lib/iso8859_utf8/iso8859_utf8.php';
			$permalink_hr = iso88592utf_hr($permalink, 'ISO-8859-1');
			$permalink_hr = iso88592utf_hr($permalink_hr);

			$a = sql_assoc('	SELECT 	*, id AS true_id
								FROM 	'.TABLE_COLUMNS.'
								WHERE 	((permalink=%s) OR (permalink=%s)) AND
										(language = %s)
								LIMIT 	1', array($permalink, $permalink_hr, $orbicon_x->ptr));

			if(!$a['id'] && $_SESSION['site_settings']['us_ascii_uris']) {

				// need to replace these since they're written that way in the DB
				$permalink_no_par = str_replace(array('(', ')'), array('%28', '%29'), $permalink);


				$a = sql_assoc('SELECT 	*, id AS true_id
								FROM 	'.TABLE_COLUMNS.'
								WHERE 	(permalink_ascii=%s) AND
										(language = %s)
								LIMIT 	1', array($permalink_no_par, $orbicon_x->ptr));
			}

			if($a['id']) {
				$ok_override = 1;
			}

			// news
			if(!$a['id'] && $force_load && $orbx_mod->validate_module('news')) {
				$a = sql_assoc('SELECT 	*
								FROM 	'.TABLE_NEWS.'
								WHERE 	((permalink=%s) OR (permalink=%s)) AND
										(language = %s)
								LIMIT 1', array($permalink, $permalink_hr, $orbicon_x->ptr));

				if(!$a['id'] && $_SESSION['site_settings']['us_ascii_uris']) {
					$a = sql_assoc('	SELECT 	*
										FROM 	'.TABLE_NEWS.'
										WHERE 	(permalink_ascii=%s) AND
												(language = %s)
										LIMIT 	1', array($permalink, $orbicon_x->ptr));
				}

				if($a['id'] && !get_is_search_engine_bot()) {
					sql_update('	UPDATE 		'.TABLE_NEWS.'
									SET			views = (views + 1)
									WHERE 		(id=%s)',
									$a['id']);
				}

				if($a['id']) {
					$news_id = true;
				}
			}

			// forms
			if(($a['type'] == 'form') && $force_load) {
				$a_f = sql_assoc('	SELECT 	*
									FROM 	'.TABLE_FORMS.'
									WHERE 	(permalink=%s) AND
											(language = %s)
									LIMIT 	1',
								array($a['content'], $orbicon_x->ptr));

				// we're logged in and we have peoplering installed, redirect now
				if($a_f['template'] == 'register') {
					if(get_is_member() && $orbx_mod->validate_module('peoplering')) {
						if($orbx_mod->validate_module('estate')) {
							redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.estate.new');
						}
						else {
							redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.peoplering');
						}
					}
				}

				// try to locate translated template or load default english
				$template_file = (is_file(DOC_ROOT . '/orbicon/templates/' . $orbicon_x->ptr . '.form.'.$a_f['template'].'.php')) ? $orbicon_x->ptr . '.form.'.$a_f['template'].'.php' : 'en.form.'.$a_f['template'].'.php';

				$a['magister_content'] = include DOC_ROOT.'/orbicon/templates/' . $template_file;

				// append lead text above the form
				$r_ = sql_res('	SELECT 		content
								FROM 		'.MAGISTER_CONTENTS.'
								WHERE 		(live = 1) AND
											(hidden = 0) AND
											(question_permalink = %s) AND
											(language = %s)
								ORDER BY 	uploader_time', array($a_f['linked_text'], $orbicon_x->ptr));
				$a_ = $dbc->_db->fetch_assoc($r_);

				while($a_) {
					$lead_txt .= $a_['content'];
					$a_ = $dbc->_db->fetch_assoc($r_);
				}
				$dbc->_db->free_result($r_);

				// add admin edit shortcut to magister db
				if(!empty($lead_txt) && get_is_admin()) {
					$lead_txt = $orbicon_x->admin_layout('<p id="admin_tool"><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/magister&amp;read=clanak/'.urlencode($a_f['linked_text']).'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/edit.png"></a></p>').$lead_txt;
				}

				$a['magister_content'] = $lead_txt . $a['magister_content'];
				unset($lead_txt);

				// new design append
				if(strpos($a['magister_content'], 'noCol') === false) {
						$a['magister_content'] =
				'<div id="innerLeftCol"><div id="innerContent">
				<h2 class="buster"><!>COLUMN_TITLE</h2>
					'.$a['magister_content'].'

				</div></div>
				' . file_get_contents(DOC_ROOT . '/site/gfx/' . $spec_column);
				}
			}
			else if(($a['type'] == 'photo') && $force_load) {
				include_once DOC_ROOT.'/orbicon/class/inc.mmedia.php';
				$a['magister_content'] = print_image_gallery($a['content']);
			}
			else if(($a['type'] == 'video') && $force_load) {
				// we have a module
				if($orbx_mod->validate_module('video-gallery')) {
					global $video_gallery;
					$video_gallery = $a['content'];
					$a['magister_content'] = include_once DOC_ROOT . '/orbicon/modules/video-gallery/render.video.php';

					if(strpos($a['magister_content'], 'innerLeftCol') === false) {
						$a['magister_content'] =
				'<div id="innerLeftCol">
					<div id="innerContent">
					<h2 class="buster"><!>COLUMN_TITLE</h2>
						'.$a['magister_content'].'
					</div>
				</div>
				' . file_get_contents(DOC_ROOT . '/site/gfx/' . $spec_column);
				}

				}
				else {
					include_once DOC_ROOT . '/orbicon/class/inc.mmedia.php';
					$a['magister_content'] = print_video_gallery($a['content']);
				}
			}
			else if(($a['type'] == 'data') && $force_load) {
				include_once DOC_ROOT . '/orbicon/class/inc.mmedia.php';
				$a['magister_content'] = print_download_gallery($a['content']);

				if(strpos($a['magister_content'], 'innerLeftCol') === false) {
						$a['magister_content'] =
				'<div id="innerLeftCol">
					<div id="innerContent">
					<h2 class="buster"><!>COLUMN_TITLE</h2>
						'.$a['magister_content'].'
					</div>
				</div>
				' . file_get_contents(DOC_ROOT . '/site/gfx/' . $spec_column);
				}
			}
			else {
				$r_ = sql_res('		SELECT 		content
									FROM 		'.MAGISTER_CONTENTS.'
									WHERE 		(live = 1) AND
												(hidden = 0) AND
												(question_permalink = %s) AND
												(language = %s)
									ORDER BY 	uploader_time', array($a['content'], $orbicon_x->ptr));
				$a_ = $dbc->_db->fetch_assoc($r_);

				while($a_) {
					$a['magister_content'] .= $a_['content'];
					$a_ = $dbc->_db->fetch_assoc($r_);
				}
				$dbc->_db->free_result($r_);

				// content is empty? perhaps we have news with intro an no content
				if($force_load && empty($a['magister_content']) && !empty($a['intro'])) {
					$r_ = sql_res('	SELECT	content
									FROM	'.MAGISTER_CONTENTS.'
									WHERE	(live = 1) AND
											(hidden = 0) AND
											(id = %s) AND
											(language = %s)
									LIMIT	1', array($a['intro'], $orbicon_x->ptr));
					$a_ = $dbc->_db->fetch_assoc($r_);
					$a['magister_content'] = $a_['content'];
					$dbc->_db->free_result($r_);
				}

				// free memory
				$a_ = null;

				// add admin edit shortcut to magister db
				if(!empty($a['magister_content']) && get_is_admin()) {
					$a['magister_content'] = $orbicon_x->admin_layout('<p id="admin_tool"><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/magister&amp;read=clanak/'.urlencode($a['content']).'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/edit.png"></a></p>').$a['magister_content'];
				}

				// replace with content map
				if(empty($a['magister_content'])) {
					$a['magister_content'] = get_column_content_map($permalink);
				}

				// new desing append
				if(strpos($a['magister_content'], 'innerLeftCol') === false) {
					if($news_id) {
						$a['magister_content'] =
						'<div id="innerLeftCol">
							<div id="innerContent">

<ul id="tools">
	<li id="smaller"><a onclick="save_stylesheet(\'small\')" href="javascript:;" title="Smanji tekst">Manja</a></li>
	<li id="normal"><a onclick="save_stylesheet(\'norm\')" href="javascript:;" title="Normalan tekst">Normalna</a></li>
	<li id="bigger"><a onclick="save_stylesheet(\'big\')" href="javascript:;" title="Povećaj tekst">Veća</a></li>
	<li class="print"><a href="COLUMN_PRINT_LINK" title="Ispiši stranicu">Ispiši</a></li>
	<li class="send"><a onclick="show_send2friend()" href="javascript:;" title="Pošalji prijatelju">Pošalji</a></li>
</ul>

							<h2 class="buster"><!>COLUMN_TITLE</h2>
<div class="clr"></div>

								'.$a['magister_content'].'
<!--
<ul id="printSend" class="bottom">
	<li class="print"><a href="COLUMN_PRINT_LINK" title="Ispiši stranicu">Ispiši</a></li>
	<li class="send"><a onclick="show_send2friend()" href="javascript:;" title="Pošalji prijatelju">Pošalji</a></li>
</ul>
-->
							</div>
						</div>';
						$a['magister_content'] .= file_get_contents(DOC_ROOT . '/site/gfx/' . $spec_column);
					}
					else {
						$a['magister_content'] =
				'<div id="innerLeftCol">
					<div id="innerContent">
					<h2 class="buster"><!>COLUMN_TITLE</h2>
						'.$a['magister_content'].'
					</div>
				</div>';
					}
				}
			}

			// special
			// sitemap
			if($permalink == 'sitemap') {
				$a['title'] = _L('sitemap');
				$a['id'] = $a['title'];
				$a['magister_content'] = $orbicon_x->generate_sitemap_index();

				if(strpos($a['magister_content'], 'innerLeftCol') === false) {
						$a['magister_content'] =
				'<div id="innerLeftCol">
					<div id="innerContent">
					<h2 class="buster"><!>COLUMN_TITLE</h2>
						'.$a['magister_content'].'
					</div>
				</div>
				' . file_get_contents(DOC_ROOT . '/site/gfx/' . $spec_column);
				}
			}
			// search
			else if($permalink == 'attila') {
				include_once DOC_ROOT.'/orbicon/class/class.attila.php';
				$attila = new Attila;

				$a['title'] = _L('search_results');
				$a['id'] = $a['title'];
				$a['magister_content'] = $attila->atl_run();
				unset($attila);

				// new design append
					if(strpos($a['magister_content'], 'innerLeftCol') === false) {
							$a['magister_content'] =
					'<div id="innerLeftCol">
					<div id="innerContent">
					<h2 class="buster"><!>COLUMN_TITLE</h2>
						'.$a['magister_content'].'

					</div></div>
					' . file_get_contents(DOC_ROOT . '/site/gfx/' . $spec_column);
				}
			}

			// we want override effect
			if($_SESSION['site_settings']['override_module'] && !isset($_GET['no-override']) && $ok_override) {
				$permalink = 'mod.' . $_SESSION['site_settings']['override_module'];
				$ok_override = 2;
			}

			$module = $orbx_mod->get_page_module($permalink);

			if($module !== null) {
				if($ok_override != 2) {
					$a['title'] = $module['title'];
				}
				$a['id'] = $module['id'];
				$a['magister_content'] = $module['magister_content'];

				if(in_array($permalink, array('mod.exchange-rates'))) {

					// new desing append
					if(strpos($a['magister_content'], 'noCol') === false) {
							$a['magister_content'] =
					'<div id="noCol">
					<h2 class="buster"><!>COLUMN_TITLE</h2>
						'.$a['magister_content'].'
					</div>
					<style type="text/css">#breadcrumbs {width:893px}</style>
					';
					}
				}
				else {

				// new design append
					if(strpos($a['magister_content'], 'innerLeftCol') === false) {

							$a['magister_content'] =
					'<div id="innerLeftCol"><div id="innerContent">

					<h2 class="buster"><!>COLUMN_TITLE</h2>

						'.$a['magister_content'].'

					</div></div>' . file_get_contents(DOC_ROOT . '/site/gfx/' . $spec_column);
					}

					if(in_array($permalink, array('mod.faq'))) {
						$a['magister_content'] = str_replace('<div id="goodToKnow">', (include  DOC_ROOT . '/orbicon/modules/faq/sumbitquestion.php') . '<div id="goodToKnow" class="h">', $a['magister_content']);

						$a['magister_content'] = str_replace('<h2 class="buster">', '
						<ul id="tools">
	<li id="smaller"><a onclick="save_stylesheet(\'small\')" href="javascript:;" title="Smanji tekst">Manja</a></li>
	<li id="normal"><a onclick="save_stylesheet(\'norm\')" href="javascript:;" title="Normalan tekst">Normalna</a></li>
	<li id="bigger"><a onclick="save_stylesheet(\'big\')" href="javascript:;" title="Povećaj tekst">Veća</a></li>
	<li class="print"><a href="COLUMN_PRINT_LINK" title="Ispiši stranicu">Ispiši</a></li>
	<li class="send"><a onclick="show_send2friend()" href="javascript:;" title="Pošalji prijatelju">Pošalji</a></li>
</ul>
<h2 class="buster">', $a['magister_content']);

					}
					else if(in_array($permalink, array('mod.news-index'))) {
						$a['magister_content'] = str_replace('<div id="goodToKnow">', (include  DOC_ROOT . '/orbicon/modules/news-alerts/render.frontend.php') . '<div id="goodToKnow" class="h">', $a['magister_content']);

						$a['magister_content'] = str_replace('<div id="serFAQ">',

						'<dl id="related"> <dt><strong>Vezani sadržaj</strong></dt>

<dd><a href="./?hr=kontakt-za-medije">
Kontakt za medije</a><br /></dd>

<dd><a href="./?hr=mod.faq">Pitajte nas</a></dd>
<dd><a href="./?hr=logotipovi">Logotipovi</a></dd>

</dl>
<div id="serFAQ">',

$a['magister_content']);

					}
				}

				unset($module);
			}

			if($_SESSION['site_settings']['text_zoom']) {
				$a['magister_content'] = '<div ondblclick="javascript: ZoomText(this,12,18,10);">'.$a['magister_content'].'</div>';
			}

			// signal 404 Not Found
			if(empty($a['id']) && $force_load) {
				$a['title'] = '404 Not Found';
				// why would we close session?!
				//session_write_close();
				header('HTTP/1.1 404 Not Found', true);
				$_SESSION['cache_status'] = 404;
			}

			// this is locked content
			if(!empty($a['id'])) {
				if($_SESSION['current_zone'] !== null) {
					foreach($_SESSION['current_zone'] as $zone) {
						if($zone['locked'] && !_get_is_orbicon_uri() && !get_is_member()) {
							// we're logged in and we have peoplering installed
							if(get_is_member() && $orbx_mod->validate_module('peoplering')) {
								redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.peoplering');
							}

							$a['magister_content'] = get_login_form();
						}
					}
				}
			}

			// cache
			if($force_load) {
				if(!isset($permalink)) {
					$permalink = ($force_load) ? $_GET[$orbicon_x->ptr] : $_GET['edit'];

					if($force_load) {
						$permalink = explode('/', $permalink);
						$permalink = $permalink[0];
					}
				}

				$dbc->_db->put_cache($a, 'SELECT orbicon_column_orbicon ' . $permalink . serialize(array_merge($_POST, $_GET)));
			}

			return $a;
		}
		return null;
	}

	/**
	 * return children pages of $permalink in HTML unordered list
	 *
	 * @param string $permalink
	 * @return string
	 */
	function get_column_content_map($permalink)
	{
		if($permalink == '') {
			trigger_error('get_column_content_map() expects parameter 1 to be non-empty', E_USER_WARNING);
			return false;
		}

		global $dbc, $orbicon_x;
		$map = '';

		include_once DOC_ROOT . '/orbicon/lib/iso8859_utf8/iso8859_utf8.php';
		$permalink_hr = iso88592utf_hr($permalink, 'ISO-8859-1');
		$permalink_hr = iso88592utf_hr($permalink_hr);

		$r = $dbc->_db->query(sprintf('	SELECT		title, permalink
										FROM 		'.TABLE_COLUMNS.'
										WHERE 		((parent = %s) OR (parent=%s)) AND
													(language = %s)
										ORDER BY 	sort',
										$dbc->_db->quote($permalink), $dbc->_db->quote($permalink_hr), $dbc->_db->quote($orbicon_x->ptr)));
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$url = (empty($a['redirect'])) ? ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$a['permalink'] : $a['redirect'];
			$map .= '<li><a href="'.$url.'" title="'.$a['title'].'">'.$a['title'].'</a></li>';

			$a = $dbc->_db->fetch_assoc($r);
		}
		$dbc->_db->free_result($r);

		$map = ($map == '') ? '' : '<h3>'._L('linked_content').'</h3><ul class="orbx_linked_content">'.$map.'</ul>';
		return $map;
	}

	/**
	 * return login / register form
	 *
	 * @return string
	 */
	function get_login_form()
	{
		global $orbicon_x;
		// try to locate translated template
		$template_file = (is_file(DOC_ROOT . '/orbicon/templates/' . $orbicon_x->ptr . '.form.register.php')) ? $orbicon_x->ptr . '.form.register.php' : 'en.form.register.php';

		$form = include DOC_ROOT . '/orbicon/templates/' . $template_file;
		return '<h2 class="login_to_view">' . _L('login_to_view_content') . '</h2>' . $form;
	}

	/**
	 * Get column title
	 *
	 * @param string $permalink
	 * @return string
	 */
	function get_column_title($permalink)
	{
		global $dbc, $orbicon_x;

		include_once DOC_ROOT . '/orbicon/lib/iso8859_utf8/iso8859_utf8.php';
		$permalink_hr = iso88592utf_hr($permalink, 'ISO-8859-1');
		$permalink_hr = iso88592utf_hr($permalink_hr);

		$a = sql_assoc('SELECT 	title
						FROM 	'.TABLE_COLUMNS.'
						WHERE 	((permalink=%s) OR (permalink=%s)) AND
								(language = %s)
						LIMIT 	1', array($permalink, $permalink_hr, $orbicon_x->ptr));

		return $a['title'];
	}

?>