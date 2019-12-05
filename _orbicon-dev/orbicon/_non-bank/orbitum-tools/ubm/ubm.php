<?php

/**
 * A library to run micro-benchmarks (from PHP 5 Power Programming, optimized it for perfomance)
 *
 * Example:
 *
 * <code>
 * <?php
 * require DOC_ROOT . '/orbicon/modules/orbitum-tools/ubm/ubm.php';
 *
 * $str = 'This string is not modified';
 * $loops = 1000000;
 * micro_benchmark('str_replace',  'bm_str_replace',  $loops);
 * micro_benchmark('preg_replace', 'bm_preg_replace', $loops);
 * function bm_str_replace($loops) {
 * 	global $str;
 *  for ($i = 0; $i < $loops; $i++) {
 *   	str_replace('is not', 'has been', $str);
 *  }
 * }
 *
 * function bm_preg_replace($loops) {
 * 	global $str;
 * 	for ($i = 0; $i < $loops; $i++) {
 * 		preg_replace('/is not/', 'has been', $str);
 * 	}
 * }
 *
 * ?>
 * </code>
 *
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconTOOLS
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-05-30
 */

register_shutdown_function('micro_benchmark_summary');
$ubm_timing = array();

function micro_benchmark($label, $impl_func, $iterations = 1)
{
	if(!function_exists('getrusage')) {
		die('"getrusage" required but not found. exiting...');
	}
    global $ubm_timing;
    echo "benchmarking '$label'...";
    flush();
    $start = current_usercpu_rusage();
    call_user_func($impl_func, $iterations);
    $ubm_timing[$label] = current_usercpu_rusage() - $start;
    echo '<br />';
    return $ubm_timing[$label];
}

function micro_benchmark_summary()
{
    global $ubm_timing;

    if (empty($ubm_timing)) {
        return;
    }
    arsort($ubm_timing);
    reset($ubm_timing);
    $slowest = current($ubm_timing);
    end($ubm_timing);
    echo '<h2>And the winner is: ';
    echo key($ubm_timing) . '</h2>';
    echo '<table border=1><tr><td>&nbsp;</td>';
    foreach($ubm_timing as $label => $usercpu) {
        echo "<th>$label</th>";
    }
    echo '</tr>';
    $ubm_timing_copy = $ubm_timing;

    foreach ($ubm_timing_copy as $label => $usercpu) {
        echo "<tr><td><b>$label</b><br />";
        printf('%.3fs</td>', $usercpu);
        foreach ($ubm_timing as $label2 => $usercpu2) {
            $percent = (($usercpu2 / $usercpu) - 1) * 100;
            if($percent > 0) {
                printf('<td>%.3fs<br />%.1f%% slower', $usercpu2, $percent);
            }
            else if($percent < 0) {
                printf('<td>%.3fs<br />%.1f%% faster', $usercpu2, -$percent);
            }
            else {
                echo '<td>&nbsp;';
            }
            echo '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
}

function current_usercpu_rusage()
{
    $ru = getrusage();
    return $ru['ru_utime.tv_sec'] + ($ru['ru_utime.tv_usec'] / 1000000.0);
}

?>