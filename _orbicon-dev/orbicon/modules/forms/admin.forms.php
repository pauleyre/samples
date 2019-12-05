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
	require DOC_ROOT . '/orbicon/modules/forms/class.form.php';
	$form = new Form;
	$form->delete_form();
	$form->save_form();
	$my_form = $form->load_form();
	$a_ = $form->build_forms(explode('|', $my_form['adrbks']));
	$all = $form->get_form_array();


?>
<script type="text/javascript"><!-- // --><![CDATA[
	YAHOO.util.Event.addListener(window,"load",createListObjects);

	YAHOO.util.Event.addListener(window,"load",start_magister_mb);

	function start_magister_mb() {
		/* lead text */
		__magister_mini_input = '<?php echo $my_form['linked_text']; ?>';
		__magister_mini_url = '<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon&ajax_text_db&action=txt';
		__magister_mini_update_list();

		switch_mini_browser('magister', '', 0, 0);
	}

// ]]></script>
<form method="post" action="" onsubmit="javascript:selectAll(); return verify_title('form_title');" id="admin_contact_form">
	<input id="content_text" name="content_text" type="hidden" />
	<input name="save_form" type="submit" id="save_form" value="<?php echo _L('save'); ?>" />
	<input <?php if(!isset($_GET['edit'])) {echo 'disabled="disabled"';} ?> type="button" value="<?php echo _L('add_new') ?>" onclick="javascript: redirect('<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/forms'; ?>');"  />

	<p>
		<label for="form_title"><?php echo _L('title'); ?></label><br />
		<input onkeyup="javascript:get_permalink_exists(this.value, <?php echo intval($my_form['id']); ?>);" value="<?php echo $my_form['title']; ?>" id="form_title" name="form_title" type="text" />
	</p>

	<p>
		<label for="contact_templates"><?php echo _L('templates'); ?></label><br />
		<select id="contact_templates" name="contact_templates">
			<optgroup label="<?php echo _L('pick_template'); ?>">
				<option value="contact" <?php if($my_form['template'] == 'contact') echo 'selected="selected"'; ?>><?php echo _L('contact'); ?></option>
				<option value="job" <?php if($my_form['template'] == 'job') echo 'selected="selected"'; ?>><?php echo _L('cv'); ?></option>
				<option value="register" <?php if($my_form['template'] == 'register') echo 'selected="selected"'; ?>><?php echo _L('registration'); ?></option>
			</optgroup>
		</select>
	</p>

	<p>
		<fieldset><legend><?php echo _L('forms-optional'); ?></legend>
			<input type="radio" id="msg_type_on" name="msg_type" value="1" <?php if($my_form['msg_type'] == 1) echo 'checked="checked"'; ?> />
			<label for="msg_type_on"><?php echo _L('forms-on'); ?></label> <br />
			<input type="radio" id="msg_type_off" name="msg_type" value="0" <?php if($my_form['msg_type'] == 0) echo 'checked="checked"'; ?> />
			<label for="msg_type_off"><?php echo _L('forms-off'); ?></label>
		</fieldset>
	</p>

	<p>
		<fieldset>
			<legend><?php echo _L('pick_adr_books_form'); ?></legend>
			<div id="contact_address_book">
				<div class="column" style="width: 45%;">
					<p><label for="orbicon_list_all[]"><?php echo _L('adr_bks_outside_form'); ?></label> <a href="<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/address-book'; ?>"><img style="width:16px; height:16px; border:none;" src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/edit.png" alt="<?php echo _L('edit'); ?>" title="<?php echo _L('edit'); ?>" /> <?php echo _L('edit'); ?></a></p><br />
					<select name="orbicon_list_all[]" size="25" multiple="multiple" id="orbicon_list_all[]">
						<?php echo $a_[0]; ?>
					</select>
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
					<p><label for="orbicon_list_selected[]"><?php echo _L('adr_bks_in_form'); ?></label></p><br />
					<select name="orbicon_list_selected[]" size="25" multiple="multiple" id="orbicon_list_selected[]">
						<?php echo $a_[1]; ?>
					</select>
				</div>
			</div>
		</fieldset>
	</p>

<p>
<label for="content_text"><?php echo _L('content'); ?></label><br />
		<div id="news_content" style=" height: 150px; overflow:auto; width:auto;background:#ffffff;border:1px solid #cccccc;"></div>
</p><br />

<p>
	<label for="redirect_url"><?php echo _L('redirect_to_url'); ?></label><br />
	<input type="text" value="<?php echo $my_form['redirect']; ?>" id="redirect_url" name="redirect_url" />
</p>

	<input name="save_form" type="submit" id="save_form2" value="<?php echo _L('save'); ?>" />
	<input <?php if(!isset($_GET['edit'])) {echo 'disabled="disabled"';} ?> type="button" value="<?php echo _L('add_new') ?>" onclick="javascript: redirect('<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/news'; ?>');"  />
</form>