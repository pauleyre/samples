var inv_ticker_width;
var inv_moStop = true;		// pause on mouseover (true or false)
var inv_tSpeed = 2;			// scroll speed (1 = slow, 5 = fast)

var inv_cps = inv_tSpeed;
var inv_aw, inv_mq;

function inv_startticker()
{
	var rss_ticker = $('inv_ticker');
	inv_ticker_width = parseInt(__get_element_width('inv_ticker'));

	if(typeof rss_ticker == 'object' && !empty(rss_ticker)) {
		var tick = '<div style="position:relative;width:'+ticker_width+'px;height:17px;overflow:hidden;background:#ffffff;color:#000000;"';

		if(inv_moStop == true) {
			tick += ' onmouseover="javascript:inv_cps=0;" onmouseout="javascript:inv_cps=inv_tSpeed;"';
		}

		tick += '><div id="inv_mq" style="position:absolute;left:0px;top:0px;white-space:nowrap;"></div></div>';
		rss_ticker.innerHTML = tick;
		inv_mq = $('inv_mq');
		inv_mq.style.left = (parseInt(ticker_width) + 10) + "px";
		inv_mq.innerHTML = '<span style="font-size:90%;" id="inv_tx">'+_inv_ticker_content+'</span>';
		inv_aw = $('inv_tx').offsetWidth;

		lefttime = setInterval("inv_scrollticker()", 50);
	}
}

function inv_scrollticker()
{
	inv_mq.style.left = (parseInt(inv_mq.style.left) > (-10 - inv_aw)) ? (parseInt(inv_mq.style.left) - inv_cps) + 'px' : (parseInt(inv_ticker_width) + 10) + 'px';
}

try {
	YAHOO.util.Event.addListener(window, "load", inv_startticker);
} catch(e) {}