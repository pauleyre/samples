function sh(id)
{
	var o = document.getElementById(id);
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