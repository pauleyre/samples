<?php

class Communicator
{
	const MAX_MESSAGES =  30;
	const MAIN_CHATROOM = 'main.log';
	const CHATROOMS_DIRNAME = 'web/chat_rooms/';
	const MSG_TYPE_IM = 1;
	const MSG_TYPE_SMS = 2;
	const MSG_TYPE_EMAIL = 4;

	public $new_msg;
	public $msg_log;
	public $msg_room;
	public $msg_type;
	public $msg_im;
	public $msg_sms;
	public $msg_mail;

	function __construct()
	{
		$this->selectRoom();
		$this->_getMessageType();
		$this->addNewMessage();
		$this->getMessagesLog();
	}

	function addNewMessage()
	{
		$this->new_msg = $this->getValidMessage();
		$clear_room = $this->_clearRoom();

		if($this->new_msg != NULL && !$clear_room && $this->msg_im) {
			//$sAllMessages .= "\xEF\xBB\xBF";					// * Add UTF-8 BOM

			include 'logic/func.utf8.php';
			include 'logic/func.hyperlink_txt.php';

			$this->new_msg = utf8_raw_url_decode($this->new_msg);
			$this->new_msg = utf8html($this->new_msg);
			$this->new_msg = hyperlink_txt($this->new_msg);

			$this->addSmileys();

			$all_msgs = file(self::CHATROOMS_DIRNAME . $this->msg_room);
			$new = (time()).'-==yXy==-'.$_SESSION['employee']['id'].'-==yXy==-'.$_SESSION['employee']['last_name'] .' '. $_SESSION['employee']['first_name'].'-==yXy==-'.$this->new_msg."\r\n";

			array_unshift($all_msgs, $new);

			$all_msgs = array_slice($all_msgs, 0, self::MAX_MESSAGES);

			if(!file_put_contents(self::CHATROOMS_DIRNAME . $this->msg_room, implode('', $all_msgs))) {
				trigger_error(__FUNCTION__.' : Unable to save message', E_WARNING);
			}

			// * update activity
			file_put_contents(self::CHATROOMS_DIRNAME . $_SESSION['employee']['id'] . '.log', time());
			$_SESSION["chat_room_size_{$this->msg_room}"] = (string) crc32(file_get_contents(self::CHATROOMS_DIRNAME . $this->msg_room));
		}
		if($this->new_msg != null && !$clear_room && $this->msg_mail) {
			$this->sendEmail();
		}
		if($this->new_msg != null && !$clear_room && $this->msg_sms) {
			$this->sendSMS();
		}
	}

	function sendSMS()
	{
		$this->new_msg = $this->getValidMessage();
		mail($_POST['email'], '', $this->new_msg, 'From: Manager');
	}

	function sendEmail()
	{
		$this->new_msg = $this->getValidMessage();
		mail($_POST['email'], '', $this->new_msg, 'From: Manager');
	}

	function getMessagesLog()
	{
		$log = file(self::CHATROOMS_DIRNAME . $this->msg_room);
		$now = time();

		foreach($log as $entry) {

			list($time, $id, $name, $msg) = explode('-==yXy==-', $entry);
			$last = file_get_contents(self::CHATROOMS_DIRNAME . $id . '.log');
			$class = (($last + 300 <= $now) || empty($last)) ? 'r' : 'g';
			$time = date('H:i', $time);

			$this->msg_log .= "<div class=cmsg><span class=$class>$name</span> [$time]: $msg</div>";
		}

		$crc32 = (string) crc32(file_get_contents(self::CHATROOMS_DIRNAME . $this->msg_room));

		if($_SESSION["chat_room_size_{$this->msg_room}"] != $crc32) {
			$this->msg_log .= '<!-- nova_poruka -->';
			$_SESSION["chat_room_size_{$this->msg_room}"] = $crc32;
		}
	}

	function getValidMessage()
	{
		$msg = (isset($_REQUEST['msg'])) ? trim($_REQUEST['msg']) : '';

		if($msg != '') {
			$msg = nl2br($msg);
			$msg = str_replace(array("\r", "\n", "\t"), '', $msg);
			return $msg;
		}

		return null;
	}

	function addSmileys()
	{
		$sml_img = array(
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
						21 => 'doubt.gif'
										);

		$sml_txt = array(
						'>:)' => $sml_img[12],
						'>:-)' => $sml_img[12],
						'>:D' => $sml_img[13],
						'>:-D' => $sml_img[13],
						':))' => $sml_img[0],
						':-))' => $sml_img[0],
						':D' => $sml_img[0],
						':-D' => $sml_img[0],
						':)' => $sml_img[1],
						':((' => $sml_img[11],
						':-((' => $sml_img[11],
						':-)' => $sml_img[1],
						':(' => $sml_img[2],
						':-(' => $sml_img[2],
						':o' => $sml_img[3],
						':-o' => $sml_img[3],
						'=o' => $sml_img[4],
						'=-o' => $sml_img[4],
						':?' => $sml_img[5],
						':-?' => $sml_img[5],
						'8)' => $sml_img[6],
						'8-)' => $sml_img[6],
						'lol' => $sml_img[7],
						'LOL' => $sml_img[7],
						':x' => $sml_img[8],
						':-x' => $sml_img[8],
						':p' => $sml_img[9],
						':-p' => $sml_img[9],
						';)' => $sml_img[15],
						';-)' => $sml_img[15],
						':!' => $sml_img[16],
						':-!' => $sml_img[16],
						'(?)' => $sml_img[17],
						'(!)' => $sml_img[18],
						'-->' => $sml_img[19],
						'->' => $sml_img[19],
						':|' => $sml_img[20],
						':-|' => $sml_img[20]
					);

		$img_tag = '<img src="./web/img/smileys/%s" class="sml">';

		foreach($sml_txt as $txt => $img) {
			$this->new_msg = str_replace($txt, sprintf($img_tag, $img), $this->new_msg);
		}
	}

	function getUserList()
	{
		global $db;

		$user_list = '<select id=UserList name=UserList class=boxlogin>
<option value=main>predvorje</option><optgroup label=Zaposlenici>';

		$r = sql_res('SELECT id, first_name, last_name, email, mobile FROM employee WHERE (id != %s) ORDER BY last_name, first_name ASC', $_SESSION['employee']['id']);
		$e = $db->fetch_assoc($r);
		$this->setMessageRoomSize(self::MAIN_CHATROOM);
		$now = time();

		while($e) {

			$last = file_get_contents(self::CHATROOMS_DIRNAME . $e['id'].'.log');

			$bck = (($last + 300 <= $now) || empty($last)) ? ' class=r style="background:url(./web/img/disconnect.gif) no-repeat;background-position:right;"' : ' class=g style="background:url(gfx/connect.gif) no-repeat;background-position:right;"';
			$sms = (substr($e['mobile'], 0, 2) == 98) ? "385{$e['mobile']}@sms.t-mobile.hr" : "385{$e['mobile']}@sms.vip.hr";
			$crc32 = (string) crc32($e['first_name'] . $e['last_name'] . $e['id']);

			$user_list .= "<option sms=\"$sms\" email=\"{$e['email']}\" value=\"$crc32\" id=\"chat_user_{$e['id']}\" $bck>{$e['last_name']} {$e['first_name']}</option>";
			$this->setMessageRoomSize($crc32);

			$e = $db->fetch_assoc($r);
		}

		$user_list .= '</optgroup></select>';

		return $user_list;
	}

	function selectRoom()
	{
		$room = isset($_REQUEST['room']) ? $_REQUEST['room'] : 'main';

		switch($room) {
			case 'main': $this->msg_room = self::MAIN_CHATROOM ; break;
			default: $this->msg_room = $this->_getRoomExists($room); break;
		}
	}

	function _getRoomExists($user_room)
	{
		$search_a = $user_room;
		$search_b = (string) crc32($_SESSION['employee']['first_name'] . $_SESSION['employee']['last_name'] . $_SESSION['employee']['id']);
		$sRoom = '';

		if(is_dir(self::CHATROOMS_DIRNAME)) {
			$rDir = opendir(self::CHATROOMS_DIRNAME);
			if($rDir != false) {
				$sChatRoomLog = readdir($rDir);
				while($sChatRoomLog !== false) {
					if(stripos($sChatRoomLog, $search_a) !== false
					&& stripos($sChatRoomLog, $search_b) !== false)
					{

						$sRoom = $sChatRoomLog;
						break;
					}
					$sChatRoomLog = readdir($rDir);
				}
				closedir($rDir);
			}
		}

		if(empty($sRoom)) {
			var_dump($sRoom);
			$sRoom = self::CHATROOMS_DIRNAME . $search_a . '_' . $search_b . '.log';
			file_put_contents($sRoom, '');
		}

		return str_replace(self::CHATROOMS_DIRNAME, '', $sRoom);
	}

	function _clearRoom()
	{
		$comm = (isset($_REQUEST['comm'])) ? $_REQUEST['comm'] : null;

		if($comm == 'clear-room' && $this->msg_room != 'main') {
			return file_put_contents(self::CHATROOMS_DIRNAME . $this->msg_room, '');
		}
		return false;
	}

	function _getMessageType()
	{
		$this->msg_type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : 0;

		if(($this->msg_type & self::MSG_TYPE_IM) > 0) {
			$this->msg_im = true;
		}
		if(($this->msg_type & self::MSG_TYPE_SMS) > 0) {
			$this->msg_sms = true;
		}
		if(($this->msg_type & self::MSG_TYPE_EMAIL) > 0) {
			$this->msg_mail = true;
		}
	}

	function setMessageRoomSize($room)
	{
		$room = ($room == self::MAIN_CHATROOM) ? $room : $this->_getRoomExists($room);
		$_SESSION["chat_room_size_$room"] = (string) crc32(file_get_contents(self::CHATROOMS_DIRNAME . $room));
	}
}

?>