<?php

/**
 * Orbicon step by step installation class
 *
 * Installation procedure follows...
 * a) verify that we can write where needed
 * b) try setting permissions with simple chmod
 * c) on failure ask for FTP data and chmod with FTP
 * d) quit on ftpchmod failure or proceed
 * e) ask for MySQL data
 * f) quit on mysql failure or proceed
 * g) setup database
 * i) that's it
 *
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconFE
 * @version 1.1
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-10-19
 */

class Orbicon_Install
{
	var $install_log;
	var $install_writable_res;
	var $required_php_version;
	var $required_mysql_version;

	function Orbicon_Install()
	{
		$this->__construct();
	}

	function __construct()
	{
		$this->install_log = null;
		// these folders / files must be writable
		$this->install_writable_res = array(
			'site/mercury' => 'dir',
			'site/mercury/flood' => 'dir',
			'site/gfx' => 'dir',
			'site/venus' => 'dir',
			'site/venus/thumbs' => 'dir',
			'site/gfx/site.css' => 'file',
			'site/gfx/site.js' => 'file',
		    'site/mercury/sql_cache' => 'dir',
			'site/mercury/sql_cache/' . date('Y') => 'dir',
			'site/mercury/sql_cache/' . date('Y/m') => 'dir',
			'site/mercury/sql_cache/' . date('Y/m/d') => 'dir'
		);

		$this->required_php_version = '4.1.2';
		$this->required_mysql_version = '3.23.49';

		if(!is_dir(DOC_ROOT . '/site')) {
			mkdir(DOC_ROOT . '/site', 0755);
		}
	}

	function install_make_writable_ftp($ftp)
	{
		// we might need this later
		$_SESSION['install_ftp_data'] = $ftp;
		$passed = 1;
		// set up basic connection
		$conn_id = ftp_connect($ftp['host']);

		if(!$conn_id) {
			$passed = 0;
			$this->install_log[] = '<span class="red">Could not connect to FTP host...</span>';
			return $passed;
		}

		// login with username and password
		if(!ftp_login($conn_id, $ftp['username'], $ftp['password'])) {
			$passed = 0;
			$this->install_log[] = '<span class="red">Could not login to FTP host...</span>';
			return $passed;
		}
		clearstatcache();

		foreach($this->install_writable_res as $folder => $type) {
			$folder = DOC_ROOT.'/'.$folder;
			$chmod = ($type == 'dir') ? 0777 : 0666;
			if(!ftp_chmod($conn_id, $chmod, $folder)) {
				$this->install_log[] = '<span class="red"><strong>'.$folder.'</strong> set to writable failed...</span>';
				$passed = 0;
				break;
			}
			else {
				$this->install_log[] = '<strong>' . $folder.'</strong> set to writable succeeded...';
			}
		}

		// close the connection
		ftp_close($conn_id);
		// ask for mysql
		if($passed) {
			$this->install_log[] = '<span class="blue">please provide MySQL authorization data to continue with installation...</span>';
		}
		// ftp failed. quit
		else {
			$this->install_log[] = '<span class="red">unable to set write permissions. cannot continue with installation...</span>';
			$this->install_log[] = '<span class="blue">please try again or manually set write permissions...</span>';
		}
		return $passed;
	}

	// permissions must be written on this step or we can't proceed
	function install_make_writable()
	{
		$this->install_log[] = date('r');
		$this->install_log[] = $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].' '.$_SERVER['SERVER_SOFTWARE'];
		$this->install_log[] = '<span class="blue">installing...</span>';

		// minimum config check
		if(!$this-> __install_check_minimal_config()) {
			return 0;
		}

		$passed = 1;
		clearstatcache();

		foreach($this->install_writable_res as $folder => $type) {
			// setup full path here
			$folder = DOC_ROOT.'/'.$folder;

			// dir of file?
			$chmod = ($type == 'dir') ? 0777 : 0666;

			if(!is_dir($folder) && ($type == 'dir')) {
				mkdir($folder, 0777);
			}
			else if(!is_file($folder) && ($type == 'file')) {
				create_empty_file($folder);
			}

			// chmod failed, exit
			if(!chmod_unlock($folder, $chmod)) {
				$this->install_log[] = '<span class="red"><strong>'.$folder.'</strong> set to writable failed...</span>';
				$passed = 0;
				break;
			}
			else {
				$this->install_log[] = '<strong>'.$folder.'</strong> set to writable succeeded...';
			}
		}

		if($passed) {
			$this->install_log[] = '<span class="blue">please provide MySQL authorization data to continue with installation...</span>';
		}
		else {
			$this->install_log[] = '<span class="blue">please provide FTP authorization data to set write permissions and continue with installation...</span>';
		}

		// if usual chmod failed, got to installation step c)
		return $passed;
	}

	function install_mysql_data($mysql, $sysadmin)
	{
		global $orbx_mod;
		$passed = 1;
		if(defined('MYSQL_CLIENT_COMPRESS')) {
			$test_link = mysql_connect($mysql['host'], $mysql['username'], $mysql['password'], true, MYSQL_CLIENT_COMPRESS);
		}
		else {
			$test_link = mysql_connect($mysql['host'], $mysql['username'], $mysql['password']);
		}

		if(!is_resource($test_link)) {
			$passed = 0;
			$this->install_log[] = '<span class="red">Could not connect to MySQL host <strong>'.$mysql['host'].'</strong>...</span>';
			if($test_link) {
				$this->install_log[] = '<span class="red">MySQL error ('.mysql_errno($test_link).'): '.mysql_error($test_link).'</span>';
			}
			else {
				$this->install_log[] = '<span class="red">MySQL error ('.mysql_errno().'): '.mysql_error().'</span>';
			}
			return $passed;
		}
		else {
			$this->install_log[] = 'Connected to MySQL host...';

			if(!$this->mysql_version_check($test_link)) {
				$this->install_log[] = '<span class="red">MySQL '.min(mysql_get_server_info($test_link), mysql_get_client_info()).' does not match minimum requirements...</span>';
				$passed = 0;
			}
			if(!mysql_select_db($mysql['db'], $test_link)) {
				$passed = 0;
				if($test_link) {
					$this->install_log[] = '<span class="red">MySQL error ('.mysql_errno($test_link).'): '.mysql_error($test_link).'</span>';
				}
				else {
					$this->install_log[] = '<span class="red">MySQL error ('.mysql_errno().'): '.mysql_error().'</span>';
				}
				mysql_close($test_link);
			}
			// we can connect
			else {
				// don't import db dump if we can't save configuration file
				create_empty_file(ORBX_SYS_CONFIG);
				// set to writable
				if(!chmod_unlock(ORBX_SYS_CONFIG)) {
					// try with ftp
					$this->__install_ftp_sys_chmod(0666);
				}
				// this is fine, we have configuration file
				clearstatcache();
				if(is_file(ORBX_SYS_CONFIG)) {
					// format config data
					// php header
					$sys_config = '<?php'."\n";

					// mysql
					$sys_config .= '/* mysql */'."\n";
					$sys_config .= 'define(\'DB_TYPE\', \'MySQL\');'."\n";
					$sys_config .= 'define(\'DB_HOST\', \''.$mysql['host'].'\');'."\n";
					$sys_config .= 'define(\'DB_NAME\', \''.$mysql['db'].'\');'."\n";
					$sys_config .= 'define(\'DB_USER\', \''.$mysql['username'].'\');'."\n";
					$sys_config .= 'define(\'DB_PASS\', \''.base64_encode($mysql['password']).'\');'."\n";
					$sys_config .= 'define(\'DB_PERMACONN\', \''.$mysql['perma'].'\');'."\n";

					// ftp data if available
					if(isset($_SESSION['install_ftp_data'])) {
						$sys_config .= '/* ftp */'."\n";
						$sys_config .= 'define(\'FTP_HOST\', \''.$_SESSION['install_ftp_data']['host'].'\');'."\n";
						$sys_config .= 'define(\'FTP_ROOTDIR\', \''.$_SESSION['install_ftp_data']['rootdir'].'\');'."\n";
						$sys_config .= 'define(\'FTP_USER\', \''.$_SESSION['install_ftp_data']['username'].'\');'."\n";
						$sys_config .= 'define(\'FTP_PASS\', \''.base64_encode($_SESSION['install_ftp_data']['password']).'\');'."\n";
						$sys_config .= 'define(\'FTP_TYPE\', \''.$_SESSION['install_ftp_data']['type'].'\');'."\n";
					}

					// system constants
					$sys_config .= '/* system */'."\n";
					$sys_config .= 'define(\'ORBX_MAINTENANCE_MODE\', 0);'."\n";
					$sys_config .= 'define(\'ORBX_INSTALL_TYPE\', 1);'."\n";
					$sys_config .= 'define(\'ORBX_INSTALL_TIME\', '.time().');'."\n";
					$sys_config .= 'define(\'ORBX_INTEGRITY_URI\', \''.str_replace('www.', '', ORBX_SITE_URL).'\');'."\n";

					// php footer
					$sys_config .= '?>';
					// write and close file
					$sys_h = fopen(ORBX_SYS_CONFIG, 'wb');
					/* Set a 64k buffer. */
					if(function_exists('stream_set_write_buffer')) {
						stream_set_write_buffer($sys_h, 65535);
					}
					if(fwrite($sys_h, $sys_config)) {
						fclose($sys_h);
						// set to read only
						if(!chmod_lock(ORBX_SYS_CONFIG)) {
							// try with ftp
							$this->__install_ftp_sys_chmod(0644);
						}
						unset($sys_config, $sys_h);
						// perform mysql import
						$this->__install_import_mysql_dump(DOC_ROOT.'/orbicon/controler/orbicon.sql', $test_link);
						$this->__install_import_mysql_dump(DOC_ROOT.'/orbicon/controler/orbicon_sys_iso_639_1_codes.sql', $test_link);
						$this->__install_import_mysql_dump(DOC_ROOT.'/orbicon/controler/orbicon_sys_iso_639_2_codes.sql', $test_link);
						$this->__install_import_mysql_dump(DOC_ROOT.'/orbicon/controler/orbicon_mime_types.sql', $test_link);

						// MODULES

						$regexp_sql = sql_regcase('*.sql');
						$regexp_css = sql_regcase('*.css');
						$site_css = DOC_ROOT . '/site/gfx/site.css';
						chmod_unlock($site_css);
						$r = fopen($site_css, 'ab');

						foreach($orbx_mod->all_modules as $module) {

							// execute sql
							$setup_sqls = glob($module . '/setup/{'.$regexp_sql.'}', GLOB_BRACE);
							// this may occur on rare ocasions
							$setup_sqls = empty($setup_sqls) ? glob($module . '/setup/{*.sql}', GLOB_BRACE) : $setup_sqls;

							foreach($setup_sqls as $setup_sql) {
								$this->__install_import_mysql_dump($setup_sql, $test_link);
							}

							// append css files to site's main css

							$setup_css_files = glob($module . '/setup/{'.$regexp_css.'}', GLOB_BRACE);
							// this may occur on rare ocasions
							$setup_css_files = empty($setup_sqls) ? glob($module . '/setup/{*.css}', GLOB_BRACE) : $setup_css_files;

							if(!empty($setup_css_files)) {

								$mod_name = $orbx_mod->_trim_mod_name($module);

								// add opening comment
								$contents = "/* $mod_name START */\r\n\r\n";

								foreach($setup_css_files as $setup_css) {
									$contents .= file_get_contents($setup_css) . "\r\n\r\n";
								}

								// add closing comment
								$contents .= "/* $mod_name END */\r\n\r\n";

								// write CSS contents
								$write = fwrite($r, $contents);

								if($write) {
									$this->install_log[] = 'Appended CSS data for module <strong>'.$$mod_name.'</strong>...';
								}
							}
						}

						fclose($r);
						chmod_lock($site_css);

						// gzip css and js
						require_once DOC_ROOT . '/orbicon/class/file/inc.file.php';

						gzip(DOC_ROOT . '/site/gfx/site.css');
						gzip(DOC_ROOT . '/site/gfx/site.js');

						// add admin
						$this->__install_sysadmin_account($sysadmin, $test_link);
						$this->install_log[] = '<span class="blue">Congratulations! Installation has successfuly finished. Press &quot;Finish&quot; to authorize yourself and start using '.ORBX_FULL_NAME.'...</span>';
					}
					else {
						mysql_close($test_link);
						$passed = 0;
						$this->install_log[] = '<span class="red">Could not write to system configuration file...</span>';
						return $passed;
					}
				}
				else {
					mysql_close($test_link);
					$passed = 0;
					$this->install_log[] = '<span class="red">Could not access system configuration file...</span>';
					return $passed;
				}
			}
		}
		return $passed;
	}

	// set $mode on ORBX_SYS_CONFIG file. silently fails
	function __install_ftp_sys_chmod($mode)
	{
		// try with ftp (silent fail)
		if(isset($_SESSION['install_ftp_data'])) {
			$ftp = $_SESSION['install_ftp_data'];
			$conn_id = ftp_connect($ftp['host']);
			// login with username and password
			ftp_login($conn_id, $ftp['username'], $ftp['password']);
			// set to writable
			ftp_chmod($conn_id, $mode, ORBX_SYS_CONFIG);
			// close the connection
			ftp_close($conn_id);
		}
	}
/*	if (mysql_query("ALTER TABLE {$db_prefix}boards ORDER BY ID_BOARD") === false)
		print_error('Error: The MySQL account in Settings.php does not have sufficient privileges.', true);
*/
	// simple SQL dump import. not suitable for general use
	function __install_import_mysql_dump($filename, $link)
	{
		$sql_filename = basename($filename);
		$this->install_log[] = 'Writing <strong>' . $sql_filename . '</strong> to MySQL database...';

		$content = file($filename);
		$buffer = '';

		foreach($content as $sql_line) {
			$tsl = trim($sql_line);
			if(($sql_line != '')
			&& ($tsl[0] . $tsl[1] != '--')	// comment
			&& ($tsl[0] != '#'))			// comment
			{
				$buffer .= $sql_line;
				// default delimiter ; found
				if(preg_match('/;\s*$/', $sql_line)) {
					// execute query
					$buffer = trim($buffer);
					while(substr($buffer, -1, 1) == ';') {
						$buffer = (substr($buffer, -1, 1) == ';') ? substr($buffer, 0, -1) : $buffer;
					}
					$r = mysql_query($buffer, $link);
					if(!$r) {
						$this->install_log[] = '<span class="red">MySQL error ('.mysql_errno($link).'): '.mysql_error($link).'</span>';
					}
					//reset query
					$buffer = null;
				}
			}	// if end
		}	// foreach end
		unset($content, $sql_line);
		$this->install_log[] = 'Finished writing <strong>'.$sql_filename.'</strong> to MySQL database...';
	}

	function __install_sysadmin_account($data, $link)
	{
		$install_dbc = new DBC();

		$this->install_log[] = 'Setting up the system administrator account...';
		$q = sprintf('		INSERT
							INTO 		'.TABLE_EDITORS.'
										(username, pwd,
										first_name, status)
							VALUES 		(PASSWORD(%s), PASSWORD(%s),
										%s, %s)',
					$install_dbc->_db->quote($data['username'], $link), $install_dbc->_db->quote($data['password'], $link),
					$install_dbc->_db->quote('sysadmin', $link), $install_dbc->_db->quote(ORBX_USER_STATUS_SYSADMIN, $link));
		mysql_query($q, $link);
		unset($install_dbc);
		$this->install_log[] = 'Finished setting up the system administrator account...';
	}

	function __install_check_minimal_config()
	{
		if(!function_exists('version_compare')) {
			// version_compare was introduced in 4.1.0
			$this->install_log[] = '<span class="red">PHP 4.0.x does not match version requirements. Version <strong>'.$this->required_php_version.'</strong> or higher is required...</span>';
			return false;
		}

		if(!$this->php_version_check()) {
			$this->install_log[] = '<span class="red">PHP <strong>'.PHP_VERSION.'</strong> does not match version requirements. Version <strong>'.$this->required_php_version.'</strong> or higher is required...</span>';
			return false;
		}

		return true;
	}

	function php_version_check()
	{
		$min_ver = explode('.', $this->required_php_version);
		$cur_ver = explode('.', PHP_VERSION);

		return !(($cur_ver[0] <= $min_ver[0]) && ($cur_ver[1] <= $min_ver[1]) && ($cur_ver[1] <= $min_ver[1]) && ($cur_ver[2][0] < $min_ver[2][0]));
	}

	function mysql_version_check($link)
	{
		$current_ver = (mysql_get_server_info($link) < mysql_get_client_info()) ? mysql_get_server_info($link) : mysql_get_client_info();
		$current_ver = preg_replace('~\-.+?$~', '', $current_ver);

		return (version_compare($this->required_mysql_version, $current_ver) <= 0);
	}
}

?>