<?php

// * requirements
$url = ORBX_SITE_URL;
$lang = $orbicon_x->ptr;
$orbx_build = ORBX_BUILD;
global $dbc;

// * requirements
include_once DOC_ROOT.'/orbicon/modules/invest/class/stock.class.php';
include_once DOC_ROOT.'/orbicon/modules/invest/class/fond.class.php';
include_once DOC_ROOT.'/orbicon/modules/invest/class/currency.class.php';
include_once DOC_ROOT.'/orbicon/modules/invest/class/chart.class.php';

// * define min & max date -/ format: mm/dd/yyyy
$stock_date = new Stock;
$date_range = $stock_date->get_date_range();

// * reformat db values from yyyy-mm-dd into mm/dd/yyyy
$min_d = explode('-', $date_range['lowest']);
$max_d = explode('-', $date_range['highest']);

$min_date = $min_d[1] . '/' . $min_d[2] . '/' . $min_d[0];
$max_date = $max_d[1] . '/' . $max_d[2] . '/' . $max_d[0];


include_once DOC_ROOT.'/orbicon/modules/invest/forms/form.calc.php';

return <<<INFOCENTAR_FRONT
<!--
<style type="text/css" media="screen">
@import url ({$url}/orbicon/modules/invest/gfx/backend.css?{$orbx_build});
@import url ({$url}/orbicon/modules/invest/setup/frontend.css?{$orbx_build});
@import url ({$url}/orbicon/3rdParty/yui/build/calendar/assets/calendar.css?{$orbx_build});
@import url ({$url}/orbicon/controler/gzip.server.php?file=/orbicon/3rdParty/yui/build/calendar/assets/calendar.css&amp;{$orbx_build});
</style>
-->
<link rel="stylesheet" type="text/css" href="{$url}/orbicon/modules/invest/gfx/backend.css" />
<link rel="stylesheet" type="text/css" href="{$url}/orbicon/modules/invest/setup/frontend.css" />
<link rel="stylesheet" type="text/css" href="{$url}/orbicon/3rdParty/yui/build/calendar/assets/calendar.css" />

<!-- yui calendar -->
<script type="text/javascript" src="{$url}/orbicon/controler/gzip.server.php?file=/orbicon/3rdParty/yui/build/calendar/calendar-min.js&amp;{$orbx_build}"></script>
<!---->
<link rel="stylesheet" type="text/css" href="{$url}/orbicon/controler/gzip.server.php?file=/orbicon/3rdParty/yui/build/calendar/assets/calendar.css&amp;{$orbx_build}" />

<script type="text/javascript" src="{$url}/orbicon/modules/invest/yuical.ext.from.js?{$orbx_build}"></script>
<script type="text/javascript" src="{$url}/orbicon/modules/invest/yuical.ext.till.js?{$orbx_build}"></script>
<script type="text/javascript"><!-- // --><![CDATA[

	var invest_from = null;
	var invest_till = null;

	function init() {
		invest_from = new YAHOO.widget.Calendar("cal1","from_date",
																	{ mindate: "{$min_date}",
																	  maxdate: "{$max_date}"}
																	);
		invest_till = new YAHOO.widget.Calendar("cal2","till_date",
																	{ mindate: "{$min_date}",
																	  maxdate: "{$max_date}"}
																	);
		if(__orbicon_ln == 'hr') {

			invest_from.cfg.setProperty("MONTHS_SHORT",   ["Sij", "Velj", "Ožu", "Tra", "Svi", "Lip", "Srp", "Kol", "Ruj", "List", "Stu", "Pro"]);
			invest_from.cfg.setProperty("MONTHS_LONG",    ["Siječanj", "Veljača", "Ožujak", "Travanj", "Svibanj", "Lipanj", "Srpanj", "Kolovoz", "Rujan", "Listopad", "Studeni", "Prosinac"]);
			invest_from.cfg.setProperty("WEEKDAYS_1CHAR", ["N", "P", "U", "S", "Č", "P", "S"]);
			invest_from.cfg.setProperty("WEEKDAYS_SHORT", ["Ne", "Po", "Ut", "Sr", "Če", "Pe", "Su"]);
			invest_from.cfg.setProperty("WEEKDAYS_MEDIUM",["Ned", "Pon", "Uto", "Sri", "Čet", "Pet", "Sub"]);
			invest_from.cfg.setProperty("WEEKDAYS_LONG",  ["Nedjelja", "Ponedjeljak", "Utorak", "Srijeda", "Četvrtak", "Petak", "Subota"]);
			invest_from.cfg.setProperty("START_WEEKDAY",  1);


			invest_till.cfg.setProperty("MONTHS_SHORT",   ["Sij", "Velj", "Ožu", "Tra", "Svi", "Lip", "Srp", "Kol", "Ruj", "List", "Stu", "Pro"]);
			invest_till.cfg.setProperty("MONTHS_LONG",    ["Siječanj", "Veljača", "Ožujak", "Travanj", "Svibanj", "Lipanj", "Srpanj", "Kolovoz", "Rujan", "Listopad", "Studeni", "Prosinac"]);
			invest_till.cfg.setProperty("WEEKDAYS_1CHAR", ["N", "P", "U", "S", "Č", "P", "S"]);
			invest_till.cfg.setProperty("WEEKDAYS_SHORT", ["Ne", "Po", "Ut", "Sr", "Če", "Pe", "Su"]);
			invest_till.cfg.setProperty("WEEKDAYS_MEDIUM",["Ned", "Pon", "Uto", "Sri", "Čet", "Pet", "Sub"]);
			invest_till.cfg.setProperty("WEEKDAYS_LONG",  ["Nedjelja", "Ponedjeljak", "Utorak", "Srijeda", "Četvrtak", "Petak", "Subota"]);
			invest_till.cfg.setProperty("START_WEEKDAY",  1);
		}

		invest_from.cfg.setProperty("NAV_ARROW_LEFT",  '{$url}/orbicon/3rdParty/yui/build/calendar/assets/callt.gif');
		invest_from.cfg.setProperty("NAV_ARROW_RIGHT",  '{$url}/orbicon/3rdParty/yui/build/calendar/assets/calrt.gif');

		invest_till.cfg.setProperty("NAV_ARROW_LEFT",  '{$url}/orbicon/3rdParty/yui/build/calendar/assets/callt.gif');
		invest_till.cfg.setProperty("NAV_ARROW_RIGHT",  '{$url}/orbicon/3rdParty/yui/build/calendar/assets/calrt.gif');

		invest_from.addMonthRenderer(1, invest_from.renderBodyCellRestricted);
		invest_from.addMonthRenderer(2, invest_from.renderBodyCellRestricted);
		invest_from.addMonthRenderer(3, invest_from.renderBodyCellRestricted);
		invest_from.addMonthRenderer(4, invest_from.renderBodyCellRestricted);
		invest_from.addMonthRenderer(5, invest_from.renderBodyCellRestricted);
		invest_from.addMonthRenderer(6, invest_from.renderBodyCellRestricted);
		invest_from.addMonthRenderer(7, invest_from.renderBodyCellRestricted);
		invest_from.addMonthRenderer(8, invest_from.renderBodyCellRestricted);
		invest_from.addMonthRenderer(9, invest_from.renderBodyCellRestricted);
		invest_from.addMonthRenderer(10, invest_from.renderBodyCellRestricted);
		invest_from.addMonthRenderer(11, invest_from.renderBodyCellRestricted);
		invest_from.addMonthRenderer(12, invest_from.renderBodyCellRestricted);

		invest_till.addMonthRenderer(1, invest_till.renderBodyCellRestricted);
		invest_till.addMonthRenderer(2, invest_till.renderBodyCellRestricted);
		invest_till.addMonthRenderer(3, invest_till.renderBodyCellRestricted);
		invest_till.addMonthRenderer(4, invest_till.renderBodyCellRestricted);
		invest_till.addMonthRenderer(5, invest_till.renderBodyCellRestricted);
		invest_till.addMonthRenderer(6, invest_till.renderBodyCellRestricted);
		invest_till.addMonthRenderer(7, invest_till.renderBodyCellRestricted);
		invest_till.addMonthRenderer(8, invest_till.renderBodyCellRestricted);
		invest_till.addMonthRenderer(9, invest_till.renderBodyCellRestricted);
		invest_till.addMonthRenderer(10, invest_till.renderBodyCellRestricted);
		invest_till.addMonthRenderer(11, invest_till.renderBodyCellRestricted);
		invest_till.addMonthRenderer(12, invest_till.renderBodyCellRestricted);

		// * trigger the selection
		invest_from.selectEvent.subscribe(handleSelectFrom, invest_from, true);
		invest_till.selectEvent.subscribe(handleSelectTill, invest_till, true);

		invest_from.changePageEvent.subscribe(myChangePageHandlerFrom, invest_from, true);
		invest_till.changePageEvent.subscribe(myChangePageHandlerTill, invest_till, true);

		invest_from.render();
		invest_till.render();

		myChangePageHandlerFrom();
		myChangePageHandlerTill();

		update_calendars();
	}

	// * this function handles selection from calendar
	function handleSelectFrom(type,args,obj) {
	    var dates = args[0];
	    var date = dates[0];
	    var year = date[0], month = date[1], day = date[2];

	    var txtDate1 = document.getElementById("from_d");
	    txtDate1.value = month + "/" + day + "/" + year;
	}

	function handleSelectTill(type,args,obj) {
		var dates = args[0];
		var date = dates[0];
		var year = date[0], month = date[1], day = date[2];

		var txtDate2 = document.getElementById("till_d");
		txtDate2.value = month + "/" + day + "/" + year;
	}

	// * initialize calendar
	YAHOO.util.Event.addListener(window, "load", init);
// ]]></script>
<!-- yui calendar -->
	<div id="invest_front">{$display}</div>
INFOCENTAR_FRONT;

?>