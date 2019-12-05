function $(id)
{
	id = String(id);
	return document.getElementById(id);
}

function modal(el, w, h)
{
	var href;

	if(typeof el == 'string') {
		href = el;
	}
	else if(typeof el == 'object') {
		href = el.href;
	}

	// forms don't work in IE8
	/*if (window.showModalDialog) {
		window.showModalDialog(href, '_blank', 'dialogWidth:'+w+'px;dialogHeight:'+h+'px;center:yes');
	}
	else {*/
		window.open(href, '_blank', 'height='+h+',width='+w+',toolbar=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,modal=yes,top='+((screen.height/2)-(h/2))+',left=' + ((screen.width/2)-(w/2)));
	//}
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

function redirect(url)
{
	window.location = url;
}

function ag(textarea)
{
	var lines = textarea.value.split("\n");
	textarea.rows = lines.length + 1;
}

sfHover = function() {
	var sfEls = document.getElementById("navbar").getElementsByTagName("li");
	for (var i=0; i<sfEls.length; i++) {
		sfEls[i].onmouseover=function() {
			this.className+=" hover";
		}
		sfEls[i].onmouseout=function() {
			this.className=this.className.replace(new RegExp(" hover\\b"), "");
		}
	}
}
if (window.attachEvent) window.attachEvent("onload", sfHover);
