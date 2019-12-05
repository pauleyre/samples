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
	require_once DOC_ROOT . '/orbicon/modules/news/class.news.admin.php';

	$news = new News_Admin;
	$news->delete_news();
	$news->save_news();
	$my_news = $news->load_news();
	$orbicon_x->set_page_title(utf8_html_entities($my_news['title'], true));

	$live_date = (isset($_GET['edit'])) ? $my_news['date'] : time();

	if(isset($_GET['edit'])) {
		// * load intro text
		$r = $dbc->_db->query(sprintf('		SELECT 		content, question_permalink
											FROM 		'.MAGISTER_CONTENTS.'
											WHERE 		(live = 1) AND
														(id = %s)
											LIMIT 		1', $dbc->_db->quote($my_news['intro'])));
		$a = $dbc->_db->fetch_assoc($r);

?>
<script type="text/javascript"><!-- // --><![CDATA[

	YAHOO.util.Event.addListener(window, "load", load_news_item);

	function load_news_item()
	{
		/* image */
		__venus_mini_input = '<?php echo $my_news['image']; ?>';

		if(!empty(__venus_mini_input)) {
			sh('res_intro_gfx_container');
		}

		__venus_mini_url = '<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon&ajax_img_db&action=img';
		__venus_mini_update_list();
		/* all text */
		__magister_mini_input = '<?php echo $my_news['content']; ?>';
		__magister_mini_url = '<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon&ajax_text_db&action=txt';
		__magister_mini_update_list();
		/* intro text */
		__change_intro_text('<?php echo base64_encode($a['content']); ?>', <?php echo intval($my_news['intro']); ?>);

		var __intro_content_check = '<?php echo $a['question_permalink']; ?>';
		if(__intro_content_check != '') {
			YAHOO.util.Event.addListener($('news_intro'), 'dblclick', function () {redirect(orbx_site_url + '/?' + __orbicon_ln +'=orbicon/magister&read=clanak/<?php echo $a['question_permalink']; ?>');});
		}
	}
// ]]></script>
<?php
	}

	$news_stats = '<div>'._L('news_views').': <strong>'. intval($a['views']) .'</strong></div><br />';

?>
<script defer="defer" type="text/javascript"><!-- // --><![CDATA[
	YAHOO.util.Event.addListener(window,"load",start_magister_mb);

	function start_magister_mb()
	{
		switch_mini_browser('magister', '', 0, 0);
		//__toggle_news_list();
	}

	function __news_clear_image()
	{
		var venus_content = $('news_image');
		venus_content.innerHTML = '';
		var img_input = $('news_img');
		img_input.value = '';
		YAHOO.util.Event.purgeElement(venus_content, false, "dblclick");
	}

	function verify_news(id)
	{
		var title = verify_title('news_title');

		if(title == false) {
			return false;
		}

		var el = $(id);

		if(empty(el.value)){
			alert('<?php echo _L('select_news_category'); ?>');
			el.focus();
			return false;
		}
		return true;
	}

// ]]></script>
<form action="" method="post" onsubmit="javascript: return verify_news('news_category');" id="news_form" name="news_form">
<br /><input name="save_news" type="submit" id="save_news" value="<?php echo _L('save'); ?>" />
<input <?php if(!isset($_GET['edit'])) {echo 'disabled="disabled"';} ?> type="button" value="<?php echo _L('add_new') ?>" onclick="javascript: redirect('<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/news'; ?>');"  />
<br /><br />


<input id="intro_text" name="intro_text" type="hidden" />
<input id="content_text" name="content_text" type="hidden" />
<input id="news_img" name="news_img" type="hidden" />
<input id="live_date" name="live_date" type="hidden" value="<?php echo $live_date; ?>" />

<?php
	// This is temporary hack for divided forms
	// Alen Novakovic, 09.01.2007.
?>
<input id="live" name="live" type="hidden" value="<?php echo $my_news['live']; ?>" />
<input id="news_category" name="news_category" type="hidden" value="<?php echo $my_news['category']; ?>" />
<input id="rss_push" name="rss_push" type="hidden" value="<?php echo $my_news['rss_push'];?>" />
<input id="news_redirect" name="news_redirect" type="hidden" value="<?php echo $my_news['redirect'];?>" />

<?php // hack ENDS?>




				<label for="news_title"><?php echo _L('title'); ?></label><br />
<div>
					<input onkeyup="javascript:get_permalink_exists(this.value, <?php echo intval($my_news['id']); ?>);" name="news_title" type="text" id="news_title" style="padding: 3px;width:99%" value="<?php echo str_sanitize($my_news['title'], STR_SANITIZE_INPUT_TEXT_VALUE); ?>" /><br />
				</div>

				<p>
		<?php echo _L('link_preview'); ?><br />
		<?php
			$url = ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='. $orbicon_x->urlnormalize($my_news['permalink']);
			echo "<a href=\"$url\">$url</a>";
		?>
	</p>

			<br />
			<div>
				<?php echo _L('subtitle'); ?><br />
				<div id="news_intro" style=" overflow:auto; padding: 1em; height: 50px; width:auto;background:#ffffff;"></div>
			</div>

			<br />
			<div>
				<?php echo _L('content'); ?><br />
				<div id="news_content" style=" overflow:auto;padding: 1em; height: 150px; width:auto;background:#ffffff;"></div>
			</div><br />

			<?php echo $news_stats; ?>

<input name="save_news" type="submit" id="save_news2" value="<?php echo _L('save'); ?>" />
<input <?php if(!isset($_GET['edit'])) {echo 'disabled="disabled"';} ?> type="button" value="<?php echo _L('add_new') ?>" onclick="javascript: redirect('<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/news'; ?>');"  />
</form>
