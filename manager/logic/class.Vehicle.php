<?php



/**
 * Enter description here...
 *
 */
class Vehicle
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
	private $vehicle_properties;

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
	function __get($vehicle_property)
	{
		return $this->vehicle_properties[$vehicle_property];
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $news_property
	 * @param unknown_type $value
	 */
	function __set($vehicle_property, $value)
	{
		$this->vehicle_properties[$vehicle_property] = $value;
	}


	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @param unknown_type $status
	 * @return unknown
	 */
	function getVehicle($id = null)
	{
		if($id) {
			$this->setId(intval($id));
		}

		$e = sql_assoc('SELECT * FROM vehicle WHERE (id=%s) LIMIT 1', $this->getId());

		$this->setId($e['id']);
		$this->vehicle = $e['vehicle'];

		return $this->getId();
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @return unknown
	 */
	function setVehicle($id = null)
	{
		if($id) {
			$this->setId(intval($id));
		}

		if(!$this->getId()) {

			return sql_insert('INSERT INTO vehicle (vehicle) VALUES (%s)', $this->vehicle);
		}
		else {
			return sql_update('UPDATE vehicle SET vehicle=%s WHERE (id=%s)', array($this->vehicle, $this->getId()));
		}
	}

	function getVehicles($limit = '')
	{
		global $db;

		$sql = array();

		if($limit) {
			$sql_limit = ' LIMIT ' . $limit;
		}

		return sql_res('SELECT * FROM vehicle ORDER BY vehicle ASC ' . $sql_limit);
	}

	function delete($id = null)
	{
		if($id) {
			$this->setId($id);
		}

		return sql_res('DELETE FROM vehicle WHERE (id = %s)', $this->getId());
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