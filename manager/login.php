<?php

	require_once 'data/db.php';

	if(isset($_POST['pass'])) {
		require_once 'logic/class.Employee.php';

		$e = new Employee();
		$pass = trim($_POST['pass']);
		$login_id = $e->login($pass);

		if(!empty($login_id)) {

			$e->getEmployee($login_id, Employee::ACTIVE);
			$e->loadIntoSession('employee');
			$_SESSION['employee']['id'] = $login_id;
			// don't need this
			$_SESSION['employee']['password'] = null;

			logw('Dobro došli u sustav, ' . $SESSION['employee']['first_name']);

			$user_dir = 'web/upload/u' . $_SESSION['employee']['id'];
			if(!is_dir($user_dir)) {
				mkdir($user_dir, 0777);
				chmod($user_dir, 0777);
			}

			redirect('http://localhost/manager/');
			exit();
		}
		else {
			$msg = '<span class=red>KORISNIK NEPOZNAT</span>';
		}
	}

	require_once 'logic/class.Client.php';

	$c = new Client(1);
	$c->getClient();

?>
<html>
<head>
<link rel=stylesheet href=web/css/main.css>
<title>Manager: Prijava</title>
</head>

<body>
<h1>Manager</h1>

<noscript>
<p>
Vaš preglednik <code><?php echo $_SERVER['HTTP_USER_AGENT']; ?></code> ima <span style="border-bottom: medium solid red;">isključen</span> Javascript. <span style="border-bottom: medium solid red;">Neke od funkcionalnosti Managera će biti nedostupne.</span>
<br>
Obratite se svome administratoru ili sami ponovno uključite / isključite Javascript u opcijama preglednika.
</p>
</noscript>

<form action="" method="post">
<label for=pass>Korisnička lozinka</label><br>
<input name="pass" id="pass" type="password" title="Unesite lozinku">
<select name="db">
<option value="manager"><?php echo $c->company_name; ?></option>

<?php

	$r = sql_res('SELECT content FROM config WHERE (flags & %s) AND (id != 1)', CFG_TYPE_EXTRA_DB);
	$a = $db->fetch_assoc($r);

	while ($a) {

		list($db_name, $company_name) = explode(',', $a['content']);
		echo "<option value=\"$db_name\">$company_name</option>";

		$a = $db->fetch_assoc($r);
	}

?>

</select><br>
<input name="submit" type="submit" value="Spoji me"> <?php echo $msg; ?></td>
</form>

<script>
document.getElementById('pass').focus()
</script>

</body>
</html>