<div class="sidebar_subprop" id="res_adbr_list" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/list.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_adbr_list_container');"><?php echo _L('adrbk_list'); ?></a></div>

<div id="res_adbr_list_container">

<?php
	if(!empty($all)) {
?>
<p>
<ol>
		<?php

			foreach($all as $value) {
				if(!empty($value['permalink'])) {
		?>
			<li>
				<a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/mod/address-book&amp;edit=<?php echo $value['permalink'];?>"><?php echo $value['title'];?></a>
				<a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/mod/address-book&amp;delete=<?php echo $value['permalink']; ?>" onclick="javascript: return false;" onmousedown="<?php echo delete_popup(addslashes($value['title'])); ?>"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/delete.png" alt="<?php echo _L('delete'); ?>" title="<?php echo _L('delete'); ?>" /></a>
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




<form action="" method="post">

<div class="sidebar_subprop" id="res_zones_list" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/mass-e-mail-add.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_zones_list_container');"><?php echo _L('mass_mail_add'); ?></a></div>

<div id="res_zones_list_container" style="display:none;">


			<label for="mass_email_add"><?php echo _L('mails'); ?> (<?php echo _L('separate_coma'); ?>)</label><br />
			<textarea id="mass_email_add" name="mass_email_add" style="width: 100%; height: 150px;"></textarea><br />
			<input type="submit" id="mass_email_save" name="mass_email_save" value="<?php echo _L('add'); ?>" />

</div>

<div class="sidebar_subprop" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/import-from-csv.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_csv_container');"><?php echo _L('csv_import'); ?></a></div>

<div id="res_csv_container" style="display:none;">
			<input type="file" id="csv_adrbk" name="csv_adrbk" />
</div>

</form>