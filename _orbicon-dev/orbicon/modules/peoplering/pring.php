<link rel="stylesheet" type="text/css" href="<?php echo ORBX_SITE_URL; ?>/orbicon/modules/peoplering/gfx/backend.css?<?php echo ORBX_BUILD; ?>" />
<script type="text/javascript"><!-- // --><![CDATA[
	var __orbicon_server_name = '<?php echo $_SERVER['SERVER_NAME']; ?>';
// ]]></script>
<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/controler/gzip.server.php?file=/orbicon/rte/rte.final.js&amp;<?php echo ORBX_BUILD; ?>"></script>

<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/modules/peoplering/library/functions.js?<?php echo ORBX_BUILD; ?>"></script>
<?php

// * requirements
include_once DOC_ROOT . '/orbicon/modules/peoplering/class/class.peoplering.php';
include_once DOC_ROOT . '/orbicon/modules/peoplering/class/class.promo.php';

// * global object initialization
$pr = new Peoplering;

// * make some database object global
global $dbx;
global $orbicon_x;
$lang = $orbicon_x->ptr;

?>
<div id="pr_holder">

<?php

if(isset($_GET['id'])) {

	echo '
	<p id="pr_admin_menu">
		<a href="'.ORBX_SITE_URL.'/?'.$lang.'=orbicon/mod/peoplering">'._L('pr-home').'</a>
		<a href="'.ORBX_SITE_URL.'/?'.$lang.'=orbicon/mod/peoplering&amp;sp=profile&amp;id='.$_GET['id'].'">'._L('pr-profile').'</a>
		<a href="'.ORBX_SITE_URL.'/?'.$lang.'=orbicon/mod/peoplering&amp;sp=cv&amp;id='.$_GET['id'].'">'._L('pr-cv').'</a>
		<a href="'.ORBX_SITE_URL.'/?'.$lang.'=orbicon/mod/peoplering&amp;sp=company&amp;id='.$_GET['id'].'">'._L('pr-comp-info').'</a>
		<a href="'.ORBX_SITE_URL.'/?'.$lang.'=orbicon/mod/peoplering&amp;sp=extra&amp;id='.$_GET['id'].'">'._L('pr-promo').'</a>
		<a href="./?'.$lang.'=orbicon/mod/peoplering&amp;sp=stats&amp;id='.$_GET['id'].'">'._L('stats').'</a>
	</p>';

}

	switch($_GET['sp']) {

		case 'search':	include_once DOC_ROOT.'/orbicon/modules/peoplering/tab.search.php';
						break;

		case 'profile':	include_once DOC_ROOT.'/orbicon/modules/peoplering/tab.profile.php';
						break;

		case 'company':	include_once DOC_ROOT.'/orbicon/modules/peoplering/tab.company.php';
						break;

		case 'cv':		include_once DOC_ROOT.'/orbicon/modules/peoplering/tab.cv.php';
						break;

		case 'extra':	include_once DOC_ROOT.'/orbicon/modules/peoplering/tab.extra.php';
						break;

		case 'promo':	include_once DOC_ROOT.'/orbicon/modules/peoplering/forms/form.promo.php';
						break;

		case 'stats':	include_once DOC_ROOT.'/orbicon/modules/peoplering/forms/form.userstats.php';
						break;

		default:		include_once DOC_ROOT.'/orbicon/modules/peoplering/tab.search.php';
						break;

	}

?>
</div>