<?php



/**
 * Enter description here...
 *
 */
class Member
{
	const ACTIVE = 1;
	//const BANNED = 2;
	const ADMIN = 4;
	const EXPERT = 8;

	/**
	 * News item id
	 *
	 * @var int
	 */
	private $id;

	/**
	 * Statistic counter
	 *
	 * @var int
	 */
	private static $invocation = 0;

	/**
	 * News properties (title, text, etc.) Names must equal column names in database
	 *
	 * @var array
	 */
	private $member_properties;

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 */
	function __construct($id = null)
	{
		if($id) {
			$this->id = intval($id);
		}

		$this->invocation ++;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $question_property
	 * @return unknown
	 */
	function __get($member_property)
	{
		return $this->member_properties[$member_property];
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $news_property
	 * @param unknown_type $value
	 */
	function __set($member_property, $value)
	{
		$this->member_properties[$member_property] = $value;
	}


	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @param unknown_type $status
	 * @return unknown
	 */
	function getMember($id = null)
	{
		if($id) {
			$this->id = intval($id);
		}

		$m = sql_assoc('SELECT * FROM member WHERE (id=%s) AND (flags & %s) LIMIT 1', array($this->id, self::ACTIVE));

		$this->id = $m['id'];
		$this->name = $m['name'];
		$this->password = $m['password'];
		$this->email = $m['email'];
		$this->flags = (int) $m['flags'];
		$this->ip = $m['ip'];
		$this->joined = $m['joined'];

		return $this->id;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @return unknown
	 */
	function setMember($id = null)
	{
		if($id) {
			$this->id = intval($id);
		}

		if(!$this->id) {

			return sql_insert('	INSERT INTO 	member
												(name, password,
												email, flags,
												ip, joined
												)
								VALUES			(%s, PASSWORD(%s),
												%s, %s,
												%s, UNIX_TIMESTAMP()
												)', array(
										$this->name, $this->password,
										$this->email, $this->flags,
										$_SERVER['REMOTE_ADDR']
										)
							);
		}
		else {
			return sql_update('	UPDATE 	member
								SET 	name=%s, password=PASSWORD(%s),
										email=%s, flags=%s,
										ip=%s
								WHERE 	(id=%s)', array(
										$this->name, $this->password,
										$this->email, $this->flags,
										$_SERVER['REMOTE_ADDR'],
										$this->id)
							);
		}
	}

	function login($email, $password)
	{
		$m = sql_assoc('SELECT id FROM member WHERE (email=%s) AND (password = PASSWORD(%s)) LIMIT 1', array($email, $password));
		return $m['id'];
	}

	function updateIP()
	{
		return sql_update('	UPDATE 	member
							SET 	ip=%s
							WHERE 	(id=%s)', array(
									$_SERVER['REMOTE_ADDR'],
									$this->id)
						);
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