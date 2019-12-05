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

	require_once DOC_ROOT.'/orbicon/modules/news-alerts/inc.newsalerts.php';


	if(isset($_POST['send_newsalerts'])) {
		verify_newsalerts();
	}
?>
<style type="text/css" media="all">

.yui-dt-odd {background-color:#eeeeee;} /*light gray*/
#news_items_table table { width:100%;}

#news_items_table th { text-align:left; }

</style>
<form method="post" action="">
<div id="news_items">
	<div id="news_items_table">
		<?php echo build_newsalerts(); ?>
	</div>
	<p>
		<input type="submit" value="<?php echo _L('submit') ?>" name="send_newsalerts" id="send_newsalerts" />
	</p>
</div>
</form>
<div style="height: 1%;"></div>