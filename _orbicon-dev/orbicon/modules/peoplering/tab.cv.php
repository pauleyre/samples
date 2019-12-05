<?php


	// * do submision if requested
	if(isset($_POST['submit_cv'])){

		$cv_edit = new Peoplering($_POST);
		$cv_edit->update_cv();

	}

	$content 		= $pr->get_cv($_GET['id']);

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

	// * skills
	$eng_chk 		= ($content['eng'] == 1) ? ' checked="checked"' : '';
	$ger_chk 		= ($content['ger'] == 1) ? ' checked="checked"' : '';
	$ita_chk 		= ($content['ita'] == 1) ? ' checked="checked"' : '';
	$fre_chk 		= ($content['fre'] == 1) ? ' checked="checked"' : '';
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

?>
<form id="edit_cv" method="post" action="">
<input type="hidden" name="contact_id" id="contact_id" value="<?php echo $_GET['id'];?>" />
<p>
<input type="submit" name="submit_cv" id="submit_cv" value="<?php echo _L('pr-save-cv');?>" />
</p>
<fieldset><legend><?php echo _L('pr-general');?></legend>
	<div class="left">
		<p>
			<label for="name"><?php echo _L('pr-cv-title');?></label><br />
			<input type="text" class="txtFld" name="name" id="name" value="<?php echo $name;?>" />
		</p>
		<p>
			<label for="pob"><?php echo _L('pr-pob');?></label><br />
			<input type="text" class="txtFld" name="pob" id="pob" value="<?php echo $pob;?>" />
		</p>
		<p>
			<label for="county"><?php echo _L('pr-county');?></label><br />
			<input type="text" class="txtFld" name="county" id="county" value="<?php echo $county;?>" />
		</p>
		<p>
			<label for="doe"><?php echo _L('pr-doe');?></label><br />
			<select id="doe" name="doe">
				<?php echo $doe_menu; ?>
			</select>
		</p>
	</div>
	<div class="left">
		<p>
			<label for="cob"><?php echo _L('pr-cob');?></label><br />
			<input type="text" class="txtFld" name="cob" id="cob" value="<?php echo $cob;?>" />
		</p>
		<p>
			<label for="country"><?php echo _L('pr-country');?></label><br />
			<input type="text" class="txtFld" name="country" id="country" value="<?php echo $country;?>" />
		</p>
		<p>
			<label for="yoe"><?php echo _L('pr-yoe');?></label><br />
			<input type="text" class="txtFld" name="yoe" id="yoe" value="<?php echo $yoe;?>" />
		</p>
	</div>
	<div class="cleaner"></div>
	<p>
		<label for="education"><?php echo _L('pr-education');?></label><br />
		<textarea id="education" name="education"><?php echo $education;?></textarea>
	</p>
	<p>
		<label for="past_jobs"><?php echo _L('pr-past-jobs');?></label><br />
		<textarea id="past_jobs" name="past_jobs"><?php echo $past_jobs;?></textarea>
	</p>
	<p>
		<label for="rest"><?php echo _L('pr-extra-desc');?></label><br />
		<textarea id="rest" name="rest"><?php echo $rest;?></textarea>
	</p>
</fieldset>
<br />
<fieldset><legend><?php echo _L('pr-languages');?></legend>
	<h2><?php echo _L('pr-general-lang-know');?></h2>
	<table id="basic_langs">
		<tr>
			<td align="left" width="50%"><strong><?php echo _L('pr-english');?></strong></td>
			<td align="center">
				<input type="radio" value="0" name="en_i" id="en_i" />
				<label for="en_i"><?php echo _L('pr-passive');?></label>
			</td>
			<td align="center">
				<input type="radio" value="1" name="en_a" id="en_a" />
				<label for="en_a"><?php echo _L('pr-active');?></label>
			</td>
		</tr>
		<tr>
			<td align="left"><strong><?php echo _L('pr-german');?></strong></td>
			<td align="center">
				<input type="radio" value="0" name="de_i" id="de_i" />
				<label for="de_i"><?php echo _L('pr-passive');?></label>
			</td>
			<td align="center">
				<input type="radio" value="1" name="de_a" id="de_a" />
				<label for="de_a"><?php echo _L('pr-active');?></label>
			</td>
		</tr>
		<tr>
			<td align="left"><strong><?php echo _L('pr-italian');?></strong></td>
			<td align="center">
				<input type="radio" value="0" name="it_i" id="it_i" />
				<label for="it_i"><?php echo _L('pr-passive');?></label>
			</td>
			<td align="center">
				<input type="radio" value="1" name="it_a" id="it_a" />
				<label for="it_a"><?php echo _L('pr-active');?></label>
			</td>
		</tr>
		<tr>
			<td align="left"><strong><?php echo _L('pr-french');?></strong></td>
			<td align="center">
				<input type="radio" value="0" name="fr_i" id="fr_i" />
				<label for="fr_i"><?php echo _L('pr-passive');?></label>
			</td>
			<td align="center">
				<input type="radio" value="1" name="fr_a" id="fr_a" />
				<label for="fr_a"><?php echo _L('pr-active');?></label>
			</td>
		</tr>
	</table>
	<div class="cleaner"></div>
	<p>
		<label for="passive"><?php echo _L('pr-other-langs-pass');?></label><br />
		<textarea id="passive" name="passive"><?php echo $passive;?></textarea>
	</p>
	<p>
		<label for="active"><?php echo _L('pr-other-langs-act');?></label><br />
		<textarea id="active" name="active"><?php echo $active;?></textarea>
	</p>
</fieldset>
<br />
<fieldset><legend><?php echo _L('pr-skills');?></legend>
	<div class="left">
		<p>
			<input type="checkbox" value="1" name="manager_skills" id="manager_skills"<?php echo $mng_chk;?> />
			<label for="manager_skills"><?php echo _L('pr-manager-skills');?></label>
		</p>
		<p>
			<input type="checkbox" value="1" name="complementary" id="complementary" />
			<label for="complementary"><?php echo _L('pr-complementary');?></label>
		</p>
	</div>
	<div class="left">
		<p>
			<input type="checkbox" value="1" name="dlic" id="dlic"<?php echo $dl_chk;?> />
			<label for="dlic"><?php echo _L('pr-drive-lic');?></label>
		</p>
	</div>
	<div class="cleaner"></div>
	<p>
		<label for="dlicmore"><?php echo _L('pr-drive-lic-note');?></label><br />
		<textarea id="dlicmore" name="dlicmore"><?php echo $dlicmore;?></textarea>
	</p>
	<p>
		<label for="capabilities"><?php echo _L('pr-capabilities');?></label><br />
		<textarea id="capabilities" name="capabilities"><?php echo $capabilities;?></textarea>
	</p>
	<p>
		<label for="achievements"><?php echo _L('pr-achievements');?></label><br />
		<textarea id="achievements" name="achievements"><?php echo $achievements;?></textarea>
	</p>
</fieldset>
</form>