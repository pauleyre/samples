<?php

	define('ORBX_MOD_TABLE_DEPOT', 'orbx_mod_orbitum_pickup_depot');
	define('ORBX_MOD_TABLE_PRODUCTS', 'orbx_mod_orbitum_products');

	function get_product($id)
	{
		global $dbc;
		$r = $dbc->_db->query(sprintf('
						SELECT 		*
						FROM 		'.ORBX_MOD_TABLE_PRODUCTS.'
						WHERE 		(id = %s)
						LIMIT 		1', $dbc->_db->quote($id)));
		$a = $dbc->_db->fetch_array($r);
		return $a;
	}

	function get_orbitum_products()
	{
		global $dbc;
		$q = sprintf('	SELECT 		*
						FROM 		'.ORBX_MOD_TABLE_DEPOT.'
						WHERE		(user_id = %s) AND
									(finished = 1)', $dbc->_db->quote($_SESSION['user.r']['id']));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_array($r);

		while($a) {

			$products = explode('|', $a['products']);

			foreach($products as $product) {

				$product = explode('*', $product);
				$name = get_product($product[0]);

				$valid = empty($a['valid_until']) ? 'Perpetual' : date('m-d-Y', $a['valid_until']);

				$license_url = empty($a['license']) ? 'window.alert(\'License not available at this time. If you have not received your license 48 hours after purchase, please contact Orbitum\'s Support\')' : ORBX_SITE_URL . '/site/mercury/' . $a['license'];

				$list .= '
					<tr>
						<td align="left"><strong>' . $name['product'] . '</strong></td>
						<td align="center">' . date('m-d-Y', $a['purchased']) . '</td>
						<td align="center">' . $valid . '</td>
						<td align="center">' . $a['id'] . '</td>
						<td align="center">' . $product[1] . '</td>
						<td align="center"><a href="' . ORBX_SITE_URL . '/site/mercury/' . $name['package'] . '"><strong>Download</strong></a> | <a href="' . $license_url . '"><strong>License</strong></a></td>
					</tr>
					<tr>
						<td colspan="6" bgcolor="#dddddd" height="4">&nbsp;</td>
					</tr>';
			}

			$a = $dbc->_db->fetch_array($r);
		}

		return $list;
	}

?>