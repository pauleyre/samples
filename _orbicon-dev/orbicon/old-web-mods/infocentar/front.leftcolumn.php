<?php

$newuser = include_once DOC_ROOT.'/orbicon/modules/infocentar-newusers/render.frontpage.php';
$topuser = include_once DOC_ROOT.'/orbicon/modules/infocentar-topusers/render.frontpage.php';
$daily_ad = include_once DOC_ROOT.'/orbicon/modules/infocentar-dailyad/render.frontpage.php';
$daily_stat = include_once DOC_ROOT.'/orbicon/modules/infocentar-dailystat/render.frontpage.php';
$tod = include_once DOC_ROOT.'/orbicon/modules/infocentar-tod/render.frontpage.php';



$leftcol = '
<div id="ic_leftcol">
	<div id="ic_news">
		<h3>'._L('ic-news').'</h3>
		<!>ORBX_IC_NEWS_LIST
	</div>
	<div id="ic_newusers">
		<h3>'._L('ic-new-members').'</h3>
		<div class="right_col">
			<a href="javascript: void(0);">'._L('ic-all-members').'</a>
		</div>
		<div class="cleaner"></div>
		'.$newuser.'
	</div>
	<div id="ic_topusers">
		<h3>'._L('ic-top-members').'</h3>
		<div class="right_col">
			<a href="javascript: void(0);">'._L('ic-top-list').'</a>
		</div>
		<div class="cleaner"></div>
		'.$topuser.'
	</div>
	<div id="ic_tod">
		<h3>'._L('ic-tod').'</h3>
		'.$tod.'
	</div>
	<div id="ic_daily_stats">
		<h3>'._L('ic-daily-stat').'</h3>
		'.$daily_stat.'
	</div>
	<div id="ic_daily_ad">
		<h3>'._L('ic-daily-ad').'</h3>
		'.$daily_ad.'
	</div>
</div>
';


?>