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
	require_once DOC_ROOT.'/orbicon/modules/rss/class.rss.php';

	$rss = new RSS_Manager;
	$rss -> load_current_rss();
	$rss -> remove_rss();
	$rss -> add_rss();
	$rss -> display_rss();
?>

<script type="text/javascript"><!-- // --><![CDATA[
	var __rss_list_window = true;

	function add_feed()
	{
		var url = $("rss_feed");

		if(empty(url.value) || url.value == 'http://' || url.value == 'https://') {
			window.alert("<?php echo _L('enter_feed_url'); ?>");
			url.focus();
			return false;
		}
		return true;
	}

	function __toggle_rss_list(o)
	{
		if(__rss_list_window) {
			$('rss_list').style.display = 'none';
			__rss_list_window = false;
			set_text_content(o, '[<?php echo _L('open'); ?>]');
		}
		else {
			$('rss_list').style.display = 'block';
			__rss_list_window = true;
			set_text_content(o, '[<?php echo _L('close'); ?>]');
		}
	}
// ]]></script>

<?php
	if(!empty($rss->rss_feed_list)) {
?>
<div>
	<sup style="float:right; height: 15px;"><a href="javascript:void(null);" onclick="javascript:__toggle_rss_list(this);">[<?php echo _L('close'); ?>]</a></sup>
	<h3><?php echo _L('feeds_on_page'); ?> <?php echo DOMAIN_NAME.' ('.ORBX_SITE_URL.')'; ?></h3>
	<div id="rss_list">
		<ol style="list-style-image: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/bullet_feed.png);">
			<?php echo $rss -> rss_feed_list; ?>
		</ol><br />
	</div>
</div>
<div style="border:1px dotted red; background:#ffffff;padding:1em; margin: 0 0 1em 0;"><?php echo $rss -> rss_feed_content; ?></div>
<div style="height: 1%;"></div>
<?php
	}
?>