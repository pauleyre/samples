<?php
/**
 * Ban page
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-07-01
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $orbicon_x->ptr; ?>" xml:lang="<?php echo $orbicon_x->ptr; ?>" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>403 Forbidden</title>
<style type="text/css">/*<![CDATA[*/
    body { color: #000000; background-color: #ffffff; font-family: "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif; }
    a:link { color: #0000cc; }
    address { font-size: smaller; }
	.sq { font-weight:bold; color:#cc3300; }
/*]]>*/</style>
</head>

<body>
<h1>403 Forbidden</h1>
<p class="sq"><?php echo _L('ban_flood_warning'); ?></p>
<p><?php echo _L('ban_flood_restore'); ?></p>
<p><?php echo _L('ban_flood_apologize'); ?></p>
<address>
    <a href="<?php echo ORBX_SITE_URL; ?>/"><?php echo DOMAIN; ?></a><br />
    <?php echo date('r'); ?><br />
</address>
<a accesskey="X" href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/authorize"></a>
</body>
</html>