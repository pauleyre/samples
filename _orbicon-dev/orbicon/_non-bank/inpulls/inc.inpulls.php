<?php
/**
 * Inpulls library
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package SystemMOD
 * @subpackage Inpulls
 * @version 1.00
 * @link http://www.inpulls.com
 * @license http://
 * @since 2007-12-09
 */
define('TABLE_INPULLS_PROFILE', 'orbx_mod_inpulls_profile');
define('TABLE_INPULLS_MOB', 'orbx_mod_inpulls_mobbing');
define('TABLE_INPULLS_COMMENTS', 'orbx_mod_inpulls_comments');

define('INPULLS_PROFILE_NONSPONSORED', 0);

define('INPULLS_PROFILE_SPONSORED', 1);

global $sponsored_profile_id;
$sponsored_profile_id = array();

global $inpulls_sex;
$inpulls_sex = array(0 => 'Muško', 1 => 'Žensko');

global $inpulls_im_here_for;
$inpulls_im_here_for = array(1 => 'curu/dečka', 2 => 'malo ljubavi', 3 => 'rame za plakanje', 4 => 'zabavu', 5 => 'ništa', 6 => 'posao/zaradu', 7 => 'prijateljstvo', 8 => 'izgubljenu baku', 9 => 'curu', 10 => 'dečka');

global $inpulls_currently_im;
$inpulls_currently_im = array(1 => 'slobodan/na \'ko ptica', 2 => 'u vezi', 3 => 'zaručen/a', 4 => 'u braku', 5 => 'razveden/a', 6 => 'u sedmom nebu', 7 => 'daj šta daš');

global $inpulls_sex_group;
$inpulls_sex_group = array(1 => 'muško/ženski', 2 => 'žensko/muški', 3 => 'muško/muški', 4 => 'žensko/ženski', 5 => 'muški/ovca', 6 => 'bez komentara');

global $inpulls_drinks;
$inpulls_drinks = array(1 => 'Bambus', 2 => 'Pivo', 4 => 'Red bull', 8 => 'Martini', 16 => 'Baileys', 32 => 'B52', 64 => 'Kamikaza', 128 => 'Orgasam', 256 => 'Mojito', 512 => 'Sex on the beach', 1024 => 'Cosmopolitan', 2048 => 'Sve ide');

global $inpulls_music;
$inpulls_music = array(1 => 'Domaće', 2 => 'Pop / Disco', 4 => 'RnB', 8 => 'Rap / Hip-Hop', 16 => 'Rock / Metal', 32 => 'Narodnjaci, cajke', 64 => 'House, techno, trance', 128 => 'Sve osim rock-a', 256 => 'Sve osim cajki', 512 => 'Oldies but goldies', 1024 => 'Sve živo');

global $inpulls_treat_girls;
$inpulls_treat_girls = array(
0 => '&mdash;',
1 => 'kao kap vode na dlanu',
2 => 'potpišemo sponzorski ugovor',
3 => 'pravi sam papučar',
4 => 'ja radim ona kuha',
5 => 'lažem je i muljam dok ide',
6 => 'ja Tarzan ti Jane');

global $inpulls_had_girls;
$inpulls_had_girls = array(
0 => '&mdash;',
1 => 'nekoliko cura',
2 => 'svaki dan druga',
3 => 'lovi se paučina dolje',
4 => 'curu, jedino kad skupim 100 eura',
5 => 'dvi, tri simpatije',
6 => 'ljubav svog života');

global $inpulls_go_shopping;
$inpulls_go_shopping = array(
0 => '&mdash;',
1 => 'Shopping? Kakav shopping?',
2 => 'Idemo barem 7 puta, za svaki dan u tjednu',
);

global $inpulls_special_skills;
$inpulls_special_skills = array(
0 => '&mdash;',
1 => 'vozim kanu na divljim vodama Save',
2 => 'znam napamet telefonski imenik',
3 => 'pojedem 37 jaja za dobro jutro',
4 => 'znam se penjat na sve vrste drveća',
5 => 'dubim na glavi'
);

global $inpulls_see_in_future;
$inpulls_see_in_future = array(
0 => '&mdash;',
1 => 'u saboru odmah kraj Šeksa',
2 => 'direktor poduzeća za export i import',
3 => 'vozim šleper',
4 => 'prolupao i odselio u Tibet',
5 => 'vodim legiju stranaca',
6 => 'kucam na vrata i prodajem kalendare'
);

global $inpulls_when_i_was_little;
$inpulls_when_i_was_little = array(
0 => '&mdash;',
1 => 'balerina',
2 => 'teta u vrtiću',
3 => 'go go plesaćica',
4 => 'glumica u hrvatskoj sapunici',
5 => 'muško'
);

global $inpulls_if_i_could;
$inpulls_if_i_could = array(
0 => '&mdash;',
1 => 'kupovala',
2 => 'sređivala nokte',
3 => 'pila pivu i kartala belu',
4 => 'bila sa dečkima u krevetu',
5 => 'kuhala i spremala po kući'
);

global $inpulls_from_boyfriend;
$inpulls_from_boyfriend = array(
0 => '&mdash;',
1 => 'ključeve od stana i auta',
2 => 'da me čuva od pijanih budala',
3 => 'da sam mu jedina na svijetu',
4 => 'serenade pod prozorom',
5 => 'da me voli više nego mamu svoju'
);

global $inpulls_special_skills_girl;
$inpulls_special_skills_girl = array(
0 => '&mdash; odaberi posebne vještine &mdash;',
1 => 'ronim školjke po Jadranu',
2 => 'honorarno vozim bager',
3 => 'skupljam salvete od II. svjetskog rata',
4 => 'sve vještine vezane za krevet'
);

global $inpulls_horoscope;
$inpulls_horoscope = array(
0=> 'Odaberi znak', 1 => 'Ovan', 2 => 'Bik', 3 => 'Blizanci', 4 => 'Rak', 5 => 'Lav', 6 => 'Djevica', 7 => 'Vaga', 8 => 'Škorpion', 9 => 'Strijelac', 10 => 'Jarac', 11 => 'Vodenjak', 12 => 'Ribe'
);

global $inpulls_horoscope_gfx;
$inpulls_horoscope_gfx = array(
1 => 'Aries.gif', 2 => 'Taurus.gif', 3 => 'Gemini.gif', 4 => 'Cancer.gif', 5 => 'Leo.gif', 6 => 'Virgo.gif', 7 => 'Libra.gif', 8 => 'Scorpio.gif', 9 => 'Sagittarius.gif', 10 => 'Capricorn.gif', 11 => 'Aquarius.gif', 12 => 'Pisces.gif'
);

global $inpulls_smoker;
$inpulls_smoker = array(
0 => '&mdash;',
1 => 'Da',
2 => 'Ne',
3 => 'Zapalim tu i tamo, uz pivu',
4 => 'Pokušavam prestat',
5 => 'Da al\' ne cigarete'
);

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 * @return object
 */
function load_inpulls_profile($id)
{
	global $dbc;

	$r = $dbc->_db->query(sprintf('SELECT * FROM ' . TABLE_INPULLS_PROFILE . ' WHERE (id = %s)', $dbc->_db->quote($id)));
	return $dbc->_db->fetch_object($r);
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $prid
 * @return object
 */
function get_iprofile_from_pring($prid)
{
	global $dbc;

	$r = $dbc->_db->query(sprintf('SELECT * FROM ' . TABLE_INPULLS_PROFILE . ' WHERE (pring_id = %s) LIMIT 1', $dbc->_db->quote($prid)));
	return $dbc->_db->fetch_object($r);
}

/**
 * Print links to inpulls tags
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $limit
 * @param int $estate_ad_id
 * @return string
 */
function print_inpulls_tag_cloud($limit = 10, $profile_id = null)
{
	if(!is_int($limit)) {
		trigger_error('print_inpulls_tag_cloud() expects parameter 1 to be integer, '.gettype($limit).' given', E_USER_WARNING);
		return false;
	}

	// sanity checks
	if($limit > 1000) {
		$limit = 1000;
	}
	elseif ($limit < 0) {
		$limit = 10;
	}

	global $dbc, $orbicon_x;

	$id_sql = ($profile_id != null) ? sprintf(' AND (id=%s) ', $dbc->_db->quote($profile_id)) : '';

	$q = '	SELECT 		tags
			FROM 		' . TABLE_INPULLS_PROFILE . '
			WHERE 		(tags != \'\')
						'.$id_sql.'
			ORDER BY	registered DESC';

	$r = $dbc->_db->query($q);
	$tag = $dbc->_db->fetch_object($r);
	$tag_cloud = array();
	$i = 1;
	$tag_popularity = array();

	while($tag) {

		$tags = explode(',', $tag->tags);

		foreach ($tags as $v) {
			$v = trim($v);
			if($v) {

				$tag_popularity[$v] ++;

				if($tag_popularity[$v] > 0) {
					$tp = 'fine';
				}
				if($tag_popularity[$v] > 3) {
					$tp = 'diminutive';
				}
				if($tag_popularity[$v] > 10) {
					$tp = 'tiny';
				}
				if($tag_popularity[$v] > 20) {
					$tp = 'small';
				}
				if($tag_popularity[$v] > 30) {
					$tp = 'medium';
				}
				if($tag_popularity[$v] > 60) {
					$tp = 'large';
				}
				if($tag_popularity[$v] > 100) {
					$tp = 'huge';
				}
				if($tag_popularity[$v] > 200) {
					$tp = 'gargantuan';
				}
				if($tag_popularity[$v] > 400) {
					$tp = 'colossal';
				}

				$tag_cloud[$v] = '<a class="tag '.$tp.'" href="'.url(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.inpulls.tags&amp;tag=' . urlencode($v), ORBX_SITE_URL . '/tag/' . urlencode($v)).'">'.$v.'</a>';
				$i ++;
			}
		}

		$tag = $dbc->_db->fetch_object($r);
	}

	$tag_cloud = array_unique($tag_cloud);

	// we specified upper limit
	if($limit) {
		$tag_cloud = array_slice($tag_cloud, 0, $limit);
	}
	elseif ($limit == 0) {
		ksort($tag_cloud);
	}

	$tag_cloud = implode(', ', $tag_cloud);
	return $tag_cloud;
}

/**
 * Print profiles
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param bool $sponsored
 * @return string
 */
function print_inpulls_profiles($sponsored = false, $sponsored_limit = 4)
{
	global $dbc, $orbicon_x, $orbx_mod, $sponsored_profile_id, $inpulls_im_here_for, $inpulls_currently_im, $inpulls_sex_group;

	if (isset($_GET['tag']) && isset($_GET['all'])) {
		// don't print twice
		if($sponsored) {
			return null;
		}

		$orbicon_x->set_page_title('Svi tagovi');
		$orbicon_x->add2breadcrumbs('Svi tagovi');
		return '<div id="all_tags">' . print_inpulls_tag_cloud(0) . '</div>';
	}

	if(isset($_GET['tag'])) {
		// don't print twice
		if($sponsored) {
			return null;
		}
	}

	if((isset($_GET['submit_bp']) || isset($_GET['submit_dp']) || isset($_GET['whos_online'])) && $sponsored) {
		return null;
	}

	// pagination
	require_once DOC_ROOT . '/orbicon/class/class.pagination.php';

	$_GET['pp'] = (isset($_GET['pp'])) ? $_GET['pp'] : 5;
	$_GET['p'] = (isset($_GET['p'])) ? $_GET['p'] : 1;

	// add rss
	/*if(isset($_GET[$orbicon_x->ptr])) {
		$orbicon_x->add_feed_link(ORBX_SITE_URL.'/orbicon/modules/inpulls/rss.php?c='.$_GET[$orbicon_x->ptr], DOMAIN_NAME . ' - ' . $_GET[$orbicon_x->ptr]);
	}*/

	$ads = '';
	$search_kw = '';

	$ads = '<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/controler/gzip.server.php?file=/orbicon/modules/inpulls/inpulls.js&amp;'.ORBX_BUILD.'"></script>';

	$pagination = new Pagination('p', 'pp');

	include_once DOC_ROOT.'/orbicon/modules/forms/class.form.php';
	$form = new Form;
	$counties = $form->get_pring_db_table('pring_counties', true);

	$search_qs = str_replace(array(',', ':', '.', '-'), ' ', $_GET['q']);
	$search_qs = explode(' ', $search_qs);

	foreach ($search_qs as $search_q) {
		$search_q_clean = $search_q;
		$search_q_clean_lc = strtolower($search_q_clean);
		$search_q = $dbc->_db->quote( '%' . $search_q . '%');
		if(isset($_GET['q'])) {

			if(in_array($search_q_clean_lc, array('dečko', 'dečki', 'decko', 'decki', 'momak', 'momci', 'muškarci', 'muškarac'))) {
				$search_kw .= ' AND (pring_id IN (SELECT id FROM pring_contact WHERE contact_sex = 0)) ';
			}
			elseif (in_array($search_q_clean_lc, array('cura', 'djevojka', 'cure', 'curica', 'curice', 'žene', 'žena', 'treba'))) {
				$search_kw .= ' AND (pring_id IN (SELECT id FROM pring_contact WHERE contact_sex = 1)) ';
			}
			else {
				$search_kw .= sprintf(' AND (
				(more_info LIKE %s) OR
				(tags LIKE %s) OR
				(hobby LIKE %s) OR
				(pring_id IN (SELECT id FROM pring_contact WHERE contact_name LIKE %s)) OR
				(pring_id IN (SELECT id FROM pring_contact WHERE contact_surname LIKE %s)) OR
				(pring_id IN (SELECT id FROM pring_contact WHERE contact_town_text LIKE %s)) OR
				(pring_id IN (SELECT pring_contact_id FROM '.TABLE_REG_USERS.' WHERE (username LIKE %s) AND (banned = 0))) OR


				(pring_id IN (SELECT id FROM pring_contact WHERE contact_region IN (SELECT id FROM pring_counties WHERE title LIKE %s)))


				OR


				(pring_id IN (SELECT id FROM pring_contact WHERE contact_city IN (SELECT id FROM pring_towns WHERE town LIKE %s)))






				) ', $search_q, $search_q, $search_q, $search_q, $search_q, $search_q, $search_q, $search_q, $search_q);

				if(is_numeric($search_q_clean)) {
					// years from
					if(intval($search_q_clean)) {
						$search_kw .= sprintf(' AND (pring_id IN (SELECT id FROM pring_contact WHERE contact_dob <= %s)) ', $dbc->_db->quote(mktime(0, 0, 0, 1, 1, (date('Y') - (intval($search_q_clean) - 1)))));
					}
				}
			}
		}
	}

	if(isset($_GET['q'])) {
		// image only
		if($_REQUEST['sa_slikom']) {
			$search_kw .= '  AND (pring_id IN (SELECT id FROM pring_contact WHERE picture != \'\'))  ';
		}
		// video only
		if($_REQUEST['sa_videom']) {
			$search_kw .= ' AND (video != \'\') ';
		}
		// map only
		if($_REQUEST['sa_kartom']) {
			$search_kw .= ' AND (
				((longitude != \'15.954895\') AND (latitude != \'45.796255\')) AND
				((longitude != \'15.95489500000000049340087571181356906890869140625\') AND (latitude != \'45.796255000000002155502443201839923858642578125\')) AND
				((longitude != \'\') AND (latitude != \'\'))
			) ';
		}
	}

	$user_sql = (isset($_REQUEST['filter_by_user'])) ? ' AND (user_id = '.$dbc->_db->quote($_REQUEST['filter_by_user']).')' : '';

	// log statistics if found
	if($orbx_mod->validate_module('stats') && $_SESSION['site_settings']['stats_attila'] && !$sponsored && isset($_GET['q'])) {
		include_once DOC_ROOT . '/orbicon/modules/stats/class.stats.php';
		$stats = new Statistics();
		$stats->log_attila_search_keywords($_GET['q']);
		$stats = null;
	}

	if($sponsored && ($_GET[$orbicon_x->ptr] == '')) {
		$ar_sort = array(
			'online' => 'Online',
			'offline' => 'Offline',
			'views_asc' => 'Popularnosti: prvo manje popularni',
			'views_desc' => 'Popularnosti: prvo više popularni',
			'date_asc' => 'Datumu: prvo najstariji',
			'date_desc' => 'Datumu: prvo najnoviji'/*,
			'title_asc' => 'Imenu: A - Z',
			'title_desc' => 'Imenu: Z - A'*/
		);

		$default_sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'date_desc';

		$r_tot = $dbc->_db->query('SELECT COUNT(id) AS total FROM ' . TABLE_INPULLS_PROFILE);
		$a_tot = $dbc->_db->fetch_assoc($r_tot);

		$h3title = (isset($_GET['whos_online']) || isset($_GET['submit']) || isset($_GET['submit_bp']) || isset($_GET['submit_dp'])) ? 'Rezultati pretrage' : 'Najnoviji korisnici (ukupno <span>'.number_format($a_tot['total'], 0, ',', ' ').'</span>)';

		$ads .= '<div id="results">
        <h3>'.$h3title.'</h3>
        <label for="select_url"><span> Poredaj po</span></label>

<select id="select_url" onchange=
"javascript: redirect(orbx_site_url + \'/?sort=\' + $(\'select_url\').options[$(\'select_url\').selectedIndex].value);">
'.print_select_menu($ar_sort, $default_sort, true).'
</select>
      </div>';
	}

	switch ($_GET['sort']) {
		case 'views_asc': $sort_by = 'views ASC'; break;
		case 'views_desc': $sort_by = 'views DESC'; break;
		case 'date_asc': $sort_by = 'registered ASC'; break;
		case 'date_desc': $sort_by = 'registered DESC'; break;
		//case 'title_asc': $sort_by = 'title ASC'; break;
		//case 'title_desc': $sort_by = 'title DESC'; break;
		case 'online': $sort_by = ' online_last_activity DESC '; break;
		case 'offline': $sort_by = ' online_last_activity ASC '; break;
		default: $sort_by = 'registered DESC'; break;
	}

	if(isset($_REQUEST['submit_bp'])) {
		$q = '	SELECT 		*
				FROM 		'.TABLE_INPULLS_PROFILE.'
				WHERE		(sponsored	= '.$dbc->_db->quote(INPULLS_PROFILE_NONSPONSORED).') ' .
				$user_sql .
				build_inpulls_fastsearch_sql() . '
				LIMIT 		'.$dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']).', ' . $dbc->_db->quote($_GET['pp']);
	}
	elseif (isset($_REQUEST['submit_dp'])) {
		$q = '	SELECT 		*
				FROM 		'.TABLE_INPULLS_PROFILE.'
				WHERE		(sponsored	= '.$dbc->_db->quote(INPULLS_PROFILE_NONSPONSORED).') ' .
				$user_sql .
				build_inpulls_deepsearch_sql() .
				build_inpulls_fastsearch_sql() . '
				LIMIT 		'.$dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']).', ' . $dbc->_db->quote($_GET['pp']);
	}
	elseif (isset($_GET['whos_online'])) {
		$q = '	SELECT 		*
				FROM 		'.TABLE_INPULLS_PROFILE.'
				WHERE		((UNIX_TIMESTAMP() - online_last_activity ) <= 1800)
				GROUP BY	id
				ORDER BY 	online_last_activity DESC
				LIMIT 		'.$dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']).', ' . $dbc->_db->quote($_GET['pp']);

	}
	elseif (isset($_GET['tag'])) {

		$orbicon_x->set_page_title('Rezultati za tag &quot;' . htmlspecialchars($_GET['tag']) . '&quot;');
		$orbicon_x->add2breadcrumbs('Rezultati za tag &quot;' . htmlspecialchars($_GET['tag']) . '&quot;');

		$tag_q = (isset($_GET['tag'])) ? sprintf(' AND (tags LIKE %s) ', $dbc->_db->quote( '%' . $_GET['tag'] . '%')) : '';

		$q = '	SELECT 		*
				FROM 		'.TABLE_INPULLS_PROFILE.'
				WHERE		(sponsored	= '.$dbc->_db->quote(INPULLS_PROFILE_NONSPONSORED).') ' .
				$tag_q .	'
				GROUP BY	id
				ORDER BY 	registered DESC
				LIMIT 		'.$dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']).', ' . $dbc->_db->quote($_GET['pp']);
	}
	elseif ($sponsored) {

		$sponsored_menu_sql = sprintf(' AND (sponsored_category=%s) ', $dbc->_db->quote($_GET[$orbicon_x->ptr]));

		$q = '	SELECT 		*
				FROM 		'.TABLE_INPULLS_PROFILE.'
				WHERE		(sponsored = '.$dbc->_db->quote(INPULLS_PROFILE_SPONSORED).') ' .
							$sponsored_menu_sql.' AND
							(sponsored_live_to >= '.time().')
				ORDER BY 	RAND()
				LIMIT 		' . $sponsored_limit;
	}
	else {

		$q = '	SELECT 		*
				FROM 		'.TABLE_INPULLS_PROFILE.'
				WHERE		(sponsored	= '.$dbc->_db->quote(INPULLS_PROFILE_NONSPONSORED).')' .
				$user_sql .
				$search_kw . '
				ORDER BY 	'.$sort_by.'
				LIMIT 		'.$dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']).', ' . $dbc->_db->quote($_GET['pp']);
	}
	$r = $dbc->_db->query($q);

	$user = $dbc->_db->fetch_object($r);

	if(!isset($_GET['tag'])) {
		if(isset($_GET['whos_online'])) {
			$q_tot = '	SELECT 		COUNT(id)
						AS			numrows
						FROM 		'.TABLE_INPULLS_PROFILE.'
						WHERE		((UNIX_TIMESTAMP() - online_last_activity ) <= 1800)';
		}
		else {
			$q_tot = '	SELECT 		COUNT(id)
						AS 			numrows
						FROM 		'.TABLE_INPULLS_PROFILE.'
						WHERE		(sponsored	= '.$dbc->_db->quote(INPULLS_PROFILE_NONSPONSORED).') '
						. $user_sql
						. $search_kw
						. build_inpulls_deepsearch_sql()
						. build_inpulls_fastsearch_sql();
		}
	}
	else {
		$tag_q = (isset($_GET['tag'])) ? sprintf(' AND (tags LIKE %s) ', $dbc->_db->quote( '%' . $_GET['tag'] . '%')) : '';

		$q_tot = '	SELECT 		COUNT(id)
					AS			numrows
					FROM 		'.TABLE_INPULLS_PROFILE.'
					WHERE		(sponsored	= '.$dbc->_db->quote(INPULLS_PROFILE_NONSPONSORED).') ' .
					$tag_q;
	}

	$read = $dbc->_db->query($q_tot);

	$row = $dbc->_db->fetch_assoc($read);
	$pagination->total = $row['numrows'];
	$pagination->split_pages();
	unset($read, $row, $q_tot);

	// remember sponsored id
	if($sponsored && $user->id) {
		$sponsored_profile_id[] = $user->id;
	}

	// no results found
	if(!$user && !$sponsored) {
		if(isset($_GET['whos_online'])) {
			$ads .= '<p id="no_ads">Nitko nije online</p>';
		}
		else {
			$ads .= '<p id="no_ads">Nema rezultata za vaš upit</p>';
		}
	}

	include_once DOC_ROOT . '/orbicon/modules/peoplering/class/class.peoplering.php';

	while ($user) {

		// skip this one since we already displayed it
		if(in_array($user->id, $sponsored_profile_id) && !$sponsored) {
			// do nothing, don't use continue; here
		}
		else {

			$pr = new Peoplering($user->pring_id);

			$user_data = $pr->get_profile($user->pring_id);

			$picture = $user_data['picture'];

			if(is_file(DOC_ROOT . '/site/venus/thumbs/t-' . $picture)) {
				$picture = ORBX_SITE_URL . '/site/venus/thumbs/t-' . $picture;
			}
			elseif (is_file(DOC_ROOT . '/site/venus/' . $picture)) {
				$picture = ORBX_SITE_URL . '/site/venus/' . $picture;
			}
			else {
				$picture = ORBX_SITE_URL . '/orbicon/modules/peoplering/gfx/unknownUser.gif';
			}

			$username = $pr->get_username($pr->get_rid_from_prid($user->pring_id));
			$username = $username['username'];

			$js_name = str_sanitize($username, STR_SANITIZE_JAVASCRIPT);
			$js_name = addslashes(str_replace('"', '', $js_name));

			$title_username = ($user_data['contact_name'] != '') ? $user_data['contact_name'] . ' ' . $user_data['contact_surname'] : $username;

			$sex = (!$user_data['contact_sex']) ? 'M' : 'Ž';

			$age = get_age($user_data['contact_dob']);
			if($age) {
				$title_username .= " ($age god. / $sex)";
			}

			$url = (get_is_member()) ? ORBX_SITE_URL . '/?user=' . $username . '&amp;' . $orbicon_x->ptr . '=mod.inpulls.profile' : ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=user&amp;user='.$username;

			if($user->more_info) {
				$more_info = '<li>Poruka svijetu <strong>'.$user->more_info.'</strong></li>';
			}
			else {
				$more_info = '';
			}

			$description = '<ul><li>Trenutno sam... <strong>'.$inpulls_currently_im[$user->currently_im].'</strong></li><li>Spolno opredjeljenje <strong>'.$inpulls_sex_group[$user->sex_group].'</strong></li>'.$more_info.'</ul>';

			$class = ($sponsored) ? ' sponzorirani' : '';

			$country = get_country_by_id(intval($user_data['contact_country']));

			$online_color = inpulls_is_online($user->pring_id) ? '#BB0110' : '#E1E1E1';

			if(!$country['domain_ext']) {
				$country['domain_ext'] = 'A1';
				$country['title'] = '?';
			}

			$ads  .= '
		<div class="oglas '.$class.'">
	        <div class="naslov">
	          <h4><a href="'.$url.'">'.$title_username.'</a></h4>
	          <p>
	          	<span class="flag"><img src="./orbicon/gfx/flag_icons/'.$country['domain_ext'].'.gif" alt="'.$country['title'].'" title="'.$country['title'].'" style="margin: 0 15px 0 0 !important;" /></span>
	            <span class="spremiOglas"><a href="javascript:void(null);" onclick="javascript:fav_profile('.$user->id.', \'add\');" title="Spremi korisnika">Spremi korisnika</a></span>
	            <span class="tekstOglasa"><a href="javascript:void(null);"><em><span>'.nl2br($description).'</span></em></a></span>
	          </p>
	          <div class="clr"></div>
	        </div>
	        <a href="'.$url.'"><img style="border-color:'.$online_color.'" src="'.$picture.'" alt="'.$picture.'" title="'.$title_username.'" class="img" /></a>';

			$town = ($user_data['contact_city']) ? get_town_by_id(intval($user_data['contact_city'])) : $user_data['contact_town_text'];
			$county = $counties[$user_data['contact_region']];

			$location = array();
			$location[] = $town;
			$location[] = (($user_data['contact_region'] == 2) || !$user_data['contact_region']) ? $county : "$county županija";
			$location = array_remove_empty($location);
			$location = implode(', ', $location);

			if(!$location) {
				$location = 'Ne zna se...';
			}

        	$ads .= '<ul class="detalji">
          <li><span>Mjesto:</span> '.$location.'</li>
          <li><span>Traži:</span> '.$inpulls_im_here_for[$user->im_here_for].'</li>
          <li><span>&bull;</span> <a title="'._L('pr-send-msg').'" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=mail&amp;to='.$username.'">'._L('pr-send-msg').'</a></li>
          <li><span>&bull;</span> <a title="'._L('pr-add-contacts').'" href="javascript:void(null);" onclick="javascript:add2contacts(\'' . $js_name . '\');">'._L('pr-add-contacts').'</a></li>
          <li class="no_border"><span>&bull;</span> <a title="'._L('pr-view-friends').'" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=friends&amp;user='.$username.'">'._L('pr-view-friends').'</a></li>
        </ul>';

	        $ads .= '<div class="clr"></div>
	      </div>';
		}
		$user = $dbc->_db->fetch_object($r);
	}

	// exit here for sponsored, we don't want anything below
	if($sponsored) {
		return $ads;
	}

	if(isset($_GET['q'])) {
		$ads .= '<div class="use_adv_search">Ukoliko niste pronašli željenog korisnika, nastavite pretragu sa <strong>Detaljnom tražilicom</strong></div>';
	}

	// add rss
	/*if(isset($_GET[$orbicon_x->ptr]) && ($_GET[$orbicon_x->ptr] != 'mod.peoplering') && !isset($_GET['tag'])) {
		$ads .= '<div id="rss_icon"><a href="'.ORBX_SITE_URL.'/orbicon/modules/estate/rss.php?c='.$_GET[$orbicon_x->ptr].'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/rss.png" alt="RSS" title="RSS" /> RSS</a></div>';
	}*/

	/*$ads .= '
      <div id="oglasavajte_se"><a href="./?'.$orbicon_x->ptr.'=dobrodo%C5%A1li-na-stranice-marketinga&amp;no-override" title="Oglašavajte se na našim stranicama"><img src="'.ORBX_SITE_URL.'/site/gfx/images/oglasavajte-se.jpg" alt="Oglašavajte se na našim stranicama" title="Oglašavajte se na našim stranicama" /></a></div>';*/

	unset($_GET['p'], $_GET['pp']);

	$query = http_build_query($_GET);
	if($query) {
		$query = '/?' . $query;
	}
	else {
		$query = '/';
	}
	$ads .= $pagination->construct_page_nav(ORBX_SITE_URL . $query);

	include_once DOC_ROOT . '/orbicon/modules/forum/class.forum.php';
	$forum = new Forum();

	return $ads . print_frontpage_gallery() . $forum->get_lastest_forum_summary();
}

/**
 * Add favorite profile
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 */
function add_favorite_profile($id)
{
	$favs = $_COOKIE['favoriteprofile'];
	$favs = explode(',', $favs);
	$favs[] = $id;
	$favs = array_remove_empty($favs);
	$favs = array_unique($favs);
	$favs = implode(',', $favs);

	// remember for 999 days
	setcookie('favoriteprofile', $favs, (time() + 86313600), '/');
	$_SESSION['inpulls_favoriteprofile'] = $favs;
}

/**
 * Remove favorite profile
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 */
function remove_favorite_profile($id)
{
	$favs = $_COOKIE['favoriteprofile'];
	$favs = explode(',', $favs);

	$new = array();

	foreach ($favs as $fav) {
		if($fav != $id) {
			$new[] = $fav;
		}
	}

	$new = array_remove_empty($new);
	$new = array_unique($new);
	$favs = implode(',', $new);

	// remember for 999 days
	setcookie('favoriteprofile', $favs, (time() + 86313600), '/');
	$_SESSION['inpulls_favoriteprofile'] = $favs;
}

/**
 * Print favorite profiles
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @return string
 */
function print_favorite_profiles()
{
	global $dbc, $orbicon_x, $inpulls_im_here_for;

	$favs = ($_SESSION['inpulls_favoriteprofile']) ? $_SESSION['inpulls_favoriteprofile'] : $_COOKIE['favoriteprofile'];
	$favs = explode(',', $favs);
	$favs = array_remove_empty($favs);
	// newer profiles go first
	$favs = array_reverse($favs);

	$profiles = '';

	$total = 0;

	$profiles_header = '<h3 id="spremljeniOglasi">Spremljeni korisnici <span class="small">('.$total.')</span></h3>';

	if(empty($favs[0]) || !is_array($favs)) {
		return $profiles;
	}

	include_once DOC_ROOT . '/orbicon/modules/peoplering/class/class.peoplering.php';

	foreach ($favs as $fav) {

		$q = '	SELECT 		*
				FROM 		' . TABLE_INPULLS_PROFILE . '
				WHERE 		(id = ' . $fav . ')
				LIMIT 		1';

		$r = $dbc->_db->query($q);
		$user = $dbc->_db->fetch_object($r);

		if(empty($user)) {
			continue;
		}

		$total ++;

		$pr = new Peoplering($user->pring_id);

		$user_data = $pr->get_profile($user->pring_id);

		$picture = $user_data['picture'];

		if(is_file(DOC_ROOT . '/site/venus/thumbs/t-' . $picture)) {
				$picture = ORBX_SITE_URL . '/site/venus/thumbs/t-' . $picture;
		}
		elseif (is_file(DOC_ROOT . '/site/venus/' . $picture)) {
			$picture = ORBX_SITE_URL . '/site/venus/' . $picture;
		}
		else {
			$picture = ORBX_SITE_URL . '/orbicon/modules/peoplering/gfx/unknownUser.gif';
		}

		$username = $pr->get_username($pr->get_rid_from_prid($user->pring_id));
		$username = $username['username'];

		$js_name = str_sanitize($username, STR_SANITIZE_JAVASCRIPT);
		$js_name = addslashes(str_replace('"', '', $js_name));

		$title_username = ($user_data['contact_name'] != '') ? $user_data['contact_name'] . ' ' . $user_data['contact_surname'] : $username;

		$age = get_age($user_data['contact_dob']);
		if($age) {
			$title_username .= " ($age god.)";
		}

		$town = ($user_data['contact_city'] != '') ? get_town_by_id(intval($user_data['contact_city'])) : $user_data['contact_town_text'];
		$county = $counties[$user_data['contact_region']];

		$location = array();
		$location[] = $town;
		$location[] = (($user_data['contact_region'] == 2) || !$user_data['contact_region']) ? $county : "$county županija";
		$location = array_remove_empty($location);
		$location = implode(', ', $location);

		if(!$location) {
			$location = 'Ne zna se...';
		}

		$url = (get_is_member()) ? ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.inpulls.profile&amp;user=' . $username : ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=user&amp;user='.$username;

		$online_color = inpulls_is_online($user->pring_id) ? '#BB0110' : '#E1E1E1';

		$profiles .= '<div class="spremljeniOglas">
    <dl class="naslov">
      <dt><strong><a href="'.$url.'">'.$title_username.'</a></strong></dt>
      <dd><a href="'.$url.'"><img style="border-color:'.$online_color.'" src="'.$picture.'" alt="'.$picture.'" class="slika" /></a></dd>
    </dl>';

		$profiles .= '
    <dl class="detalji">
      <dd><strong>Mjesto:</strong> '.$location.'</dd>
      <dd><strong>Traži:</strong> '.$inpulls_im_here_for[$user->im_here_for].'</dd>
      <dd class="obrisi"><a href="javascript:void(null)" onclick="javascript:fav_profile('.$user->id.', \'remove\');" title="Obriši korisnika">Obriši korisnika</a></dd>
    </dl>';

		$profiles .='
    <div class="clr"></div>
   </div>';
	}

	$profiles_header = '<h3 id="spremljeniOglasi">Spremljeni korisnici <span class="small">('.$total.')</span></h3>';

	return $profiles_header . $profiles;
}

/**
 * Build SQL for fast search
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @return string
 */
function build_inpulls_fastsearch_sql()
{
	global $dbc;
	$sql = '';

	// don't interfere with deep search ID
	if($_REQUEST['br_korisnika']) {
		return '';
	}

	// im_here_for
	if($_REQUEST['im_here_for']) {
		$sql .= sprintf(' AND (im_here_for = %s) ', $dbc->_db->quote($_REQUEST['im_here_for']));
	}
	// sex_group
	if($_REQUEST['sex_group']) {
		$sql .= sprintf(' AND (sex_group = %s) ', $dbc->_db->quote($_REQUEST['sex_group']));
	}
	// county
	if($_REQUEST['regija']) {
		if($_REQUEST['regija'] != 1) {
			$sql .= sprintf('
			AND (pring_id IN (SELECT id FROM pring_contact WHERE contact_region = %s)) ', $dbc->_db->quote($_REQUEST['regija']));
		}
	}
	// town / neighborhood
	if($_REQUEST['naselje']) {
		$sql .= sprintf(' AND (pring_id IN (SELECT id FROM pring_contact WHERE contact_city = %s)) ', $dbc->_db->quote($_REQUEST['naselje']));
	}
	// horoscope
	if($_REQUEST['horoscope']) {
		$sql .= sprintf(' AND (horoscope = %s) ', $dbc->_db->quote($_REQUEST['horoscope']));
	}
	// years from
	if($_REQUEST['years_from']) {
		$sql .= sprintf(' AND (pring_id IN (SELECT id FROM pring_contact WHERE contact_dob <= %s)) ', $dbc->_db->quote(mktime(0, 0, 0, 1, 1, (date('Y') - ($_REQUEST['years_from'] - 1)))));
	}
	// years to
	if($_REQUEST['years_to']) {
		$sql .= sprintf(' AND (pring_id IN (SELECT id FROM pring_contact WHERE contact_dob >= %s)) ', $dbc->_db->quote(mktime(0, 0, 0, 1, 1, (date('Y') - $_REQUEST['years_to']))));
	}

	// sex
	if(isset($_REQUEST['sex'])) {
		$sql .= sprintf(' AND (pring_id IN (SELECT id FROM pring_contact WHERE contact_sex = %s)) ', $dbc->_db->quote($_REQUEST['sex']));
	}

	// image only
	if($_REQUEST['sa_slikom']) {
		$sql .= ' AND (pring_id IN (SELECT id FROM pring_contact WHERE picture != \'\')) ';
	}
	// video only
	if($_REQUEST['sa_videom']) {
		$sql .= ' AND (video != \'\') ';
	}
	// map only
	if($_REQUEST['sa_kartom']) {
		$sql .= ' AND (
				((longitude != \'15.954895\') AND (latitude != \'45.796255\')) AND
				((longitude != \'15.95489500000000049340087571181356906890869140625\') AND (latitude != \'45.796255000000002155502443201839923858642578125\')) AND
				((longitude != \'\') AND (latitude != \'\'))
			) ';
	}

	// sorting
	switch ($_REQUEST['poredak']) {
		case 'online': $sql .= ' ORDER BY online_last_activity DESC '; break;
		case 'offline': $sql .= ' ORDER BY online_last_activity DESC '; break;
		case 'popular_more': $sql .= ' ORDER BY views DESC '; break;
		case 'popular_less': $sql .= ' ORDER BY views ASC '; break;
		case 'date_older': $sql .= ' ORDER BY registered DESC '; break;
		case 'date_newer': $sql .= ' ORDER BY registered ASC '; break;
	}

	return $sql;
}

/**
 * Build SQL for deep search
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @return string
 */
function build_inpulls_deepsearch_sql()
{
	global $dbc;
	$sql = '';

	// currently_im
	if($_REQUEST['currently_im']) {
		$sql .= sprintf(' AND (currently_im = %s) ', $dbc->_db->quote($_REQUEST['currently_im']));
	}
	// favorite_band
	if($_REQUEST['favorite_band']) {
		$sql .= sprintf(' AND (favorite_band = %s) ', $dbc->_db->quote($_REQUEST['favorite_band']));
	}
	// activity
	if($_REQUEST['activity']) {
		$sql .= sprintf(' AND (activities = %s) ', $dbc->_db->quote($_REQUEST['activity']));
	}
	// hobby
	if($_REQUEST['hobby']) {
		$sql .= sprintf(' AND (hobby = %s) ', $dbc->_db->quote($_REQUEST['hobby']));
	}
	// eye_color
	if($_REQUEST['eye_color']) {
		$sql .= sprintf(' AND (eye_color = %s) ', $dbc->_db->quote($_REQUEST['eye_color']));
	}
	// hair_color
	if($_REQUEST['hair_color']) {
		$sql .= sprintf(' AND (hair_color = %s) ', $dbc->_db->quote($_REQUEST['hair_color']));
	}

	// ad id
	if($_REQUEST['br_korisnika']) {
		return sprintf(' AND (pring_id = %s) ', $dbc->_db->quote($_REQUEST['br_korisnika']));
	}

	$drinks = ((int) $_POST['drink_1'] | (int) $_POST['drink_2'] | (int) $_POST['drink_3'] | (int) $_POST['drink_4'] | (int) $_POST['drink_5'] | (int) $_POST['drink_6'] | (int) $_POST['drink_7'] | (int) $_POST['drink_8'] | (int) $_POST['drink_9'] | (int) $_POST['drink_10'] | (int) $_POST['drink_11'] | (int) $_POST['drink_12']);

	$music = ((int) $_POST['music_1'] | (int) $_POST['music_2'] | (int) $_POST['music_3'] | (int) $_POST['music_4'] | (int) $_POST['music_5'] | (int) $_POST['music_6'] | (int) $_POST['music_7'] | (int) $_POST['music_8'] | (int) $_POST['music_9'] | (int) $_POST['music_10'] | (int) $_POST['music_11']);

	if($drinks) {
		$sql .= sprintf(' AND (favorite_drinks & %s) ', $dbc->_db->quote($drinks));
	}

	if($music) {
		$sql .= sprintf(' AND (music & %s) ', $dbc->_db->quote($music));
	}


	return $sql;
}

/**
 * Calculate age
 *
 * @param mixed $birthday
 * @return int
 */
function get_age($birthday)
{
	if(!is_int($birthday)) {
		$birthday = intval($birthday);
	}

	$birthday = date('Y-m-d', $birthday);

	list($year, $month, $day) = explode('-', $birthday);

	if(!intval($year)) {
		return false;
	}

	$year_diff = date('Y') - $year;
	$month_diff = date('m') - $month;
	$day_diff = date('d') - $day;

	if ($month_diff < 0) {
		$year_diff--;
	}
	elseif (($month_diff==0) && ($day_diff < 0)) {
		$year_diff--;
	}
	return $year_diff;
}

/**
 * Get town name by ID
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 * @return string
 */
function get_town_by_id($id)
{
	if(!is_int($id)) {
		trigger_error('get_town_by_id() expects parameter 1 to be integer, '.gettype($id).' given', E_USER_WARNING);
		return false;
	}

	global $dbc;

	$q = sprintf('	SELECT		town
					FROM		pring_towns
					WHERE 		(id=%s)
					LIMIT		1',
					$dbc->_db->quote($id));
	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_assoc($r);

	return $a['town'];
}

/**
 * Get country by ID
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 * @return array
 */
function get_country_by_id($id)
{
	if(!is_int($id)) {
		trigger_error('get_country_by_id() expects parameter 1 to be integer, '.gettype($id).' given', E_USER_WARNING);
		return false;
	}

	global $dbc;

	$q = sprintf('	SELECT		*
					FROM		pring_countries
					WHERE 		(id=%s)
					LIMIT		1',
					$dbc->_db->quote($id));
	$r = $dbc->_db->query($q);
	return $dbc->_db->fetch_assoc($r);
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param string $userq
 * @return array
 */
function new_xhr_group($userq, $field)
{
	global $dbc;
	$similar_items = array();
	$q = sprintf('	SELECT 		'.$field.'
					FROM 		'.TABLE_INPULLS_PROFILE.'
					WHERE		('.$field.' LIKE %s)',
					$dbc->_db->quote("%$userq%"));

	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_assoc($r);

	while ($a) {

		$group = explode(',', $a[$field]);

		foreach ($group as $item) {
			if(strpos($item, $userq) !== false) {
				$similar_items[] = $item;
			}
		}

		$a = $dbc->_db->fetch_assoc($r);
	}

	return array_unique($similar_items);
}

/**
 * Edit inpulls profile
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $pr_id
 */
function edit_inpulls_profile($pr_id)
{
	global $dbc;

	$drinks = ((int) $_POST['drink_1'] | (int) $_POST['drink_2'] | (int) $_POST['drink_3'] | (int) $_POST['drink_4'] | (int) $_POST['drink_5'] | (int) $_POST['drink_6'] | (int) $_POST['drink_7'] | (int) $_POST['drink_8'] | (int) $_POST['drink_9'] | (int) $_POST['drink_10'] | (int) $_POST['drink_11'] | (int) $_POST['drink_12']);

	$music = ((int) $_POST['music_1'] | (int) $_POST['music_2'] | (int) $_POST['music_3'] | (int) $_POST['music_4'] | (int) $_POST['music_5'] | (int) $_POST['music_6'] | (int) $_POST['music_7'] | (int) $_POST['music_8'] | (int) $_POST['music_9'] | (int) $_POST['music_10'] | (int) $_POST['music_11']);

	$tags = str_replace(';', ',', $_POST['tags']);
	$tags = explode(',', $tags);
	$tags = array_map('trim', $tags);
	$tags = array_unique($tags);
	$tags = array_remove_empty($tags);
	$tags = implode(',', $tags);
	$tags = strtolower($tags);

	$keywords = keyword_generator($tags . $_POST['more_info']);

	$q = sprintf('	UPDATE 	' . TABLE_INPULLS_PROFILE . '
					SET 	im_here_for=%s, currently_im=%s,
							sex_group=%s, more_info=%s,
							hobby=%s, life_moto=%s,
							im_proud_of=%s, life_hero=%s,
							activities=%s, favorite_food=%s,
							favorite_book=%s, favorite_movie=%s,
							favorite_actor=%s, favorite_band=%s,
							favorite_song=%s, eye_color=%s,
							hair_color=%s, heritage=%s,
							best_physical_feature=%s, favorite_drinks=%s,
						    what_attracts_you_most=%s, hair_length=%s,
							weight=%s, height=%s,
							tattoo_piercings=%s, music=%s,
							tags=%s, keywords=%s,
							latitude=%s,longitude=%s,
							treat_girls=%s, had_girls=%s,
							crazy_thing_for_girls=%s, shopping_with_girl=%s,
							monthly_income=%s, special_skills=%s,
							you_in_future=%s, message_for_future_girl=%s,
							when_i_was_little=%s, all_day=%s,
							from_boyfriend=%s, special_skills_girls=%s,
							message_for_future_boy=%s, horoscope=%s
					WHERE 	(pring_id = %s)',
	$dbc->_db->quote($_POST['im_here_for']), $dbc->_db->quote($_POST['currently_im']),
	$dbc->_db->quote($_POST['sex_group']), $dbc->_db->quote($_POST['more_info']),
	$dbc->_db->quote($_POST['hobby']), $dbc->_db->quote($_POST['life_moto']),
	$dbc->_db->quote($_POST['im_proud_of']), $dbc->_db->quote($_POST['life_hero']),
	$dbc->_db->quote($_POST['activities']), $dbc->_db->quote($_POST['favorite_food']),
	$dbc->_db->quote($_POST['favorite_book']), $dbc->_db->quote($_POST['favorite_movie']),
	$dbc->_db->quote($_POST['favorite_actor']), $dbc->_db->quote($_POST['favorite_band']),
	$dbc->_db->quote($_POST['favorite_song']), $dbc->_db->quote($_POST['eye_color']),
	$dbc->_db->quote($_POST['hair_color']), $dbc->_db->quote($_POST['heritage']),
	$dbc->_db->quote($_POST['best_physical_feature']), $dbc->_db->quote($drinks),
	$dbc->_db->quote($_POST['what_attracts_you_most']), $dbc->_db->quote($_POST['hair_length']),
	$dbc->_db->quote($_POST['weight']), $dbc->_db->quote($_POST['height']),
	$dbc->_db->quote($_POST['tattoo_piercings']), $dbc->_db->quote($music),
	$dbc->_db->quote($tags), $dbc->_db->quote($keywords),
	$dbc->_db->quote($_POST['latitude']), $dbc->_db->quote($_POST['longitude']),
	$dbc->_db->quote($_POST['treat_girls']), $dbc->_db->quote($_POST['had_girls']),
	$dbc->_db->quote($_POST['crazy_thing_for_girls']), $dbc->_db->quote($_POST['shopping_with_girl']),
	$dbc->_db->quote($_POST['monthly_income']), $dbc->_db->quote($_POST['special_skills']),
	$dbc->_db->quote($_POST['you_in_future']), $dbc->_db->quote($_POST['message_for_future_girl']),
	$dbc->_db->quote($_POST['when_i_was_little']), $dbc->_db->quote($_POST['all_day']),
	$dbc->_db->quote($_POST['from_boyfriend']), $dbc->_db->quote($_POST['special_skills_girls']),
	$dbc->_db->quote($_POST['message_for_future_boy']),$dbc->_db->quote($_POST['horoscope']),
	$dbc->_db->quote($pr_id));

	$dbc->_db->query($q);
}

/**
 * Check if flag is set on bit
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $bit
 * @param int $flag
 * @return bool
 */
function get_inpulls_flag($bit, $flag)
{
	return (bool) ($bit & $flag);
}


function print_frontpage_gallery()
{
	global $dbc, $orbicon_x;

	$images = '';
	//$max_images_box = 2;
	//$max_image_box_previews = 3;
	//$css_width = intval(60 / $max_image_box_previews);
	//$n = 0;
	$i = 1;

	$r = $dbc->_db->query(sprintf('		SELECT 		*
										FROM 		'.VENUS_IMAGES.'
										WHERE 		(category LIKE %s)
										ORDER BY 	last_modified DESC
										LIMIT 		6',
	$dbc->_db->quote('pring_u_%')));

	$a = $dbc->_db->fetch_assoc($r);

	if(!$a) {
		return false;
	}

	$images = '<h3>Najnovije slike</h3><table style="text-align:center;width:100%;" id="image_gallery" summary="Image gallery" cellpadding="0"><tr>';

	while($a) {

		$img_link = (is_file(DOC_ROOT . '/site/venus/thumbs/t-'.$a['permalink'])) ? '<img id="image' . $i . '" class="thumb_image" style="width:150px;" src="'.ORBX_SITE_URL.'/site/venus/thumbs/t-'.$a['permalink'].'" alt="'.$a['permalink'].'" title="'.$a['permalink'].'" /></a>' : '<img style="width:150px;" src="'.ORBX_SITE_URL.'/site/venus/'.$a['permalink'].'" class="thumb_image" alt="'.$a['permalink'].'" title="'.$a['permalink'].'" id="image' . $i . '" />';
		//$title = substr($a['title'], 0, 20).'...';
		$user = str_replace('pring_u_', '', $a['category']);

		$images .= '
				<td>
				<div style="width:150px; overflow:auto;"><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=gallery&amp;user='.$user.'" title="'.$a['title'].'">'.$img_link.'</a></div>
				';

		if(($i % 3) == 0) {
			$images .= '</td></tr><tr>';
		}
		else {
			$images .= '</td>';
		}

		$a = $dbc->_db->fetch_assoc($r);
		$i ++;
	}

	$images .= '</tr></table>';

	return '<div class="latest_images">' . $images . '</div>';
}

/**
 * Update profile views by one
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 * @return int
 */
function update_profile_views($id)
{
	if(!is_int($id)) {
		trigger_error('update_profile_views() expects parameter 1 to be integer, '.gettype($id).' given', E_USER_WARNING);
		return false;
	}

	global $dbc;

	$q = sprintf('	UPDATE 		'.TABLE_INPULLS_PROFILE.'
					SET			views = (views + 1)
					WHERE 		(id=%s)',
					$dbc->_db->quote($id));
	$dbc->_db->query($q);

	return $dbc->_db->affected_rows();
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $prid
 * @return bool
 */
function inpulls_is_online($prid)
{
	global $dbc;

	$q = sprintf('	SELECT		online_last_activity
					FROM		' . TABLE_INPULLS_PROFILE . '
					WHERE 		(pring_id=%s)
					LIMIT		1',
					$dbc->_db->quote($prid));
	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_assoc($r);

	// we'll treat users online for 30 minutes maximum
	if((time() - $a['online_last_activity']) <= 1800) {
		return true;
	}
	return false;
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $user_rid
 * @param int $mobber_rid
 * @param string $reason
 */
function add_cesar($user_rid, $mobber_rid, $reason)
{
	global $dbc;

	$q = sprintf('	INSERT INTO 	'.TABLE_INPULLS_MOB.'
									(user_reg_id, mobber_reg_id,
									reason)
									VALUES (%s, %s, %s)',
					$dbc->_db->quote($user_rid), $dbc->_db->quote($mobber_rid),
					$dbc->_db->quote($reason));

	$dbc->_db->query($q);
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $user_rid
 * @param int $mobber_rid
 * @return bool
 */
function get_already_did_cesar($user_rid, $mobber_rid)
{
	global $dbc;

	$q = sprintf('	SELECT		id
					FROM		' . TABLE_INPULLS_MOB . '
					WHERE 		(user_reg_id=%s) AND
								(mobber_reg_id=%s)
					LIMIT		1',
					$dbc->_db->quote($user_rid), $dbc->_db->quote($mobber_rid));
	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_assoc($r);

	// already mobbed by this mobber
	if($a['id']) {
		return true;
	}
	return false;
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $user_rid
 * @param int $limit
 */
function check_cesar_limit($user_rid, $limit = 50)
{
	global $dbc;

	$q = sprintf('	SELECT		COUNT(id) AS total
					FROM		' . TABLE_INPULLS_MOB . '
					WHERE 		(user_reg_id=%s) AND
								(reason != \'\')',
					$dbc->_db->quote($user_rid));
	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_assoc($r);

	// total mobs are greater than limit. ban user
	if($a['total'] > $limit) {
		return ban_user($user_rid);
	}
	return false;
}

/**
 * Enter description here...
 *
 * @param unknown_type $user_rid
 * @param unknown_type $user_email
 */
function send_cesar_ban_email($user_rid, $user_email)
{
	global $dbc;

	$q = sprintf('	SELECT		reason
					FROM		' . TABLE_INPULLS_MOB . '
					WHERE 		(user_reg_id=%s)',
					$dbc->_db->quote($user_rid));
	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_assoc($r);
	$i = 1;
	$reasons = '';

	while ($a) {
		$reasons .= 'Razlog korisnika #'.$i.':'.$a['reason'].'<br/>';
		$a = $dbc->_db->fetch_assoc($r);
		$i ++;
	}

	$mail_body = 'Poštovani (bivši) korisniče,<br>
korisnici Inpulls.com-a su vas izbacili sa stranice koristeći uslugu "Stisni cezara". Ako želite i dalje posjećivati i koristiti Inpulls.com, morati ćete se registrirati kao novi korisnik.<br>
Slijedi popis razloga za izbacivanje koje su naveli korisnici:<br>' . $reasons;

	include_once DOC_ROOT . '/orbicon/3rdParty/phpmailer/class.phpmailer.php';

	$mail = new PHPMailer();

	if($_SESSION['site_settings']['smtp_server'] != '') {
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->Host = $_SESSION['site_settings']['smtp_server']; // SMTP server
		$mail->Port = $_SESSION['site_settings']['smtp_port'];
	}

	$mail->CharSet = 'UTF-8';
	$mail->From = $_SESSION['site_settings']['main_site_email'];
	$mail->FromName = utf8_html_entities($_SESSION['site_settings']['main_site_title'], true);

	$email = trim($user_email);
	if(is_email($email)) {
		$mail->AddAddress($email);
	}

	$title = utf8_html_entities('Važna obavijest', true);

	$mail->Subject = $title;
	$mail->Body = $mail_body;
	$mail->WordWrap = 50;
	$mail->IsHTML(true);

	if(!$mail->Send()) {
		mail($email, $title, $mail_body, 'Content-Type: text/html; charset=UTF-8');
	}

	// send response header
	$mail = null;
}

/**
 * Change video for user
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 * @param string $video
 */
function edit_inpulls_video($pr_id, $video)
{
	global $dbc;
	$q = sprintf('	UPDATE 		'.TABLE_INPULLS_PROFILE.'
					SET			video=%s
					WHERE 		(pring_id=%s)',
					$dbc->_db->quote($video), $dbc->_db->quote($pr_id));
	$dbc->_db->query($q);
}

function add_profile_viewer($pr_id, $viewer_username)
{
	global $dbc;

	$viewers = get_profile_viewers($pr_id);
	$viewers = explode(',', $viewers);
	$viewers[] = $viewer_username;

	$viewers = array_unique($viewers);
	$viewers = array_reverse($viewers);
	$viewers = array_slice($viewers, 0, 10);
	$viewers = array_remove_empty($viewers);
	$viewers = implode(',', $viewers);

	$q = sprintf('	UPDATE 		'.TABLE_INPULLS_PROFILE.'
					SET			last_profile_viewers=%s
					WHERE 		(pring_id=%s)',
					$dbc->_db->quote($viewers), $dbc->_db->quote($pr_id));
	$dbc->_db->query($q);
}

/**
 * Enter description here...
 *
 * @param int $pr_id
 * @return string
 */
function get_profile_viewers($pr_id)
{
	global $dbc;
	$q = sprintf('	SELECT 		last_profile_viewers
					FROM		'.TABLE_INPULLS_PROFILE.'
					WHERE 		(pring_id=%s)',
					$dbc->_db->quote($pr_id));
	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_assoc($r);
	return $a['last_profile_viewers'];
}

/**
 * Enter description here...
 *
 * @param unknown_type $pr_id
 * @return unknown
 */
function render_last_profile_viewers($pr_id)
{
	global $orbicon_x;

	$viewers = get_profile_viewers($pr_id);
	$viewers = explode(',', $viewers);
	$render = array();

	foreach ($viewers as $viewer) {
		list($username, $name) = explode('*!**!*', $viewer);
		$name = trim($name);
		$name = ($name == '') ? $username : $name;


		$render[] = '<a href="'.ORBX_SITE_URL.'/?user='.$username.'&amp;'.$orbicon_x->ptr.'=mod.inpulls.profile">'.$name.'</a>';
	}

	$render = implode(', ', $render);
	return $render;
}

/**
 * Enter description here...
 *
 * @param unknown_type $last_active_id
 * @return unknown
 */
function inpulls_reg_scan_success($last_active_id)
{
	global $dbc;
	$q_chk = '	SELECT 	id
				FROM 	' . TABLE_INPULLS_PROFILE . '
				WHERE 	(pring_id = ' . $dbc->_db->quote($last_active_id) . ')
				LIMIT	1';

	$r_chk = $dbc->_db->query($q_chk);
	$a_chk = $dbc->_db->fetch_assoc($r_chk);

	return $a_chk['id'];
}

function inpulls_reg_iprofile_insert($pr_id)
{
	global $dbc;

	// inpulls fields
	$q = sprintf('	INSERT INTO 	'.TABLE_INPULLS_PROFILE.'
									(pring_id)
					VALUES			(%s)',
						$dbc->_db->quote($pr_id));

	$dbc->_db->query($q);
	return $dbc->_db->insert_id();
}

function add_new_comment($text, $author_rid, $user_rid)
{
	global $dbc;

    $text = trim(strip_tags($text, '<p><b><span><strong><i><u><em><br><img><a><u><h1><h2><h3><h4><h5><h6><abbr><acronym><address><blockquote><hr><big><font><center><ul><ol><li><small><q><strike><sub><sup><table><tr><td><th><thead>'));

    // passthru safehtml
    if(!defined('XML_HTMLSAX3')) {
		define('XML_HTMLSAX3', DOC_ROOT . '/orbicon/3rdParty/safehtml/classes/');
	}
	require_once XML_HTMLSAX3 . 'safehtml.php';
	$safehtml = new SafeHTML();
	$text = $safehtml->parse($text);
	$safehtml = null;

	require_once DOC_ROOT . '/orbicon/magister/class.magister.php';
	$magister = new Magister();

	$text = utf8_html_entities(trim(stripslashes($text)));
	$text = $magister->close_tags($text);
	$text = $magister->hyperlinks_add($text);
	$magister = null;

	$q = sprintf('	INSERT INTO 	'.TABLE_INPULLS_COMMENTS.'
									(content, time,
									author_rid, user_rid)
					VALUES 			(%s, UNIX_TIMESTAMP(),
									%s, %s)',
						$dbc->_db->quote($text),
						$dbc->_db->quote($author_rid), $dbc->_db->quote($user_rid));

	$dbc->_db->query($q);
	return $dbc->_db->insert_id();
}

function print_comments($user_rid, $pr)
{
	global $dbc, $orbicon_x;

    $q = sprintf('	SELECT 		*
					FROM 		'.TABLE_INPULLS_COMMENTS.'
					WHERE 		(user_rid=%s)
					ORDER BY 	time DESC
					LIMIT 	 	20', $dbc->_db->quote($user_rid));

    $r = $dbc->_db->query($q);
    $affected = $dbc->_db->affected_rows();

    if($affected > 0) {
        $forum = '<table id="forum_messages" style="width:100%">';
		$a = $dbc->_db->fetch_assoc($r);

        while($a) {

            $text = stripslashes($a['content']);
       		$user = $pr->get_profile($pr->get_prid_from_rid($a['author_rid']));
       		$username = $pr->get_username($a['author_rid']);
       		$username = $username['username'];

       		$picture = $user['picture'];

       		if(is_file(DOC_ROOT . '/site/venus/thumbs/t-' . $picture)) {
				$picture = ORBX_SITE_URL.'/site/venus/thumbs/t-' . $picture;
			}
			elseif(is_file(DOC_ROOT . '/site/venus/' . $picture)) {
				$picture = ORBX_SITE_URL.'/site/venus/' . $picture;
			}
			else {
				$picture = ORBX_SITE_URL.'/orbicon/modules/peoplering/gfx/unknownUser.gif';
			}

       		$display_username = (empty($user['contact_name'])) ? $username : $user['contact_name'].' '.$user['contact_surname'];

        	$display_name = '<a href="'.url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=user&amp;user='.$username, ORBX_SITE_URL . '/~' . $username).'">'.$display_username.'<br /><img class="forum_avatar" src="' . $picture . '" alt="'.$username.'" title="'.$username.'" /></a>';

        	$del_link = ($_SESSION['user.r']['id'] == $user_rid) ? ' <a href="javascript:void(null)" onclick="javascript:delete_comment('.intval($a['id']).','. $user_rid.')">Zbriši komentar</a>' : '';

            $forum .= '
            	<tr id="row_1_'.$a['id'].'" class="forum_user">
                	<td class="forum_name">'.$display_name.'</td>
	            	<td class="forum_txt">'.$text . $del_link . '</td>
	            </tr>
	            <tr id="row_2_'.$a['id'].'" class="forum_date">
                	<td colspan="2">'.date($_SESSION['site_settings']['date_format'] . ' H:i', $a['time']).'</td>
                </tr>
                <tr id="row_3_'.$a['id'].'" class="forum_msg_separator"><td colspan="2"><hr /></td></tr>';
			$a = $dbc->_db->fetch_assoc($r);
        }

        $forum .= '</table>';
    }
	else {
       return false;
    }

	return $forum;
}

function delete_comment($id, $user_rid)
{
	global $dbc;

	$q = sprintf('	DELETE
					FROM 		'.TABLE_INPULLS_COMMENTS.'
					WHERE 		(id=%s) AND
								(user_rid=%s)
					LIMIT 	 	1', $dbc->_db->quote($id), $dbc->_db->quote($user_rid));

    $dbc->_db->query($q);
}

?>