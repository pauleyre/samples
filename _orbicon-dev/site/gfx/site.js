function show_exch_calc()
{
	$('dialog1').style.display = 'block';
	YAHOO.example.container.dialog1.show();
}


YAHOO.namespace("example.container");

function init() {

	// Define various event handlers for Dialog
	var handleSubmit = function() {
	};
	var handleCancel = function() {
		this.cancel();
	};
	var handleSuccess = function(o) {
	};
	var handleFailure = function(o) {
		alert("Submission failed: " + o.status);
	};

	// Instantiate the Dialog
	YAHOO.example.container.dialog1 = new YAHOO.widget.Dialog("dialog1",
							{

								zIndex : 101,
								fixedcenter : true,
								visible : false,
								constraintoviewport : true,
								iframe: true
							});

	// Wire up the success and failure handlers
	YAHOO.example.container.dialog1.callback = { success: handleSuccess,
						     failure: handleFailure };

	// Render the Dialog
	YAHOO.example.container.dialog1.render();
}

YAHOO.util.Event.onDOMReady(init);

// -----------------------------------

function show_cred_calc()
{
	$('cred_calc').style.display = 'block';
	YAHOO.ccalc.container.cred_calc.show();
}

YAHOO.namespace("ccalc.container");

function init_ccalc() {

	// Define various event handlers for Dialog
	var handleSubmit = function() {
		credit_calc();
	};
	var handleCancel = function() {
		this.cancel();
		$('error_cred').innerHTML = '&nbsp;';
	};
	var handleResults = function() {
		this.submit();
	};
	var handleSuccess = function(o) {
	};
	var handleFailure = function(o) {
		window.alert("Failure: " + o.status);
	};

	var ln_calculate;
	var ln_cancel;
	switch(__orbicon_ln) {
		case 'hr':
			ln_calculate = 'Izračunaj';
			ln_cancel = 'Zatvori';
		break;
		case 'en':
			ln_calculate = 'Calculate';
			ln_cancel = 'Cancel';
		break;
	}

	// Instantiate the Dialog
	YAHOO.ccalc.container.cred_calc = new YAHOO.widget.Dialog("cred_calc",
																{
																  zIndex : 100,
																  fixedcenter : true,
																  visible : false,
																  iframe: true,
																  constraintoviewport : true,
																  buttons : [ { text:ln_calculate, handler:handleSubmit },
																			  { text:ln_cancel, handler:handleCancel }
																			  ]
																 } );

	// Validate the entries in the form to require that both first and last name are entered
	YAHOO.ccalc.container.cred_calc.validate = function() {
		var data = this.getData();
		if (data.name == "") {
			return false;
		} else {
			return true;
		}
	};

	// Wire up the success and failure handlers
	YAHOO.ccalc.container.cred_calc.callback = { success: handleSuccess,
												 failure: handleFailure };

	// Render the Dialog
	YAHOO.ccalc.container.cred_calc.render();
}

YAHOO.util.Event.addListener(window, "load", init_ccalc);

// stylesheet

function save_stylesheet(sheet)
{
	YAHOO.util.Cookie.set("fontsize", sheet);
	window.location.reload();
}

function write_stylesheet()
{
	var percent = 0;

	if(YAHOO.util.Cookie.get("fontsize") == 'big') {
		percent = 130;
		 YAHOO.util.Dom.addClass('bigger', 'active');
	}
	if(YAHOO.util.Cookie.get("fontsize") == 'small') {
		percent = 110;
		 YAHOO.util.Dom.addClass('smaller', 'active');
	}

	if(!empty(percent)) {
		document.write('<style type="text/css">#innerContent{font-size:'+percent+'% !important}</style>');
	}
	else {
		 YAHOO.util.Dom.addClass('normal', 'active');
	}
}

// quotes

var quotes = ['<cite>Nešto što možemo raditi, nešto što ćemo voljeti i nešto čemu se možemo nadati, tri su najvažnija sastojka za sretan život.</cite> <strong class="signature">ALLAN K. CHALMERS</strong>',
'<cite>Ambicija je sjeme ljudskog rasta i plemenitosti.</cite> <strong class="signature">OSCAR WILDE</strong>',
'<cite>Kada nešto dugo očekuješ, onda to kad se napokon dogodi poprima dimenziju neočekivanog.</cite> <strong class="signature">MARK TWAIN</strong>',
'<cite>Ako vam nešto izgleda kao kraj, sjetite se da je zemlja okrugla i da svaki kraj ujedno može biti početak.</cite> <strong class="signature">IVY BAKER PRIEST</strong>',
'<cite>Optimist vidi priliku u svakoj poteškoći, a pesimist poteškoću u svakoj prilici.</cite> <strong class="signature">WINSTON CHURCHILL</strong>',
'<cite>Ako želite postići velike stvari, morate djelovati, ali i sanjati… Planirati, ali i vjerovati.</cite> <strong class="signature">ANATOLE FRANCE</strong>',
'<cite>Biramo svoju sreću i tugu puno prije negoli ih zaista osjetimo.</cite> <strong class="signature">KAHLIL GIBRAN</strong>',
'<cite>Mnogo ljudi vjeruje da se stvari mijenjaju s vremenom, no istina je da promjenu morate napraviti sami.</cite> <strong class="signature">ANDY WARHOL</strong>',
'<cite>Budimo zahvalni ljudima koji nas usrećuju, jer su upravo oni šarmantni vrtlari uz koje naše duše cvatu.</cite> <strong class="signature">MARCEL PROUST</strong>'
];

var quotes_en = [

'<cite>We choose our happiness and our sorrow before we actually feel them.</cite> <strong class="signature">KAHLIL GIBRAN</strong>',
'<cite>If you wish to accomplish great things, you have to act but also dream… You have to plan, but also believe.</cite> <strong class="signature">ANATOLE FRANCE</strong>',
'<cite>If something looks like the end, remember that the Earth is round and that every end can also be a beginning.</cite> <strong class="signature">IVY BAKER PRIEST</strong>',
'<cite>Many people believe that things change with time, but the truth is that it is you yourself who has to make the change.</cite> <strong class="signature">ANDY WARHOL</strong>',
'<cite>Let us be grateful to the people who make us happy because they are the charming gardeners that make our souls blossom.</cite> <strong class="signature">MARCEL PROUST</strong>',
'<cite>When you\'re expecting something for a long time, it seems unexpected when it happens at last.</cite> <strong class="signature">MARK TWAIN</strong>',
'<cite>The optimist sees an opportunity in every obstacle, and a pessimist an obstacle in every opportunity.</cite> <strong class="signature">WINSTON CHURCHILL</strong>',
'<cite>The ambition is the seeds of human growth and nobility.</cite> <strong class="signature">OSCAR WILDE</strong>'

];


function quote()
{
	var q = undefined;
	while(q == undefined) {
		if(__orbicon_ln == 'hr') {
			q = quotes[Math.round(Math.random() * 8) + 1];
		}
		else {
			q = quotes_en[Math.round(Math.random() * 8) + 1];
		}
	}
	return q;
}

YAHOO.util.Event.addListener(window, "load", function () {try {$('goodToKnow').innerHTML = quote()} catch (e) {}});

// userbox
var userbox_links = ['linkGradjanstvo', 'linkPoduzetnistvo', 'linkVelikeTvrtke', 'linkInvBankarstvo', 'linkIzravnoBankarstvo', 'linkMreza', 'linkOnama'];

function add_userbox()
{
	try {
		var i = 1;
		var userboxHTML;

		if(user_member) {
			userboxHTML = $('showHideMember').innerHTML;
		}
		else {
			userboxHTML = $('showHide').innerHTML;
		}

		for(i = 1; i <= 7; i += 1) {
			$('ctr_link'+i).innerHTML = userboxHTML;
		}
	}
	catch(e) {}
}

YAHOO.util.Event.addListener(window, "load", add_userbox);

function switch_regional_input(value)
{
	if(empty(value)) {
		return;
	}

	if(value != 82) {
		$('cro_only').style.display = 'none';
		$('other_only').style.display = 'block';
	}
	else {
		$('cro_only').style.display = 'block';
		$('other_only').style.display = 'none';
	}
}

function switch_towns(county, country_code, container, id, name)
{
	if(/*(county == 1) || */empty(county)) {
		return;
	}

	// stop people from using it until we updated
	$(id).disabled = true;

	var handleSuccess = function(o) {
		if(o.responseText !== undefined) {
			$(container).innerHTML = '<select id="' + id + '" name="' + name + '" class="select big"><option value="">&mdash; Sva mjesta &mdash;</option>' + o.responseText + '</select>';
		}
	}

	var callback =
	{
		success:handleSuccess,
		timeout: 15000
	};

	try {
		if(county == 2) {
			$('zg').disabled = false;
		}
		else {
			$('zg').disabled = true;
		}
	} catch(e) {}

	var data = new Array();
	data[0] = 'county=' + county;
	data[1] = 'country_code=' + country_code;

	data = data.join('&');

	YAHOO.util.Connect.asyncRequest('POST', orbx_site_url + '/orbicon/modules/peoplering/xhr.pring_towns.php', callback, data);
}

// MENU

/*var mtimeout	= 750;
var mclosetimer	= 0;
var mddmenuitem	= null;
var mshowthatmenu = false;

function mshow(id)
{
	mshowthatmenu = true;
	window.setTimeout(function(){_mopen(id)}, mtimeout);
}

function _mopen(id)
{
	if(mshowthatmenu) mopen(id);
}

// open hidden layer
function mopen(id)
{
	// cancel close timer
	mcancelclosetime();

	// close old layer
	if(mddmenuitem) mddmenuitem.style.display = 'none';

	// get new layer and show it
	mddmenuitem = document.getElementById(id);
	mddmenuitem.style.display = 'block';
}

// close showed layer
function mclose()
{
	if(mddmenuitem) {
		mddmenuitem.style.display = 'none';
		mddmenuitem = null;
	}
	mshowthatmenu = false;
}

// go close timer
function mclosetime()
{
	mclosetimer = window.setTimeout(mclose, mtimeout);
}

// cancel close timer
function mcancelclosetime()
{
	if(mclosetimer) {
		window.clearTimeout(mclosetimer);
		mclosetimer = null;
	}
}

// close layer when click-out
YAHOO.util.Event.addListener(document, 'click', mclose);*/