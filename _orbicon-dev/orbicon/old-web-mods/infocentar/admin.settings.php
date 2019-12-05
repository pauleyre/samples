<?php


// * checkboxes & select options
switch($icsetting['apply_author_info']){

	case 0: 	$ai_0 = ' selected="selected"';
				break;
	case 1: 	$ai_1 = ' selected="selected"';
				break;
	case 2: 	$ai_2 = ' selected="selected"';
				break;
	case 3: 	$ai_3 = ' selected="selected"';
				break;
}

// * checkboxes & select options
switch($icsetting['apply_title_info']){

	case 0: 	$ti_0 = ' selected="selected"';
				break;
	case 1: 	$ti_1 = ' selected="selected"';
				break;
	case 2: 	$ti_2 = ' selected="selected"';
				break;
	case 3: 	$ti_3 = ' selected="selected"';
				break;
}

// * checkboxes & select options
switch($icsetting['answer_privileges']){

	case 'a': 		$ap_0 = ' selected="selected"';
					break;
	case 'ar': 		$ap_1 = ' selected="selected"';
					break;
	case 'arp': 	$ap_2 = ' selected="selected"';
					break;
}

$intro = ($icsetting['intro'] == 1) ? ' checked="checked"' : '';
$tag_cloud = ($icsetting['tag_cloud'] == 1) ? ' checked="checked"' : '';

$mail_req = ($icsetting['mail_required'] == 1) ? ' checked="checked"' : '';
$app_polls = ($icsetting['append_polls'] == 1) ? ' checked="checked"' : '';
$app_polls_disabled = ($orbx_mod->validate_module('polls')) ? '' : 'disabled="disabled"';
$quest_notif = ($icsetting['question_notif'] == 1) ? ' checked="checked"' : '';
$quest_notif_hidden = ($icsetting['question_notif'] == 1) ? '' : ' class="hidenitem"';

$author = ($icsetting['author'] == 1) ? ' checked="checked"' : '';
$category = ($icsetting['category'] == 1) ? ' checked="checked"' : '';
$date = ($icsetting['date_show'] == 1) ? ' checked="checked"' : '';
$depart = ($icsetting['depart'] == 1) ? ' checked="checked"' : '';


?>

<script type="text/javascript"><!-- // --><![CDATA[
	YAHOO.util.Event.addListener(window,"load", createListObjects);
	YAHOO.util.Event.addListener(window,"load", start_magister_mb);

	function start_magister_mb()
	{
		/* lead text */
		__magister_mini_input = '<?php echo $icsetting['intro_text'];?>';
		__magister_mini_url = '<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon&ajax_text_db&action=txt';
		__magister_mini_update_list();

		switch_mini_browser('magister', '', 0, 0);
	}

// ]]></script>

<form action="" method="post" id="settings">
<input id="content_text" name="content_text" type="hidden" />

<div>
	<input type="submit" id="uper_submit" name="uper_submit" value="<?php echo _L('save');?>" />
</div>
<br />
<div class="float_cell">
	<input type="checkbox" id="intro" name="intro" value="1"<?php echo $intro;?> />
	<label for="intro"><?php echo _L('ic-req-intro');?></label>
</div>
<div class="float_cell">
	<input type="checkbox" id="tag_cloud" name="tag_cloud" value="1"<?php echo $tag_cloud;?> />
	<label for="tag_cloud"><?php echo _L('ic-req-cloud');?></label>
</div>
<div class="cleaner"></div>
<br />
<p>
<label for="content_text"><?php echo _L('ic-intro-text'); ?></label><br />
		<div id="news_content" style=" height: 150px; overflow:auto; width:95%;background:#ffffff;border:1px solid #cccccc;"></div>
</p><br />

<p>
	<label for="answer_privileges"><?php echo _L('ic-auth-users'); ?></label><br />
	<select id="answer_privileges" name="answer_privileges">
		<option value="a" <?php echo $ap_0; ?>><?php echo _L('ic-admins'); ?></option>
		<option value="ar" <?php echo $ap_1; ?>><?php echo _L('ic-admins_reg'); ?></option>
		<option value="arp" <?php echo $ap_2; ?>><?php echo _L('ic-admins_reg_pub'); ?></option>
	</select>
</p><br />

<p>
	<label for="alt_author"><?php echo _L('ic-alt-author');?></label><br />
	<input type="text" id="alt_author" name="alt_author" class="txtfld" value="<?php echo $icsetting['alt_author'];?>" />
</p>
<br />
<div class="float_cell">
	<label for="admin_per_page"><?php echo _L('ic-ipp-admin');?></label><br />
	<input type="text" id="admin_per_page" name="admin_per_page" value="<?php echo $icsetting['admin_per_page'];?>" />
</div>
<div class="float_cell">
	<label for="public_per_page"><?php echo _L('ic-ipp-public');?></label><br />
	<input type="text" id="public_per_page" name="public_per_page" value="<?php echo $icsetting['public_per_page'];?>" />
</div>
<div class="cleaner"></div>
<br />
<div class="float_cell">
	<label for="apply_author_info"><?php echo _L('ic-author-info');?></label> <br />
	<select id="apply_author_info" name="apply_author_info">
		<option value="0"<?php echo $ai_0;?>><?php echo _L('ic-info-all');?></option>
		<option value="1"<?php echo $ai_1;?>><?php echo _L('ic-info-none');?></option>
		<option value="2"<?php echo $ai_2;?>><?php echo _L('ic-info-question');?></option>
		<option value="3"<?php echo $ai_3;?>><?php echo _L('ic-info-answer');?></option>
	</select>
</div>
<div class="float_cell">
	<label for="apply_title_info"><?php echo _L('ic-title-info');?></label> <br />
	<select id="apply_title_info" name="apply_title_info">
		<option value="0"<?php echo $ti_0;?>><?php echo _L('ic-title-both');?></option>
		<option value="1"<?php echo $ti_1;?>><?php echo _L('ic-title-none');?></option>
		<option value="2"<?php echo $ti_2;?>><?php echo _L('ic-title-cat');?></option>
		<option value="3"<?php echo $ti_3;?>><?php echo _L('ic-title-quest');?></option>
	</select>
</div>
<div class="cleaner"></div>
<br />
<div class="float_cell">
	<input type="checkbox" id="mail_required" name="mail_required" value="1"<?php echo $mail_req;?> />
	<label for="mail_required"><?php echo _L('ic-req-mail');?></label>
</div>
<div class="float_cell">
	<input type="checkbox" id="question_notif" name="question_notif" onclick="javascript: checkList(this, 'mail_list');" value="1"<?php echo $quest_notif;?> />
	<label for="question_notif"><?php echo _L('ic-question-mail');?></label><br /><br />
	<div id="mail_list"<?php echo $quest_notif_hidden;?>>
		<textarea id="question_notif_mail" name="question_notif_mail"><?php echo $icsetting['question_notif_mail'];?></textarea>
		<p><?php echo _L('ic-separate-mail');?></p>
	</div>
</div>
<div class="cleaner"></div>

<div class="float_cell">
	<input <?php echo $app_polls_disabled; ?> type="checkbox" id="append_polls" name="append_polls" value="1"<?php echo $app_polls; ?> />
	<label for="append_polls"><?php echo _L('ic-app-polls');?></label>
</div>

<div class="cleaner"></div>
<br />

<fieldset><legend>Author</legend>
	<div class="float_cell">
		<p><span><label for="author">
			<?php echo _L('ic-author-descr');?></label>
		</span></p>
		<input type="checkbox" id="author" name="author" value="1"<?php echo $author;?> />
		<label for="author"><?php echo _L('ic-ans-yes');?></label>
		<br /><br />
		<p><span><label for="date_show">
			<?php echo _L('ic-date-descr');?></label>
		</span></p>
		<input type="checkbox" id="date_show" name="date_show" value="1"<?php echo $date;?> />
		<label for="date_show"><?php echo _L('ic-ans-yes');?></label>
	</div>
	<div class="float_cell">
		<p><span><label for="category">
			<?php echo _L('ic-category-descr');?></label>
		</span></p>
		<input type="checkbox" id="category" name="category" value="1"<?php echo $category;?> />
		<label for="category"><?php echo _L('ic-ans-yes');?></label>
		<br /><br />
		<p><span><label for="depart">
			<?php echo _L('ic-depart-descr');?></label>
		</span></p>
		<input type="checkbox" id="depart" name="depart" value="1"<?php echo $depart;?> />
		<label for="depart"><?php echo _L('ic-ans-yes');?></label>
	</div>
	<div class="cleaner"></div>
</fieldset> <br />
<div>
	<input type="submit" id="bottom_submit" name="bottom_submit" value="<?php echo _L('save');?>" />
</div>
</form>