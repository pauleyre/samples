<div class="sidebar_subprop" id="res_properties" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/list.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_properties_container');"><?php echo _L('created_groups'); ?></a></div>

<div id="res_properties_container">

<div id="site_editors_list">

<ol>
<?php
	if(!empty($all)) {
		foreach($all as $value) {
			if($value['permalink'] != '') {
?>
<li><a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/privileges&amp;edit=<?php echo $value['permalink']; ?>"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/edit.png" /> <?php echo _L('edit'); ?></a> <a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/privileges&amp;del=<?php echo $value['permalink']; ?>"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/delete.png" /> <?php echo _L('delete'); ?></a> <strong><?php echo $value['group_name']; ?></strong> </li>
<?php
			}
		}
	}
?>
</ol>

</div>

</div>