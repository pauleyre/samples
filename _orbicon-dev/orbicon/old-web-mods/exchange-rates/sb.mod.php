<?php



	$min_date = date('m/d/Y', $first_date);
	$min_year = date('Y', $first_date);

	$max_date = date('m/d/Y', $last_date);
	$max_year = date('Y', $last_date);

	while($min_year <= $max_year) {
		$years .= '<option value="'.$min_year.'">'.$min_year.'</option>';
		$min_year ++;
	}

?>
<!-- yui calendar -->
<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/3rdParty/yui/build/calendar/calendar-min.js?<?php echo ORBX_BUILD; ?>"></script>
<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/modules/exchange-rates/admin.exch_rates.js?<?php echo ORBX_BUILD; ?>"></script>
<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/modules/exchange-rates/yuical.ext.js?<?php echo ORBX_BUILD; ?>"></script>

<script type="text/javascript"><!-- // --><![CDATA[

	var orbx_calendar = null;

	function init_exch_admin()
	{
		orbx_calendar = new YAHOO.widget.Calendar("YAHOO.orbicon.calendar.cal1", "cal1Container", { MULTI_SELECT: false, mindate:"<?php echo $min_date; ?>", maxdate:"<?php echo $max_date; ?>" });

		if(__orbicon_ln == 'hr') {
			orbx_calendar.cfg.setProperty("MONTHS_SHORT",   ["Sij", "Velj", "Ožu", "Tra", "Svi", "Lip", "Srp", "Kol", "Ruj", "List", "Stu", "Pro"]);
			orbx_calendar.cfg.setProperty("MONTHS_LONG",    ["Siječanj", "Veljača", "Ožujak", "Travanj", "Svibanj", "Lipanj", "Srpanj", "Kolovoz", "Rujan", "Listopad", "Studeni", "Prosinac"]);
			orbx_calendar.cfg.setProperty("WEEKDAYS_1CHAR", ["N", "P", "U", "S", "Č", "P", "S"]);
			orbx_calendar.cfg.setProperty("WEEKDAYS_SHORT", ["Ne", "Po", "Ut", "Sr", "Če", "Pe", "Su"]);
			orbx_calendar.cfg.setProperty("WEEKDAYS_MEDIUM",["Ned", "Pon", "Uto", "Sri", "Čet", "Pet", "Sub"]);
			orbx_calendar.cfg.setProperty("WEEKDAYS_LONG",  ["Nedjelja", "Ponedjeljak", "Utorak", "Srijeda", "Četvrtak", "Petak", "Subota"]);
			orbx_calendar.cfg.setProperty("START_WEEKDAY",  1);
		}
		orbx_calendar.cfg.setProperty("NAV_ARROW_LEFT",  '<?php echo ORBX_SITE_URL; ?>/orbicon/3rdParty/yui/build/calendar/assets/callt.gif');
		orbx_calendar.cfg.setProperty("NAV_ARROW_RIGHT",  '<?php echo ORBX_SITE_URL; ?>/orbicon/3rdParty/yui/build/calendar/assets/calrt.gif');

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

		orbx_calendar.selectEvent.subscribe(handleSelect_admin, orbx_calendar, true);
		orbx_calendar.changePageEvent.subscribe(myChangePageHandler, orbx_calendar, true);

		orbx_calendar.render();

		YAHOO.util.Event.addListener(["selMonth","selDay","selYear"], "change", updateCal_admin);
		myChangePageHandler();

	}

	YAHOO.util.Event.addListener(window,"load",init_exch_admin);

// ]]></script>
<!-- yui calendar -->

		<form name="dates">

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
				<?php echo $years; ?>
			</select>
		</form>
			<div id="cal1Container"></div>
			<div style="clear:both"></div>

