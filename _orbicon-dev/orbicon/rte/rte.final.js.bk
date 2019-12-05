var nViewMode=1;var sToolbarID="RTE";var oToolbar=null;var __rte_toolbar_win=null;var aFonts=new Array();var __rte_msie_use_dialog_helper_object=false;var __style_with_css=false;var __rte_help_window=true;var __rte_charmap_window=true;YAHOO.util.Event.addListener(window,"load",RichTextOnLoad);function RichTextOnLoad()
{var __rte_toolbar=$(sToolbarID);if(__rte_toolbar==null||typeof __rte_toolbar!='object'){return false;}
if(__rte_toolbar.contentWindow.document){oToolbar=__rte_toolbar.contentWindow.document;__rte_toolbar_win=__rte_toolbar.contentWindow;if(oToolbar.designMode){oToolbar.designMode="On";oToolbar=__rte_toolbar.contentWindow.document;__rte_toolbar_win=__rte_toolbar.contentWindow;if(oToolbar.contentEditable){oToolbar.contentEditable=true;}
try{oToolbar.execCommand("2D-Position",true,true);oToolbar.execCommand("MultipleSelection",true,true);}catch(e){}}
else{if(window.alert){window.alert("Your browser doesn't support Rich-Text editor.\nPlease upgrade.");}}
RichTextGetSystemFonts("FontType");RichTextGetBlockFormats("BlockFormats");var _rte_replace_with=$('rte_replace_with');if(oToolbar.addEventListener){YAHOO.util.Event.addListener(oToolbar,"keypress",RichTextKeyboardShortcuts);}
YAHOO.util.Event.addListener(oToolbar,"mousedown",RichTextHideBlockFormatCollection);YAHOO.util.Event.addListener(oToolbar,"keypress",RichTextHideBlockFormatCollection);YAHOO.util.Event.addListener(oToolbar,"keyup",RichTextContentLength);YAHOO.util.Event.addListener(oToolbar,"focus",RichTextBorderFocus);YAHOO.util.Event.addListener(oToolbar,"blur",RichTextBorderBlur);var aImages;var __rte_button_div_containers;var __rte_button_div_containers_imgs;var i;var n;__rte_button_div_containers=new Array('rich_text_editor_toggle','rich_text_editor_plaintext_controls','rich_text_editor_table');for(n=0;n<__rte_button_div_containers.length;n++){var __div_container=$(__rte_button_div_containers[n]);if(__div_container.getElementsByTagName){__rte_button_div_containers_imgs=__div_container.getElementsByTagName('IMG');}
if(!empty(__rte_button_div_containers_imgs)){for(i=0;i<__rte_button_div_containers_imgs.length;i++){}}}
RichTextDisplayCharMap();LoadCharacterMap();_RichTextSpinButton("nColumn");RichTextStyleWithCSS();RichTextFocus();}
else{if(window.alert){window.alert("Your browser doesn't support Rich-Text editor.\nPlease upgrade.");}}}
function __rte_content_fix()
{}
function RichTextFocus()
{var __rte_window=$(sToolbarID);if(__rte_window.contentWindow.focus){__rte_window.contentWindow.focus();}}
function RichTextBorderFocus()
{var __rte_window=$(sToolbarID);YAHOO.util.Dom.setStyle(__rte_window,'border','1px solid blue');}
function RichTextBorderBlur()
{var __rte_window=$(sToolbarID);YAHOO.util.Dom.setStyle(__rte_window,'border','1px dotted blue');}
function RichTextSelOn()
{YAHOO.util.Dom.setStyle(this,'cursor','default !important');YAHOO.util.Dom.setStyle(this,'borderBottom','1px solid ButtonShadow !important');YAHOO.util.Dom.setStyle(this,'borderRight','1px solid ButtonShadow !important');YAHOO.util.Dom.setStyle(this,'borderLeft','1px solid ButtonHighlight !important');YAHOO.util.Dom.setStyle(this,'borderTop','1px solid ButtonHighlight !important');if(window.status){window.status=this.getAttribute('title');}}
function RichTextSelOff()
{YAHOO.util.Dom.setStyle(this,'border','1px solid #FDF9ED !important');if(window.status){window.status='';}}
function RichTextSelDown()
{YAHOO.util.Dom.setStyle(this,'borderBottom','1px solid ButtonHighlight !important');YAHOO.util.Dom.setStyle(this,'borderRight','1px solid ButtonHighlight !important');YAHOO.util.Dom.setStyle(this,'borderLeft','1px solid ButtonShadow !important');YAHOO.util.Dom.setStyle(this,'borderTop','1px solid ButtonShadow !important');}
function RichTextSelUp(){YAHOO.util.Dom.setStyle(this,'border','1px solid #FDF9ED !important');}
function RichTextBold(){oToolbar.execCommand("Bold",false,null);}
function RichTextItalic(){oToolbar.execCommand("Italic",false,null);}
function RichTextUnderline(){oToolbar.execCommand("Underline",false,null);}
function RichTextStrikeThrough(){oToolbar.execCommand("Strikethrough",false,null);}
function RichTextAlignLeft()
{RichTextFocus();oToolbar.execCommand("JustifyLeft",false,null);}
function RichTextCenter()
{RichTextFocus();oToolbar.execCommand("JustifyCenter",false,null);}
function RichTextAlignRight()
{RichTextFocus();oToolbar.execCommand("JustifyRight",false,null);}
function RichTextJustify()
{RichTextFocus();oToolbar.execCommand("JustifyFull",false,null);}
function RichTextSubscript()
{RichTextFocus();oToolbar.execCommand("Subscript",false,null);}
function RichTextSuperscript()
{RichTextFocus();oToolbar.execCommand("Superscript",false,null);}
function RichTextIndent()
{RichTextFocus();oToolbar.execCommand("Indent",false,null);}
function RichTextOutdent()
{RichTextFocus();oToolbar.execCommand("Outdent",false,null);}
function RichTextOrdList()
{RichTextFocus();oToolbar.execCommand("InsertOrderedList",false,null);}
function RichTextBulList()
{RichTextFocus();oToolbar.execCommand("InsertUnorderedList",false,null);}
function RichTextHyperlink()
{var _bHyperlink=false;RichTextFocus();try{oToolbar.execCommand("CreateLink",true);_bHyperlink=true;}
catch(e){}
finally{if(window.prompt&&!_bHyperlink){var __rte_window=$(sToolbarID);var sSelected=__rte_window.contentWindow.getSelection();var field=sSelected.toString();if((field.search(new RegExp('@','gi'))!=-1)){var sLinkSource=window.prompt('Enter e-mail...','mailto:'+(sSelected));}
else{var sLinkSource;if((field.search(new RegExp('http://','gi'))!=-1)){sLinkSource=window.prompt('Enter URL...',sSelected);}
else if((field.search(new RegExp('https://','gi'))!=-1)){sLinkSource=window.prompt('Enter URL...',sSelected);}
else{sLinkSource=window.prompt('Enter URL...','http://'+(sSelected));}}
if(sLinkSource!=null){oToolbar.execCommand("CreateLink",false,encodeURI(sLinkSource));}}}}
function RichTextUnlink()
{RichTextFocus();oToolbar.execCommand("Unlink",false,null);}
function RichTextInsertImage()
{var _bInsertImage=false;RichTextFocus();try{oToolbar.execCommand("InsertImage",true);_bInsertImage=true;}
catch(e){}
finally{if(window.prompt&&!_bInsertImage){var sImageSource=window.prompt("Enter URL path to image...","http://");if(sImageSource!=null&&sImageSource!=""){oToolbar.execCommand("InsertImage",false,encodeURI(sImageSource));}}}}
function RichTextInsertImageDirect(url)
{if(url!=''){RichTextFocus();oToolbar.execCommand("InsertImage",false,encodeURI(url));}}
function RichTextHorizontalLine()
{RichTextFocus();oToolbar.execCommand("InsertHorizontalRule",false,null);}
function RichTextPaste()
{try{RichTextFocus();oToolbar.execCommand("Paste",false,null);}
catch(e){window.alert(e);}}
function RichTextFont()
{var oFontCollection=$('FontType');var sName=oFontCollection.options[oFontCollection.selectedIndex].getAttribute("value");if(!empty(sName)){oToolbar.execCommand('FontName',false,sName);}}
function RichTextFontSize()
{var oFontSizeCollection=$("FontSize");var nSize=oFontSizeCollection.options[oFontSizeCollection.selectedIndex].getAttribute("value");if(!empty(nSize)){oToolbar.execCommand("FontSize",false,nSize);}}
function RichTextBlockFormat()
{var oBlockFormatCollection=$("BlockFormats");var sBlockFormat=oBlockFormatCollection.options[oBlockFormatCollection.selectedIndex].getAttribute("value");if(!empty(sBlockFormat)){oToolbar.execCommand("FormatBlock",false,sBlockFormat);}}
function RichTextToggleView()
{var __rte_plaintext_div=$('rich_text_editor_plaintext_controls');var __rte_table_div=$('rich_text_editor_table');var __rte_font_div=$('rich_text_editor_font');var __rte_link_div=$('rich_text_link_page');if(window.document.getElementsByTagName){var __submit_inputs=window.document.getElementsByTagName('INPUT');}
if(nViewMode==1){var html_source=oToolbar.body.innerHTML;if(oToolbar.body.innerText!=null){oToolbar.body.innerText=html_source;}
else{oToolbar.body.innerHTML='';oToolbar.body.appendChild(oToolbar.createTextNode(html_source));}
__rte_plaintext_div.style.display="none";__rte_table_div.style.display="none";__rte_font_div.style.display="none";__rte_link_div.style.display='none';if(typeof __submit_inputs!='undefined'&&__submit_inputs!=null){for(i=0;i<__submit_inputs.length;i++){if(__submit_inputs[i].type=='submit'&&!__submit_inputs[i].disabled){__submit_inputs[i].disabled=true;}}}
RichTextFocus();nViewMode=2;}
else
{var html="";if(oToolbar.body.innerText!=null){html=oToolbar.body.innerText;oToolbar.body.innerHTML=html;}
else{html=oToolbar.body.ownerDocument.createRange();html.selectNodeContents(oToolbar.body);oToolbar.body.innerHTML=html.toString();}
__rte_plaintext_div.style.display="block";__rte_table_div.style.display="block";__rte_font_div.style.display="block";__rte_link_div.style.display='block';if(typeof __submit_inputs!='undefined'&&__submit_inputs!=null)
{for(i=0;i<__submit_inputs.length;i++)
{if(__submit_inputs[i].type=='submit'&&__submit_inputs[i].disabled){__submit_inputs[i].disabled=false;}}}
RichTextFocus();nViewMode=1;}}
function RichTextCaptureData(sMethod)
{RichTextAddNoFollow();if(sMethod=="post"){$("RTEData").setAttribute("value",oToolbar.body.innerHTML);}
else if(sMethod=="return"){return oToolbar.body.innerHTML;}
else{throw new Error("RichTextCaptureData: Unknown method "+sMethod);return false;}
return true;}
function RichTextCopyToClipBoard(sCopytextID,sHoldtextID)
{var __text_holder=$(sHoldtextID);if(__text_holder.innerText){__text_holder.innerText=$(sCopytextID).innerText;}
else{__text_holder.textContent=$(sCopytextID).textContent;}
try
{var sCopied=__text_holder.createTextRange();sCopied.execCommand("Copy",false,null);}
catch(e){window.alert(e);}}
function RichTextClearFormat()
{RichTextFocus();oToolbar.execCommand("RemoveFormat",false,null);RichTextCleanUpHTML();}
function RichTextUndo()
{RichTextFocus();oToolbar.execCommand("Undo",false,null);}
function RichTextRedo()
{RichTextFocus();oToolbar.execCommand("Redo",false,null);}
function RichTextCut()
{try
{RichTextFocus();oToolbar.execCommand("Cut",false,null);}
catch(e){window.alert(e);}}
function RichTextCopy()
{try
{RichTextFocus();oToolbar.execCommand("Copy",false,null);}
catch(e){window.alert(e);}}
function RichTextInsertTable()
{RichTextFocus();var j;var i;var x;var y;var eCurrentRow;var eCurrentCell;var sText;var oDocBody=oToolbar.getElementsByTagName("BODY").item(0);var nRow=($("nRow").getAttribute("value")>0)?$("nRow").getAttribute("value"):1;var nColumn=($("nColumn").getAttribute("value")>0)?$("nColumn").getAttribute("value"):1;var eTable=oToolbar.createElement("TABLE");var eTableBody=oToolbar.createElement("TBODY");for(j=0;j<nRow;j++)
{eCurrentRow=oToolbar.createElement("TR");for(i=0;i<nColumn;i++)
{x=j+1;y=i+1;eCurrentCell=oToolbar.createElement("TD");sText=oToolbar.createTextNode("Row: "+x+" / Column : "+y);eCurrentCell.appendChild(sText);eCurrentRow.appendChild(eCurrentCell);}
eTableBody.appendChild(eCurrentRow);}
eTable.appendChild(eTableBody);eTable.setAttribute("border","1");if(window.getSelection){insertNodeAtSelection(__rte_toolbar_win,eTable);}
else{oDocBody.appendChild(eTable);}}
function RichTextSaveAs()
{try{oToolbar.execCommand("SaveAs",true,null);}
catch(e){window.alert(e);}}
function RichTextOpen()
{try{oToolbar.execCommand("Open",true,null);}
catch(e){window.alert(e);}}
function RichTextPrint()
{var _bPrint=false;try{oToolbar.execCommand("Print",false,null);_bPrint=true;}
catch(e){}
finally{if(!_bPrint){$(sToolbarID).contentWindow.print();}}}
function RichTextInsertFieldset()
{var __fieldset_inserted=false;try
{RichTextFocus();oToolbar.execCommand("InsertFieldset",false,null);__fieldset_inserted=true;}
catch(e){}
finally
{if(__fieldset_inserted){return;}
var fieldset=oToolbar.createElement("FIELDSET");var legend=oToolbar.createElement("LEGEND");var label=oToolbar.createTextNode("LABEL");var content=oToolbar.createTextNode("ENTER CONTENT HERE");legend.appendChild(label);fieldset.appendChild(legend);fieldset.appendChild(content);insertNodeAtSelection(__rte_toolbar_win,fieldset);}}
function RichTextNumericModifier(bIncrease,sElementID)
{var oElement=$(sElementID);var nValue=oElement.getAttribute("value");if(bIncrease){nValue++;}
else{nValue=(nValue-1);}
nValue=(nValue<0)?0:nValue;nValue=(nValue>9)?9:nValue;oElement.setAttribute("value",nValue);}
function RichTextDisplayBackgroundColorPalette(e)
{var _bBackgroundPaletteStarted=false;RichTextHideCharacterMap();try
{var __dialog_helper=$("dialog_helper");var nChosenColor=__dialog_helper.ChooseColorDlg();nChosenColor=nChosenColor.toString(16);if(nChosenColor.length<6)
{var sTempString="000000".substring(0,6-nChosenColor.length);nChosenColor=sTempString.concat(nChosenColor);}
nChosenColor=RichTextGetRGBValue(nChosenColor);oToolbar.execCommand("BackColor",false,nChosenColor);_bBackgroundPaletteStarted=true;}
catch(e){}
finally
{if(_bBackgroundPaletteStarted){return;}
var oPalette=$("rich_text_editor_color_palette");var nX=(e.pageX)?e.pageX:window.document.body.clientWidth-event.clientX;var nY=(e.pageY)?e.pageY:window.document.body.clientHeight-event.clientY;if(nX<oPalette.offsetWidth){oPalette.style.left=(e.pageX)?(e.pageX+"px"):(window.document.body.scrollLeft+event.clientX-oPalette.offsetWidth)+"px";}
else{oPalette.style.left=(e.pageX)?(e.pageX-oPalette.offsetWidth)+"px":(window.document.body.scrollLeft+event.clientX)+"px";}
if(nY<oPalette.offsetHeight){oPalette.style.top=(e.pageY)?(e.pageY+"px"):(window.document.body.scrollTop+event.clientY-oPalette.offsetHeight)+"px";}
else{oPalette.style.top=(e.pageY)?(e.pageY-oPalette.offsetHeight)+"px":(window.document.body.scrollTop+event.clientY)+"px";}
oPalette.style.visibility="visible";if(oPalette.focus){oPalette.focus();}
LoadBackgroundColorPalette();}}
function LoadBackgroundColorPalette()
{var i;var aElements;if($("rich_text_editor_color_palette").getElementsByTagName){aElements=$("rich_text_editor_color_palette").getElementsByTagName("td");}
else if(window.document.all.getElementById("rich_text_editor_color_palette").all.tags){aElements=window.document.all.getElementById("rich_text_editor_color_palette").all.tags("td");}
for(i=0;i<aElements.length;i++)
{aElements[i].onmouseover=BackgroundColorPaletteOnOver;aElements[i].onmouseout=BackgroundColorPaletteOnOut;aElements[i].onclick=BackgroundColorPaletteOnClick;}}
function BackgroundColorPaletteOnOver()
{this.className="ColorPaletteOnMouseOver";RichTextChangeHexValue(this.style.backgroundColor);RichTextModifyColor(this.style.backgroundColor,"HiliteColor");}
function BackgroundColorPaletteOnOut(){this.className="ColorPaletteOnMouseOut";}
function BackgroundColorPaletteOnClick(){RichTextModifyColor(this.style.backgroundColor,"HiliteColor");}
function RichTextDisplayColorPalette(e)
{var _bPaletteStarted=false;RichTextHideCharacterMap();try
{var __dialog_helper=$("dialog_helper");var nChosenColor=__dialog_helper.ChooseColorDlg();nChosenColor=nChosenColor.toString(16);if(nChosenColor.length<6)
{var sTempString="000000".substring(0,6-nChosenColor.length)
nChosenColor=sTempString.concat(nChosenColor);}
nChosenColor=RichTextGetRGBValue(nChosenColor);oToolbar.execCommand("ForeColor",false,nChosenColor);var _bPaletteStarted=true;}
catch(e){}
finally
{if(_bPaletteStarted){return;}
var oPalette=$("rich_text_editor_color_palette");var nX=(e.pageX)?e.pageX:window.document.body.clientWidth-event.clientX;var nY=(e.pageY)?e.pageY:window.document.body.clientHeight-event.clientY;if(nX<oPalette.offsetWidth){oPalette.style.left=(e.pageX)?(e.pageX+"px"):(window.document.body.scrollLeft+event.clientX-oPalette.offsetWidth)+"px";}
else{oPalette.style.left=(e.pageX)?(e.pageX-oPalette.offsetWidth)+"px":(window.document.body.scrollLeft+event.clientX)+"px";}
if(nY<oPalette.offsetHeight){oPalette.style.top=(e.pageY)?(e.pageY+"px"):(window.document.body.scrollTop+event.clientY-oPalette.offsetHeight)+"px";}
else{oPalette.style.top=(e.pageY)?(e.pageY-oPalette.offsetHeight)+"px":(window.document.body.scrollTop+event.clientY)+"px";}
oPalette.style.visibility="visible";if(oPalette.focus){oPalette.focus();}
LoadColorPalette();}}
function LoadColorPalette()
{var i;var aElements;if($("rich_text_editor_color_palette").getElementsByTagName){aElements=$("rich_text_editor_color_palette").getElementsByTagName("TD");}
else if(window.document.all.getElementById("rich_text_editor_color_palette").all.tags){aElements=window.document.all.getElementById("rich_text_editor_color_palette").all.tags("TD");}
for(i=0;i<aElements.length;i++)
{aElements[i].onmouseover=ColorPaletteOnOver;aElements[i].onmouseout=ColorPaletteOnOut;aElements[i].onclick=ColorPaletteOnClick;}}
function ColorPaletteOnOver()
{this.className="ColorPaletteOnMouseOver";RichTextChangeHexValue(this.style.backgroundColor);RichTextModifyColor(this.style.backgroundColor,"ForeColor");}
function ColorPaletteOnOut(){this.className="ColorPaletteOnMouseOut";}
function ColorPaletteOnClick(){RichTextModifyColor(this.style.backgroundColor,"ForeColor");}
function RichTextHiliteSelection(){RichTextModifyColor('ffff00','HiliteColor')}
function RichTextModifyColor(sColor,sType)
{if(sColor!=null&&sColor!="")
{if(sType=='DocumentColor'){var oDocBody=oToolbar.getElementsByTagName("BODY").item(0);oDocBody.style.backgroundColor=sColor;return;}
var __hilite=false;RichTextFocus();try{oToolbar.execCommand(sType,false,sColor);}
catch(e){__hilite=true;}
finally
{if(__hilite){oToolbar.execCommand('BackColor',false,sColor);}}}}
function RichTextHideColorPalette()
{oPalette=$("rich_text_editor_color_palette");oPalette.style.visibility="hidden";oPalette.style.top="-10000px";oPalette.style.left="-10000px";}
function RichTextChangeHexValue(nHex){$("ColorPaletteInput").value=nHex;}
function RichTextDisplayCharacterMap(e)
{var oCharacterMap=$("rich_text_editor_character_map");var nX=(e.pageX)?e.pageX:window.document.body.clientWidth-event.clientX;var nY=(e.pageY)?e.pageY:window.document.body.clientHeight-event.clientY;if(nX<oCharacterMap.offsetWidth){oCharacterMap.style.left=(e.pageX)?(e.pageX+"px"):(window.document.body.scrollLeft+event.clientX-oCharacterMap.offsetWidth)+"px";}
else{oCharacterMap.style.left=(e.pageX)?(e.pageX-oCharacterMap.offsetWidth)+"px":(window.document.body.scrollLeft+event.clientX)+"px";}
if(nY<oCharacterMap.offsetHeight){oCharacterMap.style.top=(e.pageY)?(e.pageY+"px"):(window.document.body.scrollTop+event.clientY-oCharacterMap.offsetHeight)+"px";}
else{oCharacterMap.style.top=(e.pageY)?(e.pageY-oCharacterMap.offsetHeight)+"px":(window.document.body.scrollTop+event.clientY)+"px";}
oCharacterMap.style.visibility="visible";if(oCharacterMap.focus){oCharacterMap.focus();}
LoadCharacterMap();}
function LoadCharacterMap()
{var i;var aElements;if($("CharacterMap").getElementsByTagName){aElements=$("CharacterMap").getElementsByTagName("BUTTON");}
else if(window.document.all.getElementById("CharacterMap").all.tags){aElements=window.document.all.getElementById("CharacterMap").all.tags("BUTTON");}
for(i=0;i<aElements.length;i++)
{aElements[i].onclick=CharMapOnClick;}}
function CharMapOnClick(){RichTextInsertAtCursor(this.innerHTML,oToolbar);}
function RichTextDate()
{var oCurrentDate=new Date();var nDay=oCurrentDate.getDate();var nMonth=oCurrentDate.getMonth();var nYear=oCurrentDate.getFullYear();var sDate=nDay+"."+nMonth+"."+nYear;RichTextInsertAtCursor(sDate,oToolbar);}
function RichTextTime()
{var oCurrentTime=new Date();var nHours=oCurrentTime.getHours();var nMinutes=oCurrentTime.getMinutes();if(nMinutes<10){nMinutes="0"+nMinutes;}
var sTime=nHours+":"+nMinutes;RichTextInsertAtCursor(sTime,oToolbar);}
function RichTextInsertAtCursor(text,el)
{RichTextFocus();__rte_toolbar_win.focus();if(el.selection){var selection=el.selection.createRange();selection.text=text;}
else if(el.selectionStart||el.selectionStart=="0"){var start_pos=el.selectionStart;var end_pos=el.selectionEnd;el.value=el.value.substring(0,start_pos)+text+el.value.substring(end_pos,el.value.length);}
else if(el.getSelection){var __span=el.createElement("SPAN");var __insert_text=el.createTextNode(text);__span.appendChild(__insert_text);insertNodeAtSelection(__rte_toolbar_win,__span);}
else{el.body.innerHTML+=text;}}
function insertNodeAtSelection(win,insertNode)
{var sel=win.getSelection();var range=sel.getRangeAt(0);sel.removeAllRanges();range.deleteContents();var container=range.startContainer;var pos=range.startOffset;range=document.createRange();if(container.nodeType==3&&insertNode.nodeType==3)
{container.insertData(pos,insertNode.nodeValue);range.setEnd(container,pos+insertNode.length);range.setStart(container,pos+insertNode.length);}
else
{var afterNode;if(container.nodeType==3)
{var textNode=container;container=textNode.parentNode;var text=textNode.nodeValue;var textBefore=text.substr(0,pos);var textAfter=text.substr(pos);var beforeNode=document.createTextNode(textBefore);afterNode=document.createTextNode(textAfter);container.insertBefore(afterNode,textNode);container.insertBefore(insertNode,afterNode);container.insertBefore(beforeNode,insertNode);container.removeChild(textNode);}
else{afterNode=container.childNodes[pos];container.insertBefore(insertNode,afterNode);}
range.setEnd(afterNode,0);range.setStart(afterNode,0);}
sel.addRange(range);}
function RichTextGetSystemFonts(sSelectID)
{var i;try
{if(__rte_msie_use_dialog_helper_object)
{var oDialogHelper=$("dialog_helper");var oFontCollection=$(sSelectID);var nTotal=oDialogHelper.fonts.count;var oOption=null;oFontCollection=$("rich_text_system_font_collection");var _bSystemFontsCreated=false;for(i=1;i<nTotal;i++)
{aFonts[i]=(aFonts[i]==null)?oDialogHelper.fonts(i):aFonts[i];oOption=window.document.createElement("DIV");oOption.setAttribute("id",aFonts[i]);oOption.style.fontFamily=aFonts[i];oOption.style.fontSize="medium";oOption.innerHTML=aFonts[i];oFontCollection.appendChild(oOption);}
LoadSystemFonts();_bSystemFontsCreated=true;}}
catch(e){}
finally
{if(_bSystemFontsCreated){return;}
var oFontCollection=$(sSelectID);var oOption=null;oFontCollection=$("rich_text_system_font_collection");aFonts[0]="Arial";aFonts[1]="Courier";aFonts[2]="Georgia";aFonts[3]="Geneva";aFonts[4]="Helvetica";aFonts[5]="Tahoma";aFonts[6]="Times";aFonts[7]="Verdana";aFonts[8]="serif";aFonts[9]="monospace";var nTotal=aFonts.length;for(i=0;i<nTotal;i++)
{oOption=window.document.createElement("DIV");oOption.setAttribute("id",aFonts[i]);oOption.style.fontFamily=aFonts[i];oOption.style.fontSize="medium";oOption.innerHTML=aFonts[i];oFontCollection.appendChild(oOption);}
LoadSystemFonts();}}
function RichTextGetBlockFormats(sSelectID)
{var i;var oBlockFormatCollection=$(sSelectID);var _bBlockCreated=false;try
{if(__rte_msie_use_dialog_helper_object)
{var oDialogHelper=$("dialog_helper");var nTotal=oDialogHelper.blockformats.count;var aBlockFormats=new Array();var oOption=null;oBlockFormatCollection=$("rich_text_block_format_collection");var a=new Array();a["Normal"]="<p>";a["Formatted"]="<pre>";a["Address"]="<address>";a["Heading 1"]="<h1>";a["Heading 2"]="<h2>";a["Heading 3"]="<h3>";a["Heading 4"]="<h4>";a["Heading 5"]="<h5>";a["Heading 6"]="<h6>";a["Numbered List"]="<ol><li>";a["Bulleted List"]="<ul><li>";a["Directory List"]="<dir><li>";a["Menu List"]="<menu><li>";a["Definition Term"]="<dl><dt>";a["Definition"]="<dl><dd>";for(i=1;i<nTotal;i++)
{aBlockFormats[i]=oDialogHelper.blockformats(i);oOption=window.document.createElement("DIV");oOption.innerHTML=a[aBlockFormats[i]]+aBlockFormats[i];oOption.setAttribute("id",aBlockFormats[i]);oBlockFormatCollection.appendChild(oOption);}
LoadBlockFormats();_bBlockCreated=true;}}
catch(e){}
finally
{if(_bBlockCreated){return;}
i=0;oBlockFormatCollection=$("rich_text_block_format_collection");var aBlockFormatsEquiv=new Array();aBlockFormatsEquiv[0]="<p>";aBlockFormatsEquiv[1]="<h1>";aBlockFormatsEquiv[2]="<h2>";aBlockFormatsEquiv[3]="<h3>";aBlockFormatsEquiv[4]="<h4>";aBlockFormatsEquiv[5]="<h5>";aBlockFormatsEquiv[6]="<h6>";aBlockFormatsEquiv[7]="<p>";aBlockFormatsEquiv[8]="<pre>";aBlockFormatsEquiv[9]="<address>";var aBlockFormatsLocalized=new Array();aBlockFormatsLocalized[0]="Normal";aBlockFormatsLocalized[1]="Heading 1";aBlockFormatsLocalized[2]="Heading 2";aBlockFormatsLocalized[3]="Heading 3";aBlockFormatsLocalized[4]="Heading 4";aBlockFormatsLocalized[5]="Heading 5";aBlockFormatsLocalized[6]="Heading 6";aBlockFormatsLocalized[7]="Paragraph";aBlockFormatsLocalized[8]="Preformatted";aBlockFormatsLocalized[9]="Address";var nTotal=aBlockFormatsEquiv.length;var oOption=null;while(i<nTotal)
{oOption=window.document.createElement("DIV");oOption.setAttribute("id",aBlockFormatsEquiv[i]);oOption.innerHTML=aBlockFormatsEquiv[i]+aBlockFormatsLocalized[i];oBlockFormatCollection.appendChild(oOption);i++;}
LoadBlockFormats();}}
function RichTextGetRGBValue(nHex)
{var sRGB="rgb("+_RichTextHexToR(nHex)+", "+_RichTextHexToG(nHex)+", "+_RichTextHexToB(nHex)+")";return sRGB;}
function _RichTextHexToR(nHex){return parseInt((_RichTextCutHex(nHex)).substring(0,2),16);}
function _RichTextHexToG(nHex){return parseInt((_RichTextCutHex(nHex)).substring(2,4),16);}
function _RichTextHexToB(nHex){return parseInt((_RichTextCutHex(nHex)).substring(4,6),16);}
function _RichTextCutHex(nHex){return(nHex.charAt(0)=="#")?nHex.substring(1,7):nHex;}
function _RichTextSpinButton(sInputID)
{var i;var aSpinButtons;var oInput=$(sInputID);if($("rich_text_editor_table").getElementsByTagName){aSpinButtons=$("rich_text_editor_table").getElementsByTagName("IMG");}
else if($("rich_text_editor_table").tags){aSpinButtons=$("rich_text_editor_table").tags("IMG");}
for(i=0;i<aSpinButtons.length;i++)
{aSpinButtons[i].style.left=GetOffsetLeft(oInput)+"px";aSpinButtons[i].style.top=GetOffsetTop(oInput)+oInput.offsetHeight+"px";}}
function RichTextDisplayBlockFormats(e)
{var oCharacterMap=$('rich_text_block_format_collection');oCharacterMap.style.left=GetOffsetLeft(e)+'px';oCharacterMap.style.top=GetOffsetTop(e)+e.offsetHeight+'px';oCharacterMap.style.visibility='visible';if(oCharacterMap.focus){oCharacterMap.focus();}}
function RichTextHideBlockFormatCollection()
{var oCollection=$('rich_text_block_format_collection');oCollection.style.visibility='hidden';oCollection.style.top='-10000px';oCollection.style.left='-10000px';}
function RichTextBlockFormatMouseOver()
{this.className="BlockFormatMouseOver";var sBlockFormat=this.getAttribute("id");if(sBlockFormat!=null&&sBlockFormat!=""){oToolbar.execCommand("FormatBlock",false,sBlockFormat);}}
function RichTextBlockFormatMouseOut(){this.className="BlockFormatMouseOut";}
function RichTextBlockFormatOnClick(){RichTextHideBlockFormatCollection();}
function LoadBlockFormats()
{var i;var aElements;if($("rich_text_block_format_collection").getElementsByTagName){aElements=$("rich_text_block_format_collection").getElementsByTagName("div");}
else if(window.document.all.getElementById("rich_text_block_format_collection").all.tags){aElements=window.document.all.getElementById("rich_text_block_format_collection").all.tags("div");}
for(i=0;i<aElements.length;i++)
{aElements[i].onmouseover=RichTextBlockFormatMouseOver;aElements[i].onmouseout=RichTextBlockFormatMouseOut;aElements[i].onclick=RichTextBlockFormatOnClick;}}
function RichTextDisplaySystemFonts(e)
{var oCharacterMap=$("rich_text_system_font_collection");oCharacterMap.style.left=GetOffsetLeft(e)+"px";oCharacterMap.style.top=GetOffsetTop(e)+e.offsetHeight+"px";oCharacterMap.style.visibility="visible";if(oCharacterMap.focus){oCharacterMap.focus();}}
function RichTextHideSystemFontsCollection()
{var oCollection=$("rich_text_system_font_collection");oCollection.style.visibility="hidden";oCollection.style.top="-10000px";oCollection.style.left="-10000px";}
function RichTextSystemFontsMouseOver()
{this.className="SystemFontMouseOver";var sName=this.getAttribute("id");if(sName!=null&&sName!=""){oToolbar.execCommand("FontName",false,sName);}}
function RichTextSystemFontsMouseOut(){this.className="SystemFontMouseOut";}
function RichTextSystemFontsOnClick(){RichTextHideSystemFontsCollection();}
function LoadSystemFonts()
{var i;var aElements;if($("rich_text_system_font_collection").getElementsByTagName){aElements=$("rich_text_system_font_collection").getElementsByTagName("div");}
else if(window.document.all.getElementById("rich_text_system_font_collection").all.tags){aElements=window.document.all.getElementById("rich_text_system_font_collection").all.tags("div");}
for(i=0;i<aElements.length;i++)
{aElements[i].onmouseover=RichTextSystemFontsMouseOver;aElements[i].onmouseout=RichTextSystemFontsMouseOut;aElements[i].onclick=RichTextSystemFontsOnClick;}}
function RichTextStyleWithCSS(){try{oToolbar.execCommand("styleWithCSS",false,__style_with_css);}
catch(e){}}
function RichTextGetSelection(win)
{if(win.document.getSelection){return win.document.getSelection();}
else if(win.getSelection){return win.getSelection();}
else if(win.document.selection){return win.document.selection.createRange().text;}
return null;}
function RichTextAllUppercase(){var __rte_selection=RichTextGetSelection(__rte_toolbar_win);RichTextInsertAtCursor(__rte_selection.toUpperCase(),oToolbar);}
function RichTextAllLowercase(){var __rte_selection=RichTextGetSelection(__rte_toolbar_win);RichTextInsertAtCursor(__rte_selection.toLowerCase(),oToolbar);}
function RichTextReplace()
{var _search_for=$('rte_search_for');var _replace_with=$('rte_replace_with');var __rte_selection=oToolbar.body.innerHTML;__rte_selection=__rte_selection.replace(new RegExp(_search_for.value,"g"),_replace_with.value);oToolbar.body.innerHTML=__rte_selection;}
function __rte_replace_key_up()
{var _search_replace_btn=$('search_replace_btn');var _rte_replace_with=$('rte_replace_with');var _search_for=$('rte_search_for');var _label='Search';if(_rte_replace_with.value!=''&&_rte_replace_with.value!=null)
{_label='Replace';if(_search_for.value==''||_search_for.value==null){_search_replace_btn.disabled=true;}
else{_search_replace_btn.disabled=false;}}
_search_replace_btn.value=_label;}
function RichTextCleanUpHTML()
{var el=null;var intLoop=0;var all_e=(oToolbar.all)?oToolbar.all:oToolbar.getElementsByTagName('*');for(intLoop=0;intLoop<all_e.length;intLoop++){el=all_e[intLoop];el.removeAttribute('class','',0);el.removeAttribute('style','',0);el.removeAttribute('className','',0);el.removeAttribute('clear','',0);}
var html=oToolbar.body.innerHTML;RegExp.global=true;RegExp.multiline=true;html=html.replace(new RegExp('/<?xml[^>]*>/','gi'),"");html=html.replace(new RegExp('/<xml[^>]*>/','gi'),"");html=html.replace(/(<[^>]+)[ \t\r\n]+lang=[^ \t\r\n|>]*([^>]*>)/gi,"$1 $2");html=html.replace(/(<[^>]+)[ \t\r\n]+class=Mso[^ \t\r\n>]*([^>]*>)/gi,"$1 $2");html=html.replace(/(<[^>]+)[ \t\r\n]+class="Mso[^ \t\r\n>"]*"([^>]*>)/gi,"$1 $2");html=html.replace(/(<[^>]+[ \t\r\n]+style="[^"]*)[; \t\r\n]*mso-[^:]+:[^;"]+;([^"]*"[^>]*>)/gi,"$1$2");html=html.replace(/(<[^>]+[ \t\r\n]+style="[^"]*)[; \t\r\n]*mso-[^:]+:[^;"]+("[^>]*>)/gi,"$1$2");html=html.replace(/(<[^>]+[ \t\r\n]+style=")mso-[^:]+:[^;"]+;([^"]*"[^>]*>)/gi,"$1$2");html=html.replace(/(<[^>]+[ \t\r\n]+style=")mso-[^:]+:[^;"]+("[^>]*>)/gi,"$1$2");html=html.replace(/<span *><span *>([^<]*)<\/span><\/span>/gi,"<span>$1</span>");html=html.replace(/<span[^>]*><\/span>/gi,"");html=html.replace(/<span *>([^<]*)<\/span>/gi,"$1");html=html.replace(/<font><font>([^<]*)<\/font><\/font>/gi,"<font>$1</font>");html=html.replace(/<font[^>]*><\/font>/gi,"");html=html.replace(/<font>([^<]*)<\/font>/gi,"$1");html=html.replace(/<p[^>]+>&nbsp;<\/p>/gi,"");html=html.replace(/<p[^>]+><\/p>/gi,"");html=html.replace(/<div[^>]+>&nbsp;<\/div>/gi,"");html=html.replace(/<div[^>]+><\/div>/gi,"");html=html.replace(/<p([ \t\r\n]+[^<]*)?><span([ \t\r\n]+[^<]*)?>([^<]*)<\/p><\/span>/gi,"<p $1><span $2>$3</span></p>");html=html.replace(/<span([ \t\r\n]+[^<]*)?><p([ \t\r\n]+[^<]*)?>([^<]*)<\/span><\/p>/gi,"<p $1><span $2>$3</span></p>");html=html.replace(new RegExp('<o:[pP]><\/o:[pP]>','g'),'');html=html.replace(new RegExp('<[spanSPAN]>&nbsp;<\/[spanSPAN]>','g'),'');html=html.replace(new RegExp('&nbsp;','gi'),' ');html=html.replace(new RegExp('\n','gi'),' ');html=html.replace(new RegExp('\r','gi'),'');html=html.replace(new RegExp('<!--([^>]+)-->','gi'),'');html=html.replace(new RegExp('&lt;!--([^>]+)--&gt;','gi'),'');oToolbar.body.innerHTML=html;}
function RichTextDisplayColorPicker(obj)
{var picker=$('rte_color_picker');if(picker.style.visibility!='visible'){picker.style.visibility='visible';$('ddPicker').style.zIndex="1000";setLyr(obj,'ddPicker');}
else{RichTextHideColorPicker();}}
function RichTextHideColorPicker()
{$('rte_color_picker').style.visibility='hidden';}
function RichTextInsertFlash()
{var __flash;if(__flash==""||__flash==null){__flash=window.prompt("Enter URL path to Flash document...","http://");}
if(__flash!=null&&__flash!="http://"){__flash=encodeURI(__flash);var object=oToolbar.createElement("OBJECT");object.data=__flash;object.type="application/x-shockwave-flash";object.width="100%";object.height="100%";var param_movie=oToolbar.createElement("PARAM");param_movie.name="movie";param_movie.value=__flash;var param_quality=oToolbar.createElement("PARAM");param_quality.name="quality";param_quality.value="high";var param_menu=oToolbar.createElement("PARAM");param_menu.name="menu";param_menu.value=0;object.appendChild(param_movie);object.appendChild(param_quality);object.appendChild(param_menu);if(window.getSelection){insertNodeAtSelection(__rte_toolbar_win,object);}
else{var oDocBody=oToolbar.getElementsByTagName("BODY").item(0);oDocBody.appendChild(object);}}}
function RichTextAddNoFollow()
{var aAnchors=null;var i;var __a_search;var __a_search_js;var __a_search_icon;if(oToolbar.getElementsByTagName){aAnchors=oToolbar.getElementsByTagName("a");}
else if(oToolbar.all.tags){aAnchors=oToolbar.all.tags("a");}
if(typeof aAnchors!='undefined'&&aAnchors!=null)
{for(i=0;i<aAnchors.length;i++)
{__a_search=aAnchors[i].href.search(new RegExp(__orbicon_server_name,'gi'));__a_search_js=aAnchors[i].href.search(new RegExp('javascript:','gi'));__a_search_root=aAnchors[i].href.search(new RegExp('./','gi'));if(aAnchors[i].href&&(__a_search==-1)&&(aAnchors[i].href!='#')&&(__a_search_js==-1))
{aAnchors[i].rel="nofollow";aAnchors[i].className="link-external";}
__a_search_icon=aAnchors[i].href.search(new RegExp("https://",'gi'));if(__a_search_icon>-1){aAnchors[i].className="link-https";}
__a_search_icon=aAnchors[i].href.search(new RegExp("mailto:",'gi'));if(__a_search_icon>-1){aAnchors[i].className="link-mailto";}
__a_search_icon=aAnchors[i].href.search(new RegExp("news://",'gi'));if(__a_search_icon>-1){aAnchors[i].className="link-news";}
__a_search_icon=aAnchors[i].href.search(new RegExp("ftp://",'gi'));if(__a_search_icon>-1){aAnchors[i].className="link-ftp";}
__a_search_icon=aAnchors[i].href.search(new RegExp("irc://",'gi'));if(__a_search_icon>-1){aAnchors[i].className="link-irc";}}}}
function RichTextKeyboardShortcuts(e)
{var ctrl_pressed;var key_pressed;var clean_key_pressed;var cancel_propagation=false;if(e){ctrl_pressed=(e.modifiers)?(e.modifiers&Event.CONTROL_MASK):e.ctrlKey;alt_pressed=(e.modifiers)?(e.modifiers&Event.ALT_MASK):e.altKey;shift_pressed=(e.modifiers)?(e.modifiers&Event.SHIFT_MASK):e.shiftKey;clean_key_pressed=(e.which)?e.which:e.keyCode;}
if(ctrl_pressed||alt_pressed||shift_pressed){key_pressed=String.fromCharCode(clean_key_pressed);key_pressed=key_pressed.toUpperCase();cancel_propagation=true;if(alt_pressed&&ctrl_pressed){switch(key_pressed){case'C':RichTextInsertAtCursor("©",oToolbar);break;case'R':RichTextInsertAtCursor("®",oToolbar);break;case'T':RichTextInsertAtCursor("™",oToolbar);break;default:cancel_propagation=false;break;}}
else if(ctrl_pressed&&shift_pressed){switch(key_pressed){case'A':RichTextAllUppercase();break;case'K':RichTextAllLowercase();break;case'+':RichTextSuperscript();break;case'-':RichTextSubscript();break;default:cancel_propagation=false;break;}}
else if(ctrl_pressed)
{key_pressed=(clean_key_pressed==32)?'SPACEBAR':key_pressed;switch(key_pressed)
{case'N':RichTextNew();break;case'O':RichTextOpen();break;case'W':RichTextClose();break;case'S':RichTextSave();break;case'P':RichTextPrint();break;case'F':RichTextFullScreen();break;case'B':RichTextBold();break;case'U':RichTextUnderline();break;case'I':RichTextItalic();break;case'K':RichTextHyperlink();break;case'SPACEBAR':RichTextClearFormat();break;case'E':RichTextCenter();break;case'J':RichTextJustify();break;case'L':RichTextAlignLeft();break;case'R':RichTextAlignRight();break;default:cancel_propagation=false;break;}}
else{cancel_propagation=false;}
if(cancel_propagation)
{if(e.preventDefault){e.preventDefault();}
if(e.stopPropagation){e.stopPropagation();}
else if(e.cancelBubble){e.cancelBubble=true;}
return false;}}}
function RichTextDisplayCharMap()
{if(__rte_charmap_window){sh('rich_text_editor_character_map');__rte_charmap_window=false;}
else{sh('rich_text_editor_character_map');__rte_charmap_window=true;}}
function RichTextContentLength()
{if(nViewMode==1){RegExp.global=true;RegExp.multiline=true;var content=RichTextCaptureData('return');content=content.replace(new RegExp('&nbsp;','gi'),'');content=content.replace(new RegExp("\n",'gi'),'');content=content.replace(new RegExp("\r",'gi'),'');content=content.replace(new RegExp('(<([^>]+)>)','gi'),"");content=content.replace("s/<[a-zA-Z\/][^>]*>//g",'');content=content.replace("\\<.*?\\>",'');$('rte_content_length').value=content.length;}}
function RichTextInsertFileDirect(url)
{RichTextFocus();var __rte_selection=RichTextGetSelection(__rte_toolbar_win);if(__rte_selection){oToolbar.execCommand("CreateLink",false,url);}
else{window.alert('Select the text that will be linked first');}}
function RichTextInsertLinkDirect(el)
{var permalink=el.options[el.selectedIndex].getAttribute("value");if(!empty(permalink)){RichTextInsertFileDirect(orbx_site_url+'/?'+__orbicon_ln+'='+encodeURIComponent(permalink));}}
var full_screen=false;var fs_old_props=new Array();function RichTextFullScreen()
{var frame=$("rte_container");var html=window.document.getElementsByTagName("html")[0];if(!full_screen){fs_old_props['old_height']=__get_element_height("rte_container");fs_old_props['old_width']=__get_element_width("rte_container");fs_old_props['old_height_iframe']=__get_element_height(sToolbarID);html.style.overflow="hidden";frame.style.backgroundColor="#ffffff";frame.style.position="absolute";frame.style.width=html.clientWidth+"px";frame.style.height=html.clientHeight+"px";frame.style.display="block";frame.style.zIndex="999";if(document.all){frame.style.top='180px';}
else{frame.style.top="0px";}
frame.style.left="0px";$(sToolbarID).style.height=(html.clientHeight-150)+'px';$('category').style.visibility='hidden';RichTextFocus();full_screen=true;}
else{frame.style.position="static";frame.style.width=fs_old_props['old_width']+"px";frame.style.height=fs_old_props['old_height']+"px";$(sToolbarID).style.height=fs_old_props['old_height_iframe']+'px';html.style.overflow="auto";$('category').style.visibility='visible';RichTextFocus();full_screen=false;}}