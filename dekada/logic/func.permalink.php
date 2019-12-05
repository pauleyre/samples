<?php

	function permalink($input, $convert_cro = true, $lowercase = true)
	{
		// quick exit
		if($input == '') {
			return '';
		}

		$output = trim($input);
		unset($input);

		$replace = array(

		// remove
		'*' => '',
		'>' => '',
		'<' => '',
		'|' => '',
		'"' => '',
		'\'' => '',
		'~' => '',
		'?' => '',
		'#' => '',
		'%' => '',
		'$' => '',
		'!' => '',
		'“' => '',
		'”' => '',
		'’' => '',
		'…' => '',
		// remove these as well. users used them!
		"\r" => '',
		"\n" => '',
		"\t" => '',
		// unwise
		'{' => '',
		'}' => '',
		'\\' => '',
		'^' => '',
		'[' => '',
		']' => '',
		'`' => '',
		// replace
		'=' => '-',
		'&' => '-',
		'+' => '-',
		' ' => '-',
		'/' => '-',
		':' => '-',
		';' => '-',
		'@' => '-',
		',' => '-',
		'.' => '-',
		'—' => '-'
		);

		if($convert_cro) {
			$replace = array_merge($replace, array(
			// cro
		'č' => 'c',
		'ć' => 'c',
		'ž' => 'z',
		'š' => 's',
		'đ' => 'dj',
		'Č' => 'C',
		'Ć' => 'C',
		'Ž' => 'Z',
		'Š' => 'S',
		'Đ' => 'Dj'
			));
		}

		$for_removal = array_keys($replace);
		$for_replacement = array_values($replace);

		$output = str_replace($for_removal, $for_replacement, $output);

		if($lowercase) {
			$output = strtolower($output);
		}

		$double_m = strpos($output, '--');
		while($double_m !== false) {
			$output = str_replace('--', '-', $output);
			$double_m = strpos($output, '--');
		}

		// remove dot or minus at the end
		$last = substr($output, -1, 1);
		while(($last == '.') || ($last == '-')) {
			$output = substr($output, 0, -1);
			$last = substr($output, -1, 1);
		}

		return $output;
	}

?>