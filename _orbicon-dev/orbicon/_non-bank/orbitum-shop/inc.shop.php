<?php

	require_once DOC_ROOT . '/orbicon/modules/orbitum-pickup-depot/inc.depot.php'; 

	function submit_order($user)
	{
		global $dbc;

		$products = $_SESSION['shopping_cart'] . '*1';

		$q = sprintf('		INSERT INTO 	'.ORBX_MOD_TABLE_DEPOT.' (
											user_id, products, 
											purchased) VALUES (
											%s, %s, 
											UNIX_TIMESTAMP()
							)', $dbc->_db->quote($user), $dbc->_db->quote($products));
		
		$dbc->_db->query($q);
		
		return $dbc->_db->insert_id();
	}

	function finish_order($id, $redirect_to = NULL)
	{
		global $dbc;

		$q = sprintf('		UPDATE 			'.ORBX_MOD_TABLE_DEPOT.' 
							SET 			finished = 1, domain = %s
							WHERE 			(id = %s)
							LIMIT 			1', 
							$dbc->_db->quote($id), $dbc->_db->quote($_REQUEST['description']));
	
		$dbc->_db->query($q);

		if(!empty($redirect_to)) {
			redirect($redirect_to);
		}
	}
	
	function get_order_menu()
	{
		global $dbc;
		$q = '	SELECT 		* 
				FROM 		'.ORBX_MOD_TABLE_PRODUCTS.'
				WHERE		(price > 0)';

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_array($r);
	
		while($a) {
			$menu .= '<option value="'.$a['id'].'">'.$a['product'].'</option>';
			$a = $dbc->_db->fetch_array($r);
		}
		
		return $menu;
	}
?>