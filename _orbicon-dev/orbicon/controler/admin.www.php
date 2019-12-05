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
	$www = new Settings;
	$www -> save_site_settings();
	$www -> build_site_settings();
	$www -> build_site_settings(true);
	$a_ = $orbicon_x->build_languages_menu(explode('|', $_SESSION['site_settings']['installed_languages']));
	$orbicon_x->export_language_file();
	$orbicon_x->import_language_file();
?>
<script type="text/javascript"><!-- // --><![CDATA[
	YAHOO.util.Event.addListener(window, "load", createListObjects);
// ]]></script>
<form method="post" onsubmit="javascript:selectAll();">
<fieldset id="www_information"><legend><?php echo _L('site_info'); ?></legend>
	<div class="main_left">
		<p><label for="main_site_title"><?php echo _L('site_title'); ?></label></p>
		<p><input name="main_site_title" type="text" id="main_site_title" value="<?php echo $_POST['main_site_title']; ?>" /></p>

		<p><label for="main_site_owner"><?php echo _L('site_owner'); ?></label></p>
		<p><input name="main_site_owner" type="text" id="main_site_owner" value="<?php echo $_POST['main_site_owner']; ?>" /></p>

		<p><label for="main_site_desc"><?php echo _L('site_desc'); ?></label></p>
		<p><input name="main_site_desc" type="text" id="main_site_desc" value="<?php echo $_POST['main_site_desc']; ?>" /></p>


		<p><label for="main_site_keywords"><?php echo _L('keywords'); ?></label></p>
		<p><textarea name="main_site_keywords" id="main_site_keywords" cols="55" rows="7"><?php echo $_POST['main_site_keywords']; ?></textarea></p>


	</div>

	<div style="margin: 15px 0 0 0;" class="main_right">
		<p><label for="#"><?php echo _L('site_url'); ?></label></p>
		<p><input disabled="disabled" type="text" value="<?php echo ORBX_SITE_URL; ?>" name="#" /></p>

		<p><label for="main_site_email"><?php echo _L('main_site_email'); ?></label></p>
		<p><input name="main_site_email" type="text" id="main_site_email" value="<?php echo $_POST['main_site_email']; ?>" /></p>

		<p>
			<label for="main_site_def_lng"><?php echo _L('main_lng'); ?></label> </p><p>
			<select name="main_site_def_lng" id="main_site_def_lng">
				<?php
					$installed = explode('|', $_SESSION['site_settings']['installed_languages']);
					foreach($installed as $value) {
						$selected = ($value == $_POST['main_site_def_lng']) ? 'selected="selected"' : '';
						// iso-639-1
						if(strlen($value) == 2) {
							$iso_639_1 = $orbicon_x->get_supported_languages_iso_639_1();
							$name = $iso_639_1[$value]['en'];
						}
						// iso-639-2
						else if(strlen($value) == 3) {
							$iso_639_2 = $orbicon_x->get_supported_languages_iso_639_2();
							$name = $iso_639_2[$value]['en'];
						}

						echo sprintf('<option value="%s" %s>%s</option>', $value, $selected, $name);
					}
					unset($installed, $iso_639_1, $iso_639_2);
				?>
			 </select>
		</p>

		<p><label for="main_site_metatags"><?php echo _L('extra_metatags'); ?></label></p>
		<p><textarea name="main_site_metatags" id="main_site_metatags" cols="55" rows="7"><?php echo $_POST['main_site_metatags']; ?></textarea></p>

	</div>
	<div class="clean"></div>
</fieldset>
<br />
<fieldset id="www_lang"><legend><?php echo _L('pick_available_lngs'); ?></legend>
	<div id="site_info_lang_left" style="width: 50%;">
		<label for="orbicon_list_all[]"><?php echo _L('available_lngs'); ?></label><br /><br />
		<select name="orbicon_list_all[]" size="25" multiple="multiple" id="orbicon_list_all[]">
			<?php echo $a_[0]; ?>
		</select>
	</div>
	<div id="site_info_lang_middle" style="width: 10%;">
		<input type="button" style="width:45px; font-weight: bold;" value="&gt;&gt;" onclick="javascript: addAll();" /><br /><br />
		<input type="button" style="width:45px; font-weight: bold;" value="&gt;" onclick="javascript: addAttribute();" /><br /><br />
		<input type="button" style="width:45px; font-weight: bold;" value="&lt;" onclick="javascript: delAttribute();" /><br /><br />
		<input name="button" style="width:45px; font-weight: bold;" type="button" onclick="javascript: delAll();" value="&lt;&lt;" />
	</div>
	<div id="site_info_lang_right" style="width:40%;overflow: auto;">
		<label for="orbicon_list_selected[]"><?php echo _L('installed_lngs'); ?></label><br /><br />
		<select name="orbicon_list_selected[]" size="25" multiple="multiple" id="orbicon_list_selected[]">
			<?php echo $a_[1]; ?>
		</select>
	</div>
</fieldset>
<p><button name="save_settings" type="submit"><?php echo _L('save'); ?></button></p>
<div style="height: 1%;"></div>
</form>