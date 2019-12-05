function fav_ad(id, action)
{
	var handleSuccess = function(o) {
		if(o.responseText !== undefined) {
			try {
				$('spremljeniOglasiContainer').innerHTML = o.responseText;
				//yfade('spremljeniOglasiContainer');

				// yellow fade for all
				var ads = YAHOO.util.Dom.getElementsByClassName('spremljeniOglas');
				var n = 0;

				for(n = 0; n < ads.length; n++) {
					yfade(ads[n]);
				}
			} catch(e) {}

			// send another request to get the updated cookie value
			if(action == 'remove') {
				fav_ad(null, null);
			}
		}
	}

	var callback =
	{
		success:handleSuccess,
		timeout: 15000
	};

	var data = new Array();
	data[0] = action + '=1';
	data[1] = 'ad=' + id;
	data[2] = 'ln=' + __orbicon_ln;

	data = data.join('&');

	YAHOO.util.Connect.asyncRequest('POST', orbx_site_url + '/orbicon/modules/estate/xhr.favads.php', callback, data);
}

function setIcon(){

	var ourIcon = new GIcon(G_DEFAULT_ICON);
	ourIcon.image = "http://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png";
	ourIcon.iconSize = new GSize(32, 32);
	markerOptions = { icon:ourIcon };
}


function argItems (theArgName) {
	sArgs = location.search.slice(1).split('&');
		r = '';
		for (var i = 0; i < sArgs.length; i++) {
			if (sArgs[i].slice(0,sArgs[i].indexOf('=')) == theArgName) {
				r = sArgs[i].slice(sArgs[i].indexOf('=')+1);
					break;
			}
		}
	return (r.length > 0 ? unescape(r).split(',') : '')
}

function getCoordForAddress(response) {

	if (!response || response.Status.code != 200) {
				alert("Sorry, we were unable to geocode that address!");
	} else {
		place = response.Placemark[0];
		setLat = place.Point.coordinates[1];
		setLon = place.Point.coordinates[0];
		setLat = setLat.toFixed(6);
		setLon = setLon.toFixed(6);
		$("frmLat").value = setLat;
		$("frmLon").value = setLon;
	}
	placeMarker(setLat, setLon)
}


function placeMarker(setLat, setLon) {

	var message = "geotagged geo:lat=" + setLat + " geo:lon=" + setLon + " ";

	var messageRoboGEO = setLat + ";" + setLon + "";

	try {
		$("frmLat").value = setLat;
		$("frmLon").value = setLon;
	}
	catch(e) {}

	var zoomlvl;

	if(is_rs()) {
		zoomlvl = ((setLat != 44.016521) && (setLon != 21.005859)) ? 6 : 11;
	}
	else {
		zoomlvl = ((setLat != 45.796255) && (setLon != 15.954895)) ? 6 : 11;
	}

	var map = new GMap($("map"));
	map.addControl(new GSmallMapControl()); // added
	map.addControl(new GMapTypeControl()); // added
	map.centerAndZoom(new GPoint(setLon, setLat), zoomlvl);

	var point = new GPoint(setLon, setLat);
	marker = new GMarker(point, markerOptions);
	map.addOverlay(marker);

	GEvent.addListener(map, 'click', function(overlay, point) {
		if (overlay) {
			map.removeOverlay(overlay);
			$("frmLat").value = '';
			$("frmLon").value = '';
		} else if (point) {
			//map.recenterOrPanToLatLng(point);
			map.removeOverlay(marker);

			marker = new GMarker(point, markerOptions);
			map.addOverlay(marker);
			var matchll = /\(([-.\d]*), ([-.\d]*)/.exec( point );
			if ( matchll ) {
				var lat = parseFloat( matchll[1] );
				var lon = parseFloat( matchll[2] );
				lat = lat.toFixed(6);
				lon = lon.toFixed(6);
				var message = "geotagged geo:lat=" + lat + " geo:lon=" + lon + " ";
				var messageRoboGEO = lat + ";" + lon + "";
			} else {
				var message = "<b>Error extracting info from</b>:" + point + "";
				var messagRoboGEO = message;
			}

			//marker.openInfoWindowHtml(message);

			$("frmLat").value = lat;
			$("frmLon").value = lon;

		}
	});
}

function findAddress() {
	myAddress = $("address").value;

}

function verify_mailform()
{
	if(empty($('ko_ime').value)) {
		$('ko_ime').focus();
		window.alert('Upišite ime');
		return false;
	}

	if(empty($('ko_email').value)) {
		$('ko_email').focus();
		window.alert('Upišite e-mail');
		return false;
	}


	if(empty($('ko_tel').value)) {
		$('ko_tel').focus();
		window.alert('Upišite telefon');
		return false;
	}

	if(empty($('ko_poruka').value)) {
		$('ko_poruka').focus();
		window.alert('Upišite poruku');
		return false;
	}


	return true;
}

function switch_estate_types(input)
{
	var house_elements = YAHOO.util.Dom.getElementsByClassName('house');
	var apartment_elements = YAHOO.util.Dom.getElementsByClassName('apartment');
	var land_elements = YAHOO.util.Dom.getElementsByClassName('land');
	var tourism_elements = YAHOO.util.Dom.getElementsByClassName('tourism');
	var business_elements = YAHOO.util.Dom.getElementsByClassName('business');

	var house = $('vrsta_kuce');
	var business = $('vrsta_prostora');
	var apartment = $('vrsta_stana');
	var land = $('vrsta_zemljista');
	var tourism = $('tourism_equipment');

	house.disabled = true;
	business.disabled = true;
	apartment.disabled = true;
	land.disabled = true;
	tourism.style.display = 'none';

	_switch_estate_helper(house_elements, 'none');
	_switch_estate_helper(apartment_elements, 'none');
	_switch_estate_helper(land_elements, 'none');
	_switch_estate_helper(tourism_elements, 'none');
	_switch_estate_helper(business_elements, 'none');

	if(input == 1) {
		apartment.disabled = false;
		_switch_estate_helper(apartment_elements, 'table-row');
	}
	else if(input == 2) {
		house.disabled = false;
		_switch_estate_helper(house_elements, 'table-row');
	}
	else if(input == 3) {
		land.disabled = false;
		_switch_estate_helper(land_elements, 'table-row');
	}
	else if(input == 4) {
		_switch_estate_helper(tourism_elements, 'table-row');
		tourism.style.display = 'block';
	}
	else if(input == 5) {
		_switch_estate_helper(business_elements, 'table-row');
		business.disabled = false;
	}
}

function _switch_estate_helper(elements, display_state)
{
	try {
		var n = 0;
		if(!empty(elements)) {
			for(n = 0; n < elements.length; n++) {
				elements[n].style.display = display_state;
			}
		}
	}
	// explorer doesn't recognize table-row so it fails. use block for it
	catch (e) {
		for(n = 0; n < elements.length; n++) {
			elements[n].style.display = 'block';
		}
	}
}

function switch_towns(county, country_code, container, id, name)
{
	if(/*(county == 1) || */empty(county)) {
		return;
	}

	// stop people from using it until we updated
	$(id).disabled = true;

	var handleSuccess = function(o) {
		if(o.responseText !== undefined) {
			var msg = (is_rs()) ? 'Sva mesta' : 'Sva mjesta';
			$(container).innerHTML = '<select id="' + id + '" name="' + name + '" class="select big"><option value="">&mdash; '+msg+' &mdash;</option>' + o.responseText + '</select>';
		}
	}

	var callback =
	{
		success:handleSuccess,
		timeout: 15000
	};

	try {
		if(county == 2 || county == 123 || county == 124) {
			$('zg').disabled = false;
		}
		else {
			$('zg').disabled = true;
		}
	} catch(e) {}

	var data = new Array();
	data[0] = 'county=' + county;
	if(is_rs()) {
		country_code = 'RS';
	}
	data[1] = 'country_code=' + country_code;

	data = data.join('&');

	YAHOO.util.Connect.asyncRequest('POST', orbx_site_url + '/orbicon/modules/peoplering/xhr.pring_towns.php', callback, data);
}

function verify_adform()
{
	var type = $("kategorija").options[$("kategorija").selectedIndex].value;

	if(empty(type)) {
		$('kategorija').focus();
		window.alert('Odaberite tip');
		return false;
	}

	if(empty($("ad_menu").options[$("ad_menu").selectedIndex].value)) {
		$('ad_menu').focus();
		window.alert('Odaberite vrstu nekretnine');
		return false;
	}

	if(empty($("ponuda").options[$("ponuda").selectedIndex].value)) {
		$('ponuda').focus();
		window.alert('Odaberite vrstu oglasa');
		return false;
	}

	if(empty($('naslov').value)) {
		$('naslov').focus();
		window.alert('Napišite naslov oglasa');
		return false;
	}

	if(empty($('povrsina').value)) {
		$('povrsina').focus();
		window.alert('Napišite površinu');
		return false;
	}

	if(empty($("grad").options[$("grad").selectedIndex].value)) {
		$('grad').focus();
		window.alert('Odaberite grad');
		return false;
	}

	if(type == 1) {
		if(empty($("vrsta_stana").options[$("vrsta_stana").selectedIndex].value)) {
			$('vrsta_stana').focus();
			window.alert('Odaberite vrstu stana');
			return false;
		}
	}
	else if(type == 2) {
		if(empty($("vrsta_kuce").options[$("vrsta_kuce").selectedIndex].value)) {
			$('vrsta_kuce').focus();
			window.alert('Odaberite vrstu kuće');
			return false;
		}
	}
	else if(type == 3) {
		if(empty($("vrsta_zemljista").options[$("vrsta_zemljista").selectedIndex].value)) {
			$('vrsta_zemljista').focus();
			window.alert('Odaberite vrstu zemljišta');
			return false;
		}
	}
	else if(type == 5) {
		if(empty($("vrsta_prostora").options[$("vrsta_prostora").selectedIndex].value)) {
			$('vrsta_prostora').focus();
			window.alert('Odaberite vrstu poslovnog prostora');
			return false;
		}
	}

	if(empty($('tagovi').value)) {
		$('tagovi').focus();
		window.alert('Upišite barem jedan tag');
		return false;
	}

	return true;
}

function verify_wesearch()
{
	if(empty($("ko_kategorija").options[$("ko_kategorija").selectedIndex].value)) {
		$('ko_kategorija').focus();
		window.alert('Odaberite tip');
		return false;
	}

	if(empty($("ko_ponuda").options[$("ko_ponuda").selectedIndex].value)) {
		$('ko_ponuda').focus();
		window.alert('Odaberite vrstu oglasa');
		return false;
	}

	if(empty($("ko_regija").options[$("ko_regija").selectedIndex].value)) {
		$('ko_regija').focus();
		window.alert('Odaberite regiju');
		return false;
	}
	if(empty($('ko_ime').value)) {
		$('ko_ime').focus();
		window.alert('Napišite vaše ime');
		return false;
	}

	if(empty($('ko_email').value)) {
		$('ko_email').focus();
		window.alert('Napišite vaš e-mail');
		return false;
	}

	return true;
}

function tags_autocomplete()
{
	// create autocomplete container if we haven't got one already
	if(empty($("tags_container"))) {
		var _body = window.document.getElementsByTagName("BODY").item(0);
		var _a = window.document.createElement("DIV");
		_a.id = 'tags_container';
		_body.appendChild(_a);
	}

	var url = orbx_site_url + '/orbicon/modules/estate/xhr.tags.php';

	// Instantiate one XHR DataSource and define schema as an array:
	//     ["Record Delimiter",
	//     "Field Delimiter"]
	var oACDS = new YAHOO.widget.DS_XHR(url, ["\n", "\t"]);
	oACDS.responseType = YAHOO.widget.DS_XHR.TYPE_FLAT;
	oACDS.maxCacheEntries = 20;
	oACDS.allowBrowserAutocomplete = false;

	// Instantiate AutoComplete
	var oAutoComp = new YAHOO.widget.AutoComplete('tagovi','tags_container', oACDS);
	oAutoComp.queryDelay = 0;
	oAutoComp.useIFrame = true;
	oAutoComp.animVert = false;
	oAutoComp.delimChar = ',';

	oAutoComp.doBeforeExpandContainer = function(oTextbox, oContainer, sQuery, aResults) {
		var pos = YAHOO.util.Dom.getXY(oTextbox);
		pos[1] += YAHOO.util.Dom.get(oTextbox).offsetHeight;
		YAHOO.util.Dom.setXY(oContainer,pos);

		// set width
		oContainer.style.width = __get_element_width('tagovi') + 'px';

		return true;
	};
}

function flag_ad(ad_id, user_rid, type)
{
	var handleSuccess = function(o) {
		if(o.responseText !== undefined) {
			alert(o.responseText);
		}
	}

	var callback =
	{
		success:handleSuccess,
		timeout: 15000
	};

	var data = new Array();
	data[0] = 'ad_id=' + ad_id;
	data[1] = 'user_rid=' + user_rid;
	data[2] = 'type=' + type;

	data = data.join('&');

	YAHOO.util.Connect.asyncRequest('POST', orbx_site_url + '/orbicon/modules/estate/xhr.flagads.php', callback, data);
}

function is_rs()
{
	return (__orbicon_base_url == 'www.foto-nekretnine.rs' || __orbicon_base_url == 'foto-nekretnine.rs');
}