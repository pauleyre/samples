<div class="sidebar_subprop" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/database-browser.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_content_container');"><?php echo _L('db_content'); ?></a></div>

<div id="res_content_container"><?php echo $hf->get_category_menu(); ?></div>

<div class="sidebar_subprop" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/add-new-picker.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_upload_container');"><?php echo _L('add_new'); ?></a></div>

<div id="res_upload_container"><?php echo $hf->get_upload_applet(); ?></div>

<div class="sidebar_subprop" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/sort-by.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_sort_container');"><?php echo _L('sort'); ?></a></div>

<div id="res_sort_container" style="display:none; padding: 0 0 0 5px;">

	<p style="line-height: 100%;"><a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/venus&amp;sort_by=name"><?php echo _L('filename'); ?></a></p>
	<p style="line-height: 100%;"><a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/venus&amp;sort_by=cat"><?php echo _L('category'); ?></a></p>
	<p style="line-height: 100%;"><a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/venus&amp;sort_by=date"><?php echo _L('uploaded'); ?></a></p>
	<p style="line-height: 100%;"><a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/venus&amp;sort_by=bytes"><?php echo _L('size'); ?></a></p>
</div>