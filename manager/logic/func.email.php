<?php

/**
 * Validate email format
 *
 * @param string $email
 * @return bool
 */
function is_email($email)
{
	$email = trim($email);

	// quick exit
	if($email == '') {
		return false;
	}

	// these are minimum requirements
	if(strpos($email, '@') !== false && strpos($email, '.') !== false) {
		// THE pattern
		$pattern = '/^[a-z0-9_-][a-z0-9._-]+@([a-z0-9][a-z0-9-]*\.)+[a-z]{2,6}$/i';
		// go for it
		if(preg_match($pattern, $email)) {
			return true;
		}
	}

	return false;
}

?>