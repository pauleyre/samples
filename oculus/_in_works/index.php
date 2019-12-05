<?php

	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	session_start();

	// logout

	if(isset($_GET['exit']))
	{
		foreach($_SESSION['client_services'] as $key => $value) {
			setcookie($value['permalink'], FALSE, time() - 86400, '/', '.corp.orbitum.net');
		}
		session_destroy();
		header("Location: http://{$_SERVER['SERVER_NAME']}/?close");
	}

	function safe_sql($input)
	{
		if(get_magic_quotes_gpc()) {
			$input = stripslashes($input);
		}
		if(!is_numeric($input)) {
				$input = '\''.mysql_escape_string($input).'\'';
		}
		return $input;
	}

	$auth_status = 'Waiting for input';
	$user_login = NULL;

	// authorize
	if(!isset($_GET['close']) && !isset($_GET['exit']))
	{
		if(isset($_POST['submit']))
		{
			mysql_connect('mysql.avalon.hr', 'spetric_admin', 'XeJ89waWN');
			mysql_select_db('spetric_orbitum');
		
			$action = isset($_POST['action']) ? $_POST['action'] : NULL;
	
			if($action == 'retrievepassword')
			{
				// * clean up inputs

				$_POST['user_login'] = isset($_POST['user_login']) ? $_POST['user_login'] : NULL;
				$_POST['email'] = isset($_POST['email']) ? $_POST['email'] : NULL;
	
				$user_login = trim(strtolower($_POST['user_login']));
				$user_email = trim(strtolower($_POST['email']));
	
				$retrieve = mysql_query(sprintf('SELECT * FROM orbitum_corp_clients WHERE username = PASSWORD(%s) AND email = %s LIMIT 1', safe_sql($user_login), safe_sql($user_email)));
				$retrieve = mysql_fetch_array($retrieve);
	
				if(empty($retrieve['id'])) {
					// redirect
				}
				else {
					//mail($retrieve['email'], 'Orbitum password reset', );
				}
			}
			else if($action == 'authorize')
			{
				// * clean up inputs
	
				$_POST['log'] = isset($_POST['log']) ? $_POST['log'] : NULL;
				$_POST['pwd'] = isset($_POST['pwd']) ? $_POST['pwd'] : NULL;
	
				$user_login = trim(strtolower($_POST['log']));
				$user_pass = trim($_POST['pwd']);

				$authorize = mysql_query(sprintf('SELECT * FROM orbitum_corp_clients WHERE username = PASSWORD(%s) AND pwd = PASSWORD(%s) LIMIT 1', safe_sql($user_login), safe_sql($user_pass)));
				$authorize = mysql_fetch_array($authorize);
	
				if(empty($authorize['id'])) {
					$auth_status = 'Unauthorized';
				}
				else
				{
					$_services = mysql_query(sprintf('SELECT * FROM orbitum_corp_access WHERE client = %s ORDER BY service', safe_sql($authorize['id'])));
					$services = mysql_fetch_array($_services);
	
					while($services)
					{
						$service = mysql_query(sprintf('SELECT * FROM orbitum_corp_services WHERE permalink = %s', safe_sql($services['service'])));
						$service = mysql_fetch_array($service);
	
						setcookie($service['permalink'], TRUE, time() + 86400, '/', '.corp.orbitum.net');
						$_SESSION['client_services'][] = array(
																'permalink' => $service['permalink'],
																'url' => $service['url'],
																'name' => $service['name']
																);
						$_SESSION['client_authorized'] = TRUE;
	
						$services = mysql_fetch_array($_services);
					}
	
					// * remember me
					$rememeber_me = isset($_POST['rememberme']) ? $_POST['rememberme'] : NULL;
					if($rememeber_me == 'forever') {
						setcookie('corp_auth', $user_login, time() + 86400, '/', '.corp.orbitum.net');
					}
					$auth_status = 'Authorized';
					header("Location: http://{$_SERVER['SERVER_NAME']}/services/");
				}
			}
		}
	}

	if(isset($_GET['lost-password']))
	{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Orbitum &raquo; Lost Password</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="http://<?= $_SERVER['SERVER_NAME']; ?>/css/corp.css" type="text/css" />
	<script type="text/javascript">
	function focusit() {
		// focus on first input field
		document.getElementById("user_login").focus();
	}
	window.onload = focusit;
	</script>
</head>
<body>
	<div id="login">
	<h1><a href="http://www.orbitum.net/">Orbitum</a></h1>
	<p>Please enter your information here. We will send you a <span class="u">new</span> password.</p>
	<form name="lostpass" action="http://<?= $_SERVER['SERVER_NAME']; ?>/" method="post" id="lostpass">
	<p>
		<input type="hidden" name="action" value="retrievepassword" />
		<label>Username:<br /><input type="text" name="user_login" id="user_login" value="" size="20" maxlength="255" tabindex="1" /></label>
	</p>
	<p><label>E-mail:<br /><input type="text" name="email" id="email" value="" size="25" tabindex="2" maxlength="255" /></label><br /></p>
	<p class="submit"><input type="submit" name="submit" id="submit" value="Retrieve Password &raquo;" tabindex="3" /></p>
	</form>
	<ul>
		<li><a href="http://<?= $_SERVER['SERVER_NAME']; ?>/">Login</a></li>
	</ul>
	</div>
</body>
</html>
<?php
	}
	else
	{
		$user_login = isset($_COOKIE['corp_auth']) ? $_COOKIE['corp_auth'] : $user_login;
		$user_login = htmlspecialchars(stripslashes($user_login));
		$checked_box = isset($_COOKIE['corp_auth']) ? 'checked="checked"' : '';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Orbitum &rsaquo; Authorize</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="http://<?= $_SERVER['SERVER_NAME']; ?>/css/corp.css" type="text/css" />
	<script type="text/javascript">
	function focusit() {
		// focus on first input field
		document.getElementById("log").focus();
	}
	window.onload = focusit;
	</script>
</head>
<body>
	<div id="login">
	<h1><a href="http://www.orbitum.net/">Orbitum</a></h1>
	<form name="loginform" id="loginform" action="http://<?= $_SERVER['SERVER_NAME']; ?>/" method="post">
	<p>
		<input type="hidden" name="action" value="authorize" />
		<label>Username:<br /><input type="text" name="log" id="log" value="<?= $user_login; ?>" size="20" maxlength="255" tabindex="1" /></label>
	</p>
	<p><label>Password:<br /><input type="password" name="pwd" id="pwd" value="" size="20" maxlength="255" tabindex="2" /></label></p>
	<p><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="3" <?= $checked_box; ?> /> Remember me on this computer</label></p>
	<p class="submit"><input type="submit" name="submit" id="submit" value="Authorize &raquo;" tabindex="4" /></p>
	</form>
	<ul>
		<li><a href="http://<?= $_SERVER['SERVER_NAME']; ?>/?lost-password" title="Password Lost and Found">Lost your password?</a></li>
		<li>Status: <?= $auth_status; ?></li>
	</ul>
	</div>
</body>
</html>
<?php
	}
?>