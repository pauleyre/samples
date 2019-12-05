<?php



/**
 * Enter description here...
 *
 */
class Absence
{
	const TYPE_SICK_LEAVE = 1;
	const TYPE_VACATION = 2;
	const TYPE_BUSSINES_TRIP = 4;
	const TYPE_FREE_DAY = 8;
	const TYPE_PRIVATE = 16;

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
	private $absence_properties;

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
	function __get($absence_property)
	{
		return $this->absence_properties[$absence_property];
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $news_property
	 * @param unknown_type $value
	 */
	function __set($absence_property, $value)
	{
		$this->absence_properties[$absence_property] = $value;
	}


	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @param unknown_type $status
	 * @return unknown
	 */
	function getAbsence($id = null)
	{
		if($id) {
			$this->setId(intval($id));
		}

		$e = sql_assoc('SELECT * FROM absence WHERE (id=%s) LIMIT 1', $this->getId());

		$this->setId($e['id']);
		$this->from = $e['from'];
		$this->to = $e['to'];
		$this->reason = $e['reason'];
		$this->comment = $e['comment'];
		$this->employee_id = $e['employee_id'];

		return $this->getId();
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @return unknown
	 */
	function setAbsence($id = null)
	{
		if($id) {
			$this->setId(intval($id));
		}

		if(!$this->getId()) {

			return sql_insert('	INSERT INTO 	absence
												(from, to,
												reason, comment,
												employee_id
												)
								VALUES			(%s, %s,
												%s, %s,
												%s
												)', array(
										$this->from, $this->to,
										$this->reason, $this->comment,
										$this->employee_id
										)
							);
		}
		else {
			return sql_update('	UPDATE 	absence
								SET 	from=%s, to=%s,
										reason=%s, comment=%s,
										employee_id=%s
								WHERE 	(id=%s)', array(
										$this->from, $this->to,
										$this->reason, $this->comment,
										$this->employee_id,
										$this->getId())
							);
		}
	}

	function getAbsences($employee_id = null, $month = null, $limit = '')
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

		return sql_res('SELECT * FROM absence '.$sql.' ORDER BY from ASC ' . $sql_limit);
	}

	function delete($id = null)
	{
		if($id) {
			$this->setId($id);
		}

		return sql_res('DELETE FROM absence WHERE (id = %s)', $this->getId());
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