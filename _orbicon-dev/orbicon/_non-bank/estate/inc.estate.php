<?php
/**
 * Estate main include
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package SystemMOD
 * @subpackage Estate
 * @version 1.0
 * @link http://
 * @license http://
 * @since 2007-10-01
 * @todo Translation
 */

/**
 * SQL table name
 *
 */
define('TABLE_ESTATE', 'orbx_mod_estate');
define('TABLE_ESTATE_USER_FLAGS', 'orbx_mod_estate_user_flags');

/**
 * equipment: phone
 *
 */
define('ESTATE_EQUIP_PHONE', 		1);
define('ESTATE_EQUIP_BALCONY', 		2);
define('ESTATE_EQUIP_GARDEN', 		4);
define('ESTATE_EQUIP_GARAGE', 		8);
define('ESTATE_EQUIP_CLIMATE', 		16);
define('ESTATE_EQUIP_INVALIDS', 	32);
define('ESTATE_EQUIP_POOL', 		64);
define('ESTATE_EQUIP_TV', 			128);
define('ESTATE_EQUIP_SAT_TV', 		256);
define('ESTATE_EQUIP_INTERNET', 	512);
define('ESTATE_EQUIP_SPORT', 		1024);
define('ESTATE_EQUIP_CONFERENCE', 	2048);
define('ESTATE_EQUIP_LAND_PATH', 	4096);
define('ESTATE_EQUIP_LAND_POWER', 	8192);
define('ESTATE_EQUIP_LAND_WATER', 	16384);
define('ESTATE_EQUIP_LAND_GAS', 	32768);
define('ESTATE_EQUIP_LAND_SEWER', 	65536);
define('ESTATE_EQUIP_LAND_PAPERS', 	131072);

define('ESTATE_PUBLIC_TR_TRAM', 	1);
define('ESTATE_PUBLIC_TR_BUS', 		2);

define('ESTATE_USER_COMM_PRICE_HIGH', 	1);
define('ESTATE_USER_COMM_PRICE_LOW', 	2);
define('ESTATE_USER_COMM_EXPIRED', 		4);

/**
 * live ad
 *
 */
define('ESTATE_AD_LIVE', 			1);
/**
 * archive ad
 *
 */
define('ESTATE_AD_ARCHIVED', 		2);

/**
 * preview ad
 *
 */
define('ESTATE_AD_PREVIEW',			3);

/**
 * nonsponsored ad
 *
 */
define('ESTATE_AD_NONSPONSORED',	0);
/**
 * sponsored ad
 *
 */
define('ESTATE_AD_SPONSORED',		1);

global $estate_type;
$estate_type = array(1 => _L('e.apart'), 2 => _L('e.houses'), 3 => _L('e.lands'), 4 => _L('e.tourism'), 5 => _L('e.bsnplaces'), 6 => _L('e.products'), 7 => _L('e.specialprojects'));

global $sponsored_ad_id;
$sponsored_ad_id = array();

global $estate_filter_displayed;
$estate_filter_displayed = false;

global $big_tag_cloud_printed;
$big_tag_cloud_printed = false;

global $estate_type_p;
// permalinked version of above list
$estate_type_p = array_map('get_permalink', $estate_type);

global $estate_ad_type;
$estate_ad_type = array(1 => _L('e.offer'), 2 => _L('e.looking'), 3 => _L('e.rent'), 4 => _L('e.rent2'));

global $estate_house_type;
$estate_house_type = array(0 =>'&mdash; '._L('e.pickhouse').' &mdash;', 1 => _L('e.house1'), 2 => _L('e.house2'), 3 => _L('e.house3'), 4 => _L('e.house4'), 5 => _L('e.house5'));

global $estate_build_type;
$estate_build_type = array(0 => '&mdash; '._L('e.choose').' &mdash;', 1 => _L('e.newbuild'), 2 => _L('e.oldbuild'), 3 => _L('e.stillbuilding'));

global $estate_heating_type;
$estate_heating_type = array(0 =>'&mdash; '._L('e.pickheat').' &mdash;', 1 => _L('e.heat1'), 2 => _L('e.heat2'), 3 => _L('e.heat3'), 4 => _L('e.heat4'), 5 => _L('e.heat5'));

global $estate_business_type;
$estate_business_type = array(0 =>'&mdash; '._L('e.pickbsntype').' &mdash;', 1 => _L('e.bsn1'), 2 => _L('e.bsn2'), 3 => _L('e.bsn3'), 4 => _L('e.bsn4'), 5 => _L('e.bsn5'), 6 => _L('e.bsn6'), 7 => _L('e.bsn7'), 8 => _L('e.bsn8'), 9 => _L('e.bsn9'), 10 => _L('e.bsn10'), 11 => _L('e.bsn11'), 12 => _L('e.bsn12'), 13 => _L('e.bsn13'));

global $estate_land_type;
$estate_land_type = array(0 =>'&mdash; '._L('e.picklandtype').' &mdash;', 1 => _L('e.land1'), 2 => _L('e.land2'), 3 => _L('e.land3'));

global $estate_apartment_type;
$estate_apartment_type = array(0 => '&mdash; '._L('e.pickaparttype').' &mdash;', 1 => _L('e.apart1'), 2 => _L('e.apart2'), 3 => _L('e.apart3'), 4 => _L('e.apart4'), 5 => _L('e.apart5'), 6 => _L('e.apart6'), 7 => _L('e.apart7'), 8 => _L('e.apart8'));

global $estate_docs_type;
$estate_docs_type = array(0 => '&mdash; '._L('e.choose').' &mdash;', 1 => _L('e.exists'), 2 => _L('e.noexist'));

global $estate_currencies;
$estate_currencies = array(
	'&euro;',
	_L('e.loccurr'),
	'$'
);

global $estate_zagreb_parts;

if(DOMAIN_NO_WWW == 'foto-nekretnine.rs') {
$estate_zagreb_parts = array(
	'&mdash; Celi Beograd &mdash;',
	'Barajevo',
	'Čukarica',
	'Grocka',
	'Lazarevac',
	'Mladenovac',
	'Novi Beograd',
	'Obrenovac',
	'Palilula',
	'Rakovica',
	'Savski Venac',
	'Sopot',
	'Stari Grad',
	'Surčin',
	'Voždovac',
	'Vračar',
	'Zemun',
	'Zvezdara'
);
}
else {
$estate_zagreb_parts = array(
	'&mdash; Cijeli Zagreb &mdash;',
	'Centar grada',
	'Sjeverni dio i podsljemenska zona',
	'Istočni dio grada',
	'Zapadni dio grada',
	'Južni dio grada'
);
}

define('AGENCY_LEVEL_NONE', 0);
define('AGENCY_LEVEL_ALL', 1);
define('AGENCY_STATUS_15', 2);
define('AGENCY_STATUS_40', 4);

global $estate_agency_level;
$estate_agency_level = array(AGENCY_LEVEL_NONE =>'&mdash;', AGENCY_LEVEL_ALL => '&#8734;', AGENCY_STATUS_15 => '1&mdash;15', AGENCY_STATUS_40 => '1&mdash;40');

/**
 * Add new ad
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @return int
 */
function new_estate($preview = false)
{
	global $dbc;

	$equipment = ((int) $_POST['telefon'] | (int) $_POST['balkon'] | (int) $_POST['vrt'] | (int) $_POST['garaza'] | (int) $_POST['klima'] | (int) $_POST['invalidi'] | (int) $_POST['bazen'] | (int) $_POST['tv'] | (int) $_POST['satelitska'] | (int) $_POST['internet'] | (int) $_POST['tereni'] | (int) $_POST['dvorana'] | (int) $_POST['put'] | (int) $_POST['struja'] | (int) $_POST['voda'] | (int) $_POST['plin'] | (int) $_POST['kanalizacija'] | (int) $_POST['lokacijska']);

	$public_transport = ((int) $_POST['bus'] | (int) $_POST['tramvaj']);

	$tags = str_replace(';', ',', $_POST['tagovi']);
	$tags = explode(',', $tags);
	$tags = array_map('trim', $tags);
	$tags = array_unique($tags);
	$tags = array_remove_empty($tags);
	$tags = implode(',', $tags);
	$tags = strtolower($tags);

	$keywords = keyword_generator($tags . $_POST['tekst_oglasa']);

	$q = sprintf('
				INSERT INTO 	'.TABLE_ESTATE.'
								(title, description,
								msquare, category,
								ad_type, price,
								currency, county,
								street, street_no,
								post_num, logitude,
								latitude, house_type,
								build_type, year_built,
								msquare_backyard, room_num,
								floor_num, bath_num,
							    heating, equipment,
								docs, tags,
								public_transport, submited,
								permalink, menu,
								business_type, apartment_type,
								land_type, length,
								width, sea_distance,
								bed_num, flat_num,
								flat, town,
								neighborhood, keywords,
								zg, publish_print,
								sponsored_category, ip)
				VALUES			(%s, %s,
								%s, %s,
								%s, %s,
								%s, %s,
								%s, %s,
								%s, %s,
								%s, %s,
								%s, %s,
								%s, %s,
								%s, %s,
								%s, %s,
								%s, %s,
								%s, %s,
								%s, %s,
								%s, %s,
								%s, %s,
								%s, %s,
								%s, %s,
								%s, %s,
								%s, %s,
								%s, %s,
								%s, %s)',
	$dbc->_db->quote($_POST['naslov']), $dbc->_db->quote($_POST['tekst_oglasa']),
	$dbc->_db->quote($_POST['povrsina']), $dbc->_db->quote($_POST['kategorija']),
	$dbc->_db->quote($_POST['ponuda']), $dbc->_db->quote($_POST['cijena']),
	$dbc->_db->quote($_POST['valuta']), $dbc->_db->quote($_POST['regija']),
	$dbc->_db->quote($_POST['ulica']), $dbc->_db->quote($_POST['kucni_broj']),
	$dbc->_db->quote($_POST['']), $dbc->_db->quote($_POST['geo_sirina']),
	$dbc->_db->quote($_POST['geo_duzina']), $dbc->_db->quote($_POST['vrsta_kuce']),
	$dbc->_db->quote($_POST['novo_staro']), $dbc->_db->quote($_POST['godina']),
	$dbc->_db->quote($_POST['povrsina_okucnice']), $dbc->_db->quote($_POST['broj_soba']),
	$dbc->_db->quote($_POST['broj_etaza']), $dbc->_db->quote($_POST['broj_kuponica']),
	$dbc->_db->quote($_POST['grijanje']), $dbc->_db->quote($equipment),
	$dbc->_db->quote($_POST['dokumentacija']), $dbc->_db->quote($tags),
	$dbc->_db->quote($public_transport), $dbc->_db->quote(time()),
	$dbc->_db->quote(get_permalink($_POST['naslov'])), $dbc->_db->quote($_POST['ad_menu']),
	$dbc->_db->quote($_POST['vrsta_prostora']), $dbc->_db->quote($_POST['vrsta_stana']),
	$dbc->_db->quote($_POST['vrsta_zemljista']), $dbc->_db->quote($_POST['sirina']),
	$dbc->_db->quote($_POST['duzina']), $dbc->_db->quote($_POST['udaljenost']),
	$dbc->_db->quote($_POST['broj_kreveta']), $dbc->_db->quote($_POST['ukupno_katova']),
	$dbc->_db->quote($_POST['kat']), $dbc->_db->quote($_POST['grad']),
	$dbc->_db->quote($_POST['naselje']), $dbc->_db->quote($keywords),
	$dbc->_db->quote($_POST['zg']), $dbc->_db->quote($_POST['tiskano']),
	$dbc->_db->quote($_POST['sponsored_category']), $dbc->_db->quote(ORBX_CLIENT_IP));

	$dbc->_db->query($q);
	$new_id = $dbc->_db->insert_id();

	if($_SESSION['user.r']['id']) {
		edit_estate_user($new_id, $_SESSION['user.r']['id']);
	}

	upload_estate_files($new_id);

	if($preview) {
		$q = sprintf('	UPDATE 		'.TABLE_ESTATE.'
						SET			status='.ESTATE_AD_PREVIEW.'
						WHERE 		(id=%s)',
						$dbc->_db->quote($new_id));
		$dbc->_db->query($q);
	}

	return $new_id;
}

/**
 * Edit ad
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 */
function edit_estate($id)
{
	global $dbc;

	$equipment = ((int) $_POST['telefon'] | (int) $_POST['balkon'] | (int) $_POST['vrt'] | (int) $_POST['garaza'] | (int) $_POST['klima'] | (int) $_POST['invalidi'] | (int) $_POST['bazen'] | (int) $_POST['tv'] | (int) $_POST['satelitska'] | (int) $_POST['internet'] | (int) $_POST['tereni'] | (int) $_POST['dvorana'] | (int) $_POST['put'] | (int) $_POST['struja'] | (int) $_POST['voda'] | (int) $_POST['plin'] | (int) $_POST['kanalizacija'] | (int) $_POST['lokacijska']);

	$public_transport = ((int) $_POST['bus'] | (int) $_POST['tramvaj']);

	$tags = str_replace(';', ',', $_POST['tagovi']);
	$tags = explode(',', $tags);
	$tags = array_map('trim', $tags);
	$tags = array_unique($tags);
	$tags = array_remove_empty($tags);
	$tags = implode(',', $tags);
	$tags = strtolower($tags);

	$keywords = keyword_generator($tags . $_POST['tekst_oglasa']);

	$q = sprintf('	UPDATE 	' . TABLE_ESTATE . '
					SET 	title=%s, description=%s,
							msquare=%s, category=%s,
							ad_type=%s, price=%s,
							currency=%s, county=%s,
							street=%s, street_no=%s,
							post_num=%s, logitude=%s,
							latitude=%s, house_type=%s,
							build_type=%s, year_built=%s,
							msquare_backyard=%s, room_num=%s,
							floor_num=%s, bath_num=%s,
						    heating=%s, equipment=%s,
							docs=%s, tags=%s,
							public_transport=%s, permalink=%s,
							menu=%s, business_type=%s,
							apartment_type=%s, land_type=%s,
							length=%s, width=%s,
							sea_distance=%s, bed_num=%s,
							flat_num=%s, flat=%s,
							town=%s, neighborhood=%s,
							keywords=%s, zg=%s,
							publish_print=%s, sponsored_category=%s
					WHERE 	(id = %s)',
					$dbc->_db->quote($_POST['naslov']), $dbc->_db->quote($_POST['tekst_oglasa']),
	$dbc->_db->quote($_POST['povrsina']), $dbc->_db->quote($_POST['kategorija']),
	$dbc->_db->quote($_POST['ponuda']), $dbc->_db->quote($_POST['cijena']),
	$dbc->_db->quote($_POST['valuta']), $dbc->_db->quote($_POST['regija']),
	$dbc->_db->quote($_POST['ulica']), $dbc->_db->quote($_POST['kucni_broj']),
	$dbc->_db->quote($_POST['']), $dbc->_db->quote($_POST['geo_sirina']),
	$dbc->_db->quote($_POST['geo_duzina']), $dbc->_db->quote($_POST['vrsta_kuce']),
	$dbc->_db->quote($_POST['novo_staro']), $dbc->_db->quote($_POST['godina']),
	$dbc->_db->quote($_POST['povrsina_okucnice']), $dbc->_db->quote($_POST['broj_soba']),
	$dbc->_db->quote($_POST['broj_etaza']), $dbc->_db->quote($_POST['broj_kuponica']),
	$dbc->_db->quote($_POST['grijanje']), $dbc->_db->quote($equipment),
	$dbc->_db->quote($_POST['dokumentacija']), $dbc->_db->quote($tags),
	$dbc->_db->quote($public_transport), $dbc->_db->quote(get_permalink($_POST['naslov'])),
	$dbc->_db->quote($_POST['ad_menu']), $dbc->_db->quote($_POST['vrsta_prostora']),
	$dbc->_db->quote($_POST['vrsta_stana']), $dbc->_db->quote($_POST['vrsta_zemljista']),
	$dbc->_db->quote($_POST['sirina']), $dbc->_db->quote($_POST['duzina']),
	$dbc->_db->quote($_POST['udaljenost']), $dbc->_db->quote($_POST['broj_kreveta']),
	$dbc->_db->quote($_POST['ukupno_katova']), $dbc->_db->quote($_POST['kat']),
	$dbc->_db->quote($_POST['grad']), $dbc->_db->quote($_POST['naselje']),
	$dbc->_db->quote($keywords), $dbc->_db->quote($_POST['zg']),
	$dbc->_db->quote($_POST['tiskano']),$dbc->_db->quote($_POST['sponsored_category']),
	$dbc->_db->quote($id));

	$dbc->_db->query($q);

	upload_estate_files($id);
}

/**
 * Delete ad
 *
 * @param int $id
 * @return int
 */
function delete_estate_ad($id)
{
	global $dbc;

	$dbc->_db->query(sprintf('	DELETE FROM 	' . TABLE_ESTATE . '
								WHERE 			(id=%s)
								LIMIT 			1', $dbc->_db->quote($id)));
	return $dbc->_db->affected_rows();
}

/**
 * Upload all ad files
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 */
function upload_estate_files($id)
{
	if(isset($_FILES)) {
		$watermark = DOC_ROOT . '/site/gfx/watermark.png';
		require_once DOC_ROOT . '/orbicon/venus/class.venus.php';
		$venus = new Venus;

		if(isset($_FILES['slika_1']) && validate_upload($_FILES['slika_1']['tmp_name'], $_FILES['slika_1']['name'], $_FILES['slika_1']['size'], $_FILES['slika_1']['error'])) {
			$file = $venus->_insert_image_to_db($_FILES['slika_1']['name'], $_FILES['slika_1']['tmp_name']);

			_estate_img_size_fix($file ,$venus);

			edit_estate_pic_main($id, $file . ',' . $_POST['opis_1']);
			$venus->watermark_image(DOC_ROOT . '/site/venus/' . $file, $watermark);
		}

		if(!empty($_FILES['slika_2']['tmp_name']) || !empty($_FILES['slika_3']['tmp_name']) || !empty($_FILES['slika_4']['tmp_name']) || !empty($_FILES['slika_5']['tmp_name'])  || !empty($_FILES['slika_6']['tmp_name'])) {
			$file2 = $venus->_insert_image_to_db($_FILES['slika_2']['name'], $_FILES['slika_2']['tmp_name']);
			$file3 = $venus->_insert_image_to_db($_FILES['slika_3']['name'], $_FILES['slika_3']['tmp_name']);
			$file4 = $venus->_insert_image_to_db($_FILES['slika_4']['name'], $_FILES['slika_4']['tmp_name']);
			$file5 = $venus->_insert_image_to_db($_FILES['slika_5']['name'], $_FILES['slika_5']['tmp_name']);
			$file6 = $venus->_insert_image_to_db($_FILES['slika_6']['name'], $_FILES['slika_6']['tmp_name']);
			edit_estate_pics($id,
			$file2 . ',' . $_POST['opis_2'] . ';' .
			$file3 . ',' . $_POST['opis_3'] . ';' .
			$file4 . ',' . $_POST['opis_4'] . ';' .
			$file5 . ',' . $_POST['opis_5'] . ';' .
			$file6 . ',' . $_POST['opis_6']);

			if($file2) {
				_estate_img_size_fix($file2, $venus);
				$venus->watermark_image(DOC_ROOT . '/site/venus/' . $file2, $watermark);
			}
			if($file3) {
				_estate_img_size_fix($file3, $venus);
				$venus->watermark_image(DOC_ROOT . '/site/venus/' . $file3, $watermark);
			}
			if($file4) {
				_estate_img_size_fix($file4, $venus);
				$venus->watermark_image(DOC_ROOT . '/site/venus/' . $file4, $watermark);
			}
			if($file5) {
				_estate_img_size_fix($file5, $venus);
				$venus->watermark_image(DOC_ROOT . '/site/venus/' . $file5, $watermark);
			}
			if($file6) {
				_estate_img_size_fix($file6, $venus);
				$venus->watermark_image(DOC_ROOT . '/site/venus/' . $file6, $watermark);
			}
		}

		$venus = null;

		if(isset($_FILES['video'])) {
			require_once DOC_ROOT . '/orbicon/mercury/class.mercury.php';

			$ext = get_extension($_FILES['video']['name']);
			if(($ext != 'jpg') && ($ext != 'bmp') && ($ext != 'gif') && ($ext != 'png')) {
				$mercury = new Mercury;
				$video = $mercury->insert_file_into_db($_FILES['video']['name'], true, $_FILES['video']['tmp_name']);
				$mercury = null;
				edit_estate_video($id, $video);
			}
		}
	}
}

/**
 * Resize image if larger than 200Kb
 *
 * @param string $file
 * @param object $venus
 * @access private
 */
function _estate_img_size_fix($file, $venus)
{
	$file = DOC_ROOT . '/site/venus/' . $file;
	list($w, $h) = getimagesize($file);

	if($w > 640) {
		$venus->generate_thumbnail($file, $file, 640);
		update_sync_cache_list($file);
	}

	if(filesize($file) > 204800) {

		list($w, $h) = getimagesize($file);
		$w = intval($w * (75 / 100));
		$h = intval($h * (75 / 100));

		$venus->generate_thumbnail($file, $file, $w, $h, null, 75);
		update_sync_cache_list($file);
	}
}

/**
 * Change ad owner
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 * @param int $user_id
 */
function edit_estate_user($id, $user_id)
{
	if(!$user_id) {
		return false;
	}

	global $dbc;
	$q = sprintf('	UPDATE 		'.TABLE_ESTATE.'
					SET			user_id=%s
					WHERE 		(id=%s)',
					$dbc->_db->quote($user_id), $dbc->_db->quote($id));
	$dbc->_db->query($q);
	return true;
}

/**
 * Change video for ad
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 * @param string $video
 */
function edit_estate_video($id, $video)
{
	global $dbc;
	$q = sprintf('	UPDATE 		'.TABLE_ESTATE.'
					SET			video=%s
					WHERE 		(id=%s)',
					$dbc->_db->quote($video), $dbc->_db->quote($id));
	$dbc->_db->query($q);
}

/**
 * Change secondary pictures for ad
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 * @param string $pics
 */
function edit_estate_pics($id, $pics)
{
	global $dbc;
	$q = sprintf('	UPDATE 		'.TABLE_ESTATE.'
					SET			pics=%s
					WHERE 		(id=%s)',
					$dbc->_db->quote($pics), $dbc->_db->quote($id));
	$dbc->_db->query($q);
}

/**
 * Change main picture for ad
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 * @param string $pic_main
 */
function edit_estate_pic_main($id, $pic_main)
{
	global $dbc;
	$q = sprintf('	UPDATE 		'.TABLE_ESTATE.'
					SET			pic_main=%s
					WHERE 		(id=%s)',
					$dbc->_db->quote($pic_main), $dbc->_db->quote($id));
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
function get_estate_flag($bit, $flag)
{
	return (bool) ($bit & $flag);
}

/**
 * Change ad status
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 * @param int $state
 * @return int
 */
function set_estate_ad_status($id, $state)
{
	if(!is_int($id)) {
		trigger_error('set_estate_ad_status() expects parameter 1 to be integer, '.gettype($id).' given', E_USER_WARNING);
		return false;
	}

	global $dbc;

	$q = sprintf('	UPDATE 		'.TABLE_ESTATE.'
					SET			status=%s
					WHERE 		(id=%s)',
					$dbc->_db->quote($state), $dbc->_db->quote($id));
	$dbc->_db->query($q);

	return $dbc->_db->affected_rows();
}

/**
 * Add favorite add
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 */
function add_favorite_ad($id)
{
	$favs = $_COOKIE['favoritead'];
	$favs = explode(',', $favs);
	$favs[] = $id;
	$favs = array_remove_empty($favs);
	$favs = array_unique($favs);
	$favs = implode(',', $favs);

	// remember for 5 days
	setcookie('favoritead', $favs, (time() + 432000), '/');
	$_SESSION['estate_favoritead'] = $favs;
}

/**
 * Remove favorite ad
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 */
function remove_favorite_ad($id)
{
	$favs = $_COOKIE['favoritead'];
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

	// remember for 5 days
	setcookie('favoritead', $favs, (time() + 432000), '/');
	$_SESSION['estate_favoritead'] = $favs;
}

/**
 * Print ads
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @return string
 */
function print_favorite_ads()
{
	global $dbc, $orbicon_x, $estate_type_p, $estate_currencies;

	$favs = ($_SESSION['estate_favoritead']) ? $_SESSION['estate_favoritead'] : $_COOKIE['favoritead'];
	$favs = explode(',', $favs);
	$favs = array_remove_empty($favs);
	// newer ads go first
	$favs = array_reverse($favs);

	$ads = '';

	$total = 0;

	$ads_header = '<h3 id="spremljeniOglasi">'._L('e.favads').' <span class="small">('.$total.')</span></h3>';

	if(empty($favs[0]) || !is_array($favs)) {
		return $ads;
	}

	foreach ($favs as $fav) {

		$q = '	SELECT 		*
				FROM 		' . TABLE_ESTATE . '
				WHERE 		(id = ' . $fav . ') AND
							(status = '.ESTATE_AD_LIVE.')
				LIMIT 		1';

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_object($r);

		if(empty($a)) {
			continue;
		}

		$total ++;

		list($pic, $desc) = explode(',', $a->pic_main);

		if(is_file(DOC_ROOT . '/site/venus/' . $pic)) {
			if(is_file(DOC_ROOT . '/site/venus/thumbs/t-' . $pic)) {
				$pic = ORBX_SITE_URL .'/site/venus/thumbs/t-' . $pic;
			}
			else {
				$pic = ORBX_SITE_URL .'/site/venus/'.$pic;
			}
		}
		else {

			require_once DOC_ROOT . '/orbicon/class/diriterator/class.diriterator.php';
			$old_dir_path = DOC_ROOT . '/site/venus/old/' . $a->user_id . '/oglasi/' . $a->id;
			$dir = new DirIterator($old_dir_path, '*');
			$files = $dir->files();
			$dir = null;

			if (is_file(DOC_ROOT . '/site/venus/old/' . $a->user_id . '/oglasi/' . $a->id . '/' . $files[0])) {
				$pic = ORBX_SITE_URL . '/site/venus/old/' . $a->user_id . '/oglasi/' . $a->id . '/' . $files[0];
			}
			else {
				$pic = ORBX_SITE_URL .'/orbicon/modules/estate/gfx/no-img.gif';
			}
		}

		$url = url(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.e&amp;c=' . urlencode($estate_type_p[$a->category]) . '/' . urlencode($a->permalink) . '/' . $a->id, ORBX_SITE_URL . '/' . urlencode($estate_type_p[$a->category]) . '/' . urlencode($a->permalink) . '/' . $a->id);

		$ads .= '<div class="spremljeniOglas">
    <dl class="naslov">
      <dt><strong><a href="'.$url.'">'.$a->title.'</a></strong></dt>
      <dd><a href="'.$url.'"><img src="'.$pic.'" title="'.$desc.'" alt="'.$desc.'" class="slika" /></a></dd>
    </dl>';

		if(($a->category != 6) && ($a->category != 7)) {
			$price = (empty($a->price) || ($a->price == 0.0)) ? _L('e.onreq') : number_format($a->price, 2, ',', '.').' '.$estate_currencies[$a->currency];

			$ads .= '
    <dl class="detalji">
      <dd><strong>'._L('e.msquare').':</strong> '.$a->msquare.' m<sup>2</sup></dd>
      <dd><strong>'._L('e.price').':</strong> '.$price.'</dd>
      <dd class="obrisi"><a href="javascript:void(null)" onclick="javascript:fav_ad('.$a->id.', \'remove\');" title="'._L('e.delead').'">'._L('e.delead').'</a></dd>
    </dl>';
		}
		else {
			$ads .= '<dl class="detalji">
      <dd>'.truncate_text(strip_tags($a->description), 100, '...').'</dd>
      <dd class="obrisi"><a href="javascript:void(null)" onclick="javascript:fav_ad('.$a->id.', \'remove\');" title="'._L('e.delead').'">'._L('e.delead').'</a></dd>
    </dl>';
		}

		$ads .='
    <div class="clr"></div>
   </div>';
	}

	$ads_header = '<h3 id="spremljeniOglasi">'._L('e.favads').' <span class="small">('.$total.')</span></h3>';

	return $ads_header . $ads;
}

/**
 * Print ads
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param bool $sponsored
 * @return string
 */
function print_estate_ads($sponsored = false, $sponsored_limit = 5, $custom_limit = null, $ret_sql = false)
{
	global $dbc, $orbicon_x, $estate_type_p, $orbx_mod, $sponsored_ad_id, $estate_currencies, $estate_filter_displayed, $big_tag_cloud_printed;

	if (isset($_GET['tag']) && isset($_GET['all']) && !$big_tag_cloud_printed) {
		// don't print twice
		if($sponsored) {
			return null;
		}

		$big_tag_cloud_printed = true;
		$orbicon_x->set_page_title(_L('e.alltags'));
		$orbicon_x->add2breadcrumbs(_L('e.alltags'));
		return '<div id="all_tags">' . print_estate_tag_cloud(0) . '</div>';
	}

	if(isset($_GET['tag'])) {
		// don't print twice
		if($sponsored) {
			return null;
		}
	}

	if((isset($_GET['submit_bp']) || isset($_GET['submit_dp'])) && $sponsored) {
		return null;
	}

	// pagination
	require_once DOC_ROOT . '/orbicon/class/class.pagination.php';

	if(!isset($_GET['p'])) {
		$unset_below = true;
	}

	$_GET['pp'] = (isset($_GET['pp'])) ? intval($_GET['pp']) : 10;
	$_GET['p'] = (isset($_GET['p'])) ? intval($_GET['p']) : 1;

	// rss parent groups
	$main_parent_groups = array('stanovi', 'kuće', 'zemljišta', 'turistička-ponuda', 'poslovni-prostori');
	$prnt = $orbicon_x->get_has_parent($_GET[$orbicon_x->ptr]);

	// add rss
	if(isset($_GET[$orbicon_x->ptr])) {

		if(in_array($prnt, $main_parent_groups)) {
			$orbicon_x->add_feed_link(ORBX_SITE_URL.'/orbicon/modules/estate/grprss.php?c='.$prnt, DOMAIN_NAME . ' - ' . $prnt);
		}

		$orbicon_x->add_feed_link(ORBX_SITE_URL.'/orbicon/modules/estate/rss.php?c='.$_GET[$orbicon_x->ptr], DOMAIN_NAME . ' - ' . $_GET[$orbicon_x->ptr]);
	}

	$ads = '';
	$search_kw = '';
	$free_ad_lowerlimit = 0;//(time() /*- 3888000*/);

	$ads = '<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/controler/gzip.server.php?file=/orbicon/modules/estate/estate.js&amp;'.ORBX_BUILD.'"></script>';

	$pagination = new Pagination('p', 'pp');

	if($_REQUEST['agencija_chck']) {
		$_REQUEST['filter_by_user'] = $_REQUEST['agency_id'];
	}

	$menu_sql = (isset($_GET[$orbicon_x->ptr]) && ($_GET[$orbicon_x->ptr] != 'mod.peoplering') && ($_GET[$orbicon_x->ptr] != 'mod.e')) ? sprintf(' AND (menu=%s) ', $dbc->_db->quote($_GET[$orbicon_x->ptr])) : '';

	include_once DOC_ROOT.'/orbicon/modules/forms/class.form.php';
	$form = new Form;
	$counties = $form->get_pring_db_table('pring_counties', true, 'title', '', true);

	if($menu_sql) {

		// check if we're in main category
		switch ($_GET[$orbicon_x->ptr]) {
			case 'stanovi': $menu_sql = ' AND (category = 1) '; break;
			case 'kuće': $menu_sql = ' AND (category = 2) '; break;
			case 'zemljišta': $menu_sql = ' AND (category = 3) '; break;
			case 'turistička-ponuda': $menu_sql = ' AND (category = 4) '; break;
			case 'poslovni-prostori': $menu_sql = ' AND (category = 5) '; break;
			default: // do nothing
		}

		if($orbicon_x->ptr != 'hr') {
			require_once DOC_ROOT . '/orbicon/modules/estate/menu.trans.php';
			$orbicon_x->set_page_title(estate_title_trans($_GET[$orbicon_x->ptr]));
		}

		if(isset($_GET['naselje'])) {

			$q = sprintf('	SELECT 	id, town
							FROM	pring_towns
							WHERE	(county = %s) AND
									(country = %s) AND
									(lang = %s)',
									$dbc->_db->quote($_GET['regija']),
									$dbc->_db->quote('HR'),
									$dbc->_db->quote($orbicon_x->ptr));
			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);
			$towns = array();

			while ($a) {
				$towns[$a['id']] .= $a['town'];
				$a = $dbc->_db->fetch_assoc($r);
			}

		}

		if(isset($_GET['tag'])) {
			$r_tot = $dbc->_db->query('SELECT COUNT(id) AS total FROM ' . TABLE_ESTATE . ' WHERE (status = ' . ESTATE_AD_LIVE . ') AND (tags LIKE ' . $dbc->_db->quote('%' . $_GET['tag'] . '%') . ') AND (sponsored	= '.$dbc->_db->quote(ESTATE_AD_NONSPONSORED).') AND (submited >= '.$free_ad_lowerlimit.') ');
		}
		else {
			$r_tot = $dbc->_db->query('SELECT COUNT(id) AS total FROM ' . TABLE_ESTATE . ' WHERE (status = ' . ESTATE_AD_LIVE . ') '.$menu_sql.' AND (sponsored	= '.$dbc->_db->quote(ESTATE_AD_NONSPONSORED).') AND (submited >= '.$free_ad_lowerlimit.') ' );
		}


		$a_tot = $dbc->_db->fetch_assoc($r_tot);

		$form = null;

		$category_filter = '
		<div class="estate_menu_total">'._L('e.totalestatestype').': <span>'.number_format($a_tot['total'], 0, ',', ' ').'</span></div>
<div id="kat_filter_container">
    <h3>'._L('e.filter').'</h3>
	<form id="kat_filter_form" method="get" action="">

		<input name="'.$orbicon_x->ptr.'" value="'.$_GET[$orbicon_x->ptr].'" type="hidden" />
		<input name="submit_bp" value="1" type="hidden" />

        <label for="kat_regija">&nbsp;</label>
		<select id="kat_regija" name="regija" class="big" onchange="javascript: switch_towns(this.options[this.selectedIndex].value, \'HR\', \'kat_grad_container\', \'kat_naselje\', \'naselje\');">
			<option value="" class="first-child">'._L('e.pickreg').'</option>
			'.print_select_menu($counties, $_GET['regija'], true).'
		</select>

		<label for="kat_naselje">&nbsp;</label>
		<span id="kat_grad_container">
			<select id="kat_naselje" name="naselje" class="select big">
				<option value="">'._L('e.pickplace').'</option>
				'.print_select_menu($towns, $_GET['naselje'], true).'
			</select>
		</span>

		<input value="'._L('e.search').'" name="filter" id="kat_filter" type="submit" />
	</form>
</div>';
	}

	if(($_GET[$orbicon_x->ptr] != 'mod.peoplering')) {
		$menu_sql .= ' AND ((category != 6) OR (category != 7)) ';
	}

	$search_qs = str_replace(array(',', ':', '.', '-'), ' ', $_GET['q']);
	$search_qs = explode(' ', $search_qs);

	foreach ($search_qs as $search_q) {

		$skip = false;
		$search_q_lc = strtolower($search_q);

		if(($search_q_lc == 'jednosoban') || ($search_q_lc == 'jednosobni')) {
			$search_kw .= ' AND (room_num = \'1\') ';
			$skip = true;
		}
		elseif (($search_q_lc == 'dvosoban') || ($search_q_lc == 'dvosobni')) {
			$search_kw .= ' AND (room_num = \'2\') ';
			$skip = true;
		}
		elseif (($search_q_lc == 'trosoban') || ($search_q_lc == 'trosobni')) {
			$search_kw .= ' AND (room_num = \'3\') ';
			$skip = true;
		}
		elseif (($search_q_lc == 'četverosoban') || ($search_q_lc == 'četverosobni')) {
			$search_kw .= ' AND (room_num = \'4\') ';
			$skip = true;
		}
		elseif (($search_q_lc == 'Četverosoban') || ($search_q_lc == 'Četverosobni')) {
			$search_kw .= ' AND (room_num = \'4\') ';
			$skip = true;
		}
		elseif (($search_q_lc == 'cetverosoban') || ($search_q_lc == 'cetverosobni')) {
			$search_kw .= ' AND (room_num = \'4\') ';
			$skip = true;
		}
		elseif (($search_q_lc == 'petosoban') || ($search_q_lc == 'petosobni')) {
			$search_kw .= ' AND (room_num = \'5\') ';
			$skip = true;
		}

		if((strlen($search_q) > 2) && !$skip) {
			if(($search_q_lc != 'županija') && ($search_q_lc != 'Županija') && ($search_q_lc != 'regija')) {
				$search_q_2 = (substr($search_q_lc, -1) == 'u') ? substr($search_q, 0, -1) : $search_q;

				$search_q_org = $search_q;
				$search_q = ucfirst($search_q);

				if($search_q_lc == 'rijeci') {
					$search_q_2 = 'rijeka';
				}
				elseif ($search_q_lc == 'garešnici') {
					$search_q_2 = 'garešnica';
				}
				elseif ($search_q_lc == 'puli') {
					$search_q_2 = 'pula';
				}
				elseif ($search_q_lc == 'karlovcu') {
					$search_q_2 = 'karlovac';
				}
				elseif ($search_q_lc == 'opatiji') {
					$search_q_2 = 'opatija';
				}
				elseif ($search_q_lc == 'istri') {
					$search_q_2 = 'istra';
				}
				elseif($search_q == 'Andrijaševcima') {
					$search_q_2 = '﻿Andrijaševac';
				}
				elseif($search_q == 'Antunovcu') {
					$search_q_2 = 'Antunovac';
				}
				elseif($search_q == 'Babinoj Gredi') {
					$search_q_2 = 'Babina Greda';
				}
				elseif($search_q == 'Bakru') {
					$search_q_2 = 'Bakar';
				}
				elseif($search_q == 'Balama') {
					$search_q_2 = 'Bale';
				}
				elseif($search_q == 'Baškoj') {
					$search_q_2 = 'Baška';
				}
				elseif($search_q == 'Baškoj Vodi') {
					$search_q_2 = 'Baška Voda';
				}
				elseif($search_q == 'Bebrinama') {
					$search_q_2 = 'Bebrina';
				}
				elseif($search_q == 'Bedekovčini') {
					$search_q_2 = 'Bedekovčina';
				}
				elseif($search_q == 'Bedenici') {
					$search_q_2 = 'Bedenica';
				}
				elseif($search_q == 'Bednji') {
					$search_q_2 = 'Bednja';
				}
				elseif($search_q == 'Belom Manastiru') {
					$search_q_2 = 'Beli Manastir';
				}
				elseif($search_q == 'Belici') {
					$search_q_2 = 'Belica';
				}
				elseif($search_q == 'Belišću') {
					$search_q_2 = 'Belišće';
				}
				elseif($search_q == 'Benkovcu') {
					$search_q_2 = 'Benkovac';
				}
				elseif($search_q == 'Beretinecu') {
					$search_q_2 = 'Beretinac';
				}
				elseif($search_q == 'Bibinju') {
					$search_q_2 = 'Bibinje';
				}
				elseif($search_q == 'Biogradu na Moru') {
					$search_q_2 = 'Biograd na Moru';
				}
				elseif($search_q == 'Biskupiji') {
					$search_q_2 = 'Biskupija';
				}
				elseif($search_q == 'Bizovcu') {
					$search_q_2 = 'Bizovac';
				}
				elseif($search_q == 'Blatu') {
					$search_q_2 = 'Blato';
				}
				elseif($search_q == 'Bogdanovcima') {
					$search_q_2 = 'Bogdanovac';
				}
				elseif($search_q == 'Borovu') {
					$search_q_2 = 'Borovo';
				}
				elseif($search_q == 'Bosiljevu') {
					$search_q_2 = 'Bosiljevo';
				}
				elseif($search_q == 'Bošnjacima') {
					$search_q_2 = 'Bošnjaci';
				}
				elseif($search_q == 'Brckovljanima') {
					$search_q_2 = 'Brckovljani';
				}
				elseif($search_q == 'Brdovcu') {
					$search_q_2 = 'Brdovec';
				}
				elseif($search_q == 'Breli') {
					$search_q_2 = 'Brela';
				}
				elseif($search_q == 'Brestovcu') {
					$search_q_2 = 'Brestovac';
				}
				elseif($search_q == 'Breznici') {
					$search_q_2 = 'Breznica';
				}
				elseif($search_q == 'Brezovačkom Humu') {
					$search_q_2 = 'Brezovački Hum';
				}
				elseif($search_q == 'Brezovici') {
					$search_q_2 = 'Brezovica';
				}
				elseif($search_q == 'Brinju') {
					$search_q_2 = 'Brinje';
				}
				elseif($search_q == 'Brod Moravicama') {
					$search_q_2 = 'Brod Moravice';
				}
				elseif($search_q == 'Brodskom Stupniku') {
					$search_q_2 = 'Brodski Stupnik';
				}
				elseif($search_q == 'Brtonigli') {
					$search_q_2 = 'Brtonigla';
				}
				elseif($search_q == 'Budišćini') {
					$search_q_2 = 'Budišćina';
				}
				elseif($search_q == 'Bujama') {
					$search_q_2 = 'Buje';
				}
				elseif($search_q == 'Bukovlju') {
					$search_q_2 = 'Bukovlje';
				}
				elseif($search_q == 'Centru') {
					$search_q_2 = 'Centar';
				}
				elseif($search_q == 'Cerni') {
					$search_q_2 = 'Cerna';
				}
				elseif($search_q == 'Cerovlju') {
					$search_q_2 = 'Cerovlje';
				}
				elseif($search_q == 'Cestici') {
					$search_q_2 = 'Cestica';
				}
				elseif($search_q == 'Cisti Provi') {
					$search_q_2 = 'Cista Prova';
				}
				elseif($search_q == 'Civljanima') {
					$search_q_2 = 'Civljani';
				}
				elseif($search_q == 'Crikvenici') {
					$search_q_2 = 'Crikvenica';
				}
				elseif($search_q == 'Dardi') {
					$search_q_2 = 'Darda';
				}
				elseif($search_q == 'Delnicama') {
					$search_q_2 = 'Delnice';
				}
				elseif($search_q == 'Dežanovcu') {
					$search_q_2 = 'Dežanovec';
				}
				elseif($search_q == 'Dicmu') {
					$search_q_2 = 'Dicmo';
				}
				elseif($search_q == 'Donjoj Dubravi') {
					$search_q_2 = 'Donja Dubrava';
				}
				elseif($search_q == 'Donjoj Motičini') {
					$search_q_2 = 'Donja Motičina';
				}
				elseif($search_q == 'Donjoj Stubici') {
					$search_q_2 = 'Donja Stubica';
				}
				elseif($search_q == 'Donjoj Voći') {
					$search_q_2 = 'Donja Voća';
				}
				elseif($search_q == 'Donjim Andrijevcima') {
					$search_q_2 = 'Donji Andrijevci';
				}
				elseif($search_q == 'Donjem Gradu') {
					$search_q_2 = 'Donji Grad';
				}
				elseif($search_q == 'Donjem Kraljevcu') {
					$search_q_2 = 'Donjem kraljevcu';
				}
				elseif($search_q == 'Donjim Kukuruzarima') {
					$search_q_2 = 'Donji Kukuruzari';
				}
				elseif($search_q == 'Donjem Lapcu') {
					$search_q_2 = 'Donji Lapac';
				}
				elseif($search_q == 'Donjem Martijanecu') {
					$search_q_2 = 'Donji Martijanec';
				}
				elseif($search_q == 'Donjem Miholjcu') {
					$search_q_2 = 'Donji Miholjec';
				}
				elseif($search_q == 'Donjem Vidovecu') {
					$search_q_2 = 'Donji Vidovec';
				}
				elseif($search_q == 'Drenju') {
					$search_q_2 = 'Drenje';
				}
				elseif($search_q == 'Drenovcima') {
					$search_q_2 = 'Drenovci';
				}
				elseif($search_q == 'Drnju') {
					$search_q_2 = 'Drnje';
				}
				elseif($search_q == 'Dubravi') {
					$search_q_2 = 'Dubrava';
				}
				elseif($search_q == 'Dubravi Donjoj') {
					$search_q_2 = 'Dubrava Donja';
				}
				elseif($search_q == 'Dubravi Gornjoj') {
					$search_q_2 = 'Dubrava Gornja';
				}
				elseif($search_q == 'Dubravici') {
					$search_q_2 = 'Dubravica';
				}
				elseif($search_q == 'Durovačkom Primorju') {
					$search_q_2 = 'Dubrovačko Primorje';
				}
				elseif($search_q == 'Dugoj Resi') {
					$search_q_2 = 'Duga Resa';
				}
				elseif($search_q == 'Dugom Ratu') {
					$search_q_2 = 'Dugi Rat';
				}
				elseif($search_q == 'Dugom Selu') {
					$search_q_2 = 'Dugo Selo';
				}
				elseif($search_q == 'Dugopolju') {
					$search_q_2 = 'Dugopolje';
				}
				elseif($search_q == 'Ernestinovu') {
					$search_q_2 = 'Ernestinovo';
				}
				elseif($search_q == 'Farkaševcu') {
					$search_q_2 = 'Farkaševac';
				}
				elseif($search_q == 'Fažani') {
					$search_q_2 = 'Fažana';
				}
				elseif($search_q == 'Ferdinandovcu') {
					$search_q_2 = 'Ferdinandovac';
				}
				elseif($search_q == 'Feričancima') {
					$search_q_2 = 'Feričanci';
				}
				elseif($search_q == 'Fužinama') {
					$search_q_2 = 'Fužine';
				}
				elseif($search_q == 'Galovcu') {
					$search_q_2 = 'Galovac';
				}
				elseif($search_q == 'Garešnici') {
					$search_q_2 = 'Garešnica';
				}
				elseif($search_q == 'Garčinu') {
					$search_q_2 = 'Garčina';
				}
				elseif($search_q == 'Generalskom Stolu') {
					$search_q_2 = 'Generalski Stol';
				}
				elseif($search_q == 'Glini') {
					$search_q_2 = 'Glina';
				}
				elseif($search_q == 'Goli') {
					$search_q_2 = 'Gola';
				}
				elseif($search_q == 'Goričanima') {
					$search_q_2 = 'Goričani';
				}
				elseif($search_q == 'Gorjanima') {
					$search_q_2 = 'Gorjani';
				}
				elseif($search_q == 'Gornjoj Rijeci') {
					$search_q_2 = 'Gornja Rijeka';
				}
				elseif($search_q == 'Gornjoj Stubici') {
					$search_q_2 = 'Gornja Stubica';
				}
				elseif($search_q == 'Gornjoj Vrbi') {
					$search_q_2 = 'Gornja Vrba';
				}
				elseif($search_q == 'Gornjim Bogičevcima') {
					$search_q_2 = 'Gornji Bogičevci';
				}
				elseif($search_q == 'Gornjem Gradu') {
					$search_q_2 = 'Gornji grad';
				}
				elseif($search_q == 'Medveščaku') {
					$search_q_2 = 'Medvešćak';
				}
				elseif($search_q == 'Gornjem Kneginecu') {
					$search_q_2 = 'Gornji Kneginec';
				}
				elseif($search_q == 'Gornjem Mihaljevcu') {
					$search_q_2 = 'Gornji Mihaljevac';
				}
				elseif($search_q == 'Gradini') {
					$search_q_2 = 'Gradina';
				}
				elseif($search_q == 'Gradištu') {
					$search_q_2 = 'Gradište';
				}
				elseif($search_q == 'Gračištu') {
					$search_q_2 = 'Gračište';
				}
				elseif($search_q == 'Grubišnom Polju') {
					$search_q_2 = 'Grubišno Polje';
				}
				elseif($search_q == 'Gundinicima') {
					$search_q_2 = 'Gundinici';
				}
				elseif($search_q == 'Gunji') {
					$search_q_2 = 'Gunja';
				}
				elseif($search_q == 'Hercegovcima') {
					$search_q_2 = 'Hercegovci';
				}
				elseif($search_q == 'Hlebinama') {
					$search_q_2 = 'Hlebine';
				}
				elseif($search_q == 'Hrašćini') {
					$search_q_2 = 'Hrašćina';
				}
				elseif($search_q == 'Hrvacima') {
					$search_q_2 = 'Hrvaci';
				}
				elseif($search_q == 'Hrvatskoj Dubici') {
					$search_q_2 = 'Hrvatska Dubica';
				}
				elseif($search_q == 'Hrvatskoj Kostanjici') {
					$search_q_2 = 'Hrvatska Kostanjica';
				}
				elseif($search_q == 'Humu na Sutli') {
					$search_q_2 = 'Hum na Sutli';
				}
				elseif($search_q == 'Imotskom') {
					$search_q_2 = 'Imotsko';
				}
				elseif($search_q == 'Ivankovom') {
					$search_q_2 = 'Ivankovo';
				}
				elseif($search_q == 'Ivanskom') {
					$search_q_2 = 'Ivansko';
				}
				elseif($search_q == 'Jakovlju') {
					$search_q_2 = 'Jakovlje';
				}
				elseif($search_q == 'Janjini') {
					$search_q_2 = 'Janjina';
				}
				elseif($search_q == 'Jarmini') {
					$search_q_2 = 'Jarmina';
				}
				elseif($search_q == 'Jasenicama') {
					$search_q_2 = 'Jasenice';
				}
				elseif($search_q == 'Jasenovcu') {
					$search_q_2 = 'Jasenovac';
				}
				elseif($search_q == 'Jastrebarskom') {
					$search_q_2 = 'Jastrebarsko';
				}
				elseif($search_q == 'Jelenju') {
					$search_q_2 = 'Jelenje';
				}
				elseif($search_q == 'Jelsi') {
					$search_q_2 = 'Jelsa';
				}
				elseif($search_q == 'Jesenju') {
					$search_q_2 = 'Jesenje';
				}
				elseif($search_q == 'Kaliju') {
					$search_q_2 = 'Kali';
				}
				elseif($search_q == 'Kalinovcu') {
					$search_q_2 = 'Kalinovac';
				}
				elseif($search_q == 'Kalniku') {
					$search_q_2 = 'Kalinik';
				}
				elseif($search_q == 'Kapeli') {
					$search_q_2 = 'Kapela';
				}
				elseif($search_q == 'Karlovcu') {
					$search_q_2 = 'Karlovac';
				}
				elseif($search_q == 'Karojbi') {
					$search_q_2 = 'Karojba';
				}
				elseif($search_q == 'Kašteli') {
					$search_q_2 = 'Kaštela';
				}
				elseif($search_q == 'Kašteliru Labincu') {
					$search_q_2 = 'Kaštelir Labinac';
				}
				elseif($search_q == 'Kijevu') {
					$search_q_2 = 'Kijevo';
				}
				elseif($search_q == 'Kistanju') {
					$search_q_2 = 'Kistanje';
				}
				elseif($search_q == 'Klani') {
					$search_q_2 = 'Klana';
				}
				elseif($search_q == 'Klenovniku') {
					$search_q_2 = 'Klekovnik';
				}
				elseif($search_q == 'Klinča Selu') {
					$search_q_2 = 'Klinča Selo';
				}
				elseif($search_q == 'Kloštaru Ivaniču') {
					$search_q_2 = 'Kloštar Ivanič';
				}
				elseif($search_q == 'Kloštru Podravskom') {
					$search_q_2 = 'Kloštar Podravski';
				}
				elseif($search_q == 'Kneževim Vinogradima') {
					$search_q_2 = 'Kneževi Vinogradi';
				}
				elseif($search_q == 'Knežiji') {
					$search_q_2 = 'Knežija';
				}
				elseif($search_q == 'Komiži') {
					$search_q_2 = 'Komiža';
				}
				elseif($search_q == 'Konavlima') {
					$search_q_2 = 'Konavli';
				}
				elseif($search_q == 'Konjšćini') {
					$search_q_2 = 'Konjšćina';
				}
				elseif($search_q == 'Končanici') {
					$search_q_2 = 'Končanica';
				}
				elseif($search_q == 'Koprivnici') {
					$search_q_2 = 'Koprivnica';
				}
				elseif($search_q == 'Koprivničkom Bregu') {
					$search_q_2 = 'Koprivnički Breg';
				}
				elseif($search_q == 'Koprivničkom Ivanecu') {
					$search_q_2 = 'Koprivnički Ivanec';
				}
				elseif($search_q == 'Korčuli') {
					$search_q_2 = 'Korčula';
				}
				elseif($search_q == 'Kostreni') {
					$search_q_2 = 'Kostrena';
				}
				elseif($search_q == 'Kotoribi') {
					$search_q_2 = 'Kotoriba';
				}
				elseif($search_q == 'Koški') {
					$search_q_2 = 'Koška';
				}
				elseif($search_q == 'Kraljevcu na Sutli') {
					$search_q_2 = 'Kraljevec na Sutli';
				}
				elseif($search_q == 'Kraljevici') {
					$search_q_2 = 'Kraljevica';
				}
				elseif($search_q == 'Krapini') {
					$search_q_2 = 'Krapina';
				}
				elseif($search_q == 'Krapinskim Toplicama') {
					$search_q_2 = 'Krapinske Toplice';
				}
				elseif($search_q == 'Kravarskom') {
					$search_q_2 = 'Kravarsko';
				}
				elseif($search_q == 'Križevcima') {
					$search_q_2 = 'Križevci';
				}
				elseif($search_q == 'Kukljici') {
					$search_q_2 = 'Kukljica';
				}
				elseif($search_q == 'Kuli Norinskoj') {
					$search_q_2 = 'Kula Norinska';
				}
				elseif($search_q == 'Kumrovcu') {
					$search_q_2 = 'Kumrovec';
				}
				elseif($search_q == 'Kutini') {
					$search_q_2 = 'Kutina';
				}
				elseif($search_q == 'Kutjevu') {
					$search_q_2 = 'Kutjevo';
				}
				elseif($search_q == 'Laništu') {
					$search_q_2 = 'Lanište';
				}
				elseif($search_q == 'Lasinju') {
					$search_q_2 = 'Lasinje';
				}
				elseif($search_q == 'Lastovu') {
					$search_q_2 = 'Lastovo';
				}
				elseif($search_q == 'Lepoglavi') {
					$search_q_2 = 'Lepoglava';
				}
				elseif($search_q == '') {
					$search_q_2 = 'Levanjskoj Varoši,';
				}
				elseif($search_q == 'Lečevici') {
					$search_q_2 = 'Lečevica';
				}
				elseif($search_q == 'Lipovljanima') {
					$search_q_2 = 'Lipovljani';
				}
				elseif($search_q == 'Lišanima Ostrovečkim') {
					$search_q_2 = 'Lišani Ostrovečki';
				}
				elseif($search_q == 'Ljubešćici') {
					$search_q_2 = 'Ljubešica';
				}
				elseif($search_q == 'Lokvama') {
					$search_q_2 = 'Lokve';
				}
				elseif($search_q == 'Lokvičici') {
					$search_q_2 = 'Lovičica';
				}
				elseif($search_q == 'Lovincu') {
					$search_q_2 = 'Lovinac';
				}
				elseif($search_q == 'Luki') {
					$search_q_2 = 'Luka';
				}
				elseif($search_q == 'Lumbardi') {
					$search_q_2 = 'Lumbarda';
				}
				elseif($search_q == 'Lupoglavi') {
					$search_q_2 = 'Lupoglava';
				}
				elseif($search_q == 'Magadenovcu') {
					$search_q_2 = 'Magadenovac';
				}
				elseif($search_q == 'Makarskoj') {
					$search_q_2 = 'Makarska';
				}
				elseif($search_q == 'Maloj Subotici') {
					$search_q_2 = 'Mala Subotica';
				}
				elseif($search_q == 'Malešnici') {
					$search_q_2 = 'Malešnica';
				}
				elseif($search_q == 'Malom Bukovcu') {
					$search_q_2 = 'Mali Bukovac';
				}
				elseif($search_q == 'Malom Lošinju') {
					$search_q_2 = 'Mali Lošinj';
				}
				elseif($search_q == 'Malinskoj Dubašnici') {
					$search_q_2 = 'Malinska Dubašnica';
				}
				elseif($search_q == 'Mariji Bistrici') {
					$search_q_2 = 'Marija Bistrica';
				}
				elseif($search_q == 'Mariji Gorici') {
					$search_q_2 = 'Marija Gorica';
				}
				elseif($search_q == 'Marijancima') {
					$search_q_2 = 'Marijanci';
				}
				elseif($search_q == 'Marini') {
					$search_q_2 = 'Marina';
				}
				elseif($search_q == 'Markušici') {
					$search_q_2 = 'Markušica';
				}
				elseif($search_q == 'Martinskoj Vesi') {
					$search_q_2 = 'Martinska Ves';
				}
				elseif($search_q == 'Markuševcu') {
					$search_q_2 = 'Markuševac';
				}
				elseif($search_q == 'Marčani') {
					$search_q_2 = 'Marčana';
				}
				elseif($search_q == 'Matuljima') {
					$search_q_2 = 'Matulji';
				}
				elseif($search_q == 'Mačima') {
					$search_q_2 = 'Mače';
				}
				elseif($search_q == 'Mihovljanima') {
					$search_q_2 = 'Mihovljani';
				}
				elseif($search_q == 'Mikleušu') {
					$search_q_2 = 'Mikleš';
				}
				elseif($search_q == 'Milni') {
					$search_q_2 = 'Milna';
				}
				elseif($search_q == 'Molvema') {
					$search_q_2 = 'Molve';
				}
				elseif($search_q == 'Mošćeničkoj Dragi') {
					$search_q_2 = 'Mošćenička Draga';
				}
				elseif($search_q == 'Mrkoplju') {
					$search_q_2 = 'Mrkopalj';
				}
				elseif($search_q == 'Murskom Središću') {
					$search_q_2 = 'Mursko Središće';
				}
				elseif($search_q == 'Našicama') {
					$search_q_2 = 'Našice';
				}
				elseif($search_q == 'Nedelišću') {
					$search_q_2 = 'Nedelišće';
				}
				elseif($search_q == 'Negoslavcima') {
					$search_q_2 = 'Negoslavci';
				}
				elseif($search_q == 'Nerežišću') {
					$search_q_2 = 'Nerežišće';
				}
				elseif($search_q == 'Nijemcima') {
					$search_q_2 = 'Nijemci';
				}
				elseif($search_q == 'Novoj Bukovici') {
					$search_q_2 = 'Nova Bukovica';
				}
				elseif($search_q == 'Novoj Gradišci') {
					$search_q_2 = 'Nova Gradiška';
				}
				elseif($search_q == 'Novoj Kapeli') {
					$search_q_2 = 'Nova Kapela';
				}
				elseif($search_q == 'Novoj Rači') {
					$search_q_2 = 'Nova Rača';
				}
				elseif($search_q == 'Novalji') {
					$search_q_2 = 'Novalja';
				}
				elseif($search_q == 'Novom Golubovcu') {
					$search_q_2 = 'Novi Golubovac';
				}
				elseif($search_q == 'Novom Marofu') {
					$search_q_2 = 'Novi Marof';
				}
				elseif($search_q == 'Novom Vinodolskom') {
					$search_q_2 = 'Novi Vinodolski';
				}
				elseif($search_q == 'Novom Zagrebu') {
					$search_q_2 = 'Novi Zagreb';
				}
				elseif($search_q == 'Novigradu Podravskom') {
					$search_q_2 = 'Novigrad Podravski';
				}
				elseif($search_q == 'Novom Virju') {
					$search_q_2 = 'Novo Virje';
				}
				elseif($search_q == 'Novskoj') {
					$search_q_2 = 'Novska';
				}
				elseif($search_q == 'Nuštru') {
					$search_q_2 = 'Nuštar';
				}
				elseif($search_q == 'Obrovcu') {
					$search_q_2 = 'Obrovac';
				}
				elseif($search_q == 'Okučanima') {
					$search_q_2 = 'Okučani';
				}
				elseif($search_q == 'Omišlju') {
					$search_q_2 = 'Omišalj';
				}
				elseif($search_q == 'Opatiji') {
					$search_q_2 = 'Opatija';
				}
				elseif($search_q == 'Oprisavcima') {
					$search_q_2 = 'Oprisavci';
				}
				elseif($search_q == 'Oprtlju') {
					$search_q_2 = 'Oprtalj';
				}
				elseif($search_q == 'Orahovici') {
					$search_q_2 = 'Orahovica';
				}
				elseif($search_q == 'Orehovici') {
					$search_q_2 = 'Orehovica';
				}
				elseif($search_q == 'Oriavcu') {
					$search_q_2 = 'Oriavec';
				}
				elseif($search_q == 'Orlima') {
					$search_q_2 = 'Orle';
				}
				elseif($search_q == 'Oroslavlju') {
					$search_q_2 = 'Oroslavlje';
				}
				elseif($search_q == 'Otoku Vinkovačkom') {
					$search_q_2 = 'Otok Vinkovači';
				}
				elseif($search_q == 'Otočcu') {
					$search_q_2 = 'Otočac';
				}
				elseif($search_q == 'Ozlju') {
					$search_q_2 = 'Ozalj';
				}
				elseif($search_q == 'Pakoštanima') {
					$search_q_2 = 'Pakoštane';
				}
				elseif($search_q == 'Petlovcu') {
					$search_q_2 = 'Petlovec';
				}
				elseif($search_q == 'Petrijevcima') {
					$search_q_2 = 'Petrijevci';
				}
				elseif($search_q == 'Petrinji') {
					$search_q_2 = 'Petrinja';
				}
				elseif($search_q == 'Petrovskom') {
					$search_q_2 = 'Petrovsko';
				}
				elseif($search_q == 'Pešćenici') {
					$search_q_2 = 'Pešćenica Žitnjak';
				}
				elseif($search_q == 'Žitnjaku') {
					$search_q_2 = 'Pešćenica Žitnjak';
				}
				elseif($search_q == 'Pirovcu') {
					$search_q_2 = 'Pirovac';
				}
				elseif($search_q == 'Pisarovini') {
					$search_q_2 = 'Pisarovina';
				}
				elseif($search_q == 'Pitomači') {
					$search_q_2 = 'Pitomača';
				}
				elseif($search_q == 'Plaškom') {
					$search_q_2 = 'Plaško';
				}
				elseif($search_q == 'Pleternici') {
					$search_q_2 = 'Pleternica';
				}
				elseif($search_q == 'Plitvičkim Jezerima') {
					$search_q_2 = 'Plitvička Jezera';
				}
				elseif($search_q == 'Pločama') {
					$search_q_2 = 'Ploče';
				}
				elseif($search_q == 'Podbablju') {
					$search_q_2 = 'Podbablje';
				}
				elseif($search_q == 'Podcrkljavima') {
					$search_q_2 = 'Podcrkavlje';
				}
				elseif($search_q == 'Podgori') {
					$search_q_2 = 'Podgora';
				}
				elseif($search_q == 'Podgoračima') {
					$search_q_2 = 'Podgorači';
				}
				elseif($search_q == 'Podravskoj Moslavini') {
					$search_q_2 = 'Podravska Moslavina';
				}
				elseif($search_q == 'Podravskim Sesvetama') {
					$search_q_2 = 'Podravske Sesvete';
				}
				elseif($search_q == 'Podsljemenu') {
					$search_q_2 = 'Podsljeme';
				}
				elseif($search_q == 'Podstrani') {
					$search_q_2 = 'Podstrana';
				}
				elseif($search_q == 'Podsusedu') {
					$search_q_2 = 'Podsused Vrapče';
				}
				elseif($search_q == 'Vrapču') {
					$search_q_2 = 'Podsused Vrapče';
				}
				elseif($search_q == 'Pojezerju') {
					$search_q_2 = 'Pojezerje';
				}
				elseif($search_q == 'Pokupskom') {
					$search_q_2 = 'Pokupsko';
				}
				elseif($search_q == 'Polači') {
					$search_q_2 = 'Polača';
				}
				elseif($search_q == 'Popovcu') {
					$search_q_2 = 'Popovac';
				}
				elseif($search_q == 'Popovači') {
					$search_q_2 = 'Popovača';
				}
				elseif($search_q == 'Posedarju') {
					$search_q_2 = 'Posedarje';
				}
				elseif($search_q == 'Postiri') {
					$search_q_2 = 'Postira';
				}
				elseif($search_q == 'Povljima') {
					$search_q_2 = 'Povlje';
				}
				elseif($search_q == 'Požegi') {
					$search_q_2 = 'Požega';
				}
				elseif($search_q == 'Pregradi') {
					$search_q_2 = 'Pregrada';
				}
				elseif($search_q == 'Preki') {
					$search_q_2 = 'Preka';
				}
				elseif($search_q == 'Prelogu') {
					$search_q_2 = 'Prellog';
				}
				elseif($search_q == 'Presaki') {
					$search_q_2 = 'Presaka';
				}
				elseif($search_q == 'Prečkom') {
					$search_q_2 = 'Prečko';
				}
				elseif($search_q == 'Primorskom Dolcu') {
					$search_q_2 = 'Primorski Dolac';
				}
				elseif($search_q == 'Privlaci') {
					$search_q_2 = 'Privlaka';
				}
				elseif($search_q == 'Proložcu') {
					$search_q_2 = 'Proložac';
				}
				elseif($search_q == 'Promini') {
					$search_q_2 = 'Promina';
				}
				elseif($search_q == 'Puli') {
					$search_q_2 = 'Pula';
				}
				elseif($search_q == 'Puntu') {
					$search_q_2 = 'Punat';
				}
				elseif($search_q == 'Punitovci') {
					$search_q_2 = 'Punitovica';
				}
				elseif($search_q == 'Pušći') {
					$search_q_2 = 'Pušća';
				}
				elseif($search_q == 'Rakovcu') {
					$search_q_2 = 'Rakovac';
				}
				elseif($search_q == 'Rakovici') {
					$search_q_2 = 'Rakovica';
				}
				elseif($search_q == 'Rasinju') {
					$search_q_2 = 'Rasinje';
				}
				elseif($search_q == 'Ravnoj Gori') {
					$search_q_2 = 'Ravna Gora';
				}
				elseif($search_q == 'Raši') {
					$search_q_2 = 'Raša';
				}
				elseif($search_q == 'Ražancu') {
					$search_q_2 = 'Ražanac';
				}
				elseif($search_q == 'Rešetarima') {
					$search_q_2 = 'Rešetari';
				}
				elseif($search_q == 'Rijeci') {
					$search_q_2 = 'Rijeka';
				}
				elseif($search_q == 'Rogoznici') {
					$search_q_2 = 'Rogoznica';
				}
				elseif($search_q == 'Rovišću') {
					$search_q_2 = 'Rovišće';
				}
				elseif($search_q == 'Rugavici') {
					$search_q_2 = 'Rugovica';
				}
				elseif($search_q == 'Runovićima') {
					$search_q_2 = 'Runovići';
				}
				elseif($search_q == 'Saborskom') {
					$search_q_2 = 'Saborsko';
				}
				elseif($search_q == 'Saliju') {
					$search_q_2 = 'Sali';
				}
				elseif($search_q == 'Satnici Đakovačkoj') {
					$search_q_2 = 'Satnica Đakovačka';
				}
				elseif($search_q == 'Selcu') {
					$search_q_2 = 'Selce';
				}
				elseif($search_q == 'Selnici') {
					$search_q_2 = 'Selnica';
				}
				elseif($search_q == 'Semeljcima') {
					$search_q_2 = 'Semeljci';
				}
				elseif($search_q == 'Sesvetama') {
					$search_q_2 = 'Sesvete';
				}
				elseif($search_q == 'Sikirevcima') {
					$search_q_2 = 'Sikirevci';
				}
				elseif($search_q == 'Sisku') {
					$search_q_2 = 'Sisak';
				}
				elseif($search_q == 'Slatini') {
					$search_q_2 = 'Slatina';
				}
				elseif($search_q == 'Slavonskom Brodu') {
					$search_q_2 = 'Slavonski Brod';
				}
				elseif($search_q == 'Slavonskom Šamcu') {
					$search_q_2 = 'Slavonski Šamac';
				}
				elseif($search_q == 'Slivnom') {
					$search_q_2 = 'Slivno';
				}
				elseif($search_q == 'Smokvici') {
					$search_q_2 = 'Smokvica';
				}
				elseif($search_q == 'Sokolavcu') {
					$search_q_2 = 'Sokolavac';
				}
				elseif($search_q == 'Sopju') {
					$search_q_2 = 'Sopje';
				}
				elseif($search_q == 'Srednjacima') {
					$search_q_2 = 'Srednjaci';
				}
				elseif($search_q == 'Stankovcima') {
					$search_q_2 = 'Stankovci';
				}
				elseif($search_q == 'Staroj Gradišci') {
					$search_q_2 = 'Stara Gradiška';
				}
				elseif($search_q == 'Starom Gradu') {
					$search_q_2 = 'Stari Grad';
				}
				elseif($search_q == 'Starim Jankovci') {
					$search_q_2 = 'Stari Jankovci';
				}
				elseif($search_q == 'Starim Mirkovcima') {
					$search_q_2 = 'Stari Mirkovci';
				}
				elseif($search_q == 'Starom Petrovom Selu') {
					$search_q_2 = 'Staro Petrovo Selo';
				}
				elseif($search_q == 'Stenjevcu') {
					$search_q_2 = 'Stenjevac';
				}
				elseif($search_q == 'Strizivojni') {
					$search_q_2 = 'Strizivojna';
				}
				elseif($search_q == 'Stubičkim Toplicama') {
					$search_q_2 = 'Stubičke Toplice';
				}
				elseif($search_q == 'Suhopolju') {
					$search_q_2 = 'Suhopolje';
				}
				elseif($search_q == 'Supetru') {
					$search_q_2 = 'Supetar';
				}
				elseif($search_q == 'Svetoj Mariji') {
					$search_q_2 = 'Sveta Marija';
				}
				elseif($search_q == 'Svetoj Nedjelji') {
					$search_q_2 = 'Sveta Nedjelja';
				}
				elseif($search_q == 'Svetom Filipu i Jakovu') {
					$search_q_2 = 'Sveti Filip i Jakov';
				}
				elseif($search_q == 'Svetom Iliji') {
					$search_q_2 = 'Sveti Ilija';
				}
				elseif($search_q == 'Svetom Ivanu Zelini') {
					$search_q_2 = 'Sveti Ivan Zelina';
				}
				elseif($search_q == 'Svetom Ivanu Žabnom') {
					$search_q_2 = 'Sveti Ivan Žabno';
				}
				elseif($search_q == 'Svetom Juraju na Bregu') {
					$search_q_2 = 'Sveti Juraj na Bregu';
				}
				elseif($search_q == 'Svetom Križu Začrtskom') {
					$search_q_2 = 'Sveti Križ Začrtje';
				}
				elseif($search_q == 'Svetom Lovreču') {
					$search_q_2 = 'Sveti Lovreč';
				}
				elseif($search_q == 'Svetom Martinu na Muri') {
					$search_q_2 = 'Sveti Martin na Muri';
				}
				elseif($search_q == 'Svetom Petru Orehovačkom') {
					$search_q_2 = 'Sveti Petar Orehovački';
				}
				elseif($search_q == 'Svetom Petru u Šumi') {
					$search_q_2 = 'Sveti Petar u Šumi';
				}
				elseif($search_q == 'Svetom Đurđu') {
					$search_q_2 = 'Sveti Đurđ';
				}
				elseif($search_q == 'Svetomvinčenatu') {
					$search_q_2 = 'Svetvinčenat';
				}
				elseif($search_q == 'Tisnom') {
					$search_q_2 = 'Tisno';
				}
				elseif($search_q == 'Tompojevcima') {
					$search_q_2 = 'Tompojevci';
				}
				elseif($search_q == 'Topuskom') {
					$search_q_2 = 'Topusko';
				}
				elseif($search_q == 'Tordinicima') {
					$search_q_2 = 'Tordinici';
				}
				elseif($search_q == 'Trešnjevci') {
					$search_q_2 = 'Trešnjevka-jug';
				}
				elseif($search_q == 'Trešnjevci') {
					$search_q_2 = 'Trešnjevka-sjever';
				}
				elseif($search_q == 'Trnavi') {
					$search_q_2 = 'Trnava';
				}
				elseif($search_q == 'Trnju') {
					$search_q_2 = 'Trnje';
				}
				elseif($search_q == 'Trnovcu Bartolovečkom') {
					$search_q_2 = 'Trnovec Bartolovečkim';
				}
				elseif($search_q == 'Trpnju') {
					$search_q_2 = 'Trpanj';
				}
				elseif($search_q == 'Tučepima') {
					$search_q_2 = 'Tučepi';
				}
				elseif($search_q == 'Udbini') {
					$search_q_2 = 'Udbina';
				}
				elseif($search_q == 'Valpovu') {
					$search_q_2 = 'Valpovo';
				}
				elseif($search_q == 'Varaždinskim Toplicama') {
					$search_q_2 = 'Varaždinske Toplice';
				}
				elseif($search_q == 'Veloj Luci') {
					$search_q_2 = 'VelaLuka';
				}
				elseif($search_q == 'Velikoj') {
					$search_q_2 = 'Velika';
				}
				elseif($search_q == 'Velikoj Gorici') {
					$search_q_2 = 'Velika Gorica';
				}
				elseif($search_q == 'Velikoj Kopanici') {
					$search_q_2 = 'Velika Kopanica';
				}
				elseif($search_q == 'Velikoj Ludini') {
					$search_q_2 = 'Velika Ludina';
				}
				elseif($search_q == 'Velikoj Pisanici') {
					$search_q_2 = 'Veika Pisanica';
				}
				elseif($search_q == 'Velikoj Trnovitici') {
					$search_q_2 = 'Velika Trnovitica';
				}
				elseif($search_q == 'Velikom Bukovcu') {
					$search_q_2 = 'Veliki Bukovac';
				}
				elseif($search_q == 'Velikom Grđevcu') {
					$search_q_2 = 'Veliki Grđevac';
				}
				elseif($search_q == 'Velikom Trgovištu') {
					$search_q_2 = 'Veliko Trgovišće';
				}
				elseif($search_q == 'Velikom Trojstvu') {
					$search_q_2 = 'Veliko Trojstvo';
				}
				elseif($search_q == 'Vidovcu') {
					$search_q_2 = 'Vidovec';
				}
				elseif($search_q == 'Vinici') {
					$search_q_2 = 'Vinica';
				}
				elseif($search_q == 'Vinkovcima') {
					$search_q_2 = 'Vinkovci';
				}
				elseif($search_q == 'Vinodolskoj Općini') {
					$search_q_2 = 'Vinodolska Općina';
				}
				elseif($search_q == 'Virovitici') {
					$search_q_2 = 'Virovitica';
				}
				elseif($search_q == 'Visokom') {
					$search_q_2 = 'Visoko';
				}
				elseif($search_q == 'Viškovcima') {
					$search_q_2 = 'Viškovci';
				}
				elseif($search_q == 'Viškovu') {
					$search_q_2 = 'Viškovo';
				}
				elseif($search_q == 'Vižinadi') {
					$search_q_2 = 'Vižinada';
				}
				elseif($search_q == 'Vladislavcima') {
					$search_q_2 = 'Vladislavci';
				}
				elseif($search_q == 'Vodicama') {
					$search_q_2 = 'Vodice';
				}
				elseif($search_q == 'Voltinom') {
					$search_q_2 = 'Voltino';
				}
				elseif($search_q == 'Vođinici') {
					$search_q_2 = 'Vođinica';
				}
				elseif($search_q == 'Vratišinecu') {
					$search_q_2 = 'Vratišenec';
				}
				elseif($search_q == 'Vrbanima') {
					$search_q_2 = 'Vrbani';
				}
				elseif($search_q == 'Vrbanji') {
					$search_q_2 = 'Vrbanja';
				}
				elseif($search_q == 'Vrbju') {
					$search_q_2 = 'Vrbje';
				}
				elseif($search_q == 'Vrbniku') {
					$search_q_2 = 'Vrbik';
				}
				elseif($search_q == 'Vrbovcu') {
					$search_q_2 = 'Vrbovec';
				}
				elseif($search_q == 'Vrbovskom') {
					$search_q_2 = 'Vrbovsko';
				}
				elseif($search_q == 'Vrgorcu') {
					$search_q_2 = 'Vrgorac';
				}
				elseif($search_q == 'Vrhovini') {
					$search_q_2 = 'Vrhovina';
				}
				elseif($search_q == 'Vrlici') {
					$search_q_2 = 'Vrlika';
				}
				elseif($search_q == 'Vrpovlju') {
					$search_q_2 = 'Vrpovlje';
				}
				elseif($search_q == 'Vuki') {
					$search_q_2 = 'Vuka';
				}
				elseif($search_q == 'Zadru') {
					$search_q_2 = 'Zadar';
				}
				elseif($search_q == 'Zadvarju') {
					$search_q_2 = 'Zadvarje';
				}
				elseif($search_q == 'Zagorskom Selu') {
					$search_q_2 = 'Zagorsko Selo';
				}
				elseif($search_q == 'Zaprešću') {
					$search_q_2 = 'Zaprešić';
				}
				elseif($search_q == 'Zažablju') {
					$search_q_2 = 'Zažablje';
				}
				elseif($search_q == 'Zdencima') {
					$search_q_2 = 'Zdenci';
				}
				elseif($search_q == 'Zemuniku Donjem') {
					$search_q_2 = 'Zemunik Donji';
				}
				elseif($search_q == 'Zlatar Bistrici') {
					$search_q_2 = 'Zlatar Bistrica';
				}
				elseif($search_q == 'Zmijavcima') {
					$search_q_2 = 'Zmijavci';
				}
				elseif($search_q == 'Zrinskom Topolovacu') {
					$search_q_2 = 'Zrinski Topolovac';
				}
				elseif($search_q == 'Šandrovcu') {
					$search_q_2 = 'Šandrovac';
				}
				elseif($search_q == 'Šenkovcu') {
					$search_q_2 = 'Šenkovec';
				}
				elseif($search_q == 'Šestanovcu') {
					$search_q_2 = 'Šestanovec';
				}
				elseif($search_q == 'Škabrnji') {
					$search_q_2 = 'Škabrnja';
				}
				elseif($search_q == 'Šodolovcima') {
					$search_q_2 = 'Šodolovci';
				}
				elseif($search_q == 'Šolti') {
					$search_q_2 = 'Šolta';
				}
				elseif($search_q == 'Španskom') {
					$search_q_2 = 'Špansko';
				}
				elseif($search_q == 'Špišiću Bukovici') {
					$search_q_2 = 'Špišić Bukovica';
				}
				elseif($search_q == 'Štefanji') {
					$search_q_2 = 'Štefanija';
				}
				elseif($search_q == 'Štrigovi') {
					$search_q_2 = 'Štrigova';
				}
				elseif($search_q == 'Žumberku') {
					$search_q_2 = 'Žumberak';
				}
				elseif($search_q == 'Župi Dubrovačkoj') {
					$search_q_2 = 'Župa Dubrovačka';
				}
				elseif($search_q == 'Županji') {
					$search_q_2 = 'Županja';
				}
				elseif($search_q == 'Čabru') {
					$search_q_2 = 'Čabar';
				}
				elseif($search_q == 'Čakovcu') {
					$search_q_2 = 'Čakovec';
				}
				elseif($search_q == 'Čavlima') {
					$search_q_2 = 'Čavli';
				}
				elseif($search_q == 'Čazmi') {
					$search_q_2 = 'Čazma';
				}
				elseif($search_q == 'Čačincima') {
					$search_q_2 = 'Čačinci';
				}
				elseif($search_q == 'Čađavici') {
					$search_q_2 = 'Čađavica';
				}
				elseif($search_q == 'Čemincu') {
					$search_q_2 = 'Čeminac';
				}
				elseif($search_q == 'Črnomercu') {
					$search_q_2 = 'Črnomerec';
				}
				elseif($search_q == 'Đakovu') {
					$search_q_2 = 'Đakovo';
				}
				elseif($search_q == 'Đulovcu') {
					$search_q_2 = 'Đulovec';
				}
				elseif($search_q == 'Đurmancu') {
					$search_q_2 = 'Đurmanac';
				}
				elseif($search_q == 'Đurđenovcu') {
					$search_q_2 = 'Đurđenovac';
				}
				elseif($search_q == 'Đurđevcu') {
					$search_q_2 = 'Đurđevac';
				}

				$search_q = $search_q_org;

				$search_q_2 = $dbc->_db->quote( $search_q_2 . '%');
				$search_q = $dbc->_db->quote( $search_q . '%');

				$hr_lett = array('č', 'ć', 'ž', 'š', 'đ', 'Č', 'Ć', 'Ž', 'Š', 'Đ');

				if(in_array($search_q_lc[0].$search_q_lc[1], $hr_lett)) {
					$search_q = '%' . substr($search_q_lc, 2);
					$search_q = $dbc->_db->quote( $search_q );
				}

				$search_perma = $dbc->_db->quote(get_permalink($search_q));

				if(isset($_GET['q'])) {
					$search_kw .= sprintf(' AND (
					(title LIKE %s) OR
					(neighborhood LIKE %s) OR
					(description LIKE %s) OR
					(town LIKE %s) OR
					(menu LIKE %s) OR
					(menu LIKE %s) OR
					(town LIKE %s) OR
					(county IN (SELECT id FROM pring_counties WHERE title LIKE %s)) OR
					(town IN (SELECT id FROM pring_towns WHERE town LIKE %s)) OR
					(town IN (SELECT id FROM pring_towns WHERE town LIKE %s))
					) ', $search_q, $search_q, $search_q, $search_q, $search_q, $search_perma, $search_q_2, $search_q, $search_q, $search_q_2);
				}
			}
		}
	}

	/**
	 * @todo
	 */

	if(isset($_GET['q'])) {
		// image only
		if($_REQUEST['sa_slikom']) {
			$search_kw .= ' AND (pic_main != \',\') AND (pic_main != \'\') ';
		}
		// video only
		if($_REQUEST['sa_videom']) {
			$search_kw .= ' AND ((video != \'\') OR (video_embed != \'\')) ';
		}
		// map only
		if($_REQUEST['sa_kartom']) {
			if(DOMAIN_NO_WWW == 'foto-nekretnine.rs') {
				$search_kw .= ' AND (
				((logitude != \'21.005859\') AND (latitude != \'44.016521\')) AND
				((logitude != \'15.95489500000000049340087571181356906890869140625\') AND (latitude != \'45.796255000000002155502443201839923858642578125\')) AND
				((logitude != \'\') AND (latitude != \'\'))
			) ';
			}
			else {
				$search_kw .= ' AND (
				((logitude != \'15.954895\') AND (latitude != \'45.796255\')) AND
				((logitude != \'15.95489500000000049340087571181356906890869140625\') AND (latitude != \'45.796255000000002155502443201839923858642578125\')) AND
				((logitude != \'\') AND (latitude != \'\'))
			) ';
			}

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
			'price_asc' => _L('e.pricelowest'),
			'price_desc' => _L('e.pricehighest'),
			'date_asc' => _L('e.dateoldest'),
			'date_desc' => _L('e.datenewest'),
			'title_asc' => _L('e.titleaz'),
			'title_desc' => _L('e.titleza'),
			'views_asc' => _L('e.viewslowest'),
			'views_desc' => _L('e.viewshighest')
		);

		$default_sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'date_desc';

		$r_tot = $dbc->_db->query('SELECT COUNT(id) AS total FROM ' . TABLE_ESTATE . ' WHERE status = ' . ESTATE_AD_LIVE);
		$a_tot = $dbc->_db->fetch_assoc($r_tot);

		$ads .= '<div id="results">
        <h3>'._L('e.latestads').' ('._L('e.total').' <span>'.number_format($a_tot['total'], 0, ',', ' ').'</span>)</h3>
        <label for="select_url"><span> '._L('e.orderby').'</span></label>

<select id="select_url" onchange=
"javascript: redirect(orbx_site_url + \'/?ln='.$orbicon_x->ptr.'&sort=\' + $(\'select_url\').options[$(\'select_url\').selectedIndex].value);">
'.print_select_menu($ar_sort, $default_sort, true).'
</select>
      </div>';
	}

	switch ($_GET['sort']) {
		case 'price_asc': $sort_by = 'price ASC'; break;
		case 'price_desc': $sort_by = 'price DESC'; break;
		case 'date_asc': $sort_by = 'submited ASC'; break;
		case 'date_desc': $sort_by = 'submited DESC'; break;
		case 'title_asc': $sort_by = 'title ASC'; break;
		case 'title_desc': $sort_by = 'title DESC'; break;
		case 'views_asc': $sort_by = 'views ASC'; break;
		case 'views_desc': $sort_by = 'views DESC'; break;
		default: $sort_by = 'submited DESC'; break;
	}

	if(isset($_REQUEST['submit_bp'])) {
		$q = '	SELECT 		*
				FROM 		'.TABLE_ESTATE.'
				WHERE		(status = '.$dbc->_db->quote(ESTATE_AD_LIVE).') AND
							(sponsored	= '.$dbc->_db->quote(ESTATE_AD_NONSPONSORED).') AND
							(submited >= '.$free_ad_lowerlimit.') ' .
				$menu_sql .
				$user_sql .
				build_estate_fastsearch_sql() . '
				LIMIT 		'.$dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']).', ' . $dbc->_db->quote($_GET['pp']);
	}
	elseif (isset($_REQUEST['submit_dp'])) {

		if ($_REQUEST['br_oglasa']) {
			$q = '	SELECT 		*
					FROM 		'.TABLE_ESTATE.'
					WHERE		(status = '.$dbc->_db->quote(ESTATE_AD_LIVE).') AND
								(submited >= '.$free_ad_lowerlimit.') ' .
					$menu_sql .
					$user_sql .
					build_estate_deepsearch_sql() .
					build_estate_fastsearch_sql() . '
					LIMIT 		'.$dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']).', ' . $dbc->_db->quote($_GET['pp']);
		}
		else {
			$q = '	SELECT 		*
					FROM 		'.TABLE_ESTATE.'
					WHERE		(status = '.$dbc->_db->quote(ESTATE_AD_LIVE).') AND
								(sponsored	= '.$dbc->_db->quote(ESTATE_AD_NONSPONSORED).') AND
								(submited >= '.$free_ad_lowerlimit.') ' .
					$menu_sql .
					$user_sql .
					build_estate_deepsearch_sql() .
					build_estate_fastsearch_sql() . '
					LIMIT 		'.$dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']).', ' . $dbc->_db->quote($_GET['pp']);
		}
	}
	// show only for user $_REQUEST['filter_by_user']
	elseif (isset($_REQUEST['filter_by_user'])) {

		if($custom_limit) {
			$sql_user = '	ORDER BY 	RAND()
							LIMIT 		' . $dbc->_db->quote($custom_limit);
		}
		else {
			$sql_user = '	ORDER BY 	submited DESC
							LIMIT 		' . $dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']).', ' . $dbc->_db->quote($_GET['pp']);
		}

		$q = '	SELECT 		*
				FROM 		'.TABLE_ESTATE.'
				WHERE		(status = '.$dbc->_db->quote(ESTATE_AD_LIVE).') AND
							(user_id = '.$dbc->_db->quote($_REQUEST['filter_by_user']).') AND
							(sponsored	= '.$dbc->_db->quote(ESTATE_AD_NONSPONSORED).') AND
							(submited >= '.$free_ad_lowerlimit.') '.
				$search_kw. $sql_user;
	}
	elseif (isset($_GET['tag'])) {

		$orbicon_x->set_page_title(_L('e.tagresults').' &quot;' . htmlspecialchars($_GET['tag']) . '&quot;');
		$orbicon_x->add2breadcrumbs(_L('e.tagresults').' &quot;' . htmlspecialchars($_GET['tag']) . '&quot;');

		$tag_q = (isset($_GET['tag'])) ? sprintf(' AND (tags LIKE %s) ', $dbc->_db->quote('%' . $_GET['tag'] . '%')) : '';

		$q = '	SELECT 		*
				FROM 		'.TABLE_ESTATE.'
				WHERE		(status = '.$dbc->_db->quote(ESTATE_AD_LIVE).') AND
							(sponsored	= '.$dbc->_db->quote(ESTATE_AD_NONSPONSORED).') AND
							(submited >= '.$free_ad_lowerlimit.') ' .
				$tag_q .	'
				GROUP BY	id
				ORDER BY 	submited DESC
				LIMIT 		'.$dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']).', ' . $dbc->_db->quote($_GET['pp']);
	}
	elseif ($sponsored) {

		$sponsored_menu_sql = sprintf(' AND (sponsored_category=%s) ', $dbc->_db->quote($_GET[$orbicon_x->ptr]));

		$q = '	SELECT 		*
				FROM 		'.TABLE_ESTATE.'
				WHERE		(status = '.$dbc->_db->quote(ESTATE_AD_LIVE).') AND
							(sponsored = '.$dbc->_db->quote(ESTATE_AD_SPONSORED).') ' .
				$sponsored_menu_sql.' AND
							(sponsored_live_to >= '.time().')
				ORDER BY 	RAND()
				LIMIT 		' . $sponsored_limit;
	}
	else {

		$q = '	SELECT 		*
				FROM 		'.TABLE_ESTATE.'
				WHERE		(status = '.$dbc->_db->quote(ESTATE_AD_LIVE).') AND
							(sponsored	= '.$dbc->_db->quote(ESTATE_AD_NONSPONSORED).') AND
							(submited >= '.$free_ad_lowerlimit.') ' .
				$user_sql .
				$menu_sql .
				$search_kw . '
				ORDER BY 	'.$sort_by.'
				LIMIT 		'.$dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']).', ' . $dbc->_db->quote($_GET['pp']);
	}
//echo "<!-- $q -->";

	if($ret_sql) {
		return $q;
	}

	$r = $dbc->_db->query($q);

	$estate = $dbc->_db->fetch_object($r);

	if(!isset($_GET['tag'])) {
		$q_tot = '	SELECT 		COUNT(id)
					AS 			numrows
					FROM 		'.TABLE_ESTATE.'
					WHERE		(status = ' . ESTATE_AD_LIVE . ') AND
								(submited >= '.$free_ad_lowerlimit.') '
					. $user_sql
					. $menu_sql
					. $search_kw
					. build_estate_deepsearch_sql()
					. build_estate_fastsearch_sql();
					//echo "<!-- $q_tot -->";
	}
	else {
		$tag_q = (isset($_GET['tag'])) ? sprintf(' AND (tags LIKE %s) ', $dbc->_db->quote('%' . $_GET['tag'] . '%')) : '';

		$q_tot = '	SELECT 		COUNT(id)
					AS			numrows
					FROM 		'.TABLE_ESTATE.'
					WHERE		(status = '.$dbc->_db->quote(ESTATE_AD_LIVE).') AND
								(sponsored	= '.$dbc->_db->quote(ESTATE_AD_NONSPONSORED).') AND
								(submited >= '.$free_ad_lowerlimit.') ' .
					$tag_q;
	}

	$read = $dbc->_db->query($q_tot);

	$row = $dbc->_db->fetch_assoc($read);
	$pagination->total = (!$sponsored && !empty($sponsored_ad_id)) ? ($row['numrows'] - 1) : $row['numrows'];
	$pagination->split_pages();
	unset($read, $row, $q_tot);

	// remember sponsored id
	if($sponsored && $estate->id) {
		$sponsored_ad_id[] = $estate->id;
	}

	if(!$estate_filter_displayed) {
		$ads .= $category_filter;
		$estate_filter_displayed = true;
	}

	// no results found
	if(!$estate && !$sponsored) {
		$ads .= '<p id="no_ads">'._L('e.noresults').'</p>';
	}

	while ($estate) {

		$x = '';

		// skip this one since we already displayed it
		if(in_array($estate->id, $sponsored_ad_id) && !$sponsored) {
			// do nothing, don't use continue; here
		}
		else {
			list($pic, $desc) = explode(',', $estate->pic_main);

			if(is_file(DOC_ROOT . '/site/venus/' . $pic)) {
				if(is_file(DOC_ROOT . '/site/venus/thumbs/t-' . $pic)) {
					$pic = ORBX_SITE_URL .'/site/venus/thumbs/t-' . $pic;
				}
				else {
					$pic = ORBX_SITE_URL .'/site/venus/'.$pic;
				}
			}
			else {

				require_once DOC_ROOT . '/orbicon/class/diriterator/class.diriterator.php';
				$old_dir_path = DOC_ROOT . '/site/venus/old/' . $estate->user_id . '/oglasi/' . $estate->id;
				$dir = new DirIterator($old_dir_path, '*');
				$files = $dir->files();
				$dir = null;

				if (is_file(DOC_ROOT . '/site/venus/old/' . $estate->user_id . '/oglasi/' . $estate->id . '/' . $files[0])) {
					$pic = ORBX_SITE_URL . '/site/venus/old/' . $estate->user_id . '/oglasi/' . $estate->id . '/' . $files[0];
				}
				else {
					$pic = ORBX_SITE_URL .'/orbicon/modules/estate/gfx/no-img.gif';
				}
			}

			$url = url(ORBX_SITE_URL . '/?c=' . urlencode($estate_type_p[$estate->category]) . '/' . urlencode($estate->permalink) . '/' . $estate->id . '&amp;' . $orbicon_x->ptr . '=mod.e', ORBX_SITE_URL . '/' . urlencode($estate_type_p[$estate->category]) . '/' . urlencode($estate->permalink) . '/' . $estate->id);

			$class = ($sponsored) ? ' sponzorirani' : '';
			$li = ($sponsored) ? '<li class="sponsored_bttn"><a href="./?'.$orbicon_x->ptr.'=dobrodo%C5%A1li-na-stranice-marketinga&amp;no-override">Plaćeni oglas</a></li>' : '';
			$a_kred = ($orbicon_x->ptr == 'hr' && DOMAIN_NO_WWW == 'foto-nekretnine.hr') ? '<a class="kredit" onclick="TagToTip(\'tip\',FOLLOWMOUSE, false, BGCOLOR, \'White\')" href="javascript:;">Izračunajte kredit</a></li>' : '';

			//if($orbicon_x->ptr == 'fr') {

				$lat = floatval($estate->latitude);
				$lon = floatval($estate->logitude);

				if(DOMAIN_NO_WWW == 'foto-nekretnine.rs') {
					$lat = empty($lat) ? 44.016521 : $lat;
					$lon = empty($lon) ? 21.005859 : $lon;

					if (($lat == 44.016521) && ($lon == 21.005859)) {

					}
					else {
						$x = '<span class="ico_mapa"><img src="./site/gfx/images/icons/map.gif" /></span>';
					}
				}
				else {
					$lat = empty($lat) ? 45.796255 : $lat;
					$lon = empty($lon) ? 15.954895 : $lon;

					if(($lat == 45.796255) && ($lon == 15.954895)) {

					}
					else {
						$x = '<span class="ico_mapa"><img src="./site/gfx/images/icons/map.gif" /></span>';
					}
				}

				if ($estate->video_embed || $estate->video) {
					$x.= '<span class="ico_video"><img src="./site/gfx/images/icons/video.gif" /></span>';
				}
			//}

			$ads  .= '
		<div class="oglas '.$class.'">
	        <div class="naslov">
	          <h4><a href="'.$url.'">'.$estate->title.'</a></h4>
	          <p>
	          '.$x.'
	            <span class="spremiOglas"><a href="javascript:void(null);" onclick="javascript:fav_ad('.$estate->id.', \'add\');" title="'._L('e.savead').'">'._L('e.savead').'</a></span>
	            <span class="tekstOglasa"><a href="javascript:void(null);"><em><span>'.nl2br($estate->description).'</span></em></a></span>
	          </p>
	          <div class="clr"></div>
	        </div>
	        <a href="'.$url.'"><img src="'.$pic.'" alt="'.$pic.'" title="'.$desc.'" class="img" /></a>';

			if(($estate->category != 6) && ($estate->category != 7)) {

				$town = (is_numeric($estate->town)) ? e_get_town_by_id(intval($estate->town)) : $estate->town;
				$county = $counties[$estate->county];

				$location = array();
				$location[] = ($estate->neighborhood != '') ? $estate->neighborhood : '';
				$location[] = $town;
				if(!$custom_limit) {
					if(DOMAIN_NO_WWW == 'foto-nekretnine.rs') {
						$location[] = $county;
					}
					else {
						$location[] = (($estate->county == 2) || !$estate->county) ? $county : "$county županija";
					}
				}
				$location = array_remove_empty($location);
				$location = implode(', ', $location);

				$price = (empty($estate->price) || ($estate->price == 0.0)) ? 'Na upit' : number_format($estate->price, 2, ',', '.').' '.$estate_currencies[$estate->currency];

	        	$ads .= '<ul class="detalji">
	          <li><span>'._L('e.location').':</span> '.$location.'</li>
	          <li><span>'._L('e.price').':</span> <strong>'.$price.'</strong></li>
	          <li><span>'._L('e.msquare').':</span> <strong>'.$estate->msquare.' m<sup>2</sup></strong> '.$a_kred.' </li>
	          <li class="no_border"><span>'._L('e.date').':</span> '.date($_SESSION['site_settings']['date_format'], $estate->submited).'</li>
	          '.$li.'
	        </ul>';
			}
			else {
				$ads .= '<ul class="detalji">
	          <li>'.$estate->description.'</li>
	          <li class="no_border"><span>'._L('e.date').':</span> '.date($_SESSION['site_settings']['date_format'], $estate->submited).'</li>
	        </ul>';
			}
	        $ads .= '<div class="clr"></div>
	      </div>';
		}
		$estate = $dbc->_db->fetch_object($r);
	}

	// exit here for sponsored, we don't want anything below
	if($sponsored || $custom_limit) {
		// this invalidates caching, clean up from memory
		if($unset_below) {
			unset($_GET['p'], $_GET['pp']);
		}
		return $ads;
	}

	if(isset($_GET['q'])) {
		$ads .= '<div class="use_adv_search">'.sprintf(_L('e.usedetailedsearch'), '<strong>').'</strong></div>';
	}

	// add rss
	if(isset($_GET[$orbicon_x->ptr]) && ($_GET[$orbicon_x->ptr] != 'mod.peoplering') && !isset($_GET['tag'])) {
		$name = $orbicon_x->load_column_name($_GET[$orbicon_x->ptr]);

		if($orbicon_x->ptr != 'hr') {
			require_once DOC_ROOT . '/orbicon/modules/estate/menu.trans.php';
			$name = estate_title_trans($_GET[$orbicon_x->ptr]);
		}

		$ads .= '<div id="rss_icon"><a href="'.ORBX_SITE_URL.'/orbicon/modules/estate/rss.php?c='.$_GET[$orbicon_x->ptr].'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/rss.png" alt="RSS" title="RSS" /> RSS '.$name.'</a></div>';

		if(in_array($prnt, $main_parent_groups)) {
			$name2 = $orbicon_x->load_column_name($prnt);

			if($orbicon_x->ptr != 'hr') {
				require_once DOC_ROOT . '/orbicon/modules/estate/menu.trans.php';
				$name2 = estate_title_trans($prnt);
			}

			$ads .= '<div id="rss_icon"><a href="'.ORBX_SITE_URL.'/orbicon/modules/estate/grprss.php?c='.$prnt.'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/rss.png" alt="RSS" title="RSS" /> RSS '.$name2.'</a></div>';
		}

	}

	$ads .= '
	<style type="text/css">
		#oglasavajte_se a:hover, #text #oglasavajte_se a:hover {
			background:transparent url(./site/gfx/images/oglasavajte-se_'.$orbicon_x->ptr.'.gif) no-repeat scroll -470px 0pt !important;
		}
		#oglasavajte_se a, #text #oglasavajte_se a {
			background:transparent url(./site/gfx/images/oglasavajte-se_'.$orbicon_x->ptr.'.gif) no-repeat scroll 0%  !important;
		}
	</style>
      <div id="oglasavajte_se"><a href="./?'.$orbicon_x->ptr.'=dobrodo%C5%A1li-na-stranice-marketinga&amp;no-override" title="'._L('e.advertrisewithus').'"><img src="'.ORBX_SITE_URL.'/site/gfx/images/oglasavajte-se_'.$orbicon_x->ptr.'.jpg" alt="'._L('e.advertrisewithus').'" title="'._L('e.advertrisewithus').'" /></a></div>';

	// this invalidates caching, clean up from memory
	if($unset_below) {
		unset($_GET['p'], $_GET['pp']);
	}

	$query = http_build_query($_GET);
	if($query) {
		$query = '/?' . $query;
	}
	else {
		$query = '/';
	}
	$ads .= $pagination->construct_page_nav(ORBX_SITE_URL . $query);

	return $ads;
}

/**
 * Create RSS for category
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param string $category
 * @return string
 */
function print_estate_rss($category = '')
{
	global $dbc, $orbicon_x, $estate_type_p;

	$rss = '<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet href="http://www.w3.org/2000/08/w3c-synd/style.css" type="text/css"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
	<atom:link href="'.ORBX_SITE_URL.'/orbicon/modules/estate/rss.php?c='.urlencode($category).'" rel="self" type="application/rss+xml" />
	<title>'.DOMAIN_NAME.'</title>
	<link>'.ORBX_SITE_URL.'/</link>
	<description>'.DOMAIN_DESC.'</description>
	<lastBuildDate>'.date('r').'</lastBuildDate>
	<generator>'.ORBX_FULL_NAME.'</generator>
	<language>'.$orbicon_x->ptr.'</language>
	<copyright>Copyright '.date('Y').', '.DOMAIN_OWNER.'</copyright>
	<managingEditor>'.DOMAIN_EMAIL.' ('.DOMAIN_NAME.')</managingEditor>
	<webMaster>'.DOMAIN_EMAIL.' ('.DOMAIN_NAME.')</webMaster>
	<docs>http://blogs.law.harvard.edu/tech/rss</docs>' . "\n";

	$menu_sql = ($category != '') ? sprintf(' AND (menu=%s) ', $dbc->_db->quote($category)) : '';

	$q = '	SELECT 		*
			FROM 		'.TABLE_ESTATE.'
			WHERE		(status = '.$dbc->_db->quote(ESTATE_AD_LIVE).')' .
			$menu_sql.'
			ORDER BY 	submited DESC
			LIMIT 		20';

	$r = $dbc->_db->query($q);
	$estate = $dbc->_db->fetch_object($r);

	while($estate) {
		$desc = strip_tags($estate->description);
		$desc = trim(str_replace(array("\n", '<', '&nbsp;', '\''), array('', '&lt;', ' ', '&#039;'), $desc));
		$url = url(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.e&amp;c=' . urlencode($estate_type_p[$estate->category] . '/' . urlencode($estate->permalink) . '/' . $estate->id), ORBX_SITE_URL . '/' . urlencode($estate_type_p[$estate->category] . '/' . urlencode($estate->permalink) . '/' . $estate->id));

		$rss .= '<item>
	<title>'.utf8_html_entities($estate->title).'</title>
	<link>'.$url.'</link>
	<description>'.$desc.'</description>
	<pubDate>'.date('r', $estate->submited).'</pubDate>
	<guid isPermaLink="true">'.$url.'</guid>
	<author>'.DOMAIN_EMAIL.' ('.DOMAIN_NAME.')</author>
	<source url="'.ORBX_SITE_URL.'/orbicon/modules/estate/rss.php/?c='.$category.'">'.DOMAIN_NAME.'</source>
</item>'."\n";
			$estate = $dbc->_db->fetch_object($r);
	}

	$rss .= ' </channel>
</rss>';

	return $rss;
}

/**
 * Build SQL for fast search
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @return string
 */
function build_estate_fastsearch_sql()
{
	global $dbc;
	$sql = '';

	// don't interfere with deep search ID
	if($_REQUEST['br_oglasa']) {
		return '';
	}

	// category
	if($_REQUEST['kategorija']) {
		$sql .= sprintf(' AND (category = %s) ', $dbc->_db->quote($_REQUEST['kategorija']));
	}
	// ad type
	if($_REQUEST['ponuda']) {
		$sql .= sprintf(' AND (ad_type = %s) ', $dbc->_db->quote($_REQUEST['ponuda']));
	}
	// county
	if($_REQUEST['regija']) {
		if($_REQUEST['regija'] != 1) {
			$sql .= sprintf(' AND (county = %s) ', $dbc->_db->quote($_REQUEST['regija']));
		}
	}
	// town / neighborhood
	if($_REQUEST['naselje']) {
		$sql .= sprintf(' AND ((town LIKE %s) OR (neighborhood LIKE %s)) ', $dbc->_db->quote($_REQUEST['naselje'] . '%'), $dbc->_db->quote($_REQUEST['naselje'] . '%'));
	}
	// msquare
	if($_REQUEST['povrsina_od'] && $_REQUEST['povrsina_do']) {
		$sql .= sprintf(' AND ((msquare >= %s) AND (msquare <= %s)) ', $dbc->_db->quote(floatval($_REQUEST['povrsina_od'])), $dbc->_db->quote(floatval($_REQUEST['povrsina_do'])));
	}

	// price fix
	if(!$_REQUEST['cijena_od'] && $_REQUEST['cijena_do']) {
		$_REQUEST['cijena_od'] = 1.0;
	}

	// price
	if($_REQUEST['cijena_od'] && $_REQUEST['cijena_do']) {
		if($_REQUEST['cijena_od'] == '0') {
			$_REQUEST['cijena_od'] = 1.0;
		}
		$sql .= sprintf(' AND ((price >= %s) AND (price <= %s)) ', $dbc->_db->quote(floatval($_REQUEST['cijena_od'])), $dbc->_db->quote(floatval($_REQUEST['cijena_do'])));
	}
	// currency
	if($_REQUEST['valuta']) {
		$sql .= sprintf(' AND (currency = %s) ', $dbc->_db->quote($_REQUEST['valuta']));
	}
	// image only
	if($_REQUEST['sa_slikom']) {
		$sql .= ' AND (pic_main != \',\') AND (pic_main != \'\') ';
	}
	// video only
	if($_REQUEST['sa_videom']) {
		$sql .= ' AND ((video != \'\') OR (video_embed != \'\')) ';
	}
	// map only
	if($_REQUEST['sa_kartom']) {
		if(DOMAIN_NO_WWW == 'foto-nekretnine.rs') {
			$sql .= ' AND (
					((logitude != \'21.005859\') AND (latitude != \'44.016521\')) AND
					((logitude != \'15.95489500000000049340087571181356906890869140625\') AND (latitude != \'45.796255000000002155502443201839923858642578125\')) AND
					((logitude != \'\') AND (latitude != \'\'))
				) ';
		}
		else {
			$sql .= ' AND (
					((logitude != \'15.954895\') AND (latitude != \'45.796255\')) AND
					((logitude != \'15.95489500000000049340087571181356906890869140625\') AND (latitude != \'45.796255000000002155502443201839923858642578125\')) AND
					((logitude != \'\') AND (latitude != \'\'))
				) ';
		}
	}

	// sorting
	switch ($_REQUEST['poredak']) {
		case 'price_lower': $sql .= ' ORDER BY price DESC '; break;
		case 'price_higher': $sql .= ' ORDER BY price ASC '; break;
		case 'date_older': $sql .= ' ORDER BY submited ASC '; break;
		case 'date_newer': $sql .= ' ORDER BY submited DESC '; break;
		default: $sql .= ' ORDER BY submited DESC '; break;
	}

	return $sql;
}

/**
 * Build SQL for deep search
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @return string
 */
function build_estate_deepsearch_sql()
{
	global $dbc;
	$sql = '';

	// house type
	if($_REQUEST['vrsta_kuce']) {
		$sql .= sprintf(' AND (house_type = %s) ', $dbc->_db->quote($_REQUEST['vrsta_kuce']));
	}
	// apartment type
	if($_REQUEST['vrsta_stana']) {
		$sql .= sprintf(' AND (apartment_type = %s) ', $dbc->_db->quote($_REQUEST['vrsta_stana']));
	}
	// business type
	if($_REQUEST['vrsta_prostora']) {
		$sql .= sprintf(' AND (business_type = %s) ', $dbc->_db->quote($_REQUEST['vrsta_prostora']));
	}
	// land type
	if($_REQUEST['vrsta_zemljista']) {
		$sql .= sprintf(' AND (land_type = %s) ', $dbc->_db->quote($_REQUEST['vrsta_zemljista']));
	}
	// heating
	if($_REQUEST['grijanje']) {
		$sql .= sprintf(' AND (heating = %s) ', $dbc->_db->quote($_REQUEST['grijanje']));
	}
	// room number
	if($_REQUEST['br_soba_od'] && $_REQUEST['br_soba_do']) {
		$sql .= sprintf(' AND ((room_num >= %s) OR (room_num <= %s)) ', $dbc->_db->quote($_REQUEST['br_soba_od']), $dbc->_db->quote($_REQUEST['br_soba_do']));
	}
	// floor number
	if($_REQUEST['br_katova_od'] && $_REQUEST['br_katova_do']) {
		$sql .= sprintf(' AND ((floor_num >= %s) OR (floor_num <= %s)) ', $dbc->_db->quote($_REQUEST['br_katova_od']), $dbc->_db->quote($_REQUEST['br_katova_do']));
	}
	// bath number
	if($_REQUEST['br_kupaonica_od'] && $_REQUEST['br_kupaonica_do']) {
		$sql .= sprintf(' AND ((bath_num >= %s) OR (bath_num <= %s)) ', $dbc->_db->quote($_REQUEST['br_kupaonica_od']), $dbc->_db->quote($_REQUEST['br_kupaonica_do']));
	}
	// flat number
	if($_REQUEST['ukupno_katova_od'] && $_REQUEST['ukupno_katova_do']) {
		$sql .= sprintf(' AND ((flat_num >= %s) OR (flat_num <= %s)) ', $dbc->_db->quote($_REQUEST['ukupno_katova_od']), $dbc->_db->quote($_REQUEST['ukupno_katova_do']));
	}
	// build type
	if($_REQUEST['ng_sg']) {
		$sql .= sprintf(' AND (build_type = %s) ', $dbc->_db->quote($_REQUEST['ng_sg']));
	}
	// year built
	if($_REQUEST['god_od'] && $_REQUEST['god_do']) {
		$sql .= sprintf(' AND ((year_built >= %s) OR (year_built <= %s)) ', $dbc->_db->quote($_REQUEST['god_od']), $dbc->_db->quote($_REQUEST['god_do']));
	}
	// street
	if($_REQUEST['ulica']) {
		$sql .= sprintf(' AND (street = %s) ', $dbc->_db->quote($_REQUEST['ulica']));
	}
	// public transport
	if($_REQUEST['prijevoz']) {
		$sql .= ' AND (public_transport != 0) ';
	}
	// ad id
	if($_REQUEST['br_oglasa']) {
		// exit here for an ID since it's unique
		return  sprintf(' AND (id = %s) ', $dbc->_db->quote($_REQUEST['br_oglasa']));
	}
	// docs
	if($_REQUEST['dokumentacija']) {
		$sql .= sprintf(' AND (docs = %s) ', $dbc->_db->quote($_REQUEST['dokumentacija']));
	}
	// zagreb
	if($_REQUEST['zg']) {
		$sql .= sprintf(' AND (zg = %s) ', $dbc->_db->quote($_REQUEST['zg']));
	}

	// equipment
	$equipment = ((int) $_REQUEST['telefon'] | (int) $_REQUEST['balkon'] | (int) $_REQUEST['vrt'] | (int) $_REQUEST['garaza'] | (int) $_REQUEST['klima'] | (int) $_REQUEST['invalidi'] | (int) $_REQUEST['bazen'] | (int) $_REQUEST['tv'] | (int) $_REQUEST['satelitska'] | (int) $_REQUEST['internet'] | (int) $_REQUEST['tereni'] | (int) $_REQUEST['dvorana']);

	if($equipment) {
		$sql .= sprintf(' AND (equipment & %s) ', $dbc->_db->quote($equipment));
	}

	return $sql;
}

/**
 * Print links to estate ads
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $limit
 * @param int $estate_ad_id
 * @return string
 */
function print_estate_tag_cloud($limit = 10, $estate_ad_id = null)
{
	if(!is_int($limit)) {
		trigger_error('print_estate_tag_cloud() expects parameter 1 to be integer, '.gettype($limit).' given', E_USER_WARNING);
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

	$id_sql = ($estate_ad_id != null) ? sprintf(' AND (id=%s) ', $dbc->_db->quote($estate_ad_id)) : '';
	$sql_limit = ($limit) ? 'LIMIT ' . $limit : '';


	$q = '	SELECT 		tags
			FROM 		' . TABLE_ESTATE . '
			WHERE 		(tags != \'\') AND
						(status = '.ESTATE_AD_LIVE.')
						'.$id_sql.'
			ORDER BY	submited DESC ' . $sql_limit;

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

				$tag_cloud[$v] = '<a class="tag '.$tp.'" href="'.ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.estate.l&amp;tag=' . urlencode($v).'">'.$v.'</a>';
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
 * Set sponsored status of ad
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 * @param int $state
 * @return int
 */
function set_estate_ad_sponsor($id, $state)
{
	if(!is_int($id)) {
		trigger_error('set_estate_ad_sponsor() expects parameter 1 to be integer, '.gettype($id).' given', E_USER_WARNING);
		return false;
	}

	global $dbc;

	$q = sprintf('	UPDATE 		'.TABLE_ESTATE.'
					SET			sponsored=%s
					WHERE 		(id=%s)',
					$dbc->_db->quote($state), $dbc->_db->quote($id));
	$dbc->_db->query($q);

	return $dbc->_db->affected_rows();
}

/**
 * Get town name by ID
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 * @return string
 */
function e_get_town_by_id($id)
{
	if(!is_int($id)) {
		trigger_error('e_get_town_by_id() expects parameter 1 to be integer, '.gettype($id).' given', E_USER_WARNING);
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
 * Archive ads for user with RID
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $rid
 * @param int $skip
 */
function archive_user_ads($rid, $skip = 2)
{
	global $dbc;

	$q_c = '	SELECT 		COUNT(id) AS total
				FROM 		'.TABLE_ESTATE.'
				WHERE		(status = '.$dbc->_db->quote(ESTATE_AD_LIVE).') AND
							(user_id = '.$dbc->_db->quote($rid).')';
	$r_c = $dbc->_db->query($q_c);
	$a_c = $dbc->_db->fetch_assoc($r_c);
	$max = $a_c['total'];

	if($max) {
		$q = '	SELECT 		id
				FROM 		'.TABLE_ESTATE.'
				WHERE		(status = '.$dbc->_db->quote(ESTATE_AD_LIVE).') AND
							(user_id = '.$dbc->_db->quote($rid).')
				ORDER BY 	submited DESC
				LIMIT 		'.$skip.', ' . $max;
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		while ($a) {
			set_estate_ad_status(intval($a['id']), ESTATE_AD_ARCHIVED);
			$a = $dbc->_db->fetch_assoc($r);
		}
	}
}

/**
 * Update total number of ads for agency
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 * @param int $total
 * @return int
 */
function update_agency_ads_num($id, $total)
{
	if(!is_int($id)) {
		trigger_error('update_agency_ads_num() expects parameter 1 to be integer, '.gettype($id).' given', E_USER_WARNING);
		return false;
	}

	global $dbc;

	$q = sprintf('	UPDATE 		pring_company
					SET			total_estate_ads=%s
					WHERE 		(id=%s)',
					$dbc->_db->quote($total), $dbc->_db->quote($id));
	$dbc->_db->query($q);

	return $dbc->_db->affected_rows();
}

/**
 * Set embed code for ad
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 * @param string $embed_code
 * @return int
 */
function set_estate_embed_video($id, $embed_code)
{
	global $dbc;

	$q = sprintf('	UPDATE 		'.TABLE_ESTATE.'
					SET			video_embed=%s
					WHERE 		(id=%s)',
					$dbc->_db->quote($embed_code), $dbc->_db->quote($id));
	$dbc->_db->query($q);

	return $dbc->_db->affected_rows();
}

/**
 * Delete previews for user
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $user_id
 * @return int
 */
function delete_previews($user_id)
{
	global $dbc;

	$q = sprintf('	DELETE
					FROM 		'.TABLE_ESTATE.'
					WHERE 		(status='.ESTATE_AD_PREVIEW.') AND
								(user_id = %s)',
					$dbc->_db->quote($user_id));
	$dbc->_db->query($q);

	return $dbc->_db->affected_rows();
}

/**
 * Transport images between ads
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $old_id
 * @param int $new_id
 */
function transport_pictures($old_id, $new_id)
{
	global $dbc;

	$q = sprintf('	SELECT 		pics, pic_main
					FROM 		'.TABLE_ESTATE.'
					WHERE		(id = %s)
					LIMIT		1',	$dbc->_db->quote($old_id));
	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_assoc($r);

	edit_estate_pics($new_id, $a['pics']);
	edit_estate_pic_main($new_id, $a['pic_main']);
}

/**
 * Enter description here...
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param string $userq
 * @return array
 */
function new_xhr_tags($userq)
{
	global $dbc;
	$similar_tags = array();
	$q = sprintf('	SELECT 		tags
					FROM 		'.TABLE_ESTATE.'
					WHERE		(tags LIKE %s)',
					$dbc->_db->quote("%$userq%"));

	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_assoc($r);

	while ($a) {

		$tags = explode(',', $a['tags']);

		foreach ($tags as $tag) {
			if(strpos($tag, $userq) !== false) {
				$similar_tags[] = $tag;
			}
		}

		$a = $dbc->_db->fetch_assoc($r);
	}

	return array_unique($similar_tags);
}

/**
 * Update sponsored live to timestamp
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $ad_id
 * @param int $live_to
 */
function edit_sponsored_time($ad_id, $live_to)
{
	if(!$live_to) {
		return false;
	}

	global $dbc;
	$q = sprintf('	UPDATE 		'.TABLE_ESTATE.'
					SET			sponsored_live_to=%s
					WHERE 		(id=%s)',
					$dbc->_db->quote($live_to), $dbc->_db->quote($ad_id));
	$dbc->_db->query($q);
	return true;
}

/**
 * Get sponsored max TTL for estate ad
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $ad_id
 * @return int
 */
function get_sponsored_time($ad_id)
{
	if(!$ad_id) {
		return false;
	}

	global $dbc;
	$q = sprintf('	SELECT 		sponsored_live_to
					FROM		'.TABLE_ESTATE.'
					WHERE 		(id=%s)',
					$dbc->_db->quote($ad_id));
	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_assoc($r);
	return $a['sponsored_live_to'];
}

/**
 * Update ad views by one
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 * @return int
 */
function update_estate_ad_views($id)
{
	if(!is_int($id)) {
		trigger_error('update_estate_ad_views() expects parameter 1 to be integer, '.gettype($id).' given', E_USER_WARNING);
		return false;
	}

	global $dbc;

	$q = sprintf('	UPDATE 		'.TABLE_ESTATE.'
					SET			views = (views + 1)
					WHERE 		(id=%s)',
					$dbc->_db->quote($id));
	$dbc->_db->query($q);

	return $dbc->_db->affected_rows();
}

/**
 * add ad user flags
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $id
 * @param int $user_rid
 * @param int $type
 * @return int
 */
function add_estate_user_flag($ad_id, $user_rid, $type)
{
	if(!is_int($ad_id)) {
		trigger_error('update_estate_user_flag() expects parameter 1 to be integer, '.gettype($ad_id).' given', E_USER_WARNING);
		return false;
	}

	global $dbc;

	$q = sprintf('	INSERT INTO 	'.TABLE_ESTATE_USER_FLAGS.'
									(ad_id, user_rid,
									type)
					VALUES			(%s, %s,
									%s)',
					$dbc->_db->quote($ad_id), $dbc->_db->quote($user_rid),
					$dbc->_db->quote($type));
	$dbc->_db->query($q);

	return $dbc->_db->affected_rows();
}

/**
 * Get already flagged
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $ad_id
 * @param int $user_rid
 * @param int $type
 * @return int
 */
function get_estate_ad_already_flagged($ad_id, $user_rid, $type)
{
	if(!$ad_id) {
		return false;
	}

	global $dbc;
	$q = sprintf('	SELECT 		id
					FROM		'.TABLE_ESTATE_USER_FLAGS.'
					WHERE 		(ad_id=%s) AND
								(user_rid=%s) AND
								(type=%s)
					LIMIT		1',
					$dbc->_db->quote($ad_id), $dbc->_db->quote($user_rid), $dbc->_db->quote($type));
	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_assoc($r);
	return $a['id'];
}

/**
 * Count total flags of type
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $ad_id
 * @param int $type
 * @return int
 */
function count_estate_ad_flags($ad_id, $type)
{
	if(!$ad_id) {
		return false;
	}

	global $dbc;
	$q = sprintf('	SELECT 		COUNT(id) AS total
					FROM		'.TABLE_ESTATE_USER_FLAGS.'
					WHERE 		(ad_id=%s) AND
								(type=%s)',
					$dbc->_db->quote($ad_id), $dbc->_db->quote($type));
	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_assoc($r);
 	return $a['total'];
}

/**
 * Enter description here...
 *
 * @param string $title
 * @return string
 */
function estate_title_subs($title)
{
	return $title;
}

/**
 * Clear expired sponsored ads
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @return int
 */
function clear_estate_expired_sponsored_ads()
{
	global $dbc;

	$q = sprintf('	UPDATE 		'.TABLE_ESTATE.'
					SET			sponsored = '.ESTATE_AD_NONSPONSORED.',
								sponsored_category = \'\',
								sponsored_live_to = 0
					WHERE		(sponsored = '.ESTATE_AD_SPONSORED.') AND
								(sponsored_live_to < '.time().')');
	$dbc->_db->query($q);

	return $dbc->_db->affected_rows();
}

function print_preview_link($pic)
{
	if(is_file(DOC_ROOT . '/site/venus/'. $pic)) {
		return '<a href="./site/venus/'.$pic.'" target="_blank"><img src="./site/gfx/images/icons/fotogalerija.gif" /></a>';
	}
	return null;
}

?>