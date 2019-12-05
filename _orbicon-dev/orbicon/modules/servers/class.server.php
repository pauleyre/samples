<?php
/**
 * Sync servers class
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @version 1.20
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-02-13
 */

class Server
{
	function _get_properties()
	{
		// stri slash
		$_POST['public_uri'] = (substr($_POST['public_uri'], -1, 1) === '/') ? substr($_POST['public_uri'], 0, -1) : $_POST['public_uri'];

		$_server_props = array(
				'conn_type' => $_POST['conn_type'],
				'public_uri' => strtolower($_POST['public_uri']),
				'last_update' => intval($_POST['last_update']),
				'request_orbx_auth' => intval($_POST['request_orbx_auth']),
				// FTP
				'ftp_host' => $_POST['ftp_host'],
				'ftp_rootdir' => $_POST['ftp_rootdir'],
				'ftp_username' => $_POST['ftp_username'],
				'ftp_password' => $_POST['ftp_password'],
				// SSH2
				'ssh2_host' => $_POST['ssh2_host'],
				'ssh2_rootdir' => $_POST['ssh2_rootdir'],
				'ssh2_username' => $_POST['ssh2_username'],
				'ssh2_password' => $_POST['ssh2_password'],
				'ssh2_port' => intval($_POST['ssh2_port']),
				'ssh2_kex' => $_POST['ssh2_kex'],
				'ssh2_hostkey' => $_POST['ssh2_hostkey'],
				'ssh2_client_to_server_crypt' => $_POST['ssh2_client_to_server_crypt'],
				'ssh2_client_to_server_comp' => $_POST['ssh2_client_to_server_comp'],
				'ssh2_client_to_server_mac' => $_POST['ssh2_client_to_server_mac'],
				'ssh2_server_to_client_crypt' => $_POST['ssh2_server_to_client_crypt'],
				'ssh2_server_to_client_comp' => $_POST['ssh2_server_to_client_comp'],
				'ssh2_server_to_client_mac' => $_POST['ssh2_server_to_client_mac'],
				'ssh2_known_host_fingerprint' => $_POST['ssh2_known_host_fingerprint'],
				'ssh2_fingerprint_flags' => $_POST['ssh2_fingerprint_flags'],
				'ssh2_pubkeyfile' => $_POST['ssh2_pubkeyfile'],
				'ssh2_privkeyfile' => $_POST['ssh2_privkeyfile'],
				'ssh2_passphrase' => $_POST['ssh2_passphrase'],
				'ssh2_hostbased_hostname' => $_POST['ssh2_hostbased_hostname'],
				'ssh2_hostbased_local_username' => $_POST['ssh2_hostbased_local_username']
			);

		return $_server_props;
	}

	function load_properties($server)
	{
		if(empty($server)) {
			return NULL;
		}

		global $dbc;

		$q = sprintf('	SELECT 		id
						FROM 		'.TABLE_SYNC_SERVERS.'
						WHERE		(server = %s)
						LIMIT		1', $dbc->_db->quote($server));
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);
		$id = $a['id'];
		unset($q, $r, $a);

		$props = array();
		$props['server_id'] = $id;

		$_server_props = array_keys($this->_get_properties());

		foreach($_server_props as $setting) {
			$q = sprintf('	SELECT 		*
							FROM 		' . TABLE_SYNC_SERVERS_PROPS . '
							WHERE 		(server_id = %s) AND
										(setting = %s)
							LIMIT 		1', $dbc->_db->quote($id), $dbc->_db->quote($setting));

			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);

			$props[$a['setting']] = $a['value'];
		}
		return $props;
	}

	function delete($server)
	{
		if(empty($server)) {
			return NULL;
		}

		global $dbc, $orbicon_x;

		$id = $this->load_properties($server);
		$id = $id['server_id'];

		// delete server
		$q = sprintf('	DELETE
						FROM 		%s
						WHERE 		(server = %s)
						LIMIT 		1', TABLE_SYNC_SERVERS, $dbc->_db->quote($server));

		$dbc->_db->query($q);

		// delete server properties
		$q = sprintf('	DELETE
						FROM 		%s
						WHERE 		(server_id = %s)', TABLE_SYNC_SERVERS_PROPS, $dbc->_db->quote($id));

		$dbc->_db->query($q);

		redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/servers');
	}

	function save()
	{
		global $dbc, $orbicon_x;

		if(isset($_POST['save_server'])) {
			$server = $_POST['server_uri'];
			$_server_props = $this->_get_properties();

			// we're editing existing server
			if(!empty($_GET['server'])) {

				$id = $this->load_properties($_GET['server']);
				$id = $id['server_id'];

				$q = sprintf('	UPDATE 	' . TABLE_SYNC_SERVERS . '
								SET 	server=%s
								WHERE 	(server = %s)',
								$dbc->_db->quote($server), $dbc->_db->quote($_GET['server']));
				$dbc->_db->query($q);

				// loop through each setting and update it
				$_server_props = array_map('trim', $_server_props);
				foreach($_server_props as $setting => $value) {
					// check passwords
					if(($setting == 'ssh2_password') || ($setting == 'ssh2_passphrase') || ($setting == 'ftp_password')) {
						// we'll only update if we changed
						if($value != '') {
							$q = sprintf('	UPDATE 	' . TABLE_SYNC_SERVERS_PROPS . '
											SET 	value=%s
											WHERE 	(setting = %s) AND
													(server_id = %s)',
											$dbc->_db->quote($value), $dbc->_db->quote($setting), $dbc->_db->quote($id));
							$dbc->_db->query($q);
						}
					}
					// update others as usual
					else {
						$q = sprintf('	UPDATE 	' . TABLE_SYNC_SERVERS_PROPS . '
										SET 	value=%s
										WHERE 	(setting = %s) AND
												(server_id = %s)',
									$dbc->_db->quote($value), $dbc->_db->quote($setting), $dbc->_db->quote($id));
						$dbc->_db->query($q);
					}
					// add new settings if nonexist
					$this->_save_add_new_setting($setting, $value, $id);
				}
			}
			// we're adding a new one
			else {
				$q = sprintf('	INSERT
								INTO 		'.TABLE_SYNC_SERVERS.'
											(server)
								VALUES 		(%s)', $dbc->_db->quote($server));
				$dbc->_db->query($q);
				$id = $dbc->_db->insert_id();

				// loop through each setting and add it
				$_server_props = array_map('trim', $_server_props);
				foreach($_server_props as $setting => $value) {
					$q = sprintf('	INSERT INTO 	'.TABLE_SYNC_SERVERS_PROPS.'
													(server_id, setting,
													value)
									VALUES 			(%s, %s,
													%s)',
					$dbc->_db->quote($id), $dbc->_db->quote($setting),
					$dbc->_db->quote($value));
					$dbc->_db->query($q);
				}
			}

			redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/servers&server=' . urlencode($server));
		}
	}

	function _save_add_new_setting($setting, $value, $server_id)
	{
		global $dbc;
		// check if update status
		$q_c = sprintf('	SELECT 	value
							FROM 	'.TABLE_SYNC_SERVERS_PROPS.'
							WHERE 	(setting = %s) AND
									(server_id = %s)
							LIMIT 	1', $dbc->_db->quote($setting), $dbc->_db->quote($server_id));
		$r_c = $dbc->_db->query($q_c);
		$a_c = $dbc->_db->fetch_array($r_c);

		// UPDATE failed, try with INSERT
		if($a_c['value'] === NULL) {
			$q_new = sprintf('	INSERT INTO 	'.TABLE_SYNC_SERVERS_PROPS.'
												(server_id, setting,
												value)
								VALUES 			(%s, %s,
								%s)',
							$dbc->_db->quote($server_id), $dbc->_db->quote($setting),
							$dbc->_db->quote($value));
			$dbc->_db->query($q_new);
		}
	}

	function print_servers()
	{
		global $dbc, $orbicon_x;

		$q = '	SELECT 		*
				FROM 		'.TABLE_SYNC_SERVERS.'
				ORDER BY 	server ASC';
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {

			$list .= '	<li>
							<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/servers&amp;server='.$a['server'].'">
								<img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/server_edit.png" alt="'._L('edit').'" title="'._L('edit').'" /></a>
							<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/servers&amp;del_server='.$a['server'].'"onclick="javascript: return false;" onmousedown="'.delete_popup(addslashes($a['server'])).'">
								<img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/server_delete.png" alt="'._L('delete').'" title="'._L('delete').'" /></a>
							<strong>'.$a['server'].'</strong>
						</li>';
			$a = $dbc->_db->fetch_assoc($r);
		}

		return '<ol>'.$list.'</ol>';
	}

	/**
	 * prints a list of servers
	 *
	 * @return string	formatted HTML
	 */
	function print_sync_servers()
	{
		global $dbc, $orbicon_x;

		$q = '	SELECT 		*
				FROM 		'.TABLE_SYNC_SERVERS.'
				ORDER BY 	server ASC';
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);
		$i = 1;

		while($a) {
			$last_sync = $this->load_properties($a['server']);
			$last_sync = $last_sync['last_update'];
			$last_sync = (empty($last_sync)) ? 'N/A' : date('r', $last_sync);
			$style = (($i % 2) == 0) ? ' style="background:#ffffff;"' : '';

			$list .= '	<li '.$style.'>
							<input type="checkbox" value="ok" id="sync_server_id_'.$a['id'].'" name="sync_server_id_'.$a['id'].'" />
							<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/servers&amp;server='.$a['server'].'">
								<img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/server_edit.png" alt="'._L('edit').'" title="'._L('edit').'" /></a>
							<label for="sync_server_id_'.$a['id'].'">'.$a['server'].' ('._L('last_sync').': '.$last_sync.')</label>
						</li>';
			$a = $dbc->_db->fetch_assoc($r);
			$i ++;
		}

		return '<ol>'.$list.'</ol>';
	}

	/**
	 *	- Duplicate function clones existing server settings
	 */
	function duplicate($info)
	{
		global $dbc;

		$server_copy = 'Copy_of_' . $info['server'];

		// * Check if there is server with this name
		$check_server_sql = sprintf('	SELECT 		server
										FROM 		'. TABLE_SYNC_SERVERS .'
										WHERE 		(server = %s)', $dbc->_db->quote($server_copy));
		$check_server = $dbc->_db->query($check_server_sql);
		$check = $dbc->_db->num_rows($check_server);

		if($check > 0){
			$server_copy = 'Copy_of_' . $server_copy;
		}

		// * create Copy_of_server
		$clone_server = sprintf('	INSERT
									INTO 		'. TABLE_SYNC_SERVERS .'
									(server) 	VALUES (%s)', $dbc->_db->quote($server_copy));
		$dbc->_db->query($clone_server);

		// * Fetch last id of server copy
		/**
		 * @todo NOTICE: fix this, probably not best performance implementation, done because of short time
		 */
		$lastid_sql = sprintf('	SELECT 		id
								FROM 		'. TABLE_SYNC_SERVERS .'
								WHERE 		(server = %s)', $dbc->_db->quote($server_copy));
		$lastid_query = $dbc->_db->query($lastid_sql);
		$last_id = $dbc->_db->fetch_assoc($lastid_query);

		// * duplicate properties of current server
		$props_sql = sprintf('	SELECT 		*
								FROM 		'. TABLE_SYNC_SERVERS_PROPS .'
								WHERE 		(server_id = %s)', $dbc->_db->quote($info['id']));
		$props_query = $dbc->_db->query($props_sql);
		$props = $dbc->_db->fetch_assoc($props_query);

		while($props) {
			$sql = sprintf('	INSERT
								INTO 		'. TABLE_SYNC_SERVERS_PROPS .'
											(server_id, setting,
											value)
								VALUES 		(%s, %s,
											%s)',
							$dbc->_db->quote($last_id['id']),
							$dbc->_db->quote($props['setting']),
							$dbc->_db->quote($props['value'])
							);
			$dbc->_db->query($sql);

			$props = $dbc->_db->fetch_assoc($props_query);
		}
	}
}

?>