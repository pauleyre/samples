$(document).ready(function() {

var showText="Prijava";
var hideText="Zatvori";

$('#showHide').hide();

$('a#toggle_link').click(function() {

if ($('a#toggle_link').text()==showText) {
$('a#toggle_link').text(hideText);
}
else {
$('a#toggle_link').text(showText);
}
$('#showHide').toggle('slow');

// return false so any link destination is not followed
return false;
				});

});