<?php
	global $dbc;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<!>ORBX_LN" xml:lang="<!>ORBX_LN" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>503 Service Unavailable</title>
<style type="text/css">
/*<![CDATA[*/
    body { color: #000000; background-color: #ffffff; font-family: "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif; }
    a:link { color: #0000cc; }
    address { font-size: smaller; }
	.sq { font-weight:bold; color:#cc3300; }
/*]]>*/
</style>
</head>

<body>
<h1>503 Service Unavailable</h1>
<p class="sq">The system is up and running but no connection to the database could be established. Error number <?php echo intval($dbc->_db->errno()); ?></p>
<p>Please verify your system and network configuration.</p>
</body>
</html>