<?php


if(isset($_GET['user'])){

	$uid = $pr->get_id_from_username($_GET['user']);

	$prid = $pr->get_prid_from_rid($uid);

	$content 		= $pr->get_cv($prid);

	if($pr->get_is_private_profile($prid)) {
		return _L('pr-private_profile');
	}

	// * general
	$name 			= $content['cvname'];
	$pob	 		= $content['placeofbirth'];
	$county			= $content['county'];
	$doe	 		= $content['doe'];
	$cob	 		= $content['countryofbirth'];
	$country 		= $content['country'];
	$yoe	 		= $content['yoe'];
	$education 		= $content['education'];
	$past_jobs 		= $content['pastjobs'];
	$rest	 		= $content['rest'];

	// * en
	switch($content['eng']){

		case 0: $en_a = ' checked="checked"';
				$en_p = '';
				$en_x = '';
				break;

		case 1: $en_a = '';
				$en_p = ' checked="checked"';
				$en_x = '';
				break;

		case 2: $en_a = '';
				$en_p = '';
				$en_x = ' checked="checked"';
				break;
	}
	// * de
	switch($content['ger']){

		case 0: $de_a = ' checked="checked"';
				$de_p = '';
				$de_x = '';
				break;

		case 1: $de_a = '';
				$de_p = ' checked="checked"';
				$de_x = '';
				break;

		case 2: $de_a = '';
				$de_p = '';
				$de_x = ' checked="checked"';
				break;
	}
	// * fr
	switch($content['fre']){

		case 0: $fr_a = ' checked="checked"';
				$fr_p = '';
				$fr_x = '';
				break;

		case 1: $fr_a = '';
				$fr_p = ' checked="checked"';
				$fr_x = '';
				break;

		case 2: $fr_a = '';
				$fr_p = '';
				$fr_x = ' checked="checked"';
				break;
	}
	// * it
	switch($content['ita']){

		case 0: $it_a = ' checked="checked"';
				$it_p = '';
				$it_x = '';
				break;

		case 1: $it_a = '';
				$it_p = ' checked="checked"';
				$it_x = '';
				break;

		case 2: $it_a = '';
				$it_p = '';
				$it_x = ' checked="checked"';
				break;
	}


	// * skills
	$mng_chk 		= ($content['gotmanagerskills'] == 1) ? ' checked="checked"' : '';
	$dl_chk  		= ($content['dlic'] == 1) ? ' checked="checked"' : '';
	$comp_chk  		= ($content['complementary'] == 1) ? ' checked="checked"' : '';
	$dlicmore 		= $content['dlicmore'];
	$passive 		= $content['otherpassive'];
	$active			= $content['otheractive'];
	$capabilities 	= $content['capabilities'];
	$achievements 	= $content['achievements'];

	include_once DOC_ROOT . '/orbicon/modules/forms/class.form.php';
	$form = new Form;
	$doe_menu = $form->get_pring_db_table('pring_doe', false, 'id', $doe);

	$html = '
<fieldset><legend>'._L('pr-cv').'</legend>
	<div class="left">
		<p>
			<label for="name">'._L('forms-cv-title').'</label><br />
			<input type="text" class="txtFld" name="name" id="name" value="'.$name.'" />
		</p>
		<p>
			<label for="pob">'._L('forms-pob').'</label><br />
			<input type="text" class="txtFld" name="pob" id="pob" value="'.$pob.'" />
		</p>
		<p>
			<label for="county">'._L('forms-county').'</label><br />
			<input type="text" class="txtFld" name="county" id="county" value="'.$county.'" />
		</p>
		<p>
			<label for="doe">'._L('forms-doe').'</label><br />
			<select id="doe" name="doe">
				'.$doe_menu.'
			</select>
		</p>
	</div>
	<div class="left">
		<p>
			<label for="cob">'._L('forms-cob').'</label><br />
			<input type="text" class="txtFld" name="cob" id="cob" value="'.$cob.'" />
		</p>
		<p>
			<label for="country">'._L('forms-country').'</label><br />
			<input type="text" class="txtFld" name="country" id="country" value="'.$country.'" />
		</p>
		<p>
			<label for="yoe">'._L('forms-yoe').'</label><br />
			<input type="text" class="txtFld" name="yoe" id="yoe" value="'.$yoe.'" />
		</p>
	</div>
	<div class="cleaner"></div>
	<p>
		<label for="education">'._L('pr-education').'</label><br />
		<textarea id="education" name="education">'.$education.'</textarea>
	</p>
	<p>
		<label for="past_jobs">'._L('forms-jobs').'</label><br />
		<textarea id="past_jobs" name="past_jobs">'.$past_jobs.'</textarea>
	</p>
	<p>
		<label for="rest">'._L('forms-other').'</label><br />
		<textarea id="rest" name="rest">'.$rest.'</textarea>
	</p>
</fieldset>
<br />
<fieldset><legend>'._L('pr-languages').'</legend>
	<h2>'._L('pr-general-lang-know').'</h2>
	<table id="basic_langs">
		<tr>
			<td align="left" width="50%"><strong>'._L('pr-english').'</strong></td>
			<td align="center">
				<input type="radio" value="0" name="en" id="en_p"'.$en_p.' />
				<label for="en_p">'._L('pr-passive').'</label>
			</td>
			<td align="center">
				<input type="radio" value="1" name="en" id="en_a"'.$en_a.' />
				<label for="en_a">'._L('pr-active').'</label>
			</td>
			<td align="center">
				<input type="radio" value="2" name="en" id="en_x"'.$en_x.' />
				<label for="en_x">'._L('pr-neither').'</label>
			</td>
		</tr>
		<tr>
			<td align="left"><strong>'._L('pr-german').'</strong></td>
			<td align="center">
				<input type="radio" value="0" name="de" id="de_p"'.$de_p.' />
				<label for="de_p">'._L('pr-passive').'</label>
			</td>
			<td align="center">
				<input type="radio" value="1" name="de" id="de_a"'.$de_a.' />
				<label for="de_a">'._L('pr-active').'</label>
			</td>
			<td align="center">
				<input type="radio" value="2" name="de" id="de_x"'.$de_x.' />
				<label for="de_x">'._L('pr-neither').'</label>
			</td>
		</tr>
		<tr>
			<td align="left"><strong>'._L('pr-italian').'</strong></td>
			<td align="center">
				<input type="radio" value="0" name="it" id="it_p"'.$it_p.' />
				<label for="it_p">'._L('pr-passive').'</label>
			</td>
			<td align="center">
				<input type="radio" value="1" name="it" id="it_a"'.$it_a.' />
				<label for="it_a">'._L('pr-active').'</label>
			</td>
			<td align="center">
				<input type="radio" value="2" name="it" id="it_x"'.$it_x.' />
				<label for="it_x">'._L('pr-neither').'</label>
			</td>
		</tr>
		<tr>
			<td align="left"><strong>'._L('pr-french').'</strong></td>
			<td align="center">
				<input type="radio" value="0" name="fr" id="fr_p"'.$fr_p.' />
				<label for="fr_p">'._L('pr-passive').'</label>
			</td>
			<td align="center">
				<input type="radio" value="1" name="fr" id="fr_a"'.$fr_a.' />
				<label for="fr_a">'._L('pr-active').'</label>
			</td>
			<td align="center">
				<input type="radio" value="2" name="fr" id="fr_x"'.$fr_x.' />
				<label for="fr_x">'._L('pr-neither').'</label>
			</td>
		</tr>
	</table>
	<div class="cleaner"></div>
	<p>
		<label for="passive">'._L('pr-other-langs-pass').'</label><br />
		<textarea id="passive" name="passive">'.$passive.'</textarea>
	</p>
	<p>
		<label for="active">'._L('pr-other-langs-act').'</label><br />
		<textarea id="active" name="active">'.$active.'</textarea>
	</p>
</fieldset>
<br />
<fieldset><legend>'._L('pr-skills').'</legend>
	<div class="left">
		<p>
			<input type="checkbox" value="1" name="manager_skills" id="manager_skills"'.$mng_chk.' />
			<label for="manager_skills">'._L('pr-manager-skills').'</label>
		</p>
		<p>
			<input type="checkbox" value="1" name="complementary" id="complementary" />
			<label for="complementary">'._L('forms-complement').'</label>
		</p>
	</div>
	<div class="left">
		<p>
			<input type="checkbox" value="1" name="dlic" id="dlic"'.$dl_chk.' />
			<label for="dlic">'._L('pr-drive-lic').'</label>
		</p>
	</div>
	<div class="cleaner"></div>
	<p>
		<label for="dlicmore">'._L('pr-drive-lic-note').'</label><br />
		<textarea id="dlicmore" name="dlicmore">'.$dlicmore.'</textarea>
	</p>
	<p>
		<label for="capabilities">'._L('pr-capabilities').'</label><br />
		<textarea id="capabilities" name="capabilities">'.$capabilities.'</textarea>
	</p>
	<p>
		<label for="achievements">'._L('forms-achiev').'</label><br />
		<textarea id="achievements" name="achievements">'.$achievements.'</textarea>
	</p>
</fieldset>';

	return $html;
}
?>