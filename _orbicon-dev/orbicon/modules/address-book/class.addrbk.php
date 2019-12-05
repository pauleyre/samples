<?php
/*-----------------------------------------------------------------------*
	Project........:	Orbicon X framework 2
	File...........:
	Version........:	1.0 (22/10/2006)
	Author.........:	Pavle Gardijan (pavle.gardijan@orbitum.net) - Orbitum d.o.o.
	Copyright......:	(c) 2006 Orbitum internet communications d.o.o. (www.orbitum.net)
	Created........:	01/07/2006
	Notes..........:
	Modified.......:
*-----------------------------------------------------------------------*/
class Address_Book
{
	function build_adrbks($columns)
	{
		global $dbc, $orbx_mod;

		// editors
		$r = $dbc->_db->query('	SELECT 		email
								FROM 		'.TABLE_EDITORS.'
								WHERE 		(email != \'\')
								ORDER BY 	email');

		if($r) {
			$rubrika = $dbc->_db->fetch_assoc($r);
			$opcije .= '<optgroup label="'._L('editors').'">';

			while($rubrika) {
								
				if(!in_array($rubrika['email'], $columns)) {
					if(!$this->mail_already_in($opcije, $rubrika['email'])) {
						$opcije .= sprintf('<option value="%s">%s</option>', $rubrika['email'], $rubrika['email']);
					}
				}
				else {
					if(!$this->mail_already_in($selected, $rubrika['email'])) {
						$selected .= sprintf('<option value="%s">%s</option>', $rubrika['email'], $rubrika['email']);
					}
				}
				$rubrika = $dbc->_db->fetch_assoc($r);
			}

			$opcije .= '</optgroup>';
		}

		// columns
		$r = $dbc->_db->query('	SELECT 		*
								FROM 		'.TABLE_EMAILS.'
								ORDER BY 	email');

		if($r) {
			$rubrika = $dbc->_db->fetch_assoc($r);
			$opcije .= '<optgroup label="'._L('other').'">';

			while($rubrika) {
				if(!in_array($rubrika['email'], $columns)) {
					if(!$this->mail_already_in($opcije, $rubrika['email'])) {
						$opcije .= sprintf('<option value="%s">%s</option>', $rubrika['email'], $rubrika['email']);
					}
				}
				else {
					if(!$this->mail_already_in($selected, $rubrika['email'])) {
						$selected .= sprintf('<option value="%s">%s</option>', $rubrika['email'], $rubrika['email']);
					}
				}
				$rubrika = $dbc->_db->fetch_assoc($r);
			}

			$opcije .= '</optgroup>';
		}

		// newsalerts
		if($orbx_mod->validate_module('news-alerts')) {

			require DOC_ROOT . '/orbicon/modules/news-alerts/inc.newsalerts.php';

			$r = $dbc->_db->query('	SELECT 		*
									FROM 		'.TABLE_NEWSALERTS_SUBS.'
									ORDER BY 	email');

			if($r) {
				$rubrika = $dbc->_db->fetch_assoc($r);
				$opcije .= '<optgroup label="'._L('news-alerts').'">';

				while($rubrika) {
					if(!in_array($rubrika['email'], $columns)) {
						if(!$this->mail_already_in($opcije, $rubrika['email'])) {
							$opcije .= sprintf('<option value="%s">%s</option>', $rubrika['email'], $rubrika['email']);
						}
					}
					else {
						if(!$this->mail_already_in($selected, $rubrika['email'])) {
							$selected .= sprintf('<option value="%s">%s</option>', $rubrika['email'], $rubrika['email']);
						}
					}
					$rubrika = $dbc->_db->fetch_assoc($r);
				}

				$opcije .= '</optgroup>';
			}
		}

		return array($opcije, $selected);
	}

	function mail_already_in($address_book, $email)
	{
		return (strpos($address_book, $email) !== false);
	}
	
	function save_address_book()
	{
		if(isset($_POST['save_adrbk'])) {
			global $dbc, $orbicon_x;

			$permalink = $_GET['edit'];
			$title = trim($_POST['adrbk_title']);

			if(empty($title)) {
				trigger_error('save_address_book() expects parameter 1 to be non-empty', E_USER_WARNING);
				return false;
			}

			$permalink = get_permalink($title);
			$title = utf8_html_entities($title);

			if(!empty($_POST['orbicon_list_selected'])) {
				$_POST['orbicon_list_selected'] = array_remove_empty($_POST['orbicon_list_selected']);
				$columns = implode('|', $_POST['orbicon_list_selected']);
			}

			if(!isset($_GET['edit'])) {
				$q = sprintf('INSERT INTO '.TABLE_ADRBKS.'
								(title, permalink,
								column_list)
								VALUES (%s, %s,
								%s)',
								$dbc->_db->quote($title), $dbc->_db->quote($permalink), $dbc->_db->quote($columns));
			}
			else {
				$q = sprintf('UPDATE '.TABLE_ADRBKS.'
								SET title = %s, permalink = %s,
								column_list = %s
								WHERE (permalink = %s)',
							$dbc->_db->quote($title), $dbc->_db->quote($permalink), $dbc->_db->quote($columns), $dbc->_db->quote($_GET['edit']));

			}
			$dbc->_db->query($q);

			redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/address-book&edit='.urlencode($permalink));
		}
	}

	function load_address_book()
	{
		if(isset($_GET['edit'])) {
			global $dbc;
			$q = sprintf('	SELECT 	*
							FROM 	'.TABLE_ADRBKS.'
							WHERE 	(permalink=%s)
							LIMIT 	1', $dbc->_db->quote($_GET['edit']));
			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);

			return $a;
		}
		return null;
	}

	function get_adrbk_array()
	{
		global $dbc;
		$q = '	SELECT 		*
				FROM 		'.TABLE_ADRBKS.'
				ORDER BY 	permalink';
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$adrbks[] = $a;
			$a = $dbc->_db->fetch_assoc($r);
		}

		return $adrbks;
	}

	function mass_email_add()
	{
		if(isset($_POST['mass_email_save'])) {
			$mails = $this -> csv_import_adrbk();
			$_POST['mass_email_add'] = $_POST['mass_email_add'].' '.$mails;

			$emails = str_replace(',', ' ', $_POST['mass_email_add']);
			$emails = explode(' ', $emails);

			global $dbc;
			foreach($emails as $value) {
				$value = trim($value);
				if(is_email($value)) {
					$dbc->_db->query(sprintf('INSERT INTO '.TABLE_EMAILS.' (email) VALUES (%s)', $dbc->_db->quote($value)));
				}
			}

		}
	}

	function csv_import_adrbk()
	{
		if(!empty($_FILES['csv_adrbk'])) {

			// seed for PHP < 4.2.0
			srand((float) microtime() * 10000000);

			$temp_file = DOC_ROOT . '/site/mercury/' . sprintf('%u', adler32(time() * rand()));
			// file exists, recreate a name
			while(is_file($temp_file)) {
				$temp_file = DOC_ROOT . '/site/mercury/' . sprintf('%u', adler32(time() * rand()));
			}

			move_uploaded_file($_FILES['csv_adrbk']['tmp_name'], $temp_file);
			chmod_lock($temp_file);

			$emails = file($temp_file);
			unlink($temp_file);

			foreach($emails as $value) {
				$value = trim(str_replace(';', '', $value));
				if(is_email($value)) {
					$mails[] = $value;
				}
			}
			return implode(',', $mails);
		}
		return null;
	}

	function delete_address_book()
	{
		if(isset($_GET['delete'])) {
			global $dbc, $orbicon_x;
			$dbc->_db->query(sprintf('	DELETE
										FROM 	'.TABLE_ADRBKS.'
										WHERE 	(permalink = %s)
										LIMIT 	1',
										$dbc->_db->quote($_GET['delete'])));

			redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/address-book');
		}
	}

	function load_address_book_emails($address_book)
	{
		global $dbc;
		$q = sprintf('	SELECT 	column_list
						FROM 	'.TABLE_ADRBKS.'
						WHERE 	(permalink=%s)
						LIMIT 	1', $dbc->_db->quote($address_book));
		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);
		$mails = explode('|', $a['column_list']);
		return $mails;
	}
}

?>