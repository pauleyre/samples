<?php

	if(isset($_GET['promoid'])) {

		if(isset($_POST['submit_promo'])){
			// * update info
			$_POST['content'] = $_POST['elm1'];
			$upd_promo = new Promo($_POST);
			$upd_promo->update_promo($_GET['id']);
		}

		$p = new Promo();
		$promo = $dbc->_db->fetch_assoc($p->get_promo($_GET['id'], $_GET['promoid']));

		// * state
		$state = ($promo['state'] == 1) ? ' checked="checked"' : '';
	}
	else {
		if(isset($_POST['submit_promo'])){
			// * update info
			$_POST['content'] = $_POST['elm1'];
			$update_promo = new Promo($_POST);
			$update_promo->set_promo();

			redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/peoplering&sp=extra&id='.$_GET['id']);
		}
	}

?>
<form id="promo_text" method="post" action="" onsubmit="javascript: RichTextSave();">
<p>
	<input type="submit" name="submit_promo" id="submit_promo" value="<?php echo _L('ic-save');?>" />
	<input type="hidden" name="id" id="id" value="<?php echo $_GET['id'];?>" />
	<input id="content" name="content" type="hidden" />
</p>
<fieldset><legend><?php echo _L('pr-promo'); ?></legend>
<p>
	<label for="title"><?php echo _L('title')?></label><br />
	<input type="text" name="title" id="title" class="txtFld" value="<?php echo $promo['title'];?>" />
</p>
<p>
	<input type="checkbox" id="state" name="state" value="1" <?php echo $state; ?> />
	<label for="state"><?php echo _L('pr-active')?></label>
</p>
<br />
<style type="text/css">/*<![CDATA[*/
	@import url("<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rich_text_editor.css");
/*]]>*/</style>
<?php 
	require_once DOC_ROOT . '/orbicon/rte/rte_components/toolbar.php';
		/*if(isset($_GET['promoid'])){

			echo "
				<script type=\"text/javascript\" language=\"javascript\">
					function setEditText() {
						var content = '".addslashes(str_sanitize($promo['textual'], STR_SANITIZE_JAVASCRIPT))."';
						oToolbar.body.innerHTML = content;
						// * load db browser
						switch_mini_browser('venus', '', 0, 0);
					}

					YAHOO.util.Event.addListener(window, 'load', setTimeout(setEditText, 1000));

				</script>";
		}*/
	?>
</fieldset>
</form>