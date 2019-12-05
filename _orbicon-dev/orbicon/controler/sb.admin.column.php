<?php
	if(!$is_box) {
?>

<div class="sidebar_subprop" style="border: 1px solid #C0C0BF; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_csv_container');"><?php echo _L('db_content'); ?></a></div>

<div id="res_csv_container">

<div id="mini_browser_container"></div>

</div>

<div class="sidebar_subprop" id="res_zones_list" style="border: 1px solid #C0C0BF; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_zones_list_container');"><?php echo _L('properties'); ?></a></div>

<div id="res_zones_list_container">


<p><label for="column_type"><?php echo _L('type'); ?></label><br />
		<select name="column_type" id="column_type" onchange="javascript: display_created_forms(this.options[this.selectedIndex].value); orbx_carrier(this, document.column_form.column_type);" onblur="javascript: orbx_carrier(this, document.column_form.column_type);">
		<optgroup label="<?php echo _L('select_column_type') ?>">
			<option style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/page.png) top left no-repeat;" value="default" <?php if($my_column['type'] == 'default') echo 'selected="selected"'; ?>><?php echo _L('default_column'); ?></option>
			<option style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/photos.png) top left no-repeat;" value="photo" <?php if($my_column['type'] == 'photo') echo 'selected="selected"'; ?>><?php echo _L('photo_gallery'); ?></option>
			<option style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/film.png) top left no-repeat;" value="video" <?php if($my_column['type'] == 'video') echo 'selected="selected"'; ?>><?php echo _L('video_gallery'); ?></option>
			<option style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/folder_page.png) top left no-repeat;" value="data" <?php if($my_column['type'] == 'data') echo 'selected="selected"'; ?>><?php echo _L('data_gallery'); ?></option>
	<?php
		if($orbx_mod->validate_module('forms')) {
	?>
			<option style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/application_form.png) top left no-repeat;" value="form" <?php if($my_column['type'] == 'form') echo 'selected="selected"'; ?>><?php echo _L('form'); ?></option>
	<?php
		}
	?>
		</optgroup>
		</select>
	</p>
	<?php
		if($orbx_mod->validate_module('forms')) {
	?>
	<p id="form_list" class="h">
		<label for="existing_form"><?php echo _L('form'); ?></label><br />
		<select id="existing_form" name="existing_form" onblur="javascript: orbx_carrier(this, document.column_form.existing_form);" onchange="javascript: orbx_carrier(this, document.column_form.existing_form);">
			<option value="" selected="selected">&mdash;</option>
			<optgroup label="<?php echo _L('pick_a_form'); ?>">
			<?php
				require_once DOC_ROOT . '/orbicon/modules/forms/class.form.php';
				$form = new Form;

				$all = $form->get_form_array();
				unset($form);

				if(!empty($all)) {
					foreach($all as $value) {
						if(!empty($value['permalink'])) {
							$selected = ($my_column['content'] == $value['permalink']) ? ' selected="selected"' : '';
			?>
				<option value="<?php echo $value['permalink'];?>"<?php echo $selected;?>><?php echo $value['title']; ?></option>
			<?php
						}
					}
				}
			?>
			</optgroup>
		</select> <a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/mod/forms"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/formulari.png" /> <?php echo _L('edit'); ?></a>
	</p>

	<?php
		}
	?>

	<p id="photo_list" class="h">
		<label for="image_categories"><?php echo _L('images'); ?></label><br />
		<select id="image_categories" name="image_categories" onblur="javascript: orbx_carrier(this, document.column_form.image_categories);" onchange="javascript: orbx_carrier(this, document.column_form.image_categories);">
			<option value="" selected="selected">&mdash;</option>
			<optgroup label="<?php echo _L('pick_a_category'); ?>">
			<?php

				require_once DOC_ROOT . '/orbicon/venus/class.venus.php';

				$venus = new Venus;

				echo $venus->get_categories($my_column['content']);
				unset($venus);

			?>
			</optgroup>
		</select>
	</p>

	<p id="video_list" class="h">
		<label for="data_categories"><?php echo _L('videos'); ?></label><br />
		<select id="data_categories" name="data_categories" onblur="javascript: orbx_carrier(this, document.column_form.data_categories);" onchange="javascript: orbx_carrier(this, document.column_form.data_categories);">
			<option value="">&mdash;</option>
			<optgroup label="<?php echo _L('pick_a_category'); ?>">
			<?php

				require_once DOC_ROOT . '/orbicon/mercury/class.mercury.php';
				$mercury = new Mercury;
				$categories = $mercury->get_categories($my_column['content']);

				echo $categories;

			?>
			</optgroup>
		</select>
	</p>

	<p id="dl_list" class="h">
		<label for="dl_categories"><?php echo _L('data'); ?></label><br />
		<select id="dl_categories" name="dl_categories" onblur="javascript: orbx_carrier(this, document.column_form.dl_categories);" onchange="javascript: orbx_carrier(this, document.column_form.dl_categories);">
			<option value="">&mdash;</option>
			<optgroup label="<?php echo _L('pick_a_category'); ?>">
			<?php

				echo $categories;
				unset($mercury);

			?>
			</optgroup>
		</select>
	</p>

  	<p><label for="column_redirect2"><?php echo _L('redirect_to_url'); ?> (<?php echo _L('for_example'); ?> http://www.hpb.hr)</label><br />
    	<input style="width:100%;" type="text" name="column_redirect" id="column_redirect2" value="<?php echo $my_column['redirect']; ?>" onchange="javascript: orbx_carrier(this, document.column_form.column_redirect);" />
    	<label for="generated_pages">&mdash;<?php echo _L('or'); ?>&mdash;</label>
    	<div style="width:100%; overflow:auto; height: 38px;">
    	<select id="generated_pages" onchange="javascript: var new_redirect = '<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '='; ?>' + this.value; document.column_form.column_redirect.value = new_redirect; $('column_redirect2').value = new_redirect;"><option value="" selected="selected">&mdash;</option>
    	<?php

    		$a_ = build_zones(array());
    		echo $a_[0];
    		unset($a_);
    	?>
    	</select>
    	</div>
	</p>


	<p>
		<label for="template2"><?php echo _L('template'); ?></label><br />
		<select id="template2" name="template" onblur="javascript: orbx_carrier(this, document.column_form.template);" onchange="javascript: orbx_carrier(this, document.column_form.template);">
		<option value="">&mdash;</option>
			<?php echo print_select_menu(array_map('basename', glob(DOC_ROOT . '/site/gfx/{*.html}', GLOB_BRACE)), $my_column['template']); ?>
		</select>

	</p>

	<p>
		<label for="group2"><?php echo _L('group'); ?></label><br />
		<select id="group2" name="group2" onblur="javascript: orbx_carrier(this, document.column_form.group);" onchange="javascript: orbx_carrier(this, document.column_form.group);">
			<option value="">&mdash;</option>
			<?php echo print_select_menu(array('info' => 'Info', 'selling' => 'Prodaja', 'misc' => 'Razno'), $my_column['infogroup'], true); ?>
		</select>

	</p>


		<p>
		<label for="parent2"><?php echo _L('parent'); ?></label><br />
		<div style="width:100%; overflow:auto; height: 38px;">
		<select id="parent2" name="parent2" onblur="javascript: orbx_carrier(this, document.column_form.parent);" onchange="javascript: orbx_carrier(this, document.column_form.parent);">
			<option value="">&mdash;</option>
<?php

				$options = '';
				$r = sql_res('	SELECT 		*
							FROM 		'.TABLE_COLUMNS.'
							WHERE 		(language = %s)
							ORDER BY 	sort', $orbicon_x->ptr);
				$a = $dbc->_db->fetch_assoc($r);

				while($a) {
					if($my_column['permalink'] != $a['permalink']) {
						$selected = ($my_column['parent'] == $a['permalink']) ? ' selected="selected"' : '';
						$_parent = (empty($a['parent'])) ? '' : ' ['.$a['parent'].']';
						$options .= sprintf('<option value="%s"%s>%s%s</option>', $a['permalink'], $selected, $a['title'], $_parent);
					}
					$a = $dbc->_db->fetch_assoc($r);
				}

				$options .= '</optgroup>';

				echo $options;

?>

		</select>
		</div>

	</p>


	<p>
		<label for="menu_name2"><?php echo _L('menu'); ?></label><br />
		<select id="menu_name2" name="menu_name2" onblur="javascript: orbx_carrier(this, document.column_form.menu_name);" onchange="javascript: orbx_carrier(this, document.column_form.menu_name);">
			<option value="">&mdash;</option>
			<?php echo print_select_menu(array('h' => _L('horizontal'), 'v' => _L('vertical'), 'hidden' => _L('internal'), 'box' => _L('boxes')), $my_column['menu_name'], true); ?>
		</select>

	</p>

	<p>
		<label for="desc2"><?php echo _L('site_desc'); ?></label><br />
		<input style="width:100%;" type="text" name="desc" id="desc2" value="<?php echo $my_column['desc']; ?>" onchange="javascript: orbx_carrier(this, document.column_form.desc);" />

	</p>

</div>

<?php
	}
	else {
?>
<script type="text/javascript">
function RichTextHideColorPicker()
{
	var __color_picker = $('rte_color_picker');
	__color_picker.style.visibility = 'hidden';
}

function RichTextDisplayColorPicker(obj)
{
	var __color_picker = $('rte_color_picker');
	__color_picker.style.visibility = 'visible';
	setLyr(obj, 'rte_color_picker');
}
</script>

<div class="sidebar_subprop" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/database-browser.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_csv_container');"><?php echo _L('db_content'); ?></a></div>

<div id="res_csv_container">

<div id="mini_browser_container"></div>

</div>


<div class="sidebar_subprop" id="res_zones_list" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/properties.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_zones_list_container');"><?php echo _L('properties'); ?></a></div>

<div id="res_zones_list_container">

<div id="rte_color_picker" style=" border: 0; display: block; background: transparent; "><?php require_once DOC_ROOT . '/orbicon/rte/rte_components/color_picker.php'; ?></div>
<p>
<label for="box_zone"><?php echo _L('box_zone'); ?><br /></label>

<?php

	$disabled_zones = (!empty($my_column['parent'])) ? 'disabled="disabled"' : '';

?>
<div style="width:100%; overflow:auto; height:38px;">
<select <?php echo $disabled_zones; ?> id="box_zone" name="box_zone" onchange="javascript: orbx_carrier(this, document.column_form.box_zone);" onblur="javascript: orbx_carrier(this, document.column_form.box_zone);">
<option value="" selected="selected">&mdash;</option>
<?php
	$all = get_zones_array();

	foreach($all as $zone) {
		$selected = ($zone['permalink'] == $my_column['box_zone']) ? 'selected="selected"' : '';
		echo '<option '.$selected.' value="'.$zone['permalink'].'">'.$zone['title'].'</option>';
	}

?>
</select>
</div>
</p>
	<p><?php echo _L('template_mark'); ?><br />
		<div style=" overflow:auto;width: 200px; border: 1px solid black; padding: 3px;">&lt;!&gt;<?php echo strtoupper($my_column['permalink']); ?></div>
	</p>
  <p><?php echo _L('border_color'); ?><br />
		<input onchange="javascript: orbx_carrier(this, document.column_form.column_border);" style="width: 200px;" name="column_border" type="hidden" id="column_border" value="<?php echo $box_style['border']; ?>" maxlength="200" />
		<div id="column_border_preview" ondblclick="javascript: try{RichTextDisplayColorPicker();}catch(e){}" style=" overflow:auto;width: 200px; border: 1px solid black; padding: 3px;"></div>
	</p>
  <p><?php echo _L('bg_color'); ?><br />
		<input onchange="javascript: orbx_carrier(this, document.column_form.column_background);" style="width: 200px;" name="column_background" type="hidden" id="column_background" value="<?php echo $box_style['background']; ?>" maxlength="200" />
		<div id="column_background_preview" ondblclick="javascript:try {RichTextDisplayColorPicker();} catch(e){}" style=" overflow:auto;width: 200px; border: 1px solid black; padding: 3px;"></div>
	</p>

	  <p><?php echo _L('css_id'); ?><br />
		<div style=" overflow:auto;width: 200px; border: 1px solid black; padding: 3px;">div#<?php echo $my_column['permalink']; ?></div>
	</p>

</div>

<div class="sidebar_subprop" id="res_css" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/list.gif) no-repeat; height: 22px;"
><a href="javascript:void(null);" onclick="javascript: sh('res_css_container');"><?php echo _L('css_style'); ?></a></div>

<div id="res_css_container" style="display:none;">

<?php

	$css_file = DOC_ROOT . '/site/gfx/site.css';
	include DOC_ROOT . '/orbicon/3rdParty/css/cssparser.php';
	$css = new cssparser();
	$css->parsestr(file_get_contents($css_file));

	if(isset($_POST['save_style'])) {
		$id = $_POST['css_id'];
		$css->css[$id]['color'] = $_POST['font_color'];

		if($_POST['font'] != 'default') {
			$css->css[$id]['font-family'] = $_POST['font'];
		}

		$css->css[$id]['font-size'] = $_POST['font_size'];
		if(!empty($_POST['bg_color'])) {
			$css->css[$id]['background-color'] = $_POST['bg_color'];
		}

		if($_POST['border_style'] != 'none') {
			$css->css[$id]['border-width'] = $_POST['border_width'];
			$css->css[$id]['border-style'] = $_POST['border_style'];
			$css->css[$id]['border-color'] = $_POST['border_color'];
		}

		chmod_unlock($css_file);
		$r = fopen($css_file, 'wb');
		if(fwrite($r, $css->getcss()) === false) {
			$orbx_log->ewrite('could not save css style in ' . $css_file,__LINE__,__FUNCTION__);
		}
		fclose($r);
		chmod_lock($css_file);
		$css->clear();
		$css->parsestr(file_get_contents($css_file));
	}

 $box_body = $css->css['#' . $my_column['permalink']];
?>

<fieldset><legend><?php echo _L('content'); ?></legend>
<form method="post" action="">
<input type="hidden" id="css_id" name="css_id" value="#<?php echo $my_column['permalink']; ?>" />
<p><label for="font"><?php echo _L('font_type'); ?></label><br />
<select id="font" name="font">
	<option value="" selected="selected">&mdash;</option>
	<?php echo print_select_menu(array('default', 'Arial, Helvetica, sans-serif', '\'Times New Roman\', Times, serif', '\'Courier New\', Courier, monospace', 'Georgia, \'Times New Roman\', Times, serif', 'Verdana, Arial, Helvetica, sans-serif', 'Geneva, Arial, Helvetica, sans-serif'), $box_body['font-family']); ?>
</select></p>
<p><label for="font_color"><?php echo _L('font_color'); ?></label><br />
<input type="text" id="font_color" name="font_color" value="<?php echo $box_body['color']; ?>" /></p>
<p><label for="font_size"><?php echo _L('font_size'); ?></label><br />
<input type="text" id="font_size" name="font_size" value="<?php echo $box_body['font-size']; ?>" /></p>
<p><label for="bg_color"><?php echo _L('bg_color'); ?></label><br />
<input type="text" id="bg_color" name="bg_color" value="<?php echo $box_body['background-color']; ?>" /></p>
<p><?php echo _L('border_style'); ?><br />
<ul>
	<li><label for="border_width"><?php echo _L('border_width'); ?>:</label> <input type="text" id="border_width" name="border_width" value="<?php echo $box_body['border-width']; ?>" /></li>
	<li><label for="border_style"><?php echo _L('border_line'); ?>:</label>
	<select id="border_style" name="border_style">
		<option value="" selected="selected">&mdash;</option>
		<?php echo print_select_menu(array('none', 'dotted', 'dashed', 'solid', 'double', 'groove', 'ridge', 'inset', 'outset'), $box_body['border-style']); ?>
	</select></li>
	<li><label for="border_color"><?php echo _L('border_color'); ?>:</label> <input type="text" id="border_color" name="border_color" value="<?php echo $box_body['border-color']; ?>" /></li>
</ul>
</p>
<input type="submit" id="save_style" name="save_style" value="<?php echo _L('save'); ?>" />
</form>
</fieldset>

</div>

<?php
	}
?>