<?php

	$q = sprintf('	SELECT 		*
					FROM 		pring_company
					WHERE		(id = %s)
					LIMIT 		1', $dbc->_db->quote($_GET['company']));

	$r = $dbc->_db->query($q);
	$company = $dbc->_db->fetch_object($r);

	include_once DOC_ROOT.'/orbicon/modules/forms/class.form.php';
	$form = new Form;
	$industries = $form->get_pring_db_table('pring_industry', true);
	$form = null;

	$orbicon_x->set_page_title($company->title);
	$orbicon_x->add2breadcrumbs($company->title);

	$logo = $company->logo;

	if(is_file(DOC_ROOT . '/site/venus/' . $logo)) {
		$logo = ORBX_SITE_URL.'/site/venus/' . $logo;
	}
	else {
		$logo = ORBX_SITE_URL.'/orbicon/modules/peoplering/gfx/unknownUser.gif';
	}

	$company->url = ((strpos($company->url, 'http://') === false) && $company->url) ? 'http://' . $company->url : $company->url;

	// append lead text above the list
	$lead_txt = '';

	if($company->intro_text) {

		$r_ = $dbc->_db->query(sprintf('SELECT 		content
										FROM 		'.MAGISTER_CONTENTS.'
										WHERE 		(live = 1) AND
													(hidden = 0) AND
													(question_permalink = %s) AND
													(language = %s)
										ORDER BY 	uploader_time', $dbc->_db->quote($company->intro_text), $dbc->_db->quote($orbicon_x->ptr)));
		$a_ = $dbc->_db->fetch_assoc($r_);

		while($a_) {
			$lead_txt .= $a_['content'];
			$a_ = $dbc->_db->fetch_assoc($r_);
		}
		$dbc->_db->free_result($r_);
	}

	if($lead_txt) {
		$lead_txt = '<div class="additional_info">' . $lead_txt . '</div>';
	}

		$display_content .= '
<div id="company">
        <h1 class="title">'.$company->title.'</h1>
		<img class="logo" src="'.$logo.'" alt="'.$company->title.'" title="'.$company->title.'" />

	 <dl class="info">';

		if($company->address) {
			$display_content .= '<dt class="address"><strong>'._L('pr-address').':</strong></dt>
			<dd class="address">'.$company->address.', '.$company->zip.' '.$company->city.'</dd>';
		}

		if($company->industry_a) {
			$display_content .= '<dt class="industry_a"><strong>'._L('pr-industry').' #1:</strong></dt>
			 <dd class="industry_a"><a href="'.ORBX_SITE_URL . "/?{$orbicon_x->ptr}=mod.peoplering&amp;sp=companies&amp;category=" . $company->industry_a.'">'.$industries[$company->industry_a].'</a></dd>';
		}

		if($company->industry_b) {
			$display_content .= '<dt class="industry_b"><strong>'._L('pr-industry').' #2:</strong></dt>
			 <dd class="industry_b"><a href="'.ORBX_SITE_URL . "/?{$orbicon_x->ptr}=mod.peoplering&amp;sp=companies&amp;category=" . $company->industry_b.'">'.$industries[$company->industry_b].'</a></dd>';
		}

		if($company->industry_c) {
			$display_content .= '<dt class="industry_c"><strong>'._L('pr-industry').' #3:</strong></dt>
			 <dd class="industry_c"><a href="'.ORBX_SITE_URL . "/?{$orbicon_x->ptr}=mod.peoplering&amp;sp=companies&amp;category=" . $company->industry_c.'">'.$industries[$company->industry_c].'</a></dd>';
		}

		if($company->phone) {
			$display_content.= '<dt class="phone"><strong>'._L('pr-phone').':</strong></dt>
			<dd class="phone">'.format_phone($company->phone, $company->phone_a, $company->phone_b).'</dd>';
		}

		if($company->mail) {
			$display_content .= '<dt class="mail"><strong>'._L('pr-comp-mail').':</strong></dt>
			<dd class="mail"><a href="mailto:'.$company->mail.'">'.$company->mail.'</a></dd>';
		}

		if($company->url) {
			$display_content .= '<dt class="url"><strong>'._L('pr-url').':</strong></dt>
			<dd class="url"><a target="_blank" href="'.$company->url.'">'.$company->url.'</a></dd>';
		}

	$display_content .= '</dl>

	<div class="clear"></div>
	 '.$lead_txt.'
	<div class="clear"></div>
</div>';

	// display estate ads for this user
	global $orbx_mod;
	if($orbx_mod->validate_module('estate')) {
		$rid = $pr->get_rid_from_prid($company->contact);
		$_REQUEST['filter_by_user'] = $rid;
		include_once DOC_ROOT . '/orbicon/modules/estate/inc.estate.php';

		$q = '	SELECT 		COUNT(id) AS total
				FROM 		'.TABLE_ESTATE.'
				WHERE		(status = '.$dbc->_db->quote(ESTATE_AD_LIVE).') AND
							(user_id = '.$dbc->_db->quote($rid).')';

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		update_agency_ads_num(intval($company->id), intval($a['total']));

		$display_content .= '<div id="results"><h3>Broj dodanih oglasa: '.$a['total'].'</h3></div>';
		$display_content .= print_estate_ads();
	}

?>