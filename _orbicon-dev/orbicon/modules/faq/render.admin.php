<?php

require 'inc.faq.php';
require 'inc.faq-admin.php';

if(isset($_GET['qid'])) {

	$q = faq_get_q($_GET['qid'], true, -1);

	if(isset($_POST['save'])) {
		$id = faq_admin_post_q();
		if(!empty($_GET['qid'])) {
			$id = $_GET['qid'];
		}
		else {
			faq_category_total_inc($_POST['category']);
		}
		redirect(ORBX_SITE_URL . '/?qid='.$id.'&'.$orbicon_x->ptr.'=orbicon/mod/faq');
	}
	elseif (isset($_POST['email_send'])) {
		faq_admin_post_q(0);
		faq_admin_email();
	}
	elseif (isset($_POST['delete'])) {
		faq_admin_delete($_GET['qid'], $_POST['org_cat']);
	}

	$email_notify = ($q['email_notify']) ? '<span class="green">DA</span>' : '<span class="red">NE</span>';

echo '
<p><input onclick="redirect(\''.ORBX_SITE_URL . '/?'.$orbicon_x->ptr.'=orbicon/mod/faq\')" type="button" value="Povratak" /></p>
<form id="askQuestion" method="post" action="">
<input type="hidden" name="org_cat" value="'.$q['category'].'" />

	<p>
		<label for="poster">Ime i prezime</label><br/>
		<input style="width:300px" name="poster" id="poster" value="'.$q['poster'].'" type="text"/>
	</p>
	<p>
		Želi odgovor na e-mail<br/>
		'.$email_notify.'
	</p>
	<p>
		<label for="email">E-mail <span class="red">*</span></label><br/>
		<input style="width:300px" value="'.$q['email'].'" name="email" id="email" type="text"/>
	</p>
	<p>
		<label for="title">Upit <span class="red">*</span></label><br/>
		<textarea style="width:500px;height:150px;" name="title" id="title">'.$q['title'].'</textarea>
	</p>
	<p>
		<label for="category">Kategorija</label><br/>
			<select style="width:300px" id="category" name="category">
				'.faq_optionlist_categories($q['category']).'
			</select>
	</p>
	<p>
		<label for="answer">Odgovor <span class="red">*</span></label><br/>
		<textarea style="width:500px;height:150px;" name="answer" id="answer">'.$q['answer'].'</textarea>
	</p>
	<p id="ask_submit">
		<input onclick="return myconfirm(\'save\')" class="chk_btn" name="save" value="Spremi i objavi odgovor javno" type="submit"/>
		<input onclick="return myconfirm(\'email\')" class="chk_btn" name="email_send" value="Spremi i pošalji na e-mail" type="submit"/>
		<input onclick="return myconfirm(\'delete\')" class="chk_btn" name="delete" value="Ukloni pitanje i odgovor" type="submit"/>
	</p>
</form>
<script>

function myconfirm(type)
{
	var msg;
	if(type == "save") {
		msg = "Objavi odgovor javno?";
	}
	else if(type == "email") {
		msg = "Pošalji odgovor na e-mail?";
	}
	else if(type == "delete") {
		msg = "Ukloni pitanje i odgovor iz baze?";
	}

	if(window.confirm(msg)) {
		return true;
	}
	return false;
}


</script>
';

}
else {
	echo '<p><input onclick="redirect(\''.ORBX_SITE_URL . '/?qid&'.$orbicon_x->ptr.'=orbicon/mod/faq\')" type="button" value="Upisivanje novog pitanja" /></p>';
	echo faq_admin_all();

}

echo '<br/>';

?>