<?php

	/**
	 * Return all live news entries for news category $category
	 *
	 * @param string $category
	 * @param int $rows
	 * @param int $columns
	 * @return string
	 */
	function get_news_index($category, $min, $max)
	{
		$user_img_xy = intval($_SESSION['site_settings']['news_img_default_xy']);
		$img_xy = ($user_img_xy  > 0) ? $user_img_xy : 'auto';

		global $dbc, $orbicon_x, $newsindex_rows;

		$r_count = $dbc->_db->query(sprintf('
										SELECT 		COUNT(id)
										FROM 		'.TABLE_NEWS.'
										WHERE 		(category = %s) AND
													(live = 1) AND
													(language = %s) AND
													(date >= %s) AND
													(date <= %s)
										ORDER BY 	date DESC',
		$dbc->_db->quote($category), $dbc->_db->quote($orbicon_x->ptr), $dbc->_db->quote($min), $dbc->_db->quote($max)));

		$a_count = $dbc->_db->fetch_array($r_count);
		$newsindex_rows[] = $a_count[0];

		$r = $dbc->_db->query(sprintf('	SELECT 		*
										FROM 		'.TABLE_NEWS.'
										WHERE 		(category = %s) AND
													(live = 1) AND
													(language = %s) AND
													(date >= %s) AND
													(date <= %s)
										ORDER BY 	date DESC
										LIMIT		%s, %s',
		$dbc->_db->quote($category), $dbc->_db->quote($orbicon_x->ptr), $dbc->_db->quote($min), $dbc->_db->quote($max), $dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']), $dbc->_db->quote($_GET['pp'])));

		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$r_intro = $dbc->_db->query(sprintf('	SELECT 	content
													FROM 	'.MAGISTER_CONTENTS.'
													WHERE 	(id = %s) AND
															(language = %s)
													LIMIT 	1', $dbc->_db->quote($a['intro']), $dbc->_db->quote($orbicon_x->ptr)));
			$intro = $dbc->_db->fetch_assoc($r_intro);

			$intro = $intro['content'];

			$url = (empty($a['content'])) ? '' : url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$a['permalink'], ORBX_SITE_URL.'/'.$orbicon_x->ptr.'/'.$a['permalink']);
			$url = (empty($a['redirect'])) ? $url : $a['redirect'];

			if(get_extension($a['image']) == 'swf') {
					include_once DOC_ROOT . '/orbicon/class/inc.mmedia.php';
					$img = swf_object(DOC_ROOT . '/site/venus/' . $a['image'], ORBX_SITE_URL . '/site/venus/' . $a['image']);
			}
			else {
				$img = (empty($a['image'])) ? null : (empty($url)) ? '<img src="'.ORBX_SITE_URL.'/site/venus/'.$a['image'].'" style="width:'.$img_xy.'px;height:'.$img_xy.'px;" title="'.$a['title'].'" alt="'.$a['image'].'" />' : '<p><a class="news_image" href="'.$url.'"><img src="'.ORBX_SITE_URL.'/site/venus/'.$a['image'].'" style="width:'.$img_xy.'px;height:'.$img_xy.'px;" title="'.$a['title'].'" alt="'.$a['image'].'" /></a></p>';
			}

			// fix this later, quick patch
			$img = (empty($a['image'])) ? null : $img;

			$title_a = (empty($url)) ? $a['title'] : '<a href="'.$url.'">'.$a['title'].'</a>';
			$more_a = (empty($url)) ? '' : '<a class="news_index_tool_more" href="'.$url.'">'._L('read_more').'</a>';

			$print_a = (Settings::get_news_property_set($_SESSION['site_settings']['news_properties'], ORBX_CONTENT_PROP_PRINT_LINK)) ? '<a class="news_index_tool_print" href="' . url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$a['permalink'].'/html', ORBX_SITE_URL.'/'.$orbicon_x->ptr.'/'.$a['permalink'].'/html').'">' . _L('print') . '</a>' : '';

			$news .= '<div class="news_index_box">
                ' . $img . '
				<h3 class="news_index_title">' . $title_a . '</h3>
				<p class="news_index_date">'.$a['date_text'].'</p>
                <p class="news_index_leadtxt">' . $intro . '</p>
				<p class="news_index_tools">' . $more_a . ' ' . $print_a . '</p>
            </div>';
			$a = $dbc->_db->fetch_assoc($r);
		}

		return $news;
	}

	/**
	 * Return all news categories and their live entries
	 *
	 * @return string
	 */
	function get_news_index_categories($year)
	{
		global $dbc, $orbicon_x;

		// get min / max range
		$min_timestamp = mktime(0, 0, 0, 1, 1, $year);
		$max_timestamp = mktime(0, 0, 0, 12, 31, $year);
		$i = 1;

		$r = $dbc->_db->query(sprintf('		SELECT 		*
											FROM 		'.TABLE_NEWS_CAT.'
											WHERE 		(language = %s)
											ORDER BY 	sort ASC',
											$dbc->_db->quote($orbicon_x->ptr)));

		$a = $dbc->_db->fetch_assoc($r);

		$n = 0;
		$m = 0;

		while($a) {

			$n ++;

			$news_list = get_news_index($a['permalink'], $min_timestamp, $max_timestamp);

			if(!$news_list) {
				$m --;
			}

			$title = '<h2 class="orbx_news_index_hd">' . $a['title'] . '</h2>';
			$news .= '<div class="orbx_news_index_bd" id="news_index_' . $i . '">' . $title . $news_list . '</div>';
			$a = $dbc->_db->fetch_assoc($r);
			$i ++;
		}

		if(($n + $m) == 0) {
			return get_news_index_categories($year - 1);
		}

		return $news;
	}

	function generate_archived_list()
	{
		global $dbc, $orbicon_x;

		$r = $dbc->_db->query(sprintf('	SELECT 		YEAR(FROM_UNIXTIME(date)) AS date_c
										FROM 		'.TABLE_NEWS.'
										WHERE 		(live = 1) AND
													(language = %s)
										GROUP BY 	YEAR(FROM_UNIXTIME(date))
										ORDER BY	date DESC',
		$dbc->_db->quote($orbicon_x->ptr)));

		$a = $dbc->_db->fetch_assoc($r);

		$menu = '';

		while($a) {
			$selected = ($_REQUEST['year'] == $a['date_c']) ? 'selected="selected"' : '';
			$menu .= '<option ' . $selected . ' value="' . $a['date_c'] . '">' . $a['date_c'] . '</option>';
			$a = $dbc->_db->fetch_assoc($r);
		}

		$menu = '<fieldset id="news_index_archivefield"><legend>'._L('archive').'</legend><table id="news_index_newsarchivebox"><tr><td><label for="year">'._L('lookup_archive') .
		'</label></td><td><form method="get" action=""><input name="'.$orbicon_x->ptr.'" value="mod.news-index" type="hidden" /><select name="year" id="year">' .
		$menu .
		'</select> <input id="btn_news_year" type="submit" value="Traži" /></form></td></tr></table></fieldset>';

		return $menu;
	}
?>