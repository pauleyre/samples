<div class="sidebar_subprop" id="res_new_rss" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/add-new-picker.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_new_rss_container');"><?php echo _L('add_new'); ?></a></div>

<div id="res_new_rss_container">

	<label for="rss_feed"><?php echo _L('add'); ?> RSS feed</label>
	<form method="post" action="" onsubmit="javascript: return add_feed();">
		<input maxlength="2047" id="rss_feed" name="rss_feed" type="text" value="http://"> <input id="add_rss" name="add_rss" title="<?php echo _L('add'); ?>" value="<?php echo _L('add'); ?>" type="submit" />
	</form><br />
</div>