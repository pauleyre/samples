<?php

	/*if(scan_templates('<!>CPHP') < 1) {
		return false;
	}*/
	
	global $dbc;
	$q = sprintf('	SELECT 	value 
					FROM 	'.TABLE_SETTINGS.' 
					WHERE 	(setting = %s) 
					LIMIT 	1', 
					$dbc->_db->quote('custom_php_code'));
	$r =$dbc->_db->query($q);
	$a = $dbc->_db->fetch_array($r);
	$php = $a['value'];
	unset($a, $r);

	return eval($php);

?>