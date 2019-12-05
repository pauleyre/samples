<?php
/*ini_set('display_errors', 1);
error_reporting(E_ALL);*/
	require($_SERVER['DOCUMENT_ROOT'].'/classlib/public/classlib.php');

	session_start();

	require($_SERVER['DOCUMENT_ROOT'].'/3_financije/class.financije.php');
	(object) $oRN = new Financije;

	if(!empty($_FILES['userfile'])) {
		$oRN -> AddProjectDoc();
	}

	if(isset($_GET['action']) && $_GET['action'] == 'delete' && !empty($_GET['id']))
	{
		if(isset($_POST['potvrdi']))
		{
			$oRN -> DeleteDoc();
			header('Location: '.$_SESSION['opaska_refer']);
			exit();
		}
		else if(isset($_POST['odustani'])) {
			header('Location: '.$_SESSION['opaska_refer']);
			exit();
		}
		else {
			$_SESSION['opaska_refer'] = $_SERVER['HTTP_REFERER'];
		}
	}

	if(isset($_GET['action']) && $_GET['action'] == 'opaska')
	{
		if(isset($_POST['update_opaska']))
		{
			$oRN -> DB_Spoji('is');

			(string) $sQuery = sprintf("UPDATE projektna_dokumentacija SET 
										opis = %s WHERE id = %s LIMIT 1",
										$oRN -> QuoteSmart(nl2br($_POST['new_doc_opis'])), $oRN -> QuoteSmart($_GET['id']));
			$oRN -> DB_Upit($sQuery);
			$oRN -> DB_Zatvori();
			header('Location: '.$_SESSION['opaska_refer']);
			exit();
		}
		else
		{
			$_SESSION['opaska_refer'] = $_SERVER['HTTP_REFERER'];
		}
			$oRN -> DB_Spoji('is');

			(string) $sQuery = sprintf("SELECT dokument, opis FROM projektna_dokumentacija 
										WHERE id = %s LIMIT 1",
										$oRN -> QuoteSmart($_GET['id']));
			$r = $oRN -> DB_Upit($sQuery);
			$r = mysql_fetch_array($r, MYSQL_ASSOC);
			$oRN -> DB_Zatvori();
	}
?>
<html>
<head>
	<title>Projektna dokumentacija</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="http://<?=$_SERVER['SERVER_NAME']?>/gfx/styleis.css">
</head>
<body style="background-color:#fdf9ed;">
<?php
	if($_GET['action'] == 'opaska') {
		echo '<form method="post"><h3>'.$r['dokument'].'</h3><textarea rows="5" cols=50 name="new_doc_opis">'.$r['opis'].'</textarea><br><input class="little" name="update_opaska" value="Potvrdi" type="submit"></form>';
	}
	else if($_GET['action'] == 'delete')
	{
		$oRN -> DB_Spoji('is');

		(string) $sQuery = sprintf("SELECT dokument FROM projektna_dokumentacija 
										WHERE id = %s LIMIT 1",
										$oRN -> QuoteSmart($_GET['id']));
		$r = $oRN -> DB_Upit($sQuery);
		$r = mysql_fetch_array($r, MYSQL_ASSOC);
		$oRN -> DB_Zatvori();

		echo '<form method="post"><h3>Potvrdite brisanje dokumenta &quot; '.$r['dokument'].' &quot;</h3><br><input value="Potvrdi" class="little" name="potvrdi" type="submit"><input class="little" value="Odustani" name="odustani" type="submit"></form>';
	}
	else {
		$oRN -> BuildCurrentDocs();
	}
?>
</body>
</html>