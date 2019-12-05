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
	require_once DOC_ROOT . '/orbicon/class/inc.column.admin.php';

	save_column();
	$my_column = load_column();

	$is_box = ($my_column['menu_name'] == 'box') ? true : false;
	$orbicon_x->set_page_title(utf8_html_entities($my_column['title'], true));

?>
<script type="text/javascript"><!-- // --><![CDATA[

	YAHOO.util.Event.addListener(window,"load",load_news_item);

	function load_news_item()
	{
		/* all text */
		__magister_mini_input = '<?php echo $my_column['content']; ?>';
		__magister_mini_url = "<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon&ajax_text_db&action=txt";
		__magister_column_type = '<?php echo $my_column['type']; ?>';
		__magister_mini_update_list();
		display_created_forms('<?php echo $my_column['type']; ?>');
		switch_mini_browser('magister', '', 0, 0);
	}

	function display_created_forms(input)
	{
		try {
			var _forms = $('form_list');
			var _videos = $('video_list');
			var _photos = $('photo_list');
			var _dl = $('dl_list');

			if(input == 'form') {
				_forms.style.display = 'block';
				_photos.style.display = 'none';
				_videos.style.display = 'none';
				_dl.style.display = 'none';
			}
			else if(input == 'photo') {
				_forms.style.display = 'none';
				_photos.style.display = 'block';
				_videos.style.display = 'none';
				_dl.style.display = 'none';
			}
			else if(input == 'video') {
				_forms.style.display = 'none';
				_photos.style.display = 'none';
				_videos.style.display = 'block';
				_dl.style.display = 'none';
			}
			else if(input == 'data') {
				_forms.style.display = 'none';
				_photos.style.display = 'none';
				_videos.style.display = 'none';
				_dl.style.display = 'block';
			}
			else {
				_forms.style.display = 'none';
				_photos.style.display = 'none';
				_videos.style.display = 'none';
				_dl.style.display = 'none';
			}
		}
		catch(e){}
	}

// ]]></script>
<form action="" method="post" id="column_form" name="column_form" onsubmit="javascript: return verify_title('column_title');">
	<input id="content_text" name="content_text" type="hidden" />

	<input name="save_column" type="submit" id="save_column" value="<?php echo _L('save'); ?>" />
	<input type="button" value="<?php echo _L('add_new') ?>" onclick="javascript: redirect(orbx_site_url + '/?' + __orbicon_ln + '=orbicon/columns');"  />

	<input id="box_zone" name="box_zone" type="hidden" value="<?php echo $my_column['box_zone']; ?>" />
	<input id="column_id" name="column_id" type="hidden" value="<?php echo $my_column['true_id']; ?>" />
<?php
	if(!$is_box) {
?>

	<input name="column_type" id="column_type" type="hidden" value="<?php echo $my_column['type']; ?>" />
	<input name="image_categories" id="image_categories" type="hidden" value="<?php echo $my_column['content']; ?>" />
	<input name="existing_form" id="existing_form" type="hidden" value="<?php echo $my_column['content']; ?>" />
	<input name="data_categories" id="data_categories" type="hidden" value="<?php echo $my_column['content']; ?>" />
	<input name="dl_categories" id="dl_categories" type="hidden" value="<?php echo $my_column['content']; ?>" />
	<input name="column_redirect" id="column_redirect" type="hidden" value="<?php echo $my_column['redirect']; ?>" />
	<input name="template" id="template" type="hidden" value="<?php echo $my_column['template']; ?>" />
	<input name="group" id="group" type="hidden" value="<?php echo $my_column['group']; ?>" />
	<input name="parent" id="parent" type="hidden" value="<?php echo $my_column['parent']; ?>" />
	<input name="menu_name" id="menu_name" type="hidden" value="<?php echo $my_column['menu_name']; ?>" />
	<input name="desc" id="desc" type="hidden" value="<?php echo $my_column['desc']; ?>" />

	<p><label for="column_title"><?php echo _L('title'); ?></label><br />
		<input style="width:50em;" name="column_title" type="text" id="column_title" value="<?php echo str_sanitize($my_column['title'], STR_SANITIZE_INPUT_TEXT_VALUE); ?>"  maxlength="200" />
	</p>

	<p>
		<?php echo _L('link_preview'); ?><br />
		<?php
			$url = ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='. $orbicon_x->urlnormalize($my_column['permalink']);
			echo "<a href=\"$url\">$url</a>";
		?>
	</p>


  	<table>
		<tr>
			<td colspan="2"><?php echo _L('content'); ?></td>
		</tr>
		<tr class="high_row">
			<td style="vertical-align:top;"><div id="news_content"></div></td>
			<td></td>
		</tr>
	</table>
  <?php
  	}
	else {
		$box_style = explode(';', $my_column['box_style']);

		$box_style['border'] = explode(':', $box_style[0]);
		$box_style['border'] = ($box_style['border'] == 'transparent') ? str_replace('#', '', $box_style['border'][1]) : $box_style['border'][1];

		$box_style['background'] = explode(':', $box_style[1]);
		$box_style['background'] = ($box_style['background'] == 'transparent') ? str_replace('#', '', $box_style['background'][1]) : $box_style['background'][1];

  ?>
<script type="text/javascript"><!-- // --><![CDATA[

	function __box_change_color(color, type)
	{
		if(!empty(color)) {
			var first_char = color.charAt(0);
			color = (first_char == '#') ? color : (color == 'transparent') ? color : '#' + color;
			var el = $('column_' + type + '_preview');
			var input = $('column_' + type);

			input.value = color;
			try {
				el.style.backgroundColor = color;
			}
			catch(e){}
		}
	}

	YAHOO.util.Event.addListener(window,"load",load_colors);

	function load_colors() {
		__box_change_color('<?php echo $box_style['border']; ?>', 'border');
		__box_change_color('<?php echo $box_style['background']; ?>', 'background');
	}

// ]]></script>

	<input name="column_border" id="column_border" type="hidden" value="<?php echo $box_style['border']; ?>" />
	<input name="column_background" id="column_background" type="hidden" value="<?php echo $box_style['background']; ?>" />

  	<p>
		<label for="column_title"><?php echo _L('title'); ?></label><br />
		<input name="column_title" type="text" id="column_title" value="<?php echo $my_column['title']; ?>" maxlength="200" />
	</p>

				<?php echo _L('content'); ?>

				<div id="<?php echo get_permalink($my_column['title']); ?>" style="border:1px solid <?php echo $box_style['border']; ?>; background-color:<?php echo $box_style['background']; ?>;">
					<div id="news_content"></div>
				</div>


  <?php
  	}
  ?>

  <input name="save_column" type="submit" id="save_column2" value="<?php echo _L('save'); ?>" />
  <input type="button" value="<?php echo _L('add_new') ?>" onclick="javascript: redirect(orbx_site_url + '/?' + __orbicon_ln + '=orbicon/columns');"  />
</form>