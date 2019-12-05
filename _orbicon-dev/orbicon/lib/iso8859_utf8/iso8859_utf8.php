<?php

	/**
	 * Enter description here...
	 *
	 * @param string $str
	 * @param string $from_encoding
	 * @return string
	 */
	function utf2iso8859_hr($str, $from_encoding = 'ISO-8859-2')
	{
		if($str == '') {
			return '';
		}

		// * UTF-8
		$utf_s = array("\xC4\x8D", "\xC4\x87", "\xC5\xBE", "\xC5\xA1", "\xC4\x91");		// * čćžšđ
		$utf_c = array("\xC4\x8C", "\xC4\x86", "\xC5\xBD", "\xC5\xA0", "\xC4\x90");		// * ČĆŽŠĐ

		// * ISO-8859-2
		if($from_encoding == 'ISO-8859-2') {
			$iso_s = array("\xE8", "\xE6", "\xBE", "\xB9", "\xF0");		// * čćžšđ
			$iso_c = array("\xC8", "\xC6", "\xAE", "\xA9", "\xD0");		// * ČĆŽŠĐ
		}
		// * ISO-8859-1
		elseif ($from_encoding == 'ISO-8859-1') {
			$iso_s = array("\xC4\x8D", "\xC4\x87", "\x9E", "\x9A", "\xC4\x91");		// * čćžšđ
			$iso_c = array("\xC4\x8C", "\xC4\x86", "\x8E", "\x8A", "\xC4\x90");		// * ČĆŽŠĐ
		}

		$str = str_replace($utf_s, $iso_s, $str);
		$str = str_replace($utf_c, $iso_c, $str);
		return $str;
	}

	/**
	 * Enter description here...
	 *
	 * @param string $str
	 * @param string $from_encoding
	 * @return string
	 */
	function iso88592utf_hr($str, $from_encoding = 'ISO-8859-2')
	{
		if($str == '') {
			return '';
		}

		// * UTF-8
		$utf_s = array("\xC4\x8D", "\xC4\x87", "\xC5\xBE", "\xC5\xA1", "\xC4\x91");		// * čćžšđ
		$utf_c = array("\xC4\x8C", "\xC4\x86", "\xC5\xBD", "\xC5\xA0", "\xC4\x90");		// * ČĆŽŠĐ

		// * ISO-8859-2
		if($from_encoding == 'ISO-8859-2') {
			$iso_s = array("\xE8", "\xE6", "\xBE", "\xB9", "\xF0");		// * čćžšđ
			$iso_c = array("\xC8", "\xC6", "\xAE", "\xA9", "\xD0");		// * ČĆŽŠĐ
		}
		// * ISO-8859-1
		elseif ($from_encoding == 'ISO-8859-1') {
			$iso_s = array("\xC4\x8D", "\xC4\x87", "\x9E", "\x9A", "\xC4\x91");		// * čćžšđ
			$iso_c = array("\xC4\x8C", "\xC4\x86", "\x8E", "\x8A", "\xC4\x90");		// * ČĆŽŠĐ
		}

		$str = str_replace($iso_s, $utf_s, $str);
		$str = str_replace($iso_c, $utf_c, $str);
		return $str;
	}

?>