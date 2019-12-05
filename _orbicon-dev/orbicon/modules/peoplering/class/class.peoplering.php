<?php
/**
 * PeopleRing class
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package SystemMOD
 * @subpackage PeopleRing
 * @version 2.0
 * @link http://
 * @license http://
 * @since 2006-11-01
 */

class PeopleRing
{
	/**
	 * Enter description here...
	 *
	 * @var unknown_type
	 */
	var $dbconn;
	/**
	 * Enter description here...
	 *
	 * @var unknown_type
	 */
	var $lang;
	/**
	 * Enter description here...
	 *
	 * @var unknown_type
	 */
	var $data;

	/**
	 * PHP 4 PeopleRing constructor
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param array $data_array
	 * @return Peoplering
	 */
	function PeopleRing($data_array = null)
	{
		$this->__construct($data_array);
	}

	/**
	 * PeopleRing constructor
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param array $data_array
	 */
	function __construct($data_array = null)
	{
		// * do some setup here
		global $dbc, $orbicon_x;

		$data_array = (is_null($data_array)) ? array() : $data_array;

		$this->lang		=& $orbicon_x->ptr;
		$this->data 	= $data_array;
		$this->dbconn	=& $dbc->_db;
	}

	/**
	 * Search user database
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param bool $limit
	 * @return resource
	 */
	function search_peoplering($limit = true)
	{
		global $orbicon_x;

		$this->data['search_input'] = trim($this->data['search_input']);

		if($this->data['search_input'] != '') {
			$query = $this->dbconn->quote('%' . $this->data['search_input'] . '%');

			// * first build query conditions
			switch($this->data['search_type']) {

				case 'name':
					$condition = sprintf(' AND ((contact_name LIKE %s) OR (contact_surname LIKE %s))', $query, $query);
				break;

				case 'email':
					$condition = sprintf(' AND (contact_email LIKE %s)', $query);
				break;

				case 'city':
					$condition = sprintf(' AND ((contact_city LIKE %s) OR (contact_address LIKE %s))', $query, $query);
				break;

				case 'user_id':
					$condition = sprintf(' AND (id = %s)', $this->dbconn->quote(intval($this->data['search_input'])));
				break;

				case 'company':

				break;

				case 'other':

					switch ($this->data['other_search']) {
						case 'zip': 			$column = 'contact_zip'; break;
						case 'url': 			$column = 'contact_url'; break;
						case 'dob': 			$column = 'contact_dob'; break;
						case 'office': 			$column = 'contact_office'; break;
						case 'position': 		$column = 'contact_position'; break;
						case 'expertise': 		$column = 'contact_expertise'; break;
						case 'fax': 			$column = 'contact_fax'; break;
						case 'gsm': 			$column = 'contact_gsm'; break;
						default: 				$column = 'contact_phone'; break;
					}

					$condition = sprintf(' AND (%s LIKE %s)', $column, $query);
				break;

				case 'username':

					$id = $this->get_id_from_username($this->data['search_input']);
					$contact_id = $this->get_username($id);
					$contact_id = $contact_id['pring_contact_id'];

					$condition = sprintf(' AND (id = %s)', intval($contact_id));
				break;

				case 'user_rid':
					$prid = $this->get_prid_from_rid($this->data['search_input']);
					$condition = sprintf(' AND (id = %s)', intval($prid));
				break;

				default : 		$condition = '';	break;
			}
		}

		// admins can see private profiles
		$private_cond = (_get_is_orbicon_uri()) ? '((private = 0) OR (private = 1))' : '(private = 0)';

		$limit_sql = ($limit) ? ' LIMIT '.$this->dbconn->quote(($_GET['p'] - 1) * $_GET['pp']).', ' . $this->dbconn->quote($_GET['pp']) : '';

		// * build query
		$sql = '	SELECT 		*
					FROM 		pring_contact
					WHERE		'.$private_cond.' '. $condition . '
					ORDER BY 	registered DESC'
					. $limit_sql;

		return $this->dbconn->query($sql);
	}

	/**
	 * Get information about registered user
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param int $id
	 * @return array
	 */
	function get_username($id)
	{
		// * this method retrieves user's username
		$sql = sprintf('SELECT 		*
						FROM 		' . TABLE_REG_USERS . '
						WHERE 		(id = %s)',
						$this->dbconn->quote($id));


		$r = $this->dbconn->query($sql);
		return $this->dbconn->fetch_assoc($r);
	}

	/**
	 * Return profile information
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param int $pr_id
	 * @return array
	 */
	function get_profile($pr_id)
	{
		// * retrieves users profile
		$sql = sprintf('	SELECT 		*
							FROM 		pring_contact
							WHERE 		(id = %s)', $this->dbconn->quote($pr_id));

		$r = $this->dbconn->query($sql);
		return $this->dbconn->fetch_assoc($r);
	}

	/**
	 * Return company information
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param int $id
	 * @return array
	 */
	function get_company($pr_id)
	{
		// * retrieves users profile
		$sql = sprintf('SELECT 		*
						FROM 		pring_company
						WHERE 		(contact = %s)',
						$this->dbconn->quote($pr_id));

		$r = $this->dbconn->query($sql);
		return $this->dbconn->fetch_assoc($r);
	}

	/**
	 * Return CV information
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param int $id
	 * @return array
	 */
	function get_cv($pr_id)
	{
		// * retrieves users cv information
		$sql = sprintf('	SELECT 		*
							FROM 		pring_cvs
							WHERE 		(contact_id = %s)',
						$this->dbconn->quote($pr_id));

		$r = $this->dbconn->query($sql);
		return $this->dbconn->fetch_assoc($r);
	}

	/**
	 * Update profile information
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 */
	function update_profile()
	{
		$dob = strtotime($_POST['dob_m']. '/' . $_POST['dob_d'] . '/' . $_POST['dob_y']);
		// * updates user profile
		$sql = sprintf('
			UPDATE	pring_contact
			SET		contact_name = %s,
					contact_surname = %s,
					contact_dob = %s,
					contact_position = %s,
					contact_office = %s,
					contact_expertise = %s,
					contact_address = %s,
					contact_city = %s,
					contact_zip = %s,
					contact_url = %s,
					contact_email = %s,
					contact_gsm = %s,
					contact_phone = %s,
					contact_fax = %s,
					contact_sex = %s,
					private = %s,
					contact_phone_a = %s,
					contact_phone_b = %s,
					credits = %s
			WHERE	(id = %s)',
			$this->dbconn->quote($this->data['name']), $this->dbconn->quote($this->data['surname']),
			$this->dbconn->quote($dob), $this->dbconn->quote($this->data['position']),
			$this->dbconn->quote($this->data['office']), $this->dbconn->quote($this->data['expertise']),
			$this->dbconn->quote($this->data['address']), $this->dbconn->quote($this->data['city']),
			$this->dbconn->quote($this->data['zip']), $this->dbconn->quote($this->data['url']),
			$this->dbconn->quote($this->data['email']), $this->dbconn->quote($this->data['gsm']),
			$this->dbconn->quote($this->data['phone']), $this->dbconn->quote($this->data['fax']),
			$this->dbconn->quote($this->data['sex']), $this->dbconn->quote($this->data['private']),
			$this->dbconn->quote($this->data['phone_a']), $this->dbconn->quote($this->data['phone_b']),
			$this->dbconn->quote($this->data['credits']),
			$this->dbconn->quote($this->data['id']));

		$this->dbconn->query($sql);

		if(($this->data['username'] != '') && ($this->data['password'] != '')) {
			// * call this function to update profile credentioals
			$this->update_profile_credentials();
		}

		global $orbx_mod;
		if($orbx_mod->validate_module('estate') && _get_is_orbicon_uri()) {
			$sql = sprintf('
			UPDATE	pring_contact
			SET		estate_agency_status = %s,
					estate_agency_level = %s
			WHERE	(id = %s)',
			$this->dbconn->quote($this->data['estate_agency_status']),
			$this->dbconn->quote($this->data['estate_agency_level']),
			$this->dbconn->quote($this->data['id']));

			$this->dbconn->query($sql);

			include_once DOC_ROOT . '/orbicon/modules/estate.inc.estate.php';

			switch ($this->data['estate_agency_level']) {
				case AGENCY_STATUS_15: archive_user_ads($this->get_rid_from_prid($this->data['id']), 15); break;
				case AGENCY_STATUS_40: archive_user_ads($this->get_rid_from_prid($this->data['id']), 40); break;
			}


		}

		// we're updating our own profile
		if(!_get_is_orbicon_uri()) {


			// * update country information
			$sql = sprintf('
			UPDATE	pring_contact
			SET		contact_region = %s,
					contact_country = %s,
					contact_town_text = %s
			WHERE	(id = %s)',
			$this->dbconn->quote($this->data['county']), $this->dbconn->quote($this->data['country']),
			$this->dbconn->quote($this->data['city_text']), $this->dbconn->quote($this->data['id']));

			$this->dbconn->query($sql);

			$_SESSION['user.r']['contact_name'] = $this->data['name'];
			$_SESSION['user.r']['contact_surname'] = $this->data['surname'];
			$_SESSION['user.r']['contact_dob'] = $this->data[$dob];
			$_SESSION['user.r']['contact_address'] = $this->data['address'];
			$_SESSION['user.r']['contact_city'] = $this->data['city'];
			$_SESSION['user.r']['contact_email'] = $this->data['email'];
			$_SESSION['user.r']['contact_gsm'] = $this->data['gsm'];
			$_SESSION['user.r']['contact_phone'] = $this->data['phone'];
			$_SESSION['user.r']['contact_phone_a'] = $this->data['phone_a'];
			$_SESSION['user.r']['contact_phone_b'] = $this->data['phone_b'];
			$_SESSION['user.r']['contact_sex'] = $this->data['sex'];
			$_SESSION['user.r']['contact_region'] = $this->data['contact_region'];
			$_SESSION['user.r']['contact_country'] = $this->data['contact_country'];
			$_SESSION['user.r']['contact_zip'] = $this->data['zip'];
			$_SESSION['user.r']['contact_town_text'] = $this->data['city_text'];
			$_SESSION['user.r']['estate_agency_status'] = $this->data['estate_agency_status'];
			$_SESSION['user.r']['estate_agency_level'] = $this->data['estate_agency_level'];
		}
	}

	/**
	 * Update CV
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 */
	function update_cv()
	{
		// * updates user cv information
		$sql = sprintf('
			UPDATE		pring_cvs
			SET			cvname = %s,
						county = %s,
						placeofbirth = %s,
						countryofbirth = %s,
						country = %s,
						yoe = %s,
						doe = %s,
						education = %s,
						pastjobs = %s,
						rest = %s,
						eng = %s,
						ger = %s,
						ita = %s,
						fre = %s,
						otherpassive = %s,
						otheractive = %s,
						gotmanagerskills = %s,
						dlic = %s,
						complementary = %s,
						dlicmore = %s,
						capabilities = %s,
						achievements = %s
			WHERE		(contact_id = %s)',
			$this->dbconn->quote($this->data['name']), $this->dbconn->quote($this->data['county']),
			$this->dbconn->quote($this->data['pob']), $this->dbconn->quote($this->data['cob']),
			$this->dbconn->quote($this->data['country']), $this->dbconn->quote($this->data['yoe']),
			$this->dbconn->quote($this->data['doe']), $this->dbconn->quote($this->data['education']),
			$this->dbconn->quote($this->data['past_jobs']), $this->dbconn->quote($this->data['rest']),
			$this->dbconn->quote($this->data['eng']), $this->dbconn->quote($this->data['ger']),
			$this->dbconn->quote($this->data['ita']), $this->dbconn->quote($this->data['fre']),
			$this->dbconn->quote($this->data['passive']), $this->dbconn->quote($this->data['active']),
			$this->dbconn->quote($this->data['manager_skills']), $this->dbconn->quote($this->data['dlic']),
			$this->dbconn->quote($this->data['complementary']), $this->dbconn->quote($this->data['dlicmore']),
			$this->dbconn->quote($this->data['capabilities']), $this->dbconn->quote($this->data['achievements']),
			$this->dbconn->quote($this->data['contact_id']));

		$this->dbconn->query($sql);
	}

	/**
	 * Update username and password
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param int $id
	 */
	function update_profile_credentials($pr_id = null)
	{
		// * if it is not called directly, get data from $data property
		$pr_id = ($pr_id != null) ? $pr_id : $this->data['id'];
		$sql = sprintf('UPDATE ' . TABLE_REG_USERS . '
						SET		username = %s, pwd = PASSWORD(%s)
						WHERE	(pring_contact_id = %s)',
						$this->dbconn->quote($this->data['username']),
						$this->dbconn->quote($this->data['password']),
						$this->dbconn->quote($pr_id));

		$this->dbconn->query($sql);
	}

	/**
	 * Updates company information
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 */
	function update_company_info()
	{
		$sql = sprintf('UPDATE		pring_company
						SET			title = %s,
									address = %s,
									city = %s,
									zip = %s,
									mb = %s,
									phone = %s,
									fax = %s,
									url = %s,
									mail = %s,
									industry_a = %s,
									industry_b = %s,
									industry_c = %s,
									intro_text = %s,
									phone_a = %s,
									phone_b = %s
						WHERE		(contact = %s)',
						$this->dbconn->quote($this->data['title_comp']),
						$this->dbconn->quote($this->data['address']),
						$this->dbconn->quote($this->data['city']),
						$this->dbconn->quote($this->data['zip']),
						$this->dbconn->quote($this->data['mb']),
						$this->dbconn->quote($this->data['phone']),
						$this->dbconn->quote($this->data['fax']),
						$this->dbconn->quote($this->data['url']),
						$this->dbconn->quote($this->data['mail']),
						$this->dbconn->quote($this->data['industry_a']),
						$this->dbconn->quote($this->data['industry_b']),
						$this->dbconn->quote($this->data['industry_c']),
						$this->dbconn->quote($this->data['content_text']),
						$this->dbconn->quote($this->data['phone_a']),
						$this->dbconn->quote($this->data['phone_b']),
						$this->dbconn->quote($this->data['id']));

		$this->dbconn->query($sql);
	}

	/**
	 * Sets contact's picture
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $picture
	 * @param int $pr_id
	 */
	function set_picture($picture, $pr_id)
	{
		$sql = sprintf('UPDATE	pring_contact
						SET		picture = %s
						WHERE	(id = %s)',
						$this->dbconn->quote($picture),
						$this->dbconn->quote($pr_id));

		$this->dbconn->query($sql);
	}

	/**
	 * Gets contact's picture filename
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param int $id
	 * @return string
	 */
	function get_picture($pr_id = null)
	{
		$id = ($pr_id) ? $pr_id : $this->data['id'];
		$profile = $this->get_profile($id);
		return $profile['picture'];
	}

	/**
	 * return ID from username
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $username
	 * @return int
	 */
	function get_id_from_username($username)
	{
		// * build query
		$sql = sprintf('	SELECT 		id
							FROM 		'.TABLE_REG_USERS.'
							WHERE 		(username = %s)', $this->dbconn->quote($username));

		$resource = $this->dbconn->query($sql);
		$id = $this->dbconn->fetch_assoc($resource);
		return $id['id'];
	}

	/**
	 * Get latest members
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param int $limit
	 * @return resource
	 */
	function get_latest_members($limit = 10, $order_by = 'registered')
	{
		if(!is_int($limit)) {
			trigger_error('get_latest_members() expects parameter 1 to be integer, ' . gettype($limit) . ' given', E_USER_WARNING);
			return false;
		}

		// sanity check
		if($limit < 1) {
			trigger_error('get_latest_members() expects parameter 1 to be 1 or higher,' . $limit . ' given', E_USER_NOTICE);
			$limit = 1;
		}

		$sql = sprintf('	SELECT 		*
							FROM 		pring_contact
							WHERE		(private = 0)
							ORDER BY 	%s DESC
							LIMIT		%s',
							$order_by,
							$this->dbconn->quote($limit));

		return $this->dbconn->query($sql);
	}

	/**
	 * return pring ID from registered ID
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param int $id
	 * @return int
	 */
	function get_prid_from_rid($r_id)
	{
		$id = $this->get_username($r_id);
		return $id['pring_contact_id'];
	}

	/**
	 * return registered ID from pring ID
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param int $id
	 * @return int
	 */
	function get_rid_from_prid($r_id)
	{
		$sql = sprintf('SELECT 		id
						FROM 		' . TABLE_REG_USERS . '
						WHERE 		(pring_contact_id = %s)',
						$this->dbconn->quote($r_id));


		$r = $this->dbconn->query($sql);
		$a = $this->dbconn->fetch_assoc($r);
		return $a['id'];
	}

	/**
	 * For search drop down labels
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return array
	 */
	function _get_other_pr_search()
	{
		return array(
			'phone' => _L('pr-phone'),
			'zip' => _L('pr-zip'),
			'url' => _L('pr-url'),
			'dob' => _L('pr-dob') . ' (' . _L('pr-dob-format') . ')',
			'office' => _L('pr-office'),
			'position' => _L('pr-position'),
			'expertise' => _L('pr-expertise'),
			'fax' => _L('pr-fax'),
			'gsm' => _L('pr-gsm')
		);
	}

	/**
	 * Sets contact's company logo
	 *
	 *  @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param int $company_id
	 * @param string $logo
	 */
	function set_logo($company_id, $logo)
	{
		$sql = sprintf('UPDATE	pring_company
						SET		logo = %s
						WHERE	(id = %s)',
						$this->dbconn->quote($logo),
						$this->dbconn->quote($company_id));

		$this->dbconn->query($sql);
	}

	/**
	 * Determine if profile is set to private
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param int $id
	 */
	function get_is_private_profile($pr_id)
	{
		$profile = $this->get_profile($pr_id);

		return (bool) $profile['private'];
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param int $pr_id
	 */
	function change_credits($pr_id, $new_credits)
	{
		if(!$new_credits || !$pr_id) {
			return false;
		}

		global $dbc;
		$q = sprintf('	UPDATE 		pring_contact
						SET			credits=%s
						WHERE 		(id=%s)',
						$dbc->_db->quote($new_credits), $dbc->_db->quote($pr_id));
		$dbc->_db->query($q);
		return true;
	}

	function delete_user($rid)
	{
		global $dbc, $orbx_mod;

		$user = $this->get_username($rid);
		$prid = $user['pring_contact_id'];

		$sql = array(
		'DELETE FROM '.TABLE_REG_USERS.' WHERE id = ' . $rid,
		'DELETE FROM pring_contact WHERE id = ' . $prid,
		'DELETE FROM pring_cvs WHERE contact_id = ' . $prid,
		'DELETE FROM pring_company WHERE contact = ' . $prid,
		'DELETE FROM pring_mails WHERE mail_from = ' . $rid,
		'DELETE FROM pring_mails WHERE owner_id = ' . $rid,
		'DELETE FROM pring_user_contacts WHERE owner_id = ' . $rid,
		'DELETE FROM pring_contact WHERE id = ' . $prid
		);

		if($orbx_mod->validate_module('inpulls')) {
			$sql[] = 'DELETE FROM orbx_mod_inpulls_profile WHERE pring_id = ' . $prid;
			$sql[] = 'DELETE FROM orbx_mod_inpulls_mobbing WHERE user_reg_id = ' . $rid;
			$sql[] = 'DELETE FROM orbx_mod_inpulls_mobbing WHERE mobber_reg_id = ' . $rid;
			$sql[] = 'DELETE FROM orbx_mod_inpulls_comments WHERE author_rid = ' . $rid;
			$sql[] = 'DELETE FROM orbx_mod_inpulls_comments WHERE user_rid = ' . $rid;
		}

		if($orbx_mod->validate_module('e')) {
			$sql[] = 'DELETE FROM orbx_mod_estate WHERE user_id = ' . $rid;
		}

		foreach ($sql as $q) {
			$dbc->_db->query($q);
		}
	}
}

?>