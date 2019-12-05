<?php

echo '
	<link rel="stylesheet" href="'.ORBX_SITE_URL.'/orbicon/modules/invest/gfx/backend.css?'.ORBX_BUILD.'" type="text/css" media="screen" />
	<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/controler/gzip.server.php?file=/orbicon/3rdParty/yui/build/calendar/calendar-min.js&amp;'.ORBX_BUILD.'"></script>
	<link rel="stylesheet" type="text/css" href="'.ORBX_SITE_URL.'/orbicon/controler/gzip.server.php?file=/orbicon/3rdParty/yui/build/calendar/assets/calendar.css&amp;'.ORBX_BUILD.'" />
	<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/modules/invest/yui.ext.adminDate.js?'.ORBX_BUILD.'"></script>
<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/controler/gzip.server.php?file=/orbicon/3rdParty/scriptaculous/lib/prototype.js&amp;'.ORBX_BUILD.'"></script>
<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/3rdParty/scriptaculous/src/scriptaculous.js?'.ORBX_BUILD.'"></script>';

echo '<div id="invest_mod_back">';

/**
 *	In this part, administration, user can add a new
 *	stock value, to review history of all entries,
 * 	and to import/export them.
 **/

// * requirements
include_once DOC_ROOT.'/orbicon/modules/invest/class/stock.class.php';
include_once DOC_ROOT.'/orbicon/modules/invest/class/fond.class.php';
include_once DOC_ROOT.'/orbicon/modules/invest/class/currency.class.php';


global $dbc;

// reorder
if(isset($_POST['reorderColumns'])){

	// initiate temp object
	$tmpFond = new Fond;

	parse_str($_POST['reorderColumns'], $arr);

	$tmpFond = new Fond;
	$tmpFond->update_sorting($arr['fond_list']);
}

echo '
<ul id="toolbar">
	<li>
		<a href="?'.$orbicon_x->ptr.'=orbicon/mod/invest">
			'._L('invest-admin-home').'</a>
	</li>
	<li>
		<a href="?'.$orbicon_x->ptr.'=orbicon/mod/invest&amp;showPage=fond&amp;do=fondvalue">
			'._L('invest-admin-value').'</a>
	</li>
	<li>
		<a href="?'.$orbicon_x->ptr.'=orbicon/mod/invest&amp;showPage=fond&amp;do=newfond">
			'._L('invest-admin-fond').'</a>
	</li>
	<li>
		<a href="?'.$orbicon_x->ptr.'=orbicon/mod/invest&amp;showPage=settings">
			'._L('invest-admin-settings').'</a>
	</li>
</ul>
';

switch($_GET['showPage']){

	case 'fond':		include DOC_ROOT.'/orbicon/modules/invest/admin.fond.php';
						break;
	case 'settings':	include DOC_ROOT.'/orbicon/modules/invest/forms/form.enviroment.php';
						break;
	default: 			include DOC_ROOT.'/orbicon/modules/invest/admin.fond.php';
						break;
}


echo '</div>
	<div class="cleaner"></div>
';
?>