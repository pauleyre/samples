<?php
/*-----------------------------------------------------------------------*
	Project........:	Orbicon X framework 2
	File...........:
	Version........:	1.0 (22/10/2006)
	Author.........:	Pavle Gardijan (pavle.gardijan@orbitum.net) - Orbitum d.o.o.
	Copyright......:	(c) 2006 Orbitum internet communications d.o.o. (www.orbitum.net)
	Created........:	01/07/2006
	Notes..........:
	Modified.......:
*-----------------------------------------------------------------------*/
	/*----- ATTENTION! -------------------------------------------------
	 | Include before wherever DOC_ROOT required.
	 |------------------------------------------------------------------*/
	if(!defined('DOC_ROOT')) {
		// we'll need this info
		$dir = dirname(dirname(__FILE__));

		$file = $dir . '/index.php';
		$license = $dir . '/license.php';
		$found = false;

		while(!$found) {

			$dir = dirname(dirname($file));

			$license = $dir . '/license.php';
			$file = $dir . '/index.php';

			if(is_file($file) && is_file($license)) {
				$found = true;
				break;
			}
		}

		$request_uri = $_SERVER['REQUEST_URI'];

		$left = str_replace($dir, '', dirname(__FILE__));
		$left = str_replace('\\', '/', $left);
		$left = str_replace($left, '', $request_uri);
		// get rid of query
		$left = explode('?', $left);
		// file or no file?
		$left = (strpos($left[0], '.php')) ? dirname($left[0]) : $left[0];
		// strip forward slash as well
		$left = (substr($left, -1, 1) == '/') ? substr($left, 0, -1) : $left;

		define('ORBX_URI_PATH', $left);
		define('DOC_ROOT', $dir);
		unset($left, $request_uri);
	}
	//----- ATTENTION! ENDS -------------------------------------------------

	// start logger
	require_once DOC_ROOT . '/orbicon/class/inc.core.php';

	$name = $_REQUEST['name'];
	$title = _L('site_recommended');
	$refer_url = $_REQUEST['refer_url'];
	$email = $_REQUEST['email'];
	$emails = array(
					$_REQUEST['email1'],
					$_REQUEST['email2'],
					$_REQUEST['email3'],
					$_REQUEST['email4'],
					$_REQUEST['email5']
					);
	$mail_body = sprintf(_L('send_message'), $name . ' ( ' . $email . ' )', '<a href="' . $refer_url.'">' . $refer_url.'</a>');

	if(is_file(DOC_ROOT . '/site/gfx/send2friend.logo.gif')) {
		$mail_body = '<center><img src="'.ORBX_SITE_URL.'/site/gfx/send2friend.logo.gif" /></center><br />'.$mail_body;
	}

	include_once DOC_ROOT . '/orbicon/3rdParty/phpmailer/class.phpmailer.php';

	$mail = new PHPMailer();

	if($_SESSION['site_settings']['smtp_server'] != '') {
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->Host = $_SESSION['site_settings']['smtp_server']; // SMTP server
		$mail->Port = $_SESSION['site_settings']['smtp_port'];
	}

	$mail->CharSet = 'UTF-8';
	$mail->From = $_SESSION['site_settings']['main_site_email'];
	$mail->FromName = utf8_html_entities($_SESSION['site_settings']['main_site_title'], true);
	foreach($emails as $email) {
		$email = trim($email);
		if(is_email($email)) {
			$mail->AddAddress($email);
		}
	}

	//$mail->Subject = utf8_html_entities($title, true);
	$mail->Subject = $title;
	$mail->Body = $mail_body;
	$mail->WordWrap = 50;
	$mail->IsHTML(true);

	if(!$mail->Send()) {
		/*foreach($emails as $email) {
			$email = trim($email);
			mail($email, $title, $mail_body, 'Content-Type: text/html; charset=UTF-8');
		}*/
	}

	// log event to system log
	/*foreach ($emails as $k=>$item){
		if($item != null){
			$orbx_log->swrite('Mail successfully sent from Send 2 friend to '. $item);
		}
	}*/

	// send response header
	echo 'Mail sent.';
	$mail = null;


?>