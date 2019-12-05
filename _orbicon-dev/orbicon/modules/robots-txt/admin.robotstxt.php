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
	if(isset($_POST['save_robotstxt'])) {
		$r = fopen(DOC_ROOT.'/robots.txt', 'wb');
		fwrite($r, $_POST['robotstxt']);
		fclose($r);
	}

	if(!is_file(DOC_ROOT . '/robots.txt')) {
		create_empty_file(DOC_ROOT . '/robots.txt');
	}

	$robotstxt = file_get_contents(DOC_ROOT.'/robots.txt');
?>

<form action="" method="post">

	<p>
		<a href="<?php echo ORBX_SITE_URL; ?>/robots.txt"><?php echo ORBX_SITE_URL; ?>/robots.txt</a>
	</p>
	<textarea id="robotstxt" name="robotstxt" style="width: 99%; height: 500px; font-family: monospace; font-size: 120%;"><?php echo $robotstxt; ?></textarea><br />

<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/3rdParty/edit_area/edit_area_full.js?<?php echo ORBX_BUILD; ?>"></script>
<script type="text/javascript"><!-- // --><![CDATA[
editAreaLoader.init({
	id : "robotstxt",
	syntax: "robotstxt",
	start_highlight: true,
	toolbar: "search, go_to_line, save, charmap, fullscreen, undo, redo, select_font, change_smooth_selection, highlight, reset_highlight, help",
	save_callback: "my_save",
	plugins: "charmap",
	language: '<?php echo $orbicon_x->ptr; ?>'
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
		data[0] = 'target=' + encodeURIComponent('robots.txt');
		data[1] = 'content=' + encodeURIComponent(content) + '<!>ORBICON_STR_SAFEGUARD<!>';
		data[3] = 'credo=' + _orbx_ajax_id;

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', url, callback, data);
	}

	if(!editAreaLoader) {
		document.write('<input name="save_robotstxt" type="submit" id="save_robotstxt2" value="<?php echo _L('save'); ?>" />');
	}

// ]]></script>

</form>