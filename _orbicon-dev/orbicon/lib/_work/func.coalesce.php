<?php

	/**
	 * A function that will take any number of arguments, and return the first one that isn't equal to false
	 *
	 * @return mixed
	 */
	function coalesce()
	{
		// Loop through all arguments given
		foreach (func_get_args() as $value) {
			// If this argument doesn't equal false, return it
			if ($value) {
				return $value;
			}
		}
	}

	// Try this on a list of arguments.  Should return 42
	// echo coalesce('', 0, NULL, false, 1-1, 42, 'triccare', '0.0');

?>
