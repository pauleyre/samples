<?php

function hyperlink_txt($string)
{
	$string = ' ' . $string;
	// in testing, using arrays here was found to be faster
	$string = preg_replace(
	array(
		'#([\s>])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is',
		'#([\s>])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is',
		'#([\s>])([a-z0-9\-_.]+)@([^,< \n\r]+)#i'),
	array(
		'$1<a href="$2">$2</a>',
		'$1<a href="http://$2">$2</a>',
		'$1<a href="mailto:$2@$3">$2@$3</a>'), $string);
	// this one is not in an array because we need it to run last, for cleanup of accidental links within links
	$string = preg_replace("#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i", "$1$3</a>", $string);
	$string = trim($string);
	return $string;
}

?>