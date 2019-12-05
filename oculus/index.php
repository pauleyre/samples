<?php
	ob_start ('ob_gzhandler');
	session_start();

	if($_GET['page'] == 'izlaz')
	{
		unset($_SESSION['zaposlenik_id']);
		$_SESSION['login_from'] = 'other';
		
		$f = fopen('_modals/online/'.$_SESSION['zaposlenik_id'].'.log', 'wb');
		fwrite($f, time() - 301);
		fclose($f);
		
		require('_modals/login.php');
		exit();
	}

	if(empty($_SESSION['zaposlenik_id']))
	{
		$_SESSION['login_from'] = 'other';
		require('_modals/login.php');
		exit();
	}

	require('2_communicator/class.communicator.php');
	(object) $oKomunikator = new Communicator;
	
	(string) $shref = ($_SESSION['zaposlenik_status'] != 1) ? 'javascript: void(null);' : '?page=fin&amp;ftype=rn';
	(string) $shref2 = ($_SESSION['zaposlenik_status'] != 1) ? 'javascript: void(null);' : '?page=admin';
	(string) $sOnClick = ($_SESSION['zaposlenik_status'] != 1) ? 'onclick="javascript: DisableAccess();"' : '';

	(string) $page = '';
	(string) $page = '';
	(string) $titl = '';

	switch($_GET['page'])
	{
		case 'evid':
			$page = '4_evidencije/loko.php';
			$titl = 'evidencije';
		break;
		case 'fin':
			$page = (isset($_GET["ver"])) ? "3_financije/radni_nalog_staro.php" : "3_financije/radni_nalog.php";
			$titl = 'financije';
		break;
		case "novi_todo":
			$page = "3_financije/dodaj_todo.php";
			$titl = "dnevni zadaci";
		break;
		case "novi_todo_lite":
			$page = "3_financije/dodaj_todo_lite.php";
			$titl = "dnevni zadaci";
		break;
		case "obavijest":
			$page = "3_financije/obavijest.php";
			$titl = "obavijesti";
		break;
		case "admin":
			$page = "0_admin/0.php";
			$titl = "administracija";
		break;
		case "godisnji":
			$page = "0_admin/godisnji_odmor.php";
			$titl = "godišnji odmori";
		break;
		case "bolovanja":
			$page = "0_admin/bolovanja.php";
			$titl = "bolovanja";
		break;
		case "loko_admin":
			$page = "0_admin/loko_voznja.php";
			$titl = "loko vožnja";
		break;
		case "dnevnici_rada":
			$page = "0_admin/dnevnici_rada.php";
			$titl = "dnevnici rada";
		break;
		case "settings":
			$page = "0_admin/postavke.php";
			$titl = "postavke";
		break;
		case 'pdf_docs':
			$page = '3_financije/dokumentacija.php';
			$titl = 'pregled PDF dokumentacije';
		break;
		case 'adresar':
			$page = '_modals/klijent.php';
			$titl = 'adresar';
		break;
		case 'adresar_pregled':
			$page = '_modals/pregled_klijent.php';
			$titl = 'adresar';
		break;
		case 'zaposlenici':
			$page = '_modals/zaposlenik.php';
			$titl = 'zaposlenici';
		break;
		case 'zaposlenici_pregled':
			$page = '_modals/pregled_zaposlenik.php';
			$titl = 'zaposlenici';
		break;
		case 'opis_projekta':
			$page = '1_todo/opis_projekta.php';
			$titl = 'opis projekta';
		break;
		case 'pretraga':
			$page = '_modals/pretraga.php';
			$titl = 'pretraga';
		break;
		case 'forum':
			$page = 'forum/forum.php';
			$titl = 'forum';
		break;
		default:
			$page = '1_todo/1.php';
			$titl = "projekti";
		break;
	}

	$_SESSION["bread"] = "<a href=\"index.php\" class=\"bread\"><b>projekti</b></a><strong>  ::  dnevni zadaci</strong>";

	if($_GET["page"] == "fin")
	{
		switch($_GET["ftype"])
		{
			case "pr":
				$_SESSION["bread"] = "<a href=\"index.php?page=fin&amp;ftype=rn\" class=\"bread\"><b>financije</b></a><strong>  ::  predračuni</strong>";
				$img = '<img src="gfx/naslov.financije.gif" border="0"><img src="gfx/naslov.predracun.gif" border="0">';
			break;
			case "procjena":
				$_SESSION["bread"] = "<a href=\"index.php?page=fin&amp;ftype=rn\" class=\"bread\"><b>financije</b></a><strong>  ::  procjene</strong>";
				$img = '<img src="gfx/naslov.financije.gif" border="0"><img src="gfx/naslov.procjena.gif" border="0">';
			break;
			case "ponuda":
				$_SESSION["bread"] = "<a href=\"index.php?page=fin&amp;ftype=rn\" class=\"bread\"><b>financije</b></a><strong>  ::  ponude</strong>";			
				$img = '<img src="gfx/naslov.financije.gif" border="0"><img src="gfx/naslov.ponuda.gif" border="0">';
			break;
			default:
				$_SESSION["bread"] = "<a href=\"index.php?page=fin&amp;ftype=rn\" class=\"bread\"><b>financije</b></a><strong>  ::  radni nalozi</strong>";
			break;
		}
	}

	if($_GET["page"] == "novi_todo") {
		$_SESSION["bread"] = "<a href=\"index.php?page=fin&amp;ftype=rn\" class=\"bread\"><b>financije</b></a><strong>  ::  todo</strong>";		
	}

	if($_GET["page"] == "evid") {
		$_SESSION["bread"] = "<a href=\"index.php?page=evid\" class=\"bread\"><b>evidencije</b></a><strong>  ::  loko vožnja</strong>";		
	}

	if($_GET["page"] == "bolovanja") {
		$_SESSION["bread"] = "<a href=\"index.php?page=admin\" class=\"bread\"><b>administracija</b></a><strong>  ::  bolovanja</strong>";		
	}

	if($_GET["page"] == "godisnji") {
		$_SESSION["bread"] = "<a href=\"index.php?page=admin\" class=\"bread\"><b>administracija</b></a><strong>  ::  godišnji odmori</strong>";		
	}

	if($_GET["page"] == "loko_admin") {
		$_SESSION["bread"] = "<a href=\"index.php?page=admin\" class=\"bread\"><b>administracija</b></a><strong>  ::  loko vožnja</strong>";		
	}

	if($_GET['page'] == 'settings') {
		$_SESSION["bread"] = "<a href=\"index.php?page=admin\" class=\"bread\"><b>administracija</b></a><strong>  ::  postavke</strong>";		
	}

	if($_GET['page'] == 'dnevnici_rada') {
		$_SESSION['bread'] = "<a href=\"index.php?page=admin\" class=\"bread\"><b>administracija</b></a><strong>  ::  dnevnici rada</strong>";		
	}

	if($_GET['page'] == 'pdf_docs') {
		$_SESSION['bread'] = "<a href=\"index.php?page=admin\" class=\"bread\"><b>administracija</b></a>  <strong>::</strong>  <a href=\"index.php?page=pdf_docs\" class=\"bread\"><b>pregled PDF dokumentacije</b></a><strong> :: {$_GET['dir']}</strong>";
	}

	$f = fopen('_modals/online/'.$_SESSION['zaposlenik_id'].'.log', 'wb');
	fwrite($f, time());
	fclose($f);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hr" lang="hr">
<head>
	<title>is . <?= $titl; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="gfx/styleis.css" title="is style" />
	<script src="_modals/rte/common.js"></script>
	<script src="2_communicator/communicator.js"></script>
	<script src="_modals/clock.js"></script>
	<script src="_modals/suggest.js"></script>
	<script src="2_communicator/dom-drag.js"></script>
	<script src="javascript/oculus.js"></script>
	<style type="text/css">
		@import "gfx/menu.css";
	</style>
</head>
<body bgcolor="#fdf9ed" leftmargin="0" bottommargin="0" marginheight="0" marginwidth="0" rightmargin="0" topmargin="0">
<div id="search_suggest"></div>
<table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
<tr valign="top">
    <td class="edge" align="left">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		
		<!-- menu -->
		
		<tr>
		<td colspan="2"><a accesskey="C" href="javascript:ShowHideComm();"  ></a>
		<div class="container4">
          <div class="menu4">
<ul>
	<li class="oculus"><a><!-- oculus --></a></li>
	<li class="home"><a href="?page=todo" accesskey="P"><span class="u">p</span>rojekti<!--[if IE 7]><!--></a><?php if($_SESSION['zaposlenik_status'] == 1) { ?><!--<![endif]--><table class="menu_table"><tbody><tr><td>
	
	<ul>
		<li class="subprod"><a href="?page=novi_todo&amp;ftype=rn" accesskey="D"><span class="u">d</span>nevni zadaci</a></li>
		<li class="subprod2"><a href="?page=fin&amp;ftype=rn" accesskey="R"><span class="u">r</span>adni nalog</a></li>
		<li class="subprod2"><a href="?page=obavijest&amp;ftype=notice" accesskey="V">oba<span class="u">v</span>ijesti</a></li>
	</ul>
	
	</td></tr></tbody></table><!--[if lte IE 6]></a><![endif]-->
	<?php } ?></li>
	<li class="products"><a accesskey="F" class="drop" href="<?= $shref; ?>" <?= $sOnClick; ?>><span class="u">f</span>inancije<!--[if IE 7]><!--></a><?php if($_SESSION['zaposlenik_status'] == 1) { ?><!--<![endif]--><table class="menu_table"><tbody><tr><td>
	<ul>
		<li class="procjena"><a href="?page=fin&amp;ftype=procjena">procjena</a></li>
		<li class="ponuda"><a href="?page=fin&amp;ftype=ponuda">ponuda</a></li>
		
		<li class="predracun"><a href="?page=fin&amp;ftype=pr">predračun</a></li>
		<li class="brzi-racun"><a accesskey="U" href="3_financije/kalkulacija.php?ftype=racun">brzi rač<span class="u">u</span>n</a></li>
	</ul>
	</td></tr></tbody></table><!--[if lte IE 6]></a><![endif]--></li>
	<li class="services"><a accesskey="E" class="drop" href="?page=evid"><span class="u">e</span>videncije<!--[if IE 7]><!--></a><!--<![endif]--><table class="menu_table"><tbody><tr><td>

	</td></tr></tbody></table><!--[if lte IE 6]></a><![endif]--><?php } ?></li>
	<li class="contact"><a accesskey="A" href="<?= $shref2; ?>" <?= $sOnClick; ?>><span class="u">a</span>dministracija<!--[if IE 7]><!--></a><?php if($_SESSION['zaposlenik_status'] == 1) { ?><!--<![endif]--><table class="menu_table"><tbody><tr><td>
	<ul>
		<li class="zaposlenici"><a accesskey="Z" href="?page=zaposlenici"><span class="u">z</span>aposlenici</a></li>
		<li class="postavke"><a accesskey="O" href="?page=settings">p<span class="u">o</span>stavke</a></li>

		<li class="pregled-evidencija"><a class="drop" href="#nogo">pregled evidencija
		<!--[if IE 7]><!--></a><!--<![endif]--><table class="menu_table"><tbody><tr><td>
		<ul>
			<li class="subsubl"><a accesskey="V" href="?page=dnevnici_rada">dne<span class="u">v</span>nici rada</a></li>
			<li class="subsubl"><a accesskey="L" href="?page=loko_admin"><span class="u">l</span>oko vožnja</a></li>
			<li class="subsubl"><a accesskey="B" href="?page=bolovanja"><span class="u">b</span>olovanja</a></li>
			<li class="subsubl"><a accesskey="G" href="?page=godisnji"><span class="u">g</span>odišnji odmor</a></li>
		</ul>
		</td></tr></tbody></table><!--[if lte IE 6]></a><![endif]-->
		</li>
	</ul>
	</td></tr></tbody></table><!--[if lte IE 6]></a><![endif]--><?php } ?></li>

	<li class="site"><a accesskey="S" href="?page=adresar">adre<span class="u">s</span>ar</a></li>
	<li id="comm_li" class="komunikator"><a href="javascript: void(0);" onclick="javascript: ShowHideComm();">komunikator<!--[if IE 7]><!--></a><!--<![endif]--><table class="menu_table"><tbody><tr><td>
<ul>
		<li class="forum"><a accesskey="Z" href="?page=forum"><span class="u">f</span>orum</a></li>
</ul>
	</td></tr></tbody></table><!--[if lte IE 6]></a><![endif]--></li>
	<li class="news"><a accesskey="I" class="drop" href="index.php?page=izlaz"><span class="u">i</span>zlaz</a></li>
</ul>
</div>
</div>
		
		<!-- menu --></td>
</tr>		
		<tr valign="top" bgcolor="#FBDFCE">
			<td colspan="2" class="edgetop"><img src="gfx/empty.gif" width="13" height="1" alt="" border="0" /><?= $_SESSION["bread"]; ?><br /></td>
		</tr>		
		<tr>
			<td colspan="2">
			<img src="gfx/empty.gif" width="1" height="1" alt="" border="0" /><br /></td>
		</tr>		
		<tr bgcolor="#fdf9ed" valign="top">
			<td>
				<img src="gfx/empty.gif" width="14" height="1" alt="" border="0" /><div style="padding-left: 1em;"><form method="get" enctype="application/x-www-form-urlencoded" action="_modals/pretraga.php" ><input tabindex="1" style="font-size:1.5em; width:300px;" type="text" id="q" name="q" /> <input tabindex="2" class="little" type="submit" value="Pretraga &raquo;" /></form></div><br />
			<img src="gfx/empty.gif" width="1" height="25" alt="" border="0" /><br /></td>
			<td align="right" class="edgetop">
				<b><span class="trans10"><?= $_SESSION['zaposlenik_status_desc']; ?> . </span><?= $_SESSION["zaposlenik_ime"]." ".$_SESSION["zaposlenik_prezime"]; ?></b><img src="gfx/empty.gif" width="25" height="1" alt="" border="0" /><br />
				<b><span id="cal_day">danas je...</span> </b><a href="javascript: void(0);" onclick="javascript: OtvoriKalendar();" class="txt"><?= strftime("%d/%m/%Y", time()); ?></a><img src="gfx/empty.gif" width="25" height="1" alt="" border="0" /><br />
				<img src="gfx/empty.gif" width="1" height="3" alt="" border="0" /><br />
			<span id="hh_mm" class="trans21"><b>hh:mm</b></span><span class="trans17" id="ss"><b>:ss</b></span><img src="gfx/empty.gif" width="25" height="1" alt="" border="0" /><br />			</td>
		</tr>
		<tr>
			<td colspan="2"><img src="gfx/empty.gif" width="1" height="1" alt="" border="0" /><br /></td>
		</tr>
		<tr bgcolor="#fdf9ed">
			<td colspan="2" class="edgetop"><img src="gfx/empty.gif" width="1" height="1" alt="" border="0" /><br /></td>
		</tr>
		<tr>
			<td colspan="2">
				<img src="gfx/empty.gif" width="1" height="20" alt="" border="0" /><br />
				<form id="main_form" method="post" enctype="multipart/form-data"><?php require($page); ?><br />
<?= $extra; ?></form>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<img src="gfx/empty.gif" width="1" height="15" alt="" border="0" /><br />
				<img src="gfx/empty.gif" width="14" height="1" alt="" border="0" /><br />			</td>
		</tr>
		</table></td>
  </tr>
</table>

<div id="chat_div" style="top: 50px; left: 1em; position: absolute; display:none;">
<table width="282" height="540" background="gfx/komjunikejtor.gif" style="background-repeat: no-repeat;" cellspacing="0" cellpadding="0" border="0">
		<tr valign="top">
    		<td><img src="gfx/empty.gif" width="26" height="540" alt="" border="0" /><br /></td>
			<td>
				<img src="gfx/empty.gif" ondblclick="ShowHideComm();" title="Hold to drag or click twice to close" alt="Hold to drag or click twice to close" style="cursor: move;" id="handle" width="230" height="55" alt="" border="0" /><br />
				<span id="UserListDiv"><?= $oKomunikator -> CommunicatorBuildUserList(); ?></span><br />
				<img src="gfx/empty.gif" width="1" height="10" alt="" border="0" /><br />
				<textarea name="MessageEntry" class="commporuka" id="MessageEntry" onkeypress="javascript: CommunicatorSendOnEnter(event);"></textarea>
				<input type="hidden" value="<?= $_SESSION['zaposlenik_email']; ?>" id="comm_my_mail" />
				<img src="gfx/empty.gif" width="1" height="10" alt="" border="0" /><br />
				<table width="230" cellspacing="0" cellpadding="2" border="0">
				<tr valign="top">
					<td><input name="comm_type_im" type="checkbox" id="comm_type_im" value="1" checked="checked" /><br /></td>
					<td><label for="comm_type_im" class="comm">im</label></td>
					<td><input name="comm_type_sms" type="checkbox" id="comm_type_sms" value="2" /><br /></td>
					<td><label for="comm_type_sms" class="comm">sms</label></td>
					<td><input name="comm_type_mail" type="checkbox" id="comm_type_mail" value="4" /><br /></td>
					<td><label for="comm_type_mail" class="comm">mail</label></td>
					<td align="right" width="30%"><input name="Button" class="littleKomunikator" type="button" onclick="javascript: CommunicatorSendMessage('');" style="padding-left:8px; padding-right:8px;" value="Šalji" />
					<br /></td>
				</tr>
				</table>
				<img src="gfx/empty.gif" width="1" height="12" alt="" border="0" /><br />
				<div class="commchat" name="MessageDisplay" id="MessageDisplay" style="overflow: auto;"></div>
				
				<img src="gfx/empty.gif" width="1" height="10" alt="" border="0" /><br />
				<input name="ClearLog" class="littleKomunikator" type="button" disabled="disabled" id="ClearLog" onclick="javascript: CommunicatorClearRoom();" style="padding-left:8px; padding-right:8px;" value="Obriši" />
			<br />			</td>
			<td><img src="gfx/empty.gif" width="26" height="540" alt="" border="0" /><br /></td>
		</tr>
  </table>
</div>
<script type="text/javascript">
var theHandle = document.getElementById("handle");
var theRoot = document.getElementById("chat_div");
Drag.init(theHandle, theRoot);
</script>
<iframe id="sound_frame" src="sound.html" style="display:none;"></iframe>
<div id="rte_color_picker" style=" visibility:hidden;">
<?php

if($_GET['page'] == 'fin')
{
	include(RTEC_COLOR_PICKER);
}
?>
</div>
</body>
</html>