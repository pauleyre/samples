<?php
/**
 * Queue implementation class (FIFO)
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-06-17
 */
class Queue
{
	/**
	 * queue container
	 *
	 * @var array
	 */
	var $queue;

	/**
	 *  The Initialize function creates a new queue
	 *
	 */
	function __construct()
	{
		$this->queue = array();
	}

	/**
	 * for PHP4
	 *
	 */
	function queue()
	{
		$this->__construct();
	}

	/**
	* The destroy function will get rid of a queue
	*
	*/
	function destroy()
	{
		// Since PHP is nice to us, we can just use unset
		unset($this->queue);
	}

	//
	/**
	 * The enqueue operation adds a new value onto the back of the queue
	 *
	 * @param mixed $value
	 */
	function enqueue($value)
	{
		// We are just adding a value to the end of the array, so we can use the
		//  [] PHP Shortcut for this.  It's faster than using array_push
		$this->queue[] = $value;
	}

	/**
	 * Dequeue removes the front of the queue and returns it to you
	 *
	 * @return mixed
	 */
	function dequeue()
	{
		// Just use array shift
		return array_shift($this->queue);
	}

	/**
	 * Peek returns a copy of the front of the queue, leaving it in place
	 *
	 * @return mixed
	 */
	function peek()
	{
		// Return a copy of the value found in front of queue (at the beginning of the array)
		return reset($this->queue);
	}

	/**
	 * Size returns the number of elements in the queue
	 *
	 * @return int
	 */
	function size()
	{
		// Just using count will give the proper number:
		return count($this->queue);
	}

	/**
	 * Rotate takes the item on the front and sends it to the back of the queue.
	 *
	 */
	function rotate()
	{
		// Remove the first item and insert it at the rear.
		$this->queue[] = $this->dequeue();
	}
}

?>