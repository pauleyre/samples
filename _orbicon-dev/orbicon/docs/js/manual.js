

function sh(id)
{
	var _o = document.getElementById(id);
	var _value = 'none';
	var _speak = 'none';
	var _current;
	
	if( window.getComputedStyle ) {
		_current = window.getComputedStyle(_o, null).display;
	}
	else if( _o.currentStyle ) {
		_current = _o.currentStyle.display;
	}
	
	if(_current=='none') {
		_value='block';
		_speak='normal';
	}
	_o.style.display=_value;
	_o.style.speak=_speak;
}
