<?php
/**
 * IP library
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @subpackage IP
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-07-06
 */

	require_once 'class.ip.php';

	/**
	 * Validate IP as public
	 *
	 * @param string $ip
	 * @return bool
	 */
    function valid_public_ip($ip)
    {
    	if(($ip != '') && (ip2long(long2ip(ip2long($ip))) != -1)) {
			// reserved IANA IPv4 addresses
			// http://www.iana.org/assignments/ipv4-address-space
    		$reserved_ips = array (
    				array('0.0.0.0', '2.255.255.255'),
    				array('10.0.0.0', '10.255.255.255'),
    				array('127.0.0.0', '127.255.255.255'),
    				array('169.254.0.0', '169.254.255.255'),
    				array('172.16.0.0', '172.31.255.255'),
    				array('192.0.2.0', '192.0.2.255'),
    				array('192.168.0.0', '192.168.255.255'),
    				array('255.255.255.0', '255.255.255.255')
    		);

    		foreach($reserved_ips as $r_ip) {
    			if((ip2long($ip) >= ip2long($r_ip[0])) && (ip2long($ip) <= ip2long($r_ip[1]))) {
					return false;
				}
    		} // foreach end
    		return true;
    	} // if end
    	else {
			return false;
		}
    }

    /**
     * sanitizes $_SERVER['REMOTE_ADDR']
     *
     * @param bool $override
     */
	function sanitize_remote_addr($override = true)
	{
		// reserved IANA IPv4 addresses
		// http://www.iana.org/assignments/ipv4-address-space
		$valid_ip_range = '~^((0|10|172\.16|192\.168|255|127\.0)\.|unknown)~';

		// Find the user's IP address. (but don't let it give you 'unknown'!)
		if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_CLIENT_IP']) && (preg_match($valid_ip_range, $_SERVER['HTTP_CLIENT_IP']) == 0 || preg_match($valid_ip_range, $_SERVER['REMOTE_ADDR']) != 0)) {
			// We have both forwarded for AND client IP... check the first forwarded for as the block - only switch if it's better that way.
			if(strtok($_SERVER['HTTP_X_FORWARDED_FOR'], '.') != strtok($_SERVER['HTTP_CLIENT_IP'], '.') && '.' . strtok($_SERVER['HTTP_X_FORWARDED_FOR'], '.') == strrchr($_SERVER['HTTP_CLIENT_IP'], '.') && (preg_match($valid_ip_range, $_SERVER['HTTP_X_FORWARDED_FOR']) == 0 || preg_match($valid_ip_range, $_SERVER['REMOTE_ADDR']) != 0)) {
				$_SERVER['REMOTE_ADDR'] = implode('.', array_reverse(explode('.', $_SERVER['HTTP_CLIENT_IP'])));
			}
			else {
				$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CLIENT_IP'];
			}
		}

		if(!empty($_SERVER['HTTP_CLIENT_IP']) && (preg_match($valid_ip_range, $_SERVER['HTTP_CLIENT_IP']) == 0 || preg_match($valid_ip_range, $_SERVER['REMOTE_ADDR']) != 0)) {
			// Since they are in different blocks, it's probably reversed.
			if(strtok($_SERVER['REMOTE_ADDR'], '.') != strtok($_SERVER['HTTP_CLIENT_IP'], '.')) {
				$_SERVER['REMOTE_ADDR'] = implode('.', array_reverse(explode('.', $_SERVER['HTTP_CLIENT_IP'])));
			}
			else {
				$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CLIENT_IP'];
			}
		}
		else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			// If there are commas, get the last one.. probably.
			if(strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
				$ips = array_reverse(explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']));

				// Go through each IP...
				foreach($ips as $ip) {
					// Make sure it's in a valid range...
					if(preg_match($valid_ip_range, $ip) != 0 && preg_match($valid_ip_range, $_SERVER['REMOTE_ADDR']) == 0)
						continue;
					// Otherwise, we've got an IP!
					$_SERVER['REMOTE_ADDR'] = trim($ip);
						break;
				}
			}
			// Otherwise just use the only one.
			else if(preg_match($valid_ip_range, $_SERVER['HTTP_X_FORWARDED_FOR']) == 0 || preg_match($valid_ip_range, $_SERVER['REMOTE_ADDR']) != 0) {
				$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
		}
		// here it could be a command line
		else if(!isset($_SERVER['REMOTE_ADDR'])) {
			$_SERVER['REMOTE_ADDR'] = '';
		}

		// Some final checking.
		if(preg_match('~^((([1]?\d)?\d|2[0-4]\d|25[0-5])\.){3}(([1]?\d)?\d|2[0-4]\d|25[0-5])$~', $_SERVER['REMOTE_ADDR']) === 0) {
			$_SERVER['REMOTE_ADDR'] = '';
		}
	}

	/**
	 * validate an IP in network range
	 * Example:
	 * <code>
	 * <?php
	 * return network_match('10.0.50.200', '10.0.50.10-10.0.50.20');
	 * ?>
	 * </code>
	 *
	 * @param string $ip
	 * @param string $network
	 * @return bool
	 */
	function network_match($ip, $network)
	{
		list($from, $to) = explode('-', $network);
		$ip = ip2long($ip);

		return (bool) (($ip >= ip2long(trim($from))) && ($ip <= ip2long(trim($to))));
	}

	/**
	 * match CIDR for IP
	 *
	 * Example:
	 * <code>
	 * <?php
	 * return cidr_match('10.0.50.20', '10.0.50.0/24');
	 * ?>
	 * </code>
	 *
	 * @param string $ip
	 * @param string $cidr
	 * @return bool
	 */
	function cidr_match($ip, $cidr)
	{
		list($net, $mask) = split ('/', $cidr);
		$ip_mask = ~((1 << (32 - $mask)) - 1);

		return (bool) ((ip2long($ip) & $ip_mask) == ip2long($net));
	}

	/**
	 * convert object IP to IP. returns false on error
	 *
	 * @param object $ip
	 * @return mixed
	 */
	function oip2ip($ip)
	{
		if(is_object($ip)) {
			return "$ip->address/$ip->subnet";
		}

		trigger_error('oip2ip() expects parameter 1 to be object, ' . gettype($ip) . 'given', E_USER_WARNING);
		return false;
	}

	/**
	 * convert string IP to object IP
	 *
	 * @param string $ip
	 * @return object
	 */
	function ip2oip($ip)
	{
		if(is_string($ip)) {
			return new IP($ip);
		}

		trigger_error('ip2oip() expects parameter 1 to be string, ' . gettype($ip) . 'given', E_USER_WARNING);
		return false;
	}

	/**
	 * Validate an IP address against a network range
	 *
	 * Supported Syntax:
	 * - *
	 * - 202.*
	 * - 202.1.*
	 * - 202.1.192.*
	 * - 202.1.192.0-202.1.192.255: a range of IPs
	 * - 200.36.161.0/24: a range of IP by using net masking
	 * - 200.36.161/24: a shorten syntax similar to the above
	 *
	 * Example
	 * <code>
	 * <?php
	 * if(net_match('127.0.0.0-127.0.0.4', $_SERVER['REMOTE_ADDR'])) {
	 * echo 'This address range has been banned!';
	 * }
	 * ?>
	 * </code>
	 *
	 * @author Dirk, Stephane, TRUSTAbyss
	 * @param string $network
	 * @param string $ip
	 * @return bool
	 * @deprecated
	 */
	function net_match($network, $ip)
	{
		$network = trim($network);
		$ip = trim($ip);
		$d = strpos($network, '-');

		if(ereg('^\*$', $network)) {
			$network = str_replace('*', '^.+', $network);
		}

		if(!ereg('\^\.\+|\.\*', $network)) {
			if($d === false) {
				$ip_arr = explode('/', $network);

				if(!preg_match('@\d*\.\d*\.\d*\.\d*@', $ip_arr[0], $matches)) {
					$ip_arr[0] .= '.0';    // Alternate form 194.1.4/24
				}

				$network_long = ip2long($ip_arr[0]);
				$x = ip2long($ip_arr[1]);
				$mask = long2ip($x) == $ip_arr[1] ? $x : (0xFFFFFFFF << (32 - $ip_arr[1]));
				$ip_long = ip2long($ip);

				return (bool) ($ip_long & $mask) == ($network_long & $mask);
			}
			else {
				list($from, $to) = explode('-', $network);
				$from = ip2long(trim($from));
				$to = ip2long(trim($to));
				$ip = ip2long($ip);

				return (bool) (($ip >= $from) && ($ip <= $to));
			}
		}
		else {
			return (bool) ereg($network, $ip);
		}
	}

?>