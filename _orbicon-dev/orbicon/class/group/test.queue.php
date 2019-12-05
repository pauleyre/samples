<?php

/**
 * Queue test
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-06-17
 */

include 'class.queue.php';

// Let's use these to create a small queue of data and manipulate it.
// Start by adding a few words to it:
$myqueue = new Queue();
$myqueue->enqueue('Opal');
$myqueue->enqueue('Dolphin');
$myqueue->enqueue('Pelican');

// The queue is: Opal Dolphin Pelican

// Check the size, it should be 3
echo '<p>Queue size is: ', $myqueue->size(), '</p>';

// Peek at the front of the queue, it should be: Opal
echo '<p>Front of the queue is: ', $myqueue->peek(), '</p>';

// Now rotate the queue, giving us: Dolphin Pelican Opal
$myqueue->rotate();

// Remove the front element, returning: Dolphin
echo '<p>Removed the element at the front of the queue: ',
$myqueue->dequeue(), '</p>';

// Now destroy it, we are done.
$myqueue->destroy();

?>