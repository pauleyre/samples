<div class="sidebar_subprop" id="res_zones_list" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/list.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_zones_list_container');"><?php echo _L('form_list'); ?></a></div>

<div id="res_zones_list_container">

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
				<a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/mod/forms&amp;edit=<?php echo $value['permalink'];?>">
					<?php echo $value['title'];?>
				</a> 
				<a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/mod/forms&amp;delete_form=<?php echo $value['permalink']; ?>" onclick="javascript:return false;" onmousedown="<?php echo delete_popup($value['title']); ?>">
					<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/delete.png" alt="<?php echo _L('delete'); ?>" title="<?php echo _L('delete'); ?>" />
				</a>
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


<div class="sidebar_subprop" id="res_nwsltr_content" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/database-browser.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_forms_content_container');"><?php echo _L('db_content'); ?></a></div>

<div id="res_forms_content_container">

<div id="mini_browser_container"></div>

</div>