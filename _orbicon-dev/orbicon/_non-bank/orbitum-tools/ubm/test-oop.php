<?php
/**
 * A test for micro-bechmark library for OOP vs. procedural
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

class Adder {
    function add2($a, $b) { return $a + $b; }
    function add3($a, $b, $c) { return $a + $b; }
}

function adder_add2($a, $b) { return $a + $b; }
function adder_add3($a, $b) { return $a + $b; }

function run_oo_bm2($count) {
    $adder = new Adder;
    for ($i = 0; $i < $count; $i++) $adder->add2(5, 7);
}
function run_oo_bm3($count) {
    $adder = new Adder;
    for ($i = 0; $i < $count; $i++) $adder->add2(5, 7, 9);
}

function run_proc_bm2($count) {
    for ($i = 0; $i < $count; $i++) adder_add2(5, 7);
}
function run_proc_bm3($count) {
    for ($i = 0; $i < $count; $i++) adder_add3(5, 7, 9);
}

$loops = 1000000;
micro_benchmark('proc_2_args', 'run_proc_bm2', $loops);
micro_benchmark('proc_3_args', 'run_proc_bm3', $loops);
micro_benchmark('oo_2_args', 'run_oo_bm2', $loops);
micro_benchmark('oo_3_args', 'run_oo_bm3', $loops);

?>