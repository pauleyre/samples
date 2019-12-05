<?php
/**
 * Backup library
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @subpackage Backup
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-09-22
 */

/**
 * Dump DB into SQL file. Currently supports MySQL
 *
 * @param string $dsn
 * @param string $username
 * @param string $password
 * @param string $dest
 * @return bool
 */
function db_dump($dsn, $username, $password, $dest)
{
	if($dsn == '') {
		trigger_error('db_dump() expects parameter 1 to be non-empty', E_USER_ERROR);
		return false;
	}

	if($username == '') {
		trigger_error('db_dump() expects parameter 2 to be non-empty', E_USER_ERROR);
		return false;
	}

	if($dest == '') {
		trigger_error('db_dump() expects parameter 4 to be non-empty', E_USER_ERROR);
		return false;
	}

	list($dbtype, $args) = explode(':', $dsn);
	list($host, $dbname) = explode(';', $args);
	$host = explode('=', $host);
	$host = $host[1];
	$dbname = explode('=', $dbname);
	$dbname = $dbname[1];

	if($dbtype == 'mysql') {
		// add password if we have one
		$password = ($password == '') ? $password : ' --password=' . escapeshellarg($password);

		$cmd = 'mysqldump -u ' . escapeshellarg($username) . $password . ' --opt ' . escapeshellarg($dbname) .' > ' . escapeshellarg($dest);
	}
	else {
		trigger_error('Database type not supported, ' . $dbtype . ' given', E_USER_ERROR);
		return false;
	}

	if(system($cmd) === false) {
		trigger_error('Undetermined system() error', E_USER_ERROR);
		return false;
	}

	return is_file($dest);
}

/**
 * Create tar archive from $source
 *
 * @param string $source
 * @param string $dest
 * @param bool $gzip
 * @return bool
 */
function file_dump($source, $dest, $gzip = true)
{
	if($source == '') {
		trigger_error('db_dump() expects parameter 1 to be non-empty', E_USER_ERROR);
		return false;
	}

	if($dest == '') {
		trigger_error('db_dump() expects parameter 2 to be non-empty', E_USER_ERROR);
		return false;
	}

	$cmd = array();
	$cmd[] = 'tar';
	$cmd[] = ($gzip) ? '-czf' : '-cf';
	$cmd[] = escapeshellarg($source);
	$cmd[] = escapeshellarg($dest);
	$cmd = implode(' ', $cmd);

	if(system($cmd) === false) {
		trigger_error('Undetermined system() error', E_USER_ERROR);
		return false;
	}

	return is_file($dest);
}

?>