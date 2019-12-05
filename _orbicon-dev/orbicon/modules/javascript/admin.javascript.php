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

	$js_dir = DOC_ROOT.'/site/gfx/';
	$js_dir_url = '/site/gfx/';
	$js_files = glob($js_dir . '*.js');

	foreach($js_files as $js_file) {
		$js_file = basename($js_file);
		$style = ($_GET['js-x'] == $js_file) ? ' style="text-decoration: none;font-weight:bold;font-style:oblique;"' : ' style="text-decoration: none;"';

		$js_list .= '
		<li>
			<a '.$style.' href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/javascript&amp;js-x='.$js_file.'">'.$js_file.'</a>
		</li>';
	}

	unset($js_files);

	if(isset($_POST['save_javascript']) && isset($_GET['js-x'])) {
		$file = $js_dir.basename($_GET['js-x']);
		chmod_unlock($file);
		$r = fopen($file, 'wb');
		/* Set a 64k buffer. */
		if(function_exists('stream_set_write_buffer')) {
			stream_set_write_buffer($r, 65535);
		}
		fwrite($r, $_POST['javascript']);
		fclose($r);
		chmod_lock($file);
	}

	$javascript = (isset($_GET['js-x'])) ? htmlspecialchars(file_get_contents($js_dir.basename($_GET['js-x']))) : '';
	$editor_ln = (is_file(DOC_ROOT . '/orbicon/3rdParty/edit_area/langs/' .  $orbicon_x->ptr . '.js')) ?  $orbicon_x->ptr : 'en';

?>
<form action="" method="post">

	<p>
	<textarea id="javascript" name="javascript" class="editor_area"><?php echo $javascript; ?></textarea></p>


<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/3rdParty/edit_area/edit_area_full.js?<?php echo ORBX_BUILD; ?>"></script>
<script type="text/javascript"><!-- // --><![CDATA[
editAreaLoader.init({
	id : "javascript",
	syntax: "js",
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
			sh_ind();
		}

		var callback =
		{
			success:handleSuccess
		};

		var url = orbx_site_url + '/orbicon/controler/ajax.save.php';
		var data = new Array();
		data[0] = 'target=' + encodeURIComponent('<?php echo 'site/gfx/'.$_GET['js-x']; ?>');
		data[1] = 'content=' + encodeURIComponent(content) + '<!>ORBICON_STR_SAFEGUARD<!>';
		data[2] = 'gzip=1';
		data[3] = 'credo=' + _orbx_ajax_id;

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
	}

	if(!editAreaLoader) {
		document.write('<input name="save_javascript" type="submit" id="save_javascript2" value="<?php echo _L('save'); ?>" />');
	}

// ]]></script>

</form>