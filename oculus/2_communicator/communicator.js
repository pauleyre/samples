	var __comm_ajax_req;
	var __comm_ajax_req_created = false;
	var __comm_loaded = false;
	var __comm_ref_interval_id;
	var __comm_win_blurred = false;
	var __comm_romms = null;
	var __comm_room_id;
	var __comm_win_title;
	var __comm_win_title_interval_id;
	var __comm_reply_mail = '';
	var __comm_retry_interval = 50;
	var __comm_refresh_rate = 2000;
	var __comm_still_unfocused = false;
	var __comm_id = 0;
	var __comm_poll = new Array();

	function CommunicatorLoad()
	{
		if(__comm_loaded) {
			return true;
		}
		__comm_reply_mail = window.document.getElementById('comm_my_mail').value;
		//window.clearInterval(__comm_ref_interval_id);

		// * Native XMLHttpRequest object
    	try
		{
			__comm_ajax_req = new XMLHttpRequest();
			__comm_ajax_req_created = true;
    	}
		catch(e) {
			__comm_ajax_req_created = false;
		}

		if(!__comm_ajax_req_created)
		{
			// * IE/Windows ActiveX version
			var nCurrentProgID;
			var aProgIDs = new Array(
									 "MSXML2.XMLHTTP.5.0",
									 "MSXML2.XMLHTTP.4.0",
									 "MSXML2.XMLHTTP.3.0",
									 "MSXML2.XMLHTTP",
									 "Microsoft.XMLHTTP"
									 );
	
			for(nCurrentProgID = 0; nCurrentProgID < aProgIDs.length; nCurrentProgID++)
			{
				try
				{
					__comm_ajax_req = new ActiveXObject(aProgIDs[nCurrentProgID]);
					__comm_ajax_req_created = true;
					break;
				}
				catch(e) {
					__comm_ajax_req_created = false;
				}
			}
		}

		__comm_romms = window.document.getElementById("UserList");
		__comm_ref_interval_id = window.setInterval(CommunicatorRefresh, __comm_refresh_rate);

		// * Initiate events
		if(window.addEventListener)
		{
			window.addEventListener('blur', SetChatBlurred, true);
			window.addEventListener('focus', SetChatFocused, true);
		}
		else if(window.attachEvent)
		{
			window.attachEvent('onblur', SetChatBlurred);
			window.attachEvent('onfocus', SetChatFocused);
		}

		__comm_win_title = window.document.title;

		if(__comm_ajax_req) {
			__comm_loaded = true;
			__comm_id = __comm_id + 1;
		}
	}

	function SetChatBlurred()
	{
		__comm_win_blurred = true;
		if(!__comm_win_blurred) {
				window.setTimeout(function() { SetChatBlurred(); }, __comm_retry_interval);
		}
	}

	function SetChatFocused()
	{
		window.clearTimeout(__comm_win_title_interval_id);
		window.document.title = __comm_win_title;
		var __comm_li = new getObj('comm_li');
		__comm_li.style.backgroundImage = 'url(gfx/komunikator.gif)';		
		__comm_win_blurred = false;
		__comm_still_unfocused = false;

		if(__comm_win_blurred || __comm_still_unfocused || (window.document.title != __comm_win_title)) {
				window.setTimeout(function() { SetChatFocused(); }, __comm_retry_interval);
		}
	}

	function CommunicatorSendMessage(sClearRoomCommand)
	{
		var __comm_send_failed = false;
		var oMessageEntry = window.document.getElementById("MessageEntry");
		oMessageEntry.disabled = true;
		var sMessage = oMessageEntry.value;
		// these chars are problematic
		sMessage = sMessage.replace('+', '&#43;');
		sMessage = sMessage.replace('%', '&#37;');

		var nIM = (window.document.getElementById("comm_type_im").checked) ? 0x01 : 0x00;
		var nSMS = (window.document.getElementById("comm_type_sms").checked) ? 0x02 : 0x00;
		var nEmail = (window.document.getElementById("comm_type_mail").checked) ? 0x04 : 0x00;
		var sEmail = encodeURIComponent(__comm_romms.options[__comm_romms.selectedIndex].getAttribute("email"));
		var sSMS = __comm_romms.options[__comm_romms.selectedIndex].getAttribute("sms");
		sEmail = sEmail + ';' + sSMS;
		var nType = nIM + nSMS + nEmail;

		__comm_room_id = __comm_romms.options[__comm_romms.selectedIndex].getAttribute("value");

		if((sMessage != "" && sMessage != null && typeof sMessage != 'undefined') || (sClearRoomCommand != ""))
		{
			sMessage = encodeURIComponent(sMessage);

			try
			{
				__comm_ajax_req.abort();
				__comm_ajax_req.open("POST", "2_communicator/log_parser.php", true, null, null);
				__comm_ajax_req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
				//__comm_ajax_req.setRequestHeader("content-length", sMessage.length);
				__comm_ajax_req.onreadystatechange = _CommunicatorGetMessages;
				//_handleReadyState(__comm_ajax_req);

				__comm_ajax_req.send("msg=" + sMessage + "&type=" + nType + "&room=" + __comm_room_id + "&email=" + sEmail + sClearRoomCommand);
			}
			catch(e) {
				__comm_send_failed = true;
			}
			finally 
			{
				if(__comm_send_failed) {
					window.setTimeout(function() { this.CommunicatorSendMessage(sClearRoomCommand); }, this.__comm_retry_interval);
				}
			}
			// * Clear contents
			oMessageEntry.value = "";
			oMessageEntry.disabled = false;
		}
	}

	function CommunicatorRefresh()
	{
		if(typeof __comm_ajax_req == 'undefined' || __comm_ajax_req == null)
		{
			__comm_loaded = false;
			CommunicatorLoad(__comm_reply_mail);
			return false;
		}
		var __comm_refresh_failed = false;
		__comm_room_id = __comm_romms.options[__comm_romms.selectedIndex].getAttribute("value");
		CommunicatorGetCanClear();

		try
		{
			__comm_ajax_req.abort();
			__comm_ajax_req.open('POST', '2_communicator/log_parser.php', true, null, null);
			__comm_ajax_req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
			__comm_ajax_req.onreadystatechange = _CommunicatorGetMessages;
			//_handleReadyState(__comm_ajax_req);
			__comm_ajax_req.send('room=' + __comm_room_id);
		}
		catch(e){
			__comm_refresh_failed = true;
		}
		finally
		{
			// try again
			if(__comm_refresh_failed) {
				window.setTimeout(function() { this.CommunicatorRefresh(); }, this.__comm_retry_interval);
			}
		}
	}

	function CommunicatorSendOnEnter(eEvent)
	{
		var oMessageEntry = window.document.getElementById('MessageEntry');

		if(eEvent && eEvent.which == 13 && eEvent.which == 16) {
			var sMessage = oMessageEntry.value + '\r\n';
		}
		else if(eEvent && eEvent.keyCode == 13 && eEvent.shiftKey) {
			var sMessage = oMessageEntry.value + '\r\n';
		}
		else if(eEvent && eEvent.which == 13)
		{
			CommunicatorSendMessage("");
			return false;
		}
		else if(eEvent && eEvent.keyCode == 13)
		{
			CommunicatorSendMessage("");
			return false;
		}
	}

	function _handleReadyState(o)
	{
		var oConn = this;
		try
		{
			__comm_poll[__comm_id] = window.setInterval(
				function() {
					if(o && o.readyState == 4) {
						window.clearInterval(__comm_poll[__comm_id]);
						__comm_poll.splice(__comm_id);
						oConn._CommunicatorGetMessages();
					}
				}
			, this.__comm_retry_interval);
			return;
		}
		catch(e) {
			window.clearInterval(__comm_poll[__comm_id]);
			__comm_poll.splice(__comm_id);
		}
	}

	function _CommunicatorGetMessages()
	{
		var __comm_get_msg_failed = false;
		var __httpStatus;

		try {
			__httpStatus = __comm_ajax_req.status;
		}
		catch(e) {
			// 13030 is the custom code to indicate the condition -- in Mozilla/FF --
			// when the o object's status and statusText properties are
			// unavailable, and a query attempt throws an exception.
			__httpStatus = 13030;
		}

		try
		{
			// * Only if __comm_ajax_req shows "complete"
			if(__comm_ajax_req && __comm_ajax_req.readyState == 4)
			{
				// * Only if "OK"
				if(__httpStatus >= 200 && __httpStatus < 300)
				{
					// * Processing statements...
					var oMessageDisplay = window.document.getElementById('MessageDisplay');
					var __comm_msg_log = __comm_ajax_req.responseText;
					__comm_msg_log = decodeURIComponent(__comm_msg_log);

					__comm_new_msg_indicator = /nova_poruka/gi;
					__comm_new_msg_srch_results = __comm_msg_log.search(__comm_new_msg_indicator);

					oMessageDisplay.innerHTML = __comm_msg_log;

					window.clearInterval(__comm_win_title_interval_id);

					var __comm_main_div = new getObj('chat_div');

					if((__comm_new_msg_srch_results > -1 && (__comm_win_blurred || __comm_main_div.style.display == 'none')) || __comm_still_unfocused)
					{
						if(__comm_msg_log != '')
						{
							if(!__comm_still_unfocused) {
								__comm_still_unfocused = true;
							}
							var __comm_li = new getObj('comm_li');
							__comm_li.style.backgroundImage = 'url(gfx/komunikator.gif)';
							window.document.title = 'nova poruka';
							__comm_win_title_interval_id = window.setTimeout(function () { window.document.title = 'NOVA PORUKA'; var __comm_li = new getObj('comm_li'); __comm_li.style.backgroundImage = 'url(gfx/komunikatorBlink.gif)'; }, 900 );
						}
					}
				}	// "OK" end
				else {
					switch(__httpStatus)
					{
						// The following case labels are wininet.dll error codes that may be encountered.
						// Server timeout
						case 12002:
						// 12029 to 12031 correspond to dropped connections.
						case 12029:
						case 12030:
						case 12031:
						// Connection closed by server.
						case 12152:
						// See above comments for variable status.
						case 13030:
							__comm_get_msg_failed = true;
						break;
						default:
							__comm_get_msg_failed = true;
					}	
				}
			}
		}
		catch(e) {
			__comm_get_msg_failed = true;
		}
		finally
		{
			if(__comm_get_msg_failed) {
				window.setTimeout(function() { this._CommunicatorGetMessages(); }, this.__comm_retry_interval);
			}
		}
	}

	function CommunicatorGetCanClear()
	{
		__comm_room_id = __comm_romms.options[__comm_romms.selectedIndex].getAttribute("value");

		if(__comm_room_id == 'main')
		{
			window.document.getElementById("ClearLog").setAttribute("disabled", "disabled");
			window.document.getElementById("comm_type_sms").setAttribute("disabled", "disabled");
			window.document.getElementById("comm_type_mail").setAttribute("disabled", "disabled");
		}
		else
		{
			window.document.getElementById("ClearLog").removeAttribute("disabled");
			window.document.getElementById("comm_type_sms").removeAttribute("disabled");
			window.document.getElementById("comm_type_mail").removeAttribute("disabled");
		}
	}

	function CommunicatorClearRoom() {
		CommunicatorSendMessage('&comm=clear-room');
	}
	
	// * Initiate events
	if(window.attachEvent) {
		window.attachEvent('onload', CommunicatorLoad);
	}
	else if(window.addEventListener) {
		window.addEventListener('load', CommunicatorLoad, true);
	}