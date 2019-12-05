<?php
/**
 * Multimedia include
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package System
 * @version 1.3
 * @link http://
 * @license http://
 * @since 2006-07-01
 */

/**
 * Enter description here...
 *
 */
define('VIDEO_DEFAULT_WIDTH', 450);
/**
 * Enter description here...
 *
 */
define('VIDEO_DEFAULT_HEIGHT', 370);

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $file
	 * @return unknown
	 */
	function get_mp3_player($file)
	{
		$player = '<object
			classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
			width="300"
			height="20"
			codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0">
    		<param name="movie" value="'.ORBX_SITE_URL.'/orbicon/gfx/mp3player.swf" />
    		<param name="flashvars" value="file='.ORBX_SITE_URL.'/site/mercury/'.urlencode($file).'&autostart=false" />
    		<param name="wmode" value="transparent" />
    		<embed
				src="'.ORBX_SITE_URL.'/orbicon/gfx/mp3player.swf"
				width="300"
				height="20"
				wmode="transparent"
				flashvars="file='.ORBX_SITE_URL.'/site/mercury/'.urlencode($file).'&autostart=false"
				type="application/x-shockwave-flash"
				pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>';

		return $player;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $file
	 * @return unknown
	 */
	function get_video_player($file)
	{
		$id = sha1(time().$file);
		list($w, $h) = get_video_size($file);

		$player = '<object id="MediaPlayer'.$id.'"
			classid="clsid:6bf52a52-394a-11d3-b153-00c04f79faa6"
			codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab# Version=5,1,52,701"
			standby="Loading Microsoft Windows&reg; Media Player components..."
			type="application/x-oleobject"
			width="'.$h.'"
			height="'.$w.'">
			<param name="fileName" value="'.ORBX_SITE_URL.'/site/mercury/'.urlencode($file).'" />
			<param name="animationatStart" value="true" />
			<param name="transparentatStart" value="true" />
			<param name="autoStart" value="false" />
			<param name="showControls" value="false" />
			<param name="Volume" value="-300" />
			<embed
				type="application/x-mplayer2"
				pluginspage="http://www.microsoft.com/Windows/MediaPlayer/"
				src="'.ORBX_SITE_URL.'/site/mercury/'.urlencode($file).'"
				name="MediaPlayer'.$id.'"
				width="'.$fileinfo['video']['streams'][2]['resolution_x'].'"
				height="'.$fileinfo['video']['streams'][2]['resolution_y'].'"
				autostart="0"
				showcontrols="1"
				volume="-300" />
			</object>';
		return $player;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $file
	 * @return unknown
	 */
	function get_apple_player($file)
	{
		list($w, $h) = get_video_size($file);

		$player = '
		<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/javascript/orbicon.ac_quicktime.js?'.ORBX_BUILD.'"></script>
		<script type="text/javascript"><!-- // --><![CDATA[
	QT_WriteOBJECT_XHTML (
		"'.ORBX_SITE_URL.'/site/mercury/'.$file.'",
		"'.$w.'",
		"'.($h + 15).'",
		"",
		"autoplay",
		"false"
	);
// ]]></script>';
		return $player;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $file
	 * @param unknown_type $width
	 * @param unknown_type $height
	 * @return unknown
	 */
	function get_flv_player($file, $width = null, $height = null)
	{
		if(!$width || !$height) {
			list($flv_player_def_w, $flv_player_def_h) = get_video_size($file);
		}
		else {
			$flv_player_def_w = $width;
			$flv_player_def_h = $height;
		}

		return flv_player(ORBX_SITE_URL.'/site/mercury/'.$file, $flv_player_def_w, $flv_player_def_h, $_SESSION['site_settings']['flv_player_autoplay']);
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $file
	 * @return unknown
	 */
	function get_video_size($file)
	{
		$file = basename($file);

		$info = getimagesize(DOC_ROOT . '/site/mercury/' . $file);

		if($_SESSION['site_settings']['flv_player_def_w'] && $_SESSION['site_settings']['flv_player_def_h']) {
			return array($_SESSION['site_settings']['flv_player_def_w'], $_SESSION['site_settings']['flv_player_def_h']);
		}

		$w = intval($_SESSION['site_settings']['flv_player_def_w']);
		$w = (empty($info[0])) ? $w : $info[0];
		$w = ($w > 0) ? $w : VIDEO_DEFAULT_WIDTH;

		$h = intval($_SESSION['site_settings']['flv_player_def_h']);
		$h = (empty($info[1])) ? $h : $info[1];
		$h = ($h > 0) ? $h : VIDEO_DEFAULT_HEIGHT;

		return array($w, $h);
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $category
	 * @return unknown
	 */
	function print_image_gallery($category)
	{
		global $dbc, $orbicon_x;

		if(!isset($_GET['p'])) {
			$unset_below = true;
		}

		$_GET['pp'] = (isset($_GET['pp'])) ? $_GET['pp'] : 12;
		$_GET['p'] = (isset($_GET['p'])) ? $_GET['p'] : 1;

		require_once DOC_ROOT . '/orbicon/class/class.pagination.php';
		$pagination = new Pagination('p', 'pp');

		$read = $dbc->_db->query(sprintf('	SELECT 		COUNT(id) AS numrows
											FROM 		'.VENUS_IMAGES.'
											WHERE 		(category=%s)', $dbc->_db->quote($category)));
		$row = $dbc->_db->fetch_assoc($read);

		$pagination->total = $row['numrows'];
		$pagination->split_pages();

		$images = '';
		$max_images_box = 4;
		$max_image_box_previews = 3;
		$css_width = intval(60 / $max_image_box_previews);
		$i = (isset($_GET['p'])) ? (1 + ($_GET['pp'] * ($_GET['p'] - 1))) : 1;
		$n = 0;

		switch ($_GET['sort']) {
			case 'views_most': $sort_by = ' views DESC '; break;
			case 'views_least': $sort_by = ' views ASC '; break;
			case 'title_az': $sort_by = ' permalink ASC '; break;
			case 'title_za': $sort_by = ' permalink DESC '; break;
			case 'newest': $sort_by = ' last_modified DESC '; break;
			case 'oldest': $sort_by = ' last_modified ASC '; break;
			default: $sort_by = ' last_modified DESC '; break;
		}

		$r = $dbc->_db->query(sprintf('		SELECT 		*
											FROM 		'.VENUS_IMAGES.'
											WHERE 		(category = %s)
											ORDER BY 	'.$sort_by.'
											LIMIT 		%s, %s',
		$dbc->_db->quote($category),
		$dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']), $dbc->_db->quote($_GET['pp'])));

		$a = $dbc->_db->fetch_assoc($r);

		if(!$a) {
			return false;
		}

		$images = '
		<form id="pring_photogallery" method="get" action="">
			<input name="'.$orbicon_x->ptr.'" value="'.$_GET[$orbicon_x->ptr].'" type="hidden" />
			<input name="sp" value="'.$_GET['sp'].'" type="hidden" />
			<input name="user" value="'.$_GET['user'].'" type="hidden" />
			<div><label for="sort">Poredaj po</label>
			<select id="sort" name="sort">
				<option value="views_most" '.($selected = ($_GET['sort'] == 'views_most') ? ' selected="selected"' : '').'>Popularnosti: Prvo najpopularnije</option>
				<option value="views_least"'.($selected = ($_GET['sort'] == 'views_least') ? ' selected="selected"' : '').'>Popularnosti: Prvo najmanje popularne</option>
				<option value="title_az"'.($selected = ($_GET['sort'] == 'title_az') ? ' selected="selected"' : '').'>Nazivu: A-Z</option>
				<option value="title_za"'.($selected = ($_GET['sort'] == 'title_za') ? ' selected="selected"' : '').'>Nazivu: Z-A</option>
				<option value="newest"'.($selected = (($_GET['sort'] == 'newest') || !isset($_GET['sort'])) ? ' selected="selected"' : '').'>Datumu: Prvo najnovije</option>
				<option value="oldest"'.($selected = ($_GET['sort'] == 'oldest') ? ' selected="selected"' : '').'>Datumu: Prvo najstarije</option>
			</select> <input id="submit" value="&gt;&gt;" name="submit" type="submit" />
			</div>
		</form><br />
		<table style="text-align:left;width:100%;" id="image_gallery" summary="Image gallery" cellpadding="0"><tr>';

		while($a) {

			$desc_img = ($a['description'] != '') ? $a['description'] : $a['permalink'];

			$img_link = (is_file(DOC_ROOT . '/site/venus/thumbs/t-'.$a['permalink'])) ? '<img id="image' . $i . '" class="thumb_image" src="'.ORBX_SITE_URL.'/site/venus/thumbs/t-'.$a['permalink'].'" alt="'.$desc_img.'" title="'.$desc_img.'" onclick="javascript:update_img_views(\''.$a['permalink'].'\');" />' : '<img src="'.ORBX_SITE_URL.'/site/venus/'.$a['permalink'].'" class="thumb_image" alt="'.$desc_img.'" title="'.$desc_img.'" id="image' . $i . '" onclick="javascript:update_img_views(\''.$a['permalink'].'\');" />';

			if($category == 'pring_u_' . $_SESSION['user.r']['username']) {
				$del_link = '<input id="img_'.$i.'" type="checkbox" value="1" onchange="javascript:delete_image(\''.$a['permalink'].'\', \''.$_SESSION['user.r']['username'].'\');this.disabled=true;" /> <label for="img_'.$i.'">'._L('delete').'</label><br />';
				$desc = '<strong>'._L('description').':</strong> <input onchange="javascript:image_text(\''.$a['permalink'].'\', \''.$_SESSION['user.r']['username'].'\', this.value);" value="'. $a['description'].'" type="text" /><br />';
			}
			else {
				$del_link = '';
				if($a['description']) {
					$desc = '<strong>'._L('description').':</strong> ' . $a['description'] . '<br />';
				}
				else {
					$desc = '';
				}
			}

			$images .= '
					<td style="width: 20%; vertical-align:top;">
						<table width="100%">
							<tr>
								<td>&nbsp;</td>
								<td style="font-size: 90%;">
									<div style="width:150px; overflow:auto;"><a rel="lightbox[gallery]" href="'.ORBX_SITE_URL.'/site/venus/'.$a['permalink'].'" title="'.$desc_img.'">'.$img_link.'</a></div><br />
									'.$del_link.'
									'.$desc.'
									<strong>'._L('size').':</strong> '.get_file_size(DOC_ROOT.'/site/venus/'.$a['permalink']).'<br />
									<strong>'._L('uploaded').':</strong> '.date($_SESSION['site_settings']['date_format'], $a['uploader_time']).'
								</td>
							</tr>
						</table>';

			if(($i % 3) == 0) {
				$images .= '</td></tr><tr>';
			}
			else {
				$images .= '</td>';
			}

			$a = $dbc->_db->fetch_assoc($r);
			$i ++;
		}

		$images .= '</tr></table>';

		$images .= $pagination->construct_page_nav(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$_GET[$orbicon_x->ptr] . '&sort=' . $_GET['sort'] . '&sp=' . $_GET['sp'] . '&user=' . $_GET['user']);

		// this invalidates caching, clean up from memory
		if($unset_below) {
			unset($_GET['p'], $_GET['pp']);
		}

		// add lightbox resources
		$lightbox = '
		<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/controler/gzip.server.php?file=/orbicon/3rdParty/scriptaculous/lib/prototype.js&amp;'.ORBX_BUILD.'"></script>
		<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/3rdParty/scriptaculous/src/scriptaculous.js?'.ORBX_BUILD.'"></script>
	<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/3rdParty/lightbox/js/lightbox.js?'.ORBX_BUILD.'"></script>
    <link rel="stylesheet" type="text/css" href="'.ORBX_SITE_URL.'/orbicon/3rdParty/lightbox/css/lightbox.css?'.ORBX_BUILD.'" />
';

		return $lightbox . $images;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $category
	 * @return unknown
	 */
	function print_video_gallery($category)
	{
		global $dbc, $orbicon_x;

		$max_video_box = 4;
		$max_video_box_previews = 3;
		$css_width = intval(100 / $max_video_box_previews);
		$i = 0;

		$r = $dbc->_db->query(sprintf('		SELECT 		*
											FROM 		'.MERCURY_FILES."
											WHERE 		(category = %s)
											ORDER BY 	uploader_time DESC
											LIMIT 		0, $max_video_box",
											$dbc->_db->quote($category)));

		$a = $dbc->_db->fetch_assoc($r);

		while($i < $max_video_box) {
			$images .= '<div class="news_cat_box">';

			while($a) {
				$images .= '<div class="news_cat_box_preview" style="width:'.$css_width.'%;">'.get_flv_player($a['content']).'</div>';
				$a = $dbc->_db->fetch_assoc($r);
			}

			$images .= '</div>';
			$i++;
		}

		return $images;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $category
	 * @return unknown
	 */
	function print_download_gallery($category)
	{
		global $dbc, $orbicon_x;

		// pagination
		require_once DOC_ROOT . '/orbicon/class/class.pagination.php';

		if(!isset($_GET['p'])) {
			$unset_below = true;
		}

		$_GET['pp'] = (isset($_GET['pp'])) ? $_GET['pp'] : 10;
		$_GET['p'] = (isset($_GET['p'])) ? $_GET['p'] : 1;
		$pagination = new Pagination('p', 'pp');

		$read = $dbc->_db->query(sprintf('	SELECT 		COUNT(id) AS numrows
											FROM 		'.MERCURY_FILES.'
											WHERE 		(live = 1) AND
														(hidden = 0) AND
														(category = %s)', $dbc->_db->quote($category)));
		$row = $dbc->_db->fetch_assoc($read);

		$pagination->total = $row['numrows'];
		$pagination->split_pages();

		$i = 0;

		$r = $dbc->_db->query(sprintf('		SELECT 		*
											FROM 		'.MERCURY_FILES.'
											WHERE 		(category = %s)
											ORDER BY 	uploader_time DESC
											LIMIT 		%s, %s',
											$dbc->_db->quote($category), $dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']), $dbc->_db->quote($_GET['pp'])));

		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$title = empty($a['title']) ? $a['content'] : $a['title'];
			$date = empty($a['custom_live_date']) ? date('d.m', $a['uploader_time']) : $a['custom_live_date'];
			$images .= '<div class="dl_gallery_item"><h4 class="dl_gallery_date">'.$date.'</h4><a href="' . ORBX_SITE_URL . '/site/mercury/' . $a['content'] . '">' . $title .  ' (' . byte_size($a['size']) . ')</a> <span class="dl_gallery_desc">'.$a['description'].'</span></div>';
			$a = $dbc->_db->fetch_assoc($r);
		}

		$images .= $pagination->construct_page_nav(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$_GET[$orbicon_x->ptr]);

		// this invalidates caching, clean up from memory
		if($unset_below) {
			unset($_GET['p'], $_GET['pp']);
		}

		return $images;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $flv_url
	 * @param unknown_type $w
	 * @param unknown_type $h
	 * @param unknown_type $autoplay
	 * @return unknown
	 */
	function flv_player($flv_url, $w, $h, $autoplay = false)
	{
		$id = adler32(time() . $flv_url);
		$autoplay = ($autoplay) ? 'true' : 'false';

		$player = '<object
		classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
		codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0"
		width="'.$w.'"
		height="'.$h.'"
		id="flv_player_'.$id.'"
		align="middle">
			<param name="movie" value="'.ORBX_SITE_URL.'/orbicon/gfx/flvplayer.swf?file='.urlencode($flv_url).'&autostart='.$autoplay.'" />
			<param name="menu" value="false" />
			<param name="allowfullscreen" value="true" />
			<param name="quality" value="high" />
			<param name="wmode" value="transparent" />
			<embed
				src="'.ORBX_SITE_URL.'/orbicon/gfx/flvplayer.swf?file='.urlencode($flv_url).'&autostart='.$autoplay.'"
				menu="false"
				allowfullscreen="true"
				quality="high"
				width="'.$w.'"
				height="'.$h.'"
				name="flv_player_'.$id.'"
				align="middle"
				allowScriptAccess="sameDomain"
				type="application/x-shockwave-flash"
				pluginspage="http://www.adobe.com/go/getflash"
				wmode="transparent" />
		</object>';

		return $player;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $swf_local
	 * @param unknown_type $swf_url
	 * @param unknown_type $flashvars
	 * @return unknown
	 */
	function swf_object($swf_local, $swf_url, $flashvars = null)
	{
		if(is_array($flashvars)) {
			$fvars = array();
			foreach ($flashvars as $k => $v) {
				$fvars[] = "$k=$v";
			}
			$fvars = implode('&', $fvars);

			if($fvars) {
				$fvars_param = '<param name="flashvars" value="'.$fvars.'" />';
				$fvars_embed = 'flashvars="'.$fvars.'"';
			}
		}

		list($w, $h) = getimagesize($swf_local);

		return '<object
		classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
		codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0"
		width="'.$w.'"
		height="'.$h.'"
		align="middle">
			<param name="movie" value="'.$swf_url.'" />
			<param name="menu" value="false" />
			<param name="quality" value="high" />
			<param name="wmode" value="transparent" />
			'.$fvars_param.'
			<embed
				'.$fvars_embed.'
				src="'.$swf_url.'"
				menu="false"
				quality="high"
				width="'.$w.'"
				height="'.$h.'"
				align="middle"
				allowScriptAccess="sameDomain"
				type="application/x-shockwave-flash"
				pluginspage="http://www.adobe.com/go/getflash"
				wmode="transparent" />
		</object>';
	}

	/**
	 * Resize large image
	 *
	 * @param string $file
	 */
	function photogallery_img_size_fix($file)
	{
		$file = DOC_ROOT . '/site/venus/' . $file;
		$w = getimagesize($file);
		$w = $w[0];

		if($w > 800) {
			exec('mogrify -resize 800x ' . $file);
			update_sync_cache_list($file);
		}
	}

?>