<?php

/**
 * Small but very efficient CAPTCHA implementation
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-07-11
 */

	/**
	 * write raw gif image for captcha
	 *
	 */
	function get_captcha_image()
	{
		// seed for PHP < 4.2.0
		srand((float) microtime() * 10000000);

		$int_a = rand(1, 10);
		$int_b = rand(1, 10);
		$plus_minus = (is_int(rand() / 2)) ? '+' : '-';
		$captcha_q_a = array(
							sprintf(_L('captcha_q'), $int_a, $plus_minus,  $int_b) => eval("return ($int_a $plus_minus $int_b);")
						);

		$pick = rand(0, (count($captcha_q_a) - 1));

		$captcha_q = array_keys($captcha_q_a);
		$captcha_a = array_values($captcha_q_a);

		/*pick the question and answer */
		$q = $captcha_q[$pick];
		$_SESSION['captcha_answer'] = $captcha_a[$pick];

		/* set up image, the first number is the width and the second is the height*/
		$font_size = (isset($_GET['big'])) ? 16 : 8;
		$width = (imagefontwidth($font_size) * strlen($q));
		$height = imagefontheight($font_size);
		$im = imagecreate($width, $height);

		/*creates two variables to store color*/
		$white = imagecolorallocate($im, 255, 255, 255);
		$black = imagecolorallocate($im, 0, 0, 0);

		imagefill($im, 0, 0, $white);

		/*writes string */
		imagestring($im, $font_size, 0, 0, $q, $black);

		/* output to browser*/
		header('Content-Type: image/gif');
		if(!strpos(strtolower(ORBX_USER_AGENT), 'msie') === false) {
			header('HTTP/1.x 205 OK');
			$_SESSION['cache_status'] = 205;
		}
		else {
			header('HTTP/1.x 200 OK');
			$_SESSION['cache_status'] = 200;
		}

		header('Pragma: no-cache');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-cache, cachehack=' . time());
		header('Cache-Control: no-store, must-revalidate');
		header('Cache-Control: post-check=-1, pre-check=-1', false);
		imagegif($im);
	}

	/**
	 * determine if input matches captcha
	 *
	 * @param mixed $input
	 * @return bool
	 */
	function check_captcha($input)
	{
		$input = trim($input);

		if(!defined('XML_HTMLSAX3')) {
			define('XML_HTMLSAX3', DOC_ROOT . '/orbicon/3rdParty/safehtml/classes/');
		}

		require_once XML_HTMLSAX3 . 'safehtml.php';
		$safehtml =& new safehtml();

		$input = $safehtml->parse($input);
		unset($safehtml);

		return (strtolower($_SESSION['captcha_answer']) == strtolower($input));
	}

?>