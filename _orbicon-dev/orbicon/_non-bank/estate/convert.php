<?php
/**
 * Frontend rendering
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @subpackage Estate
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-09-10
 */

	/*----- ATTENTION! -------------------------------------------------
	 | Include before wherever DOC_ROOT required.
	 |------------------------------------------------------------------*/
	if(!defined('DOC_ROOT')) {
		// we'll need this info
		$dir = dirname(dirname(__FILE__));

		$file = $dir . '/index.php';
		$license = $dir . '/license.php';
		$found = false;

		while(!$found) {

			$dir = dirname(dirname($file));

			$license = $dir . '/license.php';
			$file = $dir . '/index.php';

			if(is_file($file) && is_file($license)) {
				$found = true;
				break;
			}
		}

		$request_uri = $_SERVER['REQUEST_URI'];

		$left = str_replace($dir, '', dirname(__FILE__));
		$left = str_replace('\\', '/', $left);
		$left = str_replace($left, '', $request_uri);
		// get rid of query
		$left = explode('?', $left);
		// file or no file?
		$left = (strpos($left[0], '.php')) ? dirname($left[0]) : $left[0];
		// strip forward slash as well
		$left = (substr($left, -1, 1) == '/') ? substr($left, 0, -1) : $left;

		define('ORBX_URI_PATH', $left);
		define('DOC_ROOT', $dir);
		unset($left, $request_uri);
	}
	//----- ATTENTION! ENDS -------------------------------------------------
set_time_limit(0);
	// core include
	require DOC_ROOT . '/orbicon/class/inc.core.php';

		$q = 'SELECT * FROM kontakt';

	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_assoc($r);
	while($a) {

			$reg_id = $a['klijent_ID'];

			$q_pr = 'select pring_contact_id from orbicon_reg_members where id = ' . $reg_id;
			$r_pr = $dbc->_db->query($q_pr);
			$a_pr = $dbc->_db->fetch_assoc($r_pr);

			if($a['tip_kontakta_ID'] == 1) {
			$q_up = sprintf('	UPDATE 	pring_contact
					SET 	contact_phone=%s
					WHERE 	(id = %s)',
					$dbc->_db->quote($a['kontakt']), $dbc->_db->quote($a_pr['pring_contact_id']));

				$dbc->_db->query($q_up);

			}
			elseif ($a['tip_kontakta_ID'] == 2) {
$q_up = sprintf('	UPDATE 	pring_contact
					SET 	contact_gsm=%s
					WHERE 	(id = %s)',
					$dbc->_db->quote($a['kontakt']), $dbc->_db->quote($a_pr['pring_contact_id']));

				$dbc->_db->query($q_up);
			}
			elseif($a['tip_kontakta_ID'] == 3) {
$q_up = sprintf('	UPDATE 	pring_contact
					SET 	contact_email=%s
					WHERE 	(id = %s)',
					$dbc->_db->quote($a['kontakt']), $dbc->_db->quote($a_pr['pring_contact_id']));

				$dbc->_db->query($q_up);
			}
			elseif($a['tip_kontakta_ID'] == 4) {
$q_up = sprintf('	UPDATE 	pring_contact
					SET 	  	contact_url=%s
					WHERE 	(id = %s)',
					$dbc->_db->quote($a['kontakt']), $dbc->_db->quote($a_pr['pring_contact_id']));

				$dbc->_db->query($q_up);
			}


			$a = $dbc->_db->fetch_assoc($r);

	}

	/*	$dbc->_db->query('SET NAMES utf8');


	$q = 'SELECT *, UNIX_TIMESTAMP(datum) AS timestamp FROM fotoold.oglas';

	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_assoc($r);
$dbc->_db->query('SET NAMES latin1');
	while($a) {

		$sql_c = 'SELECT id FROM test.orbx_mod_estate WHERE id = ' . $a['oglas_ID'];
		$r_chk = $dbc->_db->query($sql_c);
		$a_chk = $dbc->_db->fetch_assoc($r_chk);

		if(empty($a_chk['id'])) {

			if($a['naslov']) {

				if($a['proizvod']) {
					$category = 6;
				}
				else  {
					if($a['kategorija_ID'] == 1) {
						$category = 2;
					}
					elseif ($a['kategorija_ID'] == 2) {
						$category = 1;
					}
					elseif($a['kategorija_ID'] == 3) {
						$category = 4;
					}
					elseif($a['kategorija_ID'] == 4) {
						$category = 3;
					}
					elseif($a['kategorija_ID'] == 5) {
						$category = 5;
					}
				}

				$q_new = sprintf('INSERT INTO test.orbx_mod_estate (id, title, description, msquare, submited, town, neighborhood, keywords, permalink, price, user_id, street, category, ad_type) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)', $dbc->_db->quote($a['oglas_ID']), $dbc->_db->quote($a['naslov']), $dbc->_db->quote($a['dod_info']), $dbc->_db->quote($a['m2']), $dbc->_db->quote($a['timestamp']), $dbc->_db->quote($a['grad']), $dbc->_db->quote($a['kvart']), $dbc->_db->quote(keyword_generator($a['dod_info'])), $dbc->_db->quote(get_permalink($a['naslov'])), $dbc->_db->quote($a['cijena']), $dbc->_db->quote($a['klijent_ID']), $dbc->_db->quote($a['ulica']), $dbc->_db->quote($category), $dbc->_db->quote($a['vrsta_oglasa_ID']));

				$dbc->_db->query($q_new);
		}
		}
		$a = $dbc->_db->fetch_assoc($r);
	}


	// users

	$dbc->_db->query('SET NAMES utf8');


	$q = 'SELECT * FROM fotoold.klijent';

	$r = $dbc->_db->query($q);
	$a = $dbc->_db->fetch_assoc($r);
$dbc->_db->query('SET NAMES latin1');

	while ($a) {

		$sql_c = 'SELECT id FROM test.orbicon_reg_members WHERE id = ' . $a['klijent_ID'];
		$r_chk = $dbc->_db->query($sql_c);
		$a_chk = $dbc->_db->fetch_assoc($r_chk);

		if(empty($a_chk['id'])) {

			$q_pring = sprintf('INSERT INTO test.pring_contact (contact_name, contact_address) VALUES (%s, %s)', $dbc->_db->quote($a['ime_klijenta']), $dbc->_db->quote($a['adresa']));
			$r_pring = $dbc->_db->query($q_pring);
			$pring_id = $dbc->_db->insert_id();

			$q_new = sprintf('INSERT INTO test.orbicon_reg_members (id, username, pwd, pring_contact_id) VALUES (%s, %s, PASSWORD(%s), %s)', $dbc->_db->quote($a['klijent_ID']), $dbc->_db->quote($a['username']), $dbc->_db->quote($a['password']), $dbc->_db->quote($pring_id));

					$dbc->_db->query($q_new);

			$cv_init = sprintf('INSERT INTO 	pring_cvs
										SET				contact_id = %s, cvname = %s',
										$pring_id, $dbc->_db->quote($a['ime_klijenta']));
							$dbc->_db->query($cv_init);


			$company_init = sprintf('	INSERT INTO 	pring_company
												SET				contact = %s',
										$pring_id);

					$dbc->_db->query($company_init);

		}

		$a = $dbc->_db->fetch_assoc($r);
	}*/

?>