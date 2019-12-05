<?php

/**
 * Resize image if larger than 200Kb
 *
 * @param string $file
 * @param object $venus
 * @access private
 */
function profile_img_size_fix($file, $venus)
{
	$file = DOC_ROOT . '/site/venus/' . $file;
	list($w, $h) = getimagesize($file);

	if($w > 200) {
		exec('mogrify -resize 200x ' . $file);

		//$venus->generate_thumbnail($file, $file, 640);
		update_sync_cache_list($file);
	}

/*	if(filesize($file) > 204800) {

		list($w, $h) = getimagesize($file);
		$w = intval($w * (75 / 100));
		$h = intval($h * (75 / 100));

		$venus->generate_thumbnail($file, $file, $w, $h, null, 75);
		update_sync_cache_list($file);
	}*/
}

	// * do submision if requested
	if(isset($_POST['submit_contact'])){

		$pr_edit = new Peoplering($_POST);
		$pr_edit->update_profile();

	}

	$pr_id 			= $pr->get_prid_from_rid($_SESSION['user.r']['id']);

	// picture uploaded
	if(isset($_POST['upload'])) {

		if(validate_upload($_FILES['picture']['tmp_name'], $_FILES['picture']['name'], $_FILES['picture']['size'], $_FILES['picture']['error'])) {
			require_once DOC_ROOT . '/orbicon/venus/class.venus.php';
			$venus = new Venus();

			$file = $venus->_insert_image_to_db($_FILES['picture']['name'], $_FILES['picture']['tmp_name'], 'pring_avatar');
			profile_img_size_fix($file, $venus);
			$pr->set_picture($file, $pr_id);

			//list($width, $height, $type, $attr) = getimagesize($_FILES['picture']['tmp_name']);
			/*if(($width > 200) || ($height > 200)) {
				$venus->generate_thumbnail(DOC_ROOT . '/site/venus/' . $file, DOC_ROOT . '/site/venus/' . $file, 200);
			}*/

			$venus = null;
		}
	}

	$content 		= $pr->get_profile($pr_id);

	// * get username & password
	$cred 			= $pr->get_username($_SESSION['user.r']['id']);
	$username 		= $cred['username'];

	// * general
	$name 			= $content['contact_name'];
	$surname 		= $content['contact_surname'];
	$dob 			= (!$content['contact_dob']) ? '' : $content['contact_dob'];
	$position 		= $content['contact_position'];
	$office 		= $content['contact_office'];
	$expertise 		= $content['contact_expertise'];

	// * contact
	$address 		= $content['contact_address'];
	$city 			= $content['contact_city'];
	$zip 			= $content['contact_zip'];
	$url 			= $content['contact_url'];
	$email 			= $content['contact_email'];
	$gsm 			= $content['contact_gsm'];
	$phone 			= $content['contact_phone'];
	$phone_a 		= $content['contact_phone_a'];
	$phone_b 		= $content['contact_phone_b'];
	$fax 			= $content['contact_fax'];

	// * sex
	$male_chk 		= ($content['contact_sex'] == 0) ? 'checked="checked"' : '';
	$female_chk 	= ($content['contact_sex'] == 1) ? 'checked="checked"' : '';

	$picture = $content['picture'];

	if(is_file(DOC_ROOT . '/site/venus/' . $picture)) {
		$picture = ORBX_SITE_URL.'/site/venus/' . $picture;
	}
	else {
		$picture = ORBX_SITE_URL.'/orbicon/modules/peoplering/gfx/unknownUser.gif';
	}

	if($dob) {
		$dob = date('d.m.Y', $dob);
		list($day, $month, $year) = explode('.', $dob);
	}

	include_once DOC_ROOT.'/orbicon/modules/forms/class.form.php';
	$form = new Form;
	//$doe = $form->get_pring_db_table('pring_doe', false, 'id');
	$counties = $form->get_pring_db_table('pring_counties', false, 'title', $content['contact_region']);
	$countries = $form->get_pring_db_table('pring_countries', false, 'title', $content['contact_country']);
	$towns = $form->get_pring_db_table('pring_towns', false, 'title', $content['contact_city']);
	$form = null;

	$display_content = '
<script type="text/javascript">

	//YAHOO.util.Event.addListener(window, "load", function(){switch_regional_input('.intval($content['contact_country']).');});

</script>

<form id="edit_profile" action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="id" id="id" value="'.$pr_id.'" />

                    <fieldset>
                    <legend>Profil</legend>
                      <p>
						<label for="name">'._L('pr-name').'</label>
                        <input type="text" class="txtFld" name="name" id="name" value="'.$name.'" />
                      </p>
                      <p>
                        <label for="surname">'._L('pr-surname').'</label>
                        <input type="text" class="txtFld" name="surname" id="surname" value="'.$surname.'" />
                      </p>
                      <p class="h">
                        <label for="username">'._L('pr-username').'</label>
                        <input type="text" class="txtFld" name="username" id="username" value="'.$username.'" />
                      </p>
                      <p>
                        <label for="password">'._L('pr-new-pass').'</label>
                        <input type="password" id="password" name="password" class="txtFld"/>
                      </p>
                    </fieldset>

                    <fieldset>
                    <legend>Osobni podaci</legend>
                      <p>
                        <label for="dob_d">'._L('pr-dob').'</label>
                        <select name="dob_d" id="dob_d">'.print_select_menu(range(1, 31), $day).'</select>
						<select name="dob_m" id="dob_m">'.print_select_menu(range(1, 12), $month).'</select>
						<select name="dob_y" id="dob_y">'.print_select_menu(range(date('Y'), 1900), $year).'</select>
                      </p>
                      <p class="sex">
                        <label for="sex">'._L('pr-sex').'</label>
                        <input type="radio" name="sex" id="m" '.$male_chk.' value="0" /> <label class="inline" for="m">'._L('pr-male').'</label>
						<input type="radio" name="sex" id="f" '.$female_chk.' value="1" /> <label class="inline" for="f">'._L('pr-female').'</label>
                      </p>
                    </fieldset>

                    <fieldset>
                      <p>
                        <label for="office">'._L('pr-office').'</label>
                        <input type="text" class="txtFld" name="office" id="office" value="'.$office.'" />
                      </p>
                      <p class="expertise">
                        <label for="expertise">'._L('pr-expertise').'</label>
                        <input type="text" class="txtFld" name="expertise" id="expertise" value="'.$expertise.'" />
                      </p>
                      <p class="position">
                        <label for="position">'._L('pr-position').'</label>
                        <input type="text" class="txtFld" name="position" id="position" value="'.$position.'" />
                      </p>
                    </fieldset>

                    <fieldset>
                    <legend>Kontakt informacije</legend>
                      <p>
                	       <label for="zip">'._L('pr-zip').'</label>
						   <input maxlength="5" type="text" class="txtFld small" name="zip" id="zip" value="'.$zip.'" />
                      </p>
                      <p>
                        <label for="address">'._L('pr-address').'</label>
                        <input type="text" id="address" name="address" class="txtFld"  value="'.$address.'" />
                      </p>

                     <p class="country h">
						<label for="country">'._L('pr-country').'</label>
						<select onchange="javascript: switch_regional_input(this.options[this.selectedIndex].value);" name="country" id="country">'.$countries.'</select>
					</p>


	<div id="cro_only" class="h">
		<p class="county">
			<label for="county">'._L('pr-county').'</label>
			<select onchange="javascript: switch_towns(this.options[this.selectedIndex].value, \'HR\', \'reg_city_container\', \'city\', \'city\');" name="county" id="county"><option value="">Odaberi regiju</option>'.$counties.'</select>
		</p>

		<div class="city">
			<label for="city">'._L('pr-city').'</label>
			<div id="reg_city_container"><select name="city" id="city"><option value="">Odaberi grad</option>'.$towns.'</select></div>
		</div>
	</div>

	<div id="other_only" class="s">
		<p class="city_text">
			<label for="city_text">'._L('pr-city').'</label>
			<input class="txtFld" value="'.$content['contact_town_text'].'" class="txtfield" type="text" name="city_text" id="city_text" />
		</p>
	</div>

                    </fieldset>

                    <fieldset>
                      <p>
                        <label for="email">'._L('pr-comp-mail').'</label>
                        <input type="text" id="email" name="email" class="txtFld"  value="'.$email.'"/>
                      </p>
                      <p>
                        <label for="fax">'._L('pr-fax').'</label>
                        <input type="text" id="fax" name="fax" class="txtFld"  value="'.$fax.'"/>
                      </p>

                      <p>
                        <label for="url">'._L('pr-url').'</label>
                        <input type="text" id="url" name="url" class="txtFld"  value="'.$url.'"/>
                      </p>
                      <p>
                        <label for="phone">'._L('pr-phone').'</label>
						<input type="hidden" size="3" name="phone_a" id="phone_a" value="'.$phone_a.'" />
						<input type="hidden" size="2" name="phone_b" id="phone_b" value="'.$phone_b.'" />
						<input type="text" name="phone" id="phone" value="'.$phone.'" class="txtFld" />
                      </p>
                      <p>
                       		<label for="gsm">'._L('pr-gsm').'</label>
							<input type="text" class="txtFld" name="gsm" id="gsm" value="'.$gsm.'" />
                      </p>

                    </fieldset>
                    <p><input type="submit" value="'._L('pr-save-profile').'" id="submit_contact" name="submit_contact"/></p>

<fieldset class="h">
	<legend>'._L('pr-pic').'</legend>
	<table style="width:100%">
		<tr>
			<td style="width:50%"><img src="'.$picture.'" alt="'.$username.'" title="'.$username.'" /><td>
			<td>
				<p>
					'._L('pr-pic-upload-msg').'
				</p>
				<p>
				'._L('pr-max-width').' : 200px<br />
				'._L('pr-max-height').' : 200px
				</p>
					<input id="picture" name="picture" type="file" /> <input name="upload" id="upload" type="submit" value="OK" />
			</td>
		<tr>
	</table>
</fieldset>

</form>';

?>