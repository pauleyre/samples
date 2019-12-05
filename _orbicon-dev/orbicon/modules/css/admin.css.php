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

	$css_dir = DOC_ROOT.'/site/gfx/';
	$css_dir_url = '/site/gfx/';
	$css_files = glob($css_dir . '*.css');

	foreach($css_files as $css_file) {
		$css_file = basename($css_file);
		$style = ($_GET['css-x'] == $css_file) ? ' style="text-decoration: none;font-weight:bold;font-style:oblique;"' : ' style="text-decoration: none;"';

		$css_list .= '
		<li>
			<a '.$style.' href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr."=orbicon/mod/css&amp;css-x=$css_file\">$css_file</a>
			<a href=\"http://jigsaw.w3.org/css-validator/validator?uri=".urlencode(ORBX_SITE_URL.$css_dir_url.$css_file).'" target="_blank">
				<img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/css_valid.png" />
			</a>
		</li>';
	}

	unset($css_files);

	if(isset($_POST['save_css']) && isset($_GET['css-x'])) {

		$file = $css_dir.basename($_GET['css-x']);
		chmod_unlock($file);
		$r = fopen($file, 'wb');
		/* Set a 64k buffer. */
		if(function_exists('stream_set_write_buffer')) {
			stream_set_write_buffer($r, 65535);
		}
		fwrite($r, $_POST['css']);
		fclose($r);
		chmod_lock($file);
	}

	$css = (isset($_GET['css-x'])) ? htmlspecialchars(file_get_contents($css_dir.basename($_GET['css-x']))) : '';
	$editor_ln = (is_file(DOC_ROOT . '/orbicon/3rdParty/edit_area/langs/' .  $orbicon_x->ptr . '.js')) ?  $orbicon_x->ptr : 'en';

?>

<script type="text/javascript"><!-- // --><![CDATA[

	function __preview_css(target, content)
	{
		sh_ind();
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				window.open(orbx_site_url + '/?preview_mode=css');
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
		data[2] = 'gzip=1';
		data[3] = 'credo=' + _orbx_ajax_id;
		
		data = data.join('&');

		var request = YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
	}

// ]]></script>

<form action="" method="post" id="admin_css_form">
	<!--
	<input name="save_css" type="submit" id="save_css" value="<?php echo _L('save'); ?>" /> <input onclick="javascript:__preview_css('site/mercury/pre-site.css', editAreaLoader.getValue('css'));" type="button" value="<?php echo _L('preview'); ?>" <?php if($_GET['css-x'] != 'site.css') {echo ' disabled="disabled"'; } ?> /><br />
	-->
	<p>
		<textarea id="css" name="css"><?php echo $css; ?></textarea>
	</p>

<input onclick="javascript:__preview_css('site/mercury/pre-site.css', $('css').value);" type="button" value="<?php echo _L('preview'); ?>" <?php if($_GET['css-x'] != 'site.css') {echo ' disabled="disabled"'; } ?> />

<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/3rdParty/edit_area/edit_area_full.js?<?php echo ORBX_BUILD; ?>"></script>
<script type="text/javascript"><!-- // --><![CDATA[

editAreaLoader.init({
	id : "css",
	syntax: "css",
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
		data[0] = 'target=' + encodeURIComponent('<?php echo 'site/gfx/'.$_GET['css-x']; ?>');
		data[1] = 'content=' + encodeURIComponent(content) + '<!>ORBICON_STR_SAFEGUARD<!>';
		data[2] = 'gzip=1';
		data[3] = 'credo=' + _orbx_ajax_id;
		
		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
	}

	if(!editAreaLoader) {
		document.write('<input name="save_css" type="submit" id="save_css2" value="<?php echo _L('save'); ?>" />');
	}

// ]]></script>

</form>