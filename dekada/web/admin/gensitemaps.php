<?php
error_reporting(E_ALL);
// -- INCLUDE PATH SETUP -----------------------------
$inc_dir = dirname(dirname(__FILE__));

$inc_root = $inc_dir . '/root.dir';

$inc_found = false;

while(!$inc_found) {

	if(is_file($inc_root)) {
		$inc_found = true;
		break;
	}

	$inc_dir = dirname(dirname($inc_root));

	$inc_root = $inc_dir . '/root.dir';
}

set_include_path($inc_dir);
// -- INCLUDE PATH SETUP ENDS -------------------------

generate_sitemaps();
	/**
	 * Generate sitemap(s)
	 *
	 */
	function generate_sitemaps()
	{
		// google sitemaps must be in top level dir
		$g_dir = '../../';
		$g_dir_url = 'http://www.dekada.org/';
		$dir = '../../';

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
<url><loc>http://www.dekada.org/</loc><changefreq>daily</changefreq></url>
<url><loc>http://www.dekada.org/?kategorije</loc><changefreq>monthly</changefreq></url>
<url><loc>http://www.dekada.org/postavite-pitanje.php</loc><changefreq>monthly</changefreq></url>
<url><loc>http://www.dekada.org/?neodgovorena-pitanja</loc><changefreq>daily</changefreq></url>
<url><loc>http://www.dekada.org/?najnoviji-odgovori</loc><changefreq>daily</changefreq></url>
<url><loc>http://www.dekada.org/top10.php</loc><changefreq>monthly</changefreq></url>
<url><loc>http://www.dekada.org/prijavite-se.php</loc><changefreq>monthly</changefreq></url>
<url><loc>http://www.dekada.org/kontakt.php</loc><changefreq>monthly</changefreq></url>
<url><loc>http://www.dekada.org/registracija.php</loc><changefreq>monthly</changefreq></url>';

		$sitemap_y = "http://www.dekada.org/
http://www.dekada.org/?kategorije
http://www.dekada.org/postavite-pitanje.php
http://www.dekada.org/?neodgovorena-pitanja
http://www.dekada.org/?najnoviji-odgovori
http://www.dekada.org/top10.php
http://www.dekada.org/prijavite-se.php
http://www.dekada.org/kontakt.php
http://www.dekada.org/registracija.php\n";

		fwrite($r, $sitemap);
		fwrite($r_y, $sitemap_y);

		include 'logic/func.categories.php';
		$categories = get_categories(true);


		foreach ($categories as $cat) {

			$item_url = 'http://www.dekada.org/?c='.$cat;
			$sitemap = '<url><loc>'.$item_url.'</loc><changefreq>daily</changefreq></url>'."\n";
			$sitemap_y = "$item_url\n";

			fwrite($r, $sitemap);
			fwrite($r_y, $sitemap_y);
		}

		include 'logic/class.Question.php';
		$q = new Question();
		$questionsRes = $q->getQuestions('', '', 1);
		$questionsList = $db->fetch_assoc($questionsRes);

		while($questionsList) {

			$item_url = 'http://www.dekada.org/?'.$questionsList['category'].','.$questionsList['permalink'].'&amp;d='.$questionsList['id'];
			$sitemap = '<url><loc>'.$item_url.'</loc><changefreq>weekly</changefreq></url>'."\n";
			$item_url = str_replace('&amp;', '&', $item_url);
			$sitemap_y = "$item_url\n";

			fwrite($r, $sitemap);
			fwrite($r_y, $sitemap_y);

			$questionsList = $db->fetch_assoc($questionsRes);
		}

		// free memory
		unset($sitemap, $sitemap_y);

		fwrite($r, '</urlset>');
		//fwrite($r_y, "\n");

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
			//pingGoogleSitemaps($g_dir_url . 'sitemap.xml.gz');
			//ping_ask_sitemaps($g_dir_url . 'sitemap.xml.gz');
		}
		else {
			// sitemaps is ready, ping Google
			//pingGoogleSitemaps($g_dir_url . 'sitemap.xml');
			//ping_ask_sitemaps($g_dir_url . 'sitemap.xml');
		}
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