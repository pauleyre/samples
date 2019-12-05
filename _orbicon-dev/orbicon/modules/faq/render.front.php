<?php

require_once DOC_ROOT . '/orbicon/modules/faq/inc.faq.php';

if(isset($_GET['cid'])) {
	return include DOC_ROOT . '/orbicon/modules/faq/category.php';
}

if(isset($_GET['qid'])) {
	return include DOC_ROOT . '/orbicon/modules/faq/question.php';
}

if(isset($_POST['submit'])) {
	if(faq_post_q()) {
		// TODO notify about success
	}
}

if(isset($_GET['q'])) {
	return include DOC_ROOT . '/orbicon/modules/faq/search.php';
}

/*if(isset($_GET['convert'])) {
global $dbc;

	$r = sql_res('SELECT *, UNIX_TIMESTAMP(created) AS unix_created, UNIX_TIMESTAMP(live) AS unix_live FROM orbx_mod_ic_question');
	$aq = $dbc->_db->fetch_assoc($r);

	while($aq) {

		$a = sql_assoc('SELECT content FROM orbx_mod_ic_answer WHERE (question = %s)', $aq['id']);

		sql_insert('INSERT INTO '. TABLE_FAQ_QUESTION . ' (id, title, answer, category, poster, poster_id, submited, live, email, email_notify, permalink, lang, live_date) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)', array($aq['id'], $aq['title'], $a['content'], $aq['category'], '', $aq['author'], $aq['unix_created'], $aq['state'], $aq['mail'],$aq['notify'], get_permalink(utf8_html_entities($aq['title'], true)), 'hr', $aq['unix_live']));

		$aq = $dbc->_db->fetch_assoc($r);
	}

	/*$r = sql_res('SELECT * FROM orbx_mod_ic_category');
	$acat = $dbc->_db->fetch_assoc($r);
	while($acat) {

		$a = sql_assoc('SELECT COUNT(id) AS total_qs FROM orbx_mod_ic_question WHERE (category = %s) and (state = 1)', $acat['id']);
		$total_qs = $a['total_qs'];

		sql_insert('INSERT INTO '. TABLE_FAQ_CATEGORY . ' (id, title, total_qs, permalink, lang) VALUES (%s, %s, %s, %s, %s)', array($acat['id'], $acat['title'], $total_qs, get_permalink(utf8_html_entities($acat['title'], true)), 'hr'));

		$acat = $dbc->_db->fetch_assoc($r);
	}
}*/

return '
	<p class="ic_intro">Kako bismo Vam omogućili da brzo i jednostavno saznate odgovore na Vaša pitanja, kao i dodatne informacije o temi koja Vas zanima, pripremili smo za Vas listu odgovora na najčešće postavljana pitanja. Pitanja su razvrstana prema proizvodima i uslugama banke, a ukoliko među njima ne pronađete odgovor kojeg tražite, obratite nam se putem obrasca "Pitajte nas".</p>

	<p id="ic_searchNote"><label for="search_string">Unesite nekoliko početnih slova pojma kojeg tražite i odaberite pitanje sa padajuće liste</label></p>

	<form id="search_ic" method="get" action="" class=" yui-skin-sam">
		<input id="sp" name="sp" value="search" type="hidden" />
		<input id="hr" name="hr" value="mod.faq" type="hidden" />

		<div class="search_form_inline">
			<input name="q" id="search_string" value="' . htmlspecialchars($_GET['q']) . '" type="text" />
			<input name="submit_search" id="submit_search" value="Traži" type="submit" />
		</div>

		<div id="faq_search_container"></div>

		<div class="cleaner"></div>
	</form>

	<dl id="ic_categories">
		<dt><strong>Kategorije</strong></dt>
		' . faq_get_all_categories() . '
	</dl>
	' . faq_last_five() . '
	<script type="text/javascript" src="./orbicon/3rdParty/yui/build/datasource/datasource-min.js?'.ORBX_BUILD.'"></script>
	<script type="text/javascript" src="./orbicon/3rdParty/yui/build/autocomplete/autocomplete-min.js?'.ORBX_BUILD.'"></script>
	<script type="text/javascript" src="./orbicon/modules/faq/faq.js?'.ORBX_BUILD.'"></script>
	';

?>