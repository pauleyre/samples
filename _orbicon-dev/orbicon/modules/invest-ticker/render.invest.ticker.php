<?php

	$url = ORBX_SITE_URL;
	$orbx_build = ORBX_BUILD;

	// * call fond class
	include_once DOC_ROOT.'/orbicon/modules/invest/class/fond.class.php';
	include_once DOC_ROOT.'/orbicon/modules/invest/class/stock.class.php';
	include_once DOC_ROOT.'/orbicon/modules/invest/class/currency.class.php';

	$f = new Fond;
	$s = new Stock;
	$c = new Currency;

	$active_fonds = $f->get_all_fonds(1);

	$ticker = array();

	while($af = $dbc->_db->fetch_array($active_fonds)){

		// * loop through all active fonds
		$last_fond_info = $s->get_latest_info($af['id']);

		if($af['frontpage']) {
			// * currency
			$currency = $c->get_currency($af['currency']);
			$currency = $dbc->_db->fetch_array($currency);
			$currency = $currency['title'];

			switch ($af['id']){
				case 1: $link = 'http://www.hpb-invest.hr/?hr=dioni%C4%8Dki-fond'; break;
				case 2: $link = 'http://www.hpb-invest.hr/?hr=global-fond'; break;
				case 3: $link = 'http://www.hpb-invest.hr/?hr=dynamic-fond'; break;
				case 4: $link = 'http://www.hpb-invest.hr/?hr=nov%C4%8Dani-fond'; break;
				case 6: $link = 'http://www.hpb-invest.hr/?hr=obvezni%C4%8Dki-fond'; break;
				case 7: $link = 'http://www.hpb-invest.hr/?hr=titan-fond'; break;
				case 9: $link = 'http://www.hpb-invest.hr/?hr=wav-dje-fond'; break;
				default: $link = 'http://www.hpb-invest.hr/'; break;

			}

			// * build display
			$ticker[] = "<a target=\"_blank\" title=\"{$af['title']}\" href=\"$link\">{$af['title']}</a> {$last_fond_info['stock_value']} $currency";
		}
	}

	$ticker = implode(' | ', $ticker);
	$ticker = addslashes(str_sanitize($ticker));


	$s = <<<INVEST_TICKER_FRONT
	<script type="text/javascript" src="/orbicon/modules/invest-ticker/invest.ticker.js?{$orbx_build}"></script>
<script type="text/javascript"><!-- // --><![CDATA[
	_inv_ticker_content = '{$ticker}';
// ]]></script>
INVEST_TICKER_FRONT;

	file_put_contents(DOC_ROOT . '/site/mercury/hpbinvest.fonds', $s);

	return $s;

?>