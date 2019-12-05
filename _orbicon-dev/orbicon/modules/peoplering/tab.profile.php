<?php


	// * do submision if requested
	if(isset($_POST['submit_contact'])){

		$pr_edit = new Peoplering($_POST);
		$pr_edit->update_profile();

	}

	$content 		= $pr->get_profile($_GET['id']);

	// * get username & password
	$cred 			= $pr->get_username($pr->get_rid_from_prid($_GET['id']));
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
	$credits 		= $content['credits'];

	// * sex
	$male_chk 		= (!$content['contact_sex']) ? 'checked="checked"' : '';
	$female_chk 	= ($content['contact_sex']) ? 'checked="checked"' : '';

	$private 		= ($content['private']) ? ' checked="checked"' : '';

	$picture = $content['picture'];

	$estate_agency_status = ($content['estate_agency_status']) ? ' checked="checked"' : '';

	if($dob) {
		$dob = date('d.m.Y', $dob);
		list($day, $month, $year) = explode('.', $dob);
	}

	// picture uploaded
	if(isset($_POST['upload'])) {

		if(validate_upload($_FILES['picture']['tmp_name'], $_FILES['picture']['name'], $_FILES['picture']['size'], $_FILES['picture']['error'])) {

			list($width, $height, $type, $attr) = getimagesize($_FILES['picture']['tmp_name']);
			if(($width <= 200) && ($height <= 200)) {
				require_once DOC_ROOT . '/orbicon/venus/class.venus.php';
				$venus = new Venus;
				$file = $venus->_insert_image_to_db($_FILES['picture']['name'], $_FILES['picture']['tmp_name'], 'pring_avatar');
				$venus = null;

				$pr->set_picture($file, $_GET['id']);

				// update picture var
				$picture = $file;
			}
		}
	}

	if(is_file(DOC_ROOT . '/site/venus/' . $picture)) {
		$picture = ORBX_SITE_URL.'/site/venus/' . $picture;
	}
	else {
		$picture = ORBX_SITE_URL.'/orbicon/modules/peoplering/gfx/unknownUser.gif';
	}

?>

<form method="post" action="" id="edit_profile" enctype="multipart/form-data">
<input type="hidden" name="id" id="id" value="<?php echo $_GET['id'];?>" />
<p>
<input type="submit" name="submit_contact" id="submit_contact" value="<?php echo _L('pr-save-profile');?>" />
</p>
<fieldset><legend><?php echo _L('pr-profile');?></legend>
	<div class="left">
		<p>
			<label for="name"><?php echo _L('pr-name');?></label><br />
			<input type="text" class="txtFld" name="name" id="name" value="<?php echo $name;?>" />
		</p>
		<p>
			<label for="dob_d"><?php echo _L('pr-dob'); ?></label><br />
			<select name="dob_d" id="dob_d"><?php echo print_select_menu(range(1, 31), $day); ?></select>.
			<select name="dob_m" id="dob_m"><?php echo print_select_menu(range(1, 12), $month); ?></select>.
			<select name="dob_y" id="dob_y"><?php echo print_select_menu(range(date('Y'), 1850), $year); ?></select>
		</p>
		<p>
			<label for="position"><?php echo _L('pr-position');?></label><br />
			<input type="text" class="txtFld" name="position" id="position" value="<?php echo $position;?>" />
		</p>
		<p>
			<label for="username"><?php echo _L('pr-username');?></label><br />
			<input type="text" class="txtFld" name="username" id="username" value="<?php echo $username;?>" />
		</p>
		<p>
			<label for="sex"><?php echo _L('pr-sex');?></label><br />
			<input type="radio" name="sex" id="m" <?php echo $male_chk;?> value="0" /> <label for="m"><?php echo _L('pr-male');?></label>
			<input type="radio" name="sex" id="f" <?php echo $female_chk;?> value="1" /> <label for="f"><?php echo _L('pr-female'); ?></label>
		</p>
	</div>
	<div class="left">
		<p>
			<label for="surname"><?php echo _L('pr-surname');?></label><br />
			<input type="text" class="txtFld" name="surname" id="surname" value="<?php echo $surname; ?>" />
		</p>
		<p>
			<label for="office"><?php echo _L('pr-office');?></label><br />
			<input type="text" class="txtFld" name="office" id="office" value="<?php echo $office; ?>" />
		</p>
		<p>
			<label for="expertise"><?php echo _L('pr-expertise');?></label><br />
			<input type="text" class="txtFld" name="expertise" id="expertise" value="<?php echo $expertise; ?>" />
		</p>
		<p>
			<label for="password"><?php echo _L('pr-new-pass'); ?></label><br />
			<input type="password" class="txtFld" name="password" id="password" />
		</p>
		<p>
			<label for="private"><?php echo _L('pr-private'); ?></label><br />
			<input type="checkbox" name="private" id="private" <?php echo $private; ?> value="1" />
		</p>
		<?php

		if($orbx_mod->validate_module('estate')) {

			include DOC_ROOT . '/orbicon/modules/estate/inc.estate.php';

		?>

		<p>
			<label for="estate_agency_status"><?php echo _L('Estate agency'); ?></label><br />
			<input type="checkbox"  name="estate_agency_status" id="estate_agency_status" <?php echo $estate_agency_status; ?> value="1" />
		</p>

		<p>
			<label for="credits"><?php echo _L('pr-credit'); ?></label><br />
			<input type="text" name="credits" id="credits" value="<?php echo $credits; ?>" />
		</p>

		<p>
			<label for="estate_agency_level">Ograničenje oglasa</label><br />
			<select name="estate_agency_level" id="estate_agency_level"><?php echo print_select_menu($estate_agency_level, $content['estate_agency_level'], true); ?></select>
		</p>

		<?php
		}

		?>
	</div>
	<div class="cleaner"></div>
</fieldset>
<br />
<fieldset>
	<legend><?php echo _L('pr-pic'); ?></legend>
	<table style="width:100%">
		<tr>
			<td style="width:50%"><img src="<?php echo $picture; ?>" alt="<?php echo $username; ?>" title="<?php echo $username; ?>" /><td>
			<td>
				<p>
					<?php echo _L('pr-pic-upload-msg'); ?>
				</p>
				<p>
				<?php echo _L('pr-max-width'); ?> : 200px<br />
				<?php echo _L('pr-max-height'); ?> : 200px
				</p>
					<input id="picture" name="picture" type="file" /> <input name="upload" id="upload" type="submit" value="OK" />
			</td>
		<tr>
	</table>
</fieldset>
<br />
<fieldset><legend><?php echo _L('pr-contact-info'); ?></legend>
	<div class="left">
		<p>
			<label for="address"><?php echo _L('pr-address');?></label><br />
			<input type="text" class="txtFld" name="address" id="address" value="<?php echo $address; ?>" />
		</p>
		<p>
			<label for="zip"><?php echo _L('pr-zip');?></label><br />
			<input type="text" class="txtFld" name="zip" id="zip" value="<?php echo $zip; ?>" />
		</p>
		<p>
			<label for="email"><?php echo _L('pr-comp-mail');?></label><br />
			<input type="text" class="txtFld" name="email" id="email" value="<?php echo $email; ?>" />
		</p>
		<p>
			<label for="fax"><?php echo _L('pr-fax');?></label><br />
			<input type="text" class="txtFld" name="fax" id="fax" value="<?php echo $fax;?>" />
		</p>
	</div>
	<div class="left">
		<p>
			<label for="city"><?php echo _L('pr-city');?></label><br />
			<input type="text" class="txtFld" name="city" id="city" value="<?php echo $city;?>" />
		</p>
		<p>
			<label for="url"><?php echo _L('pr-url');?></label><br />
			<input type="text" class="txtFld" name="url" id="url" value="<?php echo $url;?>" />
		</p>
		<p>
			<label for="phone"><?php echo _L('pr-phone');?></label><br />
			<input type="text" size="3" name="phone_a" id="phone_a" value="<?php echo $phone_a;?>" />
			<input type="text" size="2" name="phone_b" id="phone_b" value="<?php echo $phone_b;?>" />
			<input type="text" name="phone" id="phone" value="<?php echo $phone;?>" />
		</p>
		<p>
			<label for="gsm"><?php echo _L('pr-gsm');?></label><br />
			<input type="text" class="txtFld" name="gsm" id="gsm" value="<?php echo $gsm;?>" />
		</p>
	</div>
	<div class="cleaner"></div>
</fieldset>
</form>