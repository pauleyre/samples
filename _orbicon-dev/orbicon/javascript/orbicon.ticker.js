var ticker_width;
var moStop = true;		// pause on mouseover (true or false)
var tSpeed = 2;			// scroll speed (1 = slow, 5 = fast)

var cps = tSpeed;
var aw, mq;
function startticker()
{
	var rss_ticker = $('ticker');
	ticker_width = parseInt(__get_element_width('ticker'));

	if(typeof rss_ticker == 'object' && !empty(rss_ticker)) {
		var tick = '<div id="ticker_cnt" style="position:relative;width:'+ticker_width+'px;height:17px;overflow:hidden;background:#ffffff;color:#000000;"';

		if(moStop == true) {
			tick += ' onmouseover="javascript:cps=0;" onmouseout="javascript:cps=tSpeed;"';
		}

		tick += '><div id="mq" style="position:absolute;left:0px;top:0px;white-space:nowrap;"></div></div>';
		rss_ticker.innerHTML = tick;
		mq = $('mq');
		mq.style.left = (parseInt(ticker_width) + 10) + "px";
		mq.innerHTML = '<span style="font-size:90%;" id="tx">'+__ticker_content+'</span>';
		aw = $("tx").offsetWidth;

		lefttime = setInterval("scrollticker()", 50);
	}
}

function scrollticker()
{
	//alert(cps);

	mq.style.left = (parseInt(mq.style.left) > (-10 - aw)) ? (parseInt(mq.style.left) - cps) + 'px' : (parseInt(ticker_width) + 10) + 'px';
}

try {
	//YAHOO.util.Event.addListener(window, "load", startticker);
} catch(e) {}