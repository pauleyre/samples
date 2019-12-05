<?php

require($_SERVER['DOCUMENT_ROOT'].'/classlib/public/classlib.php');

define('COMMUNICATOR_MAX_MESSAGES', 30);
define('COMMUNICATOR_MAIN_ROOT_FILENAME', 'main.log');
define('COMMUNICATOR_MAIN_DIRNAME', $_SERVER['DOCUMENT_ROOT'].'/is/2_communicator/');
define('COMMUNICATOR_MSG_TYPE_IM', 0x01);
define('COMMUNICATOR_MSG_TYPE_SMS', 0x02);
define('COMMUNICATOR_MSG_TYPE_EMAIL', 0x04);

session_start();

class Communicator extends ClassLib
{
	var $sNewMessage;
	var $sMessageLog;
	var $sMessageRoom;
	var $nMessageType;
	var $bMessageIM;
	var $bMessageSMS;
	var $bMessageMail;

	function CommunicatorMain()
	{
		$this -> CommunicatorSelectRoom();
		$this -> _CommunicatorGetMessageType();
		$this -> CommunicatorAddNewMessage();
		$this -> CommunicatorGetMessagesLog();
	}

	function CommunicatorAddNewMessage()
	{
		(string) $this -> sNewMessage = $this -> CommunicatorGetValidMessage();
		(bool) $bClearRoom = $this -> _CommunicatorClearRoom();

		if($this -> sNewMessage != NULL && !$bClearRoom && $this -> bMessageIM)
		{
			(string) $sAllMessages = '';
			//$sAllMessages .= "\xEF\xBB\xBF";					// * Add UTF-8 BOM

			$this -> sNewMessage = $this -> utf8RawUrlDecode($this -> sNewMessage);
			$this -> sNewMessage = $this -> ConvertHMTLEnitites($this -> sNewMessage);

			$this -> CommunicatorConvertURLToHyperlink();
			$this -> CommunicatorAddSmileys();

			(array) $aAllMessages = file($this -> sMessageRoom);
			$class = ($this -> sMessageRoom != COMMUNICATOR_MAIN_ROOT_FILENAME) ? $this -> sMessageRoom : 'hide';
			//(array) $aNewMessage[] = sprintf("<div class=\"communicator_message\"><abbr class=\"r\" title=\"".date('r', time())."\">%s</abbr>: %s</div>\r\n", $_SESSION['zaposlenik_ime'], $this -> sNewMessage);
			(array) $aNewMessage[] = (time()).'-==yXy==-'.$_SESSION['zaposlenik_id'].'-==yXy==-'.$_SESSION['zaposlenik_ime'].'-==yXy==-'.$this -> sNewMessage."\r\n";

			(array) $aAllMessagesFormated = array_merge($aNewMessage, $aAllMessages);

			$aAllMessagesFormated = array_chunk($aAllMessagesFormated, COMMUNICATOR_MAX_MESSAGES);
			$aAllMessagesFormated = $aAllMessagesFormated[0];

			$sAllMessages = implode('', $aAllMessagesFormated);

			$rLog = fopen($this -> sMessageRoom, 'wb');
			if(!fwrite($rLog, $sAllMessages)) {
				$this -> AddError(__FUNCTION__.' : Unable to save message.');
			}
			fclose($rLog);
			// * update activity
			$f = fopen('../_modals/online/'.$_SESSION['zaposlenik_id'].'.log', 'wb');
			fwrite($f, time());
			fclose($f);
		}
		if($this -> sNewMessage != NULL && !$bClearRoom && $this -> bMessageMail) {
			$this -> CommunicatorSendEmail();
		}
		if($this -> sNewMessage != NULL && !$bClearRoom && $this -> bMessageSMS) {
			$this -> CommunicatorSendSMS();
		}
		/*if($this -> sMessageRoom != COMMUNICATOR_MAIN_ROOT_FILENAME)
		{
			$this -> sMessageRoom = COMMUNICATOR_MAIN_ROOT_FILENAME;
			$this -> CommunicatorAddNewMessage();
		}*/

	}

	function _CommunicatorWriteLogo()
	{
		if(file_exists('logo.gif')) {
			return sprintf('<span><img src=\"%s\" style=\"vertical-align: bottom;\" /></span>', 'logo.gif');
		}
		return '';
	}

	function CommunicatorSendSMS()
	{
		(string) $this -> sNewMessage = $this -> CommunicatorGetValidMessage();
		mail($_POST['email'], '', $this -> sNewMessage, 'From: Oculus');
	}

	function CommunicatorSendEmail()
	{
		(string) $this -> sNewMessage = $this -> CommunicatorGetValidMessage();
		mail($_POST['email'], '', $this -> sNewMessage);
	}

	function CommunicatorGetMessagesLog()
	{
		(array) $aLog = file($this -> sMessageRoom);
		$now = time();

		foreach($aLog as $key => $entry)
		{
			$chat_line = explode('-==yXy==-', $entry);
			// $chat_line : 0 - time, 1 - id, 2 - name, 3 - msg
			$last = file_get_contents('../_modals/online/'.$chat_line[1].'.log');

			$class = (($last + 300 <= $now) || empty($last)) ? 'r' : 'g';
			(array) $aLogMessages[] = sprintf("<div class=\"communicator_message\"><span class=\"%s\">%s</span> [%s]: %s</div>\r\n", $class, $chat_line[2], date('H:i', $chat_line[0]), $chat_line[3]);
		}
		
		(string) $this -> sMessageLog = implode('', $aLogMessages);
		$bChange = FALSE;
		$sRoom = $this -> sMessageRoom;

		if($_SESSION["chat_room_size_$sRoom"] != $this -> SFVChecksum(file_get_contents($sRoom)))
		{
			$this -> sMessageLog .= "<!-- nova_poruka -->";
			$bChange = TRUE;
		}

		$this -> sMessageLog = str_replace("\r\n", '', $this -> sMessageLog);

		if($bChange) {
			$_SESSION["chat_room_size_$sRoom"] = $this -> SFVChecksum(file_get_contents($this -> sMessageRoom));
		}
	}

	function CommunicatorGetValidMessage()
	{
		$msg = (isset($_POST['msg'])) ? trim($_POST['msg']) : '';
		$msg = trim($msg);

		if($msg != '')
		{
			$msg = nl2br($msg);
			$msg = str_replace(array("\r", "\n"), '', $msg);
			return $msg;
		}
		return NULL;
	}

	function CommunicatorAddSmileys()
	{
		(array) $aSmileysImages = array(
										0 => 'biggrin.gif',
										1 => 'smile.gif',
										2 => 'sad.gif',
										3 => 'surprised.gif',
										4 => 'shock.gif',
										5 => 'confused.gif',
										6 => 'cool.gif',
										7 => 'lol.gif',
										8 => 'mad.gif',
										9 => 'razz.gif',
										10 => 'redface.gif',
										11 => 'cry.gif',
										12 => 'evil.gif',
										13 => 'badgrin.gif',
										14 => 'rolleyes.gif',
										15 => 'wink.gif',
										16 => 'exclaim.gif',
										17 => 'question.gif',
										18 => 'idea.gif',
										19 => 'arrow.gif',
										20 => 'neutral.gif',
										21 => 'doubt.gif',
										22 => 'metal.gif'
										);

		(array) $aSmileysText = array(
										'>:)' => $aSmileysImages[12],
										'>:-)' => $aSmileysImages[12],
										'>:D' => $aSmileysImages[13],
										'>:-D' => $aSmileysImages[13],
										':))' => $aSmileysImages[0],
										':-))' => $aSmileysImages[0],
										':D' => $aSmileysImages[0],
										':-D' => $aSmileysImages[0],
										':)' => $aSmileysImages[1],
										':-)' => $aSmileysImages[1],
										':(' => $aSmileysImages[2],
										':-(' => $aSmileysImages[2],
										':o' => $aSmileysImages[3],
										':-o' => $aSmileysImages[3],
										'=o' => $aSmileysImages[4],
										'=-o' => $aSmileysImages[4],
										':?' => $aSmileysImages[5],
										':-?' => $aSmileysImages[5],
										'???' => $aSmileysImages[5],
										'8)' => $aSmileysImages[6],
										'8-)' => $aSmileysImages[6],
										'lol' => $aSmileysImages[7],
										'rofl' => $aSmileysImages[7],
										'roflol' => $aSmileysImages[7],
										':x' => $aSmileysImages[8],
										':-x' => $aSmileysImages[8],
										':p' => $aSmileysImages[9],
										':-p' => $aSmileysImages[9],
										';)' => $aSmileysImages[15],
										';-)' => $aSmileysImages[15],
										':!' => $aSmileysImages[16],
										':-!' => $aSmileysImages[16],
										'(?)' => $aSmileysImages[17],
										'(!)' => $aSmileysImages[18],
										'-->' => $aSmileysImages[19],
										'->' => $aSmileysImages[19],
										':|' => $aSmileysImages[20],
										':-|' => $aSmileysImages[20],
										'**' => $aSmileysImages[14],
										':((' => $aSmileysImages[11],
										':-((' => $aSmileysImages[11],
										'\m/' => $aSmileysImages[22]
										);

		(string) $sSmileySource = '<img src="2_communicator/smileys/%s" class="sml" />';

		foreach($aSmileysText as $sTextSmiley => $sImageSmiley) {
			$this -> sNewMessage = str_replace($sTextSmiley, sprintf($sSmileySource, $sImageSmiley), $this -> sNewMessage);
		}
		foreach($aSmileysText as $sTextSmiley => $sImageSmiley) {
			$this -> sNewMessage = str_replace(strtoupper($sTextSmiley), sprintf($sSmileySource, $sImageSmiley), $this -> sNewMessage);
		}
	}

	function CommunicatorConvertURLToHyperlink()
	{
		$this -> sNewMessage = ereg_replace(
							"[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]",
							"<a href=\"\\0\" target=\"_blank\" title=\"Otvara se u novom prozoru\">\\0</a>", 
							$this -> sNewMessage);
					

/*$this -> sNewMessage = ereg_replace(
"(?:(?:(?:http)://(?:(?:(?:(?:(?:(?:[a-zA-Z0-9][-a-zA-Z0-9]*)?[a-zA-Z0-9])[.])*(?:[a-zA-Z][-a-zA-Z0-9]*[a-zA-Z0-9]|[a-zA-Z])[.]?)|(?:[0-9]+[.][0-9]+[.][0-9]+[.][0-9]+)))(?::(?:(?:[0-9]*)))?(?:/(?:(?:(?:(?:(?:(?:[a-zA-Z0-9\-_.!~*'():@&=+$,]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*)(?:;(?:(?:[a-zA-Z0-9\-_.!~*'():@&=+$,]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*))*)(?:/(?:(?:(?:[a-zA-Z0-9\-_.!~*'():@&=+$,]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*)(?:;(?:(?:[a-zA-Z0-9\-_.!~*'():@&=+$,]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*))*))*))(?:[?](?:(?:(?:[;/?:@&=+$,a-zA-Z0-9\-_.!~*'()]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*)))?))?)|(?:(?:nntp)://(?:(?:(?:(?:(?:(?:(?:(?:[a-zA-Z0-9][-a-zA-Z0-9]*)?[a-zA-Z0-9])[.])*(?:[a-zA-Z][-a-zA-Z0-9]*[a-zA-Z0-9]|[a-zA-Z]))|(?:[0-9]+[.][0-9]+[.][0-9]+[.][0-9]+)))(?::(?:(?:[0-9]+)))?)/(?:(?:[a-zA-Z][-A-Za-z0-9.+_]*))(?:/(?:[0-9]+))?))|(?:(?:file)://(?:(?:(?:(?:(?:(?:(?:(?:[a-zA-Z0-9][-a-zA-Z0-9]*)?[a-zA-Z0-9])[.])*(?:[a-zA-Z][-a-zA-Z0-9]*[a-zA-Z0-9]|[a-zA-Z]))|(?:[0-9]+[.][0-9]+[.][0-9]+[.][0-9]+))|localhost)?)(?:/(?:(?:(?:(?:[-a-zA-Z0-9$_.+!*'(),:@&=]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*)(?:/(?:(?:[-a-zA-Z0-9$_.+!*'(),:@&=]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*))*)))))|(?:(?:ftp)://(?:(?:(?:(?:[a-zA-Z0-9\-_.!~*'();:&=+$,]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*))(?:)@)?(?:(?:(?:(?:(?:(?:[a-zA-Z0-9][-a-zA-Z0-9]*)?[a-zA-Z0-9])[.])*(?:[a-zA-Z][-a-zA-Z0-9]*[a-zA-Z0-9]|[a-zA-Z])[.]?)|(?:[0-9]+[.][0-9]+[.][0-9]+[.][0-9]+)))(?::(?:(?:[0-9]*)))?(?:/(?:(?:(?:(?:(?:[a-zA-Z0-9\-_.!~*'():@&=+$,]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*)(?:/(?:(?:[a-zA-Z0-9\-_.!~*'():@&=+$,]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*))*))(?:;type=(?:[AIai]))?))?)|(?:(?:tel):(?:(?:(?:[+](?:[0-9\-.()]+)(?:;isub=[0-9\-.()]+)?(?:;postd=[0-9\-.()*#ABCDwp]+)?(?:(?:;(?:phone-context)=(?:(?:(?:[+][0-9\-.()]+)|(?:[0-9\-.()*#ABCDwp]+))|(?:(?:[!'E-OQ-VX-Z_e-oq-vx-z~]|(?:%(?:2[124-7CFcf]|3[AC-Fac-f]|4[05-9A-Fa-f]|5[1-689A-Fa-f]|6[05-9A-Fa-f]|7[1-689A-Ea-e])))(?:[!'()*\-.0-9A-Z_a-z~]+|(?:%(?:2[1-9A-Fa-f]|3[AC-Fac-f]|[4-6][0-9A-Fa-f]|7[0-9A-Ea-e])))*)))|(?:;(?:tsp)=(?: |(?:(?:(?:[A-Za-z](?:(?:(?:[-A-Za-z0-9]+)){0,61}[A-Za-z0-9])?)(?:[.](?:[A-Za-z](?:(?:(?:[-A-Za-z0-9]+)){0,61}[A-Za-z0-9])?))*))))|(?:;(?:(?:[!'*\-.0-9A-Z_a-z~]+|%(?:2[13-7ABDEabde]|3[0-9]|4[1-9A-Fa-f]|5[AEFaef]|6[0-9A-Fa-f]|7[0-9ACEace]))*)(?:=(?:(?:(?:(?:[!'*\-.0-9A-Z_a-z~]+|%(?:2[13-7ABDEabde]|3[0-9]|4[1-9A-Fa-f]|5[AEFaef]|6[0-9A-Fa-f]|7[0-9ACEace]))*)(?:[?](?:(?:[!'*\-.0-9A-Z_a-z~]+|%(?:2[13-7ABDEabde]|3[0-9]|4[1-9A-Fa-f]|5[AEFaef]|6[0-9A-Fa-f]|7[0-9ACEace]))*))?)|(?:%22(?:(?:%5C(?:[a-zA-Z0-9\-_.!~*'()]|(?:%[a-fA-F0-9][a-fA-F0-9])))|[a-zA-Z0-9\-_.!~*'()]+|(?:%(?:[01][a-fA-F0-9])|2[013-9A-Fa-f]|[3-9A-Fa-f][a-fA-F0-9]))*%22)))?))*)|(?:[0-9\-.()*#ABCDwp]+(?:;isub=[0-9\-.()]+)?(?:;postd=[0-9\-.()*#ABCDwp]+)?(?:;(?:phone-context)=(?:(?:(?:[+][0-9\-.()]+)|(?:[0-9\-.()*#ABCDwp]+))|(?:(?:[!'E-OQ-VX-Z_e-oq-vx-z~]|(?:%(?:2[124-7CFcf]|3[AC-Fac-f]|4[05-9A-Fa-f]|5[1-689A-Fa-f]|6[05-9A-Fa-f]|7[1-689A-Ea-e])))(?:[!'()*\-.0-9A-Z_a-z~]+|(?:%(?:2[1-9A-Fa-f]|3[AC-Fac-f]|[4-6][0-9A-Fa-f]|7[0-9A-Ea-e])))*)))(?:(?:;(?:phone-context)=(?:(?:(?:[+][0-9\-.()]+)|(?:[0-9\-.()*#ABCDwp]+))|(?:(?:[!'E-OQ-VX-Z_e-oq-vx-z~]|(?:%(?:2[124-7CFcf]|3[AC-Fac-f]|4[05-9A-Fa-f]|5[1-689A-Fa-f]|6[05-9A-Fa-f]|7[1-689A-Ea-e])))(?:[!'()*\-.0-9A-Z_a-z~]+|(?:%(?:2[1-9A-Fa-f]|3[AC-Fac-f]|[4-6][0-9A-Fa-f]|7[0-9A-Ea-e])))*)))|(?:;(?:tsp)=(?: |(?:(?:(?:[A-Za-z](?:(?:(?:[-A-Za-z0-9]+)){0,61}[A-Za-z0-9])?)(?:[.](?:[A-Za-z](?:(?:(?:[-A-Za-z0-9]+)){0,61}[A-Za-z0-9])?))*))))|(?:;(?:(?:[!'*\-.0-9A-Z_a-z~]+|%(?:2[13-7ABDEabde]|3[0-9]|4[1-9A-Fa-f]|5[AEFaef]|6[0-9A-Fa-f]|7[0-9ACEace]))*)(?:=(?:(?:(?:(?:[!'*\-.0-9A-Z_a-z~]+|%(?:2[13-7ABDEabde]|3[0-9]|4[1-9A-Fa-f]|5[AEFaef]|6[0-9A-Fa-f]|7[0-9ACEace]))*)(?:[?](?:(?:[!'*\-.0-9A-Z_a-z~]+|%(?:2[13-7ABDEabde]|3[0-9]|4[1-9A-Fa-f]|5[AEFaef]|6[0-9A-Fa-f]|7[0-9ACEace]))*))?)|(?:%22(?:(?:%5C(?:[a-zA-Z0-9\-_.!~*'()]|(?:%[a-fA-F0-9][a-fA-F0-9])))|[a-zA-Z0-9\-_.!~*'()]+|(?:%(?:[01][a-fA-F0-9])|2[013-9A-Fa-f]|[3-9A-Fa-f][a-fA-F0-9]))*%22)))?))*))))|(?:(?:fax):(?:(?:(?:[+](?:[0-9\-.()]+)(?:;isub=[0-9\-.()]+)?(?:;tsub=[0-9\-.()]+)?(?:;postd=[0-9\-.()*#ABCDwp]+)?(?:(?:;(?:phone-context)=(?:(?:(?:[+][0-9\-.()]+)|(?:[0-9\-.()*#ABCDwp]+))|(?:(?:[!'E-OQ-VX-Z_e-oq-vx-z~]|(?:%(?:2[124-7CFcf]|3[AC-Fac-f]|4[05-9A-Fa-f]|5[1-689A-Fa-f]|6[05-9A-Fa-f]|7[1-689A-Ea-e])))(?:[!'()*\-.0-9A-Z_a-z~]+|(?:%(?:2[1-9A-Fa-f]|3[AC-Fac-f]|[4-6][0-9A-Fa-f]|7[0-9A-Ea-e])))*)))|(?:;(?:tsp)=(?: |(?:(?:(?:[A-Za-z](?:(?:(?:[-A-Za-z0-9]+)){0,61}[A-Za-z0-9])?)(?:[.](?:[A-Za-z](?:(?:(?:[-A-Za-z0-9]+)){0,61}[A-Za-z0-9])?))*))))|(?:;(?:(?:[!'*\-.0-9A-Z_a-z~]+|%(?:2[13-7ABDEabde]|3[0-9]|4[1-9A-Fa-f]|5[AEFaef]|6[0-9A-Fa-f]|7[0-9ACEace]))*)(?:=(?:(?:(?:(?:[!'*\-.0-9A-Z_a-z~]+|%(?:2[13-7ABDEabde]|3[0-9]|4[1-9A-Fa-f]|5[AEFaef]|6[0-9A-Fa-f]|7[0-9ACEace]))*)(?:[?](?:(?:[!'*\-.0-9A-Z_a-z~]+|%(?:2[13-7ABDEabde]|3[0-9]|4[1-9A-Fa-f]|5[AEFaef]|6[0-9A-Fa-f]|7[0-9ACEace]))*))?)|(?:%22(?:(?:%5C(?:[a-zA-Z0-9\-_.!~*'()]|(?:%[a-fA-F0-9][a-fA-F0-9])))|[a-zA-Z0-9\-_.!~*'()]+|(?:%(?:[01][a-fA-F0-9])|2[013-9A-Fa-f]|[3-9A-Fa-f][a-fA-F0-9]))*%22)))?))*)|(?:[0-9\-.()*#ABCDwp]+(?:;isub=[0-9\-.()]+)?(?:;tsub=[0-9\-.()]+)?(?:;postd=[0-9\-.()*#ABCDwp]+)?(?:;(?:phone-context)=(?:(?:(?:[+][0-9\-.()]+)|(?:[0-9\-.()*#ABCDwp]+))|(?:(?:[!'E-OQ-VX-Z_e-oq-vx-z~]|(?:%(?:2[124-7CFcf]|3[AC-Fac-f]|4[05-9A-Fa-f]|5[1-689A-Fa-f]|6[05-9A-Fa-f]|7[1-689A-Ea-e])))(?:[!'()*\-.0-9A-Z_a-z~]+|(?:%(?:2[1-9A-Fa-f]|3[AC-Fac-f]|[4-6][0-9A-Fa-f]|7[0-9A-Ea-e])))*)))(?:(?:;(?:phone-context)=(?:(?:(?:[+][0-9\-.()]+)|(?:[0-9\-.()*#ABCDwp]+))|(?:(?:[!'E-OQ-VX-Z_e-oq-vx-z~]|(?:%(?:2[124-7CFcf]|3[AC-Fac-f]|4[05-9A-Fa-f]|5[1-689A-Fa-f]|6[05-9A-Fa-f]|7[1-689A-Ea-e])))(?:[!'()*\-.0-9A-Z_a-z~]+|(?:%(?:2[1-9A-Fa-f]|3[AC-Fac-f]|[4-6][0-9A-Fa-f]|7[0-9A-Ea-e])))*)))|(?:;(?:tsp)=(?: |(?:(?:(?:[A-Za-z](?:(?:(?:[-A-Za-z0-9]+)){0,61}[A-Za-z0-9])?)(?:[.](?:[A-Za-z](?:(?:(?:[-A-Za-z0-9]+)){0,61}[A-Za-z0-9])?))*))))|(?:;(?:(?:[!'*\-.0-9A-Z_a-z~]+|%(?:2[13-7ABDEabde]|3[0-9]|4[1-9A-Fa-f]|5[AEFaef]|6[0-9A-Fa-f]|7[0-9ACEace]))*)(?:=(?:(?:(?:(?:[!'*\-.0-9A-Z_a-z~]+|%(?:2[13-7ABDEabde]|3[0-9]|4[1-9A-Fa-f]|5[AEFaef]|6[0-9A-Fa-f]|7[0-9ACEace]))*)(?:[?](?:(?:[!'*\-.0-9A-Z_a-z~]+|%(?:2[13-7ABDEabde]|3[0-9]|4[1-9A-Fa-f]|5[AEFaef]|6[0-9A-Fa-f]|7[0-9ACEace]))*))?)|(?:%22(?:(?:%5C(?:[a-zA-Z0-9\-_.!~*'()]|(?:%[a-fA-F0-9][a-fA-F0-9])))|[a-zA-Z0-9\-_.!~*'()]+|(?:%(?:[01][a-fA-F0-9])|2[013-9A-Fa-f]|[3-9A-Fa-f][a-fA-F0-9]))*%22)))?))*))))|(?:(?:prospero)://(?:(?:(?:(?:(?:(?:[a-zA-Z0-9][-a-zA-Z0-9]*)?[a-zA-Z0-9])[.])*(?:[a-zA-Z][-a-zA-Z0-9]*[a-zA-Z0-9]|[a-zA-Z]))|(?:[0-9]+[.][0-9]+[.][0-9]+[.][0-9]+)))(?::(?:(?:[0-9]+)))?/(?:(?:(?:(?:[-a-zA-Z0-9$_.+!*'(),?:@&=]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*)(?:/(?:(?:[-a-zA-Z0-9$_.+!*'(),?:@&=]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*))*))(?:(?:;(?:(?:[-a-zA-Z0-9$_.+!*'(),?:@&]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*)=(?:(?:[-a-zA-Z0-9$_.+!*'(),?:@&]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*))*))|(?:(?:tv):(?:(?:(?:(?:(?:[a-zA-Z0-9][-a-zA-Z0-9]*)?[a-zA-Z0-9])[.])*(?:[a-zA-Z][-a-zA-Z0-9]*[a-zA-Z0-9]|[a-zA-Z])[.]?))?)|(?:(?:telnet)://(?:(?:(?:(?:(?:[-a-zA-Z0-9$_.+!*'(),;?&=]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*))(?::(?:(?:(?:[-a-zA-Z0-9$_.+!*'(),;?&=]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*)))?)@)?(?:(?:(?:(?:(?:(?:(?:[a-zA-Z0-9][-a-zA-Z0-9]*)?[a-zA-Z0-9])[.])*(?:[a-zA-Z][-a-zA-Z0-9]*[a-zA-Z0-9]|[a-zA-Z]))|(?:[0-9]+[.][0-9]+[.][0-9]+[.][0-9]+)))(?::(?:(?:[0-9]+)))?)(?:/)?)|(?:(?:news):(?:(?:[*]|(?:(?:[-a-zA-Z0-9$_.+!*'(),;/?:&=]+|(?:%[a-fA-F0-9][a-fA-F0-9]))+@(?:(?:(?:(?:(?:[a-zA-Z0-9][-a-zA-Z0-9]*)?[a-zA-Z0-9])[.])*(?:[a-zA-Z][-a-zA-Z0-9]*[a-zA-Z0-9]|[a-zA-Z]))|(?:[0-9]+[.][0-9]+[.][0-9]+[.][0-9]+)))|(?:[a-zA-Z][-A-Za-z0-9.+_]*))))|(?:(?:wais)://(?:(?:(?:(?:(?:(?:[a-zA-Z0-9][-a-zA-Z0-9]*)?[a-zA-Z0-9])[.])*(?:[a-zA-Z][-a-zA-Z0-9]*[a-zA-Z0-9]|[a-zA-Z]))|(?:[0-9]+[.][0-9]+[.][0-9]+[.][0-9]+)))(?::(?:(?:[0-9]+)))?/(?:(?:(?:(?:[-a-zA-Z0-9$_.+!*'(),]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*))(?:[?](?:(?:(?:[-a-zA-Z0-9$_.+!*'(),;:@&=]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*))|/(?:(?:(?:[-a-zA-Z0-9$_.+!*'(),]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*))/(?:(?:(?:[-a-zA-Z0-9$_.+!*'(),]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*)))?))|(?:(?:gopher)://(?:(?:(?:(?:(?:(?:[a-zA-Z0-9][-a-zA-Z0-9]*)?[a-zA-Z0-9])[.])*(?:[a-zA-Z][-a-zA-Z0-9]*[a-zA-Z0-9]|[a-zA-Z]))|(?:[0-9]+[.][0-9]+[.][0-9]+[.][0-9]+)))(?::(?:(?:[0-9]+)))?/(?:(?:(?:[0-9+IgT]))(?:(?:(?:[-a-zA-Z0-9$_.+!*'(),:@&=]+|(?:%[a-fA-F0-9][a-fA-F0-9]))*))))|(?:(?:pop)://(?:(?:(?:(?:[-a-zA-Z0-9$_.+!*'(),&=~]+|(?:%[a-fA-F0-9][a-fA-F0-9]))+))(?:;AUTH=(?:[*]|(?:(?:(?:[-a-zA-Z0-9$_.+!*'(),&=~]+|(?:%[a-fA-F0-9][a-fA-F0-9]))+)|(?:[+](?:APOP|(?:(?:[-a-zA-Z0-9$_.+!*'(),&=~]+|(?:%[a-fA-F0-9][a-fA-F0-9]))+))))))?@)?(?:(?:(?:(?:(?:(?:[a-zA-Z0-9][-a-zA-Z0-9]*)?[a-zA-Z0-9])[.])*(?:[a-zA-Z][-a-zA-Z0-9]*[a-zA-Z0-9]|[a-zA-Z]))|(?:[0-9]+[.][0-9]+[.][0-9]+[.][0-9]+)))(?::(?:(?:[0-9]+)))?))",
							"<a href=\"\\0\" target=\"_blank\" title=\"Otvara se u novom prozoru\">\\0</a>", 
							$this -> sNewMessage);*/

		/*$this -> sNewMessage = ereg_replace(
							"(((URL:|url:|http:|htt:)\/\/)|www\.)(((([A-Za-z0-9][A-Za-z0-9-]*[A-Za-z0-9]|[A-Za-z0-9])\.)*([a-zA-Z][A-Za-z0-9-]*[A-Za-z0-9]|[a-zA-Z]))|([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+))(:[0-9]+)?(\/([a-zA-Z0-9$_.+!*'(,);:@&=\~\#-]|%[0-9A-Fa-f][0-9A-Fa-f])*(\/([a-zA-Z0-9$_.+!*'(,);:@&=\~\#-]|%[0-9A-Fa-f][0-9A-Fa-f])*)*(\?([a-zA-Z0-9$_.+!*'(,);:@&=\~\#-]|%[0-9A-Fa-f][0-9A-Fa-f])*)?)?",
							"<a href=\"\\0\" target=\"_blank\" title=\"Otvara se u novom prozoru\">\\0</a>", 
							$this -> sNewMessage);*/
		
					
	}

	function CommunicatorBuildUserList()
	{
		(int) $i = 1;
		(string) $sUserList = '<select id="UserList" name="UserList" class="boxlogin">
			<option value="main">predvorje</option><optgroup label="korisnici">';
		(string) $sNewMessage = '';
		$this -> DB_Spoji('is');
		(string) $sQuery = 'SELECT id, ime, email, mob FROM zaposlenici ORDER BY ime ASC';
		$rResult = $this -> DB_Upit($sQuery);
		(array) $aUsers = mysql_fetch_array($rResult, MYSQL_ASSOC);
		$this -> GetMessageRoomSize('main');
		$now = time();

		while($aUsers)
		{
			if($aUsers['ime'] != $_SESSION['zaposlenik_ime'])
			{
				$last = file_get_contents('_modals/online/'.$aUsers['id'].'.log');

				$bck = (($last + 300 <= $now) || empty($last)) ? ' class="r" style="background: url(gfx/disconnect.gif) no-repeat;background-position:right;"' : ' class="g" style="background: url(gfx/connect.gif) no-repeat;background-position:right;"';
				$sSMS = (substr($aUsers['mob'], 0, 2) == 98) ? "385{$aUsers['mob']}@sms.t-mobile.hr" : "385{$aUsers['mob']}@sms.vip.hr";
				$sUserList .= sprintf("<option sms=\"%s\" email=\"%s\" value=\"%s\" id=\"chat_user_".$aUsers['id']."\" $bck>%s</option>\n", $sSMS, $aUsers['email'], $this -> SFVChecksum($aUsers['ime']), $aUsers['ime']);
				$this -> GetMessageRoomSize($this -> SFVChecksum($aUsers['ime']));
			}
			$aUsers = mysql_fetch_array($rResult, MYSQL_ASSOC);
			$i ++;
		}

		$sUserList .= '</optgroup></select>';
		mysql_free_result($rResult);
		$this -> DB_Zatvori();
		return $sUserList;
	}

	function CommunicatorSelectRoom()
	{
		$room = isset($_POST['room']) ? $_POST['room'] : 'main';

		switch($room)
		{
			case 'main': $this -> sMessageRoom = COMMUNICATOR_MAIN_ROOT_FILENAME; break;
			default: $this -> sMessageRoom = $this -> _CommunicatorGetRoomExists($room); break;
		}
	}

	function _CommunicatorGetRoomExists($sUserRoom)
	{
		(string) $sRoom = '';
		(string) $sDir = COMMUNICATOR_MAIN_DIRNAME;
		(string) $sSearchA = $sUserRoom;
		(string) $sSearchB = $this -> SFVChecksum($_SESSION['zaposlenik_ime']);

		if(is_dir($sDir))
		{
			$rDir = opendir($sDir);
			if($rDir != FALSE)
			{
				$sChatRoomLog = readdir($rDir);
				while($sChatRoomLog !== FALSE)
				{
					if(strpos($sChatRoomLog, $sSearchA) !== FALSE
					&& strpos($sChatRoomLog, $sSearchB) !== FALSE)
					{
						$sRoom = $sChatRoomLog;
						break;
					}
					$sChatRoomLog = readdir($rDir);
				}
				closedir($rDir);
			}
		}

		if(empty($sRoom))
		{
			$sRoom = sprintf("$sDir%s_%s.log", $sSearchA, $sSearchB);
			$rFile = fopen($sRoom, 'wb');
			fclose($rFile);
		}

		return str_replace(COMMUNICATOR_MAIN_DIRNAME, '', $sRoom);
	}

	function _CommunicatorClearRoom()
	{
		$comm = (isset($_POST['comm'])) ? $_POST['comm'] : NULL;

		if($comm == 'clear-room' && $this -> sMessageRoom != 'main')
		{
			$rFile = fopen($this -> sMessageRoom, 'wb');
			fclose($rFile);
			return TRUE;
		}
		return FALSE;
	}

	function _CommunicatorGetMessageType()
	{
		$this -> nMessageType = (isset($_POST['type'])) ? $_POST['type'] : 0;

		if(($this -> nMessageType & COMMUNICATOR_MSG_TYPE_IM) > 0x00) {
			$this -> bMessageIM = TRUE;
		}
		if(($this -> nMessageType & COMMUNICATOR_MSG_TYPE_SMS) > 0x00) {
			$this -> bMessageSMS = TRUE;
		}
		if(($this -> nMessageType & COMMUNICATOR_MSG_TYPE_EMAIL) > 0x00) {
			$this -> bMessageMail = TRUE;
		}
	}

	function GetMessageRoomSize($sRoom)
	{
		$sRoom = ($sRoom == 'main') ? COMMUNICATOR_MAIN_ROOT_FILENAME : $this -> _CommunicatorGetRoomExists($sRoom);
		$sRoom = str_replace($sRoom);
		$_SESSION['chat_room_size_$sRoom'] = $this -> SFVChecksum(file_get_contents($sRoom));
	}

	function ConvertHMTLEnitites($sInput)
	{
		// * HTML tags for removal
		(array) $aEntities = array(
'€' => '&euro;',
'`' =>	'&#96;',
'¢' =>	'&cent;',
'£' =>	'&pound;',
'¤' =>	'&curren;',
'¥' =>	'&yen;',
'§' =>	'&sect;',
'¨' =>	'&uml;',
'©' =>	'&copy;',
'ª' =>	'&ordf;',
'«' =>	'&#171; ',
'¬' =>	'&not;',
'®' =>	'&reg;',
'¯' =>	'&macr;',
'°' =>	'&deg;',
'²' =>	'&sup2;',
'³' =>	'&sup3;',
'´' =>	'&acute;',
'¶' =>	'&para;',
'·' =>	'&middot;',
'¸' =>	'&cedil;',
'¹' =>	'&sup1;',
'º' =>	'&ordm;',
'»' =>	'&raquo;',
'¼' =>	'&frac14;',
'½' =>	'&frac12;',
'¾' =>	'&frac34;',
'¿' =>	'&iquest;',
'À' =>	'&Agrave;',
'Á' =>	'&Aacute;',
'Â' =>	'&#194;',
'Ã' =>	'&Atilde;',
'Ä' =>	'&Auml;',
'Å' =>	'&Aring;',
'Æ' =>	'&AElig;',
'Ç' =>	'&Ccedil;',
'È' =>	'&Egrave;',
'É' =>	'&Eacute;',
'Ê' =>	'&Ecirc;',
'Ë' =>	'&Euml;',
'Ì' =>	'&Igrave;',
'Í' =>	'&Iacute;',
'Î' =>	'&Icirc;',
'Ï' =>	'&Iuml;',
'Ð' =>	'&ETH;',
'Ñ' =>	'&Ntilde;',
'Ò' =>	'&Ograve;',
'Ó' =>	'&Oacute;',
'Ô' =>	'&Ocirc;',
'Õ' =>	'&Otilde;',
'Ö' =>	'&Ouml;',
'×' =>	'&times;',
'Ø' =>	'&Oslash;',
'Ù' =>	'&Ugrave;',
'Ú' =>	'&Uacute;',
'Û' =>	'&Ucirc;',
'Ü' =>	'&Uuml;',
'Ý' =>	'&Yacute;',
'Þ' =>	'&THORN;',
'ß' =>	'&szlig;',
'à' =>	'&agrave;',
'á' =>	'&aacute;',
'â' =>	'&acirc;',
'ã' =>	'&atilde;',
'ä' =>	'&auml;',
'å' =>	'&aring;',
'æ' =>	'&aelig;',
'ç' =>	'&ccedil;',
'è' =>	'&egrave;',
'é' =>	'&eacute;',
'ê' =>	'&ecirc;',
'ë' =>	'&euml;',
'ì' =>	'&igrave;',
'í' =>	'&iacute;',
'î' =>	'&icirc;',
'ï' =>	'&iuml;',
'ð' =>	'&eth;',
'ñ' =>	'&ntilde;',
'ò' =>	'&ograve;',
'ó' =>	'&oacute;',
'ô' =>	'&ocirc;',
'õ' =>	'&otilde;',
'ö' =>	'&ouml;',
'÷' =>	'&divide;',
'ø' =>	'&oslash;',
'ù' =>	'&ugrave;',
'ú' =>	'&uacute;',
'û' =>	'&ucirc;',
'ü' =>	'&uuml;',
'ý' =>	'&yacute;',
'þ' =>	'&thorn;',
'ÿ' =>	'&#255;',
'Ā' =>	'&#256;',
'ā' =>	'&#257;',
'Ă' =>	'&#258;',
'ă' =>	'&#259;',
'Ą' =>	'&#260;',
'ą' =>	'&#261;',
'Ć' =>	'&#262;',
'ć' =>	'&#263;',
'Ĉ' =>	'&#264;',
'ĉ' =>	'&#265;',
'Ċ' =>	'&#266;',
'ċ' =>	'&#267;',
'Č' =>	'&#268;',
'č' =>	'&#269;',
'Ď' =>	'&#270;',
'ď' =>	'&#271;',
'Đ' =>	'&#272;',
'đ' =>	'&#273;',
'Ē' =>	'&#274;',
'ē' =>	'&#275;',
'Ĕ' =>	'&#276;',
'ĕ' =>	'&#277;',
'Ė' =>	'&#278;',
'ė' =>	'&#279;',
'Ę' =>	'&#280;',
'ę' =>	'&#281;',
'Ě' =>	'&#282;',
'ě' =>	'&#283;',
'Ĝ' =>	'&#284;',
'ĝ' =>	'&#285;',
'Ğ' =>	'&#286;',
'ğ' =>	'&#287;',
'Ġ' =>	'&#288;',
'ġ' =>	'&#289;',
'Ģ' =>	'&#290;',
'ģ' =>	'&#291;',
'Ĥ' =>	'&#292;',
'ĥ' =>	'&#293;',
'Ħ' => 	'&#294;',
'ħ' =>	'&#295;',
'Ĩ' =>	'&#296;',
'ĩ' =>	'&#297;',
'Ī' =>	'&#298;',
'ī' =>	'&#299;',
'Ĭ' =>	'&#300;',
'ĭ' =>	'&#301;',
'Į' =>	'&#302;',
'į' =>	'&#303;',
'İ' =>	'&#304;',
'ı' =>	'&#305;',
'Ĳ' =>	'&#306;',
'ĳ'	=>	'&#307;',
'Ĵ' =>	'&#308;',
'ĵ' =>	'&#309;',
'Ķ' =>	'&#310;',
'ķ' =>	'&#311;',
'ĸ' 	=>	'&#312;',
'Ĺ' =>	'&#313;',
'ĺ' =>	'&#314;',
'Ļ' =>	'&#315;',
'ļ' =>	'&#316;',
'Ľ' =>	'&#317;',
'ľ' =>	'&#318;',
'Ŀ' =>	'&#319;',
'ŀ' 	=>	'&#320;',
'Ł' =>	'&#321;',	 
'ł' =>	'&#322;',	 
'Ń' =>	'&#323;',	 
'ń' =>	'&#324;',
'Ņ' =>	'&#325;',
'ņ' =>	'&#326;',
'Ň' =>	'&#327;',	 
'ň' =>	'&#328;',	 
'ŉ' =>	'&#329;',
'Ŋ' =>	'&#330;',
'ŋ' =>	'&#331;',	 
'Ō' =>	'&#332;',	 
'ō' =>	'&#333;',	 
'Ŏ' =>	'&#334;',
'ŏ' =>	'&#335;',
'Ő' =>	'&#336;',
'ő' =>	'&#337;',
'Œ' =>	'&#338;',
'œ' =>	'&#339;',
'Ŕ' =>	'&#340;',
'ŕ' =>	'&#341;',
'Ŗ' =>	'&#342;',
'ŗ' =>	'&#343;',
'Ř' =>	'&#344;',
'ř' =>	'&#345;',
'Ś' =>	'&#346;',
'ś' =>	'&#347;',
'Ŝ' =>	'&#348;',
'ŝ' =>	'&#349;',
'Ş' =>	'&#350;',
'ş' =>	'&#351;',
'Š' =>	'&#352;',
'š' =>	'&#353;',
'Ţ' =>	'&#354;',
'ţ' =>	'&#355;',
'Ť' =>	'&#356;',
'ť' =>	'&#357;',
'Ŧ' =>	'&#358;',
'ŧ' =>	'&#359;',
'Ũ' =>	'&#360;',
'ũ' =>	'&#361;',
'Ū' =>	'&#362;',
'ū' =>	'&#363;',
'Ŭ' =>	'&#364;',
'ŭ' =>	'&#365;',
'Ů' =>	'&#366;',
'ů' =>	'&#367;',
'Ű' =>	'&#368;',
'ű' =>	'&#369;',
'Ų' =>	'&#370;',
'ų' =>	'&#371;',
'Ŵ' =>	'&#372;',
'ŵ' =>	'&#373;',
'Ŷ' =>	'&#374;',
'ŷ' =>	'&#375;',
'Ÿ' =>	'&#376;',
'Ź' =>	'&#377;',
'ź' =>	'&#378;',
'Ż' =>	'&#379;',
'ż' =>	'&#380;',
'Ž' =>	'&#381;',
'ž' =>	'&#382;',
'ſ' =>	'&#383;',
'Ŕ' =>	'&#340;',
'ŕ' =>	'&#341;',
'Ŗ' =>	'&#342;',
'ŗ' =>	'&#343;',
'Ř' =>	'&#344;',
'ř' =>	'&#345;',
'Ś' =>	'&#346;',
'ś' =>	'&#347;',
'Ŝ' =>	'&#348;',
'ŝ' =>	'&#349;',
'Ş' =>	'&#350;',
'ş' =>	'&#351;',
'Š' =>	'&#352;',
'š' =>	'&#353;',
'Ţ' =>	'&#354;',
'ţ' =>	'&#355;',
'Ť' =>	'&#356;',
'ť' =>	'&#577;',
'Ŧ' =>	'&#358;',
'ŧ' =>	'&#359;',
'Ũ' =>	'&#360;',
'ũ' =>	'&#361;',
'Ū' =>	'&#362;',
'ū' =>	'&#363;',
'Ŭ' =>	'&#364;',
'ŭ' =>	'&#365;',
'Ů' =>	'&#366;',
'ů' =>	'&#367;',
'Ű' =>	'&#368;',
'ű' =>	'&#369;',
'Ų' =>	'&#370;',
'ų' =>	'&#371;',
'Ŵ' =>	'&#372;',
'ŵ' =>	'&#373;',
'Ŷ' =>	'&#374;',
'ŷ' =>	'&#375;',
'Ÿ' =>	'&#376;',
'Ź' =>	'&#377;',
'ź' =>	'&#378;',
'Ż' =>	'&#379;',
'ż' =>	'&#380;',
'Ž' =>	'&#381;',
'ž' =>	'&#382;',
'ſ' =>	'&#383;',
'‰'	=>	'&permil;',
'†'	=>	'&dagger;',
'‡'	=>	'&Dagger;',
'…'	=>	'&hellip;',
'“'	=>	'&ldquo;',
'”'	=>	'&rdquo;',
'„'	=>	'&bdquo;',
'‹'	=>	'&lsaquo;',
'›'	=>	'&rsaquo;',
'Œ'	=>	'&OElig;',
'œ'	=>	'&oelig;',
'™'	=>	'&trade;',
'ƒ' => '&fnof;',
'◊' => '&loz;',
'♠' => '&spades;',
'♣' => '&clubs;',
'♥' => '&hearts;',
'♦' => '&diams;',
'⁄' => '&frasl;',
// greek
'Α' => '&Alpha;',
'α' => '&alpha;',
'Β' => '&Beta;',
'β' => '&beta;',
'Χ' => '&Chi;',
'χ' => '&chi;',
'Δ' => '&Delta;',
'δ' => '&delta;',
'Ε' => '&Epsilon;',
'ε' => '&epsilon;',
'Η' => '&Eta;',
'η' => '&eta;',
'Γ' => '&Gamma;',
'γ' => '&gamma;',
'Ι' => '&Iota;',
'ι' => '&iota;',
'Κ' => '&Kappa;',
'κ' => '&kappa;',
'Λ' => '&Lambda;',
'λ' => '&lambda;',
'Μ' => '&Mu;',
'μ' => '&mu;',
'Ν' => '&Nu;',
'ν' => '&nu;',
'Ω' => '&Omega;',
'ω' => '&omega;',
'Ο' => '&Omicron;',
'ο' => '&omicron;',
'Φ' => '&Phi;',
'φ' => '&phi;',
'Π' => '&Pi;',
'π' => '&pi;',
'ϖ' => '&piv;',
'Ψ' => '&Psi;',
'ψ' => '&psi;',
'Ρ' => '&Rho;',
'ρ' => '&rho;',
'Σ' => '&Sigma;',
'σ' => '&sigma;',
'ς' => '&sigmaf;',
'Τ' => '&Tau;',
'τ' => '&tau;',
'Θ' => '&Theta;',
'θ' => '&theta;',
'ϑ' => '&thetasym;',
'ϒ' => '&upsih;',
'Υ' => '&Upsilon;',
'υ' => '&upsilon;',
'Ξ' => '&Xi;',
'ξ' => '&xi;',
'Ζ' => '&Zeta;',
'ζ' => '&zeta;',
// greek end
'ℵ' => '&alefsym;',
'∧' => '&and;',
'∠' => '&ang;',
'≈' => '&asymp;',
'∩' => '&cap;',
'≅' => '&cong;',
'∪' => '&cup;',
'∅' => '&empty;',
'≡' => '&#8801;',
'∃' => '&exist;',
'ƒ' => '&fnof;',
'∀' => '&forall;',
'∞' => '&infin;',
'∫' => '&int;',
'∈' => '&isin;',
'〈' => '&lang;',
'⌈' => '&lceil;',
'⌊' => '&lfloor;',
'∗' => '&lowast;',
'µ' => '&micro;',
'∇' => '&nabla;',
'≠' => '&ne;',
'∋' => '&ni;',
'∉' => '&notin;',
'⊄' => '&nsub;',
'⊕' => '&oplus;',
'∨' => '&or;',
'⊗' => '&otimes;',
'∂' => '&part;',
'⊥' => '&perp;',
'±' => '&plusmn;',
'∏' => '&prod;',
'∝' => '&prop;',
'√' => '&radic;',
'〉' => '&rang;',
'⌉' => '&rceil;',
'⌋' => '&rfloor;',
'⋅' => '&sdot;',
'⊂' => '&sub;',
'⊆' => '&sube;',
'∑' => '&sum;',
'⊃' => '&sup;',
'⊇' => '&supe;',
'∴' => '&there4;'
					);

		$aForRemoval = array_keys($aEntities);
		$aForReplacement = array_values($aEntities);

		$sInput = str_replace($aForRemoval, $aForReplacement, $sInput);

		return $sInput;
	}

	function utf8RawUrlDecode($source)
	{
		$decodedStr = '';
		$pos = 0;
		$len = strlen ($source);
		while ($pos < $len) 
		{
			$charAt = substr ($source, $pos, 1);
			if ($charAt == '%') 
			{
				$pos++;
				$charAt = substr ($source, $pos, 1);
				if ($charAt == 'u') 
				{
					// we got a unicode character
					$pos++;
					$unicodeHexVal = substr ($source, $pos, 4);
					$unicode = hexdec ($unicodeHexVal);
					$entity = '&#'. $unicode . ';';
					$decodedStr .= utf8_encode ($entity);
					$pos += 4;
				}
				else 
				{
					// we have an escaped ascii character
					$hexVal = substr ($source, $pos, 2);
					$decodedStr .= chr (hexdec ($hexVal));
					$pos += 2;
				}
			}
			else 
			{
				$decodedStr .= $charAt;
				$pos++;
			}
		}
		return $decodedStr;
	}
}

?>