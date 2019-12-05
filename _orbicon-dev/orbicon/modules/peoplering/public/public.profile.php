<?php

if(isset($_GET['user'])){

	$uid = $pr->get_id_from_username($_GET['user']);

	$content 		= $pr->get_profile($pr->get_prid_from_rid($uid));

	if($content['private']) {
		return _L('pr-private_profile');
	}

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
	$fax 			= $content['contact_fax'];

	// * sex
	$male_chk 		= ($content['contact_sex'] == 0) ? 'checked="checked"' : '';
	$female_chk 	= ($content['contact_sex'] == 1) ? 'checked="checked"' : '';

	$html = '
<fieldset><legend>'._L('pr-profile').'</legend>
	<div class="left">
		<p>
			<label for="name">'._L('forms-name').'</label><br />
			<input type="text" class="txtFld" name="name" id="name" value="'.$name.'" />
		</p>
		<p>
			<label for="dob">'._L('pr-dob').' (<span>'._L('pr-dob-format').'</span>)</label><br />
			<input type="text" class="txtFld" name="dob" id="dob" value="'.$dob.'" />
		</p>
		<p>
			<label for="position">'._L('pr-position').'</label><br />
			<input type="text" class="txtFld" name="position" id="position" value="'.$position.'" />
		</p>
		<p>
			<label for="sex">'._L('pr-sex').'</label><br />
			<input type="radio" name="sex" id="m" '.$male_chk.' value="0" /> <label for="m">'._L('pr-male').'</label>
			<input type="radio" name="sex" id="f" '.$female_chk.' value="1" /> <label for="f">'._L('pr-female').'</label>
		</p>
	</div>
	<div class="left">
		<p>
			<label for="surname">'._L('pr-surname').'</label><br />
			<input type="text" class="txtFld" name="surname" id="surname" value="'.$surname.'" />
		</p>
		<p>
			<label for="office">'._L('pr-office').'</label><br />
			<input type="text" class="txtFld" name="office" id="office" value="'.$office.'" />
		</p>
		<p>
			<label for="expertise">'._L('pr-expertise').'</label><br />
			<input type="text" class="txtFld" name="expertise" id="expertise" value="'.$expertise.'" />
		</p>
	</div>
	<div class="cleaner"></div>
</fieldset>
<br />
<fieldset><legend>'._L('pr-contact-info').'</legend>
	<div class="left">
		<p>
			<label for="address">'._L('pr-address').'</label><br />
			<input type="text" class="txtFld" name="address" id="address" value="'.$address.'" />
		</p>
		<p>
			<label for="zip">'._L('pr-zip').'</label><br />
			<input type="text" class="txtFld" name="zip" id="zip" value="'.$zip.'" />
		</p>
		<p>
			<label for="email">'._L('pr-comp-mail').'</label><br />
			<input type="text" class="txtFld" name="email" id="email" value="'.$email.'" />
		</p>
		<p>
			<label for="fax">'._L('pr-fax').'</label><br />
			<input type="text" class="txtFld" name="fax" id="fax" value="'.$fax.'" />
		</p>
	</div>
	<div class="left">
		<p>
			<label for="city">'._L('pr-city').'</label><br />
			<input type="text" class="txtFld" name="city" id="city" value="'.$city.'" />
		</p>
		<p>
			<label for="url">'._L('pr-url').'</label><br />
			<input type="text" class="txtFld" name="url" id="url" value="'.$url.'" />
		</p>
		<p>
			<label for="phone">'._L('pr-phone').'</label><br />
			<input type="text" class="txtFld" name="phone" id="phone" value="'.$phone.'" />
		</p>
		<p>
			<label for="gsm">'._L('pr-gsm').'</label><br />
			<input type="text" class="txtFld" name="gsm" id="gsm" value="'.$gsm.'" />
		</p>
	</div>
	<div class="cleaner"></div>
</fieldset>';

	return $html;

}
?>