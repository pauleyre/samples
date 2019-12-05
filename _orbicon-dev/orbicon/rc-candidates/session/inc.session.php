<?php
/*-----------------------------------------------------------------------*
	Project........:	Orbicon X framework 2
	File...........:	class.session.php
	Version........:	0.4 (16/10/2006)
	Author.........:	Pavle Gardijan (pavle.gardijan@orbitum.net) - Orbitum d.o.o.
	Copyright......:	(c) 2006 Orbitum internet communications d.o.o. (www.orbitum.net)
	Created........:	16/10/2006
	Notes..........:	store sessions in a database
	Modified.......:
*-----------------------------------------------------------------------*/



	// Attempt to start the session, unless it already has been.

	function session_start()
	{
		// Attempt to change a few PHP settings.
		ini_set('session.use_cookies', true);
		ini_set('session.use_only_cookies', false);
		ini_set('url_rewriter.tags', '');
		ini_set('session.use_trans_sid', false);
		ini_set('arg_separator.output', '&amp;');
		if(ORBX_DEBUG === false) {
			ini_set('session.cookie_domain', '.' . DOMAIN_NO_WWW);
		}

		// Attempt to end the already-started session.
		if(ini_get('session.auto_start') == 1) {
			session_write_close();
		}

		// This is here to stop people from using bad junky PHPSESSIDs.
		if(isset($_REQUEST[session_name()]) && preg_match('~^[A-Za-z0-9]{16,32}$~', $_REQUEST[session_name()]) == 0 && !isset($_COOKIE[session_name()])) {

			// seed for PHP < 4.2.0
			srand((float) microtime() * 10000000);

			$sess_id = md5(md5('orbx_sess_' . time()) . rand());
			$_REQUEST[session_name()] = $sess_id;
			$_GET[session_name()] = $sess_id;
			$_POST[session_name()] = $sess_id;
		}

		// Use database sessions? (they don't work in 4.1.x!)
		if(version_compare(PHP_VERSION, '4.2.0') != -1) {
			session_set_save_handler(
				array(&$this, '_session_open'),
				array(&$this, '_session_close'),
				array(&$this, '_session_read'),
				array(&$this, '_session_write'),
				array(&$this, '_session_destroy'),
				array(&$this, '_session_gc')
			);
		}
		else if((int) ini_get('session.gc_maxlifetime') <= 1200) {
			ini_set('session.gc_maxlifetime', '1200');
		}

		session_cache_limiter('private_no_expire, must-revalidate');

		if(session_id() == '') {
			session_start();
		}

		// * session fixation check
		if(!isset($_SESSION['orbx_session_started'])) {
			session_regenerate_id();
			$_SESSION['orbx_session_started'] = true;
		}

		// * session hijack protection
		if(isset($_SESSION['orbx_virtual_id_card'])) {
			if($_SESSION['orbx_virtual_id_card'] != md5(ORBX_USER_AGENT . $_SERVER['HTTP_ACCEPT_CHARSET'] . DOC_ROOT . session_id())) {
				session_destroy();
				header('Location: ' . ORBX_SITE_URL);
				trigger_error('Session hijack detected', E_USER_ERROR);
				exit();
			}
		}
		else {
			$_SESSION['orbx_virtual_id_card'] = md5(ORBX_USER_AGENT . $_SERVER['HTTP_ACCEPT_CHARSET'] . DOC_ROOT . session_id());
		}
	}

	function session_open($save_path, $session_name)
	{
		return true;
	}

	function session_close()
	{
		return true;
	}

	function session_read($session_id)
	{
		if(preg_match('~^[A-Za-z0-9]{16,32}$~', $session_id) == 0) {
			return false;
		}

		// Look for it in the database.
		global $dbc;

		$q = sprintf('	SELECT 	data
						FROM 	'.TABLE_SESSION.'
						WHERE 	((session_id = %s) AND
								(user_agent = %s) AND
								(ip = %s))
						LIMIT 	1',
						$dbc->_db->quote($session_id), $dbc->_db->quote(ORBX_USER_AGENT), $dbc->_db->quote(ORBX_CLIENT_IP)

						);

		$r = $dbc->_db->query($q);
		unset($q);
		$a = $dbc->_db->fetch_array($r);
		$dbc->_db->free_result($r);

		return (unserialize($a['data']));
	}

	function session_write($session_id, $data)
	{
		if(preg_match('~^[A-Za-z0-9]{16,32}$~', $session_id) == 0) {
			return false;
		}

		// First try to update an existing row...
		global $dbc;

		$data = serialize($data);

		$q = sprintf('	UPDATE 		'.TABLE_SESSION.'
						SET 		data = %s, last_update = %s
						WHERE 		((session_id = %s) AND
									(user_agent = %s) AND
									(ip = %s))
						LIMIT 		1',
						$dbc->_db->quote($data), $dbc->_db->quote(time()),
						$dbc->_db->quote($session_id), $dbc->_db->quote(ORBX_USER_AGENT),
						$dbc->_db->quote(ORBX_CLIENT_IP)
						);

		$r = $dbc->_db->query($q);

		// If that didn't work, try inserting a new one.
		if($dbc->_db->affected_rows() == 0) {

			$q = sprintf('	INSERT 		IGNORE
							INTO 		'.TABLE_SESSION.'
										(session_id, data,
										last_update, user_agent,
										ip)
							VALUES 		(%s, %s,
										%s, %s,
										%s)',
					$dbc->_db->quote($session_id), $dbc->_db->quote($data),
					$dbc->_db->quote(time()), $dbc->_db->quote(ORBX_USER_AGENT),
					$dbc->_db->quote(ORBX_CLIENT_IP)
					);

			$r = $dbc->_db->query($q);
		}
		return $r;
	}

	function session_destroy($session_id)
	{
		if(preg_match('~^[A-Za-z0-9]{16,32}$~', $session_id) == 0) {
			return false;
		}

		// Just delete the row...
		global $dbc;

		$q = sprintf('	DELETE
						FROM 	'.TABLE_SESSION.'
						WHERE 	((session_id = %s) AND
								(user_agent = %s) AND
								(ip = %s))
						LIMIT 	1',
						$dbc->_db->quote($session_id), $dbc->_db->quote(ORBX_USER_AGENT), $dbc->_db->quote(ORBX_CLIENT_IP)
						);

		return $dbc->_db->query($q);
	}

	function session_gc($max_lifetime)
	{
		// Clean up
		global $dbc;

		$q = sprintf('	DELETE
						FROM 	'.TABLE_SESSION.'
						WHERE 	(last_update < %s)',
						$dbc->_db->quote((time() - $max_lifetime))
						);

		return $dbc->_db->query($q);
	}

?>