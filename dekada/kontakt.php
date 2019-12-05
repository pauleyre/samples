<?php

	include 'data/db.php';
	include 'logic/func.main.php';
	main();

	if(isset($_GET['pre']) && !isset($_GET['report'])) {
		header('HTTP/1.1 404 Not Found', true);
	}

	if(isset($_GET['pre']) && $_SERVER['HTTP_REFERER']) {
		$_SESSION['pre_refer'] = $_SERVER['HTTP_REFERER'];
	}

	if(isset($_POST['submit']) && isset($_POST['as']))	{

		$message = trim($_POST['message']);

		if(empty($message)) {
			$msg = '<span class=red>Niste napisali sadržaj poruke</span>';
		}
		else {
			mail('pavle.gardijan@gmail.com', '(' . $_POST['subtype'] . ') Email sa Dekade', nl2br(stripslashes($_POST['message'] . "<br>Želim odgovor na e-mail: {$_POST['email']}<br>Želim odgovor na Dekadi: {$_POST['dekada']}")), 'Content-Type: text/html; charset=UTF-8');
			$msg = '<span class=green>Vaša poruka je zaprimljena</span>';

			if($_SESSION['pre_refer']) {
				header('Location: ' . $_SESSION['pre_refer']);
				exit();
			}
		}
	}

?>
<html>

<head>
<title>Kontaktni obrazac - Dekada</title>
<link rel=stylesheet href=web/css/main.css>
<meta content=noarchive name=robots>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-23952860-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</head>

<body>

<?php include 'render.header.php'; ?>

<div style="margin:0pt auto;text-align:left;width:750px;">

<div class=ContentInfo>

<table style="width:100%;margin:15px 0 30px 0">

<tr>

<td style="width:50%;vertical-align:top">
<h1><a class="georg h1" href=./>Dekada</a></h1>
<span class=tot_qs>Postavite novo ili odgovorite na jedno od <?php echo total_qs(); ?> pitanja.<br>Ex machina, Scientia.</span>

</td>
<td style="padding-top:6px">

<form method=get action="./">
<input size=50 id=q name=q maxlength=255 value="<?php echo htmlspecialchars($_GET['q']); ?>"><br>
<input size=25 name=s value="Dekada pretraživanje" type=submit>
<input size=25 name=post value="Postavite pitanje" type=submit>
</form>


</td>

</tr>

</table>


	<div class=PageInfo>
		<p class=georg>Kontaktni obrazac</p>
	</div>
</div>

<div style="margin-top:10px; font-size:0.86em;border-bottom:1px solid #ccc">

<form id=contact_form method=post action="">
<script>document.write('<input type=hidden name=as value=1>');</script>
	<p>
		<label for=subtype><b>1.</b> Sažetak<br/>
		<select id=subtype title="Vaša poruka se može sažeti kao..." class=w350px size=4 name=subtype>
			<option value="Prijedlog" selected>Prijedlog</option>
			<option value="Pitanje">Pitanje</option>
			<option value="Kompliment">Kompliment</option>
			<option value="Kritika">Kritika</option>
		</select>
		</label>
	</p>

	<p>
		<label for=message><b>2.</b> Poruka<br/>
		<textarea title="Unesite poruku ovdje" class=w350px rows=12 id=message name=message><?php echo (isset($_GET['pre'])) ? base64_decode($_GET['pre']) : '' ?></textarea>
		</label>
	</p>

	<p>
		<label for=email><b>3.</b> Želim primiti odgovor na e-mail<br/>
		<input title="Unesite e-mail ovdje" class=w350px id=email name=email type=text></label>
	</p>

	<p>
		<label for=forum><b>4.</b> <input id=forum title="Označite ovu kučicu ako želite javni odgovor" name=forum type=checkbox value="Da"> Želim javni odgovor u kategoriji <a href="./?c=Dekada">Dekada</a></label>
	</p>

	<p><b>5.</b> <input title="Pošalji poruku" name=submit type=submit value="Pošalji"> <?php echo $msg; ?></p>
</form>

</div>

<?php include 'render.footer.php'; ?>

</div>

</body>

</html>