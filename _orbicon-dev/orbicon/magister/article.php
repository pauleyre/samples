<?php
/**
 * Text DB article
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @subpackage Magister
 * @version 1.30
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-07-01
 */

	$q = explode('/', $_GET['read']);

	if(isset($_POST['new_magister_category']) && $_POST['new_magister_category'] !== ''){
		$hf->add_new_category($_POST['new_magister_category']);
	}

	if(isset($_POST['save_article'])) {
		$now = time();

		$title = $_POST['title'];
		$permalink = get_permalink($title);
		$live_time = ($_POST['live_time'] == $_POST['uploader_time']) ? $now : $_POST['live_time'];

		if(empty($q[1])) {
			$already_exists = sprintf('
										SELECT	id, permalink
										FROM 	'.MAGISTER_TITLES.'
										WHERE 	(permalink = %s) AND
												(language = %s)
										LIMIT 	1', $dbc->_db->quote($permalink), $dbc->_db->quote($orbicon_x->ptr));
			$check = $dbc->_db->query($already_exists);
			$check = $dbc->_db->fetch_array($check);

			$insert = sprintf('	INSERT INTO 	'.MAGISTER_TITLES.'
												(category, title,
												uploader, uploader_ip,
												uploader_time, live,
												live_time, permalink,
												language)
								VALUES 			(%s, %s,
												%s, %s,
												%s, 1,
												%s, %s,
												%s)',
					$dbc->_db->quote($_POST['category']), $dbc->_db->quote(utf8_html_entities($title)),
					$dbc->_db->quote(utf8_html_entities($_POST['uploader'])), $dbc->_db->quote(ORBX_CLIENT_IP),
					$dbc->_db->quote($now), $dbc->_db->quote($now),
					$dbc->_db->quote($permalink), $dbc->_db->quote($orbicon_x->ptr));

			if(empty($check['id'])) {
				$content_test = trim($_POST['title']);

				if($content_test == '') {
					$empty_feedback = '<p><fieldset><legend style="color:red; font-size: 1.7em;">'._L('error').'</legend></fieldset></p>';
				}
				else {
					$dbc->_db->query($insert);

					echo '<meta http-equiv="refresh" content="0; URL='.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/magister&amp;read=clanak/'.urlencode($permalink).'" />';
				}
			}
			else {
				echo '<meta http-equiv="refresh" content="0; URL='.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/magister&amp;read=clanak/'.urlencode($check['permalink']).'" />';
			}
		}
		else {
			$q_ = sprintf('
							UPDATE 			'.MAGISTER_TITLES.'
							SET 			category=%s, title=%s,
											uploader=%s, permalink=%s,
											last_modified = %s
							WHERE 			(permalink=%s) AND
											(language = %s)',
			$dbc->_db->quote($_POST['category']), $dbc->_db->quote(utf8_html_entities($title)), $dbc->_db->quote($_POST['uploader']),$dbc->_db->quote($permalink), $dbc->_db->quote($now),
			$dbc->_db->quote($q[1]), $dbc->_db->quote($orbicon_x->ptr));
			$dbc->_db->query($q_);

			$q2 = sprintf('		UPDATE 		'.MAGISTER_CONTENTS.'
								SET 		question_permalink=%s
								WHERE 		(question_permalink=%s) AND
											(language = %s)', $dbc->_db->quote($permalink), $dbc->_db->quote($q[1]), $dbc->_db->quote($orbicon_x->ptr));
			$dbc->_db->query($q2);
		}

		if($permalink != $q[1]) {
			redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/magister&read=clanak/'.urlencode($permalink).'/');
		}
	}

	// load article
	if(!empty($q[1])) {
		$r = $dbc->_db->query(sprintf('		SELECT 	*
											FROM 	'.MAGISTER_TITLES.'
											WHERE 	(live = 1) AND
													(permalink = %s) AND
													(language = %s)
											LIMIT 	1', $dbc->_db->quote($q[1]), $dbc->_db->quote($orbicon_x->ptr)));
		$a = $dbc->_db->fetch_assoc($r);

		$dbc->_db->free_result($r);

		if(empty($a['id'])) {
			unset($a);
			header('HTTP/1.1 404 Not Found', true);
			$_SESSION['cache_status'] = 404;
			$a['title'] = '404 Not Found';
		}
	}

	$orbicon_x->set_page_title($a['title']);

	if(($q[2] == 'delete-answer') && is_numeric($q[3]) && get_is_admin()) {

		// perform backup
		$dbc->_db->query(sprintf('	INSERT	INTO 	'.MAGISTER_CONTENTS_BCK.'
									SELECT 			*
									FROM 			'.MAGISTER_CONTENTS.'
									WHERE 			(id = %s) AND
													(language = %s) ',
									$dbc->_db->quote($q[3]), $dbc->_db->quote($orbicon_x->ptr)));

		$dbc->_db->query(sprintf('	DELETE
									FROM 		'.MAGISTER_CONTENTS.'
									WHERE 		(id = %s) AND
												(language = %s)
									LIMIT 		1',
									$dbc->_db->quote($q[3]), $dbc->_db->quote($orbicon_x->ptr)));
	}

	if(($q[2] == 'delete-article') && get_is_admin()) {

		// perform backup
		$dbc->_db->query(sprintf('	INSERT
									INTO 	'.MAGISTER_TITLES_BCK.'
									SELECT 	*
									FROM 	'.MAGISTER_TITLES.'
									WHERE 	(permalink = %s) AND
											(language = %s) ',
									$dbc->_db->quote($q[1]), $dbc->_db->quote($orbicon_x->ptr)));


		$dbc->_db->query(sprintf('	INSERT
									INTO 	'.MAGISTER_CONTENTS_BCK.'
									SELECT 	*
									FROM 	'.MAGISTER_CONTENTS.'
									WHERE 	(question_permalink = %s) AND
											(language = %s) ',
									$dbc->_db->quote($q[1]), $dbc->_db->quote($orbicon_x->ptr)));

		$dbc->_db->query(sprintf('DELETE FROM '.MAGISTER_TITLES.' WHERE (permalink = %s) AND (language = %s) LIMIT 1', $dbc->_db->quote($q[1]), $dbc->_db->quote($orbicon_x->ptr)));
		$dbc->_db->query(sprintf('DELETE FROM '.MAGISTER_CONTENTS.' WHERE (question_permalink = %s) AND (language = %s)', $dbc->_db->quote($q[1]), $dbc->_db->quote($orbicon_x->ptr)));

		redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/magister');
	}

?>

<script type="text/javascript"><!-- // --><![CDATA[
	var __orbicon_server_name = '<?php echo $_SERVER['SERVER_NAME']; ?>';
// ]]></script>

<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/controler/gzip.server.php?file=/orbicon/rte/rte.final.js&amp;<?php echo ORBX_BUILD; ?>"></script>

<script type="text/javascript"><!-- // --><![CDATA[

	function set_editor(input)
	{
		sh_ind();
		var handleSuccess = function(o)
		{
			if(o.responseText !== undefined) {


				tinyMCE.execCommand('mceFocus', false, 'elm1');
				tinyMCE.execCommand('mceSetContent', false, o.responseText);

				//oToolbar.body.innerHTML = o.responseText;
				var current_edit = $('current_edit');
				current_edit.value = input;
				//RichTextFocus();
				//__rte_content_fix();
				sh_ind();
			}
		}

		var callback =
		{
			success:handleSuccess
		};

		YAHOO.util.Connect.asyncRequest('POST', '<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon&ajax_text_db&action=txt', callback, 'text_id=' + input);
	}

	function set_hidden_flag(el, flag)
	{
		sh_ind();
		var handleSuccess = function(o)
		{
			if(o.responseText !== undefined)
			{
				sh_ind();
			}
		}

		var callback =
		{
			success:handleSuccess
		};

		var data = new Array();
		data[0] = 'card_id=' + el;
		data[1] = 'flag=' + flag;

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', '<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon&ajax_text_db&action=hidden_flag', callback, data);
	}

	function RichTextSave()
	{
		/*if(nViewMode == 2) {
			window.alert('You cannot save while in HTML source view.\nSwitch back to normal view and try again.');
			return false;
		}*/
		sh_ind();

		// disable editor
		/*try {
			oToolbar.execCommand('contentReadOnly', false, true);
		} catch(e) {}*/

		var handleSuccess = function(o) {

			if(o.responseText !== undefined) {
				var current_edit_id = $('current_edit').value;
				// * update preview
				var article_content = $('answer_' + current_edit_id);
				if(article_content != null) {
					article_content.innerHTML = o.responseText;
					// * on-screen effect
					yfade('answer_' + current_edit_id);
				}
				else {
					// * refresh
					redirect(window.location);
				}
				// enable editor
				/*try {
					oToolbar.execCommand('contentReadOnly', false, false);
				} catch(e) {}*/
				sh_ind();
			}
		}

		var callback =
		{
			success:handleSuccess
		};

		var data = new Array();
		data[0] = 'current_edit_permalink=' + $('current_edit_permalink').value;
		data[1] = 'current_edit=' + $('current_edit').value;
		//data[2] = 'content=' + encodeURIComponent(RichTextCaptureData('return'));
		data[2] = 'content=' + encodeURIComponent(tinyMCE.get('elm1').getContent());
		data[3] = 'update_article=update';
		//data[4] = 'internal_links=' + orbicon_internal_link_scan();

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', '<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon&ajax_text_db&action=card_edit', callback, data);
		//orbicon_internal_link_scan();
	}

	function RichTextNew()
	{
		$('current_edit').value = '';
		RichTextFocus();
		oToolbar.body.innerHTML = "";
	}

	function orbicon_internal_link()
	{
		var selected;
		RichTextFocus();

		if(oToolbar.selection) {
			selected = oToolbar.selection.createRange().text;
		}
		else if($(sToolbarID).contentWindow.getSelection) {
			selected = $(sToolbarID).contentWindow.getSelection();
		}
		selected = encodeURIComponent(selected);

		var url = 'orbicon://' + __orbicon_base_url + '/?'+ __orbicon_ln +'=' + selected;

		oToolbar.execCommand("CreateLink", false, url);
	}

	function orbicon_internal_link_scan()
	{
		var aAnchors = null;
		var i;
		var __a_search;
		var internal_links = new Array();

		if(oToolbar.getElementsByTagName) {
			aAnchors = oToolbar.getElementsByTagName("a");
		}
		else if(oToolbar.all.tags) {
			aAnchors = oToolbar.all.tags("a");
		}

		if(!empty(aAnchors))
		{
			for(i = 0; i < aAnchors.length; i++) {
				__a_search = aAnchors[i].href.search(new RegExp("orbicon://" + __orbicon_base_url, 'gi'));
				if(aAnchors[i].href && __a_search != -1) {
					internal_links[i] = aAnchors[i].href;
				}
			}
		}
		return internal_links.join('|');
	}

// ]]></script>
<style type="text/css">
/*<![CDATA[*/
	@import url("<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rich_text_editor.css");
/*]]>*/
</style>
<table style="width:100%;">
<tr>
	<td style="background:#f0f0ee; padding:0.5em; vertical-align:top;">
			<?php

				if(($hf->check_category() == 0) && !isset($_GET['read'])){

				echo '
				<h2>'._L('no_txt_cat').'</h2>
				<form method="post" action="">
				<fieldset>
					<legend><strong><label for="new_magister_category2">'._L('new_categories').'</label></strong></legend><br />
						<textarea name="new_magister_category" id="new_magister_category2" cols="22" rows="4"></textarea>
						<input type="submit" value="'._L('submit').'" />
				</fieldset>
				</form>
				';

				} else {
			?>
			<form onsubmit="javascript: return verify_title('title');" method="post" action="" id="magister_form" name="magister_form">
			<input type="hidden" name="live_time" id="live_time" value="<?php echo $a['live_time']; ?>" />
			<input type="hidden" name="uploader_time" id="uploader_time" value="<?php echo $a['uploader_time']; ?>" />
			<input name="uploader" id="uploader" type="hidden" value="<?php echo $a['uploader']; ?>" />

				<input name="save_article" type="submit" id="save_article" value="<?php echo _L('save_title'); ?>" />
				<input <?php if(!isset($_GET['read'])) {echo 'disabled="disabled"';} ?> type="button" value="<?php echo _L('add_new') ?>" onclick="javascript: redirect('<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/magister'; ?>');"  />
				<input <?php if(!isset($_GET['read'])) {echo 'disabled="disabled"';} ?> onclick="javascript: if(window.confirm('<?php echo _L('delete_article'); ?>?')) {redirect('<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/magister&amp;read=clanak/<?php echo urlencode($a['permalink']); ?>/delete-article/');}" type="button" value="<?php echo _L('delete_article'); ?>" />

				<table style="width:100%;margin-top:20px">
					<tr style="vertical-align:top">
						<td>
							<label for="title"><?php echo _L('title'); ?></label><br />
							<input type="text" style="font-size:14px;padding:10px;width: 600px;" id="title" name="title" value="<?php echo str_replace('&amp;', '&', htmlspecialchars($a['title'])); ?>" />
						</td>
						<td>

							<label for="category"><?php echo _L('category'); ?></label><br />
							<div id="magister_cat">
							<?php

								// Alen Novakovic, 08.01.2007.

								$refer = parse_str($_SERVER['HTTP_REFERER'], $refer_category);
								$category_from_referer = explode('/', $refer_category['read']);

								if($a['category'] == ''){
									$category_provided = $category_from_referer[1];
								}
								else {
									$category_provided = $a['category'];
								}

							?>
							<select name="category" id="category">
								<?php echo $hf->get_categories($category_provided); ?>
							</select></div>

						</td>
						<td>

				<?php
					if(!empty($_GET['read'])) {

						$r_c = $dbc->_db->query(sprintf('	SELECT 		title, permalink,
																		language
															FROM 		'.TABLE_NEWS.'
															WHERE 		(content = %s) AND
																		(language=%s)',
						$dbc->_db->quote($a['permalink']), $dbc->_db->quote($orbicon_x->ptr)));
						$a_c = $dbc->_db->fetch_array($r_c);

						while($a_c) {
							$url = url(ORBX_SITE_URL.'/?'.$a_c['language'].'='.urlencode($a_c['permalink']), ORBX_SITE_URL.'/'.$a_c['language'].'/'.urlencode($a_c['permalink']));
							$active_links[] = "<a href=\"$url\">{$a_c['title']}</a>";
							$a_c = $dbc->_db->fetch_array($r_c);
						}

						$dbc->_db->free_result($r_c);

						$r_c = $dbc->_db->query(sprintf('	SELECT 		title, permalink,
																		language, menu_name
															FROM 		'.TABLE_COLUMNS.'
															WHERE 		(content = %s) AND
																		(language=%s)',
						$dbc->_db->quote($a['permalink']), $dbc->_db->quote($orbicon_x->ptr)));
						$a_c = $dbc->_db->fetch_assoc($r_c);

						while($a_c) {
							// not in a box
							if($a_c['menu_name'] != 'box') {
								$url = url(ORBX_SITE_URL.'/?'.$a_c['language'].'='.urlencode($a_c['permalink']), ORBX_SITE_URL.'/'.$a_c['language'].'/'.urlencode($a_c['permalink']));
								$active_links[] = "<a href=\"$url\">{$a_c['title']}</a>";
							}
							// in box
							else {
								$active_links[] = $a_c['title'] . ' <strong>( BOX )</strong>';
							}
							$a_c = $dbc->_db->fetch_assoc($r_c);
						}

						$dbc->_db->free_result($r_c);

						if(!empty($active_links)) {
							echo _L('article_used').'<br/>
							<div style="font-size:90%;border:1px solid #ccc;background:#fff;"><ol>';

							foreach($active_links as $value) {
								echo "<li>$value</li>";
							}

							echo '</ol></div>';
						}
					}
				?>

						</td>
					</tr>
				</table>

				</form>

<?php

	if(!empty($_GET['read'])) {

?>

<p>
				<a name="editor" id="editor"></a>
				<?php
					require_once DOC_ROOT . '/orbicon/rte/rte_components/toolbar.php';
				?>
				<input id="current_edit" name="current_edit" type="hidden" />
				<input id="current_edit_permalink" name="current_edit_permalink" type="hidden" value="<?php echo $q[1]; ?>" />
</p>

				<div style="margin: 5px 0;text-transform: uppercase;"><?php echo _L('text_cards'); ?></div>
<?php
	$i = 1;
	$r = $dbc->_db->query(sprintf('		SELECT 		*
										FROM 		'.MAGISTER_CONTENTS.'
										WHERE 		(live = 1) AND
													(question_permalink = %s) AND
													(language = %s)
										ORDER BY 	uploader_time', $dbc->_db->quote($q[1]), $dbc->_db->quote($orbicon_x->ptr)));
	$card = $dbc->_db->fetch_assoc($r);

	while($card) {
		$hidden = ($card['hidden'] == 1) ? 'checked="checked"' : NULL;
		$bg = ($card['hidden'] == 1) ? 'background:#eee;' : 'background:#fff;';
		echo '	<fieldset style="border:1px solid #ccc; margin-bottom:15px; width:1060px;padding:0 !important;'.$bg.'">
					<p><legend>'.$i.'.</legend></p>';
		echo '		<div class="answer_content" id="answer_'.$card['id'].'" style="width: 1020px; padding:20px; overflow:auto; !important;">
						'.$card['content'].'
					</div>
					<div style="clear: both;"></div>';
		echo '
					<div style="padding:0 7px;border-top: 1px solid #ccc; background:#f0f0ee;"><br />
						<input type="button" onclick="javascript:set_editor('.$card['id'].');" value="'._L('edit_card').' '.$i.'." />
						<input type="button" onclick="javascript: if(window.confirm(\''._L('delete_card').'?\')) {redirect(\''.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/magister&amp;read=clanak/'.urlencode($a['permalink']).'/delete-answer/'.$card['id'].'\');}" value="'._L('delete_card').' '.$i.'." />

						<input onclick="javascript:set_hidden_flag('.$card['id'].', this.checked);" type="checkbox" id="card_hidden_'.$card['id'].'" name="card_hidden_'.$card['id'].'" '.$hidden.' />
						<label for="card_hidden_'.$card['id'].'">'._L('hidden').'</label><br /><br />
						<div style="padding-bottom:5px;">'._L('author').': '.$card['uploader'].', '.date('r', $card['uploader_time']).'</div>
					</div>
				</fieldset>';
		$card = $dbc->_db->fetch_assoc($r);
		$i ++;
	}
	$dbc->_db->free_result($r);

?>

				<?php
					}
				}
				?>
	</td>
	<td ></td>
</tr>
</table>