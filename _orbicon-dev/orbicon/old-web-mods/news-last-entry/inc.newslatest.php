<?php

/**
	 * Return latest news entry, regardless of category (unless set via news options)
	 *
	 * @return string		Formatted HTML
	 */
	function get_last_news_entry()
	{
		global $dbc, $orbicon_x;

		$user_img_xy = intval($_SESSION['site_settings']['news_img_default_xy']);
		$img_xy = ($user_img_xy  > 0) ? $user_img_xy : 'auto';
		$cat_sql = ($_SESSION['site_settings']['show_last_news_from'] != '') ? sprintf(' AND (category = %s)', $dbc->_db->quote($_SESSION['site_settings']['show_last_news_from'])) : '';

		$r = $dbc->_db->query(sprintf('		SELECT 		*
											FROM 		'.TABLE_NEWS.'
											WHERE 		(date <= UNIX_TIMESTAMP()) AND
														(live = 1) AND
														(language = %s)
														'.$cat_sql.'
											ORDER BY 	date DESC
											LIMIT 		1', $dbc->_db->quote($orbicon_x->ptr)));
		$a = $dbc->_db->fetch_assoc($r);
		$a['permalink'] = $orbicon_x->urlnormalize($a['permalink']);
		
		$r_intro = $dbc->_db->query(sprintf('	SELECT 	content
												FROM 	'.MAGISTER_CONTENTS.'
												WHERE 	(id = %s) AND
														(language = %s)
												LIMIT 	1', $dbc->_db->quote($a['intro']), $dbc->_db->quote($orbicon_x->ptr)));
		$intro = $dbc->_db->fetch_assoc($r_intro);

		$intro = $intro['content'];

		$url = (empty($a['content'])) ? '' : url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$a['permalink'], ORBX_SITE_URL.'/'.$orbicon_x->ptr.'/'.$a['permalink']);
		$url = (empty($a['redirect'])) ? $url : $a['redirect'];

		// determine what to do with image
		if(get_extension($a['image']) == 'swf') {
			include_once DOC_ROOT . '/orbicon/class/inc.mmedia.php';
			$img = swf_object(DOC_ROOT . '/site/venus/' . $a['image'], ORBX_SITE_URL . '/site/venus/' . $a['image']);
		}
		else {
			$img = (empty($a['image'])) ? null : (empty($url)) ? '<img src="'.ORBX_SITE_URL.'/site/venus/'.$a['image'].'" style="width:'.$img_xy.'px;height:'.$img_xy.'px;" title="'.$a['title'].'" alt="'.$a['image'].'" />' : '<p><a class="news_image" href="'.$url.'"><img src="'.ORBX_SITE_URL.'/site/venus/'.$a['image'].'" style="width:'.$img_xy.'px;height:'.$img_xy.'px;" title="'.$a['title'].'" alt="'.$a['image'].'" /></a></p>';
		}
		/**
		 * @todo fix this later, quick patch
		 */
		$img = (empty($a['image'])) ? null : $img;

		$title_a = (empty($url)) ? $a['title'] : '<a href="'.$url.'">'.$a['title'].'</a>';
		$more_a = (empty($url)) ? '' : '<a href="'.$url.'">'._L('read_more').'</a>';

		$print_a = (Settings::get_news_property_set($_SESSION['site_settings']['news_properties'], ORBX_CONTENT_PROP_PRINT_LINK)) ? '<a href="'.url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$a['permalink'].'/html', ORBX_SITE_URL.'/'.$orbicon_x->ptr.'/'.$a['permalink'].'/html').'">'._L('print').'</a>' : '';

		$news = $img .
		'<h3 class="orbx_news_title">'.$title_a.'</h3>
		<p class="news_date">'.date($_SESSION['site_settings']['date_format'], $a['date']).'</p>
		<p class="orbx_news_lead">'.$intro.'</p>
		<p class="news_tools">'.$more_a.' ' . $print_a . '</p>';

		return $news;
	}

?>