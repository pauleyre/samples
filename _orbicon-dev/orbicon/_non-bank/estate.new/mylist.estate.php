<?php
/**
 * Estate ad list
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package OrbiconMOD
 * @subpackage Estate
 * @version 1.1
 * @link http://
 * @license http://
 * @since 2007-10-04
 */

	global $dbc, $orbicon_x;

	// pagination
	require_once DOC_ROOT . '/orbicon/class/class.pagination.php';

	$_GET['pp'] = (isset($_GET['pp'])) ? $_GET['pp'] : 10;
	$_GET['p'] = (isset($_GET['p'])) ? $_GET['p'] : 1;

	$pagination = new Pagination('p', 'pp');

	$read = $dbc->_db->query(sprintf('	SELECT 		COUNT(id)
										AS 			numrows
										FROM 		'.TABLE_ESTATE.'
										WHERE		(user_id=%s)',
										$dbc->_db->quote($_SESSION['user.r']['id'])));

	$row = $dbc->_db->fetch_assoc($read);
	$pagination->total = $row['numrows'];
	$pagination->split_pages();
	unset($read, $row);

	if(isset($_GET['archive'])) {
		set_estate_ad_status(intval($_GET['archive']), ESTATE_AD_ARCHIVED);
		redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.estate.new');
	}

	if(isset($_GET['unarchive'])) {
		set_estate_ad_status(intval($_GET['unarchive']), ESTATE_AD_LIVE);

		if(!$_SESSION['user.r']['estate_agency_status']) {
			archive_user_ads($_SESSION['user.r']['id']);
		}
		else {
			switch ($_SESSION['user.r']['estate_agency_level']) {
				case AGENCY_STATUS_15: archive_user_ads($_SESSION['user.r']['id'], 15); break;
				case AGENCY_STATUS_40: archive_user_ads($_SESSION['user.r']['id'], 40); break;
			}
		}

		redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.estate.new');
	}

	// delete ad
	if(isset($_GET['del'])) {
		delete_estate_ad($_GET['del']);
		redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.estate.new');
	}


	switch ($_SESSION['user.r']['estate_agency_level']) {
		case AGENCY_STATUS_15:
			$max_ads = 15;
			$get_more = '<a href="./?'.$orbicon_x->ptr.'=dobrodo%C5%A1li-na-stranice-marketinga&amp;no-override">Povećajte limit</a>';
		break;
		case AGENCY_STATUS_40: $max_ads = 40; break;
	}

	if($max_ads) {
		$restrict = "<p>Maksimalno aktivnih oglasa: <strong>$max_ads</strong> $get_more<p>";
	}


$render = '
<div id="user_navigation">
<a href="'.ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.estate.new&amp;page=add" class="ads">'._L('e.newad').'</a>
<a href="'.ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.peoplering&amp;sp=profile" class="profile">'._L('e.myprofile').'</a>
<a href="'.ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.peoplering&amp;sp=credits" class="credits">'._L('e.useraccount').'</a>
<a href="javascript: void(null);" onclick="javascript: __unload();" title="'._L('pr-exit').'" class="signout">'._L('pr-exit').'</a></div>


'.$restrict.'

<table style="width:100%">
    <tr class="title" style="font-weight:bold">
      <td class="broj">#</td>
      <td class="naziv">'._L('e.adtitle').'</td>
      <td class="kategorija">'._L('e.category').'</td>
      <td class="izmjena">'._L('e.modify').'</td>
      <td class="arhiva">'._L('e.archive').'</td>
      <td class="datum">'._L('e.date').'</td>
      <td class="ukloni">'._L('e.remove').'</td>
    </tr>';

	$q = sprintf('	SELECT 		*
					FROM 		'.TABLE_ESTATE.'
					WHERE		(user_id=%s)
					ORDER BY 	submited DESC
					LIMIT 		%s, %s',
	$dbc->_db->quote($_SESSION['user.r']['id']),
	$dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']), $dbc->_db->quote($_GET['pp']));

	$r = $dbc->_db->query($q);

	$item = $dbc->_db->fetch_object($r);
	$i = 1;

	while($item) {

		$bg = (($i % 2) == 0) ? '#ffffff' :'#cccccc';
		$archive_link = ($item->status == ESTATE_AD_LIVE) ? '<a href="'.ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.estate.new&amp;archive=' . $item->id.'">'._L('e.active').'</a>' : '<a href="'.ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.estate.new&amp;unarchive=' . $item->id.'">'._L('e.inactive').'</a>';
		$delete_link = '<a onmousedown="'.delete_popup($item->title).'" onclick="javascript:return false;" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.estate.new&amp;del='.$item->id.'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/delete.png" alt="'._L('delete').'" title="'._L('delete').'" /></a>';

		$preview = ($item->status == ESTATE_AD_PREVIEW) ? '&amp;preview' : '';

    	$render .= '<tr class="row" style="background-color:'.$bg.'">
      <td class="broj">'.$item->id.'</td>
      <td class="naziv"><a href="'.ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.estate.new&page=add&id=' . $item->id.$preview.'">'.$item->title.'</a></td>
      <td class="kategorija">'.$estate_type[$item->category].'</td>
      <td class="izmjena"><a href="'.ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.estate.new&page=add&id=' . $item->id.$preview.'">'._L('e.modify').'</a></td>
      <td class="arhiva">'.$archive_link.'</td>
      <td class="datum">'.date($_SESSION['site_settings']['date_format'], $item->submited).'</td>
      <td>'.$delete_link.'</td>
    </tr>';

		$item = $dbc->_db->fetch_object($r);
		$i ++;
	}

  $render .= '</table>

<div class="clean"></div>';

	$render .= $pagination->construct_page_nav(ORBX_SITE_URL . "/?{$orbicon_x->ptr}=mod.estate.new");

	return $render;

?>