<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require($_SERVER["DOCUMENT_ROOT"]."/classlib/public/classlib.php");

	require('../3_financije/class.financije.php');

	(object) $oArticle = new Financije;

	function GetPermalink($sInput)
	{
		$sInput = strip_tags(trim(strtolower($sInput)));
		$sInput = str_replace(array('"', '=', '?', '&', '+', ' ', '/', ':', "'", 'č', 'ć', 'ž', 'š', 'đ', 'Č', 'Ć', 'Š', 'Đ', 'Ž', '#'), array("", "-", "", "-", "-", "-", "-", "-", "-", 'c', 'c', 'z', 's', 'dj', 'C', 'C', 'S', 'Dj', 'Z', ''), $sInput);
		$sInput = strtolower($sInput);
		return $sInput;
	}

	if(isset($_POST['source']) && $_POST['source'] == 'related_btn') {
		$_GET['q'] = $_POST['q'];
	}

	$starttime = explode(' ', microtime());
	$starttime = $starttime[1] + $starttime[0];

	// * calc start

	$use_calc = FALSE;
	$calc_query = NULL;
	$calc_operators = array('+','-','*','/','%','plus','minus','podjeljeno','puta','pi','manje','više');

	foreach($calc_operators as $key => $value)
	{
		if($use_calc == 0)
		{
			$use_calc = (strpos(strtolower($_GET['q']), $value) !== FALSE) ? 1 : 0;
			$math_operation = trim(strip_tags(strtolower($_GET['q'])));
		}
	}

	// let's now see if stripped of operators and other chars matches an int or float
	$math_operation_sanity_check = str_replace($calc_operators, '', $math_operation);
	$math_operation_sanity_check = str_replace(array('.', ',', '(', ')', ' '), '', $math_operation_sanity_check);

	if(is_numeric($math_operation_sanity_check)) {
		$use_calc ++;
	}
	//echo "<!--$math_operation_sanity_check|$use_calc-->";
	if($use_calc == 2)
	{
		$math_operation = str_replace(array('plus','minus','podjeljeno','puta','pi','manje','više'), array('+','-','/','*',M_PI,'-','+'), $math_operation);
		$search_results = eval("return ($math_operation);");

		// finish the process time
		$mtime = explode(' ', microtime());
		$totaltime = $mtime[0] + $mtime[1] - $starttime;
		$process_time = $totaltime;
		// fill out vars
		$clean_query = $math_operation;
		$result_feedback = "Rezultati matemati&#269;ke kalkulacije : <span style=\"font-size: 150%;\">$clean_query</span>\n";
	}
	// * calc end

$stop_words = <<<EOF
što kako zašto gdje koliko ili to svim svima svih koje nešto nakon neke reći čitav niz koje neki zapravo uvijek svojim sve kroz što tko kako pa nam nama vama biti takav takvo ali time svog sada vrlo isti ovog oni kojeg kojega koji kojim bi bih bismo onima mogu možeš ovoga ove ovi naš ću ćeš će ćemo ako kome od može da ne a e i o u je nije kao te tu tamo se su s iz smo zašto ovo na sa moj na do on ona opet po nema trenutno za svi
EOF;

	$stop_words = explode(' ', $stop_words);

	if($use_calc != 2)
	{
		$query = $oArticle -> ConvertHMTLEnitites2(trim(strip_tags($_GET['q'])));
		$clean_query = $query;
		$query = str_replace(array('?', '!'), '', $query);
		$query_permalink = GetPermalink(trim(strip_tags($_GET['q'])));

		$query = explode(' ', $query);

		foreach($query as $key => $value)	
		{
			$sponsored = strtolower($value);
			if($sponsored == 'web' || $sponsored == 'design' 
			|| $sponsored == 'css' || $sponsored == 'dizajn'
			|| $sponsored == 'html')
			{
				$sponsored_link[] = array('href' => 'tutor.blog.hr',
							'title' => 'Tutor.Blog',
							'desc' => 'HTML i CSS tutoriali na hrvatskom jeziku - nau&#269;ite kako dizajnirati svoje web stranice. Prilago&#273;eno za potpune po&#269;etnike.');
			}
			if(strlen($value) > 3)
			{
				if(in_array(strtolower($value), $stop_words)) {
					$excluded[] = "<abbr title=\"Riječ je prečesta\">$value</abbr>";
				}
				else {
					$temp[] = $value;
				}
			}
			else {
				$excluded[] = "<abbr title=\"Riječ je prekratka\">$value</abbr>";
			}
		}

		$sponsored_link = (!empty($sponsored_link)) ? array_unique($sponsored_link) : NULL;
		$excluded = implode(', ', $excluded);

		$query = implode(' ', $temp);
		$query = str_replace(array('?', ',', ':', '!', '.', '-','"','\''), ' ', $query);

		$titles = '';
		$search_results = '';
		$closest = '';

		if(!empty($query) && strlen($query) > 3)
		{
			$oArticle -> DB_Spoji();

			// permalink match
			$sQuery = sprintf('SELECT * FROM magister_articles WHERE permalink = %s AND live = 1 AND hidden = 0 LIMIT 1', $oArticle -> QuoteSmart($query_permalink));
			$rResult = $oArticle -> DB_Upit($sQuery);
			$aArticle = mysql_fetch_array($rResult, MYSQL_ASSOC);

			if(!empty($aArticle['permalink']))
			{
				$title_excerpt = $aArticle['title'];
				$search_results[$aArticle['permalink']] = array(	'permalink' => $aArticle['permalink'],
											'title' => $title_excerpt,
											'live_time' => $aArticle['live_time'],
											'type' => 'title',
											'excerpt' => NULL,
											'match' => TRUE,
											'freq' => 99);
			}

			// questions
			$sQuery = sprintf('SELECT *, MATCH( title ) AGAINST (%s) AS freq
			FROM magister_articles WHERE
			MATCH( title ) AGAINST (%s IN BOOLEAN MODE) AND live = 1 AND hidden = 0
			ORDER BY freq DESC LIMIT 10', $oArticle -> QuoteSmart($query), $oArticle -> QuoteSmart($query));

			$rResult = $oArticle -> DB_Upit($sQuery);
			$aArticle = mysql_fetch_array($rResult, MYSQL_ASSOC);

			while($aArticle)
			{
				// match
				$match = ($query_permalink == $aArticle['permalink']) ? TRUE : FALSE;
	
				$title_excerpt = $aArticle['title'];
	
				$search_results[$aArticle['permalink']] = array(	'permalink' => $aArticle['permalink'],
											'title' => $title_excerpt,
											'live_time' => $aArticle['live_time'],
											'type' => 'title',
											'excerpt' => NULL,
											'match' => $match,
											'freq' => $aArticle['freq']);
				$aArticle = mysql_fetch_array($rResult, MYSQL_ASSOC);
			}

			mysql_free_result($rResult);

			// answers

			$sQuery = sprintf('SELECT *, MATCH( content ) AGAINST (%s) AS freq
			FROM magister_answers WHERE
			MATCH( content ) AGAINST (%s IN BOOLEAN MODE) AND live = 1 AND hidden = 0
			ORDER BY freq DESC LIMIT 10', $oArticle -> QuoteSmart($query), $oArticle -> QuoteSmart($query));

			$rResult = $oArticle -> DB_Upit($sQuery);
			$aArticle = mysql_fetch_array($rResult, MYSQL_ASSOC);

			while($aArticle)
			{
				$rResult2 = $oArticle -> DB_Upit(sprintf('SELECT title, live_time, permalink FROM magister_articles WHERE permalink = %s LIMIT 1', $oArticle -> QuoteSmart($aArticle['question_permalink'])));
				$aArticle2 = mysql_fetch_array($rResult2, MYSQL_ASSOC);

				$temp_excerpt = NULL;

				$query_terms = explode(' ', $query);

				$excerpt = strip_tags($aArticle['content'], '<br>');
				$excerpt = str_replace(array('<br />', '<br>', '<br/>'), ' ', $excerpt);

				foreach($query_terms as $key => $value)
				{
					if(strlen($value) > 2)
					{
						$str = trim(substr(strstr($excerpt, $value), 0, 50));
						if(!empty($str)) {
							$temp_excerpt[$aArticle2['permalink']] = $str;
							break;
						}
					}
				}

				$excerpt = implode('<br />', $temp_excerpt);

				// match
				$match = ($query_permalink == $aArticle['question_permalink']) ? TRUE : FALSE;

				$search_results[$aArticle['question_permalink']] = array(	'permalink' => $aArticle['question_permalink'],
											'title' => $aArticle2['title'],
											'live_time' => $aArticle2['live_time'],
											'type' => 'question',
											'excerpt' => $excerpt,
											'match' => $match,
											'freq' => $aArticle['freq']);
	
				$aArticle = mysql_fetch_array($rResult, MYSQL_ASSOC);
			}

			mysql_free_result($rResult);

			// match
			$sQuery = 'SELECT title FROM magister_articles WHERE live = 1 AND hidden = 0';

			$rResult = $oArticle -> DB_Upit($sQuery);
			$aArticle = mysql_fetch_array($rResult, MYSQL_ASSOC);

			while($aArticle)
			{
				$titles .= $aArticle['title'].' ';
				$aArticle = mysql_fetch_array($rResult, MYSQL_ASSOC);
			}
			mysql_free_result($rResult);

			$oArticle -> DB_Zatvori();
		}

		$oArticle -> fKraj = $oArticle -> UVrijeme();
	
		if(empty($search_results))
		{
			$titles = str_replace(array('?', ',', ':', '!', '.', '-','"','\''), ' ', $titles);

			// array of words to check against
			$words = explode(' ', $titles);

			// no shortest distance found, yet
			$shortest = -1;
			// loop through words to find the closest
			foreach($words as $word)
			{
				$word = trim($word);

				if(!empty($word) && strlen($word) > 3)
				{
					// calculate the distance between the input word,
					// and the current word
					$lev = levenshtein($query, $word);

					// check for an exact match
					if($lev == 0)
					{
						// closest word is this one (exact match)
						$closest = $word;
						$shortest = 0;
		
						// break out of the loop; we've found an exact match
						break;
					}

					// if this distance is less than the next found shortest
					// distance, OR if a next shortest word has not yet been found
					if($lev <= $shortest || $shortest < 0)
					{
						// set the closest match, and shortest distance
						$closest = $word;
						$shortest = $lev;
					}
				}
			}

			if($shortest == 0) {
				$result_feedback = "Rezultati pretrage : <span style=\"font-size: 150%;\">$clean_query</span>\n";
			}
			else {
				if(!empty($closest))
					$result_feedback = "Da li ste mislili na <a style=\"font-size: 150%;\" href=\"http://magister.laniste.net/pretraga/?q=$closest\">$closest</a>?\n";
			}
		}
		else {
			$result_feedback = "Rezultati pretrage : <span style=\"font-size: 150%;\">$clean_query</span>\n";
		}

		$mtime = explode(' ', microtime());
		$totaltime = $mtime[0] + $mtime[1] - $starttime;
		$process_time = $totaltime;
	
		// * hilite results
		$q_search = explode(' ', $query);

		foreach($q_search as $q_key => $q_value)
		{
			$q_value = trim($q_value);

			if(!empty($q_value))
			{
				foreach($search_results as $key => $value)
				{
					// * prepare all combinations of $q_value
					$q_value_variations = array($q_value, strtolower($q_value), strtoupper($q_value), ucfirst(strtolower($q_value)));

					$search_results[$key]['title'] = $value['title'];
					$search_results[$key]['excerpt'] = $value['excerpt'];

					foreach($q_value_variations as $q_var_key => $q_value_variation)
					{
						$search_results[$key]['title'] = str_replace($q_value_variation, "<span class=\"h\">$q_value_variation</span>", $search_results[$key]['title']);

						if(!empty($search_results[$key]['excerpt']))
							$search_results[$key]['excerpt'] = str_replace($q_value_variation, "<span class=\"h\">$q_value_variation</span>", $search_results[$key]['excerpt']);
					}
				}
			}
		}
	}	// calc check end

	if(isset($_POST['source']) && $_POST['source'] == 'related_btn')
	{
		unset($search_results[$query_permalink]);
		echo'<ol>';
		if(empty($search_results)) {
			echo '<li>Nema srodnih pitanja.</li>';
		}
		else
		{
			foreach($search_results as $key => $value)
			{
				if(!empty($value['permalink'])) {
					echo "<li><a href=\"http://magister.laniste.net/clanak/{$value['permalink']}/\">{$value['title']}</a></li>\n";
				}
			}
		}
		echo '</ol>';
		return;
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="hr" xml:lang="hr">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="http://magister.laniste.net/stylesheet.css" />
	<style type="text/css">
	#content {
		padding-top: 278px;
	}
	</style>
	<title><?= $clean_query; ?> - Rezultati pretrage @ Magister. Powered by Mater ( Attila node )</title>
</head>

<body>
	<div id="content">
			<div class="date" style="background: none;">&nbsp;</div>
			<div class="post">
				<div class="postTitle">
					<h1><span>Rezultati pretrage</span></h1>
					<q style="font-size: 90%; font-style: italic;">Napomena: Pretraga je u testnoj fazi.</q>
				</div>
			<div class="postContent" style="padding-top: 15px !important;">
				<?= $result_feedback; ?>
				<?php if(!empty($excluded)) { ?><blockquote>Sljedeći pojmovi nisu uzeti u obzir: <?= $excluded; ?></blockquote><?php } ?>
				<ol>
				<?php

					if(empty($search_results))
					{
						echo '<li>Nema rezultata pretrage za Va&#353; upit - <span style="font-size: 110%;">'.$clean_query.'</span></li>
						<li><a href="http://magister.laniste.net/posalji-pitanje/">Mater predlaže da postavite pitanje vezano uz <span style="font-size: 110%;">&quot; '.$clean_query.' &quot;</span></a></li>
						<li><a href="http://www.google.hr/search?q='.$clean_query.'+site%3Amagister.laniste.net&amp;hl=hr">Možete pokušati pronaći Va&#353; upit uz pomoć <span style="font-size: 110%;">Google-a.</span></a></li>';
					}
					// 2 means it's a valid calc query
					else if($use_calc == 2)
					{
						echo "<h1 id=\"math_result\">$search_results</h1>";

						if(is_float($search_results))
						{
							echo '<h4>Zaokruži rezultat na <select onchange="javascript: window.document.getElementById(\'math_result\').innerHTML = (parseFloat('.$search_results.').toFixed(this.options[this.selectedIndex].value));">';
							$float = explode('.', $search_results);
							$dec_pts = strlen($float[1]);
							$i = 1;
							while($i <= $dec_pts)
							{
								$selected = ($i == $dec_pts) ? 'selected="selected"' : '';
								echo "<option $selected value=\"$i\">$i</option>";
								$i ++;
							}

							echo '</select> decimalno mjesto.</h4>';
						}
					}
					else
					{
						foreach($search_results as $key => $value)
						{
							if(!empty($value['permalink']))
							{
								$style = ($value['match']) ? 'style="font-size:120%;"' : '';
								echo "<li><a $style href=\"http://magister.laniste.net/clanak/{$value['permalink']}/\">{$value['title']}</a>";
								// display excerpt
								if(!empty($value['excerpt']) && $value['type'] == 'question') {
									echo "<blockquote><span style=\"font-size: 120%;\">&ldquo;</span>...{$value['excerpt']}...<span style=\"font-size: 120%;\">&rdquo;</span></blockquote>";
								}
								echo "</li>\n";
							}
						}
					}
				?>
				</ol>
			</div>

			</div>

<div class="comment">
			<div class="commentMain">
				<ul>
					<li>&clubs; <span>Vrijeme procesiranja upita : <abbr style="font-size: 120%;font-weight:bold;" title="<?= $process_time; ?> sekundi"><?= round($process_time, 2); ?> s</abbr>.</span><br /></li>
					<li>&clubs; <span>Powered by Mater ( Attila node ).</span><br /></li>
				</ul>
			</div>

			</div>
		</div>

	</div>

	<div style="top: 0px; position: absolute; height: 181px;">
		<?= $oArticle -> GetHeader(); ?>
		<?= $oArticle -> GetNavigationMenu(); ?>
	</div>

	<div id="sidebar"><?= $oArticle -> GetMenuRight($aArticle['category'], $aArticle['hidden']); ?></div>
	<div id="footer">
		<?= $oArticle -> GetFooter(); ?>
    </div>
<?= $oArticle -> GetGoogleAnalytics(); ?>
</body>
</html>