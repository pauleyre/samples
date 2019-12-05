<?php

define('TABLE_INPULLS_HOROSCOPE', 'orbx_mod_inpulls_horoscope');

/**
 * Add favorite horoscope
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 */
function add_favorite_horoscope($id)
{
	$favs = $_COOKIE['favoritehoro'];
	$favs = explode(',', $favs);
	$favs[] = $id;
	$favs = array_remove_empty($favs);
	$favs = array_unique($favs);
	$favs = implode(',', $favs);

	// remember for 999 days
	setcookie('favoritehoro', $favs, (time() + 86313600), '/');
	$_SESSION['inpulls_favoritehoro'] = $favs;
}

/**
 * Remove favorite horoscope
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 */
function remove_favorite_horoscope($id)
{
	$favs = $_COOKIE['favoritehoro'];
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
	setcookie('favoritehoro', $favs, (time() + 86313600), '/');
	$_SESSION['inpulls_favoritehoro'] = $favs;
}

/**
 * Print favorite horoscopes
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @return string
 */
function print_favorite_horoscopes()
{
	global $dbc, $orbicon_x, $inpulls_im_here_for;

	$favs = ($_SESSION['inpulls_favoritehoro']) ? $_SESSION['inpulls_favoritehoro'] : $_COOKIE['favoritehoro'];
	$favs = explode(',', $favs);
	$favs = array_remove_empty($favs);
	// newer profiles go first
	$favs = array_reverse($favs);

	$profiles = '';

	$total = 0;

	$profiles_header = '<h3 id="spremljeniHoroskopi">Horoskop</h3>';

	if(empty($favs[0]) || !is_array($favs)) {
		return $profiles;
	}

	foreach ($favs as $fav) {

		$q = '	SELECT 		*
				FROM 		' . TABLE_INPULLS_HOROSCOPE . '
				WHERE 		(id = ' . $fav . ')
				LIMIT 		1';

		$r = $dbc->_db->query($q);
		$user = $dbc->_db->fetch_object($r);

		if(empty($user)) {
			continue;
		}

		$total ++;

		$picture = ORBX_SITE_URL .'/orbicon/modules/inpulls/gfx/horoscope/' . $user->icon;

		$js_name = str_sanitize($user->title, STR_SANITIZE_JAVASCRIPT);
		$js_name = addslashes(str_replace('"', '', $js_name));

		$profiles .= '<div class="spremljeniHoroskop">
    <dl class="naslov">
      <dt><strong>'.$user->title.'</strong></dt>
      <dd><img src="'.$picture.'" alt="'.$picture.'" class="slika" />'.$user->text.'Izvor: www.astrolook.com</dd>
    </dl>';

		$profiles .= '
    <dl class="detalji">
      <dd class="obrisi"><a href="javascript:void(null)" onclick="javascript:fav_horoscope('.$user->id.', \'remove\');" title="Obriši horoskop">Obriši horoskop</a></dd>
    </dl>';

		$profiles .='
    <div class="clr"></div>
   </div>';
	}

	$profiles_header = '<h3 id="spremljeniHoroskopi">Horoskop</h3>';

	return $profiles_header . $profiles;
}

function print_all_horoscopes()
{
	global $dbc;
	$horo = '<div id="horoscope">';

	$q = '	SELECT 		*
			FROM 		' . TABLE_INPULLS_HOROSCOPE;

	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_object($r);
	$i = 0;

	while ($a) {
		$class = (($i % 2) == 0) ? ' horo_odd' : '';
		$horo .= '<div class="horo_sign'.$class.'"><h3>'.$a->title.'</h3><img class="horo_icon" src="'.ORBX_SITE_URL.'/orbicon/modules/inpulls/gfx/horoscope/'.$a->icon.'" alt="'.$a->title.'" title="'.$a->title.'" />'.$a->text.'<a href="javascript:void(null);" onclick="javascript:fav_horoscope('.$a->id.', \'add\');" title="Spremi horoskop"><img src="./site/gfx/images/icons/add.gif" /> Spremi horoskop</a></div><div style="clear:both"></div>';
		$a = $dbc->_db->fetch_object($r);
		$i ++;
	}

	$horo .= '</div>';
	return $horo;
}

function fetch_horoscope()
{
	// create agent. we'll probably need it
	include_once DOC_ROOT . '/orbicon/3rdParty/snoopy/Snoopy.class.php';
	$agent = new Snoopy;
	$agent->fetch('http://www.astrolook.com/dnevni.shtml');
	$contents = $agent->results;
	$contents = explode('<!-pocetak-->', $contents);

	foreach ($contents as $i => $entry) {
		if($i > 0) {
			list($txt, $junk) = explode('<!-kraj-->', $entry);
			update_horoscope_sign($i, $txt);
		}
	}
}


function get_horoscope_date_obsolete()
{
	global $dbc;
	$q = '	SELECT 		last_update
			FROM 		' . TABLE_INPULLS_HOROSCOPE.'
			LIMIT		1';

	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_object($r);

	return (date('dmY') != date('dmY', $a->last_update));
}

function get_horoscope_content_obsolete($compare_txt, $id)
{
	global $dbc;
	$q = '	SELECT 		text
			FROM 		' . TABLE_INPULLS_HOROSCOPE.'
			WHERE		id = '.$id.'
			LIMIT		1';

	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_object($r);

	return ($compare_txt != $a->text);
}

function update_horoscope_sign($id, $txt)
{
	global $dbc;
	if(get_horoscope_content_obsolete($txt, $id)) {
		$q = sprintf('	UPDATE 	' . TABLE_INPULLS_HOROSCOPE . '
						SET 	text=%s, last_update=UNIX_TIMESTAMP()
						WHERE 	(id = %s)',
		$dbc->_db->quote($txt), $dbc->_db->quote($id));

		$dbc->_db->query($q);
	}
}

function sync_horoscope()
{
	if(get_horoscope_date_obsolete()) {
		fetch_horoscope();
	}
}


?>