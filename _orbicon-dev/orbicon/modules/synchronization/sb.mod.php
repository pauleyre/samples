<div class="sidebar_subprop" id="res_properties" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/properties.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_properties_container');"><?php echo _L('properties'); ?></a></div>

<div id="res_properties_container">

<form method="post" action="">
<p>
	<strong><?php echo _L('role'); ?></strong><br />
	<input type="radio" name="syncm_type" value="<?php echo SYNC_MANAGER_TYPE_NONE; ?>" id="type_none" <?php if($_SESSION['site_settings']['syncm_type'] == SYNC_MANAGER_TYPE_NONE) echo 'checked="checked"'; ?> /> <label for="type_none"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/server.png" /> <?php echo _L('none'); ?></label><br />
	<input <?php echo $sync_enabled; ?> type="radio" name="syncm_type" value="<?php echo SYNC_MANAGER_TYPE_RECEIVER; ?>" id="type_rec" <?php if($_SESSION['site_settings']['syncm_type'] == SYNC_MANAGER_TYPE_RECEIVER) echo 'checked="checked"'; ?> /> <label for="type_rec"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/server.png" /> <?php echo _L('receiver'); ?></label><br />
	<input <?php echo $sync_enabled; ?> type="radio" name="syncm_type" value="<?php echo SYNC_MANAGER_TYPE_REPOSITORY; ?>" id="type_repos" <?php if($_SESSION['site_settings']['syncm_type'] == SYNC_MANAGER_TYPE_REPOSITORY) echo 'checked="checked"'; ?> /> <label for="type_repos"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/server_go.png" /> <?php echo _L('repository'); ?></label>
</p>

<input name="save_syncm" type="submit" id="save_syncm" value="<?php echo _L('save'); ?>" />

</form>
</div>