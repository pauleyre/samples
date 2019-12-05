<?php
/*-----------------------------------------------------------------------*
	Project........:	Orbicon X framework 2
	File...........:
	Version........:	1.0 (22/10/2006)
	Author.........:	Pavle Gardijan (pavle.gardijan@orbitum.net) - Orbitum d.o.o.
	Copyright......:	(c) 2006 Orbitum internet communications d.o.o. (www.orbitum.net)
	Created........:	01/07/2006
	Notes..........:
	Modified.......:
*-----------------------------------------------------------------------*/

require_once DOC_ROOT . '/orbicon/modules/news/class.news.php';

class News_Admin extends News
{
	// update existing or add new news item
	function save_news()
	{
		if(isset($_POST['save_news'])) {
			$title = trim($_POST['news_title']);

			if($title == '') {
				trigger_error('save_news() expects parameter 1 to be non-empty', E_USER_WARNING);
				return false;
			}

			$intro = $_POST['intro_text'];
			$content = $_POST['content_text'];
			$live_date = $_POST['live_date'];
			$image = $_POST['news_img'];
			$category = $_POST['news_category'];
			$rss_push = $_POST['rss_push'];
			$redirect = $_POST['news_redirect'];

			$permalink = get_permalink($title);
			$title = utf8_html_entities($title);

			$content = trim(stripslashes($content));

			global $dbc, $orbicon_x, $orbx_mod;
			
			$permalink_ascii = $orbicon_x->urlnormalize($permalink, true);
			
			$r = $dbc->_db->query(sprintf('		SELECT 		content
												FROM 		'.MAGISTER_CONTENTS.'
												WHERE 		(live = 1) AND
															(hidden = 0) AND
															(question_permalink = %s) AND
															(language = %s)
												ORDER BY 	uploader_time', $dbc->_db->quote($content), $dbc->_db->quote($orbicon_x->ptr)));
			$a = $dbc->_db->fetch_assoc($r);

			while($a) {
				$keywords .= $a['content'];
				$a = $dbc->_db->fetch_assoc($r);
			}

			$dbc->_db->free_result($r);

			$keywords = keyword_generator(strip_tags($keywords));

			// update
			if(isset($_GET['edit'])) {
				$q = sprintf('	UPDATE 		'.TABLE_NEWS.'
								SET			title=%s, date=%s,
											content=%s, image=%s,
											intro=%s, rss_push=%s,
											keywords=%s, permalink=%s,
											category=%s, live=%s,
											redirect=%s, permalink_ascii=%s
								WHERE 		(permalink=%s) AND
											(language=%s)',
					$dbc->_db->quote($title), $dbc->_db->quote($live_date),
					$dbc->_db->quote($content), $dbc->_db->quote($image),
					$dbc->_db->quote($intro), $dbc->_db->quote($rss_push),
					$dbc->_db->quote($keywords), $dbc->_db->quote($permalink),
					$dbc->_db->quote($category), $dbc->_db->quote($_POST['live']),
					$dbc->_db->quote($redirect), $dbc->_db->quote($permalink_ascii),
					$dbc->_db->quote($_GET['edit']), $dbc->_db->quote($orbicon_x->ptr));
			}
			// add new
			else {

				// check for existing permalink

				$q_c = sprintf('SELECT 	id
								FROM 	'.TABLE_NEWS.'
								WHERE 	(permalink=%s) AND
										(language = %s)
								LIMIT 	1',
								$dbc->_db->quote($permalink),$dbc->_db->quote($orbicon_x->ptr));
				$r_c = $dbc->_db->query($q_c);
				$a_c = $dbc->_db->fetch_assoc($r_c);

				if(!empty($a['id'])) {
					// redirect
					redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/news&edit='.urlencode($permalink));
				}

				$q = sprintf('	INSERT INTO '.TABLE_NEWS.'
									(title, date,
									editor, content,
									image, intro,
									rss_push, keywords,
									permalink, category,
									live, language,
									created, redirect,
									permalink_ascii)
								VALUES
									(%s, %s,
									%s, %s,
									%s, %s,
									%s, %s,
									%s, %s,
									%s, %s,
									UNIX_TIMESTAMP(), %s,
									%s)',
								$dbc->_db->quote($title), $dbc->_db->quote($live_date),
								$dbc->_db->quote($_SESSION['user.a']['id']), $dbc->_db->quote($content),
								$dbc->_db->quote($image), $dbc->_db->quote($intro),
								$dbc->_db->quote($rss_push), $dbc->_db->quote($keywords),
								$dbc->_db->quote($permalink), $dbc->_db->quote($category),
								$dbc->_db->quote($_POST['live']), $dbc->_db->quote($orbicon_x->ptr),
								$dbc->_db->quote($redirect), $dbc->_db->quote($permalink_ascii));
			}

			$dbc->_db->query($q);

			if($orbx_mod->validate_module('rss')) {
				include_once DOC_ROOT.'/orbicon/modules/rss/class.rss.php';
				$rss = new RSS_Manager;

				// build new rss and sitemaps
				// rss 2
				if($_SESSION['site_settings']['rss_type'] == 'rss2') {
					$rss->build_news_rss();
					$rss->build_news_rss(true);
				}
				// rdf rss
				else if($_SESSION['site_settings']['rss_type'] == 'rdf') {
					$rss->build_news_rdf();
					$rss->build_news_rdf(true);
				}
				unset($rss);
			}

			// update yahoo and google sitemaps
			include_once DOC_ROOT.'/orbicon/class/inc.seositemap.php';
			generate_sitemaps();

			// redirect
			redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/news&edit='.urlencode($permalink));
		}
	}

	// load news item
	function load_news()
	{
		if(isset($_GET['edit'])) {
			global $dbc, $orbicon_x;
			$q = sprintf('	SELECT 	*
							FROM 	'.TABLE_NEWS.'
							WHERE 	(permalink=%s) AND
									(language = %s)
							LIMIT 	1',
							$dbc->_db->quote($_GET['edit']),$dbc->_db->quote($orbicon_x->ptr));
			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);

			return $a;
		}
		return null;
	}

	function delete_news()
	{
		if(isset($_GET['delete_news']) && get_is_admin()) {
			global $dbc, $orbicon_x, $orbx_mod;
			$dbc->_db->query(sprintf('	DELETE FROM '.TABLE_NEWS.'
										WHERE (permalink = %s) AND (language = %s)
										LIMIT 1', $dbc->_db->quote($_GET['delete_news']), $dbc->_db->quote($orbicon_x->ptr)));


			if($orbx_mod->validate_module('rss')) {
				include_once DOC_ROOT . '/orbicon/modules/rss/class.rss.php';
				$rss = new RSS_Manager;

				// build new rss and sitemaps
				// rss 2
				if($_SESSION['site_settings']['rss_type'] == 'rss2') {
					$rss->build_news_rss();
					$rss->build_news_rss(true);
				}
				// rdf rss
				else if($_SESSION['site_settings']['rss_type'] == 'rdf') {
					$rss->build_news_rdf();
					$rss->build_news_rdf(true);
				}
				unset($rss);
			}

			// update yahoo and google sitemaps
			include_once DOC_ROOT.'/orbicon/class/inc.seositemap.php';
			generate_sitemaps();

			redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/newsboard');
		}
	}

	function get_news_categories_array()
	{
		global $dbc, $orbicon_x;
		$categories = array();

		$q = sprintf('	SELECT 		*
						FROM 		'.TABLE_NEWS_CAT.'
						WHERE 		(language = %s)
						ORDER BY 	sort', $dbc->_db->quote($orbicon_x->ptr));
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$categories[$a['permalink']] = $a;
			$a = $dbc->_db->fetch_assoc($r);
		}

		return array_remove_empty($categories);
	}

	function delete_news_category()
	{
		if(isset($_GET['delete_news_cat']) && get_is_admin()) {
			global $dbc, $orbicon_x;
			$dbc->_db->query(sprintf('	DELETE
										FROM 		'.TABLE_NEWS_CAT.'
										WHERE 		(permalink = %s) AND
													(language = %s)
										LIMIT 		1', $dbc->_db->quote($_GET['delete_news_cat']), $dbc->_db->quote($orbicon_x->ptr)));

			redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/news-category');
		}
	}

	function save_news_category_order()
	{
		global $dbc, $orbicon_x, $orbx_log;
		$i = 0;

		if(!empty($_REQUEST['news_category_sort_list'])) {
			foreach($_REQUEST['news_category_sort_list'] as $value) {
				$q = sprintf('	UPDATE 	'.TABLE_NEWS_CAT.'
								SET 	sort=%s
								WHERE 	(permalink=%s) AND
										(language = %s)',
										$i, $dbc->_db->quote(str_replace('sort_', '', $value)), $dbc->_db->quote($orbicon_x->ptr));
				$r = $dbc->_db->query($q);

				if($r) {
					$i++;
				}
			}
		}
	}

	function build_news_items()
	{
		if(get_is_valid_ajax_id($_REQUEST['orbx_ajax_id']) === false) {
			return false;
		}

		global $orbicon_x, $dbc;
		if(empty($_REQUEST['news_items_sort_by']) || ($_REQUEST['news_items_sort_by'] == 'date')) {
			$r = $dbc->_db->query(sprintf('	SELECT 		*
											FROM 		'.TABLE_NEWS.'
											WHERE 		(language = %s)
											ORDER BY 	date DESC, permalink',

											$dbc->_db->quote($orbicon_x->ptr)));
		}
		else if($_REQUEST['news_items_sort_by'] == 'alpha') {
			$r = $dbc->_db->query(sprintf('	SELECT 		*
											FROM 		'.TABLE_NEWS.'
											WHERE 		(language = %s)
											ORDER BY 	permalink ASC',
											$dbc->_db->quote($orbicon_x->ptr)));
		}
		else if($_REQUEST['news_items_sort_by'] == 'cat') {
			$r = $dbc->_db->query(sprintf('	SELECT 		*
											FROM 		'.TABLE_NEWS.'
											WHERE 		(language = %s)
											ORDER BY 	category ASC',
											$dbc->_db->quote($orbicon_x->ptr)));
		}
		echo '<table width="100%"><thead>
	<tr style="border-bottom: 1px solid #cccccc;">
		<th>#</th>
		<th>'._L('title').'</th>
		<th>'._L('created_date').'</th>
		<th>'._L('publish_date').'</th>
		<th>'._L('category').'</th>
		<th>'._L('published').'</th>
		<th>'._L('delete').'</th>
	</tr></thead><tbody>';

		$i = 0;

		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$style = (($i % 2) == 0) ? 'style="background:#eeeeee;"' : '';
			$status_img = ($a['live']) ? 'accept.png' : 'cancel.png';

			echo '
			<tr '.$style.'>
				<td>'.($i + 1).'</td>
				<td><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/news&amp;edit='.$a['permalink'].'">'.$a['title'].'</a></td>
				<td>'.date($_SESSION['site_settings']['date_format'] . ' H:i:s', $a['created']).'</td>
				<td>'.date($_SESSION['site_settings']['date_format'] . ' H:i:s', $a['date']).'</td>
				<td>'.$a['category'].'</td>
				<td><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/'.$status_img.'" /></td>
				<td><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/news&amp;delete_news='.$a['permalink'].'" onclick="javascript:return false;" onmousedown="'.delete_popup($a['title']).'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/delete.png" alt="'._L('delete').'" title="'._L('delete').'" /></a></td>
			</tr>';
			$a = $dbc->_db->fetch_assoc($r);
			$i ++;
		}

		echo '</tbody></table>';
		return true;
	}

	/**
	 * @todo this is a poorly written method
	 *
	 * @return unknown
	 */
	function check_news_category()
	{
		global $dbc, $orbicon_x;
		$r = $dbc->_db->query(sprintf('	SELECT 		*
										FROM 		'.TABLE_NEWS_CAT.'
										WHERE 		(language = %s)
										ORDER BY 	permalink', $dbc->_db->quote($orbicon_x->ptr)));
		$a = $dbc->_db->num_rows($r);

		return $a;
	}
}

?>