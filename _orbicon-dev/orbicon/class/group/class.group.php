<?php
/**
 * Group class
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-06-17
 */
require_once 'class.queue.php';

class Group
{
	/**
	 * All group members. Group uses queue logic (FIFO)
	 *
	 * @var array
	 */
	var $members;
	/**
	 * Group queue
	 *
	 * @var object
	 */
	var $_queue;

	/**
	 * Build a group from inputs
	 *
	 */
	function __construct()
	{
		$this->_queue = new Queue();
		$this->members =& $this->_queue->queue;

		$numargs = func_num_args();
		$arg_list = func_get_args();

		for ($i = 0; $i < $numargs; $i++) {
			// merge array members
			if(is_array($arg_list[$i])) {
				$this->members = array_merge($this->members, $arg_list[$i]);
			}
			// append others
			else {
				$this->_queue->enqueue($arg_list[$i]);
			}
		}
	}

	/**
	 * For PHP 4
	 *
	 */
	function group()
	{
		$this->_queue = new Queue();
		$this->members =& $this->_queue->queue;

		$numargs = func_num_args();
		$arg_list = func_get_args();

		for ($i = 0; $i < $numargs; $i++) {
			// merge array members
			if(is_array($arg_list[$i])) {
				$this->members = array_merge($this->members, $arg_list[$i]);
			}
			// append others
			else {
				$this->_queue->enqueue($arg_list[$i]);
			}
		}
	}

	/**
	 * Splits the group members into several arrays with size values in them
	 *
	 * @param int $size
	 * @param bool $preserve_keys
	 * @return array
	 */
	function split($size, $preserve_keys = false)
	{
		return array_chunk($this->members, $size, $preserve_keys);
	}

	/**
	 * Merge arrays and variables into group
	 *
	 */
	function merge()
	{
		$numargs = func_num_args();
		$arg_list = func_get_args();

		for ($i = 0; $i < $numargs; $i++) {
			// merge array members
			if(is_array($arg_list[$i])) {
				$this->members = array_merge($this->members, $arg_list[$i]);
			}
			// append others
			else {
				$this->_queue->enqueue($arg_list[$i]);
			}
		}
	}

	/**
	 * Remove duplicates
	 *
	 */
	function unique()
	{
		$this->members = array_unique($this->members);
	}

	/**
	 * Pick one or more random members from the group. Defaults to one
	 *
	 * @param int $num_req
	 * @return mixed
	 */
	function random($num_req = 1)
	{
		if(empty($this->members)) {
			user_error('The group has no members', E_USER_WARNING);
			return false;
		}

		if(count($this->members) < 2) {
			return reset($this->members);
		}

		$random_members = array();
		// seed for PHP < 4.2.0
		srand((float) microtime() * 10000000);
		$random_keys = array_rand($this->members, $num_req);

		// exit here for single member
		if($num_req == 1) {
			return $this->members[$random_keys];
		}

		$i = 0;

		while($i < $num_req) {
			$random_members[] = $this->members[$random_keys[$i]];
			$i ++;
		}

		return $random_members;
	}

	/**
	 * Get total members number
	 *
	 * @return int
	 */
	function size()
	{
		return $this->_queue->size();
	}

	/**
	 * Destroy group
	 *
	 */
	function destroy()
	{
		$this->_queue->destroy();
	}

	/**
	 * Get first group member
	 *
	 * @return mixed
	 */
	function first()
	{
		return $this->_queue->peek();
	}

	/**
	 * Get last group member
	 *
	 * @return mixed
	 */
	function last()
	{
		// get last member
		$last = end($this->members);
		// reset pointer
		reset($this->members);
		return $last;
	}

	/**
	 * Converts group to string where members are separated with separator
	 *
	 * @param string $separator
	 * @return string
	 */
	function serialize($separator = ',')
	{
		if(!is_string($separator)) {
			trigger_error('serialize() expects parameter 1 to be string, '.gettype($separator).' given', E_USER_WARNING);
			return false;
		}

		return implode($separator, $this->members);
	}

	/**
	 * Remove one or more objects from group
	 *
	 * @param mixed $members
	 */
	function remove($members)
	{
		if(!is_array($members)) {
			$members = array($members);
		}

		$this->members = array_diff($this->members, $members);
	}

	/**
	 * Remove empty group members
	 *
	 */
	function remove_empty()
	{
		$this->remove(array('', null, 0, 0.0, false));
	}

	function __toString()
	{
		return $this->serialize();
	}
}

?>