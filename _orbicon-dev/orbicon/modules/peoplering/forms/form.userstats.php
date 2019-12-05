<table width="100%">

<tr>

<td>

<form method="post" action="">

<div>
<strong>Legenda</strong><br>
<img src="./orbicon/modules/userstats/gfx/info.gif" alt="Info" title="Info" /> Info
<img src="./orbicon/modules/userstats/gfx/selling.gif" alt="Prodaja" title="Prodaja" /> Prodaja
<img src="./orbicon/modules/userstats/gfx/misc.gif" alt="Razno" title="Razno" /> Razno
<img src="./orbicon/modules/userstats/gfx/finale.gif" alt="Cilj" title="Cilj" /> Cilj

</div>

<div>

<br>

<select id="date" name="date">

	<option value="1">Zadnje</option>
	<option value="7">7 dana</option>
	<option value="30">Mjesec dana</option>
	<option value="90">3 mjeseca</option>
	<option value="180">6 mjeseci</option>
	<option value="240">9 mjeseci</option>
	<option value="365">Godina dana</option>
	<option value="99999">Sve</option>

</select>

<input type="submit" value="OK" />

</div>

<div id="pr_info">&nbsp;</div>

<?php

	require_once DOC_ROOT.'/orbicon/modules/userstats/class.userstats.php';
	$userstats = new UserStats($_GET['id']);

	echo $userstats->generate_stats($_POST['date']);

	$userstats = null;

?>

</form>
</td>

<td width="30%">

<?php

	$last_searches = array();

	$r = sql_res('SELECT query FROM user_last_search WHERE (user_id = %s) GROUP BY query ORDER BY time LIMIT 5', $_GET['id']);

	$a = $dbc->_db->fetch_assoc($r);
	while($a) {

		$last_searches[] = '<a href="./?hr=attila&amp;q='.$a['query'].'">'.$a['query'].'</a>';

		$a = $dbc->_db->fetch_assoc($r);
	}

	$last_visits = array();

	$r = sql_res('SELECT url, title FROM user_last_visit WHERE (user_id = %s) GROUP BY url ORDER BY time LIMIT 5', $_GET['id']);

	$a = $dbc->_db->fetch_assoc($r);
	while($a) {

		$last_visits[] = '<a href="'.$a['url'].'">'.$a['title'].'</a>';

		$a = $dbc->_db->fetch_assoc($r);
	}

	$a = sql_assoc('SELECT selling, info, misc FROM user_stats_pers WHERE (user_id = %s)', $_GET['id']);

	$total = $a['info'] + $a['selling'] + $a['misc'];
	$info = round((($a['info'] / $total) * 100), 2);
	$selling = round((($a['selling'] / $total) * 100), 2);
	$misc = round((($a['misc'] / $total) * 100), 2);

?>
<p><strong>Pregled sadržaja</strong></p>
<ul>
<li>Prodaja - <?php echo $selling; ?>%</li>
<li>Info - <?php echo $info; ?>%</li>
<li>Razno - <?php echo $misc; ?>%</li>
</ul>

<p><strong>Posljednja pretraga putem tražilice</strong></p>
<ul>
<li><?php echo implode('</li><li>', $last_searches); ?></li>
</ul>

<p><strong>Posljednje posjećene stranice</strong></p>
<ul>
<li><?php echo implode('</li><li>', $last_visits); ?></li>
</ul>

</td>

</tr>


</table>