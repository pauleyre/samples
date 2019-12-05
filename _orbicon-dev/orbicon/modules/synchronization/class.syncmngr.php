<?php
/**
 * Synchronization manager for Orbicon
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @subpackage Synchronization
 * @version 1.20
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-12-01
 */

// create our own function
if(!function_exists('ftp_chmod')) {
	function ftp_chmod($ftp_stream, $mode, $filename)
	{
		return ftp_site($ftp_stream, sprintf('CHMOD %o %s', $mode, $filename));
	}
}

class SyncManager
{
	var $temp_dir;
	var $temp_basedir;

	var $_sync_log;
	var $_server_type;
	var $_remote_server;
	var $_sync_status;
	var $_ftp_conn;
	var $_sql_tables;
	var $_mercury_files;
	var $_gfx_files;
	var $_venus_files;
	var $_misc_files;
	var $_templates_files;
	var $_remote_base_dir;
	var $_syncm_status;

	var $_ssh_conn;
	var $_is_ssh2;
	var $_sm_feedback_con_type;
	var $_sync_cache_file;
	var $_sftp_batch;
	var $_target_host_props;
	var $_remote_glob_vars;

	var $_md5_list_filepath;
	var $_md5_local_list;
	var $_md5_remote_list;

	/**
	 * PHP 4 compatibility
	 *
	 * @param string $target_host
	 */
	function syncmanager($target_host)
	{
		$this->__construct($target_host);
	}

	/**
	 * initiate synchronization process for host $target_host
	 *
	 * @param string $target_host
	 * @return bool
	 */
	function __construct($target_host)
	{
		global $orbx_log, $dbc;

		$orbx_log->dwrite('starting up synchronization subsystem', __LINE__, __FUNCTION__);
		$this->_syncm_status = 0;
		$this->_sync_log = array();
		$this->_remote_glob_vars = array();
		$this->_server_type = intval($_SESSION['site_settings']['syncm_type']);
		$this->_sync_cache_file = DOC_ROOT . '/site/mercury/sync.cache.log';
		$this->_md5_list_filepath = DOC_ROOT . '/site/mercury/sync.list.md5';

		// load server properties
		require_once DOC_ROOT . '/orbicon/modules/servers/class.server.php';
		$my_server = new Server;

		$this->_target_host_props = $my_server->load_properties($target_host);

		if(empty($this->_target_host_props)) {
			$this->_sync_log[] = '<span class="red">Server <strong>' . $target_host . '</strong> is not configured</span>';
			$orbx_log->ewrite($target_host . ' configuration is empty', __LINE__, __FUNCTION__);
			return false;
		}

		// * update last sync time property
		$q = sprintf('	UPDATE 	' . TABLE_SYNC_SERVERS_PROPS . '
						SET 	value=UNIX_TIMESTAMP()
						WHERE 	(setting = \'last_update\') AND
								(server_id = %s)',
					$dbc->_db->quote($this->_target_host_props['server_id']));
		$dbc->_db->query($q);

		unset($my_server);

		// only check for repos
		if(($this->_target_host_props === null) && ($this->_server_type == SYNC_MANAGER_TYPE_REPOSITORY)) {
			$this->_sync_log[] = '<span class="red">Cannot locate server <strong>' . $target_host . '</strong> in settings</span>';
			$orbx_log->ewrite($target_host . ' is not listed under receiver hosts. exiting...', __LINE__, __FUNCTION__);
			return false;
		}

		$this->_remote_server = ($this->_server_type == SYNC_MANAGER_TYPE_REPOSITORY) ? $target_host : ORBX_CLIENT_IP;

		$this->_remote_server = (substr($this->_remote_server, -1, 1) == '/') ? substr($this->_remote_server, 0, -1) : $this->_remote_server;
		// no scheme? try with http
		if(strpos($this->_remote_server, '://') === false) {
			$orbx_log->dwrite('could not find scheme for ' . $this->_remote_server . '. defaulting to http scheme', __LINE__, __FUNCTION__);
			$this->_remote_server = 'http://' . $this->_remote_server;
		}

		if($this->_server_type == SYNC_MANAGER_TYPE_REPOSITORY) {
			$this->_sync_log[] = date('r');
			$this->_sync_log[] = '<span class="blue">&raquo; Local host <strong>' . ORBX_SITE_URL . '</strong></span>';
			$this->_sync_log[] = '';
		}

		// is SSH2?
		$this->_is_ssh2 = (bool) ($this->_target_host_props['conn_type'] == 'ssh2');
		$this->_sm_feedback_con_type = ($this->_is_ssh2) ? 'SSH2' : 'FTP';

		// test for missing ssh2 extension
		if (!extension_loaded('ssh2')) {
			$prefix = (PHP_SHLIB_SUFFIX == 'dll') ? 'php_' : '';
			dl($prefix . 'ssh2.' . PHP_SHLIB_SUFFIX);
		}

		if($this->_is_ssh2 && !function_exists('ssh2_connect')) {
			$this->_sync_log[] = '<span class="red">SSH2 extension is not installed on <strong>' . ORBX_SITE_URL . '</strong></span>';
			$orbx_log->ewrite('SSH2 extension (www.php.net/ssh2) could not be located and loaded. exiting...', __LINE__, __FUNCTION__);
			return false;
		}
		else {
			if(!function_exists('ftp_connect')) {
				$this->_sync_log[] = '<span class="red">FTP extension is not installed on <strong>' . ORBX_SITE_URL . '</strong></span>';
				$orbx_log->ewrite('FTP extension (www.php.net/ftp) could not be located and loaded. exiting...', __LINE__, __FUNCTION__);
				return false;
			}
		}

		// setup global vars used by both FTP and SSH2
		if($this->_is_ssh2) {
			$this->_remote_glob_vars['host'] = $this->_target_host_props['ssh2_host'];
			$this->_remote_glob_vars['root_dir'] = $this->_target_host_props['ssh2_rootdir'];
			$this->_remote_glob_vars['username'] = $this->_target_host_props['ssh2_username'];
			$this->_remote_glob_vars['password'] = $this->_target_host_props['ssh2_password'];
		}
		else {
			$this->_remote_glob_vars['host'] = $this->_target_host_props['ftp_host'];
			$this->_remote_glob_vars['root_dir'] = $this->_target_host_props['ftp_rootdir'];
			$this->_remote_glob_vars['username'] = $this->_target_host_props['ftp_username'];
			$this->_remote_glob_vars['password'] = $this->_target_host_props['ftp_password'];
		}

		// setup remote root directory
		$ftp_root_dir = $this->_remote_glob_vars['root_dir'];
		$ftp_root_dir = (substr($ftp_root_dir, -1, 1) == '/') ? $ftp_root_dir : "$ftp_root_dir/";
		// this caused problems on Linux
		//$ftp_root_dir = ($ftp_root_dir[0] == '/') ? substr($ftp_root_dir, 1) : $ftp_root_dir;

		$this->_remote_base_dir = parse_url($this->_remote_server);
		$this->_remote_base_dir = $this->_remote_base_dir['path'];
		$this->_remote_base_dir = ($this->_remote_base_dir[0] == '/') ? substr($this->_remote_base_dir, 1) : $this->_remote_base_dir;
		$this->_remote_base_dir = (substr($this->_remote_base_dir, -1, 1) == '/') ? substr($this->_remote_base_dir, 0, -1) : $this->_remote_base_dir;

		$this->_remote_base_dir = $ftp_root_dir . $this->_remote_base_dir;

		// repeat stripping in case we had an empty value before for _remote_base_dir
		$this->_remote_base_dir = (substr($this->_remote_base_dir, -1, 1) == '/') ? substr($this->_remote_base_dir, 0, -1) : $this->_remote_base_dir;

		$this->_sql_tables = array(
			// orbicon
			TABLE_EDITORS,
			TABLE_COLUMNS,
			TABLE_SETTINGS,
			TABLE_ZONES,
			TABLE_EMAILS,
			TABLE_FORMS,
			TABLE_PRIVILEGES,
			TABLE_DESKTOP,
			TABLE_DESKTOP_RSS,
			TABLE_DESKTOP_WALLPAPER,
			// magister db
			MAGISTER_TITLES,
			MAGISTER_CONTENTS,
			MAGISTER_CATEGORIES,
			// venus db
			VENUS_IMAGES,
			VENUS_CATEGORIES,
			// mercury db
			MERCURY_FILES,
			MERCURY_CATEGORIES,
			MERCURY_COMMENTS
		);

		$this->_sql_tables = array_unique(array_merge($this->_sql_tables, $this->get_mod_sync_tables()));

		// get temp dir name
		$this->_get_temp_dirname();

		$login_result = false;

		if($this->_server_type == SYNC_MANAGER_TYPE_REPOSITORY) {
			$this->_sync_log[] = 'Accessing '.$this->_sm_feedback_con_type.' host <strong>' . $this->_remote_glob_vars['host'] . '</strong>';

			if(empty($this->_remote_glob_vars['host'])) {
				$this->_sync_log[] = '<span class="red">Host address name is not set</span>';
			}

			// FTP login
			if($this->_is_ssh2) {
				$orbx_log->dwrite('using SSH2 for sychronization', __LINE__, __FUNCTION__);
				$this->_sftp_batch = array();

				$ssh2_meth['client_to_server'] = array(
									'crypt' => $this->_target_host_props['ssh2_client_to_server_crypt'],
									'comp' => $this->_target_host_props['ssh2_client_to_server_comp'],
									'mac' => $this->_target_host_props['ssh2_client_to_server_mac']
									);

				$ssh2_meth['client_to_server'] = array_remove_empty($ssh2_meth['client_to_server']);

				$ssh2_meth['server_to_client'] = array(
									'crypt' => $this->_target_host_props['ssh2_server_to_client_crypt'],
									'comp' => $this->_target_host_props['ssh2_server_to_client_comp'],
									'mac' => $this->_target_host_props['ssh2_server_to_client_mac']
									);

				$ssh2_meth['server_to_client'] = array_remove_empty($ssh2_meth['server_to_client']);

				$ssh2_methods = array(
										'kex' => $this->_target_host_props['ssh2_kex'],
										'hostkey' => $this->_target_host_props['ssh2_hostkey'],
										'client_to_server' => $ssh2_meth['client_to_server'],
										'server_to_client' => $ssh2_meth['server_to_client']
								);

				$ssh2_methods = array_remove_empty($ssh2_methods);

				// connect
				$ssh2_callbacks = array('disconnect' => array($this, '_syncm_ssh2_disconnect'));
				$this->_ssh_conn = ssh2_connect($this->_remote_glob_vars['host'], $this->_target_host_props['ssh2_port'], $ssh2_methods, $ssh2_callbacks);

				if(!$this->_ssh_conn) {
					$this->_sync_log[] = '<span class="red">Failed to access ' . $this->_sm_feedback_con_type . ' host <strong>' . $this->_remote_glob_vars['host'] . ':'.$this->_target_host_props['ssh2_port'].'</strong></span>';
					$orbx_log->ewrite('could not connect to ' . $this->_remote_glob_vars['host'] . '. exiting...', __LINE__, __FUNCTION__);
					return false;
				}
				else {
					$this->_sync_log[] = '<span class="green">Connected to ' . $this->_sm_feedback_con_type . ' host <strong>' . $this->_remote_glob_vars['host'] . ':'.$this->_target_host_props['ssh2_port'].'</strong></span>';
					$orbx_log->dwrite('connected to ' . $this->_remote_glob_vars['host'], __LINE__,  __FUNCTION__);
				}

				// verify fingerprint
				if(!empty($this->_target_host_props['ssh2_known_host_fingerprint'])) {
					$_fingerprint_flags_settings = $this->_target_host_props['ssh2_fingerprint_flags'];
					$fingerprints = explode(',', $this->_target_host_props['ssh2_known_host_fingerprint']);
					$fingerprints = array_map('trim', $fingerprints);

					if($_fingerprint_flags_settings == 'md5_raw') {
						$fingerprint_flags = SSH2_FINGERPRINT_MD5 | SSH2_FINGERPRINT_RAW;

					}
					else if($_fingerprint_flags_settings == 'sha1_hex') {
						$fingerprint_flags = SSH2_FINGERPRINT_SHA1 | SSH2_FINGERPRINT_HEX;
						$fingerprints = array_map('sha1', $fingerprints);
					}
					else if($_fingerprint_flags_settings == 'sha1_raw') {
						$fingerprint_flags = SSH2_FINGERPRINT_SHA1 | SSH2_FINGERPRINT_RAW;
					}
					else {
						$fingerprint_flags = SSH2_FINGERPRINT_MD5 | SSH2_FINGERPRINT_HEX;
						$fingerprints = array_map('md5', $fingerprints);
					}

					$orbx_log->dwrite('comparing host fingerprints', __LINE__, __FUNCTION__);
					(string) $fingerprint = ssh2_fingerprint($this->_ssh_conn, $fingerprint_flags);

					if(!in_array($fingerprint, $fingerprints)) {
						$this->_sync_log[] = '<span class="red">SSH hostkey mismatch on <strong>' . $this->_remote_glob_vars['host'] . '</strong></span>';
						$orbx_log->ewrite('known host fingerprint mismatch. exiting...', __LINE__, __FUNCTION__);
						return false;
					}
				}

				// login via keys
				if(!empty($this->_target_host_props['ssh2_pubkeyfile'])) {

					if(!is_file($this->_target_host_props['ssh2_pubkeyfile'])) {
						$this->_sync_log[] = '<span class="red">Unable to access public keyfile</span>';
						$orbx_log->ewrite('public key ' . $this->_target_host_props['ssh2_pubkeyfile'] . ' could not be loaded', __LINE__, __FUNCTION__);
						return false;
					}

					if(!is_file($this->_target_host_props['ssh2_privkeyfile'])) {
						$this->_sync_log[] = '<span class="red">Unable to access private keyfile</span>';
						$orbx_log->ewrite('private key '.$this->_target_host_props['ssh2_privkeyfile'] . ' could not be loaded', __LINE__, __FUNCTION__);
						return false;
					}

					if(!empty($this->_target_host_props['ssh2_hostbased_hostname'])) {
						// Authenticate using a public hostkey
						$orbx_log->dwrite('SSH2 login method : public hostkey', __LINE__, __FUNCTION__);

						$login_result = ssh2_auth_hostbased_file(
								$this->_ssh_conn,
								$this->_remote_glob_vars['username'],
								$this->_target_host_props['ssh2_hostbased_hostname'],
								$this->_target_host_props['ssh2_pubkeyfile'],
								$this->_target_host_props['ssh2_privkeyfile'],
								$this->_target_host_props['ssh2_passphrase'],
								$this->_target_host_props['ssh2_hostbased_local_username']
							);
					}
					else {
						// Authenticate using a public key
						$orbx_log->dwrite('SSH2 login method : public key', __LINE__, __FUNCTION__);

						$login_result = ssh2_auth_pubkey_file(
								$this->_ssh_conn,
								$this->_remote_glob_vars['username'],
								$this->_target_host_props['ssh2_pubkeyfile'],
								$this->_target_host_props['ssh2_privkeyfile'],
								$this->_target_host_props['ssh2_passphrase']
							);
					}
				}
				// login via username / pass
				else {
					$orbx_log->dwrite('SSH2 login method : username / password', __LINE__, __FUNCTION__);
					$login_result = ssh2_auth_password($this->_ssh_conn, $this->_remote_glob_vars['username'], $this->_remote_glob_vars['password']);
				}
			}
			else {
				$orbx_log->dwrite('using FTP for sychronization', __LINE__, __FUNCTION__);
				$orbx_log->dwrite('connecting to ' . $this->_remote_glob_vars['host'], __LINE__, __FUNCTION__);

				$this->_ftp_conn = ftp_connect($this->_remote_glob_vars['host']);

				if(!$this->_ftp_conn) {
					$this->_sync_log[] = '<span class="red">Failed to access ' . $this->_sm_feedback_con_type . ' host <strong>' . $this->_remote_glob_vars['host'] . '</strong></span>';
					$orbx_log->ewrite('could not connect to '.$this->_remote_glob_vars['host'].'. exiting...', __LINE__, __FUNCTION__);
					return false;
				}
				else {
					$this->_sync_log[] = '<span class="green">Connected to ' . $this->_sm_feedback_con_type . ' host <strong>' . $this->_remote_glob_vars['host'] . '</strong></span>';
					$orbx_log->dwrite('connected to ' . $this->_remote_glob_vars['host'], __LINE__, __FUNCTION__);
				}

				$login_result = ftp_login($this->_ftp_conn, $this->_remote_glob_vars['username'], $this->_remote_glob_vars['password']);
			}

			if(!$login_result) {
				$this->_sync_log[] = '<span class="red">Failed to authorize ' . $this->_sm_feedback_con_type . ' user <strong>' . $this->_remote_glob_vars['username'] . '@' . $this->_remote_glob_vars['host'] . '</strong></span>';
				$orbx_log->ewrite('could not authorize user '.$this->_remote_glob_vars['username'] . ' on host '.$this->_remote_glob_vars['host'], __LINE__, __FUNCTION__);
				return false;
			}
			else {
				$this->_sync_log[] = '<span class="green">Authorized ' . $this->_sm_feedback_con_type . ' user <strong>' . $this->_remote_glob_vars['username'] . '@' . $this->_remote_glob_vars['host'] . '</strong></span>';
				$orbx_log->dwrite('authorized user ' . $this->_remote_glob_vars['username'] . ' on host ' . $this->_remote_glob_vars['host'], __LINE__, __FUNCTION__);
			}

			$ftp_temp_dir = $this->_remote_base_dir . '/site/mercury/' . $this->temp_basedir;

			// startup sftp for ssh2
			if($this->_is_ssh2) {
				$this->_ftp_conn = ssh2_sftp($this->_ssh_conn);
			}

			$_mkdir_loc = mkdir($this->temp_dir, 0777);

			if(!$_mkdir_loc) {
				$this->_sync_log[] = '<span class="red">Failed to create directory <strong>' . $this->temp_dir . '</strong> on <strong>' . ORBX_SITE_URL . '</strong></span>';
				$orbx_log->ewrite('could not create directory '. $this->temp_dir. ' on host '.ORBX_SITE_URL, __LINE__, __FUNCTION__);
				return false;
			}
			else {
				chmod_unlock($this->temp_dir, 0777);
			}

			clearstatcache();

			if(substr(sprintf('%o', fileperms($this->temp_dir)), -4) !== '0777') {
				$this->_sync_log[] = '<span class="red">Failed to set read/write permissions for <strong>' . $this->temp_dir . '</strong> on <strong>'.ORBX_SITE_URL.'</strong></span>';
				return false;
			}
		}

		$this->_syncm_status = 1;
		$orbx_log->dwrite('finished starting up synchronization subsystem', __LINE__, __FUNCTION__);
	}

	function _syncm_ssh2_disconnect($reason, $message)
	{
		global $orbx_log;
		$this->_sync_log[] = '<span class="red">'.$reason.' : ' . $message.'</span>';
		$orbx_log->ewrite('SSH2 disconnect : '.$reason.' : '.$message, __LINE__, __FUNCTION__);
	}

	function __destruct()
	{
		global $orbx_log;
		if($this->_syncm_status == 0) {
			$orbx_log->dwrite('skipping synchronization cleanup', __LINE__, __FUNCTION__);
			return false;
		}

		$orbx_log->dwrite('starting up synchronization cleanup', __LINE__, __FUNCTION__);

		// unlink MySQL cache files
		if(((string) $this->temp_dir !== '') && is_dir($this->temp_dir)) {
			$temp_files = glob($this->temp_dir . '/*');
			foreach($temp_files as $filename) {
				if($this->_is_valid_sync_file($filename)) {
					$orbx_log->dwrite('removing ' . $filename, __LINE__, __FUNCTION__);
					if(!unlink($filename)) {
						$orbx_log->ewrite('failed to remove ' . $filename, __LINE__, __FUNCTION__);
					}
					else {
						$orbx_log->dwrite('removed ' . $filename, __LINE__, __FUNCTION__);
					}
				}
				else {
					$orbx_log->dwrite('skipping removal of ' . $filename, __LINE__, __FUNCTION__);
				}
			}

			$orbx_log->dwrite('removing directory ' . $this->temp_dir, __LINE__, __FUNCTION__);
			if(!rmdir($this->temp_dir)) {
				$orbx_log->ewrite('failed to remove directory' . $this->temp_dir, __LINE__, __FUNCTION__);
			}
			else {
				$orbx_log->dwrite('removed directory ' . $this->temp_dir, __LINE__, __FUNCTION__);
			}
		}

		if($this->_server_type == SYNC_MANAGER_TYPE_REPOSITORY) {
			$orbx_log->dwrite('closing remote connection', __LINE__, __FUNCTION__);
			if(is_resource($this->_ftp_conn)) {
				if(!$this->_is_ssh2) {
					if(!ftp_close($this->_ftp_conn)) {
						$orbx_log->ewrite('failed to close remote connection', __LINE__, __FUNCTION__);
					}
					else {
						$orbx_log->dwrite('closed remote connection', __LINE__, __FUNCTION__);
					}
				}
				else {
					if(!ssh2_exec($this->_ssh_conn, 'exit')) {
						$orbx_log->ewrite('failed to close remote connection', __LINE__, __FUNCTION__);
					}
					else {
						$orbx_log->dwrite('closed remote connection', __LINE__, __FUNCTION__);
					}
				}
			}
			else {
				$orbx_log->ewrite('remote pointer is not a valid resource', __LINE__, __FUNCTION__);
			}
		}

		clearstatcache();
		if(is_file(DOC_ROOT . '/site/mercury/repos.ip')) {
			if(!unlink(DOC_ROOT . '/site/mercury/repos.ip')) {
				$orbx_log->ewrite('failed to remove repos.ip', __LINE__, __FUNCTION__);
			}
			else {
				$orbx_log->dwrite('removed repos.ip', __LINE__, __FUNCTION__);
			}
		}
		$orbx_log->dwrite('finished synchronization cleanup', __LINE__, __FUNCTION__);
	}

	function _get_temp_dirname()
	{
		if($this->_server_type == SYNC_MANAGER_TYPE_RECEIVER) {
			$this->temp_basedir = base64_decode($_REQUEST['tmp_dir']);
			$this->temp_dir = DOC_ROOT . '/site/mercury/' . $this->temp_basedir;
		}
		else if($this->_server_type == SYNC_MANAGER_TYPE_REPOSITORY) {

			// seed for PHP < 4.2.0
			srand((float) microtime() * 10000000);

			$this->temp_basedir = 'syncm_' . adler32(time() * rand());
			$this->temp_dir = DOC_ROOT . '/site/mercury/' . $this->temp_basedir;

			// file exists, recreate a name
			while(is_dir($this->temp_file)) {
				$this->temp_basedir = 'syncm_' . adler32(time() * rand());
				$this->temp_dir = DOC_ROOT . '/site/mercury/' . $this->temp_basedir;
			}
		}
	}

	/**
	 *  verify $server role as $type
	 *
	 * @param string $server
	 * @param int $type		SYNC_MANAGER_TYPE_*
	 * @return bool
	 */
	function _verify_server_role($server, $type)
	{
		if($type == SYNC_MANAGER_TYPE_RECEIVER) {
			// is $server in our list?
			if($_SESSION['site_settings']['syncm_server'] != $server) {
				return false;
			}

			// can we accept files?
			if($this->_server_type != SYNC_MANAGER_TYPE_RECEIVER) {
				return false;
			}
			return true;
		}
		else if($type == SYNC_MANAGER_TYPE_REPOSITORY) {
			// is $server in our list?
			if($_SESSION['site_settings']['syncm_server'] != $server) {
				return false;
			}

			// can we update files?
			if($this->_server_type != SYNC_MANAGER_TYPE_REPOSITORY) {
				return false;
			}
			return true;
		}
		return false;
	}

	function _sql_dump_export()
	{
		// silent fail
		if(!$this->_ftp_conn) {
			return false;
		}

		global $dbc, $orbx_log;

		$orbx_log->dwrite('exporting database', __LINE__, __FUNCTION__);

		foreach($this->_sql_tables as $table) {
			$dumpname = $this->temp_dir . '/' . $table . '.sqldmp';
			$query = sprintf('	SELECT 			*
								INTO OUTFILE 	%s
								FROM 			%s',
								$dbc->_db->quote($dumpname), $table);

			$dbc->_db->query($query);
			if(is_file($dumpname)) {
				$orbx_log->dwrite('successfully dumped table ' . $table . ' to ' . $dumpname, __LINE__, __FUNCTION__);
				$this->_sync_log[] = 'Exporting SQL table <strong>' . $table . '</strong> to <strong>' . $dumpname . '</strong>';
				$this->_sync_rewrite_uri($dumpname);
			}
			else {
				$orbx_log->dwrite('db server does not support SELECT INTO OUTFILE directive. performing selective dump', __LINE__, __FUNCTION__);
				$dumpname = DOC_ROOT . '/site/mercury/' . $this->temp_basedir . '.sql';

				clearstatcache();

				if(!is_file($dumpname)) {
					create_empty_file($dumpname);
				}

				// add password if we have one
				$_sql_pass = base64_decode(DB_PASS);
				$_sql_pass = (empty($_sql_pass)) ? '' : ' --password=' . $_sql_pass;

				// Dump ONLY tables from array $this->_sql_tables
				$tables = implode(' ', $this->_sql_tables);
				$sql_cmd = 'mysqldump -u ' . DB_USER . $_sql_pass . ' --opt ' . DB_NAME .' '. $tables.' > ' . $dumpname;

				system($sql_cmd);

				if(!is_file($dumpname)) {
					$this->_sync_log[] = '<span class="red">Could not export SQL tables into <strong>' . $dumpname . '</strong></span>';
					$orbx_log->ewrite('could not perform selective dump', __LINE__, __FUNCTION__);

				}
				else {
					$this->_sync_rewrite_uri($dumpname);
				}

				break;
			}
		}
		$orbx_log->dwrite('finished exporting database', __LINE__, __FUNCTION__);
	}

	function _sync_rewrite_uri($filename)
	{
		global $orbx_log;
		$orbx_log->dwrite('starting rewrite of public URIs for ' . $filename, __LINE__, __FUNCTION__);

		$tmp_filename = '';
		$failed_open = false;
		// open source
		$handle = fopen($filename, 'rb');
		// open temp
		chmod_unlock("$filename.tmp", 0666, false);
		$r = fopen("$filename.tmp", 'ab');

		// hmm, can't open temporary file handle -> try in mercury folder
		if(!$r) {
			$tmp_filename = DOC_ROOT . 'site/mercury/' . basename($filename) . '.tmp';
			$r = fopen($tmp_filename, 'ab');
			if($r) {
				$failed_open = true;
			}
			else {
				$orbx_log->ewrite('failed to rewrite public URIs for ', $filename, __LINE__, __FUNCTION__);
				return false;
			}
		}

		if($handle) {
			while(!feof($handle)) {
				$buffer = fgets($handle, 8192);

				$buffer = str_replace(
					array(
							ORBX_SITE_URL,
							SCHEME . '://' . DOMAIN_NO_WWW,
							strtoupper(ORBX_SITE_URL),
							strtoupper(SCHEME . '://' . DOMAIN_NO_WWW),
							strtolower(ORBX_SITE_URL),
							strtolower(SCHEME . '://' . DOMAIN_NO_WWW)),
					$this->_target_host_props['public_uri'],
					$buffer);

				fwrite($r, $buffer);
			}
			fclose($handle);
			fclose($r);

			if($failed_open) {
				chmod_lock($tmp_filename);
			}
			else {
				chmod_lock("$filename.tmp");
			}
			// delete old
			unlink($filename);
			// wait
			sleep(1);

			// rename temp
			if($failed_open) {
				rename($tmp_filename, $filename);
			}
			else {
				rename("$filename.tmp", $filename);
			}
		}

		// replace wrong uris
		/*$dumpfile = file_get_contents($filename);
		$dumpfile = str_replace(array(
			ORBX_SITE_URL,
			SCHEME . '://' . DOMAIN_NO_WWW,
			strtoupper(ORBX_SITE_URL),
			strtoupper(SCHEME . '://' . DOMAIN_NO_WWW),
			strtolower(ORBX_SITE_URL),
			strtolower(SCHEME . '://' . DOMAIN_NO_WWW)),
				$this->_target_host_props['public_uri'],
				$dumpfile);

		// save to file
		chmod_unlock($filename, 0666, false);
		$r = fopen($filename, 'wb');
		fwrite($r, $dumpfile);
		unset($dumpfile);
		fclose($r);
		chmod_lock($filename);*/
		$orbx_log->dwrite('finished rewrite of public URIs for ' . $filename, __LINE__, __FUNCTION__);
	}

	function _sql_dump_put()
	{
		clearstatcache();
		if(is_file(DOC_ROOT . '/site/mercury/'.$this->temp_basedir.'.sql')) {
			$dumpname = DOC_ROOT . '/site/mercury/'.$this->temp_basedir.'.sql';
			if($this->_is_ssh2) {
				$this->_update_file_ssh2($dumpname,  'site/mercury/'.$this->temp_basedir.'.sql');
			}
			else {
				$this->_update_file($dumpname, 'site/mercury/'.$this->temp_basedir.'.sql');
			}
			// remove
			unlink($dumpname);
		}
		else {
			foreach($this->_sql_tables as $table) {
				$dumpname = $this->temp_dir . '/' . $table . '.sqldmp';
				if($this->_is_ssh2) {
					$this->_update_file_ssh2($dumpname, 'site/mercury/' . $this->temp_basedir . '/' . $table . '.sqldmp');
				}
				else {
					$this->_update_file($dumpname, 'site/mercury/' . $this->temp_basedir . '/' . $table . '.sqldmp');
				}
			}
		}
	}

	function _sql_dump_import()
	{
		global $dbc, $orbx_log;

		foreach($this->_sql_tables as $table) {
			$dumpname = $this->temp_dir . '/' . $table . '.sqldmp';
			clearstatcache();
			if(is_file($dumpname)) {
				$query = sprintf('	LOAD DATA
									INFILE 			%s
									INTO TABLE 		%s',
									$dbc->_db->quote($dumpname), $table);
				$r = $dbc->_db->query($query);

				if($r !== false) {
					$this->_sync_log[] = 'Importing SQL table <strong>' . $table . '</strong> from <strong>' . $dumpname . '</strong>';
				}
				else {
					$this->_syncm_status = 0;
					$this->_sync_log[] = '<span class="red">Could not import SQL table <strong>' . $table . '</strong> from <strong>' . $dumpname . '</strong></span>';
				}
				unlink($dumpname);
			}
			else {
				$dumpname = DOC_ROOT . '/site/mercury/'.$this->temp_basedir.'.sql';
				$this->_sync_log[] = 'Importing SQL tables from <strong>' . $dumpname . '</strong>';

				clearstatcache();

				if(is_file($dumpname)) {
					// add password if we have one
					$_sql_pass = base64_decode(DB_PASS);
					$_sql_pass = (empty($_sql_pass)) ? '' : ' --password=' . $_sql_pass;
					$_cmd = 'mysql -u ' . DB_USER . $_sql_pass . ' ' . DB_NAME . ' < ' . $dumpname;

					system($_cmd);

					// reset to 0
					unlink($dumpname);
					file_put_contents($dumpname, '');

					/*$q = sprintf('	UPDATE 	'.TABLE_SETTINGS.'
									SET 	value = %s
									WHERE 	(setting = %s)',
									$dbc->_db->quote(SYNC_MANAGER_TYPE_RECEIVER), $dbc->_db->quote('syncm_type'));

					$dbc->_db->query($q);*/
				}
				else {
					$this->_syncm_status = 0;
					$this->_sync_log[] = '<span class="red">Could not import SQL tables from <strong>' . $dumpname . '</strong></span>';
				}
				break;
			}
		}

		// revert server status back to receiver or else it won't accept synchronization anymore
		$q = sprintf('	UPDATE 	'.TABLE_SETTINGS.'
						SET 	value = %s
						WHERE 	(setting = %s)',
						$dbc->_db->quote(SYNC_MANAGER_TYPE_RECEIVER), $dbc->_db->quote('syncm_type'));

		$dbc->_db->query($q);

		// remove temp folder
		rmdir($this->temp_dir);
	}

	function _files_dump_buildlist()
	{
		if(is_file($this->_sync_cache_file)) {
			if(is_readable($this->_sync_cache_file)) {
				$files = file($this->_sync_cache_file);

				foreach($files as $file) {
					if(strpos($file, '/site/mercury/') !== false) {
						$this->_mercury_files[] = $file;
					}
					else if(strpos($file, '/site/gfx/') !== false) {
						$this->_gfx_files[] = $file;
					}
					else if(strpos($file, '/site/venus/') !== false) {
						$this->_venus_files[] = $file;
					}
					else {
						$this->_misc_files[] = $file;
					}
				}
				// free memory
				unset($files, $file);
			}
		}
		else {
			$this->_mercury_files = glob(DOC_ROOT . '/site/mercury/*');
			$this->_gfx_files = glob(DOC_ROOT . '/site/gfx/*');
			$this->_venus_files = glob(DOC_ROOT . '/site/venus/*');
			// add manually google sitemaps
			$this->_misc_files[] = DOC_ROOT . '/sitemap.xml';
		}
	}

	function _mkdir($files, $dir)
	{


		foreach ($files as $file) {
			if(is_dir($file)) {

				$dirname = dirname($file);
				$dirname = str_replace("{$this->_remote_base_dir}/$dir/", '', $dirname);
				$dirname = $this->win_fixpath("{$this->_remote_base_dir}/$dir/$dirname");

				$create = ($this->_is_ssh2) ? ssh2_sftp_mkdir($this->_ftp_conn, $dirname) : ftp_mkdir($this->_ftp_conn, $dirname);

				if($create) {
					$this->_sync_log[] = '<span class="green">Succesfully created remote directory <strong>' . $dirname . '</strong></span>';
				}
				else {
					$this->_sync_log[] = '<span class="red">Failed to create remote directory <strong>' . $dirname . '</strong></span>';
				}

			}
		}
	}

	/**
	 * build import list for files from all site folders
	 *
	 */
	function _files_dump_import_files()
	{

		$create = ($this->_is_ssh2) ? ssh2_sftp_mkdir($this->_ftp_conn, "xxx") : ftp_mkdir($this->_ftp_conn, $dirname);

		// make sure we have all the directories
		$this->_import_dir($this->_mercury_files, 'site/mercury');
		/*$this->_import_dir($this->_gfx_files, 'site/gfx');
		$this->_import_dir($this->_venus_files, 'site/venus');
		$this->_import_dir($this->_misc_files, '');
		// other directories
		if(!empty($_SESSION['site_settings']['sync_dirs'])) {
			$dirs = explode("\n", $_SESSION['site_settings']['sync_dirs']);
			foreach ($dirs as $dir) {
				$this->_import_dir(glob(DOC_ROOT . "/$dir/*"), $dir);
			}
		}*/
	}

	function _import_dir($files, $dir)
	{
		//$this->_mkdir($files, $dir);

		/*if($this->_is_ssh2) {
			foreach($files as $file) {
				if($this->_compare_server_files($file, $dir)) {
					if(!$this->_update_file_ssh2($file, $dir . '/' . basename($file))) {

					}
				}
			}
		}
		else {
			foreach($files as $file) {
				if($this->_compare_server_files($file, $dir)) {
					$this->_update_file($file, $dir . '/' . basename($file));
				}
			}
		}*/
	}

	/**
	 * Returns true if $file is a valid file for synchronization
	 *
	 * @param string $file
	 * @return bool
	 */
	function _is_valid_sync_file($file)
	{
		$basename = basename($file);
		$ext = get_extension($file);

		return (bool) (
			(is_file($file)) &&								// regular file
			(strpos($basename, 'orbxc~') === false) && 		// not cached HTML
			(strpos($basename, 'sqlc~') === false) &&		// not cached SQL
			($ext !== 'php') &&								// these are updated through auto88
			($ext !== 'phps') &&							// these should't be updated as well
			($ext !== 'log') &&								// no need to update logs
			($basename !== 'logs.ini') &&					// logs.ini is out
			($ext !== 'bk')	&&								// no backups
			($basename !== 'zse.report')					// no ZSE reports from cron jobs
			);
	}

	function _compare_server_files($file, $dir)
	{
		// invalid file
		if(!$this->_is_valid_sync_file($file)) {
			return false;
		}

		$ftp_filename = basename($file);
		$ftp_filename = $this->_remote_base_dir . '/' . $dir . '/' . $ftp_filename;

		$local_filesize = filesize($file);
		$local_filemtime = filemtime($file);

		$remote_filesize = null;
		$remote_filemtime = null;

		$statinfo = ($this->_is_ssh2) ? ssh2_sftp_stat($this->_ftp_conn, $ftp_filename) : null;

		if($this->_is_ssh2) {
			$remote_filesize = (!$this->_ftp_conn) ? 0 : $statinfo['size'];
		}
		else {
			$remote_filesize = (!$this->_ftp_conn) ? 0 : ftp_size($this->_ftp_conn, $ftp_filename);
		}

		// file doesn't exist, we should update it
		if($remote_filesize == -1) {
			$this->_sync_log[] = 'Identified new file <strong>' . $ftp_filename . '</strong>';
			return true;
		}

		if($this->_is_ssh2) {
			$remote_filemtime = (!$this->_ftp_conn) ? 0 : $statinfo['mtime'];
		}
		else {
			$remote_filemtime = (!$this->_ftp_conn) ? 0 : ftp_mdtm($this->_ftp_conn, $ftp_filename);
		}

		// no change, skip
		if(($local_filesize == $remote_filesize) && ($local_filemtime <= $remote_filemtime)) {
			$this->_sync_log[] = 'Skipping file <strong>' . $file . '</strong>';
			return false;
		}
		return true;
	}

	function _rewrite_url_otherfile($filename)
	{
		$ext = get_extension($filename);

		if(
			($ext == 'html') ||
			($ext == 'rdf') ||
			($ext == 'xml') ||
			(basename($filename) == 'urllist.txt')
		) {
			$this->_sync_rewrite_uri($filename);
		}
	}

	function _update_file_ssh2($local, $remote)
	{
		// silent fail
		if(!$this->_ssh_conn || !is_file($local)) {
			return false;
		}

		$this->_rewrite_url_otherfile($local);

		// this should fix root dir files
		$remote = ($remote[0] == '/') ? substr($remote, 1) : $remote;

		$remote_path = $this->win_fixpath($this->_remote_base_dir . '/' . $remote);

		// start uploading
		$ret = ssh2_scp_send($this->_ssh_conn, $local, escapeshellarg($remote_path), 0644);

		if(!$ret) {
			$this->_sync_log[] = '<span class="red">There was an error uploading the file <strong>' . $local . '</strong> to <strong>' . $remote_path . '</strong></span>';
			//SSH2_STREAM_STDERR

			$err_stream = ssh2_fetch_stream($this->_ssh_conn, SSH2_STREAM_STDERR);
			stream_set_blocking($err_stream, true);
			$stderr = fgets($err_stream, 8192);
			if($stderr) {
				$this->_sync_log[] = '<span class="red">Response: <strong>' . $this->_remote_base_dir . '/' . $stderr . '</strong></span>';
			}
			return false;
		}

		$this->_sync_log[] = '<span class="green">Updated file <strong>' . $local . ' .</strong></span>';
		return true;
	}

	function _update_file($local, $remote)
	{
		// silent fail
		if(!$this->_ftp_conn || !is_file($local)) {
			return false;
		}

		$this->_rewrite_url_otherfile($local);

		// this should fix root dir files
		$remote = ($remote[0] == '/') ? substr($remote, 1) : $remote;

		$dots = '.';
		// start uploading
		$ret = ftp_nb_put($this->_ftp_conn, escapeshellarg($this->_remote_base_dir . '/' . $remote), $local, FTP_BINARY);
		while($ret == FTP_MOREDATA) {
			// add dots
			$dots .= '.';
			// continue uploading...
			$ret = ftp_nb_continue($this->_ftp_conn);
		}

		if($ret != FTP_FINISHED) {
			$this->_sync_log[] = '<span class="red">There was an error uploading the file <strong>' . $local . '</strong> to <strong>' . $this->_remote_base_dir . '/' . $remote . '</strong></span>';
			return false;
		}

		$this->_sync_log[] = '<span class="green">Updated file <strong>' . $local . ' ' .$dots . '</strong></span>';
		return true;
	}

	/**
	 * format sync log
	 *
	 * @return string	formatted HTML
	 */
	function get_log()
	{
		return implode('<br />', $this->_sync_log);
	}

	/**
	 * start synchronization
	 *
	 * @return bool
	 */
	function start()
	{
		if($this->_syncm_status == 0) {
			$this->_sync_log[] = '<span class="red">Cannot start synchronization. Initialization failed. See error log for details</span>';
			return false;
		}

		ignore_user_abort(true);
		set_time_limit(0);

		if($this->_server_type == SYNC_MANAGER_TYPE_RECEIVER) {
			$this->_import();
		}
		else if($this->_server_type == SYNC_MANAGER_TYPE_REPOSITORY) {
			if($this->_is_ssh2) {
				$this->_export_ssh2();
			}
			else {
				$this->_export();
			}
		}

		ignore_user_abort(false);
	}

	/**
	 * save remote user IP address for later usage
	 *
	 */
	function _lock_respos_ip_addr()
	{
		global $orbx_log;
		$r = fopen(DOC_ROOT . '/site/mercury/repos.ip', 'wb');

		if(!$r) {
			$orbx_log->ewrite('could not open repos.ip for writing', __LINE__,__FUNCTION__);
		}

		fwrite($r, ORBX_CLIENT_IP);
		fclose($r);
	}

	/**
	 * compare saved IP address with the current one
	 *
	 * @return bool
	 */
	function _authorize_repos_ip()
	{
		$repos_saved_ip = trim(file_get_contents(DOC_ROOT . '/site/mercury/repos.ip'));
		return ($repos_saved_ip == ORBX_CLIENT_IP);
	}

	function _import()
	{
		global $orbx_log;

		if(!isset($_REQUEST['action']) && $this->_authorize_repos_ip()) {
			$this->_sync_log[] = 'Hello <strong>' . ORBX_CLIENT_IP . '</strong>';
			$this->_sync_log[] = 'Starting synchronization on remote server <strong>' . ORBX_SITE_URL . '</strong>';

			$credentials = base64_decode($_REQUEST['credentials']);

			if(get_is_valid_ajax_id($credentials)) {
				$this->_sql_dump_import();
				if($this->_syncm_status == 1) {
					$this->_sync_log[] = '<span class="green">Successfully finished synchronization for host <strong>' . ORBX_SITE_URL . '</strong></span>';
				}
				else {
					$this->_sync_log[] = '<span class="red">There was an error importing SQL tables</span>';
				}
			}
			else {
				$this->_sync_log[] = '<span class="red">You are not authorized to access <strong>' . ORBX_SITE_URL . '</strong></span>';
			}
			echo $this->get_log();
		}
		else if (isset($_REQUEST['action'])) {
			if($_REQUEST['action'] == 'authorize_export') {
				// clear log
				unset($this->_sync_log);
				$this->_sync_log = array();

				$credentials = base64_decode($_REQUEST['credentials']);

				// we're authorized
				if(get_is_valid_ajax_id($credentials)) {
					$orbx_log->dwrite(ORBX_CLIENT_IP . ' authorized with public credentials "' . $credentials . '"', __LINE__,__FUNCTION__);

					$this->_lock_respos_ip_addr();
					$this->_sync_log[] = sprintf('%u', adler32($credentials . ORBX_UNIQUE_ID . 'ok'));

					// prepare some files and folders for remote host
					create_empty_file($this->temp_dir . '.sql');
					mkdir($this->temp_dir, 0777);
					chmod($this->temp_dir, 0777);
				}
				// we're not authorized
				else {
					$this->_sync_log[] = '000';
					$orbx_log->ewrite(ORBX_CLIENT_IP . ' tried to get authorized with public credentials "' . $credentials . '" but was rejected', __LINE__,__FUNCTION__);
				}
				echo $this->get_log();
			}
		}
	}

	/**
	 * export data via FTP
	 *
	 * @return bool
	 */
	function _export()
	{
		global $orbx_log;

		if($this->_target_host_props['request_orbx_auth'] == 1) {
			$data = array();
			$data['credentials'] = base64_encode(get_ajax_id());
			$data['action'] = 'authorize_export';
			$data['tmp_dir'] = base64_encode($this->temp_basedir);
			$data = http_build_query($data);

			include_once DOC_ROOT . '/orbicon/3rdParty/snoopy/Snoopy.class.php';
			$agent = new Snoopy;

			$agent->submit($this->_remote_server . '/orbicon/modules/synchronization/syncmanager.php?' . $data);
			$answer = $agent->results;

			if($answer == sprintf('%u', adler32(get_ajax_id() . ORBX_UNIQUE_ID . 'ok')) && $this->_ftp_conn) {
				$this->_sync_log[] = "<span class=\"green\">Authorized user <strong>{$_SESSION['user.a']['first_name']} {$_SESSION['user.a']['last_name']} (ID #{$_SESSION['user.a']['id']})</strong></span>";
				$orbx_log->dwrite("authorized user {$_SESSION['user.a']['first_name']} {$_SESSION['user.a']['last_name']} (ID #{$_SESSION['user.a']['id']}) on host " . $this->_remote_server, __LINE__, __FUNCTION__);
			}

		}
		else {
			// generate correct answer
			$answer = sprintf('%u', adler32(get_ajax_id() . ORBX_UNIQUE_ID . 'ok'));
		}

		if($answer == sprintf('%u', adler32(get_ajax_id() . ORBX_UNIQUE_ID . 'ok')) && $this->_ftp_conn) {
			$this->_files_dump_buildlist();
			$this->_files_dump_import_files();

			$this->_sql_dump_export();
			$this->_sql_dump_put();
			rmdir($this->temp_dir);

			if($this->_syncm_status == 1) {
				//$this->_remove_sync_cache_list();
				unset($data);
				$data = array();
				$data['credentials'] = base64_encode(get_ajax_id());
				$data['tmp_dir'] = base64_encode($this->temp_basedir);
				$data = http_build_query($data);

				$agent->submit($this->_remote_server . '/orbicon/modules/synchronization/syncmanager.php?' . $data);
				$answer = $agent->results;

				$this->_sync_log[] = '';
				$this->_sync_log[] = date('r');
				$this->_sync_log[] = '<span class="blue">&raquo; Remote host <strong>' . $this->_remote_server . '</strong></span>';
				$this->_sync_log[] = (empty($answer)) ? '<span class="red">There was an error connecting to <strong>' . $this->_remote_server . '</strong></span>' : '<span class="green">Successfully connected to <strong>' . $this->_remote_server . '</strong></span>';
				$this->_sync_log[] = $answer;
			}
			else {
				$this->_sync_log[] = '<span class="red">Cannot start synchronization. See error log for details</span>';
				return false;
			}
		}
		else if($answer == '000') {
			$this->_sync_log[] = '<span class="red">You are not authorized to access <strong>' . $this->_remote_server . '</strong></span>';
			$this->_sync_log[] = '<span class="red">Shutting down synchronization process</span>';
		}
		else {
			$orbx_log->ewrite('failed to authorize. remote host unavailable. remote host answer: ' . $answer, __LINE__, __FUNCTION__);
			$this->_sync_log[] = '<span class="red">Remote host <strong>' . $this->_remote_server . '</strong> may be down</span>';
			$this->_sync_log[] = '<span class="red">Shutting down synchronization process</span>';
		}
	}

	/**
	 * remove cache list
	 *
	 * @return bool
	 */
	function _remove_sync_cache_list()
	{
		return unlink($this->_sync_cache_file);
	}

	/**
	 * export data via SSH2
	 *
	 * @return bool
	 */
	function _export_ssh2()
	{
		global $dbc, $orbx_log;
		//ssh2 admin check if we enabled it
		if($this->_target_host_props['request_orbx_auth'] == 1) {
			$q = sprintf('	SELECT 		id
							FROM 		'.DB_NAME.'.'.TABLE_EDITORS.'
							WHERE 		(status != %s) AND
										(username = %s) AND
										(pwd =%s)
							LIMIT 		1',
							$dbc->_db->quote(ORBX_USER_STATUS_EX_USER), $dbc->_db->quote($_SESSION['user.a']['username']), $dbc->_db->quote($_SESSION['user.a']['pwd']));

			//var_dump($q);

			$_sql_pass = base64_decode(DB_PASS);
			$_sql_pass = (empty($_sql_pass)) ? '' : ' --password=' . $_sql_pass;
			$_cmd = 'mysql -u ' . DB_USER . $_sql_pass . ' -e "' . $q . '"';

			//var_dump($_cmd);

			$_admin_sql_chk = ssh2_exec($this->_ssh_conn, $_cmd);
			sleep(6);
			/**
			 * @todo replace fgets with stream_get_line in PHP5
			 */
			if(function_exists('stream_get_contents')) {
				$_admin_sql_chk_str = stream_get_contents(ssh2_fetch_stream($_admin_sql_chk, SSH2_STREAM_STDIO));
			}
			else {
				$_admin_sql_chk_str = fgets(ssh2_fetch_stream($_admin_sql_chk, SSH2_STREAM_STDIO), 8192);
			}

			if(function_exists('stream_get_contents')) {
				$stderr = stream_get_contents(ssh2_fetch_stream($_admin_sql_chk, SSH2_STREAM_STDERR));
			}
			else {
				$stderr = fgets(ssh2_fetch_stream($_admin_sql_chk, SSH2_STREAM_STDERR), 8192);
			}

			unset($_cmd, $q, $_sql_pass, $_admin_sql_chk);

			if($stderr === false) {
				//var_dump($_admin_sql_chk_str);

				if(!empty($_admin_sql_chk_str)) {
					$this->_sync_log[] = "<span class=\"green\">Authorized user <strong>{$_SESSION['user.a']['first_name']} {$_SESSION['user.a']['last_name']} (ID #{$_SESSION['user.a']['id']})</strong></span>";
					$orbx_log->dwrite("authorized user {$_SESSION['user.a']['first_name']} {$_SESSION['user.a']['last_name']} (ID #{$_SESSION['user.a']['id']}) on host " . $this->_remote_server, __LINE__, __FUNCTION__);
				}
			}
			else {
					$this->_syncm_status = 0;
					$this->_sync_log[] = '<span class="red">Failed to authorize user. Remote host <strong>' . $this->_remote_server . '</strong> responded: <strong>' . $stderr . '</strong></span>';
			}
		}
		else {
			// set it to nonempty, we are ok to continue
			$_admin_sql_chk_str = 'ok';
		}

		if(!empty($_admin_sql_chk_str)) {
			$this->_files_dump_buildlist();
			$this->_files_dump_import_files();

			$this->_sql_dump_export();
			$this->_sql_dump_put();
			rmdir($this->temp_dir);

			if($this->_syncm_status == 1) {
				$dumpname = $this->_remote_base_dir . '/site/mercury/' . $this->temp_basedir . '.sql';
				$this->_sync_log[] = 'Importing SQL tables from <strong>' . $dumpname . '</strong>';
				// add password if we have one
				$_sql_pass = base64_decode(DB_PASS);
				$_sql_pass = (empty($_sql_pass)) ? '' : ' --password=' . $_sql_pass;
				$_cmd = 'mysql -u ' . DB_USER . $_sql_pass . ' ' . DB_NAME . ' < ' . $dumpname;

				// execute import
				$_import_sql_chk = ssh2_exec($this->_ssh_conn, $_cmd);
				// pause for awhile
				sleep(6);
				$_import_sql_chk_str = fgets(ssh2_fetch_stream($_import_sql_chk, SSH2_STREAM_STDIO), 8192);

				// fetch any errors
				$err = fgets(ssh2_fetch_stream($_import_sql_chk, SSH2_STREAM_STDERR), 8192);

				while($err) {
					$stderr .= $err;
					$err = fgets(ssh2_fetch_stream($_import_sql_chk, SSH2_STREAM_STDERR), 8192);
				}

				if((strpos(strtolower($stderr), 'error 1045') !== false) && ($this->_target_host_props['request_orbx_auth'] != 1)) {
					$stderr = false;
				}

				// pause some more
				sleep(6);
				// reset to 0
				$del_dump = ssh2_exec($this->_ssh_conn, 'rm -f ' . $dumpname);

				$err2 = fgets(ssh2_fetch_stream($del_dump, SSH2_STREAM_STDERR), 8192);

				while($err2) {
					$stderr2 .= $err2;
					$err2 = fgets(ssh2_fetch_stream($del_dump, SSH2_STREAM_STDERR), 8192);
				}

				if($stderr2) {
					$this->_sync_log[] = '<span class="red">Failed to delete SQL dump <b>'.$dumpname.'</b> Remote host <strong>' . $this->_remote_server . '</strong> responded: <strong>' . $stderr2 . '</strong></span>';
				}

				if(empty($stderr)) {
					$stderr = false;
				}

				if($stderr === false) {

					$q = sprintf('	UPDATE 	' . DB_NAME . '.' . TABLE_SETTINGS . '
									SET 	value = %s
									WHERE 	(setting = %s)',
									$dbc->_db->quote(SYNC_MANAGER_TYPE_RECEIVER), $dbc->_db->quote('syncm_type'));

					$_cmd = 'mysql -u ' . DB_USER . $_sql_pass . ' -e "' . $q . '"';
					ssh2_exec($this->_ssh_conn, $_cmd);

					$this->_sync_log[] = '<span class="green">Successfully finished synchronization for host <strong>' . $this->_remote_server . '</strong></span>';
				}
				else {

					$this->_syncm_status = 0;
					$this->_sync_log[] = '<span class="red">Failed to import SQL data. Remote host <strong>' . $this->_remote_server . '</strong> responded: <strong>' . $stderr . '</strong></span>';

				}


				//$this->_remove_sync_cache_list();

				/*$this->_sync_log[] = '';
				$this->_sync_log[] = date('r');
				$this->_sync_log[] = '<span class="blue">&raquo; Remote host <strong>' . $this->_remote_server . '</strong></span>';
				$this->_sync_log[] = (empty($answer)) ? '<span class="red">There was an error connecting to <strong>' . $this->_remote_server . '</strong></span>' : '<span class="green">Successfully connected to <strong>' . $this->_remote_server . '</strong></span>';
				$this->_sync_log[] = $answer;*/
			}
			else {
				$this->_sync_log[] = '<span class="red">Cannot start synchronization. See error log for details</span>';
				return false;
			}
		}
		else {
			$this->_sync_log[] = '<span class="red">You are not authorized to access <strong>' . $this->_remote_server . '</strong></span>';
			$this->_sync_log[] = '<span class="red">Shutting down synchronization process</span>';
			$orbx_log->ewrite('failed to authorize framework user', __LINE__, __FUNCTION__);
		}
	}

	/**
	 * @todo finish this
	 */
	// 5.2.2007
	/*function _sftp_batch_create()
	{
		$this->_sftp_batch[] = 'open '. FTP_USER . '@' . FTP_HOST;
		$this->_sftp_batch[] = 'binary';
		$this->_sftp_batch[] = 'cd ' . $this->_remote_base_dir . '/';
	}

	function _sftp_batch_append_file($filename)
	{
		$this->_sftp_batch[] = 'put ' . $filename;
	}

	function _sftp_batch_make()
	{
		$this->_sftp_batch[] = 'quit';
		$this->_sftp_batch = implode("\n", $this->_sftp_batch);

		$batch = DOC_ROOT . '/site/mercury/sftp.bat';
		create_empty_file($batch);
		chmod_unlock($batch);
		$r = fopen($batch, 'wb');
		fwrite($r, $this->_sftp_batch);
		fclose($r);
		chmod_lock($batch);

		system('sftp -b '.$batch . ' '. FTP_USER . '@' . FTP_HOST);

	}*/

	/**
	 * get a list of all synchronization tables from modules.
	 * also prevents tables which aren't installed to be included in the list
	 *
	 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	 * @return array
	 */
	function get_mod_sync_tables()
	{
		global $orbx_mod, $dbc;
		$sync_tables = array();

		foreach($orbx_mod->all_modules as $module) {
			$module = $orbx_mod->_trim_mod_name($module);
			$frontend = $orbx_mod->load_info($module);

			// we're ready to be implemented
			if(!empty($frontend['module']['sync'])) {
				$sql_tables = explode(',', $frontend['module']['sync']);

				if(!empty($sql_tables)) {
					$sql_tables = array_map('trim', $sql_tables);
					$sync_tables = array_merge($sync_tables, $sql_tables);
				}
			}
		}

		// check to really see if we need to update these tables


		// fetch all tables
		$r = $dbc->_db->query('	SHOW 	TABLES
								FROM 	'.DB_NAME);
		$table = $dbc->_db->fetch_array($r);

		// exit here since we have nothing
		if(empty($table)) {
			return $sync_tables;
		}

		// create an array of their names
		while($table) {
			$installed_tables[] = $table[0];
			$table = $dbc->_db->fetch_array($r);
		}

		// compare them and add to new array only those that exist
		foreach($sync_tables as $sync_table) {
			if(in_array($sync_table, $installed_tables)) {
				$new[] = $sync_table;
			}
		}

		// update array
		$sync_tables = $new;

		return $sync_tables;
	}

	/**
	 * Load local md5 file list into array with filenames as keys and hashes for values
	 *
	 */
	function load_local_md5sum()
	{
		$locals = file($this->_md5_list_filepath);

		foreach($locals as $local) {
			list($filename, $hash) = explode('|', $local);

			$this->_md5_local_list[$filename] = $hash;
		}

	}

	function win_fixpath($path)
	{
		//var_dump(DOC_ROOT);
		$path = str_replace(array('//', '\\\\'), '/', $path);
		if (preg_match_all ('/([a-z])(:)/is', $path, $matches)) {
			if($matches[1][0] && $matches[2][0]) {
				return str_replace(array('/', DOC_ROOT), array('\\', ''), $path);
			}
		}
		return $path;
	}
}

?>