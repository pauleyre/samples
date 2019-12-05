<?php

	require DOC_ROOT . '/orbicon/modules/exchange-rates/inc.exch_rates.php';

	if(isset($_POST['save_exch'])) {
		save_exch_rates();
	}

	$first_date = get_first_exch_date(false);
	$last_date = get_last_exch_date(false);

?>
<form method="post" action="" enctype="multipart/form-data">
	<input type="file" name="csv" id="csv" /> <input type="submit" id="save_exch" name="save_exch" value="<?php echo _L('import'); ?>" />
</form>

<div id="exch_table_container">
<?php

	print_exchange_list($last_date);

?>
</div>