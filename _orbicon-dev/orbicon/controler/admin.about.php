<?php
/**
 * About screen
 * @author Pavle Gardijan
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-07-01
 */

	$i = 1;

	foreach($_SERVER as $k => $v) {
		$style = (($i % 2) == 0) ? ' style="background:#ffffff;"' : '';
		$value = stripslashes($v);
		$server_info .= "<tr $style><td><strong>$k</strong></td><td>$v</td></tr>";
		$i ++;
	}
?>

	<div id="admin_about">
		<p><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/desktop_icons/about37px.gif" /></p>
		<span><?php echo ORBX_FULL_NAME; ?></span><br />
		<span><?php echo _L('user'); ?>:</span> <?php echo $_SESSION['user.a']['first_name']; ?> <?php echo $_SESSION['user.a']['last_name']; ?><br />
		<span><?php echo _L('installed'); ?>:</span> <?php echo date($_SESSION['site_settings']['date_format'], ORBX_INSTALL_TIME); ?><br />
		<span><?php echo _L('version'); ?>:</span> <?php echo $orbicon_info->get_orbicon_version(); ?><br />
		<span>PHP:</span> <?php echo PHP_VERSION; ?><br />
		<span><?php echo DB_TYPE; ?>:</span> <?php echo $dbc->_db->get_version(); ?><br />
		<span>Server:</span> <?php echo $_SERVER['SERVER_SOFTWARE']; ?> <a href="javascript:void(null);" onclick="javascript:sh('server_info');">[<?php echo _L('more'); ?>]</a>
	</div>
	<br />
	<table id="server_info" style="border-collapse: collapse;"><?php echo $server_info; ?></table>