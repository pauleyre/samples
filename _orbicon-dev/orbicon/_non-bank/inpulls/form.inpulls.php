<?php

	require_once DOC_ROOT . '/orbicon/modules/inpulls/inc.inpulls.php';

	include_once DOC_ROOT . '/orbicon/modules/inpulls.groups/inc.groups.php';

	$id_chk = inpulls_reg_scan_success($_SESSION['user.r']['pring_id']);

	if(!$id_chk) {
		inpulls_reg_iprofile_insert($_SESSION['user.r']['pring_id']);
	}

	// * do submision if requested
	if(isset($_POST['submit_inpulls'])){
		edit_inpulls_profile($_SESSION['user.r']['pring_id']);
	}

	$pr_id = $pr->get_prid_from_rid($_SESSION['user.r']['id']);
	$iprofile = get_iprofile_from_pring($pr_id);

	$lat = floatval($iprofile->latitude);
	$lon = floatval($iprofile->logitude);

	$lat = empty($lat) ? 45.796255 : $lat;
	$lon = empty($lon) ? 15.954895 : $lon;

	// male
	if($_SESSION['user.r']['contact_sex'] == 0) {
		$boys = '
	<p>
		<label for="treat_girls">Djevojku tretiram</label><br />
		<select name="treat_girls" id="treat_girls">'.print_select_menu($inpulls_treat_girls, $iprofile->treat_girls, true).'</select>
	</p>

	<p>
		<label for="had_girls">Imao sam dosad</label><br />
		<select name="had_girls" id="had_girls">'.print_select_menu($inpulls_had_girls, $iprofile->had_girls, true).'</select>
	</p>

	<p>
		<label for="crazy_thing_for_girls">Najluđe što sam dosad (ili što bi) napravio za djevojku?</label><br />
		<input type="text" class="txtFld" name="crazy_thing_for_girls" id="crazy_thing_for_girls" value="'.$iprofile->crazy_thing_for_girls.'" />
	</p>

	<p>
		<label for="shopping_with_girl">Jesi spreman ići sa djevojkom u &quot;kratki&quot; shopping da te lijepo zamoli? 5 puta tjedno?</label><br />

		<select name="shopping_with_girl" id="shopping_with_girl">'.print_select_menu($inpulls_go_shopping, $iprofile->shopping_with_girl, true).'</select>
	</p>

	<p>
		<label for="monthly_income">Mjesečna primanja</label><br />
		<input type="text" class="txtFld" name="monthly_income" id="monthly_income" value="'.$iprofile->monthly_income.'" />
	</p>

	<p>
		<label for="special_skills">Dodatne vještine kojima se ponosiš</label><br />
		<select name="special_skills" id="special_skills">'.print_select_menu($inpulls_special_skills, $iprofile->special_skills, true).'"</select>
	</p>

	<p>
		<label for="you_in_future">Kakvim se vidiš u budućnosti?</label><br />
		<select name="you_in_future" id="you_in_future">'.print_select_menu($inpulls_see_in_future, $iprofile->you_in_future, true).'</select>
	</p>

	<p>
		<label for="message_for_future_girl">Poruka za potencijalnu djevojku</label><br />
		<input type="text" class="txtFld" name="message_for_future_girl" id="message_for_future_girl" value="'.$iprofile->message_for_future_girl.'" />
	</p>';
	}
	// female
	elseif ($_SESSION['user.r']['contact_sex'] == 1) {

		$girls = '
	<p>
		<label for="when_i_was_little">Kad sam bila mala htjela sam biti</label><br />
		<select name="when_i_was_little" id="when_i_was_little">'.print_select_menu($inpulls_when_i_was_little, $iprofile->when_i_was_little, true).'</select>
	</p>

	<p>
		<label for="all_day">Da mogu po cijele dane samo bi</label><br />
		<select name="all_day" id="all_day">'.print_select_menu($inpulls_if_i_could, $iprofile->all_day, true).'</select>
	</p>

	<p>
		<label for="from_boyfriend">Od dečka očekujem</label><br />
		<select name="from_boyfriend" id="from_boyfriend">'.print_select_menu($inpulls_from_boyfriend, $iprofile->from_boyfriend, true).'</select>
	</p>

	<p>
		<label for="monthly_income">Mjesečna primanja</label><br />
		<input type="text" class="txtFld" name="monthly_income" id="monthly_income" value="'.$iprofile->monthly_income.'" />
	</p>

	<p>
		<label for="special_skills_girls">Dodatne vještine zbog kojih se ponosim</label><br />
		<select name="special_skills_girls" id="special_skills_girls">'.print_select_menu($inpulls_special_skills_girl, $iprofile->special_skills_girls, true).'</select>
	</p>

	<p>
		<label for="message_for_future_boy">Poruka za potencijalnog dečka</label><br />
		<input type="text" class="txtFld" name="message_for_future_boy" id="message_for_future_boy" value="'.$iprofile->message_for_future_boy.'" />
	</p>';

	}

	$display_content = '
<script type="text/javascript" src="./orbicon/controler/gzip.server.php?file=/orbicon/modules/inpulls/inpulls.js&amp;'.ORBX_BUILD.'"></script>
<script type="text/javascript"><!-- // --><![CDATA[
	YAHOO.util.Event.addListener(window, "load", function () {
		group_autocomplete("tags", "tags");
		group_autocomplete("music", "music");
	});
// ]]></script>

<form method="post" action="" id="edit_profile" enctype="multipart/form-data">
<input type="hidden" name="id" id="id" value="'.$pr_id.'" />
<input type="hidden" id="frmLat" name="latitude" value="'.$iprofile->latitude.'" />
<input type="hidden" id="frmLon" name="longitude" value="'.$iprofile->logitude.'" />


<p>
<input type="submit" name="submit_inpulls" id="submit_inpulls" value="'._L('save').'" />
</p>

<fieldset><legend>'._L('pr-comp-info').'</legend>
	<div class="left">
		<p class="im_here_for">
			<label for="im_here_for">Ovdje sam jer tražim... </label><br />
			<select id="im_here_for" name="im_here_for">
				'.print_select_menu($inpulls_im_here_for, $iprofile->im_here_for, true).'
			</select>
		</p>

		<p class="currently_im">
			<label for="currently_im">Trenutno sam... </label><br />
			<select id="currently_im" name="currently_im">
				'.print_select_menu($inpulls_currently_im, $iprofile->currently_im, true).'
			</select>
		</p>

		<p class="sex_group">
			<label for="sex_group">Spolno oprijedeljenje</label><br />
			<select id="sex_group" name="sex_group">
				'.print_select_menu($inpulls_sex_group, $iprofile->sex_group, true).'
			</select>
		</p>

		<p>
			<label for="life_moto">Životni moto</label><br />
			<input type="text" class="txtFld" name="life_moto" id="life_moto" value="'.$iprofile->life_moto.'" />
		</p>

	<p>
		<label for="im_proud_of">Ponosim se...</label><br />
		<input type="text" class="txtFld" name="im_proud_of" id="im_proud_of" value="'.$iprofile->im_proud_of.'" />
	</p>

	<p>
		<label for="life_hero">Životni uzor (osim Pervana)</label><br />
		<input type="text" class="txtFld" name="life_hero" id="life_hero" value="'.$iprofile->life_hero.'" />
	</p>

	<p>
		<label for="activities">Aktivnosti</label><br />
		<input type="text" class="txtFld" name="activities" id="activities" value="'.$iprofile->activities.'" />
	</p>

	<p>
		<label for="hobby">Hobi</label><br />
		<input type="text" class="txtFld" name="hobby" id="hobby" value="'.$iprofile->hobby.'" />
	</p>

	<p>
		<label for="horoscope">Što si po horoskopu?</label><br />
		<select id="horoscope" name="horoscope">
				'.print_select_menu($inpulls_horoscope, $iprofile->horoscope, true).'
			</select>
	</p>

	</div>
	<div class="left">

	<p>
		<label for="eye_color">Boja očiju</label><br />
		<input type="text" class="txtFld" name="eye_color" id="eye_color" value="'.$iprofile->eye_color.'" />
	</p>

	<p>
		<label for="hair_color">Boja kose</label><br />
		<input type="text" class="txtFld" name="hair_color" id="hair_color" value="'.$iprofile->hair_color.'" />
	</p>

	<p>
		<label for="what_attracts_you_most">Najprivlačnije kod druge osobe?</label><br />
		<input type="text" class="txtFld" name="what_attracts_you_most" id="what_attracts_you_most" value="'.$iprofile->what_attracts_you_most.'" />
	</p>

	<p>
		<label for="best_physical_feature">Tvoja najbolja fizička osobina</label><br />
		<input type="text" class="txtFld" name="best_physical_feature" id="best_physical_feature" value="'.$iprofile->best_physical_feature.'" />
	</p>
	<p>
		<label for="hair_length">Duljina kose</label><br />
		<input type="text" class="txtFld" name="hair_length" id="hair_length" value="'.$iprofile->hair_length.'" />
	</p>

	<p>
		<label for="height">Visina (cure, bez štikli molimo)</label><br />
		<input style="width: 70% !important" type="text" class="txtFld" name="height" id="height" maxlength="4" size="4" value="'.$iprofile->height.'" /> m
	</p>

	<p>
		<label for="weight">Težina (ono otprilike)</label><br />
		<input style="width: 70% !important" type="text" class="txtFld" name="weight" maxlength="3" size="3" id="weight" value="'.$iprofile->weight.'" /> kg
	</p>

	<p>
		<label for="tattoo_piercings">Tetovaže / piercing</label><br />
		<input type="text" class="txtFld" name="tattoo_piercings" id="tattoo_piercings" value="'.$iprofile->tattoo_piercings.'" />
	</p>

	<p>
		<label for="smoker">Cigarete</label><br />
		<select id="smoker" name="smoker">
				'.print_select_menu($inpulls_smoker, $iprofile->smoker, true).'
			</select>
	</p>

	</div>
	<div class="cleaner"></div>
</fieldset>

<p class="more_info"><label for="more_info">Nešto o meni što nećete saznati iz ovih glupih pitanja</label></p>
<p class="more_info"><textarea name="more_info" id="more_info">'.$iprofile->more_info.'</textarea></p>

<p>
	<label for="group_memberships">Članstvo u grupama</label><br />
	'.print_users_groups($_SESSION['user.r']['id']).'
</p>

<p>
	<a href="javascript:void(null);" class="tooltip"><em><span>Tagovi su ključne riječi koje služe za kratki opis profila. Više tagova odvojite zarezom. Npr. studentica, u vezi</span></em></a> <label for="tags">Tagovi</label><br />
	<input type="text" class="txtFld" name="tags" id="tags" value="'.$iprofile->tags.'" />
</p>

<fieldset class="music">

<p>Glazba</p>
<span><input type="checkbox" name="music_1" id="music_1" value="1" '.($checked = (get_inpulls_flag($iprofile->music, 1)) ? 'checked="checked"' : '').' /> <label for="music_1">'.$inpulls_music[1].'</label></span>
<span><input type="checkbox" name="music_2" id="music_2" value="2" '.($checked = (get_inpulls_flag($iprofile->music, 2)) ? 'checked="checked"' : '').'/> <label for="music_2">'.$inpulls_music[2].'</label></span>
<span><input type="checkbox" name="music_3" id="music_3" value="4" '.($checked = (get_inpulls_flag($iprofile->music, 4)) ? 'checked="checked"' : '').'/> <label for="music_3">'.$inpulls_music[4].'</label></span>
<span><input type="checkbox" name="music_4" id="music_4" value="8" '.($checked = (get_inpulls_flag($iprofile->music, 8)) ? 'checked="checked"' : '').'/> <label for="music_4">'.$inpulls_music[8].'</label></span>
<span><input type="checkbox" name="music_5" id="music_5" value="16" '.($checked = (get_inpulls_flag($iprofile->music, 16)) ? 'checked="checked"' : '').'/> <label for="music_5">'.$inpulls_music[16].'</label></span>
<span><input type="checkbox" name="music_6" id="music_6" value="32" '.($checked = (get_inpulls_flag($iprofile->music, 32)) ? 'checked="checked"' : '').'/> <label for="music_6">'.$inpulls_music[32].'</label></span><br />
<span><input type="checkbox" name="music_7" id="music_7" value="64" '.($checked = (get_inpulls_flag($iprofile->music, 64)) ? 'checked="checked"' : '').'/> <label for="music_7">'.$inpulls_music[64].'</label></span>
<span><input type="checkbox" name="music_8" id="music_8" value="128" '.($checked = (get_inpulls_flag($iprofile->music, 128)) ? 'checked="checked"' : '').'/> <label for="music_8">'.$inpulls_music[128].'</label></span>
<span><input type="checkbox" name="music_9" id="music_9" value="256" '.($checked = (get_inpulls_flag($iprofile->music, 256)) ? 'checked="checked"' : '').'/> <label for="music_9">'.$inpulls_music[256].'</label></span>
<span><input type="checkbox" name="music_10" id="music_10" value="512" '.($checked = (get_inpulls_flag($iprofile->music, 512)) ? 'checked="checked"' : '').'/> <label for="music_10">'.$inpulls_music[512].'</label></span>
<span><input type="checkbox" name="music_11" id="music_11" value="1024" '.($checked = (get_inpulls_flag($iprofile->music, 1024)) ? 'checked="checked"' : '').'/> <label for="music_11">'.$inpulls_music[1024].'</label></span>

</fieldset>

<fieldset class="drinks">

<p>Omiljena cuga</p>

<span><input type="checkbox" id="drink_1" name="drink_1" value="1" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 1)) ? 'checked="checked"' : '').' /> <label for="drink_1">'.$inpulls_drinks[1].'</label></span>
<span><input type="checkbox" id="drink_2" name="drink_2" value="2" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 2)) ? 'checked="checked"' : '').'/> <label for="drink_2">'.$inpulls_drinks[2].'</label></span>
<span><input type="checkbox" id="drink_3" name="drink_3" value="4" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 4)) ? 'checked="checked"' : '').'/> <label for="drink_3">'.$inpulls_drinks[4].'</label></span>
<span><input type="checkbox" id="drink_4" name="drink_4" value="8" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 8)) ? 'checked="checked"' : '').'/> <label for="drink_4">'.$inpulls_drinks[8].'</label></span>
<span><input type="checkbox" id="drink_5" name="drink_5" value="16" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 16)) ? 'checked="checked"' : '').'/> <label for="drink_5">'.$inpulls_drinks[16].'</label></span>
<span><input type="checkbox" id="drink_6" name="drink_6" value="32" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 32)) ? 'checked="checked"' : '').'/> <label for="drink_6">'.$inpulls_drinks[32].'</label></span>
<span><input type="checkbox" id="drink_7" name="drink_7" value="64" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 64)) ? 'checked="checked"' : '').'/> <label for="drink_7">'.$inpulls_drinks[64].'</label></span>
<span><input type="checkbox" id="drink_8" name="drink_8" value="128" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 128)) ? 'checked="checked"' : '').'/> <label for="drink_8">'.$inpulls_drinks[128].'</label></span><br />
<span><input type="checkbox" id="drink_9" name="drink_9" value="256" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 256)) ? 'checked="checked"' : '').'/> <label for="drink_9">'.$inpulls_drinks[256].'</label></span>
<span><input type="checkbox" id="drink_10" name="drink_10" value="512" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 512)) ? 'checked="checked"' : '').'/> <label for="drink_10">'.$inpulls_drinks[512].'</label></span>
<span><input type="checkbox" id="drink_11" name="drink_11" value="1024" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 1024)) ? 'checked="checked"' : '').'/> <label for="drink_11">'.$inpulls_drinks[1024].'</label></span>
<span><input type="checkbox" id="drink_12" name="drink_12" value="2048" '.($checked = (get_inpulls_flag($iprofile->favorite_drinks, 2048)) ? 'checked="checked"' : '').'/> <label for="drink_12">'.$inpulls_drinks[2048].'</label></span>

</fieldset>

<fieldset>

	<p>
		<label for="heritage">Od kud su tvoji?</label><br />
		<input type="text" class="txtFld" name="heritage" id="heritage" value="'.$iprofile->heritage.'" />
	</p>

		<p>
		<label for="favorite_food">Omiljena hrana</label><br />
		<input type="text" class="txtFld" name="favorite_food" id="favorite_food" value="'.$iprofile->favorite_food.'" />
	</p>

	<p>
		<label for="favorite_book">Omiljena knjiga</label><br />
		<input type="text" class="txtFld" name="favorite_book" id="favorite_book" value="'.$iprofile->favorite_book.'" />
	</p>

	<p>
		<label for="favorite_movie">Omiljeni film</label><br />
		<input type="text" class="txtFld" name="favorite_movie" id="favorite_movie" value="'.$iprofile->favorite_movie.'" />
	</p>

	<p>
		<label for="favorite_actor">Omiljeni glumac/ica</label><br />
		<input type="text" class="txtFld" name="favorite_actor" id="favorite_actor" value="'.$iprofile->favorite_actor.'" />
	</p>

	<p>
		<label for="favorite_band">Omiljeni band</label><br />
		<input type="text" class="txtFld" name="favorite_band" id="favorite_band" value="'.$iprofile->favorite_band.'" />
	</p>

	<p>
		<label for="favorite_song">Omiljena pjesma</label><br />
		<input type="text" class="txtFld" name="favorite_song" id="favorite_song" value="'.$iprofile->favorite_song.'" />
	</p>

	' . $boys . $girls . '

	<p><a href="javascript:void(null);" class="tooltip"><em><span>Koristite + za približavanje odnosno - za udaljavanje. Lokacija je označena kada se pojavi plavi marker</span></em></a> Gdje sam trenutno</p>

	<!--- g maps -->

			<div id="google_map_container">
				<div id="map" style="height: 400px"></div>
			</div>

				<script src="http://maps.google.com/maps?file=api&v=2&key=ABQIAAAAlv-tQTaTwI9UJQ8CUFylZBRzttjvWDbkBfQsi2JalZVQQoKWgxSukfkSuB3SA83xZtYVZtg2DfHYtw" type="text/javascript"></script>
<script type="text/javascript"><!-- // --><![CDATA[

	var setLat = '.$lat.';
	var setLon = '.$lon.';
	var marker = null;

	setIcon();

	if	(argItems("address") != "") {
	myAddress = unescape(argItems("address"));
	document.getElementById("address").value = myAddress;

} else if (argItems("lat") == "" || argItems("lon") == "") {
	placeMarker(setLat, setLon);
	} else {
	var setLat = parseFloat( argItems("lat") );
	var setLon = parseFloat( argItems("lon") );
	setLat = setLat.toFixed(6);
	setLon = setLon.toFixed(6);
	placeMarker(setLat, setLon);
}

//]]></script>

				<!-- g maps  -->

</fieldset>

<p>
	<input type="submit" name="submit_inpulls" id="submit_inpulls2" value="'._L('save').'" />
</p>

</form>';

?>