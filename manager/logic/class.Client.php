<?php



/**
 * Enter description here...
 *
 */
class Client
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
	private $client_properties;

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
	function __get($client_property)
	{
		return $this->client_properties[$client_property];
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $news_property
	 * @param unknown_type $value
	 */
	function __set($client_property, $value)
	{
		$this->client_properties[$client_property] = $value;
	}


	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @param unknown_type $status
	 * @return unknown
	 */
	function getClient($id = null)
	{
		if($id) {
			$this->setId(intval($id));
		}

		$c = sql_assoc('SELECT * FROM client WHERE (id=%s) LIMIT 1', $this->getId());

		$this->setId($c['id']);
		$this->company_name = $c['company_name'];
		$this->mb = $c['mb'];
		$this->contact_person = $c['contact_person'];
		$this->address = $c['address'];
		$this->city = $c['city'];
		$this->zip = $c['zip'];
		$this->country = $c['country'];
		$this->phone = $c['phone'];
		$this->fax = $c['fax'];
		$this->email = $c['email'];
		$this->added_by = $c['added_by'];
		$this->last_edited_by = $c['last_edited_by'];

		return $this->getId();
	}

	function loadIntoSession($varname)
	{
		foreach ($this->client_properties as $prop_name => $prop_value) {
			$_SESSION[$varname][$prop_name] = $prop_value;
		}
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 * @return unknown
	 */
	function setClient($id = null)
	{
		if($id) {
			$this->setId(intval($id));
		}

		if(!$this->getId()) {

			return sql_insert('	INSERT INTO 	client
												(company_name, mb,
												contact_person, address,
												city, zip,
												country, phone,
												fax, email,
												added_by, last_edited_by
												)
								VALUES			(%s, %s,
												%s, %s,
												%s, %s,
												%s, %s,
												%s, %s,
												%s, %s
												)', array(
										$this->company_name, $this->mb,
										$this->contact_person, $this->address,
										$this->city, $this->zip,
										$this->country, $this->phone,
										$this->fax, $this->email,
										$this->added_by, $this->last_edited_by
										)
							);
		}
		else {
			return sql_update('	UPDATE 	client
								SET 	company_name=%s, mb=%s,
										contact_person=%s, address=%s,
										city=%s, zip=%s,
										country=%s, phone=%s,
										fax=%s, email=%s,
										last_edited_by=%s
								WHERE 	(id=%s)', array(
										$this->company_name, $this->mb,
										$this->contact_person, $this->address,
										$this->city, $this->zip,
										$this->country, $this->phone,
										$this->fax, $this->email,
										$this->last_edited_by,
										$this->getId())
							);
		}
	}

	function getClients($limit = '')
	{
		if($limit) {
			$sql_limit = ' LIMIT ' . $limit;
		}

		return sql_res('SELECT * FROM client ORDER BY company_name ASC ' . $sql_limit);
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
			$this->setId(intval($id));
		}

		return sql_res('DELETE FROM client WHERE (id = %s)', $this->getId());
	}
}

?>