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
	require_once DOC_ROOT.'/orbicon/modules/address-book/class.addrbk.php';

	$adrbk = new Address_Book;
	$adrbk->delete_address_book();
	$adrbk->mass_email_add();
	$adrbk->save_address_book();
	$my_adrbk = $adrbk->load_address_book();
	$a_ = $adrbk->build_adrbks(explode('|', $my_adrbk['column_list']));
	$all = $adrbk->get_adrbk_array();
?>
<script type="text/javascript"><!-- // --><![CDATA[
	YAHOO.util.Event.addListener(window,"load",createListObjects);
// ]]></script>
<form method="post" action="" onsubmit="javascript:selectAll(); return verify_title('adrbk_title');">
	<input type="hidden" id="current_adrbk" value="<?php echo $_GET['edit']; ?>" />
	<input name="save_adrbk" type="submit" id="save_adrbk" value="<?php echo _L('save'); ?>" />
	<input <?php if(!isset($_GET['edit'])) {echo 'disabled="disabled"';} ?> type="button" value="<?php echo _L('add_new') ?>" onclick="javascript: redirect(orbx_site_url + '/?' + __orbicon_ln + '=orbicon/mod/address-book');"  />
	<p>
		<label for="adrbk_title"><?php echo _L('title'); ?></label><br />
		<input type="text" style="width:50em; padding: 3px;" id="adrbk_title" name="adrbk_title" value="<?php echo $my_adrbk['title'];?>" />
	</p>

	<fieldset><legend><?php echo _L('pick_mails_in_adrbk'); ?></legend>
		<div id="adbook_left" style="width:35%;">
			<p><label for="orbicon_list_all[]"><?php echo _L('mails_outside_adrbk'); ?></label></p>
			<br />
			<select name="orbicon_list_all[]" size="25" multiple="multiple" id="orbicon_list_all[]">
				<?php echo $a_[0]; ?>
			</select>
		</div>
		<div id="adbook_middle" style="width:5%;">
			<p><input type="button" style="width:45px; font-weight: bold;" value="&gt;&gt;" onclick="javascript: addAll();" /></p>
			<p><input type="button" style="width:45px; font-weight: bold;" value="&gt;" onclick="javascript: addAttribute();" /></p>
			<p><input type="button" style="width:45px; font-weight: bold;" value="&lt;" onclick="javascript: delAttribute();" /></p>
			<p><input type="button" style="width:45px; font-weight: bold;" value="&lt;&lt;" onclick="javascript: delAll();" /></p>
		</div>
		<div id="adbook_right" style="width:40%;overflow: auto;">
			<p><label for="orbicon_list_selected[]"><?php echo _L('mails_in_adrbk'); ?></label></p>
			<br />
			<select name="orbicon_list_selected[]" size="25" multiple="multiple" id="orbicon_list_selected[]">
				<?php echo $a_[1]; ?>
			</select>
		</div>
	</fieldset>
	<br />
	<input name="save_adrbk" type="submit" id="save_adrbk2" value="<?php echo _L('save'); ?>" />
	<input type="button" <?php if(!isset($_GET['edit'])) {echo 'disabled="disabled"';} ?> value="<?php echo _L('add_new') ?>" onclick="javascript: redirect(orbx_site_url + '/?' + __orbicon_ln + '=orbicon/mod/address-book');"  />
</form>