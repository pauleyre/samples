<?php

include_once 'logic/func.utf8.php';

function clean($str)
{
	$str = str_replace(array('-', ',', ':', '?', '!', '.', '"', '\''), ' ', $str);
	$r = array('tko', 'zašto', 'što', 'Što', 'koga', 'čega', 'Čega', 'gdje', 'kako', ' je', 'koja', 'da', ' li',
	' imena', ' ime', ' molim', ' vas', ' mi', ' ili', ' ne', ' kao', ' to', ' se', ' po', ' nisu', ' jesu',
	' nije', ' za', 'može', ' su', 'koje', ' ima', ' sa', ' bio', ' na', ' više', ' manje',
	' me', ' sve', ' bili', 'imate', 'zna', ' netko', ' neke', ' iz', 'zanima', 'imam', ' rekli', 'kakva', 'znači');
	$r = array_map('clean_helper', $r);
	return trim(str_ireplace($r, ' ', $str));
}

function clean_helper($x)
{
	return utf8html($x).' ';
}

?>