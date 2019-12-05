<link rel="stylesheet" type="text/css" href="<?php echo ORBX_SITE_URL; ?>/orbicon/modules/infocentar/gfx/backend.css" media="screen" />
<script type="text/javascript"><!-- // --><![CDATA[
	var __orbicon_server_name = '<?php echo $_SERVER['SERVER_NAME']; ?>';
// ]]></script>
<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte.final.js"></script>
<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/modules/infocentar/library/functions.js"></script>
<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/3rdParty/yui/build/utilities/utilities.js"></script>
<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/3rdParty/scriptaculous/lib/prototype.js"></script>
<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/3rdParty/scriptaculous/src/scriptaculous.js"></script>

<div id="infocentar_holder">
<ul id="toolbar">
	<li><a href="?<?php echo $orbicon_x->ptr;?>=orbicon/mod/infocentar&amp;goto=question"><?php echo _L('ic-questions');?></a></li>
	<li><a href="?<?php echo $orbicon_x->ptr;?>=orbicon/mod/infocentar&amp;goto=category"><?php echo _L('ic-category-title');?></a></li>
	<li><a href="?<?php echo $orbicon_x->ptr;?>=orbicon/mod/infocentar&amp;goto=log"><?php echo _L('ic-log');?></a></li>
	<li><a href="?<?php echo $orbicon_x->ptr;?>=orbicon/mod/infocentar&amp;goto=tags"><?php echo _L('ic-tags');?></a></li>
	<?php
		if(get_is_admin()) {
	?>
	<li><a href="?<?php echo $orbicon_x->ptr;?>=orbicon/mod/infocentar&amp;goto=settings"><?php echo _L('ic-settings');?></a></li>
	<?php
		}
	?>
</ul>
<?php

	$url = ORBX_SITE_URL;
	$lang = $orbicon_x->ptr;

	// * requirements
	include_once DOC_ROOT . '/orbicon/modules/infocentar/class/icsettings.class.php';
	require_once DOC_ROOT.'/orbicon/modules/infocentar/class/tag.class.php';
	require_once DOC_ROOT.'/orbicon/modules/infocentar/class/category.class.php';

	// * update settings
	if(isset($_POST['uper_submit']) || isset($_POST['bottom_submit'])){
		$update = new ICSettings($_POST);
	}


	// reorder
	if(isset($_POST['reorderColumns'])){
		parse_str($_POST['reorderColumns'], $arr);
		var_dump($arr);
		$catObj = new Category;
		$catObj->updateSortNum($arr['ic_cat_listing']);
	}


	// * load infocentar settings
	$icsettings = new ICSettings;
	$icsetting = $icsettings->_get_settings();

	switch($_GET['goto']){

		case 'question':	include DOC_ROOT.'/orbicon/modules/infocentar/admin.content.php';
							break;
		case 'log':			include DOC_ROOT.'/orbicon/modules/infocentar/admin.stats.php';
							break;
		case 'category':	include DOC_ROOT.'/orbicon/modules/infocentar/admin.category.php';
							break;
		case 'tags':		include DOC_ROOT.'/orbicon/modules/infocentar/form/form.tag.php';
							break;
		case 'settings':	include DOC_ROOT.'/orbicon/modules/infocentar/admin.settings.php';
							break;
		default:			include DOC_ROOT.'/orbicon/modules/infocentar/admin.content.php';
							break;

	}

?>
</div>
<div class="cleaner"></div>