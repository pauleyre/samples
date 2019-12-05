<?php
	$my_desktop = $orbicon_x->print_desktop();

	require_once DOC_ROOT . '/orbicon/class/inc.desktop_rss.php';
	$all = load_desktop_rss();
	remove_desktop_rss();
	add_desktop_rss();

	$orbicon_x->add_wallpaper();
	if(isset($_POST['reset_wallpaper'])) {
		$orbicon_x->reset_wallpaper();
	}
	$wallpaper = $orbicon_x->get_current_wallpaper();

?>
<style type="text/css">/*<![CDATA[*/

<?php

	// display wallpaper
	if(!empty($wallpaper['image'])) {
		echo '#orbx_tools_sidebar_right { z-index: 1; position: relative;} #desktop_image {position: absolute; width: 100%; height: 100%; top: 72px; left: 0px;}';
	}

?>

/*]]>*/</style>

<script type="text/javascript"><!-- // --><![CDATA[

var __desktop_owner = <?php echo $_SESSION['user.a']['id']; ?>;

function desktop_init()
{
 	orbx_load_desktop_rss('<?php echo $all[0]; ?>');
	<?php echo $my_desktop['js']; ?>
}

YAHOO.util.Event.addListener(window, 'load', desktop_init);

	function add_desktop_feed()
	{
		var url = $("rss_feed");

		if(empty(url.value) || (url.value == "http://") || (url.value == 'https://')) {
			window.alert("<?php echo _L('enter_feed_url'); ?>");
			url.focus();
			return false;
		}
		return true;
	}

// ]]></script>

<?php

	// display wallpaper
	if(!empty($wallpaper['image'])) {
		echo '<div id="desktop_image"><img id="desktop_img_src" src="'.ORBX_SITE_URL.'/site/venus/'.$wallpaper['image'].'" /></div>';
	}

?>

<div id="orbx_desktop">
	<?php echo $my_desktop['icons']; ?>
</div>

<div id="orbx_tools_sidebar_right">
	<div class="rtop">
		<div class="r1"></div> <div class="r2"></div> <div class="r3"></div> <div class="r4"></div>
	</div>

	<div id="orbx_window">
		<h2>
			<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/desktop_icons/my-desktop.gif" alt="<?php echo _L('my_desktop'); ?>" title="<?php echo _L('my_desktop'); ?>" />
			<?php echo _L('my_desktop'); ?> [<span id="orbx_clock_hh_mm"><b>hh:mm</b></span><span id="orbx_clock_ss"><b>:ss</b></span>]
		</h2>

		<div class="sidebar_subprop" style="border: 1px solid #C0C0BF;">
			<a href="javascript:void(null);" onclick="javascript: sh('res_wallpaper_container');"><?php echo _L('properties'); ?></a>
		</div>

		<div id="res_wallpaper_container" style="display:none;">
			<form method="post" action="" enctype="multipart/form-data">
				<fieldset><legend><label for="wallpaper"><?php echo _L('wallpaper'); ?></label></legend>
					<input maxlength="2047" id="wallpaper" name="wallpaper" type="file" />
					<input id="add_wallpaper" name="add_wallpaper" title="<?php echo _L('add'); ?>" value="<?php echo _L('add'); ?> +" type="submit" />
					<input type="submit" id="reset_wallpaper" name="reset_wallpaper" value="<?php echo _L('reset'); ?>" />
				</fieldset>
			</form>
		</div>

		<div class="sidebar_subprop" style="border: 1px solid #C0C0BF;">
			<a href="javascript:void(null);" onclick="javascript: sh('my_rsslist_container');"><?php echo _L('rss_list'); ?></a>
		</div>

		<div id="my_rsslist_container" style="display:none;">

		<form method="post" action="" onsubmit="javascript: return add_desktop_feed();">
				<label for="rss_feed"><?php echo _L('add_new_rss'); ?></label>
				<input maxlength="2047" id="rss_feed" name="rss_feed" type="text" value="http://" />
				<input id="add_rss" name="add_rss" title="<?php echo _L('add'); ?>" value="+" type="submit" />
			</form>

			<ol>
		<?php

			foreach($all as $rss) {
				if(!empty($rss)) {
					$host = parse_url($rss);
					$host = $host['host'];
		?>
				<li>
					<div class="overflow_hide">
						<a href="javascript:void(null);" onclick="javascript: orbx_load_desktop_rss('<?php echo $rss; ?>');"><?php echo $host; ?></a>
						<a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon&amp;remove-rss=<?php echo $rss; ?>">
							<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/delete.png" style="vertical-align:bottom;" />
						</a>
					</div>
				</li>
		<?php
				}
			}
		?>
			</ol>
		</div>

		<div class="rss_rtop">
			<div class="r1"></div> <div class="r2"></div> <div class="r3"></div> <div class="r4"></div>
		</div>

		<div id="my_rss_loader" style="display: none;">
			<h3><?php echo _L('loading'); ?>...</h3>
			<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/indicator.gif" alt="!" title="<?php echo _L('update_prog'); ?>..." />
		</div>

		<div id="my_rss_content" style="display: none;"></div>

		<div class="rss_rbottom">
			 <div class="r4"></div> <div class="r3"></div> <div class="r2"></div> <div class="r1"></div>
		</div>
	</div>



	<div class="rbottom">
		<div class="r4"></div> <div class="r3"></div> <div class="r2"></div> <div class="r1"></div>
	</div>
</div>