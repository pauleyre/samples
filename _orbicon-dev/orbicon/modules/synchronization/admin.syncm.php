<?php

/**
 * Sync manager GUI
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @version 1.20
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-12-01
 */

	require_once DOC_ROOT . '/orbicon/modules/synchronization/class.syncmngr.php';

	$syncm_settings = new Settings;
	$syncm_settings->save_sync_settings();
	$syncm_settings->build_site_settings(true);

	$log = '';

	if($_POST){

		// get our servers
		$r = $dbc->_db->query('	SELECT 	*
								FROM 	'.TABLE_SYNC_SERVERS);
		$a = $dbc->_db->fetch_assoc($r);

		// start sync
		if(isset($_POST['do_sync'])) {
			while($a) {

				// we selected this server
				if($_POST['sync_server_id_' . $a['id']] == 'ok') {

					$syncm = new SyncManager($a['server']);
					$cache_filename = $syncm->_sync_cache_file;
					$syncm->start();
					$log .= $syncm->get_log().'<br /><br />';
					unset($syncm);
				}
				$a = $dbc->_db->fetch_assoc($r);
			}
			if(!empty($cache_filename)) {
				unlink($cache_filename);
			}
		}

		// * Added 08.03.07 - server setting clone
		// * Alen Novakovic
		if(isset($_POST['duplicate'])) {
			require_once DOC_ROOT . '/orbicon/modules/servers/class.server.php';
			$clone_server = new Server;

			while($a) {
				// we selected this server
				if($_POST['sync_server_id_' . $a['id']] == 'ok') {
					$clone_server->duplicate($a);
				}
				$a = $dbc->_db->fetch_assoc($r);
			}
			unset($clone_server);
		}
	}

	// determine what message to display. leave \n\n in single quotes or else javascript will fail
	$sync_msg = /*(is_file(DOC_ROOT . '/site/mercury/sync.cache.log')) ?*/ _L('start_synchronization') /*: _L('start_sync_no_log') .'\n\n'. _L('start_synchronization')*/;

?>
<script type="text/javascript"><!-- // --><![CDATA[

	function verify_sync()
	{
		var sync = window.confirm('<?php echo _L($sync_msg); ?>?');
		if(sync == false) {
			sh_ind();
		}
		return sync;
	}

	var state;

	function checkUncheck(state)
	{
		var i;
		var type;
		var gallery = $('sync_form');
		var cboxes = gallery.getElementsByTagName('INPUT');

		for(i = 0; i < cboxes.length; i++) {
			type = cboxes[i].type;
			type = type.toLowerCase();
			if(type == 'checkbox') {
				cboxes[i].checked = state;
			}
		}
	}

// ]]></script>
<form method="post" action="" id="sync_form">
<?php

		if($_SESSION['site_settings']['syncm_type'] == SYNC_MANAGER_TYPE_REPOSITORY) {

			// get receiver servers
			/*$sync_servers = explode(',', $_SESSION['site_settings']['syncm_server']);
			$sync_servers = array_remove_empty(array_map('trim', $sync_servers));
	*/
			require_once DOC_ROOT . '/orbicon/modules/servers/class.server.php';
			$my_server = new Server;


?>
<p>
	<fieldset>
		<legend><?php echo _L('avail_servers'); ?></legend>
		<a href="javascript:void(null)" onclick="javascript: checkUncheck(true);"><?php echo _L('select_all'); ?></a> |
		<a href="javascript:void(null)" onclick="javascript: checkUncheck(false);"><?php echo _L('unselect_all'); ?></a><br />
		<?php echo $my_server->print_sync_servers(); ?>
		<p><a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/mod/servers"><?php echo _L('manage_servers'); ?></a> | <input type="submit" name="duplicate" id="duplicate" value="<?php echo _L('duplicate'); ?>" style="background: none; border-bottom: none; border-left: none; border-right: none; border-top: none; " /></p>
	</fieldset>
</p>
<p><input onclick="javascript:sh_ind(); return verify_sync();" name="do_sync" type="submit" id="do_sync" value="<?php echo _L('synchronize'); ?>" /></p>
<div class="log_container">
<?php echo $log; ?>
</div><br />
<?php
		}

?>
</form>