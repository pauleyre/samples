<?php


	// save settings
	$tg_settings = new Settings;
	$tg_settings->save_tg_settings();
	$tg_settings->build_site_settings(true);
	$tg_settings = null;

	$tgr = explode(',', $_SESSION['site_settings']['tg_rules']);

?>

<div class="sidebar_subprop" id="res_props" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/properties.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_props_container');"><?php echo _L('properties'); ?></a></div>

<div id="res_props_container" style="padding: 0 0 0 10px;">
<form method="post" action="">
	<p>
		<label for="ip_whitelist"><?php echo _L('ip_whitelist'); ?></label><br />
		<textarea id="ip_whitelist" name="ip_whitelist" style="width: 99%; height: 100px;"><?php echo $_SESSION['site_settings']['tg_whitelist']; ?></textarea>
	</p>

	<p>
		<label for="ip_blacklist"><?php echo _L('ip_blacklist'); ?></label><br />
		<textarea id="ip_blacklist" name="ip_blacklist" style="width: 99%; height: 100px;"><?php echo $_SESSION['site_settings']['tg_blacklist']; ?></textarea>
	</p>

	<p>
		<label for="tg_req_1"><?php echo _L('tg_rules'); ?></label>
		<table>
			<tr>
				<th><?php echo _L('tg_reqs'); ?></th>
				<th><?php echo _L('tg_secs'); ?></th>
			</tr>

			<?php

			$i = 1;

			while($i <= 5) {

				list($tg_request, $tg_seconds) = explode(':', $tgr[($i - 1)]);

			?>

			<tr>
				<td><input id="tg_req_<?php echo $i; ?>" name="tg_req_<?php echo $i; ?>" type="text" value="<?php echo $tg_request; ?>" /></td>
				<td><input id="tg_sec_<?php echo $i; ?>" name="tg_sec_<?php echo $i; ?>" type="text" value="<?php echo $tg_seconds; ?>" /></td>
			</tr>

			<?php

				$i ++;

			}

			?>

		</table>
	</p>

	<p>
		<input type="submit" id="save_tg_lists" name="save_tg_lists" value="<?php echo _L('submit'); ?>" />
	</p>

</form>
</div>
