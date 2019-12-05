<?php

if(!get_is_admin()) {
	return null;
}

$url = ORBX_SITE_URL;
$exit = _L('exit');
$domain_name = DOMAIN_NAME;
$lng = $orbicon_x->ptr;

return <<<TXT
<!-- admin menu -->
<div id="orbx_header">
<div id="orbx_logo"><img src="{$url}/orbicon/gfx/orbx-logo.gif" alt="System2" title="System2" /></div>
<div>
<ul id="orbx_nav-main" >
	<li class="home" style="list-style: none;"><a title="{$domain_name}" href="{$url}/?{$lng}=orbicon"><img alt="Enter" title="Enter" src="{$url}/orbicon/gfx/enter-orbicon.png" /></a></li>
	<li id="comm_li" class="comm" style="list-style: none;"><a href="javascript: void(null);" onclick="javascript: __unload();"><img alt="Exit" title="Exit" src="{$url}/orbicon/gfx/icon-exit.png" /> {$exit}</a></li>
</ul>
</div></div>
<!-- admin menu -->
TXT;

?>