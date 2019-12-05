<?php

global $orbx_mod, $orbicon_x;

if($orbx_mod->validate_module('stats')) {

	require_once DOC_ROOT . '/orbicon/modules/stats/class.stats.php';
	$props = $orbx_mod->load_info('top_search_keywords');
	$stats = new Statistics();
	$top_kw = /*array_keys*/($stats->get_top_attila_keywords(true, true));

	/*if($orbx_mod->validate_module('hpb.form')) {
		$top_kw = $stats->get_top_content(true);
		var_dump($top_kw);
		$top_kw = array_slice($top_kw, 0, $props['search_input']['limit']);
	}*/

	if($top_kw) {
		$top_kw = array_slice($top_kw, 0, $props['search_input']['limit']);
		//$kw_list = '<strong>'._L('top_search_keywords').':</strong><p id="kw_list">';

		foreach ($top_kw as $kw => $num) {
			$kw = trim($kw);
			if($orbx_mod->validate_module('e')) {
				if(strlen($kw) > 1) {
					$kw_list .= '<li><a href="'.ORBX_SITE_URL.'/?ln='.$orbicon_x->ptr.'&amp;submit=1&amp;'.$props['search_input']['id'].'='.urlencode($kw).'">'.$kw.'</a></li>';
				}
			}
			/*else if($orbx_mod->validate_module('hpb.form')) {

				if($num > 0) {
					$tp = 'fine';
				}
				if($num > 1) {
					$tp = 'diminutive';
				}
				if($num > 3) {
					$tp = 'tiny';
				}
				if($num > 9) {
					$tp = 'small';
				}
				if($num > 18) {
					$tp = 'medium';
				}
				if($num > 36) {
					$tp = 'large';
				}
				if($num > 50) {
					$tp = 'huge';
				}
				if($num > 100) {
					$tp = 'gargantuan';
				}
				if($num > 200) {
					$tp = 'colossal';
				}

				$kw_list .= '<li><a class="'.$tp.'" href="'.ORBX_SITE_URL.$kw.'"><strong>'.$kw.'</strong></a></li>';
			}*/
			else {


				if($num > 0) {
					$tp = 'fine';
				}
				if($num > 1) {
					$tp = 'diminutive';
				}
				if($num > 3) {
					$tp = 'tiny';
				}
				if($num > 9) {
					$tp = 'small';
				}
				if($num > 18) {
					$tp = 'medium';
				}
				if($num > 36) {
					$tp = 'large';
				}
				if($num > 50) {
					$tp = 'huge';
				}
				if($num > 100) {
					$tp = 'gargantuan';
				}
				if($num > 200) {
					$tp = 'colossal';
				}


				$kw_list .= '<li><a class="'.$tp.'" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=attila&amp;submit=1&amp;'.$props['search_input']['id'].'='.$kw.'"><strong>'.$kw.'</strong></a></li>';
			}
		}

		//$kw_list .= '</p>';

		return "<ul>$kw_list</ul>";
	}
}

return '';

?>