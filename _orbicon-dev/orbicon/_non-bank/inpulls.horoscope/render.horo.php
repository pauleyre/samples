<?php
/**
 * Horoscope
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2008, Pavle Gardijan
 * @package SystemMOD
 * @subpackage Inpulls
 * @version 1.0
 * @link http://www.inpulls.com
 * @license http://
 * @since 2008-03-12
 * @todo Translation
 */

	require_once DOC_ROOT . '/orbicon/modules/inpulls/inc.inpulls.php';
	require_once DOC_ROOT . '/orbicon/modules/inpulls.horoscope/inc.horo.php';

	sync_horoscope();
	return '<p><h3>Dnevni horoskop by Ana Rakić za '.date($_SESSION['site_settings']['date_format']).'.</h3><br />Nismo mogli dobiti dopuštenje za besplatnom objavom nekog našeg horoskopa pa vam donosimo istočnu varijantu sa www.astrolook.com koja nije ništa lošija.</p><br /><br />' . print_all_horoscopes();

?>