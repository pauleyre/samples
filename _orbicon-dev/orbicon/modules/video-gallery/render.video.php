<?php

	// module include
	require_once DOC_ROOT.'/orbicon/class/class.module.php';

	$mod_params = $orbx_mod->load_info('video-gallery');

	// module include
	require_once DOC_ROOT.'/orbicon/class/inc.mmedia.php';

	list($w, $h) = get_video_size($video);

	global $video_gallery;
	require DOC_ROOT . '/orbicon/modules/video-gallery/inc.video.php';

	$url = ORBX_SITE_URL;
	$first_date = get_first_video_date();
	$last_date = get_last_video_date();

	$min_date = date('m/d/Y', $first_date);
	$min_year = date('Y', $first_date);

	$max_date = date('m/d/Y', $last_date);
	$max_year = date('Y', $last_date);

	while($min_year <= $max_year) {
		$years .= '<option value="'.$min_year.'">'.$min_year.'</option>';
		$min_year ++;
	}

	$table = ($_SESSION['site_settings']['video_gallery_show_date']) ? print_video_list($last_date) : print_category_video_list();
	$last_video_path = get_last_available_video();
	$video_dates = print_video_months();
	$aplay = ($_SESSION['site_settings']['flv_player_autoplay']) ? 'true' : 'false';
	$player = $mod_params['video']['player'];
	$daily_txt = ($_SESSION['site_settings']['video_gallery_show_date']) ? _L('daily_preview') : _L('general_preview');
	$no_vid_available = _L('no_video_available');

	if(!is_file(DOC_ROOT . '/' . $player)) {
		$player = 'orbicon/modules/video-gallery/gfx/flvplayer.swf';
	}

	$date_lising = ($_SESSION['site_settings']['video_gallery_show_date']) ? '<div id="date_listing">'.$video_dates.'</div>' : '';

return <<<TXT
<input type="hidden" id="current_valid_date" name="current_valid_date" value="{$max_date}" />
<div id="video_flash_container">

<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
		codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0"
		height="{$h}"
		width="{$w}">
			<embed src="{$url}/{$player}?file={$last_video_path}&amp;autostart={$aplay}"
			menu="false"
			allowfullscreen="true"
			quality="high"
			allowscriptaccess="sameDomain"
			type="application/x-shockwave-flash"
			pluginspage="http://www.adobe.com/go/getflash"
			height="{$h}"
			width="{$w}"
			wmode="transparent" />
			<param name="movie" value="{$url}/{$player}?file={$last_video_path}&amp;autostart={$aplay}" />
			<param name="quality" value="high" />
			<param name="menu" value="0" />
			<param name="wmode" value="transparent" />
			<param name="allowfullscreen" value="true" />
			<param name="allowscriptaccess" value="sameDomain" />
</object>

</div>
<br />
<div style="border-bottom: 1px solid #cccccc;"></div>
<br />
<!-- yui calendar -->
<style type="text/css">/*<![CDATA[*/
	@import url("{$url}/orbicon/modules/video-gallery/gfx/video.css");
/*]]>*/</style>
<script type="text/javascript"><!-- // --><![CDATA[

	function update_table_vid(date)
	{
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {

				if(!empty(o.responseText)) {
					// * update mini browser container
					$('video_table_container').innerHTML = o.responseText;
					$('current_valid_date').value = date;
				}
				else {
					window.alert('{$no_vid_available}');
				}
			}
		}

		var callback =
		{
			success:handleSuccess,
			timeout: 15000
		};

		var data = new Array();
		data[0] = 'date=' + date;
		data[1] = 'video_gallery={$video_gallery}';

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', orbx_site_url + '/orbicon/modules/video-gallery/xhr.video.table.php', callback, data);
	}

	function update_flash_vid(video)
	{
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				if(!empty(o.responseText)) {
					// * update container
					$('video_flash_container').innerHTML = o.responseText;
				}
			}
		}

		var callback =
		{
			success:handleSuccess,
			timeout: 15000
		};

		var data = new Array();
		data[0] = 'video=' + video;

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', orbx_site_url + '/orbicon/modules/video-gallery/xhr.video.flash.php', callback, data);
	}

// ]]></script>
<!-- yui calendar -->
<div id="video_table">
	{$date_lising}
	<div id="video_listing">
		<fieldset class="video_fieldset"><legend>{$daily_txt}</legend>
			<div class="video_scroll_container" id="video_table_container">{$table}</div>
		</fieldset>
	</div>
	<div class="cleaner"></div>
</div>
<!--
<table id="">
	<tr>
		<td style="width:145px;">{$video_dates}</td>
		<td><fieldset class="video_fieldset"><legend>{$daily_txt}</legend><div class="video_scroll_container" id="video_table_container">{$table}</div></fieldset></td>
</tr>
</table> -->
TXT;

?>