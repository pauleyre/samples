<?php
/**
 * File handling class
 * Example:
 * <code>
 * <?php
 * // open file
 * $file = new File(DOC_ROOT . '/site/mercury/apple.txt', 'wb');
 * // put content
 * $file->put('new content');
 * // delete file
 * $file->unlink();
 * ?>
 * </code>
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-05-28
 */


/**
 * Unknown file type
 *
 */
define('FILE_TYPE_UNKNOWN', 0);
/**
 * Common file
 *
 */
define('FILE_TYPE_FILE', 1);
/**
 * File is directory
 *
 */
define('FILE_TYPE_DIR', 2);

// we'll need this one
require_once DOC_ROOT . '/orbicon/class/file/inc.file.php';

class File
{
	/**
	 * File name path
	 *
	 * @var string
	 */
	var $name;

	/**
	 * file resource
	 *
	 * @access private
	 * @var resource
	 */
	var $_handle;

	/**
	 * file type of FILE_TYPE_*
	 *
	 * @var int
	 */
	var $type;

	/**
	 * Binds file to class. If $mode is set, a file handle will be set with that mode
	 *
	 * @param string $name
	 * @param int $mode
	 */
	function __construct($name, $mode = '')
	{
		// sometimes we might reference another File class object so we want its name here
		if(is_object($name)) {
			$this->name = $name->name;
			// try to close it
			$name->close();
		}
		else {
			$this->name = $name;
		}

		// determine file type
		if(is_file($this->name)) {
			$this->type = FILE_TYPE_FILE;
		}
		else if(is_dir($this->name)) {
			$this->type = FILE_TYPE_DIR;
		}
		else {
			$this->type = FILE_TYPE_UNKNOWN;
		}

		// if mode is set, open file handle
		if(($mode != '') && ($this->type == FILE_TYPE_FILE)) {
			$this->set_handle($this->open($mode));
			// set a 64k buffer if not in read-only mode
			if(($mode != 'r') && ($mode != 'rb')) {
				/* Set a 64k buffer. */
				if(function_exists('stream_set_write_buffer')) {
					stream_set_write_buffer($this->get_handle(), 65535);
				}

				// we're going to write something so make sure we're writable
				$this->chmod(0666);
			}
			$this->type = FILE_TYPE_FILE;
		}
	}

	/**
	 * Unbinds file handle
	 *
	 */
	function __destruct()
	{
		$this->close();
	}

	/**
	 * PHP 4 compatibility
	 *
	 */
	function file($name, $mode = '')
	{
		$this->__construct($name, $mode);
	}

	/**
	 * print name
	 *
	 * @return string
	 */
	function __toString()
	{
		return $this->name;
	}

	/**
	 * Opens file or URL in mode
	 *
	 * @param string $name
	 * @param int $mode
	 * @return resource
	 */
	function open($mode)
	{
		return fopen($this->name, $mode);
	}

	/**
	 * close file handle
	 *
	 * @return bool
	 */
	function close()
	{
		if($this->validate_handle()) {
			return fclose($this->get_handle());
		}

		trigger_error('Invalid file handle', E_USER_WARNING);
		return false;
	}

	/**
	 * Validates file handle
	 *
	 * @return bool
	 */
	function validate_handle()
	{
		$type = get_resource_type($this->get_handle());
		return (bool) (is_resource($this->get_handle()) && (($type == 'file') || ($type == 'stream')));
	}

	/**
	 * Write a string to a file. Appends data if file not already opened
	 *
	 * @param mixed $data
	 * @return bool
	 */
	function put($data)
	{
		// we never opened file, open with append by default
		if(!$this->validate_handle()) {
			$this->set_handle($this->open('ab'));
			/* Set a 64k buffer. */
			if(function_exists('stream_set_write_buffer')) {
				stream_set_write_buffer($this->get_handle(), 65535);
			}
		}
		return fwrite($this->get_handle(), $data);
	}

	/**
	 *  Write a string to a file and close it. Appends data if file not already opened
	 *
	 * @param string $data
	 * @return bool
	 */
	function putc($data)
	{
		$this->put($data);
		return $this->close();
	}

	/**
	 * Return file contents
	 *
	 * @return string
	 */
	function get()
	{
		return file_get_contents($this->name);
	}

	/**
	 * unlink file
	 *
	 * @return bool
	 */
	function unlink()
	{
		return unlink_r($this->name);
	}

	/**
	 * chmod file
	 *
	 * @param int $mode
	 * @return bool
	 */
	function chmod($mode)
	{
		return chmod($this->name, $mode);
	}

	/**
	 * copy file or directory
	 *
	 * @param unknown_type $path
	 */
	function copy($path)
	{
		return copy_r($this->name, $path);
	}

	/**
	 * move file or directory to $path
	 *
	 * @param unknown_type $path
	 */
	function move($path)
	{
		rename($this->name, $path);
	}

	/**
	 * return size for file or directory
	 *
	 * @param bool $format		return bytes or human readable
	 * @return mixed
	 */
	function size($format = false)
	{
		if($this->type == FILE_TYPE_DIR) {
			return get_dir_size($this->name, $format);
		}

		return get_file_size($this->name, $format);
	}

	/**
	 * Gets line from file pointer
	 *
	 * @return string
	 */
	function gets()
	{
		return fgets($this->get_handle(), 8192);
	}

	/**
	 * return file handle
	 *
	 * @return resource
	 */
	function get_handle()
	{
		return $this->_handle;
	}

	/**
	 * set file handle to $resource
	 *
	 * @param resource $resource
	 */
	function set_handle($resource)
	{
		$this->_handle = $resource;
	}
}

?>