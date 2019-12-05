<?php


if(isset($_GET['user'])){

	$uid = $pr->get_id_from_username($_GET['user']);
	$prid = $pr->get_prid_from_rid($uid);

	$content 		= $pr->get_company($prid);

	if($pr->get_is_private_profile($prid)) {
		return _L('pr-private_profile');
	}

	// * general
	$title_comp 	= $content['title'];
	$address 		= $content['address'];
	$city 			= $content['city'];
	$zip 			= $content['zip'];
	$mb 			= $content['mb'];
	$url 			= $content['url'];
	$mail 			= $content['mail'];
	$phone 			= $content['phone'];
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
		$logo = '<img src="' . ORBX_SITE_URL.'/site/venus/' . $logo . '" alt="'.$title_comp.'" title="'.$title_comp.'" />';
	}

	$html = $logo . '
<fieldset><legend>'._L('pr-company').'</legend>
	<div class="left">
		<p>
			<label for="title_comp">'._L('pr-title').'</label><br />
			<input type="text" class="txtFld" name="title_comp" id="title_comp" value="'.$title_comp.'" />
		</p>
		<p>
			<label for="address">'._L('pr-address').'</label><br />
			<input type="text" class="txtFld" name="address" id="address" value="'.$address.'" />
		</p>
		<p>
			<label for="city">'._L('pr-city').'</label><br />
			<input type="text" class="txtFld" name="city" id="city" value="'.$city.'" />
		</p>
		<p>
			<label for="zip">'._L('pr-zip').'</label><br />
			<input type="text" class="txtFld" name="zip" id="zip" value="'.$zip.'" />
		</p>
		<p>
			<label for="mb">'._L('pr-comp-mb').'</label><br />
			<input type="text" class="txtFld" name="mb" id="mb" value="'.$mb.'" />
		</p>
	</div>
	<div class="left">
		<p>
			<label for="phone">'._L('pr-phone').'</label><br />
			<input type="text" class="txtFld" name="phone" id="phone" value="'.$phone.'" />
		</p>
		<p>
			<label for="fax">'._L('pr-fax').'</label><br />
			<input type="text" class="txtFld" name="fax" id="fax" value="'.$fax.'" />
		</p>
		<p>
			<label for="mail">'._L('pr-comp-mail').'</label><br />
			<input type="text" class="txtFld" name="mail" id="mail" value="'.$mail.'" />
		</p>
		<p>
			<label for="url">'._L('pr-url').'</label><br />
			<input type="text" class="txtFld" name="url" id="url" value="'.$url.'" />
		</p>
		<p>
			<label for="industry_a">'._L('pr-industry').' #1</label><br />
			<select class="txtFld" name="industry_a" id="industry_a" value="'. $url.'">
			<option value="">&mdash;</option>
			'. print_select_menu($industries, $industry_a, true).'
			</select><br />

			<label for="industry_b">'. _L('pr-industry').' #2</label><br />
			<select class="txtFld" name="industry_b" id="industry_b" value="'. $url.'">
			<option value="">&mdash;</option>
			'. print_select_menu($industries, $industry_b, true).'
			</select><br />

			<label for="industry_c">'. _L('pr-industry').' #3</label><br />
			<select class="txtFld" name="industry_c" id="industry_c" value="'. $url.'">
			<option value="">&mdash;</option>
			'. print_select_menu($industries, $industry_c, true).'
			</select><br />

		</p>
	</div>
	<div class="cleaner"></div>
</fieldset>';

	return $html;
}
?>