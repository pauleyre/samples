<?php

	$a2_ = $orbicon_x->build_languages_menu(null);

?>
<div class="sidebar_subprop" id="res_properties" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/translation-wizard.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_properties_container');"><?php echo _L('translation_wizard'); ?></a></div>

<div id="res_properties_container">

<form action="" method="post" enctype="multipart/form-data">

<fieldset>
<legend><label for="import_file"><?php echo _L('import'); ?></label></legend>

<input type="file" id="import_file" name="import_file" />

<select style="overflow:hidden; width: 150px; font-size: 80%;" id="import_lng" name="import_lng">
	<option value="" selected="selected">&mdash;</option>
	<?php echo $a2_[0]; ?>
</select>

<input id="import" name="import" type="submit" value="<?php echo _L('import'); ?>" />
</fieldset>

<fieldset>
<legend><label for="export_file"><?php echo _L('export'); ?></label></legend>

<select id="export_file" name="export_file"><option value="" selected="selected">&mdash;</option><?php echo $a_[1]; ?></select>

<input type="submit" id="export" name="export" value="<?php echo _L('export'); ?>" />
</fieldset>

</form>

</div>