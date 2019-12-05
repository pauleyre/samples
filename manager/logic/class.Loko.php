<?php



/**
 * Enter description here...
 *
 */
class Loko
{
	/**
	 * News item id
	 *
	 * @var int
	 */
	private $id;

	/**
	 * News properties (title, text, etc.) Names must equal column names in database
	 *
	 * @var array
	 */
	private $loko_properties;

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 */
	function __construct($id = null)
	{
		if($id) {
			$this->setId(intval($id));
		}
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $question_property
	 * @return unknown
	 */
	function __get($loko_property)
	{
		return $this->loko_properties[$loko_property];
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $news_property
	 * @param unknown_type $value
	 */
	function __set($loko_property, $value)
	{
		$this->loko_properties[$loko_property] = $value;
	}


	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @param unknown_type $status
	 * @return unknown
	 */
	function getLoko($id = null)
	{
		if($id) {
			$this->setId(intval($id));
		}

		$e = sql_assoc('SELECT * FROM loko WHERE (id=%s) LIMIT 1', $this->getId());

		$this->setId($e['id']);
		$this->loko_date = $e['loko_date'];
		$this->loko_destination = $e['loko_destination'];
		$this->loko_purpose = $e['loko_purpose'];
		$this->loko_vehicle = $e['loko_vehicle'];
		$this->loko_kmh = $e['loko_kmh'];
		$this->employee_id = $e['employee_id'];

		return $this->getId();
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @return unknown
	 */
	function setLoko($id = null)
	{
		if($id) {
			$this->setId(intval($id));
		}

		if(!$this->getId()) {

			return sql_insert('	INSERT INTO 	loko
												(loko_date, loko_destination,
												loko_purpose, loko_vehicle,
												loko_kmh, employee_id
												)
								VALUES			(%s, %s,
												%s, %s,
												%s, %s
												)', array(
										$this->loko_date, $this->loko_destination,
										$this->loko_purpose, $this->loko_vehicle,
										$this->loko_kmh, $this->employee_id
										)
							);
		}
		else {
			return sql_update('	UPDATE 	loko
								SET 	loko_date=%s, loko_destination=%s,
										loko_purpose=%s, loko_vehicle=%s,
										loko_kmh=%s, employee_id=%s
								WHERE 	(id=%s)', array(
										$this->loko_date, $this->loko_destination,
										$this->loko_purpose, $this->loko_vehicle,
										$this->loko_kmh, $this->employee_id,
										$this->getId())
							);
		}
	}

	function getLokos($employee_id = null, $month = null, $limit = '')
	{
		global $db;

		$sql = array();

		if($limit) {
			$sql_limit = ' LIMIT ' . $limit;
		}

		if($employee_id) {
			$sql[] = ' (employee_id = '.$db->quote($employee_id).')';
		}

		if($month) {
			$sql[] = ' (loko_date >= '.$db->quote($month).') AND (loko_date <= '.$db->quote($month).')';
		}

		if($sql) {
			$sql = implode(' AND ', $sql);
			$sql = " WHERE $sql";
		}

		return sql_res('SELECT * FROM loko '.$sql.' ORDER BY loko_date ASC ' . $sql_limit);
	}

	function delete($id = null)
	{
		if($id) {
			$this->setId($id);
		}

		return sql_res('DELETE FROM loko WHERE (id = %s)', $this->getId());
	}

	function getId()
	{
		return $this->id;
	}

	function setId($id)
	{
		$this->id = intval($id);
	}
}

?>