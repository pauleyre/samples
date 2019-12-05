<?php
/**
 * sidebar code of savings calculator module
 * @author Dario Benšić <dario.bensic@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @version 2.04
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-06-27
 */

	echo '<fieldset><legend>'._L('list_of_savings').'</legend>';

        echo'<p><a href="' . ORBX_SITE_URL . '/?'. $orbicon_x->ptr . '=orbicon/mod/savings_calculator">'._L('home').'</a></p>';

    	ispis_stednji();

    echo '</fieldset>';

?>