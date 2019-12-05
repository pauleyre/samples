<?php

/**
 * Column administration library
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package SystemFE
 * @version 1.20
 * @link http://
 * @license http://
 * @since 2007-07-01
 */

require_once DOC_ROOT . '/orbicon/class/inc.column.php';

	/**
	 * Enter description here...
	 *
	 */
	function save_column()
	{
		if(isset($_POST['save_column'])) {
			$title = $_POST['column_title'];
			$content = $_POST['content_text'];
			$type = $_POST['column_type'];
			$redirect = ($_POST['column_redirect']);
			$box_zone = $_POST['box_zone'];
			$tpl = $_POST['template'];
			$tpl = (!$tpl) ? 'column.html' : $tpl;

			global $dbc, $orbicon_x, $orbx_mod;

			$r = sql_res('		SELECT 		content
								FROM 		'.MAGISTER_CONTENTS.'
								WHERE 		(live = 1) AND
											(hidden = 0) AND
											(question_permalink = %s) AND
											(language = %s)
								ORDER BY 	uploader_time', array($content, $orbicon_x->ptr));
			$a = $dbc->_db->fetch_assoc($r);

			while($a) {
				$keywords .= $a['content'];
				$a = $dbc->_db->fetch_assoc($r);
			}

			$dbc->_db->free_result($r);

			$permalink = get_permalink($title);

			// stupid
			$tmp = new OrbiconX_Administration;
			$_check_permalink = $tmp->get_permalink_exists($permalink);
			unset($tmp);

			// we might have selected our own id so what should we do?
			// let's see if we did
			$_check_permalink = ($_check_permalink == $_POST['column_id']) ? 0 : $_check_permalink;

			// don't allow conflicting permalinks
			if(!empty($_check_permalink)) {

				$a_parent = sql_assoc('	SELECT 		parent
										FROM 		'.TABLE_COLUMNS.'
										WHERE 		(permalink=%s) AND
													(language=%s)',
										array($_GET['edit'], $orbicon_x->ptr));

				if(!empty($a_parent['parent'])) {
					$permalink = "$permalink-({$a_parent['parent']})";
				}
				else {
					echo '<script type="text/javascript">window.alert(\''._L('perma_conflict').'\');</script>';
					$permalink = $permalink . adler32(time() . $permalink);
				}
			}

			$title = utf8_html_entities($title);

			$content = (trim(stripslashes($content)));

			$keywords = keyword_generator(strip_tags($keywords));

			// * box style
			$box_style = null;

			if(isset($_POST['column_background'])) {
				$_POST['column_background'] = (empty($_POST['column_background'])) ? 'transparent' : $_POST['column_background'];
				$_POST['column_border'] = (empty($_POST['column_border'])) ? 'transparent' : $_POST['column_border'];

				$background = 'background:'.$_POST['column_background'].';';
				$border = 'border-color:'.$_POST['column_border'].';';
				$box_style = $border . $background;
			}

			// form
			if($type == 'form') {
				$content = $_POST['existing_form'];
			}
			// photo gallery
			else if($type == 'photo') {
				$content = $_POST['image_categories'];
			}
			// video gallery
			else if($type == 'video') {
				$content = $_POST['data_categories'];
			}
			// download gallery
			else if($type == 'data') {
				$content = $_POST['dl_categories'];
			}

			$permalink_ascii = $orbicon_x->urlnormalize($permalink, true);

			sql_update('
						UPDATE 	'.TABLE_COLUMNS.'
						SET 	title=%s, content=%s,
								keywords=%s, permalink=%s,
								lastmod=UNIX_TIMESTAMP(), '.TABLE_COLUMNS.'.type=%s,
								box_style=%s, redirect=%s,
								box_zone=%s, language=%s,
								template=%s, permalink_ascii=%s,
								infogroup=%s, parent=%s,
								parent_ascii=%s, menu_name=%s,
								'.TABLE_COLUMNS.'.desc=%s
						WHERE 	(permalink=%s) AND
								(language=%s)',
						array($title, $content,
						$keywords, $permalink,
						$type,
						$box_style, $redirect,
						$box_zone, $orbicon_x->ptr,
						$tpl, $permalink_ascii,
						$_POST['group'], $_POST['parent'],
						$orbicon_x->urlnormalize($_POST['parent']), $_POST['menu_name'],
						$_POST['desc'],
						$_GET['edit'], $orbicon_x->ptr));

			if(empty($_POST['parent']))	{
				sql_update('
						UPDATE 	'.TABLE_COLUMNS.'
						SET 	parent=NULL, parent_ascii=NULL
						WHERE 	(permalink=%s) AND
								(language=%s)',
						array($_GET['edit'], $orbicon_x->ptr));
			}

			// update children columns
			sql_update('UPDATE 	'.TABLE_COLUMNS.'
						SET 	parent=%s, parent_ascii=%s
						WHERE 	(parent=%s) AND
								(language=%s)',
						array($permalink, $permalink_ascii,
						$_GET['edit'],
						$orbicon_x->ptr));

			if($orbx_mod->validate_module('rss')) {
				include_once DOC_ROOT . '/orbicon/modules/rss/class.rss.php';
				$rss = new RSS_Manager;

				// build new rss and sitemaps
				// rss 2
				if($_SESSION['site_settings']['rss_type'] == 'rss2') {
					$rss -> build_news_rss();
					$rss -> build_news_rss(true);
				}
				// rdf rss
				else if($_SESSION['site_settings']['rss_type'] == 'rdf') {
					$rss->build_news_rdf();
					$rss->build_news_rdf(true);
				}
				unset($rss);
			}

			// sitemaps
			require_once DOC_ROOT . '/orbicon/class/inc.seositemap.php';
			generate_sitemaps();

			redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/columns&edit=' . urlencode($permalink));
		}
	}

?>