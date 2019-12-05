<?php



?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!-- saved from url=(0013)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<title>Komunikator</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link href="communicator.css" rel="stylesheet" type="text/css" media="all" />
<script src="communicator.js" type="text/javascript"></script>
<script>

	function AddClient()
	{
		var nTop = Math.floor(screen.height/2-300/2);
		var nLeft = Math.floor(screen.width/2-400/2);

		if(window.open) {
			window.open("../_modals/klijent.php", "", "height=300, width=400, resize=0, scrollbars=yes, top=" + nTop + ", left=" + nLeft);
		}
	}

</script>
</head>
<body onload="javascript: CommunicatorLoad();">
	<div style="text-align: left;">
		<div class="Title">Komunikator&nbsp;<input type="button" value="adresar" onclick="javascript: AddClient();" /></div>
		<div style="padding-bottom: 2pt;">
			<textarea id="MessageEntry" name="MessageEntry" class="communicator_message_entry" onkeypress="javascript: CommunicatorSendOnEnter(event);" title="Enter your message"></textarea>
		</div>
		<div style="vertical-align: middle; padding-bottom: 2pt;" id="UserListDiv">
			<select id="UserList" name="UserList" title="Select a user" class="communicator_user_list">
				<option value="main">-- All --</option>
				<?= $oKomunikator -> CommunicatorBuildUserList(); ?>
			</select>
			<input name="Button" type="button" id="Button" onclick="javascript: CommunicatorSendMessage('');" class="ie_submit_input" value="> >" style="padding-left: 0px; padding-right: 0px;" />
			<input name="ClearLog" type="button" id="ClearLog" class="ie_submit_input" value="clear" onclick="javascript: CommunicatorClearRoom();" style="padding-left: 0px; padding-right: 0px;" />
		</div>
		<div id="communicator_send_as">
			<span style="padding-bottom: 3px; padding-right: 2px;">[IM]</span>
			<span><input name="comm_type_im" type="checkbox" id="comm_type_im" value="1" checked="checked" /></span>
			<span style="padding-bottom: 3px; padding-right: 2px;">[SMS]</span>
			<span><input name="comm_type_sms" type="checkbox" id="comm_type_sms" value="2" /></span>
			<span style="padding-bottom: 3px; padding-right: 2px;">[Mail]</span>
			<span><input name="comm_type_mail" type="checkbox" id="comm_type_mail" value="4" /></span>
		</div>
		<div class="MessageDisplay" name="MessageDisplay" id="MessageDisplay"></div>
	</div>
</body>
</html>