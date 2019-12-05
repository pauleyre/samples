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

	/**
	 * Generate sitemap(s)
	 *
	 */
	function generate_sitemaps()
	{
		return false;
		$server = empty($_SERVER['SERVER_NAME']) ? DOMAIN . ORBX_URI_PATH : $_SERVER['SERVER_NAME'] . ORBX_URI_PATH;
		// google sitemaps must be in top level dir
		$g_dir = DOC_ROOT . '/';
		$g_dir_url = ORBX_SITE_URL . '/';
		$dir = DOC_ROOT . '/site/mercury/';

		chmod_unlock($g_dir . 'sitemap.xml');
		chmod_unlock($dir . 'urllist.txt');

		$r = fopen($g_dir . 'sitemap.xml', 'wb');
		$r_y = fopen($dir . 'urllist.txt', 'wb');

		if(!$r) {
			return false;
		}

		/* Set a 64k buffer. */
		if(function_exists('stream_set_write_buffer')) {
			stream_set_write_buffer($r, 65535);
			stream_set_write_buffer($r_y, 65535);
		}

		$lastmod_now = date('Y-m-d', time());

		$sitemap = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">
<url><loc>'.SCHEME.'://'.$server.'/</loc><lastmod>'.$lastmod_now.'</lastmod><changefreq>daily</changefreq><priority>1.00</priority></url>';

		$sitemap_y = SCHEME . "://$server/\n";

		fwrite($r, $sitemap);
		fwrite($r_y, $sitemap_y);

		global $dbc, $orbicon_x, $orbx_mod;

		// news
		if($orbx_mod->validate_module('news')) {
			$r_sql = $dbc->_db->query('	SELECT 		*
										FROM 		'.TABLE_NEWS.'
										WHERE 		(live = 1)
										ORDER BY 	date DESC');
			$a = $dbc->_db->fetch_assoc($r_sql);

			while($a) {
				$item_url = url(SCHEME . '://' . $server . '/?' . $a['language'] . '=' . htmlspecialchars($a['permalink']), SCHEME . '://' . $server . '/' . $a['language'] . '/' . htmlspecialchars($a['permalink']));
				$sitemap = '<url><loc>'.$item_url.'</loc><lastmod>'.date('Y-m-d', $a['date']).'</lastmod><changefreq>daily</changefreq><priority>0.50</priority></url>'."\n";
				$sitemap_y = "$item_url\n";

				fwrite($r, $sitemap);
				fwrite($r_y, $sitemap_y);

				$a = $dbc->_db->fetch_assoc($r_sql);
			}

			// news categories
			$r_sql = $dbc->_db->query('	SELECT 		*
										FROM 		'.TABLE_NEWS_CAT);
			$a = $dbc->_db->fetch_assoc($r_sql);

			while($a) {
				$item_url = url(SCHEME.'://'.$server.'/?'.$a['language'].'='.htmlspecialchars($a['permalink']), SCHEME.'://'.$server.'/'.$a['language'].'/'.htmlspecialchars($a['permalink']));
				$sitemap = '<url><loc>'.$item_url.'</loc><lastmod>'.date('Y-m-d', $a['date']).'</lastmod><changefreq>daily</changefreq><priority>0.50</priority></url>'."\n";
				$sitemap_y = "$item_url\n";

				fwrite($r, $sitemap);
				fwrite($r_y, $sitemap_y);

				$a = $dbc->_db->fetch_assoc($r_sql);
			}
		}

		// columns
		$dbc->_db->free_result($r_sql);
		$r_sql = $dbc->_db->query('		SELECT 		*
										FROM 		'.TABLE_COLUMNS.'
										WHERE 		(menu_name != \'box\')
										ORDER BY 	lastmod DESC');
		$a = $dbc->_db->fetch_assoc($r_sql);

		while($a) {
			if(empty($a['redirect'])) {
				$item_url = url(SCHEME.'://'.$server.'/?'.$a['language'].'='.htmlspecialchars($a['permalink']), SCHEME.'://'.$server.'/'.$a['language'].'/'.htmlspecialchars($a['permalink']));
				$sitemap = '<url><loc>'.$item_url.'</loc><lastmod>'.date('Y-m-d', $a['lastmod']).'</lastmod><changefreq>daily</changefreq><priority>0.50</priority></url>'."\n";
				$sitemap_y = "$item_url\n";

				fwrite($r, $sitemap);
				fwrite($r_y, $sitemap_y);
			}
			$a = $dbc->_db->fetch_assoc($r_sql);
		}

		// free memory
		unset($sitemap, $sitemap_y);

		fwrite($r, '</urlset>');
		fwrite($r_y, "\n");

		fclose($r);
		fclose($r_y);

		// create gziped files
		if(function_exists('gzencode')) {
			$data = implode('', file($g_dir . 'sitemap.xml'));
			$gzdata = gzencode($data);
			unset($data);
			$fp = fopen($g_dir . 'sitemap.xml.gz', 'wb');
			/* Set a 64k buffer. */
			if(function_exists('stream_set_write_buffer')) {
				stream_set_write_buffer($fp, 65535);
			}
			fwrite($fp, $gzdata);
			unset($gzdata);
			fclose($fp);

			$data = implode('', file($dir . 'urllist.txt'));
			$gzdata = gzencode($data);
			unset($data);
			$fp = fopen($dir . 'urllist.txt.gz', 'wb');
			/* Set a 64k buffer. */
			if(function_exists('stream_set_write_buffer')) {
				stream_set_write_buffer($fp, 65535);
			}
			fwrite($fp, $gzdata);
			unset($gzdata);
			fclose($fp);
			// sitemap is ready, ping Google
			pingGoogleSitemaps($g_dir_url . 'sitemap.xml.gz');
			ping_ask_sitemaps($g_dir_url . 'sitemap.xml.gz');
		}
		else {
			// sitemaps is ready, ping Google
			pingGoogleSitemaps($g_dir_url . 'sitemap.xml');
			ping_ask_sitemaps($g_dir_url . 'sitemap.xml');
		}

		update_sync_cache_list($g_dir . 'sitemap.xml');
		update_sync_cache_list($g_dir . 'sitemap.xml.gz');
	}

	/**
	* Function to ping Google Sitemaps.
	*
	* Function to ping Google Sitemaps. Returns an integer, e.g. 200 or 404,
	* 0 on error.
	*
	* @author     J de Silva                           <giddomains@gmail.com>
	* @copyright  Copyright &copy; 2005, J de Silva
	* @link       http://www.gidnetwork.com/b-54.html  PHP function to ping Google Sitemaps
	* @param      string   $url_xml  The sitemap url, e.g. http://www.example.com/google-sitemap-index.xml
	* @return     integer            Status code, e.g. 200|404|302 or 0 on error
	*/
	function pingGoogleSitemaps($url_xml)
	{
		$status = 0;
		$google = 'www.google.com';
		$fp = fsockopen($google, 80);
		$m = null;

		if($fp) {

			$req =
			'GET /webmasters/sitemaps/ping?sitemap=' . urlencode($url_xml) . " HTTP/1.1\r\n" .
			"Host: $google\r\n" .
			'User-Agent: Mozilla/5.0 (compatible; ' . PHP_OS . ') PHP/' . PHP_VERSION . "\r\n" .
			"Connection: Close\r\n\r\n";

			fwrite($fp, $req);

			while(!feof($fp)) {
				if(preg_match('~^HTTP/\d\.\d (\d+)~i', fgets($fp, 128), $m)) {
					$status = intval($m[1]);
					break;
				}
			}
			fclose($fp);
		}
		return $status;
	}


	/**
	* Function to ping Ask Sitemaps.
	*
	* Function to ping Ask Sitemaps. Returns an integer, e.g. 200 or 404,
	* 0 on error.
	*
	* @author Pavle Gardijan <pavle.gardijan@orbitum.net>
	* @param string $url_xml  The sitemap url, e.g. http://www.example.com/google-sitemap-index.xml
	* @return integer  Status code, e.g. 200|404|302 or 0 on error
	*/
	function ping_ask_sitemaps($url_xml)
	{
		$status = 0;
		$ask = 'submissions.ask.com';
		$fp = fsockopen($ask, 80);
		$m = null;

		if($fp) {

			$req =
			'GET /ping?sitemap=' . urlencode($url_xml) . " HTTP/1.1\r\n" .
			"Host: $ask\r\n" .
			'User-Agent: Mozilla/5.0 (compatible; ' . PHP_OS . ') PHP/' . PHP_VERSION . "\r\n" .
			"Connection: Close\r\n\r\n";

			fwrite($fp, $req);

			while(!feof($fp)) {
				if(preg_match('~^HTTP/\d\.\d (\d+)~i', fgets($fp, 128), $m)) {
					$status = intval($m[1]);
					break;
				}
			}
			fclose($fp);
		}
		return $status;
	}

?>