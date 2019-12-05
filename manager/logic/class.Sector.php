<?php



/**
 * Enter description here...
 *
 */
class Sector
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
	private $sector_properties;

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
	function __get($sector_property)
	{
		return $this->sector_properties[$sector_property];
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $news_property
	 * @param unknown_type $value
	 */
	function __set($sector_property, $value)
	{
		$this->sector_properties[$sector_property] = $value;
	}


	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @param unknown_type $status
	 * @return unknown
	 */
	function getSector($id = null)
	{
		if($id) {
			$this->setId(intval($id));
		}

		$e = sql_assoc('SELECT * FROM sector WHERE (id=%s) LIMIT 1', $this->getId());

		$this->setId($e['id']);
		$this->vehicle = $e['sector'];

		return $this->getId();
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @return unknown
	 */
	function setSector($id = null)
	{
		if($id) {
			$this->setId(intval($id));
		}

		if(!$this->getId()) {

			return sql_insert('INSERT INTO sector (sector) VALUES (%s)', $this->sector);
		}
		else {
			return sql_update('UPDATE sector SET sector=%s WHERE (id=%s)', array($this->sector, $this->getId()));
		}
	}

	function getSectors($limit = '')
	{
		global $db;

		$sql = array();

		if($limit) {
			$sql_limit = ' LIMIT ' . $limit;
		}

		return sql_res('SELECT * FROM sector ORDER BY id ASC ' . $sql_limit);
	}

	function delete($id = null)
	{
		if($id) {
			$this->setId($id);
		}

		return sql_res('DELETE FROM sector WHERE (id = %s)', $this->getId());
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