<?php

	$stk = new Stock();

	if(isset($_POST['submit_fund_value'])){

		$stock_set = new Stock($_POST);

		if(isset($_GET['id'])){
			$stock_set->edit_stock();
		} else {
			$stock_set->set_stock();
		}
	}

	if(isset($_GET['id'])){
		$stk_tmp_res = $stk->get_stock($_GET['id']);
		$stock = $dbc->_db->fetch_array($stk_tmp_res);
	}

?>
<script type="text/javascript"><!-- // --><![CDATA[
function confirmDate()
{
	if(confirmValue == false){
		document.fvalue_form.stock_value.focus();
		return false;
	}

	var dateFld = document.fvalue_form.date1.value;

	if(dateFld != ''){
		return true;
	} else {
		return false;
	}

}

function confirmValue()
{
	if(empty(document.fvalue_form.stock_value.value)) {
		return false;
	}
	return true;
}
// ]]></script>
<form name="fvalue_form" id="fvalue_form" method="post" action="" enctype="multipart/form-data" onsubmit="javascript: return confirmDate();">
<input type="hidden" name="id" id="id" value="<?php echo $_GET['id']; ?>" />
<div class="quater_view">
<p>
	<label for="stock_value"><?php echo _L('invest-fond-value'); ?></label><br />
	<input type="text" name="stock_value" id="stock_value" value="<?php echo $stock['stock_value']; ?>" />
</p>
<p>
	<label for="fond"><?php echo _L('invest-fond-title'); ?></label><br />
	<select id="fond" name="fond" <?php if(!isset($_GET['id'])){ echo 'onchange="javascript: update_admin_calendar();"'; }?>>
	<?php
		$fonds = new Fond();

		$tmp_fond_res = $fonds->get_all_fonds(1);
		while($fond = $dbc->_db->fetch_array($tmp_fond_res)){


			if(isset($_GET['id'])){
				$selected = ($stock['fond'] == $fond['id']) ? ' selected="selected"' : '';
			} else {

				$testFund = $stk->testFund($fond['id']);
				$dis = ($testFund > 0) ? ' disabled="disabled"': '';

				$selected = '';
			}

			$range = $stk->get_date_range($fond['id']);
			$min = $range['lowest'];
			$max = $range['highest'];

			// * reformat db values from yyyy-mm-dd into mm/dd/yyyy
			$min = explode('-', $min);
			$max = explode('-', $max);

			$min = $min[1] . '/' . $min[2] . '/' . $min[0];
			$max = date('m/d/Y');

			echo '<option max="'.$max.'" min="'.$min.'" value="'.$fond['id'].'"'.$selected.$dis.'>'.$fond['title'].'</option>';


		}
	?>
	</select>
</p>
<br />
<p>
	<input type="submit" name="submit_fund_value" id="submit_fund_value" value="<?php echo _L('save');?>" class="chk_btn" />
</p>
</div>

<div class="quater_view">
<?php

if(!isset($_GET['id'])){
	$date_range = $stk->get_date_range();

	// * reformat db values from yyyy-mm-dd into mm/dd/yyyy
	$min_d = explode('-', $date_range['lowest']);

	$min_date = $min_d[1] . '/' . $min_d[2] . '/' . $min_d[0];

	// * generate max date
	$max_date = date("m/d/Y");

	$curd_src = split(' ', $stock['date']);
	$curent_date_src = split('-', $curd_src[0]);
	$cds = $curent_date_src[1] . '/' . $curent_date_src[2] . '/' . $curent_date_src[0];



	echo '
<script type="text/javascript"><!-- // --><![CDATA[

	var dateAdmin = null;

	function initAdminCalc() {

		var url = orbx_site_url;

		dateAdmin = new YAHOO.widget.Calendar("calAdmin","date",
																	{   mindate: "'.$min_date.'",
																	  	maxdate: "'.$max_date.'"}
																	 );

		if(__orbicon_ln == "hr") {

			dateAdmin.cfg.setProperty("MONTHS_SHORT",   ["Sij", "Velj", "Ožu", "Tra", "Svi", "Lip", "Srp", "Kol", "Ruj", "List", "Stu", "Pro"]);
			dateAdmin.cfg.setProperty("MONTHS_LONG",    ["Siječanj", "Veljača", "Ožujak", "Travanj", "Svibanj", "Lipanj", "Srpanj", "Kolovoz", "Rujan", "Listopad", "Studeni", "Prosinac"]);
			dateAdmin.cfg.setProperty("WEEKDAYS_1CHAR", ["N", "P", "U", "S", "Č", "P", "S"]);
			dateAdmin.cfg.setProperty("WEEKDAYS_SHORT", ["Ne", "Po", "Ut", "Sr", "Če", "Pe", "Su"]);
			dateAdmin.cfg.setProperty("WEEKDAYS_MEDIUM",["Ned", "Pon", "Uto", "Sri", "Čet", "Pet", "Sub"]);
			dateAdmin.cfg.setProperty("WEEKDAYS_LONG",  ["Nedjelja", "Ponedjeljak", "Utorak", "Srijeda", "Četvrtak", "Petak", "Subota"]);
			dateAdmin.cfg.setProperty("START_WEEKDAY",  1);
		}

		dateAdmin.cfg.setProperty("NAV_ARROW_LEFT",  url + "/orbicon/3rdParty/yui/build/calendar/assets/callt.gif");
		dateAdmin.cfg.setProperty("NAV_ARROW_RIGHT",  url + "/orbicon/3rdParty/yui/build/calendar/assets/calrt.gif");


		// * trigger the selection
		dateAdmin.selectEvent.subscribe(handleSelectAdminDate, dateAdmin, true);

		// * display it
		dateAdmin.render();

	}

	// * this function handles selection from calendar
	function handleSelectAdminDate(type,args,obj) {
	    var dates = args[0];
	    var date = dates[0];
	    var year = date[0], month = date[1], day = date[2];

	    var txtDate1 = document.getElementById("date1");
	    txtDate1.value = month + "/" + day + "/" + year;
	}

	// * initialize calendar
	YAHOO.util.Event.addListener(window, "load", initAdminCalc);
// ]]></script>


	<div id="date"></div>
	<input type="hidden" name="date1" id="date1" />
	';
} else {
	$date = split(' ', $stock['date']);
	$date = split('-', $date[0]);
	$date = $date[2] .'.'. $date = $date[1] .'.'. $date = $date[0] .'.';

	echo '
		<label for="date">'._L('date').'</label><br />
		<input type="text" name="date" id="date" value="'.$date.'" disabled="disabled" />';
}
?>
</div>
<div class="cleaner"></div>

</form>
<br />
<!--
<table id="todays_stocks">
	<caption><?php echo _L('invest-share-value-overview');?></caption>
	<tr>
		<th><?php echo _L('invest-overview-fond');?></th>
		<th><?php echo _L('invest-overview-currency');?></th>
		<th><?php echo _L('invest-overview-value');?></th>
		<th><?php echo _L('invest-overview-date');?></th>
	</tr>
<?php

	$i = 1;

	$fondObject = new Fond();

	$fondListing = $fondObject->get_all_fonds(1);
	while($fondItem = $dbc->_db->fetch_array($fondListing)){

		$stock_item = $stk->get_todays_stock_listing($fondItem['id']);

		$stockValue = ($stock_item['stock_value'] != '') ? $stock_item['stock_value'] : _L('invest-na');
		$stockDate = ($stock_item['date'] != '') ? $stock_item['date'] : _L('invest-na');

		$high = ($i%2 == 0) ? ' class="high"' : '';

		$currencyObj = new Currency;
		$curr_res = $currencyObj->get_currency($fondItem['currency']);
		$curr = $dbc->_db->fetch_array($curr_res);

		echo '
			<tr>
				<td align="center"'.$high.'>'.$fondItem['title'].'</td>
				<td align="center"'.$high.'>'.$curr['title'].'</td>
				<td align="center"'.$high.'>';
		if($stock_item['stock_value'] != ''){
			echo '<a href="?'.$orbicon_x->ptr.'=orbicon/mod/invest&amp;showPage=fond&amp;do=fondvalue&amp;id='.$stock_item['id'].'" name="'.$stock_item['stock_value'].'">'.$stockValue.'</a>';
		} else {
			echo $stockValue;
		}

		echo '	</td>
				<td align="center"'.$high.'>'.$stockDate.'</td>
			</tr>
		';

		$i++;

	}

	?>
</table> -->