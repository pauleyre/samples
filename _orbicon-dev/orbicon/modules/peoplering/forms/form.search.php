<?php

	$chk_username = ($_GET['search_type'] == 'username') ? 'checked="checked"' : '';
	$chk_name = ($_GET['search_type'] == 'name') ? 'checked="checked"' : '';
	$chk_mail = ($_GET['search_type'] == 'email') ? 'checked="checked"' : '';
	$chk_city = ($_GET['search_type'] == 'city') ? 'checked="checked"' : '';
	$chk_other = ($_GET['search_type'] == 'other') ? 'checked="checked"' : '';
	$chk_id = ($_GET['search_type'] == 'user_id') ? 'checked="checked"' : '';
	$chk_rid = ($_GET['search_type'] == 'user_rid') ? 'checked="checked"' : '';

	// pagination
	require_once DOC_ROOT . '/orbicon/class/class.pagination.php';

	$_GET['pp'] = (isset($_GET['pp'])) ? $_GET['pp'] : 20;
	$_GET['p'] = (isset($_GET['p'])) ? $_GET['p'] : 1;

	$pagination = new Pagination('p', 'pp');

$display_content = '<form action="" method="get" id="pr_search">
<input type="hidden" name="sp" value="search" />
<input type="hidden" name="'.$orbicon_x->ptr.'" value="'.$_GET[$orbicon_x->ptr].'" />
	<p><label for="search_input">'. _L('pr-search-for').'</label><br />
		<input type="text" name="search_input" id="search_input" value="'. $_GET['search_input'].'" class="fld" />
		<input type="submit" id="submit_search" name="submit_search" value="'. _L('pr-search-go').'" />
	</p>
	<fieldset class="left"><legend>'. _L('pr-search-col').'</legend>
		<p>
			<input type="radio" name="search_type" id="name" value="name" '. $chk_name.' />
			<label for="name">'. _L('name_surname').'</label>
		</p>
		<p>
			<input type="radio" name="search_type" id="username" value="username" ' . $chk_username.' />
			<label for="username">' . _L('username') . '</label>
		</p>
		<p>
			<input type="radio" name="search_type" id="email" value="email" '. $chk_mail .' />
			<label for="email">'. _L('email').'</label>
		</p>
		<p>
			<input type="radio" name="search_type" id="city" value="city" '. $chk_city .' />
			<label for="city">'. _L('pr-city').'</label>
		</p>
		<p>
			<input type="radio" name="search_type" id="other" value="other" '. $chk_other.' />
			<label for="other">'. _L('pr-other').'</label>
			<select onfocus="javascript:$(\'other\').checked = true;" name="other_search" id="other_search">
				'. print_select_menu(Peoplering::_get_other_pr_search(), $_GET['other_search'], true).'
			</select>
		</p>
	</fieldset>
	<div class="cleaner"></div>
</form>';

	if($_GET['submit_search']){

		// * initialize object for searching
		$prs_obj = new Peoplering($_GET);
		$query = $prs_obj->search_peoplering();
		$query_total = $prs_obj->search_peoplering(false);
	}
	else {

		// * procces complete listing
		// _ NOTE _ : $pr is defined in pring.php
		$query = $pr->search_peoplering();
		$query_total = $pr->search_peoplering(false);
	}

$display_content .= '
<table id="pr_listing">
	<tr>
		<th width="30%">'. _L('pr-tbl-lbl-name').'</th>
		<th width="30%">'. _L('pr-tbl-lbl-mail').'</th>
		<th width="15%">'. _L('pr-tbl-lbl-username').'</th>
		<th>'. _L('pr-tbl-lbl-registered').'</th>
	</tr>';

	$pagination->total = $dbc->_db->num_rows($query_total);
	$pagination->split_pages();

	if($dbc->_db->num_rows($query) > 0) {
		$pr_listing = $dbc->_db->fetch_assoc($query);
		while($pr_listing) {

			if(is_file(DOC_ROOT . '/site/venus/' . $pr_listing['picture'])) {
				$picture = ORBX_SITE_URL.'/site/venus/' . $pr_listing['picture'];
			}
			else {
				$picture = ORBX_SITE_URL.'/orbicon/modules/peoplering/gfx/unknownUser.gif';
			}

			// * join name and surname
			$surname = ($pr_listing['contact_surname'] == '') ? '' : '<strong>' . $pr_listing['contact_surname'] . '</strong>';
			$name = $surname . ' '. $pr_listing['contact_name'];
			$usr_name = $pr->get_username($pr->get_rid_from_prid($pr_listing['id']));

			// * I-fuckin-E fix, if there is no value in field it won't show border ;(
			$name = ($name == '') ? '['._L('pr-not-available').']' : '<a href="'.url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=user&amp;user='.$usr_name['username'], ORBX_SITE_URL . '/~' . $usr_name['username']).'">' . $name . '</a>';

			$mail = ($pr_listing['contact_email'] == '') ? '&nbsp;' : '<a href="mailto:' . $pr_listing['contact_email'] . '">' . $pr_listing['contact_email'] . '</a>';

			$usr_name_display = ($usr_name['username'] == '') ? '&nbsp;' : '<a href="'.url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=user&amp;user='.$usr_name['username'], ORBX_SITE_URL . '/~' . $usr_name['username']).'">' . $usr_name['username'] . '</a>';

			$registered = ($pr_listing['registered'] == '') ? '['._L('pr-not-available').']' : date($_SESSION['site_settings']['date_format'] . ' H:i:s', $pr_listing['registered']);

			$display_content .= '
		<tr onmouseover=\'javascript: this.bgColor="#e6e6e6"\' onmouseout=\'javascript: this.bgColor=""\'>
			<td><a href="'.url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=user&amp;user='.$usr_name['username'], ORBX_SITE_URL . '/~' . $usr_name['username']).'"><img src="'.$picture.'" style="width: 30px;" /></a> ' . $name . '</td>
			<td>' . $mail . '</td>
			<td>' . $usr_name_display . '</td>
			<td>' . $registered . '</td>
		</tr>
			';

			$pr_listing = $dbc->_db->fetch_assoc($query);
		}
	}
	else {
		$display_content .= '
	<tr>
		<td colspan="4" align="center">'. _L('pr-tbl-norec') .'</td>
	</tr>
		';
	}

	$display_content .= '</table>';

	unset($_GET['p'], $_GET['pp']);

	$query = http_build_query($_GET);
	if($query) {
		$query = '/?' . $query;
	}
	else {
		$query = '/';
	}

	$display_content .= $pagination->construct_page_nav(ORBX_SITE_URL . $query);

?>