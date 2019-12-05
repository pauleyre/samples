<?php

// * requirements
$url = ORBX_SITE_URL;
$lang = $orbicon_x->ptr;
$orbx_build = ORBX_BUILD;

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
	$ch = new Chart();
	$xml = $ch->summary_chart_data($af['id']);

	// '. $cfg['chart']['flash'] .'
	$flash_name = substr($cfg['chart']['flash'], 0, strlen($cfg['chart']['flash']) - 4);

	$sel = ($af['sortnum'] == 0) ? ' class="selected" title="active"' : '';

	$short_title = str_replace(array(' Fond', ' Fund'), '', $af['title']);

	if(strtolower($orbicon_x->ptr) == 'en') {
		if($short_title == 'Obveznički') {
			$short_title = 'Bond';
			$lnk = 'bond-fund';
		}
		elseif ($short_title == 'Global') {
			$lnk = 'global-fund';
		}
		elseif ($short_title == 'Dionički') {
			$short_title = 'Equity';
			$lnk = 'equity-fund';
		}
		elseif ($short_title == 'Novčani') {
			$short_title = 'Money market';
			$lnk = 'money-market-fund';
		}
		elseif ($short_title == 'WAV-DJE') {
			$lnk = 'world-absolute-value-%E2%80%93-wav-%E2%80%93-dje-fund';
		}
		elseif ($short_title == 'Titan') {
			$lnk = 'titan-fund';
		}
		elseif ($short_title == 'Dynamic') {
			$lnk = 'dynamic-fund';
		}
	}

	// build menu
	$menu_items .= '<li'.$sel.'><a href="#tab_'.$af['id'].'">'.$short_title.'</a></li>';

	/* OLD SCRIPT

			<object type="application/x-shockwave-flash" width="510" height="200" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" data="'.ORBX_SITE_URL.'/orbicon/modules/invest-summary/gfx/'.$flash_name.'">
				<param name="movie" value="'.ORBX_SITE_URL.'/orbicon/modules/invest-summary/gfx/'.$flash_name.'" />
				<param name="quality" value="best" />
				<param name="FlashVars" value="dataXML=' . $xml . '&chartWidth=510&chartHeight=200" />
				<img src="'.ORBX_SITE_URL.'/site/gfx/graf_small_zamjenska_'.$lang.'.jpg" alt="'.$af['title'].'" title="'.$af['title'].'" />
			</object>

	*/

	// build charts
	$chart_item .= '
		<div id="tab_'.$af['id'].'">
		<div>
			<script type="text/javascript"><!-- // --><![CDATA[
				if (hasReqestedVersion) {
					AC_FL_RunContent(
							"codebase",
							"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab",
							"width",
							"510",
							"height",
							"200",
							"src",
							"' . ORBX_SITE_URL . '/orbicon/modules/invest-summary/gfx/'.$flash_name.'",
							"quality",
							"high", "flashvars", "dataXML=' . $xml . '&chartWidth=510&chartHeight=200",
							"pluginspage",
							"http://www.macromedia.com/go/getflashplayer",
							"movie",
							"' . ORBX_SITE_URL . '/orbicon/modules/invest-summary/gfx/'.$flash_name.'",
							"wmode",
							"transparent"
							);
				} else {
					document.write(\'<p><img src="'.ORBX_SITE_URL.'/site/gfx/graf_small_zamjenska_'.$lang.'.jpg" alt="'.$af['title'].'" title="'.$af['title'].'" /></p><p class="flash_player"><a href="http://fpdownload.macromedia.com/get/flashplayer/current/install_flash_player.exe" title="Download Flash Player">Download Flash player</a></p>\');
				}

			// ]]></script>
			<noscript>
				<p><img src="'.ORBX_SITE_URL.'/site/gfx/graf_small_zamjenska_'.$lang.'.jpg" alt="'.$af['title'].'" title="'.$af['title'].'" /></p>
				<p class="flash_player"><a href="http://fpdownload.macromedia.com/get/flashplayer/current/install_flash_player.exe" title="Download Flash Player">Download Flash player</a></p>
			</noscript>
			</div>
			<br />
			<div>
				<p>'._L('invest-summary-ondate').': <strong> '.$date.'</strong></p>
				<p>'._L('invest-summary-fond-value').': <strong>'.$last_fond_info['stock_value'].' '.$currency.'</strong></p>
				<p class="detailed"><a href="?'.$lang.'='.$lnk.'">'._L('invest-summary-more').'</a></p>
			</div>
		</div>
	';
}

$h2_title = _L('invest-summary-header');

return <<<INVEST_SUMMARY_FRONT

<link rel="stylesheet" type="text/css" href="{$url}/orbicon/modules/invest-summary/setup/frontend.css" />

<!-- for default tab skin, which includes tabview-core.css and skins/sam/tabview-skin.css -->
<link rel="stylesheet" type="text/css" href="{$url}/orbicon/3rdParty/yui/build/tabview/assets/skins/sam/tabview.css?{$orbx_build}" media="screen" />

<!-- utilities includes all dependencies for this example -->
<script type="text/javascript" src="{$url}/orbicon/3rdParty/yui/build/tabview/tabview-min.js?{$orbx_build}"></script>

<h2>{$h2_title}</h2>
<div id="summary_invest">
	<div id="invest_summary" class="yui-navset">
		<ul class="yui-nav">
			{$menu_items}
		</ul>
	 	<div class="cleaner"></div>
		<div class="yui-content">
			{$chart_item}
		</div>
		<div class="cleaner"></div>
	</div>
	<script type="text/javascript">
	    var tabView = new YAHOO.widget.TabView('invest_summary');
	</script>
	<div class="cleaner"></div>
</div>
INVEST_SUMMARY_FRONT;

?>