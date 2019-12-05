<?php
/**
 * Class for private mailer contacts
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package SystemMOD
 * @subpackage PeopleRing
 * @version 1.00
 * @link http://
 * @license http://
 * @since 2007-09-10
 */

require_once DOC_ROOT . '/orbicon/class/group/class.group.php';

define('TABLE_PR_USER_CONTACTS', 'pring_user_contacts');
define('TABLE_PR_USER_ACTIONS', 'pring_user_actions');

class User_Contacts
{
	/**
	 * contacts container
	 *
	 * @var object
	 */
	var $contacts;

	/**
	 * user's ID
	 *
	 * @var int
	 */
	var $user_id;

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $user_id
	 */
	function __construct($user_id)
	{
		if(!is_int($user_id)) {
			trigger_error('User_Contacts() expects parameter 1 to be integer, '.gettype($user_id).' given', E_USER_NOTICE);
		}

		// setup properties
		$this->user_id = $user_id;
		$this->contacts = new Group();

		// load contacts
		$this->load();
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $user_id
	 * @return User_Contacts
	 */
	function User_Contacts($user_id)
	{
		$this->__construct($user_id);
	}

	/**
	 * Save contacts
	 *
	 * @return bool
	 */
	function save()
	{
		global $dbc;

		$this->contacts->unique();

		$q = sprintf('	UPDATE 		'.TABLE_PR_USER_CONTACTS.'
						SET 		contacts = %s
						WHERE		(owner_id = %s)',
						$dbc->_db->quote($this->contacts->serialize()),
						$dbc->_db->quote($this->user_id)
					);

		$r = $dbc->_db->query($q);

		if($dbc->_db->affected_rows()) {
			return true;
		}

		// check if update was ok
		$q_c = sprintf('	SELECT 	contacts
							FROM 	'.TABLE_PR_USER_CONTACTS.'
							WHERE 	(owner_id = %s)
							LIMIT 	1', $dbc->_db->quote($this->user_id));
		$r_c = $dbc->_db->query($q_c);
		$a_c = $dbc->_db->fetch_assoc($r_c);

		// UPDATE failed, try with INSERT
		if($a_c['value'] === null) {
			$q_new = sprintf('	INSERT INTO 	'.TABLE_PR_USER_CONTACTS.'
												(contacts, owner_id)
								VALUES 			(%s, %s)',
											$dbc->_db->quote($this->contacts->serialize()), $dbc->_db->quote($this->user_id));

			$r = $dbc->_db->query($q_new);
		}

		if($dbc->_db->insert_id()) {
			return true;
		}

		trigger_error('Failed to save contact list, could not write to database', E_USER_WARNING);
		return false;
	}

	/**
	 * Load all contacts
	 *
	 */
	function load()
	{
		global $dbc;

		$q = sprintf('	SELECT 		contacts
						FROM 		'.TABLE_PR_USER_CONTACTS.'
						WHERE 		(owner_id = %s)
						LIMIT 		1',
						$dbc->_db->quote($this->user_id)
						);

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		$this->contacts->merge(explode(',', $a['contacts']));
		$this->contacts->remove_empty();
	}

	/**
	 * Add contact
	 *
	 * @param string $contact
	 */
	function add($contact)
	{
		if(!is_string($contact)) {
			trigger_error('add() expects parameter 1 to be string, '.gettype($contact).' given', E_USER_NOTICE);
		}

		$this->contacts->merge($contact);
	}

	/**
	 * Delete contact
	 *
	 */
	function delete($contact)
	{
		$this->contacts->remove($contact);
	}

	/**
	 * Filter out contacts without needle
	 *
	 * @param string $needle
	 */
	function filter($needle)
	{
		$remove = array();

		// group unwanted members into a new array
		foreach ($this->contacts->members as $member) {
			if(strpos(strtolower($member), strtolower($needle)) === false) {
				$remove[] = $member;
			}
		}

		// remove them
		$this->contacts->remove($remove);
	}
}

?>