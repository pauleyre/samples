<?php
	// * import class files
	require_once DOC_ROOT.'/orbicon/modules/infocentar/class/category.class.php';
	$cl = new Category();

	if($_GET['edit'] == 'category'){

		// * if form has been submited update db
		if(isset($_POST['submit'])){

			$cu = new Category($_POST);
			$cu->edit_category();
			unset($_POST);
		}

		$c = new Category();

		$cat = $c->get_category($_GET['id']);
	}

	if($_POST['id'] == '' && isset($_POST['submit'])){

		// * create object category $c
		$c = new Category($_POST);
		$c->set_category();
		unset($_POST);
	}
?>
<form name="category_editor" id="category_editor" method="post" action="">
<input type="hidden" name="id" id="id" value="<?php echo $cat['id'];?>" />
<fieldset><legend><?php echo _L('ic-category-edit');?></legend>
<p>
	<label for="title"><?php echo _L('ic-category');?></label><br />
	<input type="text" name="title" id="title" value="<?php echo $cat['title'];?>" />
</p>
<p>
	<label for="desc"><?php echo _L('ic-cat-desc');?></label><br />
	<textarea id="desc" name="desc"><?php echo $cat['description'];?></textarea>
</p>
<p>
	<label for="state"><?php echo _L('ic-state');?></label>
	<select name="state" id="state">
		<?php

			// * selection for category
			$state = ($cat['state'] == 0) ? '' : ' selected="selected"';

		?>
		<option value="0"><?php echo _L('ic-inactive');?></option>
		<option value="1"<?php echo $state;?>><?php echo _L('ic-active');?></option>
	</select>
</p>
<p>
	<input type="submit" name="submit" id="submit" value="<?php echo _L('ic-save');?>" class="norm_input" />
</p>
</fieldset>
</form>

