<?php
/**
 * A test for micro-bechmark library for variations of echo
 *
 *
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconTOOLS
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-05-30
 */

require 'ubm.php';

$loops = 1000000;
micro_benchmark('echo_dots',  'echo_dots',  $loops);
micro_benchmark('echo_comas', 'echo_comas', $loops);

function echo_dots($loops) {
    for ($i = 0; $i < $loops; $i++) {
        echo 'This ' . 'is ' . 'a ' . 'string';
    }
}

function echo_comas($loops) {
    for ($i = 0; $i < $loops; $i++) {
        echo 'This ' , 'is ' , 'a ' , 'string';
    }
}

?>