<?php

// * requirements
$url_site = ORBX_SITE_URL;
$lang = $orbicon_x->ptr;
$orbx_build = ORBX_BUILD;
global $dbc;

$show_navigation = true;
$public_page = false;

include_once DOC_ROOT . '/orbicon/modules/peoplering/class/class.peoplering.php';
$pr = new Peoplering($_SESSION['user.r']);

include_once DOC_ROOT . '/orbicon/modules/peoplering/class/class.promo.php';
$promo = new Promo;

if(get_is_member() && !$_SESSION['user.r']['username']) {
	$_SESSION['user.r']['username'] = $pr->get_username($_SESSION['user.r']['id']);
	$_SESSION['user.r']['username'] = $_SESSION['user.r']['username']['username'];
}

$title_username = ($_SESSION['user.r']['contact_name'] != '') ? $_SESSION['user.r']['contact_name'] . ' ' . $_SESSION['user.r']['contact_surname'] : $_SESSION['user.r']['username'];
$title_username = _L('user') . ': ' . $title_username;

$active = array();

switch($_GET['sp']) {


	case 'profile': 	$include = DOC_ROOT . '/orbicon/modules/peoplering/forms/form.profile.php';
						$orbicon_x->set_page_title($title_username .' - ' . _L('pr-my-profile'));
						$orbicon_x->add2breadcrumbs($title_username .' - ' . _L('pr-my-profile'));
						$active['profile'] = true;
						break;
	case 'cv':			$include = DOC_ROOT . '/orbicon/modules/peoplering/forms/form.cv.php';
						$orbicon_x->set_page_title($title_username .' - ' . _L('pr-cv'));
						$orbicon_x->add2breadcrumbs($title_username .' - ' . _L('pr-cv'));
						break;
	case 'company':		$include = DOC_ROOT . '/orbicon/modules/peoplering/forms/form.company.php';
						$orbicon_x->set_page_title($title_username .' - ' . _L('pr-comp-info'));
						$orbicon_x->add2breadcrumbs($title_username .' - ' . _L('pr-comp-info'));
						$active['company'] = true;
						break;
	case 'mail':		$include = DOC_ROOT . '/orbicon/modules/peoplering/forms/form.mail.php';
						$orbicon_x->set_page_title($title_username .' - ' . _L('pr-mail'));
						$orbicon_x->add2breadcrumbs($title_username .' - ' . _L('pr-mail'));
						break;
	case 'read':		$include = DOC_ROOT . '/orbicon/modules/peoplering/forms/form.mail.read.php';
						break;
	case 'user':		$include = DOC_ROOT . '/orbicon/modules/peoplering/forms/form.user.php';
						$show_navigation = false;
						$public_page = true;
						break;
	case 'friends':		$include = DOC_ROOT . '/orbicon/modules/peoplering/forms/form.friends.php';
						$show_navigation = false;
						$public_page = true;
						$orbicon_x->set_page_title(htmlspecialchars($_GET['user']) .' - ' . _L('pr-friends'));
						$orbicon_x->add2breadcrumbs(htmlspecialchars($_GET['user']) .' - ' . _L('pr-friends'));
						break;
	case 'search':		$include = DOC_ROOT . '/orbicon/modules/peoplering/forms/form.search.php';
						$show_navigation = false;
						$public_page = true;
						break;
	case 'help':		$include = DOC_ROOT . '/orbicon/modules/peoplering/forms/form.help.php';
						$show_navigation = false;
						$public_page = true;
						break;
	case 'companies':	$include = DOC_ROOT . '/orbicon/modules/peoplering/forms/form.companies.php';
						$show_navigation = false;
						$public_page = true;
						break;
	case 'gallery':
						$include = DOC_ROOT . '/orbicon/modules/peoplering/forms/form.gallery.php';
						$public_page = true;
						$show_navigation = false;
						break;
	case 'company_details':
						$include = DOC_ROOT . '/orbicon/modules/peoplering/public/public.company_details.php';
						$show_navigation = false;
						$public_page = true;
						break;
	case 'credits':
						$include = DOC_ROOT . '/orbicon/modules/peoplering/forms/form.credits.php';
						$orbicon_x->set_page_title($title_username .' - ' . _L('e.useraccount'));
						$orbicon_x->add2breadcrumbs($title_username .' - ' . _L('e.useraccount'));
						break;
	case 'inpulls.survey':
						$include = DOC_ROOT . '/orbicon/modules/inpulls/form.inpulls.php';
						break;
	case 'hpb.forms':
						$include = DOC_ROOT . '/orbicon/modules/hpb.form/form.pring.php';
						$active['hpb'] = true;
						$orbicon_x->set_page_title($title_username .' - Moji obrasci' );
						$orbicon_x->add2breadcrumbs($title_username .' - Moji obrasci');
						break;
	default: 			$include = DOC_ROOT . '/orbicon/modules/peoplering/forms/form.home.php';
						$orbicon_x->set_page_title($title_username);
						$orbicon_x->add2breadcrumbs($title_username);
						$orbicon_x->add_feed_link(ORBX_SITE_URL.'/orbicon/modules/peoplering/rss.mbox.php?mbox='.sha1(md5(pow($_SESSION['user.r']['id'], 5) * 999123123.999)), _L('pr-inbox').'@'.DOMAIN_NAME);
						break;

}

if(!get_is_member() && !$public_page) {

	if($orbicon_x->get_page_title()) {
		$orbicon_x->set_page_title(_L('login'));
		$orbicon_x->add2breadcrumbs(_L('login'));
	}

	global $orbx_mod;
	if($orbx_mod->validate_module('inpulls')) {
		redirect(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.inpulls.reg');
	}

	// add function for login screen
	include_once DOC_ROOT . '/orbicon/class/inc.column.php';
	return get_login_form();
}

include_once $include;

if($show_navigation && get_is_member()) {
		$navigation = '<ul id="pr_menu">
		<li class="pr_menu_home"><a href="'.$url_site.'/?'.$lang.'=mod.peoplering" title="'._L('pr-home').'">'._L('pr-home').'</a></li>
		<li class="pr_menu_profile '.($active['profile'] ? 'active' : '').'"><a href="'.$url_site.'/?'.$lang.'=mod.peoplering&amp;sp=profile" title="'._L('pr-my-profile').'">'._L('pr-my-profile').'</a></li>
		<li class="pr_menu_cv"><a href="'.$url_site.'/?'.$lang.'=mod.peoplering&amp;sp=cv" title="'._L('pr-cv').'">'._L('pr-cv').'</a></li>
		<li class="pr_menu_mail"><a href="'.$url_site.'/?'.$lang.'=mod.peoplering&amp;sp=mail" title="'._L('pr-mail').'">'._L('pr-mail').'</a></li>';

		global $orbx_mod;
		if($orbx_mod->validate_module('estate')) {
			$navigation .= '
			<li class="pr_menu_estate_new"><a href="'.$url_site.'/?'.$lang.'=mod.estate.new&amp;page=add">'._L('e.newad').'</a></li>
			<li class="pr_menu_estate_all"><a href="'.$url_site.'/?'.$lang.'=mod.estate.new">'._L('e.allads').'</a></li>
			<li class="pr_menu_estate_credits"><a href="'.$url_site.'/?'.$lang.'=mod.peoplering&amp;sp=credits">'._L('e.useraccount').'</a></li>';
		}

		global $orbx_mod;
		if($orbx_mod->validate_module('hpb.form')) {

			if($_SESSION['user.r']['bank_status'] == 'posl') {
				$navigation .= '<li class="pr_menu_comp '.($active['company'] ? 'active' : '').'"><a href="'.$url_site.'/?'.$lang.'=mod.peoplering&amp;sp=company" title="'._L('pr-comp-info').'">'._L('pr-comp-info').'</a></li>';
}

			$navigation .= '<li class="pr_menu_hpb '.($active['hpb'] ? 'active' : '').'"><a href="'.$url_site.'/?'.$lang.'=mod.peoplering&amp;sp=hpb.forms">Moji obrasci</a></li>';
		}

		if($orbx_mod->validate_module('inpulls')) {
			$navigation .= '<li class="pr_menu_inpulls"><a href="'.$url_site.'/?'.$lang.'=mod.peoplering&amp;sp=inpulls.survey" title="inpulls.upitnik">Inpulls upitnik</a></li>
			<li class="pr_menu_gallery"><a href="'.$url_site.'/?'.$lang.'=mod.peoplering&amp;sp=gallery&amp;user='.$_SESSION['user.r']['username'].'" title="'._L('pr-gall').'">Fotoalbum</a></li>';
		}

		$navigation.= '<li class="pr_menu_exit"><a href="javascript:;" onclick="__unload();" title="'._L('pr-exit').'">'._L('pr-exit').'</a></li>
	</ul>';
}
else {
	$navigation = '';
}

return <<<PEOPLERING
<script type="text/javascript" src="{$url_site}/orbicon/modules/peoplering/library/functions.js?{$orbx_build}"></script>
<div id="peoplering">
{$navigation}
	<div class="cleaner"></div>
	<div id="pr_content_display">{$display_content}</div>
</div>
<div class="cleaner"></div>
PEOPLERING;

?>