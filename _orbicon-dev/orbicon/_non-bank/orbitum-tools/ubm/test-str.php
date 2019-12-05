<?php
/**
 * A test for micro-bechmark library for variations of variable handling in strings
 *
 *
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconTOOLS
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-06-04
 */

require 'ubm.php';

$loops = 1000000;
micro_benchmark('make_embed',  'make_embed',  $loops);
micro_benchmark('make_dots', 'make_dots', $loops);

function make_embed($loops) {
	$a['permalink'] = 'x';
	$a['name'] = 'b';
	$selected = '';

    for ($i = 0; $i < $loops; $i++) {
    	$categories = "<option value=\"{$a['permalink']}\"$selected>{$a['name']}</option>";
    }
}

function make_dots($loops) {
	$a['permalink'] = 'x';
	$a['name'] = 'b';
	$selected = '';

    for ($i = 0; $i < $loops; $i++) {
		$categories = '<option value="' . $a['permalink'] . "\"$selected>" . $a['name'] . '</option>';
    }
}

?>