<?php
/**
 * Directory iteration class
 * Example:
 * <code>
 * <?php
 * // include class
 * require_once DOC_ROOT . '/orbicon/class/class.diriterator.php';
 * // open root directory and list only php files (optionally)
 * $dir = new DirIterator('.', '*.php');
 * $files = $dir->all();
 * foreach ($files as $entry) {
 * echo "<p>$entry</p>";
 * }
 * // release the resource
 * unset($dir, $files);
 * ?>
 * </code>
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-05-30
 */

class DirIterator
{
	/**
	 * an array of directories
	 *
	 * @var array
	 */
	var $directories;
	/**
	 * an array of files
	 *
	 * @var array
	 */
	var $files;
	/**
	 * root directory
	 *
	 * @var string
	 */
	var $directory;

	/**
	 * build directory's lists
	 *
	 * @access privat
	 * @param string $path
	 * @return array
	 */
	function __construct($path, $pattern = '')
	{
		if(!is_dir($path)) {
			trigger_error('DirIterator('.$path.') failed to open dir: No such directory', E_USER_WARNING);
			return false;
		}

		$this->directory = $path;
	    $this->directories = array();
		$this->files = array();

		$dir = dir($this->directory);
		$file = $dir->read();

		// we only want files that match a pattern
		if($pattern != '') {
	  		while($file) {
	  			if(!$this->get_is_dot($file)) {
		  			// include us only if we match a pattern
		  			if($this->matches_pattern($pattern, $file)) {
			  			// directories go here
			  			if(is_dir($this->directory . $file)) {
			  				$this->directories[] = $file;
			  			}
			  			else {
				  			$this->files[] = $file;
			  			}
		  			}
	  			}
				$file = $dir->read();
			}
		}
		// we want all files
		else {
	  		while($file) {
	  			if(!$this->get_is_dot($file)) {
		  			// directories go here
		  			if(is_dir($this->directory . $file)) {
		  				$this->directories[] = $file;
		  			}
		  			else {
			  			$this->files[] = $file;
		  			}
	  			}
				$file = $dir->read();
			}
		}
		$dir->close();
		unset($dir, $file, $path);

		// sort lists naturally
		natsort($this->directories);
		natsort($this->files);
	}

	/**
	 * For PHP 4
	 *
	 * @access private
	 * @param string $path
	 */
	function diriterator($path, $pattern = '')
	{
		$this->__construct($path, $pattern);
	}

	/**
	 * return both files and directories
	 *
	 * @return array
	 */
	function all()
	{
		return array_merge($this->directories, $this->files);
	}

	/**
	 * return only directories
	 *
	 * @return array
	 */
	function directories()
	{
		return $this->directories;
	}

	/**
	 * return only files
	 *
	 * @return array
	 */
	function files()
	{
		return $this->files;
	}

	/**
	 * returns true if $filename is '.' or '..'
	 *
	 * @param string $filename
	 * @return bool
	 */
	function get_is_dot($filename)
	{
		return (bool) (($filename == '.') || ($filename == '..'));
	}

	/**
	 * Match filename against a pattern. can be used for any string
	 *
	 * @param string $pattern
	 * @param string $filename
	 * @return bool
	 */
	function matches_pattern($pattern, $filename)
	{
		return fnmatch($pattern, $filename);
	}
}

?>