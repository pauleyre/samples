<?php

	include 'data/db.php';
	include 'logic/func.main.php';
	main();

	include 'logic//func.categories.php';

	if(isset($_POST['submit_q']) && isset($_POST['as'])) {
		include 'logic/class.Question.php';

		$q = new Question();
		$q->title = trim($_POST['question']);
		$q->member_id = $_SESSION['member']['id'];

		if($q->title != '') {
			if(isset($_POST['c']) && valid_category($_POST['c'])) {
				$q->category = $_POST['c'];
			}

			if(empty($_SESSION['member']['id'])) {

				$guestname = trim($_POST['guestname']);

				if(!empty($guestname)) {
					$q->guestname = $guestname;
					setcookie('dekada_guestname', '', (time()-60000));
					setcookie('dekada_guestname', $guestname, (time() + 155520000));
				}
			}

			$q->live = 0;//(!empty($_SESSION['member']['id'])) ? 1 : 0;

			$q->setQuestion();

			$_SESSION['new_q'] = null;
			$msg = '<span class=green>Vaše pitanje je zaprimljeno</span>';
		}
		else {
			$msg = '<span class=red>Niste napisali pitanje</span>';
		}
	}

	$disabled = (!empty($_SESSION['member']['id'])) ? 'disabled' : '';

?>
<html>

<head>
<title>Pošaljite pitanje - Dekada</title>
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
		<p class="georg">Pošaljite pitanje</p>
	</div>
</div>

<div style="margin-top:10px; font-size:0.86em;border-bottom:1px solid #ccc">

<form method=post action="">
<script>document.write('<input type=hidden name=as value=1>');</script>
<label for=question><b>1.</b> Kako glasi pitanje?<br>
<cite>Pripazite na gramatiku, ne postavljajte više pitanja odjednom.</cite><br>
<br>
<textarea id=question name=question class=w350px rows=6 cols=50><?php echo $_SESSION['new_q'] ?></textarea></label><br><br>

<label for=c><b>2.</b> U kojoj kategoriji će se pitanje objaviti?<br>
<br>
<select id=c name=c size=10 class=w350px>
<?php echo categories_menu(); ?>
</select></label><br><br>

<label for=guestname><b>3.</b> Kako ćete se potpisati?<br>
<cite>Potpis nije obavezan.</cite><br>
<br>
<input class="w350px" type="text" value="<?php echo get_username(); ?>" maxlength="255" name="guestname" <?php echo $disabled; ?>>
</label><br><br>


<b>4.</b> <input name=submit_q size=25 value="Pošaljite pitanje" type=submit> <?php echo $msg; ?><br><br><br>
<cite>Pitanje će biti objavljeno nakon odobrenja uredništva.<br>Ako ste pripazili na gramatiku te odabrali ispravnu kategoriju, pitanje će biti ranije objavljeno.</cite><br>
</form>
</div>

<?php include 'render.footer.php'; ?>

</div>

<script>
document.getElementById('question').focus()
</script>

</body>

</html>