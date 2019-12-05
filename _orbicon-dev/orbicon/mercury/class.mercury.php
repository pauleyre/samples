<?php
/**
 * Mercury class
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package OrbiconFE
 * @subpackage Mercury
 * @version 1.5
 * @link http://
 * @license http://
 * @since 2006-05-01
 */

class Mercury
{
	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $category
	 */
	function delete_category($category)
	{
		if($category != '') {
			global $dbc, $orbicon_x;

			$dbc->_db->query(sprintf('	DELETE
										FROM 	'.MERCURY_CATEGORIES.'
										WHERE 	(permalink = %s)
										LIMIT 	1',
										$dbc->_db->quote($category)));

			redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mercury');
		}
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $file
	 */
	function delete_file($file)
	{
		if($file != '') {
			global $dbc, $orbicon_x;
			$name = $dbc->_db->query(sprintf('	SELECT 		content
												FROM 		'.MERCURY_FILES.'
												WHERE 		(permalink = %s)
												LIMIT 		1',
												$dbc->_db->quote($file)));
			$name = $dbc->_db->fetch_assoc($name);

			unlink(DOC_ROOT . '/site/mercury/' . $name['content']);
			// delete backup file
			unlink(DOC_ROOT . '/site/mercury/bck/' . $name['content'] . '.bk');
			$dbc->_db->query(sprintf('	DELETE
										FROM 		'.MERCURY_FILES.'
										WHERE 		(permalink = %s)
										LIMIT 		1', $dbc->_db->quote($file)));

			redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mercury');
		}
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return unknown
	 */
	function get_category_menu()
	{
		global $dbc, $orbicon_x;
		$r = $dbc->_db->query('	SELECT 		*
								FROM 		'.MERCURY_CATEGORIES.'
								ORDER BY 	permalink');
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$r_c = $dbc->_db->query(sprintf('	SELECT 		COUNT(id)
												FROM 		'.MERCURY_FILES.'
												WHERE 		(live = 1) AND
															(hidden = 0) AND
															(category = %s)', $dbc->_db->quote($a['permalink'])));
			$a_c = $dbc->_db->fetch_array($r_c);

			$delete_url = '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mercury&amp;del_cat='.$a['permalink'].'" onclick="javascript:return false;" onmousedown="' . delete_popup($a['name']) . '"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/delete.png" /></a>';

			$menu .= '<li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mercury&amp;show_only='.$a['permalink'].'">'.$a['name'].'</a><br />
			<div class="div-controller">'.$delete_url.' | '._L('docs_lc').': '.$a_c[0].'</div></li>';

			$a = $dbc->_db->fetch_assoc($r);
		}

		// unsorted
		$r_c = $dbc->_db->query('		SELECT 		COUNT(id)
										FROM 		'.MERCURY_FILES.'
										WHERE 		(live = 1) AND
													(hidden = 0) AND
													((category = \'\') OR (category IS NULL))');
		$a_c = $dbc->_db->fetch_array($r_c);

		if($a_c[0] > 0) {
				$menu.= '<li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mercury&amp;show_only=_orbx_unsorted">'._L('unsorted').'</a><br />
					<div class="div-controller">'._L('docs_lc').': '.$a_c[0].'</div></li>';
		}

		$menu = '
				<div class="category-picker-filter-images">
					<img src="'.ORBX_SITE_URL.'/orbicon/gfx/mini_browser_gui/picker-documents.gif" />
					<h1>' . _L('categories') . ' - ' . _L('data').'</h1>
					<ul class="ul-file-category-filter">'.$menu.'</ul>
				</div>';

		$form = '
				<div class="category-picker-filter-images">
					<a href="javascript:void(null); "onclick="javascript:sh(\'div_new_cat\');"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/mini_browser_gui/add.png" width="16" height="16" border="0" /> '._L('new_category').'</a><br />
					<span style="color: #666666;">['._L('multiple_categories_separate').' &quot;,&quot;]</span><br />
				</div>
				<div class="category-picker-filter-images" style="display:none;" id="div_new_cat">
				<fieldset>
					<legend><strong><label for="new_mercury_category">'._L('new_categories').'</label></strong></legend><br />
						<textarea name="new_mercury_category" id="new_mercury_category" cols="22" rows="4"></textarea>
						<input type="button" onclick="__mercury_cat_update_list(\''.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon&ajax_data_db&action=add_category\');" value="'._L('submit').'" />
				</fieldset><br />
			</div>';

		return $menu . $form;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return unknown
	 */
	function get_mini_browser_categories()
	{
		global $dbc, $orbicon_x;
		$r = $dbc->_db->query('	SELECT 		*
								FROM 		'.MERCURY_CATEGORIES.'
								ORDER BY 	permalink');
		$list = $dbc->_db->fetch_assoc($r);

		$menu = '<div class="category-picker-filter-images"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/mini_browser_gui/picker-documents.gif" />
					<h1>'._L('categories').' - '._L('data').'</h1>
					<ul class="ul-file-category-filter">';

		while($list) {
			$r_count = $dbc->_db->query(sprintf('	SELECT 		COUNT(id)
													FROM 		'.MERCURY_FILES.'
													WHERE 		(live = 1) AND
																(hidden = 0) AND
																(category = %s)', $dbc->_db->quote($list['permalink'])));
			$count = $dbc->_db->fetch_array($r_count);

			$delete_url = '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mercury&amp;del_cat='.$list['permalink'].'" onclick="javascript:return false;" onmousedown="' . delete_popup($list['name']) . '"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/delete.png" /></a>';

			$menu .= '
				<li><a href="javascript:void(null);" onclick="javascript:switch_mini_browser(\'mercury\', \''.$list['permalink'].'\', 0, 0);">'.$list['name'].'</a><br />
					<div class="div-controller">'.$delete_url.' | '._L('docs_lc').': '.$count[0].'</div>
				</li>';

			$list = $dbc->_db->fetch_assoc($r);
		}

		// unsorted
		$r = $dbc->_db->query('		SELECT 		COUNT(id)
									FROM 		'.MERCURY_FILES.'
									WHERE 		(live = 1) AND
												(hidden = 0) AND
												((category = \'\') OR (category IS NULL))');
		$list = $dbc->_db->fetch_array($r);

		if($list[0] > 0) {
				$menu .= '
				<li><a href="javascript:void(null);" onclick="javascript:switch_mini_browser(\'mercury\', \'_orbx_unsorted\', 0, 0);">' . _L('unsorted') . '</a><br />
					<div class="div-controller">' . _L('docs_lc') . ': ' . $list[0] . '</div>
				</li>';
		}

		$menu .= '</ul></div>';
		return $menu.'<div style="padding: 0 0 0 1px;">' . $this->generate_upload_applet() . '</div>';
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $category
	 * @param unknown_type $start
	 * @param unknown_type $search
	 * @return unknown
	 */
	function get_mini_browser_files($category, $start, $search = '')
	{
		global $dbc;
		$cat_sql = ($category == '_orbx_unsorted') ? '((category = \'\') OR (category IS NULL))' : sprintf('(category = %s)', $dbc->_db->quote($category));

		if($search !== '') {
			$r = $dbc->_db->query(sprintf('	SELECT 		*
											FROM 		'.MERCURY_FILES.'
											WHERE 		'.$cat_sql.'
											AND			content LIKE %s
											ORDER BY 	uploader_time DESC, title
											LIMIT 		'.$start.', 4',
											$dbc->_db->quote('%' . $search . '%')));
		} else {
			$r = $dbc->_db->query('	SELECT 		*
									FROM 		'.MERCURY_FILES.'
									WHERE 		'.$cat_sql.'
									ORDER BY 	uploader_time DESC, title
									LIMIT 		'.$start.', 4');
		}
		$a = $dbc->_db->fetch_assoc($r);

		$menu = '<div class="image-picker">
<img src="'.ORBX_SITE_URL.'/orbicon/gfx/mini_browser_gui/picker-documents.gif">
<h1>'._L('data_picker').'</h1>
<div class="div-search">'._L('data_search').'</div>
<div class="div-controller">
  <input type="text" name="minibrowser_search" id="minibrowser_search" onkeypress="javascript: if(get_enter_pressed(event)) {switch_mini_browser(\'mercury\', \''.$category.'\', 0, 0);}" />
  <input type="button" name="Submit" value="GO!" onclick="javascript:switch_mini_browser(\'mercury\', \''.$category.'\', 0, 0);" />
</div>
<ul class="ul-file-picker">';

		while($a) {
			$ext = get_extension($a['content']);

			switch($ext) {
				case 'swf':
					if(filesize(DOC_ROOT . '/site/mercury/' . $a['content']) < 2097152) {
						$info = getimagesize(DOC_ROOT . '/site/mercury/' . $a['content']);
					}
					$extra = $info[0] . ':' . $info[1];
				break;
				case 'flv':
					include_once(DOC_ROOT.'/orbicon/class/inc.mmedia.php');
					list($flv_player_def_w, $flv_player_def_h) = get_video_size(DOC_ROOT . '/site/mercury/' . $a['content']);

					$extra = 	$flv_player_def_w . ':' .								// width
								$flv_player_def_h . ':' .								// height
								$_SESSION['site_settings']['flv_player_autoplay'];		// autoplay
				break;
			}

			$menu .= '
				<li><a href="javascript:void(null);" onclick="javascript:mercury_do_mini_update(\''.$a['content'].'\', \''.$ext.'\', \''.$extra.'\');">'.$a['content'].'</a><br />
					<div class="documents-div-preview"> '.$this->get_document_icon($ext).' | .'.$ext.'| ' . get_file_size(DOC_ROOT . '/site/mercury/' . $a['content']) . '</div>';

			$a = $dbc->_db->fetch_assoc($r);
		}

		$current = (($start + 4) / 4);

		$count = $dbc->_db->query('	SELECT 		COUNT(id)
									FROM 		'.MERCURY_FILES.'
									WHERE 		' . $cat_sql);
		$count = $dbc->_db->fetch_array($count);
		$count = $count[0];

		$next = ($count > ($start + 4)) ? '<a href="javascript:void(null);" onclick="javascript:switch_mini_browser(\'mercury\', \''.$category.'\', 0, '.($start + 4).');">'._L('next').' &gt;&gt;</a>' : _L('next').' &gt;&gt;';
		$back = ($start > 0) ? '<a href="javascript:void(null);" onclick="javascript:switch_mini_browser(\'mercury\', \''.$category.'\', 0, '.($start - 4).');">&lt;&lt; '._L('previous').'</a>' : '&lt;&lt; '._L('previous');

		$root_menu = '<a href="javascript:void(null);" onclick="javascript:switch_mini_browser(\'mercury\', \'\', 0, 0);">' . _L('back') . '</a>';

		$menu .= "</ul></div>
		<div class=\"image-picker\"><strong>$root_menu | $back | $current | $next</strong></div>";

		return $menu . '<div style="padding: 0 0 0 1px;">' . $this->generate_upload_applet() . '</div>';
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $current
	 * @return unknown
	 */
	function get_categories($current = '')
	{
		global $dbc;
		$r = $dbc->_db->query('	SELECT 		*
								FROM 		'.MERCURY_CATEGORIES.'
								ORDER BY 	permalink');
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$selected = ($current != '' && $current == $a['permalink']) ? ' selected="selected"' : '';
			$categories .= "<option value=\"{$a['permalink']}\" $selected>{$a['name']}</option>";

			$a = $dbc->_db->fetch_assoc($r);
		}
		$dbc->_db->free_result($r);

		return $categories;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $category
	 */
	function add_new_category($category)
	{
		global $dbc;

		$new = explode(',', $category);

		foreach($new as $value) {
			$value = trim($value);
			if($value != '') {
				$permalink = get_permalink($value);

				if(!$this->category_exists($permalink)) {

					$value = utf8_html_entities($value);

					$dbc->_db->query(sprintf('	INSERT INTO 	'.MERCURY_CATEGORIES.'
																(name, permalink)
												VALUES 			(%s, %s)',
												$dbc->_db->quote($value), $dbc->_db->quote($permalink)));
				}
			}
		}
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $post_url
	 */
	function get_upload_applet($post_url = '')
	{
		echo $this->generate_upload_applet($post_url);
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $post_url
	 * @return unknown
	 */
	function generate_upload_applet($post_url = '')
	{
		global $orbicon_x;

		$dnd_applet = $this->_get_supported_upload_applet();
		$applet = '';
		$use_applet = false;
		$ua = strtolower(ORBX_USER_AGENT);
		list($data, $file, $empty) = explode('/', $_REQUEST['read']);
		unset($data, $empty);

		if(($file != '') && $_GET[$orbicon_x->ptr] == 'orbicon/mercury') {
			$msg = sprintf(_L('dnd_add_files_replace'), "<b>$file</b>");
		}
		else {
			 $msg = _L('dnd_add_files');
			 $file = '';
		}

		$post_url = ($post_url == '') ? ORBX_SITE_URL.'/orbicon/mercury/publish_document.php?credentials=' . get_ajax_id() . '&amp;category='. base64_encode($_REQUEST['show_only']) . '&amp;file=' . $file : $post_url;

		if(strstr($ua, 'konqueror') || strstr($ua, 'macintosh') || strstr($ua, 'opera')) {
			$use_applet = true;
			$applet .= '<applet name="'.$dnd_applet['name'].'" archive="'.$dnd_applet['url'].'" code="'.$dnd_applet['param_code'].'" id="rup_applet" MAYSCRIPT="yes">';
		}
		else {
			if(strstr($ua, 'msie')) {
				$applet .= '<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" height="100" width="273" id="rup" name="rup" codebase="http://java.sun.com/update/1.5.0/jinstall-1_5-windows-i586.cab#version=1,4,1">';
			}
			else {
				$applet .= '<object type="application/x-java-applet;version=1.4.1" height="100" width="273" id="rup" name="rup">';
			}
			$applet .= '<param name="archive" value="'.$dnd_applet['url'].'" />
				<param name="code" value="'.$dnd_applet['param_code'].'" />
				<param name="name" value="'.$dnd_applet['name'].'" />';
		}

		$applet .= '
		<param name="wmode" value="transparent" />
		<param name="max_upload" value="'.intval(get_php_ini_bytes(ini_get('post_max_size')) / 1024).'" />
		<param name="browse" value="1" />
		<param name="browse_button" value="1" />
		<param name="message" value="'.$msg.'" />
		<param name="url" value="'.$post_url.'" />
		<param name="image" value="'.ORBX_SITE_URL.'/orbicon/gfx/empty.gif" />
		<param name="monitor.keep_visible" value="yes" />
		<param name="external_redir" value="'.ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=' . $_REQUEST[$orbicon_x->ptr].'" />
		<param name="external_target" value="_top" />
		<param name="redirect_delay" value="1000" />';

		if(isset($_SERVER['PHP_AUTH_USER'])) {
			$applet .= '<param name="chap" value="'.base64_encode($_SERVER['PHP_AUTH_USER'].':'.$_SERVER['PHP_AUTH_PW']).'" />';
		}

		if($use_applet) {
			$applet .= '</applet>';
		}
		else {
			$applet .= '</object>';
		}

		return $applet;
	}

	/**
	 * determines suppported Rad Upload applet and returns an array of its information
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @access private
	 * @return array
	 */
	function _get_supported_upload_applet()
	{
		// Plus version
		if(is_file(DOC_ROOT . '/orbicon/3rdParty/rad-plus/dndplus.jar')) {
			return array(
			'name' => 'Rad Upload Plus',
			'url' => ORBX_SITE_URL.'/orbicon/3rdParty/rad-plus/dndplus.jar',
			'param_code' => 'com.radinks.dnd.DNDAppletPlus'
			);
		}
		// Lite version
		else if(is_file(DOC_ROOT . '/orbicon/3rdParty/rad-lite/dndlite.jar')) {
			return array(
			'name' => 'Rad Upload Lite',
			'url' => ORBX_SITE_URL . '/orbicon/3rdParty/rad-lite/dndlite.jar',
			'param_code' => 'com.radinks.dnd.DNDAppletLite'
			);
		}

		trigger_error('No applet found', E_USER_WARNING);
		return null;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return unknown
	 */
	function add_document()
	{
		$file = $_FILES['userfile'];
		$max = count($file['name']);
		// decode category permalink. we encoded it earlier to save UTF8 from corruption
		$data_category = base64_decode($_REQUEST['category']);
		$data_category = ($data_category == '_orbx_unsorted') ? '' : $data_category;

		for($i = 0; $i < $max ; $i++) {
			// security checks
			if(validate_upload($_FILES['userfile']['tmp_name'][$i], $_FILES['userfile']['name'][$i], $_FILES['userfile']['size'][$i], $_FILES['userfile']['error'][$i])) {
				$files[] = $this->insert_file_into_db($_FILES['userfile']['name'][$i], true, $_FILES['userfile']['tmp_name'][$i], false, $data_category);
			}
		}
		return $files;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $filename
	 * @param bool $uploaded_file
	 * @param string $tmp_filename
	 * @param bool $unlink
	 * @param string $category
	 * @return string
	 */
	function insert_file_into_db($filename, $uploaded_file = true, $tmp_filename = null, $unlink = false, $category = '')
	{
		if($filename == '') {
			trigger_error('insert_file_into_db() expects parameter 1 to be non-empty', E_USER_WARNING);
			return null;
		}

		global $dbc;
		$ext = get_extension($filename);
		$now = time();
		$do_db_insert_sql = true;

		// overwrite
		if($_REQUEST['file']) {

			$q = sprintf('	SELECT 		id, content
							FROM 		'.MERCURY_FILES.'
							WHERE		(permalink = %s)',
							$dbc->_db->quote($_REQUEST['file']));

			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);

			$file = $a['content'];
			$old_id = $a['id'];
			$path = DOC_ROOT . "/site/mercury/$file";
			$do_db_insert_sql = false;

			unset($q, $r, $a);
		}
		else {
			$file = sanitize_name(sprintf('%s-%s-%s.%s', date('Ymd', $now), get_permalink(basename($filename)), substr(sha1(uniqid(rand(), true)), 0, 4), $ext));
			$path = DOC_ROOT . "/site/mercury/$file";

			while(is_file($path)) {
				$file = sanitize_name(sprintf('%s-%s-%s.%s', date('Ymd', $now), get_permalink(basename($filename)), substr(sha1(uniqid(rand(), true)), 0, 4), $ext));
				$path = DOC_ROOT . "/site/mercury/$file";
			}
		}

		$created = ($uploaded_file) ? move_uploaded_file($tmp_filename, $path) : copy($filename, $path);
		if(!$uploaded_file && $unlink) {
			unlink($filename);
		}
		else if($uploaded_file) {
			chmod_lock($path);
		}

		if($created) {
			chmod_unlock($path);
			chmod_lock($path);

			// * add to db
			if($do_db_insert_sql) {

				if($category != '') {

					// category doesn't exist, create it
					if(!$this->category_exists($category)) {
						$this->add_new_category($category);
					}
				}

				$q = sprintf('	INSERT
								INTO '.MERCURY_FILES.' (
									content, uploader_time,
									uploader_ip, live_time,
									permalink, size,
									category)
								VALUES
									(%s, UNIX_TIMESTAMP(),
									%s, UNIX_TIMESTAMP(),
									%s, %s,
									%s)',
									$dbc->_db->quote($file), $dbc->_db->quote(ORBX_CLIENT_IP),
									$dbc->_db->quote(get_permalink($file)), $dbc->_db->quote(filesize($path)),
									$dbc->_db->quote($category));

				$r = $dbc->_db->query($q);
			}

			// filter out words for search engine
			$supported_text_formats = array('doc', 'txt', 'rtf', 'rdf', 'log', 'xml', 'c', 'cpp', 'h', 'cs', 'cfm', 'phps');

			if(in_array($ext, $supported_text_formats)) {

				$id = ($do_db_insert_sql) ? $dbc->_db->insert_id($r) : $old_id;

				$q = sprintf('	UPDATE 		' . MERCURY_FILES . '
								SET			search_index=%s
								WHERE 		(id=%s)',

				$dbc->_db->quote(preg_replace('/[^a-zA-Z0-9\s-.]/i', '', file_get_contents($path))), $dbc->_db->quote($id));
				$dbc->_db->query($q);
			}
			else if($ext == 'pdf') {

				global $orbx_log;
				$tmp = DOC_ROOT . '/site/mercury/pdftxt.tmp';
				$output = null;
				$error = null;

				if (strtolower(substr(PHP_OS, 0, 3)) == 'win') {
					system(DOC_ROOT . '\orbicon\3rdParty\pdftext\pdftext.exe ' . escapeshellarg($path), $output);
				}
				else {
					system('pdftotext ' . escapeshellarg($path), $output);
				}

				switch ($output) {
					case 1: $error = 'pdftotext: Error opening a PDF file ' . $path; break;
					case 2: $error = 'pdftotext: Error opening an output file ' . $tmp; break;
					case 3: $error = 'pdftotext:  Error related to PDF permissions'; break;
					case 99: $error = 'pdftotext: Other error'; break;
				}

				if($error) {
					$orbx_log->ewrite($error, __LINE__, __FUNCTION__);
				}

				$new_file = substr($path, 0, -4) . '.txt';
				$id = ($do_db_insert_sql) ? $dbc->_db->insert_id($r) : $old_id;
				$q = sprintf('	UPDATE 		'.MERCURY_FILES.'
								SET			search_index=%s
								WHERE 		(id=%s)',
				$dbc->_db->quote(file_get_contents($new_file)), $dbc->_db->quote($id));
				$dbc->_db->query($q);
				unlink($new_file);
			}

			return $file;
		}

		trigger_error('Failed to move / copy uploaded file' . $path, E_USER_WARNING);
		return null;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $ext
	 * @return string
	 */
	function get_document_icon($ext)
	{
		$ext = strtolower($ext);

		switch($ext) {
			case 'rar':
			case 'zip':
			case 'gz':
			case 'tgz':
			case '7z':
				$pic = 'page_white_compressed.png';
			break;
			case 'xls': $pic = 'page_white_excel.png'; break;
			case 'swf': $pic = 'page_white_flash.png'; break;
			case 'as': $pic = 'page_white_flash.png'; break;
			case 'fh':
			case 'fh8':
			case 'fh9':
			case 'fh10':
			case 'fh11':
				$pic = 'page_white_freehand.png';
			break;
			case 'jpg':
			case 'jpeg':
			case 'tif':
			case 'tiff':
			case 'bmp':
			case 'gif':
			case 'png':
			case 'ico':
			case 'psd':
			case 'dib':
			case 'jpe':
			case 'jfif':
				$pic = 'page_white_picture.png';
			break;
			case 'pdf': $pic = 'page_white_acrobat.png'; break;
			case 'php':
			case 'php3':
			case 'phps':
				$pic = 'page_white_php.png';
			break;
			case 'mp3':
			case 'wav':
			case 'ogg':
			case 'wma':
				$pic = 'sound.png';
			break;
			case 'ppt': $pic = 'page_white_powerpoint.png'; break;
			case 'h': $pic = 'page_white_h.png'; break;
			case 'c': $pic = 'page_white_c.png'; break;
			case 'cpp': $pic = 'page_white_cplusplus.png'; break;
			case 'cs': $pic = 'page_white_csharp.png'; break;
			case 'doc': $pic = 'page_white_word.png'; break;
			case 'cfm': $pic = 'page_white_coldfusion.png'; break;
			case 'txt':
			case 'log':
				$pic = 'page_white_text.png'; break;
			case 'rtf': $pic = 'page_white_office.png'; break;
			case 'ai':
			case 'eps': $pic = 'page_white_vector.png'; break;
			case 'xml': $pic = 'page_white_code.png'; break;
			case 'flv':
			case 'mpg':
			case 'mpeg':
			case 'avi':
			case 'wmv':
				$pic = 'television.png';
			break;

			default: $pic = 'page_white.png'; break;
		}

		return '<img src="'.ORBX_SITE_URL."/orbicon/gfx/file_icons/$pic\" alt=\"$pic\" title=\"$ext\" />";
	}

	/**
	 * Check if category exists
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $permalink
	 * @return bool
	 */
	function category_exists($permalink)
	{
		global $dbc;
		$sql_c = sprintf('	SELECT 		id
							FROM 		'.MERCURY_CATEGORIES.'
							WHERE 		(permalink = %s)
							LIMIT 		1', $dbc->_db->quote($permalink));

		$r_c = $dbc->_db->query($sql_c);
		$a_c = $dbc->_db->fetch_assoc($r_c);

		return !empty($a_c['id']);
	}
}
?>
