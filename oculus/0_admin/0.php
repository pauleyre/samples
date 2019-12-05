<?php

	session_start();

	if(empty($_SESSION["zaposlenik_id"]))
	{
		$_SESSION["login_from"] = "admin";
		require("../_modals/login.php");
		exit();
	}

?>
<!DOCTYPE HTML 
	PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>ADMIN PANEL</title>
<script type="text/javascript">

	function OtvoriZaposlenika()
	{
		var nTop = Math.floor(screen.height/2-300/2);
		var nLeft = Math.floor(screen.width/2-400/2);

		if(window.open) {
			window.open("_modals/zaposlenik.php", "", "height=400, width=400, resize=0, scrollbars=yes, top=" + nTop + ", left=" + nLeft);
		}
	}

</script>
</head>

<body>
	<p><strong>ZAPOSLENICI I POSTAVKE</strong>
	<ul>
	<li><a class="txt" href="javascript: void(0);" onClick="javascript: OtvoriZaposlenika();">ZAPOSLENICI</a></li>
	<li><a class="txt" href="index.php?page=settings">POSTAVKE</a></li>
		</ul>
	</p>
	<p><strong>PREGLED EVIDENCIJA</strong>
	<ul>
	<li><a class="txt" href="index.php?page=dnevnici_rada">DNEVNICI RADA</a></li>
	<li><a class="txt" href="index.php?page=loko_admin">LOKO VOŽNJA</a></li>
	<li><a class="txt" href="index.php?page=bolovanja">BOLOVANJA</a></li>
	<li><a class="txt" href="index.php?page=godisnji">GODI&Scaron;NJI ODMOR</a></li>
		</ul>

	</p>
</body>
</html>