<?php
/**
 * Venus expo
 *
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconFE
 * @subpackage Venus
 * @version 1.5
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-05-01
 */

	(array) $aQuery = explode('/', $_GET['read']);

	$rResult = $dbc->_db->query(sprintf('	SELECT 	*
											FROM 	'.VENUS_IMAGES.'
											WHERE	(live = 1) AND
													(permalink = %s)',
													$dbc->_db->quote($aQuery[1])));
	$image = $dbc->_db->fetch_assoc($rResult);
	$dbc->_db->free_result($rResult);

	if(empty($image['id'])) {
		header('HTTP/1.1 404 Not Found', true);
		$_SESSION['cache_status'] = 404;
		$image['title'] = '404 Not Found';
	}

	$orbicon_x->set_page_title($image['title']);
	
	if(($aQuery[2] == 'delete-image') && is_numeric($aQuery[3])) {
		$expo -> db_open();
		$dbc->_db->query('	DELETE
							FROM '.VENUS_IMAGES.'
							WHERE (id = '.$aQuery[3].')
							LIMIT 1');
		$expo -> db_close();
	}

	$image_info = getimagesize(DOC_ROOT.'/site/venus/'.$image['permalink']);

	switch($image_info[2]) {
		case IMAGETYPE_GIF: 		$ext = 'GIF'; break;
		case IMAGETYPE_JPEG: 		$ext = 'JPG'; break;
		case IMAGETYPE_PNG: 		$ext = 'PNG'; break;
		case IMAGETYPE_SWF: 		$ext = 'SWF'; break;
		case IMAGETYPE_PSD: 		$ext = 'PSD'; break;
		case IMAGETYPE_BMP: 		$ext = 'BMP'; break;
		case IMAGETYPE_TIFF_II:		$ext = 'TIFF (intel byte order)'; break; // (intel byte order)
		case IMAGETYPE_TIFF_MM: 	$ext = 'TIFF (motorola byte order)'; break; // (motorola byte order)
		case IMAGETYPE_JPC: 		$ext = 'JPC'; break;
		case IMAGETYPE_JP2: 		$ext = 'JP2'; break;
		case IMAGETYPE_JPX: 		$ext = 'JPX'; break;
		case IMAGETYPE_JB2: 		$ext = 'JB2'; break;
		case IMAGETYPE_SWC: 		$ext = 'SWC'; break;
		case IMAGETYPE_IFF: 		$ext = 'IFF'; break;
		case IMAGETYPE_WBMP: 		$ext = 'WBMP'; break;
		case IMAGETYPE_XBM: 		$ext = 'XBM'; break;
		default: 					$ext = 'N/A'; break;
	}

	$format = strtolower($ext);
	$size = get_file_size(DOC_ROOT.'/site/venus/'.$image['permalink']);

?>
<script type="text/javascript"><!-- // --><![CDATA[
	var org_imageWidth = <?php echo $image_info[0]; ?>;
	var org_imageHeight = <?php echo $image_info[1]; ?>;
	var crop_script_server_file = '<?php echo ORBX_SITE_URL; ?>/orbicon/controler/admin.venus.tools.php';
// ]]></script>
<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/javascript/orbicon.venus.tools.js?<?php echo ORBX_BUILD; ?>"></script>

<p>
<?php
	if(function_exists('imagecopyresampled')) {
?>
<strong><?php echo _L('tools'); ?> : </strong>
<a href="<?php echo ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/venus&amp;read='.$_GET['read'];?>&amp;tools=crop"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/crop.png" /> <?php echo _L('crop'); ?></a>
| <a href="<?php echo ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/venus&amp;read='.$_GET['read'];?>&amp;tools=resize"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/resize.png" /> <?php echo _L('resize'); ?></a>
| <a href="<?php echo ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/venus&amp;read='.$_GET['read'];?>&amp;tools=grayscale"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/grayscale.png" /> <?php echo _L('grayscale'); ?></a>
<?php
	}
	else {
		echo _L('gd_extension_not_installed');
	}

	if(function_exists('imageconvolution')) {
?>
| <a href="<?php echo ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/venus&amp;read='.$_GET['read'];?>&amp;tools=sharpen"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/sharpen.png" /> <?php echo _L('sharpen'); ?></a>
| <a href="<?php echo ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/venus&amp;read='.$_GET['read'];?>&amp;tools=blur"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/blur.png" /> <?php echo _L('blur'); ?></a>
| <a href="<?php echo ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/venus&amp;read='.$_GET['read'];?>&amp;tools=emboss"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/emboss.png" /> <?php echo _L('emboss'); ?></a>
| <a href="<?php echo ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/venus&amp;read='.$_GET['read'];?>&amp;tools=edge_detect"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/edge_detect.png" /> <?php echo _L('edge_detect'); ?></a>
| <a href="<?php echo ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/venus&amp;read='.$_GET['read'];?>&amp;tools=edge_enhance"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/edge_enhance.png" /> <?php echo _L('edge_enhance'); ?></a>
<?php
	}
?>
</p>
<table  width="100%">
<tr>
<td valign="top">
<?php
	if($_GET['tools'] == 'crop') {
?>
	<link rel="stylesheet" href="<?php echo ORBX_SITE_URL; ?>/orbicon/venus/crop/image-crop.css">

<script type="text/javascript"><!-- // --><![CDATA[

	/* Variables you could modify */

	var cropToolBorderWidth = 1;	// Width of dotted border around crop rectangle
	var smallSquareWidth = 7;	// Size of small squares used to resize crop rectangle

	// Size of image shown in crop tool
	var crop_imageWidth = <?php echo $image_info[0];?>;
	var crop_imageHeight = <?php echo $image_info[1];?>;

	// Size of original image
	var crop_originalImageWidth = <?php echo $image_info[0];?>;
	var crop_originalImageHeight = <?php echo $image_info[1];?>;

	var crop_minimumPercent = 10;	// Minimum percent - resize
	var crop_maximumPercent = 200;	// Maximum percent -resize

	var crop_minimumWidthHeight = 15;	// Minimum width and height of crop area

	var updateFormValuesAsYouDrag = true;	// This variable indicates if form values should be updated as we drag. This process could make the script work a little bit slow. That's why this option is set as a variable.
	if(!document.all)updateFormValuesAsYouDrag = false;	// Enable this feature only in IE

	/* End of variables you could modify */
// ]]></script>
<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/venus/crop/image-crop.js?<?php echo ORBX_BUILD; ?>"></script>
<script type="text/javascript"><!-- // --><![CDATA[
	YAHOO.util.Event.addListener(window,"load",init_imageCrop);
// ]]></script>
<div id="pageContent">
<div id="dhtmlgoodies_xpPane">
	<div class="dhtmlgoodies_panel">
		<div>
			<!-- Start content of pane -->
			<form>
			<input  type="hidden" id="input_image_ref" value="<?php echo $image['permalink']; ?>" />
			<table>
				<tr>
					<td><label for="input_crop_x">X:</label></td><td><input type="text" class="textInput" name="crop_x" id="input_crop_x"></td>
				</tr>
				<tr>
					<td><label for="input_crop_y">Y:</label></td><td><input type="text" class="textInput" name="crop_y" id="input_crop_y"></td>
				</tr>
				<tr>
					<td><label for="input_crop_width"><?php echo _L('width'); ?>:</label></td><td><input type="text" class="textInput" name="crop_width" id="input_crop_width"></td>
				</tr>
				<tr>
					<td><label for="input_crop_height"><?php echo _L('height'); ?>:</label></td><td><input type="text" class="textInput" name="crop_height" id="input_crop_height"></td>
				</tr>
				<tr style="display:none;">
					<td><label for="crop_percent_size"><?php echo _L('percent_size'); ?>:</label></td><td><input type="text" class="textInput" name="crop_percent_size" id="crop_percent_size" value="100"></td>
				</TR>
				<tr>
					<td><label for="input_convert_to"><?php echo _L('convert_to'); ?>:</label></td>
					<td>
						<select class="textInput" id="input_convert_to">
						<optgroup label="select image format">
							<option value="jpg">JPEG</option>
							<option value="gif">GIF</option>
							<option value="png">PNG</option>
						</optgroup>
						</select>
					</td>
				</tr>
				<tr>
					<td></td>
					<td id="cropButtonCell"><input type="button" onclick="javascript:cropScript_executeCrop(this);" value="<?php echo _L('crop'); ?>">

					</td>
				</tr>
			</table>
			<!-- <div id="crop_progressBar">

			</div>		-->
			</form>
			<!-- End content -->
		</div>
	</div>
	<span id="label_dimension" style="display:none;"></span>


</div>

<div class="crop_content">
<div id="imageContainer" class="alen_hack">
<img src="<?php echo ORBX_SITE_URL; ?>/site/venus/<?php echo $image['permalink']; ?>?<?php echo md5(uniqid(rand(), true)); ?>" height="<?php echo $image_info[1];?>" width="<?php echo $image_info[0];?>" />
</div>
</div>
</div>

<div style="clear:both;"></div>

<?php
	}
	else if($_GET['tools'] == 'resize') {
		$value_w = $image_info[0];
		$value_h = $image_info[1];
?>
<p>
<form>
<input type="hidden" id="input_image_ref" value="<?php echo $image['permalink']; ?>" />
<input type="checkbox" id="proportions" name="proportions" value="yes" /><label for="proportions"> <?php echo _L('keep_proportions'); ?></label><br />
<select id="unit" name="unit" onchange="javascript:switch_image_unit();">
	<optgroup label="<?php echo _L('measuring_unit'); ?>">
		<option value="px"><?php echo _L('pixel'); ?></option>
		<option value="percent"><?php echo _L('percent'); ?></option>
	</optgroup>
</select><label for="unit"> <?php echo _L('measuring_unit'); ?></label><br />
<input onchange="javascript:resize_normalize_inputs('width');" type="text" value="<?php echo $value_w; ?>" id="width" name="width" /><label for="width"> <?php echo _L('width'); ?></label><br />
<input onchange="javascript:resize_normalize_inputs('height');" type="text" value="<?php echo $value_h; ?>" id="height" name="height" /><label for="height"> <?php echo _L('height'); ?></label><br />
<input type="button" value="<?php echo _L('resize'); ?>" onclick="javascript:resize();" /><br />
</form>
</p>
<?php
		echo '<div class="alen_hack"><img id="current_image" src="'.ORBX_SITE_URL.'/site/venus/'.$image['permalink'].'?'.md5(uniqid(rand(), true)).'" /></div>';
	}
	else if($_GET['tools'] == 'sharpen') {
		echo '
		<input  type="hidden" id="input_image_ref" value="'.$image['permalink'].'" />
		<div class="alen_hack">
		<img src="'.ORBX_SITE_URL.'/site/venus/'.$image['permalink'].'?'.md5(uniqid(rand(), true)).'" /></div><br />
		<input type="button" value="'._L('sharpen').'" onclick="javascript:sharpen();" /><br />';
	}
	else if($_GET['tools'] == 'blur') {
		echo '
		<input  type="hidden" id="input_image_ref" value="'.$image['permalink'].'" />
		<div class="alen_hack">
		<img src="'.ORBX_SITE_URL.'/site/venus/'.$image['permalink'].'?'.md5(uniqid(rand(), true)).'" /></div><br />
		<input type="button" value="'._L('blur').'" onclick="javascript:blur_image();" /><br />';
	}
	else if($_GET['tools'] == 'emboss') {
		echo '
		<input  type="hidden" id="input_image_ref" value="'.$image['permalink'].'" />
		<div class="alen_hack">
		<img src="'.ORBX_SITE_URL.'/site/venus/'.$image['permalink'].'?'.md5(uniqid(rand(), true)).'" /></div><br />
		<input type="button" value="'._L('emboss').'" onclick="javascript:emboss();" /><br />';
	}
	else if($_GET['tools'] == 'edge_enhance') {
		echo '
		<input  type="hidden" id="input_image_ref" value="'.$image['permalink'].'" />
		<div class="alen_hack">
		<img src="'.ORBX_SITE_URL.'/site/venus/'.$image['permalink'].'?'.md5(uniqid(rand(), true)).'" /></div><br />
		<input type="button" value="'._L('edge_enhance').'" onclick="javascript:edge_enhance();" /><br />';
	}
	else if($_GET['tools'] == 'edge_detect') {
		echo '
		<input  type="hidden" id="input_image_ref" value="'.$image['permalink'].'" />
		<div class="alen_hack">
		<img src="'.ORBX_SITE_URL.'/site/venus/'.$image['permalink'].'?'.md5(uniqid(rand(), true)).'" /></div><br />
		<input type="button" value="'._L('edge_detect').'" onclick="javascript:edge_detect();" /><br />';
	}
	else if($_GET['tools'] == 'grayscale') {
		echo '
		<input  type="hidden" id="input_image_ref" value="'.$image['permalink'].'" />
		<div class="alen_hack">
		<img src="'.ORBX_SITE_URL.'/site/venus/'.$image['permalink'].'?'.md5(uniqid(rand(), true)).'" /></div><br />
		<input type="button" value="'._L('grayscale').'" onclick="javascript:grayscale();" /><br />';
	}
	else {

		$q_c = sprintf('SELECT question_permalink,language FROM '.MAGISTER_CONTENTS.' WHERE content LIKE %s', $dbc->_db->quote('%/site/venus/'.$image['permalink'].'%'));
		$r_c = $dbc->_db->query($q_c);
		$a_c = $dbc->_db->fetch_array($r_c);

		while($a_c) {
			$url = ORBX_SITE_URL.'/?'.$a_c['language'].'=orbicon/magister&amp;read=clanak/'.$a_c['question_permalink'].'/';
			$active_links[] = '<a href="'.$url.'">'.$a_c['question_permalink'].'</a>';
			$a_c = $dbc->_db->fetch_array($r_c);
		}

		$dbc->_db->free_result($r_c);

		// banner check
		$q_c = sprintf('SELECT 		id
							FROM 	'.TABLE_BANNERS.'
							WHERE 	(permalink = %s)
							LIMIT 	1', $dbc->_db->quote($image['permalink']));
		$r_c = $dbc->_db->query($q_c);
		$a_c = $dbc->_db->fetch_array($r_c);

		if(!empty($a_c['id'])) {
			$active_links[] = _L('as_active_banner');
		}

		// intro gfx check
		$q_c = sprintf('	SELECT 	permalink, language
							FROM 	'.TABLE_NEWS.'
							WHERE 	(image = %s)', $dbc->_db->quote($image['permalink']));
		$r_c = $dbc->_db->query($q_c);
		$a_c = $dbc->_db->fetch_array($r_c);

		while($a_c) {
			$url = ORBX_SITE_URL.'/?'.$a_c['language'].'=orbicon/news&amp;edit='.$a_c['permalink'];
			$active_links[] = '<a href="'.$url.'">'.$a_c['permalink'].'</a>';
			$a_c = $dbc->_db->fetch_array($r_c);
		}

		if(!empty($active_links)) {
			echo '<div style="margin-top:1em;padding:0.5em;font-size:90%;border:1px solid red;background:#e8e8e8;"><p><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/error-log.png" /> <strong>'._L('image_used').'</strong></p><ol>';

			foreach($active_links as $key => $value) {
				echo "<li>$value</li>";
			}

			echo '</ol></div><br />';
		}

		echo '<div class="alen_hack">
		<img src="'.ORBX_SITE_URL.'/site/venus/'.$image['permalink'].'?'.md5(uniqid(rand(), true)).'" /></div>';
	}
?>

<table width="100%"><tr><td>

					<tr><td><?php echo _L('title'); ?></td><td><?php echo $image['title']; ?></td></tr>
					<tr><td><?php echo _L('dimensions'); ?></td><td><?php echo $image_info[0].' &times; '.$image_info[1]; ?></td></tr>
					<tr><td><?php echo _L('size'); ?></td><td><?php echo  $size; ?></td></tr>
					<tr><td><?php echo _L('format'); ?></td><td><?php echo $format; ?> [<?php echo get_mime_by_ext(get_extension($image['permalink'])); ?>]</td></tr>
					<tr><td><?php echo _L('author'); ?></td><td><a href="./?<?php echo $orbicon_x->ptr; ?>=orbicon/editors&amp;action=edit&amp;id=<?php echo $image['uploader']; ?>">ID: <?php echo $image['uploader']; ?></a> ( <?php echo date($_SESSION['site_settings']['date_format'], $image['live_time']); ?> )</td></tr>
					<tr><td><label for="img_tag">&lt;img&gt; <?php echo _L('tag_link'); ?></label></td><td><input id="img_tag" onclick="this.select();" size="80" type="text" value="&lt;img src=&quot;<?php echo ORBX_SITE_URL; ?>/site/venus/<?php echo htmlspecialchars($image['permalink']); ?>&quot; alt=&quot;<?php echo htmlspecialchars($image['permalink']); ?>&quot; title=&quot;<?php echo htmlspecialchars($image['title']); ?>&quot; /&gt;" /></td></tr>
					<tr><td><label for="url_link"><?php echo _L('url'); ?></label></td><td><input id="url_link" onclick="javascript: this.select();" size="80" type="text" value="<?php echo ORBX_SITE_URL; ?>/site/venus/<?php echo htmlspecialchars($image['permalink']); ?>" /></td></tr>
					<tr><td><?php echo _L('last_mod'); ?></td><td><?php echo date($_SESSION['site_settings']['date_format'], $image['last_modified']); ?></td></tr>

</table></td>
<td></td>
</tr></table>