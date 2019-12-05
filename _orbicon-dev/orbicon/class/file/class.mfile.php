<?php
/**
 * Mirror File handling class
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
 * @since 2007-07-06
 */

// we'll need this one
require_once DOC_ROOT . '/orbicon/class/file/class.file.php';
require_once DOC_ROOT . '/orbicon/class/group/class.group.php';

class Mfile extends File
{
	/**
	 * mirror copies container (Group object)
	 *
	 * @var object
	 */
	var $_mirror_copies;

	/**
	 * parent File object for this mfile
	 *
	 * @var object
	 */
	var $_parent_file;

	/**
	 * miror main filename
	 *
	 * @var string
	 */
	var $name;

	/**
	 * currently selected active copy
	 *
	 * @var string
	 */
	var $_active_copy;

	/**
	 * open/create new mirror file for writing and/or reading
	 *
	 * @param string $name
	 * @param string $mode
	 */
	function mfile($name, $mode = '')
	{
		$this->__construct($name, $mode);
	}

	function __construct($name, $mode = '')
	{
		// sometimes we might reference another File/Mfile object so we want its name here
		if(is_object($name)) {
			$this->name = $name->name;
			// try to close it
			$name->close();
		}
		else {
			$this->name = $name;
		}

		// determine our active copy. defaults to one
		if($this->get_max() < 1) {
			$this->set_max(1);
		}

		$this->_parent_file = new File($this->_active_copy, $mode);
	}

	/**
	 * close and release all handles
	 *
	 */
	function __destruct()
	{
		Mfile::close();
	}

	/**
	 * set maximum number of copies
	 *
	 * @param int $num
	 */
	function set_max($num = 1)
	{
		// sanity check
		$num = ($num < 1) ? 1 : intval($num);

		foreach($this->_mirror_copies->members as $i => $copy) {
			if(is_file("$this->name.$i")) {
				if($i > $num) {
					unlink("$this->name.$i");
				}
			}
			else {
				$new = new File("$this->name.$i", 'wb');
				$new->close();
			}
		}
	}

	/**
	 * Enter description here...
	 *
	 */
	function get_max()
	{
		// set all mirror copies we find
		$this->_mirror_copies = new Group(glob("$this->name{.*}", GLOB_BRACE));
		// set active copy
		$this->_active_copy = $this->_mirror_copies->random();

		// if empty we default to 0 and refill members list
		if(empty($this->_active_copy)) {
			$this->_active_copy = "$this->name.0";
			$this->_mirror_copies->members = array($this->_active_copy);
		}
		// count total number
		return count($this->_mirror_copies->members);
	}

	function close()
	{
		foreach($this->_mirror_copies->members as $copy) {
			if($this->_parent_file->name != $copy) {
				copy($this->_parent_file->name, $copy);
			}
		}
		$this->export();
	}

	function all()
	{
		return $this->_mirror_copies;
	}

	function export()
	{
		backup($this->name);
		copy($this->_parent_file, $this->name);
	}

}

?>