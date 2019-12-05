<?php

	$user = $_GET['user'];

	$username = $pr->get_username($_SESSION['user.r']['id']);
	$username = $username['username'];

	if(!isset($_GET['user']) && get_is_member()) {
		redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.peoplering&sp=gallery&user=' . $username);
	}
	elseif(!get_is_member()) {
		redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.peoplering');
	}

	include_once DOC_ROOT.'/orbicon/class/inc.mmedia.php';

	$orbicon_x->set_page_title(_L('pr-gall') . ' ' . $user);

	if(isset($_POST['submit'])) {

		require_once DOC_ROOT . '/orbicon/venus/class.venus.php';
		$venus = new Venus();

		if(validate_upload($_FILES['pic1']['tmp_name'], $_FILES['pic1']['name'], $_FILES['pic1']['size'], $_FILES['pic1']['error'])) {
			$file = $venus->_insert_image_to_db($_FILES['pic1']['name'], $_FILES['pic1']['tmp_name'], "pring_u_$user");
			photogallery_img_size_fix($file);
			$venus->watermark_image(DOC_ROOT . '/site/venus/' . $file, DOC_ROOT . '/site/gfx/watermark.png');
		}

		if(validate_upload($_FILES['pic2']['tmp_name'], $_FILES['pic2']['name'], $_FILES['pic2']['size'], $_FILES['pic2']['error'])) {
			$file = $venus->_insert_image_to_db($_FILES['pic2']['name'], $_FILES['pic2']['tmp_name'], "pring_u_$user");
			photogallery_img_size_fix($file);
			$venus->watermark_image(DOC_ROOT . '/site/venus/' . $file, DOC_ROOT . '/site/gfx/watermark.png');
		}

		if(validate_upload($_FILES['pic3']['tmp_name'], $_FILES['pic3']['name'], $_FILES['pic3']['size'], $_FILES['pic3']['error'])) {
			$file = $venus->_insert_image_to_db($_FILES['pic3']['name'], $_FILES['pic3']['tmp_name'], "pring_u_$user");
			photogallery_img_size_fix($file);
			$venus->watermark_image(DOC_ROOT . '/site/venus/' . $file, DOC_ROOT . '/site/gfx/watermark.png');
		}

		if(validate_upload($_FILES['vid']['tmp_name'], $_FILES['vid']['name'], $_FILES['vid']['size'], $_FILES['vid']['error'])) {

			$ext = get_extension($_FILES['vid']['name']);
			if(($ext != 'jpg') && ($ext != 'bmp') && ($ext != 'gif') && ($ext != 'png')) {

				require_once DOC_ROOT . '/orbicon/mercury/class.mercury.php';
				$mercury = new Mercury();

				$vid = $mercury->insert_file_into_db($_FILES['vid']['name'], true, $_FILES['vid']['tmp_name'], false, "pring_u_$user");
				$mercury = null;

				global $orbx_mod;
				if($orbx_mod->validate_module('inpulls')) {
					require_once DOC_ROOT . '/orbicon/modules/inpulls/inc.inpulls.php';

					edit_inpulls_video($pr->get_prid_from_rid($pr->get_id_from_username($user)), $vid);
				}
			}
		}

		$venus = null;
	}

	$display_content = print_image_gallery("pring_u_$user");

	if(!$display_content) {
		$display_content = _L('pr-gall-nopic');
	}

	if(($user == $username) && get_is_member()) {

		$form = '
		<div id="pring_gallery">
		<h3>'._L('pr-gall-welcome').'</h3>
		<p>'.sprintf(_L('pr-gall-msg'), '<strong>'.byte_size(get_php_ini_bytes(ini_get('post_max_size'))).'</strong>').'</p>
			<form enctype="multipart/form-data" method="post" action="" id="upload_pring_gallery">
			<table style="width:100%">
			<tr>
				<td style="width:33%"><label class="pic" for="pic1">'._L('pr-gall-pic').' 1.</label></td>
				<td><input type="file" id="pic1" name="pic1" /></td>
			</tr>
			<tr>
				<td><label class="pic" for="pic2">'._L('pr-gall-pic').' 2.</label></td>
				<td><input type="file" id="pic2" name="pic2" /></td>
			</tr>
			<tr>
				<td><label class="pic" for="pic3">'._L('pr-gall-pic').' 3.</label></td>
				<td><input type="file" id="pic3" name="pic3" /></td>
			</tr>
			<tr>
				<td><label class="vid" for="vid">'._L('pr-gall-vid').'</label></td>
				<td><input type="file" id="vid" name="vid" /></td>
			</tr>
			</table>
			<br />
			<input type="submit" value="OK" id="submit" name="submit" />
			</form>
		</div>';

		$display_content = $display_content . $form;
	}

?>