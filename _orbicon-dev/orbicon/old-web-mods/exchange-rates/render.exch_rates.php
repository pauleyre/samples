<?php

	require_once DOC_ROOT . '/orbicon/modules/exchange-rates/inc.exch_rates.php';

	$url = ORBX_SITE_URL;
	$orbx_build = ORBX_BUILD;
	$restrict = (isset($_GET['archive'])) ? false : true;
	$first_date = get_first_exch_date($restrict);
	$last_date = get_last_exch_date($restrict);

	$min_date = date('m/d/Y', $first_date);
	$min_year = date('Y', $first_date);
	$lastmonth = mktime(0, 0, 0, (date('m') - 1), date('d'), date('Y'));
	$lastmonth = date('m/Y', $lastmonth);

	$max_date = date('m/d/Y', $last_date);
	$max_year = date('Y', $last_date);

	while($min_year <= $max_year) {
		$years .= "<option value=\"$min_year\">$min_year</option>";
		$min_year ++;
	}

	global $orbx_mod;
	$cfg = $orbx_mod->load_info('exchange-rates');
	$table = print_last_exch_list($restrict);
	$calc_menus = print_last_exch_menus();
	$xml = print_chart_xml('EUR', $last_date);
	$calculate = _L('calculate');
	$change_graph = _L('change_graph');
	$flash = $cfg['chart']['flash'];
	$flash = substr($flash, 0, -4);
	unset($cfg);

	$tooltip = _L('archive_exch_tooltip');

	$archive = (!isset($_GET['archive'])) ? '<a href="' . ORBX_SITE_URL . '/?' . $orbicon_x->ptr .'=mod.exchange-rates&amp;archive">'._L('open_exch_archive').'</a>' : '<a href="' . ORBX_SITE_URL . '/?' . $orbicon_x->ptr .'=mod.exchange-rates">'._L('close_exch_archive').'</a>';

	$calendar = (!isset($_GET['archive'])) ? '' : <<<CAL
<!-- yui calendar -->
<style type="text/css">/*<![CDATA[*/
	@import url("{$url}/orbicon/3rdParty/yui/build/calendar/assets/calendar.css?{$orbx_build}");
/*]]>*/</style>
<script type="text/javascript" src="{$url}/orbicon/controler/gzip.server.php?file=/orbicon/3rdParty/yui/build/calendar/calendar-min.js&amp;{$orbx_build}"></script>
<script type="text/javascript" src="{$url}/orbicon/modules/exchange-rates/yuical.ext.js?{$orbx_build}"></script>
<script type="text/javascript"><!-- // --><![CDATA[

	var orbx_calendar = null;

	function init()
	{
		orbx_calendar = new YAHOO.widget.Calendar("YAHOO.orbicon.calendar.cal1", "cal1Container", { MULTI_SELECT: false, mindate:"{$min_date}", maxdate:"{$max_date}" });

		if(__orbicon_ln == 'hr') {
			orbx_calendar.cfg.setProperty("MONTHS_SHORT",   ["Sij", "Velj", "Ožu", "Tra", "Svi", "Lip", "Srp", "Kol", "Ruj", "List", "Stu", "Pro"]);
			orbx_calendar.cfg.setProperty("MONTHS_LONG",    ["Siječanj", "Veljača", "Ožujak", "Travanj", "Svibanj", "Lipanj", "Srpanj", "Kolovoz", "Rujan", "Listopad", "Studeni", "Prosinac"]);
			orbx_calendar.cfg.setProperty("WEEKDAYS_1CHAR", ["N", "P", "U", "S", "Č", "P", "S"]);
			orbx_calendar.cfg.setProperty("WEEKDAYS_SHORT", ["Ne", "Po", "Ut", "Sr", "Če", "Pe", "Su"]);
			orbx_calendar.cfg.setProperty("WEEKDAYS_MEDIUM",["Ned", "Pon", "Uto", "Sri", "Čet", "Pet", "Sub"]);
			orbx_calendar.cfg.setProperty("WEEKDAYS_LONG",  ["Nedjelja", "Ponedjeljak", "Utorak", "Srijeda", "Četvrtak", "Petak", "Subota"]);
			orbx_calendar.cfg.setProperty("START_WEEKDAY",  1);
		}
		orbx_calendar.cfg.setProperty("NAV_ARROW_LEFT",  '{$url}/orbicon/3rdParty/yui/build/calendar/assets/callt.gif');
		orbx_calendar.cfg.setProperty("NAV_ARROW_RIGHT",  '{$url}/orbicon/3rdParty/yui/build/calendar/assets/calrt.gif');

		orbx_calendar.addMonthRenderer(1, orbx_calendar.renderBodyCellRestricted);
		orbx_calendar.addMonthRenderer(2, orbx_calendar.renderBodyCellRestricted);
		orbx_calendar.addMonthRenderer(3, orbx_calendar.renderBodyCellRestricted);
		orbx_calendar.addMonthRenderer(4, orbx_calendar.renderBodyCellRestricted);
		orbx_calendar.addMonthRenderer(5, orbx_calendar.renderBodyCellRestricted);
		orbx_calendar.addMonthRenderer(6, orbx_calendar.renderBodyCellRestricted);
		orbx_calendar.addMonthRenderer(7, orbx_calendar.renderBodyCellRestricted);
		orbx_calendar.addMonthRenderer(8, orbx_calendar.renderBodyCellRestricted);
		orbx_calendar.addMonthRenderer(9, orbx_calendar.renderBodyCellRestricted);
		orbx_calendar.addMonthRenderer(10, orbx_calendar.renderBodyCellRestricted);
		orbx_calendar.addMonthRenderer(11, orbx_calendar.renderBodyCellRestricted);
		orbx_calendar.addMonthRenderer(12, orbx_calendar.renderBodyCellRestricted);

		orbx_calendar.selectEvent.subscribe(handleSelect, orbx_calendar, true);
		orbx_calendar.changePageEvent.subscribe(myChangePageHandler, orbx_calendar, true);

		orbx_calendar.render();

		YAHOO.util.Event.addListener(["selMonth","selDay","selYear"], "change", updateCal);
		myChangePageHandler();
	}

	YAHOO.util.Event.addListener(window,"load",init);

// ]]></script>
<!-- yui calendar -->
<table id="cal_calc_table">
	<tr>
		<td>
		<form name="dates" action="">
<p>
			<select name="selDay" id="selDay">
				<option value="" selected="selected">&mdash;</option>

				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>

				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>

				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>

				<option value="19">19</option>
				<option value="20">20</option>
				<option value="21">21</option>
				<option value="22">22</option>
				<option value="23">23</option>
				<option value="24">24</option>

				<option value="25">25</option>
				<option value="26">26</option>
				<option value="27">27</option>
				<option value="28">28</option>
				<option value="29">29</option>
				<option value="30">30</option>

				<option value="31">31</option>
			</select>

			<select id="selMonth" name="selMonth">
				<option value="" selected="selected">&mdash;</option>
				<option value="Jan">1</option>
				<option value="Feb">2</option>
				<option value="Mar">3</option>

				<option value="Apr">4</option>
				<option value="May">5</option>
				<option value="Jun">6</option>
				<option value="Jul">7</option>
				<option value="Aug">8</option>
				<option value="Sep">9</option>

				<option value="Oct">10</option>
				<option value="Nov">11</option>
				<option value="Dec">12</option>
			</select>

			<select name="selYear" id="selYear">
				<option value="" selected="selected">&mdash;</option>
				{$years}

			</select>
</p>
		</form>
			<div id="cal1Container"></div>
</td>
 <td style="width:200px;vertical-align:top;padding-left: 10px;">{$tooltip}
<!--	<div id="exch_calculator">
	    <div id="calc_container">{$calc_menus}</div>
	    <p style="text-align:right;"><input onclick="javascript: calc();" value="{$calculate}" type="button" /></p>
	</div> -->
</td>
</tr>
</table>

<br />
<div style="border-bottom: 1px solid #cccccc;"></div>
<br />
CAL;

	$valid = _L('exch_valid_from');
	$curr_date = date($_SESSION['site_settings']['date_format'], $last_date);

return <<<TXT

<style type="text/css">/*<![CDATA[*/
	@import url("{$url}/orbicon/modules/exchange-rates/gfx/exch_rates.css?{$orbx_build}");
/*]]>*/</style>
<script type="text/javascript" src="{$url}/orbicon/controler/gzip.server.php?file=/orbicon/modules/exchange-rates/NumberFormat154.js&amp;{$orbx_build}"></script>
<script type="text/javascript" src="{$url}/orbicon/modules/exchange-rates/render.exch_rates.js?{$orbx_build}"></script>

<input type="hidden" id="current_valid_date" name="current_valid_date" value="{$max_date}" />
<h3>{$valid}: <span id="current_date_container">{$curr_date}</span></h3>
<p style="text-align:right;">{$archive}</p>
<div style="border-bottom: 1px solid #cccccc;"></div>
<br />
{$calendar}

<div id="exch_table_container">{$table}</div>

<br />

<div id="exch_flash_container">
<script type="text/javascript"><!-- // --><![CDATA[
	AC_FL_RunContent(
					'codebase',
					'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab',
					'width',
					'530',
					'height',
					'200',
					'src',
					'{$url}/orbicon/modules/exchange-rates/gfx/{$flash}',
					'quality',
					'high',
					'pluginspage',
					'http://www.macromedia.com/go/getflashplayer',
					'movie',
					'{$url}/orbicon/modules/exchange-rates/gfx/{$flash}',
					'wmode',
					'transparent',
					'FlashVars',
					"dataXML={$xml}&amp;chartWidth=530&amp;chartHeight=200"
					);
// ]]></script>
</div>
<div id="change_graph_container"><span id="change_graph">{$change_graph}</span>
	<a href="javascript:void(null);" onclick="javascript:update_flash($('current_valid_date').value, 'EUR');">EUR/HRK</a> |
	<a href="javascript:void(null);" onclick="javascript:update_flash($('current_valid_date').value, 'USD');">USD/HRK</a> |
	<a href="javascript:void(null);" onclick="javascript:update_flash($('current_valid_date').value, 'GBP');">GBP/HRK</a> |
	<a href="javascript:void(null);" onclick="javascript:update_flash($('current_valid_date').value, 'CHF');">CHF/HRK</a>
</div>

TXT;

?>