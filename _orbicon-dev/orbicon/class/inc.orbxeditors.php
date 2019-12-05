<?php
/**
 * Orbicon administrators
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @subpackage Core
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-07-01
 */

	function build_site_editors()
	{
		global $dbc;

		if($_GET['action'] == 'edit' && isset($_GET['id'])) {
			$q = sprintf('	SELECT 	*
							FROM 	%s
							WHERE 	(id = %s)
							LIMIT 	1',
			TABLE_EDITORS, $dbc->_db->quote($_GET['id']));

			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_array($r);

			$_POST['first_name'] = $a['first_name'];
			$_POST['last_name'] = $a['last_name'];
			$_POST['username'] = $a['username'];
			$_POST['pwd'] = $a['pwd'];
			$_POST['email'] = $a['email'];
			$_POST['mob'] = $a['mob'];
			$_POST['tel'] = $a['tel'];
			$_POST['occupation'] = $a['occupation'];
			$_POST['notes'] = $a['notes'];
			$_POST['status'] = $a['status'];
		}

		unset($a);
	}

	function delete_site_editor()
	{
		if(($_REQUEST['action'] == 'delete') && isset($_REQUEST['id'])) {
			global $dbc, $orbicon_x;

			$q = sprintf('	DELETE
							FROM 		%s
							WHERE 		(id = %s)
							LIMIT 		1', TABLE_EDITORS, $dbc->_db->quote($_REQUEST['id']));

			$dbc->_db->query($q);
		}
	}

	function save_site_editor()
	{
		global $dbc, $orbicon_x;

		if(isset($_REQUEST['id'])) {
			$username = strtolower($_REQUEST['username']);
			$password = $_REQUEST['pwd'];

			if($_REQUEST['action'] == 'edit') {
				$status_check = sprintf('	SELECT 		username, pwd,
														status
											FROM 		%s
											WHERE 		(id = %s)
											LIMIT 		1', TABLE_EDITORS, $dbc->_db->quote($_REQUEST['id']));
				$status_check = $dbc->_db->query($status_check);
				$status_check = $dbc->_db->fetch_array($status_check);
				$old_username = $_REQUEST['old_username'];
				$old_password = $_REQUEST['old_password'];

				if($username != $old_username) {
					$q = sprintf('UPDATE '.TABLE_EDITORS.'
									SET username = PASSWORD(%s)
									WHERE (id = %s)
									LIMIT 1', $dbc->_db->quote($username), $dbc->_db->quote($_REQUEST['id']));
					$dbc->_db->query($q);
				}

				if($password != $old_password) {
					$q = sprintf('UPDATE '.TABLE_EDITORS.'
									SET pwd = PASSWORD(%s)
									WHERE (id = %s)
									LIMIT 1', $dbc->_db->quote($password), $dbc->_db->quote($_REQUEST['id']));
					$dbc->_db->query($q);
				}

				$q = sprintf('UPDATE '.TABLE_EDITORS.' SET
											first_name = %s, last_name = %s, email = %s, occupation = %s,
											notes = %s, status = %s, mob = %s, tel = %s
											WHERE (id = %s)
											LIMIT 1',
											$dbc->_db->quote($_REQUEST['first_name']), $dbc->_db->quote($_REQUEST['last_name']), $dbc->_db->quote($_REQUEST['email']), $dbc->_db->quote($_REQUEST['occupation']),
											$dbc->_db->quote($_REQUEST['notes']), $dbc->_db->quote($_REQUEST['status']), $dbc->_db->quote($_REQUEST['mob']), $dbc->_db->quote($_REQUEST['tel']),
											$dbc->_db->quote($_REQUEST['id']));

				$dbc->_db->query($q);
				// * we denied access
				if($_REQUEST['status'] == ORBX_USER_STATUS_EX_USER && !empty($_REQUEST['email']) && $status_check['status'] != ORBX_USER_STATUS_EX_USER) {
					//mail(trim($_REQUEST['email']), DOMAIN_NAME.' - '.ORBICON_FULL_NAME.' status', "Postovani,\nVas korisnicki racun koji ste koristili na adresi ".ORBX_SITE_URL.' je *zatvoren* odlukom supervizora na dan '.date('r').".\nHvala,\n".DOMAIN_OWNER, 'Reply-to: <'.DOMAIN_EMAIL.">\nFrom: <".DOMAIN_EMAIL.'>');
				}

				// * we allowed access
				if($_REQUEST['status'] != ORBX_USER_STATUS_EX_USER && !empty($_REQUEST['email']) && $status_check == ORBX_USER_STATUS_EX_USER) {
					//mail(trim($_REQUEST['email']), DOMAIN_NAME.' - Dobrodosli u '.ORBICON_FULL_NAME, "Postovani,\notvoren je Vas korisnicki racun na adresi ".ORBX_SITE_URL.' odlukom supervizora na dan '.date('r').".\n*Korisnicko ime:* {$status_check['username']}\n*Pristupna lozinka:* {$status_check['pwd']}\n*Login:* ".ORBX_SITE_URL.'/?'.$orbicon_x->ptr."=orbicon/authorize\nHvala,\n".DOMAIN_OWNER, 'Reply-to: <'.DOMAIN_EMAIL.">\nFrom: <".DOMAIN_EMAIL.'>');
				}
			}
			else if(empty($_REQUEST['action'])) {
				if(!empty($_REQUEST['first_name']) && !empty($password) && !empty($username)) {
					$q = sprintf('INSERT INTO '.TABLE_EDITORS.' (
													username, pwd, first_name, last_name, email,
													occupation, notes, status, mob, tel) VALUES (
													PASSWORD(%s), PASSWORD(%s), %s, %s, %s,
													%s, %s, %s, %s, %s)',
													$dbc->_db->quote($username), $dbc->_db->quote($password), $dbc->_db->quote($_REQUEST['first_name']), $dbc->_db->quote($_REQUEST['last_name']), $dbc->_db->quote($_REQUEST['email']),
													$dbc->_db->quote($_REQUEST['occupation']), $dbc->_db->quote($_REQUEST['notes']), $dbc->_db->quote($_REQUEST['status']), $dbc->_db->quote($_REQUEST['mob']), $dbc->_db->quote($_REQUEST['tel']));

					$dbc->_db->query($q);

					$orbicon_x->add_desktop_icon('wwwroot', $dbc->_db->insert_id());
				}
			}
		}
	}

	// * Display clients
	function display_site_editors()
	{
		global $dbc, $orbicon_x;

		$q = '	SELECT 		id, first_name, last_name
				FROM 		'.TABLE_EDITORS.'
				ORDER BY 	first_name ASC';

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);
		$list = '';

		while($a) {
			$list .= '<li><a href="'.
					ORBX_SITE_URL.
					'/?'.$orbicon_x->ptr.'=orbicon/editors&amp;action=edit&amp;id='.
					$a['id'].
					'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/user_edit.png" alt="'._L('edit').'" title="'._L('edit').'" /></a> <a href="javascript:void(null);" onclick="javascript: delete_site_editor('.
					'\''.
					ORBX_SITE_URL.
					'/orbicon/controler/admin.editors.update.php\','.
					$a['id'].
					');"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/user_delete.png" alt="'._L('delete').'" title="'._L('delete').'" /></a>
					<strong>'.$a['first_name'].' '.$a['last_name'].'</strong></li>';
			$a = $dbc->_db->fetch_assoc($r);
		}

		return '<ol>'.$list.'</ol>';
	}

?>