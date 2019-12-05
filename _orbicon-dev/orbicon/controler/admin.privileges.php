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
	$orbicon_x->delete_privilege();
	$orbicon_x->save_privilege();
	$my_privilege = $orbicon_x -> load_privilege();
	$all = $orbicon_x->get_privileges_array();
	$a_ = $orbx_mod->build_module_lists(explode('|', $my_privilege['modules']));

?>
<script type="text/javascript"><!-- // --><![CDATA[
	YAHOO.util.Event.addListener(window,"load",createListObjects);
// ]]></script>
<form method="post" action="" onsubmit="javascript: selectAll(); return verify_title('privilege_title');">
<p>
<input name="save_privilege" type="submit" id="save_privilege" value="<?php echo _L('save'); ?>" />
<input <?php if(!isset($_GET['edit'])) {echo 'disabled="disabled"';} ?> type="button" value="<?php echo _L('add_new') ?>" onclick="javascript: redirect('<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/privileges'; ?>');"  />
</p>
<p>
<label for="privilege_title"><?php echo _L('title'); ?></label><br />
<input type="text" style="width:50em; padding: 3px;" id="privilege_title" name="privilege_title" value="<?php echo $my_privilege['group_name']; ?>" />
</p>

<fieldset>
<legend><strong><?php echo _L('core_elements'); ?></strong></legend>
<table class="privileges_options">
  <tr>
    <td>
		<ul>
			<li><label for="flag_tab_content"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/wrench.png" /> <input id="flag_tab_content" name="flag_tab_content" type="checkbox" value="<?php echo ORBX_ACCESS_CONTENT; ?>" <?php echo $orbicon_x->check_privilege_option($my_privilege['tabs'], ORBX_ACCESS_CONTENT); ?> /> <?php echo _L('content'); ?></label></li>
			<li><br /></li>
			<li><label for="flag_col"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/chart_organisation.png" /> <input id="flag_col" name="flag_col" type="checkbox" value="<?php echo ORBX_ACCESS_COLUMNS; ?>" <?php echo $orbicon_x->check_privilege_option($my_privilege['content'], ORBX_ACCESS_COLUMNS); ?> /> <?php echo _L('columns'); ?></label></li>
		</ul>
	</td>




<td><ul><li><label for="flag_tab_db"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/database_gear.png" /> <input id="flag_tab_db" name="flag_tab_db" type="checkbox" value="<?php echo ORBX_ACCESS_DB; ?>" <?php echo $orbicon_x->check_privilege_option($my_privilege['tabs'], ORBX_ACCESS_DB); ?> /> <?php echo _L('db'); ?> </label></li>
	<li><br /></li>
	<li><label for="flag_magister"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/page_white_text.png" /> <input id="flag_magister" name="flag_magister" type="checkbox" value="<?php echo ORBX_ACCESS_MAGISTER; ?>" <?php echo $orbicon_x->check_privilege_option($my_privilege['db'], ORBX_ACCESS_MAGISTER); ?> /> <?php echo _L('texts'); ?></label>
      </li>
	  <li><label for="flag_venus"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/camera.png" /> <input id="flag_venus" name="flag_venus" type="checkbox" value="<?php echo ORBX_ACCESS_VENUS; ?>" <?php echo $orbicon_x->check_privilege_option($my_privilege['db'], ORBX_ACCESS_VENUS); ?> />    <?php echo _L('images'); ?></label>
      </li>      <li><label for="flag_mercury"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/folder_page.png" /> <input id="flag_mercury" name="flag_mercury" type="checkbox" value="<?php echo ORBX_ACCESS_MERCURY; ?>" <?php echo $orbicon_x->check_privilege_option($my_privilege['db'], ORBX_ACCESS_MERCURY); ?> />    <?php echo _L('data'); ?></label>
      </li>
	  </ul></td>




    <td>
	<ul>
		<li><label for="flag_tab_dynamic"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/plugin.png" /> <input id="flag_tab_dynamic" name="flag_tab_dynamic" type="checkbox" value="<?php echo ORBX_ACCESS_DYNAMIC; ?>" <?php echo $orbicon_x->check_privilege_option($my_privilege['tabs'], ORBX_ACCESS_DYNAMIC); ?> /> <?php echo _L('dynamic'); ?></label></li>
		<li><br /></li>
	</ul>
	</td>

	<td>
		<ul>
			<li><label for="flag_tab_tools"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/wrench.png" /> <input id="flag_tab_tools" name="flag_tab_tools" type="checkbox" value="<?php echo ORBX_ACCESS_TOOLS; ?>" <?php echo $orbicon_x->check_privilege_option($my_privilege['tabs'], ORBX_ACCESS_TOOLS); ?> /> <?php echo _L('tools'); ?></label></li>
			<li><br /></li>
			<li><label for="flag_gfxdir"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/world_edit.png" /> <input id="flag_gfxdir" name="flag_gfxdir" type="checkbox" value="<?php echo ORBX_ACCESS_GFXDIR; ?>" <?php echo $orbicon_x->check_privilege_option($my_privilege['tools'], ORBX_ACCESS_GFXDIR); ?> />    <?php echo _L('www_folder'); ?></label></li>
			<li><label for="flag_zone"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/layers.png" /> <input id="flag_zone" name="flag_zone" type="checkbox" value="<?php echo ORBX_ACCESS_ZONES; ?>" <?php echo $orbicon_x->check_privilege_option($my_privilege['tools'], ORBX_ACCESS_ZONES); ?> />      <?php echo _L('zones'); ?> </label>   </li>
		</ul>
	</td>





    <td>
	<ul>
	<li><label for="flag_tab_crm"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/group.png" /> <input id="flag_tab_crm" name="flag_tab_crm" type="checkbox" value="<?php echo ORBX_ACCESS_CRM; ?>" <?php echo $orbicon_x->check_privilege_option($my_privilege['tabs'], ORBX_ACCESS_CRM); ?> /> <?php echo _L('crm'); ?> </label></li>
	<li><br /></li>
	</ul></td>





    <td><ul>
	<li><label for="flag_tab_sett"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/cog.png" /> <input id="flag_tab_sett" name="flag_tab_sett" type="checkbox" value="<?php echo ORBX_ACCESS_SETTINGS; ?>" <?php echo $orbicon_x->check_privilege_option($my_privilege['tabs'], ORBX_ACCESS_SETTINGS); ?> /> <?php echo _L('settings'); ?> </label></li>
	<li><br /></li>
	<li><label for="flag_info"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/information.png" /> <input id="flag_info" name="flag_info" type="checkbox" value="<?php echo ORBX_ACCESS_INFO; ?>" <?php echo $orbicon_x->check_privilege_option($my_privilege['settings'], ORBX_ACCESS_INFO); ?> />    <?php echo _L('site_info'); ?></label></li>
      <li><label for="flag_admins"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/group_key.png" /> <input id="flag_admins" name="flag_admins" type="checkbox" value="<?php echo ORBX_ACCESS_ADMINS; ?>" <?php echo $orbicon_x->check_privilege_option($my_privilege['settings'], ORBX_ACCESS_ADMINS); ?> />    <?php echo _L('editors'); ?></label></li>
	   <li><label for="flag_privileges"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/group_error.png" /> <input id="flag_privileges" name="flag_privileges" type="checkbox" value="<?php echo ORBX_ACCESS_PRIVILEGES; ?>" <?php echo $orbicon_x->check_privilege_option($my_privilege['settings'], ORBX_ACCESS_PRIVILEGES); ?> /> <?php echo _L('privileges'); ?></label></li>
</ul></td>






	  <td>
	  <ul>
	  <li><label for="flag_tab_adv"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/cog_add.png" /> <input id="flag_tab_adv" name="flag_tab_adv" type="checkbox" value="<?php echo ORBX_ACCESS_SYSTEM; ?>" <?php echo $orbicon_x->check_privilege_option($my_privilege['tabs'], ORBX_ACCESS_SYSTEM); ?> /> <?php echo _L('system'); ?> </label></li>
	<li><br /></li>
	<li><label for="flag_adv_settings"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/cog_add.png" /> <input id="flag_adv_settings" name="flag_adv_settings" type="checkbox" value="<?php echo ORBX_ACCESS_ADV_SETTINGS; ?>" <?php echo $orbicon_x->check_privilege_option($my_privilege['system'], ORBX_ACCESS_ADV_SETTINGS); ?> /> <?php echo _L('settings'); ?></label></li>
	<li><label for="flag_updatec"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/server_go.png" /> <input id="flag_updatec" name="flag_updatec" type="checkbox" value="<?php echo ORBX_ACCESS_UPDATE_CENTER; ?>" <?php echo $orbicon_x->check_privilege_option($my_privilege['system'], ORBX_ACCESS_UPDATE_CENTER); ?> />    <?php echo _L('update_center'); ?></label></li>
	  </ul></td>


  </tr>
</table>
</fieldset>

<fieldset>
<legend><strong><?php echo _L('modules'); ?></strong></legend>
<table style="width: 100%;">
	<tr>
		<td><label for="orbicon_list_all[]"><strong><?php echo _L('disallowed_modules'); ?></strong></label></td>
		<td></td>
	    <td><label for="orbicon_list_selected[]"><strong><?php echo _L('allowed_modules'); ?></strong></label></td>
	</tr>
	<tr>
		<td rowspan="5" style="width: 33%;">
			<select name="orbicon_list_all[]" size="25" multiple="multiple" id="orbicon_list_all[]">
				<?php echo $a_[0]; ?>
			</select></td>
		<td style="height: 25%; width: 33%; text-align: center; vertical-align: middle;"><input style="width:45px; font-weight: bold;" type="button" value="&gt;&gt;" onclick="javascript: addAll();" /></td>
		<td rowspan="5" style="width: 33%;">
			<select name="orbicon_list_selected[]" size="25" multiple="multiple" id="orbicon_list_selected[]">
        		<?php echo $a_[1]; ?>
			</select></td>
	</tr>
	<tr>
		<td style="height: 25%; width: 33%; text-align: center; vertical-align: middle;"><input style="width:45px; font-weight: bold;" type="button" value="&gt;" onclick="javascript: addAttribute();" /></td>
	</tr>
	<tr>
		<td></td>
	</tr>
	<tr>
		<td style="height: 25%; width: 33%; text-align: center; vertical-align: middle;"><input style="width:45px; font-weight: bold;" type="button" value="&lt;" onclick="javascript: delAttribute();" /></td>
	</tr>
	<tr>
		<td style="height: 25%; text-align: center; vertical-align: middle;"><input style="width:45px; font-weight: bold;" type="button" value="&lt;&lt;" onclick="javascript: delAll();" /></td>
	</tr>
</table>
</fieldset>
<p>
	<input name="save_privilege" type="submit" id="save_privilege2" value="<?php echo _L('save'); ?>" />
	<input <?php if(!isset($_GET['edit'])) {echo 'disabled="disabled"';} ?> type="button" value="<?php echo _L('add_new') ?>" onclick="javascript: redirect('<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/privileges'; ?>');"  />
</p>
<div style="height: 1%;"></div>
</form>