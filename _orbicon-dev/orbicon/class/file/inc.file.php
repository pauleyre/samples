<?php
/**
 * Library for class File
 *
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-06-09
 */

// we'll need this one
require_once DOC_ROOT . '/orbicon/class/diriterator/class.diriterator.php';

	/**
     * Recursive unlink function
     *
     * This will delete the provided path.  If the path is a
     * directory, it will delete the directory.  It will
     * recursively delete any subdirectories/files.
     *
     * @param string $path Path to file/directory to recursively unlink
     */
	function unlink_r($path)
	{
		// make sure the path exists
		if(!file_exists($path)) {
			return false;
		}

		// if it is a file or link, just delete it
		if(is_file($path) || is_link($path)) {
			return unlink($path);
		}

		// scan the dir and recursively unlink
		$dir = new DirIterator($path);
		$files = $dir->all();

		foreach($files as $filename) {
			if($dir->get_is_dot($filename)) {
				continue;
			}

			$file = str_replace('//' , '/' , $path . '/' . $filename);
			unlink_r($file);
		} // end foreach

		// release from memory
		unset($dir, $files);

		// remove the parent dir
		if(!rmdir($path)) {
			return false;
		}
		return true;
	} // end function unlink

	/**
	 * copy recursive
	 *
	 * @access private
	 * @param string $source
	 * @param string $dest
	 * @return bool
	 */
	function copy_r($source, $dest)
	{
		// Simple copy for a file
		if(is_file($source)) {
			return copy($source, $dest);
		}

		// Make destination directory
		if(!is_dir($dest)) {
			$oldumask = umask(0);
			mkdir($dest, 0644);
			umask($oldumask);
		}

		// Loop through the folder
		$dir = new DirIterator($source);
		$files = $dir->all();

		foreach($files as $entry) {
			// Skip pointers
			if($dir->get_is_dot($entry)) {
				continue;
			}

			// Deep copy directories
			if($dest !== "$source/$entry") {
				copy_r("$source/$entry", "$dest/$entry");
			}
		}

		$dir = null;
		return true;
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
	 * sanitize filename
	 *
	 * @param string $filename
	 * @return string
	 */
	function sanitize_name($filename)
	{
		$filename = strtolower($filename);
		$filename = preg_replace('/&.+?;/', '', $filename); // kill entities
		$filename = str_replace('_', '-', $filename);
		$filename = preg_replace('/[^a-z0-9\s-.]/', '', $filename);
		$filename = preg_replace('/\s+/', '-', $filename);
		$filename = preg_replace('|-+|', '-', $filename);
		$filename = trim($filename, '-');
		return $filename;
	}

	/**
	 * gzip file on maximum level. creates file of filename.gz
	 *
	 * @param string $filename
	 * @return bool
	 */
	function gzip($filename)
	{
		// create gziped files
		if(function_exists('gzencode')) {
			$fp = fopen("$filename.gz", 'wb');
			/* Set a 64k buffer. */
			if(function_exists('stream_set_write_buffer')) {
				stream_set_write_buffer($fp, 65535);
			}
			fwrite($fp, gzencode(file_get_contents($filename), 9, FORCE_GZIP));
			fclose($fp);
			return is_file("$filename.gz");
		}
		return false;
	}

	/**
	 * return true if $path has / on end
	 *
	 * @param string $path
	 * @return bool
	 */
	function get_endslash($path)
	{
		return (substr($path, -1, 1) == '/');
	}

	/**
	 * removes end slash from path
	 *
	 * @param string $path
	 * @return string
	 */
	function strip_endslash($path)
	{
		if(get_endslash($path) === true) {
			return substr($path, 0, -1);
		}
		return $path;
	}

	/**
	 * backup file with .bk extension
	 *
	 * @param string $filename
	 * @return bool
	 */
	function backup($filename)
	{
		$do_backup = copy($filename, "$filename.bk");

		if(!$do_backup) {
			global $orbx_log;
			$orbx_log->ewrite('unable to backup ' . $filename, __LINE__, __FUNCTION__);
		}

		return $do_backup;
	}

?>