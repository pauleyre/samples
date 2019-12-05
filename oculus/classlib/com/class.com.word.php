<?php

// NOTE: Using COM with windows NT/2000/XP with apache as a service
// - Run dcomcnfg.exe
// - Find word application and click properties
// - Click the Security tab
// - Use Custom Access Permissions
// - Add the user who runs the web server service
// - Use Custom Launch permissions
// - Add the user who runs the web server service

define("MSWORD_DOC_TYPE_DOC", 0);
define("MSWORD_DOC_TYPE_TEMPLATE", 1);
define("MSWORD_DOC_TYPE_TEXT", 2);
define("MSWORD_DOC_TYPE_TEXT_LB", 3);
define("MSWORD_DOC_TYPE_DOS_TEXT", 4);
define("MSWORD_DOC_TYPE_DOS_TEXT_LB", 5);
define("MSWORD_DOC_TYPE_RTF", 6);
define("MSWORD_DOC_TYPE_UNICODE_TEXT", 7);
define("MSWORD_DOC_TYPE_HTML", 8);

class MSWord
{
	var $_rWordHandle;

	// Create COM instance to word
	function MSWord_Start($bVisible = FALSE)
	{
		$this -> _rWordHandle = new COM("word.application") or $this -> AddError(__FUNCTION__." : Unable to instanciate Word");
		$this -> _rWordHandle -> Visible = $bVisible;
	}

	// Open existing document
	function MSWord_Open($sFilename) {
		$this -> _rWordHandle -> Documents -> Open($sFilename);
	}

	// Create new document
	function MSWord_NewDocument() {
		$this -> _rWordHandle -> Documents -> Add();
	}

	// Write text to active document
	function MSWord_WriteText($sText) {
		$this -> _rWordHandle -> Selection -> Typetext($sText);
	}

	// Number of documents open
	function MSWord_DocumentCount() {
		return $this -> _rWordHandle -> Documents -> Count;
	}

	// Save document as another file and/or format
	function MSWord_SaveAs($sFilename, $nFormat = MSWORD_DOC_TYPE_DOC) {
		$this -> _rWordHandle -> ActiveDocument -> SaveAs($sFilename, $nFormat);
	}

	// Save active document
	function MSWord_Save() {
		$this -> _rWordHandle -> ActiveDocument -> Save();
	}

	// close active document.
	function MSWord_Close() {
		$this -> _rWordHandle -> ActiveDocument -> Close();
	}

	// Get word version
	function MSWord_GetVersion() {
		return $this -> _rWordHandle -> Version;
	}

	// get handle to word
	function MSWord_GetHandle() {
		return $this -> _rWordHandle;
	}

	// Clean up instance with word
	function MSWord_Quit()
	{
		if($this -> _rWordHandle)
		{
			// close word
			$this -> _rWordHandle -> Quit();
			$this -> _rWordHandle -> Release();

			// free the object
			$this -> _rWordHandle = NULL;
		}
	}
}

/* Example 1, opens an html file, writes text to it, then saves it as a document:
$input = "C:\\test.htm";
$output = "C:\\test.doc";

$Word = new MSWord;
$Word -> Open($input);
$Word -> WriteText("This is a test ");
$Word -> SaveAs($output);
$Word -> Quit();
*/

/* Example 2, opens an html file, then saves it as a rtf file: */
/*$input = "C:\\test.doc";
$output = "C:\\test.html";

$Word = new MSWord;
$Word -> MSWord_Open($input);
$Word -> MSWord_SaveAs($output, MSWORD_DOC_TYPE_TEXT);
$Word -> MSWord_Close();
$Word -> MSWord_Quit();*/
?>