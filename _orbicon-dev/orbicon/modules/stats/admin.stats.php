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
	require_once DOC_ROOT.'/orbicon/modules/stats/class.stats.php';
	$stats = new Statistics;
	$stats->save_stats_settings();

?>
<input id="stats_start_date" name="stats_start_date" type="hidden" value="<?php echo $start_date; ?>" />
<input id="stats_end_date" name="stats_end_date" type="hidden" value="<?php echo $end_date; ?>" />
<style type="text/css">/*<![CDATA[*/
	table#stats {
		font-size: 90%;
	}
	table#stats table tr:hover {
		background:#ffffcc !important;
	}
/*]]>*/</style>
<fieldset style="padding: 10px;"><legend style="padding: 4px;"><strong><?php echo _L('visits'); ?></strong></legend>
	<script type="text/javascript">
		AC_FL_RunContent(
			"src", "<?php echo ORBX_SITE_URL; ?>/orbicon/modules/stats/gfx/FC_2_3_MSLine",
			"FlashVars", "dataXML=<?php echo min_str($stats -> get_daily_visits_stats()); ?>",
			"width", "600",
			"height", "400",
			"align", "middle",
			"id", "charts",
			"quality", "high",
			"bgcolor", "#ffffff",
			"name", "charts",
			"allowScriptAccess","sameDomain",
			"type", "application/x-shockwave-flash",
			"pluginspage", "http://www.adobe.com/go/getflashplayer",
			'codebase', 'http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab',
			"swliveconnect", "true",
			'wmode', 'transparent'
		);
	</script>
</fieldset>

<fieldset style="padding: 10px;"><legend style="padding: 4px;"><strong><?php echo _L('hourly_visits'); ?></strong></legend>
	<script type="text/javascript">
		AC_FL_RunContent(
			"src", "<?php echo ORBX_SITE_URL; ?>/orbicon/modules/stats/gfx/FC_2_3_MSLine",
			"FlashVars", "dataXML=<?php echo min_str($stats->get_hourly_visits_stats()); ?>",
			"width", "600",
			"height", "400",
			"align", "middle",
			"id", "charts",
			"quality", "high",
			"bgcolor", "#ffffff",
			"name", "charts",
			"allowScriptAccess","sameDomain",
			"type", "application/x-shockwave-flash",
			"pluginspage", "http://www.adobe.com/go/getflashplayer",
			'codebase', 'http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab',
			"swliveconnect", "true",
			'wmode', 'transparent'
		);
	</script>
</fieldset>

<fieldset style="padding: 10px;"><legend style="padding: 4px;"><strong><?php echo _L('content'); ?></strong></legend>
	<table width="100%">
		<tr style="font-weight:bold;">
			<td>#</td>
			<td><?php echo _L('url'); ?></td>
			<td><?php echo _L('num_views'); ?></td>
		</tr>
		<?php echo $stats->get_top_content(); ?>
	</table><br />
	<strong>
		<a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr;?>=orbicon/mod/stats&amp;expand_content">
			<?php echo _L('show_all'); ?></a>
	</strong>
</fieldset>

<fieldset style="padding: 10px;"><legend style="padding: 4px;"><strong><?php echo _L('refers'); ?></strong></legend>
	<table width="100%">
		<tr style="font-weight:bold;">
			<td>#</th>
			<td><?php echo _L('url'); ?></td>
			<td><?php echo _L('num_entries'); ?></td>
		</tr>
		<?php echo $stats->get_top_refers(); ?>
	</table><br />
	<strong>
		<a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/mod/stats&amp;expand_refers"><?php echo _L('show_all'); ?></a>
	</strong>
</fieldset>

<fieldset style="padding: 10px;"><legend style="padding: 4px;"><strong><?php echo _L('keywords'); ?></strong></legend>
	<table width="100%">
		<tr style="font-weight:bold;">
			<td>#</td>
			<td><?php echo _L('keyword'); ?></td>
			<td><?php echo _L('num_searches'); ?></td>
		</tr>
		<?php echo $stats->get_top_keywords(); ?>
	</table><br />
	<strong>
		<a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr;?>=orbicon/mod/stats&amp;expand_keywords"><?php echo _L('show_all'); ?></a>
	</strong>
</fieldset>

<fieldset style="padding: 10px;"><legend style="padding: 4px;"><strong><?php echo _L('attila_keywords'); ?></strong></legend>
	<table width="100%">
		<tr style="font-weight:bold;">
			<td>#</td>
			<td><?php echo _L('keyword'); ?></td>
			<td><?php echo _L('num_searches'); ?></td>
		</tr>
		<?php echo $stats->get_top_attila_keywords(); ?>
	</table><br />
	<strong>
		<a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr;?>=orbicon/mod/stats&amp;expand_attila_keywords"><?php echo _L('show_all'); ?></a>
	</strong>
</fieldset>

<fieldset style="padding: 10px;"><legend style="padding: 4px;"><strong><?php echo _L('countries'); ?></strong></legend>
	<table width="100%">
		<tr style="font-weight:bold;">
			<td>#</td>
			<td><?php echo _L('country'); ?></td>
			<td><?php echo _L('num_entries'); ?></td>
		</tr>
		<?php echo $stats->get_top_countries(); ?>
	</table><br />
	<strong>
		<a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr;?>=orbicon/mod/stats&amp;expand_countries"><?php echo _L('show_all'); ?></a>
	</strong>
</fieldset>
<div style="height: 1%;"></div>