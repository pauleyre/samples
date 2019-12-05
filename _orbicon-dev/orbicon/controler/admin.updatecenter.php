<?php

/**
 * Update center GUI
 *
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @version 1.0
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-11-05
 */

	require DOC_ROOT . '/orbicon/lib/auto88/class.auto88.php';

	$log = '';

	if(isset($_POST['do_update'])) {

		require_once DOC_ROOT . '/orbicon/class/class.version.php';
		$version = new Version;
		$current = $version->get_orbicon_version(PRODUCT_VERSION_DEV);
		unset($version);

		$update = new Auto88($current);
		$log .= $update->get_log();
	}

?>
<script type="text/javascript"><!-- // --><![CDATA[

	function verify_update()
	{
		var sync = window.confirm('<?php echo _L('start_update'); ?>?');
		if(sync == false) {
			sh_ind();
		}
		return sync;
	}

// ]]></script>
<form method="post" action="" onsubmit="javascript: return verify_update();">
<p><input onclick="javascript:sh_ind();" name="do_update" type="submit" id="do_update" value="<?php echo _L('update'); ?>" /></p>
<div class="log_container">
<?php echo $log; ?>
</div><br />
</form>