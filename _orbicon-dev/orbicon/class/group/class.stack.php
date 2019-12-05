<?php
/**
 * Stack implementation class (LIFO)
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-06-17
 */
class Stack
{
	/**
	 * Stack container
	 *
	 * @var array
	 */
	var $stack;

	/**
	 *  The Initialize function creates a new stack
	 *
	 */
	function __construct()
	{
		$this->stack = array();
	}

	/**
	 * for PHP4
	 *
	 */
	function stack()
	{
		$this->__construct();
	}

	/**
	* The destroy function will get rid of a stack
	*
	*/
	function destroy()
	{
		// Since PHP is nice to us, we can just use unset
		unset($this->stack);
	}

	/**
	 * The push operation on a stack adds a new value onto the top of the stack
	 *
	 * @param unknown_type $value
	 */
	function push($value)
	{
		// We are just adding a value to the end of the array, so we can use the [] PHP Shortcut for this. It's faster than using array_push
		$this->stack[] = $value;
	}

	/**
	 * Pop removes the top value from the stack and returns it to you
	 *
	 * @return mixed
	 */
	function pop()
	{
		// Just use array pop
		return array_pop($this->stack);
	}

	/**
	 * Peek returns a copy of the top value from the stack, leaving it in place
	 *
	 * @return mixed
	 */
	function peek()
	{
		// Return a copy of the value on top of the stack (the end of the array)
		$last = end($this->stack);
		reset($this->stack);
		return $last;
	}

	/**
	 * Size returns the number of elements in the stack
	 *
	 * @return int
	 */
	function size()
	{
		// Just using count will give the proper number
		return count($this->stack);
	}

	/**
	 * Swap takes the top two values of the stack and switches them
	 *
	 */
	function swap()
	{
		// Calculate the count:
		$n = $this->size();

		// Only do anything if count is greater than 1
		if ($n > 1) {
			// Now save a copy of the second to last value
			$second = $this->stack[$n - 2];
			// Place the last value in second to last place
			$this->stack[$n - 2] = $this->stack[$n - 1];
			// And put the second to last, now in the last place
			$this->stack[$n - 1] = $second;
		}
	}

	/**
	 * Dup takes the top value from the stack, duplicates it, and adds it back onto the stack
	 *
	 */
	function dup()
	{
		// Actually rather simple, just reinsert the last value
		$this->stack[] = $this->peek();
	}
}

?>