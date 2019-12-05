<?php

	if(isset($_POST['submit'])) {
		$i = 1;
		foreach($_POST as $k=>$v) {
			if($k == 'id_' . $i) {
				$q = sprintf('	UPDATE 		'.TABLE_KWLINKS.'
								SET 		kw=%s, url=%s
								WHERE 		(id=%s)
								LIMIT 		1',
								$dbc->_db->quote($_POST['kw_' . $i]), $dbc->_db->quote($_POST['url_'  . $i]),
								$dbc->_db->quote($_POST['id_'  . $i]));								
				$dbc->_db->query($q);
				$i ++;
			}
			elseif(($k == 'new_keyws') && $v) {
				$q = sprintf('	INSERT INTO 	'.TABLE_KWLINKS.'
												(kw, url)
								VALUES 			(%s, %s)',
				$dbc->_db->quote($_POST['new_keyws']), $dbc->_db->quote($_POST['new_url']));
				$dbc->_db->query($q);
			}
		}		
	}

?>
<form method="post" action="">
<table style="width:100%">

	<tr>
		<td><strong><?php echo _L('keywords') ?></strong></td>
		<td><strong><?php echo _L('target_url'); ?></strong></td>
	</tr>
<?php

	$q = 'SELECT * FROM ' . TABLE_KWLINKS;
	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_assoc($r);
	$i = 1;

	while($a) {

		echo '
	<tr>
		<td>
			<input type="hidden" id="id_'.$i.'" name="id_'.$i.'" value="'.$a['id'].'" />
			<input type="text" id="kw_'.$i.'" name="kw_'.$i.'" value="'.$a['kw'].'" />
		</td>
		<td><input type="text" id="url_'.$i.'" name="url_'.$i.'" value="'.$a['url'].'" /></td>
	</tr>';
		
		$a = $dbc->_db->fetch_assoc($r);	
		$i ++;
	}

?>
	<tr>
		<td><input type="text" id="new_keyws" name="new_keyws" /></td>
		<td><input type="text" id="new_url" name="new_url" /></td>
	</tr>

	<tr>
		<td colspan="2"><input value="<?php echo _L('submit'); ?>" type="submit" name="submit" id="submit" /></td>
	</tr>

</table>
</form>