<?php
/*-----------------------------------------------------------------------*
	Project........:	Orbicon X framework 2
	File...........:
	Version........:	1.0 (22/10/2006)
	Author.........:	Pavle Gardijan (pavle.gardijan@orbitum.net) - Orbitum d.o.o.
	Copyright......:	(c) 2006 Orbitum internet communications d.o.o. (www.orbitum.net)
	Created........:	01/07/2006
	Notes..........:
	Modified.......:
*-----------------------------------------------------------------------*/

	if(isset($_GET['delete_col'])) {
		$orbicon_x->delete_column($_GET['delete_col']);
	}
	$empty = 0;

	/*function ci_build_menu($current)
	{

		$menu = '<option value="">&mdash;</option>';
		global $dbc, $orbicon_x;

		$r = $dbc->_db->query(sprintf('	SELECT 		title, parent, permalink
										FROM 		'.TABLE_COLUMNS.'
										WHERE 		(language = %s)
										ORDER BY 	sort', $dbc->_db->quote($orbicon_x->ptr)));
		$a = $dbc->_db->fetch_assoc($r);

		while($a) {
			$selected = ($current == $a['permalink']) ? ' selected="selected"' : '';
			$_parent = (empty($a['parent'])) ? '' : ' ['.$a['parent'].']';
			$menu .= sprintf('<option value="%s"%s>%s%s</option>', $a['permalink'], $selected, $a['title'], $_parent);
			$a = $dbc->_db->fetch_assoc($r);
		}

		return $menu;
	}*/

?>

<table style="width:100%;font-size:11px">
<tbody>
<tr>
	<th><?php echo _L('ci_id'); ?></th>
	<th><?php echo _L('title'); ?></th>
	<th><?php echo _L('permalink'); ?></th>
	<th><?php echo _L('ci_parent'); ?></th>
	<th><?php echo _L('status'); ?></th>
	<th><?php echo _L('delete'); ?></th>
</tr>
<?php

	// do a full scan for invalid columns and their parents
	$r = $dbc->_db->query(sprintf('	SELECT 	id, parent, title, permalink
									FROM 	'.TABLE_COLUMNS.'
									WHERE 	(parent IS NOT NULL) AND
											(parent != \'\') AND
											(language = %s)',
									$dbc->_db->quote($orbicon_x->ptr)));

	$a = $dbc->_db->fetch_assoc($r);

	// loop for each subcolumn

	if(!$a) {
		$empty ++;
	}

	while($a) {

		$r_ = $dbc->_db->query(sprintf('
									SELECT 	id
									FROM 	'.TABLE_COLUMNS.'
									WHERE 	(permalink = %s) AND
											(language = %s)',
									$dbc->_db->quote($a['parent']), $dbc->_db->quote($orbicon_x->ptr)));

		$a_ = $dbc->_db->fetch_assoc($r_);

		// parent column doesn't exist
		if(empty($a_['id'])) {

			echo '<tr>
					<td>'.$a['id'].'</td>
					<td><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/columns&amp;edit='.$a['permalink'].'">'.$a['title'].'</a></td>
					<td>'.substr($a['permalink'], 0, 15).'...</td>
					<td>'.$a['parent'].'</td>
					<td>'._L('ci_parent_none').'</td>
					<td><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=/orbicon/mod/column-inspector&amp;delete_col='.$a['permalink'].'" title="'._L('delete').'" onclick="javascript:return false;" onmousedown="'.delete_popup($a['title']).'">
							<img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/delete.png" alt="'._L('delete').'" title="'._L('delete').'" />
						</a>
					</td>
				</tr>';
		}

		$a = $dbc->_db->fetch_assoc($r);
	}

	// permalink conflict scan



	// do a full scan for invalid columns and their parents
	$r_ = $dbc->_db->query(sprintf('	SELECT 				permalink
										FROM 				'.TABLE_COLUMNS.'
										WHERE				(language = %s)
										GROUP BY 			permalink
										HAVING 				(COUNT(permalink) > 1)', $dbc->_db->quote($orbicon_x->ptr)));

	$a_ = $dbc->_db->fetch_assoc($r_);

	$columns = array();

	if(!$a_) {
		$empty ++;
	}

	while($a_) {

	$r = $dbc->_db->query(sprintf('
									SELECT 	id, parent, title, permalink
									FROM 	'.TABLE_COLUMNS.'
									WHERE 	(permalink = %s) AND
											(language = %s)',
									$dbc->_db->quote($a_['permalink']), $dbc->_db->quote($orbicon_x->ptr)));

	$a = $dbc->_db->fetch_assoc($r);

	while($a) {
		echo '<tr>
				<td>'.$a['id'].'</td>
				<td>'.$a['title'].'</td>
				<td>'.substr($a['permalink'], 0, 15).'...</td>
				<td>'.$a['parent'].'</td>
				<td>'._L('ci_perma_conflict').'</td>
				<td><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=/orbicon/mod/column-inspector&amp;delete_col='.$a['permalink'].'" title="'._L('delete').'" onclick="javascript:return false;" onmousedown="'.delete_popup($a['title']).'">
						<img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/delete.png" alt="'._L('delete').'" title="'._L('delete').'" />
					</a>
				</td>
			</tr>';
		$a = $dbc->_db->fetch_assoc($r);
	}

		$a_ = $dbc->_db->fetch_assoc($r_);
	}

	// nothing found
	if($empty == 2) {
		echo '<tr><td colspan="6">'._L('ci_none').'</td></tr>';
	}

?>
</tbody>
</table>