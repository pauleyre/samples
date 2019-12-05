<?php

class CompareMatrix
{

	private $items;

	private $rendered_buffer;

	function add_item($title, $properties)
	{
		$this->items[] = array(	't' => $title,
								'p' => $properties
								);
	}

	function _render()
	{
		$width = intval(100 / count($this->items));

		$this->rendered_buffer = '<h1>Matrix</h1><div style="width:100%;display:inline;position:relative;">';

		// first create fields for properties on the left

		$this->rendered_buffer .= '<div style="float:left;width:'.$width.'%"><div>Title</div>';

		$first = reset($this->items);
		$titles = array_keys($first['p']);
		foreach ($titles as $title) {
			$this->rendered_buffer .= '<div>' . $title . '</div>';
		}

		$this->rendered_buffer .= '</div>';

		unset($first, $title, $titles);

		// now fill in items
		foreach ($this->items as $item) {

			$this->rendered_buffer .= '<div style="float:left;width:'.$width.'%"><div>' . $item['t'] . '</div>';

			foreach ($item['p'] as $property) {
				$this->rendered_buffer .= '<div>' . $property . '</div>';
			}

			$this->rendered_buffer .= '</div>';
		}

		$this->rendered_buffer .= '</div>';
	}

	function __toString()
	{
		$this->_render();

		return $this->rendered_buffer;
	}
}

?>