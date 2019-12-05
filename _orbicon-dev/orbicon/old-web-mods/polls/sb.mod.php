<div class="sidebar_subprop" id="res_poll_list" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/list.gif) no-repeat; height: 22px;"
><a href="javascript:void(null);" onclick="javascript: sh('res_poll_list_container');"><?php echo _L('poll_list'); ?></a></div>

<div id="res_poll_list_container">

<div id="polls_items">
<p>
<strong><?php echo _L('sort'); ?></strong><br />
<input name="poll_items_sort_by" type="radio" value="alpha" id="poll_sort_alpha" onclick="javascript:__update_polls_items_list('<?php echo ORBX_SITE_URL; ?>/orbicon/modules/polls/admin.polls_items.list.php', this.value);" /> <label for="poll_sort_alpha">A &mdash; Z</label><br />
<input name="poll_items_sort_by" type="radio" value="date" id="poll_sort_date" checked="checked" onclick="javascript:__update_polls_items_list('<?php echo ORBX_SITE_URL; ?>/orbicon/modules/polls/admin.polls_items.list.php', this.value);" /> <label for="poll_sort_date"><?php echo _L('date'); ?></label><br />
<input name="poll_items_sort_by" type="radio" value="zone" id="poll_sort_zone" onclick="javascript:__update_polls_items_list('<?php echo ORBX_SITE_URL; ?>/orbicon/modules/polls/admin.polls_items.list.php', this.value);" /> <label for="poll_sort_zone"><?php echo _L('zone'); ?></label>
</p>
	<div id="polls_items_table">
	<?php
		$polls->build_polls_items();
	?>
	</div>
	<div style="clear: both;">&nbsp;</div>
</div>

<div id="past_polls_preview"></div>

</div>

<div class="sidebar_subprop" id="res_poll_date" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/date-range.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_poll_date_container');"><?php echo _L('poll_date_range'); ?></a></div>

<div id="res_poll_date_container"  style="display:none;">

			<div id="live_date_preview_start"><?php echo date($_SESSION['site_settings']['date_format'], $start_date); ?></div>
			<div id="live_date_preview_end"><?php echo date($_SESSION['site_settings']['date_format'], $end_date); ?></div>

		<div id="cal1Container"></div>
	<div style="margin-left:auto;margin-right:auto;text-align:center;width:180px;clear:both">
		<p><button id="date_picker_button" type="button" onclick="javascript:__polls_update_live_date('<?php echo ORBX_SITE_URL; ?>/orbicon/modules/polls/admin.polls.live_date.php', orbx_dual_cal.getSelectedDates());"><?php echo _L('select'); ?></button></p>
	</div>


</div>

<div class="sidebar_subprop" id="res_properties" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/properties.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_properties_container');"><?php echo _L('properties'); ?></a></div>

<div id="res_properties_container"  style="display:none;">

<p><label for="poll_zone"><strong><?php echo _L('zone'); ?></strong></label><br />
                <select name="poll_zone" id="poll_zone" onchange="javascript: orbx_carrier(this, document.polls_form.poll_zone);" onblur="javascript: orbx_carrier(this, document.polls_form.poll_zone);">
				<option></option>
				<optgroup label="<?php echo _L('pick_poll_zone'); ?>">
					<option value="all" <?php if($my_poll['zone'] == 'all') echo 'selected="selected"'; ?>><?php echo _L('all_pages'); ?></option>
					<?php
						$all = get_zones_array();

						foreach($all as $value) {
							$selected = ($value['permalink'] == $my_poll['zone']) ? 'selected="selected"' : '';
					?>
					<option value="<?php echo $value['permalink']; ?>"<?php echo $selected; ?>><?php echo $value['title']; ?></option>
					<?php
						}
					?>
				</optgroup>
				</select> <a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/zones"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/layers.png" /> <?php echo _L('edit'); ?></a>
            </p>

<p>
				<strong><?php echo _L('locked_results'); ?></strong><br />
				<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/lock.png" /> <input type="radio"  onchange="javascript: orbx_carrier(this, document.polls_form.locked_view);" name="locked_view" value="1" id="locked_yes" <?php if($my_poll['locked_view'] == 1) echo 'checked="checked"'; ?> /> <label for="locked_yes"><?php echo _L('yes'); ?></label><br />
				<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/lock_open.png" /> <input type="radio" onchange="javascript: orbx_carrier(this, document.polls_form.locked_view);" name="locked_view" value="0" id="locked_no" <?php if($my_poll['locked_view'] == 0) echo 'checked="checked"'; ?> /> <label for="locked_no"><?php echo _L('no'); ?></label>
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
		$css->css[$id]['font-family'] = $_POST['font'];
		$css->css[$id]['font-size'] = $_POST['font_size'];
		$css->css[$id]['background-color'] = $_POST['bg_color'];
		$css->css[$id]['border-width'] = $_POST['border_width'];
		$css->css[$id]['border-style'] = $_POST['border_style'];
		$css->css[$id]['border-color'] = $_POST['border_color'];

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

	$poll_title = $css->getsection('.poll_title');

?>

<fieldset><legend><?php echo _L('title'); ?></legend>
<form method="post" action="">
<input type="hidden" id="css_id" name="css_id" value=".poll_title" />
<p><label for="font"><?php echo _L('font_type'); ?></label><br />
<select id="font" name="font">
	<option></option>
	<?php echo print_select_menu(array('default', 'Arial, Helvetica, sans-serif', '\'Times New Roman\', Times, serif', '\'Courier New\', Courier, monospace', 'Georgia, \'Times New Roman\', Times, serif', 'Verdana, Arial, Helvetica, sans-serif', 'Geneva, Arial, Helvetica, sans-serif'), $poll_title['font-family']); ?>
</select></p>
<p><label for="font_color"><?php echo _L('font_color'); ?></label><br />
<input type="text" id="font_color" name="font_color" value="<?php echo $poll_title['color']; ?>" /></p>
<p><label for="font_size"><?php echo _L('font_size'); ?></label><br />
<input type="text" id="font_size" name="font_size" value="<?php echo $poll_title['font-size']; ?>" /></p>
<p><label for="bg_color"><?php echo _L('bg_color'); ?></label><br />
<input type="text" id="bg_color" name="bg_color" value="<?php echo $poll_title['background-color']; ?>" /></p>
<p><?php echo _L('border_style'); ?><br />
<ul>
	<li><label for="border_width"><?php echo _L('border_width'); ?>:</label> <input type="text" id="border_width" name="border_width" value="<?php echo $poll_title['border-width']; ?>" /></li>
	<li><label for="border_style"><?php echo _L('border_line'); ?>:</label>
	<select id="border_style" name="border_style">
		<option></option>
		<?php echo print_select_menu(array('none', 'dotted', 'dashed', 'solid', 'double', 'groove', 'ridge', 'inset', 'outset'), $poll_title['border-style']); ?>
	</select></li>
	<li><label for="border_color"><?php echo _L('border_color'); ?>:</label> <input type="text" id="border_color" name="border_color" value="<?php echo $poll_title['border-color']; ?>" /></li>
</ul>
</p>
<input type="submit" id="save_style" name="save_style" value="<?php echo _L('save'); ?>" />
</form>
</fieldset>

</div>