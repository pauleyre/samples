<?php

	global $orbicon_x;

	$orbx_desktop_icons = array(
		'wwwroot' => array('href' => ORBX_SITE_URL, 'title' => DOMAIN_NAME, 'gfx' => '38-site-name.gif', 'page_gfx' => ''),
		'venus' => array('href' => ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/venus', 'title' => _L('images'), 'gfx' => '8-images.gif', 'page_gfx' => 'images37px.gif'),
		'magister' => array('href' => ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/magister', 'title' => _L('texts'), 'gfx' => '7-texts.gif', 'page_gfx' => 'texts37px.gif'),
		'mercury' => array('href' => ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mercury', 'title' => _L('data'), 'gfx' => '9-documents.gif', 'page_gfx' => 'documents37px.gif'),
		'gfxdir' => array('href' => ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/gfxdir', 'title' => _L('www_folder'), 'gfx' => '10-web-graphic.gif', 'page_gfx' => 'web-graphic37px.gif'),
		'columns' => array('href' => ORBX_SITE_URL . '/?' . $orbicon_x->ptr.'=orbicon/columns', 'title' => _L('columns'), 'gfx' => '1-rubrike.gif', 'page_gfx' => 'rubrike37px.gif'),
		'zones' => array('href' => ORBX_SITE_URL . '/?' . $orbicon_x->ptr.'=orbicon/zones', 'title' => _L('zones'), 'gfx' => '18-zones.gif', 'page_gfx' => 'zones37px.gif'),
		'editors' => array('href' => ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/editors', 'title' => _L('editors'), 'gfx' => '21-administrators.gif', 'page_gfx' => 'administrators37px.gif'),
		'www' => array('href' => ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/www', 'title' => _L('site_info'), 'gfx' => '20-site-information.gif', 'page_gfx' => 'site-information37px.gif'),
		'privileges' => array('href' => '', 'title' => _L('privileges'), 'gfx' => '36-privilegije.gif', 'page_gfx' => 'privilegije37px.gif'),
		'advanced' => array('href' => ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/advanced', 'title' => _L('adv_settings'), 'gfx' => '23-settings.gif', 'page_gfx' => 'settings37px.gif'),
		'update' => array('href' => ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/update', 'title' => _L('update_center'), 'gfx' => '29-update-center.gif', 'page_gfx' => 'update-center37px.gif'),
		'helpdesk' => array('href' => ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/helpdesk', 'title' => _L('helpdesk'), 'gfx' => '32-support.gif', 'page_gfx' => 'support37px.gif'),
		'module_info' => array('href' => ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/module_info', 'title' => _L('modules'), 'gfx' => '41-modules.gif', 'page_gfx' => 'modules37px.gif')
	);

?>