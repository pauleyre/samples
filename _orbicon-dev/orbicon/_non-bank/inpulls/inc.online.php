<?php

/**
 * Update online time
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param int $prid
 * @return int
 */
function update_inpulls_online($prid)
{
	global $dbc;

	$q = sprintf('	UPDATE 		orbx_mod_inpulls_profile
					SET			online_last_activity = UNIX_TIMESTAMP()
					WHERE 		(pring_id=%s)',
					$dbc->_db->quote($prid));
	$dbc->_db->query($q);

	return $dbc->_db->affected_rows();
}

?>