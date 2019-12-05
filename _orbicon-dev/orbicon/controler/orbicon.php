<?php
/**
 * Backend main index file
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb (ZG), CROATIA, www.orbitum.net, info@orbitum.net
 * @package OrbiconFE
 * @version 1.00
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-07-01
 */

	unset($orbicon_x);

	require_once DOC_ROOT . '/orbicon/class/class.orbicon.admin.php';
	global $orbicon_x, $orbx_mod;
	$orbicon_x = new OrbiconX_Administration;

	$client_request = explode('/', $_GET[$orbicon_x->ptr]);
	$request = trim($client_request[1]);
	$mod_request = trim($client_request[2]);

	// login first
	if($request == 'authorize') {
		require DOC_ROOT . '/orbicon/controler/authorize.php';
		exit();
	}

	$orbicon_x->update_last_location($_SESSION['user.a']['id'], http_build_query($_GET));

	// ajax requested content from magister db
	if(isset($_REQUEST['ajax_text_db'])) {
		include_once DOC_ROOT.'/orbicon/magister/class.magister.php';
		$magister = new Magister;
		if($_REQUEST['action'] == 'txt') {
			$magister->print_magister_ajax_text();
		}
		else if($_REQUEST['action'] == 'add_category') {
			$magister->add_new_category($_POST['new_magister_category']);
			echo $magister->get_categories();
		}
		else if($_REQUEST['action'] == 'card_edit') {
			$magister->save_card();
		}
		else if($_REQUEST['action'] == 'hidden_flag') {
			echo $magister->set_hidden_flag();
		}

		return true;
	}

	// ajax requested content from venus db
	if(isset($_REQUEST['ajax_img_db'])) {
		include_once DOC_ROOT . '/orbicon/venus/class.venus.php';
		$venus = new Venus;

		if($_REQUEST['action'] == 'img') {
			$venus->print_venus_ajax_image();
		}
		else if($_REQUEST['action'] == 'add_category') {
			$venus->add_new_category($_POST['new_venus_category']);
			echo $venus->get_categories();
		}

		return true;
	}

	// ajax requested content from mercury db
	if(isset($_REQUEST['ajax_data_db'])) {
		include_once DOC_ROOT.'/orbicon/mercury/class.mercury.php';
		$mercury = new Mercury;

		if($_REQUEST['action'] == 'add_category') {
			$mercury->add_new_category($_REQUEST['new_mercury_category']);
			echo $mercury->get_categories();
		}

		return true;
	}

	// oops!
	if(!get_is_admin()) {
		$_SESSION['cache_status'] = 403;
		session_write_close();
		header('HTTP/1.1 403 Forbidden', true);
		include_once DOC_ROOT . '/orbicon/controler/forbidden.php';
		exit();
	}

	$inc = false;

	$uri_no_www = str_replace('www.', '', ORBX_SITE_URL);
	if(ORBX_INTEGRITY_URI != $uri_no_www) {
		$orbx_log->dwrite('identified new main site URI', __LINE__, __FUNCTION__);
		$orbicon_x->rewrite_db_uris();
	}
	unset($uri_no_www);

	// load info
	require_once DOC_ROOT . '/orbicon/class/class.version.php';
	$orbicon_info = new Version;

	// css rule for selected tab
	if(empty($request)) {
		$links['css']['home'] = 'class="current"';
	}
	else {
		$links['css'][$request] = 'class="current"';
	}

	$links['main']['home'] = '<a id="orbx_desktop_target" onmouseover="javascript:_orbx_hide_submenu();" onclick="javascript:sh_ind();" '.$links['css']['home'].' href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/icon-home.png" /> '._L('homepage').'</a>';
	$links['main']['content'] = '<a onmouseover="javascript: try {_orbx_show_submenu(this, \'sub_content\');} catch(e){}" '.$links['css']['content'].' href="javascript:void(null);"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/icon-content.png" /> '._L('content').'</a>';
	$links['main']['db'] = '<a onmouseover="javascript: try {_orbx_show_submenu(this, \'sub_db\');} catch(e){}" '.$links['css']['db'].' href="javascript:void(null);"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/icon-database.png" /> '._L('db').'</a>';
	$links['main']['dynamic'] = '<a onmouseover="javascript: try {_orbx_show_submenu(this, \'sub_dynamic\');} catch(e){}" '.$links['css']['dynamic'].' href="javascript:void(null);"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/icon-dynamic-elements.png" /> '._L('dynamic').'</a>';
	$links['main']['tools'] = '<a onmouseover="javascript: try {_orbx_show_submenu(this, \'sub_tools\');} catch(e){}" '.$links['css']['tools'].' href="javascript:void(null);"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/icon-tools.png" /> '._L('tools').'</a>';
	$links['main']['crm'] = '<a onmouseover="javascript: try {_orbx_show_submenu(this, \'sub_crm\');} catch(e){}" '.$links['css']['crm'].' href="javascript:void(null);"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/icon-crm.png" /> '._L('crm').'</a>';
	$links['main']['settings'] = '<a onmouseover="javascript: try {_orbx_show_submenu(this, \'sub_settings\');} catch(e){}" '.$links['css']['settings'].' href="javascript:void(null);"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/icon-options.png" /> '._L('settings').'</a>';
	$links['main']['system'] = '<a onmouseover="javascript: try {_orbx_show_submenu(this, \'sub_system\');} catch(e){}" '.$links['css']['system'].' href="javascript:void(null);"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/icon-system.png" /> '._L('system').'</a>';
	$links['main']['helpcenter'] = '<a onmouseover="javascript:try {_orbx_show_submenu(this, \'sub_helpcenter\');} catch(e){}" onclick="javascript:sh_ind();" '.$links['css']['helpcenter'].' href="javascript:void(null);"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/icon-help.png" /> '._L('helpcenter').'</a>';
	$links['main']['languages'] = '<a onmouseover="javascript:try {_orbx_show_submenu(this, \'sub_languages\');} catch(e){}" onclick="javascript:sh_ind();" '.$links['css']['languages'].' href="javascript:void(null);"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/icon-language.png" /> '._L('languages').'</a>';
	$links['main']['exit'] = '<a onmouseover="javascript:_orbx_hide_submenu();" href="javascript: void(null);" onclick="javascript: __unload();"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/icon-exit.png" alt="'._L('exit').'" title="'._L('exit').'" /> '._L('exit').'</a>';

	$_access = $orbicon_x->get_can_access_section($request, $mod_request);

	if(!$_access) {
		$_SESSION['cache_status'] = 403;
		session_write_close();
		header('HTTP/1.1 403 Forbidden', true);
		echo '<h1>403 Forbidden</h1>';
		exit();
	}

	switch($request) {
		// CONTENT
		case 'columns':

			$links['main']['content'] = '<a onmouseover="javascript: try {_orbx_show_submenu(this, \'sub_content\');} catch(e){}" class="current" href="javascript:void(null);"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/icon-content.png" /> ' . _L('content') . '</a>';

			if($request == 'columns') {
				$inc = true;
				$title = _L('columns');

				if(isset($_GET['edit'])) {
					$content = DOC_ROOT.'/orbicon/controler/admin.column.php';
				}
				else {
					$content = DOC_ROOT.'/orbicon/controler/admin.columns.php';
				}
			}
		break;

		// DATABASE
		case 'magister':
		case 'venus':
		case 'mercury':

			$links['main']['db'] = '<a onmouseover="javascript:  try {_orbx_show_submenu(this, \'sub_db\');} catch(e){}" class="current" href="javascript:void(null);"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/icon-database.png" /> '._L('db') . '</a>';

			if($request == 'magister') {
				$inc = true;
				$content = DOC_ROOT.'/orbicon/magister/index.php';
				$title = _L('texts');
			}
			else if($request == 'venus') {
				$inc = true;
				$content = DOC_ROOT.'/orbicon/venus/index.php';
				$title = _L('images');
			}
			else if($request == 'mercury') {
				$inc = true;
				$content = DOC_ROOT.'/orbicon/mercury/index.php';
				$title = _L('data');
			}
		break;

		// TOOLS
		case 'gfxdir':
		case 'zones':

			$links['main']['tools'] = '<a class="current" onmouseover="javascript:  try {_orbx_show_submenu(this, \'sub_tools\');} catch(e){}" '.$links['css']['tools'].' href="javascript:void(null);"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/icon-tools.png" /> '._L('tools').'</a>';

			if($request == 'zones') {
				$inc = true;
				$title = _L('zones');
				$content = DOC_ROOT.'/orbicon/controler/admin.zones.php';
			}
			else if($request == 'gfxdir') {
				$inc = true;
				$content = DOC_ROOT.'/orbicon/controler/admin.gfxdir.php';
				$title = _L('www_folder');
			}
		break;

		// SETTINGS
		case 'editors':
		case 'www':
		case 'privileges':

			$links['main']['settings'] = '<a class="current" href="javascript:void(null);" onmouseover="javascript:  try {_orbx_show_submenu(this, \'sub_settings\');} catch(e){}"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/icon-options.png" /> '._L('settings').'</a>';

			if($request == 'editors') {
				$inc = true;
				$content = DOC_ROOT.'/orbicon/controler/admin.editors.php';
				$title = _L('editors');
			}
			else if($request == 'www') {
				$inc = true;
				$content = DOC_ROOT.'/orbicon/controler/admin.www.php';
				$title = _L('site_info');
			}
			else if($request == 'privileges') {
				$inc = true;
				$content = DOC_ROOT.'/orbicon/controler/admin.privileges.php';
				$title = _L('privileges');
			}
		break;

		// SYSTEM
		case 'advanced':
		case 'update':
		case 'module_info':

			$links['main']['system'] = '<a class="current" onmouseover="javascript:  try {_orbx_show_submenu(this, \'sub_system\');} catch(e){}" '.$links['css']['system'].' href="javascript:void(null);"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/icon-system.png" /> '._L('system').'</a>';

			if($request == 'advanced') {
				$inc = true;
				$content = DOC_ROOT.'/orbicon/controler/admin.advanced.php';
				$title = _L('adv_settings');
			}
			else if($request == 'update') {
				$inc = true;
				$content = DOC_ROOT.'/orbicon/controler/admin.updatecenter.php';
				$title = _L('update_center');
			}
			else if($request == 'module_info') {
				$inc = true;
				$content = DOC_ROOT.'/orbicon/controler/admin.module_info.php';
				$title = _L('modules');
			}

		break;

		// HELP
		case 'helpdesk':
		case 'about':
		case 'intro':

			$links['main']['helpcenter'] = '<a class="current" onmouseover="javascript:  try {_orbx_show_submenu(this, \'sub_helpcenter\');} catch(e){}" '.$links['css']['helpcenter'].' href="javascript:void(null);"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/icon-help.png" /> '._L('helpcenter').'</a>';

			if($request == 'helpdesk') {
				$inc = true;
				$content = DOC_ROOT.'/orbicon/controler/admin.helpdesk.php';
				$title = _L('helpdesk');
			}
			else if($request == 'about') {
				$inc = true;
				$content = DOC_ROOT.'/orbicon/controler/admin.about.php';
				$title = _L('about');
			}
			else if($request == 'intro') {
				require DOC_ROOT . '/orbicon/controler/admin.intro.php';
				exit();
			}
		break;

		// MODULES
		case 'mod':
			$mod_bckend = $orbx_mod->get_module_backend($mod_request);
			$inc = true;
			$title = $mod_bckend['title'];
			$content = $mod_bckend['module_backend_file'];
			unset($mod_bckend);
		break;

		// DESKTOP
		default:
			$title = _L('homepage');
			$content = 'desktop';
			$links['sublinks']['home'] = NULL;
	}

	// sublinks

		// CONTENT

		$links['sublinks']['content'] = '
		<ul class="submenu" id="sub_content">
			<li><a href="' . ORBX_SITE_URL . '/?' . $orbicon_x->ptr.'=orbicon/columns"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/chart_organisation.png" /> '._L('columns').'</a></li>
			'.$orbx_mod->print_menu_icons(ORBX_ACCESS_CONTENT).'
		</ul>';

		// DB
		$links['sublinks']['db'] = '
		<ul class="submenu" id="sub_db">
			<li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/magister"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/page_white_text.png" /> '._L('texts').'</a></li>
			<li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/venus"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/camera.png" /> '._L('images').'</a></li>
			<li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mercury"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/folder_page.png" /> '._L('data').'</a></li>
			'.$orbx_mod->print_menu_icons(ORBX_ACCESS_DB).'
		</ul>';

		// DYNAMIC ELEMENTS

		// it is possible for the whole tab to be removed
		$dyn_items = $orbx_mod->print_menu_icons(ORBX_ACCESS_DYNAMIC);

		$links['sublinks']['dynamic'] = '
		<ul class="submenu" id="sub_dynamic">
			'.$dyn_items.'
		</ul>';

		// TOOLS

		$links['sublinks']['tools'] = '
		<ul class="submenu" id="sub_tools">
			<li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/gfxdir"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/world_edit.png" /> '._L('www_folder').'</a></li>
			<li><a href="' . ORBX_SITE_URL . '/?' . $orbicon_x->ptr.'=orbicon/zones"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/layers.png" /> '._L('zones').'</a></li>
			'.$orbx_mod->print_menu_icons(ORBX_ACCESS_TOOLS).'
		</ul>';

		// CRM

		$crm_items = $orbx_mod->print_menu_icons(ORBX_ACCESS_CRM);

		$links['sublinks']['crm'] = '
		<ul class="submenu" id="sub_crm">'
			.$crm_items.
		'</ul>';

		// SETTINGS
		$links['sublinks']['settings'] = '
		<ul class="submenu" id="sub_settings">
			<li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/www"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/information.png" /> '._L('site_info').'</a></li>
			<li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/editors"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/group_key.png" /> '._L('editors').'</a></li>
			'.$orbx_mod->print_menu_icons(ORBX_ACCESS_SETTINGS).'
		</ul>';

		// HELP
		$links['sublinks']['helpcenter'] = '
		<ul class="submenu" id="sub_helpcenter">
			<li><a href="'.ORBX_SITE_URL.'/orbicon/docs/index.html"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/knowledge-database.png" /> '._L('documentation').'</a></li>
			<li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/helpdesk"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/support.png" /> '._L('helpdesk').'</a></li>
			<li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/about"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/about.png" /> '._L('about').'</a></li>
		</ul>';

		// TRANSLATIONS
		$links['sublinks']['languages'] = '
		<ul class="submenu" id="sub_languages">'.$orbicon_x->__orbicon_get_language_links().'</ul>';

		// SYSTEM

		$links['sublinks']['system'] = '
	<ul class="submenu" id="sub_system">
		<li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/advanced"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/cog_add.png" /> '._L('settings').'</a></li>
		<!-- <li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/update"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/server_go.png" /> '._L('update_center').'</a></li> -->
		<li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/module_info"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/bricks.png" /> '._L('modules').'</a></li>
		'.$orbx_mod->print_menu_icons(ORBX_ACCESS_SYSTEM).'
	</ul>';

?>
<!DOCTYPE html PUBLIC
	"-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="<?php echo $orbicon_x->ptr; ?>" lang="<?php echo $orbicon_x->ptr; ?>">
<head profile="http://www.w3.org/2000/08/w3c-synd/#">
<title><?php echo $title.' - '.ORBX_FULL_NAME . ' (' . ORBX_BUILD . ')' ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo $orbicon_x->get_html_metatags(null); ?>
</head>
<body id="orbx_home">

<style type="text/css">/*<![CDATA[*/
	/* Netscape 4, IE 4.x-5.0/Win and other lesser browsers will use this */
	#update_indicator { position: absolute; right: 0px; bottom: 0px; }
	/* used by Opera 5+, Netscape6+/Mozilla, Konqueror, Safari, OmniWeb 4.5+, iCab, ICEbrowser */
	body > div#update_indicator { position: fixed; }
/*]]>*/</style>

<!--[if gte IE 5.5]>
<![if lt IE 7]>
<style type="text/css">
	div#update_indicator {
		right: auto; bottom: auto;
		left: expression( ( 0 - update_indicator.offsetWidth + ( document.documentElement.clientWidth ? document.documentElement.clientWidth : document.body.clientWidth ) + ( ignoreMe2 = document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft ) ) + 'px' );
		top: expression( ( 0 - update_indicator.offsetHeight + ( document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight ) + ( ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop ) ) + 'px' );
	}
</style>
<![endif]>
<![endif]-->
<?php
	// special phantom code! XP

	$indicator = (isset($_GET['phantom'])) ? 'phantom.gif' : 'indicator.gif';

?>
<div class="h" id="update_indicator" style="background:#fff; border:1px solid #999; width: 16px; height:16px; padding: 4px;">
	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/<?php echo $indicator; ?>" alt="!" title="<?php echo _L('update_prog'); ?>..." />
</div>

<div id="orbx_container">
	<!-- start #header -->
	<div id="orbx_header">
		<div id="orbx_logo">
			<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/orbx-logo.gif" alt="<?php echo ORBX_FULL_NAME; ?>" title="<?php echo ORBX_FULL_NAME; ?>" />
		</div>

		<div id="top_menu_holder">
			<ul id="orbx_nav-main">
				<li><?php echo $links['main']['home']; ?></li>
				<?php
					if($orbicon_x->get_can_access_tab(ORBX_ACCESS_CONTENT)) {
				?>
				<li><?php echo $links['main']['content']; ?></li>
				<?php
					}

					if($orbicon_x->get_can_access_tab(ORBX_ACCESS_DB)) {
				?>
				<li><?php echo $links['main']['db']; ?></li>
				<?php
					}

					if($orbicon_x->get_can_access_tab(ORBX_ACCESS_DYNAMIC) && !empty($dyn_items)) {
				?>
				<li><?php echo $links['main']['dynamic']; ?></li>
				<?php
					}

					if($orbicon_x->get_can_access_tab(ORBX_ACCESS_TOOLS)) {
				?>
				<li><?php echo $links['main']['tools']; ?></li>
				<?php
					}

					if($orbicon_x->get_can_access_tab(ORBX_ACCESS_CRM) && !empty($crm_items)) {
				?>
				<li><?php echo $links['main']['crm']; ?></li>
				<?php
					}

					if($orbicon_x->get_can_access_tab(ORBX_ACCESS_SETTINGS)) {
				?>
				<li><?php echo $links['main']['settings']; ?></li>
				<?php
					}

					if($orbicon_x->get_can_access_tab(ORBX_ACCESS_SYSTEM)) {
				?>
				<li><?php echo $links['main']['system']; ?></li>
				<?php
					}
				?>
				<li><?php echo $links['main']['languages']; ?></li>
				<li><?php echo $links['main']['helpcenter']; ?></li>
				<li><?php echo $links['main']['exit']; ?></li>
			</ul>
			<?php
				// free memory here
				unset($crm_items, $dyn_items, $indicator);
			?>
		</div>
		 <!-- end #nav-main -->
	</div>

	<div id="orbx_header_separator"></div>
	<!-- end #header -->

	<div id="orbx_breadcrumbs">
		<a href="<?php echo ORBX_SITE_URL; ?>/?ln=<?php echo $orbicon_x->ptr; ?>"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/switch-to-frontend.png" /> <?php echo ORBX_SITE_URL; ?></a>
		&gt;
		<?php echo $title; ?>
	</div>

	<?php
		if($content != 'desktop') {
	?>
	<div id="orbx_main-feature"></div>
	<div id="orbx_wrapper">
		<div id="orbx_content">
			<div class="rtop">
				<div class="r1"></div> <div class="r2"></div> <div class="r3"></div> <div class="r4"></div>
			</div>

			<div id="orbx_window">
				<div id="orbx_window_options" style="float:right;">
					<div class="helper_toolbar" id="helper_full">

						<div style="padding-right: 3px;float:left"><?php echo _L('whos_here'); ?>? <?php echo $orbicon_x->print_whos_here($_GET); ?></div>

						<?php
							if($request == 'mod') {
								echo $orbicon_x->print_icon_manager($mod_request);
							}
							else {
								echo $orbicon_x->print_icon_manager($request);
							}
						?>

						<div style="float:left">
						<a href="javascript:void(null);" onclick="javascript: sh('orbx_sidebar_window'); sh('orbx_help');">
							<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/help.png" alt="<?php echo _L('help'); ?>" title="<?php echo _L('help'); ?>" />
						</a>
						
						<a href="javascript:void(null);" onclick="javascript: window.location.reload();">
							<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/arrow_refresh.png" alt="<?php echo _L('refresh'); ?>" title="<?php echo _L('refresh'); ?>" />
						</a>
						<!-- <a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon">
							<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/cancel.png" alt="<?php echo _L('stop'); ?>" title="<?php echo _L('stop'); ?>" />
						</a>
						-->
						<a href="javascript:void(null);" onclick="javascript:sh('helper_small'); sh('helper_full');">
							<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/bullet_arrow_up.png" alt="<?php echo _L('hide'); ?>" title="<?php echo _L('hide'); ?>" />
						</a>
						</div>
					</div>

					<div class="helper_toolbar" id="helper_small" style="display:none;">
						<a href="javascript:void(null);" onclick="javascript:sh('helper_small'); sh('helper_full');">
							<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/bullet_arrow_down.png" alt="<?php echo _L('open'); ?>" title="<?php echo _L('open'); ?>" />
						</a>
					</div>
				</div>

				<h2 style="text-transform: uppercase;" id="orbx_window_title"><?php echo $title; ?></h2>
				<noscript>
					<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/error-log.png" alt="<?php echo _L('error'); ?>" title="<?php echo _L('error'); ?>" />
					You may not have everything you need to use certain sections of <?php echo ORBX_FULL_NAME; ?>. Please see our <a href="<?php echo ORBX_SITE_URL; ?>/orbicon/docs/index.html">software requirements.</a>
				</noscript>
				<?php

					// receiver alert
					if($_SESSION['site_settings']['syncm_type'] == SYNC_MANAGER_TYPE_RECEIVER) {
						echo '<img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/error-log.png" alt="'._L('error').'" title="'._L('error').'" /> ' . _L('alert_receiver');
					}

					if(!$_access) {
						$content = '<h1>403 Forbidden</h1>';
						echo $content;
					}
					else {
						if($inc) {
							include_once $content;
						}
						else {
							echo $content;
						}
					}
					unset($note);
				?>
			</div>
			<div class="rbottom">
			  <div class="r4"></div> <div class="r3"></div> <div class="r2"></div> <div class="r1"></div>
			</div>
		</div><!-- end #content div -->

		<div id="orbx_tools_sidebar">

			<div class="rtop">
				<div class="r1"></div> <div class="r2"></div> <div class="r3"></div> <div class="r4"></div>
			</div>

			<div id="orbx_sidebar_window">
			<?php

				switch($request) {
					case 'magister':	$sidebar = DOC_ROOT . '/orbicon/controler/sb.magister.php'; 				break;
					case 'venus':		$sidebar = DOC_ROOT . '/orbicon/controler/sb.venus.php'; 					break;
					case 'mercury':		$sidebar = DOC_ROOT . '/orbicon/controler/sb.mercury.php'; 					break;
					case 'mod':			$sidebar = DOC_ROOT . '/orbicon/modules/' . $mod_request . '/sb.mod.php';	break;
					default:			$sidebar = DOC_ROOT . '/orbicon/controler/sb.' . basename($content);
				}

				if(is_file($sidebar)) {
					include_once $sidebar;
				}

			?>
			</div>

			<div id="orbx_help" style="display:none;">

			<?php

				switch($request) {
					case 'magister':
						$help = DOC_ROOT . '/orbicon/controler/help/' . $orbicon_x->ptr . '/hlp.magister.php';
						$_fail_help = DOC_ROOT . '/orbicon/controler/help/en/hlp.magister.php';
					break;

					case 'venus':
						$help = DOC_ROOT . '/orbicon/controler/help/' . $orbicon_x->ptr . '/hlp.venus.php';
						$_fail_help = DOC_ROOT . '/orbicon/controler/help/en/hlp.venus.php';
					break;

					case 'mercury':
						$help = DOC_ROOT . '/orbicon/controler/help/' . $orbicon_x->ptr . '/hlp.mercury.php';
						$_fail_help = DOC_ROOT . '/orbicon/controler/help/en/hlp.mercury.php';
					break;

					case 'mod':
						$help = DOC_ROOT . '/orbicon/modules/' . $mod_request . '/help/' . $orbicon_x->ptr . '.hlp.mod.php';
						$_fail_help = DOC_ROOT . '/orbicon/modules/' . $mod_request . '/help/en.hlp.mod.php';
					break;

					default:
						$help = DOC_ROOT . '/orbicon/controler/help/' . $orbicon_x->ptr . '/hlp.' . basename($content);
						$_fail_help = DOC_ROOT . '/orbicon/controler/help/en/hlp.' . basename($content);
				}

			?>
			<!-- Edit area -->

			<div class="rss_rtop">
				<div class="r1"></div> <div class="r2"></div> <div class="r3"></div> <div class="r4"></div>
			</div>

			<div class="orbx_help_container">

			<h3><?php echo $title; ?></h3>

		<?php

			// load eng help by default
			if(!is_file($help)) {
				$help = $_fail_help;
				echo '
				<h3><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/error-log.png" alt="'._L('error').'" title="'._L('error').'" />
				Help for your language could not be located. If available, help in English language will be used as an alternative and displayed below.</h3>';
			}

			include_once $help;

		?>

			<!-- Edit area ends -->
			<div class="clean"></div>

			</div>

			<div class="rss_rbottom">
				 <div class="r4"></div> <div class="r3"></div> <div class="r2"></div> <div class="r1"></div>
			</div>
			</div>

			<div class="rbottom">
				<div class="r4"></div> <div class="r3"></div> <div class="r2"></div> <div class="r1"></div>
			</div>

		</div>
	</div>
	<!-- start #footer -->
	<div id="orbx_footer">
		<div id="orbx_footer-contents">
			<p style="color:blue;">Copyright &copy; 2006&mdash;<?php echo date('Y'); ?> Pavle Gardijan. All rights reserved.</p>
		</div>
	</div>
	<div style="clear: both;"></div>
</div>
<!-- end #footer -->

<?php
	}
	// load desktop
	else {
		include_once DOC_ROOT . '/orbicon/controler/admin.desktop.php';
	}

	foreach($links['sublinks'] as $sublinks) {
		echo $sublinks;
	}

	// overriden title
	$custom_title = $orbicon_x->get_page_title();
	if($custom_title != '') {
		echo '
<script type="text/javascript"><!-- // --><![CDATA[
	document.title = "'.$custom_title.'";
// ]]></script>';
	}

?>
</body>
</html>