<div class="sidebar_subprop" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/database-browser.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_content_container');"><?php echo _L('add_new'); ?></a></div>

<div id="res_content_container" style="padding: 10px 0 0 10px;">
<?php
	require_once DOC_ROOT . '/orbicon/mercury/class.mercury.php';
	$applet = new Mercury;
	echo $applet->get_upload_applet(ORBX_SITE_URL.'/orbicon/modules/daily_report/admin.daily_report.upload.php?credentials=' . get_ajax_id());
?>
</div>