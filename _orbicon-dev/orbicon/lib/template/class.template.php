<?php
/**
 * Template class
 *
 * Example:
 * <code>
 *
 * # test.html
 *
 * <html>
 * <body>
 * <!-- REPLACE_ME -->
 * </body>
 * </html>
 *
 * # END test.html
 *
 * <?php
 *
 * require_once 'class.template.php';
 *
 * $tpl = new Template('test.html');
 * $tpl->set('REPLACE_ME', 'Sheeps are running over the hills');
 * // output to screen
 * echo $tpl;
 *
 * // or use with Layout class
 *
 * $layout = new Layout();
 * $layout->insert($tpl);
 * $layout->render_and_display();
 *
 * ?>
 * </code>
 *
 * @author Alen Novaković <alen.novakovic@orbitum.net>
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-08-14
 */

class Template
{
	/**
	 * Template opening tag
	 *
	 */
	const OPENING_TAG = '<!--';

	/**
	 * Template label closing tag
	 *
	 */
	const CLOSING_TAG = '-->';

	/**
	 * statistic counter
	 *
	 * @var int
	 */
	private static $invocation = 0;

	/**
	 * compress HTML
	 *
	 * @var bool
	 */
	static $compress_html = false;

	/**
	 * Template file's full path
	 *
	 * @var string
	 */
	private $tpl_file;

	/**
	 * Parsed buffer
	 *
	 * @var string
	 */
	private $buffer;

	/**
	 * Template variables
	 *
	 * @var array
	 */
	public $tpl_var;

	/**
	 * Cached bind templates
	 *
	 * @var array
	 */
	private static $bind_cache;

	/**
	 * Build new template object
	 *
	 * @param string $file
	 * @return bool
	 */
	function __construct($file = null)
	{
		// another invocation
		self::$invocation ++;

		$this->tpl_var = array();
		$this->binds = array();

		if($file != null) {

			$this->tpl_file = $file;

			// check wether this file exist
			if(!is_file($this->tpl_file)){
				trigger_error('Template() expects parameter 1 to be a valid file, ' . $file . ' given', E_USER_WARNING);
				return false;
			}

			// call for template fetcher
			self::read_template_file();
		}

		return true;
	}

	/**
	 * reads template file into memory buffer
	 *
	 */
	private function read_template_file()
	{
		$this->buffer = file_get_contents($this->tpl_file);
	}

	/**
	 * sets template label
	 *
	 * @param string $label
	 * @param mixed $value
	 * @param bool $compress
	 */
	public function set($label, $value, $compress = false)
	{
		if($compress) {
			$value = $this->_compress($value);
		}
		// set new var into arrays
		$this->tpl_var[$label] = $value;
	}

	/**
	 * get contents of label
	 *
	 * @param string $label
	 * @return mixed
	 */
	public function get($label)
	{
		return $this->tpl_var[$label];
	}

	/**
	 * add opening and closing tags, quote and prepare as regular expression
	 *
	 * @param string $label
	 * @return string
	 */
	private function _normalize_label($label)
	{
		// this doesn't support spaces
		//$label = preg_quote(self::OPENING_TAG . $label . self::CLOSING_TAG);
		$label = preg_quote(self::OPENING_TAG) . '\s+' . preg_quote($label) . '\s+'. preg_quote(self::CLOSING_TAG);

		return "/$label/i";
	}

	/**
	 * parse template
	 *
	 */
	private function parse()
	{
		// get defined labels
		$labels = array_keys($this->tpl_var);
		// normalize labels
		$labels = array_map(array($this, '_normalize_label'), $labels);

		// get defined content
		$content = array_values($this->tpl_var);
		// compress the content if requested
		if(self::$compress_html) {
			$content = array_map(array($this, '_compress'), $content);
		}

		$this->buffer = preg_replace($labels, $content, $this->buffer);
	}

	/**
	 * helper function for compressing (HTML)
	 *
	 * @param string $content
	 * @return string
	 */
	private function _compress($content)
	{
		$content = min_str($content, true);

		//-- replace any non-space, with a space
		$content = preg_replace('/[\n\r\t]/', ' ', $content);

		//-- remove any double-up whitespace
		$content = preg_replace('/\s(?=\s)/', '', $content);

		//-- remove surrounding whitespace
		$content = trim($content);

		return $content;
	}

	/**
	 * parse and return template
	 *
	 * @return string
	 */
	function __toString()
	{
		// * process replacing labels with values
		$this->parse();

		return $this->buffer;
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

	/**
	 * Enter description here...
	 *
	 * @param string $label
	 * @param array $array
	 * @todo finish this
	 */
	public function bind($label, $array)
	{
		$bind = new Template();
		$bind->buffer = $bind->_parse_bind($label);
		foreach ($array as $k => $v) {
			$bind->set($k, $v);
		}

		// append more data
		$this->set($label, $this->get($label) . $bind);
		// unset
		$bind = null;
	}

	private function _parse_bind($label)
	{
		$label_open = preg_quote(self::OPENING_TAG) . '\s+' . preg_quote($label) . '\s+'. preg_quote(self::CLOSING_TAG);

		$label_close = preg_quote(self::OPENING_TAG) . '\s+' . preg_quote("#$label") . '\s+'. preg_quote(self::CLOSING_TAG);

		var_dump(preg_replace($label, 'it works', $this->buffer));
	}
}

?>