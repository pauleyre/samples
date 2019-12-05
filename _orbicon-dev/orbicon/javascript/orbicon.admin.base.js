var _orbx_current_submenu = null;
var _old_main_tab = null;

function _orbx_show_submenu(menu_el, submenu_id)
{
	if(_orbx_current_submenu != null) {
		$(_orbx_current_submenu).style.display = 'none';
	}

	if(_old_main_tab != null) {
		YAHOO.util.Dom.removeClass(_old_main_tab, 'current');
	}

	_orbx_current_submenu = submenu_id;
	setLyr(menu_el, submenu_id);
	var submenu = $(submenu_id);
	submenu.style.display = 'block';
	submenu.style.zIndex = '99999';

	YAHOO.util.Dom.addClass(menu_el, 'current');
	_old_main_tab = menu_el;
}

function _orbx_hide_submenu()
{
	if(_orbx_current_submenu != null) {
		 $(_orbx_current_submenu).style.display = 'none';
	}
	if(_old_main_tab != null) {
		YAHOO.util.Dom.removeClass(_old_main_tab, 'current');
	}
}

function verify_title(id)
{
	var el = $(id);

	// error checking
	if(empty(el.value)) {
		window.alert('Please provide a title');
		el.focus();
		return false;
	}
	return true;
}

	function save_desktop()
	{
		var callback =
		{
			timeout: 15000
		};

		var url = orbx_site_url + '/orbicon/controler/admin.desktop.update.php';
		var icons = $('orbx_desktop').getElementsByTagName('DIV');
		var n = 0;
		var new_state = new Array();

		for(n = 0; n < icons.length; n++) {
			new_state[n] = icons[n].id + ':' + icons[n].style.top + ':' + icons[n].style.left + ':' + __desktop_owner;
		}

		new_state = new_state.join('#');

		YAHOO.util.Connect.asyncRequest('POST', url, callback, 'data=' + new_state);
	}

	function orbx_icon_handler(icon_id, icon_owner, action)
	{
		sh_ind();

		var handleSuccess = function(o)
		{
			if(o.responseText !== undefined) {
				$('orbx_icon_container').innerHTML = o.responseText;
			}
			sh_ind();
		}

		var callback =
		{
			success:handleSuccess,
			timeout: 15000
		};

		var data = new Array();
		data[0] = 'icon_id=' + icon_id;
		data[1] = 'icon_owner=' + icon_owner;
		data[2] = 'action=' + action;

		data = data.join('&');

		var url = orbx_site_url + '/orbicon/controler/admin.desktop.icon.manager.php';

		YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
	}

	// this is a hack function for separate forms
	function orbx_carrier(source, target_object)
	{
		target_object.value = source.value;
	}

	function orbx_load_desktop_rss(rss)
	{
		$('my_rss_content').style.display = 'none';
		sh('my_rss_loader');

		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				$('my_rss_content').innerHTML = o.responseText;
				$('my_rss_content').style.display = 'block';
			}
			sh('my_rss_loader');
		}

		var callback =
		{
			success:handleSuccess,
			timeout: 15000
		};

		var url = orbx_site_url + '/orbicon/controler/admin.desktop.rss.php';

		YAHOO.util.Connect.asyncRequest('POST', url, callback, 'rss=' + rss);
	}

	// Adding listener for closing submenu
	YAHOO.util.Event.addListener(document, 'click', _orbx_hide_submenu);