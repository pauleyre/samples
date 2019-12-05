<?php

	// * object
	$stock_obj = new Stock();

	// * show list of current month values from desired fond
	$fond_to_display = (isset($_GET['fond'])) ? $_GET['fond'] : 1;
	$list_resource = $stock_obj->get_all_entries($fond_to_display);

	if(isset($_POST['delete_overview_entry'])|| isset($_POST['delete_overview_entry'])){

		if(isset($_POST['del'])){
			$stock_obj->remove_stock_values($_POST['del']);
		} else {
			$error = _L('invest-empty-select');
		}

	}


?>
<script type="text/javascript"><!-- // --><![CDATA[
function confirmRemoval()
{
	var agree = confirm('<?php echo _L('invest-confirm');?>');
	return agree;

}
// ]]></script>
<form id="fund_val_overview" method="post" action="" onsubmit="javascript: return confirmRemoval();">
<p>
	<input type="submit" id="delete_overview_entry" name="delete_overview_entry" value="<?php echo _L('invest-overview-delete');?>" />
</p>
<table id="fond_values">
	<caption style="color: red;"><?if(isset($error)) { echo ''.$error.'';}?></caption>
	<tr>
		<th width="30%" align="left"><?php echo _L('invest-overview-fond');?></th>
		<th width="30%"><?php echo _L('invest-overview-date');?></th>
		<th width="20%"><?php echo _L('invest-overview-value');?></th>
		<th width="20%"><?php echo _L('invest-overview-currency');?></th>
	</tr>
	<?php

		if($dbc->_db->num_rows($list_resource) > 0) {
			$stock_element = $dbc->_db->fetch_array($list_resource);

			$i = 0;
			while($stock_element) {

				$tmp_curr = new Currency();
				$curr = $tmp_curr->get_currency($stock_element['currency']);
				$currency = $dbc->_db->fetch_array($curr);

				$high = ($i%2 == 0) ? ' class="high"' : '';

				// * display list of items
				echo '
					<tr>
						<td'.$high.'>
							<input type="checkbox" id="del[]" name="del[]" value="'.$stock_element['id'].'" />
							<a href="?'.$orbicon_x->ptr.'=orbicon/mod/invest&amp;showPage=fond&amp;do=fondvalue&amp;id='.$stock_element['id'].'" name="'.$stock_element['title'].'" title="'.$stock_element['title'].'">
								'.$stock_element['title'].'
							</a>
						</td>
						<td align="center"'.$high.'>'.date($_SESSION['site_settings']['date_format'], $stock_element['uDate']).'</td>
						<td align="center"'.$high.'><a href="?'.$orbicon_x->ptr.'=orbicon/mod/invest&amp;showPage=fond&amp;do=fondvalue&amp;id='.$stock_element['id'].'" name="'.$stock_element['stock_value'].'" title="'.$stock_element['stock_value'].'">'.$stock_element['stock_value'].'</a></td>
						<td align="center"'.$high.'>'.$currency['title'].'</td>
					</tr>
				';
				$i++;
				$stock_element = $dbc->_db->fetch_array($list_resource);
			}
		} else {
			echo '
				<tr>
					<td colspan="4">'._L('invest-overview-info').'</td>
				</tr>
			';
		}
	?>
</table>
<p>
	<input type="submit" id="delete_overview_entry_bottom" name="delete_overview_entry_bottom" value="<?php echo _L('invest-overview-delete');?>" />
</p>
</form>