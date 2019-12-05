<?php

	require DOC_ROOT . '/orbicon/modules/credit-calculator/inc.credit.calc.php';

	delete_credit();
	if(isset($_POST['save_cred'])) {
		save_credit();
	}

	// load credit
	if(isset($_GET['edit'])) {
		$my_credit = load_credit($_GET['edit']);
	}

?>
<form method="post" action="">
	<p>
		<label for="credit_title"><?php echo _L('credit_title'); ?></label><br />
		<input id="credit_title" name="credit_title" value="<?php echo $my_credit['title']; ?>"  />
	</p>
	<p>
		<label for="interest"><?php echo _L('interest_rate'); ?></label><br />
		<input id="interest" name="interest" value="<?php echo $my_credit['interest']; ?>" />
	</p>
	<p>
		<label for="max_years"><?php echo _L('max_years'); ?></label><br />
		<input id="max_years" name="max_years" value="<?php echo $my_credit['max_years']; ?>" />
	</p>
	<input type="submit" id="save_cred" name="save_cred" value="<?php echo _L('save'); ?>" />
	<input <?php if(!isset($_GET['edit'])) {echo 'disabled="disabled"';} ?> type="button" value="<?php echo _L('add_new') ?>" onclick="javascript: redirect('<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/credit-calculator'; ?>');"  />
</form>
<br />
<br />
<fieldset>
<legend><?php echo _L('credits'); ?></legend>
<?php

	echo print_credit_list();

?>
</fieldset>