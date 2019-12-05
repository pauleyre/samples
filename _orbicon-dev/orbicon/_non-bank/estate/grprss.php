<?php
/**
 * Group RSS
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package OrbiconMOD
 * @subpackage Estate
 * @version 1.00
 * @link http://
 * @license http://
 * @since 2008-01-29
 */

	/*----- ATTENTION! -------------------------------------------------
	 | Include before wherever DOC_ROOT required.
	 |------------------------------------------------------------------*/
	if(!defined('DOC_ROOT')) {
		// we'll need this info
		$dir = dirname(dirname(__FILE__));

		$file = $dir . '/index.php';
		$license = $dir . '/license.php';
		$found = false;

		while(!$found) {

			$dir = dirname(dirname($file));

			$license = $dir . '/license.php';
			$file = $dir . '/index.php';

			if(is_file($file) && is_file($license)) {
				$found = true;
				break;
			}
		}

		$request_uri = $_SERVER['REQUEST_URI'];

		$left = str_replace($dir, '', dirname(__FILE__));
		$left = str_replace('\\', '/', $left);
		$left = str_replace($left, '', $request_uri);
		// get rid of query
		$left = explode('?', $left);
		// file or no file?
		$left = (strpos($left[0], '.php')) ? dirname($left[0]) : $left[0];
		// strip forward slash as well
		$left = (substr($left, -1, 1) == '/') ? substr($left, 0, -1) : $left;

		define('ORBX_URI_PATH', $left);
		define('DOC_ROOT', $dir);
		unset($left, $request_uri);
	}
	//----- ATTENTION! ENDS -------------------------------------------------

	// core include
	require DOC_ROOT . '/orbicon/class/inc.core.php';

	require_once DOC_ROOT . '/orbicon/modules/estate/inc.estate.php';

/**
 * Create group RSS for main category
 *
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @param string $category
 * @return string
 */
function print_estate_grprss($main_category = '')
{
	global $dbc, $orbicon_x, $estate_type_p;

	$q = '	SELECT 		permalink
			FROM 		'.TABLE_COLUMNS.'
			WHERE		(parent = '.$dbc->_db->quote($main_category).') AND
						(language = '.$dbc->_db->quote($orbicon_x->ptr).')';

	$r = $dbc->_db->query($q);
	$c = $dbc->_db->fetch_assoc($r);

	$rss = '<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet href="http://www.w3.org/2000/08/w3c-synd/style.css" type="text/css"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
	<atom:link href="'.ORBX_SITE_URL.'/orbicon/modules/estate/grprss.php?c='.urlencode($main_category).'" rel="self" type="application/rss+xml" />
	<title>'.DOMAIN_NAME.'</title>
	<link>'.ORBX_SITE_URL.'/</link>
	<description>'.DOMAIN_DESC.'</description>
	<lastBuildDate>'.date('r').'</lastBuildDate>
	<generator>'.ORBX_FULL_NAME.'</generator>
	<language>'.$orbicon_x->ptr.'</language>
	<copyright>Copyright '.date('Y').', '.DOMAIN_OWNER.'</copyright>
	<managingEditor>'.DOMAIN_EMAIL.' ('.DOMAIN_NAME.')</managingEditor>
	<webMaster>'.DOMAIN_EMAIL.' ('.DOMAIN_NAME.')</webMaster>
	<docs>http://blogs.law.harvard.edu/tech/rss</docs>' . "\n";

	while ($c) {
		$menu_sql = sprintf(' AND (menu=%s) ', $dbc->_db->quote($c['permalink']));

		$q = '	SELECT 		*
				FROM 		'.TABLE_ESTATE.'
				WHERE		(status = '.$dbc->_db->quote(ESTATE_AD_LIVE).')' .
				$menu_sql.'
				ORDER BY 	submited DESC
				LIMIT 		3';

		$r2 = $dbc->_db->query($q);
		$estate = $dbc->_db->fetch_object($r2);

		while($estate) {
			$desc = strip_tags($estate->description);
			$desc = trim(str_replace(array("\n", '<', '&nbsp;', '\''), array('', '&lt;', ' ', '&#039;'), $desc));
			$url = url(ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.e&amp;c=' . urlencode($estate_type_p[$estate->category] . '/' . urlencode($estate->permalink) . '/' . $estate->id), ORBX_SITE_URL . '/' . urlencode($estate_type_p[$estate->category] . '/' . urlencode($estate->permalink) . '/' . $estate->id));
			$title = utf8_html_entities($estate->title);

			$rss .= '<item>
		<title>'.$title.'</title>
		<link>'.$url.'</link>
		<description>'.$desc.'</description>
		<pubDate>'.date('r', $estate->submited).'</pubDate>
		<guid isPermaLink="true">'.$url.'</guid>
		<author>'.DOMAIN_EMAIL.' ('.DOMAIN_NAME.')</author>
		<source url="'.ORBX_SITE_URL.'/orbicon/modules/estate/rss.php/?c='.$category.'">'.DOMAIN_NAME.'</source>
	</item>'."\n";
				$estate = $dbc->_db->fetch_object($r2);
		}
		$c = $dbc->_db->fetch_assoc($r);
	}

	$rss .= ' </channel>
</rss>';

	return $rss;
}

	header('Content-Type: application/xml', true);

	echo print_estate_grprss($_GET['c']);

?>