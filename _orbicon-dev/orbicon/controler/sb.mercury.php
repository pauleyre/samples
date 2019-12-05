<div class="sidebar_subprop" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/database-browser.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_content_container');"><?php echo _L('db_content'); ?></a></div>

<div id="res_content_container"><?php echo $hf -> get_category_menu(); ?></div>

<div class="sidebar_subprop" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/add-new-picker.gif) no-repeat; height: 22px;"
><a href="javascript:void(null);" onclick="javascript: sh('res_upload_container');"><?php echo _L('add_new'); ?></a></div>

<div id="res_upload_container"><?php echo $hf -> get_upload_applet(); ?></div>
