<?php

	include 'data/db.php';
	include 'logic/func.main.php';
	main();

	if(!empty($_GET['m'])) {

		include 'logic/class.Member.php';
		$member = new Member($_GET['m']);
		$member->getMember();

		$tot_q = sql_assoc('SELECT COUNT(id) AS tot_q FROM question WHERE member_id = %s', $member->getId());
		$tot_q = $tot_q['tot_q'];
		$tot_q = number_format($tot_q, 0, ',', ' ');

		$tot_a = sql_assoc('SELECT COUNT(id) AS tot_a FROM answer WHERE member_id = %s', $member->getId());
		$tot_a = $tot_a['tot_a'];
		$tot_a = number_format($tot_a, 0, ',', ' ');
		$banned = ($member->flags & Member::ACTIVE) ? '' : 'text-decoration:line-through';
	}

?>
<html>

<head>
<title><?php echo $member->name; ?> - Dekada</title>
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
		<p class="georg"><span style="<?php echo $banned ?>"><?php echo $member->name; ?></span><?php echo ($banned) ? '<span style="color:black;padding-left:15px">Izbačen/a</span>' : ''; ?></p>
	</div>
</div>

<ul style="<?php echo $banned ?>">
<li>Registriran/a od: <?php echo date('j.n.Y.', $member->joined); ?></li>
<li>Pitanja: <?php echo $tot_q; ?></li>
<li>Odgovori: <?php echo $tot_a; ?> ( najviše u

<?php

global $db;

$r = sql_res('SELECT category, count( category ) AS total, id FROM question WHERE (live = 1) AND id IN (SELECT question_id FROM answer WHERE member_id = %s) GROUP BY category ORDER BY total DESC LIMIT 3  ', $_GET['m']);

$a = $db->fetch_assoc($r);

$c = array();

while ($a) {
	$c[] = "<a href=\"./?c={$a['category']}\">{$a['category']}</a> ". (int) (($a['total'] / $tot_a) * 100).'%';
	$a = $db->fetch_assoc($r);
}

echo implode(' , ', $c);
?>

)</li>
</ul>

<?php include 'render.footer.php'; ?>

</div>

</body>

</html>