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

	include_once DOC_ROOT . '/orbicon/mercury/class.mercury.php';

	if(isset($_POST['perform_file_actions']) && !empty($_POST['files_actions'])) {
		if(!empty($_POST['current_files'])) {

			if($_POST['files_actions'] == 'archive') {
				// Include TAR Class
				include_once DOC_ROOT . '/orbicon/3rdParty/tarmanager/tar.class.php';
				// Creating a NEW Tar file
				$tar = new tar();
			}

			$files = explode('|', $_POST['current_files']);

			foreach($files as $value) {
				if($_POST['file_chk_' . $value]) {
					if($_POST['files_actions'] == 'delete') {
						unlink(DOC_ROOT . '/site/gfx/' . base64_decode($value));
					}
					else if($_POST['files_actions'] == 'archive') {
						$tar->addFile(DOC_ROOT . '/site/gfx/' . base64_decode($value));
					}
				}
			}

			if($_POST['files_actions'] == 'archive') {
				$archive = DOC_ROOT . '/site/mercury/archive_'.sprintf('%u', adler32(time() * uniqid())).'.tgz';
				$tar->toTar($archive, true);	// Gzipped TAR
				unset($tar);
				Mercury::insert_file_into_db($archive, false, null, true);

				echo '
				<script type="text/javascript"><!-- // --><![CDATA[
				var msg = "'._L('archive_created_q').'";
				if(window.confirm(msg)) {
					redirect("'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mercury");
				}
// ]]></script>';
			}
		}
	}

	if(isset($_GET['del_file'])) {
		unlink(DOC_ROOT . '/site/gfx/' . base64_decode($_GET['del_file']));
		redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/gfxdir');
	}

?>
<script type="text/javascript"><!-- // --><![CDATA[

	var state;

	function checkUncheck(state)
	{
		var i;
		var type;
		var gallery = $('admin_gfxdir_form');
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
<form method="post" action="" id="admin_gfxdir_form">
<table width="100%">
  <tr>
    <td valign="top">
	<table id="files" width="100%;">
		<tr style="font-weight:bold;">
		 <td>&nbsp;</td>
		 <td align=center width="6%"><?php echo _L('edit'); ?></td>
		 <td align=center width="6%"><?php echo _L('delete'); ?></td>
		 <td width="11%"><?php echo _L('uploaded'); ?></td>
		 <td><?php echo _L('filename'); ?></td>
		 <td>MIME</td>
		 <td width="11%"><?php echo _L('size'); ?></td>
		</tr>

		<?php

			include_once DOC_ROOT . '/orbicon/mercury/class.mercury.php';
			$current_files = null;
			$files = array();
			$i = 1;

			$priority = array('*.html,*.htm,*.css,*.js,*.jpeg,*.jpg,*.JPG,*.gif,*.GIF,*.png,*.PNG', '*');

			foreach($priority as $ext) {
				$search = glob(DOC_ROOT . '/site/gfx/{' . $ext . '}', GLOB_BRACE);
				$files = array_merge($files, $search);
			}

			$files = array_remove_empty(array_unique($files));

			$core_files = array('home.html', 'column.html', 'site.css', 'site.js');

			foreach($files as $file) {

				if(is_dir($file)) {
					continue;
				}

				$basename = basename($file);
				$core_ext = get_extension($basename);
				$id = base64_encode($basename);
				$current_files[] = $id;
				$bg = (($i % 2) == 0) ? '#ffffff' : '#cccccc';

				if(!in_array($basename, $core_files)) {
					$link_del = '<a onmousedown="'.delete_popup($basename).'" onclick="javascript:return false;" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/gfxdir&amp;del_file='.$id.'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/delete.png" alt="'._L('delete').'" title="'._L('delete').'" /></a>';
					$link_edit = '';

					if($core_ext == 'css') {
						$link_edit = '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/css&amp;css-x='.$basename.'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/css.png" /></a>';
					}
					else if($core_ext == 'js') {
						$link_edit = '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/javascript&amp;js-x='.$basename.'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/javascript.png" /></a>';
					}
					else if($core_ext == 'html') {
						$link_edit = '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/html&amp;html-x='.$basename.'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/html.png" /></a>';
					}
					else if($core_ext == 'xml') {
						$link_edit = '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/xml&amp;xml-x='.$basename.'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/file_icons/xml.png" /></a>';
					}
				}
				else {
					switch($core_ext) {
						case 'css':
							$link_edit = '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/css&amp;css-x='.$basename.'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/css.png" /></a>';
							$link_del = '';
						break;
						case 'js':
							$link_edit = '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/javascript&amp;js-x='.$basename.'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/javascript.png" /></a>';
							$link_del = '';
						break;
						case 'html':
							$link_edit = '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/html&amp;html-x='.$basename.'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/html.png" /></a>';
							$link_del = '';
						break;
					}
				}

				$dl_link = '<a href="'.ORBX_SITE_URL.'/site/gfx/'.$basename.'">'.$basename.'</a>';

				echo '<tr class="file_row" bgcolor='.$bg.'>
		 <td><label for="file_chk_'.$id.'">'.$i.'.</label> <input tabindex="'.$i.'" type="checkbox" value="1" id="file_chk_'.$id.'" name="file_chk_'.$id.'" /></td>
		 <td align=center>'.$link_edit.'</td>
		  <td align=center>'.$link_del.'</td>
		 <td><label for="file_chk_'.$id.'">'.date($_SESSION['site_settings']['date_format'], filemtime($file)).'</label></td>
		 <td><div style="overflow:hidden;"><label for="file_chk_' . $id . '">' . Mercury::get_document_icon($core_ext).' '.$dl_link.'</label></div></td>
		 <td>' . get_mime_by_ext($core_ext) . '</td>
		 <td><label for="file_chk_'.$id.'">' . get_file_size($file) . '</label></td>
		</tr>';

				$i++;
			}

			$current_files = (is_array($current_files)) ? implode('|', $current_files) : null;
	?>
	<tr>
		 <td>&nbsp;</td>
		 <td colspan="6">
		 <p>
			<a href="javascript:void(null)" onclick="javascript: checkUncheck(true);"><?php echo _L('select_all'); ?></a> |
			<a href="javascript:void(null)" onclick="javascript: checkUncheck(false);"><?php echo _L('unselect_all'); ?></a><br />

<label for="files_actions"><?php echo _L('with_selected'); ?>: </label>
<select name="files_actions" id="files_actions">
	<option value="0"><?php echo _L('do_nothing'); ?></option>
	<option value="delete" class="delete"><?php echo _L('delete'); ?></option>
	<option value="archive" class="compress"><?php echo _L('archive_files'); ?></option>
</select> <input type="submit" id="perform_file_actions" name="perform_file_actions" value="<?php echo _L('submit'); ?>" />
</p>
		 </td>
		 </tr>
	   </table></td>
    <td></td>
  </tr>
</table>
<input type="hidden" id="current_files" name="current_files" value="<?php echo $current_files; ?>" />
</form>