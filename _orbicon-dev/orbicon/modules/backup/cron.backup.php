<?php
/**
 * Backup cron
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @subpackage ZSEStockExchCron
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-03-22
 */

	/*----- ATTENTION! -------------------------------------------------
	 | Include before wherever DOC_ROOT required.
	 |------------------------------------------------------------------*/
	if(!defined('DOC_ROOT')) {
		// we'll need this info
		$dir = dirname(dirname(__FILE__));

		$file = $dir . '/index.php';
		$license = $dir . '/license.php';
		$found = false;

		while(!$found) {

			$dir = dirname(dirname($file));

			$license = $dir . '/license.php';
			$file = $dir . '/index.php';

			if(is_file($file) && is_file($license)) {
				$found = true;
				break;
			}
		}

		$request_uri = $_SERVER['REQUEST_URI'];

		$left = str_replace($dir, '', dirname(__FILE__));
		$left = str_replace('\\', '/', $left);
		$left = str_replace($left, '', $request_uri);
		// get rid of query
		$left = explode('?', $left);
		// file or no file?
		$left = (strpos($left[0], '.php')) ? dirname($left[0]) : $left[0];
		// strip forward slash as well
		$left = (substr($left, -1, 1) == '/') ? substr($left, 0, -1) : $left;

		define('ORBX_URI_PATH', $left);
		define('DOC_ROOT', $dir);
		unset($left, $request_uri);
	}
	//----- ATTENTION! ENDS -------------------------------------------------

	// core include
	require_once DOC_ROOT . '/orbicon/class/inc.core.php';

	// lib include
	require DOC_ROOT . '/orbicon/modules/backup/inc.backup.php';

	$orbx_log->dwrite('starting cron job' . basename(__FILE__), __LINE__, __FUNCTION__);

	// some configuration
	set_time_limit(600);
	ini_set('memory_limit', '128M');

?>

	This is a command line PHP script with no options.

	Usage:

	php -f <?php echo $argv[0]; ?>

	Backing up "<?php echo ORBX_SITE_URL; ?>". Please stand by...

<?php
	echo "\n-OUTPUT" . str_repeat('-', 30)."\n";

	$dbdump = DOC_ROOT . '/site/dbdump.' . date('Y-m-d') . '-'. uniqid(md5(rand()), true) . '.sql';
	$filedump = DOC_ROOT . '/site/filedump.' . date('Y-m-d') . '-' . uniqid(md5(rand()), true) . '.tar.gz';

	// benchmark start
	$bstarttime = explode(' ', microtime());
	$bstarttime = $bstarttime[1] + $bstarttime[0];

	echo 'backing up database into ' . $dbdump;
	db_dump('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, base64_decode(DB_PASS), $dbdump);

	echo 'backing up files into ' . $filedump;
	file_dump(DOC_ROOT . '/site', $filedump);

	// finish benchmark
	$bmtime = explode(' ', microtime());
	$btotaltime = $bmtime[0] + $bmtime[1] - $bstarttime;
	$benchmark_msg = 'finished backing up "' . ORBX_SITE_URL . '" in '.rounddown($btotaltime, 2).'s ('.$btotaltime.')';

	// cleanup
	$to_unlink = glob(DOC_ROOT . '/site/{dbdump.}*{.sql}', GLOB_BRACE);
	$to_unlink = array_merge($to_unlink, glob(DOC_ROOT . '/site/{filedump.}*{.tar.gz}', GLOB_BRACE));

	foreach($to_unlink as $file) {
		// delete those older than 8 days (691200 seconds)
		if((time() - filemtime($file)) > 691200) {
			$orbx_log->dwrite('cleaning up file' . $file, __LINE__, __FUNCTION__);
			if(!unlink($file)) {
				$orbx_log->ewrite('unable to remove ' . $file, __LINE__, __FUNCTION__);
			}
		}
	}

	echo "\n-OUTPUT END" . str_repeat('-', 30)."\n";
	echo $benchmark_msg . "\n";
	$orbx_log->dwrite('finished executing cron job', __LINE__, __FUNCTION__);

?>