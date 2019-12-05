<?php
/**
 * Class for frontend news
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package OrbiconMOD
 * @version 1.20
 * @link http://
 * @license http://
 * @since 2006-07-01
 */

class News
{
	/**
	 * Return all live news entries for news category $category
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $category
	 * @param int $rows
	 * @param int $columns
	 * @return string
	 */
	function get_news($category, $rows = null, $columns = null)
	{
		if(($rows === null) && ($columns === null)) {
			$max_news_box = $loop = intval($_SESSION['site_settings']['news_grid_rows']);
			$max_news_box_previews = intval($_SESSION['site_settings']['news_grid_columns']);
			$max_news_box_previews = ($max_news_box_previews < 1) ? 1 : $max_news_box_previews;
			$max_news_box *= $max_news_box_previews;
			$css_width = intval(100 / $max_news_box_previews);
		}
		else {
			$max_news_box = $loop = intval($rows);
			$max_news_box_previews = intval($columns);
			$max_news_box_previews = ($max_news_box_previews < 1) ? 1 : $max_news_box_previews;
			$max_news_box *= $max_news_box_previews;
			$css_width = rounddown(100 / $max_news_box_previews);
		}

		/*$user_img_xy = intval($_SESSION['site_settings']['news_img_default_xy']);
		$img_xy = ($user_img_xy  > 0) ? $user_img_xy : 'auto';
		$img_xy = ($img_xy == 'auto') ? $img_xy : $img_xy.'px';*/
		$i = 0;
		$b = 1;
		$n = 1;

		global $dbc, $orbicon_x;

		$r_count = $dbc->_db->query(sprintf('	SELECT 	COUNT(id)
												FROM 	'.TABLE_NEWS.'
												WHERE 	(date <= UNIX_TIMESTAMP()) AND
														(category = %s) AND
														(live = 1) AND
														(language = %s)', $dbc->_db->quote($category), $dbc->_db->quote($orbicon_x->ptr)));
		$a_count = $dbc->_db->fetch_array($r_count);
		$max_news_box = ($max_news_box > $a_count[0]) ? $a_count[0] : $max_news_box;

		if($max_news_box == 1) {
			$r = $dbc->_db->query(sprintf('	SELECT 		*
											FROM 		'.TABLE_NEWS.'
											WHERE 		(date <= UNIX_TIMESTAMP()) AND
														(category = %s) AND
														(live = 1) AND
														(language = %s)
											ORDER BY 	date DESC
											LIMIT 		1', $dbc->_db->quote($category), $dbc->_db->quote($orbicon_x->ptr)));
		}
		else {
			$start_position = (scan_templates('<!>LAST_NEWS_ENTRY') < 1) ? 0 : 1;
			$r = $dbc->_db->query(sprintf('	SELECT 		*
											FROM 		'.TABLE_NEWS.'
											WHERE 		(date <= UNIX_TIMESTAMP()) AND
														(category = %s) AND
														(live = 1) AND
														(language = %s)
											ORDER BY 	date DESC
											LIMIT 		'.$start_position.','.$max_news_box, $dbc->_db->quote($category), $dbc->_db->quote($orbicon_x->ptr)));
		}
		$a = $dbc->_db->fetch_assoc($r);

		while($i < $loop) {
			//$news .= '<li>';

			while($a) {
				$a['permalink'] = $orbicon_x->urlnormalize($a['permalink']);
				$a['title'] = str_replace('"', '&quot;', $a['title']);
				$r_intro = $dbc->_db->query(sprintf('	SELECT 	content
														FROM 	'.MAGISTER_CONTENTS.'
														WHERE 	(id = %s) AND
																(language = %s)
														LIMIT 	1', $dbc->_db->quote($a['intro']), $dbc->_db->quote($orbicon_x->ptr)));
				$intro = $dbc->_db->fetch_assoc($r_intro);

				$intro = $intro['content'];

				$url = (empty($a['content'])) ? '' : url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.urlencode($a['permalink']), ORBX_SITE_URL.'/'.$orbicon_x->ptr.'/'.urlencode($a['permalink']));
				$url = (empty($a['redirect'])) ? $url : $a['redirect'];

				if(get_extension($a['image']) == 'swf') {
					include_once DOC_ROOT . '/orbicon/class/inc.mmedia.php';
					$img = swf_object(DOC_ROOT . '/site/venus/' . $a['image'], ORBX_SITE_URL . '/site/venus/' . $a['image'], array('url' => ORBX_SITE_URL . '/orbicon/modules/news/flash.news.php?nid='.$a['id'].'|' . base64_encode($url)));
				}
				else {
					$img = (empty($a['image'])) ? null : (empty($url)) ? '<img class="img" src="'.ORBX_SITE_URL.'/site/venus/'.$a['image'].'" title="'.$a['title'].'" alt="'.$a['image'].'" />' : '<p><a class="news_image" href="'.$url.'"><img src="'.ORBX_SITE_URL.'/site/venus/'.$a['image'].'" title="'.$a['title'].'" alt="'.$a['image'].'" class="img" /></a></p>';
				}
				// fix this later, quick patch
				$img = (empty($a['image'])) ? null : $img;

				$title_a = (empty($url)) ? $a['title'] : '<a href="'.$url.'">'.$a['title'].'</a>';
				$more_a = (empty($url)) ? '' : '<a class="orbx_news_tools_more" href="'.$url.'">'._L('read_more').'</a>';

				$print_a = (Settings::get_news_property_set($_SESSION['site_settings']['news_properties'], ORBX_CONTENT_PROP_PRINT_LINK)) ? '<a class="orbx_news_tools_print" href="'.url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.urlencode($a['permalink'].'/html'), ORBX_SITE_URL.'/'.$orbicon_x->ptr.'/'.urlencode($a['permalink'].'/html')).'">'._L('print').'</a>' : '';

				$news .= '<li>
                    <div>' . $img . '</div>
                    <p>
	                    <span class="date">' . $a['date_text'].'</span><br />
	                    ' . $title_a . '
                    </p>
           </li>';

				$n ++;
				$a = $dbc->_db->fetch_assoc($r);

				if($b == $max_news_box_previews) {
					$b = 1;
					break;
				}
				$b ++;
			}

			//$news .= '</li>';
			$i++;
		}

		return "<ul>$news</ul>";
	}

	/**
	 * Return all news categories and their live entries
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return string
	 */
	function get_news_categories()
	{
		global $dbc, $orbicon_x;

		$max_news_box = $loop = intval($_SESSION['site_settings']['news_category_grid_rows']);
		$max_news_box = ($max_news_box < 1) ? 1 : $max_news_box;
		$max_news_box_previews = intval($_SESSION['site_settings']['news_category_grid_columns']);
		$max_news_box_previews = ($max_news_box_previews < 1) ? 1 : $max_news_box_previews;
		$max_news_box *= $max_news_box_previews;
		$css_width = intval(100 / $max_news_box_previews);
		$i = 0;
		$b = 1;

		if($max_news_box == 1) {
			$r = $dbc->_db->query(sprintf('		SELECT 		*
												FROM 		'.TABLE_NEWS_CAT.'
												WHERE 		(language = %s)
												ORDER BY 	sort ASC
												LIMIT 		1',
												$dbc->_db->quote($orbicon_x->ptr)));
		}
		else {
			$r = $dbc->_db->query(sprintf('		SELECT 		*
												FROM 		'.TABLE_NEWS_CAT.'
												WHERE 		(language = %s)
												ORDER BY 	sort ASC
												LIMIT 		0,' . $max_news_box,
												$dbc->_db->quote($orbicon_x->ptr)));
		}

		$a = $dbc->_db->fetch_assoc($r);

		while($i < $loop) {
			$news .= '<div class="news_cat_box" id="orbx_news_cat_' . $i . '">';

			while($a) {
				$title = '<h2 class="orbx_news_category_title">'.$a['title'].'</h2>';
				$news .= '<div class="news_cat_box_preview" style="width:'.$css_width.'%;">'.$title.$this->get_news($a['permalink'], $a['scheme_rows'], $a['scheme_columns']).'</div>';
				$a = $dbc->_db->fetch_assoc($r);

				if($b == $max_news_box_previews) {
					$b = 1;
					break;
				}
				$b ++;
			}

			$news .= '</div>';
			$i++;
		}

		return $news;
	}

	/**
	 * Return all archived news entries sorted by their categories
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return string
	 */
	function print_news_archive()
	{
		global $dbc, $orbicon_x;

		if(($_GET[$orbicon_x->ptr] != 'mod.news-archive') && (scan_templates('<!>NWS_ARCHIVE_SUMMARY') < 1) && ($_SESSION['site_settings']['news_archive_summary_items'] > 0)) {
			return false;
		}

		$max_news_box = intval($_SESSION['site_settings']['news_category_grid_rows']);
		$max_news_box = ($max_news_box < 1) ? 1 : $max_news_box;
		$max_news_box_previews = intval($_SESSION['site_settings']['news_category_grid_columns']);
		$max_news_box_previews = ($max_news_box_previews < 1) ? 1 : $max_news_box_previews;
		$max_news_box *= $max_news_box_previews;

		if($max_news_box == 1) {
			$r = $dbc->_db->query(sprintf('		SELECT 		*
												FROM 		'.TABLE_NEWS_CAT.'
												WHERE 		(language = %s)
												ORDER BY 	sort ASC
												LIMIT 		1',
												$dbc->_db->quote($orbicon_x->ptr)));
		}
		else {
			$r = $dbc->_db->query(sprintf('		SELECT 		*
												FROM 		'.TABLE_NEWS_CAT.'
												WHERE 		(language = %s)
												ORDER BY 	sort ASC
												LIMIT 		0,' . $max_news_box,
												$dbc->_db->quote($orbicon_x->ptr)));
		}

		$a = $dbc->_db->fetch_assoc($r);

		$i = 1;
		while($a) {
			$title = "<br /><h2 class=\"news_arch_cat_title\">{$a['title']}</h2>";
			$list = $this->print_news_archive_category($a['permalink'], $a['scheme_rows'], $a['scheme_columns']);
			$news .= "<div id=\"news_archive_$i\">$title $list</div>";
			$a = $dbc->_db->fetch_assoc($r);
			$i ++;
		}

		return $news;
	}

	/**
	 * Return all archived news entries for $category
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $category
	 * @param int $rows
	 * @param int $columns
	 * @return string
	 */
	function print_news_archive_category($category, $rows = null, $columns = null)
	{
		if(($rows === null) && ($columns === null)) {
			$max_news_box = intval($_SESSION['site_settings']['news_grid_rows']);
			$max_news_box_previews = intval($_SESSION['site_settings']['news_grid_columns']);
			$max_news_box_previews = ($max_news_box_previews < 1) ? 1 : $max_news_box_previews;
			$max_news_box *= $max_news_box_previews;
		}
		else {
			$max_news_box = intval($rows);
			$max_news_box_previews = intval($columns);
			$max_news_box_previews = ($max_news_box_previews < 1) ? 1 : $max_news_box_previews;
			$max_news_box *= $max_news_box_previews;
		}

		global $dbc, $orbicon_x;

		if(!isset($_GET['p'])) {
			$unset_below = true;
		}

		// declare these
		$_GET['pp'] = isset($_GET['pp']) ? intval($_GET['pp']) : 15;
		$_GET['p'] = isset($_GET['p']) ? intval($_GET['p']) : 1;

		$user_img_xy = intval($_SESSION['site_settings']['news_img_default_xy']);
		$img_xy = ($user_img_xy  > 0) ? $user_img_xy : 'auto';
		$_sql_news_cat = ' AND (category = ' . $dbc->_db->quote($category) . ')';

		// should we count latest news entry?
		$start_position = (scan_templates('<!>LAST_NEWS_ENTRY') < 1) ? 0 : 1;

		// are we displaying news at all and if we do, how much?
		$start_position = (scan_templates('<!>NEWS') < 1) ? $start_position : ($start_position + $max_news_box);

		$upper = ($_GET['p'] - 1) * $_GET['pp'];
		$upper = ($_GET['p'] == 1) ? $upper : $upper + $start_position;
		$start = ($_GET['p'] == 1) ? $start_position : $upper;

		$q = sprintf('		SELECT 		*
							FROM 		'.TABLE_NEWS.'
							WHERE 		(live = 1) AND
										(language = %s) '.$_sql_news_cat.'
							ORDER BY 	date DESC
							LIMIT 		%s,%s', $dbc->_db->quote($orbicon_x->ptr), $dbc->_db->quote($start), $dbc->_db->quote($_GET['pp']));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$a['permalink'] = $orbicon_x->urlnormalize($a['permalink']);
			// build url or use redirect value if set
			$url = (empty($a['redirect'])) ? url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$a['permalink'], ORBX_SITE_URL.'/'.$orbicon_x->ptr.'/'.$a['permalink']) : $a['redirect'];

			// determine what to do with image
			if(get_extension($a['image']) == 'swf') {
					include_once DOC_ROOT . '/orbicon/class/inc.mmedia.php';
					$img = swf_object(DOC_ROOT . '/site/venus/' . $a['image'], ORBX_SITE_URL . '/site/venus/' . $a['image'], array('url' => ORBX_SITE_URL . '/orbicon/modules/news/flash.news.php?nid='.$a['id'].'|' . base64_encode($url)));
			}
			else {
				$img = (empty($a['image'])) ? '' : (empty($a['content'])) ? '<img src="'.ORBX_SITE_URL.'/site/venus/'.$a['image'].'" style="width:'.$img_xy.'px;height:'.$img_xy.'px;" title="'.$a['title'].'" alt="'.$a['image'].'" />' : '<p><a href="'.$url.'"><img src="'.ORBX_SITE_URL.'/site/venus/'.$a['image'].'" style="width:'.$img_xy.'px;height:'.$img_xy.'px;" title="'.$a['title'].'" alt="'.$a['image'].'" /></a></p>';
			}
			// fix this later, quick patch
			$img = (empty($a['image'])) ? '' : $img;

			$title_a = '<a href="' . $url . '">' . $a['title'] . '</a>';

			$news_archive .= '<div class="orbx_news_archive_item">
				<p class="orbx_news_archive_item_date">' . $a['date_text'].'</p>
				' . $img . '
				<p class="orbx_news_archive_title">' . $title_a . '</p>
			</div>';

			$a = $dbc->_db->fetch_assoc($r);
		}

		if(empty($news_archive)) {
			$news_archive = '<span class="no_archived_news">' . _L('no_archived_news') . '</span>';
		}
		else {

			// identify if we are using this for news archive module or for archived summary module
			$limit = ($_GET[$orbicon_x->ptr] == 'mod.news-archive') ? 0 : intval($_SESSION['site_settings']['news_archive_summary_items']);

			if($limit < 1) {

				$r_count = $dbc->_db->query(sprintf('	SELECT 	COUNT(id)
														FROM 	'.TABLE_NEWS.'
														WHERE 	(live = 1) AND
																(language = %s)'
																. $_sql_news_cat,
														$dbc->_db->quote($orbicon_x->ptr)));
				$a_count = $dbc->_db->fetch_array($r_count);
				// needed for pagination as well
				$limit = $a_count[0];
				// don't need this
				unset($r_count, $a_count);
			}

			// add pagination
			require_once DOC_ROOT . '/orbicon/class/class.pagination.php';

			$pagination = new Pagination('p', 'pp');

			$pagination->total = ($limit - $start_position);
			$pagination->split_pages();
			$news_archive .= $pagination->construct_page_nav(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.news-archive');
		}

		// this invalidates caching, clean up from memory
		if($unset_below) {
			unset($_GET['p'], $_GET['pp']);
		}

		return $news_archive;
	}

	function flash_news()
	{
		ignore_user_abort(true);
		$nid = $_REQUEST['nid'];

		// flash banner
		if(strpos($nid, '|') !== false) {
			// split permalink and target URL
			list($id, $target) = explode('|', $nid);
			// set URL
			$target = base64_decode($target);
		}

		global $dbc;

		$q = sprintf('	UPDATE 	'.TABLE_NEWS.'
						SET 	views = views + 1
						WHERE 	(id = %s)',
							$dbc->_db->quote($id));
		$dbc->_db->query($q);

		ignore_user_abort(false);

		if(!empty($target)) {
			redirect($target);
		}

		return 1;
	}
}

?>