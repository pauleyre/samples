<?php

require_once DOC_ROOT . '/orbicon/modules/peoplering/class/class.prvmailer.php';

$mailer = new PrivateMailer((int) $_SESSION['user.r']['id']);

// send
if(isset($_POST['send'])) {

	$mailer->send($_POST['to'], $_POST['mail_subject'], $_POST['body']);
	redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&sp=mail');
}

if(isset($_POST['submit_mail'])) {
	$action = $_POST['mail_action'];

	if(strpos($action, 'move_') === 0) {
		$mailbox = substr($action, 5);
		$mailer->move($_POST['prv_mail'], $mailbox);
	}
	elseif ($action == 'delete') {
		$mailer->delete($_POST['prv_mail']);
	}
}

if(isset($_POST['empty_trash'])) {
	$mailer->empty_trash();
}

$total_inbox_all = $mailer->count_mails(PR_MAILBOX_INBOX);
$total_sent_all = $mailer->count_mails(PR_MAILBOX_SENT);
$total_trash_all = $mailer->count_mails(PR_MAILBOX_TRASH);

$total_inbox_ur = $mailer->count_mails(PR_MAILBOX_INBOX, PR_MAIL_UNREAD);
$total_sent_ur = $mailer->count_mails(PR_MAILBOX_SENT, PR_MAIL_UNREAD);
$total_trash_ur = $mailer->count_mails(PR_MAILBOX_TRASH, PR_MAIL_UNREAD);

$total_new = $total_inbox_ur + $total_sent_ur + $total_trash_ur;
$total_all = $total_inbox_all + $total_sent_all + $total_trash_all;

$friends = '';

// friends
require_once DOC_ROOT.'/orbicon/modules/peoplering/class/class.user_contacts.php';

$uc = new User_Contacts((int) $_SESSION['user.r']['id']);

if(isset($_GET['delete_uc'])) {
	$uc->delete($_GET['delete_uc']);
	$uc->save();
	redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=mail');
}

foreach ($uc->contacts->members as $member) {

	$picture = $pr->get_picture($pr->get_prid_from_rid($pr->get_id_from_username($member)));

	if(is_file(DOC_ROOT . '/site/venus/thumbs/t-' . $picture)) {
		$picture = ORBX_SITE_URL.'/site/venus/thumbs/t-' . $picture;
	}
	elseif(is_file(DOC_ROOT . '/site/venus/' . $picture)) {
		$picture = ORBX_SITE_URL.'/site/venus/' . $picture;
	}
	else {
		$picture = ORBX_SITE_URL.'/orbicon/modules/peoplering/gfx/unknownUser.gif';
	}

	$friends .= '<a href="'.url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=user&amp;user='.$member, ORBX_SITE_URL . '/~' . $member).'"><img class="friend_avatar" src="'.$picture.'" alt="' . $member . '" title="' . $member . '" /> ' . $member . '</a> <a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=mail&amp;delete_uc='.$member.'" onclick="javacscript: return false;" onmousedown="'.delete_popup($member).'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/delete.png" alt="'._L('delete').'" title="'._L('delete').'" /></a><br />';
}

$total_friends = count($uc->contacts->members);

$uc = null;

if(!$friends) {
	$friends = sprintf(_L('pr-no-contacts') . '. '/* . _L('pr-add-new-contacts') . '.', '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=search">', '</a>'*/);
}
else {
	/*$friends .= sprintf(_L('pr-add-new-contacts') . '.', '<a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=search">', '</a>');*/
}

$quota_color = 'green';

if($total_all > 50) {
	$quota_color = 'yellow';
}

if($total_all > 75) {
	$quota_color = 'orange';
}

if($total_all > 90) {
	$quota_color = 'red';
}

$active_index = (isset($_GET['reply']) || isset($_GET['to'])) ? 0 : 1;

$reply = null;

// we're sending a message to someone
if(isset($_GET['to'])) {
	$reply['mail_from'] = $_GET['to'];
}

// prepare reply message
if(isset($_GET['reply'])) {
	$reply = $mailer->get($_GET['reply']);

	// add Re: if not already present
	if(substr(strtolower($reply['subject']), 0, 3) != 're:') {
		$reply['subject'] = 'Re: ' . $reply['subject'];
	}

	$reply['body'] = '<br /><br />' . str_repeat('-', 40) . '<br /><br />' . $reply['body'];
	$reply['mail_from'] = $pr->get_username($reply['mail_from']);
	$reply['mail_from'] = $reply['mail_from']['username'];
}

// quota message
$quota_mess = sprintf(_L('pr-quota-msg'), $total_all, $total_all, '%');

$display_content = '
<script type="text/javascript"><!-- // --><![CDATA[

	YAHOO.util.Event.addListener(window, "load", rte_lite_load);
	YAHOO.util.Event.addListener(window, "load", __rte_lite_attach);
	YAHOO.util.Event.addListener(window, "load", function() {
		var tabView = new YAHOO.widget.TabView("mail_navigation");
		tabView.set("activeIndex", '.intval($active_index).');
	});

	function __rte_lite_attach() {
		YAHOO.util.Event.addListener($("form_mailer"), "submit", function () {$("body").value = rte_lite.body.innerHTML;});
	}

// ]]></script>
<style type="text/css">/*<![CDATA[*/

	#rte_lite_content, #mail_subject, #to {
		background-color:#ffffff;
		width:99%;
	}

/*]]>*/</style>
<link rel="stylesheet" type="text/css" href="'.ORBX_SITE_URL.'/orbicon/3rdParty/yui/build/tabview/assets/tabview.css?'.ORBX_BUILD.'" />
<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/controler/gzip.server.php?file=/orbicon/3rdParty/yui/build/tabview/tabview-min.js&amp;'.ORBX_BUILD.'"></script>

<link rel="stylesheet" type="text/css" href="'.ORBX_SITE_URL.'/orbicon/modules/peoplering/setup/autocomplete.css?'.ORBX_BUILD.'" />
<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/modules/peoplering/library/autocomplete.js?'.ORBX_BUILD.'"></script>
<table style="width:100%">
<tr>
	<td style="vertical-align:top">
		<h1>'._L('pr-my-mail').'</h1>
		<p>'.sprintf(_L('pr-new-mail'), $total_inbox_all, $total_new).'</p>
	</td>
	<td style="vertical-align:top">
		<h1>'._L('pr-mailboxes').'</h1>

		<table width="100%">
			<tbody>
				<tr>
	                <td>'._L('pr-inbox').'</td>
	                <td>'.$total_inbox_ur.'/'.$total_inbox_all.'</td>
              	</tr>
				  <tr>
						<td>'._L('pr-sent').'</td>
						<td>'.$total_sent_ur.'/'.$total_sent_all.'</td>
	              </tr>
				  <tr>
						<td>'._L('pr-trash').'</td>
						<td>'.$total_trash_ur.'/'.$total_trash_all.'</td>
	              </tr>
       		</tbody>
    	</table>
	</td>
</tr>
<tr>
	<td colspan="2" class="quota">
		<h1>'._L('pr-quota').'</h1>
		<table style="width:100%">
			<tr>
				<td>'.$quota_mess.'</td>
				<td>
					<div style="float:left;width:100px;border:1px solid black;">
						<div style="width:'.$total_all.'px;background-color:'.$quota_color.';">&nbsp;</div>
					</div>
				</td>
			</tr>
		</table><br />
	</td>
</tr>
<tr>
	<td colspan="2">
	<form id="form_mailer" method="post" action="" enctype="multipart/form-data">
		<div id="mail_navigation" class="yui-navset">
		    <ul class="yui-nav">
		    	<li class="mail_write selected"><a href="#write"><em>'._L('pr-write-new').'</em></a></li>
		        <li class="mail_inbox"><a href="#inbox"><em>'._L('pr-inbox').' ('.$total_inbox_ur.')</em></a></li>
		        <li class="mail_sent"><a href="#sent"><em>'._L('pr-sent').' ('.$total_sent_ur.')</em></a></li>
		        <li class="mail_trash"><a href="#trash"><em>'._L('pr-trash').' ('.$total_trash_ur.')</em></a></li>
		        <li class="mail_friends"><a href="#friends"><em>'._L('pr-friends').' ('.$total_friends.')</em></a></li>
		    </ul>
		    <div class="yui-content">
		     	<div id="write">
						<input type="hidden" id="body" name="body" />
						<p>
						<label for="to">'._L('pr-mail-to').': <span class="red">*</span></label><br />
						<input id="to" name="to" value="'.$reply['mail_from'].'" /><br />
						<div id="pring_mail_container"></div>
						<label for="mail_subject">'._L('pr-mail-subject').':</label><br />
						<input id="mail_subject" name="mail_subject" maxlength="255" value="'.$reply['subject'].'" /><br />
						<label for="email">'._L('message').' : <span class="red">*</span></label>
						<a href="javascript:void(null);" onclick="javascript:rte_lite_bold();">
							<img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/text_bold.png" alt="text_bold.png" title="Bold (CTRL + B)" />
						</a>
						<a href="javascript:void(null);" onclick="javascript:rte_lite_italic();">
							<img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/text_italic.png" alt="text_italic.png" title="Italic (CTRL + I)" />
						</a>
						<a href="javascript:void(null);" onclick="javascript:rte_lite_underline();">
							<img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/text_underline.png" alt="text_underline.png" title="Underline (CTRL + U)" />
						</a>
						<a href="javascript:void(null);" onclick="javascript:rte_lite_strikethrough();">
							<img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/text_strikethrough.png" alt="text_strikethrough.png" title="Strikethrough" />
						</a>
						<a href="javascript:void(null);" onclick="javascript: rte_lite_link();">
							<img src="'.ORBX_SITE_URL.'/orbicon/rte/rte_buttons/link.gif" alt="link.gif" title="Link (CTRL + K)" />
						</a><br />

						<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/smile.png" alt=":)" title=":)" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/dunno.png" alt=":/" title=":/" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/wink.png" alt=";)" title=";)" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/veryhappy.png" alt=":D" title=":D" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/sad.png" alt=":(" title=":(" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/serious.png" alt=":|" title=":|" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/tongue.png" alt=":P" title=":P" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/yelling.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/zipped.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/angel.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/badhairday.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/cool.png" alt="8)" title="8)" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/crying.png" alt=":\')" title=":\')" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/embarrassed.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/evil.png" alt=">:)" title=">:)" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/huh.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/lmao.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/nerd.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/oooh.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/retard.png" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/sarcastic.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/sleepy.png" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/teeth.png"  /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/beer.png" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/gift.png" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/love.png" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/cd.png" /></a>
				<a href="javascript:void(null);"><img onclick="javascript:rlis(this.src);" src="'.ORBX_SITE_URL.'/orbicon/gfx/smileys/note.png" /></a>

						<iframe id="rte_lite_content"></iframe>
						<br /><br />
						<label for="atch">'.sprintf(_L('pr-atch'), '<strong>'.byte_size(get_php_ini_bytes(ini_get('post_max_size'))).'</strong>').'</label><br />
						<div>
							<ol id="mail_attch">
								<li><input type="file" id="atch" name="atch[]" /></li>
							</ol>
						</div>
						<p><a href="javascript:void(null);" onclick="javascript:add_new_attch();">Dodaj novi dokument</a></p>
						<p><input onclick="return verify_pring_mail();" id="send" name="send" type="submit" value="'._L('send_msg').'" /></p>
						</p>
		     	</div>
		        <div id="inbox"><br />'.$mailer->mailbox_table(PR_MAILBOX_INBOX).'</div>
		        <div id="sent"><br />'.$mailer->mailbox_table(PR_MAILBOX_SENT).'</div>
		        <div id="trash"><br />'.$mailer->mailbox_table(PR_MAILBOX_TRASH).'
		        <p><input name="empty_trash" id="empty_trash" value="'._L('pr-empty_trash').'" type="submit" /></p></div>
		        <div id="friends"><br />'.$friends.'</div>
		    </div>
		</div>
		<p class="mail_actions">
		<label for="mail_action">'._L('pr-with-sel').'</label>
		<select name="mail_action" id="mail_action">
              <option value="">'._L('pr-sel-action').'</option>
			  <option value="move_INBOX">'._L('pr-move-inbox').'</option>
			  <option value="move_SENT">'._L('pr-move-sent').'</option>
			  <option value="move_TRASH">'._L('pr-move-trash').'</option>
			  <option value="delete">'._L('pr-del-all').'</option>
			  <!-- <option value="spam">'._L('pr-report-spam').'</option>
			  <option value="abuse">'._L('pr-report-abuse').'</option> -->
            </select> <input type="submit" value="OK" id="submit_mail" name="submit_mail" />
       </p>
       </form>
	</td>
</tr>
</table>'.
"
<script type=\"text/javascript\"><!-- // --><![CDATA[
	function setEditText() {
		var content = '".addslashes(str_sanitize($reply['body'], STR_SANITIZE_JAVASCRIPT))."';
		// this sucks
		try {
			if(rte_lite) {
				rte_lite.body.innerHTML = content;
			}
			else {
				// another delay
				setTimeout(function () {rte_lite.body.innerHTML = content;}, 1000);
			}
		} catch(e) {}
		// * load db browser
	}

	YAHOO.util.Event.addListener(window, 'load', setTimeout(setEditText, 1000));

// ]]></script>
";

?>

