YAHOO.util.Event.addListener(window, 'load', orbx_add_login);

function sh(id)
{
	var o = $(id);
	var value = 'none';
	var speak = 'none';
	var current;

	if(window.getComputedStyle) {
		current = window.getComputedStyle(o, null).display;
	}
	else if(o.currentStyle) {
		current = o.currentStyle.display;
	}
	else {
		current = o.style.display;
	}

	if(current == 'none') {
		value = 'block';
		speak = 'normal';
	}

	o.style.display = value;
	o.style.speak = speak;
}

function h(id)
{
	var o = $(id);
	o.style.display = 'none';
	o.style.speak = 'none';
}

function s(id)
{
	var o = $(id);
	o.style.display = 'block';
	o.style.speak = 'normal';
}

function sh_ind()
{
	var indicator = $('update_indicator');
	var _class = 'h';
	var remove = 's';

	if(YAHOO.util.Dom.hasClass(indicator, 'h')) {
		_class = 's';
		remove = 'h';
	}

	YAHOO.util.Dom.removeClass(indicator, remove);
	YAHOO.util.Dom.addClass(indicator, _class);
}

function orbx_bookmark(title, url)
{
	var added = false;

	try {
		if(window.external) {
			window.external.AddFavorite(url, title);
			added = true;
		}
	}
	catch(e) {}
	finally {
		if(added) {
			return true;
		}

		if(window.sidebar) {
			window.sidebar.addPanel(title, url, '');
		}
		// http://www.opera.com/support/search/view/570/
		else if(navigator.userAgent.indexOf('Opera') != -1) {
			var opera_link = document.createElement('a');
			opera_link.setAttribute('rel', 'sidebar');
			opera_link.setAttribute('href', url);
			opera_link.setAttribute('title', title);
			opera_link.click();
		}
	}
}

// * Get top offset
function GetOffsetTop(o)
{
	var nOffsetTop = o.offsetTop;
	var oOffsetParent = o.offsetParent;

	while(oOffsetParent) {
		nOffsetTop += oOffsetParent.offsetTop;
		oOffsetParent = oOffsetParent.offsetParent;
	}

	return nOffsetTop;
}

// * Get left offset
function GetOffsetLeft(o)
{
	var nOffsetLeft = o.offsetLeft;
	var oOffsetParent = o.offsetParent;

	while(oOffsetParent) {
		nOffsetLeft += oOffsetParent.offsetLeft;
		oOffsetParent = oOffsetParent.offsetParent;
	}

	return nOffsetLeft;
}

function $(id)
{
	id = String(id);
	//return new YAHOO.util.Dom.get(id);
	return new getObj(id).obj;
}

function getObj(name)
{
	if(document.getElementById) {
		//if(document.getElementById(name)) {
			try {
				this.obj = document.getElementById(name);
				this.style = document.getElementById(name).style;
			}
			catch(e) {
				//throw new Error('getObj() could not find "' + name + '"');
			}
		//}
	}
	else if(document.all) {
		this.obj = document.all[name];
		this.style = document.all[name].style;
	}
	else if(document.layers) {
		this.obj = getObjNN4(document, name);
		this.style = this.obj;
	}
}

function getObjNN4(obj,name)
{
	var x = obj.layers;
	var thereturn;
	for(var i=0; i < x.length; i++) {
		if(x[i].id == name) {
			thereturn = x[i];
		}
		else if(x[i].layers.length) {
			var tmp = getObjNN4(x[i],name);
		}
		if(tmp) {
			thereturn = tmp;
		}
	}
	return thereturn;
}

function orbx_add_login()
{
	var body = window.document.getElementsByTagName("BODY").item(0);
	var a = window.document.createElement("A");
	a.href = orbx_site_url + '/?' + __orbicon_ln + '=orbicon/authorize';
	a.rel = 'nofollow';
	a.accessKey = 'X';
	a.id = 'orbx_login_a';
	body.appendChild(a);
}

/*function modal_open(src, name, width, height)
{
	if(window.showModalDialog) {
		window.showModalDialog(src,name, 'dialogWidth:'+width+'px;dialogHeight:'+height+'px');
	}
	else if(window.open) {
		window.open(src,name,'height='+width+', width='+height+', toolbar=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, modal=yes');
	}
}*/

function __unload()
{
	var handleSuccess = function(o) {
		sh_ind();
		redirect(orbx_site_url);
	}

	var callback =
	{
		success:handleSuccess,
		timeout: 15000
	};

	var msg = (__orbicon_ln == 'hr') ? 'Potvrdite odjavu?' : 'End authorized session and exit?';

	if(window.confirm(msg)) {
		sh_ind();
		YAHOO.util.Connect.asyncRequest('POST', orbx_site_url + '/?' + __orbicon_ln +'=exit', callback, 'null=null');
	}
}

function findPosX(obj)
{
	var curleft = 0;
	if(obj.offsetParent) {
		while(obj.offsetParent) {
			curleft += obj.offsetLeft
			obj = obj.offsetParent;
		}
	}
	else if(obj.x) {
		curleft += obj.x;
	}
	return curleft;
}

function findPosY(obj)
{
	var curtop = 0;

	if(obj.offsetParent) {
		while(obj.offsetParent) {
			curtop += obj.offsetTop
			obj = obj.offsetParent;
		}
	}
	else if(obj.y) {
		curtop += obj.y;
	}
	return curtop;
}

function setLyr(obj, lyr)
{
	var newX = findPosX(obj);
	var newY = findPosY(obj);
	var padding = obj.clientHeight;

	var x = $(lyr);
	x.style.top = parseInt(newY + padding) + 'px';
	x.style.left = parseInt(newX) + 'px';
}

/*function set_text_content(el, text)
{
	if(typeof el != 'object' || el == null) {
		return false;
	}

	if(el.textContent) {
		el.textContent = text;
		return true;
	}
	else if(el.innerText) {
		el.innerText = text;
		return true;
	}
	else if(el.text) {
		el.text = text;
		return true;
	}
	else if(el.value) {
		el.value = text;
		return true;
	}

	return false;
}*/

/*function get_text_content(o)
{
	if(typeof o != 'object' || o == null) {
		return null;
	}

	if(o.textContent) {
		return o.textContent;
	}
	else if(o.innerText) {
		return o.innerText;
	}
	else if(o.text) {
		return o.text;
	}
	else if(o.value) {
		return o.value;
	}

	return null;
}*/

// return the value of the radio button that is checked
// return an empty string if none are checked, or
// there are no radio buttons
function getCheckedValue(radioObj)
{
	if(!radioObj) {
		return "";
	}

	var radioLength = radioObj.length;

	if(radioLength == undefined) {
		if(radioObj.checked) {
			return radioObj.value;
		}
		else {
			return "";
		}
	}

	for(var i = 0; i < radioLength; i++) {
		if(radioObj[i].checked) {
			return radioObj[i].value;
		}
	}
	return "";
}

function get_permalink_exists(input, id)
{
	var handleSuccess = function(o) {
		var disabled = false;

		if(o.responseText > 0) {

			var msg = (__orbicon_ln == 'hr') ? 'Promjenite naziv - novost, rubrika, modul ili forma naziva \n"' + input + '"\n postoji ili je naziv "' + input + '" sistemski rezerviran' : 'Change the title - news, column, module or a form entitled \n"' + input + '"\n already exists or the title "' + input + '" is system-reserved.';

			window.alert(msg);
			disabled = true;
		}

		var n = 0;
		var inputs = document.getElementsByTagName('INPUT');
		for(n = 0; n < inputs.length; n++) {
			if(inputs[n].getAttribute('type') == 'submit') {
				inputs[n].disabled = disabled;
			}
		}
	}

	var callback =
	{
		success:handleSuccess
	};

	YAHOO.util.Connect.asyncRequest('POST', orbx_site_url + '/orbicon/controler/check_permalink.php', callback, 'input=' + input + '&id=' + id);
}

/*function preload_subcolumns(permalink)
{
	try {
		var container = $('h_menu_subcontainer');
		var subcolumns = $('sub_col_' + permalink);
		var content = subcolumns.value;
		content = (empty(content)) ? '&nbsp;' : content;
		container.innerHTML = content;
	}
	catch(e) {}
}*/

function basename(id)
{
	var filename = $(id);

	if(!empty(filename.value)) {
		var x = filename.value;
		var f = "";
		if(x.indexOf('/') > -1) {
			f = x.substring(x.lastIndexOf('/')+1);
		}
		else {
			f = x.substring(x.lastIndexOf('\\')+1);
		}

		return f;
	}
}

function __bnclick(permalink) {
	YAHOO.util.Connect.asyncRequest('POST', orbx_site_url + '/orbicon/modules/banners/banner.click.php', null, 'banner=' + permalink);
}

function __get_element_height(id)
{
	var element = $(id);

	if(empty(element)) {
		return null;
	}

	if(document.layers) {
		return element.clip.height;
	}
	else {
		if(element.style.pixelHeight) {
			return element.style.pixelHeight;
		}
		else {
			return element.offsetHeight;
		}
	}
}

function __get_element_width(id)
{
	var element = $(id);

	if(empty(element)) {
		return null;
	}

	if(document.layers) {
		return element.clip.width;
	}
	else {
		if(element.style.pixelWidth) {
			return element.style.pixelWidth;
		}
		else {
			return element.offsetWidth;
		}
	}
}

function empty(value)
{
    if(
    (value == 0) ||
    (value == 0.0) ||
    (value == '0') ||
    (value == '0.0') ||
    (value == '') ||
    (typeof value == undefined) ||
    (value == null) ||
    (value == undefined) ||
    (value == false)
    ) {
        return true;
    }

	return false;
}

/*function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}*/

/*var current_submenu = null;

function show_submenu(id, where)
{
	try {
		if(current_submenu != null) {
			$(current_submenu).style.display = 'none';
		}

		current_submenu = id;

		setLyr(where, id);

		$(id).style.display = 'block';
	}
	catch(e) {}
}

function hide_submenu()
{
	try {
		if(current_submenu != null) {
			 $(current_submenu).style.display = 'none';
		}
	} catch(e) {}
}*/

function redirect(url)
{
	window.location = url;
}

// make this globally accessible
// var hasReqestedVersion = DetectFlashVer(8, 0, 0);

function update_img_views(permalink)
{
	YAHOO.util.Connect.asyncRequest('POST', orbx_site_url + '/orbicon/controler/xhr.img_views.php', null, 'img=' + permalink);
}

/*function admin_hotkey(e)
{
	var shift_pressed;
	var alt_pressed;
	var key_pressed;
	var clean_key_pressed;

	if(e) {
		shift_pressed = (e.modifiers) ? (e.modifiers & Event.SHIFT_MASK) : e.shiftKey;
		alt_pressed = (e.modifiers) ? (e.modifiers & Event.ALT_MASK) : e.altLeft;
		clean_key_pressed = (e.which) ? e.which : e.keyCode;
		key_pressed = String.fromCharCode(clean_key_pressed);
		key_pressed = key_pressed.toUpperCase();
	}

	if(shift_pressed && alt_pressed && (key_pressed == 'X')) {

		if(e.preventDefault) {
			e.preventDefault();
		}
		if(e.stopPropagation) {
			e.stopPropagation();
		}
		else if(e.cancelBubble) {
			e.cancelBubble = true;
		}

		redirect(orbx_site_url + '/?' + __orbicon_ln + '=orbicon/authorize');

		return;
	}
}

YAHOO.util.Event.addListener(window.document, "keypress", admin_hotkey);*/

function ag(textarea)
{
	var lines = textarea.value.split("\n");
	textarea.rows = lines.length + 1;
}