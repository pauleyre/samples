<?php
	(object) $oToolbar = new RichTextEditor;
	$oToolbar -> RichTextStartNewToolbar();
	/*ini_set('display_errors', 1);
	error_reporting(E_ALL);*/
	if(isset($_GET['id']))
	{
		$rte_ = new ClassLib;
		$rte_ -> DB_Spoji('is');

		(string) $query = sprintf('SELECT opis FROM radni_nalog WHERE id = %s AND tip = %s', $rte_ -> QuoteSmart($_GET['id']), $rte_ -> QuoteSmart($_GET['ftype']));

		$rResult = $rte_ -> DB_Upit($query);
		(array) $aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);
		$content = $aResult['opis'];
		$rte_ -> DB_Zatvori();
	}

	$content = stripslashes($content);
	
?>
<div id="rte_container">
<!--Create the Color Dialog Helper Object-->
<!--[if gte IE 6]>
<object id="dialog_helper" name="dialog_helper" classid="clsid:3050f819-98b5-11cf-bb82-00aa00bdce0b" style="width: 0px; height: 0px;"></object>
<![endif]-->
<div id="rich_text_editor_toggle">
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/mode.gif" alt="HTML source" title="HTML source" onclick="javascript: RichTextToggleView();" />
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/new.gif" alt="New Blank Document" title="New Blank Document" onclick="javascript: RichTextNew();" />
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/open.gif" alt="Open..." title="Open..." onclick="javascript: RichTextOpen();" />
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/save.gif" alt="Save" title="Save" onclick="javascript: RichTextSave('<?= $_SESSION["RTE_UserDocument"]; ?>');" />
	<!-- <img src="<?=RTE_ROOT;?>/rte/rte_buttons/save_as.gif" alt="Save As..." title="Save As..." onclick="javascript: RichTextSaveAs(<?= $_GET["filename"]; ?>, <?= $_GET["type"]; ?>);" /> -->
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/print.gif" alt="Print" title="Print" onclick="javascript: RichTextPrint();" />
</div>
<div id="rich_text_editor_color_palette" name="rich_text_editor_color_palette">
	<?php //include(RTEC_COLOR_PALETTE); ?>
</div>
<div id="rich_text_editor_character_map" name="rich_text_editor_character_map">
	<?php include(RTEC_CHARACTER_MAP); ?>
</div>
<div id="autosave_notice">Autosave successful.</div>
<div id="rich_text_block_format_collection" name="rich_text_block_format_collection"></div>
<div id="rich_text_system_font_collection" name="rich_text_system_font_collection">
	<span class="CloseDropDown">
		<input type="button" onclick="javascript: RichTextHideSystemFontsCollection();" value="X" title="Close" />
	</span>
</div>
<div id="rich_text_editor_plaintext_controls">
	<img id="btn_cut" src="<?=RTE_ROOT;?>/rte/rte_buttons/cut.gif" alt="Cut" title="Cut" onclick="javascript: RichTextCut();" />
	<img id="btn_copy" src="<?=RTE_ROOT;?>/rte/rte_buttons/copy.gif" alt="Copy"  title="Copy" onclick="javascript: RichTextCopy();" />
	<img id="btn_paste" src="<?=RTE_ROOT;?>/rte/rte_buttons/paste.gif" alt="Paste" title="Paste" onclick="javascript: RichTextPaste();" />

	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/undo.gif" alt="Undo" title="Undo" onclick="javascript: RichTextUndo();" />
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/redo.gif" alt="Redo" title="Redo" onclick="javascript: RichTextRedo();" />

	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/link.gif" alt="Create a Hyperlink" title="Create a Hyperlink" onclick="javascript: RichTextHyperlink();" /> 
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/unlink.gif" alt="Remove a Hyperlink" title="Remove a Hyperlink" onclick="javascript: RichTextUnlink();" /> 

	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/bold.gif" alt="Bold" title="Bold" onclick="javascript: RichTextBold();" /> 
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/italic.gif" alt="Italic" title="Italic" onclick="javascript: RichTextItalic();" /> 
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/underline.gif" alt="Underline" title="Underline" onclick="javascript: RichTextUnderline();" /> 
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/strikethrough.gif" alt="Strikethrough" title="Strikethrough" onclick="javascript: RichTextStrikeThrough();" /> 

	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/left.gif" alt="Align Left" title="Align Left" onclick="javascript: RichTextAlignLeft();" /> 
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/center.gif" alt="Center" title="Center" onclick="javascript: RichTextCenter();" /> 
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/right.gif" alt="Align Right" title="Align Right" onclick="javascript: RichTextAlignRight();" /> 
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/justify.gif" alt="Justify" title="Justify" onclick="javascript: RichTextJustify();"  />

	<?php /*<img id="FontColorButton" src="<?=RTE_ROOT;?>/rte/rte_buttons/forecol.gif" alt="Font Color" title="Font Color" onclick="javascript: RichTextDisplayColorPalette(event);" /> 

	<img id="BackgroundColorButton" src="<?=RTE_ROOT;?>/rte/rte_buttons/bgcol.gif" alt="Background Color" title="Background Color" onclick="javascript: RichTextDisplayBackgroundColorPalette(event);" /> 
	*/ ?>
	
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/color_picker.gif" alt="Color Picker" title="Color Picker" onclick="RichTextDisplayColorPicker();" />

	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/flash.gif" alt="Flash" title="Flash" onclick="javascript: RichTextInsertFlash();" />
	
	<img id="CharMapButton" src="<?=RTE_ROOT;?>/rte/rte_buttons/char_map.gif" alt="Character Map" title="Character Map" onclick="javascript: RichTextDisplayCharacterMap(event);" />

	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/sub.gif" alt="Subscript" title="Subscript" onclick="javascript: RichTextSubscript();" />
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/super.gif" alt="Superscript" title="Superscript" onclick="javascript: RichTextSuperscript();" />

	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/lowercase.gif" alt="Lowercase" title="Lowercase" onclick="javascript: RichTextAllLowercase();" />
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/uppercase.gif" alt="Uppercase" title="Uppercase" onclick="javascript: RichTextAllUppercase();" />

	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/date.gif" alt="Date" title="Date" onclick="javascript: RichTextDate();" />
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/time.gif" alt="Time" title="Time" onclick="javascript: RichTextTime();" />

	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/ordlist.gif" alt="Formatting Numbers" title="Formatting Numbers" onclick="RichTextOrdList();" />
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/bullist.gif" alt="Formatting Bullets" title="Formatting Bullets" onclick="RichTextBulList();" /> 
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/indent.gif" alt="Indent" title="Indent" onclick="javascript: RichTextIndent();" />
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/outdent.gif" alt="Outdent" title="Outdent" onclick="javascript: RichTextOutdent();" />

	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/image.gif" alt="Insert Picture" title="Insert Picture" onclick="javascript: RichTextInsertImage();" /> 
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/rule.gif" alt="Insert Horizontal Line" title="Insert Horizontal Line" onclick="javascript: RichTextHorizontalLine();" />
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/clear.gif" alt="Clear Formatting" title="Clear Formatting" onclick="javascript: RichTextClearFormat();" />
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/field.gif" alt="Insert Field" title="Insert Field" onclick="javascript: RichTextInsertFieldset();" />
</div>
<div id="rich_text_editor_table">
	<img src="<?=RTE_ROOT;?>/rte/rte_buttons/table.gif" alt="Insert Table" title="Insert Table" onclick="javascript: RichTextInsertTable();" />
	Row: 
	<input name="nRow" type="text" id="nRow" size="1" maxlength="1" value="1" title="Row" />
	<img class="spin_button" src="<?=RTE_ROOT;?>/rte/rte_buttons/spin_up.gif" alt="Increase Value" title="Increase Value" onclick="javascript: RichTextNumericModifier(true, 'nRow');" />
	<img class="spin_button" src="<?=RTE_ROOT;?>/rte/rte_buttons/spin_down.gif" alt="Decrease Value" title="Decrease Value" onclick="javascript: RichTextNumericModifier(false, 'nRow');" />
	Column: 
	<input name="nColumn" type="text" id="nColumn" size="1" maxlength="1" value="1" title="Column" />
	<img class="spin_button" src="<?=RTE_ROOT;?>/rte/rte_buttons/spin_up.gif" alt="Increase Value" title="Increase Value" onclick="javascript: RichTextNumericModifier(true, 'nColumn');" />
	<img class="spin_button" src="<?=RTE_ROOT;?>/rte/rte_buttons/spin_down.gif" alt="Decrease Value" title="Decrease Value" onclick="javascript: RichTextNumericModifier(false, 'nColumn');" />
</div>
<div id="rich_text_editor_font">
	<select name="BlockFormats" id="BlockFormats" onchange="javascript: RichTextBlockFormat();" title="Block Format" onclick="javascript: RichTextDisplayBlockFormats(this);">
		<option value="" selected="selected">-- Block Format --</option>
	</select>
	<select name="FontType" id="FontType" onchange="javascript: RichTextFont();" title="Font" onclick="javascript: RichTextDisplaySystemFonts(this);">
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
	</select>
	Search for : <input id="rte_search_for" type="text" /> Replace with: <input id="rte_replace_with" type="text" /> <input type="button" value="Search" style="width: 60px;" id="search_replace_btn" onclick="javascript: RichTextReplace();" />
</div>
<iframe id="RTE" name="RTE" style="width: <?= $oToolbar -> nToolbarWidth; ?>%; height: <?= $oToolbar -> nToolbarHeight; ?>px;" class="editing_windowframe" title="Enter content"><?= $content; ?></iframe>
</div>
<input type="hidden" id="rte_data" name="rte_data" />