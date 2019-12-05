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

	$html_dir = DOC_ROOT.'/site/gfx/';
	$html_dir_url = '/site/gfx/';
	$html_files = glob($html_dir . '*.html');

	foreach ($html_files as $html_file) {
		$html_file = basename($html_file);
		$style = ($_GET['html-x'] == $html_file) ? ' style="font-weight:bold;font-style:oblique;"' : '';
		$html_list .= '<li>

			<a '.$style.' href="' . ORBX_SITE_URL . '/?' . $orbicon_x->ptr.'=orbicon/mod/html&amp;html-x='.$html_file.'">'.$html_file.'</a>

			<a href="http://validator.w3.org/check?uri='.urlencode(ORBX_SITE_URL.$html_dir_url.$html_file).'" target="_blank">
					<img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/html_valid.png" />
				</a>
			</li>';
	}

	if(isset($_POST['save_html']) && isset($_GET['html-x'])) {
		$file = $html_dir.basename($_GET['html-x']);
		chmod_unlock($file);
		$r = fopen($file, 'wb');
		/* Set a 64k buffer. */
		if(function_exists('stream_set_write_buffer')) {
			stream_set_write_buffer($r, 65535);
		}
		fwrite($r, $_POST['html']);
		fclose($r);
		chmod_lock($file);
	}

	$html = (isset($_GET['html-x'])) ? htmlspecialchars(file_get_contents($html_dir.basename($_GET['html-x']))) : '';
	$editor_ln = (is_file(DOC_ROOT . '/orbicon/3rdParty/edit_area/langs/' .  $orbicon_x->ptr . '.js')) ?  $orbicon_x->ptr : 'en';

?>

<script type="text/javascript"><!-- // --><![CDATA[

	function __preview_html(target, content)
	{
		sh_ind();
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				window.open(orbx_site_url + '/?preview_mode=html');
			}
			sh_ind();
		}

		var callback =
		{
			success:handleSuccess
		};

		var url = orbx_site_url + '/orbicon/controler/ajax.save.php';
		var data = new Array();
		data[0] = 'target=' + encodeURIComponent(target);
		data[1] = 'content=' + encodeURIComponent(content) + '<!>ORBICON_STR_SAFEGUARD<!>';
		data[2] = 'gzip=0';
		data[3] = 'credo=' + _orbx_ajax_id;

		data = data.join('&');

		var request = YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
	}

	function validate_template_marks()
	{
		var template = editAreaLoader.getValue('html');
		var metatags = template.search('<!>METATAGS');
		var admin = template.search('<!>ADMIN');
		var infobox = template.search('<!>TOP_INFOBOX');

		metatags = (metatags == -1) ? 'NOT PRESENT' : 'PRESENT';
		admin = (admin == -1) ? 'NOT PRESENT' : 'PRESENT';
		infobox = (infobox == -1) ? 'NOT PRESENT' : 'PRESENT';

		var msg = '<?php echo _L('val_results'); ?>\n--------------------------------------\n<!>METATAGS : '+metatags+'\n<!>ADMIN : '+admin+'\n<!>TOP_INFOBOX : '+infobox+'\n';

		// a template mark is missing
		if(msg.search('NOT PRESENT') != -1) {
			var add = window.confirm(msg + '--------------------------------------\n<?php echo _L('no_sys_tpl_marks'); ?>.\n\n<?php echo _L('add_them'); ?>?');

			if(add == true) {
				rearrange_tpl_marks();
			}
		}
		else {
			window.alert(msg + '--------------------------------------\n<?php echo _L('all_tpl_marks_ok'); ?>!');
		}
	}

	function rearrange_tpl_marks()
	{
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				if(o.responseText == 'done') {
					window.alert('<?php echo _L('tpl_marks_added'); ?>');
					//redirect(window.location);
				}
				else {
					window.alert('<?php echo _L('fail_add_tpl_marks'); ?>');
				}
			}
		}

		var callback =
		{
			success:handleSuccess,
			timeout: 15000
		};

		var data = new Array();
		data[0] = 'credentials=' + _orbx_ajax_id;

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', orbx_site_url + '/orbicon/modules/html/arrange.tpl.marks.php', callback, data);
	}

// ]]></script>

<form action="" method="post">
	<!--
	<input name="save_html" type="submit" id="save_html" value="<?php echo _L('save'); ?>"  />
	<input onclick="javascript:__preview_html('site/mercury/pre-<?php echo basename($_GET['html-x']); ?>', editAreaLoader.getValue('html'));" type="button" value="<?php echo _L('preview'); ?>" <?php if(strpos($_GET['html-x'], 'home.html') === false) {echo ' disabled="disabled"'; } ?> />
	<input type="button" value="<?php echo _L('validate_marks'); ?>"  onclick="javascript: validate_template_marks();" /><br />
	-->

	<p>
	<textarea id="html" name="html" class="editor_area"><?php echo $html; ?></textarea>
</p>

	<input onclick="javascript:__preview_html('site/mercury/pre-<?php echo basename($_GET['html-x']); ?>', $('html').value);" type="button" value="<?php echo _L('preview'); ?>" <?php if(strpos($_GET['html-x'], 'home.html') === false) {echo ' disabled="disabled"'; } ?> />
	<input type="button" value="<?php echo _L('validate_marks'); ?>" onclick="javascript: validate_template_marks();" />


<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/3rdParty/edit_area/edit_area_full.js?<?php echo ORBX_BUILD; ?>"></script>
<script type="text/javascript"><!-- // --><![CDATA[
editAreaLoader.init({
	id : "html",
	syntax: "html",
	start_highlight: true,
	toolbar: "search, go_to_line, save, charmap, fullscreen, undo, redo, select_font, change_smooth_selection, highlight, reset_highlight, help",
	save_callback: "my_save",
	plugins: "charmap",

	language: '<?php echo $editor_ln; ?>'
});
	function my_save(id, content)
	{
		sh_ind();
		var handleSuccess = function(o) {

			if(o.responseText == '0') {
				window.alert('ERROR: Failed to save. Please try again');
			}
			else if(o.responseText == '1') {
				// window.alert('Saved');
			}
			sh_ind();
		}

		var callback =
		{
			success:handleSuccess
		};

		var url = orbx_site_url + '/orbicon/controler/ajax.save.php';
		var data = new Array();
		data[0] = 'target=' + encodeURIComponent('<?php echo 'site/gfx/' . $_GET['html-x']; ?>');
		data[1] = 'content=' + encodeURIComponent(content)  + '<!>ORBICON_STR_SAFEGUARD<!>';
		data[2] = 'gzip=0';
		data[3] = 'credo=' + _orbx_ajax_id;
		
		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
	}

	if(!editAreaLoader) {
		document.write('<input name="save_html" type="submit" id="save_html2" value="<?php echo _L('save'); ?>" />');
	}

// ]]></script>


</form>