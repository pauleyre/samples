<?php

	function http_build_query($formdata, $numeric_prefix = null, $key = null)
	{
		$res = array();
		foreach((array) $formdata as $k => $v) {
			$tmp_key = urlencode(is_int($k) ? $numeric_prefix.$k : $k);
			if($key) {
				$tmp_key = $key.'['.$tmp_key.']';
			}

			if(is_array($v) || is_object($v) ) {
				$res[] = http_build_query($v, null /* or $numeric_prefix if you want to add numeric_prefix to all indexes in array*/, $tmp_key);
			}
			else {
				$res[] = $tmp_key . '=' . urlencode($v);
			}
			/*
			If you want, you can write this as one string:
			$res[] = ( ( is_array($v) || is_object($v) ) ? http_build_query($v, null, $tmp_key) : $tmp_key."=".urlencode($v) );
			*/
		}

		$separator = ini_get('arg_separator.output');
		return implode($separator, $res);
	}

?>