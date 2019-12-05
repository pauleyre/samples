	function RichTextSave()
	{
		if(nViewMode == 2) {
			window.alert('You cannot save while in HTML source view.\nSwitch back to normal view and try again.');
			return false;
		}

		// disable editor
		try {
			oToolbar.execCommand('contentReadOnly', false, true);
		} catch(e) {}

		// fetch RTE content
		var rte_data = RichTextCaptureData('return');

		// assign it to hidden "rte_data" input
		$('content').value = rte_data;

		YAHOO.util.Event.addListener('question_form', 'submit', RichTextSave);
	}

	function RichTextNew()
	{
		RichTextFocus();
		oToolbar.body.innerHTML = "";
	}


	function add2contacts(contact_name)
	{
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				if($('inpulls_popup')) {

					var popup = $('inpulls_popup');
					popup.style.top = Math.floor(screen.height/2 - 150/2) + 'px';
					popup.style.left = Math.floor(screen.width/2 - 250/2) + 'px';
					$('inpulls_popup_txt').innerHTML = o.responseText;
					popup.style.display = 'block';

					$('inpulls_popup_focus').focus();
					$('inpulls_popup_close').focus();
				}
				else {
					window.alert(o.responseText);
				}
			}
		}

		var callback =
		{
			success:handleSuccess,
			timeout: 15000
		};

		var url = orbx_site_url + '/orbicon/modules/peoplering/xhr.add2contacts.php';
		var data = new Array();
		data[0] = 'contact=' + contact_name;

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
	}

function verify_pring_mail()
{
	if(empty($('to').value)) {
		$('to').focus();
		return false;
	}

	return true;
}

function delete_image(image, user)
{
	var handleSuccess = function(o) {
		if(o.responseText !== undefined && !empty(o.responseText)) {
			if($('inpulls_popup')) {

				var popup = $('inpulls_popup');
				popup.style.top = Math.floor(screen.height/2 - 150/2) + 'px';
				popup.style.left = Math.floor(screen.width/2 - 250/2) + 'px';
				$('inpulls_popup_txt').innerHTML = o.responseText;
				popup.style.display = 'block';

				$('inpulls_popup_focus').focus();
				$('inpulls_popup_close').focus();
			}
			else {
				window.alert(o.responseText);
			}
		}
	}

	var callback =
	{
		success:handleSuccess,
		timeout: 15000
	};

	var url = orbx_site_url + '/orbicon/modules/peoplering/xhr.delimg.php';
	var data = new Array();
	data[0] = 'img=' + image;
	data[1] = 'user=' + user;

	data = data.join('&');

	YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
}

function image_text(image, user, text)
{
	var handleSuccess = function(o) {
		if(o.responseText !== undefined && !empty(o.responseText)) {
			if($('inpulls_popup')) {

				var popup = $('inpulls_popup');
				popup.style.top = Math.floor(screen.height/2 - 150/2) + 'px';
				popup.style.left = Math.floor(screen.width/2 - 250/2) + 'px';
				$('inpulls_popup_txt').innerHTML = o.responseText;
				popup.style.display = 'block';

				$('inpulls_popup_focus').focus();
				$('inpulls_popup_close').focus();
			}
			else {
				window.alert(o.responseText);
			}
		}
	}

	var callback =
	{
		success:handleSuccess,
		timeout: 15000
	};

	var url = orbx_site_url + '/orbicon/modules/peoplering/xhr.imgtxt.php';
	var data = new Array();
	data[0] = 'img=' + image;
	data[1] = 'user=' + user;
	data[2] = 'text=' + text;

	data = data.join('&');

	YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
}

var state;

function checkUncheck(state, container_id)
{
	var i;
	var type;
	var gallery = $(container_id);
	var cboxes = gallery.getElementsByTagName('INPUT');

	for(i = 0; i < cboxes.length; i++) {
		type = cboxes[i].type;
		type = type.toLowerCase();
		if(type == 'checkbox') {
			cboxes[i].checked = state;
		}
	}
}

function add_new_attch()
{
	var atch = $('mail_attch');
	var li = window.document.createElement('LI');
	var input = window.document.createElement('INPUT');
	input.type = 'file';
	input.name = 'atch[]';
	li.appendChild(input);
	atch.appendChild(li);
}