<?php

	global $orbx_mod, $dbc, $orbicon_x;
	if(!$orbx_mod->validate_module('peoplering') || !$orbx_mod->validate_module('estate')) {
		return '';
	}

	$q = '	SELECT 		*
			FROM 		pring_company
			WHERE		(title != \'\') AND
						(logo != \'\') AND
						(industry_a != \'\') AND
						(
							contact IN (SELECT id FROM pring_contact WHERE estate_agency_status = \'1\')
						)
			ORDER BY	RAND()
			LIMIT 		1';

	$r = $dbc->_db->query($q);
	$company = $dbc->_db->fetch_object($r);

	$url_details = ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.peoplering&amp;sp=company_details&amp;company=' . $company->id;

	$logo = $company->logo;

	if(is_file(DOC_ROOT . '/site/venus/' . $logo)) {
		if(is_file(ORBX_SITE_URL.'/site/venus/thumbs/t-' . $logo)) {
			$logo = ORBX_SITE_URL.'/site/venus/thumbs/t-' . $logo;
		}
		else {
			$logo = ORBX_SITE_URL.'/site/venus/' . $logo;
		}
	}
	else {
		$logo = ORBX_SITE_URL.'/orbicon/modules/peoplering/gfx/unknownUser.gif';
	}

	include_once DOC_ROOT . '/orbicon/modules/peoplering/class/class.peoplering.php';
	$pr = new Peoplering();
	$rid = $pr->get_rid_from_prid($company->contact);
	$pr = null;

	$_REQUEST['filter_by_user'] = $rid;

	include_once DOC_ROOT . '/orbicon/modules/estate/inc.estate.php';

	$title = explode('-', $company->title);
	$title = $title[0];

	$ad = print_estate_ads(false, 5, 1);

	if(strpos($ad, _L('e.noresults')) !== false) {
		$ad = '';
	}

	return '
	<div class="agency_focus">
		<div class="title">
			<h3><a href="'.$url_details.'">'.$title.'</a></h3>
			<div class="clear"></div>
			<a href="'.$url_details.'"><img src="'.$logo.'" alt="'.$company->title.'" title="'.$company->title.'" class="logo" /></a>
		</div>
		<div class="ad">
		'.$ad.'
		</div>
	</div>';

?>