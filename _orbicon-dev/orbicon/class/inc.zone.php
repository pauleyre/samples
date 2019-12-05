<?php
/**
 * Zone include
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @subpackage Global
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-07-01
 */

	/**
	 * Setup zone
	 *
	 * @param string $area
	 */
	function setup_zone($area)
	{
		global $dbc, $orbicon_x;

		$column_list = ($_SESSION['site_settings']['us_ascii_uris']) ? 'column_list_ascii' : 'column_list';
		
		// get candidates
		$q = sprintf('	SELECT 	*
						FROM 	'.TABLE_ZONES.'
						WHERE 	('.$column_list.' LIKE %s) AND
								(language = %s)',
						$dbc->_db->quote("%$area%"), $dbc->_db->quote($orbicon_x->ptr));

		$a = $dbc->_db->get_cache($q);
		if($a !== null) {
			$_SESSION['current_zone'] = $a;
			return;
		}

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			// now explode them and check if they're truly in the zone
			$columns = explode('|', $a[$column_list]);
			if(in_array($area, $columns)) {
				$zones[] = $a;
			}
			$a = $dbc->_db->fetch_assoc($r);
		}

		$dbc->_db->put_cache($zones, $q);

		$_SESSION['current_zone'] = $zones;
	}

?>