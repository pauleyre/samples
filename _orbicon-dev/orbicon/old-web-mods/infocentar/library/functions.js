function RichTextSave()
{
	if(nViewMode == 2) {
		window.alert('You cannot save while in HTML source view.\nSwitch back to normal view and try again.');
		return false;
	}

	// disable editor
	try {
		oToolbar.execCommand('contentReadOnly', false, true);
	} catch(e) {}

	// fetch RTE content
	var rte_data = RichTextCaptureData('return');

	// assign it to hidden "rte_data" input
	$('content').value = rte_data;

	YAHOO.util.Event.addListener('question_form','submit',RichTextSave);
}

function RichTextNew()
{
	RichTextFocus();
	oToolbar.body.innerHTML = "";
}


function checkList(el, id)
{
	var obj = $(id);
	if(el.checked == true) {
		obj.style.display = 'block';
	} else {
		obj.style.display = 'none';
	}

}

function clearField(el, msg)
{
	if(msg == ''){
		el.value = '';
	} else {
		el.value = msg;
	}
}

function removeTitleFromSubmit() {
	// this pops up in the backend, so ignore it
	try {
		$('submit_search').value = '';
	}
	catch(e) {}
}

var search_opened = true;
var ask_opened = false;
var status_form_holder = 'on';
var num= 0;
function animate_holder(num)
{
	if(status_form_holder == 'off') {
		// now animate it
		var open_box = new YAHOO.util.Anim('form_holder', { height: {from: 0, to: num} }, 0.2);
		open_box.animate();
		status_form_holder = 'on';
	}
	else {
		var open_box1 = new YAHOO.util.Anim('form_holder', { height: {from: num, to: 0} }, 0.2);
		open_box1.animate();
		status_form_holder = 'off';
	}
}

function open_search() {
	search_opened = true;
	var el = $('search_holder');
	el.style.display = 'block';
	var elHeight = el.offsetHeight + 10;
	animate_holder(elHeight);
}

function close_search() {
	search_opened = false;
	var el = $('search_holder');
	animate_holder(el.offsetHeight);
	setTimeout(function () {hideItem(el); }, 100);
}

function open_ask() {
	ask_opened = true;
	var el = $('ask_holder');
	el.style.display = 'block';
	var elHeight = el.offsetHeight + 10;
	animate_holder(elHeight);
}

function close_ask() {
	ask_opened = false;
	var el = $('ask_holder');
	animate_holder(el.offsetHeight);
	setTimeout(function (){hideItem($("ask_holder"));}, 100);
}

function show_search()
{
	if(search_opened == true) {
		return false;
	}

	var el_search = $('search_holder').style.display;
	var el_ask = $('ask_holder').style.display;

	if(el_ask != 'none' || el_ask == ''){
		close_ask();
	}

	if(el_search != 'block' || el_search == ''){
		setTimeout(open_search, 300);
	}
	else {
		return;
	}
}

function hideItem(el)
{
	el.style.display = 'none';
}

function show_ask()
{
	if(ask_opened == true) {
		return false;
	}

	var el_search = $('search_holder');
	var el_ask = $('ask_holder');

	if(el_search != 'none' || el_search.style.display == ''){
		close_search();
	}

	if(el_ask.style.display != 'block' || el_ask.style.display == ''){
		setTimeout(open_ask, 300);
	}
	else {
		return;
	}
}

YAHOO.util.Event.addListener('search_view', 'click', show_search);
YAHOO.util.Event.addListener('ask_view', 'click', show_ask);
YAHOO.util.Event.addListener(window, 'load', removeTitleFromSubmit);

// this is for rating stars
function hilite_add(level, group)
{
	level = parseInt(level);
	var i = 1;
	$('vote_response_' + group).innerHTML = ic_scores[level];

	while(i <= level) {
		$('sg_' + group + '_' + i).style.backgroundImage = 'url(orbicon/modules/infocentar/gfx/over.png)';
		i ++;
	}
}

function hilite_remove(group)
{
	var i = 1;
	var star_bg;
	var el;
	$('vote_response_' + group).innerHTML = ic_scores[0];

	while(i <= 5) {
		el = $('sg_' + group + '_' + i);
		star_bg = (el.className == 'sg_a sg_starred') ? 'url(orbicon/modules/infocentar/gfx/active.png)' : 'url(orbicon/modules/infocentar/gfx/inactive.png)';
		el.style.backgroundImage = star_bg;
		i++;
	}
}

function submit_vote(vote, answer_id, qid, uid)
{
	// path to our script
	var url = orbx_site_url + '/orbicon/modules/infocentar/xhr.rating.php';

	// lets build variables
	var data = "voteValue=" + vote + "&answer=" + answer_id + "&qid=" + qid;
	var display_area = $('star_holder_' + answer_id);

	// build handler, YUI stuff
	var handleSuccess = function(o){

		if(o.responseText !== undefined){
			display_area.innerHTML = o.responseText;
		}
	}

	var handleFailure = function(o){

		if(o.responseText !== undefined){
			display_area.innerHTML = o.statusText;
		}
	}

	var callback =
	{
	  success:handleSuccess,
	  failure: handleFailure
	};

	YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
}

function testFormField(formElement, flds)
{
	var countFields = flds.count;
	for(var i=0; i<=countFields; i++){
		if(formElement.flds[i].value == ''){
			return false;
		}
	}

	return true;
}

function askConfirmation(msg)
{
	var ask = confirm(msg);
	
	if(ask){
		return true;
	}
	
	return false;
}

var itemstate = 0;
function handleFrontpageMenu(id)
{
	if(itemstate == 0){
		document.getElementById(id).style.display = 'none';
		itemstate = 1;
	} else {
		document.getElementById(id).style.display = 'block';
		itemstate = 0;
	}
}