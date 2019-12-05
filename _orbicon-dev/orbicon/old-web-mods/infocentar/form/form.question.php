<?php
// * import class files
require_once DOC_ROOT.'/orbicon/modules/infocentar/class/question.class.php';
require_once DOC_ROOT.'/orbicon/modules/infocentar/class/category.class.php';

// * create objects question $q & category $c
$c = new Category();

// * if question should be edited go inside
if(isset($_GET['id'])){

	// * if form has been submited update db
	if($_POST['submit']) {

		$_POST['content'] = $_POST['elm1'];
		$_POST['title'] = strip_tags($_POST['title']);
		
			
		// * create object for update
		$qu = new Question($_POST);

		if($_POST['permalink'] == ''){

			if(!$qu->check_permalink($_POST['title'])){
				echo '<h2 style="color: #c00; text-align: center;">'._L('ic-quest-exist').'</h2>';
			} else {
				$qu->edit_question();
			}

		}
		else {
			$qu->edit_question();
		}

		if(strip_tags($_POST['content']) != ''){
			if($dbc->_db->num_rows($qu->get_answer($_GET['id'])) > 0){
				$qu->edit_answer();
			} else {
				$qu->set_new_answer();
			}
		}

		// if user wants send answer to him
		if($_POST['notify'] && $_POST['state'] && !$_POST['notify_sent']){

			$subject = DOMAIN_NAME . ' - ' . _L('ic-mail-answer-sub');

			$message .= '<p>'._L('name').': <em>'.$_POST['author'].'</em></p>';
			$message .= '<p>'._L('mail').': <em>'.$_POST['mail'].'</em></p><br />';

			$message .= '<h3>'._L('ic-notif-user-quest').': </h3>';
			$message .= '<p>'.$_POST['title'].'</p><br />';
			$message .= '<h3>'._L('ic-notif-user-quest').': </h3>';
			$message .= '<p style="line-height: 1.5em;">'.$_POST['content'].'</p><br />';

			$message .= '<h3>'.sprintf(_L('ic-msg-mail-sig'), DOMAIN_NAME).'</h3><br />';

			/**
			 * @todo  build some security !!!!
			 */

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

			$mail->Subject = utf8_html_entities($subject, true);
			$mail->Body = $message;
			$mail->WordWrap = 50;
			$mail->IsHTML(true);

			if(!$mail->Send()) {
				foreach($emails as $email) {
					// bug fix, if there is whitespace on the beginning
					// of string returns flase
					$email = trim($email);
					mail($email, $subject, $message, 'Content-Type: text/html; charset=UTF-8');
				}
			}

			$mail = null;

			// stop resending mail
			$qu->updateSendedToUser($_POST['id']);

		}

		unset($_POST, $qu);
	}
	$q = new Question();
	$question = $dbc->_db->fetch_assoc($q->get_question($_GET['id']));

	$answer = $dbc->_db->fetch_assoc($q->get_answer($_GET['id']));

	if(isset($_POST['send_mail_to_user']) && $answer !== ''){
		// * do some mailing here
		/**
		 * @todo implement this?
		 */
		//$updateSendedToUser = $q->updateSendedToUser($question['id']);

		$subject = DOMAIN_NAME . ' - ' . _L('ic-mail-answer-sub');

		$message .= '<h3>'.$question['title'].'</h3>';
		$message .= '<p style="line-height: 1.5em;">'.$answer['content'].'</p><br />';

		$message .= '<h3>'.sprintf(_L('ic-msg-mail-sig'), DOMAIN_NAME).'</h3><br />';


		/**
		 * @todo  build some security !!!!
		 */

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

		$mail->Subject = utf8_html_entities($subject, true);
		$mail->Body = $message;
		$mail->WordWrap = 50;
		$mail->IsHTML(true);

		if(!$mail->Send()) {
			foreach($emails as $email) {
				// bug fix, if there is whitespace on the beginning
				// of string returns flase
				$email = trim($email);
				mail($email, $subject, $message, 'Content-Type: text/html; charset=UTF-8');
			}
		}

		$mail = null;

	} else {
		$xyc = '';
	}

}

// * create object for update

if(isset($_GET['new']) && isset($_POST['submit'])) { 
	$_POST['content'] = $_POST['elm1'];
	$_POST['title'] = strip_tags($_POST['title']);
}


$q = new Question($_POST);


if(isset($_GET['new'])){

	// * if form has been submited insert into db
	if($_POST['submit']) {
	
		if($q->admin_sets_new_question()) {

			// * get inserted id
			$tempid = $dbc->_db->insert_id();

			if(strip_tags($_POST['content']) !== ''){
				$q->set_new_answer($tempid);
			}

			unset($_POST);
			redirect(ORBX_SITE_URL . '/?goto=question&'.$orbicon_x->ptr.'=orbicon/mod/infocentar&edit=question&id='.$tempid);

		} 
		else {

			echo '<h2 style="color: #c00; text-align: center;">'._L('ic-quest-exist').'</h2>';

		}
	}

}

// * place answer on
$mail_a = ($question['mail_answer'] == 1) ? ' checked="checked"' : '';

// * hidden button send_mail_to_user
$smtu = ($question['mail_answer'] == 1) ? '' : ' class="hidenitem"';

?>


<form name="question_form" id="question_form" method="post" action="" onsubmit="javascript: RichTextSave();">
<input type="hidden" name="live_time" id="live_time" value="<?php echo $question['live'];?>" />
<div>
<input type="submit" name="submit" id="submit" value="<?php echo _L('ic-save');?>" /></div>
<input type="hidden" name="id" id="id" value="<?php echo $question['id'];?>" />
<input type="hidden" name="notify" id="notify" value="<?php echo $question['notify'];?>" />
<input type="hidden" name="editor" id="editor" value="<?php echo $_SESSION['user.a']['id'];?>" />
<input type="hidden" name="author_id" id="author_id" value="<?php echo $_SESSION['user.a']['id'];?>" />
<input type="hidden" name="permalink" id="permalink" value="<?php echo $question['permalink'];?>" />
<input type="hidden" name="notify_sent" id="notify_sent" value="<?php echo $question['notify_sent'];?>" />
<input id="content" name="content" type="hidden" />
<fieldset><legend><?php echo _L('ic-question');?></legend>
	<p>
		<label for="title"><?php echo _L('ic-question');?></label><br />
		<!-- <input type="text" style="width:90%" name="title" id="title" value="<?php /*echo $question['title'];*/?>" /> -->
		<textarea id="title" name="title" cols="50" rows="6" style="width: 90%;"><?php echo $question['title'];?></textarea>
		<img style="vertical-align:bottom;" src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/lowercase.gif" alt="Lowercase" title="Lowercase" onclick="javascript: $('title').value = $('title').value.toLowerCase();" />
		<img style="vertical-align:bottom;" src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/uppercase.gif" alt="Uppercase" title="Uppercase" onclick="javascript: $('title').value = $('title').value.toUpperCase();" />
	</p>
	<br />
	<p class="tertiary_view">
		<label for="category"><?php echo _L('ic-category');?></label>
		<select name="category" id="category">
			<?php
				echo '<option value="0">'._L('unsorted').'</option>';
				$cat = $c->get_all_categories();
				while($category = $dbc->_db->fetch_array($cat)){

					// * selection for category
					$cat_state = ($question['category'] == $category['id']) ? ' selected="selected"' : '';

					echo '<option value="'.$category['id'].'"'.$cat_state.'>'.$category['title'].'</option>';

				}

			?>
		</select>
	</p>

	<p class="tertiary_view">
		<label for="state"><?php echo _L('ic-state');?></label>
		<select name="state" id="state">
			<?php

				// * selection for category
				$state = ($question['state'] == 0) ? '' : ' selected="selected"';

			?>
			<option value="0"><?php echo _L('ic-inactive');?></option>
			<option value="1"<?php echo $state;?>><?php echo _L('ic-active');?></option>
		</select>
	</p>
	<div class="cleaner"></div>
	<br />
	<?php
		if(!$q->has_answer($_GET['id'])){
	?>
	<p>
		<input type="checkbox" id="mail_answer" name="mail_answer" value="1"<?php echo $mail_a;?> onclick="javascript: checkList(this, 'send_mail_to_user');" />
		<label for="mail_answer"><?php echo _L('ic-place-answer');?></label><br />
		<span><strong><?php echo _L('ic-place-note');?></strong></span><br />
		<input type="submit" <?php echo $smtu;?> id="send_mail_to_user" name="send_mail_to_user" value="<?php echo _L('ic-send_mail_to_user');?>" /> <?php echo $xyc;?>
	</p>
	<?php
		}
	?>
</fieldset>
<fieldset><legend><?php echo _L('ic-tags');?></legend>
<table id="tag_list">
	<tr>
	<?php

		// build tag table
		$adminTagObj = new Tag;

		$tag_list = $adminTagObj->get_tag_list();

		$i = 1;

		while($tag = $dbc->_db->fetch_array($tag_list)){

			$chk = (in_array($tag['tag_title'], unserialize($question['tags']))) ? 'checked="checked"' : '';

			echo 	'<td>
						<input type="checkbox" id="tag['.$tag['id'].']" name="tag['.$tag['id'].']" value="'.$tag['tag_title'].'" '.$chk.' />
						<label for="tag['.$tag['id'].']">'.$tag['tag_title'].'</label>
					</td>';

			if(($i % 7) == 0){
				echo '</tr><tr>';
			}

			$i++;

		}

	?>
	</tr>
</table>
</fieldset>
<fieldset><legend><?php echo _L('ic-author');?></legend>
	<p class="float_cell">
		<label for="author"><?php echo _L('ic-lbl-name');?></label>
		<input type="text" id="author" name="author" value="<?php echo $question['author'];?>" />
	</p>
	<p class="float_cell">
		<label for="mail"><?php echo _L('ic-lbl-mail');?></label>
		<input type="text" id="mail" name="mail" value="<?php echo $question['mail'];?>" />
	</p>
	<div class="cleaner"></div>
	<p><strong>
		<?php

			$note = ($question['notify'] == 1) ? _L('ic-notify-me') : '';
			echo $note;
		?></strong>
	</p>
</fieldset>
<fieldset><legend><?php echo _L('ic-answer');?></legend>
	<?php

		// include editor
		require_once DOC_ROOT . '/orbicon/rte/rte_components/toolbar.php';

		/*if(isset($_GET['edit'])){

			echo "
				<script type=\"text/javascript\"><!-- // --><![CDATA[
					function setEditText() {
						switch_mini_browser('venus', '', 0, 0);
						var content = '".addslashes(str_sanitize($answer['content'], STR_SANITIZE_JAVASCRIPT))."';
						// this sucks
						try {
							if(oToolbar) {
								oToolbar.body.innerHTML = content;
							}
							else {
								// another delay
								setTimeout(function () {oToolbar.body.innerHTML = content;}, 1000)
							}
						} catch(e) {}
						// * load db browser
					}

					YAHOO.util.Event.addListener(window, 'load', setTimeout(setEditText, 1000));

				// ]]></script>";
		}*/
	?>
</fieldset>
</form>
<div class="cleaner"></div>