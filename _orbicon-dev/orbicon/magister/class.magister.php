<?php
/**
 * Text DB handler
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package System
 * @subpackage Magister
 * @version 1.30
 * @link http://
 * @license http://
 * @since 2006-07-01
 */
class Magister
{
	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $category
	 */
	function delete_category($category)
	{
		if(($category != '') && get_is_admin()) {
			global $dbc, $orbicon_x;

			// perform backup
			$dbc->_db->query(sprintf('	INSERT
										INTO 	'.MAGISTER_CATEGORIES_BCK.'
										SELECT 	*
										FROM 	'.MAGISTER_CATEGORIES.'
										WHERE 	(permalink = %s) AND
												(language = %s) ',
										$dbc->_db->quote($category), $dbc->_db->quote($orbicon_x->ptr)));

			$dbc->_db->query(sprintf('	DELETE
										FROM 	'.MAGISTER_CATEGORIES.'
										WHERE 	(permalink = %s) AND
												(language = %s)
										LIMIT 	1',
										$dbc->_db->quote($category), $dbc->_db->quote($orbicon_x->ptr)));


			$q = sprintf('	UPDATE 		'.MAGISTER_TITLES.'
							SET 		category=\'\'
							WHERE 		(category=%s) AND
										(language = %s)',
						$dbc->_db->quote($category), $dbc->_db->quote($orbicon_x->ptr));
			$dbc->_db->query($q);

			redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/magister');
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
		$r = $dbc->_db->query(sprintf('	SELECT 		*
										FROM 		'.MAGISTER_CATEGORIES.'
										WHERE 		(language = %s)
										ORDER BY 	permalink', $dbc->_db->quote($orbicon_x->ptr)));
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$r_c = $dbc->_db->query(sprintf('	SELECT 		COUNT(id)
												FROM 		'.MAGISTER_TITLES.'
												WHERE 		(live = 1) AND
															(hidden = 0) AND
															(category = %s) AND
															(language = %s)',
			$dbc->_db->quote($a['permalink']), $dbc->_db->quote($orbicon_x->ptr)));
			$a_c = $dbc->_db->fetch_array($r_c);

			$delete_url = '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/magister&amp;del_cat='.$a['permalink'].'" onclick="return false;" onmousedown="'.delete_popup($a['name']).'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/delete.png" /></a>';

			$menu .= '<li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/magister&amp;read=kategorija/'.$a['permalink'].'/">'.$a['name'].'</a><br />
			<div class="div-controller">'.$delete_url.' | '._L('texts_lc').': '.$a_c[0].'</div></li>';

			$a = $dbc->_db->fetch_assoc($r);
		}

		// unsorted
		$r_c = $dbc->_db->query(sprintf('	SELECT 		COUNT(id)
											FROM 		'.MAGISTER_TITLES.'
											WHERE 		(live = 1) AND
														(hidden = 0) AND
														((category = \'\') OR
														(category IS NULL)) AND
														(language = %s)',
											$dbc->_db->quote($orbicon_x->ptr)));
		$a_c = $dbc->_db->fetch_array($r_c);

		if($a_c[0] > 0) {
			$menu.= '<li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/magister&amp;read=kategorija/_orbx_unsorted/">'._L('unsorted').'</a><br />
				<div class="div-controller">'._L('texts_lc').': '.$a_c[0].'</div></li>';
		}

		$menu = '
				<div class="category-picker-filter-images">
					<img src="'.ORBX_SITE_URL.'/orbicon/gfx/mini_browser_gui/picker-txt.gif" />
					<h1>'._L('categories').' - '._L('texts').'</h1>
					<ul class="ul-text-picker">'.$menu.'</ul>
				</div>';

		$form = '
				<div class="category-picker-filter-images">
					<a href="javascript:void(null); "onclick="javascript:sh(\'div_new_cat\');"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/mini_browser_gui/add.png" width="16" height="16" border="0"> '._L('new_category').'</a><br />
					<span style="color: #666;">['._L('multiple_categories_separate').' &quot;,&quot;]</span><br />
				</div>
				<div class="category-picker-filter-images" style="display:none;" id="div_new_cat">
				<fieldset>
					<legend><strong><label for="new_magister_category">'._L('new_categories').'</label></strong></legend><br />
						<textarea name="new_magister_category" id="new_magister_category" cols="22" rows="4"></textarea>
						<input type="button" onclick="__magister_cat_update_list(\''.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon&ajax_text_db&action=add_category\');" value="'._L('submit').'" />
				</fieldset><br />
			</div>';

		return $menu.$form;
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
		$r = $dbc->_db->query(sprintf('	SELECT 		*
										FROM 		'.MAGISTER_CATEGORIES.'
										WHERE 		(language = %s)
										ORDER BY 	name', $dbc->_db->quote($orbicon_x->ptr)));
		$list = $dbc->_db->fetch_assoc($r);

		$menu = '<div class="category-picker-filter-images"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/mini_browser_gui/picker-txt.gif" />
						<h1>'._L('categories').' - '._L('texts').'</h1>
						<ul class="ul-text-picker">';

		while($list) {
			$r_count = $dbc->_db->query(sprintf('	SELECT 		COUNT(id)
													FROM 		'.MAGISTER_TITLES.'
													WHERE 		(live = 1) AND
																(hidden = 0) AND
																(category = %s) AND
																(language = %s)',
			$dbc->_db->quote($list['permalink']), $dbc->_db->quote($orbicon_x->ptr)));

			$count = $dbc->_db->fetch_array($r_count);

			$delete_url = '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/magister&amp;del_cat='.$list['permalink'].'" onclick="return false;" onmousedown="'.delete_popup($list['name']).'"><img alt="'._L('delete').'" title="'._L('delete').'" src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/delete.png" /></a>';

			$menu .= '
				<li><a href="javascript:void(null);" onclick="javascript:switch_mini_browser(\'magister\', \''.$list['permalink'].'\', 0, 0);">'.$list['name'].'</a><br />
					<div class="div-controller">'.$delete_url.' | '._L('texts_lc').': '.$count[0].'</div>
				</li>';

			$list = $dbc->_db->fetch_assoc($r);
		}

		// unsorted
		$r = $dbc->_db->query(sprintf('	SELECT 		COUNT(id) AS total
										FROM 		'.MAGISTER_TITLES.'
										WHERE 		(live = 1) AND
													(hidden = 0) AND
													(language = %s) AND
													((category = \'\') OR (category IS NULL))',
										$dbc->_db->quote($orbicon_x->ptr)));

		$unsorted = $dbc->_db->fetch_assoc($r);

		if($unsorted['total'] > 0) {
				$menu .= '
				<li><a href="javascript:void(null);" onclick="javascript:switch_mini_browser(\'magister\', \'_orbx_unsorted\', 0, 0);">'._L('unsorted').'</a><br />
					<div class="div-controller">'._L('texts_lc').': '.$unsorted['total'].'</div>
				</li>';
		}

		$menu .= '</ul></div>';

		if(urldecode($_GET[$orbicon_x->ptr]) == 'orbicon/magister') {
			$form = '
				<div class="category-picker-filter-images">
					<a href="javascript:void(null); "onclick="javascript:sh(\'div_new_cat\');"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/mini_browser_gui/add.png" width="16" height="16" border="0"/> '._L('new_category').'</a><br />
					<span style="color: #666;">['._L('multiple_categories_separate').' &quot;,&quot;]</span><br />
				</div>
				<div class="category-picker-filter-images" style="display:none;" id="div_new_cat">
				<fieldset>
					<legend><strong><label for="new_magister_category">'._L('new_categories').'</label></strong></legend><br />
						<textarea name="new_magister_category" id="new_magister_category" cols="22" rows="4"></textarea>
						<input type="button" onclick="__magister_cat_update_list(\''.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon&ajax_text_db&action=add_category\');" value="'._L('submit').'" />
				</fieldset><br />
			</div>';
		}

		return $menu.$form;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param unknown_type $category
	 * @param unknown_type $start
	 * @param unknown_type $search
	 * @param unknown_type $show_cards
	 * @return unknown
	 */
	function get_mini_browser_texts($category, $start, $search = '', $show_cards = 0)
	{
		global $dbc, $orbicon_x;

		$cat_sql = ($category == '_orbx_unsorted') ? '((category = \'\') OR (category IS NULL))' : sprintf('(category = %s)', $dbc->_db->quote($category));

		if($search !== '') {

			$search = urldecode($search);
			$search_lc = utf8_html_entities(strtolower($search));
			$search_sql = utf8_html_entities($search);

			$r = $dbc->_db->query(sprintf('	SELECT 		*
											FROM 		'.MAGISTER_TITLES.'
											WHERE 		'.$cat_sql.' AND
														((title LIKE %s) OR
														(title LIKE %s)) AND
														(language = %s)
											ORDER BY 	uploader_time DESC, title
											LIMIT 		'.$start.', 4',
											$dbc->_db->quote('%' . $search_lc . '%'), $dbc->_db->quote('%' . $search_sql . '%'), $dbc->_db->quote($orbicon_x->ptr)));
		}
		else {
			$r = $dbc->_db->query(sprintf('	SELECT 		*
											FROM 		'.MAGISTER_TITLES.'
											WHERE 		'.$cat_sql.' AND
														(language = %s)
											ORDER BY 	uploader_time DESC, title
											LIMIT 		'.$start.', 4',
											$dbc->_db->quote($orbicon_x->ptr)));
		}

		$a = $dbc->_db->fetch_assoc($r);

		$menu = '<div class="image-picker">
<img src="./orbicon/gfx/mini_browser_gui/picker-txt.gif">
<h1>'._L('text_picker').'</h1>
<div class="div-search">'._L('text_search').'</div>
<div class="div-controller">
  <input type="text" name="minibrowser_search" id="minibrowser_search" onkeypress="javascript: if(get_enter_pressed(event)) {switch_mini_browser(\'magister\', \''.$category.'\', 0, 0);}" />
  <input type="button" name="Submit" value="GO!" onclick="javascript:switch_mini_browser(\'magister\', \''.$category.'\', 0, 0);" />
</div>
<ul class="ul-text-picker">';

		$referer = (strpos(urldecode($_SERVER['HTTP_REFERER']), 'orbicon/magister') === false) ? false : true;

		while($a) {

			if($referer) {
				$menu .= '<li><div class="text-div-preview"><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/magister&read=clanak/'.$a['permalink'].'/">'.$a['title'].'</a><br />';
			}
			else {
				$menu .= '<li><a href="javascript:void(null);" onclick="javascript:magister_do_mini_update(\''.$a['permalink'].'\', this);">'.$a['title'].'</a><br />
<div class="text-div-preview"><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/magister&read=clanak/'.$a['permalink'].'/"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/mini_browser_gui/edit.png" alt="'._L('edit').'" title="'._L('edit').'" width="16" height="16" align="left" hspace="4px" vspace="2px"></a>';
			}


			// text summaries
			if($show_cards) {
				$r_s = $dbc->_db->query(sprintf('	SELECT 		id, content
													FROM 		'.MAGISTER_CONTENTS.'
													WHERE 		(live = 1) AND
																(question_permalink = %s) AND
																(language = %s)',
																$dbc->_db->quote($a['permalink']), $dbc->_db->quote($orbicon_x->ptr)));
				$a_s = $dbc->_db->fetch_assoc($r_s);
				$i = 1;

				if(!$referer) {
					$menu .= truncate_text(strip_tags($a_s['content']), 50, '...').'<br /><a href="javascript:void(null);" onclick="javascript: sh(\'intro_txts_'.$a['permalink'].'\');">'._L('more').'<img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/bullet_arrow_down.png" alt="'._L('more').'" title="'._L('more').'" /></a></div><div class="h" id="intro_txts_'.$a['permalink'].'">';

					while($a_s) {
						$short = truncate_text(strip_tags($a_s['content']), 50, '...');
						$menu .= '<div class="text-div-preview-text"><strong><a href="javascript:void(null);" onclick="javascript:__change_intro_text(\''.base64_encode($a_s['content']).'\', '.$a_s['id'].');">'._L('pick_intro_text').' '.$i.'</a> </strong><br />'.$short.'</div>';

						$a_s = $dbc->_db->fetch_assoc($r_s);
						$i ++;
					}
				}
				else {
					$menu .= truncate_text(strip_tags($a_s['content']), 50, '...');
				}
			}

			$menu .= '</div></li>';

			$i = 1;
			$a = $dbc->_db->fetch_assoc($r);
		}

		$current = (($start + 4) / 4);

		$count = $dbc->_db->query(sprintf('		SELECT 		COUNT(id)
												FROM 		' . MAGISTER_TITLES . '
												WHERE 		' . $cat_sql . ' AND
															(language = %s)', $dbc->_db->quote($orbicon_x->ptr)));
		$count = $dbc->_db->fetch_array($count);
		$count = $count[0];

		$next = ($count > ($start + 4)) ? '<a href="javascript:void(null);" onclick="javascript:switch_mini_browser(\'magister\', \''.$category.'\', 0, '.($start + 4).');">'._L('next').' &gt;&gt;</a>' : _L('next').' &gt;&gt;';
		$back = ($start > 0) ? '<a href="javascript:void(null);" onclick="javascript:switch_mini_browser(\'magister\', \''.$category.'\', 0, '.($start - 4).');">&lt;&lt; '._L('previous').'</a>' : '&lt;&lt; '._L('previous');

		$root_menu = '<a href="javascript:void(null);" onclick="switch_mini_browser(\'magister\', \'\', 0, 0);">' . _L('back') . '</a>';

		$menu .= "</ul></div><div class=\"image-picker\"><strong>$root_menu | $back | $current | $next</strong></div>";
		return $menu;
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
		global $dbc, $orbicon_x;

		$categories = '<option value="">'._L('unsorted').'</option>';

		$r = $dbc->_db->query(sprintf('		SELECT 		*
											FROM 		'.MAGISTER_CATEGORIES.'
											WHERE 		(language = %s)
											ORDER BY 	permalink', $dbc->_db->quote($orbicon_x->ptr)));
		$a = $dbc->_db->fetch_assoc($r);
		while($a) {

			$selected = (($current != '') && ($current == $a['permalink'])) ? ' selected="selected"' : '';
			$categories .= '<option value="'.$a['permalink']."\"$selected>".$a['name'].'</option>';
			$a = $dbc->_db->fetch_array($r);
		}
		$dbc->_db->free_result($r);

		return $categories;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $category
	 */
	function add_new_category($category)
	{
		global $dbc, $orbicon_x;

		$new = explode(',', $category);

		foreach($new as $value) {
			$value = trim($value);
			if($value != '') {
				$permalink = get_permalink($value);

				if(!$this->category_exists($permalink)) {

					$value = utf8_html_entities($value);
					$dbc->_db->query(sprintf('	INSERT INTO 	'.MAGISTER_CATEGORIES.'
																(name, permalink,
																language)
												VALUES 			(%s, %s,
																%s)',
					$dbc->_db->quote($value), $dbc->_db->quote($permalink),
					$dbc->_db->quote($orbicon_x->ptr)));
				}
			}
		}
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 */
	function print_magister_ajax_text()
	{
		global $dbc, $orbicon_x, $orbx_mod;

		if(isset($_POST['text_id'])) {
			$r = $dbc->_db->query(sprintf('		SELECT 		content
												FROM 		'.MAGISTER_CONTENTS.'
												WHERE 		(live = 1) AND
															(id = %s) AND
															(language = %s)
												LIMIT 		1', $dbc->_db->quote($_POST['text_id']), $dbc->_db->quote($orbicon_x->ptr)));
			$a = $dbc->_db->fetch_assoc($r);
			echo $a['content'];
			unset($a);
		}
		else if(isset($_POST['permalink'])) {
			$r = $dbc->_db->query(sprintf('		SELECT 		content
												FROM 		'.MAGISTER_CONTENTS.'
												WHERE 		(live = 1) AND
															(hidden = 0) AND
															(question_permalink = %s) AND
															(language = %s)', $dbc->_db->quote($_POST['permalink']), $dbc->_db->quote($orbicon_x->ptr)));
			$a = $dbc->_db->fetch_assoc($r);
			while($a) {
				echo '<p>'.$a['content'].'</p>';
				$a = $dbc->_db->fetch_assoc($r);
			}

			if(!$a['content']) {
				if($_POST['column_type'] == 'photo') {
					include_once DOC_ROOT.'/orbicon/class/inc.mmedia.php';
					echo print_image_gallery($_POST['permalink']);
				}
				elseif ($_POST['column_type'] == 'photo') {

				}
				elseif ($_POST['column_type'] == 'video') {
					// we have a module
					if($orbx_mod->validate_module('video-gallery')) {
						global $video_gallery;
						$video_gallery = $_POST['permalink'];
						echo include_once DOC_ROOT . '/orbicon/modules/video-gallery/render.video.php';
					}
					else {
						include_once DOC_ROOT . '/orbicon/class/inc.mmedia.php';
						echo print_video_gallery($_POST['permalink']);
					}
				}
				elseif ($_POST['column_type'] == 'data') {
					include_once DOC_ROOT . '/orbicon/class/inc.mmedia.php';
					echo print_download_gallery($_POST['permalink']);
				}
				elseif ($_POST['column_type'] == 'form') {
					$q_f = sprintf('SELECT 	*
									FROM 	'.TABLE_FORMS.'
									WHERE 	(permalink=%s) AND
											(language = %s)
									LIMIT 	1',
									$dbc->_db->quote($_POST['permalink']), $dbc->_db->quote($orbicon_x->ptr));
					$r_f = $dbc->_db->query($q_f);
					$a_f = $dbc->_db->fetch_assoc($r_f);

					// try to locate translated template or load default english
					$template_file = (is_file(DOC_ROOT . '/orbicon/templates/' . $orbicon_x->ptr . '.form.'.$a_f['template'].'.php')) ? $orbicon_x->ptr . '.form.'.$a_f['template'].'.php' : 'en.form.'.$a_f['template'].'.php';

					$a['magister_content'] = include DOC_ROOT.'/orbicon/templates/' . $template_file;

					// append lead text above the form
					$r_ = $dbc->_db->query(sprintf('SELECT 		content
													FROM 		'.MAGISTER_CONTENTS.'
													WHERE 		(live = 1) AND
																(hidden = 0) AND
																(question_permalink = %s) AND
																(language = %s)
													ORDER BY 	uploader_time', $dbc->_db->quote($a_f['linked_text']), $dbc->_db->quote($orbicon_x->ptr)));
					$a_ = $dbc->_db->fetch_assoc($r_);

					while($a_) {
						$lead_txt .= $a_['content'];
						$a_ = $dbc->_db->fetch_assoc($r_);
					}
					$dbc->_db->free_result($r_);

					// add admin edit shortcut to magister db
					if(!empty($lead_txt) && get_is_admin()) {
						$lead_txt = $orbicon_x->admin_layout('<p><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/magister&amp;read=clanak/'.$a_f['linked_text'].'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/edit.png"></a></p>').$lead_txt;
					}

					$a['magister_content'] = $lead_txt . $a['magister_content'];
					unset($lead_txt);

					echo $a['magister_content'];
				}
			}
		}
		else if(isset($_POST['intro_permalink'])) {
			$r = $dbc->_db->query(sprintf('		SELECT 		id,content
												FROM 		'.MAGISTER_CONTENTS.'
												WHERE 		(live = 1) AND
															(question_permalink = %s) AND
															(language = %s)', $dbc->_db->quote($_POST['intro_permalink']), $dbc->_db->quote($orbicon_x->ptr)));
			$a = $dbc->_db->fetch_assoc($r);
			echo '<h3>'._L('select_intro_txt').'</h3><ol>';

			while($a) {
				$short = substr($a['content'], 0, 50);
				echo '<li><a href="javascript:void(null);" onclick="javascript:__change_intro_text(\''.base64_encode($a['content']).'\', '.$a['id'].');">&laquo; '.$short.'...</a></li>';
				$a = $dbc->_db->fetch_array($r);
			}

			echo '</ol>';
		}
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return unknown
	 */
	function set_hidden_flag()
	{
		if(isset($_POST['card_id'])) {
			global $dbc, $orbicon_x;
			$flag = ($_POST['flag'] == 'true') ? 1 : 0;

			$q = sprintf('		UPDATE 		'.MAGISTER_CONTENTS.'
								SET 		hidden=%s, last_modified = UNIX_TIMESTAMP()
								WHERE 		(id=%s) AND
											(language = %s)
								LIMIT 		1',
			$dbc->_db->quote($flag), $dbc->_db->quote($_POST['card_id']), $dbc->_db->quote($orbicon_x->ptr));
			$dbc->_db->query($q);
			return $flag;
		}
		return 0;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 */
	function save_card()
	{
		if(isset($_POST['update_article'])) {
			global $dbc, $orbicon_x;

			// check for new internal links

			$internal_links = explode('|', $_POST['internal_links']);

			if(!empty($internal_links)) {
				foreach($internal_links as $value) {
					if(!empty($value)) {
						$check = explode('?'.$orbicon_x->ptr.'=', $value);
						$permalink = get_permalink($check[1]);
						$_check = sprintf('	SELECT 	id
											FROM 	'.TABLE_COLUMNS.'
											WHERE 	(permalink = %s) AND
													(language = %s)',
											$dbc->_db->quote($permalink), $dbc->_db->quote($orbicon_x->ptr));
						$r = $dbc->_db->query($_check);
						$a = $dbc->_db->fetch_assoc($r);

						if(empty($a['id'])) {
							$a_orbicon[] = $value;
							$a_href[] = str_replace($value, url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$permalink, ORBX_SITE_URL.'/'.$orbicon_x->ptr.'/'.$permalink), $value);

							$q = sprintf('	INSERT INTO 	'.TABLE_COLUMNS.'
															(title, permalink,
															menu_name, language)
											VALUES 			(%s, %s,
															\'hidden\', %s)',
							$dbc->_db->quote($check[1]), $dbc->_db->quote($permalink),
							$dbc->_db->quote($orbicon_x->ptr));
							$dbc->_db->query($q);
						}
					}
				}

				$_POST['content'] = str_replace($a_orbicon, $a_href, $_POST['content']);
			}

			$content = utf8_html_entities(trim(stripslashes($_POST['content'])));
			$content = $this->close_tags($content);
			$content = $this->hyperlinks_add($content);
			// standardize these and fix javascript comment if found

			$content = str_replace(
				array(
				'<b>', '<B>', '</b>', '</B>',
				'<i>', '<I>', '</i>', '</I>',
				'<script type="text/javascript"><!-- // --><![CDATA[',
				'// ]]></script>'),
				array(
				'<strong>', '<strong>', '</strong>', '</strong>',
				'<em>', '<em>', '</em>', '</em>',
				'<script type="text/javascript"><!-- // --><![CDATA[' . "\n\n",
				"\n\n" . '// ]]></script>'),
				$content);

			$author = $_SESSION['user.a']['first_name'].' '.$_SESSION['user.a']['last_name'];

			if(empty($_POST['current_edit'])) {
				$_q = sprintf('		INSERT INTO '.MAGISTER_CONTENTS.'
												(question_permalink, original_author,
												original_author_permalink, original_author_contact,
												content, uploader,
												uploader_ip, uploader_time,
												live, live_time,
												language)
									VALUES 		(%s, %s,
												%s, %s,
												%s, %s,
												%s, UNIX_TIMESTAMP(),
												1, UNIX_TIMESTAMP(),
												%s)',
												$dbc->_db->quote($_POST['current_edit_permalink']), $dbc->_db->quote($author),
												$dbc->_db->quote(get_permalink($author)), $dbc->_db->quote($_SESSION['user.a']['email']),
												$dbc->_db->quote($content), $dbc->_db->quote($author),
												$dbc->_db->quote(ORBX_CLIENT_IP), $dbc->_db->quote($orbicon_x->ptr));
			}
			else {
				$_q = sprintf('	UPDATE 		'.MAGISTER_CONTENTS.'
								SET 		content=%s, last_modified = UNIX_TIMESTAMP()
								WHERE 		(id=%s) AND
											(language = %s)
								LIMIT 		1',
				$dbc->_db->quote($content), $dbc->_db->quote($_POST['current_edit']),
				$dbc->_db->quote($orbicon_x->ptr));
			}
			$dbc->_db->query($_q);

			echo $content;
		}
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 */
	function update_article_info()
	{
		global $orbicon_x, $dbc;

		$now = time();
		$title = $_REQUEST['title'];
		$permalink = get_permalink($title);
		//$live_time = ($_REQUEST['live_time'] == $_REQUEST['uploader_time']) ? $now : $_REQUEST['live_time'];
		$current_q = $_REQUEST['current_permalink'];

		$q_ = sprintf('	UPDATE 		'.MAGISTER_TITLES.'
						SET			category=%s, title=%s,
									uploader=%s, permalink=%s,
									last_modified = %s
						WHERE 		(permalink=%s) AND
									(language = %s)',
			$dbc->_db->quote($_REQUEST['category']), $dbc->_db->quote(utf8_html_entities($title)),
			$dbc->_db->quote($_REQUEST['uploader']),$dbc->_db->quote($permalink), $dbc->_db->quote($now),
			$dbc->_db->quote($current_q), $dbc->_db->quote($orbicon_x->ptr));
			$dbc->_db->query($q_);

		$q2 = sprintf('	UPDATE 		'.MAGISTER_CONTENTS.'
						SET 		question_permalink=%s
						WHERE 		(question_permalink=%s) AND
									(language = %s)', $dbc->_db->quote($permalink), $dbc->_db->quote($current_q), $dbc->_db->quote($orbicon_x->ptr));
		$dbc->_db->query($q2);
	}

	/**
	 * Enter description here...
	 *
	 * @author Alen Novakovic <alen.novakovic@orbitum.net>
	 * @return unknown
	 */
	function check_category()
	{
		global $dbc, $orbicon_x;
		$r = $dbc->_db->query(sprintf('	SELECT 		id
										FROM 		'.MAGISTER_CATEGORIES.'
										WHERE 		(language = %s)
										ORDER BY 	permalink', $dbc->_db->quote($orbicon_x->ptr)));
		$a = $dbc->_db->num_rows($r);

		return $a;
	}

	// closes tags via WP
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $text
	 * @return unknown
	 */
	function close_tags($text)
	{
		$tagstack = array();
		$stacksize = 0;
		$tagqueue = '';
		$newtext = '';

		while(preg_match('/<(\/?\w*)\s*([^>]*)>/', $text, $regex)) {
			$newtext .= $tagqueue;

			$i = strpos($text,$regex[0]);
			$l = strlen($regex[0]);

			// clear the shifter
			$tagqueue = '';
			// Pop or Push
			if ($regex[1][0] == '/') { // End Tag
				$tag = strtolower(substr($regex[1],1));
				// if too many closing tags
				if($stacksize <= 0) {
					$tag = '';
					//or close to be safe $tag = '/' . $tag;
				}
				// if stacktop value = tag close value then pop
				else if ($tagstack[$stacksize - 1] == $tag) { // found closing tag
					$tag = '</' . $tag . '>'; // Close Tag
					// Pop
					array_pop ($tagstack);
					$stacksize--;
				}
				else { // closing tag not at top, search for it
					for($j = $stacksize - 1; $j >= 0; $j--) {
						if($tagstack[$j] == $tag) {
						// add tag to tagqueue
							for ($k = $stacksize - 1; $k >= $j; $k--){
								$tagqueue .= '</' . array_pop ($tagstack) . '>';
								$stacksize--;
							}
							break;
						}
					}
					$tag = '';
				}
			}
			else { // Begin Tag
				$tag = strtolower($regex[1]);

				// Tag Cleaning

				// If self-closing or '', don't do anything.
				if((substr($regex[2],-1) == '/') || ($tag == '')) {
					// do nothing
				}
				// ElseIf it's a known single-entity tag but it doesn't close itself, do so
				else if($tag == 'br' || $tag == 'img' || $tag == 'hr' || $tag == 'input' || $tag == 'link' || $tag == 'meta' || $tag == 'xml' || $tag == 'param') {
					$regex[2] .= '/';
				}
				else {	// Push the tag onto the stack
					// If the top of the stack is the same as the tag we want to push, close previous tag
					if (($stacksize > 0) && ($tag != 'div') && ($tagstack[$stacksize - 1] == $tag)) {
						$tagqueue = '</' . array_pop ($tagstack) . '>';
						$stacksize--;
					}
					$stacksize = array_push ($tagstack, $tag);
				}

				// Attributes
				$attributes = $regex[2];

				if($attributes) {
					$attributes = ' '.$attributes;
				}
				$tag = '<'.$tag.$attributes.'>';
				//If already queuing a close tag, then put this tag on, too
				if ($tagqueue) {
					$tagqueue .= $tag;
					$tag = '';
				}
			}
			$newtext .= substr($text,0,$i) . $tag;
			$text = substr($text,$i+$l);
		}

		// Clear Tag Queue
		$newtext .= $tagqueue;

		// Add Remaining text
		$newtext .= $text;

		// Empty Stack
		while($x = array_pop($tagstack)) {
			$newtext .= '</' . $x . '>'; // Add remaining tags to close
		}

		return $newtext;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $haystack
	 * @return unknown
	 */
	function hyperlinks_add($haystack)
	{
		$haystack = ' ' . $haystack;
		// in testing, using arrays here was found to be faster
		$haystack = preg_replace(
		array(
			'#([\s>])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is',
			'#([\s>])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is',
			'#([\s>])([a-z0-9\-_.]+)@([^,< \n\r]+)#i'),
		array(
			'$1<a href="$2" rel="nofollow">$2</a>',
			'$1<a href="http://$2" rel="nofollow">$2</a>',
			'$1<a href="mailto:$2@$3">$2@$3</a>'), $haystack);
		// this one is not in an array because we need it to run last, for cleanup of accidental links within links
		$haystack = preg_replace("#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i", "$1$3</a>", $haystack);
		$haystack = trim($haystack);
		return $haystack;
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
		global $dbc, $orbicon_x;

		$sql_c = sprintf('	SELECT 		id
							FROM 		'.MAGISTER_CATEGORIES.'
							WHERE 		(permalink = %s) AND
										(language = %s)
							LIMIT 		1', $dbc->_db->quote($permalink), $dbc->_db->quote($orbicon_x->ptr));

		$r_c = $dbc->_db->query($sql_c);
		$a_c = $dbc->_db->fetch_assoc($r_c);

		return !empty($a_c['id']);
	}

	/*function save_answer()
	{
		global $dbc, $orbicon_x;
		$sPitanje = sprintf('SELECT * FROM '.MAGISTER_TITLES.' WHERE (permalink = %s) AND (live = 1) AND (hidden = 0) AND (language = %s) LIMIT 1', $dbc->_db->quote($aQ[1]), $dbc->_db->quote($orbicon_x->ptr));
		$rPitanje = $dbc->_db->query($sPitanje);
		$aPitanje = $dbc->_db->fetch_assoc($rPitanje);

		if(isset($_POST['Submit']) && !empty($aPitanje['permalink']))
		{
			$sPermalink2 = get_permalink($_POST['original_author_permalink']);

			$sContent = trim($_POST['content_article']);

			require_once(DOC_ROOT.'/orbicon/3rdParty/snoopy/Snoopy.class.php');
			$agent = new Snoopy;

			// * fetch all images from IMG tags and save them locally
			preg_match_all("/<IMG.+?SRC=[\"']([^\"']+)/si", $sContent, $sub, PREG_SET_ORDER);

			$formvars['original_author'] = $_POST['original_author'];
			$formvars['original_author_contact'] = $_POST['original_author_contact'];
			$formvars['content_article'] = $aPitanje['title'];
			$formvars['licence'] = 'gnu';
			$formvars['Submit'] = 'Submit';

			for($i=0; $i < count($sub); $i++)
			{
				$img_name = basename($sub[$i][1]);
				$local_path = DOC_ROOT.'/site/venus/'.$img_name;
				// * fetch image
				$agent->fetch($sub[$i][1]);

				// * save it locally
				$r = fopen($local_path, 'wb');
				fwrite($r, $agent->results);
				fclose($r);

				// * post it to venus
				$formvars['image_name'] = $img_name;

				$formfiles['image_file'] = $local_path;

				$agent->set_submit_multipart();
				$agent->submit(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/venus&amp;read=objavi-sliku/', $formvars, $formfiles);

				$venus_img_url = explode("\r\n", $mater->results, 1);
				$venus_img_url = trim(str_replace(array('<SNOOPY_POST_URL>', '</SNOOPY_POST_URL>'), '', $venus_img_url[0]));

				// * delete it from magister
				unlink($local_path);

				// * replace SRC attribute
				$sContent = str_replace($sub[$i][1], $venus_img_url, $sContent);
			}

			if(!empty($_FILES['image_file']['name']))
			{
				$formvars['original_author'] = $_POST['original_author'];
				$formvars['original_author_contact'] = $_POST['original_author_contact'];
				$formvars['image_name'] = $_FILES['image_file']['name'];
				$formvars['content_article'] = $aPitanje['title'];
				$formvars['licence'] = 'gnu';
				$formvars['Submit'] = 'Submit';

				$formfiles['image_file'] = $_FILES['image_file']['tmp_name'];

				$agent->set_submit_multipart();
				$agent->submit(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/venus&amp;read=objavi-sliku/', $formvars, $formfiles);

				$venus_img_url = explode("\r\n", $agent->results, 1);
				$venus_img_url = trim(str_replace(array('<SNOOPY_POST_URL>', '</SNOOPY_POST_URL>'), '', $venus_img_url[0]));

				$img_title = str_replace('"', '&quot;', $aPitanje['title']);
				$sContent = "<img src=\"$venus_img_url\" alt=\"$img_title\" title=\"$img_title\" style=\"padding: 1em;\" /><br />\n$sContent";

				copy($_FILES['image_file']['tmp_name'], $sPutanja);
			}

			$content_test = trim(strip_tags($sContent, '<img>'));

			$sContent = strip_tags($sContent, '<img><p><br><table><tr><td><span><li><ul><ol><a><b><i><u><abbr><acronym><blockquote><q><strong><em><cite><h1><h2><h3><h4><h5><h6><sub><sup>');*/
			//$sContent = str_replace(array("\"", "'"), array("&quot;", "&#039;"), $sContent);
			/*define('XML_HTMLSAX3', dirname(__FILE__).'/administration/safehtml/classes/');
			require_once(dirname(__FILE__).'/administration/safehtml/classes/safehtml.php');
			$safehtml =& new safehtml();
			$sContent = $safehtml->parse($sContent);*/

			/*$sInsert = sprintf('INSERT INTO '.MAGISTER_CONTENTS.' (
														id, question_permalink, original_author, original_author_permalink, original_author_contact,
														content, uploader, uploader_ip, uploader_time,
														live, live_time, language) VALUES (
														\'\', %s, %s, %s, %s,
														%s, %s, %s, UNIX_TIMESTAMP(),
														1, UNIX_TIMESTAMP(), %s)',
														$dbc->_db->quote($aPitanje['permalink']), $dbc->_db->quote(utf8_html_entities($_POST['original_author'])), $dbc->_db->quote($sPermalink2), $dbc->_db->quote($_POST['original_author_contact']),
														$dbc->_db->quote(utf8_html_entities($sContent)), $dbc->_db->quote(utf8_html_entities($_POST['original_author'])), $dbc->_db->quote(ORBX_CLIENT_IP), $dbc->_db->quote($orbicon_x->ptr));
			if($content_test == '' && empty($_FILES['image_file']['name']))
			{
				$empty_js = 'alert(\'Tekst nije poslan jer niste ništa napisali.\');';
				$empty_feedback = '<p><fieldset><legend style="color:red; font-size: 1.7em;">Gre&#353;ka!</legend>Tekst nije poslan jer niste ništa napisali.</fieldset></p>';

			}
			else
			{
				$dbc->_db->query($sInsert);

				echo '<meta http-equiv="refresh" content="0; URL='.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/magister&amp;read=clanak/'.$aPitanje['permalink'].'" />';
			}
		}
	}*/
}
?>