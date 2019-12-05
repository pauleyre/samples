
<div class="sidebar_subprop" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/sort-by.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_sort_container');"><?php echo _L('sort'); ?></a></div>

<div id="res_sort_container" style="padding: 0 0 0 5px;">

<p>
<strong><?php echo _L('sort'); ?></strong><br />
<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/font.png" /> <input name="news_items_sort_by" type="radio" value="alpha" id="news_sort_alpha" onclick="javascript:__update_news_items_list('<?php echo ORBX_SITE_URL; ?>/orbicon/modules/newsboard/admin.news_items.list.php', this.value);" /> <label for="news_sort_alpha">A &mdash; Z</label><br />
<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/date.png" /> <input name="news_items_sort_by" type="radio" value="date" id="news_sort_date" checked="checked" onclick="javascript:__update_news_items_list('<?php echo ORBX_SITE_URL; ?>/orbicon/modules/newsboard/admin.news_items.list.php', this.value);" /> <label for="news_sort_date"><?php echo _L('date'); ?></label><br />
<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/page_copy.png" /> <input name="news_items_sort_by" type="radio" value="cat" id="news_sort_cat" onclick="javascript:__update_news_items_list('<?php echo ORBX_SITE_URL; ?>/orbicon/modules/newsboard/admin.news_items.list.php', this.value);" /> <label for="news_sort_cat"><?php echo _L('category'); ?></label>
</p>
</div>

<div class="sidebar_subprop" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/properties.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_nwsprop_container');"><?php echo _L('properties'); ?></a></div>

<div id="res_nwsprop_container" style="padding: 0 0 0 5px; display:none;">

<form method="post" action="">
		<p>
			<input name="outsource_print" type="checkbox" id="outsource_print" value="<?php echo ORBX_CONTENT_PROP_PRINT_LINK; ?>" <?php echo $checked = ($settings->get_news_property_set($_SESSION['site_settings']['news_properties'], ORBX_CONTENT_PROP_PRINT_LINK)) ? 'checked="checked"' : ''; ?> /> <label for="outsource_print"><?php echo _L('print'); ?></label><br />
			<input name="outsource_pdf" type="checkbox" id="outsource_pdf" value="<?php echo ORBX_CONTENT_PROP_ALT_PDF; ?>" <?php echo $checked = ($settings->get_news_property_set($_SESSION['site_settings']['news_properties'], ORBX_CONTENT_PROP_ALT_PDF)) ? 'checked="checked"' : ''; ?>  /> <label for="outsource_pdf"><?php echo _L('pdf'); ?></label><br />
			<input name="outsource_txt" type="checkbox" id="outsource_txt" value="<?php echo ORBX_CONTENT_PROP_ALT_TXT; ?>" <?php echo $checked = ($settings->get_news_property_set($_SESSION['site_settings']['news_properties'], ORBX_CONTENT_PROP_ALT_TXT)) ? 'checked="checked"' : ''; ?>  /> <label for="outsource_txt"><?php echo _L('text_only'); ?></label><br />
			<input name="outsource_html" type="checkbox" id="outsource_html" value="<?php echo ORBX_CONTENT_PROP_ALT_HTML; ?>" <?php echo $checked = ($settings->get_news_property_set($_SESSION['site_settings']['news_properties'], ORBX_CONTENT_PROP_ALT_HTML)) ? 'checked="checked"' : ''; ?>  /> <label for="outsource_html"><?php echo _L('handheld'); ?></label><br />
		</p>

		<?php

			if($orbx_mod->validate_module('news-last-entry')) {

				require_once DOC_ROOT.'/orbicon/modules/news/class.news.admin.php';
				$news_cat = new News_Admin;
				$categories = $news_cat->get_news_categories_array();

		?>

		<p>
			<label><?php echo _L('show_last_news_from'); ?></label><br />
			<select id="show_last_news_from" name="show_last_news_from">
				<option value="" <?php if($_SESSION['site_settings']['show_last_news_from'] == '') {echo ' selected="selected"';} ?>><?php echo _L('all_categories'); ?></option>
				<optgroup label="<?php echo _L('single_category'); ?>">
				<?php
					if(!empty($categories)) {
						foreach($categories as $value) {
							$selected = ($_SESSION['site_settings']['show_last_news_from'] == $value['permalink']) ? ' selected="selected"' : '';
							echo '<option value="'.$value['permalink'].'">'.$value['title'].'</option>';
						}
					}
				?>
				</optgroup>
			</select>
		</p>

		<?php
			}
		?>

		<input type="submit" id="save_news_prop" name="save_news_prop" value="<?php echo _L('save'); ?>" />
</form>
</div>