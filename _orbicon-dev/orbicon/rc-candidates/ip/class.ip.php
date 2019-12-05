<?php
/**
 * IP class
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan, Laniste 10A, 10000 Zagreb, Croatia
 * @package Codex
 * @subpackage IP
 * @version 1.00
 * @link http://
 * @license http://
 * @since 2007-07-06
 */

class IP
{
	/**
	 * IP subnet
	 *
	 * @var int
	 */
	public $subnet;
	/**
	 * IP address
	 *
	 * @var string
	 */
	public $address;

	function __construct($ip)
	{
		list($this->address, $this->subnet) = explode('/', $ip);
		$this->subnet = intval($this->subnet);
	}
}

?>