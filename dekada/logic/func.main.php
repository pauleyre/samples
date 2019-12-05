<?php

function update_total_qs()
{
	if(date('G') == 4) {
		sql_update('UPDATE main SET total_qs = (SELECT COUNT(id) FROM question WHERE (live = 1))');
	}
}

function total_qs()
{
	$total_qs = sql_assoc('SELECT total_qs FROM main');
	return number_format($total_qs['total_qs'], 0, ',', ' ');
}

function main()
{
	error_reporting(0);

	header('Content-Type: text/html; charset=UTF-8', true);
	header('Content-Language: hr', true);

	session_start();

	// * session fixation check
	if(!isset($_SESSION['session_started'])) {
		session_regenerate_id();
		$_SESSION['session_started'] = true;
	}

	$hash = md5($_SERVER['HTTP_USER_AGENT'] . @$_SERVER['HTTP_ACCEPT_CHARSET'] . $_SERVER['DOCUMENT_ROOT'] . session_id() . $_SERVER['REMOTE_ADDR']);

	// * session hijack protection
	if(isset($_SESSION['virtual_id_card'])) {
		if($_SESSION['virtual_id_card'] != $hash) {
			session_destroy();
			header('Location: http://www.dekada.org/');
			trigger_error('Session hijack', E_USER_ERROR);
			exit();
		}
	}
	else {
		$_SESSION['virtual_id_card'] = $hash;
	}

	unset($hash);

	if(isset($_GET['odjava'])) {
		session_destroy();

		header('Location: http://www.dekada.org/');
		exit();
	}

	setlocale(LC_TIME, 'hr_HR.UTF8', 'hr.UTF8', 'hrv_HRV.UTF8', 'cro.UTF8');

	if(get_magic_quotes_gpc() && isset($_GET['q'])) {
		$_GET['q'] = stripslashes($_GET['q']);
	}
}

function timeformat($timestamp)
{
	$format = ucfirst(strftime('%A, %d. %B', $timestamp));

	/*if(substr($format, 1, 7) == 'etvrtak') {
		$format = substr_replace($format, 'Č', 0, 1);
	}*/

	return $format;
}

function get_username()
{
	if(!empty($_SESSION['member']['id'])) {
		return $_SESSION['member']['name'];
	}
	elseif(isset($_COOKIE['dekada_guestname'])) {
		$c_name = trim($_COOKIE['dekada_guestname']);
		if($c_name) {
			return $_COOKIE['dekada_guestname'];
		}
		return 'Anonimni';
	}

	return 'Anonimni';
}

?>