<?php

global $dbc;

if($_GET['sp'] == 'promo') {

?>

<div class="sidebar_subprop browser" id="res_content" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/database-browser.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_content_container');"><?php echo _L('db_content'); ?></a></div>

<div id="res_content_container">

<div class="toolbar-picker">
			<a href="javascript:void(null);" onclick="javascript:switch_mini_browser('venus', '', 0, 0);"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/picture.png" alt="image-tool-picker" width="16" height="16" border="0" /> <?php echo _L('images'); ?></a> |
			<a href="javascript:void(null);" onclick="javascript:switch_mini_browser('mercury', '', 0, 0);"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/ul-file-picker.png" alt="file-tool-picker" width="16" height="16" border="0" /> <?php echo _L('data'); ?></a>
		</div>
		<div id="mini_browser_container"></div>

</div>

<?php
}
elseif ($_GET['sp'] == 'company') {
?>


<div class="sidebar_subprop" id="res_nwsltr_content" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/database-browser.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_forms_content_container');"><?php echo _L('db_content'); ?></a></div>

<div id="res_forms_content_container">

<div id="mini_browser_container"></div>

</div>

<?php
}
?>