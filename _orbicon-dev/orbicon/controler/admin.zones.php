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
	delete_zone();
	save_zone();
	$my_zone = load_zone();
	$a_ = build_zones(explode('|', $my_zone['column_list']), true);
	// used in a sidebar
	$all = get_zones_array();
?>
<script type="text/javascript"><!-- // --><![CDATA[
	YAHOO.util.Event.addListener(window, "load", createListObjects);
// ]]></script>
<form method="post" id="zones_form" name="zones_form" action="" onsubmit="javascript:selectAll(); return verify_title('zone_title');">
<input name="save_zone" type="submit" id="save_zone" value="<?php echo _L('save'); ?>" />
<input <?php if(!isset($_GET['edit'])) {echo 'disabled="disabled"';} ?> type="button" value="<?php echo _L('add_new'); ?>" onclick="javascript: redirect('<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/zones'; ?>');"  />
<p><label for="zone_title"><?php echo _L('title'); ?></label><br />
<input type="text" style="width:50em; padding: 3px;" id="zone_title" name="zone_title" value="<?php echo $my_zone['title']; ?>" />
</p>

<?php
	// This is temporary hack for divided forms
	// Alen Novakovic, 09.01.2007.
?>
<input id="locked" name="locked" type="hidden" value="<?php echo $my_zone['locked']; ?>" />
<input id="under_ssl" name="under_ssl" type="hidden" value="<?php echo $my_zone['under_ssl']; ?>" />
<?php // hack ENDS ?>

<p>
		<fieldset>
			<legend><?php echo _L('pick_pages_in_zone'); ?></legend>
			<div id="contact_address_book">
				<div class="column" style="width: 45%;">
					<p><label for="orbicon_list_all[]"><?php echo _L('pages_outside_zone'); ?></label></p><br />
					<div style="width:100%; height:100%; overflow:auto;">
					<select name="orbicon_list_all[]" size="25" multiple="multiple" id="orbicon_list_all[]">
						<?php echo $a_[0]; ?>
					</select>
					</div>
				</div>

				<div class="column" style="width: 10%;">
					<div class="controls">
						<p><input type="button" style="width:45px; font-weight: bold;" value="&gt;&gt;" onclick="javascript: addAll();" /></p>
						<p><input type="button" style="width:45px; font-weight: bold;" value="&gt;" onclick="javascript: addAttribute();" /></p>
						<p><input type="button" style="width:45px; font-weight: bold;" value="&lt;" onclick="javascript: delAttribute();" /></p>
						<p><input type="button" style="width:45px; font-weight: bold;" value="&lt;&lt;" onclick="javascript: delAll();" /></p>
					</div>
				</div>

				<div class="column">
					<p><label for="orbicon_list_selected[]"><?php echo _L('pages_in_zone'); ?></label></p><br />
					<div style="width:100%; height:100%; overflow:auto;">
					<select name="orbicon_list_selected[]" size="25" multiple="multiple" id="orbicon_list_selected[]">
						<?php echo $a_[1]; ?>
					</select>
					</div>
				</div>
			</div>
		</fieldset>
	</p>
<input name="save_zone" type="submit" id="save_zone2" value="<?php echo _L('save'); ?>" />
<input <?php if(!isset($_GET['edit'])) {echo 'disabled="disabled"';} ?> type="button" value="<?php echo _L('add_new') ?>" onclick="javascript: redirect('<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/zones'; ?>');"  />

</form>