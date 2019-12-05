<?php

include '../data/db.php';
include 'logic/func.main.php';
main();

require 'logic/func.upload.php';
/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @return unknown
	 */
	function add_document($user_id)
	{
		$file = $_FILES['userfile'];
		$max = count($file['name']);

		for($i = 0; $i < $max ; $i++) {
			// security checks
			if(validate_upload($_FILES['userfile']['tmp_name'][$i], $_FILES['userfile']['name'][$i], $_FILES['userfile']['size'][$i], $_FILES['userfile']['error'][$i])) {
				$files[] = insert_file_into_db($_FILES['userfile']['name'][$i], $_FILES['userfile']['tmp_name'][$i], $user_id);
			}
		}
		return $files;
	}

	/**
	 * return true if file is a valid uploaded file. expects parameters from $_FILES array
	 *
	 * @param string $tmp_filename 		$_FILES['userfile']['tmp_name']
	 * @param string $files_name		$_FILES['userfile']['name']
	 * @param int $files_size			$_FILES['userfile']['size']
	 * @param int $error_code			$_FILES['userfile']['error']
	 * @return bool
	 */
	function validate_upload($tmp_filename, $files_name, $files_size, $error_code)
	{
		// all of these indicate a valid file
		if(
			(is_uploaded_file($tmp_filename)) &&
			(filesize($tmp_filename) == $files_size) &&
			($files_name != '') &&
			($tmp_filename != '') &&
			($error_code == UPLOAD_ERR_OK)
		) {
			return true;
		}

		return false;
	}

	/**
	 * Enter description here...
	 *
	 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
	 * @param string $filename
	 * @param bool $uploaded_file
	 * @param string $tmp_filename
	 * @param bool $unlink
	 * @param string $category
	 * @return string
	 */
	function insert_file_into_db($filename,  $tmp_filename = null, $user_id = 0)
	{
		if($filename == '') {
			trigger_error('insert_file_into_db() expects parameter 1 to be non-empty', E_USER_WARNING);
			return null;
		}

		$target = "../web/upload/u$user_id/$filename";

		$i = 2;

		while(is_file($target)) {
			$target = "../web/upload/u$user_id/($i) $filename";
			$i ++;
		}

		$created = move_uploaded_file($tmp_filename, $target);

		if($created) {

			// filter out words for search engine
			/*$supported_text_formats = array('doc', 'txt', 'rtf', 'rdf', 'log', 'xml', 'c', 'cpp', 'h', 'cs', 'cfm', 'phps', 'php');

			if(in_array($ext, $supported_text_formats)) {

				$id = ($do_db_insert_sql) ? $dbc->_db->insert_id($r) : $old_id;

				$q = sprintf('	UPDATE 		' . MERCURY_FILES . '
								SET			search_index=%s
								WHERE 		(id=%s)',

				$dbc->_db->quote(preg_replace('/[^a-zA-Z0-9\s-.]/i', '', file_get_contents($path))), $dbc->_db->quote($id));
				$dbc->_db->query($q);
			}
			else if($ext == 'pdf') {

				global $orbx_log;
				$tmp = DOC_ROOT . '/site/mercury/pdftxt.tmp';
				$output = null;
				$error = null;

				if (strtolower(substr(PHP_OS, 0, 3)) == 'win') {
					system(DOC_ROOT . '\orbicon\3rdParty\pdftext\pdftext.exe ' . escapeshellarg($path), $output);
				}
				else {
					system('pdftotext ' . escapeshellarg($path), $output);
				}

				switch ($output) {
					case 1: $error = 'pdftotext: Error opening a PDF file ' . $path; break;
					case 2: $error = 'pdftotext: Error opening an output file ' . $tmp; break;
					case 3: $error = 'pdftotext:  Error related to PDF permissions'; break;
					case 99: $error = 'pdftotext: Other error'; break;
				}

				if($error) {
					$orbx_log->ewrite($error, __LINE__, __FUNCTION__);
				}

				$new_file = substr($path, 0, -4) . '.txt';
				$id = ($do_db_insert_sql) ? $dbc->_db->insert_id($r) : $old_id;
				$q = sprintf('	UPDATE 		'.MERCURY_FILES.'
								SET			search_index=%s
								WHERE 		(id=%s)',
				$dbc->_db->quote(file_get_contents($new_file)), $dbc->_db->quote($id));
				$dbc->_db->query($q);
				unlink($new_file);
			}*/

			return $file;
		}

		trigger_error('Failed to move / copy uploaded file' . $path, E_USER_WARNING);
		return null;
	}

	$valid_upload = get_is_valid_ajax_id($_REQUEST['credentials']);

	if($valid_upload) {
		$uploaded_files = add_document($valid_upload);
		if(!isset($_REQUEST['simple'])) {
			echo implode('<br>', $uploaded_files);
		}
	}

if(isset($_REQUEST['simple'])) {
	meta_redirect('../?action=dc');
}

?>