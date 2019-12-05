<?php

	include 'data/db.php';
	include 'logic/func.main.php';
	main();

	if(isset($_POST['login']) && isset($_POST['as'])) {
		$email = trim($_POST['email']);
		$pass = trim($_POST['pass']);

		include 'logic/func.email.php';

		if(!is_email($email)) {
			$msg = 'E-mail adresa je neispravna';
		}
		elseif (empty($email) && empty($pass)) {
			$msg = 'Niste upisali e-mail adresu i lozinku';
		}
		elseif (empty($email)) {
			$msg = 'Niste upisali e-mail adresu';
		}
		elseif (empty($pass)) {
			$msg = 'Niste upisali lozinku';
		}
		else {
			include 'logic/class.Member.php';

			$m = new Member();
			$id = $m->login($email, $pass);

			if(!empty($id)) {

				$m->getMember($id);

				$id = $m->getId();

				if(!empty($id)) {

					$_SESSION['member']['id'] = $m->getId();
					$_SESSION['member']['name'] = $m->name;
					$_SESSION['member']['email'] = $m->email;
					$_SESSION['member']['flags'] = $m->flags;

					$m->updateIP();

					header('Location: http://www.dekada.org/');
					exit();
				}
			}
			else {
				$msg = 'Niste prijavljeni. Trebate li se možda <a href="registracija.php">registrirati</a>?';
			}
		}
	}

?>
<html>

<head>
<title>Prijavite se - Dekada</title>
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


	<div class="PageInfo">
		<p class="georg">Prijavite se</p>
	</div>
</div>

<div style="margin-top:10px; font-size:0.86em;border-bottom:1px solid #ccc">

<form method=post action="">
<script>document.write('<input type=hidden name=as value=1>');</script>
<label for=email><b>1.</b> E-mail adresa<br>
<br>
<input name="email" id="email" type="text" size="25">
</label><br><br>

<label for=pass><b>2.</b> Lozinka<br>
<br>
<input name="pass" id="pass" type="password" size="25"></label><br><br>

<b>3.</b> <input name=login size=25 value="Prijava" type=submit> <span style="color:red"><?php echo $msg; ?></span><br><br><br>
<cite>Prijava i registracija nisu obavezni. Klikom na gumb Prijava prihvaćate <a href="./?Dekada,uvjeti-koristenja&d=10404">Uvjete Korištenja</a>.<br>Prijavu možete ostvariti nakon uspješne <a href="registracija.php">registracije</a>. O prednostima registracije i prijave pročitajte u tekstu <a href="./?Dekada,zasto-se-registrirati&d=10402">Zašto se registrirati?</a></cite><br>
</form>
</div>

<?php include 'render.footer.php'; ?>

</div>

</body>

</html>