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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>503 Service Unavailable</title>
<style type="text/css">/*<![CDATA[*/
    body { color: #000000; background-color: #ffffff; font-family: "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif; }
    a:link { color: #0000cc; }
    address { font-size: smaller; }
	.sq { font-weight:bold; color:#cc3300; }
/*]]>*/</style>
</head>

<body>
<h1>Welcome to <?php echo ORBX_FULL_NAME; ?></h1>
<p class="sq"><?php echo ORBX_FULL_NAME; ?> is not installed.</p>
<p>Please follow the link below to install <?php echo ORBX_FULL_NAME; ?></p>
<p><a href="<?php echo ORBX_SITE_URL; ?>/?en=orbicon.setup"><?php echo ORBX_FULL_NAME; ?> installation</a></p>
<address>
    <a href="<?php echo ORBX_SITE_URL; ?>/"><?php echo DOMAIN; ?></a><br />
    <?php echo date('r'); ?><br />
</address>
</body>
</html>