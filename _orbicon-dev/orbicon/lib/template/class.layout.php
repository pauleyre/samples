<?php
/**
 * Layout class
 *
 * Example:
 * <code>
 * <?php
 *
 * require_once 'class.layout.php';
 *
 * $layout = new Layout(Layout::DEFAULT_CONTENT_TYPE, 'windows-1250');
 * $id = $layout->insert_top(new Template('test.html'));
 * $layout->insert_html_tag(new Tag('div'));
 * $layout->insert_html_tag(new Tag('img'), 'html');
 * $layout->insert_after('dummy text', $id);
 *
 * // output with proper headers
 * $layout->render_and_display();
 *
 * // output without headers
 * echo $layout;
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
 * @since 2007-08-14
 */

class Layout
{
	/**
	 * default layout content encoding
	 *
	 */
	const DEFAULT_CONTENT_ENCODING = 'UTF-8';

	/**
	 * default layout content type
	 *
	 */
	const DEFAULT_CONTENT_TYPE = 'text/html';

	/**
	 * layout parts group
	 *
	 * @var object
	 */
	private $layout;
	/**
	 * rendered buffer
	 *
	 * @var string
	 */
	private $rendered_buffer;

	/**
	 * Layout content type. Defaults to text/html
	 *
	 * @var string
	 */
	private $content_type;
	/**
	 * Layout content encoding. Defaults to UTF-8
	 *
	 * @var string
	 */
	private $content_encoding;

	/**
	 * HTML tags
	 *
	 * @var array
	 */
	private $html_tags;

	/**
	 * setup
	 *
	 * @param string $type
	 * @param string $encoding
	 */
	function __construct($type = self::DEFAULT_CONTENT_TYPE, $encoding = self::DEFAULT_CONTENT_ENCODING)
	{
		$this->set_encoding($encoding);
		$this->set_type($type);
		$this->layout = new Group();
		if($this->_get_is_html_layout()) {
			$this->_setup_html_tags();
		}
	}

	/**
	 * setup HTML tags array
	 *
	 */
	private function _setup_html_tags()
	{
		$this->html_tags = array('null' => '');
	}

	/**
	 * render all layout members
	 *
	 */
	private function render()
	{
		foreach($this->layout->members as $layout_parts) {
			$this->rendered_buffer .= (string) $layout_parts;
		}

		// render any html tags
		if($this->_get_is_html_layout()) {
			$container_k = $container_v = array();

			// prepare arrays
			foreach ($this->html_tags as $k => $v) {
				$container_k[] = "</$k>";
				$container_v[] = "$v</$k>";
			}
			// do the replacement
			$this->rendered_buffer = str_ireplace($container_k, $container_v, $this->rendered_buffer);
			unset($container_k, $container_v);
			$this->_setup_html_tags();
		}
		else {
			$this->_setup_html_tags();
		}
	}

	function __toString()
	{
		$this->render();
		return $this->rendered_buffer;
	}

	/**
	 * outputs rendered buffer and destroys it
	 *
	 */
	private function display()
	{
		$this->send_content_header();
		echo $this->get_clean();
	}

	/**
	 * renders layout and outputs it to screen with
	 * properly set headers. destroys rendered buffer
	 *
	 */
	public function render_and_display()
	{
		$this->render();
		$this->display();
	}

	/**
	 * insert layout part on top and move others down
	 *
	 * @param mixed $template
	 * @return int
	 */
	public function insert_top($template)
	{
		array_unshift($this->layout->members, $template);
		return 0;
	}

	/**
	 * insert layout part on bottom
	 *
	 * @param mixed $template
	 * @return int
	 */
	public function insert_bottom($template)
	{
		// just use insert
		return $this->insert($template);
	}

	/**
	 * insert layout part before another one
	 *
	 * @param mixed $template
	 * @param int $id
	 * @return int
	 */
	public function insert_before($template, $id)
	{
		return $this->_insert_helper($template, intval($id - 1));
	}

	/**
	 * insert layout part after another one
	 *
	 * @param mixed $template
	 * @param int $id
	 * @return int
	 */
	public function insert_after($template, $id)
	{
		return $this->_insert_helper($template, intval($id + 1));
	}

	/**
	 * helper method for insert methods
	 *
	 * @param mixed $template
	 * @param int $new_id
	 * @return int
	 */
	private function _insert_helper($template, $new_id)
	{
		// sanity checks
		if($new_id < 1) {
			return $this->insert_top($template);
		}
		else if($new_id > ($this->layout->size() - 1)) {
			return $this->insert_bottom($template);
		}

		$array = array_splice($this->layout->members, $new_id, count($this->layout->members), $template);

		$this->layout->members = array_merge($this->layout->members, $array);

		return $new_id;
	}

	/**
	 * append layout part
	 *
	 * @param mixed $template
	 * @return int
	 */
	public function insert($template)
	{
		$this->layout->merge($template);
		return ($this->layout->size() - 1);
	}

	/**
	 * replace layout part with another
	 *
	 * @param mixed $template
	 * @param int $id
	 * @return int
	 */
	public function replace($template, $id)
	{
		if(!array_key_exists($id, $this->layout->members)) {
			trigger_error('ID out of bounds, ' . $id . ' given', E_USER_WARNING);
			return false;
		}

		$this->layout->members[$id] = $template;
		return $id;
	}

	/**
	 * delete layout part
	 *
	 * @param int $id
	 */
	public function delete($id)
	{
		unset($this->layout->members[$id]);
	}

	/**
	 * destroy layout
	 *
	 */
	public function destroy()
	{
		$this->layout->destroy();
	}

	/**
	 * get layout IDs list
	 *
	 * @return array
	 */
	public function get_ids()
	{
		return array_keys($this->layout->members);
	}

	/**
	 * merge layouts
	 *
	 * @param object $layout
	 * @todo fix this
	 */
	public function merge($layout)
	{
		if (!is_object($layout)) {
			trigger_error('merge() expects parameter 1 to be object, ' . gettype($layout) . ' given', E_USER_WARNING);
			return false;
		}

		$this->layout->merge($layout->get_parts());
	}

	/**
	 * get layout parts
	 *
	 * @return array
	 */
	public function get_parts()
	{
		return $this->layout->members;
	}

	/**
	 * delete buffer
	 *
	 */
	public function clean()
	{
		$this->rendered_buffer = null;
	}

	/**
	 * return and delete buffer
	 *
	 * @return string
	 */
	public function get_clean()
	{
		$contents = $this->rendered_buffer;
		$this->clean();
		return $contents;
	}

	/**
	 * clean and replace buffer
	 *
	 * @param string $content
	 */
	public function set_clean($content)
	{
		$this->clean();
		$this->rendered_buffer = $content;
	}

	/**
	 * return current content encoding
	 *
	 * @return string
	 */
	public function get_encoding()
	{
		return $this->content_encoding;
	}

	/**
	 * set content encoding
	 *
	 * @param string $encoding
	 */
	public function set_encoding($encoding)
	{
		$this->content_encoding = $encoding;
		// reset html tags array
		if($this->_get_is_html_layout()) {
			$this->_setup_html_tags();
		}
	}

	/**
	 * return current content type
	 *
	 * @return string
	 */
	public function get_type()
	{
		return $this->content_type;
	}

	/**
	 * set content type
	 *
	 * @param string $content_type
	 */
	public function set_type($content_type)
	{
		$this->content_type = $content_type;
	}

	/**
	 * sends Content-Type header
	 *
	 */
	public function send_content_header()
	{
		header('Content-Type: '.$this->get_type().'; charset=' . $this->get_encoding(), true);
	}

	/**
	 * return size of rendered buffer
	 *
	 * @return int
	 */
	public function size()
	{
		return strlen($this->rendered_buffer);
	}

	/**
	 * Inserts HTML tag to layout. If parent is set, tag will be appended
	 * to that parent (head, title, html, body). Defaults to body
	 * Does not support self-closing tags (br, link)
	 *
	 * @param string $tag
	 * @param string $parent
	 * @todo add support for basic CSS selectors (div, div#id, div.classname)
	 * @return bool
	 */
	public function insert_html_tag($tag, $parent = 'body')
	{
		if($this->_get_is_html_layout()) {
			// append to parent
			if(!array_key_exists($parent, $this->html_tags)) {
				$this->html_tags[$parent] = '';
			}
			$this->html_tags[$parent] .= $tag;
			return true;
		}
		trigger_error('Unsupported content type. Must be text/html, ' . $this->get_type() . ' given', E_USER_WARNING);
		return false;
	}

	/**
	 * return true if layout is a HTML document
	 *
	 * @return bool
	 */
	private function _get_is_html_layout()
	{
		return (bool) ($this->get_type() == 'text/html');
	}
}

?>