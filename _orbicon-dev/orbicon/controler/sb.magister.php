<div class="sidebar_subprop browser" id="res_content" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/database-browser.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_content_container');"><?php echo _L('db_content'); ?></a></div>

<div id="res_content_container">

<div class="toolbar-picker">
			<a href="javascript:void(null);" onclick="javascript:switch_mini_browser('venus', '', 0, 0);"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/picture.png" alt="image-tool-picker" width="16" height="16" border="0" /> <?php echo _L('images'); ?></a> |
			<a href="javascript:void(null);" onclick="javascript:switch_mini_browser('mercury', '', 0, 0);"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/ul-file-picker.png" alt="file-tool-picker" width="16" height="16" border="0" /> <?php echo _L('data'); ?></a> |
			<a href="javascript:void(null);" onclick="javascript:switch_mini_browser('magister', '', 0, 0);"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/ul-text-category-filter.png" alt="text-tool-picker" width="16" height="16" border="0" /> <?php echo _L('texts'); ?></a>
		</div>
		<div id="mini_browser_container">
			<?php echo /*$hf -> get_category_menu();*/ $hf->get_mini_browser_categories(); ?>
		</div>

</div>



<div class="sidebar_subprop" id="res_properties" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/properties.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_properties_container');"><?php echo _L('properties'); ?></a></div>

<div id="res_properties_container" style="display:none;">

					<p><label for="uploader"><?php echo _L('author'); ?><br /></label>
					<input onchange="javascript: orbx_carrier(this, document.magister_form.uploader);"  style="width: 85%;" name="uploader" id="uploader" type="text" value="<?php echo (empty($a['uploader'])) ? $_SESSION['user.a']['first_name'].' '.$_SESSION['user.a']['last_name'] : $a['uploader']; ?>" maxlength="200" /></p>
					<p><strong><?php echo _L('ip_addr'); ?></strong><br />
					<?php echo (empty($a['uploader_ip'])) ? ORBX_CLIENT_IP : $a['uploader_ip']; ?></p>
					<p><strong><?php echo _L('live_date'); ?></strong><br />
					<?php echo (empty($a['uploader_time'])) ? date('r', time()) : date('r', $a['uploader_time']); ?></p>
					<p><strong><?php echo _L('last_mod'); ?></strong><br />
					<?php echo (empty($a['last_modified'])) ? date('r', time()) : date('r', $a['last_modified']); ?></p>

</div>