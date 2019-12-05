<?php
$fnd = new Fond();



if(isset($_POST['submit_fond'])){

	$fondObj = new  Fond($_POST);

	if(isset($_GET['id'])){
		$fondObj->edit_fond();
	} else {
		$fondObj->set_fond();
	}

}

if(isset($_GET['id'])){

	$fondRes = $fnd->get_fond($_GET['id']);
	$editFond = $dbc->_db->fetch_array($fondRes);

	$checked = ($editFond['state'] == 0) ? '' : ' checked="checked"';

	$chkFrontPage = ($editFond['frontpage'] == 0) ? '' : ' checked="checked"';

}


?>
<form name="fond_form" id="fond_form" method="post" action="">
<input type="hidden" name="id" id="id" value="<?php echo $_GET['id']; ?>" />
<p>
	<label for="title"><?php echo _L('invest-fond-title');?></label><br />
	<input type="text" name="title" id="title" value="<?php echo $editFond['title'];?>" />
</p>
<div class="half_view">
	<p>
		<input type="checkbox" id="frontpage" name="frontpage" value="1" class="chk_btn"<?php echo $chkFrontPage;?> />
		<label for="frontpage"><?php echo _L('invest-fond-frontpage');?></label>
	</p>
</div>
<div class="cleaner"></div>
<div class="quater_view">
	<p>
		<label for="currency"><?php echo _L('invest-fond-currency');?></label><br />
		<select id="currency" name="currency">
		<?php
			$curr = new Currency();

			$tmp_curr_res = $curr->get_all_currencies(1);
			while($currency = $dbc->_db->fetch_array($tmp_curr_res)){
				if(isset($_GET['id'])){
					$selected = ($currency['id'] == $editFond['currency']) ? ' selected="selected"' : '';
				} else {
					$selected = '';
				}

				echo '<option value="'.$currency['id'].'"'.$selected.'>'.$currency['title'].'</option>';

			}
		?>
		</select>
	</p>
</div>
<div class="quater_view">
	<p>
		<label for="min_entry"><?php echo _L('invest-fond-min');?></label><br />
		<input type="text" id="min_entry" name="min_entry" value="<?php echo $editFond['min_entry'];?>" maxlength="12" class="short_input" />
	</p>
</div>
<div class="quater_view">
	<p>
		<label for="entry_fee"><?php echo _L('invest-fond-fee');?></label><br />
		<input type="text" id="entry_fee" name="entry_fee" value="<?php echo $editFond['entry_fee'];?>" maxlength="6" class="short_input" /> %
	</p>
</div>
<div class="quater_view">
<p>
	<label for="state"><?php echo _L('invest-fond-status');?></label><br />
		<select id="state" name="state">
		<?php

			$sel_act 	= '';
			$sel_inact	= '';

			if($editFond['state'] == 0) {
				$sel_act = ' selected="selected"';
			} else {
				$sel_inact = ' selected="selected"';
			}
			echo '<option value="0"'.$sel_act.'>'._L('invest-fond-inactive').'</option>';
			echo '<option value="1"'.$sel_inact.'>'._L('invest-fond-active').'</option>';

		?>
		</select>
</p>
</div>
<div class="cleaner"></div>
<p>
	<input type="submit" name="submit_fond" id="submit_fond" value="<?php echo _L('save');?>" class="chk_btn" />
</p>
</form>
<table id="fond_listing">
	<tr>
		<th width="10">#</th>
		<th width="80%"><?php echo _L('invest-env-title');?></th>
		<th width="10%"><?php echo _L('invest-env-state');?></th>
	</tr>
<?php


$fond_res = $fnd->get_all_fonds();

while($fond = $dbc->_db->fetch_array($fond_res)){

	$state = ($fond['state'] == '1') ? _L('invest-fond-active') : _L('invest-fond-inactive');

	echo '
	<tr>
		<td>'.$fond['id'].'</td>
		<td>
			<a href="?'.$orbicon_x->ptr.'=orbicon/mod/invest&amp;showPage=fond&amp;do=newfond&amp;id='.$fond['id'].'">'.$fond['title'].'</a></td>
		<td>'.$state.'</td>
	</tr>
	';

}
?>
</table>