<?php
/**
 * Venus class
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package OrbiconFE
 * @subpackage Venus
 * @version 1.5
 * @link http://
 * @license http://
 * @since 2006-05-01
 */
define('IMG_MATRIX_SHARPEN', 0);
define('IMG_MATRIX_BLUR', 1);
define('IMG_MATRIX_EMBOSS', 2);
define('IMG_MATRIX_EDGE_DETECT', 3);
define('IMG_MATRIX_EDGE_ENHANCE', 4);

class Venus
{
	/**
	 * Delete category
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $category
	 */
	function delete_category($category)
	{
		if($category != '') {
			global $dbc, $orbicon_x;
			$dbc->_db->query(sprintf('	DELETE
										FROM 	'.VENUS_CATEGORIES.'
										WHERE 	(permalink = %s)
										LIMIT 	1',
										$dbc->_db->quote($category)));

			// redirect
			redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/venus');
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
		$file = basename($file);

		if($file != '') {
			global $dbc, $orbicon_x;

			// original
			unlink(DOC_ROOT . '/site/venus/' . $file);
			// thumbnail
			unlink(DOC_ROOT . '/site/venus/thumbs/t-' . $file);

			$dbc->_db->query(sprintf('	DELETE
										FROM 	'.VENUS_IMAGES.'
										WHERE 	(permalink = %s)
										LIMIT 	1', $dbc->_db->quote($file)));

			redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/venus');
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
								FROM 		'.VENUS_CATEGORIES.'
								ORDER BY 	permalink');
		$a = $dbc->_db->fetch_array($r);

		while($a) {
			$r_c = $dbc->_db->query(sprintf('	SELECT 	COUNT(id)
												FROM 	'.VENUS_IMAGES.'
												WHERE 	(live = 1) AND
														(hidden = 0) AND
														(category = %s)',
														$dbc->_db->quote($a['permalink'])));
			$a_c = $dbc->_db->fetch_array($r_c);

			$delete_url = '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/venus&amp;del_cat='.$a['permalink'].'" onclick="javascript:return false;" onmousedown="' . delete_popup($a['name']) . '"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/delete.png" alt="'._L('delete').'" title="'._L('delete').'" /></a>';

			$menu .= '<li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/venus&amp;show_only='.$a['permalink'].'">'.$a['name'].'</a><br />
			<div class="div-controller">'.$delete_url.' | '._L('images_lc').': '.$a_c[0].'</div></li>';

			$a = $dbc->_db->fetch_array($r);
		}

		// unsorted
		$r_c = $dbc->_db->query('	SELECT 		COUNT(id)
									FROM 		'.VENUS_IMAGES.'
									WHERE 		(live = 1) AND
												(hidden = 0) AND
												((category = \'\') OR (category IS NULL))');
		$a_c = $dbc->_db->fetch_array($r_c);

		if($a_c[0] > 0) {
				$menu.= '<li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/venus&amp;show_only=_orbx_unsorted">'._L('unsorted').'</a><br />
					<div class="div-controller">'._L('images_lc').': '.$a_c[0].'</div></li>';
		}

		$menu = '
				<div class="category-picker-filter-images" style="padding: 10px 0 0 10px;">
					<img src="'.ORBX_SITE_URL.'/orbicon/gfx/mini_browser_gui/picker-images.gif" />
					<h1>'._L('categories').' - '._L('images').'</h1>
					<ul class="ul-image-category-filter">'.$menu.'</ul>
				</div>';

		$form = '
				<div class="category-picker-filter-images">
					<a href="javascript:void(null); "onclick="javascript:sh(\'div_new_cat\');"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/mini_browser_gui/add.png" width="16" height="16" border="0" /> '._L('new_category').'</a><br />
					<span style="color: #666666;">['._L('multiple_categories_separate').' &quot;,&quot;]</span><br />
				</div>
				<div class="category-picker-filter-images" style="display:none;" id="div_new_cat">
				<fieldset>
					<legend><strong><label for="new_venus_category">'._L('new_categories').'</label></strong></legend><br />
						<textarea name="new_venus_category" id="new_venus_category" cols="22" rows="4"></textarea>
						<input type="button" onclick="javascript:__venus_cat_update_list(\''.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon&ajax_img_db&action=add_category\');" value="'._L('submit').'" />
				</fieldset><br />
			</div>';

		return $menu . $form;
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
		$r = $dbc->_db->query('	SELECT 		permalink, name
								FROM 		'.VENUS_CATEGORIES.'
								ORDER BY 	permalink');
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$selected = (($current != '') && ($current == $a['permalink'])) ? ' selected="selected"' : null;
			$categories .= "<option value=\"{$a['permalink']}\" $selected>{$a['name']}</option>";

			$a = $dbc->_db->fetch_assoc($r);
		}

		return $categories;
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

		$r = $dbc->_db->query('		SELECT 		*
									FROM 		'.VENUS_CATEGORIES.'
									ORDER BY 	name');
		$list = $dbc->_db->fetch_assoc($r);

		$menu = '<div class="category-picker-filter-images"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/mini_browser_gui/picker-images.gif" />
					<h1>'._L('categories').' - '._L('images').'</h1>
					<ul class="ul-image-category-filter">';

		while($list) {
			$r_count = $dbc->_db->query(sprintf('	SELECT 		COUNT(id)
													FROM 		'.VENUS_IMAGES.'
													WHERE 		(live = 1) AND
																(hidden = 0) AND
																(category = %s)', $dbc->_db->quote($list['permalink'])));
			$count = $dbc->_db->fetch_array($r_count);

			$delete_url = '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/venus&amp;del_cat='.$list['permalink'].'" onclick="return false;" onmousedown="' . delete_popup($list['name']) . '"><img alt="'._L('delete').'" title="'._L('delete').'" src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/delete.png" /></a>';

			$menu .= '<li><a href="javascript:void(null);" onclick="javascript:switch_mini_browser(\'venus\', \''.$list['permalink'].'\', 0, 0);">'.$list['name'].'</a><br />
					<div class="div-controller">'.$delete_url.' | '._L('images_lc').': '.$count[0].'</div></li>';

			$list = $dbc->_db->fetch_assoc($r);
		}

		// unsorted
		$r = $dbc->_db->query('	SELECT 		COUNT(id)
								FROM 		'.VENUS_IMAGES.'
								WHERE 		(live = 1) AND
											(hidden = 0) AND
											((category = \'\') OR (category IS NULL))');
		$list = $dbc->_db->fetch_array($r);

		if($list[0] > 0) {
				$menu .= '<li><a href="javascript:void(null);" onclick="javascript:switch_mini_browser(\'venus\', \'_orbx_unsorted\', 0, 0);">'._L('unsorted').'</a><br />
					<div class="div-controller">'._L('images_lc').': '.$list[0].'</div>
				</li>';
		}

		$menu .= '</ul></div>';
		return $menu . '<div style="padding: 0 0 0 1px;">' . $this->generate_upload_applet() . '</div>';
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
	function get_mini_browser_images($category, $start, $search = '')
	{
		global $dbc, $orbicon_x;

		$cat_sql = ($category == '_orbx_unsorted') ? '((category = \'\') OR (category IS NULL))' : sprintf('(category = %s)', $dbc->_db->quote($category));

		if($search != '') {
			$r = $dbc->_db->query(sprintf('	SELECT 		*
											FROM 		'.VENUS_IMAGES.'
											WHERE 		'.$cat_sql.'
											AND 		content LIKE %s
											ORDER BY 	last_modified DESC, permalink
											LIMIT 		'.$start.', 4'
											, $dbc->_db->quote('%' . $search . '%')));
		}
		else {
			$r = $dbc->_db->query('	SELECT 		*
									FROM 		'.VENUS_IMAGES.'
									WHERE 		'.$cat_sql.'
									ORDER BY 	last_modified DESC, permalink
									LIMIT 		'.$start.', 4');
		}

		$list = $dbc->_db->fetch_assoc($r);

		$menu = '<div class="image-picker">
<img src="'.ORBX_SITE_URL.'/orbicon/gfx/mini_browser_gui/picker-images.gif">
<h1>'._L('image_picker').'</h1>
<div class="div-search">'._L('image_search').'</div>
<div class="div-controller">
<input type="text" name="minibrowser_search" id="minibrowser_search" onkeypress="javascript: if(get_enter_pressed(event)) {switch_mini_browser(\'venus\', \''.$category.'\', 0, 0);}" />
  <input type="button" name="Submit" value="GO!" onclick="javascript:switch_mini_browser(\'venus\', \''.$category.'\', 0, 0);" />
</div>
<ul class="ul-image-picker">';

		while($list) {
			$ext = get_extension($list['permalink']);
			$info = getimagesize(DOC_ROOT.'/site/venus/'.$list['permalink']);
			$factor = (38 / $info[0]);
			$height = (($info[1] * $factor) > 38) ? 38 : rounddown($info[1] * $factor);
			$img = (is_file(DOC_ROOT . '/site/venus/thumbs/t-'.$list['permalink'] )) ? '<img src="'.ORBX_SITE_URL.'/site/venus/thumbs/t-'.$list['permalink'].'" width="38" height="'.$height.'" border="0" />' : '<img src="'.ORBX_SITE_URL.'/site/venus/'.$list['permalink'].'" width="38" height="'.$height.'" border="0" />';

			$menu .= '
				<li><a href="javascript:void(null);" onclick="javascript:venus_do_mini_update(\''.$list['permalink'].'\');">'.$list['title'].'</a><br />
					<div class="div-preview">
						<a href="javascript:void(null);" onclick="javascript:venus_do_mini_update(\''.$list['permalink'].'\');">'.$img.'</a> |
						<a target="_blank" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/venus&amp;read=expo/'.$list['permalink'].'/"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/mini_browser_gui/edit.png" width="16" height="16" /></a>| .'.$ext.' | '.$info[0].'x'.$info[1].'px</div>
				</li>';

			$list = $dbc->_db->fetch_assoc($r);
		}

		$current = (($start + 4) / 4);

		$count = $dbc->_db->query('		SELECT 		COUNT(id)
										FROM 		'.VENUS_IMAGES.'
										WHERE 		' . $cat_sql);
		$count = $dbc->_db->fetch_array($count);
		$count = $count[0];

		$next = ($count > ($start + 4)) ? '<a href="javascript:void(null);" onclick="javascript:switch_mini_browser(\'venus\', \''.$category.'\', 0, '.($start + 4).');">'._L('next').' &gt;&gt;</a>' : _L('next').' &gt;&gt;';
		$back = ($start > 0) ? '<a href="javascript:void(null);" onclick="javascript:switch_mini_browser(\'venus\', \''.$category.'\', 0, '.($start - 4).');">&lt;&lt; '._L('previous').'</a>' : '&lt;&lt; '._L('previous');

		$root_menu = '<a href="javascript:void(null);" onclick="switch_mini_browser(\'venus\', \'\', 0, 0);">' . _L('back') . '</a>';

		$menu .= "</ul></div>
		<div class=\"image-picker\"><strong>$root_menu | $back | $current | $next</strong></div>";

		return $menu . '<div style="padding: 0 0 0 1px;">' . $this->generate_upload_applet() . '</div>';
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 */
	function print_venus_ajax_image()
	{
		global $dbc;
		$r = $dbc->_db->query(sprintf('	SELECT 	permalink, content
										FROM 	'.VENUS_IMAGES.'
										WHERE 	(live = 1) AND
												(permalink = %s)
										LIMIT 	1',
										$dbc->_db->quote($_REQUEST['permalink'])));
		$img = $dbc->_db->fetch_assoc($r);

		echo '<div style="overflow:auto;"><img src="'.ORBX_SITE_URL.'/site/venus/'.$img['permalink'].'" title="'.$img['content'].'" alt="'.$img['permalink'].'" /></div>';
	}

	/**
	 * generate thumbnail from $input to $output
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $input
	 * @param string $output
	 * @param int $width
	 * @param int $height
	 * @param int $scale
	 * @param int $quality
	 * @return bool
	 */
	function generate_thumbnail($input, $output, $width = null, $height = null, $scale = 100, $quality = 80)
	{
		if(!function_exists('imagecreatefromjpeg')) {
			trigger_error('generate_thumbnail() requires GD extension', E_USER_WARNING);
			return false;
		}

		if(is_file($input)) {
			$info = getimagesize($input);

			// thumbnail is larger than the original? exit here
			if($info[0] <= $width) {
				return false;
			}

			// sanity checks
			if($width != null) {
				$width = ($width > 4999) ? 4999 : (($width < 1) ? 1 : $width);
			}
			if($height != null) {
				$height = ($height > 4999) ? 4999 : (($height < 1) ? 1 : $height);
			}
			if($quality != null) {
				$quality = ($quality > 100) ? 100 : (($quality < 0) ? 0 : $quality);
			}
			if($scale != null) {
				$scale = ($scale > 100) ? 100 : (($scale < 0) ? 0 : $scale);
			}

			if(($width == null) || ($height == null)) {
				if($width != null) {
					$scale = intval(($width / $info[0]) * 100);
				}
				else if(($height != null) && ($scale == 100)) {
					$scale = intval(($height / $info[1]) * 100);
				}
			}

			if($scale > 0 && $scale < 100) {
				$width = intval($info[0] * ($scale / 100));
				$height = intval($info[1] * ($scale / 100));
			}

			$new_img = imagecreatetruecolor($width, $height);

			$white = imagecolorallocate($new_img, 255, 255, 255);
			imagefill($new_img, 0, 0, $white);

			switch($info[2]) {
				case IMAGETYPE_GIF:
					$temp_img = imagecreatefromgif($input);
					imagecopyresampled($new_img, $temp_img, 0, 0, 0, 0, $width, $height, $info[0], $info[1]);
					imagegif($new_img, $output);
				break;
				case IMAGETYPE_JPEG:
					$temp_img = imagecreatefromjpeg($input);
					imagecopyresampled($new_img, $temp_img, 0, 0, 0, 0, $width, $height, $info[0], $info[1]);
					imagejpeg($new_img, $output, $quality);
				break;
				case IMAGETYPE_PNG:
					$temp_img = imagecreatefrompng($input);
					imagecopyresampled($new_img, $temp_img, 0, 0, 0, 0, $width, $height, $info[0], $info[1]);
					imagepng($new_img, $output);
				break;
			}
			imagedestroy($temp_img);
			return true;
		}
		else {
			trigger_error($input . ' is not a valid file', E_USER_WARNING);
			return false;
		}
		return false;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $image
	 */
	function sharpen($image) {
		$m = array(	array(-1, -1, -1),
					array(-1, 16, -1),
					array(-1, -1, -1));
		imageconvolution($image, $m, 8, 0);
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $image
	 */
	function blur($image) {
		$m = array(	array(1, 1, 1),
					array(1, 15, 1),
					array(1, 1, 1));
		imageconvolution($image, $m, 23, 0);
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $image
	 */
	function emboss($image) {
		$m = array(	array(1, 1, -1),
					array(1, 3, -1),
					array(1, -1, -1));
		imageconvolution($image, $m, 3, 0);
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $image
	 */
	function edge_detect($image) {
		$m = array(	array(0, 1, 0),
					array(1, -4, -1),
					array(0, 1, 0));
		imageconvolution($image, $m, 0, 0);
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $image
	 */
	function edge_enhance($image) {
		$m = array(	array(0, 0, 0),
					array(-1, 1, 0),
					array(0, 0, 0));
		imageconvolution($image, $m, 0, 0);
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
					$dbc->_db->query(sprintf('	INSERT
												INTO 		'.VENUS_CATEGORIES.'
															(name, permalink)
												VALUES 		(%s, %s)', $dbc->_db->quote($value), $dbc->_db->quote($permalink)));
				}
			}
		}
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 */
	function get_upload_applet()
	{
		echo $this->generate_upload_applet();
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return unknown
	 */
	function generate_upload_applet()
	{
		global $orbicon_x;

		require_once DOC_ROOT . '/orbicon/mercury/class.mercury.php';
		$dnd_applet = Mercury::_get_supported_upload_applet();

		$applet = '';
		$use_applet = false;
		$ua = strtolower(ORBX_USER_AGENT);

		if(strstr($ua, 'konqueror') || strstr($ua, 'macintosh') || strstr($ua, 'opera')) {
			$use_applet = true;
			$applet .= '<applet name="'.$dnd_applet['name'].'" archive="'.$dnd_applet['url'].'" code="'.$dnd_applet['param_code'].'" id="rup_applet" MAYSCRIPT="yes">';
		}
		else {
			if(strstr($ua, 'msie')) {
				$applet .=  '<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" height="100" width="273" id="rup" name="rup" codebase="http://java.sun.com/update/1.5.0/jinstall-1_5-windows-i586.cab#version=1,4,1">';
			}
			else {
				$applet .=  '<object type="application/x-java-applet;version=1.4.1" height="100" width="273" id="rup" name="rup">';
			}
			$applet .=  '<param name="archive" value="'.$dnd_applet['url'].'" />
				<param name="code" value="'.$dnd_applet['param_code'].'" />
				<param name="name" value="'.$dnd_applet['name'].'" />';
		}

		list($expo, $file, $empty) = explode('/', $_REQUEST['read']);
		unset($expo, $empty);
		if(($file != '') && $_GET[$orbicon_x->ptr] == 'orbicon/venus') {
			$msg = sprintf(_L('dnd_add_files_replace'), "<b>$file</b>");
		}
		else {
			 $msg = _L('dnd_add_files');
			 $file = '';
		}

		$applet .=  '
		<param name="max_upload" value="'.intval(get_php_ini_bytes(ini_get('post_max_size')) / 1024).'" />
		<param name="browse" value="1" />
		<param name="browse_button" value="1" />
		<param name="message" value="'.$msg.'" />
		<param name="url" value="'.ORBX_SITE_URL.'/orbicon/venus/publish_image.php?credentials=' . get_ajax_id() . '&amp;category='. base64_encode($_REQUEST['show_only']).'&amp;file='.$file.'" />
		<param name="image" value="'.ORBX_SITE_URL.'/orbicon/gfx/empty.gif" />
		<param name="allow_types" value="gif,jpg,jpeg,png,swf,psd,bmp,tif,tiff,jpc,jp2,jpx,jb2,swc,iff,wbmp,xbm,xcf" />
		<param name="monitor.keep_visible" value="yes" />
		<param name="external_redir" value="'.ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=' . $_REQUEST[$orbicon_x->ptr].'" />
		<param name="external_target" value="_top" />
		<param name="redirect_delay" value="1000" />';

		if(isset($_SERVER['PHP_AUTH_USER'])) {
			$applet .= sprintf('<param name="chap" value="%s" />', base64_encode($_SERVER['PHP_AUTH_USER'].':'.$_SERVER['PHP_AUTH_PW']));
		}

		if($use_applet) {
			$applet .=  '</applet>';
		}
		else {
			$applet .=  '</object>';
		}

		return $applet;
	}

	/**
	 * Inserts image into DB
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $image
	 * @param string $post_temp_image
	 * @return string
	 */
	function _insert_image_to_db($image, $post_temp_image, $category = '')
	{
		global $orbx_mod;
		$ext = '';
		$image = basename($image);
		$img_info = getimagesize($post_temp_image);

		switch($img_info[2]) {
			case IMAGETYPE_GIF: 		$ext = 'GIF'; break;
			case IMAGETYPE_JPEG: 		$ext = 'JPG'; break;
			case IMAGETYPE_PNG: 		$ext = 'PNG'; break;
			case IMAGETYPE_SWF: 		$ext = 'SWF'; break;
			case IMAGETYPE_PSD: 		$ext = 'PSD'; break;
			case IMAGETYPE_BMP: 		$ext = 'BMP'; break;
			case IMAGETYPE_TIFF_II:		$ext = 'TIFF'; break; // (intel byte order)
			case IMAGETYPE_TIFF_MM: 	$ext = 'TIFF'; break; // (motorola byte order)
			case IMAGETYPE_JPC: 		$ext = 'JPC'; break;
			case IMAGETYPE_JP2: 		$ext = 'JP2'; break;
			case IMAGETYPE_JPX: 		$ext = 'JPX'; break;
			case IMAGETYPE_JB2: 		$ext = 'JB2'; break;
			case IMAGETYPE_SWC: 		$ext = 'SWC'; break;
			case IMAGETYPE_IFF: 		$ext = 'IFF'; break;
			case IMAGETYPE_WBMP: 		$ext = 'WBMP'; break;
			case IMAGETYPE_XBM: 		$ext = 'XBM'; break;
			default: 					$ext = str_replace('.', '', strrchr($image, '.'));
		}
		$ext = strtolower($ext);

		switch($ext) {
			case 'jpeg': $ext = 'jpg'; break;
			case 'tiff': $ext = 'tif'; break;
		}

		// we have overwrite in effect
		if($_REQUEST['file'] && !isset($_REQUEST['tools'])) {
			$file = $_REQUEST['file'];
			$path = DOC_ROOT.'/site/venus/'.$file;

			if(copy($post_temp_image, $path)) {
				if($orbx_mod->validate_module('inpulls') || $orbx_mod->validate_module('estate')) {
					copy($path, DOC_ROOT . '/site/venus/thumbs/t-' . $file);
					exec('mogrify -resize 150x ' . DOC_ROOT . '/site/venus/thumbs/t-' . $file);
				}
				else {
					$this->generate_thumbnail($path, DOC_ROOT . '/site/venus/thumbs/t-' . $file, 150, null);
				}
				return $file;
			}

			trigger_error('Could not copy ' . $post_temp_image . ' to ' . $path, E_USER_WARNING);
			return false;
		}

		$now = time();
		$file = sprintf('%s-%s-%s.%s', date('Ymd', $now), get_permalink($image), substr(sha1(uniqid(rand(), true)), 0, 4), $ext);
		$path = DOC_ROOT.'/site/venus/'.$file;

		while(is_file($path)) {
			$file = sprintf('%s-%s-%s.%s', date('Ymd', $now), get_permalink($image), substr(sha1(uniqid(rand(), true)), 0, 4), $ext);
			$path = DOC_ROOT . '/site/venus/' . $file;
		}

		if(copy($post_temp_image, $path)) {

			chmod_unlock($path);
			chmod_lock($path);
			$filesize = filesize($path);

			global $dbc;
			$title = trim(basename($path));
			$content = utf8_html_entities(str_replace(array('.', '?', ':', '!', ',', '-', '_', '`', '´', '(', ')', '[', ']', '<', '>', '*', '+', '#', ';', '~', '\'', '"'), ' ', $title));

			if($category == '') {

				// * add to db
				$q = sprintf(' INSERT INTO 	' . VENUS_IMAGES . '
												(title, content,
												uploader, uploader_ip,
												uploader_time, live_time,
												permalink, last_modified,
												size)
								VALUES 			(%s, %s,
												%s, %s,
												UNIX_TIMESTAMP(), UNIX_TIMESTAMP(),
												%s, UNIX_TIMESTAMP(),
												%s)',
												$dbc->_db->quote(utf8_html_entities($title)), $dbc->_db->quote($content),
												$dbc->_db->quote($_SESSION['user.a']['id']), $dbc->_db->quote(ORBX_CLIENT_IP),
												$dbc->_db->quote($title), $dbc->_db->quote($filesize));
			}
			else {

				// category doesn't exist, create it
				if(!$this->category_exists($category)) {
					$this->add_new_category($category);
				}

				// * add to db
				$q = sprintf('	INSERT INTO 	'.VENUS_IMAGES.'
												(category, title,
												content, uploader,
												uploader_ip, uploader_time,
												live_time, permalink,
												last_modified, size)
								VALUES 			(%s, %s,
												%s, %s,
												%s, UNIX_TIMESTAMP(),
												UNIX_TIMESTAMP(), %s,
												UNIX_TIMESTAMP(), %s)',
												$dbc->_db->quote($category), $dbc->_db->quote(utf8_html_entities($title)),
												$dbc->_db->quote($content), $dbc->_db->quote($_SESSION['user.a']['id']),
												$dbc->_db->quote(ORBX_CLIENT_IP), $dbc->_db->quote($title),
												$dbc->_db->quote($filesize));
			}

			$dbc->_db->query($q);

			if($orbx_mod->validate_module('inpulls') || $orbx_mod->validate_module('estate')) {
				copy($path, DOC_ROOT . '/site/venus/thumbs/t-' . $title);
				exec('mogrify -resize 150x ' . DOC_ROOT . '/site/venus/thumbs/t-' . $title);
			}
			else {
				$this->generate_thumbnail($path, DOC_ROOT . '/site/venus/thumbs/t-' . $title, 150, null);
			}
			return $file;
		}
		return false;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return unknown
	 */
	function upload_images()
	{
		$file = $_FILES['userfile'];
		$max = count($file['name']);

		// decode category permalink. we encoded it earlier to save UTF8 from corruption
		$image_category = base64_decode($_REQUEST['category']);
		$image_category = ($image_category == '_orbx_unsorted') ? '' : $image_category;

		for($i = 0; $i < $max ; $i++) {
			// security check
			if(validate_upload($_FILES['userfile']['tmp_name'][$i], $_FILES['userfile']['name'][$i], $_FILES['userfile']['size'][$i], $_FILES['userfile']['error'][$i])) {
				$file = $this->_insert_image_to_db($_FILES['userfile']['name'][$i], $_FILES['userfile']['tmp_name'][$i], $image_category);

				$files[] = $file;
			}
		}

		return $files;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return unknown
	 */
	function crop_image()
	{
		require_once DOC_ROOT . '/orbicon/venus/crop/class.cropcanvas.php';
		$crop = new CropCanvas;

		if(!empty($_REQUEST['file'])) {
			$image = DOC_ROOT . '/site/venus/' . $_REQUEST['file'];
			$ext = get_extension($_REQUEST['file']);

			$sx = $_REQUEST['crop_x'];
			$sy = $_REQUEST['crop_y'];
			$ex = $sx + $_REQUEST['crop_w'];
			$ey = $sy + $_REQUEST['crop_h'];
			$crop->loadImage($image);
			$crop->cropToDimensions($sx, $sy, $ex, $ey);

			if($_REQUEST['overwrite'] == 'true') {
				$crop->saveImage($image, 90, $ext);
				$thumbnail = DOC_ROOT.'/site/venus/thumbs/t-'.$_REQUEST['file'];
				$this->generate_thumbnail($image, $thumbnail, 150, 150);

				update_sync_cache_list(array($image, $thumbnail));

				unset($crop);
				return basename($image);
			}
			else if($_REQUEST['overwrite'] == 'false') {
				$img_base_name = explode('-', $_REQUEST['file']);
				$img_base_name = $img_base_name[1];

				// seed for PHP < 4.2.0
				srand((float) microtime() * 10000000);

				$temp = DOC_ROOT.'/site/mercury/'.$img_base_name.rand(1, 999);
				while(is_file($temp)) {
					$temp = DOC_ROOT.'/site/mercury/'.$img_base_name.rand(1, 999);
				}

				$crop->saveImage($temp, 90, $_REQUEST['type']);

				$new_image = $this->_insert_image_to_db($temp, $temp);

				update_sync_cache_list(array($new_image, DOC_ROOT . '/site/venus/thumbs/t-' . basename($new_image)));

				unlink($temp);
				unset($crop);
				return basename($new_image);
			}
		}
		return false;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return unknown
	 */
	function resize_image()
	{
		global $orbx_log;
		$input = DOC_ROOT . '/site/venus/' . $_REQUEST['file'];

		$orbx_log->dwrite('resizing image ' . $_REQUEST['file'], __LINE__, __FUNCTION__);

		if($_REQUEST['unit'] == 'percent') {
			$orbx_log->dwrite('calculating new dimensions from percentage', __LINE__, __FUNCTION__);
			$info = getimagesize($input);
			$width = $info[0];
			$height = $info[1];

			$_REQUEST['width'] = intval($width * ($_REQUEST['width'] / 100));
			$_REQUEST['height'] = intval($height * ($_REQUEST['height'] / 100));
		}

		// overwriting original?
		if($_REQUEST['overwrite'] == 'true') {

			$this->generate_thumbnail($input, $input, $_REQUEST['width'], $_REQUEST['height']);
			update_sync_cache_list($input);

			return basename($input);
		}
		else if($_REQUEST['overwrite'] == 'false') {
			$img_base_name = explode('-', $_REQUEST['file']);
			$img_base_name = $img_base_name[1];
			// temp image
			
			$output = DOC_ROOT . '/site/mercury/' . $img_base_name . rand(1, 999);

			while(is_file($output)) {
				$output = DOC_ROOT . '/site/mercury/' . $img_base_name . rand(1, 999);
			}

			$this->generate_thumbnail($input, $output, $_REQUEST['width'], $_REQUEST['height']);

			if(!is_file($output)) {
				$orbx_log->ewrite($output . ' could not be created', __LINE__, __FUNCTION__);
			}
			else {
				$orbx_log->dwrite('inserting ' . $output . ' into database', __LINE__, __FUNCTION__);
			}

			$new_image = $this->_insert_image_to_db($output, $output);

			update_sync_cache_list(array($new_image, DOC_ROOT . '/site/venus/thumbs/t-' . basename($new_image)));

			unlink($output);
			return basename($new_image);
		}
		return false;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $matrix
	 * @return unknown
	 */
	function matrix_image($matrix)
	{
		$input = DOC_ROOT . '/site/venus/' . $_POST['file'];

		// overwriting original?
		if($_REQUEST['overwrite'] == 'true') {
			$this->apply_3x3matrix($input, $input, $matrix);
			$thumb = DOC_ROOT.'/site/venus/thumbs/t-'.$_REQUEST['file'];
			$this->generate_thumbnail($input, $thumb, 150, NULL);
			update_sync_cache_list(array($input, $thumb));

			return basename($input);
		}
		else if($_REQUEST['overwrite'] == 'false') {
			$img_base_name = explode('-', $_POST['file']);
			$img_base_name = $img_base_name[1];

			// seed for PHP < 4.2.0
			srand((float) microtime() * 10000000);

			// temp image
			$output = DOC_ROOT.'/site/mercury/'.$img_base_name.rand(1, 999);
			while(is_file($output)) {
				$output = DOC_ROOT . '/site/mercury/'.$img_base_name.rand(1, 999);
			}

			$this->apply_3x3matrix($input, $output, $matrix);

			$new_image = $this->_insert_image_to_db($output, $output);
			update_sync_cache_list(array($new_image, DOC_ROOT . '/site/venus/thumbs/t-' . basename($new_image)));

			unlink($output);
			return basename($new_image);
		}
		return false;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $input
	 * @param unknown_type $output
	 * @param unknown_type $matrix
	 */
	function apply_3x3matrix($input, $output, $matrix)
	{
		if(is_file($input))
		{
			$info = getimagesize($input);

			switch($info[2]) {
				case IMAGETYPE_GIF:
					$source = imagecreatefromgif($input);
				break;
				case IMAGETYPE_JPEG:
					$source = imagecreatefromjpeg($input);
				break;
				case IMAGETYPE_PNG:
					$source = imagecreatefrompng($input);
				break;
			}

			switch($matrix) {
				case IMG_MATRIX_SHARPEN:
					$this->sharpen($source);
				break;
				case IMG_MATRIX_BLUR:
					$this->blur($source);
				break;
				case IMG_MATRIX_EMBOSS:
					$this->emboss($source);
				break;
				case IMG_MATRIX_EDGE_DETECT:
					$this->edge_detect($source);
				break;
				case IMG_MATRIX_EDGE_ENHANCE:
					$this->edge_enhance($source);
				break;
			}

			switch($info[2]) {
				case IMAGETYPE_GIF:
					imagegif($source, $output);
				break;
				case IMAGETYPE_JPEG:
					imagejpeg($source, $output);
				break;
				case IMAGETYPE_PNG:
					imagepng($source, $output);
				break;
			}
		}
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return unknown
	 */
	function grayscale_image()
	{
		$input = DOC_ROOT . '/site/venus/' . $_REQUEST['file'];
		// overwriting original?
		if($_REQUEST['overwrite'] == 'true') {
			$this->generate_grayscale($input, $input);
			$thumb = DOC_ROOT.'/site/venus/thumbs/t-'.$_REQUEST['file'];
			$this->generate_thumbnail($input, $thumb, 150, NULL);
			update_sync_cache_list(array($input, $thumb));

			return basename($input);
		}
		else if($_REQUEST['overwrite'] == 'false') {
			$img_base_name = explode('-', $_REQUEST['file']);
			$img_base_name = $img_base_name[1];
			// temp image
			$output = DOC_ROOT . '/site/mercury/' . $img_base_name.rand(1, 999);

			while(is_file($output)) {
				$output = DOC_ROOT . '/site/mercury/' . $img_base_name.rand(1, 999);
			}

			$this->generate_grayscale($input, $output);

			global $dbc;
			$new_image = $this->_insert_image_to_db($output, $output);
			update_sync_cache_list(array($new_image, DOC_ROOT . '/site/venus/thumbs/t-' . basename($new_image)));

			unlink($output);
			return basename($new_image);
		}
		return false;
	}

	//Creates yiq function
	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $r
	 * @param unknown_type $g
	 * @param unknown_type $b
	 * @return unknown
	 */
	function yiq($r, $g, $b) {
		return(($r * 0.299) + ($g * 0.587) + ($b * 0.114));
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $input
	 * @param unknown_type $output
	 */
	function generate_grayscale($input, $output)
	{
		// Get the dimensions
		list($width, $height, $type) = getimagesize($input);

		switch($type) {
			case IMAGETYPE_GIF:
				$source = imagecreatefromgif($input);
			break;
			case IMAGETYPE_JPEG:
				$source = imagecreatefromjpeg($input);
			break;
			case IMAGETYPE_PNG:
				$source = imagecreatefrompng($input);
			break;
		}

		// Creating the Canvas
		$bwimage = imagecreate($width, $height);

		//Creates the 256 color palette
		for($c=0; $c < 256; $c++) {
			$palette[$c] = imagecolorallocate($bwimage, $c, $c, $c);
		}

		//Reads the original colors pixel by pixel
		for($y = 0; $y < $height; $y++) {
			for($x = 0; $x < $width; $x++) {
				$rgb = imagecolorat($source, $x, $y);
				$r = ($rgb >> 16) & 0xFF;
				$g = ($rgb >> 8) & 0xFF;
				$b = $rgb & 0xFF;

				// This is where we actually use yiq to modify our rbg values, and then convert them to our grayscale palette
				$gs = $this->yiq($r,$g,$b);
				imagesetpixel($bwimage, $x, $y, $palette[$gs]);
			}
		}

		switch($type) {
			case IMAGETYPE_GIF:
				imagegif($bwimage, $output);
			break;
			case IMAGETYPE_JPEG:
				imagejpeg($bwimage, $output);
			break;
			case IMAGETYPE_PNG:
				imagepng($bwimage, $output);
			break;
		}
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $image_path
	 * @param unknown_type $watermark_path
	 * @return unknown
	 */
	function watermark_image($image_path, $watermark_path)
	{
		if(!is_string($image_path)) {
			trigger_error('watermark_image() expects parameter 1 to be string, '.gettype($image_path).' given', E_USER_WARNING);
			return false;
		}

		if(!is_file($image_path)) {
			trigger_error($image_path . 'is not an image', E_USER_WARNING);
			return false;
		}

		list($width, $height, $type, $attr) = getimagesize($watermark_path);

		if(!is_file($watermark_path) || ($type != IMAGETYPE_PNG)) {
			trigger_error('watermark_image() requires a watermark PNG image', E_USER_WARNING);
			return false;
		}

		// prepare watermark image
		$watermark = imagecreatefrompng($watermark_path);
		$watermark_width = imagesx($watermark);
		$watermark_height = imagesy($watermark);

		list($width, $height, $type, $attr) = getimagesize($image_path);

		switch($type) {
			case IMAGETYPE_GIF:
				$image = imagecreatefromgif($image_path);
			break;
			case IMAGETYPE_JPEG:
				$image = imagecreatefromjpeg($image_path);
			break;
			case IMAGETYPE_PNG:
				$image = imagecreatefrompng($image_path);
			break;
		}

		imagealphablending($image, true);

		$dest_x = ($width - $watermark_width - 5);
		$dest_y = ($height - $watermark_height - 5);

		imagecopy($image, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height);

		switch($type) {
			case IMAGETYPE_GIF:
				imagegif($image, $image_path);
			break;
			case IMAGETYPE_JPEG:
				imagejpeg($image, $image_path);
			break;
			case IMAGETYPE_PNG:
				imagealphablending($image, false);
				imagesavealpha($image, true);
				imagepng($image, $image_path);
			break;
		}

		imagedestroy($image);
		imagedestroy($watermark);

		return true;
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
							FROM 		'.VENUS_CATEGORIES.'
							WHERE 		(permalink = %s)
							LIMIT 		1', $dbc->_db->quote($permalink));

		$r_c = $dbc->_db->query($sql_c);
		$a_c = $dbc->_db->fetch_assoc($r_c);

		return !empty($a_c['id']);
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $file
	 */
	function get_image($file)
	{
		$file = basename($file);

		if($file != '') {
			global $dbc;

			$r = $dbc->_db->query(sprintf('
										SELECT 	*
										FROM 	'.VENUS_IMAGES.'
										WHERE 	(permalink = %s)
										LIMIT 	1', $dbc->_db->quote($file)));
			return $dbc->_db->fetch_assoc($r);
		}

		return false;
	}

		/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $file
	 */
	function update_image_desc($file, $text)
	{
		$file = basename($file);

		if($file != '') {
			global $dbc;

			$dbc->_db->query(sprintf('	UPDATE	'.VENUS_IMAGES.'
										SET		description = %s
										WHERE 	(permalink = %s)',
			$dbc->_db->quote($text), $dbc->_db->quote($file)));
		}
	}
}
?>