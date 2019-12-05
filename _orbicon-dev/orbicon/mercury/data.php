<?php
/**
 * Mercury entry
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

	$q = explode('/', $_GET['read']);
	$mercury_dir = DOC_ROOT.'/site/mercury/';

	if(isset($_POST['save_article'])) {

		$now = time();
		$title = utf8_html_entities($_POST['title']);
		$desc = utf8_html_entities($_POST['description']);
		$live_time = ($_POST['live_time'] == $_POST['uploader_time']) ? $now : $_POST['live_time'];

		$q_ = sprintf('	UPDATE 		'.MERCURY_FILES.'
						SET			category=%s, title=%s,
									uploader=%s, last_modified = %s,
									description=%s, custom_live_date = %s
						WHERE 		(permalink=%s)',
		$dbc->_db->quote($_POST['category']), $dbc->_db->quote($title),
		$dbc->_db->quote($_POST['uploader']), $dbc->_db->quote($now),
		$dbc->_db->quote($desc), $dbc->_db->quote($_POST['custom_live']),
		$dbc->_db->quote($q[1]));
		$dbc->_db->query($q_);
	}

	// add comment
	if(isset($_POST['add_comment'])) {
		$dbc->_db->query(sprintf('	INSERT INTO 		'.MERCURY_COMMENTS.'
														(question_permalink, content,
														uploader, uploader_ip,
														uploader_time)
									VALUES 				(%s, %s,
														%s, %s,
														UNIX_TIMESTAMP())',
		$dbc->_db->quote($q[1]), $dbc->_db->quote($_POST['comment']),
		$dbc->_db->quote($_SESSION['user.a']['id']), $dbc->_db->quote(ORBX_CLIENT_IP)));
	}

	// load file data
	$r = $dbc->_db->query(sprintf('		SELECT 		*
										FROM 		'.MERCURY_FILES.'
										WHERE 		(live = 1) AND
													(permalink = %s)
										LIMIT 		1', $dbc->_db->quote($q[1])));
	$a = $dbc->_db->fetch_assoc($r);
	$dbc->_db->free_result($r);

	// setup custom date
	$custom_live = (empty($a['custom_live_date'])) ? date('r', $a['uploader_time']) : $a['custom_live_date'];

	if(empty($a['id'])) {
		unset($a);
		$_SESSION['cache_status'] = 404;
		session_write_close();
		header('HTTP/1.1 404 Not Found', true);
		$a['title'] = '404 Not Found';
	}

	$orbicon_x->set_page_title($a['title']);

	// delete comment
	if($q[2] == 'delete-comment' && is_numeric($q[3])) {
		$dbc->_db->query(sprintf('	DELETE
									FROM '.MERCURY_COMMENTS.'
									WHERE (id = %s)
									LIMIT 1', $dbc->_db->quote($q[3])));
	}

	if(isset($_POST['save_txt_file'])) {
		$path = $mercury_dir.$a['content'];

		chmod_unlock($path);
		$r = fopen($path, 'wb');
		fwrite($r, $_POST['mercury_txt']);
		fclose($r);
		chmod_lock($path);

		update_sync_cache_list($path);
		unset($path);
	}

	if(!empty($q[1]) && !empty($a['content'])) {
		$ext = get_extension($a['content']);
		include_once DOC_ROOT.'/orbicon/class/inc.mmedia.php';

		switch($ext) {
			case 'mp3': case 'wav': case 'ogg': case 'wma':
				$file_preview = get_mp3_player($a['content']);
			break;
			case 'mpg': case 'mpeg': case 'wmv': case 'avi':
				$file_preview = get_video_player($a['content']);
			break;
			case 'mov': case '3gp':
				$file_preview = get_apple_player($a['content']);
			break;
			case 'flv':
				$file_preview = get_flv_player($a['content']);
			break;
			case 'swf':
			$size = getimagesize(DOC_ROOT.'/site/mercury/'.$a['content']);
$file_preview = '<object data="'.ORBX_SITE_URL.'/site/mercury/'.$a['content'].'" type="application/x-shockwave-flash" width="'.$size[0].'" height="'.$size[1].'">
	<param name="movie" value="'.ORBX_SITE_URL.'/site/mercury/'.$a['content'].'" />
	<param name="quality" value="high" />
	<param name="menu" value="0" />
</object>';
			break;
			// txt
			case 'txt': case 'log': case 'bat':
				$mercury_txt = htmlspecialchars(file_get_contents($mercury_dir.$a['content']));
				$file_preview = '<textarea id="mercury_txt" name="mercury_txt" style="width: 99%; height: 500px; font-family: monospace; font-size: 120%;">'.$mercury_txt.'</textarea><br /><input name="save_txt_file" type="submit" id="save_txt_file" value="'._L('save').'" />';
			break;
			// html
			case 'html': case 'htm': case 'shtml':
				$mercury_txt = htmlspecialchars(file_get_contents($mercury_dir.$a['content']));
				$file_preview = '<textarea id="mercury_txt" name="mercury_txt" style="width: 99%; height: 500px; font-family: monospace; font-size: 120%;">'.$mercury_txt.'</textarea><br /><input name="save_txt_file" type="submit" id="save_txt_file" value="'._L('save').'" />';
				$file_preview .= '<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/3rdParty/edit_area/edit_area_full.js?'.ORBX_BUILD.'"></script>
<script type="text/javascript"><!-- // --><![CDATA[
editAreaLoader.init({
	id : "mercury_txt",
	syntax: "html",
	start_highlight: true
});
// ]]></script>';
			break;
			// php
			case 'php': case 'php3': case 'phps':
				$mercury_txt = htmlspecialchars(file_get_contents($mercury_dir.$a['content']));
				$file_preview = '<textarea id="mercury_txt" name="mercury_txt" style="width: 99%; height: 500px; font-family: monospace; font-size: 120%;">'.$mercury_txt.'</textarea><br /><input name="save_txt_file" type="submit" id="save_txt_file" value="'._L('save').'" />';
				$file_preview .= '<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/3rdParty/edit_area/edit_area_full.js?'.ORBX_BUILD.'"></script>
<script type="text/javascript"><!-- // --><![CDATA[
editAreaLoader.init({
	id : "mercury_txt",
	syntax: "php",
	start_highlight: true
});
// ]]></script>';
			break;
			// xml
			case 'rdf': case 'xml': case 'rss':
				$mercury_txt = htmlspecialchars(file_get_contents($mercury_dir.$a['content']));
				$file_preview = '<textarea id="mercury_txt" name="mercury_txt" style="width: 99%; height: 500px; font-family: monospace; font-size: 120%;">'.$mercury_txt.'</textarea><br /><input name="save_txt_file" type="submit" id="save_txt_file" value="'._L('save').'" />';
				$file_preview .= '<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/3rdParty/edit_area/edit_area_full.js?'.ORBX_BUILD.'"></script>
<script type="text/javascript"><!-- // --><![CDATA[
editAreaLoader.init({
	id : "mercury_txt",
	syntax: "xml",
	start_highlight: true
});
// ]]></script>';
			break;
			// js
			case 'js':
				$mercury_txt = htmlspecialchars(file_get_contents($mercury_dir.$a['content']));
				$file_preview = '<textarea id="mercury_txt" name="mercury_txt" style="width: 99%; height: 500px; font-family: monospace; font-size: 120%;">'.$mercury_txt.'</textarea><br /><input name="save_txt_file" type="submit" id="save_txt_file" value="'._L('save').'" />';
				$file_preview .= '<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/3rdParty/edit_area/edit_area_full.js?'.ORBX_BUILD.'"></script>
<script type="text/javascript"><!-- // --><![CDATA[
editAreaLoader.init({
	id : "mercury_txt",
	syntax: "js",
	start_highlight: true
});
// ]]></script>';
			break;
			// css
			case 'css':
				$mercury_txt = htmlspecialchars(file_get_contents($mercury_dir.$a['content']));
				$file_preview = '<textarea id="mercury_txt" name="mercury_txt" style="width: 99%; height: 500px; font-family: monospace; font-size: 120%;">'.$mercury_txt.'</textarea><br /><input name="save_txt_file" type="submit" id="save_txt_file" value="'._L('save').'" />';
				$file_preview .= '<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/3rdParty/edit_area/edit_area_full.js?'.ORBX_BUILD.'"></script>
<script type="text/javascript"><!-- // --><![CDATA[
editAreaLoader.init({
	id : "mercury_txt",
	syntax: "css",
	start_highlight: true
});
// ]]></script>';
			break;
			// vbs
			case 'vbs':
				$mercury_txt = htmlspecialchars(file_get_contents($mercury_dir.$a['content']));
				$file_preview = '<textarea id="mercury_txt" name="mercury_txt" style="width: 99%; height: 500px; font-family: monospace; font-size: 120%;">'.$mercury_txt.'</textarea><br /><input name="save_txt_file" type="submit" id="save_txt_file" value="'._L('save').'" />';
				$file_preview .= '<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/3rdParty/edit_area/edit_area_full.js?'.ORBX_BUILD.'"></script>
<script type="text/javascript"><!-- // --><![CDATA[
editAreaLoader.init({
	id : "mercury_txt",
	syntax: "vb",
	start_highlight: true
});
// ]]></script>';
			break;
			// python
			case 'py':
				$mercury_txt = htmlspecialchars(file_get_contents($mercury_dir.$a['content']));
				$file_preview = '<textarea id="mercury_txt" name="mercury_txt" style="width: 99%; height: 500px; font-family: monospace; font-size: 120%;">'.$mercury_txt.'</textarea><br /><input name="save_txt_file" type="submit" id="save_txt_file" value="'._L('save').'" />';
				$file_preview .= '<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/3rdParty/edit_area/edit_area_full.js?'.ORBX_BUILD.'"></script>
<script type="text/javascript"><!-- // --><![CDATA[
editAreaLoader.init({
	id : "mercury_txt",
	syntax: "python",
	start_highlight: true
});
// ]]></script>';
			break;
			case 'pdf':
				$file_preview = '<iframe src="'.ORBX_SITE_URL.'/site/mercury/'.$a['content'].'?'.uniqid(md5(rand()), true).'" width="100%" height="500px"></iframe>';
				break;
			default: $file_preview = '<a href="'.ORBX_SITE_URL.'/site/mercury/'.$a['content'].'">'.$a['content'].'</a>';
			break;
		}
	}

?>

				<form method="post" action="">
				<input type="hidden" name="live_time" id="live_time" value="<?php echo $a['live_time']; ?>" />
				<input type="hidden" name="uploader_time" id="uploader_time" value="<?php echo $a['uploader_time']; ?>" />
				<input id="intro_text" name="intro_text" type="hidden" />
				<input name="save_article" type="submit" id="save_article" value="<?php echo _L('save'); ?>" /><br />
				<?php

					$q_c = sprintf('SELECT 	question_permalink, language
									FROM 	'.MAGISTER_CONTENTS.'
									WHERE 	UCASE(content)
									LIKE 	UCASE(%s)',
									$dbc->_db->quote('%site/mercury/'.$a['content'].'%'));
					$r_c = $dbc->_db->query($q_c);
					$a_c = $dbc->_db->fetch_assoc($r_c);

					while($a_c) {
						$url = ORBX_SITE_URL.'/?'.$a_c['language'].'=orbicon/magister&amp;read=clanak/'.$a_c['question_permalink'].'/';
						$active_links[] = '<a href="'.$url.'">'.$a_c['question_permalink'].'</a>';
						$a_c = $dbc->_db->fetch_assoc($r_c);
					}

					$dbc->_db->free_result($r_c);

					if(!empty($active_links)) {
						echo '<div style="margin-top:1em;padding:0.5em;font-size:90%;border:1px solid red;background:#e8e8e8;"><p><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/error-log.png" /> <strong>'._L('data_used').'</strong></p><ol><li>'
						. implode('</li><li>', $active_links)
						. '</li></ol></div>';
					}

				?>
				<p>
				<label for="title"><strong><?php echo _L('title'); ?></strong></label><br />
					<input style="width: 85%;" id="title" name="title" value="<?php echo str_replace('&amp;', '&', htmlspecialchars($a['title'])); ?>" />
				</p>
				<p><label for="category"><strong><?php echo _L('category'); ?></strong></label><br />
				<select name="category" id="category">
					<?php echo $hf->get_categories($a['category']); ?>
				</select></p>
				<p>
				<strong><?php echo _L('preview'); ?></strong><br />
				<?php echo $file_preview; ?>
				</p>
				<p>
				<label for="data_url"><?php echo _L('url'); ?></label><br />
				<input id="data_url" onclick="this.select();" style="width: 85%;" type="text" value="<?php echo ORBX_SITE_URL.'/site/mercury/' . htmlspecialchars($a['content']); ?>" />
				</p>
				<a href="javascript: void(null);" onclick="javascript: sh('extra_data');"><strong><?php echo _L('extra_info'); ?> [+]</strong></a>
				<div id="extra_data" style="display:none;">
					<p><label for="description"><strong><?php echo _L('description'); ?></strong><br /></label>
					<textarea style="width:85%; height:100px;" name="description" id="description"><?php echo $a['description']; ?></textarea></p>
					<p><label for="uploader"><strong><?php echo _L('author'); ?></strong><br /></label>
					<input name="uploader" id="uploader" type="text" value="<?php echo $a['uploader']; ?>" size="50" maxlength="200" /></p>
					<p><strong><?php echo _L('ip_addr'); ?></strong><br />
					<input disabled="disabled" type="text" value="<?php echo $a['uploader_ip']; ?>" size="50" maxlength="200" /></p>
					<p><strong><?php echo _L('live_date'); ?></strong><br />
					<input id="custom_live" name="custom_live" type="text" value="<?php echo $custom_live; ?>" size="50" maxlength="200" /></p>
					<p><strong><?php echo _L('last_mod'); ?></strong><br />
					<input disabled="disabled" type="text" value="<?php if(empty($a['last_modified'])) echo _L('none'); else {echo date('r', $a['last_modified']);} ?>" size="50" maxlength="200" /></p>
				</div>
</form>

				<p>
				<hr />
				<h3><label for="comment"><?php echo _L('comments'); ?></label></h3>
				<form method="post" action="">
				<textarea style="width:85%; height:100px;" id="comment" name="comment"></textarea><br />
				<input value="<?php echo _L('submit'); ?>" type="submit" id="add_comment" name="add_comment" />
				</form>
				</p>

<?php

	$r = $dbc->_db->query(sprintf('	SELECT 		*
									FROM 		'.MERCURY_COMMENTS.'
									WHERE 		(question_permalink = %s)
									ORDER BY 	uploader_time', $dbc->_db->quote($q[1])));
	$comment = $dbc->_db->fetch_assoc($r);

	while($comment) {
		echo '<p><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mercury&amp;read=data/'.$a['permalink'].'/delete-comment/'.$comment['id'].'" onclick="javascript: return false;" onmousedown="'.delete_popup('#' . $comment['id']).'">['._L('delete').']</a></p>';
		echo "<div class=\"answer_content\" id=\"answer_{$comment['id']}\">{$comment['content']}</div>";
		echo '<p class="answer_uploader">'._L('author').': '.$comment['uploader'] . ', '.date($_SESSION['site_settings']['date_format'], $comment['uploader_time']).'</p><hr />';

		$comment = $dbc->_db->fetch_assoc($r);
	}
	$dbc->_db->free_result($r);
?>
<div style="height: 1%;"></div>