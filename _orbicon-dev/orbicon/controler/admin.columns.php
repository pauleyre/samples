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

	if(isset($_GET['delete_col'])) {
		$orbicon_x->delete_column($_GET['delete_col']);
	}
	$orbicon_x->add_column();

?>

<script type="text/javascript" src="./orbicon/controler/gzip.server.php?file=/orbicon/3rdParty/scriptaculous/lib/prototype.js&amp;<?php echo ORBX_BUILD; ?>"></script>
<script type="text/javascript" src="./orbicon/3rdParty/scriptaculous/src/scriptaculous.js?<?php echo ORBX_BUILD; ?>"></script>

<script type="text/javascript"><!-- // --><![CDATA[

	var __orbicon_server_name = '<?php echo $_SERVER['SERVER_NAME']; ?>';

	function __sortable_onload() {
		//try {
			Sortable.create('navigation_list', { onUpdate : updateOrder });
		//} catch(e) {}
	}

	function updateOrder() {
		__navigation_update_list(Sortable.serialize('navigation_list'), '<?php echo ORBX_SITE_URL; ?>/orbicon/controler/admin.nav.update.php?credentials=<?php echo get_ajax_id(); ?>')
	}

	YAHOO.util.Event.addListener(window,"load",__sortable_onload);

// ]]></script>
<?php

	$_GET['menu'] = (isset($_GET['menu'])) ? $_GET['menu'] : 'v';

	$opcije = '<option value="">&mdash;</option><option class="orbicon_new_parent" value="orbicon_new_parent">'._L('column').'...</option><optgroup label="'._L('subcolumn').'...">';
	global $dbc;
	$r = $dbc->_db->query(sprintf('	SELECT 		*
									FROM 		'.TABLE_COLUMNS.'
									WHERE 		(menu_name = %s) AND
												(language = %s)
									ORDER BY 	sort', $dbc->_db->quote($_GET['menu']), $dbc->_db->quote($orbicon_x->ptr)));
	$a = $dbc->_db->fetch_assoc($r);

	while($a) {
		$selected = ($_GET['parent'] == $a['permalink']) ? ' selected="selected"' : '';
		$_parent = (empty($a['parent'])) ? '' : ' ['.$a['parent'].']';
		$opcije .= sprintf('<option value="%s"%s>%s%s</option>', $a['permalink'], $selected, $a['title'], $_parent);
		$a = $dbc->_db->fetch_assoc($r);
	}

	$opcije .= '</optgroup>';

	$checked[$_GET['menu']] = 'checked="checked"';

	// styles

	$_style['h'] = ($_GET['menu'] == 'h') ? 'orbx_columns_image_current' : 'orbx_columns_image';
	$_style['v'] = ($_GET['menu'] == 'v') ? 'orbx_columns_image_current' : 'orbx_columns_image';
	$_style['box'] = ($_GET['menu'] == 'box') ? 'orbx_columns_image_current' : 'orbx_columns_image';
	$_style['hidden'] = ($_GET['menu'] == 'hidden') ? 'orbx_columns_image_current' : 'orbx_columns_image';

?>
<br />
<fieldset><legend><?php echo _L('select_a_menu'); ?></legend>
<div class="typo_chooser">
	<input <?php echo $checked['h']; ?> name="menu_type" type="radio" value="h" id="h_menu_radio" onclick="redirect('<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/columns&menu=h');" /> <label for="h_menu_radio"><?php echo _L('horizontal'); ?></label><br />
	<label for="h_menu_radio"><img onmouseover="javascript: YAHOO.util.Dom.setStyle(this, 'opacity', 1);" onmouseout="javascript:YAHOO.util.Dom.setStyle(this, 'opacity', .5);" class="<?php echo $_style['h']; ?>" src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/horizontal-column.gif" alt="<?php echo _L('horizontal'); ?>" title="<?php echo _L('horizontal'); ?>" /></label>
</div>

<div class="typo_chooser">
	<input <?php echo $checked['v']; ?> name="menu_type" type="radio" value="v" id="v_menu_radio" onclick="redirect('<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/columns&menu=v');" /> <label for="v_menu_radio"><?php echo _L('vertical'); ?></label><br />
	<label for="v_menu_radio"><img onmouseover="javascript: YAHOO.util.Dom.setStyle(this, 'opacity', 1);" onmouseout="javascript:YAHOO.util.Dom.setStyle(this, 'opacity', .5);" class="<?php echo $_style['v']; ?>" src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/vertical-column.gif" alt="<?php echo _L('vertical'); ?>" title="<?php echo _L('vertical'); ?>" /></label>
</div>

<div class="typo_chooser">
	<input <?php echo $checked['box']; ?> name="menu_type" type="radio" value="box" id="box_menu_radio" onclick="redirect('<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/columns&menu=box');" /> <label for="box_menu_radio"><?php echo _L('boxes'); ?></label><br />
	<label for="box_menu_radio"><img onmouseover="javascript: YAHOO.util.Dom.setStyle(this, 'opacity', 1);" onmouseout="javascript:YAHOO.util.Dom.setStyle(this, 'opacity', .5);" class="<?php echo $_style['box']; ?>" src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/boxes.gif" alt="<?php echo _L('boxes'); ?>" title="<?php echo _L('boxes'); ?>" /></label>
</div>

<div class="typo_chooser">
	<input <?php echo $checked['hidden']; ?> name="menu_type" type="radio" value="hidden" id="hidden_menu_radio" onclick="redirect('<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/columns&menu=hidden');" /> <label for="hidden_menu_radio"><?php echo _L('internal'); ?></label><br />
	<label for="hidden_menu_radio"><img onmouseover="javascript: YAHOO.util.Dom.setStyle(this, 'opacity', 1);" onmouseout="javascript:YAHOO.util.Dom.setStyle(this, 'opacity', .5);" class="<?php echo $_style['hidden']; ?>" src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/internal-pages.gif" alt="<?php echo _L('internal'); ?>" title="<?php echo _L('internal'); ?>" /></label>
</div>
<br />
</fieldset>

<p class="clean">
	<strong><?php echo _L('organize_menu'); ?></strong>
	<?php echo $orbicon_x->navigation_verti_menu(); ?>
</p>
<div style="height: 1%;"></div>