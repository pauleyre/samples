<?php
/**
 * Install GUI
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE (or OrbiconMOD, OrbiconTOOLS)
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-07-01
 */

	require_once DOC_ROOT . '/orbicon/class/class.install.php';
	$setup = new Orbicon_Install;

	require_once DOC_ROOT . '/orbicon/class/class.version.php';
	$orbicon_info = new Version;

	if(empty($setup->install_log)) {
		$setup->install_log[] = date('r');
		$setup->install_log[] = $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].' '.$_SERVER['SERVER_SOFTWARE'];
		$setup->install_log[] = 'ready to install...';
		$setup->install_log = implode('<br />', $setup->install_log);
	}

?>
<!DOCTYPE html PUBLIC
	"-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ORBX_FULL_NAME; ?> installation</title>
<?php echo $orbicon_x->get_html_metatags(NULL); ?>
<style type="text/css">
/*<![CDATA[*/
* {
	font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif;
}
body, html {
	background: #ffffff;
}
/* top container */
#oX_install_content,
#oX_install_eula {
	margin: 3em auto;
	padding: 10px;
	border: 1px solid #cccccc;
	width: 750px;
	min-height: 60px;
	background: #F1F3F5;
}
/* install log */
#oX_install_log {
	margin: 0 auto;
	padding: 10px;
	border: 1px solid #cccccc;
	width: 750px;
	background: #F1F3F5;
	overflow:auto;
}

/* big title and button */
#oX_install_content #title,
#oX_install_content #start_install {
	font-size: 30px;
	color: #666666;
	line-height: 1em;
}

/* title */
#oX_install_content #title {
	padding: 10px 0px 20px 80px;
	white-space: nowrap;
	text-align: center;
	text-align: left;
}

/* extra divs for mysql and ftp data. hidden by default */
#oX_install_content #extra_mysql,
#oX_install_content #extra_ftp {
	display:none;
}

#update_indicator {
	/* Netscape 4, IE 4.x-5.0/Win and other lesser browsers will use this */
	position: absolute;
	right: 0px;
	bottom: 0px;
}
body > div#update_indicator {
	/* used by Opera 5+, Netscape6+/Mozilla, Konqueror, Safari, OmniWeb 4.5+, iCab, ICEbrowser */
	position:fixed;
}

/*]]>*/
</style>

<!--[if gte IE 5.5]>
<![if lt IE 7]>
<style type="text/css">
div#update_indicator {
  /* IE5.5+/Win - this is more specific than the IE 5.0 version */
  right:auto;bottom:auto;
  left: expression( ( update_indicator.offsetWidth + ( document.documentElement.clientWidth ? document.documentElement.clientWidth : document.body.clientWidth ) + ( ignoreMe2 = document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft ) ) + 'px' );
  top: expression( ( update_indicator.offsetHeight + ( document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight ) + ( ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop ) ) + 'px' );
}
</style>
<![endif]>
<![endif]-->

<script type="text/javascript"><!-- // --><![CDATA[

	function __start_install()
	{
		var handleSuccess = function(o)
		{
			if(o.responseText !== undefined) {
				// * update log
				var setup_log = $('oX_install_log');
				setup_log.innerHTML = o.responseText;
				var status = $('__setup_status');
				var button = $('start_install');
				// chmod failed, offer ftp
				if(status.value == 0) {
					$('extra_ftp').style.display = 'block';
					button.onclick = __ftp_setup;
				}
				// proceed and ask for mysql data
				// exit from this function
				else if(status.value == 1) {
					$('extra_mysql').style.display = 'block';
					button.onclick = __mysql_setup;
				}
				button.value = 'Continue';
			}
			sh_ind();
		}

		var callback =
		{
			success:handleSuccess
		};

		// we must accept license terms
		if(empty($('eula_ok').checked)) {
			window.alert('You must comply with terms in the License Agreement before continuing by selecting the "I accept the terms of the license agreement" box below')
			return false;
		}
		else {
			$('oX_install_eula').style.display = 'none';
		}

		if(window.confirm('Are you sure you want to start installation?')) {
			sh_ind();
			var url = '<?php echo ORBX_SITE_URL; ?>/orbicon/controler/setup.handler.php';
			YAHOO.util.Connect.asyncRequest('POST', url, callback, 'step=0');
		}
	}

	function __ftp_setup()
	{
		var hostname = $('ftp_host');
		var rootdir = $('ftp_rootdir');
		var username = $('ftp_username');
		var password = $('ftp_pwd');
		var password_v = $('ftp_pwd_v');
		var type = $('ftp_type');

		// error checking
		// password mismatch
		if(password.value != password_v.value) {
			window.alert('Error: Please verify password entries before you continue');
			return false;
		}

		var verify_msg = 'Please verify that this FTP configuration is correct\n\n';
		verify_msg += 'Type: ' + type.value + '\n';
		verify_msg += 'Host name: ' + hostname.value + '\n';
		verify_msg += 'Username: ' + username.value + '\n';
		verify_msg += 'Password: ' + password.value + '\n';

		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				// * clean up old status
				var obsolete_setup_status = $('__setup_status');
				obsolete_setup_status.parentNode.removeChild(obsolete_setup_status);
				// * update log
				var setup_log = $('oX_install_log');
				setup_log.innerHTML = setup_log.innerHTML + '<br />' + o.responseText;
				var status = $('__setup_status');
				var button = $('start_install');
				// ftp chmod failed, try again
				if(status.value == 0) {
					// do nothing
				}
				// proceed and ask for mysql data
				// exit from this function
				else if(status.value == 1) {
					$('extra_ftp').style.display = 'none';
					$('extra_mysql').style.display = 'block';
					button.onclick = __mysql_setup;
				}
				button.value = 'Continue';
			}
			sh_ind();
		}

		var callback =
		{
			success:handleSuccess
		};

		if(window.confirm(verify_msg)) {
			sh_ind();
			var data = new Array();
			data[0] = 'ftp_host=' + hostname.value;
			data[1] = 'ftp_rootdir=' + rootdir.value;
			data[2] = 'ftp_username=' + username.value;
			data[3] = 'ftp_pwd=' + password.value;
			data[4] = 'step=1';
			data[5] = 'ftp_type=' + type.value;

			data = data.join('&');

			var url = '<?php echo ORBX_SITE_URL; ?>/orbicon/controler/setup.handler.php';
			YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
		}
	}

	function __mysql_setup()
	{
		var hostname = $('mysql_host');
		var username = $('mysql_username');
		var password = $('mysql_pwd');
		var password_v = $('mysql_pwd_v');
		var dbname = $('mysql_dbname');
		var sysadmin_username = $('sysadmin_username');
		var sysadmin_password = $('sysadmin_pwd');
		var sysadmin_password_v = $('sysadmin_pwd_v');

		var sample_data = $('add_sample_data');

		// error checking
		// password mismatch
		if(password.value != password_v.value) {
			window.alert('Error: Please verify MySQL password entries before you continue');
			return false;
		}

		if(sysadmin_password.value != sysadmin_password_v.value) {
			window.alert('Error: Please verify system administrator password entries before you continue');
			return false;
		}

		// for verify_msg display
		var sample_data_txt = (sample_data.checked) ? 'Yes' : 'No';

		var verify_msg = 'Please verify that this configuration is correct\n\n';
		verify_msg += '-- MySQL --\n\n';
		verify_msg += 'Host name: ' + hostname.value + '\n';
		verify_msg += 'Username: ' + username.value + '\n';
		verify_msg += 'Password: ' + password.value + '\n';
		verify_msg += 'Database name: ' + dbname.value + '\n';
		verify_msg += 'Add sample data: ' + sample_data_txt + '\n\n';
		verify_msg += '-- System administrator --\n\n';
		verify_msg += 'Username: ' + sysadmin_username.value + '\n';
		verify_msg += 'Password: ' + sysadmin_password.value + '\n';

		var handleSuccess = function(o)
		{
			if(o.responseText !== undefined) {
				// * clean up old status
				var obsolete_setup_status = $('__setup_status');
				obsolete_setup_status.parentNode.removeChild(obsolete_setup_status);
				// * update log
				var setup_log = $('oX_install_log');
				setup_log.innerHTML = setup_log.innerHTML + '<br />' + o.responseText;
				var status = $('__setup_status');
				var button = $('start_install');
				// mysql failed, try again
				if(status.value == 0) {
					// do nothing
				}
				// finish installation
				else if(status.value == 1) {
					$('extra_mysql').style.display = 'none';
					button.onclick = function() { redirect('<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/authorize'); };
					button.value = 'Finish';
				}
			}
			sh_ind();
		}

		var callback =
		{
			success:handleSuccess
		};

		if(window.confirm(verify_msg)) {
			sh_ind();
			var data = new Array();
			data[0] = 'mysql_host=' + hostname.value;
			data[1] = 'mysql_username=' + username.value;
			data[2] = 'mysql_pwd=' + password.value;
			data[3] = 'mysql_dbname=' + dbname.value;
			data[4] = 'sysadmin_username=' + sysadmin_username.value;
			data[5] = 'sysadmin_pwd=' + sysadmin_password.value;
			data[6] = 'add_sample_data=' + sample_data.checked;
			data[7] = 'step=2';

			data = data.join('&');

			var url = '<?php echo ORBX_SITE_URL; ?>/orbicon/controler/setup.handler.php';
			YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
		}
	}

// ]]></script>

</head>

<body>
	<div id="oX_install_content">
		<div id="title"><?php echo $orbicon_info->product_name; ?> installation <input onclick="javascript:__start_install();" id="start_install" type="button" value="Start &raquo;" /></div>
		<div id="extra_mysql">
		<fieldset>
			<legend><strong>MySQL database configuration</strong></legend>
				<p>
					<label for="mysql_host">MySQL Host name</label><br />
					<input size="50" type="text" name="mysql_host" id="mysql_host" value="" />
				</p>
				<p>
					<label for="mysql_username">MySQL Username</label><br />
					<input size="50" type="text" name="mysql_username" id="mysql_username" value="" /></td>
				</p>
				<p>
					<label for="mysql_pwd">MySQL Password</label><br />
					<input size="50" type="password" id="mysql_pwd" name="mysql_pwd" value="" /></td>
				</p>
				<p>
					<label for="mysql_pwd_v">Verify MySQL password</label><br />
					<input size="50" type="password" id="mysql_pwd_v" name="mysql_pwd_v" value="" /></td>
				</p>
				<p>
					<label for="mysql_dbname">MySQL Database name</label><br />
					<input size="50" type="text" id="mysql_dbname" name="mysql_dbname" value="" /></td>
				</p>
				<p>
					<input checked="checked" type="checkbox" name="add_sample_data" id="add_sample_data" value="1" /> <label for="add_sample_data">Add sample data</label>
				</p>
			</fieldset><br />
			<fieldset>
				<legend><strong>System administrator configuration</strong></legend>
				<p>
					<label for="sysadmin_username">System administrator Username</label><br />
					<input size="50" type="text" name="sysadmin_username" id="sysadmin_username" value="" /></td>
				</p>
				<p>
					<label for="sysadmin_pwd">System administrator Password</label><br />
					<input size="50" type="password" id="sysadmin_pwd" name="sysadmin_pwd" value="" /></td>
				</p>
				<p>
					<label for="sysadmin_pwd_v">Verify system administrator password</label><br />
					<input size="50" type="password" id="sysadmin_pwd_v" name="sysadmin_pwd_v" value="" /></td>
				</p>
			</fieldset>
		</div>
		<div id="extra_ftp">
		<fieldset>
			<legend><strong>FTP configuration</strong></legend>
				<p>
					<label for="ftp_type">FTP type</label><br />
					<select id="ftp_type" name="ftp_type">
							<option value="ftp">FTP</option>
							<!-- <option value="ssh2">SFTP (SSH2)</option> -->
					</select>
				</p>
				<p>
					<label for="ftp_host">FTP Host name</label><br />
					<input size="50" type="text" name="ftp_host" id="ftp_host" value="" />
				</p>

				<p>
					<label for="ftp_rootdir">FTP Root directory</label><br />
					<input size="50" type="text" name="ftp_rootdir" id="ftp_rootdir" value="" />
				</p>

				<p>
					<label for="ftp_username">FTP Username</label><br />
					<input size="50" type="text" name="ftp_username" id="ftp_username" value="" /></td>
				</p>
				<p>
					<label for="ftp_pwd">FTP Password</label><br />
					<input size="50" type="password" id="ftp_pwd" name="ftp_pwd" value="" /></td>
				</p>
				<p>
					<label for="ftp_pwd_v">Verify FTP password</label><br />
					<input size="50" type="password" id="ftp_pwd_v" name="ftp_pwd_v" value="" /></td>
				</p>
			</fieldset>
		</div>
	</div>
	<div id="oX_install_bar"></div>
	<div id="oX_install_log"><?php echo $setup->install_log; ?></div>
	<div id="oX_install_eula"><?php echo nl2br(file_get_contents(DOC_ROOT . '/orbicon/controler/eula.txt')); ?><p><hr /><input type="checkbox" value="1" id="eula_ok" name="eula_ok" /> <label for="eula_ok">I accept the terms of the license agreement</label></p></div>
	<p id="oX_version_sig"><?php echo $orbicon_info->get_orbicon_version(); ?></p>
	<div class="h" id="update_indicator" style="background:#ffffff; border:1px solid #999999; width: 16px; height:16px; padding: 4px;"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/indicator.gif" alt="!" title="update in progress..." /></div>
</body>
</html>
