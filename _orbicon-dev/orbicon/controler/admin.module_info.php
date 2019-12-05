<table cellpadding="0" border="0" cellspacing="0" style="font-size: 11px;"><tbody>
<tr>
	<th>#</th>
	<th><?php echo _L('module'); ?></th>
	<th><?php echo _L('localized_name'); ?></th>
	<th><?php echo _L('runtime_implementation'); ?></th>
	<th><?php echo _L('author'); ?></th>
	<th><?php echo _L('version'); ?></th>
	<th><?php echo _L('created_date'); ?></th>
	<th><?php echo _L('last_mod'); ?></th>
</tr>

<?php

	$i = 1;

	foreach($orbx_mod->all_modules as $module) {


		$module = $orbx_mod->_trim_mod_name($module);
		$props = $orbx_mod->load_info($module);

		if(!empty($props)) {

			$style = (($i % 2) == 0) ? ' style="background:#ffffff;"' : '';

			switch($props['module']['runtime']) {
				case 'box': $rnt = _L('box'); break;
				case 'page': $rnt = _L('page'); break;
				default: $rnt = _L('none');
			}

			$updated = (empty($props['about']['updated'])) ? _L('none') : $props['about']['updated'];

			echo '
			<tr '.$style.'>
				<td>'.$i.'</td>
				<td>'.htmlspecialchars($props['module']['name']).'</td>
				<td>'._L($module).'</td>
				<td>'.$rnt.'</td>
				<td>'.htmlspecialchars($props['about']['author']).'</td>
				<td>'.$props['about']['version'].'</td>
				<td>'.$props['about']['date'].'</td>
				<td>'.$updated.'</td>
			</tr>';
			$i ++;
		}
	}

?>

</tbody></table>