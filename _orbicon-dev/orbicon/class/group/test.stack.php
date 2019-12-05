<?php

/**
 * Stack test
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-06-17
 */

include 'class.stack.php';

// Let's use these to create a small stack of data and manipulate it.
//  Start by adding a few numbers onto it, making it: 73 74 5
$mystack = new Stack();
$mystack->push(73);
$mystack->push(74);
$mystack->push(5);

// Now duplicate the top, giving us:  73 74 5 5
$mystack->dup();

// Check the size now, it should be 4
echo '<p>Stack size is: ', $mystack->size(), '</p>';

// Now pop the top, giving us: 5
echo '<p>Popped off the value: ', $mystack->pop(), '</p>';

// Next swap the top two values, leaving us with: 73 5 74
$mystack->swap();

// Peek at the top element to ensure it is 74
echo '<p>Current top element is: ', $mystack->peek(), '</p>';

// Now destroy it, we are done.
$mystack->destroy();

?>