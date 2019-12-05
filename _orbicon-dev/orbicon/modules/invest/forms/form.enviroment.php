<?php

// * csv import
if(isset($_POST['submit_csv_file'])){

	if(isset($_POST['import_fond'])){

		$import = new Stock($_POST);

		// * import
		if($import->__import($_FILES) === true){
			$import_status .= '<br /> '._L('invest-env-msg-file').' ' . $_FILES['import_file']['name'] . ' ' . _L('invest-env-msg-success');
		} else {
			$import_status .= _L('invest-env-msg-fail');
		}
	} else {
		$import_req = '<span style="color: red;">'._L('invest-env-msg-info').'<span>';
	}
}

// * edit currency
if(isset($_POST['submit_currency'])){
	$c = new Currency($_POST);
	if(isset($_GET['id'])){
		$c->edit_currency();
	} else {
		$c->set_currency();
	}
}

// * general settigns
$fonds = new Fond();
$stock = new Stock();
$currency = new Currency();



?>
<div id="env">
<fieldset><legend><?php echo _L('invest-env-import');?></legend>
<form id="import_csv" method="post" action="" enctype="multipart/form-data">
<div class="half_view">
<p>
	<label for="import_file"><?php echo _L('invest-env-import-file');?>: </label>
	<input type="file" name="import_file" id="import_file" />
</p>
</div>
<div class="half_view">
<p>
	<label for="import_fond"><?php echo _L('invest-env-import-fond');?>:</label>
	<select id="import_fond" name="import_fond">
		<?php

			$tmp_fond_res = $fonds->get_all_fonds(1);
			while($fond = $dbc->_db->fetch_array($tmp_fond_res)){
				if(isset($_POST['import_fond'])){
					$selected = ($_POST['import_fond'] == $fond['id']) ? ' selected="selected"' : '';
				} else {
					$selected = '';
				}

				echo '<option value="'.$fond['id'].'"'.$selected.'>'.$fond['title'].'</option>';

			}
		?>
	</select>
</p>
</div>
<div class="cleaner"></div>
<br />
<p>
	<input type="submit" id="submit_csv_file" name="submit_csv_file" value="<?php echo _L('invest-env-import-btn');?>" />
	<?php if(isset($import_req)){ echo $import_req;} ?>
</p>
</form>
<?php

	if(isset($import_status)){ echo $import_status;}

?>
</fieldset>


<fieldset><legend><?php echo _L('invest-env-currency');?></legend>
<?php

if($_GET['type'] == 'currency'){
	$get_curr_res = $currency->get_currency($_GET['id']);
	$get_curr = $dbc->_db->fetch_array($get_curr_res);

}

?>
<form id="currency_form" method="post" action="">
<input type="hidden" id="currency_id" name="currency_id" value="<?php echo $_GET['id'];?>" />
<div class="quater_view">
<p>
	<label for="title"><?php echo _L('invest-env-currency-title');?></label><br />
	<input type="text" name="title" id="title" value="<?php echo $get_curr['title']?>" />
</p>
</div>
<div class="quater_view">
<p>
	<label for="state"><?php echo _L('invest-env-currency-state');?></label><br />
	<select id="state" name="state">
	<?php
		$sel_act 	= '';
		$sel_inact	= '';

		if($get_curr['state'] == 0) {
			$sel_act = ' selected="selected"';
		} else {
			$sel_inact = ' selected="selected"';
		}
		echo '<option value="0"'.$sel_act.'>'._L('invest-env-currency-inactive').'</option>';
		echo '<option value="1"'.$sel_inact.'>'._L('invest-env-currency-active').'</option>';
	?>
	</select>
</p>
</div>
<div class="cleaner"></div>
<br />
<p>
	<input type="submit" id="submit_currency" name="submit_currency" value="<?php echo _L('invest-env-currency-btn');?>" />
</p>
</form>
<br />
<table id="currency_list">
	<tr>
		<th><?php echo _L('invest-env-id');?></th>
		<th><?php echo _L('invest-env-title');?></th>
		<th><?php echo _L('invest-env-state');?></th>
	</tr>
<?php

	$i = 1;

	$curr_res =	$currency->get_all_currencies();
	while($curr = $dbc->_db->fetch_array($curr_res)){

		$curr_state = ($curr['state'] == 1) ? _L('invest-env-currency-active'): _L('invest-env-currency-inactive');

		$high = ($i%2 == 0) ? ' class="high"' : '';

		echo '
			<tr>
				<td align="center"'.$high.'>'.$curr['id'].'</td>
				<td'.$high.'><a href="?'.$orbicon_x->ptr.'=orbicon/mod/invest&showPage=settings&amp;type=currency&amp;id='.$curr['id'].'" title="Edit '.$curr['title'].'">
					'.$curr['title'].'</a></td>
				<td align="center"'.$high.'>'.$curr_state.'</td>
			</tr>
		';
		$i++;
	}

?>
</table>
</fieldset>
<fieldset><legend><?php echo _L('invest-env-export');?></legend>
<form id="export_values" method="post" action="">
<p>
	<input type="submit" id="eport_fond_values" name="eport_fond_values" value="<?php echo _L('invest-env-export-btn');?>" />
</p>
</form>
<div class="sql_export">
<?php
	if(isset($_POST['eport_fond_values'])){

		$query = $fonds->__export_fond_data();
		echo $query;

	}
?>
</div>
</fieldset>
</div>