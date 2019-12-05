<?php

// * requirements
$url = ORBX_SITE_URL;
$lang = $orbicon_x->ptr;

global $dbc;

global $orbx_mod;
$cfg = $orbx_mod->load_info('invest-summary');

// * call fond class
include_once DOC_ROOT.'/orbicon/modules/invest/class/fond.class.php';
include_once DOC_ROOT.'/orbicon/modules/invest/class/stock.class.php';
include_once DOC_ROOT.'/orbicon/modules/invest/class/currency.class.php';
include_once DOC_ROOT.'/orbicon/modules/invest/class/chart.class.php';

$f = new Fond;
$s = new Stock;
$c = new Currency;

$active_fonds = $f->get_all_frontpage_fonds(1);

while($af = $dbc->_db->fetch_array($active_fonds)){

	// * loop through all active fonds
	$last_fond_info = $s->get_latest_info($af['id']);
	$last_fond_graph = $s->get_latest_graph($af['id']);

	// * alt & title image text
	$title = $af['title'].'-'.$last_fond_info['date'];

	// * link
	$lnk = get_permalink($af['title']);

	// * currency
	$currency = $c->get_currency($af['currency']);
	$currency = $dbc->_db->fetch_array($currency);
	$currency = $currency['title'];

	// * graph & date
	$graph = DOC_ROOT . '/site/venus/invest/' . $last_fond_graph;
	$graph = (is_file($graph)) ? ORBX_SITE_URL . '/site/venus/invest/' . $last_fond_graph : ORBX_SITE_URL . '/orbicon/modules/invest/gfx/no_graph.png';


	$date = strtotime($last_fond_info['date']);
	$date = date($_SESSION['site_settings']['date_format'], $date);

	// * set XML data for chart
	$ch = new Chart($af);
	$xml = $ch->monthly_chart_data();

	// '. $cfg['chart']['flash'] .'
	$flash_name = substr($cfg['chart']['flash'], 0, strlen($cfg['chart']['flash']) - 4);

	// * build display
	$display .= '
		<div class="graph_box">
			<h3><a href="?'.$lang.'='.$lnk.'" title="'.$af['title'].'">'.$af['title'].'</a></h3>
			<p>'._L('invest-summary-ondate').': <strong> '.$date.'</strong></p>
			<p>'._L('invest-summary-fond-value').': <strong>'.$last_fond_info['stock_value'].' '.$currency.'</strong></p>
			<div>
				<script type="text/javascript"><!-- // --><![CDATA[
					if (hasReqestedVersion) {
						AC_FL_RunContent(
								"codebase",
								"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab",
								"width",
								"190",
								"height",
								"117",
								"src",
								"' . ORBX_SITE_URL . '/orbicon/modules/invest-summary/gfx/'.$flash_name.'",
								"quality",
								"high", "flashvars", "dataXML=' . $xml . '",
								"pluginspage",
								"http://www.macromedia.com/go/getflashplayer",
								"movie",
								"' . ORBX_SITE_URL . '/orbicon/modules/invest-summary/gfx/'.$flash_name.'",
								"wmode",
								"transparent"
								);
					} else {
						document.write(\'<img src="'.ORBX_SITE_URL.'/site/gfx/graf_small_zamjenska_'.$lang.'.jpg" alt="'.$af['title'].'" title="'.$af['title'].'" />\');
					}

			// ]]></script>
			<noscript>
				<img src="'.ORBX_SITE_URL.'/site/gfx/graf_small_zamjenska_'.$lang.'.jpg" alt="'.$af['title'].'" title="'.$af['title'].'" />
			</noscript>

			</div>
		</div>';
}


return <<<INVEST_SUMMARY_FRONT
<style type="text/css" media="screen">
@import url ({$url}/orbicon/modules/invest-summary/setup/frontend.css); 
</style>
<!--
<link rel="stylesheet" type="text/css" href="{$url}/orbicon/modules/invest-summary/setup/frontend.css" />
-->
<div id="invest_summary">
	{$display}
	<div class="cleaner"></div>
</div>
INVEST_SUMMARY_FRONT;

?>