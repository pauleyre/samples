<?php

	$error = false;
	$status = (isset($_GET['a'])) ? -1 : 1;
	$security = true;

	$colors = array(
		'cc0000' => array('crvena', 'crveno', 'crvene', 'red'),
		'0000ff' => array('plava', 'plavo', 'plave', 'blue'),
		//'ffff00' => array('žuta', 'žuto', 'žute', 'yellow'),
		'c0c0c0' => array('siva', 'sivo', 'sive', 'gray', 'grey'),
		'008000' => array('zelena', 'zeleno', 'zelene', 'green'),
		'000000' => array('crna', 'crno', 'crne', 'black')		
	);

	$_SESSION['security_color'] = (empty($_SESSION['security_color'])) ? array_rand($colors) : $_SESSION['security_color'];

	// load question
	include 'logic/class.Question.php';
	$q = new Question($_GET['d']);

	$q->getQuestion(null, $status);

	include 'logic/class.Member.php';
	$member = new Member();

	// question doesn't exist
	if(!$q->getId()) {
		header('HTTP/1.1 404 Not Found', true, 404);
		$q->title = 'Greška! Pitanje ne postoji :(';
		$error = true;
	}
	else {
		$q->update_reads();

		if($q->flags & Question::LOCKED) {
			$error = true;
		}
	}

	if($q->member_id) {
		$member->getMember($q->member_id);
	}

	if($member->name) {
		$q_name = "<a href=\"profil.php?$member->name&m=$q->member_id\">$member->name</a>";
	}
	else {
		$q_name = $q->guestname;
	}

	if($q->subject) {
		$q_subject = '<span class=georg style="padding-left: 15px">Tema: <a href="./?q=%2Btema:'.$q->subject.'">'.$q->subject.'</a></span>';
	}

	//$q_date = date('j.n.Y.', $q->live_time);

	include 'logic/class.Answer.php';
	$a = new  Answer();

	$test = trim(str_ireplace(array('<br>', '<br/>', '<br />', '&nbsp;'), '', strip_tags($_POST['a'], '<img><object>')));


	if(isset($_POST['send']) && !isset($_SESSION['member']['id']) && !$_SESSION['security_ok']) {	
		if(in_array(strtolower($_POST['sec_field']), $colors[$_SESSION['security_color']])) {
			$_SESSION['security_ok'] = true;
		}
		else {
			$security = false;
		}
	}

	if(isset($_POST['send']) && $security && $q->getId() && !empty($test) && !$error && isset($_POST['as'])) {

		if(get_magic_quotes_gpc()) {
			$_POST['a'] = stripslashes($_POST['a']);
		}

		include 'logic/htmLawed.php';

		$config = array(
			'safe' => 1,
			'elements' => '* +object'
		);
		$a->answer = htmLawed($_POST['a'], $config);
		// correct small and captial Š
		$a->answer = str_replace(array('&scaron;', '&Scaron;'), array('&#353;', '&#352;'), $a->answer);

		$a->member_id = $_SESSION['member']['id'];
		$a->question_id = $q->getId();

		if(empty($_SESSION['member']['id'])) {

			$guestname = trim($_POST['guestname']);
			$guestname = (!$guestname) ? 'Anonimni' : $guestname;

			$a->guestname = $guestname;
			setcookie('dekada_guestname', '', (time()-60000));
			setcookie('dekada_guestname', $guestname, (time() + 155520000));
		}
		$a->setAnswer();
		$a->update_total_as($q->getId());

		if($q->member_id) {
			mail($member->email, 'Novi odgovor', "Postovani $member->name,\n\nVase pitanje \"$q->title\" je dobilo novi odgovor. Procitajte ga ovdje http://www.dekada.org/?d=" . $q->getId(), 'From: info@dekada.org');
		}
	}

	if(isset($_GET['delete-answer']) && !$error && isset($_SESSION['member']['id']) && ($_SESSION['member']['flags'] & Member::ADMIN)) {
		$a->getAnswer($_GET['id']);

		if($a->hasPic()) {
			$a->update_has_pic($q->getId(), '-');
		}

		if($a->hasVideo()) {
			$a->update_has_video($q->getId(), '-');
		}

		$a->delete();
		$a->update_total_as($q->getId());
	}
	
	if(isset($_GET['vote']) && isset($_GET['aid']) && isset($_SESSION['member']['id']) && ($_SESSION['member']['flags'] & Member::ACTIVE)) {
	
		include 'logic/class.Vote.php';
		$vote = new Vote();
		
		$vote->answer_id = intval($_GET['aid']);
		$vote->user_id = $_SESSION['member']['id'];
		$vote->vote = $_GET['vote'];
		$vote->submited = time();

		if(!$vote->alreadyVoted($vote->answer_id, $vote->user_id)) {
			$vote->setVote();
		}
	}

	$edit_q = (isset($_SESSION['member']['id']) && ($_SESSION['member']['flags'] & Member::ADMIN)) ? '<a href="./?q=%2Bip:'.$q->ip.'">[ '.$q->ip.' ]</a> <a href="./web/admin/?id='.$q->getId().'">[ Uredi pitanje ]</a>' : '';

?>
<html>

<head>
<title><?php echo $q->title . " ( $q->category )"; ?> - Dekada</title>
<link rel=stylesheet href=web/css/main.css>
<meta content=noarchive name=robots>

<script>

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
		<p class=georg>
		Kategorija: <a href="./?c=<?php echo $q->category; ?>"><?php echo $q->category ?></a>
		<?php echo $q_subject ?>
		<span class=georg style="padding-left: 15px">Autor pitanja: <?php echo $q_name ?></span>
		<?php echo $edit_q ?>

		</p>
	</div>

</div>



<h1 class=qtitle><?php echo $q->title; ?></h1>
<p class=a_header><?php echo $m_name, $m_date; ?></p>

<?php

	$r = $a->getAnswers($q->getId());
	$answ = $db->fetch_assoc($r);
	$i = 1;

	while($answ) {

		if($answ['member_id']) {
			$member->getMember($answ['member_id']);
			$m_name = "<a href=\"profil.php?$member->name&m={$answ['member_id']}\">$member->name</a>";
		}
		else {
			$m_name = trim($answ['guestname']);
			if($m_name == 'Anonimni') {
				$m_name =  "$m_name ($i)";
				$i ++;
			}
			//$m_name = htmlspecialchars($m_name);
		}

		$m_date = date('j.n.Y.', $answ['submited']);

		$del_answ = (isset($_SESSION['member']['id']) && ($_SESSION['member']['flags'] & Member::ADMIN)) ? '<a href="./?q=%2Bip:'.$answ['ip'].'">[ '.$answ['ip'].' ]</a> <a href="./?delete-answer&id='.$answ['id'].'&d='.$q->getId().'">[ Izbriši odgovor ]</a>' : '';

		$not_logged_in = (isset($_SESSION['member']['id']) && ($_SESSION['member']['flags'] & Member::ACTIVE)) ? '' : ' onclick="alert(\'Morate biti registrirani\'); return false;"';

		$answer_txt = ($answ['score'] < -2) ? "<div><em style=\"color:#333\">Ovaj odgovor ima previše negativnih glasova.</em> <a href=\"javascript:void(null);\" onclick=\"sh('answ_{$answ['id']}')\">Klikni ovdje za prikaz</a>
		<div id=\"answ_{$answ['id']}\" style=\"display:none\">{$answ['answer']}</div>
</div>" : $answ['answer'];

		$head_color = ($answ['score'] > 2) ? 'style=\'background-color: #D1FF70 !important\'' : '';
		$score = ($answ['score'] > 0) ? '<span style="color:green">+'.$answ['score'].'</span>' : '<span style="color:red">'.$answ['score'].'</span>';
		$score = ($answ['score'] == 0) ? '' : $score;

		echo "<div class=answ>
			<p $head_color class=\"answ_head georg\">$m_name, $m_date $del_answ 
			
			<span style=\"padding-left: 10px\">
			<a title=\"Dobar odgovor\" $not_logged_in href=\"./?d={$_GET['d']}&amp;vote=OK&amp;aid={$answ['id']}\" rel=\"nofollow\"><img src=\"./web/css/arr_up.gif\" onmouseover=\"this.src='./web/css/arr_up2.gif'\" onmouseout=\"this.src='./web/css/arr_up.gif'\"></a>
			<a title=\"Loš odgovor / spam\"  $not_logged_in href=\"./?d={$_GET['d']}&amp;vote=NOK&amp;aid={$answ['id']}\" rel=\"nofollow\"><img src=\"./web/css/arr_down.gif\" onmouseover=\"this.src='./web/css/arr_down2.gif'\" onmouseout=\"this.src='./web/css/arr_down.gif'\" ></a>
			$score
			</span>
			
			</p>
			<div class=answ_body>$answer_txt</div>
			</div>";

		$answ = $db->fetch_assoc($r);
	}

	$disabled = (!empty($_SESSION['member']['id'])) ? 'disabled' : '';

?>
<div class=PageInfo style="padding-top: 30px;clear:both">
	<p class=georg style="border-top: none !important">Napišite svoj odgovor</p>
</div>
<h3 style="font-weight:normal" class=georg>Odgovorite: <?php echo $q->title; ?></h3>
<form method=post action="">
<script>
document.write('<input type=hidden name=as value=1>');
document.write('<textarea <?php echo ($error) ? 'disabled' : '' ?> name=a id=a style="width:100%" rows=12><?php echo $_POST['a'] ?></textarea>');
</script>
<noscript>
<span class=red>Imate isključen JavaScript. Uključite ga kako bi mogli odgovoriti.</span>
</noscript>
<br>
<table width=100%>

<tr>
<td valign=top><label for=guestname>Potpis (nije obavezan)</label> <input <?php echo ($error) ? 'disabled' : '' ?> size=25 type=text id=guestname name=guestname maxlength=255 value="<?php echo get_username(); ?>" <?php echo $disabled; ?>></td>
<td rowspan=2>
<?php

if(!isset($_SESSION['member']['id'])) {
	if(!$_SESSION['security_ok']) {
	
?>

Koje je boje kvadrat? (Obavezna sigurnosna provjera, samo jednom)<br>
<div style="width:50px;height:50px;background-color:#<?php echo $_SESSION['security_color'] ?>"></div>
<label>Odgovor: <input type="text" name="sec_field"></label>
<?php
	}
}
?>

</td></tr>
<tr>
<td valign=top><input <?php echo ($error) ? 'disabled' : '' ?> size=25 name=send type=submit value="Objavite odgovor"></td>
</tr>
</table>

</form>

<form method=get action=kontakt.php>
<input name=pre type=hidden value="<?php echo base64_encode("Prijavljujem sadržaj stranice $q->title ( http://www.dekada.org/?$q->category,$q->permalink&d=".$q->getId().' ). Razlog prijave je...'); ?>">

<p class=tool>
<input name=report type=submit value="Prijavite sadržaj stranice uredništvu">
<g:plusone size="medium" annotation="inline"></g:plusone>

</p>

</form>

<?php

	include 'logic/func.related.php';

	$qsimRes = $q->getQuestions('', 5, 1, null, 'live_time', clean($q->title . " $q->subject"), null, 'DESC', false, $q->getId());
	$qsimList = $db->fetch_assoc($qsimRes);

	if($qsimList) {
		echo '
<div class=PageInfo>
	<p class=georg style="border-top: none !important">Slična pitanja</p>
</div>
		<ol class="top10 nonum">';

		while($qsimList) {

			$pic = ($qsimList['has_pic'] > 0) ? ' - <span class=pic>slika</span>' : '';
			$video = ($qsimList['has_video'] > 0) ? ' - <span class=pic>video</span>' : '';
			$title = (strlen($qsimList['title']) > 90) ? substr($qsimList['title'], 0, 90).'...' : $qsimList['title'];
			$total_as = ($qsimList['total_as'] == 1) ? $qsimList['total_as'].' odgovor' : $qsimList['total_as'].' odgovora';

			echo "<li>
				<a href=\"./?{$qsimList['category']},{$qsimList['permalink']}&d={$qsimList['id']}\">$title <span class=ansnum>$total_as</span>{$pic}{$video}</a>
			</li>";

			$qsimList = $db->fetch_assoc($qsimRes);
		}

		echo '</ol>';
	}

	if(!$security) {
		echo '<script>alert("Krivo ste odgovorili na sigurnosnu provjeru! Pokušajte ponovno")</script>';
	}

?>

<p class=notice>
<b>VAŽNO</b><br>
Informacije koje se nalaze na Dekadi su generalne informacije koje se ne mogu smatrati profesionalnim medicinskim, psihijatrijskim, psihološkim, poreznim, pravnim, poslovnim, ekonomskim i drugim profesionalnim savjetima. Ako imate želju za dobivanjem profesionalnog savjeta, konzultirajte kvalificiranog davatelja usluga koji je licenciran za to u Vašoj državi.
Dekada ne garantira i izričito odbija odgovornost za bilo koji proizvod, proizvođača, distributera, uslugu ili pružatelja usluga ili bilo kakvo mišljenje objavljeno na Dekadi. Sav sadržaj na Dekadi se objavljuje pod <a href="http://www.gnu.org/copyleft/fdl.html" target="_blank">GNU Free Documentation License</a>.
</p>

<script src="./web/js/tiny_mce/tiny_mce.js"></script>
<script src="./web/js/tinymce_start.js"></script>
<script>

window.___gcfg = {lang: 'hr'};

(function() {
var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
po.src = 'https://apis.google.com/js/plusone.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();

</script>
<script src="./web/js/votes.js"></script>
<?php include 'render.footer.php'; ?>

</div>

</body>

</html>