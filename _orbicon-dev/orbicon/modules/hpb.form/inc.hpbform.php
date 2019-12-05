<?php

	function xmlpdf_header()
	{
		header('Content-Type: application/vnd.adobe.xdp+xml', true);
	}

	/**
	 * Log new form filling
	 *
	 * @author pgardijan
	 * @param string $form
	 * @param int $form_id
	 * @return int
	 */
	function new_hpbform($form, $form_id)
	{
		/*if($_SESSION['user.r']['id']) {*/
			$q = '	INSERT INTO 	'.TABLE_HPB_FORMS.'
									(owner_id, form_id,
									form, form_date)
					VALUES			(%s, %s,
									%s, UNIX_TIMESTAMP())';
			return sql_insert($q, array(
										$_SESSION['user.r']['id'], $form_id,
										$form));
		/*}

		return false;*/
	}

	/**
	 * Enter description here...
	 *
	 * @param string $form
	 * @param int $form_id
	 * @return array
	 */
	function get_opunomocenik($id)
	{
		$q = '	SELECT 	*
				FROM 	' . TABLE_OPUNOMOCENIK .'
				WHERE 	(id = %s)
				LIMIT 	1';
		return sql_assoc($q, $id);
	}

	/**
	 * Enter description here...
	 *
	 * @param string $form
	 * @param int $form_id
	 * @param string $name
	 * @param string $surname
	 * @param string $mbg
	 * @param string $address
	 * @return int
	 */
	function new_opunomocenik($form, $form_id, $name, $surname, $mbg, $address, $zip, $town)
	{
		$q = '	INSERT INTO '.TABLE_OPUNOMOCENIK.'
							(contact_name, contact_surname,
							mbg, contact_address,
							form, form_id,
							contact_city, contact_zip)
				VALUES		(%s, %s,
							%s, %s,
							%s, %s,
							%s, %s)';

		return sql_insert($q, array(
								$name, $surname,
								$mbg, $address,
								$form, $form_id,
								$town, $zip));
	}

	function update_opunomocenik_formid($op_id, $form_id)
	{
		sql_update('	UPDATE 		'.TABLE_OPUNOMOCENIK.'
						SET			form_id=%s
						WHERE 		(id=%s)', array($form_id, $op_id));
	}

	function split_pdftextarea($string)
	{
		/*if(strlen($string) > 59) {
			return preg_split('//u', $string, 59,  PREG_SPLIT_NO_EMPTY);
		}
		return array($string);*/

		$string = wordwrap($string, 59, "\n");
		return explode("\n", $string, 3);
	}

	function form_replace_zero($el)
	{
		if(empty($el)) {
			return '';
		}

		return $el;
	}

?>