<?php
/**
 * HTML Tag class
 *
 * Example:
 * <code>
 * <?php
 *
 * require_once 'class.tag.php';
 *
 * $div = new Tag('div');
 * $div->innerHTML = $div->stats();
 * $div->style = 'border:2px inset red;padding:3px';
 * $div->id = 'x';
 * $div->set_type('textarea');
 *
 * echo $div;
 *
 * $img = new Tag('img');
 * $img->set_xhtml_markup(false);
 * $img->set_quotes('\'');
 * $img->SRC = 'abc.jpg';
 *
 * echo $img;
 *
 * ?>
 * </code>
 *
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-08-18
 */

class Tag
{
	/**
	 * Lowercase all attributes and tag names
	 *
	 * @var bool
	 */
	static $xhtml_markup = true;

	/**
	 * Statistic counter
	 *
	 * @var int
	 */
	private static $invocation = 0;

	/**
	 * Quote style (single or double)
	 *
	 * @var string
	 */
	static $quote = '"';

	/**
	 * Tag's attributes
	 *
	 * @var array
	 */
	private $_attributes;

	/**
	 * Tag's type
	 *
	 * @var string
	 */
	private $type;

	/**
	 * Tag's text/HTML content (as Javascript's innerHTML) where supported
	 *
	 * @var string
	 */
	public $innerHTML;

	/**
	 * Build a new Tag object
	 *
	 * @param string $type
	 */
	function __construct($type)
	{
		self::$invocation ++;
		$this->_attributes = array();
		$this->set_type($type);
	}

	function __get($property)
	{
		if(self::$xhtml_markup) {
			$property = strtolower($property);
		}
		return $this->_attributes[$property];
	}

	function __set($property, $value)
	{
		if(self::$xhtml_markup) {
			$property = strtolower($property);
		}
		$this->_attributes[$property] = $value;
	}

	/**
	 * Return tag's type
	 *
	 * @return string
	 */
	public function get_type()
	{
		return $this->type;
	}

	/**
	 * Set tag's type
	 *
	 * @param string $type
	 */
	public function set_type($type)
	{
		if(self::$xhtml_markup) {
			$this->type = strtolower($type);
		}
		else {
			$this->type = $type;
		}
	}

	/**
	 * return true if tag's self-closing
	 *
	 * @return bool
	 */
	private function _get_is_selfclosing()
	{
		$selfclosing = array(	'br',
								'link',
								'meta',
								'xml',
								'input',
								'param',
								'img',
								'hr'
							);

		return in_array($this->type, $selfclosing);
	}

	/**
	 * parse all attributes, innerHTML and return tag
	 *
	 * @return string
	 */
	private function _parse()
	{
		// help to determine space character status
		$counter = 0;
		$final = '<' . $this->type;

		if(is_array($this->_attributes)) {
			foreach($this->_attributes as $attribute => $value) {
				$space = ($counter == 0) ? ' ' : '';
				$final .= $space . $attribute .'='. self::$quote . $value . self::$quote;
				$counter ++;
			}
		}

		// self-closing exit here
		if($this->_get_is_selfclosing()) {
			if(self::$xhtml_markup) {
				return $final . '/>';
			}
			else {
				return $final . '>';
			}
		}
		else {
			$final = $final. '>' . $this->innerHTML . '</' . $this->type . '>';
		}

		return $final;
	}

	/**
	 * return parsed tag
	 *
	 * @return string
	 */
	function __toString()
	{
		return $this->_parse();
	}

	/**
	 * return number of object invocations
	 *
	 * @return int
	 */
	public function stats()
	{
		return self::$invocation;
	}
}

?>