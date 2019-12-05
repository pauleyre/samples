<?php
/**
 * Estate administration for registered users
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package OrbiconMOD
 * @subpackage Estate
 * @version 1.1
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-10-04
 */

	if(!$_SESSION['user.r']['id']) {
		redirect(ORBX_SITE_URL . '/?'.$orbicon_x->ptr.'=registracija-postanite-korisnik&no-override');
	}

	include_once DOC_ROOT . '/orbicon/modules/estate/inc.estate.php';

	switch ($_GET['page']) {
		case 'add':
		case 'edit':
			$load = 'myedit.estate.php';
		break;
		case 'thx':
			$load = 'thx.php';
		break;
		default:
			$load = 'mylist.estate.php';
		break;
	}

	return include_once DOC_ROOT . '/orbicon/modules/estate.new/' . $load;

?>