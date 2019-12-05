<?php

	define('TABLE_MOD_CREDIT_CALC', 'orbx_mod_credit_calc');

	function save_credit()
	{
		global $dbc, $orbicon_x;

		$interest = str_replace(',', '.', $_POST['interest']);
		$title = $_POST['credit_title'];
		$max_years = intval($_POST['max_years']);

		if(isset($_GET['edit'])) {
			$q = sprintf('	UPDATE 		'.TABLE_MOD_CREDIT_CALC.'
							SET			title = %s, interest = %s,
										max_years = %s
							WHERE 		(id = %s) AND
										(language = %s)',
							$dbc->_db->quote($title), $dbc->_db->quote($interest),
							$dbc->_db->quote($max_years),
							$dbc->_db->quote($_GET['edit']), $dbc->_db->quote($orbicon_x->ptr));
			$id = $_GET['edit'];
		}
		else {
			$q = sprintf('	INSERT
							INTO 		'.TABLE_MOD_CREDIT_CALC.' (
										title, interest, max_years,
										language
							) VALUES (	%s, %s,
										%s, %s
							)',
						$dbc->_db->quote($title), $dbc->_db->quote($interest),
						$dbc->_db->quote($max_years), $dbc->_db->quote($orbicon_x->ptr));

			$id = $dbc->_db->insert_id();
		}

		$dbc->_db->query($q);

		redirect(ORBX_SITE_URL . '/?'. $orbicon_x->ptr . '=orbicon/mod/credit-calculator&edit=' . $id);
	}

	function load_credit($id)
	{
		global $dbc, $orbicon_x;
		$q = sprintf('	SELECT 		*
						FROM		'.TABLE_MOD_CREDIT_CALC.'
						WHERE 		(id = %s) AND
									(language = %s)
						LIMIT 		1', $dbc->_db->quote($id), $dbc->_db->quote($orbicon_x->ptr));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_array($r);

		if(empty($a)) {
			return null;
		}

		return $a;
	}

	function delete_credit()
	{
		if(isset($_GET['delete'])) {
			global $dbc, $orbicon_x;
			$q = sprintf('	DELETE
							FROM		'.TABLE_MOD_CREDIT_CALC.'
							WHERE 		(id = %s) AND
										(language = %s)
							LIMIT 		1', $dbc->_db->quote($_GET['delete']), $dbc->_db->quote($orbicon_x->ptr));

			$r = $dbc->_db->query($q);

			redirect(ORBX_SITE_URL . '/?'. $orbicon_x->ptr . '=orbicon/mod/credit-calculator');
		}
	}

	function print_credit_list()
	{
		global $dbc, $orbicon_x;
		$q = sprintf('	SELECT 		*
						FROM		'.TABLE_MOD_CREDIT_CALC.'
						WHERE 		(language = %s)
						ORDER BY 	title', $dbc->_db->quote($orbicon_x->ptr));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_array($r);

		if(empty($a)) {
			return null;
		}

		$table = '
		<table>
			<tr>
				<th><strong>#</strong></th>
				<th><strong>'._L('credit_title').'</strong></th>
				<th><strong>'._L('interest_rate').'</strong></th>
				<th><strong>'._L('max_years').'</strong></th>
				<th><strong>'._L('edit').'</strong></th>
				<th><strong>'._L('delete').'</strong></th>
			</tr>';

		$i = 1;

		while($a) {

			$style = (($i % 2) == 0) ? ' style="background:#eeeeee;"' : '';
			$max_years = (empty($a['max_years'])) ? 'N/A' : $a['max_years'];

			$table .= "<tr $style>
			<td>$i.</td>
			<td>{$a['title']}</td>
			<td>{$a['interest']}%</td>
			<td>".$max_years.'</td>
			<td><a href="' . ORBX_SITE_URL . '/?'. $orbicon_x->ptr . '=orbicon/mod/credit-calculator&edit=' . $a['id'] . '">'._L('edit').'</a></td>
			<td><a href="' . ORBX_SITE_URL . '/?'. $orbicon_x->ptr . '=orbicon/mod/credit-calculator&delete=' . $a['id'] . '">'._L('delete').'</a></td>
			</tr>';

			$a = $dbc->_db->fetch_array($r);
			$i ++;
		}

		$table .= '</table>';

		echo $table;
	}

	function get_credit_menu()
	{
		global $dbc, $orbicon_x;

		$q = sprintf('	SELECT 		*
						FROM		'.TABLE_MOD_CREDIT_CALC.'
						WHERE 		(language = %s)
						ORDER BY 	title', $dbc->_db->quote($orbicon_x->ptr));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_array($r);

		if(empty($a)) {
			return null;
		}

		while($a) {
			$menu .= "<option value=\"{$a['interest']}\" title=\"{$a['max_years']}\">{$a['title']}</option>";
			$a = $dbc->_db->fetch_array($r);
		}

		return $menu;
	}

?>