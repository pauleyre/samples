<?php

if(get_is_member()) {

	$username = ($_SESSION['user.r']['contact_name'] != '') ? $_SESSION['user.r']['contact_name'] . ' ' . $_SESSION['user.r']['contact_surname'] : $_SESSION['user.r']['username'];

	$last_searches = array();

	$r = sql_res('SELECT query FROM user_last_search WHERE (user_id = %s) GROUP BY query ORDER BY time LIMIT 5', $_SESSION['user.r']['id']);

	$a = $dbc->_db->fetch_assoc($r);
	while($a) {

		$last_searches[] = '<a href="./?hr=attila&amp;q='.$a['query'].'">'.$a['query'].'</a>';

		$a = $dbc->_db->fetch_assoc($r);
	}

	$last_visits = array();

	$r = sql_res('SELECT url, title FROM user_last_visit WHERE (user_id = %s) GROUP BY url ORDER BY time LIMIT 5', $_SESSION['user.r']['id']);

	$a = $dbc->_db->fetch_assoc($r);
	while($a) {

		$last_visits[] = '<a href="'.$a['url'].'">'.$a['title'].'</a>';

		$a = $dbc->_db->fetch_assoc($r);
	}

	$interest = '';

	switch($_SESSION['user.r']['bank_status']) {

		case 'posl_m': $interest = '
<a href="./?hr=visa-bonus-plus-%28kartice-za-poslovne-subjekte%29">VISA Bonus plus</a>,
<a href="./?hr=brzac-krediti">Brzac krediti</a>,
<a href="./?hr=hpb-agro-ponuda-za-poslovne-subjekte">AGRO ponuda</a>'; break;

		case 'posl_v': $interest = '
<a href="./?hr=visa-business-%28kartice-za-poslovne-subjekte-%28velike-tvrtke%29%29">VISA Business kartica</a>,
<a href="./?hr=otvaranje-poslovnog-ra%C4%8Duna-%28platni-promet-%28velike-tvrtke%29%29">Otvaranje poslovnog računa</a>,
<a href="./?hr=krediti-za-razvoj-turisti%25C4%258Dke-djelatnosti-%2528dugoro%25C4%258Dno-financiranje-%2528velike-tvrtke%2529%2529">Turistički krediti</a>
'; break;

		case 'gradj': $interest = '<a href="./?hr=oro%C4%8Dena-kunska-%C5%A1tednja-%28%C5%A0tednja%29">Oročena kunska štednja</a>,
<a href="./?hr=stambeni-kredit-hpb-a">Stambeni kredit</a>,
<a href="./?hr=internet-bankarstvo-za-gra%C4%91anstvo-%28hpb-internet-bankarstvo%29">Internet bankarstvo</a>'; break;
	}

	return '<h4>Ulogirani ste kao <a href="./?hr=mod.peoplering">'.$username.'</a></h4>
        <p class="searched"><strong>Vaša posljednja pretraga putem tražilice:</strong> '.implode(', ', $last_searches).'</p>
        <p class="visited"><strong>Posljednje posjećene stranice:</strong> '.implode(', ', $last_visits).'</p>
        <p class="interested"><strong>Možda Vas zanima:</strong> ' . $interest . '</p>';
}

?>