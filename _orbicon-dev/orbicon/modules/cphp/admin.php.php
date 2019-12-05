<?php
/*-----------------------------------------------------------------------*
	Project........:	Orbicon X framework 2
	File...........:
	Version........:	1.0 (22/10/2006)
	Author.........:	Pavle Gardijan (pavle.gardijan@orbitum.net) - Orbitum d.o.o.
	Copyright......:	(c) 2006 Orbitum internet communications d.o.o. (www.orbitum.net)
	Created........:	01/07/2006
	Notes..........:
	Modified.......:
*-----------------------------------------------------------------------*/

	if(isset($_POST['save_php'])) {
		$q = sprintf('UPDATE '.TABLE_SETTINGS.'
						SET value = %s
						WHERE (setting = %s)',
					$dbc->_db->quote($_POST['php']), $dbc->_db->quote('custom_php_code'));

		$dbc->_db->query($q);
		// check if update status
		$q_c = sprintf('SELECT value
							FROM '.TABLE_SETTINGS.'
							WHERE (setting = %s)
							LIMIT 1', $dbc->_db->quote('custom_php_code'));
		$r_c = $dbc->_db->query($q_c);
		$a_c = $dbc->_db->fetch_array($r_c);

		// UPDATE failed, try with INSERT
		if($a_c['value'] === null)
		{
			$q_new = sprintf('INSERT INTO '.TABLE_SETTINGS.'
								(value, setting)
								VALUES (%s, %s)',
								$dbc->_db->quote($_POST['php']), $dbc->_db->quote('custom_php_code'));
			$dbc->_db->query($q_new);
		}
	}

	// load custom php
	$q = sprintf('SELECT value
					FROM '.TABLE_SETTINGS.'
					WHERE (setting = %s)
					LIMIT 1', $dbc->_db->quote('custom_php_code'));
	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_array($r);
	$php = $a['value'];
	unset($a, $r);

	$editor_ln = (is_file(DOC_ROOT . '/orbicon/3rdParty/edit_area/langs/' .  $orbicon_x->ptr . '.js')) ?  $orbicon_x->ptr : 'en';

?>

<form action="" method="post">
	<p>
	<input name="save_php" type="submit" id="save_php" value="<?php echo _L('save'); ?>" />
	</p>
	<textarea id="php" name="php" class="editor_area"><?php echo $php; ?></textarea><br />
	<input name="save_php" type="submit" id="save_php2" value="<?php echo _L('save'); ?>" />
</form>

<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/3rdParty/edit_area/edit_area_full.js?<?php echo ORBX_BUILD; ?>"></script>
<script type="text/javascript"><!-- // --><![CDATA[
editAreaLoader.init({
	id : "php",
	syntax: "php",
	start_highlight: true,
	language: '<?php echo $editor_ln; ?>'
});
// ]]></script>