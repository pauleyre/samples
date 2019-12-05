<?php
/**
 * Frontend rendering
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package OrbiconMOD
 * @subpackage Inpulls
 * @version 1.00
 * @link http://www.inpulls.com
 * @license http://
 * @since 2007-11-29
 */

	require_once DOC_ROOT . '/orbicon/modules/inpulls/inc.inpulls.php';

	return print_inpulls_profiles(true) . print_inpulls_profiles();
?>