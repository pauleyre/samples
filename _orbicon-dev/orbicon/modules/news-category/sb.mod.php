<div class="sidebar_subprop" id="res_properties" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/properties.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_properties_container');"><?php echo _L('properties'); ?></a></div>

<div id="res_properties_container">

<p><strong><?php echo _L('display_order'); ?></strong>

<ol id="news_category_sort_list">
	<?php

	if(!empty($categories)) {
		foreach($categories as $value) {
			echo sprintf("<li id=\"sort_%s\">%s ".'<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/news-category&amp;edit_news_cat='.$value['permalink'].'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/wrench.png" alt="'._L('edit').'" title="'._L('edit').'" /></a> <a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/news-category&amp;delete_news_cat='.$value['permalink'].'" onclick="javascript:return false;" onmousedown="'.delete_popup($value['title']).'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/delete.png" alt="'._L('delete').'" title="'._L('delete').'" /></a></li>', $value['permalink'], $value['title']);
		}
	}
	?>
</ol>

</p>


</div>