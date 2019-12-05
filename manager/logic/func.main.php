<?php

function main()
{
	error_reporting(E_ALL ^ E_NOTICE);
	setlocale(LC_TIME, 'hr_HR', 'hr', 'hrv_HRV', 'cro');

	header('Content-Type: text/html; charset=UTF-8', true);
	header('Content-Language: hr', true);

	session_start();

	// session fixation check
	if(!isset($_SESSION['session_started'])) {
		session_regenerate_id();
		$_SESSION['session_started'] = true;
	}

	$hash = md5($_SERVER['HTTP_USER_AGENT'] . @$_SERVER['HTTP_ACCEPT_CHARSET'] . $_SERVER['DOCUMENT_ROOT'] . session_id() . $_SERVER['REMOTE_ADDR']);

	// * session hijack protection
	if(isset($_SESSION['virtual_id_card'])) {
		if($_SESSION['virtual_id_card'] != $hash) {
			session_destroy();
			redirect('http://www.localhost/manager/');
			trigger_error('Session hijack', E_USER_ERROR);
			exit();
		}
	}
	else {
		$_SESSION['virtual_id_card'] = $hash;
	}

	unset($hash);

	// log
	if(isset($_SESSION['employee'])) {
		ini_set('log_errors', '1');
		ini_set('error_log', 'web/infologs/u'.$_SESSION['employee']['id'].'.log');
	}

	// exit
	if(isset($_GET['odjava'])) {
		session_destroy();

		file_put_contents('web/chat_rooms/'.$_SESSION['employee']['id'].'.log', (time() - 301));

		logw('Izlazim iz sustava...');

		redirect('http://localhost/manager/');
		exit();
	}

	file_put_contents('web/chat_rooms/'.$_SESSION['employee']['id'].'.log', time());

	if(get_magic_quotes_gpc() && isset($_GET['q'])) {
		$_GET['q'] = stripslashes($_GET['q']);
	}
}

function timeformat($timestamp)
{
	$format = ucfirst(strftime('%A, %d. %B', $timestamp));

	if(substr($format, 1, 7) == 'etvrtak') {
		$format = substr_replace($format, 'Č', 0, 1);
	}

	return $format;
}

function meta_redirect($url)
{
	echo "<meta http-equiv=refresh content=\"0; URL=$url\">";
}

function get_menu_options($options, $default = null, $keys_values = false)
{
	if(!is_array($options)) {
		trigger_error('get_select_menu_options() expects parameter 1 to be array, '.gettype($options).' given', E_USER_WARNING);
		return false;
	}

	$menu = '';

	if($keys_values) {
		foreach($options as $k => $v) {
			$selected = ($k == $default) ? ' selected="selected"' : '';
			$menu .= '<option value="' . $k . '"' . $selected . '>' . $v . '</option>';
		}
	}
	else {
		foreach($options as $option) {
			$selected = ($option == $default) ? ' selected="selected"' : '';
			$menu .= '<option value="' . $option . '"' . $selected . '>' . $option . '</option>';
		}
	}

	return $menu;
}

function redirect($url)
{
	if(!is_string($url)) {
		trigger_error('redirect() expects parameter 1 to be string, ' . gettype($url) . ' given', E_USER_WARNING);
		return false;
	}

	if($url == '') {
		trigger_error('redirect() expects parameter 1 to be non-empty', E_USER_WARNING);
		return false;
	}

	if(!parse_url($url)) {
		trigger_error('redirect() expects parameter 1 to be a valid URL', E_USER_WARNING);
		return false;
	}

	// these may occur so we want to remove them
	$url = str_replace('&amp;', '&', $url);
	// close session if open properly
	if(session_id() != '') {
		session_write_close();
	}

	// set location header, this will redirect
	header('Location: ' . $url);
	exit();
}

function logw($msg)
{
	$log = 'web/infologs/u'.$_SESSION['employee']['id'].'.log';
	$all_msgs = file($log);
	$all_msgs[] = strftime('[%d-%b-%Y %H:%M:%S] ') . "$msg\r\n";
	//array_unshift($all_msgs, "$msg\r\n");

	$all_msgs = array_slice($all_msgs, -40, 40);

	return file_put_contents($log, implode('', $all_msgs));
}

?>