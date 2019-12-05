<?php

	if(isset($_GET['delete'])){
		// remove selected item
		$remove = new Tag;
		$remove->removeTag($_GET['delete']);
	}

	if(isset($_GET['edit']) && $_GET['edit'] != ''){

		// this is not edit mode
		if(isset($_POST['tag_title'])){

			if($_POST['tag_title'] != ''){

				$updateTag = new Tag($_POST);
				$updateTag->edit_tag();
			} else {

				$error = '<p class="error_label">'._L('ic-tag-empty').'</p>';

			}

		}

		$editTag = new Tag;
		$etag = $editTag->get_tag($_GET['edit']);

		$etag = $dbc->_db->fetch_array($etag);

	} else {

		// this is not edit mode
		if(isset($_POST['tag_title'])){

			if($_POST['tag_title'] != ''){

				$writeTag = new Tag($_POST);
				$writeTag->set_new_tag();
			} else {

				$error = '<p class="error_label">'._L('ic-tag-empty').'</p>';

			}

		}
	}

?>

<form id="tag_form" method="post" action="">
	<input type="hidden" name="id" id="id" value="<?php echo $_GET['edit'];?>" />
	<p>
		<label for="tag_title"><?php echo _L('title');?></label><br />
		<input type="text" id="tag_title" name="tag_title" value="<?php echo $etag['tag_title'];?>" />
		<input type="submit" id="submit_tag" name="submit_tag" value="<?php echo _L('save');?>" />
		<?php
			if(isset($_GET['edit'])){

				echo '<input type="button" name="new" id="new" value="'._L('ic-new-entry').'" onclick="javascript: document.location = \''.ORBX_SITE_URL.'?'.$orbicon_x->ptr.'=orbicon/mod/infocentar&goto=tags\'" />';

			}
		?>
	</p>
	<?php if(isset($error)) { echo $error; }?>
</form>
<br />

<table id="tags_table">
	<tr>
<?php

	$tagObj = new Tag;
	$tag_list = $tagObj->get_tag_list();
	$i = 1;

	while($tag = $dbc->_db->fetch_array($tag_list)){

		echo '	<td>
					<a href="?'.$orbicon_x->ptr.'=orbicon/mod/infocentar&amp;goto=tags&amp;edit='.$tag["id"].'" title="'.$tag["tag_title"].'">
						'.$tag["tag_title"].'
					</a>
					<a href="?'.$orbicon_x->ptr.'=orbicon/mod/infocentar&amp;goto=tags&amp;delete='.$tag["id"].'" onclick="javascript:return false;" onmousedown="javascript:if(window.confirm(\''._L('ic-del-tag').'\')) {window.location = this.href;}">
						<img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/delete.png" alt="'._L('delete').'" />
					</a>
				<td>';

		if(($i % 5) == 0){
			echo '</tr><tr>';
		}

		$i++;

	}

?>
	</tr>
</table>