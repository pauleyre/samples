<?php
/*-----------------------------------------------------------------------*
	Project........:	Orbicon X framework 2
	File...........:
	Version........:	1.0 (22/10/2006)
	Author.........:	Pavle Gardijan (pavle.gardijan@orbitum.net) - Orbitum d.o.o.
	Copyright......:	(c) 2006 Orbitum internet communications d.o.o. (www.orbitum.net)
	Created........:	01/07/2006
	Notes..........:
	Modified.......:
*-----------------------------------------------------------------------*/
	$grid = new Settings;
	$grid->save_news_category_scheme();
	$grid->build_site_settings(true);

	require_once DOC_ROOT.'/orbicon/modules/news/class.news.admin.php';
	$news_cat = new News_Admin;
	$news_cat->delete_news_category();
	$categories = $news_cat->get_news_categories_array();

	if(isset($_GET['edit_news_cat'])) {
		$permalink = $_GET['edit_news_cat'];
		$rows = intval($categories[$permalink]['scheme_rows']);
		$columns = intval($categories[$permalink]['scheme_columns']);
		$title = $categories[$permalink]['title'];
	}
	else {
		$rows = $_SESSION['site_settings']['news_category_grid_rows'];
		$columns = $_SESSION['site_settings']['news_category_grid_columns'];
		$title = null;
	}
?>
<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/controler/gzip.server.php?file=/orbicon/3rdParty/scriptaculous/lib/prototype.js&amp;<?php echo ORBX_BUILD; ?>"></script>
<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/3rdParty/scriptaculous/src/scriptaculous.js?<?php echo ORBX_BUILD; ?>"></script>
<script type="text/javascript"><!-- // --><![CDATA[

	YAHOO.util.Event.addListener(window,"load",__scheme_grid_preview);

 	function __scheme_grid_preview()
	{
		var j;
		var i;
		var count = 1;
		var eCurrentRow;
		var eCurrentCell;
		var sText;
		var oDocBody = document.getElementsByTagName("BODY").item(0);

		var rows = $('news_rows');
		var nRow = rows.options[rows.selectedIndex].getAttribute("value");

		var columns = $('news_columns');
		var nColumn = columns.options[columns.selectedIndex].getAttribute("value");

		var eTable = document.createElement("TABLE");
		var eTableBody = document.createElement("TBODY");

		for(j = 0; j < nRow; j ++) {
			eCurrentRow = document.createElement("TR");

			for(i = 0; i < nColumn; i ++) {
				eCurrentCell = document.createElement("TD");
				sText = document.createTextNode(count);
				eCurrentCell.appendChild(sText);
				eCurrentRow.appendChild(eCurrentCell);
				count ++;
			}

			eTableBody.appendChild(eCurrentRow);
		}

		eTable.appendChild(eTableBody);
		eTable.setAttribute("border", "1");
		eTable.setAttribute("class", "news_scheme_grid");

		var o = $('scheme_preview');
		o.innerHTML = '';

		o.appendChild(eTable);
	}

	function __news_cat_update_list(input, url)
	{
		sh_ind();
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
			//	alert(o.responseText)
				// * on-screen effect
				new Effect.Highlight('news_category_sort_list');
				sh_ind();
			}
		}

		var callback =
		{
		  success:handleSuccess
		};

		var request = YAHOO.util.Connect.asyncRequest('POST', url, callback, input);
	}

	function __news_cat_sortable_onload() {
		try {
			Sortable.create('news_category_sort_list', { onUpdate : updateNewsCatOrder });
		} catch(e) {}
	}

	function updateNewsCatOrder() {
		__news_cat_update_list(Sortable.serialize('news_category_sort_list'), '<?php echo ORBX_SITE_URL; ?>/orbicon/modules/news-category/admin.news.cat.update.php');
	}

	YAHOO.util.Event.addListener(window,"load",__news_cat_sortable_onload);

// ]]></script>
<style type="text/css">/*<![CDATA[*/

.news_scheme_grid td {
	padding: 1em;
	empty-cells: show;
	content: " ";
}

#news_category_sort_list li {
	background: #ffffff;
	cursor:move;
	border:1px solid #000000;
	width:250px;
	height: 2em;
}

/*]]>*/</style>
<form method="post" action="">
<p>
<input type="submit" id="save_scheme" name="save_scheme" value="<?php echo _L('save'); ?>" />
<input <?php if(!isset($_GET['edit_news_cat'])) {echo 'disabled="disabled"';} ?> type="button" value="<?php echo _L('add_new') ?>" onclick="javascript: redirect('<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/news-category'; ?>');"  />
</p>
<p>
<label for="new_news_cat"><?php echo _L('title'); ?><br /></label>
<input style="width:50em; padding: 3px;" type="text" id="new_news_cat" name="new_news_cat" value="<?php echo $title; ?>" />
</p>
<label for="news_rows"><?php echo _L('rows'); ?></label>
<select id="news_rows" name="news_rows" onchange="javascript:__scheme_grid_preview();">
	<optgroup label="<?php echo _L('pick_num_rows'); ?>">
	<?php
		$i = 1;
		while($i <= 5) {
			$selected = ($rows == $i) ? ' selected="selected"' : '';
			echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
			$i ++;
		}
	?>
	</optgroup>
</select>
<label for="news_columns"><?php echo _L('columns'); ?></label>
<select id="news_columns" name="news_columns" onchange="javascript:__scheme_grid_preview();">
	<optgroup label="<?php echo _L('pick_num_columns'); ?>">
	<?php
		$i = 1;
		while($i <= 5) {
			$selected = ($columns == $i) ? ' selected="selected"' : '';
			echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
			$i ++;
		}
	?>
	</optgroup>
</select><br>

<?php
	if(isset($_GET['edit_news_cat'])) {
		echo '<h3>'._L('category_specific_scheme').' &quot;'.$title.'&quot;</h3>';
	}
	else {
		echo '<h3>'._L('global_scheme').'</h3>';
	}
?>

<p>
<div id="scheme_preview"></div></p>
</form>
<div style="height: 1%;"></div>