<?php

/**
 * Library for star ratings
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-07-20
 */

	/**
	 * prints interactive stars
	 *
	 * @param int $answer_id
	 * @param string $msg
	 * @return string
	 */
	function print_stars($answer_id, $msg = '', $qid = NULL)
	{
		require_once DOC_ROOT.'/orbicon/modules/infocentar/class/rating.class.php';
		require_once DOC_ROOT.'/orbicon/modules/infocentar/class/question.class.php';

		if($qid === NULL) {
			global $dbc;
			$q = new Question();
			$question = $dbc->_db->fetch_array($q->get_question($_GET['id'], 1));
			$qid = $question['id'];
			unset($q);
		}
		
		$uid = $visitor['id'];

		// voting score
		$rating = new Rating(NULL);
		$rating_score = $rating->get_answer_rating($answer_id);
		$score = ($rating_score != NULL) ? floor($rating_score['score']) : 0;
		$voters = $rating_score['voters'];
		unset($rating);

		$msg = ($msg == '') ? _L('ic-vote-answer') : $msg;

		$stars = '<div class="vote_response" id="vote_response_'.$answer_id.'">' . $msg . '</div>';
		$i = 1;

		while($i <= 5) {

			$hilite_css = ($i <= $score) ? 'sg_starred' : '';

			$stars .= "<a
				href=\"javascript:void(null);\"
				id=\"sg_$answer_id".'_'."$i\"
				class=\"sg_a $hilite_css\"
				onmouseover=\"javascript:hilite_add($i, $answer_id);\"
				onmouseout=\"javascript:hilite_remove($answer_id);\"
				onclick=\"javascript: submit_vote($i, $answer_id, $qid);\"
				title=\"$i\">&nbsp;</a>";
			$i ++;
		}

		$stars .= '<div class="vote_score">' . intval($voters) . ' ' . _L('ic-votes') . '</div><br />';

		return $stars;
	}

	/**
	 * prints noninteractive stars for question
	 *
	 * @param int $qid
	 * @return string
	 */
	function print_totalrating_stars($total_rating)
	{
		$stars = '';
		$i = 1;

		while($i <= 5) {

			$hilite_css = ($i <= $total_rating) ? ' sg_starred' : '';

			$stars .= '<span class="sg_a'.$hilite_css.'">&nbsp;</span>';
			$i ++;
		}


		return $stars;
	}

?>