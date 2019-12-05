function update_admin_calendar()
{
	var menu = $('fond');
	var min = menu.options[menu.selectedIndex].getAttribute('min');
	var max = menu.options[menu.selectedIndex].getAttribute('max');
	var current = max.split('/');
	current = current[0] + '/' + current[2];

	dateAdmin.cfg.setProperty("pagedate", current);
	dateAdmin.cfg.setProperty("mindate", min);
	dateAdmin.cfg.setProperty("maxdate", max);

	dateAdmin.render();
}


function checkPostsForMonthFrom(month,year)
{
	var handleSuccess = function(o) {
	if(o.responseText !== undefined) {
			if(!empty(o.responseText)) {
				checkPostsCallbackFrom(o.responseText);
			}
		}
	}

	var callback =
	{
		success:handleSuccess,
		timeout: 15000
	};

	var menu = $('fond');
	var data = new Array();
	data[0] = 'month=' + month;
	data[1] = 'year=' + year;
	data[2] = 'fond=' + menu.options[menu.selectedIndex].value;

	data = data.join('&');

	YAHOO.util.Connect.asyncRequest('POST', orbx_site_url + '/orbicon/modules/invest/xhr.invest.admindates.php', callback, data);
};


function checkPostsCallbackFrom(dates) {

		dates = dates.split(',');

		for(i = 0; i < dates.length; i++) {
			dateAdmin.addRenderer(dates[i], myCustomRendererFrom);
		}
		dateAdmin.render();

};

