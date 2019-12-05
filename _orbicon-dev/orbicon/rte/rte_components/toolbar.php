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

	$a_ = build_zones(array());

?>
<?php
/*
<div id="rte_container">
<!--Create the Color Dialog Helper Object-->
<!--[if gte IE 6]>
<object id="dialog_helper" name="dialog_helper" classid="clsid:3050f819-98b5-11cf-bb82-00aa00bdce0b" style="width: 0px; height: 0px;"></object>
<![endif]-->
<div id="rich_text_editor_toggle">
	<img id="rte_btn_html" src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/mode.gif" alt="HTML source" title="HTML source" onclick="javascript: RichTextToggleView();" />
	<img id="rte_btn_new" src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/new.gif" alt="New Blank Document [CTRL + N]" title="New Blank Document [CTRL + N]" onclick="javascript: RichTextNew();" />
	<img id="rte_btn_open" src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/open.gif" alt="Open... [CTRL + O]" title="Open... [CTRL + O]" onclick="javascript: RichTextOpen();" />
	<img id="rte_btn_save" src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/disk.png" alt="Save [CTRL + S]" title="Save [CTRL + S]" onclick="javascript: RichTextSave();" />
	<img id="rte_btn_print" src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/print.gif" alt="Print [CTRL + P]" title="Print [CTRL + P]" onclick="javascript: RichTextPrint();" />
	<img id="rte_btn_full" src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/monitor.png" alt="Toogle Full Screen [CTRL + F]" title="Toogle Full Screen [CTRL + F]" onclick="javascript: RichTextFullScreen();" />
</div>
<div id="rich_text_block_format_collection" name="rich_text_block_format_collection"></div>
<div id="rich_text_system_font_collection" name="rich_text_system_font_collection">
	<span class="CloseDropDown">
		<input type="button" onclick="javascript: RichTextHideSystemFontsCollection();" value="X" title="Close" />
	</span>
</div>
<div id="rich_text_editor_plaintext_controls">
	<img id="btn_cut" src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/cut.gif" alt="Cut [CTRL + X]" title="Cut [CTRL + X]" onclick="javascript: RichTextCut();" />
	<img id="btn_copy" src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/copy.gif" alt="Copy [CTRL + C]"  title="Copy [CTRL + C]" onclick="javascript: RichTextCopy();" />
	<img id="btn_paste" src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/paste.gif" alt="Paste [CTRL + V]" title="Paste [CTRL + V]" onclick="javascript: RichTextPaste();" />

	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/undo.gif" alt="Undo [CTRL + Z]" title="Undo [CTRL + Z]" onclick="javascript: RichTextUndo();" />
	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/redo.gif" alt="Redo [CTRL + Y]" title="Redo [CTRL + Y]" onclick="javascript: RichTextRedo();" />

	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/link.gif" alt="Create a Hyperlink" title="Create a Hyperlink" onclick="javascript: RichTextHyperlink();" />
	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/unlink.gif" alt="Remove a Hyperlink" title="Remove a Hyperlink" onclick="javascript: RichTextUnlink();" />

	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/bold.gif" alt="Bold [CTRL + B]" title="Bold [CTRL + B]" onclick="javascript: RichTextBold();" />
	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/italic.gif" alt="Italic [CTRL + I]" title="Italic [CTRL + I]" onclick="javascript: RichTextItalic();" />
	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/underline.gif" alt="Underline [CTRL + U]" title="Underline [CTRL + U]" onclick="javascript: RichTextUnderline();" />
	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/strikethrough.gif" alt="Strikethrough" title="Strikethrough" onclick="javascript: RichTextStrikeThrough();" />

	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/left.gif" alt="Align Left" title="Align Left" onclick="javascript: RichTextAlignLeft();" />
	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/center.gif" alt="Center" title="Center" onclick="javascript: RichTextCenter();" />
	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/right.gif" alt="Align Right" title="Align Right" onclick="javascript: RichTextAlignRight();" />
	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/justify.gif" alt="Justify" title="Justify" onclick="javascript: RichTextJustify();"  />

	<img id="btn_color_picker" src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/color_picker.gif" alt="Color Picker" title="Color Picker" onclick="RichTextDisplayColorPicker(this);" />

	<img id="CharMapButton" src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/char_map.gif" alt="Character Map" title="Character Map" onclick="javascript:RichTextDisplayCharMap();" />

	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/sub.gif" alt="Subscript" title="Subscript" onclick="javascript: RichTextSubscript();" />
	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/super.gif" alt="Superscript" title="Superscript" onclick="javascript: RichTextSuperscript();" />

	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/lowercase.gif" alt="Lowercase" title="Lowercase" onclick="javascript: RichTextAllLowercase();" />
	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/uppercase.gif" alt="Uppercase" title="Uppercase" onclick="javascript: RichTextAllUppercase();" />

	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/date.gif" alt="Date" title="Date" onclick="javascript: RichTextDate();" />
	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/time.gif" alt="Time" title="Time" onclick="javascript: RichTextTime();" />

	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/ordlist.gif" alt="Formatting Numbers" title="Formatting Numbers" onclick="RichTextOrdList();" />
	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/bullist.gif" alt="Formatting Bullets" title="Formatting Bullets" onclick="RichTextBulList();" />
	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/indent.gif" alt="Indent" title="Indent" onclick="javascript: RichTextIndent();" />
	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/outdent.gif" alt="Outdent" title="Outdent" onclick="javascript: RichTextOutdent();" />
	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/hilite.png" alt="Hilite Selection" title="Hilite Selection" onclick="javascript: RichTextHiliteSelection();" />

	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/horizontalrule.png" alt="Insert Horizontal Line" title="Insert Horizontal Line" onclick="javascript: RichTextHorizontalLine();" />
	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/clear.gif" alt="Clear Formatting" title="Clear Formatting" onclick="javascript: RichTextClearFormat();" />
	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/field.gif" alt="Insert Field" title="Insert Field" onclick="javascript: RichTextInsertFieldset();" />
	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/orbicon.internal.png" alt="Internal Link" title="Internal Link" onclick="javascript: orbicon_internal_link();" />
<!--[if IE]>
	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/image.gif" alt="Edit image properties" title="Edit image properties" onclick="javascript: RichTextInsertImage();" />
<![endif]-->
<div id="rich_text_editor_table" style="display:inline">
	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/table.gif" alt="Insert Table" title="Insert Table" onclick="javascript: RichTextInsertTable();" />
	<label for="nRow">Row:</label>
	<input name="nRow" type="text" id="nRow" size="1" maxlength="1" value="1" title="Row" />
	<img class="spin_button" src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/spin_up.gif" alt="Increase Value" title="Increase Value" onclick="javascript: RichTextNumericModifier(true, 'nRow');" />
	<img class="spin_button" src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/spin_down.gif" alt="Decrease Value" title="Decrease Value" onclick="javascript: RichTextNumericModifier(false, 'nRow');" />
	<label for="nColumn">Column:</label>
	<input name="nColumn" type="text" id="nColumn" size="1" maxlength="1" value="1" title="Column" />
	<img class="spin_button" src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/spin_up.gif" alt="Increase Value" title="Increase Value" onclick="javascript: RichTextNumericModifier(true, 'nColumn');" />
	<img class="spin_button" src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/spin_down.gif" alt="Decrease Value" title="Decrease Value" onclick="javascript: RichTextNumericModifier(false, 'nColumn');" />

</div>
</div>


<div style="width: 650px; overflow:auto; height: 38px;" id="rich_text_link_page">
	<select onchange="javascript: RichTextInsertLinkDirect(this);">
		<option value="" selected="selected">&mdash; Link to page &mdash;</option>
		<?php echo $a_[0]; ?>
	</select>
</div>

	<div id="rich_text_editor_font">
	<select name="BlockFormats" id="BlockFormats" onchange="javascript: RichTextBlockFormat();" title="Block Format" onclick="javascript: RichTextDisplayBlockFormats(this);">
		<option value="" selected="selected">&mdash; Block Format &mdash;</option>
	</select>
	<!-- <select name="FontType" id="FontType" onchange="javascript: RichTextFont();" title="Font" onclick="javascript: RichTextDisplaySystemFonts(this);">
		<option value="" selected="selected">-- Font --</option>
	</select>
	<select name="FontSize" id="FontSize" onchange="javascript: RichTextFontSize();" title="Font Size">
		<option value="" selected="selected">-- Size --</option>
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
		<option value="6">6</option>
		<option value="7">7</option>
	</select> -->
	<label for="rte_content_length">Letters :</label> <input type="text" id="rte_content_length" name="rte_content_length" size="5" readonly="readonly" />

<!-- Search for : <input id="rte_search_for" type="text" /> Replace with: <input id="rte_replace_with" type="text" /> <input type="button" value="Search" style="width: 60px;" id="search_replace_btn" onclick="javascript: RichTextReplace();" /> -->
</div>

<?php require_once DOC_ROOT.'/orbicon/rte/rte_components/char_map.php'; ?>

<iframe id="RTE" name="RTE" class="editing_windowframe" title="Enter content"></iframe>
</div>
<input type="hidden" id="rte_data" name="rte_data" />
<div id="rte_color_picker" style=" border: 0; background: transparent; visibility:hidden;"><?php require_once DOC_ROOT.'/orbicon/rte/rte_components/color_picker.php'; ?></div>
*/

?>

<table style="border:1px solid #ccc; border-bottom: none">

<tr style="vertical-align:top;">
	<td>
		<a onclick="javascript: NewDocument();" href="javascript:;">Dodaj novu karticu: <img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/new.gif" alt="Novi dokument" title="Novi dokument" /></a>
	</td>
	<td>
		<a onclick="javascript: CleanUpHTML();" href="javascript:;">Pročisti HTML kod: <img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/clear.gif" alt="Očisti formatiranje" title="Očisti formatiranje" /></a>
	</td>
 	<td>
		<a href="./?<?php echo $orbicon_x->ptr; ?>=orbicon/magister&amp;read=<?php echo $_GET['read']; ?>&amp;tpl=template_glavni_tekst.html">Predložak za glavni tekst: <img style="cursor:pointer" src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/field.gif" /></a>
	</td>
	<td>
		<a href="./?<?php echo $orbicon_x->ptr; ?>=orbicon/magister&amp;read=<?php echo $_GET['read']; ?>&amp;tpl=template_desna_kolona.html">Predložak za desnu kolonu: <img style="cursor:pointer" src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/field.gif" /></a>
	</td>
</tr>

<tr>

<td colspan="4">
	<div style="width: 1050px; overflow:auto; height: 64px;" id="rich_text_link_page">
		<select style="font-size:14px;padding:10px;min-width:1050px" onchange="javascript: InsertLinkDirect(this);">
			<option value="" selected="selected">&mdash; Link na stranicu &mdash;</option>
			<?php echo $a_[0]; ?>
		</select>
	</div>
</td>

</tr>

</table>

<!-- TinyMCE -->
<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/javascript/orbicon.mce.js"></script>
<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/3rdParty/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">

	tinyMCE.init({
		// General options
		mode : "textareas",
		language : "hr",
		theme : "advanced",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advcode",

		// Theme options
		theme_advanced_buttons1 : "save,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,advcode",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		// Example content CSS (should be your site CSS)
		content_css : "<?php echo ORBX_SITE_URL; ?>/site/gfx/css/main.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		save_enablewhendirty : true,
		save_onsavecallback : "RichTextSave",

		auto_focus : 'elm1',

		button_tile_map : true,
		extended_valid_elements : "iframe[src|width|height|name|align|style|class|id|frameborder|hspace|marginheight|marginwidth|scrolling|vspace]",

		entity_encoding : 'raw',
		remove_linebreaks : false,
		apply_source_formatting : true,


		valid_elements : "@[id|class|style|title|dir<ltr?rtl|lang|xml::lang|onclick|ondblclick|"
+ "onmousedown|onmouseup|onmouseover|onmousemove|onmouseout|onkeypress|"
+ "onkeydown|onkeyup],a[rel|rev|charset|hreflang|tabindex|accesskey|type|"
+ "name|href|target|title|class|onfocus|onblur],strong/b,em/i,strike,u,"
+ "#p[align],-ol[type|compact],-ul[type|compact],-li,br,img[longdesc|usemap|"
+ "src|border|alt=|title|hspace|vspace|width|height|align],-sub,-sup,"
+ "-blockquote,-table[border=0|cellspacing|cellpadding|width|frame|rules|"
+ "height|align|summary|bgcolor|background|bordercolor],-tr[rowspan|width|"
+ "height|align|valign|bgcolor|background|bordercolor],tbody,thead,tfoot,"
+ "#td[colspan|rowspan|width|height|align|valign|bgcolor|background|bordercolor"
+ "|scope],#th[colspan|rowspan|width|height|align|valign|scope],caption,div,"
+ "-span,-code,-pre,address,-h1,-h2,-h3,-h4,-h5,-h6,hr[size|noshade],-font[face"
+ "|size|color],dd,dl,dt,cite,abbr,acronym,del[datetime|cite],ins[datetime|cite],"
+ "object[classid|width|height|codebase|*],param[name|value|_value],embed[type|width"
+ "|height|src|*],script[src|type],map[name],area[shape|coords|href|alt|target],bdo,"
+ "button,col[align|char|charoff|span|valign|width],colgroup[align|char|charoff|span|"
+ "valign|width],dfn,fieldset,form[action|accept|accept-charset|enctype|method],"
+ "input[accept|alt|checked|disabled|maxlength|name|readonly|size|src|type|value],"
+ "kbd,label[for],legend,noscript,optgroup[label|disabled],option[disabled|label|selected|value],"
+ "q[cite],samp,select[disabled|multiple|name|size],small,"
+ "textarea[cols|rows|disabled|name|readonly],tt,var,big"



		//,
		//cleanup_on_startup : false,
		//verify_html : false


	});
</script>
<!-- /TinyMCE -->

<!-- Gets replaced with TinyMCE, remember HTML in a textarea should be encoded -->
<textarea id="elm1" name="elm1" rows="80" cols="80" style="width: 100%">

<?php

if(isset($_GET['edit']) && ($_GET[$orbicon_x->ptr] == 'orbicon/mod/infocentar')) {
	echo $answer['content'];
}
else if(($_GET['sp'] == 'promo') && isset($_GET['promoid'])) {
	echo $promo['textual'];
}
else if(isset($_GET['tpl'])) {
	echo file_get_contents(DOC_ROOT. '/site/gfx/' . $_GET['tpl']);
}

?>

</textarea>