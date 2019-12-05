<?php

	$pr_id 			= $pr->get_prid_from_rid($_SESSION['user.r']['id']);
	$content 		= $pr->get_profile($pr_id);

	$display_content = '<div class="credits_msg">'._L('e.saldo').' <span>'.number_format($content['credits'], 2, ',', '.') . ' '._L('e.loccurr').'</span></div>';

?>