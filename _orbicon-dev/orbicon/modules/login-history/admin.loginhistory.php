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

	require_once DOC_ROOT . '/orbicon/modules/stats/class.stats.php';
	$stats = new Statistics;

?>

<p>
	<strong><?php echo _L('format'); ?>:</strong> <?php echo _L('date'); ?> : <?php echo _L('user'); ?> <?php echo _L('ip_addr'); ?> [#:<?php echo _L('id'); ?>, n:<?php echo _L('name'); ?> <?php echo _L('surname'); ?>, s:<?php echo _L('status'); ?>] <?php echo _L('action'); ?>
</p>
<textarea readonly="readonly" class="editor_area"><?php echo $stats->get_login_history(); ?></textarea>