<?php

	include_once DOC_ROOT . '/orbicon/modules/polls/class.polls.php';
	$poll = new Poll;
	return $poll->get_past_polls();

?>