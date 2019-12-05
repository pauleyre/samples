<?php

/**
 * close tags
 *
 * @param unknown_type $text
 * @return unknown
 */
function close_tags($text)
{
	$tagstack = array();
	$stacksize = 0;
	$tagqueue = '';
	$newtext = '';

	while(preg_match('/<(\/?\w*)\s*([^>]*)>/', $text, $regex)) {
		$newtext .= $tagqueue;

		$i = strpos($text,$regex[0]);
		$l = strlen($regex[0]);

		// clear the shifter
		$tagqueue = '';
		// Pop or Push
		if ($regex[1][0] == '/') { // End Tag
			$tag = strtolower(substr($regex[1],1));
			// if too many closing tags
			if($stacksize <= 0) {
				$tag = '';
				//or close to be safe $tag = '/' . $tag;
			}
			// if stacktop value = tag close value then pop
			else if ($tagstack[$stacksize - 1] == $tag) { // found closing tag
				$tag = '</' . $tag . '>'; // Close Tag
				// Pop
				array_pop ($tagstack);
				$stacksize--;
			}
			else { // closing tag not at top, search for it
				for($j = $stacksize - 1; $j >= 0; $j--) {
					if($tagstack[$j] == $tag) {
					// add tag to tagqueue
						for ($k = $stacksize - 1; $k >= $j; $k--){
							$tagqueue .= '</' . array_pop ($tagstack) . '>';
							$stacksize--;
						}
						break;
					}
				}
				$tag = '';
			}
		}
		else { // Begin Tag
			$tag = strtolower($regex[1]);

			// Tag Cleaning

			// If self-closing or '', don't do anything.
			if((substr($regex[2],-1) == '/') || ($tag == '')) {
				// do nothing
			}
			// ElseIf it's a known single-entity tag but it doesn't close itself, do so
			else if($tag == 'br' || $tag == 'img' || $tag == 'hr' || $tag == 'input' || $tag == 'link' || $tag == 'meta' || $tag == 'xml' || $tag == 'param') {
				$regex[2] .= '/';
			}
			else {	// Push the tag onto the stack
				// If the top of the stack is the same as the tag we want to push, close previous tag
				if (($stacksize > 0) && ($tag != 'div') && ($tagstack[$stacksize - 1] == $tag)) {
					$tagqueue = '</' . array_pop ($tagstack) . '>';
					$stacksize--;
				}
				$stacksize = array_push ($tagstack, $tag);
			}

			// Attributes
			$attributes = $regex[2];

			if($attributes) {
				$attributes = ' '.$attributes;
			}
			$tag = '<'.$tag.$attributes.'>';
			//If already queuing a close tag, then put this tag on, too
			if ($tagqueue) {
				$tagqueue .= $tag;
				$tag = '';
			}
		}
		$newtext .= substr($text,0,$i) . $tag;
		$text = substr($text,$i+$l);
	}

	// Clear Tag Queue
	$newtext .= $tagqueue;

	// Add Remaining text
	$newtext .= $text;

	// Empty Stack
	while($x = array_pop($tagstack)) {
		$newtext .= '</' . $x . '>'; // Add remaining tags to close
	}

	return $newtext;
}

?>