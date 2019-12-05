<?php

	function print_db_status()
	{
		global $dbc, $orbicon_x;
		if(isset($_POST['optimize_sql'])) {
			return print_db_optimize();
		}
		if(isset($_GET['table'])) {
			return table_info();
		}

		$status = '<strong><address>'.DB_NAME.'@'.DB_HOST.'</address></strong><br /><table style="width:100%;font-size:85%;"><tr><td><strong>'._L('table').'</strong><td><td><strong>'._L('size').'</strong><td></tr>';
		$r = $dbc->_db->query('	SHOW 	TABLE STATUS
								FROM 	'.DB_NAME);
		$a = $dbc->_db->fetch_array($r);
		$i = 1;

		while($a) {
			$style = (($i % 2) == 0) ? ' style="background:#ffffff;"' : '';

			$status .= sprintf('<tr' . $style . '><td>%s<td><td>%s<td></tr>', '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/sql_db&amp;table=' . $a['Name'] . '">' . $a['Name'] . '</a>', byte_size(($a['Data_length']+$a['Index_length'])));
			$total += ($a['Data_length'] + $a['Index_length']);
			$a = $dbc->_db->fetch_array($r);
			$i ++;
		}

		$status .= '<tr><td><strong>'._L('total').'</strong><td><td><strong>'.byte_size($total).'</strong><td></tr></table>';
		$status .= '<p><form method="post" action=""><input type="submit" id="optimize_sql" name="optimize_sql" value="'._L('optimize').'" /></form></p> <div style="height: 1%;"></div>';
		return $status;
	}

	function print_db_optimize()
	{
		global $dbc, $orbx_mod;

		if($orbx_mod->validate_module('stats')) {
			include_once DOC_ROOT . '/orbicon/class/class.stats.php';
			$stats = new Statistics;
			$stats->_compress_stats();
			unset($stats);
		}

		require_once DOC_ROOT .'/orbicon/class/class.cachee.php';
		$cleanup = new CacheEngine();
		$size_files = $cleanup->_cache_cleanup();
		$cleanup = null;

		$tables = $dbc->_db->query('	SHOW 	TABLES
										FROM 	'.DB_NAME);
		$table = $dbc->_db->fetch_array($tables);

		$total = 0;
		$i = 1;

		$status = '<strong><address>'.DB_NAME.'@'.DB_HOST.'</address></strong><br />
		<table style="width:100%;font-size:85%;">
			<tr>
				<td><strong>'._L('table').'</strong></td>
				<td><strong>'._L('before').'</strong></td>
				<td><strong>'._L('after').'</strong></td>
			</tr>';

		while($table) {
			$before = 0;
			$after = 0;

			// before
			$r_1 = $dbc->_db->query(sprintf('SHOW TABLE STATUS
											FROM '.DB_NAME.'
											LIKE %s', $dbc->_db->quote($table[0])));

			// empty cache table
			$dbc->_db->query('DELETE FROM '.TABLE_HTML_CACHE);

			if(stripos(DOMAIN_NO_WWW, 'foto-nekretine') !== false) {
				sql_res('DELETE FROM ' . TABLE_STATISTICS );
			}

			// optimize
			$dbc->_db->query('OPTIMIZE TABLE '.$table[0]);
			// after
			$r_2 = $dbc->_db->query(sprintf('SHOW TABLE STATUS
											FROM '.DB_NAME.'
											LIKE %s', $dbc->_db->quote($table[0])));

			$a_1 = $dbc->_db->fetch_array($r_1);
			while($a_1) {
				$before = $before + $a_1['Data_length'] + $a_1['Index_length'];
				$a_1 = $dbc->_db->fetch_array($r_1);
			}

			$a_2 = $dbc->_db->fetch_array($r_2);
			while($a_2) {
				$after = $after + $a_2['Data_length'] + $a_2['Index_length'];
				$a_2 = $dbc->_db->fetch_array($r_2);
			}

			$color = ($before != $after) ? 'style="color:green;"' : '';

			$style = (($i % 2) == 0) ? ' style="background:#ffffff;"' : '';

			$status .= '
			<tr '.$style.'>
				<td>'.$table[0].'</td>
				<td>'.byte_size($before).'</td>
				<td '.$color.'>'.byte_size($after).'</td>
			</tr>';

			$total += ($before - $after);
			$dbc->_db->free_result($r_1);
			$dbc->_db->free_result($r_2);
			$table = $dbc->_db->fetch_array($tables);
			$i ++;
		}

		$color = ($total > 0) ? 'style="color:green;"' : '';

		$status .= '</tr></table>';
		$status .= '<p><strong>'._L('saved_space').':</strong> <span '.$color.'>'.byte_size($total).' (DB) + '.byte_size($size_files).' (HD)</span>.</p> <div style="height: 1%;"></div>';
		return $status;
	}

	function execute_sql()
	{
		if(isset($_POST['exec_sql'])) {
			global $dbc;
			$dbc->_db->query($_POST['user_sql']);
		}
	}

	function table_info()
	{
		global $dbc, $orbicon_x;
		$table_name = trim($_GET['table']);


		// pagination
		require_once DOC_ROOT . '/orbicon/class/class.pagination.php';

		$_GET['pp'] = (isset($_GET['pp'])) ? $_GET['pp'] : 30;
		$_GET['p'] = (isset($_GET['p'])) ? $_GET['p'] : 1;

		$pagination = new Pagination('p', 'pp');

		$read = $dbc->_db->query('SELECT COUNT(*) AS numrows FROM '.$table_name);
		$row = $dbc->_db->fetch_assoc($read);
		$pagination->total = $row['numrows'];
		$pagination->split_pages();
		unset($read, $row);

		$r = $dbc->_db->query(sprintf('SELECT * FROM '.$table_name . ' LIMIT %s, %s', $dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']), $dbc->_db->quote($_GET['pp'])));
		$a = $dbc->_db->fetch_assoc($r);

		$keys = array_keys($a);

		// build table header
		$info .= '<tr>';

		foreach ($keys as $key) {
			$info .= "<th>$key</th>";
		}

		$info .= '</tr>';

		$i = 0;

		while ($a) {
			$style = (($i % 2) == 0) ? ' style="background:#fff"' : '';
			$info .= "<tr $style>";

			foreach ($a as $value) {
				$value = htmlspecialchars($value);
				$info .= "<td>$value</td>";
			}

			$info .= '</tr>';
			$a = $dbc->_db->fetch_assoc($r);
			$i ++;
		}

		// empty?
		$info = ($info == '<tr></tr>') ? _L('none') : $info;
		$navigation = $pagination->construct_page_nav(ORBX_SITE_URL . "/?{$orbicon_x->ptr}=orbicon/mod/sql_db&amp;table=".$_GET['table']);

		return "<div class=\"slq_table_container\"><p><input type=\"button\" value=\""._L('back')."\" onclick=\"redirect('".ORBX_SITE_URL.'/?'.$orbicon_x->ptr."=orbicon/mod/sql_db');\" /></p><p><strong>$table_name</strong></p><table class=\"sql_table\">$info</table></div>$navigation";
	}

?>