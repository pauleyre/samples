<?php

	// * do submision if requested
	if(isset($_POST['submit_comp'])){

		$cmp_edit = new Peoplering($_POST);
		$cmp_edit->update_company_info();

	}

	$pr_id = $pr->get_prid_from_rid($_SESSION['user.r']['id']);

	// logo uploaded
	if(isset($_POST['upload'])) {

		if(validate_upload($_FILES['logo']['tmp_name'], $_FILES['logo']['name'], $_FILES['logo']['size'], $_FILES['logo']['error'])) {

			list($width, $height, $type, $attr) = getimagesize($_FILES['logo']['tmp_name']);
			if(($width <= 300) && ($height <= 300)) {
				require_once DOC_ROOT . '/orbicon/venus/class.venus.php';
				$venus = new Venus;
				$file = $venus->_insert_image_to_db($_FILES['logo']['name'], $_FILES['logo']['tmp_name'], 'pring_c_logo');

				$venus = null;

				$company = $pr->get_company($pr_id);

				$pr->set_logo($company['id'], $file);

				$company = null;
			}
		}
	}

	$content 		= $pr->get_company($pr_id);

	// * general
	$title_comp 	= $content['title'];
	$address 		= $content['address'];
	$city 			= $content['city'];
	$zip 			= $content['zip'];
	$mb 			= $content['mb'];
	$url 			= $content['url'];
	$mail 			= $content['mail'];
	$phone 			= $content['phone'];
	$phone_a 		= $content['phone_a'];
	$phone_b 		= $content['phone_b'];
	$fax 			= $content['fax'];
	$logo 			= $content['logo'];
	$industry_a 	= $content['industry_a'];
	$industry_b 	= $content['industry_b'];
	$industry_c 	= $content['industry_c'];

	include_once DOC_ROOT.'/orbicon/modules/forms/class.form.php';
	$form = new Form;
	$industries = $form->get_pring_db_table('pring_industry', true);
	$form = null;

	if(is_file(DOC_ROOT . '/site/venus/' . $logo)) {
		$logo = ORBX_SITE_URL.'/site/venus/' . $logo;
	}
	else {
		$logo = ORBX_SITE_URL.'/orbicon/modules/peoplering/gfx/unknownUser.gif';
	}

	$display_content = '
<form method="post" action="" id="edit_profile" enctype="multipart/form-data">
<input type="hidden" name="id" id="id" value="'.$pr_id.'" />

<fieldset><legend>'._L('pr-comp-info').'</legend>
		<p>
			<label for="title_comp">'._L('pr-title').'</label>
			<input type="text" class="txtFld" name="title_comp" id="title_comp" value="'.$title_comp.'" />
		</p>
		<p>
			<label for="mb">'._L('pr-comp-mb').'</label>
			<input type="text" class="txtFld" name="mb" id="mb" value="'.$mb.'" />
		</p>
		<p>
			<label for="address">'._L('pr-address').'</label>
			<input type="text" class="txtFld" name="address" id="address" value="'.$address.'" />
		</p>
		<p>
			<label for="city">'._L('pr-city').'</label>
			<input type="text" class="txtFld" name="city" id="city" value="'.$city.'" />
		</p>
		<p>
			<label for="zip">'._L('pr-zip').'</label>
			<input type="text" class="txtFld" name="zip" id="zip" value="'.$zip.'" />
		</p>
	</fieldset>

	<fieldset>
		<p>
			<label for="phone">'._L('pr-phone').'</label>
			<input type="hidden" size="3" name="phone_a" id="phone_a" value="'.$phone_a.'" />
			<input type="hidden" size="2" name="phone_b" id="phone_b" value="'.$phone_b.'" />
			<input  class="txtFld" type="text" name="phone" id="phone" value="'.$phone.'" />
		</p>
		<p>
			<label for="fax">'._L('pr-fax').'</label>
			<input type="text" class="txtFld" name="fax" id="fax" value="'.$fax.'" />
		</p>
		<p>
			<label for="mail">'._L('pr-comp-mail').'</label>
			<input type="text" class="txtFld" name="mail" id="mail" value="'.$mail.'" />
		</p>
		<p>
			<label for="url">'._L('pr-url').'</label>
			<input type="text" class="txtFld" name="url" id="url" value="'.$url.'" />
		</p>
</fieldset>
<fieldset>
		<p>
			<label for="industry_a">'._L('pr-industry').' 1.</label>
			<select class="txtFld" name="industry_a" id="industry_a" value="'. $url.'">
				<option value="">&mdash;</option>
				'. print_select_menu($industries, $industry_a, true).'
			</select>
		</p>
		<p>

			<label for="industry_b">'. _L('pr-industry').' 2.</label>
			<select class="txtFld" name="industry_b" id="industry_b" value="'. $url.'">
				<option value="">&mdash;</option>
				'. print_select_menu($industries, $industry_b, true).'
			</select>
		</p>
		<p>

			<label for="industry_c">'. _L('pr-industry').' 3.</label>
			<select class="txtFld" name="industry_c" id="industry_c" value="'. $url.'">
				<option value="">&mdash;</option>
				'. print_select_menu($industries, $industry_c, true).'
			</select><br />
		</p>
</fieldset>

<fieldset class="h">
	<legend>'._L('pr-comp-logo').'</legend>
	<table style="width:100%">
		<tr>
			<td style="width:50%"><img src="'.$logo.'" alt="'.$title_comp.'" title="'.$title_comp.'" /><td>
			<td>
				<p>
					'._L('pr-logo-upload-msg').'
				</p>
				<p>
				'._L('pr-max-width').' : 300px<br />
				'._L('pr-max-height').' : 300px
				</p>
					<input id="logo" name="logo" type="file" /> <input name="upload" id="upload" type="submit" value="OK" />
			</td>
		<tr>
	</table>
</fieldset>

<p>
<input type="submit" name="submit_comp" id="submit_comp" value="'._L('save').'" />
</p>

</form>';

?>