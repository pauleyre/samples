<?php

include 'logic/class.Communicator.php';
$c = new Communicator();

?>
<div id="chat_div" style="top: 50px; left: 1em; position: absolute; display:none;">
<table width="282" height="540" background="gfx/komjunikejtor.gif" style="background-repeat: no-repeat;" cellspacing="0" cellpadding="0" border="0">
		<tr valign="top">
    		<td><img src="gfx/empty.gif" width="26" height="540" alt="" border="0" /><br></td>
			<td>
				<img src="gfx/empty.gif" ondblclick="sh_comm()" title="Hold to drag or click twice to close" alt="Hold to drag or click twice to close" style="cursor: move;" id="handle" width="230" height="55" alt="" border="0" /><br>
				<span id="UserListDiv"><?php echo $c->getUserList(); ?></span><br>
				<img src="gfx/empty.gif" width="1" height="10" alt="" border="0" /><br>
				<textarea name="MessageEntry" class="commporuka" id="MessageEntry" onkeypress="CommunicatorSendOnEnter(event);"></textarea>
				<input type="hidden" value="<?php echo $_SESSION['employee']['email']; ?>" id="comm_my_mail" />
				<img src="gfx/empty.gif" width="1" height="10" alt="" border="0" /><br>
				<table width="230" cellspacing="0" cellpadding="2" border="0">
				<tr valign="top">
					<td><input name="comm_type_im" type="checkbox" id="comm_type_im" value="1" checked="checked" /><br></td>
					<td><label for="comm_type_im" class="comm">im</label></td>
					<td><input name="comm_type_sms" type="checkbox" id="comm_type_sms" value="2" /><br></td>
					<td><label for="comm_type_sms" class="comm">sms</label></td>
					<td><input name="comm_type_mail" type="checkbox" id="comm_type_mail" value="4" /><br></td>
					<td><label for="comm_type_mail" class="comm">mail</label></td>
					<td align="right" width="30%"><input name="Button" class="littleKomunikator" type="button" onclick="CommunicatorSendMessage('');" style="padding-left:8px; padding-right:8px;" value="Šalji" />
					<br></td>
				</tr>
				</table>
				<img src="gfx/empty.gif" width="1" height="12" alt="" border="0" /><br>
				<div class="commchat" name="MessageDisplay" id="MessageDisplay" style="overflow: auto;"></div>

				<img src="gfx/empty.gif" width="1" height="10" alt="" border="0" /><br>
				<input name="ClearLog" class="littleKomunikator" type="button" disabled="disabled" id="ClearLog" onclick="CommunicatorClearRoom();" style="padding-left:8px; padding-right:8px;" value="Obriši" />
			<br>			</td>
			<td><img src="gfx/empty.gif" width="26" height="540" alt="" border="0" /><br></td>
		</tr>
  </table>
</div>
<a accesskey=C href=sh_comm()></a>
<script src=web/js/communicator.js></script>
<script src=web/js//dom-drag.js></script>
<script>
Drag.init($("handle"), $("chat_div"));
</script>