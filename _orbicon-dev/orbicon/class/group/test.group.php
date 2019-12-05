<?php

/**
 * Group class test
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-06-17
 */

include 'class.group.php';

// Let's use these to create a small group
// Start by adding a few words to it:
$mygroup = new Group(array('a', 'b', 'c'), 'Opal', 'Dolphin', 'Pelican');
echo '<pre>Single:
';
print_r($mygroup->random());
echo '

Four:
';
print_r($mygroup->random(4));

echo 'Sort:
';
print_r($mygroup->members);
$mygroup->sort();
print_r($mygroup->members);

echo 'Split:
';
print_r($mygroup->split(3));

echo 'Merge:
';
print_r($mygroup->merge('abc', array(1, 2, 4)));

echo '</pre>';



?>