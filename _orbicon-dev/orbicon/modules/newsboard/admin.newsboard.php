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
	require_once DOC_ROOT.'/orbicon/modules/news/class.news.admin.php';

	$news = new News_Admin();

	$settings = new Settings();
	$settings->save_news_properties();
	$settings->build_site_settings(true);

?>

<!-- Required CSS -->

<style type="text/css" media="all">

.yui-dt-odd {background-color:#eeeeee;} /*light gray*/
#news_items_table table { width:100%;}

#news_items_table th { text-align:left; }

</style>

<?php
	if($news->check_news_category() == 0) {
?>
	<h2><a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/mod/news-category"> <img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/error-log.png" alt="<?php echo _L('error'); ?>" title="<?php echo _L('error'); ?>" /> <?php echo _L('no_news_cat'); ?></a></h2><br />
<?php
	}
?>
<div id="news_items">

	<p>
		<input type="button" value="<?php echo _L('add_new') ?>" onclick="javascript: redirect('<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/news'; ?>');"  />
	</p>

	<div id="news_items_table">
	<?php $news->build_news_items(); ?>
	</div>

	<p>
		<input type="button" value="<?php echo _L('add_new') ?>" onclick="javascript: redirect('<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/mod/news'; ?>');"  />
	</p>
</div>
<div style="height: 1%;"></div>