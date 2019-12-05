<?php

echo '<link rel="stylesheet" href="'.ORBX_SITE_URL.'/orbicon/modules/orbitum-elv/style.css" type="text/css" media="screen" />';

	/**
	 * returns sql errors
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @todo move somewhere more appropriate
	 * @return string
	 */
	function get_sql_errors()
	{
		global $dbc;

		$q = '	SELECT 		*
				FROM 		orbx_error_sql
				ORDER BY 	time';
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		$stats = '';

		while($a) {
			$stats .= date('r', $a['time']) . ' : ' . str_sanitize($a['query'], STR_SANITIZE_XML) . "\n";
			$a = $dbc->_db->fetch_assoc($r);
		}

		return $stats;
	}


	$log_file_exists = true;

	switch($_GET['log']){

		case 'php': $log = 'php';
					break;
		case 'err': $log = 'error';
					break;
		case 'dbg': $log = 'debug';
					break;
		case 'sys': $log = 'system';
					break;
		case 'sql':
			$log = get_sql_errors();
			$log_file_exists = false;
					break;
		default: 	$log = 'php';
					break;

	}

	if($log_file_exists) {

		// Open the file we wish to read - In this case the access log of our server
		$fp = fopen(DOC_ROOT . '/site/mercury/logs/' . date('Y-m-d') . '.orbx.' . $log . '.log', 'rb');
		fseek($fp, -262144, SEEK_END);
		fgets($fp, 8192);

		// We can begin to loop through reading all lines and echoing them:
		if($fp) {
			while(!feof($fp)) {
				$line = fgets($fp, 8192);
				if($line && !feof($fp)) {
					$file .= $line;
				}
				$line = fgets($fp, 8192);
			}
		}

	    // Ok, we hit the end of the file, there are a few odds-n-ends we need:
	    // First reseek to the end of the file, this is necessary to reset
	    //  the filepointer, else it won't read anything else.
	    fseek($fp, 0, SEEK_END);
	    fclose($fp);
	}
	else {
		$file = $log;
	}

echo '
<ul>
	<li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/orbitum-elv&amp;log=php">View php log (last 256 KB)</a></li>
	<li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/orbitum-elv&amp;log=err">View error log (last 256 KB)</a></li>
	<li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/orbitum-elv&amp;log=dbg">View debug log (last 256 KB)</a></li>
	<li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/orbitum-elv&amp;log=sys">View system log (last 256 KB)</a></li>
	<li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/orbitum-elv&amp;log=sql">View SQL error log (all)</a></li>
</ul>
';

echo '<pre>'.$file.'</pre>';

?>