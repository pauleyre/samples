<script defer="defer" type="text/javascript"><!-- // --><![CDATA[

	YAHOO.util.Event.addListener(window,"load",start_magister_mb);

	function start_magister_mb()
	{
		switch_mini_browser('venus', '', 0, 0);
	}

<?php

if($_GET['event'] == 'edit') {

?>
	/* image */
	__venus_mini_input = '<?php echo $urow->img_naziv; ?>';
	__venus_mini_url = '<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon&ajax_img_db&action=img';
	__venus_mini_update_list();

<?php

	}

?>

// ]]></script>

<div class="sidebar_subprop" id="res_browser" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/database-browser.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_browser_container');"><?php echo _L('db_content'); ?></a></div>

<div id="res_browser_container">

	<div class="clean"></div>
				<div id="mini_browser_container"></div>			
</div>