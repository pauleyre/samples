<?php
/**
 * Mercury frontpage
 *
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconFE
 * @subpackage Mercury
 * @version 1.5
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-05-01
 */

	$sort_by = (empty($_GET['sort_by'])) ? 'date' : $_GET['sort_by'];

	switch($sort_by) {
		case 'name': $sort_by = 'permalink DESC'; break;
		case 'bytes': $sort_by = 'size DESC'; break;
		case 'cat': $sort_by = 'category DESC'; break;
		default : $sort_by = 'uploader_time DESC'; break;
	}

	if($_GET['show_only'] == '_orbx_unsorted') {
		$show_only = 'AND ((category = \'\') OR (category IS NULL))';
	}
	else {
		$show_only = (empty($_GET['show_only'])) ? '' : 'AND (category = '.$dbc->_db->quote($_GET['show_only']).')';
	}

	if(isset($_GET['q'])) {
		$search_only = ' AND (permalink LIKE ' . $dbc->_db->quote('%' . $_GET['q'] . '%') . ')';
	}


	// pagination
	require_once DOC_ROOT . '/orbicon/class/class.pagination.php';

	$_GET['pp'] = (isset($_GET['pp'])) ? $_GET['pp'] : 20;
	$_GET['p'] = (isset($_GET['p'])) ? $_GET['p'] : 1;

	$pagination = new Pagination('p', 'pp');

	$read = $dbc->_db->query('	SELECT 		COUNT(id) AS numrows
								FROM 		'.MERCURY_FILES.'
								WHERE 		(live = 1) AND
											(hidden = 0)'.$show_only . $search_only);
	$row = $dbc->_db->fetch_assoc($read);

	$pagination->total = $row['numrows'];
	$pagination->split_pages();
	unset($read, $row);

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
				if($_POST['file_chk_'.$value] == 1) {
					/**
					 * @todo there are methods for this action
					 */
					if($_POST['files_actions'] == 'delete') {
						$name = $dbc->_db->query(sprintf('SELECT content
																FROM '.MERCURY_FILES.'
																WHERE (id = %s)
																LIMIT 1', $dbc->_db->quote($value)));
						$name = $dbc->_db->fetch_array($name);

						unlink(DOC_ROOT.'/site/mercury/'.$name['content']);
						unlink(DOC_ROOT.'/site/mercury/bck/'.$name['content'] . '.bk');
						$dbc->_db->query(sprintf('	DELETE
													FROM '.MERCURY_FILES.'
													WHERE (id = %s)
													LIMIT 1', $dbc->_db->quote($value)));
					}
					else if($_POST['files_actions'] == 'banner') {
						$name = $dbc->_db->query(sprintf('		SELECT 		content, permalink
																FROM 		'.MERCURY_FILES.'
																WHERE 		(id = %s)
																LIMIT 		1', $dbc->_db->quote($value)));
						$name = $dbc->_db->fetch_array($name);

						$dbc->_db->query(sprintf('INSERT INTO '.TABLE_BANNERS.' (title, permalink, language) VALUES (%s, %s, %s)', $dbc->_db->quote($name['content']), $dbc->_db->quote($name['permalink']), $dbc->_db->quote($orbicon_x->ptr)));
					}
					else if(strpos($_POST['files_actions'], 'cat_switch_') !== false) {
						$category = str_replace('cat_switch_', '', $_POST['files_actions']);
						$q_ = sprintf('UPDATE '.MERCURY_FILES.' SET
							category=%s WHERE (id = %s) LIMIT 1', $dbc->_db->quote($category), $dbc->_db->quote($value));
						$dbc->_db->query($q_);
					}
					else if($_POST['files_actions'] == 'archive') {
						$name = $dbc->_db->query(sprintf('		SELECT 		content
																FROM 		'.MERCURY_FILES.'
																WHERE 		(id = %s)
																LIMIT 		1', $dbc->_db->quote($value)));
						$name = $dbc->_db->fetch_array($name);
						$tar->addFile(DOC_ROOT . '/site/mercury/' . $name['content']);
					}
				}
			}

			if($_POST['files_actions'] == 'archive') {
				$archive = DOC_ROOT . '/site/mercury/archive_'.sprintf('%u', adler32(time() * uniqid())).'.tgz';
				$tar->toTar($archive, true);	// Gzipped TAR
				unset($tar);
				$hf->insert_file_into_db($archive, false, null, true);
			}
		}
	}

?>
<script type="text/javascript"><!-- // --><![CDATA[

	var state;

	function checkUncheck(state)
	{
		var i;
		var type;
		var gallery = $('doc_repos');
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
<style type="text/css">/*<![CDATA[*/

#files {
	font-size:90%;
}

#files .file_row:hover {
	background: #ffffcc;
}

#doc_listing { width: 100%; border: none;}

.delete_selector {background: #ffffff url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/delete.png) right top no-repeat;}
.banner_selector {background: #ffffff url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/page_white_flash.png) right top no-repeat;}
.compres_selector {background: #ffffff url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/compress.png) right top no-repeat;}

/*]]>*/</style>
<form method="post" action="" name="doc_repos" id="doc_repos">
<p>
	<input onkeypress="javascript: if(get_enter_pressed(event)) {return false;}" id="search_q" type="text" value="<?php echo $_GET['q']; ?>" /> <input type="button" value="<?php echo _L('search'); ?>" onclick="javascript: redirect('<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mercury' ;?>&q=' + encodeURIComponent($('search_q').value));" />
</p>

<table id="doc_listing" title="Document repository">
	<tr>
		<th></th>
		<th><?php echo _L('edit'); ?></th>
		<th><?php echo _L('delete'); ?></th>
		<th><a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr;?>=orbicon/mercury&amp;sort_by=date"><?php echo _L('uploaded'); ?></a></th>
		<th><a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr;?>=orbicon/mercury&amp;sort_by=name"><?php echo _L('filename'); ?></a></th>
		<th><a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr;?>=orbicon/mercury&amp;sort_by=cat"><?php echo _L('category'); ?></a></th>
		<th><a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr;?>=orbicon/mercury&amp;sort_by=bytes"><?php echo _L('size'); ?></a></th>
		<th>MIME</th>
		<th><?php echo _L('comments'); ?></th>
	</tr>
	<?php

		$current_files = null;

		$r = $dbc->_db->query(sprintf('	SELECT	 	*
										FROM 		'.MERCURY_FILES.'
										WHERE 		(live = 1) AND
													(hidden = 0) '.$show_only.' %s
										ORDER BY 	%s
										LIMIT		%s, %s',
										$search_only, $sort_by, $dbc->_db->quote(($_GET['p'] - 1) * $_GET['pp']), $dbc->_db->quote($_GET['pp'])));

		$file = $dbc->_db->fetch_assoc($r);
		$i = (isset($_GET['p'])) ? (1 + ($_GET['pp'] * ($_GET['p'] - 1))) : 1;

		while($file) {

			$current_files[] = $file['id'];
			$bg = (($i % 2) == 0) ? '#ffffff' :'#cccccc';
			$r_ = $dbc->_db->query(sprintf('	SELECT 	COUNT(id)
												FROM 	'.MERCURY_COMMENTS.'
												WHERE 	(question_permalink = %s)', $dbc->_db->quote($file['permalink'])));
			$a_ = $dbc->_db->fetch_array($r_);

			$link = '
				<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mercury&amp;read=data/'.$file['permalink'].'/">
					<img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/edit.png" /></a>';

			$link_del = '
				<a onmousedown="' . delete_popup($file['content']) . '" onclick="return false;" href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mercury&amp;del_file='.$file['permalink'].'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/delete.png" alt="' . _L('delete') . '" title="' . _L('delete') . '" /></a>';

			$dl_link = '<a href="'.ORBX_SITE_URL.'/site/mercury/'.$file['content'].'">'.$file['content'].'</a>';
			$ext = get_extension($file['content']);

			$file_category = ($file['category']) ? '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mercury&amp;show_only='.$file['category'].'">' . $file['category'] . '</a>' : '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mercury&amp;show_only=_orbx_unsorted">'._L('unsorted').'</a>';

			echo '
			<tr class="file_row" bgcolor='.$bg.'>
	 			<td>
					<label for="file_chk_'.$file['id'].'">'.$i.'.</label>
					<input tabindex="'.$i.'" type="checkbox" value="1" id="file_chk_'.$file['id'].'" name="file_chk_'.$file['id'].'" />
				</td>
	 			<td align="center">'.$link.'</td>
	  			<td align="center">'.$link_del.'</td>
	 			<td><label for="file_chk_'.$file['id'].'">'.date($_SESSION['site_settings']['date_format'], $file['uploader_time']).'</label></td>
	 			<td>
					<div style="overflow:hidden;">
					<label for="file_chk_'.$file['id'].'">'.Mercury::get_document_icon($ext).' '.$dl_link.'</label></div>
				</td>
				<td><label for="file_chk_'.$file['id'].'">'.$file_category.'</label></td>
				<td><label for="file_chk_'.$file['id'].'">'.get_file_size(DOC_ROOT.'/site/mercury/'.$file['content']).'</label></td>
				<td align="center"><label for="file_chk_'.$file['id'].'">'.get_mime_by_ext($ext).'</label></td>
				<td align="center"><label for="file_chk_'.$file['id'].'">'.$a_[0].'</label></td>
			</tr>';

			$file = $dbc->_db->fetch_assoc($r);
			$i++;
		}
		$dbc->_db->free_result($r);

		$current_files = (is_array($current_files)) ? implode('|', $current_files) : NULL;
	?>
</table>

<p>
	<label for="files_actions"><?php echo _L('with_selected'); ?> : </label>
	<select name="files_actions" id="files_actions">
		<option value="0"><?php echo _L('do_nothing'); ?></option>
		<option value="delete" class="delete_selector"><?php echo _L('delete'); ?></option>
		<option value="banner" class="banner_selector"><?php echo _L('insert_into_banners'); ?></option>
		<option value="archive" class="compres_selector"><?php echo _L('archive_files'); ?></option>

		<optgroup label="<?php echo _L('move_to_category'); ?>">
	<?php

	$r = $dbc->_db->query('	SELECT 		*
							FROM 		'.MERCURY_CATEGORIES.'
							ORDER BY 	permalink');
	$a = $dbc->_db->fetch_array($r);

	while($a) {
		echo '
			<option style="background: #ffffff url('.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/expand.png) right top no-repeat;" value="cat_switch_'.$a['permalink'].'">
				'.$a['name'].'
			</option>';

		$a = $dbc->_db->fetch_array($r);
	}
	$dbc->_db->free_result($r);

?>
		</optgroup>
	</select> <input type="submit" id="perform_file_actions" name="perform_file_actions" value="<?php echo _L('submit'); ?>" />
	<a href="javascript:void(null)" onclick="javascript: checkUncheck(true);"><?php echo _L('select_all'); ?></a> |
	<a href="javascript:void(null)" onclick="javascript: checkUncheck(false);"><?php echo _L('unselect_all'); ?></a>
</p>
<div style="height: 1%;"></div>

<input type="hidden" id="current_files" name="current_files" value="<?php echo $current_files; ?>" />
</form>

<?php

	echo $pagination->construct_page_nav(ORBX_SITE_URL . "/?{$orbicon_x->ptr}=orbicon/mercury&amp;show_only=".$_GET['show_only'].'&amp;q='.$_GET['q'].'&amp;sort_by='.$_GET['sort_by']);

?>