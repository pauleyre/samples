<?php



/**
 * Enter description here...
 *
 */
class Todo
{

	const STATUS_OPEN = 1;
	const STATUS_CLOSED = 2;

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
	private $todo_properties;

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 */
	function __construct($id = null)
	{
		if($id) {
			$this->setId($id);
		}
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $question_property
	 * @return unknown
	 */
	function __get($todo_property)
	{
		return $this->todo_properties[$todo_property];
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $news_property
	 * @param unknown_type $value
	 */
	function __set($todo_property, $value)
	{
		$this->todo_properties[$todo_property] = $value;
	}


	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @param unknown_type $status
	 * @return unknown
	 */
	function getTodo($id = null)
	{
		if($id) {
			$this->setId($id);
		}

		$c = sql_assoc('SELECT * FROM work_order_todo WHERE (id=%s) LIMIT 1', $this->getId());

		$this->setId($c['id']);
		$this->wo_id = $c['wo_id'];
		$this->employee_id = $c['employee_id'];
		$this->description = $c['description'];
		$this->started = $c['started'];
		$this->finished = $c['finished'];
		$this->total_hours = $c['total_hours'];
		$this->status = $c['status'];
		$this->comment = $c['comment'];
		$this->target_date_start = $c['target_date_start'];
		$this->target_date_end = $c['target_date_end'];

		return $this->getId();
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @return unknown
	 */
	function setTodo($id = null)
	{
		if($id) {
			$this->setId($id);
		}

		if(!$this->getId()) {

			return sql_insert('	INSERT INTO 	work_order_todo
												(wo_id, employee_id,
												description, status,
												comment, target_date_start,
												target_date_end
												)
								VALUES			(%s, %s,
												%s, %s,
												%s, %s,
												%s
												)', array(
										$this->wo_id, $this->employee_id,
										$this->description, $this->status,
										$this->comment, $this->target_date_start,
										$this->target_date_end
										)
							);
		}
		else {
			return sql_update('	UPDATE 	work_order_todo
								SET 	wo_id=%s, employee_id=%s,
										description=%s, started=%s,
										finished=%s, total_hours=%s,
										status=%s, comment=%s,
										target_date_start=%s, target_date_end=%s
								WHERE 	(id=%s)', array(
										$this->wo_id, $this->employee_id,
										$this->description, $this->started,
										$this->finished, $this->total_hours,
										$this->status, $this->comment,
										$this->target_date_start, $this->target_date_end,
										$this->getId())
							);
		}
	}

	function getTodos($wo_id, $limit = '')
	{
		global $db;

		if($limit) {
			$sql_limit = ' LIMIT ' . $limit;
		}

		return sql_res('SELECT * FROM work_order_todo WHERE (wo_id=%s) '.$sql.' ORDER BY target_date_end ASC ' . $sql_limit, $wo_id);
	}

	function getOldTodos($wo_id, $version, $limit = '')
	{
		if($limit) {
			$sql_limit = ' LIMIT ' . $limit;
		}

		return sql_res('SELECT *, UNCOMPRESS(description) AS description, UNCOMPRESS(comment) AS comment FROM work_order_todo_old WHERE (wo_id=%s) AND (version=%s) ORDER BY target_date_end ASC ' . $sql_limit, array($wo_id, $version));
	}

	function getEmployeeTodos($employee_id, $status = -1, $limit = '')
	{
		global $db;

		if($limit) {
			$sql_limit = ' LIMIT ' . $limit;
		}

		if($status != -1) {
			$sql = 'AND (status = '.$db->quote($status).')';
		}

		return sql_res('SELECT * FROM work_order_todo WHERE (employee_id=%s) AND (target_date_start <= UNIX_TIMESTAMP()) '.$sql.' ORDER BY target_date_end ASC, wo_id DESC ' . $sql_limit, $employee_id);
	}

	function getOldTodo($ver, $id = null)
	{
		if($id) {
			$this->setId($id);
		}

		$c = sql_assoc('SELECT *, UNCOMPRESS(description) AS description, UNCOMPRESS(comment) AS comment FROM work_order_todo_old WHERE (id=%s) AND (version=%s) LIMIT 1', $this->getId());

		$this->setId($c['id']);
		$this->wo_id = $c['wo_id'];
		$this->employee_id = $c['employee_id'];
		$this->description = $c['description'];
		$this->started = $c['started'];
		$this->finished = $c['finished'];
		$this->total_hours = $c['total_hours'];
		$this->status = $c['status'];
		$this->comment = $c['comment'];
		$this->target_date_start = $c['target_date_start'];
		$this->target_date_end = $c['target_date_end'];
		$this->version = $c['version'];

		return $this->getId();
	}

	function archive($ver, $id = null)
	{
		if($id) {
			$this->setId($id);
		}

		$oldid = sql_insert('INSERT INTO work_order_todo_old (wo_id, employee_id, description, started, finished, total_hours, status, comment, target_date_start, target_date_end)
		SELECT wo_id, employee_id, COMPRESS(description), started, finished, total_hours, status, COMPRESS(comment), target_date_start, target_date_end FROM work_order_todo WHERE (id = %s)', $this->getId());
		sql_update('UPDATE work_order_todo_old SET version=%s WHERE (id=%s)', array($ver, $oldid));

		return $oldid;
	}

	function getId()
	{
		return $this->id;
	}

	function setId($id)
	{
		$this->id = intval($id);
	}

	function delete($id = null)
	{
		if($id) {
			$this->setId($id);
		}

		return sql_res('DELETE FROM work_order_todo WHERE (id = %s)', $this->getId());
	}
}

?>