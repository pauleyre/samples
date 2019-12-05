<?php
/**
 * Venus frontpage
 *
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconFE
 * @subpackage Venus
 * @version 1.5
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-05-01
 */

	$sort_by = (empty($_GET['sort_by'])) ? 'date' : $_GET['sort_by'];

	switch($sort_by) {
		case 'name': $sort_by = 'permalink'; break;
		case 'bytes': $sort_by = 'size DESC'; break;
		case 'cat': $sort_by = 'category'; break;
		default : $sort_by = 'uploader_time DESC'; break;
	}

	if($_GET['show_only'] == '_orbx_unsorted') {
		$show_only = ' AND ((category = \'\') OR (category IS NULL))';
	}
	else {
		$show_only = (empty($_GET['show_only'])) ? '' : ' AND (category = '.$dbc->_db->quote($_GET['show_only']).')';
	}

	if(isset($_GET['q'])) {
		$search_only = ' AND (permalink LIKE ' . $dbc->_db->quote('%' . $_GET['q'] . '%') . ')';
	}

	// pagination
	require_once DOC_ROOT . '/orbicon/class/class.pagination.php';

	$_GET['pp'] = (isset($_GET['pp'])) ? $_GET['pp'] : 9;
	$_GET['p'] = (isset($_GET['p'])) ? $_GET['p'] : 1;

	$pagination = new Pagination('p', 'pp');

	$read = $dbc->_db->query('	SELECT 		COUNT(id) AS numrows
								FROM 		'.VENUS_IMAGES.'
								WHERE 		(live = 1) AND
											(hidden = 0)' . $show_only . $search_only);
	$row = $dbc->_db->fetch_assoc($read);
	$pagination->total = $row['numrows'];
	$pagination->split_pages();
	unset($read, $row);

	if(isset($_POST['perform_file_actions']) && !empty($_POST['files_actions'])) {
		if(!empty($_POST['current_files'])) {

			$files = explode('|', $_POST['current_files']);

			if($_POST['files_actions'] == 'archive') {
				// Include TAR Class
				include_once DOC_ROOT . '/orbicon/3rdParty/tarmanager/tar.class.php';
				// Creating a NEW Tar file
				$tar = new tar();
			}

			foreach($files as $value) {
				if($_POST['file_chk_'.$value] == 1) {
					if($_POST['files_actions'] == 'delete') {
						$r = $dbc->_db->query(sprintf('SELECT permalink
						FROM '.VENUS_IMAGES.'
						WHERE (id = %s)
						LIMIT 1', $dbc->_db->quote($value)));
						$a = $dbc->_db->fetch_assoc($r);

						unlink(DOC_ROOT.'/site/venus/'.$a['permalink']);
						unlink(DOC_ROOT.'/site/venus/thumbs/t-'.$a['permalink']);
						$dbc->_db->query(sprintf('DELETE FROM '.VENUS_IMAGES.' WHERE (permalink = %s) LIMIT 1', $dbc->_db->quote($a['permalink'])));
					}
					else if($_POST['files_actions'] == 'banner') {
						$name = $dbc->_db->query(sprintf('SELECT permalink FROM '.VENUS_IMAGES.' WHERE (id = %s) LIMIT 1', $dbc->_db->quote($value)));
						$name = $dbc->_db->fetch_assoc($name);

						$dbc->_db->query(sprintf('INSERT INTO '.TABLE_BANNERS.' (title, permalink, language) VALUES (%s, %s, %s)', $dbc->_db->quote($name['permalink']), $dbc->_db->quote($name['permalink']), $dbc->_db->quote($orbicon_x->ptr)));
					}
					else if(strpos($_POST['files_actions'], 'cat_switch_') !== false) {
						$category = str_replace('cat_switch_', '', $_POST['files_actions']);
						$q_ = sprintf('UPDATE '.VENUS_IMAGES.'
						SET category=%s
						WHERE (id=%s)
						LIMIT 1', $dbc->_db->quote($category), $dbc->_db->quote($value));
						$dbc->_db->query($q_);
					}
					else if($_POST['files_actions'] == 'archive') {
						$r = $dbc->_db->query(sprintf('SELECT permalink
						FROM '.VENUS_IMAGES.'
						WHERE (id = %s)
						LIMIT 1', $dbc->_db->quote($value)));
						$a = $dbc->_db->fetch_array($r);
						$tar->addFile(DOC_ROOT . '/site/venus/' . $a['permalink']);
					}
				}
			}

			if($_POST['files_actions'] == 'archive') {
				$archive = DOC_ROOT . '/site/mercury/archive_'.sprintf('%u', adler32(time() * uniqid())).'.tgz';
				$tar->toTar($archive, true);	// Gzipped TAR
				unset($tar);
				include_once DOC_ROOT . '/orbicon/mercury/class.mercury.php';
				Mercury::insert_file_into_db($archive, false, NULL, true);

				echo '
				<script type="text/javascript"><!-- // --><![CDATA[
				var msg = "'.addslashes(_L('archive_created_q')).'";
				if(window.confirm(msg)) {
					redirect("'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mercury");
				}
// ]]></script>';
			}
		}
	}

?>
<style type="text/css">/*<![CDATA[*/

#files {
	font-size:90%;
}

#files .file_row:hover {
	background: #ffffcc;
}

/*]]>*/</style>
<script type="text/javascript"><!-- // --><![CDATA[

	var state;

	function checkUncheck(state)
	{
		var i;
		var type;
		var gallery = $('gallery');
		var cboxes = gallery.getElementsByTagName('INPUT');

		for(i = 0; i < cboxes.length; i++) {
			type = cboxes[i].type;
			type = type.toLowerCase();
			if(type == 'checkbox') {
				cboxes[i].checked = state;
			}
		}
	}

// ]]></script>
<form method="post" action="" name="gallery" id="gallery">

<div style="padding: 10px 0 0 0;">
	<table style="text-align:left;width:100%;" id="image_gallery" summary="Image gallery" title="Image gallery" cellpadding="0">
		<tr>
			<td colspan="2">
				<a href="javascript:void(null)" onclick="javascript: checkUncheck(true);"><?php echo _L('select_all'); ?></a> |
				<a href="javascript:void(null)" onclick="javascript: checkUncheck(false);"><?php echo _L('unselect_all'); ?></a><br />
<label for="files_actions"><?php echo _L('with_selected'); ?> : </label>
<select name="files_actions" id="files_actions">
	<option value="0"><?php echo _L('do_nothing'); ?></option>
	<option value="delete" style="background: #ffffff url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/delete.png) right top no-repeat;">
		<?php echo _L('delete'); ?>
	</option>
	<option value="banner" style="background: #ffffff url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/page_white_flash.png) right top no-repeat;">
		<?php echo _L('insert_into_banners'); ?>
	</option>
	<option value="archive" style="background: #ffffff url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/compress.png) right top no-repeat;">
		<?php echo _L('archive_files'); ?>
	</option>
	<optgroup label="<?php echo _L('move_to_category'); ?>">
	<?php

			$r = $dbc->_db->query('		SELECT 		*
										FROM 		'.VENUS_CATEGORIES.'
										ORDER BY 	permalink');
			$a = $dbc->_db->fetch_assoc($r);

			while($a) {
				echo '<option style="background: #ffffff url('.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/expand.png) right top no-repeat;" value="cat_switch_'.$a['permalink'].'">'.$a['name'].'</option>';

				$a = $dbc->_db->fetch_assoc($r);
			}
			$dbc->_db->free_result($r);

	?>
	</optgroup>
</select> <input type="submit" id="perform_file_actions" name="perform_file_actions" value="<?php echo _L('submit'); ?>" />

			</td>
			<td><br />
			<input onkeypress="javascript: if(get_enter_pressed(event)) {return false;}" id="search_q" type="text" value="<?php echo $_GET['q']; ?>" /> <input type="button" value="<?php echo _L('search'); ?>" onclick="javascript: redirect('<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/venus' ;?>&q=' + encodeURIComponent($('search_q').value));" />
			</td>
		</tr>
		<tr>
		<?php

			$current_files = null;

			$r = $dbc->_db->query(sprintf('	SELECT 		*
											FROM 		'.VENUS_IMAGES.'
											WHERE 		(live = 1) AND
														(hidden = 0) '.$show_only. ' %s ORDER BY %s LIMIT %s, %s', $search_only, $sort_by, $dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']), $dbc->_db->quote($_GET['pp'])));

			$file = $dbc->_db->fetch_assoc($r);
			$i = (isset($_GET['p'])) ? (1 + ($_GET['pp'] * ($_GET['p'] - 1))) : 1;

			while($file) {
				$current_files[] = $file['id'];
				$bg = (($i % 2) == 0) ? '#ffffff' :'#cccccc';

				$link = '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/venus&amp;read=expo/'.$file['permalink'].'/"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/edit.png" alt="'.$file['title'].'"  title="'.$file['title'].'" /></a>';

				$link_del = '<a onmousedown="' . delete_popup($file['title']) . '" onclick="javascript:return false;" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/venus&amp;del_file='.$file['permalink'].'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/delete.png" /></a>';

				$dl_link = '<a href="'.ORBX_SITE_URL.'/site/venus/'.$file['permalink'].'" title="'.$file['title'].'">'.substr($file['title'], 0, 20).'...</a>';

				// determine what to do with image
			if(get_extension($file['permalink']) == 'swf') {
				include_once DOC_ROOT . '/orbicon/class/inc.mmedia.php';
				$img_link = swf_object(DOC_ROOT . '/site/venus/' . $file['permalink'], ORBX_SITE_URL . '/site/venus/' . $file['permalink']);
			}
			else {
				$img_link = (is_file(DOC_ROOT . '/site/venus/thumbs/t-'.$file['permalink'])) ? '<img class="thumb_image" src="'.ORBX_SITE_URL.'/site/venus/thumbs/t-'.$file['permalink'].'?'.md5(uniqid(rand(), true)).'" alt="'.$file['permalink'] . '" title="'.$file['permalink'].'" />' : '<img src="'.ORBX_SITE_URL.'/site/venus/'.$file['permalink'] . '?' . md5(uniqid(rand(), true)).'" class="thumb_image" alt="'.$file['permalink'].'" title="' . $file['permalink'] . '" />';
			}

				$img_category = ($file['category']) ? '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/venus&amp;show_only='.$file['category'].'">' . $file['category'] . '</a>' : '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/venus&amp;show_only=_orbx_unsorted">'._L('unsorted').'</a>';

				echo '
					<td style="border: 1px solid #cecece; width: 20%; vertical-align:top;">
						<table width="100%">
							<tr>
								<td colspan="2" style="background-color: #cecece;text-align:center;"><label for="file_chk_'.$file['id'].'">'.$dl_link.'</label></td>
							</tr>
							<tr>
								<td style="background-color: #cecece;text-align:center;">
									<input tabindex="'.$i.'" type="checkbox" value="1" id="file_chk_'.$file['id'].'" name="file_chk_'.$file['id'].'" /><br />
									'.$link.'<br />
									'.$link_del.'
								</td>
								<td style="font-size: 90%;">
									<div style="width:150px; overflow:auto;">'.$img_link.'</div><br /><label for="file_chk_'.$file['id'].'">
									<strong>MIME:</strong> '.get_mime_by_ext(get_extension($file['permalink'])).'<br />
									<strong>'._L('size').':</strong> '.get_file_size(DOC_ROOT.'/site/venus/'.$file['permalink']).'<br />
									<strong>'._L('category').':</strong> '.$img_category.'<br />
									<strong>'._L('uploaded').':</strong> '.date($_SESSION['site_settings']['date_format'], $file['uploader_time']).'</label>
								</td>
							</tr>
						</table>';

				$if_third_end_it = ($i % 3);

				if($if_third_end_it == 0){
					echo '</td></tr><tr>';
				}
				else {
					echo '</td>';
				}

				$file = $dbc->_db->fetch_assoc($r);
				$i++;
			}

			$dbc->_db->free_result($r);

			$current_files = (is_array($current_files)) ? implode('|', $current_files) : null;
	?>
		</tr>
	</table>
</div>
<input type="hidden" id="current_files" name="current_files" value="<?php echo $current_files; ?>" />
</form>

<div class="clean"></div>

<?php

	echo $pagination->construct_page_nav(ORBX_SITE_URL . "/?{$orbicon_x->ptr}=orbicon/venus&amp;show_only=".$_GET['show_only'].'&amp;q='.$_GET['q'].'&amp;sort_by='.$_GET['sort_by']);

?>