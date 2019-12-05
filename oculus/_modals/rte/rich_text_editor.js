	// * Rich-Text Editor 2.1b
	// * All rights reserved. Copyright Laniste.net 2005-2006
	// * www.laniste.net
	// </?\w+((\s+\w+(\s*=\s*(?:".*?"|'.*?'|[^'">\s]+))?)+\s*|\s*)/?>

	var nViewMode = 1; 			// Rich-Text
	var sToolbarID = "";
	var oToolbar = null;
	var __rte_toolbar_win = null;
	var aFonts = new Array();
	// * use dialog helper for blockformat and fonts. heavy resource usage, coca cola, sometimes war
	var __rte_msie_use_dialog_helper_object = false;
	// * generate CSS or markup
	var __style_with_css = true;

	// * Load RTE
	if(window.addEventListener) {
		window.addEventListener("load", RichTextOnLoad, true);
	}
	else if(window.attachEvent) {
		window.attachEvent("onload", RichTextOnLoad);
	}

	// * Load editor
	function RichTextOnLoad()
	{
		sToolbarID = "RTE";
		var __rte_toolbar = new getObj(sToolbarID);

		if(__rte_toolbar.obj.contentWindow.document)
		{
			oToolbar = __rte_toolbar.obj.contentWindow.document;
			__rte_toolbar_win = __rte_toolbar.obj.contentWindow;

			// * Start up
			if(oToolbar.designMode) {
				oToolbar.designMode = "On";
			}
			else
			{
				if(window.alert) {
					window.alert("Your browser doesn't support Rich-Text editor.\nPlease upgrade.");
				}
			}

			var __content_check = '';

			// we don't have a src
			if(__rte_toolbar.obj.src == '')
			{
				if(__rte_toolbar.obj.textContent) {
					__content_check = __rte_toolbar.obj.textContent;
					oToolbar.body.innerHTML = __content_check.toString();
				}
				else
				{
					setTimeout(function () { 
										 __content_check = __rte_toolbar.obj.innerHTML;
										oToolbar.body.innerHTML = __content_check;
										 }, 1);
				}
			}

			var __rte_form = new getObj('main_form');
			var __rte_data_input = new getObj('rte_data');

			// Create rte_data hidden container
			if(__rte_data_input.obj == null || typeof __rte_data_input.obj == 'undefined')
			{
				throw new Error('RTE: rte_data input not found... created.');
				var __rte_data = window.document.createElement("INPUT");
				__rte_data.id = 'rte_data';
				__rte_data.name = 'rte_data';
				__rte_data.type = 'hidden';

				// Append it to the end of the form
				__rte_form.obj.appendChild(__rte_data);
			}

			if(__rte_form.obj.addEventListener) {
				__rte_form.obj.addEventListener("submit", function() { var __data = new getObj('rte_data'); __data.obj.value = RichTextCaptureData('return'); }, true);
			}
			else if(__rte_form.obj.attachEvent) {
				__rte_form.obj.attachEvent("onsubmit", function() { var __data = new getObj('rte_data'); __data.obj.value = RichTextCaptureData('return'); });
			}

			RichTextGetSystemFonts("FontType");
			RichTextGetBlockFormats("BlockFormats");

			// * Initiate events

			var _rte_replace_with = new getObj('rte_replace_with');

			if(window.document.addEventListener) {
				window.document.addEventListener("mousedown", RichTextHideColorPalette, true);
				window.document.addEventListener("keypress", RichTextHideColorPalette, true);
				oToolbar.addEventListener("mousedown", RichTextHideCharacterMap, true);
				oToolbar.addEventListener("keypress", RichTextHideCharacterMap, true);
				oToolbar.addEventListener("mousedown", RichTextHideColorPalette, true);
				oToolbar.addEventListener("keypress", RichTextHideColorPalette, true);
				oToolbar.addEventListener("mousedown", RichTextHideBlockFormatCollection, true);
				oToolbar.addEventListener("keypress", RichTextHideBlockFormatCollection, true);
				oToolbar.addEventListener("mouseup", RichTextCaptureDesktopItems, true);
				_rte_replace_with.obj.addEventListener("keyup", __rte_replace_key_up, true);	
			}
			else if(oToolbar.attachEvent) {
				window.document.attachEvent("onmousedown", RichTextHideColorPalette);
				window.document.attachEvent("onkeypress", RichTextHideColorPalette);
				oToolbar.attachEvent("onmousedown", RichTextHideColorPalette);
				oToolbar.attachEvent("onmousedown", RichTextHideCharacterMap);
				oToolbar.attachEvent("onmousedown", RichTextHideBlockFormatCollection);
				oToolbar.attachEvent("onkeypress", RichTextHideColorPalette);
				oToolbar.attachEvent("onkeypress", RichTextHideCharacterMap);
				oToolbar.attachEvent("onkeypress", RichTextHideBlockFormatCollection);
				oToolbar.addEventListener("onmouseup", RichTextCaptureDesktopItems);				
				_rte_replace_with.obj.attachEvent("onkeyup", __rte_replace_key_up);	
				
			}
 
			var aImages;
			var __rte_button_div_containers;
			var __rte_button_div_containers_imgs;
			var i;
			var n;

			__rte_button_div_containers = new Array('rich_text_editor_toggle',
													'rich_text_editor_plaintext_controls',
													'rich_text_editor_table',
													'rich_text_editor_color_picker_buttons');

			for(n = 0; n < __rte_button_div_containers.length; n++)
			{
				var __div_container = new getObj(__rte_button_div_containers[n]);
				if(__div_container.obj.getElementsByTagName) {
					__rte_button_div_containers_imgs = __div_container.obj.getElementsByTagName('IMG');
				}

				if(typeof __rte_button_div_containers_imgs != 'undefined' && __rte_button_div_containers_imgs != null)
				{
					for(i = 0; i < __rte_button_div_containers_imgs.length; i++)
					{
						__rte_button_div_containers_imgs[i].onmouseover = RichTextSelOn;
						__rte_button_div_containers_imgs[i].onmouseout = RichTextSelOff;
						__rte_button_div_containers_imgs[i].onmousedown = RichTextSelDown;
						__rte_button_div_containers_imgs[i].onmouseup = RichTextSelUp;
					}
				}
			}

			// * Spin buttons
			_RichTextSpinButton("nColumn");
			RichTextStyleWithCSS();
			RichTextFocus();
		}
		else
		{
			if(window.alert) {
				window.alert("Your browser doesn't support Rich-Text editor.\nPlease upgrade.");
			}
		}
	}

	function RichTextFocus()
	{
		var __rte_window = new getObj(sToolbarID);
		if(__rte_window.obj.contentWindow.focus) {
			__rte_window.obj.contentWindow.focus();
		}
	}

	function RichTextSelOn()
	{
		this.style.cursor = 'default';
		this.style.borderBottom = '1px solid ButtonShadow';
		this.style.borderRight = '1px solid ButtonShadow';
		this.style.borderLeft = '1px solid ButtonHighlight';
		this.style.borderTop = '1px solid ButtonHighlight';
		window.status = this.getAttribute('title');
	}

	function RichTextSelOff()
	{
		this.style.border = '1px solid #FDF9ED';
		window.status = '';
	}

	function RichTextSelDown()
	{
		this.style.borderBottom = '1px solid ButtonHighlight';
		this.style.borderRight = '1px solid ButtonHighlight';
		this.style.borderLeft = '1px solid ButtonShadow';
		this.style.borderTop = '1px solid ButtonShadow';
	}

	function RichTextSelUp() {
		this.style.border = '1px solid #FDF9ED';
	}

	function RichTextBold() {
		oToolbar.execCommand("Bold", false, null);
	}

	function RichTextItalic() {
		oToolbar.execCommand("Italic", false, null);
	}

	function RichTextUnderline() {
		oToolbar.execCommand("Underline", false, null);
	}

	function RichTextStrikeThrough() {
		oToolbar.execCommand("Strikethrough", false, null);
	}

	// * Align text left
	function RichTextAlignLeft()
	{
		RichTextFocus();
		oToolbar.execCommand("JustifyLeft", false, null);
	}

	// * Center text 
	function RichTextCenter()
	{
		RichTextFocus();
		oToolbar.execCommand("JustifyCenter", false, null);
	}

	// * Align text right
	function RichTextAlignRight()
	{
		RichTextFocus();
		oToolbar.execCommand("JustifyRight", false, null);
	}

	// * Justify selection
	function RichTextJustify()
	{
		RichTextFocus();
		oToolbar.execCommand("JustifyFull", false, null);
	}

	// * Subscript selection
	function RichTextSubscript()
	{
		RichTextFocus();
		oToolbar.execCommand("Subscript", false, null);
	}

	// * Superscript selection
	function RichTextSuperscript()
	{
		RichTextFocus();
		oToolbar.execCommand("Superscript", false, null);
	}

	// * Do indent
	function RichTextIndent()
	{
		RichTextFocus();
		oToolbar.execCommand("Indent", false, null);
	}

	// * Do outdent
	function RichTextOutdent()
	{
		RichTextFocus();
		oToolbar.execCommand("Outdent", false, null);
	}

	// * Insert numbered list
	function RichTextOrdList()
	{
		RichTextFocus();
		oToolbar.execCommand("InsertOrderedList", false, null);
	}

	// * Insert bulleted list
	function RichTextBulList()
	{
		RichTextFocus();
		oToolbar.execCommand("InsertUnorderedList", false, null);
	}

	// * Insert hyperlink
	function RichTextHyperlink()
	{
		var _bHyperlink = false;
		RichTextFocus();

		try
		{
			oToolbar.execCommand("CreateLink", true);
			_bHyperlink = true;
		}
		catch(e) {}
		finally
		{
			if(window.prompt && !_bHyperlink)
			{
				var __rte_window = new getObj(sToolbarID);
				var sSelected = __rte_window.obj.contentWindow.getSelection();
				var sLinkSource = window.prompt("Enter URL...", "http://" + sSelected);

				if(sLinkSource != null) {
					oToolbar.execCommand("CreateLink", false, sLinkSource);
				}
			}
		}
	}

	// * Unlink
	function RichTextUnlink()
	{
		RichTextFocus();
		oToolbar.execCommand("Unlink", false, null);
	}

	// * Insert image
	function RichTextInsertImage()
	{
		var _bInsertImage = false;
		RichTextFocus();

		try
		{
			oToolbar.execCommand("InsertImage", true);
			_bInsertImage = true;
		}
		catch(e) {}
		finally
		{
			if(window.prompt && !_bInsertImage)
			{
				var sImageSource = window.prompt("Enter URL path to image...", "http://");

				if(sImageSource != null && sImageSource != "") {
					oToolbar.execCommand("InsertImage", false, sImageSource);
				}
			}
		}
	}

	// * Insert horizontal line
	function RichTextHorizontalLine()
	{
		RichTextFocus();
		oToolbar.execCommand("InsertHorizontalRule", false, null);
	}

	// * Paste from clipboard
	function RichTextPaste()
	{
		try
		{
			RichTextFocus();
			oToolbar.execCommand("Paste", false, null);
		}
		catch(e) {
			window.alert(e);
		}
	}

	// * Change font
	function RichTextFont()
	{
		var oFontCollection = new getObj('FontType');
		var sName = oFontCollection.obj.options[oFontCollection.obj.selectedIndex].getAttribute("value");

		if(typeof sName != 'undefined' && sName != null && sName != '') {
			oToolbar.execCommand('FontName', false, sName);
		}
	}

	// * Change font's size
	function RichTextFontSize()
	{
		var oFontSizeCollection = new getObj("FontSize");
		var nSize = oFontSizeCollection.obj.options[oFontSizeCollection.obj.selectedIndex].getAttribute("value");

		if(typeof nSize != 'undefined' && nSize != null && nSize != '') {
			oToolbar.execCommand("FontSize", false, nSize);
		}
	}

	// * Change block format
	function RichTextBlockFormat()
	{
		var oBlockFormatCollection = new getObj("BlockFormats");
		var sBlockFormat = oBlockFormatCollection.obj.options[oBlockFormatCollection.obj.selectedIndex].getAttribute("value");

		if(typeof sBlockFormat != 'undefined' && sBlockFormat != null && sBlockFormat != "") {
			oToolbar.execCommand("FormatBlock", false, sBlockFormat);
		}
	}

	// * Toggle between HTML / Design view
	function RichTextToggleView()
	{
		var __rte_plaintext_div = new getObj('rich_text_editor_plaintext_controls');
		var __rte_color_pal_div = new getObj('rich_text_editor_color_palette');
		var __rte_table_div = new getObj('rich_text_editor_table');
		var __rte_font_div = new getObj('rich_text_editor_font');

		if(window.document.getElementsByTagName) {
			var __submit_inputs = window.document.getElementsByTagName('INPUT');
		}

		// * HTML source view
		if(nViewMode == 1)
		{
			var sHTMLSource = oToolbar.body.innerHTML;

			if(oToolbar.body.innerText != null) {
				oToolbar.body.innerText = sHTMLSource;
			}
			else
			{
				oToolbar.body.innerHTML = '';
				oToolbar.body.appendChild(oToolbar.createTextNode(sHTMLSource));
			}

			// Hide all controls			
			__rte_plaintext_div.style.display = "none";
			__rte_color_pal_div.style.display = "none";
			__rte_table_div.style.display = "none";
			__rte_font_div.style.display = "none";
			
			// * disable all submit inputs
			if(typeof __submit_inputs != 'undefined' && __submit_inputs != null)
			{
				for(i = 0; i < __submit_inputs.length; i++)
				{
					if(__submit_inputs[i].type == 'submit' && !__submit_inputs[i].disabled) {
						__submit_inputs[i].disabled = true;
					}
				}
			}

			RichTextFocus();

			nViewMode = 2; // Source
		}
		// * HTML view
		else
		{
			var sHTML = "";

			if(oToolbar.body.innerText != null)
			{
				sHTML = oToolbar.body.innerText;
				oToolbar.body.innerHTML = sHTML;
			}
			else
			{
				sHTML = oToolbar.body.ownerDocument.createRange();
				sHTML.selectNodeContents(oToolbar.body);
				oToolbar.body.innerHTML = sHTML.toString();
			}

			// Show all controls
			__rte_plaintext_div.style.display = "block";
			__rte_color_pal_div.style.display = "block";
			__rte_table_div.style.display = "block";
			__rte_font_div.style.display = "block";

			// * enable all submit inputs
			if(typeof __submit_inputs != 'undefined' && __submit_inputs != null)
			{
				for(i = 0; i < __submit_inputs.length; i++)
				{
					if(__submit_inputs[i].type == 'submit' && __submit_inputs[i].disabled) {
						__submit_inputs[i].disabled = false;
					}
				}
			}

			RichTextFocus();

			nViewMode = 1; // Rich-Text
		}
	}

	// * Capture written data
	function RichTextCaptureData(sMethod)
	{
		if(sMethod == "post") {
			window.document.getElementById("RTEData").setAttribute("value", oToolbar.body.innerHTML);
		}
		else if(sMethod == "return") {
			return oToolbar.body.innerHTML;
		}
		else
		{
			throw new Error("RichTextCaptureData: Unknown method " + sMethod);
			return false;
		}
		return true;
	}

	// * Copy to clipboard on click
	function RichTextCopyToClipBoard(sCopytextID, sHoldtextID)
	{
		var __text_holder = new getObj(sHoldtextID);
		if(__text_holder.obj.innerText) {
			__text_holder.obj.innerText = document.getElementById(sCopytextID).innerText;
		}
		else {
			__text_holder.obj.textContent = window.document.getElementById(sCopytextID).textContent;
		}

		try
		{
			var sCopied = __text_holder.obj.createTextRange();
			sCopied.execCommand("Copy", false, null);
		}
		catch(e) {
			window.alert(e);
		}
	}

	// * Clear formatted selection
	function RichTextClearFormat()
	{
		RichTextFocus();
		oToolbar.execCommand("RemoveFormat", false, null);
		RichTextCleanUpHTML();
	}

	// * Undo action
	function RichTextUndo()
	{
		RichTextFocus();
		oToolbar.execCommand("Undo", false, null);
	}

	// * Redo action
	function RichTextRedo()
	{
		RichTextFocus();
		oToolbar.execCommand("Redo", false, null);
	}

	// * Cut selection
	function RichTextCut()
	{
		try
		{
			RichTextFocus();
			oToolbar.execCommand("Cut", false, null);
		}
		catch(e) {
			window.alert(e);
		}
	}

	// * Copy selection to clipboard
	function RichTextCopy()
	{
		try
		{
			RichTextFocus();
			oToolbar.execCommand("Copy", false, null);
		}
		catch(e) {
			window.alert(e);
		}
	}

	// * Insert table
 	function RichTextInsertTable()
	{
		RichTextFocus();
		var j;
		var i;
		var x;
		var y;
		var eCurrentRow;
		var eCurrentCell;
		var sText;
		var oDocBody = oToolbar.getElementsByTagName("BODY").item(0);
		var nRow = (window.document.getElementById("nRow").getAttribute("value") > 0) ? window.document.getElementById("nRow").getAttribute("value") : 1;
		var nColumn = (window.document.getElementById("nColumn").getAttribute("value") > 0) ? window.document.getElementById("nColumn").getAttribute("value") : 1;
		var eTable = oToolbar.createElement("TABLE");
		var eTableBody = oToolbar.createElement("TBODY");

		for(j = 0; j < nRow; j ++)
		{
			eCurrentRow = oToolbar.createElement("TR");

			for(i = 0; i < nColumn; i ++)
			{
				x = j + 1;
				y = i + 1;
				eCurrentCell = oToolbar.createElement("TD");
				sText = oToolbar.createTextNode("Row: " + x + " / Column : " + y);
				eCurrentCell.appendChild(sText);
				eCurrentRow.appendChild(eCurrentCell);
			}

			eTableBody.appendChild(eCurrentRow);
		}

		eTable.appendChild(eTableBody);
		eTable.setAttribute("border", "1");

		if(window.getSelection) {
			insertNodeAtSelection(__rte_toolbar_win, eTable);
		}
		else {
			oDocBody.appendChild(eTable);
		}
	}

	function RichTextNew()
	{
		RichTextFocus();
		oToolbar.body.innerHTML = "";
	}

	function RichTextSaveAs()
	{
    	try {
			oToolbar.execCommand("SaveAs", true, null);
    	}
		catch(e) {
			window.alert(e);
		}
	}

	function RichTextSave(sDocumentName)
	{
		if(sDocumentName == "" || sDocumentName == null) {
			sDocumentName = window.prompt("Enter document name...", "Untitled");
		}

		if(sDocumentName != null)
		{
			try {
				oToolbar.execCommand("Save", true, null);
			}
			catch(e) {
				window.alert(e);
			}
		}
	}


	function RichTextOpen()
	{
    	try {
			oToolbar.execCommand("Open", true, null);
    	}
		catch(e) {
			window.alert(e);
		}
	}

	function RichTextPrint()
	{
		var _bPrint = false;

		try
		{
		   oToolbar.execCommand("Print", false, null);
		   _bPrint = true;
		}
		catch(e) {}
		finally
		{
			if(!_bPrint) {
				window.document.getElementById(sToolbarID).contentWindow.print();
			}
		}
	}

	function RichTextInsertFieldset()
	{
		var __fieldset_inserted = false;
		try
		{
			RichTextFocus();
			oToolbar.execCommand("InsertFieldset", false, null);
			__fieldset_inserted = true;
		}
		catch(e) {}
		finally
		{
			if(__fieldset_inserted) {
				return;
			}
			var fieldset = oToolbar.createElement("FIELDSET");
			var legend = oToolbar.createElement("LEGEND");
			var label = oToolbar.createTextNode("LABEL");
			legend.appendChild(label);
			fieldset.appendChild(legend);
			insertNodeAtSelection(__rte_toolbar_win, fieldset);
		}		
	}

	function RichTextNumericModifier(bIncrease, sElementID)
	{
		var oElement = new getObj(sElementID);
		var nValue = oElement.obj.getAttribute("value");

		if(bIncrease) {
			nValue ++;
		}
		else {
			nValue = nValue - 1;
		}

		nValue = (nValue < 0) ? 0 : nValue;
		nValue = (nValue > 9) ? 9 : nValue;
		oElement.obj.setAttribute("value", nValue);
	}

	// * Color Palette

	function RichTextDisplayBackgroundColorPalette(e)
	{
		var _bBackgroundPaletteStarted = false;
		RichTextHideCharacterMap();

		try
		{
			var __dialog_helper = new getObj("dialog_helper");
			var nChosenColor = __dialog_helper.obj.ChooseColorDlg();

			// Change decimal to hex
			nChosenColor = nChosenColor.toString(16);

			// Add extra zeroes if hex number is less than 6 digits
			if(nChosenColor.length < 6)
			{
  				var sTempString = "000000".substring(0, 6 - nChosenColor.length);
  				nChosenColor = sTempString.concat(nChosenColor);
			}

			nChosenColor = RichTextGetRGBValue(nChosenColor);
			oToolbar.execCommand("BackColor", false, nChosenColor);
			_bBackgroundPaletteStarted = true;
		}
		catch(e) {}
		finally
		{
			if(_bBackgroundPaletteStarted) {
				return;
			}

			var oPalette = new getObj("rich_text_editor_color_palette");

			var nX = (e.pageX) ? e.pageX : window.document.body.clientWidth - event.clientX;
			var nY = (e.pageY) ? e.pageY : window.document.body.clientHeight - event.clientY;

			if(nX < oPalette.obj.offsetWidth) {
				oPalette.style.left = (e.pageX) ? (e.pageX + "px") : (window.document.body.scrollLeft + event.clientX - oPalette.obj.offsetWidth) + "px";
			}
			else {
				oPalette.style.left = (e.pageX) ? (e.pageX - oPalette.obj.offsetWidth) + "px" : (window.document.body.scrollLeft + event.clientX) + "px";
			}

			if(nY < oPalette.obj.offsetHeight) {
				oPalette.style.top = (e.pageY) ? (e.pageY + "px") : (window.document.body.scrollTop + event.clientY - oPalette.obj.offsetHeight) + "px";
			}
			else {
				oPalette.style.top = (e.pageY) ? (e.pageY - oPalette.obj.offsetHeight) + "px" : (window.document.body.scrollTop + event.clientY) + "px";
			}

			oPalette.style.visibility = "visible";

			if(oPalette.obj.focus) {
				oPalette.obj.focus();
			}

			LoadBackgroundColorPalette();
		}
	}

	function LoadBackgroundColorPalette()
	{
		var i;
		var aElements;		

		if(window.document.getElementById("rich_text_editor_color_palette").getElementsByTagName) {
			aElements = window.document.getElementById("rich_text_editor_color_palette").getElementsByTagName("td");
		}
		else if(window.document.all.getElementById("rich_text_editor_color_palette").all.tags) {
			aElements = window.document.all.getElementById("rich_text_editor_color_palette").all.tags("td");
		}

		for(i = 0; i < aElements.length; i++)
		{
			aElements[i].onmouseover = BackgroundColorPaletteOnOver;
			aElements[i].onmouseout = BackgroundColorPaletteOnOut;
			aElements[i].onclick = BackgroundColorPaletteOnClick;
		}
	}

	function BackgroundColorPaletteOnOver()
	{
		this.className = "ColorPaletteOnMouseOver";
		RichTextChangeHexValue(this.style.backgroundColor);
		RichTextModifyColor(this.style.backgroundColor, "HiliteColor");
	}

	function BackgroundColorPaletteOnOut() {
		this.className = "ColorPaletteOnMouseOut";
	}

	function BackgroundColorPaletteOnClick() {
		RichTextModifyColor(this.style.backgroundColor, "HiliteColor");
	}

	function RichTextDisplayColorPalette(e)
	{
		var _bPaletteStarted = false;
		RichTextHideCharacterMap();

		try
		{
			var __dialog_helper = new getObj("dialog_helper");
			var nChosenColor = __dialog_helper.obj.ChooseColorDlg();

			// Change decimal to hex
			nChosenColor = nChosenColor.toString(16);

			// Add extra zeroes if hex number is less than 6 digits
			if(nChosenColor.length < 6)
			{
  				var sTempString = "000000".substring(0, 6 - nChosenColor.length)
  				nChosenColor = sTempString.concat(nChosenColor);
			}

			nChosenColor = RichTextGetRGBValue(nChosenColor);
			oToolbar.execCommand("ForeColor", false, nChosenColor);
			var _bPaletteStarted = true;
		}
		catch(e) {}
		finally
		{
			if(_bPaletteStarted) {
				return;
			}

			var oPalette = new getObj("rich_text_editor_color_palette");

			var nX = (e.pageX) ? e.pageX : window.document.body.clientWidth - event.clientX;
			var nY = (e.pageY) ? e.pageY : window.document.body.clientHeight - event.clientY;

			if(nX < oPalette.obj.offsetWidth) {
				oPalette.style.left = (e.pageX) ? (e.pageX + "px") : (window.document.body.scrollLeft + event.clientX - oPalette.obj.offsetWidth) + "px";
			}
			else {
				oPalette.style.left = (e.pageX) ? (e.pageX - oPalette.obj.offsetWidth) + "px" : (window.document.body.scrollLeft + event.clientX) + "px";
			}

			if(nY < oPalette.obj.offsetHeight) {
				oPalette.style.top = (e.pageY) ? (e.pageY + "px") : (window.document.body.scrollTop + event.clientY - oPalette.obj.offsetHeight) + "px";
			}
			else {
				oPalette.style.top = (e.pageY) ? (e.pageY - oPalette.obj.offsetHeight) + "px" : (window.document.body.scrollTop + event.clientY) + "px";
			}

			oPalette.style.visibility = "visible";

			if(oPalette.obj.focus) {
				oPalette.obj.focus();
			}

			LoadColorPalette();
		}
	}

	function LoadColorPalette()
	{
		var i;
		var aElements;

		if(window.document.getElementById("rich_text_editor_color_palette").getElementsByTagName) {
			aElements = window.document.getElementById("rich_text_editor_color_palette").getElementsByTagName("TD");
		}
		else if(window.document.all.getElementById("rich_text_editor_color_palette").all.tags) {
			aElements = window.document.all.getElementById("rich_text_editor_color_palette").all.tags("td");
		}

		for(i = 0; i < aElements.length; i++)
		{
			aElements[i].onmouseover = ColorPaletteOnOver;
			aElements[i].onmouseout = ColorPaletteOnOut;
			aElements[i].onclick = ColorPaletteOnClick;
		}
	}

	function ColorPaletteOnOver()
	{
		this.className = "ColorPaletteOnMouseOver";
		RichTextChangeHexValue(this.style.backgroundColor);
		RichTextModifyColor(this.style.backgroundColor, "ForeColor");
	}

	function ColorPaletteOnOut() {
		this.className = "ColorPaletteOnMouseOut";
	}

	function ColorPaletteOnClick() {
		RichTextModifyColor(this.style.backgroundColor, "ForeColor");
	}

	function RichTextModifyColor(sColor, sType)
	{
		if(sColor != null && sColor != "")
		{
			if(sType == 'DocumentColor') {
				var oDocBody = oToolbar.getElementsByTagName("BODY").item(0);
				oDocBody.style.backgroundColor = sColor;
				return;
			}

			var __hilite = false;
			RichTextFocus();
			try {
				oToolbar.execCommand(sType, false, sColor);
			}
			catch(e) { __hilite = true; }
			finally
			{
				if(__hilite) {
					oToolbar.execCommand('BackColor', false, sColor);
				}
			}
		}
	}

	function RichTextHideColorPalette()
	{
		oPalette = new getObj("rich_text_editor_color_palette");

		oPalette.style.visibility = "hidden";
		oPalette.style.top = "-10000px";
		oPalette.style.left = "-10000px";
	}

	function RichTextChangeHexValue(nHex) {
		window.document.getElementById("ColorPaletteInput").value = nHex;
	}

	// * Character Map

	function RichTextDisplayCharacterMap(e)
	{
		var oCharacterMap = new getObj("rich_text_editor_character_map");

		var nX = (e.pageX) ? e.pageX : window.document.body.clientWidth - event.clientX;
		var nY = (e.pageY) ? e.pageY : window.document.body.clientHeight - event.clientY;

		if(nX < oCharacterMap.obj.offsetWidth) {
			oCharacterMap.style.left = (e.pageX) ? (e.pageX + "px") : (window.document.body.scrollLeft + event.clientX - oCharacterMap.obj.offsetWidth) + "px";
		}
		else {
			oCharacterMap.style.left = (e.pageX) ? (e.pageX - oCharacterMap.obj.offsetWidth) + "px" : (window.document.body.scrollLeft + event.clientX) + "px";
		}

		if(nY < oCharacterMap.obj.offsetHeight) {
			oCharacterMap.style.top = (e.pageY) ? (e.pageY + "px") : (window.document.body.scrollTop + event.clientY - oCharacterMap.obj.offsetHeight) + "px";
		}
		else {
			oCharacterMap.style.top = (e.pageY) ? (e.pageY - oCharacterMap.obj.offsetHeight) + "px" : (window.document.body.scrollTop + event.clientY) + "px";
		}

		oCharacterMap.style.visibility = "visible";

		if(oCharacterMap.obj.focus) {
			oCharacterMap.obj.focus();
		}

		LoadCharacterMap();
	}

	function LoadCharacterMap()
	{
		var i;
		var aElements;

		if(window.document.getElementById("rich_text_editor_character_map").getElementsByTagName) {
			aElements = window.document.getElementById("rich_text_editor_character_map").getElementsByTagName("TD");
		}
		else if(window.document.all.getElementById("rich_text_editor_character_map").all.tags) {
			aElements = window.document.all.getElementById("rich_text_editor_character_map").all.tags("td");
		}

		for(i = 0; i < aElements.length; i++)
		{
			aElements[i].onmouseover = CharMapOnOver;
			aElements[i].onmouseout = CharMapOnOut;
			aElements[i].onclick = CharMapOnClick;
			aElements[i].onblur = RichTextHideCharacterMap;
		}
	}

	function CharMapOnOver() {
		this.className = "CharacterMapOnMouseOver";
	}

	function CharMapOnOut() {
		this.className = "CharacterMapOnMouseOut";
	}

	function CharMapOnClick() {
		RichTextInsertSymbol(this.id);
	}

	function RichTextHideCharacterMap()
	{
		var oCharacterMap = new getObj("rich_text_editor_character_map");

		oCharacterMap.style.visibility = "hidden";
		oCharacterMap.style.top = "-10000px";
		oCharacterMap.style.left = "-10000px";
	}

	function RichTextInsertSymbol(sSymbol)
	{
		var oCharacterMap = new getObj("rich_text_editor_character_map");

		oCharacterMap.style.visibility = "hidden";
		oCharacterMap.style.top = "-10000px";
		oCharacterMap.style.left = "-10000px";

		RichTextInsertAtCursor(sSymbol, oToolbar);
	}

	function RichTextDate()
	{
		var oCurrentDate = new Date();
		var nDay = oCurrentDate.getDate();
		var nMonth = oCurrentDate.getMonth();
		var nYear = oCurrentDate.getFullYear();
		var sDate = nDay + "." + nMonth + "." + nYear;

		RichTextInsertAtCursor(sDate, oToolbar);
	}

	function RichTextTime()
	{
		var oCurrentTime = new Date();
		var nHours = oCurrentTime.getHours();
		var nMinutes = oCurrentTime.getMinutes();

		if(nMinutes < 10) {
			nMinutes = "0" + nMinutes;
		}

		var sTime = nHours + ":" + nMinutes;

		RichTextInsertAtCursor(sTime, oToolbar);
	}

	function RichTextInsertAtCursor(sInsertText, oObject)
	{
		RichTextFocus();
		__rte_toolbar_win.focus();

		if(oObject.selection)
		{
			var sSelection = oObject.selection.createRange();
			sSelection.text = sInsertText;
		}
		else if(oObject.selectionStart || oObject.selectionStart == "0")
		{
			var nStartPos = oObject.selectionStart;
			var nEndPos = oObject.selectionEnd;
			oObject.value = oObject.value.substring(0, nStartPos) + sInsertText + oObject.value.substring(nEndPos, oObject.value.length);
		}
		else if(oObject.getSelection) {
			var __span = oObject.createElement("SPAN");
			var __insert_text = oObject.createTextNode(sInsertText);
			__span.appendChild(__insert_text);

			insertNodeAtSelection(__rte_toolbar_win, __span);
		}
		else {
			oObject.body.innerHTML += sInsertText;
		}
	}

	// * unbelievable firefox insert at selection
	function insertNodeAtSelection(win, insertNode)
	{
		// get current selection
		var sel = win.getSelection();

		// get the first range of the selection
		// (there's almost always only one range)
		var range = sel.getRangeAt(0);

		// deselect everything
		sel.removeAllRanges();

		// remove content of current selection from document
		range.deleteContents();

		// get location of current selection
		var container = range.startContainer;
		var pos = range.startOffset;

		// make a new range for the new selection
		range = document.createRange();

		if(container.nodeType == 3 && insertNode.nodeType == 3)
		{
			// if we insert text in a textnode, do optimized insertion
			container.insertData(pos, insertNode.nodeValue);
			
			// put cursor after inserted text
			range.setEnd(container, pos+insertNode.length);
			range.setStart(container, pos+insertNode.length);
		}
		else
		{
			var afterNode;
			if(container.nodeType == 3)
			{
				// when inserting into a textnode
				// we create 2 new textnodes
				// and put the insertNode in between
	
				var textNode = container;
				container = textNode.parentNode;
				var text = textNode.nodeValue;
				
				// text before the split
				var textBefore = text.substr(0,pos);
				// text after the split
				var textAfter = text.substr(pos);
				
				var beforeNode = document.createTextNode(textBefore);
				afterNode = document.createTextNode(textAfter);
				
				// insert the 3 new nodes before the old one
				container.insertBefore(afterNode, textNode);
				container.insertBefore(insertNode, afterNode);
				container.insertBefore(beforeNode, insertNode);
				
				// remove the old node
				container.removeChild(textNode);

        	}
			else {
			  // else simply insert the node
			  afterNode = container.childNodes[pos];
			  container.insertBefore(insertNode, afterNode);
	        }

			range.setEnd(afterNode, 0);
			range.setStart(afterNode, 0);
		}

		sel.addRange(range);
	}

	function RichTextGetSystemFonts(sSelectID)
	{
		var i;

		try
		{
			if(__rte_msie_use_dialog_helper_object)
			{
				var oDialogHelper = new getObj("dialog_helper");
				var oFontCollection = new getObj(sSelectID);
				var nTotal = oDialogHelper.obj.fonts.count;
				var oOption = null;
				oFontCollection = new getObj("rich_text_system_font_collection");
				var _bSystemFontsCreated = false;
	
				for(i = 1; i < nTotal; i++)
				{
					aFonts[i] = (aFonts[i] == null) ? oDialogHelper.obj.fonts(i) : aFonts[i];
	
					oOption = window.document.createElement("DIV");
					oOption.setAttribute("id", aFonts[i]);
					oOption.style.fontFamily = aFonts[i];
					oOption.style.fontSize = "medium";
					oOption.innerHTML = aFonts[i];
	
					oFontCollection.obj.appendChild(oOption);
				}
				LoadSystemFonts();
				_bSystemFontsCreated = true;
			}
		}
		catch(e) {}
		finally
		{
			if(_bSystemFontsCreated) {
				return;
			}
			var oFontCollection = new getObj(sSelectID);

			var oOption = null;
			oFontCollection = new getObj("rich_text_system_font_collection");

			aFonts[0] = "Arial";
			aFonts[1] = "Courier";
			aFonts[2] = "Georgia";
			aFonts[3] = "Geneva";
			aFonts[4] = "Helvetica";
			aFonts[5] = "Tahoma";
			aFonts[6] = "Times";
			aFonts[7] = "Verdana";
			aFonts[8] = "sans-serif";
			aFonts[9] = "mono";
			var nTotal = aFonts.length;

			for(i = 0; i < nTotal; i++)
			{
				oOption = window.document.createElement("DIV");
				oOption.setAttribute("id", aFonts[i]);
				oOption.style.fontFamily = aFonts[i];
				oOption.style.fontSize = "medium";
				oOption.innerHTML = aFonts[i];
				oFontCollection.obj.appendChild(oOption);				
			}
			LoadSystemFonts();
		}
	}

	function RichTextGetBlockFormats(sSelectID)
	{
		var i;
		var oBlockFormatCollection = new getObj(sSelectID);
		var _bBlockCreated = false;

		try
		{
			if(__rte_msie_use_dialog_helper_object)
			{
				var oDialogHelper = new getObj("dialog_helper");
				var nTotal = oDialogHelper.obj.blockformats.count;
				var aBlockFormats = new Array();
				var oOption = null;
				oBlockFormatCollection = new getObj("rich_text_block_format_collection");
	
				var a = new Array();
				a["Normal"] = "<p>";
				a["Formatted"] = "<pre>";
				a["Address"] = "<address>";
				a["Heading 1"] = "<h1>";
				a["Heading 2"] = "<h2>";
				a["Heading 3"] = "<h3>";
				a["Heading 4"] = "<h4>";
				a["Heading 5"] = "<h5>";
				a["Heading 6"] = "<h6>";
				a["Numbered List"] = "<ol><li>";
				a["Bulleted List"] = "<ul><li>";
				a["Directory List"] = "<dir><li>";
				a["Menu List"] = "<menu><li>";
				a["Definition Term"] = "<dl><dt>";
				a["Definition"] = "<dl><dd>";
	
				for(i = 1; i < nTotal; i++)
				{
					aBlockFormats[i] = oDialogHelper.obj.blockformats(i);
					oOption = window.document.createElement("DIV");
					oOption.innerHTML = a[aBlockFormats[i]] + aBlockFormats[i];
					oOption.setAttribute("id", aBlockFormats[i]);
					oBlockFormatCollection.obj.appendChild(oOption);
				}
				LoadBlockFormats();
				_bBlockCreated = true;
			}
		}
		catch(e) {}
		finally
		{
			if(_bBlockCreated) {
				return;
			}

			i = 0;
			oBlockFormatCollection = new getObj("rich_text_block_format_collection");

			var aBlockFormatsEquiv = new Array();
			aBlockFormatsEquiv[0] = "<p>";
			aBlockFormatsEquiv[1] = "<h1>";
			aBlockFormatsEquiv[2] = "<h2>";
			aBlockFormatsEquiv[3] = "<h3>";
			aBlockFormatsEquiv[4] = "<h4>";
			aBlockFormatsEquiv[5] = "<h5>";
			aBlockFormatsEquiv[6] = "<h6>";
			aBlockFormatsEquiv[7] = "<p>";
			aBlockFormatsEquiv[8] = "<pre>";
			aBlockFormatsEquiv[9] = "<address>";

			var aBlockFormatsLocalized = new Array();
			aBlockFormatsLocalized[0] = "Normal";
			aBlockFormatsLocalized[1] = "Heading 1";
			aBlockFormatsLocalized[2] = "Heading 2";
			aBlockFormatsLocalized[3] = "Heading 3";
			aBlockFormatsLocalized[4] = "Heading 4";
			aBlockFormatsLocalized[5] = "Heading 5";
			aBlockFormatsLocalized[6] = "Heading 6";
			aBlockFormatsLocalized[7] = "Paragraph";
			aBlockFormatsLocalized[8] = "Preformatted";
			aBlockFormatsLocalized[9] = "Address";

			var nTotal = aBlockFormatsEquiv.length;
			var oOption = null;

			while(i < nTotal)
			{
				oOption = window.document.createElement("DIV");
				oOption.setAttribute("id", aBlockFormatsEquiv[i]);
				oOption.innerHTML = aBlockFormatsEquiv[i] + aBlockFormatsLocalized[i];
				oBlockFormatCollection.obj.appendChild(oOption);
				i ++;
			}
			LoadBlockFormats();
		}
	}

	function RichTextGetRGBValue(nHex)
	{
		var sRGB = "rgb(" + 
					_RichTextHexToR(nHex) + ", " + 
					_RichTextHexToG(nHex) + ", " + 
					_RichTextHexToB(nHex) +
					")";
		return sRGB;
	}

	function _RichTextHexToR(nHex) {
		return parseInt((_RichTextCutHex(nHex)).substring(0, 2), 16);
	}

	function _RichTextHexToG(nHex) {
		return parseInt((_RichTextCutHex(nHex)).substring(2, 4), 16);
	}

	function _RichTextHexToB(nHex) {
		return parseInt((_RichTextCutHex(nHex)).substring(4, 6), 16);
	}

	function _RichTextCutHex(nHex) {
		return (nHex.charAt(0) == "#") ? nHex.substring(1, 7) : nHex;
	}

	function _RichTextSpinButton(sInputID)
	{
		var i;
		var aSpinButtons;
		var oInput = new getObj(sInputID);

		if(window.document.getElementById("rich_text_editor_table").getElementsByTagName) {
			aSpinButtons = window.document.getElementById("rich_text_editor_table").getElementsByTagName("IMG");
		}
		else if(window.document.getElementById("rich_text_editor_table").tags) {
			aSpinButtons = window.document.getElementById("rich_text_editor_table").tags("IMG");
		}

		for(i = 0; i < aSpinButtons.length; i++)
		{
			aSpinButtons[i].style.left = GetOffsetLeft(oInput.obj) + "px";
			aSpinButtons[i].style.top = GetOffsetTop(oInput.obj) + oInput.obj.offsetHeight + "px";
		}
	}

	// * Block formats

	function RichTextDisplayBlockFormats(e)
	{
		var oCharacterMap = new getObj('rich_text_block_format_collection');

		oCharacterMap.style.left = GetOffsetLeft(e) + 'px';
		oCharacterMap.style.top = GetOffsetTop(e) + e.offsetHeight + 'px';

		oCharacterMap.style.visibility = 'visible';

		if(oCharacterMap.obj.focus) {
			oCharacterMap.obj.focus();
		}
	}

	function RichTextHideBlockFormatCollection()
	{
		var oCollection = new getObj('rich_text_block_format_collection');

		oCollection.style.visibility = 'hidden';
		oCollection.style.top = '-10000px';
		oCollection.style.left = '-10000px';
	}

	function RichTextBlockFormatMouseOver()
	{
		this.className = "BlockFormatMouseOver";
		var sBlockFormat = this.getAttribute("id");

		if(sBlockFormat != null && sBlockFormat != "") {
			oToolbar.execCommand("FormatBlock", false, sBlockFormat);
		}
	}

	function RichTextBlockFormatMouseOut() {
		this.className = "BlockFormatMouseOut";
	}

	function RichTextBlockFormatOnClick() {
		RichTextHideBlockFormatCollection();
	}

	function LoadBlockFormats()
	{
		var i;
		var aElements;

		if(window.document.getElementById("rich_text_block_format_collection").getElementsByTagName) {
			aElements = window.document.getElementById("rich_text_block_format_collection").getElementsByTagName("div");
		}
		else if(window.document.all.getElementById("rich_text_block_format_collection").all.tags) {
			aElements = window.document.all.getElementById("rich_text_block_format_collection").all.tags("div");
		}

		for(i = 0; i < aElements.length; i++)
		{
			aElements[i].onmouseover = RichTextBlockFormatMouseOver;
			aElements[i].onmouseout = RichTextBlockFormatMouseOut;
			aElements[i].onclick = RichTextBlockFormatOnClick;
		}
	}

	// * Font collection

	function RichTextDisplaySystemFonts(e)
	{
		var oCharacterMap = new getObj("rich_text_system_font_collection");

		oCharacterMap.style.left = GetOffsetLeft(e) + "px";
		oCharacterMap.style.top = GetOffsetTop(e) + e.offsetHeight + "px";

		oCharacterMap.style.visibility = "visible";

		if(oCharacterMap.obj.focus) {
			oCharacterMap.obj.focus();
		}
	}

	function RichTextHideSystemFontsCollection()
	{
		var oCollection = new getObj("rich_text_system_font_collection");

		oCollection.style.visibility = "hidden";
		oCollection.style.top = "-10000px";
		oCollection.style.left = "-10000px";
	}

	function RichTextSystemFontsMouseOver()
	{
		this.className = "SystemFontMouseOver";

		var sName = this.getAttribute("id");

		if(sName != null && sName != "") {
			oToolbar.execCommand("FontName", false, sName);
		}
	}

	function RichTextSystemFontsMouseOut() {
		this.className = "SystemFontMouseOut";
	}

	function RichTextSystemFontsOnClick() {
		RichTextHideSystemFontsCollection();
	}

	function LoadSystemFonts()
	{
		var i;
		var aElements;

		if(window.document.getElementById("rich_text_system_font_collection").getElementsByTagName) {
			aElements = window.document.getElementById("rich_text_system_font_collection").getElementsByTagName("div");
		}
		else if(window.document.all.getElementById("rich_text_system_font_collection").all.tags) {
			aElements = window.document.all.getElementById("rich_text_system_font_collection").all.tags("div");
		}

		for(i = 0; i < aElements.length; i++)
		{
			aElements[i].onmouseover = RichTextSystemFontsMouseOver;
			aElements[i].onmouseout = RichTextSystemFontsMouseOut;
			aElements[i].onclick = RichTextSystemFontsOnClick;
		}
	}

	function RichTextStyleWithCSS() {
		try {
			oToolbar.execCommand("styleWithCSS", false, __style_with_css);
		}
		catch(e) {}
	}

	// * Text transformation

	function RichTextGetSelection(win)
	{
		if(win.document.getSelection) {
			return win.document.getSelection();
		}
		else if(win.getSelection) {
			return win.getSelection();
		}
		else if (win.document.selection) {
			return win.document.selection.createRange().text;
		}
		return null;
	}

	function RichTextAllUppercase() {
		var __rte_selection = RichTextGetSelection(__rte_toolbar_win);
		RichTextInsertAtCursor(__rte_selection.toUpperCase(), oToolbar);
	}

	function RichTextAllLowercase() {
		var __rte_selection = RichTextGetSelection(__rte_toolbar_win);
		RichTextInsertAtCursor(__rte_selection.toLowerCase(), oToolbar);
	}

	function RichTextReplace()
	{
		var _search_for = new getObj('rte_search_for');
		var _replace_with = new getObj('rte_replace_with');

		var __rte_selection = oToolbar.body.innerHTML;
		__rte_selection = __rte_selection.replace(new RegExp(_search_for.obj.value, "g"), _replace_with.obj.value);
		oToolbar.body.innerHTML = __rte_selection;
	}

	function __rte_replace_key_up()
	{
		var _search_replace_btn = new getObj('search_replace_btn');
		var _rte_replace_with = new getObj('rte_replace_with');
		var _search_for = new getObj('rte_search_for');
		
		var _label = 'Search';

		if(_rte_replace_with.obj.value != '' && _rte_replace_with.obj.value != null)
		{
			_label = 'Replace';
			if(_search_for.obj.value == '' || _search_for.obj.value == null) {
				_search_replace_btn.obj.disabled = true;
			}
			else {
				_search_replace_btn.obj.disabled = false;
			}
		}
		_search_replace_btn.obj.value = _label;
	}

	function RichTextCleanUpHTML()
	{
		var el = null;
		/* clean up word content */
		// remove all class and style attributes
		for(var intLoop = 0; intLoop < oToolbar.all.length; intLoop++)
		{
			el = oToolbar.all[intLoop];
			el.removeAttribute('class', '', 0);
			el.removeAttribute('style', '', 0);
			el.removeAttribute('className', '', 0);			
		}
		// remove all xml prefixes and smarttags
		var html = oToolbar.body.innerHTML;

		html = html.replace(new RegExp(' <\/o:[pP]>', 'g'), ''); // Remove all instances of
		html = html.replace(new RegExp('<[pP]><\/[pP]>', 'g'), ''); // delete all empty paragraph tags
		html = html.replace(new RegExp('<[pP]>&nbsp;<\/[pP]>', 'g'), ''); // delete all empty paragraph tags
		html = html.replace(new RegExp('&nbsp;', 'gi'), ' '); // delete all empty paragraph tags
		
		oToolbar.body.innerHTML = html;
	}

	function RichTextDisplayColorPicker()
	{
		var __color_picker = new getObj('rte_color_picker');
		__color_picker.style.visibility = 'visible';
	}
	
	function RichTextHideColorPicker()
	{
		var __color_picker = new getObj('rte_color_picker');
		__color_picker.style.visibility = 'hidden';
	}
	
	function RichTextInsertFlash()
	{
		var __flash;
		if(__flash == "" || __flash == null) {
			__flash = window.prompt("Enter Flash URL...", "http://");
		}

		if(__flash != null) {		
			var object = oToolbar.createElement("OBJECT");
			object.data = __flash;
			object.type = "application/x-shockwave-flash";
			object.width = "100%";
			object.height = "100%";

			var param_movie = oToolbar.createElement("PARAM");
			param_movie.name = "movie";
			param_movie.value = __flash;

			var param_quality = oToolbar.createElement("PARAM");
			param_quality.name = "quality";
			param_quality.value = "high";	

			var param_menu = oToolbar.createElement("PARAM");
			param_menu.name = "menu";
			param_menu.value = 0;

			object.appendChild(param_movie);
			object.appendChild(param_quality);
			object.appendChild(param_menu);

			if(window.getSelection) {
				insertNodeAtSelection(__rte_toolbar_win, object);
			}
			else {
				var oDocBody = oToolbar.getElementsByTagName("BODY").item(0);
				oDocBody.appendChild(object);
			}
		}
	}

	function RichTextCaptureDesktopItems(event)
	{
		var __imgs = oToolbar.getElementsByTagName("IMG");
		if(typeof __imgs != 'undefined' && __imgs != null)
		{
			var __local_file = new RegExp('file:///', 'gi');

			for(i = 0; i < __imgs.length; i++)
			{
				if(__imgs[i].src) {
					__img_search = __imgs[i].src.search(__local_file);
					if(__img_search > -1) {
						alert(__imgs[i].src);
					}
				}
			}
		}
	}