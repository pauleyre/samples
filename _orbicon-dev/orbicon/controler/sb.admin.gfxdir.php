<div class="sidebar_subprop" style="height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_content_container');"><?php echo _L('add_new'); ?></a></div>

<div id="res_content_container" style="padding: 10px 0 0 10px;">
<?php
	$applet = new Mercury;
	echo $applet->get_upload_applet(ORBX_SITE_URL.'/orbicon/controler/admin.gfxdir.upload.php?credentials=' . get_ajax_id());
?>
</div>