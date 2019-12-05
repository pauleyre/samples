<?php
/**
 * Display polls / surveys
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @version 2.0
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-07-01
 * @subpackage Polls
 */

	require_once DOC_ROOT.'/orbicon/modules/polls/class.polls.php';
	$poll = new Poll;
	$poll->display_control_links = true;
	return $poll->get_poll();
	unset($poll);

?>