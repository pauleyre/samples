<?php
/**
 * Estate administration
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @subpackage Estate
 * @version 1.1
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-10-04
 */

	include_once 'inc.estate.php';

	switch ($_GET['page']) {
		case 'add':
		case 'edit':
			$load = 'edit.estate.php';
		break;
		default:
			$load = 'list.estate.php';
		break;
	}

	include_once $load;

?>