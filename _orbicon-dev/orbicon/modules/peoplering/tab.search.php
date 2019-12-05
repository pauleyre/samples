<?php

	$chk_username = ($_GET['search_type'] == 'username') ? 'checked="checked"' : '';
	$chk_name = ($_GET['search_type'] == 'name') ? 'checked="checked"' : '';
	$chk_mail = ($_GET['search_type'] == 'email') ? 'checked="checked"' : '';
	$chk_city = ($_GET['search_type'] == 'city') ? 'checked="checked"' : '';
	$chk_other = ($_GET['search_type'] == 'other') ? 'checked="checked"' : '';
	$chk_id = ($_GET['search_type'] == 'user_id') ? 'checked="checked"' : '';
	$chk_company = ($_GET['search_type'] == 'company') ? 'checked="checked"' : '';
	$chk_rid = ($_GET['search_type'] == 'user_rid') ? 'checked="checked"' : '';

	if(isset($_GET['block'])) {
		ban_user($_GET['block']);
	}

	if(isset($_GET['unblock'])) {
		ban_user($_GET['unblock'], 0);
	}

	if(isset($_GET['del_user']) && get_is_admin()) {
		$pr->delete_user($_GET['del_user']);
	}

	// pagination
	require_once DOC_ROOT . '/orbicon/class/class.pagination.php';

	$_GET['pp'] = (isset($_GET['pp'])) ? $_GET['pp'] : 20;
	$_GET['p'] = (isset($_GET['p'])) ? $_GET['p'] : 1;

	$pagination = new Pagination('p', 'pp');

?>
<form action="" method="get" id="pr_search">
<input type="hidden" name="<?php echo $orbicon_x->ptr; ?>" value="<?php echo $_GET[$orbicon_x->ptr]; ?>" />
	<p><label for="search_input"><?php echo _L('pr-search-for'); ?></label><br />
		<input type="text" name="search_input" id="search_input" value="<?php echo $_GET['search_input']; ?>" class="fld" />
		<input type="submit" id="submit_search" name="submit_search" value="<?php echo _L('pr-search-go'); ?>" />
	</p>
	<fieldset class="left"><legend><?php echo _L('pr-search-col'); ?></legend>
		<p>
			<input type="radio" name="search_type" id="name" value="name" <?php echo $chk_name;?> />
			<label for="name"><?php echo _L('name_surname'); ?></label>
		</p>
		<p>
			<input type="radio" name="search_type" id="username" value="username" <?php echo $chk_username; ?> />
			<label for="username"><?php echo _L('username'); ?></label>
		</p>
		<!--
		<p>
			<input type="radio" name="search_type" id="company" value="company" <?php echo $chk_company; ?> />
			<label for="company"><?php echo _L('pr-title'); ?></label>
		</p>
		-->
		<p>
			<input type="radio" name="search_type" id="user_id" value="user_id" <?php echo $chk_id; ?> />
			<label for="user_id">PID</label>
		</p>
		<p>
			<input type="radio" name="search_type" id="user_rid" value="user_rid" <?php echo $chk_rid; ?> />
			<label for="user_rid">RID</label>
		</p>
		<p>
			<input type="radio" name="search_type" id="email" value="email" <?php echo $chk_mail; ?> />
			<label for="email"><?php echo _L('email'); ?></label>
		</p>
		<p>
			<input type="radio" name="search_type" id="city" value="city" <?php echo $chk_city; ?> />
			<label for="city"><?php echo _L('pr-city'); ?></label>
		</p>
		<p>
			<input type="radio" name="search_type" id="other" value="other" <?php echo $chk_other; ?> />
			<label for="other"><?php echo _L('pr-other'); ?></label>
			<select onfocus="javascript:$('other').checked = true;" name="other_search" id="other_search">
				<?php echo print_select_menu(Peoplering::_get_other_pr_search(), $_GET['other_search'], true); ?>
			</select>
		</p>
	</fieldset>
	<div class="cleaner"></div>
</form>

<?php

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
?>

<table id="pr_listing">
	<tr>
		<th width="5%">RID</th>
		<th width="5%">PID</th>
		<th width="30%"><?php echo _L('pr-tbl-lbl-name'); ?></th>
		<th width="30%"><?php echo _L('pr-tbl-lbl-mail'); ?></th>
		<th width="15%"><?php echo _L('pr-tbl-lbl-username'); ?></th>
		<th><?php echo _L('pr-tbl-lbl-registered'); ?></th>
		<th><?php echo _L('pr-block'); ?></th>
		<th><?php echo _L('pr-delete'); ?></th>
	</tr>
<?php

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

			$rid = $pr->get_rid_from_prid($pr_listing['id']);

			// * join name and surname
			$surname = ($pr_listing['contact_surname'] == '') ? '' : '<strong>' . $pr_listing['contact_surname'] . '</strong>';
			$name = $surname . ' '. $pr_listing['contact_name'];
			$usr_name = $pr->get_username($rid);

			$link_del = '<a onmousedown="' . delete_popup($usr_name['username']) . '" onclick="javascript:return false;" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/peoplering&amp;del_user='.$rid.'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/delete.png" /></a>';

			$block_link = ($usr_name['banned']) ? '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/peoplering&amp;unblock='.$rid.'">'._L('pr-unblock').'</a>' : '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/peoplering&amp;block='.$rid.'">'._L('pr-block').'</a>';

			// * I-fuckin-E fix, if there is no value in field it won't show border ;(
			$name = ($name == '') ? '['._L('pr-not-available').']' : '<a href="'.ORBX_SITE_URL.'/?'.$lang.'=orbicon/mod/peoplering&amp;sp=profile&amp;id='.$pr_listing['id'].'">' . $name . '</a>';

			$mail = ($pr_listing['contact_email'] == '') ? '&nbsp;' : '<a href="mailto:' . $pr_listing['contact_email'] . '">' . $pr_listing['contact_email'] . '</a>';

			$usr_name = ($usr_name['username'] == '') ? '&nbsp;' : '<a href="'.ORBX_SITE_URL.'/?'.$lang.'=orbicon/mod/peoplering&amp;sp=profile&amp;id='.$pr_listing['id'].'">' . $usr_name['username'] . '</a>';

			$registered = ($pr_listing['registered'] == '') ? '['._L('pr-not-available').']' : date($_SESSION['site_settings']['date_format'] . ' H:i:s', $pr_listing['registered']);

			echo '
		<tr onmouseover="javascript: this.bgColor=\'#e6e6e6\'" onmouseout="javascript: this.bgColor=\'\'">
			<td>' . $rid . '</td>
			<td>' . $pr_listing['id'] . '</td>
			<td><a href="'.ORBX_SITE_URL.'/?'.$lang.'=orbicon/mod/peoplering&amp;sp=profile&amp;id='.$pr_listing['id'].'"><img src="'.$picture.'" style="width: 30px;" /></a> ' . $name . '</td>
			<td>' . $mail . '</td>
			<td>' . $usr_name . '</td>
			<td>' . $registered . '</td>
			<td>' . $block_link . '</td>
			<td>' . $link_del . '</td>
		</tr>';

			$pr_listing = $dbc->_db->fetch_assoc($query);
		}
	}
	else {
		echo '
	<tr>
		<td colspan="8" align="center">'. _L('pr-tbl-norec') .'.</td>
	</tr>
		';
	}
?>
</table>

<?php

	unset($_GET['p'], $_GET['pp']);

	$query = http_build_query($_GET);
	if($query) {
		$query = '/?' . $query;
	}
	else {
		$query = '/';
	}

	echo $pagination->construct_page_nav(ORBX_SITE_URL . $query);

?>