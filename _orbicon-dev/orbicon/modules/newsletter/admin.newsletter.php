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

	require_once DOC_ROOT.'/orbicon/modules/newsletter/inc.newsltr.php';

	send_newsletter();

?>

<script type="text/javascript"><!-- // --><![CDATA[

	function __validate_newsletter()
	{
		var msg = "<?php echo _L('fill_in_fields'); ?>:\n";
		var error_msg = msg;

		if(empty($("newsletter_title").value)) {
			error_msg = error_msg + "- <?php echo _L('title'); ?>\n";
			$("newsletter_title").focus();
		}

		if(empty($("content_text").value)) {
			error_msg = error_msg + "- <?php echo _L('content'); ?>\n";
		}

		if(msg == error_msg) {
			return true;
		}
		window.alert(error_msg);
		return false;
	}

	function edit_adrbk()
	{
		try {
			var adrbks = $('newsletter_adrbk');
			var permalink = adrbks.options[adrbks.selectedIndex].value;
			if(empty(permalink)) {
				redirect(orbx_site_url + '/?' + __orbicon_ln + '=orbicon/mod/address-book');
			}
			else {
				redirect(orbx_site_url + '/?' + __orbicon_ln + '=orbicon/mod/address-book&edit=' + permalink);
			}
		}
		catch (e) {
			redirect(orbx_site_url + '/?' + __orbicon_ln + '=orbicon/mod/address-book');
		}
	}

	YAHOO.util.Event.addListener(window,"load",start_magister_mb);

	function start_magister_mb() {
		switch_mini_browser('magister', '', 0, 0);
	}

// ]]></script>
<form onsubmit="javascript:return __validate_newsletter();" action="" method="post" name="nwsltr_form" id="nwsltr_form">
<input name="send_newsletter" type="submit" id="send_newsletter" value="<?php echo _L('send_newsletter'); ?>" /><br />
<input id="content_text" name="content_text" type="hidden" />
<input name="newsletter_server_pause" id="newsletter_server_pause" type="hidden" value="500000" />
<p>
<label for="newsletter_adrbk"><?php echo _L('adr_books'); ?></label><br />
<select id="newsletter_adrbk" name="newsletter_adrbk">
<option value=""><?php echo _L('add_new'); ?></option>
<optgroup label="<?php echo _L('pick_adr_book'); ?>">
<?php
	require_once DOC_ROOT . '/orbicon/modules/address-book/class.addrbk.php';
	$adrbk = new Address_Book;
	$all = $adrbk->get_adrbk_array();
	unset($adrbk);

	if(!empty($all)) {
		foreach($all as $value) {
			if(!empty($value['permalink'])) {
?>
<option value="<?php echo $value['permalink'];?>"><?php echo $value['title']; ?></option>
<?php
			}
		}
	}
?>
</optgroup>
</select> <a href="javascript:void(null);" onclick="javascript:edit_adrbk();"><img style="width:16px; height:16px; border:none;" src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/edit.png" alt="<?php echo _L('edit'); ?>" title="<?php echo _L('edit'); ?>" /> <?php echo _L('edit'); ?></a>
</p>

<p>
<label for="newsletter_title"><?php echo _L('title'); ?></label><br />
<input name="newsletter_title" type="text" id="newsletter_title" style="width:50em; padding: 3px;" /><br />
</p>

<p>
<label for="content_text"><?php echo _L('content'); ?></label><br />
		<div id="news_content" style=" height: 350px; overflow:auto; width:auto;background:#ffffff;border:1px solid #cccccc;"></div>
</p>
<br />
<input name="send_newsletter" type="submit" id="send_newsletter2" value="<?php echo _L('send_newsletter'); ?>" />

</form>