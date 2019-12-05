<?php

	function estate_gencol_menu_adm($default)
	{
		global $dbc, $orbicon_x;
		$opcije = '';

		$r_p = $dbc->_db->query(sprintf('	SELECT 		*
											FROM 		'.TABLE_COLUMNS.'
											WHERE 		(menu_name = %s) AND
														(language = %s) AND
														((parent = \'\') OR
														(parent IS NULL))
											ORDER BY 	sort', $dbc->_db->quote('v'), $dbc->_db->quote($orbicon_x->ptr)));
		$a_p = $dbc->_db->fetch_assoc($r_p);

		while($a_p) {

			//$opcije .= '<optgroup label="'.$a_p['title'].'">';

			$selected_x = ($default == $a_p['permalink']) ? ' selected="selected"' : '';
			$opcije .= '<option '.$selected_x.' value="'.$a_p['permalink'].'" style="font-style:oblique">'.$a_p['title'].'</option>';

			$r = $dbc->_db->query(sprintf('	SELECT 		*
											FROM 		'.TABLE_COLUMNS.'
											WHERE 		(menu_name = %s) AND
														(language = %s) AND
														(parent = %s)
											ORDER BY 	sort', $dbc->_db->quote('v'), $dbc->_db->quote($orbicon_x->ptr), $dbc->_db->quote($a_p['permalink'])));
			$a = $dbc->_db->fetch_assoc($r);

			while($a) {

				$selected = ($default == $a['permalink']) ? ' selected="selected"' : '';
				if($a['permalink'] != 'plovila') {
					$opcije .= sprintf('<option value="%s"%s>%s</option>', $a['permalink'], $selected, $a['title']);
				}
				$a = $dbc->_db->fetch_assoc($r);
			}

			//$opcije .= '</optgroup>';
			$a_p = $dbc->_db->fetch_assoc($r_p);
		}

		/*$opcije .= '<optgroup label="Plovila"><option value="motorna-vozila">Motorna vozila</option>
		<option value="jedrilice">Jedrilice</option></optgroup>';*/

		$opcije .= '<option value="plovila">Plovila</option>
		<option value="motorna-vozila">Motorna vozila</option>
		<option value="jedrilice">Jedrilice</option>';

		return $opcije;
	}

	if(isset($_POST['submit'])) {
		if(isset($_GET['id'])) {
			edit_estate($_GET['id']);
			set_estate_embed_video($_GET['id'], $_POST['embed']);

			if(isset($_REQUEST['korisnik_id'])) {
				edit_estate_user($_GET['id'], $_REQUEST['korisnik_id']);
			}
			if(isset($_REQUEST['sponsored_live_to'])) {
				edit_sponsored_time($_GET['id'], strtotime($_REQUEST['sponsored_live_to']));
			}
		}
		else {
			$new_id = new_estate();
			set_estate_embed_video($new_id, $_POST['embed']);

			if(isset($_REQUEST['sponsored_live_to'])) {
				edit_sponsored_time($new_id, strtotime($_REQUEST['sponsored_live_to']));
			}
			redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/estate&page=add&id=' . $new_id);
		}
	}

	// archive ad
	if(isset($_GET['archive'])) {
		set_estate_ad_status(intval($_GET['archive']), ESTATE_AD_ARCHIVED);
	}

	// unarchive ad
	if(isset($_GET['unarchive'])) {
		set_estate_ad_status(intval($_GET['unarchive']), ESTATE_AD_LIVE);
	}

	// sponsor ad
	if(isset($_GET['sponsor'])) {

		if(!get_sponsored_time(intval($_GET['sponsor']))) {
			edit_sponsored_time(intval($_GET['sponsor']), mktime(0, 0, 0, date('m'), date('d') + 14, date('Y')));
		}

		set_estate_ad_sponsor(intval($_GET['sponsor']), ESTATE_AD_SPONSORED);
	}

	// unsponsor ad
	if(isset($_GET['unsponsor'])) {
		set_estate_ad_sponsor(intval($_GET['unsponsor']), ESTATE_AD_NONSPONSORED);
	}

	// delete ad
	if(isset($_GET['del'])) {
		delete_estate_ad($_GET['del']);
		redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/estate');
	}

	if(isset($_POST['remvideo']) && isset($_GET['id']) && !isset($_GET['preview'])) {
		edit_estate_video($_GET['id'], '');
	}

	$q = '	SELECT 		*
			FROM 		' . TABLE_ESTATE . '
			WHERE 		(id = ' . $dbc->_db->quote($_GET['id']) . ')';
	$r = $dbc->_db->query($q);
	$estate = $dbc->_db->fetch_object($r);
	$opcije = estate_gencol_menu_adm($estate->menu);


	include_once DOC_ROOT.'/orbicon/modules/forms/class.form.php';
	$form = new Form;
	$counties = $form->get_pring_db_table('pring_counties', true);
	$towns = $form->get_pring_db_table('pring_towns', true);
	$form = null;

	$lat = floatval($estate->latitude);
	$lon = floatval($estate->logitude);

	if(DOMAIN_NO_WWW == 'foto-nekretnine.rs') {
		$lat = empty($lat) ? 44.016521 : $lat;
		$lon = empty($lon) ? 21.005859 : $lon;
		$gkey = 'ABQIAAAAlv-tQTaTwI9UJQ8CUFylZBSkb9_cQ_vOV4GjXzIBcBXRJ1SA9xRqD2lJoGpZBdCsgLgm2_KeV5DbLA';
		$city = 'Beograd';
	}
	else {
		$lat = empty($lat) ? 45.796255 : $lat;
		$lon = empty($lon) ? 15.954895 : $lon;
		$gkey = 'ABQIAAAAlv-tQTaTwI9UJQ8CUFylZBSkvgFR4CQCkwR0qIUYDWKbZwEeORSOvoH-JrNVbxzEU-2AYcgodQH7OQ';
		$city = 'Zagreb';
	}

	list($pic_main_file, $pic_main_desc) = explode(',', $estate->pic_main);
	list($pic2, $pic3, $pic4, $pic5, $pic6) = explode(';', $estate->pics);
	list($pic2_file, $pic2_desc) = explode(',', $pic2);
	list($pic3_file, $pic3_desc) = explode(',', $pic3);
	list($pic4_file, $pic4_desc) = explode(',', $pic4);
	list($pic5_file, $pic5_desc) = explode(',', $pic5);
	list($pic6_file, $pic6_desc) = explode(',', $pic6);

	$archive_link = ($estate->status == ESTATE_AD_LIVE) ? '<a href="'.ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/estate&amp;archive=' . $estate->id.'&amp;page=add&amp;id='.$estate->id.'">Arhiviraj</a>' : '<a href="'.ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/estate&amp;unarchive=' . $estate->id.'&amp;page=add&amp;id='.$estate->id.'">Odarhiviraj</a>';

	$sponsor_link = ($estate->sponsored == ESTATE_AD_NONSPONSORED) ? '<a href="'.ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/estate&amp;sponsor=' . $estate->id.'&amp;page=add&amp;id='.$estate->id.'">Sponzoriraj</a>' : '<a href="'.ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/estate&amp;unsponsor=' . $estate->id.'&amp;page=add&amp;id='.$estate->id.'">Odsponzoriraj</a>';

	$delete_link = '<a onmousedown="'.delete_popup($estate->title).'" onclick="javascript:return false;" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/estate&amp;del='.$estate->id.'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/delete.png" alt="'._L('delete').'" title="'._L('delete').'" /></a>';

?>
<style type="text/css">
#tags_container {position:absolute;width:150px;}
#tags_container .yui-ac-content {position:absolute;width:100%;border:1px solid #404040;background:#ffffff;overflow:hidden;z-index:9050;}
#tags_container .yui-ac-shadow {position:absolute;margin:.3em;width:100%;background:#a0a0a0;z-index:9049;}
#tags_container ul {padding:5px 0 5px 0;width:100%; background:white;}
#tags_container li {padding:0 5px 0 5px;cursor:default;white-space:nowrap;}
#tags_container li.yui-ac-highlight {background:#a0a0a0;}
</style>
<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/controler/gzip.server.php?file=/orbicon/modules/estate/estate.js&amp;<?php echo ORBX_BUILD; ?>"></script>
<input onclick="redirect('<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/estate'; ?>');" value="Svi oglasi" type="button" />
<?php echo $archive_link .' '. $sponsor_link .' '. $delete_link; ?>
    <form action="" method="post" enctype="multipart/form-data">
      <fieldset>
        <legend>Odaberi kategorije</legend>
        <table id="form_holder">
          <tr>
            <td><label for="kategorija">Tip</label></td>
            <td>
              <select id="kategorija" name="kategorija" class="mid" onchange="javascript:switch_estate_types(this.options[this.selectedIndex].value);">
				<?php echo print_select_menu($estate_type, $estate->category, true); ?>
              </select>
            </td>
          </tr>

          <tr>

	          <td><label for="ad_menu">Vrsta nekretnine</label></td>
	          <td>
	          <select id="ad_menu" name="ad_menu" class="mid">
               <?php echo $opcije; ?>
              </select></td>
	      </tr>

          <tr>
            <td><label for="ponuda">Vrsta oglasa</label></td>
            <td>
              <select id="ponuda" name="ponuda" class="select mid">
                <?php echo print_select_menu($estate_ad_type, $estate->ad_type, true); ?>
              </select>
            </td>
          </tr>
      </table>
    </fieldset>


          <fieldset>
            <legend>Oglas</legend>
            <table id="form_holder">
              <tr>
                <td><label for="naslov" class="required">Naslov oglasa</label></td>
                <td><input type="text" id="naslov" name="naslov" class="input_text big" value="<?php echo $estate->title; ?>" /></td>
              </tr>
              <tr>
                <td><label for="cijena" class="required">Cijena</label></td>
                <td>
                  <input type="text" id="cijena" name="cijena" value="<?php echo $estate->price; ?>" maxlength="10" class="input_text mid cijena"    />
                  <label for="valuta" title="Odaberite valutu" class="inline">Valuta</label>
                    <select id="valuta" name="valuta"><?php echo print_select_menu($estate_currencies, $estate->currency, true); ?></select>
                </td>
              </tr>
              <tr>
                <td><label for="povrsina" class="required">Površina</label></td>
                <td><input type="text" id="povrsina" name="povrsina" class="input_text mid" value="<?php echo $estate->msquare; ?>" maxlength="6"    /> <span>m<sup>2</sup></span></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><label for="regija" class="required">Županija</label></td>
                <td>
                  <select id="regija" name="regija" class="select big" onchange="javascript: switch_towns(this.options[this.selectedIndex].value, 'HR', 'grad_container', 'grad', 'grad');">
                    <option value="" class="first-child">Sve regije</option>
                    <?php echo print_select_menu($counties, $estate->county, true); ?>
                  </select>
                </td>
              </tr>

              <tr class="spacing">
                <td><label for="grad">Mjesto</label></td>
                <td id="grad_container">
                <select id="grad" name="grad" class="select big">
                	<?php echo print_select_menu($towns, $estate->town, true); ?>
                </select></td>
              </tr>

              <tr class="spacing">
                <td><label for="zg"><?php echo $city; ?></label></td>
                <td>
                <select id="zg" name="zg" class="select big">
                	<?php echo print_select_menu($estate_zagreb_parts, $estate->zg, true); ?>
                </select></td>
              </tr>

              <tr class="spacing">
                <td><label for="naselje">Naselje</label></td>
                <td><input type="text" id="naselje" name="naselje" class="input_text big" value="<?php echo $estate->neighborhood; ?>" /> </td>
              </tr>

              <tr class="spacing">
                <td><label for="ulica">Ulica</label></td>
                <td><input type="text" id="ulica" name="ulica" class="input_text big" value="<?php echo $estate->street; ?>" /> </td>
              </tr>
              <tr>
                <td><label for="kucni_broj">Kućni broj</label></td>
                <td><input type="text" id="kucni_broj" name="kucni_broj" class="input_text small" value="<?php echo $estate->street_no; ?>" maxlength="5" /> </td>
              </tr>
              <tr>
                <td><label for="frmLat">Geografska dužina</label></td>
                <td><input type="text" id="frmLat" name="geo_duzina" class="input_text mid" value="<?php echo $estate->latitude; ?>" /> <span class="small left_indent_10"><a href="javascript:void(null)" onclick="javascript:sh('google_map_container')">Potraži na karti</a></span></td>
              </tr>
              <tr>
                <td><label for="frmLon">Geografska širina</label></td>
                <td><input type="text" id="frmLon" name="geo_sirina" class="input_text mid" value="<?php echo $estate->logitude; ?>" />  <span class="small left_indent_10"><a href="javascript:void(null)" onclick="javascript:sh('google_map_container')">Potraži na karti</a></span></td>
              </tr>

              <tr>
				<td colspan="2">
				<!--- g maps -->

				<div id="google_map_container">
					<div id="map" style="width: 600px; height: 400px"></div>
					<div id="geo" style="width: 300px;position: absolute;left: 620px;top: 100px;" class="tekst">
					</div>
				</div>

				<script src="http://maps.google.com/maps?file=api&v=2&key=<?php echo $gkey ?> " type="text/javascript"></script>
<script type="text/javascript"><!-- // --><![CDATA[

	YAHOO.util.Event.addListener(window, 'load', function () {switch_estate_types($("kategorija").options[$("kategorija").selectedIndex].value);});

	var setLat = <?php echo $lat; ?>;
	var setLon = <?php echo $lon; ?>;
	var marker = null;

	setIcon();

	if	(argItems("address") != '') {
	myAddress = unescape(argItems("address"));
	document.getElementById("address").value = myAddress;

} else if (argItems("lat") == '' || argItems("lon") == '') {
	placeMarker(setLat, setLon);
	} else {
	var setLat = parseFloat( argItems("lat") );
	var setLon = parseFloat( argItems("lon") );
	setLat = setLat.toFixed(6);
	setLon = setLon.toFixed(6);
	placeMarker(setLat, setLon);
}

YAHOO.util.Event.addListener(window, "load", tags_autocomplete);

//]]></script>

				<!-- g maps  -->
				</td>
			</tr>

              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr class="spacing">
                <td><label for="vrsta_kuce">Vrsta kuće</label></td>
                <td>
                  <select id="vrsta_kuce" name="vrsta_kuce" class="select big">
                    <?php echo print_select_menu($estate_house_type, $estate->house_type, true); ?>
                  </select>
                </td>
              </tr>

              <tr class="spacing">
                <td><label for="vrsta_prostora">Vrsta poslovnog prostora</label></td>
                <td>
                  <select id="vrsta_prostora" name="vrsta_prostora" class="select big">
                  <?php echo print_select_menu($estate_business_type, $estate->business_type, true); ?>
                  </select>
                </td>
              </tr>

			<tr class="spacing">
                <td><label for="vrsta_stana">Vrsta stana</label></td>
                <td>
                  <select id="vrsta_stana" name="vrsta_stana" class="select mid">
                  <?php echo print_select_menu($estate_apartment_type, $estate->apartment_type, true); ?>
                  </select>
                </td>
              </tr>

              <tr class="spacing">
                <td><label for="vrsta_zemljista">Vrsta zemljišta</label></td>
                <td>
                  <select id="vrsta_zemljista" name="vrsta_zemljista" class="select mid">
                  <?php echo print_select_menu($estate_land_type, $estate->land_type, true); ?>
                  </select>
                </td>
              </tr>

              <tr>
                <td><label for="sirina">Širina</label></td>
                <td><input type="text" id="sirina" name="sirina" class="input_text small" value="<?php echo $estate->width; ?>" maxlength="6"     /> <span>m</span></td>
              </tr>

              <tr>
                <td><label for="duzina">Dužina</label></td>
                <td><input type="text" id="duzina" name="duzina" class="input_text small" value="<?php echo $estate->length; ?>" maxlength="6"     />  <span>m</span></td>
              </tr>

              <tr>
                <td><label for="novo_staro">Novogradnja/starogradnja</label></td>
                <td>
                  <select id="novo_staro" name="novo_staro" class="select mid">
                  <?php echo print_select_menu($estate_build_type, $estate->build_type, true); ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><label for="godina">Godina izgradnje</label></td>
                <td><input type="text" id="godina" name="godina" class="input_text small" value="<?php echo $estate->year_built; ?>"     /> </td>
              </tr>
              <tr>
                <td><label for="povrsina_okucnice">Površina okućnice</label></td>
                <td><input type="text" id="povrsina_okucnice" name="povrsina_okucnice" class="input_text small" value="<?php echo $estate->msquare_backyard; ?>" maxlength="5"     /> <span>m<sup>2</sup></span></td>
              </tr>
              <tr>
                <td><label for="broj_soba" class="broj">Broj soba</label></td>
                <td><input type="text" id="broj_soba" name="broj_soba" class="input_text small" value="<?php echo $estate->room_num; ?>" maxlength="2" /> </td>
              </tr>
              <tr>
                <td><label for="broj_etaza">Broj etaža</label></td>
                <td><input type="text" id="broj_etaza" name="broj_etaza" class="input_text small" value="<?php echo $estate->floor_num; ?>" maxlength="2" /> </td>
              </tr>

 			 <tr>
                <td><label for="udaljenost">Udaljenost od mora</label></td>
                <td><input type="text" id="udaljenost" name="udaljenost" class="input_text small" value="<?php echo $estate->sea_distance; ?>" maxlength="6"     /> <span>m</span></td>
              </tr>

 <tr>
                <td><label for="broj_kreveta" class="broj">Broj kreveta</label></td>
                <td><input type="text" id="broj_kreveta" name="broj_kreveta" class="input_text small" value="<?php echo $estate->bed_num; ?>" maxlength="2"     /> </td>
              </tr>

              <tr>
                <td><label for="kat">Kat</label></td>
                <td><input type="text" id="kat" name="kat" class="input_text small" value="<?php echo $estate->flat; ?>" maxlength="2"     /> </td>
              </tr>

              <tr>
                <td><label for="ukupno_katova">Ukupno katova</label></td>
                <td><input type="text" id="ukupno_katova" name="ukupno_katova" class="input_text small" value="<?php echo $estate->flat_num; ?>" maxlength="2"     /> </td>
              </tr>

              <tr>
                <td><label for="broj_kuponica">Broj kupaonica</label></td>
                <td><input type="text" id="broj_kuponica" name="broj_kuponica" class="input_text small" value="<?php echo $estate->bath_num; ?>" maxlength="2"     /> </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
               <tr>
                <td><label for="grijanje">Grijanje</label></td>
                <td>
                  <select id="grijanje" name="grijanje" class="select mid">
                    <?php echo print_select_menu($estate_heating_type, $estate->heating, true); ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><label>Javni prijevoz</label></td>
                <td><label for="bus" class="chck"><input <?php echo $checked = (get_estate_flag($estate->public_transport, ESTATE_PUBLIC_TR_BUS)) ? 'checked="checked"' : ''; ?> type="checkbox" id="bus" name="bus" value="<?php echo ESTATE_PUBLIC_TR_BUS; ?>" /> Autobus</label><label for="tramvaj" class="chck"><input <?php echo $checked = (get_estate_flag($estate->public_transport, ESTATE_PUBLIC_TR_TRAM)) ? 'checked="checked"' : ''; ?> type="checkbox" id="tramvaj" name="tramvaj" value="<?php echo ESTATE_PUBLIC_TR_TRAM; ?>" /> Tramvaj</label> </td>
              </tr>
               <tr>
                <td class="top"><label>Oprema</label></td>
                <td>
                  <label for="telefon" class="chck"><input value="<?php echo ESTATE_EQUIP_PHONE; ?>" type="checkbox" id="telefon" name="telefon" <?php echo $checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_PHONE)) ? 'checked="checked"' : ''; ?> /> Telefon</label>
                  <label for="balkon" class="chck"><input value="<?php echo ESTATE_EQUIP_BALCONY; ?>" type="checkbox" id="balkon" name="balkon" <?php echo $checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_BALCONY)) ? 'checked="checked"' : ''; ?> /> Balkon / Terasa</label>
                  <label for="vrt" class="chck"><input value="<?php echo ESTATE_EQUIP_GARDEN; ?>" type="checkbox" id="vrt" name="vrt" <?php echo $checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_GARDEN)) ? 'checked="checked"' : ''; ?> /> Vrt</label><br />
                  <label for="garaza" class="chck"><input value="<?php echo ESTATE_EQUIP_GARAGE; ?>" type="checkbox" id="garaza" name="garaza" <?php echo $checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_GARAGE)) ? 'checked="checked"' : ''; ?> /> Garaža / Parking</label>
                  <label for="klima" class="chck"><input value="<?php echo ESTATE_EQUIP_CLIMATE; ?>" type="checkbox" id="klima" name="klima" <?php echo $checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_CLIMATE)) ? 'checked="checked"' : ''; ?> /> Klima uređaj</label><br />
                  <label for="invalidi" class="chck block"><input value="<?php echo ESTATE_EQUIP_INVALIDS; ?>" type="checkbox" id="invalidi" name="invalidi" <?php echo $checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_INVALIDS)) ? 'checked="checked"' : ''; ?> /> Prilagođeno osobama s invaliditetom</label>

                  <p id="tourism_equipment">
                  <label for="bazen" class="chck"><input value="<?php echo ESTATE_EQUIP_POOL; ?>" <?php echo $checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_POOL)) ? 'checked="checked"' : ''; ?> type="checkbox" id="bazen" name="bazen" /> Bazen</label>

                  <label for="tv" class="chck"><input value="<?php echo ESTATE_EQUIP_TV; ?>" <?php echo $checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_TV)) ? 'checked="checked"' : ''; ?> type="checkbox" id="tv" name="tv" /> TV</label><br />
                  <label for="satelitska" class="chck"><input value="<?php echo ESTATE_EQUIP_SAT_TV; ?>" <?php echo $checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_SAT_TV)) ? 'checked="checked"' : ''; ?> type="checkbox" id="satelitska" name="satelitska" /> Satelitska TV</label>
                  <label for="internet" class="chck"><input value="<?php echo ESTATE_EQUIP_INTERNET; ?>" <?php echo $checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_INTERNET)) ? 'checked="checked"' : ''; ?> type="checkbox" id="internet" name="internet" /> Internet</label>
                  <label for="tereni" class="chck"><input value="<?php echo ESTATE_EQUIP_SPORT; ?>" <?php echo $checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_SPORT)) ? 'checked="checked"' : ''; ?> type="checkbox" id="tereni" name="tereni" /> Sportski tereni</label><br />
                  <label for="dvorana" class="chck"><input value="<?php echo ESTATE_EQUIP_CONFERENCE; ?>" <?php echo $checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_CONFERENCE)) ? 'checked="checked"' : ''; ?> type="checkbox" id="dvorana" name="dvorana" /> Konferencijska dvorana</label><br />
				</p>

				<p>

					<label for="put" class="chck"><input type="checkbox" id="put" name="put" value="<?php echo ESTATE_EQUIP_LAND_PATH; ?>" <?php echo ($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_LAND_PATH)) ? 'checked="checked"' : ''); ?> /> Pristupni put</label>
                  	<label for="struja" class="chck"><input type="checkbox" id="struja" name="struja" value="<?php echo ESTATE_EQUIP_LAND_POWER; ?>" <?php echo ($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_LAND_POWER)) ? 'checked="checked"' : ''); ?> /> Struja</label>
                  	<label for="voda" class="chck"><input type="checkbox" id="voda" name="voda" value="<?php echo ESTATE_EQUIP_LAND_WATER; ?>" <?php echo ($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_LAND_WATER)) ? 'checked="checked"' : ''); ?> /> Voda</label>
                  	<label for="plin" class="chck"><input type="checkbox" id="plin" name="plin" value="<?php echo ESTATE_EQUIP_LAND_GAS; ?>" <?php echo ($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_LAND_GAS)) ? 'checked="checked"' : ''); ?> /> Plin</label><br />
                 	 <label for="kanalizacija" class="chck"><input type="checkbox" id="kanalizacija" name="kanalizacija" value="<?php echo ESTATE_EQUIP_LAND_SEWER; ?>" <?php echo ($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_LAND_SEWER)) ? 'checked="checked"' : ''); ?> /> Kanalizacija</label>
                 	 <label for="telefon_land" class="chck block"><input type="checkbox" id="telefon_land" name="telefon" value="<?php echo ESTATE_EQUIP_PHONE; ?>" <?php echo ($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_PHONE)) ? 'checked="checked"' : ''); ?> /> Telefon</label>
                  	<label for="lokacijska" class="chck block"><input type="checkbox" id="lokacijska" name="lokacijska" value="<?php echo ESTATE_EQUIP_LAND_PAPERS; ?>" <?php echo ($checked = (get_estate_flag($estate->equipment, ESTATE_EQUIP_LAND_PAPERS)) ? 'checked="checked"' : ''); ?> /> Lokacijska dozvola</label>

				</p>

                 </td>
              </tr>

              <tr>
                <td><label for="dokumentacija">Dokumentacija</label></td>
                <td>
                  <select id="dokumentacija" name="dokumentacija" class="select mid">
                  <?php echo print_select_menu($estate_docs_type, $estate->docs, true); ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="top"><label for="tekst_oglasa" class="oglas">Tekst oglasa</label></td>
                <td><textarea cols="50" rows="10" id="tekst_oglasa" name="tekst_oglasa" class="input_textarea"><?php echo $estate->description; ?></textarea> </td>
              </tr>
              <tr>
                <td><label for="tagovi">Tagovi (ključne riječi)</label></td>
                <td><input type="text" id="tagovi" name="tagovi" class="input_text big" value="<?php echo $estate->tags; ?>" /> </td>
              </tr>
              <tr>
                <td><label for="tiskano">Objavi oglas i u tiskanom izdanju</label></td>
                <td><input type="checkbox" id="tiskano" name="tiskano" value="1" <?php echo($checked = ($estate->publish_print == 1) ? 'checked="checked"' : ''); ?> /></td>
              </tr>

              <tr>
                <td><label for="korisnik_id">RID korisnika koji je objavio oglas (oprez prilikom izmjene)</label></td>
                <td><input type="text" id="korisnik_id" name="korisnik_id" value="<?php echo $estate->user_id; ?>" /></td>
              </tr>

              <tr>
                <td><label for="sponsored_category">Zona prikaza</label></td>
                <td><select <?php echo $disabled = ($estate->sponsored == ESTATE_AD_SPONSORED) ? '' : 'disabled="disabled"'; ?> id="sponsored_category" name="sponsored_category"><option value="">Naslovnica</option><?php echo estate_gencol_menu_adm($estate->sponsored_category); ?></select></td>
              </tr>

              <tr>
                <td><label for="sponsored_live_to">Datum do kojeg se oglas prikazuje kao sponzorirani (format zapisa: MM/DD/GGGG)</label></td>
                <td><input <?php echo $disabled = ($estate->sponsored == ESTATE_AD_SPONSORED) ? '' : 'disabled="disabled"'; ?> type="text" id="sponsored_live_to" name="sponsored_live_to" value="<?php echo date('m/d/Y', $estate->sponsored_live_to); ?>" /></td>
              </tr>

			 <tr>
                <td><label for="ip_addr">IP Adresa</label></td>
                <td><input value="<?php echo $ip = (!$estate->ip) ? 'N/A' : $estate->ip; ?>" type="text" id="ip_addr" name="ip_addr" /></td>
              </tr>

            </table>
        </fieldset>

 <fieldset>
            <legend>Pošalji slike i video</legend>
             <table id="form_holder">
              <tr>
                <td><label for="slika_1">Postavi naslovnu sliku <?php echo print_preview_link($pic_main_file); ?></label></td>
                <td><input type="file" id="slika_1" name="slika_1" class="file" value="" /></td>
              </tr>
              <tr>
                <td><label for="opis_1">Opis naslovne slike</label></td>
                <td><input type="text" id="opis_1" name="opis_1" class="input_text big" value="<?php echo $pic_main_desc; ?>" /></td>
              </tr>
               <tr>
                <td></td>
                <td>&nbsp;</td>
              </tr>
               <tr>
                <td><label for="slika_2">Unesi 2. sliku <?php echo print_preview_link($pic2_file); ?></label></td>
                <td><input type="file" id="slika_2" name="slika_2" class="file" value="" /></td>
              </tr>
              <tr>
                <td><label for="opis_2">Kratki opis 2. slike</label></td>
                <td><input type="text" id="opis_2" name="opis_2" class="input_text big" value="<?php echo $pic2_desc; ?>" /></td>
              </tr>
               <tr>
                <td><label for="slika_3">Unesi 3. sliku <?php echo print_preview_link($pic4_file); ?></label></td>
                <td><input type="file" id="slika_3" name="slika_3" class="file" value="" /></td>
              </tr>
              <tr>
                <td><label for="opis_3">Kratki opis 3. slike</label></td>
                <td><input type="text" id="opis_3" name="opis_3" class="input_text big" value="<?php echo $pic3_desc; ?>" /></td>
              </tr>
               <tr>
                <td><label for="slika_4">Unesi 4. sliku <?php echo print_preview_link($pic4_file); ?></label></td>
                <td><input type="file" id="slika_4" name="slika_4" class="file" value="" /></td>
              </tr>
              <tr>
                <td><label for="opis_4">Kratki opis 4. slike</label></td>
                <td><input type="text" id="opis_4" name="opis_4" class="input_text big" value="<?php echo $pic4_desc; ?>" /></td>
              </tr>
              <tr>
                <td><label for="slika_5">Unesi 5. sliku <?php echo print_preview_link($pic5_file); ?></label></td>
                <td><input type="file" id="slika_5" name="slika_5" class="file" value="" /></td>
              </tr>
              <tr>
                <td><label for="opis_5">Kratki opis 5. slike</label></td>
                <td><input type="text" id="opis_5" name="opis_5" class="input_text big" value="<?php echo $pic5_desc; ?>" /></td>
              </tr>
              <tr>
                <td><label for="slika_6">Unesi 6. sliku <?php echo print_preview_link($pic6_file); ?></label></td>
                <td><input type="file" id="slika_6" name="slika_6" class="file" value="" /></td>
              </tr>
              <tr>
                <td><label for="opis_6">Kratki opis 6. slike</label></td>
                <td><input type="text" id="opis_6" name="opis_6" class="input_text big" value="<?php echo $pic6_desc; ?>" /></td>
              </tr>
               <tr>
                <td></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><label for="video">Pošalji video (max. <?php echo byte_size(get_php_ini_bytes(ini_get('post_max_size'))); ?>)</td>
                <td><input type="file" id="video" name="video" class="file" value="" /> <input id="remvideo" type="submit" name="remvideo" value="Ukloni video" ></td>
              </tr>
              <tr>
                <td><label for="embed">Video embed HTML</label></td>
                <td><textarea cols="50" rows="10" id="embed" name="embed" class="input_textarea"><?php echo $estate->video_embed; ?></textarea></td>
              </tr>
              <tr class="no_border">
                <td></td>
                <td><input type="submit" id="submit" name="submit" value="Spremi" class="step_bttn" /></td>
              </tr>
            </table>
        </fieldset>

   </form>