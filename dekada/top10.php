<?php

	include 'data/db.php';
	include 'logic/func.main.php';
	main();

?>
<html>

<head>
<title>Top 10 - Dekada</title>
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
		<p class=georg>Statistika i Top 10</p>
	</div>
</div>

<div style="margin-top:10px; border-bottom:1px solid #ccc">

<ul style="list-style-type:none;margin:0">
<li>Sveukupno pitanja : <?php echo total_qs(); ?></li>
<?php
	$tot_a = sql_assoc('SELECT COUNT(id) AS tot_a FROM answer');

	echo '<li>Sveukupno odgovora : '.number_format($tot_a['tot_a'], 0, ',', ' ').'</li>';


/* Define how long the maximum amount of time the session can be inactive. */
/*define("MAX_IDLE_TIME", 15);

function getOnlineUsers()
{
	$sessionpath = session_save_path();
	if (strpos ($sessionpath, ";") !== FALSE)
 	 	$sessionpath = substr ($sessionpath, strpos ($sessionpath, ";")+1);

	if ( $directory_handle = opendir( $sessionpath ) )
	{
		$count = 0;
		while ( false !== ( $file = readdir( $directory_handle ) ) ) {
			if($file != '.' && $file != '..' && substr($file, 0, 4) == 'sess') {
				var_dump(time()- fileatime($sessionpath . '\\' . $file));
				// Comment the 'if(...){' and '}' lines if you get a significant amount of traffic
				if(time()- fileatime($sessionpath . '\\' . $file) < MAX_IDLE_TIME * 60) {
					$count++;
				}
			}
		}
		closedir($directory_handle);

		return $count;
	}

	return false;
}

echo getOnlineUsers();*/

?>
</ul>

<div class=PageInfo style="padding-top:15px">
		<p class=georg style="border-top:none !important">Top 10 korisnika</p>
	</div>


<ol class=top10>
<?php

	include 'logic/class.Member.php';

	$m = new Member();

	$top_res = sql_res('SELECT member_id, COUNT(member_id) AS top FROM answer WHERE (member_id != 0) GROUP BY member_id ORDER BY top DESC LIMIT 10');
	$top_m = $db->fetch_assoc($top_res);

	while($top_m) {

		$m->getMember($top_m['member_id']);

		echo "<li>
			<a href=\"./profil.php?{$m->name}&m={$m->getId()}\">{$m->name}<span class=ansnum>{$top_m['top']} odgovora</span></a>
		</li>";

		$top_m = $db->fetch_assoc($top_res);
	}

?>
</ol>

<div class=PageInfo style="padding-top:15px">
	<p class=georg style="border-top:none !important">Top 10 najpopularnijih tema</p>
</div>

<ol class=top10>
<?php
	// pop
	//$total_qs = sql_assoc('SELECT total_qs FROM main');
	$pop_r = sql_res('SELECT subject, COUNT(subject) AS total FROM question WHERE (subject != \'\') GROUP BY subject ORDER BY total DESC LIMIT 10');
	$pop = $db->fetch_assoc($pop_r);
//'<span class=ansnum>'.(int) (($pop['total'] / $total_qs['total_qs']) * 100).'%</span>
	while ($pop) {
		echo '<li><a href="./?q=%2Btema:'.$pop['subject'].'">'.$pop['subject'].'</a></li>';
		$pop = $db->fetch_assoc($pop_r);
	}

?>
</ol>

<div class=PageInfo style="padding-top:15px">
		<p class=georg style="border-top:none !important">Top 10 neodgovorenih pitanja</p>
	</div>


<ol class=top10>
<?php

	include 'logic/class.Question.php';

	$q = new Question();
	$questionsRes = $q->getQuestions('', 10, 1, null, 'live_time', '', true, 'ASC');
	$questionsList = $db->fetch_assoc($questionsRes);

	while($questionsList) {

		echo "<li>
			<a href=\"./?{$questionsList['category']},{$questionsList['permalink']}&d={$questionsList['id']}\">{$questionsList['title']} <span class=ansnum>Objavljeno ".date('j.n.Y.', $questionsList['live_time']).'</span></a>
		</li>';

		$questionsList = $db->fetch_assoc($questionsRes);
	}

?>
</ol>

<p style="font-size:0.89em">
<a style="text-decoration:none;font-weight:bold;color:#000" href="./?neodgovorena-pitanja" title="Popis svih neodgovorenih pitanja">Popis svih neodgovorenih pitanja</a>
</p>

<div class=PageInfo style="padding-top:15px">
		<p class=georg style="border-top:none !important">Top 10 odgovorenih pitanja</p>
	</div>

<ol class=top10>
<?php

	$questionsRes = $q->getQuestions('', 10, 1, null, 'total_as', '');
	$questionsList = $db->fetch_assoc($questionsRes);


	while($questionsList) {

		echo "<li>
			<a href=\"./?{$questionsList['category']},{$questionsList['permalink']}&d={$questionsList['id']}\">{$questionsList['title']} <span class=ansnum>{$questionsList['total_as']} odgovora</span></a>
			</li>";

		$questionsList = $db->fetch_assoc($questionsRes);
	}

?>
</ol>

</div>

<?php include 'render.footer.php'; ?>

</div>

</body>

</html>