<?php

	// * do submision if requested
	if(isset($_POST['submit_comp'])){
		$cmp_edit = new Peoplering($_POST);
		$cmp_edit->update_company_info();
	}

	$content = $pr->get_company($_GET['id']);
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
	$intro 			= $content['intro_text'];

	// logo uploaded
	if(isset($_POST['upload'])) {

		if(validate_upload($_FILES['logo']['tmp_name'], $_FILES['logo']['name'], $_FILES['logo']['size'], $_FILES['logo']['error'])) {

			list($width, $height, $type, $attr) = getimagesize($_FILES['logo']['tmp_name']);
			if(($width <= 300) && ($height <= 300)) {
				require_once DOC_ROOT . '/orbicon/venus/class.venus.php';
				$venus = new Venus;
				$file = $venus->_insert_image_to_db($_FILES['logo']['name'], $_FILES['logo']['tmp_name'], 'pring_c_logo');
				$venus = null;

				$pr->set_logo($content['id'], $file);
				// update logo var
				$logo = $file;
			}
		}
	}

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

?>
<script type="text/javascript"><!-- // --><![CDATA[

	YAHOO.util.Event.addListener(window,"load",start_magister_mb);

	function start_magister_mb() {
		/* lead text */
		__magister_mini_input = '<?php echo $intro; ?>';
		__magister_mini_url = '<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon&ajax_text_db&action=txt';
		__magister_mini_update_list();

		switch_mini_browser('magister', '', 0, 0);
	}

// ]]></script>
<form method="post" action="" id="edit_profile" enctype="multipart/form-data">
<input type="hidden" name="id" id="id" value="<?php echo $_GET['id'];?>" />
<input id="content_text" name="content_text" type="hidden" />
<p>
<input type="submit" name="submit_comp" id="submit_comp" value="<?php echo _L('pr-save-company');?>" />
</p>
<fieldset><legend><?php echo _L('pr-comp-info');?></legend>
	<div class="left">
		<p>
			<label for="title_comp"><?php echo _L('pr-title');?></label><br />
			<input type="text" class="txtFld" name="title_comp" id="title_comp" value="<?php echo $title_comp;?>" />
		</p>
		<p>
			<label for="address"><?php echo _L('pr-address');?></label><br />
			<input type="text" class="txtFld" name="address" id="address" value="<?php echo $address;?>" />
		</p>
		<p>
			<label for="city"><?php echo _L('pr-city');?></label><br />
			<input type="text" class="txtFld" name="city" id="city" value="<?php echo $city;?>" />
		</p>
		<p>
			<label for="zip"><?php echo _L('pr-zip');?></label><br />
			<input type="text" name="zip" id="zip" value="<?php echo $zip;?>" />
		</p>
		<p>
			<label for="mb"><?php echo _L('pr-comp-mb');?></label><br />
			<input type="text" class="txtFld" name="mb" id="mb" value="<?php echo $mb;?>" />
		</p>
	</div>
	<div class="left">
		<p>
			<label for="phone"><?php echo _L('pr-phone'); ?></label><br />
			<input type="text" size="3" name="phone_a" id="phone_a" value="<?php echo $phone_a;?>" />
			<input type="text" size="2" name="phone_b" id="phone_b" value="<?php echo $phone_b;?>" />
			<input type="text" name="phone" id="phone" value="<?php echo $phone;?>" />

		</p>
		<p>
			<label for="fax"><?php echo _L('pr-fax'); ?></label><br />
			<input type="text" class="txtFld" name="fax" id="fax" value="<?php echo $fax;?>" />
		</p>
		<p>
			<label for="mail"><?php echo _L('pr-comp-mail'); ?></label><br />
			<input type="text" class="txtFld" name="mail" id="mail" value="<?php echo $mail;?>" />
		</p>
		<p>
			<label for="url"><?php echo _L('pr-url'); ?></label><br />
			<input type="text" class="txtFld" name="url" id="url" value="<?php echo $url;?>" />
		</p>
		<p>
			<label for="industry_a"><?php echo _L('pr-industry');?> #1</label><br />
			<select class="txtFld" name="industry_a" id="industry_a" value="<?php echo $url; ?>">
				<option value="">&mdash;</option>
				<?php echo print_select_menu($industries, $industry_a, true); ?>
			</select><br />

			<label for="industry_b"><?php echo _L('pr-industry');?> #2</label><br />
			<select class="txtFld" name="industry_b" id="industry_b" value="<?php echo $url; ?>">
				<option value="">&mdash;</option>
				<?php echo print_select_menu($industries, $industry_b, true); ?>
			</select><br />

			<label for="industry_c"><?php echo _L('pr-industry');?> #3</label><br />
			<select class="txtFld" name="industry_c" id="industry_c" value="<?php echo $url; ?>">
				<option value="">&mdash;</option>
				<?php echo print_select_menu($industries, $industry_c, true); ?>
			</select><br />

		</p>
	</div>
	<div class="cleaner"></div>
</fieldset>
<br />
<fieldset>
	<legend><?php echo _L('pr-comp-logo'); ?></legend>
	<table style="width:100%">
		<tr>
			<td style="width:50%"><img src="<?php echo $logo; ?>" alt="<?php echo $title_comp; ?>" title="<?php echo $title_comp; ?>" /><td>
			<td>
				<p>
					<?php echo _L('pr-logo-upload-msg'); ?>
				</p>

				<p>
				<?php echo _L('pr-max-width'); ?> : 300px<br />
				<?php echo _L('pr-max-height'); ?> : 300px
				</p>
					<input id="logo" name="logo" type="file" /> <input name="upload" id="upload" type="submit" value="OK" />
			</td>
		<tr>
	</table>
</fieldset><br />
<fieldset>
	<legend><label for="content_text"><?php echo _L('subtitle'); ?></label></legend>
	<div id="news_content" style=" height: 150px; overflow:auto; width:auto;background:#ffffff;border:1px solid #cccccc;"></div>
</fieldset>
</form>