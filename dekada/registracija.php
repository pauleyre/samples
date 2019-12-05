<?php

	include 'data/db.php';
	include 'logic/func.main.php';
	main();

	$profil = (isset($_GET['profil']) && !empty($_SESSION['member']['id'])) ? true : false;

	if(isset($_POST['login']) && isset($_POST['as'])) {
		$email = trim($_POST['email']);
		$pass = trim($_POST['pass']);
		$name = trim($_POST['name']);

		include 'logic/func.email.php';

		$sql_profil = ($profil) ? " AND (id != {$_SESSION['member']['id']}) " : '';

		$duplicate_email = sql_assoc('SELECT id FROM member WHERE (email=%s) '.$sql_profil.' LIMIT 1', $email);
		$duplicate_name = sql_assoc('SELECT id FROM member WHERE (name=%s) '.$sql_profil.' LIMIT 1', $name);

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
		elseif (empty($name)) {
			$msg = 'Niste upisali ime za potpis';
		}
		elseif (strlen($pass) < 3) {
			$msg = 'Lozinka je prekratka';
		}
		elseif(!empty($duplicate_email['id'])) {
			$msg = 'E-mail adresa <i>'.$email.'</i> je već registrirana';
		}
		elseif(!empty($duplicate_name['id'])) {
			$msg = 'Ime za potpis <i>'.$name.'</i> je već registrirano';
		}
		elseif ($email == $pass) {
			$msg = 'E-mail adresa i lozinka su isti';
		}
		elseif ($name == $pass) {
			$msg = 'Ime za potpis i lozinka su isti';
		}
		elseif (stripos($pass, $name) !== false) {
			$msg = 'Lozinka sadržava ime za potpis';
		}
		else {
			include 'logic/class.Member.php';

			if($profil) {
				$m = new Member($_SESSION['member']['id']);
				$m->getMember();

				$m->name = $name;
				$m->password = $pass;
				$m->email = $email;

				$m->setMember();

				$id = $_SESSION['member']['id'];
			}
			else {
				$m = new Member();

				$m->name = $name;
				$m->password = $pass;
				$m->email = $email;
				$m->flags = Member::ACTIVE;

				$id = $m->setMember();

				mail($email, 'Uspjesna registracija - Dekada', "Postovani $name, \n uspjesno ste se registrirali na Dekadi ( http://www.dekada.org ). Ovo su vasi podaci za prijavu\n E-mail: $email\n Lozinka: $pass\n\n Molimo da ih ne dijelite sa drugim osobama.\n", 'From: info@dekada.org');
			}

			if(!empty($id)) {
				$_SESSION['member']['id'] = $id;
				$_SESSION['member']['name'] = $m->name;
				$_SESSION['member']['email'] = $m->email;
				$_SESSION['member']['flags'] = $m->flags;

				if(!$profil) {
					header('Location: http://www.dekada.org/');
					exit();
				}
			}
		}
	}

?>
<html>

<head>
<title><?php echo ($profil) ? 'Profil' : 'Registracija'; ?> - Dekada</title>
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
		<p class=georg><?php echo ($profil) ? 'Profil' : 'Registracija'; ?></p>
	</div>
</div>

<div style="margin-top:10px; font-size:0.86em;border-bottom:1px solid #ccc">

<form method=post action="">

<script>document.write('<input type=hidden name=as value=1>');</script>

<label for=email><b>1.</b> E-mail adresa<br>
<br>
<input value="<?php echo ($profil) ? $_SESSION['member']['email'] : ''; ?>" name=email id=email type=text size=25>
</label><br><br>

<label for=pass><b>2.</b> Lozinka<br>
<br>
<input name=pass id=pass type=password size=25></label><br><br>

<label for=name><b>3.</b> Ime za potpis<br>
<br>
<input value="<?php echo ($profil) ? $_SESSION['member']['name'] : ''; ?>" name="name" id="name" type="text" size="25"></label><br><br>

<b>4.</b> <input name=login size=25 value="<?php echo ($profil) ? 'Uredi profil' : 'Registracija'; ?>" type=submit> <span style="color:red"><?php echo $msg; ?></span><br><br><br>
<?php
if($profil) {
?>
<cite>Ovdje možete urediti detalje vašeg korisničkog profila.</cite><br>
<?php
}
else {
?>
<cite>Prijava i registracija nisu obavezni. Klikom na gumb Registracija prihvaćate <a href="./?Dekada,uvjeti-koristenja&d=10404">Uvjete Korištenja</a>.<br>Nakon uspješne registracije se možete <a href="prijavite-se.php">prijaviti</a>. O prednostima registracije i prijave pročitajte u tekstu <a href="./?Dekada,zasto-se-registrirati&d=10402">Zašto se registrirati?</a></cite><br>
<?php
}
?>
</form>
</div>

<?php include 'render.footer.php'; ?>

</div>

</body>

</html>