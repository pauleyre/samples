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

	$badbot_log = OrbX_TornadoGuard::tg_get_blacklist_filename();

	if(isset($_POST['tg_submit'])) {

		$lines_to_remove = NULL;

		foreach($_POST as $key => $value) {
			if(strpos($key, 'flood_', 0) === 0) {
				if($_POST[$key] == 'off') {
					$ip = base64_decode(str_replace('flood_', '', $key));
					unlink(DOC_ROOT . '/site/mercury/flood/' . $ip);
				}
			}
			else if(strpos($key, 'badbot_', 0) === 0) {
				if($_POST[$key] == 'off') {
					$badbot = str_replace('badbot_', '', $key);
					$lines_to_remove[] = base64_decode($badbot);
				}
			}
		}

		if(empty($lines_to_remove) === false)
		{
			$log = file($badbot_log);
			$log = array_diff($log, $lines_to_remove);
			$log = array_map('trim', $log);
			$log = array_remove_empty($log);
			$log = implode("\n", $log);

			chmod_unlock($badbot_log);
			$r = fopen($badbot_log, 'wb');
			fwrite($r, $log);
			fclose($r);
			chmod_lock($badbot_log);
		}
	}

?>
<form method="post" action="">
<h3><?php echo _L('badbots'); ?></h3>
<ol>
<?php

	$log = file($badbot_log);
	if(empty($log)) {
		echo '<li>N/A</li>';
	}
	else {
		foreach($log as $log_line) {
			$id = base64_encode($log_line);
			$badbot_ip = explode(' ', $log_line);
			$badbot_ip = $badbot_ip[0];
			if($orbx_mod->validate_module('stats')) {
				$country_code = Statistics::get_country($badbot_ip);
				$country_name = Statistics::get_country_name($country_code);
				$img = '<img src="'.ORBX_SITE_URL.'/orbicon/gfx/flag_icons/'.strtoupper($country_code).'.gif" title="'.$country_name.'" alt="'.$country_name.'" />';
			}

			echo '<li><input type="checkbox" id="badbot_' . $id . '" name="badbot_' . $id . '" value="off" /><label for="badbot_'.$id.'">' . $img . ' ' . $log_line . '</label></li>';
		}
	}

	unset($country_code, $country_name);
?>
</ol>

<h3><?php echo _L('active_ips'); ?></h3>
<ol>
<?php

	$ips = glob(DOC_ROOT . '/site/mercury/flood/{*.*.*.*}*', GLOB_BRACE);
	// tornado guard rules
	$rules = array (
			10 => 10,		// rule 1 - maximum 10 requests in 10 secs
			60 => 30,		// rule 2 - maximum 30 requests in 60 secs
			300 => 50,		// rule 3 - maximum 50 requests in 300 secs
			3600 => 200		// rule 4 - maximum 200 requests in 3600 secs
	);

	foreach($ips as $ip) {
		$info = unserialize(file_get_contents($ip));
		$failed = OrbX_TornadoGuard::tg_rule_check($info, $rules);
		if($orbx_mod->validate_module('stats')) {
			$country_code = Statistics::get_country(basename($ip));
			$country_name = Statistics::get_country_name($country_code);
			$img = '<img src="'.ORBX_SITE_URL.'/orbicon/gfx/flag_icons/'.strtoupper($country_code).'.gif" title="'.$country_name.'" alt="'.$country_name.'" />';
		}

		if($failed === true) {
			$id = base64_encode(basename($ip));
			echo '<li><input type="checkbox" id="flood_' . $id . '" name="flood_' . $id . '" value="off" /> <label for="flood_'.$id.'">'.$img.' <img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/cancel.png" /> ' . basename($ip) . '</label></li>';
		}
		else {
			echo '<li><input type="checkbox" disabled="disabled" /> '.$img.' <img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/accept.png" /> ' . basename($ip) . '</li>';
		}
	}

?>
</ol>

<input type="submit" id="tg_submit" name="tg_submit" value="<?php echo _L('submit'); ?>" />

</form>