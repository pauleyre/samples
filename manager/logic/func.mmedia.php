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