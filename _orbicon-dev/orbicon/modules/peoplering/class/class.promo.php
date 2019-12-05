<?php
/**
 * Promo class
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package SystemMOD
 * @subpackage PeopleRing
 * @version 2.0
 * @link http://
 * @license http://
 * @since 2006-11-01
 */
class Promo
{
	/**
	 * Enter description here...
	 *
	 * @var unknown_type
	 */
	var $dbconn;
	/**
	 * Enter description here...
	 *
	 * @var unknown_type
	 */
	var $lang;
	/**
	 * Enter description here...
	 *
	 * @var unknown_type
	 */
	var $data;

	/**
	 * Enter description here...
	 *
	 * @param array $data_array
	 * @return Promo
	 */
	function Promo($data_array = null)
	{
		$this->__construct($data_array);
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $data_array
	 */
	function __construct($data_array = null)
	{
		// * do some setup here
		global $dbc;
		global $orbicon_x;

		$this->lang		=& $orbicon_x->ptr;
		$this->data 	= $data_array;
		$this->dbconn	=& $dbc->_db;
	}

	/**
	 * Enter description here...
	 *
	 */
	function set_promo()
	{
		// * assigns new promo material to user
		$sql = sprintf('INSERT INTO
							pring_promo

							(contact_id,
							title,
							ad,
							textual,
							state)
							VALUES (%s, %s, %s, %s, %s)',
						$this->dbconn->quote($this->data['id']),
						$this->dbconn->quote($this->data['title']),
						$this->dbconn->quote($this->data['ad_banner']),
						$this->dbconn->quote($this->data['content']),
						$this->dbconn->quote($this->data['state']));
		$this->dbconn->query($sql);
	}

	/**
	 * Enter description here...
	 *
	 */
	function update_promo($pr_id)
	{
		// * assigns new promo material to user
		$sql = sprintf('UPDATE 		pring_promo
						SET			title = %s,
									ad = %s,
									textual = %s,
									state = %s
						WHERE		(contact_id = %s)',
						$this->dbconn->quote($this->data['title']),
						$this->dbconn->quote($this->data['ad_banner']),
						$this->dbconn->quote($this->data['content']),
						$this->dbconn->quote($this->data['state']),
						$this->dbconn->quote($pr_id));

		$this->dbconn->query($sql);
	}

	/**
	 * Enter description here...
	 *
	 * @param int $pr_id
	 * @return resource
	 */
	function get_promo($pr_id, $promo_id = null)
	{
		$promo_id = ($promo_id) ? sprintf(' AND (id = %s) LIMIT 1', $this->dbconn->quote($promo_id)) : '';

		// * fetch promo material
		$sql = sprintf('SELECT 	*, UNIX_TIMESTAMP(created) AS created_timestamp
						FROM 	pring_promo
						WHERE 	(contact_id = %s)' . $promo_id,
						$this->dbconn->quote($pr_id));

		return $this->dbconn->query($sql);
	}
}

?>