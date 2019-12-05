<?php

header('Content-Type: text/html; charset=UTF-8', true);

// -- INCLUDE PATH SETUP -----------------------------
$inc_dir = dirname(dirname(__FILE__));

$inc_root = $inc_dir . '/root.dir';

$inc_found = false;

while(!$inc_found) {

	if(is_file($inc_root)) {
		$inc_found = true;
		break;
	}

	$inc_dir = dirname(dirname($inc_root));

	$inc_root = $inc_dir . '/root.dir';
}

set_include_path($inc_dir);
// -- INCLUDE PATH SETUP ENDS -------------------------

include 'logic/class.Question.php';

global $q;
if(isset($_GET['id'])) {
	$q = new Question($_GET['id']);

	if(isset($_POST['submit']) || isset($_POST['archive'])) {

		$q->category = $_POST['category'];
		$q->title = $_POST['title'];
		$q->subject = $_POST['subject'];
		$q->live = (isset($_POST['submit'])) ? 1 : 0;

		$q->setQuestion();

		include '../../logic/func.rss2.php';
		if($_POST['category'] != '') {
			rss2('../../web/rss/' . $_POST['category'] . '.xml', $q, $_POST['category']);
		}
		rss2('../rss/Sve.xml', $q, '');
	}

	if(isset($_POST['delete'])) {
		$q->delete();
	}

	$q->getQuestion();
	$color = 'green';
	$color_status = (!$q->live) ? 'red' : 'green';

}
else {

	$q = new Question();

	if(isset($_POST['submit']) || isset($_POST['archive'])) {

		$q->category = $_POST['category'];
		$q->member_id = $_SESSION['member']['id'];
		$q->title = $_POST['title'];
		$q->subject = $_POST['subject'];
		$q->live = (isset($_POST['submit'])) ? 1 : 0;
		$q_id = $q->setQuestion();

		include '../../logic/func.rss2.php';
		if($_POST['category'] != '') {
			rss2('../rss/' . $_POST['category'] . '.xml', $q, $_POST['category']);
		}
		rss2('../rss/Sve.xml', $q, '');
	}

	$color = 'red';
	$disabled = ' disabled="disabled"';
	$color_status = 'orange';
}

include_once 'logic/func.related.php';

?>
<html>

<head>
<title>EDITOR PITANJA</title>
<style type="text/css">

body {
text-align:center;
}

table {
font-size: 14px;
}

.long {
width: 600px;
margin-bottom:10px;
}

.short {
width: 197px;
margin-bottom:10px;
}

.tall {
height: 25em;
}

.medium {
height: 5em;
}

.big_font {
font-size:1.5em;
}

.left_margin {
margin-left:200px;
}

</style>

<!-- Combo-handled YUI CSS files: -->
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/combo?2.7.0/build/assets/skins/sam/skin.css" />

<style type="text/css">

    /*
        Set the "zoom" property to "normal" since it is set to "1" by the
        ".example-container .bd" rule in yui.css and this causes a Menu
        instance's width to expand to 100% of the browser viewport.
    */

    div.yuimenu .bd {

        zoom: normal;

    }

    /*
        Restore default padding of 10px for the calendar containtainer
        that is overridden by the ".example-container .bd .bd" rule
        in yui.css.
    */

    #calendarcontainer {

        padding:10px;

    }

    #calendarmenu {

        position: absolute;

    }

    #calendarpicker button {

        background: url(../img/calendar_icon.gif) center center no-repeat;
        text-align: left;
        text-indent: -10em;
        overflow: hidden;
        *margin-left: 10em; /* For IE */
        *padding: 0 3em;    /* For IE */
        white-space: nowrap;

    }

    #month-field,
    #day-field,
    .w2em {

        width: 2em;

    }

    #year-field {

        width: 3em;

    }

	#calendarpicker  {

		vertical-align: bottom;

	}

	#yui-dt0-paginator0 {
		margin: 0 0 6px;
	}


</style>

</head>

<body>


<h2>EDITOR PITANJA <span style="color:<?php echo $color_status; ?>"><?php echo $str = (isset($_GET['id'])) ? '#'.$_GET['id'] : 'NOVO'; ?></span></h2>

<table style="width:100%">
<tbody>

<tr>

	<td style="width:50%;vertical-align:top">

	<form method="post" action="" name="qform">

		<select name="category" onchange="colorcheck(this, 'select', '')" class="long" style="color:<?php echo $color; ?>">
			<option <?php echo (!$q->category) ? 'selected="selected"' : '' ?> style="color:red" value="">KATEGORIJA</option>
		<?php

			include 'logic/func.categories.php';
			echo categories_menu($q->category, 'green', true);
		?>

		</select><br/>

		<textarea id="title" name="title" onchange="colorcheck(this, 'textarea', 'PITANJE')" class="long tall" style="color:<?php echo $color; ?>"><?php echo ($q->title) ? stripslashes($q->title): 'PITANJE' ?></textarea><br/>
		TEMA: <input type="text" name="subject" value="<?php echo htmlspecialchars($q->subject) ?>"><br>

		<input class="big_font" onclick="document.location = ''" type="button" value="NOVO" <?php echo $disabled;  ?> style="color:orange" />
		<input name="archive" class="big_font" onclick="return myconfirm('archive'))" type="submit" value="SPREMI &amp; ARHIVIRAJ" style="color:red"  />
		<input name="submit" class="big_font" onclick="return myconfirm('publish'))" type="submit" value="SPREMI &amp; OBJAVI" style="color:green" />
		<input name="delete" class="big_font" onclick="return myconfirm('delete'))" type="submit" value="X" style="color:red" />

	</form><br/>

	<p>
		<strong>HELP:</strong>
		<a style="<?php echo (!$_GET['id']) ? 'text-decoration: line-through;'  : '' ?>" target="_blank" href="../../?<?php echo $q->category . ',' . $q->permalink . '&d=' . $q->getId(); ?>&amp;a">pregled pitanja</a>
	</p>

	Slična pitanja:

	<ol>
	<?php
	$x = clean($q->title);
var_dump($x);
	$q_similar_r = $q->getQuestions('', 10, 1, null, 'live_time', clean($q->title));
	$q_similar = $db->fetch_assoc($q_similar_r);

	while ($q_similar) {
		echo "<li>{$q_similar['title']}</li>";
		$q_similar = $db->fetch_assoc($q_similar_r);
	}

	?>
	</ol>
	</td>
	<td style="width:25%;vertical-align:top" class="yui-skin-sam">
<a href="./">SVE</a> -
	<?php

		$categories = get_categories(true);

		foreach($categories as $category) {

			echo "<a title=\"$category\" href=\"./?c=$category\">$category</a> - ";
		}

	?>

	<br>
	<a href="./?status=0&c=<?php echo $_GET['c'] ?>" class="red">Neobjavljena</a> -
	<a href="./?status=1&c=<?php echo $_GET['c'] ?>" class="green">Objavljena</a> -
	<a href="./?status=-1&c=<?php echo $_GET['c'] ?>">Sva</a>
	<br>

		<div id="complex"></div>
	</td>
</tr>

</tbody>
</table>

<script type="text/javascript">

function colorcheck(el, type, check)
{
	var value;

	if(type == 'select') {
		value = el.options[el.selectedIndex].value;
	}
	else if (type == 'input') {
		value = el.value;
	}
	else if (type == 'textarea') {
		value = el.value;
	}

	if(value == check) {
		el.style.color = 'red';
	}
	else {
		el.style.color = 'green';
	}
}

function myconfirm(type)
{
	var msg;
	if(type == 'archive') {
		msg = 'Arhiviraj ovo pitanje?';
	}
	else if(type == 'publish') {
		msg = 'Objavi ovo pitanje?';
	}
	else if(type == 'delete') {
		msg = 'Izbriši ovo pitanje (i njegove odgovore)?';
	}

	if(window.confirm(msg)) {
		return true;
	}
	return false;
}

</script>

<!-- Combo-handled YUI JS files: -->
<script type="text/javascript" src="http://yui.yahooapis.com/combo?2.7.0/build/utilities/utilities.js&2.7.0/build/datasource/datasource-min.js&2.7.0/build/paginator/paginator-min.js&2.7.0/build/datatable/datatable-min.js"></script>

<script type="text/javascript">

YAHOO.example.Data = {
	news: [
	<?php include '../../logic/render.QuestionsJS.php'; ?>
	]
};

YAHOO.util.Event.addListener(window, "load", function() {
    YAHOO.example.MultipleFeatures = function() {

		var formatUrl = function(elCell, oRecord, oColumn, sData) {
			elCell.innerHTML = '<a href="' + oRecord.getData('url') + '" title="'+sData+'">' + sData + '</a>';
		};

        var myColumnDefs = [
            {key:'id',label:'ID',formatter:'number',width:50,resizeable:true,sortable:true},
            {key:'title',label:'Title',formatter:formatUrl,width:250,resizeable:true,sortable:true},
            {key:'category',label:'Category',resizeable:true,sortable:true},
            {key:'live_time',label:'Date',formatter:'date',resizeable:true,sortable:true},
            {key:'live',label:'Live',formatter:'number',resizeable:true,sortable:true}
        ];

        var myDataSource = new YAHOO.util.DataSource(YAHOO.example.Data.news);
        myDataSource.responseType = YAHOO.util.DataSource.TYPE_JSARRAY;
        myDataSource.responseSchema = {
            fields: ['id','title','category','live_time','live','url']
        };

        var myConfigs = {
            sortedBy:{key:"live_time",dir:"desc"},
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: 25,
                template: YAHOO.widget.Paginator.TEMPLATE_ROWS_PER_PAGE,
                rowsPerPageOptions: [10,25,50,100],
                pageLinks: 5
            })
        }

        var myDataTable = new YAHOO.widget.DataTable("complex", myColumnDefs, myDataSource, myConfigs);
        myDataTable.subscribe("rowClickEvent",myDataTable.onEventSelectRow);

        return {
            oDS: myDataSource,
            oDT: myDataTable
        };
    }();
});
</script>

</body>
</html>