<div class="sidebar_subprop" id="res_monthpicker" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/date-range.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_monthpicker_container');"><?php echo _L('pick_month'); ?></a></div>

<div id="res_monthpicker_container">

<form method="get" action="">
	<input type="hidden" id="<?php echo $orbicon_x->ptr; ?>" name="<?php echo $orbicon_x->ptr; ?>" value="orbicon/mod/stats" />
	<select id="range" name="range" title="<?php echo _L('select_a_month'); ?>">
		<option></option>
		<optgroup><?php echo _L('select_a_month'); ?></optgroup>
		<?php

			$months = array_reverse($stats->get_stat_months());
			foreach($months as $month) {
				echo sprintf('<option value="%s">%s</option>', $month, strftime('[%m] %B', $month));
			}
		?>
	</select> <input type="submit" value="<?php echo _L('submit'); ?>" />
</form>

</div>

<div class="sidebar_subprop" id="res_properties" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/properties.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_properties_container');"><?php echo _L('properties'); ?></a></div>

<div id="res_properties_container">

<form method="post" action="">

	<input id="stats_sess" name="stats_sess" value="1" type="checkbox" <?php if($_SESSION['site_settings']['stats_sess']) echo 'checked="checked"'; ?> /> <label for="stats_sess"><?php echo _L('stats_sess'); ?></label><br />
	<input id="stats_ip" name="stats_ip" value="1" type="checkbox" <?php if($_SESSION['site_settings']['stats_ip']) echo 'checked="checked"'; ?> /> <label for="stats_ip"><?php echo _L('stats_ip'); ?></label><br />
	<input id="stats_content" name="stats_content" value="1" type="checkbox" <?php if($_SESSION['site_settings']['stats_content']) echo 'checked="checked"'; ?> /> <label for="stats_content"><?php echo _L('stats_content'); ?></label><br />
	<input id="stats_refer" name="stats_refer" value="1" type="checkbox" <?php if($_SESSION['site_settings']['stats_refer']) echo 'checked="checked"'; ?> /> <label for="stats_refer"><?php echo _L('stats_refer'); ?></label><br />
	<input id="stats_country" name="stats_country" value="1" type="checkbox" <?php if($_SESSION['site_settings']['stats_country']) echo 'checked="checked"'; ?> /> <label for="stats_country"><?php echo _L('stats_country'); ?></label><br />
	<input id="stats_keyword" name="stats_keyword" value="1" type="checkbox" <?php if($_SESSION['site_settings']['stats_keyword']) echo 'checked="checked"'; ?> /> <label for="stats_keyword"><?php echo _L('stats_keyword'); ?></label><br />
	<input id="stats_hourly" name="stats_hourly" value="1" type="checkbox" <?php if($_SESSION['site_settings']['stats_hourly']) echo 'checked="checked"'; ?> /> <label for="stats_hourly"><?php echo _L('stats_hourly'); ?></label><br />
	<input id="stats_attila" name="stats_attila" value="1" type="checkbox" <?php if($_SESSION['site_settings']['stats_attila']) echo 'checked="checked"'; ?> /> <label for="stats_attila"><?php echo _L('stats_attila'); ?></label><br />

	<input id="save_stats_props" name="save_stats_props" type="submit" value="<?php echo _L('submit'); ?>" />
</form>

</div>