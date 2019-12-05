<?php

	include 'logic/class.Question.php';

	update_total_qs();

	if (isset($_REQUEST['post'])) {
		$_SESSION['new_q'] = $_GET['q'];
		header('Location: http://www.dekada.org/postavite-pitanje.php');
		exit();
	}

	$p_link = array();

	$_GET['pp'] = (isset($_GET['pp'])) ? $_GET['pp'] : 20;
	$_GET['p'] = (isset($_GET['p'])) ? $_GET['p'] : 1;

	$day_printed = array();
	$total_as = (isset($_GET['neodgovorena-pitanja'])) ? true : false;
	$order = (isset($_GET['najnoviji-odgovori'])) ? 'last_time_ans' : 'live_time';

	$q = new Question();
	$questionsRes = $q->getQuestions($_GET['c'], (($_GET['p'] - 1) * $_GET['pp']) .','. $_GET['pp'], 1, null, $order, $_GET['q'], $total_as, 'DESC');
	$questionsList = $db->fetch_assoc($questionsRes);

	if(isset($_GET['q']) && !empty($_GET['q'])) {
		$p_link['q'] = $_GET['q'];
		$p_link['s'] = $_GET['s'];
	}

	$pages = ($_GET['p'] > 1) ? ' (od '.(($_GET['p'] - 1) * $_GET['pp']).' do '.((($_GET['p'] - 1) * $_GET['pp']) + $_GET['pp']) .')' : '';
	$meta = '<meta content=noarchive name=robots>';

	include 'logic/func.categories.php';

	if(isset($_GET['q']) && !empty($_GET['q'])) {
		if($questionsList) {
			$p_title = 'Rezultati pretrage za upit <i>'.htmlspecialchars($_GET['q']).'</i>' . $pages;
		}
		else {
			$p_title = 'Nema rezultata pretrage za upit <i>'.htmlspecialchars($_GET['q']).'</i><br>Dođite do odgovora <a href=postavite-pitanje.php>postavljanjem pitanja</a>';
		}
		$meta = '<meta content="noarchive,noindex" name=robots>';
	}
	elseif(isset($_GET['kategorije'])) {
		$p_title = 'Kategorije';
	}
	elseif(isset($_GET['neodgovorena-pitanja'])) {
		$p_title = 'Neodgovorena pitanja' . $pages;
	}
	elseif(isset($_GET['najnoviji-odgovori'])) {
		$p_title = 'Najnoviji odgovori' . $pages;
	}
	elseif(isset($_GET['c'])) {
		if(valid_category($_GET['c'])) {
			$p_title = $_GET['c'] . $pages;
			$rss = "{$_GET['c']}.xml";
		}
		else {
			header('HTTP/1.1 404 Not Found', true);
			$p_title = 'Greška! Kategorija ne postoji :(';
		}
	}
	else {
		//$p_link['root'] = true;
		$p_title = 'Najnovija pitanja' . $pages;
		if($_GET['p'] == 1 && !isset($_GET['q'])) {
			$meta .= '<meta content="Pitajte bilo što ili pokažite znanje svojim odgovorima." name=description>';
		}
		$rss = 'Sve.xml';
	}

?>
<html>

<head>
<title><?php echo ($p_title && ($p_title != 'Najnovija pitanja')) ? "$p_title - Dekada" : 'Dekada'; ?></title>
<link rel=stylesheet href=web/css/main.css>
<?php
	echo $meta;
	if($rss) {
		echo '<link rel="alternate" type="application/rss+xml" title="'.str_replace('.xml', '', $rss).'" href="http://www.dekada.org/web/rss/'.$rss.'">';
	}
?>

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

<form method=get action=./>
<input size=50 id=q name=q maxlength=255 value="<?php echo htmlspecialchars($_GET['q']); ?>"><br>
<input size=25 name=s value="Dekada pretraživanje" type=submit>
<input size=25 name=post value="Postavite pitanje" type=submit>
</form>


</td>

</tr>

</table>

	<div class=PageInfo>
		<p class=georg><?php echo ($p_title && ($p_title == 'Najnovija pitanja' || $p_title == 'Najnoviji odgovori')) ? 'Najnovija <span class=new_index><a '.((!isset($_GET['najnoviji-odgovori'])) ? 'class=em' : '').' href=./>pitanja</a> | <a '.((isset($_GET['najnoviji-odgovori'])) ? 'class=em' : '').' href=./?najnoviji-odgovori>odgovori</a></span>' : $p_title; ?></p>
	</div>
</div>

<ol class=qlist>

<?php



	if(isset($_GET['kategorije'])) {

		echo '<li class=cat>';

		$categories = get_categories(true);

		$last_lt = 'A';

		foreach($categories as $category) {

			$first_lt = substr($category, 0, 1);

			if($last_lt != $first_lt) {
				echo '</li><li class=cat>';
				$last_lt = $first_lt;
			}

			$style = ($category == 'Trash, Spam i Vic') ? 'style="text-decoration:line-through"' : '';

			echo "<a $style href=\"./?c=$category\">$category</a>";
		}

		echo '</li>';
	}
	else {

		while ($questionsList) {

			if(!isset($_GET['q'])) {
				// date headers START
				if(!$day_printed['day0'] && (date('mdY', $questionsList[$order]) == date('mdY', mktime(0, 0, 0, date('m'), date('d'), date('Y'))))) {
					$day_printed['day0'] = true;
					echo '<li class="date georg">Danas</li>';
				}

				if(!$day_printed['day1'] && (date('mdY', $questionsList[$order]) == date('mdY', mktime(0, 0, 0, date('m'), date('d')-1, date('Y'))))) {
					$day_printed['day1'] = true;
					echo '<li class="date georg">Jučer</li>';
				}

				if(!$day_printed['day2'] && (date('mdY', $questionsList[$order]) == date('mdY', mktime(0, 0, 0, date('m'), date('d')-2, date('Y'))))) {
					$day_printed['day2'] = true;
					echo '<li class="date georg">'.timeformat(mktime(0, 0, 0, date('m'), date('d')-2, date('Y'))).'</li>';
				}

				if(!$day_printed['day3'] && (date('mdY', $questionsList[$order]) == date('mdY', mktime(0, 0, 0, date('m'), date('d')-3, date('Y'))))) {
					$day_printed['day3'] = true;
					echo '<li class="date georg">'.timeformat(mktime(0, 0, 0, date('m'), date('d')-3, date('Y'))).'</li>';
				}

				if(!$day_printed['day4'] && (date('mdY', $questionsList[$order]) == date('mdY', mktime(0, 0, 0, date('m'), date('d')-4, date('Y'))))) {
					$day_printed['day4'] = true;
					echo '<li class="date georg">'.timeformat(mktime(0, 0, 0, date('m'), date('d')-4, date('Y'))).'</li>';
				}

				if(!$day_printed['day_old'] && (date('mdY', $questionsList[$order]) == date('mdY', mktime(0, 0, 0, date('m'), date('d')-5, date('Y'))))) {
					$day_printed['day_old'] = true;
					echo '<li class="date georg">Arhiva</li>';
				}

				if(!$day_printed['day_old'] && ($questionsList[$order] <= mktime(0, 0, 0, date('m'), date('d')-5, date('Y')))) {
					$day_printed['day_old'] = true;
					echo '<li class="date georg">Arhiva</li>';
				}
				// date headers END
			}

			$pic = ($questionsList['has_pic'] > 0) ? ' - <span class=pic>slika</span>' : '';
			$video = ($questionsList['has_video'] > 0) ? ' - <span class=pic>video</span>' : '';
			$title = (strlen($questionsList['title']) > 90) ? substr($questionsList['title'], 0, 90).'...' : $questionsList['title'];
			$total_ans = ($questionsList['total_as'] == 1) ? $questionsList['total_as'].' odgovor' : $questionsList['total_as'].' odgovora';

	echo '
<li class=qitem>
	<a title="Kategorija: '.$questionsList['category'].'" href="./?'.$questionsList['category'].','.$questionsList['permalink'].'&d='.$questionsList['id'].'">'.$title.' <span class=ansnum>'.$total_ans.'</span>'.$pic.$video.'</a>
</li>';

			$questionsList = $db->fetch_assoc($questionsRes);
		}

	}

?>

</ol>

<?php

	if(!isset($_GET['kategorije'])) {
		include 'logic/class.pagination.php';

		$pagination = new Pagination('p', 'pp');

		if(isset($_GET['c'])) {
			$a = sql_assoc('SELECT COUNT(id) AS total_qs FROM question WHERE (category = %s) AND (live = 1)', $_GET['c']);
			$p_link['c'] = $_GET['c'];
		}
		else if(isset($_GET['neodgovorena-pitanja'])) {
			$a = sql_assoc('SELECT COUNT(id) AS total_qs FROM question WHERE (total_as = 0) AND (live = 1)');
			$p_link['neodgovorena-pitanja'] = '';
		}
		else if(isset($_GET['q'])) {

			$questionsRes_t = $q->getQuestions($_GET['c'], (($_GET['p'] - 1) * $_GET['pp']) .','. $_GET['pp'], 1, null, $order, $_GET['q'], $total_as, 'DESC', true, false, true);
			$questionsList_t = $db->fetch_assoc($questionsRes_t);

			$a['total_qs'] = $questionsList_t['total_qs'];
		}
		else if(isset($_GET['najnoviji-odgovori'])) {
			$p_link['najnoviji-odgovori'] = '';
			$a = sql_assoc('SELECT COUNT(id) AS total_qs FROM question WHERE (total_as > 0) AND (live = 1)');
		}
		else {
			$a = sql_assoc('SELECT total_qs FROM main');
		}

		$pagination->total = $a['total_qs'];

		if($a['total_qs']) {
			$pagination->split_pages();

			$p_link = http_build_query($p_link);
			if($p_link) {
				$p_link = "?$p_link";
			}

			echo '<div class=PageInfo>'.$pagination->construct_page_nav('./' . $p_link).'</div>';
		}

		$pagination = null;
	}

?>

<?php include 'render.footer.php'; ?>

</div>

<script>
document.getElementById('q').focus()
<?php
			//var_dump($p_link);
?>
</script>

</body>

</html>