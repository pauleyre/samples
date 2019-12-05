<?php
/**
 * Sync servers GUI
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-07-01
 */

	require_once DOC_ROOT . '/orbicon/modules/servers/class.server.php';
	$my_server = new Server;
	$my_server->save();
	$my_server->delete($_GET['del_server']);
	$my_server_props = $my_server->load_properties($_GET['server']);

	// by default this setting is on
	if(!isset($_GET['server'])) {
		$my_server_props['request_orbx_auth'] = 1;
	}

?>
<form method="post" action="" id="servers">
	<input type="hidden" id="last_update" name="last_update" value="<?php echo $my_server_props['last_update']; ?>" />

	<div class="control_btn">
		<p>
			<button name="save_server" type="submit"><?php echo _L('save'); ?></button>
			<input <?php if(!isset($_GET['server'])) {echo 'disabled="disabled"';} ?> type="button" value="<?php echo _L('add_new') ?>" onclick="javascript: redirect(orbx_site_url + '/?' + __orbicon_ln + '=orbicon/mod/servers');"  />
		</p>
	</div>

	<fieldset>
	<legend><?php echo _L('server'); ?></legend>
	<div class="column">

	<p>
		<label for="server_uri"><?php echo _L('server_uri'); ?></label><br />
		<input name="server_uri" id="server_uri" type="text" value="<?php echo $_GET['server']; ?>" class="text_field" />
	</p>

	<p>
		<label for="request_orbx_auth"><?php echo _L('request_orbx_auth'); ?></label><br />
		<input name="request_orbx_auth" id="request_orbx_auth" type="checkbox" value="1" <?php if($my_server_props['request_orbx_auth'] == 1) echo 'checked="checked"'; ?> />
	</p>

	</div>

	<div class="column">

	<p>
		<label for="conn_type"><?php echo _L('conn_type'); ?></label><br />
		<select id="conn_type" name="conn_type">
			<optgroup label="<?php echo _L('pick_conn_type'); ?>">
				<option value="ftp" <?php if($my_server_props['conn_type'] == 'ftp') echo 'selected="selected"'; ?>>FTP</option>
				<option value="ssh2" <?php if($my_server_props['conn_type'] == 'ssh2') echo 'selected="selected"'; ?>>SSH2 (SFTP)</option>
			</optgroup>
		</select>
	</p>
	</div>

	<div class="column">

	<p>
		<label for="public_uri"><?php echo _L('public_uri'); ?></label><br />
		<input name="public_uri" id="public_uri" type="text" value="<?php echo $my_server_props['public_uri']; ?>" class="text_field" />
	</p>
	</div>

	</fieldset>

	<?php

		if($my_server_props['conn_type'] == 'ftp') {
	?>

	<!-- FTP -->
	<fieldset id="ftp_advanced"><legend><?php echo _L('advanced_ftp_settings'); ?></legend>
		<div class="column">
			<p>
				<label for="ftp_host"><?php echo _L('ftp_host'); ?></label><br />
				<input name="ftp_host" id="ftp_host" type="text" value="<?php echo $my_server_props['ftp_host']; ?>" class="text_field" />
			</p>
			<p>
				<label for="ftp_rootdir"><?php echo _L('ftp_rootdir'); ?></label><br />
				<input name="ftp_rootdir" id="ftp_rootdir" type="text" value="<?php echo $my_server_props['ftp_rootdir']; ?>" class="text_field" />
			</p>
		</div>

		<div class="column">
			<p>
				<label for="ftp_username"><?php echo _L('ftp_username'); ?></label><br />
				<input name="ftp_username" id="ftp_username" type="text" value="<?php echo $my_server_props['ftp_username']; ?>" class="text_field"  />
			</p>
			<p>
				<label for="ftp_password"><?php echo _L('ftp_pass'); ?></label><br />
				<input name="ftp_password" id="ftp_password" type="password" value="<?php if(!empty($my_server_props['ftp_password'])) echo str_repeat(' ', 10); ?>" class="text_field" />
			</p>
		</div>
	</fieldset>
	<?php
		}
		else if($my_server_props['conn_type'] == 'ssh2') {
	?>
	<!-- SSH2 -->
	<fieldset><legend><?php echo _L('advanced_ssh_settings'); ?></legend>

		<div class="column">
			<p><span><?php echo _L('ssh2_protocol'); ?></span></p>
			<br />

			<p>
				<label for="ssh2_host"><?php echo _L('ssh_host'); ?></label><br />
				<input name="ssh2_host" id="ssh2_host" type="text" value="<?php echo $my_server_props['ssh2_host']; ?>" class="text_field" />
			</p>
			<p>
				<label for="ssh2_rootdir"><?php echo _L('ssh_rootdir'); ?></label><br />
				<input name="ssh2_rootdir" id="ssh2_rootdir" type="text" value="<?php echo $my_server_props['ssh2_rootdir']; ?>" class="text_field" />
			</p>

			<p>
				<label for="ssh2_username"><?php echo _L('ssh_username'); ?></label><br />
				<input name="ssh2_username" id="ssh2_username" type="text" value="<?php echo $my_server_props['ssh2_username']; ?>" class="text_field"  />
			</p>
			<p>
				<label for="ssh2_pass"><?php echo _L('ssh_pass'); ?></label><br />
				<input name="ssh2_password" id="ssh2_password" type="password" value="<?php if(!empty($my_server_props['ssh2_password'])) echo str_repeat(' ', 10); ?>" class="text_field" />
			</p>

			<p>
				<label for="ssh2_port"><?php echo _L('ssh2_port'); ?></label><br />
				<input name="ssh2_port" id="ssh2_port" type="text" value="<?php echo $my_server_props['ssh2_port']; ?>" class="text_field" />
			</p>
			<p>
				<label for="ssh2_kex"><?php echo _L('ssh2_kex'); ?></label><br />
				<input name="ssh2_kex" id="ssh2_kex" type="text" value="<?php echo $my_server_props['ssh2_kex']; ?>" class="text_field" />
			</p>
			<p>
				<label for="ssh2_hostkey"><?php echo _L('ssh2_hostkey'); ?></label><br />
				<input name="ssh2_hostkey" id="ssh2_hostkey" type="text" value="<?php echo $my_server_props['ssh2_hostkey']; ?>" class="text_field" />
			</p>
			<p>
				<label for="ssh2_known_host_fingerprint"><?php echo _L('ssh2_known_host_fingerprint'); ?></label><br />
				<input name="ssh2_known_host_fingerprint" id="ssh2_known_host_fingerprint" type="text" value="<?php echo $my_server_props['ssh2_known_host_fingerprint']; ?>" class="text_field" />
			</p>
			<p>
				<label for="ssh2_fingerprint_flags"><?php echo _L('ssh2_fingerprint_flags'); ?></label><br />
				<select name="ssh2_fingerprint_flags" id="ssh2_fingerprint_flags">
					<option value="md5_hex" <?php if($my_server_props['ssh2_fingerprint_flags'] == 'md5_hex') echo 'selected="selected"'; ?>>MD5 + HEX</option>
					<option value="md5_raw" <?php if($my_server_props['ssh2_fingerprint_flags'] == 'md5_raw') echo 'selected="selected"'; ?>>MD5 + RAW</option>
					<option value="sha1_hex" <?php if($my_server_props['ssh2_fingerprint_flags'] == 'sha1_hex') echo 'selected="selected"'; ?>>SHA1 + HEX</option>
					<option value="sha1_raw" <?php if($my_server_props['ssh2_fingerprint_flags'] == 'sha1_raw') echo 'selected="selected"'; ?>>SHA1 + RAW</option>
				</select>
			</p>
			<p>
				<label for="ssh2_pubkeyfile"><?php echo _L('ssh2_pubkeyfile'); ?></label><br />
				<input name="ssh2_pubkeyfile" id="ssh2_pubkeyfile" type="text" value="<?php echo $my_server_props['ssh2_pubkeyfile']; ?>" class="text_field" />
			</p>
			<p>
				<label for="ssh2_privkeyfile"><?php echo _L('ssh2_privkeyfile'); ?></label><br />
				<input name="ssh2_privkeyfile" id="ssh2_privkeyfile" type="text" value="<?php echo $my_server_props['ssh2_privkeyfile']; ?>" class="text_field" />
			</p>
			<p>
				<label for="ssh2_passphrase"><?php echo _L('ssh2_passphrase'); ?></label><br />
				<input name="ssh2_passphrase" id="ssh2_passphrase" type="password" value="<?php if(!empty($my_server_props['ssh2_passphrase'])) echo str_repeat(' ', 10); ?>" class="text_field" />
			</p>
			<p>
				<label for="ssh2_hostbased_hostname"><?php echo _L('ssh2_hostbased_hostname'); ?></label><br />
				<input name="ssh2_hostbased_hostname" id="ssh2_hostbased_hostname" type="text" value="<?php echo $my_server_props['ssh2_hostbased_hostname']; ?>" class="text_field" />
			</p>
			<p>
				<label for="ssh2_hostbased_local_username"><?php echo _L('ssh2_hostbased_local_username'); ?></label><br />
				<input name="ssh2_hostbased_local_username" id="ssh2_hostbased_local_username" type="text" value="<?php echo $my_server_props['ssh2_hostbased_local_username']; ?>" class="text_field" />
			</p>
		</div>

		<div class="column">
			<p><span>Server &gt; Client</span></p>
			<br />

			<p>
				<label for="ssh2_server_to_client_crypt"><?php echo _L('ssh2_server_to_client_crypt'); ?></label><br />
				<input name="ssh2_server_to_client_crypt" id="ssh2_server_to_client_crypt" type="text" value="<?php echo $my_server_props['ssh2_server_to_client_crypt']; ?>" class="text_field" />
			</p>
			<p>
				<label for="ssh2_server_to_client_comp"><?php echo _L('ssh2_server_to_client_comp'); ?></label><br />
				<input name="ssh2_server_to_client_comp" id="ssh2_server_to_client_comp" type="text" value="<?php echo $my_server_props['ssh2_server_to_client_comp']; ?>" class="text_field" />
			</p>
			<p>
				<label for="ssh2_server_to_client_mac"><?php echo _L('ssh2_server_to_client_mac'); ?></label><br />
				<input name="ssh2_server_to_client_mac" id="ssh2_server_to_client_mac" type="text" value="<?php echo $my_server_props['ssh2_server_to_client_mac']; ?>" class="text_field" />
			</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p><strong>NOTE(*):</strong><br /><em><?php echo _L('ssh2_note'); ?></em></p>
		</div>

		<div class="column">
			<p><span>Client &gt; Server</span></p>
			<br />

			<p>
				<label for="ssh2_client_to_server_crypt"><?php echo _L('ssh2_client_to_server_crypt'); ?></label><br />
				<input name="ssh2_client_to_server_crypt" id="ssh2_client_to_server_crypt" type="text" value="<?php echo $my_server_props['ssh2_client_to_server_crypt']; ?>" class="text_field" />
			</p>
			<p>
				<label for="ssh2_client_to_server_comp"><?php echo _L('ssh2_client_to_server_comp'); ?></label><br />
				<input name="ssh2_client_to_server_comp" id="ssh2_client_to_server_comp" type="text" value="<?php echo $my_server_props['ssh2_client_to_server_comp']; ?>" class="text_field" />
			</p>
			<p>
				<label for="ssh2_client_to_server_mac"><?php echo _L('ssh2_client_to_server_mac'); ?></label><br />
				<input name="ssh2_client_to_server_mac" id="ssh2_client_to_server_mac" type="text" value="<?php echo $my_server_props['ssh2_client_to_server_mac']; ?>" class="text_field" />
			</p>
		</div>
	</fieldset>
	<?php
		}
	?>
	<div class="control_btn">
		<p>
			<button name="save_server" type="submit"><?php echo _L('save'); ?></button>
			<input type="button" <?php if(!isset($_GET['server'])) {echo 'disabled="disabled"';} ?> value="<?php echo _L('add_new') ?>" onclick="javascript: redirect(orbx_site_url + '/?' + __orbicon_ln + '=orbicon/mod/servers');"  />
		</p>
	</div>
	<div class="null_it">&nbsp;</div>
</form>