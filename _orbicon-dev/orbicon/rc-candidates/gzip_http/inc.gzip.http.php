<?php

	/**
	 * Enter description here...
	 *
	 * @param int $level
	 * @return string
	 */
    function gzip_http_encode($level = 6)
	{
		if(!is_int($level)) {
			trigger_error('', E_USER_WARNING);
			return false;
		}

		// sanity checks
		if($level > 9) {
			$level = 9;
		}
		elseif ($level < 0) {
			$level = 0;
		}

		// function available?
		if(!function_exists('gzcompress')) {
			trigger_error('gzip_http_encode() requires gzcompress()', E_USER_WARNING);
			return false;
		}

		// headers already sent?
		if(headers_sent()) {
			return false;
		}

		// everyhing ok with php connection?
		if(connection_status() !== 0) {
			return false;
		}

		// does our client user agent support gzip?
		if(!get_gzip_accepted()) {
			return false;
		}

 		// zlib output turned on?
		if(ini_get('zlib.output_compression') == '1') {
			return false;
		}

		// turned on already?
		if(ini_get('output_handler') == 'ob_gzhandler') {
			return false;
		}

		// contents available?
		$size = ob_get_length();
		if(!function_exists('ob_get_clean')) {
			include DOC_ROOT . '/orbicon/3rdParty/php-compat/ob_get_clean.php';
		}

		$contents = ob_get_clean();
		if($contents === false) {
			return false;
		}

		// send proper headers
		header('Content-Encoding: ' . $encoding, true);
		header('Vary: Accept-Encoding', true);

		// output gziped content
		echo "\x1f\x8b\x08\x00\x00\x00\x00\x00" .	// gzip header
			substr(gzcompress($contents, $level), 0, -4) .	// substr -4 trailing bytes aren't needed
			pack('V', crc32($contents)) .			// crc32
			pack('V', $size);						// size
    }

	/**
	 * Test broswer accept encoding headers for Accept-Encoding: gzip
	 * returns gzip or x-gzip on success or FALSE otherwise
	 *
	 * @return string
	 */
	function get_gzip_accepted()
	{
		$accept_encoding = trim($_SERVER['HTTP_ACCEPT_ENCODING']);

		if($accept_encoding == '') {
			return false;
		}

		if(strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') === false) {
			return false;
		}

		// test for file type. I wish I could get HTTP response headers.
		$magic = substr(ob_get_contents(), 0, 4);
		if (substr($magic, 0, 2) == '^_') {
			// gzip data
			return false;
		}
		else if(substr($magic, 0, 3) == 'GIF') {
			// gif images
			return false;
		}
		else if(substr($magic, 0, 2) == "\xFF\xD8") {
			// jpeg images
			return false;
		}
		else if(substr($magic, 0, 4) == "\x89PNG") {
			// png images
			return false;
		}
		else if(substr($magic, 0, 3) == 'FWS') {
			// don't gzip Shockwave Flash files. Flash on Windows incorrectly
			// claims it accepts gzip'd content.
			return false;
		}
		else if(substr($magic, 0, 2) == 'PK') {
			// pk zip file
			return false;
		}

		$encoding = (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') === false) ? 'gzip' : 'x-gzip';
		return $encoding;
	}

?>