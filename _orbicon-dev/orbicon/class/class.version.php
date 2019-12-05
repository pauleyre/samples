<?php

/**
 * Version class
 *
 * 1.0 - 	Orca (1-7-2006)
 * 2.0 - 	Blade (17-9-2006)
 * 2.0.1 - 	Sigil (20-11-2006)
 * 2.0.2 - 	Fort Knox (27-Mar-2007)
 * 2.0.3 -	Enigma (26-Apr-2007)
 * 2.0.3a -	Enigma (23-May-2007)
 * 2.0.4 - Atlas (21-Jun-2007)
 * 2.0.5 - Atlas (11-Jul-2007)
 * 2.0.6 - Antiorb (16-Jul-2007)
 * 2.0.7 - Newfoundland (23-Jul-2007)
 * 2.0.8 - Newfoundland (25-Jul-2007)
 * 2.0.9 - Newfoundland (26-Jul-2007)
 * 2.0.18 - Solis Lacus (2-Sep-2007)
 * 2.1 - Solis Lacus (6-Sep-2007)
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package OrbiconFE
 * @version 2.1.6
 * @link http://
 * @license http://
 * @since 2006-07-01
 */

/**
 * print full version information
 *
 */
define('PRODUCT_VERSION_FULL', 0);
/**
 * print development string only
 *
 */
define('PRODUCT_VERSION_DEV', 1);

class Version
{
	/**
	 * name
	 *
	 * @var string
	 */
	var $product_name;
	/**
	 * 	main release level
	 *
	 * @var string
	 */
	var $product_release_level;
	/**
	 * development status
	 *
	 * # 1 - Planning (?!? if you're still planning then there's no code. ignore this status)
	 * # 2 - Pre-Alpha
	 * # 3 - Alpha
	 * # 4 - Beta
	 * # 5 - RC (1, 2, 3 etc.)
	 * # 6 - Production/Stable
	 * # 7 - Mature
	 *
	 * use these, not those above
	 * # 1 - dev
	 * # 2 - alpha (a)
	 * # 3 - beta (b)
	 * # 4 - RC(1, 2, 3, etc.)
	 * # 5 - pl
	 *
	 * @var string
	 */
	var $product_development_status;
	/**
	 * sub release level
	 *
	 * @var string
	 */
	var $product_development_level;
	/**
	 * codename
	 *
	 * @var string
	 */
	var $product_codename;
	/**
	 * date
	 *
	 * @var string
	 */
	var $product_release_date;
	/**
	 * time (eg. 23:00)
	 *
	 * @var string
	 */
	var $product_release_time;
	/**
	 * timezone (GMT)
	 *
	 * @var string
	 */
	var $product_release_timezone;
	/**
	 * copyright text
	 *
	 * @var string
	 */
	var $product_copyright;
	/**
	 * author
	 *
	 * @var string
	 */
	var $product_author;
	/**
	 * support team's email
	 *
	 * @var string
	 */
	var $product_support_email;

	/**
	 * PHP 4 compatibility
	 *
	 */
	function Version()
	{
		$this->__construct();
	}

	/**
	 * version constructor
	 *
	 */
	function __construct()
	{
		$this->product_name = 'System2';
		// main release level
		$this->product_release_level = '2.2';
		/*
		Development Status
		# 1 - Planning (?!? if you're still planning then there's no code. ignore this status)
		# 2 - Pre-Alpha
		# 3 - Alpha
		# 4 - Beta
		# 5 - RC (1, 2, 3 etc.)
		# 6 - Production/Stable
		# 7 - Mature

		# 1 - dev
		# 2 - alpha (a)
		# 3 - beta (b)
		# 4 - RC(1, 2, 3, etc.)
		# 5 - pl

		*/
		$this->product_development_status = 'pl';
		// sub release level
		$this->product_development_level = '2';
		// codename
		$this->product_codename = '☺';
		// date
		$this->product_release_date = '16-Feb-2009';
		// time
		$this->product_release_time = '00:00';
		// timezone
		$this->product_release_timezone = 'GMT';
		$this->product_author = 'Pavle Gardijan';
		$this->product_support_email = 'pavle.gardijan@hpb.hr';
		// copyright text
		$this->product_copyright = 'Copyright 2006-'.date('Y').' '.$this->product_author.'. All rights reserved.';
	}

	function get_orbicon_version($type = PRODUCT_VERSION_FULL)
	{
		if($type == PRODUCT_VERSION_DEV) {
			return $this->product_release_level.'.'.$this->product_development_level.' '.$this->product_development_status;
		}
		return $this->product_name.' '.$this->product_release_level.'.'.$this->product_development_level.' '.$this->product_development_status.' [ '.$this->product_codename.' ] '.$this->product_release_date .' '.$this->product_release_time.' '.$this->product_release_timezone;
	}
}

?>