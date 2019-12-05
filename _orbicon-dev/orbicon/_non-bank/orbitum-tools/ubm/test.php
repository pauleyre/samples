<?php
/**
 * A test for micro-bechmark library
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

$str = 'This string is not modified';
$loops = 1000000;
micro_benchmark('str_replace',  'bm_str_replace',  $loops);
micro_benchmark('preg_replace', 'bm_preg_replace', $loops);

function bm_str_replace($loops) {
    global $str;
    for ($i = 0; $i < $loops; $i++) {
        str_replace('is not', 'has been', $str);
    }
}

function bm_preg_replace($loops) {
    global $str;
    for ($i = 0; $i < $loops; $i++) {
        preg_replace('/is not/', 'has been', $str);
    }
}

?>