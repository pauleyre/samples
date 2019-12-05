<?php

function php_combined_lcg()
{
	$tv = gettimeofday();
	$lcg['s1'] = $tv['sec'] ^ (~$tv['usec']);
	$lcg['s2'] = posix_getpid();

	$q = (int) ($lcg['s1'] / 53668);
	$lcg['s1'] = (int) (40014 * ($lcg['s1'] - 53668 * $q) - 12211 * $q);
	if ($lcg['s1'] < 0)
		$lcg['s1'] += 2147483563;

	$q = (int) ($lcg['s2'] / 52774);
	$lcg['s2'] = (int) (40692 * ($lcg['s2'] - 52774 * $q) - 3791 * $q);
	if ($lcg['s2'] < 0)
		$lcg['s2'] += 2147483399;

	$z = (int) ($lcg['s1'] - $lcg['s2']);
	if ($z < 1) {
		$z += 2147483562;
	}

	return $z * 4.656613e-10;
}

function php_compat_session_regenerate_id()
{
	$tv = gettimeofday();
	$buf = sprintf("%.15s%ld%ld%0.8f", $_SERVER['REMOTE_ADDR'], $tv['sec'], $tv['usec'], php_combined_lcg() * 10);
	session_id(md5($buf));
	if (ini_get('session.use_cookies'))
		setcookie('PHPSESSID', session_id(), NULL, '/');
	return true;
}

if(!function_exists('session_regenerate_id')) {
	function session_regenerate_id()
	{
		return php_compat_session_regenerate_id();
	}
}

?>