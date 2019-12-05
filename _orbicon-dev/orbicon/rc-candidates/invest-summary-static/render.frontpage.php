<?php
	
	// * requirements
	$url = ORBX_SITE_URL;
	$lang = $orbicon_x->ptr;
	
	// * call fond class
	include_once DOC_ROOT.'/orbicon/modules/invest/class/fond.class.php';
	include_once DOC_ROOT.'/orbicon/modules/invest/class/stock.class.php';
	include_once DOC_ROOT.'/orbicon/modules/invest/class/currency.class.php';

	$f = new Fond;
	$s = new Stock;
	$c = new Currency;

	$active_fonds = $f->get_all_fonds(1);

	global $dbc;
	$i = 0;
	$b = 1;

	$a = $dbc->_db->fetch_array($active_fonds);

	$display = '<h2>' . _L('fonds') . '</h2>';
	
	while($i < 2) {			
		$display .= '<div class="graph_row">';

		while($a) {
			
			$last_fond_info = $s->get_latest_info($a['id']);
			$last_fond_graph = $s->get_latest_graph($a['id']);

			// * alt & title image text
			$title = $a['title'].'-'.$last_fond_info['date'];
			$currency = $c->get_currency($a['currency']);
			$currency = $dbc->_db->fetch_array($currency);
			$currency = $currency['title'];
			
			$graph = DOC_ROOT . '/site/venus/invest/' . $last_fond_graph;
			$graph = (is_file($graph)) ? ORBX_SITE_URL . '/site/venus/invest/' . $last_fond_graph : ORBX_SITE_URL . '/orbicon/modules/invest/gfx/no_graph.png';
			
			$date = strtotime($last_fond_info['date']);
			$date = date('d.m.Y.', $date);
			
			$display .= '
			<div class="graph_box" style="width:50%;">
				<div>
					<h3>'.$a['title'].'</h3>
					<p class="split_view">
						<strong>'.$date.'</strong><br />'.$last_fond_info['stock_value'].' '. $currency .'
					</p>
					<p class="split_view"><img src="'.$graph.'" alt="'.$title.'" title="'.$title.'" /></p>
				</div>
			</div>';
			$a = $dbc->_db->fetch_array($active_fonds);
			
			if($b == $max_news_box_previews) {
				$b = 1;
				break;
			}
			$b ++;
		}

		$display .= '</div>';
		$i++;
	}


return <<<INVEST_SUMMARY_FRONT
<link rel="stylesheet" type="text/css" href="{$url}/orbicon/modules/invest-summary/setup/frontend.css" />
<div id="invest_summary">{$display}</div>
INVEST_SUMMARY_FRONT;

?>