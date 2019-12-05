// JavaScript Document

	function OtvoriKalendar()
	{
		var nTop = Math.floor(screen.height/2-200/2);
		var nLeft = Math.floor(screen.width/2-200/2);

		if(window.open) {
			window.open("_modals/calendar.html", "kalendar_modal", "resizable=yes, height=200, width=200, top=" + nTop + ", left=" + nLeft);
		}
	}

	function DisableAccess()
	{
		if(window.alert) {
			window.alert("Nemate dovoljne privilegije za pristup.");
		}
	}

	function ShowHideComm()
	{
		var comm_div = new getObj('chat_div');
		var next = 'none';
		if(comm_div.style.display == next) {
			next = 'block';
		}
		comm_div.style.display = next;

		if(next == 'block') {
			var __msg_entry = new getObj('MessageEntry');
			__msg_entry.obj.focus();	
		}
	}