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

	require_once DOC_ROOT . '/orbicon/modules/inpulls/inc.inpulls.php';

	return print_inpulls_profiles(true) . print_inpulls_profiles();
?>