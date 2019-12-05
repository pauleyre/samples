<?php

	if(($_GET[$orbicon_x->ptr] == 'mod.peoplering') && ($_GET['sp'] != 'company_details')) {
		return null;
	}

	if(isset($_GET['tag'])) {
		return null;
	}

	if(($_GET[$orbicon_x->ptr] == 'mod.e') ||
	($_GET[$orbicon_x->ptr] == 'mod.estate.we.search') ||
	($_GET[$orbicon_x->ptr] == 'mod.estate.new')) {
		return null;
	}

	$type = $orbicon_x->get_column_type($_GET[$orbicon_x->ptr]);

	if(($type == 'h') || ($type == 'hidden')) {
		return null;
	}

	global $dbc;
	$r_news = $dbc->_db->query('SELECT id FROM ' . TABLE_NEWS . ' WHERE (permalink = '.$dbc->_db->quote($_GET[$orbicon_x->ptr]).')');
	$a_news = $dbc->_db->fetch_assoc($r_news);

	if($a_news['id']) {
		return null;
	}

	require_once DOC_ROOT . '/orbicon/modules/estate/inc.estate.php';

	global $estate_type, $estate_apartment_type, $estate_business_type, $estate_house_type, $estate_land_type, $estate_currencies, $estate_ad_type, $estate_build_type, $estate_docs_type, $estate_heating_type, $estate_zagreb_parts;

	global $orbx_mod;
	if($orbx_mod->validate_module('top_search_keywords')) {
		$top_kw = include_once DOC_ROOT . '/orbicon/modules/top_search_keywords/render.topkwrds.php';
	}

	include_once DOC_ROOT.'/orbicon/modules/forms/class.form.php';
	$form = new Form;
	$counties = $form->get_pring_db_table('pring_counties', true, 'title', '', true);
	if($_GET['regija'] == 1) {
		$towns = $form->get_pring_db_table('pring_towns', true, 'title', '', true);
	}
	$form = null;

	$selected_a = (isset($_GET['submit'])) ? ' class="selected" ' : '';
	/*$selected_b = (isset($_GET['submit_bp'])) ? ' class="selected" ' : '';
	$selected_c = (isset($_GET['submit_dp'])) ? ' class="selected" ' : '';*/

	// default state
	if(!$selected_a && !$selected_b && !$selected_c) {
		$selected_a = ' class="selected" ';
	}

	if($_GET['company']) {
		global $dbc;
		$q = sprintf('	SELECT 		title, contact
						FROM 		pring_company
						WHERE		(id = %s)
						LIMIT 		1', $dbc->_db->quote($_GET['company']));

		$r = $dbc->_db->query($q);
		$company = $dbc->_db->fetch_assoc($r);

		$agency_filter = '<label for="agencija_chck" class="agencija_chck"><input name="agencija_chck" id="agencija_chck" type="checkbox" value="1" '.($checked = (isset($_GET['agencija_chck'])) ? ' checked="checked"' : '').' /> '._L('e.showagencyads').' <strong>'.$company['title'].'</strong></label><input type="hidden" value="'.$company['contact'].'" id="agency_id" name="agency_id" />

		<input type="hidden" value="mod.peoplering" id="' . $orbicon_x->ptr . '" name="' . $orbicon_x->ptr . '" />
		<input type="hidden" value="company_details" id="sp" name="sp" />
		<input type="hidden" value="'.$_GET['company'].'" id="company" name="company" />
		';
	}
	else {
		$agency_filter = '';
	}

	if(DOMAIN_NO_WWW == 'foto-nekretnine.rs') {
		$city = 'Beograd';
	}
	else {
		$city = 'Zagreb';
	}

	return '
<!-- searchbox -->
<link rel="stylesheet" type="text/css" href="'.ORBX_SITE_URL.'/orbicon/controler/gzip.server.php?file=/orbicon/3rdParty/yui/build/tabview/assets/tabview.css&amp;'.ORBX_BUILD.'" />
<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/controler/gzip.server.php?file=/orbicon/3rdParty/yui/build/tabview/tabview-min.js&amp;'.ORBX_BUILD.'"></script>
<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/controler/gzip.server.php?file=/orbicon/modules/estate/estate.js&amp;'.ORBX_BUILD.'"></script>
<script type="text/javascript"><!-- // --><![CDATA[

	new YAHOO.widget.TabView("placeholder");

	YAHOO.util.Event.addListener(window, "load", function () {switch_estate_types($("kategorija_dp").options[$("kategorija_dp").selectedIndex].value);});

// ]]></script>

  <div id="placeholder" class="yui-navset">
    <ul class="yui-nav">
        <li '.$selected_a.'><a href="#kljucne-rijeci"><em>'._L('e.searchkw').'</em></a></li>
        <li '.$selected_b.'><a href="#brza-pretraga"><em>'._L('e.fastsearch').'</em></a></li>
        <li '.$selected_c.'><a href="#detaljna-pretraga"><em>'._L('e.deepsearh').'</em></a></li>
    </ul>
    <div class="yui-content">
        <div id="kljucne-rijeci">

              <div id="kr">
		        <form action="" method="get" title="'._L('e.searchkw').'" id="searchBox">
		          <fieldset>
		            <legend>'._L('e.searchkw').'</legend>
	    	          <input value="'.$orbicon_x->ptr.'" type="hidden" id="ln" name="ln" />
		              <input value="'.$_GET['q'].'" type="text" id="q" name="q" title="'._L('e.searchterm').'" class="input"  />
		              <input type="submit" title="'._L('e.search').'" name="submit" value="'._L('e.search').'" class="bttn"  />
		              '.$agency_filter.'
					 <!-- <label for="sa_slikom_kr" class="agencija_chck"><input type="checkbox" name="sa_slikom" id="sa_slikom_kr" value="1" '.($checked = ($_GET['sa_slikom']) ? 'checked="checked"' : '').' /> <span>'._L('e.adswithpic').'</span></label> -->

					   <label for="#" class="agencija_chck"><span>'._L('e.showonlyads').' <input type="checkbox" name="sa_slikom" id="sa_slikom_kr" value="1" '.($checked = ($_GET['sa_slikom']) ? 'checked="checked"' : '').' /> <label for="sa_slikom_kr">'._L('e.withpic').'</label>
		              <input type="checkbox" name="sa_videom" id="sa_videom_kr" value="1" '.($checked = ($_GET['sa_videom']) ? 'checked="checked"' : '').' /> <label for="sa_videom_kr">'._L('e.withvid').'</label>
		              <input type="checkbox" name="sa_kartom" id="sa_kartom_kr" value="1" '.($checked = ($_GET['sa_kartom']) ? 'checked="checked"' : '').' /> <label for="sa_kartom_kr">'._L('e.withmap').'</label></span></label>

		          </fieldset>
		        </form>
		      '.$top_kw.'
		      </div>

        </div>
        <div id="brza-pretraga" class="h">

        	      <div id="bp" >
			        <form action="" method="get" id="dp_form" class="clr">
			        <input value="'.$orbicon_x->ptr.'" type="hidden" id="ln" name="ln" />
			          <fieldset>
			          <legend>'._L('e.fastsearch').'</legend>

			            <table id="search_table">
			              <tr class="top">
			                <td><label for="kategorija_bp">
			                  <select id="kategorija_bp" name="kategorija" class="main">
			                    '.print_select_menu($estate_type, $_GET['kategorija'], true).'
			                  </select></label>
			                </td>
			                <td><label for="ponuda_bp">
			                  <select id="ponuda_bp" name="ponuda" class="main2">
			                    '.print_select_menu($estate_ad_type, $_GET['ponuda'], true).'
			                  </select></label>
			                </td>
			              </tr>

			              <tr class="white">
			                <td><label for="bp_regija">'._L('e.region').'</label></td>
			                <td><label for="naselje">'._L('e.place_neigh').'</label></td>
			              </tr>
			              <tr class="gray">
			                <td colspan="2">
			                  <select id="bp_regija" name="regija" class="big" onchange="javascript: switch_towns(this.options[this.selectedIndex].value, \'HR\', \'bp_grad_container\', \'naselje\', \'naselje\');">
			                    <option value="" class="first-child">'._L('e.pickregion').'</option>
			                    '.print_select_menu($counties, $_GET['regija'], true).'
			                  </select>

			                  <span id="bp_grad_container">
								<select id="naselje" name="naselje" class="select big">
									<option value="">&mdash; '._L('e.allplaces').' &mdash;</option>
                					'.print_select_menu($towns, $_GET['naselje'], true).'
                				</select>
                			</span>

			                </td>
			              </tr>
			              <tr class="white">
			                <td><label for="povrsina_od">'._L('e.msquare').'</label></td>
			                <td><label for="cijena_od">'._L('e.pricerange').'</label></td>
			              </tr>
			              <tr class="gray">
			                <td><input type="text" id="povrsina_od" name="povrsina_od" class="input_text small" value="'.$_GET['povrsina_od'].'"  /> - <input type="text" id="povrsina_do" name="povrsina_do" class="input_text small" value="'.$_GET['povrsina_do'].'"  /> m<sup>2</sup></td>
			                <td><input type="text" id="cijena_od" name="cijena_od" class="input_text small" maxlength="8" value="'.$_GET['cijena_od'].'"  /> - <input type="text" id="cijena_do" name="cijena_do" class="input_text small" maxlength="8"  value="'.$_GET['cijena_do'].'" /> <select id="bp_valuta" name="valuta" class="select small">'.print_select_menu($estate_currencies, $_GET['valuta'], true).'</select></td>
			              </tr>
			              <tr class="white">
			                <td><label for="bp_poredak">'._L('e.sortby').'</label></td>
			                <td></label></td>
			              </tr>
			              <tr class="gray">
			                <td>
			                  <select name="poredak" id="bp_poredak" class="mid">
			                    <option value="price_lower" '.($s = ($_GET['poredak'] == 'price_lower') ? ' selected="selected" ' : '').'>'._L('e.pricelowest').'</option>
			                    <option value="price_higher"'.($s = ($_GET['poredak'] == 'price_higher') ? ' selected="selected" ' : '').'>'._L('e.pricehighest').'</option>
			                    <option value="date_older"'.($s = ($_GET['poredak'] == 'date_older') ? ' selected="selected" ' : '').'>'._L('e.dateoldest').'</option>
			                    <option value="date_newer"'.($s = ($_GET['poredak'] == 'date_newer') ? ' selected="selected" ' : '').'>'._L('e.datenewest').'</option>
			                  </select>
			                </td>
			                <td><label for="sa_slikom" class="normal"><input type="checkbox" name="sa_slikom" id="sa_slikom" value="1" '.($checked = ($_GET['sa_slikom']) ? 'checked="checked"' : '').' /> <span class="normal">'._L('e.adswithpic').'</span></label></td>
			              </tr>

			              <tr class="gray">
		                <td><label for="sa_videom" class="normal"><input type="checkbox" name="sa_videom" id="sa_videom" value="1" '.($checked = ($_GET['sa_videom']) ? 'checked="checked"' : '').' /> <span class="normal">'._L('e.adsvideo').'</span></label></td>
		                <td><label for="sa_kartom" class="normal"><input type="checkbox" name="sa_kartom" id="sa_kartom" value="1" '.($checked = ($_GET['sa_kartom']) ? 'checked="checked"' : '').' /> <span class="normal">'._L('e.adsmap').'</span></label></td>
		              </tr>

			              <tr>
			                <td><input id="submit_bp" name="submit_bp" type="submit" value="'._L('e.search').'" class="bttn" /></td>
			                <td></td>
			              </tr>
			            </table>
			          </fieldset>
			        </form>
			      </div>

        </div>
        <div id="detaljna-pretraga" class="h">

        	<div id="dp" >
		        <form action="" method="get" id="dp_form" class="clr">
		        <input value="'.$orbicon_x->ptr.'" type="hidden" id="ln" name="ln" />
		          <fieldset>
		          <legend>'._L('e.deepsearh').'</legend>

		            <table id="search_table">
		              <tr class="top">
		                <td><label for="kategorija_dp">
		                  <select id="kategorija_dp" name="kategorija" class="main" onchange="javascript:switch_estate_types(this.options[this.selectedIndex].value);">
		                   '.print_select_menu($estate_type, $_GET['kategorija'], true).'
		                  </select></label>
		                </td>
		                <td><label for="ponuda_dp">
		                  <select id="ponuda_dp" name="ponuda" class="main2">
		                    '.print_select_menu($estate_ad_type, $_GET['ponuda'], true).'
		                  </select></label>
		                </td>
		              </tr>

		              <tr class="big_space">
		                <td><label for="dp_regija">'._L('e.region').'</label></td>
		                <td><label for="naselje_dp">'._L('e.place_neigh').'</label></td>
		              </tr>
		              <tr class="gray">
		                <td colspan="2">
		                  <select id="dp_regija" name="regija" class="big" onchange="javascript: switch_towns(this.options[this.selectedIndex].value, \'HR\', \'dp_grad_container\', \'naselje_dp\', \'naselje\');">
		                    <option value="" class="first-child">'._L('e.pickregion').'</option>
		                    '.print_select_menu($counties, $_GET['regija'], true).'
		                  </select>
							<span id="dp_grad_container">
								<select id="naselje_dp" name="naselje" class="select big">
									<option value="">&mdash; '._L('e.allplaces').' &mdash;</option>
                					'.print_select_menu($towns, $_GET['naselje'], true).'
                				</select>
                			</span>
		                </td>
		              </tr>
		              <tr class="white">
		                <td><label for="povrsina_od_dp">'._L('e.msquare').'</label></td>
		                <td><label for="cijena_od_dp">'._L('e.pricerange').'</label></td>
		              </tr>
		              <tr class="gray">
		                <td><input value="'.$_GET['povrsina_od'].'" type="text" id="povrsina_od_dp" name="povrsina_od" class="input_text small"   /> - <input type="text" id="povrsina_do" name="povrsina_do" class="input_text small" value="'.$_GET['povrsina_do'].'"  /> m<sup>2</sup></td>
		                <td><input type="text" id="cijena_od_dp" name="cijena_od" class="input_text small" maxlength="8" value="'.$_GET['cijena_od'].'"  /> - <input type="text" id="cijena_do" name="cijena_do" class="input_text small" maxlength="8"  value="'.$_GET['cijena_do'].'" /> <select id="dp_valuta" name="valuta" class="select small">'.print_select_menu($estate_currencies, $_GET['valuta'], true).'</select></td>
		              </tr>
		              <tr class="big_space">
		                <td><label for="vrsta_stana">'._L('e.aparttypo').'</label></td>
		                <td><label for="grijanje">'._L('e.heat').'</label></td>
		              </tr>
		              <tr class="gray">
		                <td>
		                	<select id="vrsta_stana" name="vrsta_stana" class="select mid">
		                	'.print_select_menu($estate_apartment_type, $_GET['vrsta_stana'], true).'
		                	</select><br />

		                	<label for="vrsta_kuce">'._L('e.housetype').'</label><br />
		                	<select id="vrsta_kuce" name="vrsta_kuce" class="select big">
                		    '.print_select_menu($estate_house_type, $_GET['vrsta_kuce'], true).'
      			            </select><br />

      			            <label for="vrsta_prostora">'._L('e.bsntype').'</label><br />
		                  <select id="vrsta_prostora" name="vrsta_prostora" class="select big">
		                  '.print_select_menu($estate_business_type, $_GET['vrsta_prostora'], true).'
		                  </select><br />

		                  <label for="vrsta_zemljista">'._L('e.landtype').'</label><br />
		                  <select id="vrsta_zemljista" name="vrsta_zemljista" class="select mid">
		                  '.print_select_menu($estate_land_type, $_GET['vrsta_zemljista'], true).'
		                  </select>
		                <td style="vertical-align:top;">
		                <select id="grijanje" name="grijanje" class="mid">
		                '.print_select_menu($estate_heating_type, $_GET['grijanje'], true).'
		                </select><br />

						<label for="zg">'.$city.'</label><br />
		                <select id="zg" name="zg" class="select big" '.($disabled = ($_GET['regija'] == 2) ? '' : 'disabled="disabled"').'>
		                	'.print_select_menu($estate_zagreb_parts, $_GET['zg'], true).'
		                </select>

		                </td>
		              </tr>
		              <tr class="white">
		                <td><label for="br_soba_od">'._L('e.roomnum').'</label></td>
		                <td><label for="br_katova_od">'._L('e.flatnum').'</label></td>
		              </tr>
		              <tr class="gray">
		                <td><input type="text" id="br_soba_od" name="br_soba_od" class="input_text small" maxlength="2" value="'.$_GET['br_soba_od'].'"  /> - <input type="text" id="broj_soba_do" name="broj_soba_do" class="input_text small" maxlength="2" value="'.$_GET['br_soba_do'].'"  /></td>
		                <td><input type="text" id="br_katova_od" name="br_katova_od" class="input_text small" maxlength="2" value="'.$_GET['br_katova_od'].'" /> - <input type="text" id="br_katova_do" name="br_katova_do" class="input_text small" maxlength="2" value="'.$_GET['br_katova_do'].'" /></td>
		              </tr>
		              <tr class="white">
		                <td><label for="br_kupaonica_od">'._L('e.bathnum').'</label></td>
		                <td><label for="ukupno_katova_od">'._L('e.totalfloor').'</label></td>
		              </tr>
		              <tr class="gray">
		                <td><input type="text" id="br_kupaonica_od" name="br_kupaonica_od" class="input_text small" maxlength="2" value="'.$_GET['br_kupaonica_od'].'"  /> - <input type="text" id="br_kupaonica_do" name="br_kupaonica_do" class="input_text small" maxlength="2"  value="'.$_GET['br_jupaonica_do'].'" /></td>
		                <td><input type="text" id="ukupno_katova_od" name="ukupno_katova_od" class="input_text small" maxlength="2"  value="'.$_GET['ukupno_katova_od'].'" /> - <input type="text" id="ukupno_katova_do" name="ukupno_katova_do" class="input_text small" maxlength="2" value="'.$_GET['ukupno_katova_do'].'"  /></td>
		              </tr>
		              <tr class="white">
		                <td><label for="ng_sg">'._L('e.builttype').'</label></td>
		                <td><label for="god_od">'._L('e.built').'</label></td>
		              </tr>
		              <tr class="gray">
		                <td>
		                <select id="ng_sg" name="ng_sg" class="mid">
		                <option value="">'._L('e.notimp').'</option>
		                '.print_select_menu($estate_build_type, $_GET['ng_sg'], true).'
		                </select></td>
		                <td><input type="text" id="god_od" name="god_od" class="input_text small" maxlength="4" value="'.$_GET['god_od'].'"  /> - <input type="text" id="god_do" name="god_do" class="input_text small" maxlength="4"  value="'.$_GET['god_do'].'" /></td>
		              </tr>
		              <tr class="white">
		                <td><label for="ulica">'._L('e.street').'</label></td>
		                <td><label>'._L('e.publictransport').'</label></td>
		              </tr>
		              <tr class="gray">
		                <td><input type="text" value="'.$_GET['ulica'].'" id="ulica" name="ulica" class="input_text mid" /></td>
		                <td><label for="prijevoz_da" class="radio_label"><input type="radio" name="prijevoz" id="prijevoz_da" class="radio" value="1" /> '._L('e.yes').'</label> <label for="prijevoz_nema_veze" class="radio_label"><input type="radio" name="prijevoz" id="prijevoz_nema_veze" class="radio" value="3" /> '._L('e.notimp').'</label></td>
		              </tr>
		              <tr class="white">
		                <td><label>'._L('e.equip').'</label></td>
		                <td></td>
		              </tr>
		              <tr class="gray">
		                <td colspan="2">
		                  <label for="telefon" class="chck"><input value="'.ESTATE_EQUIP_PHONE.'" type="checkbox" id="telefon" name="telefon" '.($checked = ($_GET['telefon'] ==  ESTATE_EQUIP_PHONE) ? 'checked="checked"' : '').' /> '._L('e.phone').'</label>
                  <label for="balkon" class="chck"><input value="'.ESTATE_EQUIP_BALCONY.'" type="checkbox" id="balkon" name="balkon" '.($checked = ($_GET['balkon'] ==  ESTATE_EQUIP_BALCONY) ? 'checked="checked"' : '').' /> '._L('e.balcony').'</label>
                  <label for="vrt" class="chck"><input value="'.ESTATE_EQUIP_GARDEN.'" type="checkbox" id="vrt" name="vrt" '.($checked = ($_GET['vrt'] ==  ESTATE_EQUIP_GARDEN) ? 'checked="checked"' : '').' /> '._L('e.garden').'</label><br />
                  <label for="garaza" class="chck"><input value="'.ESTATE_EQUIP_GARAGE.'" type="checkbox" id="garaza" name="garaza" '.($checked = ($_GET['garaza'] ==  ESTATE_EQUIP_GARAGE) ? 'checked="checked"' : '').' /> '._L('e.garage').'</label>
                  <label for="klima" class="chck"><input value="'.ESTATE_EQUIP_CLIMATE.'" type="checkbox" id="klima" name="klima" '.($checked = ($_GET['klima'] ==  ESTATE_EQUIP_CLIMATE) ? 'checked="checked"' : '').' /> '._L('e.climate').'</label><br />
                  <label for="invalidi" class="chck block"><input value="'.ESTATE_EQUIP_INVALIDS.'" type="checkbox" id="invalidi" name="invalidi" '.($checked = ($_GET['invalidi'] ==  ESTATE_EQUIP_INVALIDS) ? 'checked="checked"' : '').' /> '._L('e.invalid').'</label>

		                  <p id="tourism_equipment">
			                  <label for="bazen" class="chck"><input value="'.ESTATE_EQUIP_POOL .'" '. ($checked = ($_GET['bazen'] == ESTATE_EQUIP_POOL) ? 'checked="checked"' : '').' type="checkbox" id="bazen" name="bazen" /> '._L('e.pool').'</label>

			                  <label for="tv" class="chck"><input value="'. ESTATE_EQUIP_TV.'" '. ($checked = ($_GET['tv'] ==  ESTATE_EQUIP_TV) ? 'checked="checked"' : '').' type="checkbox" id="tv" name="tv" /> '._L('e.tv').'</label><br />
			                  <label for="satelitska" class="chck"><input value="'. ESTATE_EQUIP_SAT_TV.'" '. ($checked = ($_GET['satelitska'] ==  ESTATE_EQUIP_SAT_TV) ? 'checked="checked"' : '').' type="checkbox" id="satelitska" name="satelitska" /> '._L('e.sattv').'</label>
			                  <label for="internet" class="chck"><input value="'. ESTATE_EQUIP_INTERNET.'" '. ($checked = ($_GET['internet'] ==  ESTATE_EQUIP_INTERNET) ? 'checked="checked"' : '').' type="checkbox" id="internet" name="internet" /> '._L('e.net').'</label>
			                  <label for="tereni" class="chck"><input value="'. ESTATE_EQUIP_SPORT.'" '. ($checked = ($_GET['tereni'] ==  ESTATE_EQUIP_SPORT) ? 'checked="checked"' : '').' type="checkbox" id="tereni" name="tereni" /> '._L('e.sport').'</label><br />
			                  <label for="dvorana" class="chck"><input value="'. ESTATE_EQUIP_CONFERENCE.'" '. ($checked = ($_GET['dvorana'] ==  ESTATE_EQUIP_CONFERENCE) ? 'checked="checked"' : '').' type="checkbox" id="dvorana" name="dvorana" /> '._L('e.hall').'</label><br />
							</p>

		                </td>
		              </tr>
		              <tr class="white">
		                <td><label for="br_oglasa">'._L('e.adnum').'</label></td>
		                <td><label for="dokumentacija">'._L('e.docs').'</label></td>
		              </tr>
		              <tr class="gray">
		                <td><input value="'.$_GET['br_oglasa'].'" type="text" id="br_oglasa" name="br_oglasa" class="input_text mid"   /></td>
		                <td>
		                <select name="dokumentacija" id="dokumentacija" class="mid">
		                <option value="">'._L('e.notimp').'</option>
		                '.print_select_menu($estate_docs_type, $_GET['dokumentacija'], true).'
		                </select>
		                </td>
		              <tr class="big_space">
		                <td><label for="dp_poredak2">'._L('e.sortby').'</label></td>
		                <td></td>
		              </tr>
		              <tr class="gray">
		                <td>
		                  <select name="poredak" id="dp_poredak2" class="mid">
		                     <option value="price_lower"'.($s = ($_GET['poredak'] == 'price_lower') ? ' selected="selected" ' : '').'>'._L('e.pricelowest').'</option>
	 	                    <option value="price_higher"'.($s = ($_GET['poredak'] == 'price_higher') ? ' selected="selected" ' : '').'>'._L('e.pricehighest').'</option>
	 	                    <option value="date_older"'.($s = ($_GET['poredak'] == 'date_older') ? ' selected="selected" ' : '').'>'._L('e.dateoldest').'</option>
		                    <option value="date_newer"'.($s = ($_GET['poredak'] == 'date_newer') ? ' selected="selected" ' : '').'>'._L('e.datenewest').'</option>
		                  </select>
		                </td>
		                <td><label for="sa_slikom2" class="normal"><input type="checkbox" name="sa_slikom" id="sa_slikom2" value="1" '.($checked = ($_GET['sa_slikom']) ? 'checked="checked"' : '').' /> <span class="normal">'._L('e.adswithpic').'</span></label></td>
		              </tr>

		              <tr class="gray">
		                <td><label for="sa_videom2" class="normal"><input type="checkbox" name="sa_videom" id="sa_videom2" value="1" '.($checked = ($_GET['sa_videom']) ? 'checked="checked"' : '').' /> <span class="normal">'._L('e.adsvideo').'</span></label></td>
		                <td><label for="sa_kartom2" class="normal"><input type="checkbox" name="sa_kartom" id="sa_kartom2" value="1" '.($checked = ($_GET['sa_kartom']) ? 'checked="checked"' : '').' /> <span class="normal">'._L('e.adsmap').'</span></label></td>
		              </tr>

		              <tr>
		                <td><input id="submit_dp" name="submit_dp" type="submit" value="'._L('e.search').'" class="bttn" /></td>
		                <td></td>
		              </tr>
		            </table>
		          </fieldset>
		        </form>
		      </div>

        </div>
    </div>
</div>';

?>