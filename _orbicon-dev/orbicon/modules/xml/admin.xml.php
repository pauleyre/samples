<?php


	$css_dir = DOC_ROOT.'/site/gfx/';
	$css_dir_url = '/site/gfx/';
	$css_files = glob($css_dir . '*.xml');

	foreach($css_files as $css_file) {
		$css_file = basename($css_file);
		$style = ($_GET['xml-x'] == $css_file) ? ' style="text-decoration: none;font-weight:bold;font-style:oblique;"' : ' style="text-decoration: none;"';

		$css_list .= '
		<li>
			<a '.$style.' href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr."=orbicon/mod/xml&amp;xml-x=$css_file\">$css_file</a>
		</li>";
	}

	unset($css_files);

	if(isset($_POST['save_xml']) && isset($_GET['xml-x'])) {

		$file = $css_dir.basename($_GET['xml-x']);
		chmod_unlock($file);
		$r = fopen($file, 'wb');
		/* Set a 64k buffer. */
		if(function_exists('stream_set_write_buffer')) {
			stream_set_write_buffer($r, 65535);
		}
		fwrite($r, $_POST['xml']);
		fclose($r);
		chmod_lock($file);
	}

	$xml = (isset($_GET['xml-x'])) ? htmlspecialchars(file_get_contents($css_dir.basename($_GET['xml-x']))) : '';
	$editor_ln = (is_file(DOC_ROOT . '/orbicon/3rdParty/edit_area/langs/' .  $orbicon_x->ptr . '.js')) ?  $orbicon_x->ptr : 'en';

?>

<form action="" method="post" id="admin_xml_form">
	<!--
	<input name="save_css" type="submit" id="save_css" value="<?php echo _L('save'); ?>" /> <input onclick="javascript:__preview_css('site/mercury/pre-site.css', editAreaLoader.getValue('css'));" type="button" value="<?php echo _L('preview'); ?>" <?php if($_GET['css-x'] != 'site.css') {echo ' disabled="disabled"'; } ?> /><br />
	-->
	<p>
		<textarea id="xml" name="xml"><?php echo $xml; ?></textarea>
	</p>

<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/3rdParty/edit_area/edit_area_full.js?<?php echo ORBX_BUILD; ?>"></script>
<script type="text/javascript"><!-- // --><![CDATA[

editAreaLoader.init({
	id : "xml",
	syntax: "xml",
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
		data[0] = 'target=' + encodeURIComponent('<?php echo 'site/gfx/'.$_GET['xml-x']; ?>');
		data[1] = 'content=' + encodeURIComponent(content) + '<!>ORBICON_STR_SAFEGUARD<!>';
		data[2] = 'gzip=0';
		data[3] = 'credo=' + _orbx_ajax_id;

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
	}

	if(!editAreaLoader) {
		document.write('<input name="save_xml" type="submit" id="save_xml2" value="<?php echo _L('save'); ?>" />');
	}

// ]]></script>

</form>