<?php



/**
 * Enter description here...
 *
 */
class Project
{
	const TYPE_WORK_ORDER = 1;
	const TYPE_ESTIMATE = 2;
	const TYPE_VALUATION = 4;
	const TYPE_OFFER = 8;

	const ORDER_CONTRACT = 1;
	const ORDER_PHONE = 2;
	const ORDER_EMAIL = 4;
	const ORDER_OTHER = 8;

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
	private $project_properties;

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
	function __get($project_property)
	{
		return $this->project_properties[$project_property];
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $news_property
	 * @param unknown_type $value
	 */
	function __set($project_property, $value)
	{
		$this->project_properties[$project_property] = $value;
	}


	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @param unknown_type $status
	 * @return unknown
	 */
	function getProject($id = null)
	{
		if($id) {
			$this->setId($id);
		}

		$c = sql_assoc('SELECT * FROM work_order WHERE (id=%s) LIMIT 1', $this->getId());

		$this->setId($c['id']);
		$this->wo_log_id = $c['wo_log_id'];
		$this->client_id = $c['client_id'];
		$this->project_name = $c['project_name'];
		$this->order_type = $c['order_type'];
		$this->target_date = $c['target_date'];
		$this->description = $c['description'];
		$this->project_manager = $c['project_manager'];
		$this->status = $c['status'];
		$this->version = $c['version'];
		$this->type = $c['type'];
		$this->added_by = $c['added_by'];
		$this->added_date = $c['added_date'];
		$this->last_edited_by = $c['last_edited_by'];
		$this->last_edited_date = $c['last_edited_date'];

		return $this->getId();
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @return unknown
	 */
	function setProject($id = null)
	{
		if($id) {
			$this->setId($id);
		}

		if(!$this->getId()) {

			return sql_insert('	INSERT INTO 	work_order
												(wo_log_id, client_id,
												project_name, order_type,
												target_date, description,
												project_manager, status,
												version, type,
												added_by, added_date,
												last_edited_by, last_edited_date
												)
								VALUES			(%s, %s,
												%s, %s,
												%s, %s,
												%s, %s,
												1, %s,
												%s, UNIX_TIMESTAMP(),
												%s, UNIX_TIMESTAMP()
												)', array(
												$this->wo_log_id, $this->client_id,
												$this->project_name, $this->order_type,
												$this->target_date, $this->description,
												$this->project_manager, $this->status,
												$this->type, $this->added_by,
												$this->last_edited_by
										)
							);
		}
		else {
			return sql_update('	UPDATE 	work_order
								SET 	wo_log_id=%s, client_id=%s,
										project_name=%s, order_type=%s,
										target_date=%s, description=%s,
										project_manager=%s, status=%s,
										version=(version + 1), type=%s,
										last_edited_by=%s, last_edited_date=UNIX_TIMESTAMP()
								WHERE 	(id=%s)', array(
										$this->wo_log_id, $this->client_id,
										$this->project_name, $this->order_type,
										$this->target_date, $this->description,
										$this->project_manager, $this->status,
										$this->type, $this->last_edited_by,
										$this->getId())
							);
		}
	}

	function getProjects($limit = '')
	{
		if($limit) {
			$sql_limit = ' LIMIT ' . $limit;
		}

		return sql_res('SELECT * FROM work_order ORDER BY target_date ASC ' . $sql_limit);
	}


	function getOldProjects($id = null, $limit = '')
	{
		if($id) {
			$this->setId($id);
		}

		if($limit) {
			$sql_limit = ' LIMIT ' . $limit;
		}

		return sql_res('SELECT * FROM work_order_old WHERE (wo_id=%s) ORDER BY target_date ASC ' . $sql_limit, $this->getId());
	}

	function getOldProject($ver, $id = null)
	{
		if($id) {
			$this->setId($id);
		}

		$c = sql_assoc('SELECT *, UNCOMPRESS(description) AS description  FROM work_order_old WHERE (wo_id=%s) AND (version=%s) LIMIT 1', array($this->getId(), $ver));

		$this->setId($c['id']);
		$this->wo_log_id = $c['wo_log_id'];
		$this->client_id = $c['client_id'];
		$this->project_name = $c['project_name'];
		$this->order_type = $c['order_type'];
		$this->target_date = $c['target_date'];
		$this->description = $c['description'];
		$this->project_manager = $c['project_manager'];
		$this->status = $c['status'];
		$this->version = $c['version'];
		$this->type = $c['type'];
		$this->added_by = $c['added_by'];
		$this->added_date = $c['added_date'];
		$this->last_edited_by = $c['last_edited_by'];
		$this->last_edited_date = $c['last_edited_date'];
		$this->wo_id = $c['wo_id'];

		return $this->getId();
	}

	function archive($id = null)
	{
		if($id) {
			$this->setId($id);
		}

		$oldid = sql_insert('INSERT INTO work_order_old (wo_log_id, client_id, project_name, order_type, target_date, description, project_manager, status, version, type, added_by, added_date, last_edited_by, last_edited_date)
		SELECT wo_log_id, client_id, project_name, order_type, target_date, COMPRESS(description), project_manager, status, version, type, added_by, added_date, last_edited_by, last_edited_date FROM work_order WHERE (id = %s)', $this->getId());
		sql_update('UPDATE work_order_old SET wo_id=%s WHERE (id=%s)', array($this->getId(), $oldid));

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

		// delete old versions
		sql_res('DELETE FROM work_order_old WHERE (wo_id = %s)', $this->getId());
		// delete todos
		sql_res('DELETE FROM work_order_todo WHERE (wo_id = %s)', $this->getId());
		//delete old todos
		sql_res('DELETE FROM work_order_todo_old WHERE (wo_id = %s)', $this->getId());
		//delete latest version
		return sql_res('DELETE FROM work_order WHERE (id = %s)', $this->getId());
	}

	function getHighestVersion($id)
	{
		$v = sql_assoc('SELECT version FROM work_order WHERE (id=%s) LIMIT 1', $id);
		return $v['version'];
	}
}

?>