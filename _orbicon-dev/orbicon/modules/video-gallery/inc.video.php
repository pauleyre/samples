<?php
/**
 * Video gallery include
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @version 1.1
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-10-16
 */

	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	function get_first_video_date()
	{
		global $dbc, $video_gallery;

		$q = sprintf('	SELECT 		uploader_time
						FROM		'.MERCURY_FILES.'
						WHERE		(category = %s)
						ORDER BY 	uploader_time ASC
						LIMIT 		1',
						$dbc->_db->quote($video_gallery));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		return $a['uploader_time'];
	}

	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	function get_last_video_date()
	{
		global $dbc, $video_gallery;

		$q = sprintf('	SELECT 		uploader_time
						FROM		'.MERCURY_FILES.'
						WHERE		(category = %s)
						ORDER BY 	uploader_time DESC
						LIMIT 		1',
						$dbc->_db->quote($video_gallery));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		return $a['uploader_time'];
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $date
	 * @return unknown
	 */
	function print_video_list($date)
	{
		$year = date('Y', $date);
		$month = date('m', $date);

		$from = mktime(0, 0, 0, $month, 1, $year);
		$to = mktime(0, 0, 0, ($month + 1), 1, $year);

		global $dbc, $video_gallery;
		$q = sprintf('	SELECT 		*
						FROM		'.MERCURY_FILES.'
						WHERE		(category = %s) AND
									(uploader_time >= %s) AND
									(uploader_time <= %s)
						ORDER BY 	uploader_time DESC',
						$dbc->_db->quote($video_gallery), $dbc->_db->quote($from), $dbc->_db->quote($to));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		if(empty($a)) {
			return null;
		}

		$table = '<ul class="video_list">';

		$i = 0;

		while($a) {

			$title = array();
			$title[] = date('d.m.Y', $a['uploader_time']);
			$title[] = ($a['description']) ? $a['description'] : $a['content'];
			$title[] = $a['uploader'];
			$title = array_remove_empty($title);
			$title = implode(' / ', $title);

			$style = (($i % 2) == 0) ? ' style="background:#eeeeee;"' : '';
			$table .= '<li'.$style.'><a title="' . $title . '" href="javascript:void(null);" onclick="javascript:update_flash_vid(\''.$a['content'].'\');">' . $title . '</a></li>';
			$a = $dbc->_db->fetch_assoc($r);
			$i ++;
		}

		$table .= '</ul>';

		return $table;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $date
	 * @return unknown
	 */
	function print_category_video_list()
	{
		global $dbc, $video_gallery;
		$q = sprintf('	SELECT 		*
						FROM		'.MERCURY_FILES.'
						WHERE		(category = %s)
						ORDER BY 	uploader_time DESC',
						$dbc->_db->quote($video_gallery));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		if(empty($a)) {
			return null;
		}

		$table = '<ul class="video_list">';

		$i = 0;

		while($a) {

			/*
			$title = array();
			$title[] = date('d.m.Y', $a['uploader_time']);
			$title[] = ($a['description']) ? $a['description'] : $a['content'];
			$title[] = $a['uploader'];
			$title = array_remove_empty($title);
			$title = implode(' / ', $title);
			*/
			$title = ($a['description']) ? $a['description'] : $a['content'];

			$style = (($i % 2) == 0) ? ' style="background:#eeeeee;"' : '';
			$table .= '<li'.$style.'><a title="' . $title . '" href="javascript:void(null);" onclick="javascript:update_flash_vid(\''.$a['content'].'\');">' . $title . '</a></li>';
			$a = $dbc->_db->fetch_assoc($r);
			$i ++;
		}

		$table .= '</ul>';

		return $table;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $video
	 */
	function print_flash_video_player($video)
	{
		// module include
		require_once DOC_ROOT.'/orbicon/class/class.module.php';

		global $orbx_mod;
		$mod_params = $orbx_mod->load_info('video-gallery');

		// mm include
		require_once DOC_ROOT.'/orbicon/class/inc.mmedia.php';

		list($w, $h) = get_video_size($video);
		$aplay = ($_SESSION['site_settings']['flv_player_autoplay']) ? 'true' : 'false';

		if(!is_file(DOC_ROOT . '/' . $mod_params['video']['player'])) {
			$mod_params['video']['player'] = 'orbicon/modules/video-gallery/gfx/flvplayer.swf';
		}

		echo '<object
		classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
		codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0"
		height="'.$h.'"
		width="'.$w.'">
			<embed src="'.ORBX_SITE_URL.'/'.$mod_params['video']['player'].'?file='.ORBX_SITE_URL . '/site/mercury/' . $video.'&amp;autostart='.$aplay.'"
			menu="false"
			allowfullscreen="true"
			quality="high"
			allowscriptaccess="sameDomain"
			type="application/x-shockwave-flash"
			pluginspage="http://www.adobe.com/go/getflash"
			height="'.$h.'"
			width="'.$w.'"
			wmode="transparent" />
			<param name="movie" value="'.ORBX_SITE_URL.'/'.$mod_params['video']['player'].'?file='.ORBX_SITE_URL . '/site/mercury/' . $video.'&amp;autostart='.$aplay.'" />
			<param name="quality" value="high" />
			<param name="menu" value="0" />
			<param name="wmode" value="transparent" />
			<param name="allowfullscreen" value="true" />
			<param name="allowscriptaccess" value="sameDomain" />
</object>';
	}

	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	function get_last_available_video()
	{
		global $dbc, $video_gallery;

		$q = sprintf('	SELECT 		content
						FROM		'.MERCURY_FILES.'
						WHERE		(category = %s)
						ORDER BY 	uploader_time DESC
						LIMIT 		1',
						$dbc->_db->quote($video_gallery));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		return ORBX_SITE_URL . '/site/mercury/' . $a['content'];
	}

	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	function get_stat_months()
	{
		$months = array();
		// get stats
		global $dbc, $video_gallery;
		$q = sprintf('	SELECT 		uploader_time
						FROM 		'.MERCURY_FILES.'
						WHERE		(category = %s)
						GROUP BY 	uploader_time', $dbc->_db->quote($video_gallery));
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			// make keys as months so we avoid duplicates
			$key = date('m', $a['uploader_time']);
			$months[$key] = $a['uploader_time'];
			$a = $dbc->_db->fetch_assoc($r);
		}

		sort($months);

		return array_unique($months);
	}

	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	function print_video_months()
	{
		$months = get_stat_months();
		$list = '';

		foreach($months as $month) {
			$list .= '<li><a href="javascript:void(null);" onclick="javascript:update_table_vid(\''.$month.'\');">'.date('m. Y.', $month).'</a></li>';
		}

		return '<fieldset class="video_fieldset"><legend>'._L('monthly_preview').'</legend><div class="video_scroll_container"><ul class="video_list">'.$list.'</ul></div></fieldset>';
	}

?>