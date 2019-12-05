<?php
/**
 * Estate ad list
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @subpackage Estate
 * @version 1.1
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-10-04
 */

	// pagination
	require_once DOC_ROOT . '/orbicon/class/class.pagination.php';

	$_GET['pp'] = (isset($_GET['pp'])) ? $_GET['pp'] : 10;
	$_GET['p'] = (isset($_GET['p'])) ? $_GET['p'] : 1;

	$pagination = new Pagination('p', 'pp');

	$read = $dbc->_db->query('	SELECT 		COUNT(id)
								AS 			numrows
								FROM 		'.TABLE_ESTATE);

	$row = $dbc->_db->fetch_assoc($read);
	$pagination->total = $row['numrows'];
	$pagination->split_pages();
	unset($read, $row);

	// archive ad
	if(isset($_GET['archive'])) {
		set_estate_ad_status(intval($_GET['archive']), ESTATE_AD_ARCHIVED);
		redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/estate');
	}

	// unarchive ad
	if(isset($_GET['unarchive'])) {
		set_estate_ad_status(intval($_GET['unarchive']), ESTATE_AD_LIVE);
		redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/estate');
	}

	// sponsor ad
	if(isset($_GET['sponsor'])) {
		set_estate_ad_sponsor(intval($_GET['sponsor']), ESTATE_AD_SPONSORED);
		redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/estate');
	}

	// unsponsor ad
	if(isset($_GET['unsponsor'])) {
		set_estate_ad_sponsor(intval($_GET['unsponsor']), ESTATE_AD_NONSPONSORED);
		redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/estate');
	}

	// delete ad
	if(isset($_GET['del'])) {
		delete_estate_ad($_GET['del']);
		redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/estate');
	}

?>

<input onclick="javascript:redirect('<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/estate&page=add'; ?>');" value="Novi oglas" type="button" />

<label for="quick_id">Otvori oglas #</label> <input type="text" value="" id="quick_id" name="quick_id" /> <input type="button" value="OK" onclick="javascript:redirect('<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/estate&page=add&id='; ?>' + $('quick_id').value);" />

<table style="width:100%">
    <tr style="font-weight:bold">
      <td>#</td>
      <td>Naslov oglasa</td>
      <td>Kategorija</td>
      <td>Izmjeni</td>
      <td>Arhiviraj</td>
      <td>Sponzoriran</td>
      <td>Datum</td>
      <td>Ukloni</td>
    </tr>

<?php

	$q = sprintf('	SELECT 		*
					FROM 		'.TABLE_ESTATE.'
					ORDER BY 	submited DESC
					LIMIT 		%s, %s',
	$dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']), $dbc->_db->quote($_GET['pp']));

	$r = $dbc->_db->query($q);

	$item = $dbc->_db->fetch_object($r);
	$i = 1;

	while($item) {

		$bg = (($i % 2) == 0) ? '#ffffff' :'#cccccc';
		$archive_link = ($item->status == ESTATE_AD_LIVE) ? '<a href="'.ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/estate&amp;archive=' . $item->id.'">Arhiviraj</a>' : '<a href="'.ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/estate&amp;unarchive=' . $item->id.'">Odarhiviraj</a>';
		$sponsor_link = ($item->sponsored == ESTATE_AD_NONSPONSORED) ? '<a href="'.ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/estate&amp;sponsor=' . $item->id.'">Sponzoriraj</a>' : '<a href="'.ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/estate&amp;unsponsor=' . $item->id.'">Odsponzoriraj</a>';
		$delete_link = '<a onmousedown="'.delete_popup($item->title).'" onclick="javascript:return false;" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/estate&amp;del='.$item->id.'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/delete.png" alt="'._L('delete').'" title="'._L('delete').'" /></a>';


?>

    <tr style="background-color: <?php echo $bg; ?>">
      <td><?php echo $item->id; ?></td>
      <td><a href="<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/estate&page=add&id=' . $item->id; ?>"><?php echo $item->title; ?></a></td>
      <td><?php echo $estate_type[$item->category]; ?></td>
      <td><a href="<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/estate&page=add&id=' . $item->id; ?>">Izmjeni</a></td>
      <td><?php echo $archive_link; ?></td>
      <td><?php echo $sponsor_link; ?></td>
      <td><?php echo date($_SESSION['site_settings']['date_format'], $item->submited); ?></td>
      <td><?php echo $delete_link; ?></td>
    </tr>
<?php

		$item = $dbc->_db->fetch_object($r);
		$i ++;
	}

?>

  </table>

<div class="clean"></div>

<?php

	echo $pagination->construct_page_nav(ORBX_SITE_URL . "/?{$orbicon_x->ptr}=orbicon/mod/estate");

?>