<div class="sidebar_subprop" id="res_browser" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/database-browser.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_browser_container');"><?php echo _L('db_content'); ?></a></div>

<div id="res_browser_container">

	<div class="clean"></div>

				<div class="toolbar-picker">
					<a href="javascript:void(null);" onclick="javascript:switch_mini_browser('venus', '', 0, 0);"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/picture.png" alt="image-tool-picker" width="16" height="16" border="0" /> <?php echo _L('images'); ?></a> |
					<a href="javascript:void(null);" onclick="javascript:switch_mini_browser('magister', '', 0, 0);"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/ul-text-category-filter.png" alt="text-tool-picker" width="16" height="16" border="0" /> <?php echo _L('texts'); ?></a>
				</div>
				<div id="mini_browser_container"></div>
</div>


<div class="sidebar_subprop" id="res_intro_gfx" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/intro-graphic.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_intro_gfx_container');"><?php echo _L('intro_gfx'); ?></a></div>

<div id="res_intro_gfx_container" style="display:none;">


						<a href="javascript:void(null);" onclick="javascript:__news_clear_image();">
						<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/delete.png" alt="<?php echo _L('clear_gfx'); ?>" title="<?php echo _L('clear_gfx'); ?>" /></a>

					<?php echo _L('clear_gfx'); ?><br />
					<div id="news_image" style="padding: 3px;overflow:auto; height: auto; width: auto;background:#ffffff;border:1px solid #cccccc;"></div>


</div>

<div class="sidebar_subprop" id="res_pubdate" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/date-range.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_pubdate_container');"><?php echo _L('publish_date'); ?></a></div>

<div id="res_pubdate_container" style="display:none;">

		<?php echo _L('current_date'); ?> : <div id="live_date_preview"><?php echo date($_SESSION['site_settings']['date_format'], $live_date); ?></div>
		<div style="width: inherit;">
			<div id="cal1Container"></div>
		</div>
		<div style="text-align:center;width:180px;clear:both">
			<p><button id="date_picker_button" type="button" onclick="javascript:__news_update_live_date('<?php echo ORBX_SITE_URL; ?>/orbicon/modules/news/admin.news.live_date.php', orbx_calendar.getSelectedDates());"><?php echo _L('select'); ?></button></p>
		</div>
</div>


<div class="sidebar_subprop" id="res_props" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/properties.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_props_container');"><?php echo _L('properties'); ?></a></div>

<div id="res_props_container" style="display:none; padding: 0 0 0 10px;">
	<div>
		<p><?php echo _L('published'); ?><br />
		<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/accept.png" /> <input type="radio" name="live" value="1" id="live_yes" <?php if($my_news['live'] == 1) echo 'checked="checked"'; ?> onchange="javascript: orbx_carrier(this, document.news_form.live);" /> <label for="live_yes"><?php echo _L('yes'); ?></label>
		<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/cancel.png" /> <input type="radio" name="live" value="0" id="live_no" <?php if($my_news['live'] == 0) echo 'checked="checked"'; ?> onchange="javascript: orbx_carrier(this, document.news_form.live);" /> <label for="live_no"><?php echo _L('no'); ?></label>
		</p>
		<p><label for="news_category"><?php echo _L('category'); ?></label><br />
		<select name="news_category" id="news_category" onblur="javascript: orbx_carrier(this, document.news_form.news_category);" onchange="javascript: orbx_carrier(this, document.news_form.news_category);">
		<option value="" selected="selected">&mdash;</option>
			<optgroup><?php echo _L('category'); ?></optgroup>
		<?php
			$categories = $news->get_news_categories_array();

			if(!empty($categories)) {
				foreach($categories as $value) {
					$selected = (isset($_GET['edit']) && ($value['permalink'] == $my_news['category'])) ? " selected=\"selected\"" : '';
					echo sprintf("<option value=\"%s\"$selected>%s</option>", $value['permalink'], $value['title']);
				}
			}
		?>
		</select> <a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/mod/news-category"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/telephone.png" /> <?php echo _L('edit'); ?></a>
		</p>
		<p><?php echo _L('rss_extra'); ?><br />
		<input type="radio" name="rss_push" onchange="javascript: orbx_carrier(this, document.news_form.rss_push);" value="1" id="rss_push_yes" <?php if($my_news['rss_push'] == 1) echo 'checked="checked"'; ?> /> <label for="rss_push_yes"><?php echo _L('yes'); ?></label>
		<input type="radio" name="rss_push" onchange="javascript: orbx_carrier(this, document.news_form.rss_push);" value="0" id="rss_push_no" <?php if($my_news['rss_push'] == 0) echo 'checked="checked"'; ?> /> <label for="rss_push_no"><?php echo _L('no'); ?></label>
		</p>

		<p><label for="news_redirect2"><?php echo _L('redirect_to_url'); ?> <code>(<?php echo _L('for_example'); ?> http://www.hpb.hr)</code></label><br />
    	<input style="width:100%;" type="text" name="news_redirect" id="news_redirect2" value="<?php echo $my_news['redirect']; ?>" onchange="javascript: orbx_carrier(this, document.news_form.news_redirect);" /><br />

    	<label for="generated_pages">&mdash;<?php echo _L('or'); ?>&mdash;</label>
    	<div style="width:100%;overflow:auto; height:38px;">
    	<select id="generated_pages" onchange="javascript: var new_redirect = '<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '='; ?>' + this.value; document.news_form.news_redirect.value = new_redirect; $('news_redirect2').value = new_redirect;"><option value="" selected="selected">&mdash;</option>
    	<?php
    		$a_ = build_zones(array());
    		echo $a_[0];
    		unset($a_);
    	?>
    	</select>
    	</div>
	</p>

	</div>

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

	$news_title = $css->getsection('.orbx_news_title');
	$news_lead = $css->getsection('.orbx_news_lead');

?>

<fieldset><legend><?php echo _L('title'); ?></legend>
<form method="post" action="">
<input type="hidden" id="css_id" name="css_id" value=".orbx_news_title" />
<p><label for="font"><?php echo _L('font_type'); ?></label><br />
<select id="font" name="font">
	<option></option>
	<?php echo print_select_menu(array('default', 'Arial, Helvetica, sans-serif', '\'Times New Roman\', Times, serif', '\'Courier New\', Courier, monospace', 'Georgia, \'Times New Roman\', Times, serif', 'Verdana, Arial, Helvetica, sans-serif', 'Geneva, Arial, Helvetica, sans-serif'), $news_title['font-family']); ?>
</select></p>
<p><label for="font_color"><?php echo _L('font_color'); ?></label><br />
<input type="text" id="font_color" name="font_color" value="<?php echo $news_title['color']; ?>" /></p>
<p><label for="font_size"><?php echo _L('font_size'); ?></label><br />
<input type="text" id="font_size" name="font_size" value="<?php echo $news_title['font-size']; ?>" /></p>
<p><label for="bg_color"><?php echo _L('bg_color'); ?></label><br />
<input type="text" id="bg_color" name="bg_color" value="<?php echo $news_title['background-color']; ?>" /></p>
<p><?php echo _L('border_style'); ?><br />
<ul>
	<li><label for="border_width"><?php echo _L('border_width'); ?>:</label> <input type="text" id="border_width" name="border_width" value="<?php echo $news_title['border-width']; ?>" /></li>
	<li><label for="border_style"><?php echo _L('border_line'); ?>:</label>
	<select id="border_style" name="border_style">
		<option></option>
		<?php echo print_select_menu(array('none', 'dotted', 'dashed', 'solid', 'double', 'groove', 'ridge', 'inset', 'outset'), $news_title['border-style']); ?>
	</select></li>
	<li><label for="border_color"><?php echo _L('border_color'); ?>:</label> <input type="text" id="border_color" name="border_color" value="<?php echo $news_title['border-color']; ?>" /></li>
</ul>
</p>
<input type="submit" id="save_style" name="save_style" value="<?php echo _L('save'); ?>" />
</form>
</fieldset>

<fieldset><legend><?php echo _L('subtitle'); ?></legend>
<form method="post" action="">
<input type="hidden" id="css_id" name="css_id" value=".orbx_news_lead" />
<p><label for="font"><?php echo _L('font_type'); ?></label><br />
<select id="font" name="font">
	<option></option>
	<?php echo print_select_menu(array('default', 'Arial, Helvetica, sans-serif', '\'Times New Roman\', Times, serif', '\'Courier New\', Courier, monospace', 'Georgia, \'Times New Roman\', Times, serif', 'Verdana, Arial, Helvetica, sans-serif', 'Geneva, Arial, Helvetica, sans-serif'), $news_lead['font-family']); ?>
</select></p>
<p><label for="font_color"><?php echo _L('font_color'); ?></label><br />
<input type="text" id="font_color" name="font_color" value="<?php echo $news_lead['color']; ?>" /></p>
<p><label for="font_size"><?php echo _L('font_size'); ?></label><br />
<input type="text" id="font_size" name="font_size" value="<?php echo $news_lead['font-size']; ?>" /></p>
<p><label for="bg_color"><?php echo _L('bg_color'); ?></label><br />
<input type="text" id="bg_color" name="bg_color" value="<?php echo $news_lead['background-color']; ?>" /></p>
<p><?php echo _L('border_style'); ?><br />
<ul>
	<li><label for="border_width"><?php echo _L('border_width'); ?>:</label> <input type="text" id="border_width" name="border_width" value="<?php echo $news_lead['border-width']; ?>" /></li>
	<li><label for="border_style"><?php echo _L('border_line'); ?>:</label>
	<select id="border_style" name="border_style">
		<option></option>
		<?php echo print_select_menu(array('none', 'dotted', 'dashed', 'solid', 'double', 'groove', 'ridge', 'inset', 'outset'), $news_lead['border-style']); ?>
	</select></li>
	<li><label for="border_color"><?php echo _L('border_color'); ?>:</label> <input type="text" id="border_color" name="border_color" value="<?php echo $news_lead['border-color']; ?>" /></li>
</ul>
</p>
<input type="submit" id="save_style" name="save_style" value="<?php echo _L('save'); ?>" />
</form>
</fieldset>

</div>