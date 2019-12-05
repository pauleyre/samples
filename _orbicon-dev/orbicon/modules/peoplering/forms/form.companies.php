<?php

	// pagination
	require_once DOC_ROOT . '/orbicon/class/class.pagination.php';

	$_GET['pp'] = (isset($_GET['pp'])) ? $_GET['pp'] : 10;
	$_GET['p'] = (isset($_GET['p'])) ? $_GET['p'] : 1;
	$pagination = new Pagination('p', 'pp');

	$cat_sql = (isset($_GET['category'])) ? sprintf(' AND ((industry_a = %s) OR (industry_b = %s) OR (industry_c = %s))', $dbc->_db->quote($_GET['category']), $dbc->_db->quote($_GET['category']), $dbc->_db->quote($_GET['category'])) : '';

	$read = $dbc->_db->query('	SELECT 		COUNT(id)
								AS 			numrows
								FROM 		pring_company
								WHERE		(title != \'\')'
								. $cat_sql);

	$row = $dbc->_db->fetch_assoc($read);
	$pagination->total = $row['numrows'];
	$pagination->split_pages();
	unset($read, $row);

	switch ($_GET['sort']) {
		case 'title_desc': $sort_comp = 'title DESC'; break;
		case 'ads_asc': $sort_comp = 'total_estate_ads ASC'; break;
		case 'ads_desc': $sort_comp = 'total_estate_ads DESC'; break;
		default: $sort_comp = 'title ASC'; break;
	}

	$q = '	SELECT 		*
			FROM 		pring_company
			WHERE		(title != \'\')
			'.$cat_sql.'
			ORDER BY	'.$sort_comp.'
			LIMIT 		'.$dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']).', ' . $dbc->_db->quote($_GET['pp']);

	$r = $dbc->_db->query($q);
	$company = $dbc->_db->fetch_object($r);

	include_once DOC_ROOT.'/orbicon/modules/forms/class.form.php';
	$form = new Form;
	$industries = $form->get_pring_db_table('pring_industry', true);
	$form = null;

	if(isset($_GET['category'])) {
		if($orbicon_x->ptr == 'en') {
			switch ($_GET['category']) {
				case 1: $head_title = 'Real-estate agency'; break;
				case 2: $head_title = 'Construction and fitting'; break;
				case 3: $head_title = 'Investment page'; break;
			}
		}
		elseif ($orbicon_x->ptr == 'de') {
			switch ($_GET['category']) {
				case 1: $head_title = 'Immobilienagentur'; break;
				case 2: $head_title = 'Bauen und Einrichten'; break;
				case 3: $head_title = 'Investoren'; break;
			}
		}
		elseif ($orbicon_x->ptr == 'sr') {
			switch ($_GET['category']) {
				case 1: $head_title = 'Agencije za nekretnine'; break;
				case 2: $head_title = 'Građenje i opremanje'; break;
				case 3: $head_title = 'Stranice za investitore'; break;
			}
		}
		else {
			$head_title = $industries[$_GET['category']];
		}

		$orbicon_x->set_page_title($head_title);
		$orbicon_x->add2breadcrumbs($head_title);
	}
	else {
		$orbicon_x->set_page_title(_L('pr-companies-index'));
		$orbicon_x->add2breadcrumbs(_L('pr-companies-index'));
	}

	// append lead text above the list
	$r_intro = $dbc->_db->query(sprintf('	SELECT 		intro_text
											FROM 		pring_industry
											WHERE 		(id = %s)', $dbc->_db->quote($_GET['category'])));
	$a_intro = $dbc->_db->fetch_assoc($r_intro);
	$lead_txt = '';

	if($a_intro['intro_text']) {

		$r_ = $dbc->_db->query(sprintf('SELECT 		content
										FROM 		'.MAGISTER_CONTENTS.'
										WHERE 		(live = 1) AND
													(hidden = 0) AND
													(question_permalink = %s) AND
													(language = %s)
										ORDER BY 	uploader_time', $dbc->_db->quote($a_intro['intro_text']), $dbc->_db->quote($orbicon_x->ptr)));
		$a_ = $dbc->_db->fetch_assoc($r_);

		while($a_) {
			$lead_txt .= $a_['content'];
			$a_ = $dbc->_db->fetch_assoc($r_);
		}
		$dbc->_db->free_result($r_);
	}

	if($lead_txt) {
		$display_content .= '<div class="pring_categories_lead_txt">' . $lead_txt . '</div>';
	}

	// free memory
	unset($lead_txt, $a_);


	global $orbx_mod;
	if($orbx_mod->validate_module('estate') &&
	($_GET['category'] == 2) ||
	(($_GET['category'] >= 4) &&
	($_GET['category'] <= 12))) {
		$display_content .= '
		<div id="results">
        <h3></h3>
        <label for="select_url"><span>'._L('e.category').'</span></label>

<select id="select_url" onchange=
"javascript: redirect(orbx_site_url + \'/?\' + __orbicon_ln + \'=mod.peoplering&sp=companies&category=\' + $(\'select_url\').options[$(\'select_url\').selectedIndex].value);">
<option value="2">'._L('e.choose').':</option>
<option value="4">Gotovi objekti</option>
<option value="5">Parketi</option>
<option value="6">Stolarija</option>
<option value="7">Opremanje kupaonica</option>
<option value="8">Bravarija</option>
<option value="9">Bazeni</option>
<option value="10">Građevni materijal</option>
<option value="11">Interijeri</option>
<option value="12">Garažni sustavi</option>
</select>
      </div>';
	}
	elseif ($orbx_mod->validate_module('estate') && (($_GET['category'] == 1) || ($_GET['category'] == 3))) {

		$ar_sort = array(
			'title_asc' => _L('e.titleaz'),
			'title_desc' => _L('e.titleza'),
			'ads_desc' => 'Broju oglasa: najviše',
			'ads_asc' => 'Broju oglasa: najmanje'
		);

		if($_GET['category'] == 1) {
			$title = 'Popis agencija za nekretnine';
		}
		elseif ($_GET['category'] == 3) {
			$title = 'Popis investitora';
		}

		$display_content .= '<div id="results">
        <h3>'.$title.'</h3>
        <label for="select_url"><span> '._L('e.orderby').'</span></label>

<select id="select_url" onchange=
"javascript: redirect(orbx_site_url + \'/?\' + __orbicon_ln + \'=mod.peoplering&sp=companies&category=1&sort=\' + $(\'select_url\').options[$(\'select_url\').selectedIndex].value);">
'.print_select_menu($ar_sort, $_GET['sort'], true).'
</select>
      </div>';
	}

	while($company) {

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

		$company->url = ((strpos($company->url, 'http://') === false) && $company->url) ? 'http://' . $company->url : $company->url;

		$url_details = url(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.peoplering&amp;sp=company_details&amp;company=' . $company->id, ORBX_SITE_URL . '/company_details/' . $company->id);

		$display_content .= '
<div class="company">
	<div class="title">
		<h4><a href="'.$url_details.'">'.$company->title.'</a></h4>
		<div class="clear"></div>
	</div>
	<a href="'.$url_details.'"><img src="'.$logo.'" alt="'.$company->title.'" title="'.$company->title.'" class="logo" /></a>
	<ul class="details">';

		if($company->address) {
			$display_content .= '<li class="address"><span>'._L('pr-address').':</span> '.$company->address.', '.$company->zip.' '.$company->city.'</li>';
		}

		if($company->industry_a) {
			$display_content .= '<li class="industry_a"><span>'._L('pr-industry').' #1:</span> <strong><a href="'.ORBX_SITE_URL . "/?{$orbicon_x->ptr}=mod.peoplering&amp;sp=companies&amp;category=" . $company->industry_a.'">'.$industries[$company->industry_a].'</a></strong></li>';
		}

		if($company->industry_b) {
			$display_content .= '<li class="industry_b"><span>'._L('pr-industry').' #2:</span> <strong><a href="'.ORBX_SITE_URL . "/?{$orbicon_x->ptr}=mod.peoplering&amp;sp=companies&amp;category=" . $company->industry_b.'">'.$industries[$company->industry_b].'</a></strong></li>';
		}

		if($company->industry_c) {
			$display_content .= '<li class="industry_c"><span>'._L('pr-industry').' #3:</span> <strong><a href="'.ORBX_SITE_URL . "/?{$orbicon_x->ptr}=mod.peoplering&amp;sp=companies&amp;category=" . $company->industry_c.'">'.$industries[$company->industry_c].'</a></strong></li>';
		}

		if($company->phone) {
			$display_content.= '<li class="no_border phone"><span>'._L('pr-phone').': </span>'.format_phone($company->phone, $company->phone_a, $company->phone_b).'</li>';
		}

		if($company->mail) {
			$display_content .= '<li class="no_border mail"><span>'._L('pr-comp-mail').': </span><a href="mailto:'.$company->mail.'">'.$company->mail.'</a></li>';
		}

		if($company->url) {
			$display_content .= '<li class="no_border url"><span>'._L('pr-url').': </span><a href="'.$company->url.'" target="_blank">'.$company->url.'</a></li>';
		}

	$display_content .= '</ul>
	<div class="clear"></div>
</div>';

		$company = $dbc->_db->fetch_object($r);
	}

	$display_content .= $pagination->construct_page_nav(ORBX_SITE_URL . "/?{$orbicon_x->ptr}=mod.peoplering&amp;sp=companies&amp;category=" . $_GET['category'] . '&amp;sort=' . $_GET['sort']);

	// unset these as they invalidate cache
	unset($_GET['p'], $_GET['pp']);

?>