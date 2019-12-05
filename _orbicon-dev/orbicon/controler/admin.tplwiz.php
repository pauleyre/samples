<?php
/*-----------------------------------------------------------------------*
	Project........:	Orbicon X framework 2
	File...........:	
	Version........:	1.0 (22/10/2006)
	Author.........:	Pavle Gardijan (pavle.gardijan@orbitum.net) - Orbitum d.o.o.
	Copyright......:	(c) 2006 Orbitum internet communications d.o.o. (www.orbitum.net)
	Created........:	01/07/2006
	Notes..........:	
	Modified.......:	
*-----------------------------------------------------------------------*/

	if(isset($_GET['tpl'])) {
	
		$tpl = trim($_GET['tpl']);
		$bk_ext = 'bk_' . sprintf('%u', adler32(uniqid('', true) . (string) time()));

		switch($tpl) {
			case 'liquid':
				rename(DOC_ROOT . '/site/gfx/home.html', DOC_ROOT . '/site/gfx/home.html.' . $bk_ext);
				rename(DOC_ROOT . '/site/gfx/column.html', DOC_ROOT . '/site/gfx/column.html.' . $bk_ext);
				
				copy(DOC_ROOT . '/orbicon/templates/tpl.liquid.html', DOC_ROOT . '/site/gfx/home.html');
				copy(DOC_ROOT . '/orbicon/templates/tpl.liquid.html', DOC_ROOT . '/site/gfx/column.html');
			break;
			case 'smart2':	
				rename(DOC_ROOT . '/site/gfx/home.html', DOC_ROOT . '/site/gfx/home.html.' . $bk_ext);
				rename(DOC_ROOT . '/site/gfx/column.html', DOC_ROOT . '/site/gfx/column.html.' . $bk_ext);
		
				copy(DOC_ROOT . '/orbicon/templates/tpl.smart2.html', DOC_ROOT . '/site/gfx/home.html');
				copy(DOC_ROOT . '/orbicon/templates/tpl.smart2.html', DOC_ROOT . '/site/gfx/column.html');
			break;
		}		
	}

?>
<style type="text/css">
/*<![CDATA[*/
#templates {
	list-style: none;
}
/*]]>*/
</style>
<ul id="templates">
	<li><p><a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr;?>=orbicon/tplwiz&amp;tpl=liquid"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/tpl_wiz/box_layout_design.png" /> Liquid Layout Boxes</a></p></li>
	<li><p><a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr;?>=orbicon/tplwiz&amp;tpl=smart2"><img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/tpl_wiz/ala_layout_design.png" /> Smart 2 column layout</a></p></li>
</ul>
<div style="height: 1%;"></div>