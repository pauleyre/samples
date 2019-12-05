<?php

switch($_GET['action']) {
	case 'ab':
		$panel = 'web/panel/panel.address_book.php';
		$title = 'Adresar';
	break;
	case 'pr':
		$panel = 'web/panel/panel.project.php';
		$title = 'Radni nalog';
	break;
	case 'em':
		$panel = 'web/panel/panel.employee.php';
		$title = 'Zaposlenici';
	break;
	case 'vh':
		$panel = 'web/panel/panel.vehicle.php';
		$title = 'Službena vozila';
	break;
	case 'lk':
		$panel = 'web/panel/panel.loko.php';
		$title = 'Loko vožnja';
	break;
	case 'sk':
		$panel = 'web/panel/panel.absence.php';
		$title = 'Izostanci';
	break;
	case 'ca':
		$panel = 'web/panel/panel.calc.php';
		$title = 'Kalkulacija';
	break;
	case 'dc':
		$panel = 'web/panel/panel.docs.php';
		$title = 'Dokumenti';
	break;
	default:
		if(isset($_GET['q'])) {
			$panel = 'web/panel/panel.search.php';
			$title = 'Rezultati pretrage';
		}
		else {
			$panel = 'web/panel/panel.todo.php';
			$title = 'Todo';
		}
	break;
}

?>
<html>

<head>
<title>Manager: <?php echo $title ?></title>
<link href=web/css/main.css rel=stylesheet>
<script src=web/js/main.js></script>

<!--[if gte IE 5]>

<style>
.menuc {
	position: absolute;
	top: expression(0+((e=document.documentElement.scrollTop)?e:document.body.scrollTop)+'px');
	left: expression(0+((e=document.documentElement.scrollLeft)?e:document.body.scrollLeft)+'px');}
}
</style>

<![endif]-->

</head>

<body>

<div class=menuc onmouseover="$('menu').style.visibility='visible'" onmouseout="$('menu').style.visibility='hidden'">
<div id=menu>

<ul id="navbar">
	<!-- The strange spacing herein prevents an IE6 whitespace bug. -->
	<li><a href=./?odjava>Izlaz</a></li>
	<li><a href="javascript:;">Otvori...</a>
		<ul>
			<li><a href=./?action=pr>Radni nalog</a></li><li>
			<a href="#">Predračun</a></li><li>
			<a href="#">Ponuda</a></li><li>
			<a href="#">Procjena</a></li><li>
			<a href="./?action=ca">Brzi račun</a></li>
		</ul>
		</li>
	<li><a href="javascript:;">Evidencija</a>
		<ul>
		<li><a href="">Dnevnici rada</a></li><li>
		<a href="">Loko vožnja</a></li><li>
		<a href="">Izostanci</a></li>
		</ul>

	</li>
	<li><a href="javascript:;">Podesi</a>
		<ul>
			<li><a href="#">Manager postavke</a></li><li>
			<a href="./?action=ab&id=1">Podaci</a></li><li>
			<a href="./?action=em">Zaposlenici</a></li><li>
		</ul>
	</li>
</ul>
</div>
</div>

<table style="width:100%;height:100%" border=1>

<tr>

<td colspan=2 height=70%><div style="overflow:auto;height:100%"><?php include $panel; ?></div></td>

</tr>

<tr>
<td width=30%>
<input type=button onclick="redirect('./')" value=ZADACI><br>
<input type=button onclick="redirect('./?action=ab')" value=ADRESAR><br>
<input type=button onclick="modal('web/modal/loko.php', 300, 300)" value="LOKO VOŽNJA"><br>
<input type=button onclick="modal('web/modal/absence.php', 300, 300)" value=IZOSTANCI><br>
<input type=button onclick=sh_comm() value=CHAT id=comm_li><br>
<input type=button onclick="redirect('./?action=dc')" value=DOKUMENTI><br>

</td>
<td>

<form method=GET action="">
<input name="q" style="width:90%"><input value="Traži" type="submit">
</form>

<div id="infobox" style="whitespace:pre;font-family:monospace;small;overflow:auto;height:75%">

<?php

$log = file('web/infologs/u'.$_SESSION['employee']['id'].'.log');
$log = array_reverse($log);

echo nl2br(implode('', $log));


?>

</div>

</td>
</tr>


</table>
<?php include 'web/modal/chat.php'; ?>
</body>

</html>