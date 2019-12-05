<?php
/**
 * IP class
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @subpackage IP
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-07-06
 */

class IP
{
	/**
	 * IP subnet
	 *
	 * @var int
	 */
	var $subnet;
	/**
	 * IP address
	 *
	 * @var string
	 */
	var $address;

	function ip($ip)
	{
		$this->__construct($ip);
	}

	function __construct($ip)
	{
		list($this->address, $this->subnet) = explode('/', $ip);
		$this->subnet = intval($this->subnet);
	}
}

?>