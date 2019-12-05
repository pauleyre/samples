<?php

	function get_categories($add_extras = false)
	{
		$cats = array(

'Agronomija',
'Antropologija',
'Arheologija',
'Arhitektura',
'Astronomija',
'Biologija',
'Ekonomija',
'Film',
'Filozofija',
'Fizika',
'Glazba',
'Hrvatska',
'Hrvatski jezik i književnost',
'Informatika',
'Internacionalni jezici',
'Kemija',
'Matematika',
'Medicina',
'Nutricionizam',
'Opća kultura',
'Politika',
'Povijest',
'Pravo',
'Promet',
'Psihologija',
'Ratna vještina',
'Religija',
'Roditelji i djeca',
'Sociologija',
'Sport',
'Umjetnost',
'Zemljopis'

		);

		if($add_extras) {
			$extra = array(
'Dekada',
'Trash, Spam i Vic'
);
			$cats = array_merge($cats, $extra);
		}

		return $cats;
	}

	/**
	 * Print HTML select menu
	 *
	 * @param array $options		List of options
	 * @param string $default		Selected option
	 * @param bool $keys_values
	 * @return string				HTML option tags
	 *
	 */
	function categories_menu($default = null, $color_it = '', $add_extras = false)
	{
		$menu = '';

		if($color_it) {
			$color_it = "style=\"color: $color_it\"";
		}

		$categories = get_categories($add_extras);
		foreach($categories as $category) {
			$selected = ($category == $default) ? ' selected' : '';
			$menu .= "<option $selected $color_it>$category</option>";
		}

		return $menu;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $input
	 * @return unknown
	 */
	function valid_category($input)
	{
		return in_array($input, get_categories(true));
	}

?>