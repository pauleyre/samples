<?php


	$ex_promo = new Promo;

	$material = $ex_promo->get_promo($_GET['id']);

	echo '<p><a href="'.ORBX_SITE_URL.'/?'.$lang.'=orbicon/mod/peoplering&amp;sp=promo&amp;id='.$_GET['id'].'">'._L('pr-new-promo').'</a></p>';

?>

<table id="promo_listing">
<tr>
	<th>#</th>
	<th><?php echo _L('title');?></th>
	<th><?php echo _L('pr-type');?></th>
	<th><?php echo _L('pr-date');?></th>
</tr>
<?php
	$res_promo = $dbc->_db->fetch_assoc($material);

	while($res_promo) {
		// * report what media is set

		echo '
		<tr>
			<td>'.$res_promo['id'].'</td>
			<td>
				<a href="'.ORBX_SITE_URL.'/?'.$lang.'=orbicon/mod/peoplering&amp;sp=promo&amp;id='.$_GET['id'].'&amp;promoid='.$res_promo['id'].'">'.$res_promo['title'].'</a>
			</td>
			<td></td>
			<td>'.date($_SESSION['site_settings']['date_format'], $res_promo['created_timestamp']).'</td>
		</tr>
		';
		$res_promo = $dbc->_db->fetch_assoc($material);
}

?>
</table>