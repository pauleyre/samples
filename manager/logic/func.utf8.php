<?php
/**
 * UTF8 library
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @subpackage Global
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-07-01
 */

	function chr_utf8($code)
	{
		if($code < 0) {
			return false;
		}
		else if($code < 128) {
			return chr($code);
		}
		else if($code < 160) // Remove Windows Illegals Cars
		{
           if ($code==128) $code=8364;
           else if ($code==129) $code=160; // not affected
           else if ($code==130) $code=8218;
           else if ($code==131) $code=402;
           else if ($code==132) $code=8222;
           else if ($code==133) $code=8230;
           else if ($code==134) $code=8224;
           else if ($code==135) $code=8225;
           else if ($code==136) $code=710;
           else if ($code==137) $code=8240;
           else if ($code==138) $code=352;
           else if ($code==139) $code=8249;
           else if ($code==140) $code=338;
           else if ($code==141) $code=160; // not affected
           else if ($code==142) $code=381;
           else if ($code==143) $code=160; // not affected
           else if ($code==144) $code=160; // not affected
           else if ($code==145) $code=8216;
           else if ($code==146) $code=8217;
           else if ($code==147) $code=8220;
           else if ($code==148) $code=8221;
           else if ($code==149) $code=8226;
           else if ($code==150) $code=8211;
           else if ($code==151) $code=8212;
           else if ($code==152) $code=732;
           else if ($code==153) $code=8482;
           else if ($code==154) $code=353;
           else if ($code==155) $code=8250;
           else if ($code==156) $code=339;
           else if ($code==157) $code=160; // not affected
           else if ($code==158) $code=382;
           else if ($code==159) $code=376;
       }
       if ($code < 2048) return chr(192 | ($code >> 6)) . chr(128 | ($code & 63));
       else if ($code < 65536) return chr(224 | ($code >> 12)) . chr(128 | (($code >> 6) & 63)) . chr(128 | ($code & 63));
       else return chr(240 | ($code >> 18)) . chr(128 | (($code >> 12) & 63)) . chr(128 | (($code >> 6) & 63)) . chr(128 | ($code & 63));
   }

	/**
	 * Callback for preg_replace_callback('~&(#(x?))?([^;]+);~', 'html_entity_replace', $str);
	 *
	 * @param unknown_type $matches
	 * @return unknown
	 */
	function html_entity_replace($matches)
	{
		if($matches[2]) {
			return chr_utf8(hexdec($matches[3]));
		}
		else if($matches[1]) {
			return chr_utf8($matches[3]);
		}

		switch($matches[3]) {
			case 'nbsp': return chr_utf8(160);
			case 'iexcl': return chr_utf8(161);
			case 'cent': return chr_utf8(162);
			case 'pound': return chr_utf8(163);
			case 'curren': return chr_utf8(164);
			case 'yen': return chr_utf8(165);
			//... etc with all named HTML entities
		}

		return false;
   }

   /**
    * covert HTML entities to UTF-8
    * (because of the html_entity_decode() bug with UTF-8)
    *
    * @param string $string
    * @return string
    */
   function htmlutf8($string) //
   {
	   	// quick exit
   		if($string == '') {
			return '';
		}

		$string = preg_replace_callback('~&(#(x?))?([^;]+);~', 'html_entity_replace', $string);
		return $string;
   }

	/**
	 * replaces ALL high UTF-8 characters with HTML entities
	 *
	 * @param string $string
	 * @return string
	 */
	function utf8html($string)
	{
		// quick exit
		if($string == '') {
			return '';
		}

		$string = preg_replace('/([\xc0-\xdf].)/se', "'&#' . ((ord(substr('$1', 0, 1)) - 192) * 64 + (ord(substr('$1', 1, 1)) - 128)) . ';'", $string);
		$string = preg_replace('/([\xe0-\xef]..)/se', "'&#' . ((ord(substr('$1', 0, 1)) - 224) * 4096 + (ord(substr('$1', 1, 1)) - 128) * 64 + (ord(substr('$1', 2, 1)) - 128)) . ';'", $string);
		return $string;
	}

	function utf8_raw_url_decode($input)
	{
		// quick exit
		if($input == '') {
			return '';
		}

		$decodedStr = '';
		$pos = 0;
		$len = strlen($input);

		while($pos < $len) {
			$charAt = substr($input, $pos, 1);
			if($charAt == '%') {
				$pos ++;
				$charAt = substr($input, $pos, 1);

				if($charAt == 'u') {
					// we got a unicode character
					$pos ++;
					$unicodeHexVal = substr($input, $pos, 4);
					$unicode = hexdec($unicodeHexVal);
					$entity = '&#'.$unicode.';';
					$decodedStr .= utf8_encode($entity);
					$pos += 4;
				}
				else {
					// we have an escaped ascii character
					$hexVal = substr($input, $pos, 2);
					$decodedStr .= chr(hexdec($hexVal));
					$pos += 2;
				}
			}
			else {
				$decodedStr .= $charAt;
				$pos ++;
			}
		}
		return $decodedStr;
	}

?>