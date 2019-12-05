<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/DTD/strict.dtd">
<html lang="en">
<head>
	<title>Tutorial - <?php echo ORBX_FULL_NAME; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="<?php echo ORBX_SITE_URL; ?>/orbicon/controler/intro/css/ajax-s.css" type="text/css">
	<script type="text/javascript">

	var strXMLURL = 			"<?php echo ORBX_SITE_URL; ?>/orbicon/controler/intro/xml/ajax-s-html.xml";
	var strPagingXSLTURL = 		"<?php echo ORBX_SITE_URL; ?>/orbicon/controler/intro/xslt/ajax-s-paging-html.xml";
	var strXSLTURL = 			"<?php echo ORBX_SITE_URL; ?>/orbicon/controler/intro/xslt/ajax-s-html.xml";

	</script>
	<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/controler/intro/js/ajax-s.js?<?php ORBX_BUILD; ?>"></script>
</head>

<body>
<div style="float:right; padding-right: 1em;"><a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon">Skip tutorial &raquo;</a></div>
	<div id="container">
		<noscript>
			<a href="xml/ajax-s-html.xml">Go to the printable version.</a>
		</noscript>
		<div id="header">
			<h1>Tutorial - <?php echo ORBX_FULL_NAME; ?></h1>
		</div>

		<div id="main">
			<div id="main-content"></div>
		</div>

		<div id="footer">
			<div id="navigation"></div>
		</div>
	</div>

</body>
</html>