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

	require_once DOC_ROOT.'/orbicon/modules/banners/class.banners.php';
	$banners = new Banners;

	if(isset($_POST['perform_file_actions']) && !empty($_POST['files_actions'])) {
		if(!empty($_POST['current_files'])) {
			$files = explode('|', $_POST['current_files']);
			foreach($files as $value) {
				if($_POST['file_chk_'.$value] == 1) {
					if($_POST['files_actions'] == 'delete') {
						$dbc->_db->query(sprintf('DELETE FROM '.TABLE_BANNERS.' WHERE (id = %s) LIMIT 1', $dbc->_db->quote($value)));
					}
					else if(strpos($_POST['files_actions'], 'zone_switch_') !== false) {
						$zone = str_replace('zone_switch_', '', $_POST['files_actions']);
						$q_ = sprintf('UPDATE '.TABLE_BANNERS.' SET
							zone=%s WHERE (id=%s) LIMIT 1', $dbc->_db->quote($zone), $dbc->_db->quote($value));
						$dbc->_db->query($q_);
					}
				}
			}
		}
	}

	if(isset($_GET['del_banner'])) {
		$dbc->_db->query(sprintf('	DELETE
									FROM 	'.TABLE_BANNERS.'
									WHERE 	(id = %s)
									LIMIT 	1', $dbc->_db->quote($_GET['del_banner'])));

		redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/banners');
	}

?>
<form method="post" action="" id="banner_list_form">
	<table width="100%" id="files">
		<tr style="font-weight:bold;">
		 <td>&nbsp;</td>
		 <td align=center><?php echo _L('preview'); ?></td>
		 <td align=center width="6%"><?php echo _L('delete'); ?></td>
		 <td width="11%"><a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr;?>=orbicon/mod/banners&amp;sort_by=display"><?php echo _L('displays'); ?></a></td>
		 <td width="11%"><a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr;?>=orbicon/mod/banners"><?php echo _L('clicks'); ?></a></td>
		 <td><a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr;?>=orbicon/mod/banners&amp;sort_by=name"><?php echo _L('filename'); ?></a></td>
		 <td><a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr;?>=orbicon/mod/banners&amp;sort_by=user"><?php echo _L('client'); ?> (RID)</a></td>
		 <td><a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr;?>=orbicon/mod/banners&amp;sort_by=area"><?php echo _L('zone'); ?></a></td>
		 <td><?php echo _L('target_url'); ?></td>
		  <td><?php echo _L('banner_type'); ?></td>
		  <td>Embed</td>
		</tr>


		<?php

			// pagination
			require_once DOC_ROOT . '/orbicon/class/class.pagination.php';

			$_GET['pp'] = (isset($_GET['pp'])) ? $_GET['pp'] : 20;
			$_GET['p'] = (isset($_GET['p'])) ? $_GET['p'] : 1;

			$pagination = new Pagination('p', 'pp');

			$read = $dbc->_db->query(sprintf('	SELECT 		COUNT(id) AS numrows
												FROM 		'.TABLE_BANNERS.'
												WHERE 		(language = %s)',
												$dbc->_db->quote($orbicon_x->ptr)));
			$row = $dbc->_db->fetch_assoc($read);

			$pagination->total = $row['numrows'];
			$pagination->split_pages();
			unset($read, $row);

			$sort_by = $_GET['sort_by'];
			$current_files = null;

			switch($sort_by) {
				case 'name': $sort_by = 'permalink DESC'; break;
				case 'display': $sort_by = 'displays DESC'; break;
				case 'user': $sort_by = 'client DESC'; break;
				case 'area': $sort_by = 'zone DESC'; break;
				default: $sort_by = 'clicks DESC'; break;
			}

			$start = (isset($_GET['start'])) ? intval($_GET['start']) : 0;
			$r = $dbc->_db->query(sprintf('	SELECT 		*
											FROM 		'.TABLE_BANNERS.'
											WHERE 		(language = %s)
											ORDER BY 	%s
											LIMIT 		%s, %s',
			$dbc->_db->quote($orbicon_x->ptr),
			$sort_by,
			$dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']), $dbc->_db->quote($_GET['pp'])));

			$file = $dbc->_db->fetch_assoc($r);

			$i = 1;

			while($file) {
				$current_files[] = $file['id'];
				$bg = (($i % 2) == 0) ? '#ffffff' :'#cccccc';

				$link_del = '<a onmousedown="'.delete_popup($file['title']).'" onclick="javascript: return false;" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/banners&amp;del_banner='.$file['id'].'"><img alt="'. _L('delete'). '" title="'. _L('delete'). '" src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/delete.png" /></a>';
				if(is_file(DOC_ROOT . '/site/mercury/' . $file['title'])) {
					$dl_link = '<a href="'.ORBX_SITE_URL.'/site/mercury/'.$file['title'].'">'.$file['title'].'</a>';
				}
				elseif (is_file(DOC_ROOT . '/site/venus/' . $file['title'])) {
					$dl_link = '<a href="'.ORBX_SITE_URL.'/site/venus/'.$file['title'].'">'.$file['title'].'</a>';
				}

				$ext = get_extension($file['title']);

				switch($ext) {
					case 'swf':
						$info = getimagesize(DOC_ROOT.'/site/mercury/'.$file['title']);
						$height = rounddown($info[1] * (48 / $info[0]));
						$preview = '<object
							type="application/x-shockwave-flash"
							data="'.ORBX_SITE_URL.'/site/mercury/'.$file['title'].'"
							height="'.$height.'" width="48">
							<param value="'.ORBX_SITE_URL.'/site/mercury/'.$file['title'].'" name="movie" />
							<param value="high" name="quality" />
						</object>';
					break;
					default:
						$info = getimagesize(DOC_ROOT.'/site/venus/'.$file['title']);
						$height = rounddown($info[1] * (48 / $info[0]));
						$preview = '<img height="'.$height.'" width="48" src="'.ORBX_SITE_URL.'/site/venus/'.$file['title'].'" />';
					break;
				}

				echo '<tr class="file_row" bgcolor='.$bg.'>
		 <td><label for="file_chk_'.$file['id'].'">'.$i.'.</label> <input tabindex="'.$i.'" type="checkbox" value="1" id="file_chk_'.$file['id'].'" name="file_chk_'.$file['id'].'" /></td>
		 <td align=center>'.$preview.'</td>
		 <td align=center>'.$link_del.'</td>
		 <td><input name="displays_'.$file['permalink'].'" id="displays_'.$file['permalink'].'" onblur="javascript:__banners_update(\''.$file['permalink'].'\');" size="6" type="text" value="'.$file['displays'].'" /></td>
		 <td><label for="file_chk_'.$file['id'].'">'.intval($file['clicks']).'</label></td>
		 <td><div style="overflow:hidden;"><label for="file_chk_'.$file['id'].'">'.$dl_link.'</label></div></td>
		 <td><input name="client_'.$file['permalink'].'" id="client_'.$file['permalink'].'" onchange="javascript:__banners_update(\''.$file['permalink'].'\');" value="'.$file['client'].'" /></td>
		 <td><select name="zone_'.$file['permalink'].'" id="zone_'.$file['permalink'].'" onchange="javascript:__banners_update(\''.$file['permalink'].'\');">'.$banners->get_banner_zones($file['zone']).'</select></td>
		 <td><input type="text" id="img_url_'.$file['permalink'].'" name="img_url_'.$file['permalink'].'" value="'.$file['img_url'].'"  onblur="javascript:__banners_update(\''.$file['permalink'].'\');" /></td>
		 <td><select id="type_'.$file['permalink'].'" name="type_'.$file['permalink'].'">'.print_select_menu($banners->types, $file['banner_type'], true).'</select></td>
		 <td><textarea>'.htmlspecialchars($banners->banner_renderer($file['title'], $file['permalink'], $file['img_url'], $file['banner_type'])).'</textarea></td>
		</tr>';

				$file = $dbc->_db->fetch_assoc($r);
				$i++;
			}
			$dbc->_db->free_result($r);

			$current_files = (is_array($current_files)) ? implode('|', $current_files) : $current_files;
	?>
	<tr>
		 <td>&nbsp;</td>
		 <td colspan="7">
		 <p>
<label for="files_actions"><?php echo _L('with_selected'); ?>: </label>
<select name="files_actions" id="files_actions">
	<option value="0"><?php echo _L('do_nothing'); ?></option>
	<option value="delete" class="delete"><?php echo _L('delete'); ?></option>
<optgroup label="<?php echo _L('move_to_zone'); ?>">
<?php

	$all = get_zones_array();

	foreach($all as $zone) {
		echo '<option class="expand" value="cat_switch_'.$zone['permalink'].'">'.$zone['title'].'</option>';
	}

?>
</optgroup>
</select> <input type="submit" id="perform_file_actions" name="perform_file_actions" value="OK" />
</p>
		 </td>
		 </tr>
	   </table>
<input type="hidden" id="current_files" name="current_files" value="<?php echo $current_files; ?>" />
</form>

<?php

	echo $pagination->construct_page_nav(ORBX_SITE_URL . "/?{$orbicon_x->ptr}=orbicon/mod/banners");

?>