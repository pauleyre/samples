<?php
/*-----------------------------------------------------------------------*
	Project........:	Orbicon X framework 2
	File...........:
	Version........:	1.0 (22/10/2006)
	Author.........:	Pavle Gardijan (pavle.gardijan@orbitum.net) - Orbitum d.o.o.
	Copyright......:	(c) 2006 Orbitum internet communications d.o.o. (www.orbitum.net)
	Created........:	01/07/2006
	Notes..........:
	Modified.......:
*-----------------------------------------------------------------------*/

	// Fix the . at the start, clear any duplicate slashes, and fix any trailing slash...
	function fix_relative_path($path)
	{
		return addslashes(preg_replace(array('~^\.([/\\\]|$)~', '~[/]+~', '~[\\\]+~', '~[/\\\]$~'), array($install_path . '$1', '/', '\\', ''), $path));
	}

	// create sfv (simple file verification) checksum for $content
	function sfv_checksum($content)
	{
		return (str_pad(strtoupper(dechex(crc32($content))), 8, '0', STR_PAD_LEFT));
	}

	// convert croatian utf8 to iso-8859-2
	function utf8_2_iso885_9_hr($input, $reverse = false)
	{
		if($input == '')
		{
			return '';
		}

		// * UTF-8
		$utf = array(
		"\xC4\x8D", "\xC4\x87", "\xC5\xBE", "\xC5\xA1", "\xC4\x91",		// * čćžšđ
		"\xC4\x8C", "\xC4\x86", "\xC5\xBD", "\xC5\xA0", "\xC4\x90");	// * ČĆŽŠĐ

		// * ISO-8859-2
		$iso = array(
		"\xE8", "\xE6", "\xBE", "\xB9", "\xF0",			// * čćžšđ
		"\xC8", "\xC6", "\xAE", "\xA9", "\xD0");		// * ČĆŽŠĐ

		$output = ($reverse) ?  str_replace($iso, $utf, $input) : str_replace($utf, $iso, $input);
		return $output;
	}

	// * compare decimal numbers
	function abs_decimal($float_a, $float_b)
	{
		$delta = 0.00001;

		if(abs($float_a - $float_b) < $delta) {
			return true;
		}
		return false;
	}

	// looks for the first occurence of $search in $subject and replaces it with $replace
	// doesn't support arrays
	function str_replace_once($search, $replace, $subject)
	{
		$pos = strpos($subject, $search);
		if($pos === false) {
			// Nothing found
			return $subject;
		}
		return substr_replace($subject, $replace, $pos, strlen($search));
	}

	// converts hex value into ascii equivalent
	function hex2bin($hex)
	{
		if($hex == '') {
			return '';
		}

		$result = '';
		$len = strlen($hex);

		for($i = 0; $i < $len; $i += 2) {
			$result .= chr(hexdec(substr($hex, $i, 2)));
		}

		return $result;
	}

	/**
	 *	Added from PHP5 In Preactice, Alen Novakovic-13/11/06
	 */
	// Script Timer Library - debugging mode
	// Decalsre a global array that we will be using to store this data
	$_timer_results = array();

	// A function that will add a new timing result to the global
	function _timer()
	{
		global $_timer_results;

		// Imidiately grab the time in useconds
		$curtime = microtime(true);

		// Grab a backtrace so we can see who call this
		$trace  = debug_backtrace();

		/**
		 * Now, the [0] entry refers to 'right now' in the backtrace, so we will
		 * use that to determine the filename and line #. But will look at the [1]
		 * entry if it exists, for the calling function name.
		 */
		$_timer_results[] = array(
								'line' => $trace[0]['line'],
								'file' => $trace[0]['file'],
								'func' => isset($trace[1]['function']) ? $trace[1]['function'] : '',
								'time' => $curtime
								);
	}

	/**
	 * Now create a function that will turn these results into a readeble text string.
	 * It will return this so that it can be dealt with as the program needs, either
	 * via displaying, adding as an HTML comment, or wathever.
	 */
	function _timer_text()
	{
		global $_timer_results;
		$result = 'Timing Results';

		// Start our rolling clock at the timestamp of the first entry
		$clock = @$_timer_results[0]['time'];

		// Now, loop through all entries in the timer results to create text
		foreach($_timer_results as $tr){
			// Calculate how long this one took, from the rolling clock
			$this_time = $tr['time'] - $clock;

			// Reset the clock for the next loop iteration
			$clock = $tr['time'];

			// Grab just the filename of the file
			$file_name = basename($tr['filename']);

			/**
			 * And to make output prettier, since we don't really need
			 * an extreme level of detail on the timing, let's convert
			 * the time to use 5 precision points
			 */
			$this_time = number_format($this_time, 5);

			// Now make abd add the string to the results
			$result .= '\n' . $this_time . ' secs - File: ' . $file_name . ' - Line: ' . $tr['line'];

			// If there was a calling function , add it
			if($tr['func']){
				$result .= '- calling Function: ' . $tr['func'];
			}

			// return  result
			return $result;
		}
	}

	function orbx_pack_all()
	{
		orbx_pack_css();
		orbx_pack_css_admin();
		orbx_pack_js();
		orbx_pack_js_admin();
		orbx_pack_js_rte();
	}

	function orbx_pack_css()
	{
		$css = array(
			/*DOC_ROOT . '/orbicon/3rdParty/yui/build/fonts/fonts-min.css',*/
			//DOC_ROOT . '/orbicon/gfx/basic.css',
			//DOC_ROOT . '/orbicon/gfx/infobox.css',
			DOC_ROOT . '/orbicon/3rdParty/yui/build/tabview/assets/skins/sam/tabview.css',
			DOC_ROOT . '/orbicon/3rdParty/yui/build/container/assets/skins/sam/container.css',
			DOC_ROOT . '/orbicon/3rdParty/yui/build/container/assets/container.css',
			DOC_ROOT . '/orbicon/3rdParty/yui/build/autocomplete/assets/skins/sam/autocomplete.css'/*,
			DOC_ROOT . '/site/gfx/css/main.css'*/

		);

		pack_txt_files($css, DOC_ROOT . '/orbicon/gfx/orbiconx.final.css');
	}

	function orbx_pack_css_admin()
	{
		$css = array(
			DOC_ROOT . '/orbicon/gfx/basic.css',
			DOC_ROOT . '/orbicon/gfx/orbicon.css',
			DOC_ROOT . '/orbicon/gfx/admin.about.css',
			DOC_ROOT . '/orbicon/gfx/admin.address_book.css',
			DOC_ROOT . '/orbicon/gfx/admin.advanced.css',
			DOC_ROOT . '/orbicon/gfx/admin.banners.css',
			DOC_ROOT . '/orbicon/gfx/admin.column.css',
			DOC_ROOT . '/orbicon/gfx/admin.contact.css',
			DOC_ROOT . '/orbicon/gfx/admin.css.css',
			DOC_ROOT . '/orbicon/gfx/admin.desktop.css',
			DOC_ROOT . '/orbicon/gfx/admin.editors.css',
			DOC_ROOT . '/orbicon/gfx/admin.errorlog.css',
			DOC_ROOT . '/orbicon/gfx/admin.gfxdir.css',
			DOC_ROOT . '/orbicon/gfx/admin.helpdesk.css',
			DOC_ROOT . '/orbicon/3rdParty/yui/build/calendar/assets/calendar.css',
			DOC_ROOT . '/orbicon/gfx/category-picker.css'
		);

		pack_txt_files($css, DOC_ROOT . '/orbicon/gfx/orbiconx.admin.final.css');
	}

	function orbx_pack_js()
	{
		$js = array(
			DOC_ROOT . '/orbicon/javascript/orbicon.effects.js',
			DOC_ROOT . '/orbicon/modules/polls/orbicon.poll.js',
			DOC_ROOT . '/orbicon/javascript/orbicon.ticker.js',
			//DOC_ROOT . '/orbicon/javascript/orbicon.suggest.js',
			DOC_ROOT . '/orbicon/javascript/orbicon.base.js',
			DOC_ROOT . '/orbicon/javascript/orbicon.rte_lite.js'
		);

		pack_txt_files($js, DOC_ROOT . '/orbicon/javascript/orbiconx.final.js');

		// append already compressed
		$target_filename = DOC_ROOT . '/orbicon/javascript/orbiconx.final.js';
		append_js(DOC_ROOT . '/orbicon/3rdParty/yui/build/utilities/utilities.js', $target_filename);
		append_js(DOC_ROOT . '/orbicon/3rdParty/yui/build/container/container-min.js', $target_filename, false);
		append_js(DOC_ROOT . '/orbicon/3rdParty/yui/build/cookie/cookie-min.js', $target_filename, false);
		append_js(DOC_ROOT . '/orbicon/3rdParty/yui/build/tabview/tabview-min.js', $target_filename, false);
		append_js(DOC_ROOT . '/site/gfx/js/sifr.js', $target_filename, false);
		//append_js(DOC_ROOT . '/site/gfx/js/sifr-config.js', $target_filename, false);
		append_js(DOC_ROOT . '/orbicon/modules/exchange-rates/NumberFormat154.js', $target_filename, false);
		append_js(DOC_ROOT . '/orbicon/3rdParty/ac/AC_OETags.js', $target_filename, false);
		append_js(DOC_ROOT . '/orbicon/modules/credit-calculator/render.credit.calc.js', $target_filename, false);
		append_js(DOC_ROOT . '/orbicon/modules/exchange-rates/render.exch_rates.js', $target_filename, false);
		append_js(DOC_ROOT . '/orbicon/modules/exch-rates-dialog/exch_rates.dialog.js', $target_filename, false);
		append_js(DOC_ROOT . '/orbicon/modules/savings_calculator/render.savings_calculator.js', $target_filename, false);
		append_js(DOC_ROOT . '/orbicon/modules/calculator/render.calculator.js', $target_filename, false);
		append_js(DOC_ROOT . '/orbicon/modules/calculator/calculate.js', $target_filename, false);
		append_js(DOC_ROOT . '/orbicon/modules/invest-ticker/invest.ticker.js', $target_filename, false);
		append_js(DOC_ROOT . '/site/gfx/site.js', $target_filename, false);

		gzip($target_filename);
	}

	function append_js($filename, $target, $top = true)
	{
		if(!is_file) {
			echo '<strong>ERROR: </strong> could not read ' . basename($filename) . '<br />';
		}
		else {
			chmod_unlock($target);
			$append = file_get_contents($filename);
			$source = file_get_contents($target);
			$r = fopen($target, 'wb');

			if($top) {
				fwrite($r, $append . "\n" . $source);
			}
			else {
				fwrite($r, $source . "\n" . $append);
			}

			fclose($r);
			chmod_lock($target);
			echo '<strong>appended</strong> ' . basename($filename) . '<br />';
		}
	}

	function orbx_pack_js_admin()
	{
		$js = array(
			DOC_ROOT . '/orbicon/javascript/orbicon.magister.mini_browser.update.js',
			DOC_ROOT . '/orbicon/javascript/orbicon.venus.mini_browser.update.js',
			DOC_ROOT . '/orbicon/javascript/orbicon.mercury.mini_browser.update.js',
			DOC_ROOT . '/orbicon/javascript/orbicon.base64.js',
			DOC_ROOT . '/orbicon/javascript/orbicon.venus.category.update.js',
			DOC_ROOT . '/orbicon/javascript/orbicon.magister.category.update.js',
			DOC_ROOT . '/orbicon/javascript/orbicon.mercury.category.update.js',
			DOC_ROOT . '/orbicon/javascript/orbicon.admin.editors.js',
			DOC_ROOT . '/orbicon/modules/news/orbicon.admin.news.js',
			DOC_ROOT . '/orbicon/modules/polls/orbicon.admin.polls.js',
			DOC_ROOT . '/orbicon/javascript/orbicon.list.manager.js',
			DOC_ROOT . '/orbicon/javascript/orbicon.related.js',
			DOC_ROOT . '/orbicon/modules/banners/orbicon.banners.update.js',
			DOC_ROOT . '/orbicon/javascript/orbicon.mini_browser.js',
			DOC_ROOT . '/orbicon/javascript/orbicon.admin.base.js',
			DOC_ROOT . '/orbicon/javascript/orbicon.clock.js',
			DOC_ROOT . '/orbicon/javascript/orbicon.admin.navigation.update.js'
		);

		pack_txt_files($js, DOC_ROOT . '/orbicon/javascript/orbiconx.admin.final.js');
	}

	function orbx_pack_js_rte()
	{
		$js = array(
			DOC_ROOT . '/orbicon/rte/rich_text_editor.js'
		);

		pack_txt_files($js, DOC_ROOT . '/orbicon/rte/rte.final.js');
	}

	function pack_txt_files($source_files, $target_filename)
	{
		$buffer = '';
		foreach($source_files as $file) {

			if(!is_file($file)) {
				echo '<strong>ERROR: </strong> could not read ' . basename($file) . '<br />';
			}
			else {
				echo '<em>compiled</em> ' . basename($file) . '<br />';
				$buffer .= file_get_contents($file)."\n";
			}
		}

		$ext = get_extension($source_files[0]);
		unset($files, $file);

		include_once DOC_ROOT.'/orbicon/modules/orbitum-finalbuilder/class.jsmart_compress.php';
		$compress = new jsmart_compress;

		if($ext == 'js') {
			$buffer = $compress->remove_js_comments($buffer);
		}
		else if($ext == 'css') {
			$buffer = $compress->remove_css_comments($buffer);
		}

		chmod_unlock($target_filename);
		$r = fopen($target_filename, 'wb');
		fwrite($r, $buffer);
		fclose($r);
		chmod_lock($target_filename);
		echo '<strong>compressed</strong> ' . basename($target_filename) . '<br />';
		gzip($target_filename);
		echo '<strong>gzipped</strong> ' . basename($target_filename) . '.gz<br />';
	}

	function harmonics()
	{
		$harmony = array(
			'.. .. . . . . ',
			'.. .... . . . ',
			'.. .... ..... ',
			'.. ...... ... ',
			'..............',
			'......... ....',
			'.... .... ....',
			'...... .......',
			'......... ....',
			'..   .........',
			'. .. . .. ....'
		);
	}

	/**
	 * emulate getallheaders which is not always available
	 *
	 * @return array
	 */
	if(!function_exists('getallheaders')) {
		function getallheaders()
		{
			foreach($_SERVER as $name => $value) {
				if(strpos($name, 'HTTP_') === 0) {
					$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
				}
			}
			return $headers;
		}
	}

?>