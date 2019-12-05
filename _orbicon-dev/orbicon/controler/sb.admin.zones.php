<div class="sidebar_subprop" id="res_zones_list" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/list.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_zones_list_container');"><?php echo _L('zones'); ?></a></div>

<div id="res_zones_list_container">

<?php
	if(!empty($all)) {
?>
<p>
<ol>
<?php
	foreach($all as $key => $value) {
		if(!empty($value['permalink'])) {
?>
<li>
	<a href="<?php echo ORBX_SITE_URL;?>/?<?php echo $orbicon_x->ptr;?>=orbicon/zones&amp;edit=<?php echo $value['permalink'];?>"><?php echo $value['title'];?></a>
	<a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/zones&amp;delete_zone=<?php echo $value['permalink']; ?>" onclick="javascript: return false;" onmousedown="<?php echo delete_popup($value['title']); ?>"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/delete.png" alt="<?php echo _L('delete'); ?>" title="<?php echo _L('delete'); ?>" /></a>
</li>
<?php
		}
	}
?>
</ol>
</p>
<?php
	}
?>

</div>




<div class="sidebar_subprop" id="res_properties" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/properties.gif) no-repeat; height: 22px;"
><a href="javascript:void(null);" onclick="javascript: sh('res_properties_container');"><?php echo _L('properties'); ?></a></div>

<div id="res_properties_container" style="display:none;">

<p>
		<strong><?php echo _L('locked_for_reg'); ?></strong><br />
		<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/lock.png" /> <input type="radio" onchange="javascript: orbx_carrier(this, document.zones_form.locked);" name="locked" value="1" id="locked_yes" <?php if($my_zone['locked']) echo 'checked="checked"'; ?> /> <label for="locked_yes"><?php echo _L('yes'); ?></label><br />
		<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/lock_open.png" /> <input type="radio" onchange="javascript: orbx_carrier(this, document.zones_form.locked);" name="locked" value="0" id="locked_no" <?php if(!$my_zone['locked']) echo 'checked="checked"'; ?> /> <label for="locked_no"><?php echo _L('no'); ?></label>
	</p>

<p>
		<strong><?php echo _L('zone_in_ssl'); ?></strong><br />
		<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/lock.png" /> <input type="radio" onchange="javascript: orbx_carrier(this, document.zones_form.under_ssl);" name="under_ssl" value="1" id="ssl_yes" <?php if($my_zone['under_ssl']) echo 'checked="checked"'; ?> /> <label for="ssl_yes"><?php echo _L('yes'); ?></label><br />
		<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/lock_open.png" /> <input type="radio" onchange="javascript: orbx_carrier(this, document.zones_form.under_ssl);" name="under_ssl" value="0" id="ssl_no" <?php if(!$my_zone['under_ssl']) echo 'checked="checked"'; ?> /> <label for="ssl_no"><?php echo _L('no'); ?></label>
	</p>
</div>