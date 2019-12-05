<?php

	$orbicon_x->set_page_title(_L('pr-help'));

	if(isset($_POST['get_username'])) {
		$user_email = trim(strtolower($_POST['email_ret']));

		$retrieve = $dbc->_db->query(sprintf('	SELECT 	id
												FROM 	pring_contact
												WHERE	(contact_email = %s)
												LIMIT 	1', $dbc->_db->quote($user_email)));
		$retrieve = $dbc->_db->fetch_assoc($retrieve);

		if(!empty($retrieve['id'])) {
			$retrieve2 = $dbc->_db->query(sprintf('	SELECT 	*
													FROM 	'.TABLE_REG_USERS.'
													WHERE	(id = %s) AND
															(banned = 0)
													LIMIT 	1', $dbc->_db->quote($retrieve['id'])));
			$retrieve2 = $dbc->_db->fetch_assoc($retrieve2);

			if(!empty($retrieve2['id'])) {
				mail($user_email, DOMAIN_NAME . ': ' . ORBX_FULL_NAME . ' ' . _L('username_ret'), $retrieve2['username']);

				$feedback = '<div id="pring_help_feedback">Uspješno ste poslali podatke.<br />Korisničko ime će Vam biti poslano na e-mail adresu.</div>';
			}
			else {
				$feedback = '<div id="pring_help_feedback">Navedena e-mail adresa ne postoji u bazi.</div>';
			}
		}
		else {
			$feedback = '<div id="pring_help_feedback">Navedena e-mail adresa ne postoji u bazi.</div>';
		}
	}
	elseif (isset($_POST['reset_pwd'])) {
		// * clean up inputs
		$user_login = trim(strtolower($_POST['username']));
		$user_email = trim(strtolower($_POST['email']));

		$retrieve = $dbc->_db->query(sprintf('	SELECT 	*
												FROM 	'.TABLE_REG_USERS.'
												WHERE	(username = %s) AND
														(banned = 0)
												LIMIT 	1', $dbc->_db->quote($user_login)));
		$retrieve = $dbc->_db->fetch_assoc($retrieve);

		if(!empty($retrieve['id'])) {
			$retrieve2 = $dbc->_db->query(sprintf('	SELECT 	contact_email
													FROM 	pring_contact
													WHERE	(id = %s) AND
															(contact_email = %s)
													LIMIT 	1', $dbc->_db->quote($retrieve['pring_contact_id']), $dbc->_db->quote($user_email)));
			$retrieve2 = $dbc->_db->fetch_assoc($retrieve2);

			if($user_email == $retrieve2['contact_email']) {
				if(is_email($user_email)) {
					$new_pwd = generate_password();
					$q = sprintf('	UPDATE 		'.TABLE_REG_USERS.'
									SET 		pwd = PASSWORD(%s)
									WHERE 		(id = %s)
									LIMIT 		1',
					$dbc->_db->quote($new_pwd), $dbc->_db->quote($retrieve['id']));
					$dbc->_db->query($q);

					mail($user_email, DOMAIN_NAME . ': ' . ORBX_FULL_NAME . ' ' . _L('pwd_reset'), $new_pwd);

					$feedback = '<div id="pring_help_feedback">Uspješno ste poslali podatke.<br />Šifra će Vam biti poslana na e-mail adresu.</div>';
				}
				else {
					$feedback = '<div id="pring_help_feedback">Navedena e-mail adresa ne postoji u bazi.</div>';
				}
			}
			else {
				$feedback = '<div id="pring_help_feedback">Navedena e-mail adresa ne postoji u bazi.</div>';
			}
		}
		else {
			$feedback = '<div id="pring_help_feedback">Korisnik ne postoji u bazi.</div>';
		}
	}

	$display_content = $feedback . '
	<form method="post" action="" id="form_help">
	<div id="get_username">
		<p>'._L('pr-retreive-username-msg').'.</p>

		<fieldset>
			<legend>'._L('pr-retreive-username').'</legend>

			<label for="email_ret">'._L('email').'</label><br />
			<input id="email_ret" name="email_ret" /><br />
			<input type="submit" value="'._L('pr-retreive-username').'" id="get_username" name="get_username" />
		</fieldset>
	</div>

	<p>&nbsp;</p>

	<div id="get_new_pwd">
		<p>'._L('pr-retreive-new-pwd-msg').'.</p>
		<fieldset>
			<legend>'._L('pr-retreive-new-pwd').'</legend>
			<label for="username">'._L('username').'</label><br />
			<input id="username" name="username" type="text" /><br />
			<label for="email">'._L('email').'</label><br />
			<input id="email" name="email" type="text" /><br />
			<input type="submit" value="'._L('pr-retreive-new-pwd').'" id="reset_pwd" name="reset_pwd" />
		</fieldset>
	</div>
</form>';

?>