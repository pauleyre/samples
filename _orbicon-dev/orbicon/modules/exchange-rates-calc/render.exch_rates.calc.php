<?php

	$url = ORBX_SITE_URL;
	$orbx_build = ORBX_BUILD;

	require_once DOC_ROOT . '/orbicon/modules/exchange-rates/inc.exch_rates.php';

	$calc_menus = print_last_exch_menus();
	$last_date = get_last_exch_date();
	$max_date = date('m/d/Y', $last_date);

return <<<TXT
<!-- calc -->
<input type="hidden" id="current_valid_date" name="current_valid_date" value="{$max_date}" />
 <div id="calc_container">{$calc_menus}</div>
<!-- calc -->
TXT;

/*return <<<TXT
<script type="text/javascript" src="{$url}/orbicon/modules/exchange-rates/render.exch_rates.js?{$orbx_build}"></script>
<script type="text/javascript" src="{$url}/orbicon/controler/gzip.server.php?file=/orbicon/modules/exchange-rates/NumberFormat154.js&amp;{$orbx_build}"></script>

<input type="hidden" id="current_valid_date" name="current_valid_date" value="{$max_date}" />
<!-- calc -->
	<div id="exch_calculator">
	    <div id="calc_container">{$calc_menus}</div>
	</div>
<!-- calc -->
TXT;*/

?>