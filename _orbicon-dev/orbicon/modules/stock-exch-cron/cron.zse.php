<?php
/**
 * Zagreb Stock Exchange cron fetcher
 * @author Pavle Gardijan <pavle.gardijan@hpb.hr>
 * @copyright Copyright (c) 2007, HPB d.d.
 * @package OrbiconMOD
 * @subpackage ZSEStockExchCron
 * @version 1.00
 * @link http://hpb.hr
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

		$request_uri = @$_SERVER['REQUEST_URI'];

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

	$orbx_log->dwrite('starting cron job' . basename(__FILE__), __LINE__, __FUNCTION__);

	// snoopy, we'll need it
	require_once DOC_ROOT . '/orbicon/3rdParty/snoopy/Snoopy.class.php';
	$bot = new Snoopy;
	
	$mod_params = $orbx_mod->load_info('stock-exch-cron');

	$zse_url = $mod_params['module']['target_url'];
	$url = (empty($argv[1])) ? $zse_url : $argv[1];

	$local_copy = DOC_ROOT . '/site/mercury/zse.report.xml';
	
	echo date('r')."\n";
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
		$bot->fetch($url);
		$contents = $bot->results;
	}

	/*if(adler32($contents) == adler32(file_get_contents($local_copy))) {
			echo 'Local copy matches remote data. Exiting...' . "\n";
			session_write_close();
			session_destroy();
			echo date('r')."\n";
			return false;
	}*/

	$cache = fopen($local_copy, 'wb');
	fwrite($cache, $contents);
	fclose($cache);
	unset($contents);

	require_once DOC_ROOT . '/orbicon/3rdParty/xmlparser/parser.php';

	//Set up the parser object
	$parser = new XMLParser(file_get_contents($local_copy));
	
	//Work the magic...
	$parser->Parse();

  	$new = array();
	$new_txt = array();

	// access children
	foreach($parser->document->orderbook as $stock) {

			if($stock->marketname[0]->tagData == 'EQTY') {

				/*$stock->last[0]->tagData = str_replace('.', '', $stock->last[0]->tagData);
				$stock->last[0]->tagData = str_replace(',', '.', $stock->last[0]->tagData);
				$stock->last[0]->tagData = floatval($stock->last[0]->tagData);

				$stock->pricechange[0]->tagData = str_replace('.', '', $stock->pricechange[0]->tagData);
				$stock->pricechange[0]->tagData = str_replace(',', '.', $stock->pricechange[0]->tagData);
				$stock->pricechange[0]->tagData = floatval($stock->pricechange[0]->tagData);

				$stock->pricechange[0]->tagData = number_format($stock->pricechange[0]->tagData, 2, ',', '.');
				$stock->last[0]->tagData = number_format($stock->last[0]->tagData, 2, ',', '.');*/

				if($stock->ticker[0]->tagData && !empty($stock->last[0]->tagData) && ($stock->last[0]->tagData != '0,00') && ($stock->pricechange[0]->tagData != '~')) {
					if($stock->pricechange[0]->tagData >= 0.00) {
						$code_span = '<span class="tckrgrn">'.$stock->pricechange[0]->tagData.'</span>';
					}
					else {
						$code_span = '<span class="red">'.$stock->pricechange[0]->tagData.'</span>';
					}
					
					$new[] = "<a class=\"black\" href=\"http://www.zse.hr/default.aspx?id=10006&amp;dionica={$stock->ticker[0]->tagData}\" target=\"_blank\">{$stock->ticker[0]->tagData}</a> {$stock->last[0]->tagData} $code_span";
					$new_txt[] = $stock->ticker[0]->tagData . ' ' . $stock->last[0]->tagData . ' ' . $stock->pricechange[0]->tagData;
				}
			}
		}

		$new = implode(' | ', $new);
		$new = trim(str_replace(array("\r", "\t", "\n"), '', $new));

		$new_txt = implode("\n", $new_txt);

	/*if(!empty($mod_params['module']['format'])) {
		
		$contents = explode('-+-', $contents);
		$new = array();
		$new_txt = array();

		foreach($contents as $stock) {
			list($code, $name) = explode('|', $stock);

			$bot->fetch('http://www.zse.hr/widget.aspx?dionice=' . $code);
			$stock_contents = $bot->results;
			
			$stock_contents = explode('|', $stock_contents);
			$stock_contents[5] = str_replace('.', '', $stock_contents[5]);
			$stock_contents[5] = str_replace(',', '.', $stock_contents[5]);
			$stock_contents[5] = floatval($stock_contents[5]);

			$stock_contents[3] = str_replace('.', '', $stock_contents[3]);
			$stock_contents[3] = str_replace(',', '.', $stock_contents[3]);
			$stock_contents[3] = floatval($stock_contents[3]);

			$stock_contents[3] = number_format($stock_contents[3], 2, ',', '.');
			$stock_contents[5] = number_format($stock_contents[5], 2, ',', '.');
			
			if($stock_contents[0] && !empty($stock_contents[5]) && ($stock_contents[5] != '0,00') && ($stock_contents[3] != '~')) {
				if($stock_contents[3] >= 0.00) {
					$code_span = '<span class="tckrgrn">'.$stock_contents[3].'</span>';
				}
				else {
					$code_span = '<span class="red">'.$stock_contents[3].'</span>';
				}
				
				$stock_contents[4] = utf8_html_entities($stock_contents[4]);
				$new[] = "<a class=\"black\" href=\"http://www.zse.hr/default.aspx?id=10006&amp;dionica={$stock_contents[0]}\" target=\"_blank\" title=\"{$stock_contents[4]}\">{$stock_contents[0]}</a> {$stock_contents[5]} $code_span";
				$new_txt[] = $stock_contents[0] . ' ' . $stock_contents[5] . ' ' . $stock_contents[3];
			}
		}

		$new = implode(' | ', $new);
		$new = trim(str_replace(array("\r", "\t", "\n"), '', $new));

		$new_txt = implode("\n", $new_txt);
	}
	else {
		$new = $contents;
		unset($contents);
	}*/

	// finish benchmark
	$bmtime = explode(' ', microtime());
	$btotaltime = $bmtime[0] + $bmtime[1] - $bstarttime;
	$benchmark_msg = 'fetched "' . $url . '" in '.rounddown($btotaltime, 2).'s ('.$btotaltime.')';

	if($new) {
		$save = fopen(DOC_ROOT . '/site/mercury/zse.report', 'wb');
		fwrite($save, $new);
		fclose($save);
		unset($save);
	}

	if($new_txt) {
		$save = fopen(DOC_ROOT . '/site/mercury/zse.report.txt', 'wb');
		fwrite($save, $new_txt);
		fclose($save);
		unset($save);
	}

	echo "\n-OUTPUT" . str_repeat('-', 30)."\n";
	echo $new;
	echo "\n-OUTPUT END" . str_repeat('-', 30)."\n";
	echo $benchmark_msg . "\n";
	$orbx_log->dwrite('finished executing cron job', __LINE__, __FUNCTION__);

	session_write_close();
	session_destroy();
	echo date('r')."\n";
?>