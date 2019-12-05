<?php

	$isItOkToDisplay = "false";

	if(isset($_POST['submit_new_question'])){

		if($icsetting['mail_required'] == '1' && (!isset($_POST['mail']) || $_POST['mail'] == '')){
			// * force second block to throw an error
			/**
			 * @todo what is this? why would you force a wrong error?
			 */
			unset($_POST['title']);
		}

		if(isset($_POST['title']) && strip_tags($_POST['title']) !== ''){

			$q = new Question($_POST);
			$q->set_new_question();

			$mail_to_user = '<p>' . _L('ic-msg-mail-touser') . '</p><br />';
			$mail_to_user .= '<p>' . sprintf(_L('ic-msg-mail-sig'), DOMAIN_NAME) . '</p>';

			// notify user of submision
			if(isset($_POST['mail']) && $_POST['mail'] != ''){

				$emails = explode(',', $_POST['mail']);

				include_once DOC_ROOT . '/orbicon/3rdParty/phpmailer/class.phpmailer.php';

				$mail = new PHPMailer();

				if($_SESSION['site_settings']['smtp_server'] != '') {
					$mail->IsSMTP(); // telling the class to use SMTP
					$mail->Host = $_SESSION['site_settings']['smtp_server']; // SMTP server
					$mail->Port = $_SESSION['site_settings']['smtp_port'];
				}

				$mail->CharSet = 'UTF-8';
				$mail->From = DOMAIN_EMAIL;
				$mail->FromName = utf8_html_entities(DOMAIN_OWNER, true);
				foreach($emails as $email) {
					if(is_email($email)) {
						$mail->AddAddress($email);
					}
				}

				$mail->Subject = utf8_html_entities(DOMAIN_NAME, true);
				$mail->Body = $mail_to_user;
				$mail->WordWrap = 50;
				$mail->IsHTML(true);

				if(!$mail->Send()) {
					foreach($emails as $email) {
						// bug fix, if there is whitespace on the beginning
						// of string returns flase
						$email = trim($email);
						mail($email, DOMAIN_NAME, $mail_to_user, 'Content-Type: text/html; charset=UTF-8');
					}
				}

				$mail = null;

				$isItOkToDisplay = 'true';
			}

			unset($_POST['title']);
		}
		else {
			$ask_form .= '<h3>'._L('ic-msg-submit-fail').'<a href="./?'.$lang.'=mod.infocentar&amp;showPage=askQuestion">'._L('ic-msg-click-here').'</a></h3>';
		}
	}
	else {
		if($icsetting['mail_required'] == '1') {
			$auto_respond = '<p><input type="checkbox" class="chk_btn" name="notify" id="notify" value="1" /> <label for="notify">'._L('ic-lbl-notify').'</label></p>';
			$req = ' <span class="red">*</span>';
		}
		else {
			$auto_respond = '';
			$req = '';
		}

	// get user's data
	$user = $_SESSION['user'];

	// not admin
	if(!get_is_admin()) {
		$user['first_name'] = $_SESSION['user']['contact_name'];
		$user['last_name'] = $_SESSION['user']['contact_surname'];
		$user['email'] = $_SESSION['user']['contact_email'];
	}

	// categories drop- down
	$categories_menu  = '<select id="user_category" name="user_category"><option value="0">&mdash;</option>';

	// * get full list of categories, that are active
	$categories = $cl->get_all_categories(1);
	$cat_item = $dbc->_db->fetch_assoc($categories);

	while($cat_item) {
		$categories_menu .= "<option value=\"{$cat_item['id']}\">{$cat_item['title']}</option>";
		$cat_item = $dbc->_db->fetch_assoc($categories);
	}

	$categories_menu .= '</select>';

	$ask_form .= '
<script type="text/javascript"><!-- // --><![CDATA[
/* EMAIL verification */
function eMailCheck(str) {

		var at="@";
		var dot=".";
		var lat=str.indexOf(at);
		var lstr=str.length;
		var ldot=str.indexOf(dot);

		if (str.indexOf(at)==-1){
		   return false;
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		   return false;
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		    return false;
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		    return false;
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    return false;
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    return false;
		 }

		 if (str.indexOf(" ")!=-1){
		    return false;
		 }

 		 return true;
	}

function ValidateForm(form)
{
	var emailID = form.mail;
	var msgID = form.title;

	if(empty(msgID.value)){
		msgID.focus();
		return false;
	}

	if (empty(emailID.value)){
		emailID.focus();
		return false;
	}
	if (empty(emailID.value)){
		emailID.value="";
		emailID.focus();
		return false;
	}
	return true;
}
// ]]></script>

<form name="askQuestion" id="askQuestion" method="post" action="" onsubmit="javascript: return ValidateForm(this);">
		
<script type="text/javascript"><!-- // --><![CDATA[
	document.write(\'<input type="hidden" id="as_clear" name="as_clear" value="1" />\');
// ]]></script>
		
<p id="ask_name">
	<label for="author">'._L('ic-lbl-name').'</label><br />
	<input type="text" name="author" id="author" value="'.$user['first_name'].' '.$user['last_name'].'" />
</p>
<p id="ask_mail">
	<label for="mail">'._L('ic-lbl-mail').''.$req.'</label><br />
	<input value="'.$user['email'].'" type="text" name="mail" id="mail" />
</p>
<p id="ask_question">
	<label for="title">'._L('ic-lbl-question').' <span class="red">*</span></label><br />
	<textarea name="title" id="title"></textarea>
</p>
<p id="ask_category">
	<label for="user_category">'._L('ic-category').'</label><br />
	'.$categories_menu.'
</p>
'.$auto_respond.'
<p id="ask_submit"><input type="submit" id="submit_new_question" class="chk_btn" name="submit_new_question" value="'._L('ic-lbl-send').'" /></p>
</form>
<div class="cleaner"></div>';
	}

	return $ask_form;

?>