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
	$r_news = $dbc->_db->query('	SELECT 		id
									FROM 		' . TABLE_NEWS . '
									WHERE 		(permalink = '.$dbc->_db->quote($_GET[$orbicon_x->ptr]).')');
	$a_news = $dbc->_db->fetch_assoc($r_news);

	if($a_news['id']) {
		return null;
	}

	require_once DOC_ROOT . '/orbicon/modules/inpulls/inc.inpulls.php';

	global $inpulls_im_here_for, $inpulls_sex_group, $inpulls_sex, $inpulls_music, $inpulls_drinks;

	global $orbx_mod;
	if($orbx_mod->validate_module('top_search_keywords')) {
		$top_kw = include_once DOC_ROOT . '/orbicon/modules/top_search_keywords/render.topkwrds.php';
	}

	include_once DOC_ROOT.'/orbicon/modules/forms/class.form.php';
	$form = new Form;
	$counties = $form->get_pring_db_table('pring_counties', true);
	//$towns = $form->get_pring_db_table('pring_towns', true);
	$form = null;

	$selected_a = (isset($_GET['submit'])) ? 'class="selected"' : '';
	/*$selected_b = (isset($_GET['submit_bp'])) ? 'class="selected"' : '';
	$selected_c = (isset($_GET['submit_dp'])) ? 'class="selected"' : '';*/

	// default state
	if(!$selected_a && !$selected_b && !$selected_c) {
		$selected_a = 'class="selected"';
	}

	/*if($_GET['company']) {
		global $dbc;
		$q = sprintf('	SELECT 		title, contact
						FROM 		pring_company
						WHERE		(id = %s)
						LIMIT 		1', $dbc->_db->quote($_GET['company']));

		$r = $dbc->_db->query($q);
		$company = $dbc->_db->fetch_assoc($r);

		$agency_filter = '<label for="agencija_chck" class="agencija_chck"><input name="agencija_chck" id="agencija_chck" type="checkbox" value="1" '.($checked = (isset($_GET['agencija_chck'])) ? ' checked="checked"' : '').' /> Pretraži oglase samo od agencije <strong>'.$company['title'].'</strong></label><input type="hidden" value="'.$company['contact'].'" id="agency_id" name="agency_id" />

		<input type="hidden" value="mod.peoplering" id="' . $orbicon_x->ptr . '" name="' . $orbicon_x->ptr . '" />
		<input type="hidden" value="company_details" id="sp" name="sp" />
		<input type="hidden" value="'.$_GET['company'].'" id="company" name="company" />
		';
	}
	else {
		$agency_filter = '';
	}*/

	return '
<!-- searchbox -->
<link rel="stylesheet" type="text/css" href="'.ORBX_SITE_URL.'/orbicon/controler/gzip.server.php?file=/orbicon/3rdParty/yui/build/tabview/assets/tabview.css&amp;'.ORBX_BUILD.'" />
<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/controler/gzip.server.php?file=/orbicon/3rdParty/yui/build/tabview/tabview-min.js&amp;'.ORBX_BUILD.'"></script>
<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/controler/gzip.server.php?file=/orbicon/modules/inpulls/inpulls.js&amp;'.ORBX_BUILD.'"></script>
<script type="text/javascript"><!-- // --><![CDATA[

	new YAHOO.widget.TabView("placeholder");

// ]]></script>

  <div id="placeholder" class="yui-navset">
    <ul class="yui-nav">
        <li '.$selected_a.'><a href="#kljucne-rijeci"><em>Pretraga po ključnim riječima</em></a></li>
        <li '.$selected_b.'><a href="#brza-pretraga"><em>Brza pretraga</em></a></li>
        <li '.$selected_c.'><a href="#detaljna-pretraga"><em>Detaljna pretraga</em></a></li>
    </ul>
    <div class="yui-content">
        <div id="kljucne-rijeci">

              <div id="kr">
		        <form action="" method="get" title="Pretraga po ključnim riječima" id="searchBox">
		          <fieldset>
		            <legend>Pretraga po ključnim riječima</legend>
		              <input value="'.$_GET['q'].'" type="text" id="q" name="q" title="Unesite traženi pojam" class="input"  />
		              <input type="submit" title="Traži" name="submit" value="Traži" class="bttn"  />
		              '.$agency_filter.'
		              <label for="#" class="agencija_chck"><span>Prikaži samo korisnike sa <input type="checkbox" name="sa_slikom" id="sa_slikom_kr" value="1" '.($checked = ($_GET['sa_slikom']) ? 'checked="checked"' : '').' /> <label for="sa_slikom_kr">slikom</label>
		              <input type="checkbox" name="sa_videom" id="sa_videom_kr" value="1" '.($checked = ($_GET['sa_videom']) ? 'checked="checked"' : '').' /> <label for="sa_videom_kr">videom</label>
		              <input type="checkbox" name="sa_kartom" id="sa_kartom_kr" value="1" '.($checked = ($_GET['sa_kartom']) ? 'checked="checked"' : '').' /> <label for="sa_kartom_kr">kartom</label></span></label>
		          </fieldset>
		        </form>
		      '.$top_kw.'
		      </div>

        </div>
        <div id="brza-pretraga" class="h">

        	      <div id="bp" >
			        <form action="" method="get" id="dp_form" class="clr">
			          <fieldset>
			          <legend>Detaljna pretraga</legend>

			            <table id="search_table">
			              <tr class="top">
			                <td><label for="im_here_for_bp">
			                  <select id="im_here_for_bp" name="im_here_for" class="main">
			                  <option value="" class="first-child">Osoba traži...</option>
			                    '.print_select_menu($inpulls_im_here_for, $_GET['im_here_for'], true).'
			                  </select></label>
			                </td>
			                <td><label for="sex_group_bp">
			                  <select id="sex_group_bp" name="sex_group" class="main2">
  			                   <option value="" class="first-child">Spolno opredjeljenje</option>
			                    '.print_select_menu($inpulls_sex_group, $_GET['sex_group'], true).'
			                  </select></label>
			                </td>
			              </tr>

			              <tr class="white">
			                <td><label for="bp_regija">Županija</label></td>
			                <td><label for="naselje">Mjesto / Naselje</label></td>
			              </tr>
			              <tr class="gray">
			                <td colspan="2">
			                  <select id="bp_regija" name="regija" class="big" onchange="javascript: switch_towns(this.options[this.selectedIndex].value, \'HR\', \'bp_grad_container\', \'naselje\', \'naselje\');">
			                    <option value="" class="first-child">Odaberi regiju</option>
			                    '.print_select_menu($counties, $_GET['regija'], true).'
			                  </select>

			                  <span id="bp_grad_container">
								<select id="naselje" name="naselje" class="select big">
									<option value="">&mdash; Sva mjesta &mdash;</option>
                					'.print_select_menu($towns, $_GET['naselje'], true).'
                				</select>
                			</span>

			                </td>
			              </tr>
			              <tr class="white">
			                <td><label for="horoscope_bp">Horoskop</label></td>
			                <td><label for="years_from_bp">Godine (spol)</label></td>
			              </tr>
			              <tr class="gray">
			                <td><select id="horoscope_bp" name="horoscope" class="select mid">
			                '.print_select_menu($inpulls_horoscope, $_GET['horoscope'], true).'
							</select></td>
			                <td><input type="text" id="years_from_bp" name="years_from" class="input_text small" maxlength="8" value="'.$_GET['years_from'].'"  /> - <input type="text" id="years_to" name="years_to" class="input_text small" maxlength="8"  value="'.$_GET['years_to'].'" /> <select id="sex" name="sex" class="select small">'.print_select_menu($inpulls_sex, $_GET['sex'], true).'</select></td>
			              </tr>
			              <tr class="white">
			                <td><label for="bp_poredak">Prikaži po</label></td>
			                <td></td>
			              </tr>
			              <tr class="gray">
			                <td>
			                  <select name="poredak" id="bp_poredak" class="mid">
			                    <option value="online">Online: prvo online</option>
			                    <option value="offline">Online: prvo offline</option>
			                    <option value="popular_more">Popularnosti: prvo više popularni</option>
			                    <option value="popular_less">Popularnosti: prvo manje popularni</option>
			                    <option value="date_older">Datumu: prvo najstariji</option>
			                    <option value="date_newer">Datumu: prvo najnoviji</option>
			                  </select>
			                </td>
			                <td><label for="sa_slikom" class="normal"><input type="checkbox" name="sa_slikom" id="sa_slikom" value="1" '.($checked = ($_GET['sa_slikom']) ? 'checked="checked"' : '').' /> <span class="normal">Prikaži samo korisnike sa slikom</span></label></td>
			              </tr>
			              <tr class="gray">
		                <td><label for="sa_videom" class="normal"><input type="checkbox" name="sa_videom" id="sa_videom" value="1" '.($checked = ($_GET['sa_videom']) ? 'checked="checked"' : '').' /> <span class="normal">Prikaži samo korisnike sa videom</span></label></td>
		                <td><label for="sa_kartom" class="normal"><input type="checkbox" name="sa_kartom" id="sa_kartom" value="1" '.($checked = ($_GET['sa_kartom']) ? 'checked="checked"' : '').' /> <span class="normal">Prikaži samo korisnike sa kartom</span></label></td>
		              </tr>
			              <tr>
			                <td><input id="submit_bp" name="submit_bp" type="submit" value="Traži" class="bttn" /></td>
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
		          <fieldset>
		          <legend>Detaljna pretraga</legend>

		            <table id="search_table">
		              <tr class="top">
		                <td><label for="im_here_for_dp">
		                  <select id="im_here_for_dp" name="im_here_for" class="main">
		                   <option value="" class="first-child">Osoba traži...</option>
			               '.print_select_menu($inpulls_im_here_for, $_GET['im_here_for'], true).'
		                  </select></label>
		                </td>
		                <td><label for="sex_group_dp">
		                  <select id="sex_group_dp" name="sex_group" class="main2">
		                    <option value="" class="first-child">Spolno opredjeljenje</option>
			               '.print_select_menu($inpulls_sex_group, $_GET['sex_group'], true).'
		                  </select></label>
		                </td>
		              </tr>

		              <tr class="big_space">
		                <td><label for="dp_regija">Županija</label></td>
		                <td><label for="naselje_dp">Mjesto / Naselje</label></td>
		              </tr>
		              <tr class="gray">
		                <td colspan="2">
		                  <select id="dp_regija" name="regija" class="big" onchange="javascript: switch_towns(this.options[this.selectedIndex].value, \'HR\', \'dp_grad_container\', \'naselje_dp\', \'naselje\');">
		                    <option value="" class="first-child">Odaberi regiju</option>
		                    '.print_select_menu($counties, $_GET['regija'], true).'
		                  </select>
							<span id="dp_grad_container">
								<select id="naselje_dp" name="naselje" class="select big">
									<option value="">&mdash; Sva mjesta &mdash;</option>
                					'.print_select_menu($towns, $_GET['naselje'], true).'
                				</select>
                			</span>
		                </td>
		              </tr>
		              <tr class="white">
		                <td><label for="horoscope_dp">Horoskop</label></td>
		                <td><label for="years_from_dp">Godine (spol)</label></td>
		              </tr>
		              <tr class="gray">
		                <td><select id="horoscope_dp" name="horoscope" class="select mid">
			                '.print_select_menu($inpulls_horoscope, $_GET['horoscope'], true).'
							</select>
						</td>
		                <td><input type="text" id="years_from_dp" name="years_from" class="input_text small" maxlength="8" value="'.$_GET['years_from'].'"  /> - <input type="text" id="years_to_dp" name="years_to" class="input_text small" maxlength="8"  value="'.$_GET['years_to'].'" /> <select id="dp_sex" name="sex" class="select small">'.print_select_menu($inpulls_sex, $_GET['sex'], true).'</select></td>
		              </tr>
		              <tr class="white">
		                <td><label for="currently_im">Trenutno je</label></td>
		                <td><label for="favorite_band">Omiljeni band</label></td>
		              </tr>
		              <tr class="gray">
		                <td>
		                	<select id="currently_im" name="currently_im" class="select mid">
		                	'.print_select_menu($inpulls_currently_im, $_GET['currently_im'], true).'
		                	</select><br />

		                <td style="vertical-align:top;">
		                	<input id="favorite_band" name="favorite_band" type="text" value="'.$_GET['favorite_band'].'" class="input_text medium" />
		                	<br />
		                </td>
		              </tr>
		              <tr class="white">
		                <td><label for="activity">Aktivnosti</label></td>
		                <td><label for="hobby">Hobi</label></td>
		              </tr>
		              <tr class="gray">
		                <td><input type="text" id="activity" name="activity" class="input_text mid" maxlength="2" value="'.$_GET['activity'].'"  /></td>
		                <td><input type="text" id="hobby" name="hobby" class="input_text mid" maxlength="2" value="'.$_GET['hobby'].'" /></td>
		              </tr>
		              <tr class="white">
		                <td><label for="eye_color">Boja očiju</label></td>
		                <td><label for="hair_color">Boja kose</label></td>
		              </tr>
		              <tr class="gray">
		                <td><input type="text" id="eye_color" name="eye_color" class="input_text mid" maxlength="2" value="'.$_GET['eye_color'].'"  /></td>
		                <td><input type="text" id="hair_color" name="hair_color" class="input_text mid" maxlength="2"  value="'.$_GET['hair_color'].'" /></td>
		              </tr>
		              </table>
		              <table id="search_table">
		               <tr class="white">
		                <td colspan="2"><label>Glazba</label></td>
		              </tr>
		              <tr class="gray">
		                <td colspan="2">
<span><input type="checkbox" name="music_1" id="music_1" value="1" '.($checked = (get_inpulls_flag($iprofile->music, 1)) ? 'checked="checked"' : '').' /> <label for="music_1">'.$inpulls_music[1].'</label></span>
<span><input type="checkbox" name="music_2" id="music_2" value="2" '.($checked = (get_inpulls_flag($iprofile->music, 2)) ? 'checked="checked"' : '').'/> <label for="music_2">'.$inpulls_music[2].'</label></span>
<span><input type="checkbox" name="music_3" id="music_3" value="4" '.($checked = (get_inpulls_flag($iprofile->music, 4)) ? 'checked="checked"' : '').'/> <label for="music_3">'.$inpulls_music[4].'</label></span>
<span><input type="checkbox" name="music_4" id="music_4" value="8" '.($checked = (get_inpulls_flag($iprofile->music, 8)) ? 'checked="checked"' : '').'/> <label for="music_4">'.$inpulls_music[8].'</label></span>
<span><input type="checkbox" name="music_5" id="music_5" value="16" '.($checked = (get_inpulls_flag($iprofile->music, 16)) ? 'checked="checked"' : '').'/> <label for="music_5">'.$inpulls_music[16].'</label></span>
<span><input type="checkbox" name="music_6" id="music_6" value="32" '.($checked = (get_inpulls_flag($iprofile->music, 32)) ? 'checked="checked"' : '').'/> <label for="music_6">'.$inpulls_music[32].'</label></span>
<span><input type="checkbox" name="music_7" id="music_7" value="64" '.($checked = (get_inpulls_flag($iprofile->music, 64)) ? 'checked="checked"' : '').'/> <label for="music_7">'.$inpulls_music[64].'</label></span>
<span><input type="checkbox" name="music_8" id="music_8" value="128" '.($checked = (get_inpulls_flag($iprofile->music, 128)) ? 'checked="checked"' : '').'/> <label for="music_8">'.$inpulls_music[128].'</label></span>
<span><input type="checkbox" name="music_9" id="music_9" value="256" '.($checked = (get_inpulls_flag($iprofile->music, 256)) ? 'checked="checked"' : '').'/> <label for="music_9">'.$inpulls_music[256].'</label></span>
<span><input type="checkbox" name="music_10" id="music_10" value="512" '.($checked = (get_inpulls_flag($iprofile->music, 512)) ? 'checked="checked"' : '').'/> <label for="music_10">'.$inpulls_music[512].'</label></span>
<span><input type="checkbox" name="music_11" id="music_11" value="1024" '.($checked = (get_inpulls_flag($iprofile->music, 1024)) ? 'checked="checked"' : '').'/> <label for="music_11">'.$inpulls_music[1024].'</label></span>
		                </td>
		              </tr>
		              <tr class="white">
		                <td colspan="2"><label>Omiljena cuga</label></td>
		              </tr>
		              <tr class="gray">
		                <td colspan="2">
<span><input type="checkbox" id="drink_1" name="drink_1" value="1" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 1)) ? 'checked="checked"' : '').' /> <label for="drink_1">'.$inpulls_drinks[1].'</label></span>
<span><input type="checkbox" id="drink_2" name="drink_2" value="2" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 2)) ? 'checked="checked"' : '').'/> <label for="drink_2">'.$inpulls_drinks[2].'</label></span>
<span><input type="checkbox" id="drink_3" name="drink_3" value="4" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 4)) ? 'checked="checked"' : '').'/> <label for="drink_3">'.$inpulls_drinks[4].'</label></span>
<span><input type="checkbox" id="drink_4" name="drink_4" value="8" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 8)) ? 'checked="checked"' : '').'/> <label for="drink_4">'.$inpulls_drinks[8].'</label></span>
<span><input type="checkbox" id="drink_5" name="drink_5" value="16" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 16)) ? 'checked="checked"' : '').'/> <label for="drink_5">'.$inpulls_drinks[16].'</label></span>
<span><input type="checkbox" id="drink_6" name="drink_6" value="32" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 32)) ? 'checked="checked"' : '').'/> <label for="drink_6">'.$inpulls_drinks[32].'</label></span>
<span><input type="checkbox" id="drink_7" name="drink_7" value="64" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 64)) ? 'checked="checked"' : '').'/> <label for="drink_7">'.$inpulls_drinks[64].'</label></span>
<span><input type="checkbox" id="drink_8" name="drink_8" value="128" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 128)) ? 'checked="checked"' : '').'/> <label for="drink_8">'.$inpulls_drinks[128].'</label></span>
<span><input type="checkbox" id="drink_9" name="drink_9" value="256" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 256)) ? 'checked="checked"' : '').'/> <label for="drink_9">'.$inpulls_drinks[256].'</label></span>
<span><input type="checkbox" id="drink_10" name="drink_10" value="512" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 512)) ? 'checked="checked"' : '').'/> <label for="drink_10">'.$inpulls_drinks[512].'</label></span>
<span><input type="checkbox" id="drink_11" name="drink_11" value="1024" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 1024)) ? 'checked="checked"' : '').'/> <label for="drink_11">'.$inpulls_drinks[1024].'</label></span>
<span><input type="checkbox" id="drink_12" name="drink_12" value="2048" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 2048)) ? 'checked="checked"' : '').'/> <label for="drink_12">'.$inpulls_drinks[2048].'</label></span>
		                </td>
		              </tr>
		              </table>
		              <table id="search_table">
		              <tr class="white">
		                <td colspan="2"><label for="br_korisnika">Korisnički broj</label></td>
		              </tr>
		              <tr class="gray">
		                <td colspan="2"><input value="'.$_GET['br_korisnika'].'" type="text" id="br_korisnika" name="br_korisnika" class="input_text mid"   /></td>
		              <tr class="big_space">
		                <td colspan="2"><label for="dp_poredak2">Poredaj po</label></td>
		              </tr>
		              <tr class="gray">
		                <td>
		                  <select name="poredak" id="dp_poredak2" class="mid">
		                    <option value="online">Online: prvo online</option>
		                    <option value="offline">Online: prvo offline</option>
		                    <option value="popular_more">Popularnosti: prvo više popularni</option>
		                    <option value="popular_less">Popularnosti: prvo manje popularni</option>
		                    <option value="date_older">Datumu: prvo najstariji</option>
		                    <option value="date_newer">Datumu: prvo najnoviji</option>
		                  </select>
		                </td>
		                <td><label for="sa_slikom2" class="normal"><input type="checkbox" name="sa_slikom" id="sa_slikom2" value="1" '.($checked = ($_GET['sa_slikom']) ? 'checked="checked"' : '').' /> <span class="normal">Prikaži samo korisnike sa slikom</span></label></td>
		              </tr>
		              <tr class="gray">
		                <td><label for="sa_videom2" class="normal"><input type="checkbox" name="sa_videom" id="sa_videom2" value="1" '.($checked = ($_GET['sa_videom']) ? 'checked="checked"' : '').' /> <span class="normal">Prikaži samo korisnike sa videom</span></label></td>
		                <td><label for="sa_kartom2" class="normal"><input type="checkbox" name="sa_kartom" id="sa_kartom2" value="1" '.($checked = ($_GET['sa_kartom']) ? 'checked="checked"' : '').' /> <span class="normal">Prikaži samo korisnike sa kartom</span></label></td>
		              </tr>
		              <tr>
		                <td><input id="submit_dp" name="submit_dp" type="submit" value="Traži" class="bttn" /></td>
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