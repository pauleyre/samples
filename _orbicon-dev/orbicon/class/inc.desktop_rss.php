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

	function display_desktop_rss($rss)
	{
		global $orbx_log;
		$url = $rss;
		$backup_url = parse_url($url);
		$rss_feed_content = '';

		if(!empty($url)) {
			/*-- TO DO ------------------------------------------------------
			| In PHP5 revision replace bottom two lines with simple xml load
			| simplexml_load_file().
			|---------------------------------------------------------------*/
			include_once DOC_ROOT.'/orbicon/3rdParty/magpierss/rss_fetch.php';
			if(get_socket_timeout($url, 2) === true) {
				$orbx_log->ewrite('failed to access '.$url.'. check your network / firewall settings', __LINE__, __FUNCTION__);
				return false;
			}

			$rss = fetch_rss($url);
			$rss->items = array_slice($rss->items, 0, 5);

			$rss_feed_content = '<div style="text-align:right;"><strong>'.htmlentities($rss->channel['title']).'</strong></div>';
			$rss_feed_content .= '<ol>';

			foreach($rss->items as $item) {
				$href = (empty($item['link'])) ? $item['guid'] : $item['link'];
				$aURL = parse_url($href);

				$href = (empty($aURL['host'])) ? $backup_url['scheme'] . '://' . $backup_url['host'].$href : $href;

				$title = $item['title'];
				$description = $item['description'];
				$pubdate = $item['pubdate'];
				$rss_feed_content .= "<li><a target=\"_blank\" href=\"$href\" title=\"$href\">$title</a><br /><em>$pubdate</em><div style=\"overflow:hidden;\">$description</div></li>";
			}
			$rss_feed_content .= '</ol>';
		}
		return utf8_html_entities($rss_feed_content);
	}

	function load_desktop_rss()
	{
		global $dbc;
		$q = sprintf('	SELECT 	rss_url
						FROM 	'.TABLE_DESKTOP_RSS.'
						WHERE 	(owner_id=%s)', $dbc->_db->quote($_SESSION['user.a']['id']));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_assoc($r);

		$my_rss = array();

		while($a) {
			$my_rss[] = $a['rss_url'];
			$a = $dbc->_db->fetch_assoc($r);
		}
		return $my_rss;
	}

	function remove_desktop_rss()
	{
		if(isset($_GET['remove-rss']) && !empty($_GET['remove-rss'])) {
			global $dbc, $orbicon_x;
			$q = sprintf('	DELETE 	FROM '.TABLE_DESKTOP_RSS.'
							WHERE 	(owner_id=%s) AND
									(rss_url=%s)
							LIMIT	1', $dbc->_db->quote($_SESSION['user.a']['id']), $dbc->_db->quote($_GET['remove-rss']));

			$dbc->_db->query($q);

			redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon');
		}
	}

	function add_desktop_rss()
	{
		if(isset($_POST['add_rss'])) {
			global $dbc, $orbicon_x;

			$q = sprintf('	INSERT INTO 	'.TABLE_DESKTOP_RSS.'
											(rss_url, owner_id)
							VALUES 			(%s, %s)',
			$dbc->_db->quote($_POST['rss_feed']), $dbc->_db->quote($_SESSION['user.a']['id']));

			$dbc->_db->query($q);

			redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon');
		}
	}

	// returns true on timeout, false otherwise
	function get_socket_timeout($url, $sec)
	{
		$url = parse_url($url);

		$port = $url['port'];
		(int) $port = ($port == '') ? 80 : $port;

		$url = (strpos($url['host'], 'www.') === false) ? 'www.' . $url['host'] : $url['host'];

		$fp = fsockopen($url, $port, $errno, $errstr, $sec);
		if(!$fp) {
			return true;
		}
		return false;
	}

?>