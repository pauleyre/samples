<?php
/**
 * Zagreb Stock Exchange cron fetcher
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

	// go back until we get a working day (mon, tue, wed, thu, fri)
	$minus_days = 1;
	$yesterday = mktime(0, 0, 0, date('m'), (date('d') - $minus_days), date('Y'));

	$last_available = date('w', $yesterday);
	//echo $last_available.'|'.$minus_days;
	$found = false;
	while($found === false) {
		if(($last_available != 0) && ($last_available != 6)) {
			$found = true;
			break;
		}

		$minus_days += 1;
		$yesterday = mktime(0, 0, 0, date('m'), (date('d') - $minus_days), date('Y'));
		$last_available = date('w', $yesterday);
		//echo $last_available.'|'.$minus_days;
	}

	// core include
	require_once DOC_ROOT . '/orbicon/class/inc.core.php';

	$orbx_log->dwrite('starting cron job' . basename(__FILE__), __LINE__, __FUNCTION__);

	// snoopy, we'll need it
	require_once DOC_ROOT . '/orbicon/3rdParty/snoopy/Snoopy.class.php';
	$bot = new Snoopy;

	$mod_params = $orbx_mod->load_info('stock-exch-cron');

	$zse_url = $mod_params['module']['target_url'] . '?date=' . date('Y-m-d', $yesterday);
	$url = (empty($argv[1])) ? $zse_url : $argv[1];

?>

	This is a command line PHP script with one option.

	Usage:

	php -f <?php echo $argv[0]; ?> <url>

	<url> must be a proper URL address. Defaults to
	"<?php echo $zse_url; ?>"

	fetching "<?php echo $url; ?>". please stand by...

<?php
	// benchmark start
	$bstarttime = explode(' ', microtime());
	$bstarttime = $bstarttime[1] + $bstarttime[0];

	$contents = file_get_contents($url);

	// we failed, go for it snoopy
	if($contents === false) {
		$bot->fetch($filename);
		$contents = $bot->results;
	}

	// finish benchmark
	$bmtime = explode(' ', microtime());
	$btotaltime = $bmtime[0] + $bmtime[1] - $bstarttime;
	$benchmark_msg = 'fetched "' . $url . '" in '.rounddown($btotaltime, 2).'s ('.$btotaltime.')';

	if(!empty($mod_params['module']['format'])) {
		$contents = strip_tags($contents, '<td>');
		$contents = explode('"> ', $contents);

		$new = array();
		foreach($contents as $line) {
			// now get stock, value and change
			$line = explode('</td>', $line);
			// get stock code
			$stock = $line[0];
			$value = $line[1];
			$change = $line[2];

			$new[] = strip_tags("$stock $value $change");
		}

		$new = implode(' | ', $new);

		$new = explode('|', $new);
		array_shift($new);
		$new = trim(str_replace(array("\r", "\t", "\n"), '', implode('|', $new)));
	}
	else {
		$new = $contents;
		unset($contents);
	}

	if(!empty($new)) {
		$save = fopen(DOC_ROOT . '/site/mercury/zse.report', 'wb');
		fwrite($save, $new);
		fclose($save);
	}

	echo "\n-OUTPUT" . str_repeat('-', 30)."\n";
	echo $new;
	echo "\n-OUTPUT END" . str_repeat('-', 30)."\n";
	echo $benchmark_msg . "\n";
	$orbx_log->dwrite('finished executing cron job', __LINE__, __FUNCTION__);

	session_write_close();
	session_destroy();

?>