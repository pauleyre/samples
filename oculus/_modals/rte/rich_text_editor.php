<?php

	(object) $oRTE = new Core;
	echo $oRTE -> GetXMLTag();

?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!-- saved from url=(0013)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<?= $oRTE -> DisplayCSSTags(); ?>
<link href="../graphics/css/rich_text_editor.css" rel="stylesheet" type="text/css" media="all" />
<script src="../javascript/common.js" type="text/javascript"></script>
<script src="../javascript/rich_text_editor.js" type="text/javascript"></script>
<?= $oRTE -> RichTextContextResources(); ?>
<title><?= $oRTE -> GetShortName(); ?> [Level: Rich-Text Editor]</title>
</head>

<body onload="javascript: RichTextOnLoad(); ContextMenuLoad();">
<?= $oRTE -> RichTextContextMenu(); ?>
<?php require("../components/rich_text_editor/toolbar.php"); ?>
</body>
</html>