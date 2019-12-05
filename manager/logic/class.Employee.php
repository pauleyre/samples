<?php



/**
 * Enter description here...
 *
 */
class Employee
{
	const ACTIVE = 1;
	const EMPLOYEE = 2;
	const ADMIN = 4;
	const EXTERNAL = 8;

	const USE_JAVA_UPLOADER = 16;

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
	private $employee_properties;

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
	function __get($employee_property)
	{
		return $this->employee_properties[$employee_property];
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $news_property
	 * @param unknown_type $value
	 */
	function __set($employee_property, $value)
	{
		$this->employee_properties[$employee_property] = $value;
	}


	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @param unknown_type $status
	 * @return unknown
	 */
	function getEmployee($id = null, $flag = null)
	{
		if($id) {
			$this->setId(intval($id));
		}

		if($flag) {
			$sql_flag = ' AND (flags & '.intval($flag).') ';
		}

		$e = sql_assoc('SELECT * FROM employee WHERE (id=%s) '.$sql_flag.' LIMIT 1', $this->getId());

		$this->setId($e['id']);
		$this->password = $e['password'];
		$this->first_name = $e['first_name'];
		$this->last_name = $e['last_name'];
		$this->email = $e['email'];
		$this->occupation = $e['occupation'];
		$this->comment = $e['comment'];
		$this->pay = $e['pay'];
		$this->work_start = $e['work_start'];
		$this->work_end = $e['work_end'];
		$this->flags = $e['flags'];
		$this->sector = $e['sector'];
		$this->phone = $e['phone'];
		$this->fax = $e['fax'];
		$this->mobile = $e['mobile'];

		return $this->getId();
	}

	function loadIntoSession($varname)
	{
		foreach ($this->employee_properties as $prop_name => $prop_value) {
			$_SESSION[$varname][$prop_name] = $prop_value;
		}
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @return unknown
	 */
	function setEmployee($id = null)
	{
		if($id) {
			$this->setId(intval($id));
		}

		if(!$this->getId()) {

			return sql_insert('	INSERT INTO 	employee
												(password, first_name,
												last_name, email,
												occupation, comment,
												pay, work_start,
												work_end, flags,
												sector, phone,
												fax, mobile
												)
								VALUES			(PASSWORD(%s), %s,
												%s, %s,
												%s, %s,
												%s, %s,
												%s, %s,
												%s, %s,
												%s, %s
												)', array(
										$this->password, $this->first_name,
										$this->last_name, $this->email,
										$this->occupation, $this->comment,
										$this->pay, $this->work_start,
										$this->work_end, $this->flags,
										$this->sector, $this->phone,
										$this->fax, $this->mobile
										)
							);
		}
		else {
			return sql_update('	UPDATE 	employee
								SET 	password=%s, first_name=%s,
										last_name=%s, email=%s,
										occupation=%s, comment=%s,
										pay=%s, work_start=%s,
										work_end=%s, flags=%s,
										sector=%s, phone=%s,
										fax=%s, mobile=%s
								WHERE 	(id=%s)', array(
										$this->password, $this->first_name,
										$this->last_name, $this->email,
										$this->occupation, $this->comment,
										$this->pay, $this->work_start,
										$this->work_end, $this->flags,
										$this->sector, $this->phone,
										$this->fax, $this->mobile,
										$this->getId())
							);
		}
	}

	function getEmployees($wo_id = null, $limit = '')
	{
		global $db;

		if($limit) {
			$sql_limit = ' LIMIT ' . $limit;
		}

		if($wo_id) {
			$sql = ' WHERE id IN (SELECT employee_id FROM work_order_todo WHERE wo_id = '.$db->quote($wo_id).')';
		}

		return sql_res('SELECT * FROM employee '.$sql.' ORDER BY last_name, first_name ASC ' . $sql_limit);
	}

	function login($password)
	{
		$m = sql_assoc('SELECT id FROM employee WHERE (password = PASSWORD(%s)) AND (flags & %s) LIMIT 1', array($password, self::ACTIVE));
		return $m['id'];
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