<?php

global $dbc;

if((isset($_GET['edit']) || isset($_GET['new'])) && $_GET['goto'] != 'category'){

?>
<div class="sidebar_subprop browser" id="res_content" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/database-browser.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_content_container');"><?php echo _L('db_content'); ?></a></div>

<div id="res_content_container">

<div class="toolbar-picker">
			<a href="javascript:void(null);" onclick="javascript:switch_mini_browser('venus', '', 0, 0);"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/picture.png" alt="image-tool-picker" width="16" height="16" border="0" /> <?php echo _L('images'); ?></a> |
			<a href="javascript:void(null);" onclick="javascript:switch_mini_browser('mercury', '', 0, 0);"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/ul-file-picker.png" alt="file-tool-picker" width="16" height="16" border="0" /> <?php echo _L('data'); ?></a>
		</div>
		<div id="mini_browser_container"></div>

</div>

<?php

}
else if($_GET['goto'] == 'settings') {
?>
	<div class="sidebar_subprop browser" id="res_content" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/database-browser.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_content_container');"><?php echo _L('db_content'); ?></a></div>

<div id="res_content_container">

<div class="toolbar-picker">
			<a href="javascript:void(null);" onclick="javascript:switch_mini_browser('magister', '', 0, 0);"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/ul-text-category-filter.png" alt="text-tool-picker" width="16" height="16" border="0" /> <?php echo _L('texts'); ?></a>
		</div>
		<div id="mini_browser_container"></div>

</div>

<?php
}
else {

	require_once DOC_ROOT.'/orbicon/modules/infocentar/class/category.class.php';
	$c = new Category();

	// * retrieve number of questions inside current category
	$quest_unsorted = $c->get_items_num(0);

	if(isset($_GET['delete']) && $_GET['delete'] != ''){
		$c->remove_category($_GET['delete']);
	}


?>

<div class="sidebar_subprop" id="res_zones_list" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/list.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('category_list');"><?php echo _L('ic-category-title'); ?></a></div>

	<div id="category_list">
		<ul id="ic_cat_listing">
			<li>
				<strong><a href="?category=0&amp;<?php echo $orbicon_x->ptr; ?>=orbicon/mod/infocentar"><?php echo _L('unsorted'); ?></a></strong>
				<a href="?category=0&amp;<?php echo $orbicon_x->ptr; ?>'=orbicon/mod/infocentar">(<?php echo $quest_unsorted['total']; ?>)</a>
			</li>


<?php

	// * get full list of categories
	$categories = $c->get_all_categories();


	$i = 1;

	while($cat_item = $dbc->_db->fetch_array($categories)){

		// * retrieve number of questions inside current category
		$quest_num = $c->get_items_num($cat_item['id']);

		$cat_param = ($_GET['goto'] == 'category') ? 'goto=category&amp;edit=category&amp;id='.$cat_item['id'] : '';

		$delCat = ($_GET['goto'] == 'category') ? '<a href="?'.$orbicon_x->ptr.'=orbicon/mod/infocentar&amp;goto=category&amp;delete='.$cat_item['id'].'" onclick="javascript:return false;" onmousedown="javascript:if(window.confirm(\''._L('ic-del-cat').'\')) {window.location = this.href;}">
				<img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/delete.png" alt="'._L('delete').'" />
			</a>' : '';

		$high = ($i & 1) ? ' class="high"' : '';

		$move = ($_GET['goto'] == 'category') ? ' style="cursor: move;"' : '';

		echo '
		<li'.$high.$move.' id="sort_'.$cat_item['id'].'">
			'.$delCat.'
			<a href="?category='.$cat_item['id'].'&amp;'.$cat_param.'&amp;'.$orbicon_x->ptr.'=orbicon/mod/infocentar" title="'.$cat_item['sortnum'].'">'.$cat_item['title'].'</a>
			<a href="?category='.$cat_item['id'].'&amp;'.$cat_param.'&amp;'.$orbicon_x->ptr.'=orbicon/mod/infocentar">('.$quest_num['total'].')</a>
		</li>
		';

		$i++;

	}

	echo '</ul></div>';

	if($_GET['goto'] == 'category'){

		echo '
		<!-- reordering part -->
		<script type="text/javascript"><!-- // --><![CDATA[


			Sortable.create("ic_cat_listing",
								{
									onUpdate: 	function()
												{
													new Ajax.Request("'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/infocentar&goto=category",
																		{
																			method: 	"post",
																			parameters: { reorderColumns: Sortable.serialize("ic_cat_listing") },
																			onComplete:	showResponse
																		}
																	);
												}
								}
							);

			function showResponse()
			{
				new Effect.Highlight("ic_cat_listing",{duration: 0.5});
				return true;
			}

			//YAHOO.util.Event.addListener(window,"load",__sortable_onload);

		// ]]></script>';
	}

}
?>