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

// w3c is ok
define('ORBX_RSS_STYLESHEET', 'http://www.w3.org/2000/08/w3c-synd/style.css');

class RSS_Manager
{
	var $__my_rss_feeds;
	var $rss_feed_list;
	var $rss_feed_content;

	function rss_manager()
	{
		$this->__construct();
	}

	function __construct()
	{
		$this->__my_rss_feeds = array();
		$this->rss_feed_list = '';
		$this->rss_feed_content = '';
	}

	function display_rss()
	{
		global $orbicon_x;

		foreach($this->__my_rss_feeds as $rss_feed_url) {
			$rss_url = parse_url($rss_feed_url);
			if(!empty($rss_url['host'])) {
				$this->rss_feed_list .= '<li><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr."=orbicon/mod/rss&amp;url=$rss_feed_url\" title=\"$rss_feed_url\">{$rss_url['host']}</a> (&lt;!&gt;MYRSS_".strtoupper(get_permalink($rss_feed_url)).") <a href=\"".ORBX_SITE_URL.'/?'.$orbicon_x->ptr."=orbicon/mod/rss&amp;remove-rss=$rss_feed_url\" style=\"font-weight: bold;\" title=\""._L('delete')."?\"><img src=\"".ORBX_SITE_URL."/orbicon/gfx/gui_icons/delete.png\" alt=\""._L('delete')."\" title=\""._L('delete')."\" style=\"vertical-align:bottom;\" /></a></li>";
			}
		}

		$url = $_GET['url'];
		$backup_url = parse_url($url);

		if(!empty($url)) {
			/**
			 * @todo In PHP5 revision replace bottom two lines with simple xml load
			 * simplexml_load_file().
			 */
			include_once DOC_ROOT.'/orbicon/3rdParty/magpierss/rss_fetch.php';
			$rss = fetch_rss($url);

			$this -> rss_feed_content = '<strong>Kanal: <span class="u">'.htmlentities($rss->channel['title']).'</span></strong> <code>('.htmlentities($url).')</code><p>';
			$this -> rss_feed_content .= '<ol>';

			foreach($rss->items as $item) {
				$href = (empty($item['link'])) ? $item['guid'] : $item['link'];
				$aURL = parse_url($href);

				$href = (empty($aURL['host'])) ? $backup_url['scheme'] . '://' . $backup_url['host'].$href : $href;

				$title = $item['title'];
				$description = $item['description'];
				$pubdate = $item['pubdate'];
				$this->rss_feed_content .= "<li><a target=\"_blank\" href=\"$href\" title=\"$href\">$title</a> <sub><em>$pubdate</em></sub><p>
<code style=\"font-size:small;\">$description</code></p></li>";
			}
			$this->rss_feed_content .= '</ol></p>';
		}
		$this->rss_feed_content = utf8_html_entities($this->rss_feed_content);
	}

	function load_current_rss()
	{
		global $dbc;
		$q = sprintf('	SELECT 	value
						FROM 	%s
						WHERE 	(setting=\'rss_feeds\')
						LIMIT 	1', TABLE_SETTINGS);

		$a = $dbc->_db->get_cache($q);
		if($a === null) {
			$r = $dbc->_db->query($q);
			$a = $dbc->_db->fetch_assoc($r);
			$dbc->_db->put_cache($a, $q);
		}

		$this->__my_rss_feeds = explode('|', $a['value']);
		$this->__my_rss_feeds = array_remove_empty($this->__my_rss_feeds);
	}

	function remove_rss()
	{
		if(isset($_GET['remove-rss']) && !empty($_GET['remove-rss'])) {
			$this -> __my_rss_feeds = array_flip($this -> __my_rss_feeds);
			unset($this -> __my_rss_feeds[$_GET['remove-rss']]);
			$this -> __my_rss_feeds = array_keys($this -> __my_rss_feeds);
			$this -> __my_rss_feeds = array_remove_empty($this -> __my_rss_feeds);
			$feeds = implode('|', $this->__my_rss_feeds);

			global $dbc, $orbicon_x;
			$q = sprintf('	UPDATE 		%s
							SET 		value=%s
							WHERE 		(setting=\'rss_feeds\')', TABLE_SETTINGS, $dbc->_db->quote($feeds));

			$dbc->_db->query($q);

			redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/rss');
		}
	}

	function add_rss()
	{
		if(isset($_POST['add_rss']) || isset($_GET['add-rss'])) {
			$this -> __my_rss_feeds[] = (!empty($_GET['add-rss'])) ? $_GET['add-rss'] : $_POST['rss_feed'];
			array_unique($this->__my_rss_feeds);
			$this->__my_rss_feeds = array_remove_empty($this->__my_rss_feeds);
			$feeds = implode('|', $this->__my_rss_feeds);

			global $dbc, $orbicon_x;
			$q = sprintf('UPDATE 	%s
							SET 	value=%s
							WHERE 	(setting=\'rss_feeds\')', TABLE_SETTINGS, $dbc->_db->quote($feeds));

			$dbc->_db->query($q);

			redirect(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/rss');
		}
	}

	function get_newsticker_content()
	{
		if(scan_templates('<!>NWS_TICKER') < 1) {
			return false;
		}

		$this->load_current_rss();
		require_once DOC_ROOT . '/orbicon/3rdParty/magpierss/rss_fetch.php';

		foreach($this->__my_rss_feeds as $value) {
			$rss = @fetch_rss($value);
			if(!empty($rss->items)) {
				$backup_url = parse_url($value);

				$aRSS[] = '<a target="_blank" href="' . $rss->channel['link'] . '" title="'.$rss->channel['description'].'" style="font-weight:bold;">'.$rss->channel['title'].'</a>';

				$rss->items = array_slice($rss->items, 0, $_SESSION['site_settings']['max_rss_items']);

				foreach($rss->items as $item) {
					$href = (empty($item['link'])) ? $item['guid'] : $item['link'];
					$aURL = parse_url($href);

					$href = (empty($aURL['host'])) ? 'http://'.$backup_url['host'].$href : $href;

					$title = $item['title'];

					$aRSS[] = "<a target=\"_blank\" href=\"$href\">&raquo; $title</a>";
				}
			}
		}

		if(empty($aRSS)) {
			return null;
		}

		$news = implode(' | ', $aRSS);
		$news = addslashes($news);
		$news = str_sanitize($news, STR_SANITIZE_JAVASCRIPT);
		return $news;
	}

	// create news rss
	function build_news_rss($rss_push = false)
	{
		global $dbc, $orbicon_x;

		$rss_filename = ($rss_push) ? 'rss_top.'.$orbicon_x->ptr.'.xml' : 'rss.'.$orbicon_x->ptr.'.xml';
		$rss_push_sql = ($rss_push) ? 'AND (rss_push = 1)' : '';

		chmod_unlock(DOC_ROOT.'/site/mercury/'.$rss_filename);
		$r = fopen(DOC_ROOT.'/site/mercury/'.$rss_filename, 'wb');
		$rss = '<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet href="'.ORBX_RSS_STYLESHEET.'" type="text/css"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<atom:link href="'.ORBX_SITE_URL.'/site/mercury/'.$rss_filename.'" rel="self" type="application/rss+xml" />
		<title>'.DOMAIN_NAME.'</title>
		<link>'.ORBX_SITE_URL.'/</link>
		<description>'.DOMAIN_DESC.'</description>
		<lastBuildDate>'.date('r').'</lastBuildDate>
		<generator>'.ORBX_FULL_NAME.'</generator>
		<language>'.$orbicon_x->ptr.'</language>
		<copyright>Copyright '.date('Y').', '.DOMAIN_OWNER.'</copyright>
		<managingEditor>'.DOMAIN_EMAIL.' ('.DOMAIN_NAME.')</managingEditor>
		<webMaster>'.DOMAIN_EMAIL.' ('.DOMAIN_NAME.')</webMaster>
		<docs>http://blogs.law.harvard.edu/tech/rss</docs>';
		$r_ = $dbc->_db->query(sprintf('	SELECT 		*
											FROM 		'.TABLE_NEWS.'
											WHERE 		(live = 1) AND
														(language = %s) '.$rss_push_sql.'
											ORDER BY 	date DESC
											LIMIT 		20', $dbc->_db->quote($orbicon_x->ptr)));
		$news = $dbc->_db->fetch_assoc($r_);

		while($news) {
			$_r = $dbc->_db->query(sprintf('	SELECT 	content
												FROM 	'.MAGISTER_CONTENTS.'
												WHERE 	(live = 1) AND
														(hidden = 0) AND
														(id = %s) AND
														(language = %s)
												LIMIT 	1', $dbc->_db->quote($news['intro']), $dbc->_db->quote($orbicon_x->ptr)));
			$_a = $dbc->_db->fetch_assoc($_r);

			$desc = strip_tags($_a['content']);
			$desc = trim(str_replace(array("\n", '<', '&nbsp;', '\''), array('', '&lt;', ' ', '&#039;'), $desc));

			$url = url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.urlencode($news['permalink']), ORBX_SITE_URL.'/'.$orbicon_x->ptr.'/'.urlencode($news['permalink']));

			$rss .= '<item>
		<title>'.utf8_html_entities($news['title']).'</title>
		<link>'.$url.'</link>
		<description>'.$desc.'</description>
		<pubDate>'.date('r', $news['date']).'</pubDate>
		<guid isPermaLink="true">'.$url.'</guid>
		<author>'.DOMAIN_EMAIL.' ('.DOMAIN_NAME.')</author>
		<source url="'.ORBX_SITE_URL.'/site/mercury/'.$rss_filename.'">'.DOMAIN_NAME.'</source>
	</item>'."\n";
  			$news = $dbc->_db->fetch_assoc($r_);
		}
		$rss .= ' </channel>
</rss>';
		/* Set a 64k buffer. */
		if(function_exists('stream_set_write_buffer')) {
			stream_set_write_buffer($r, 65535);
		}
		fwrite($r, $rss);
		fclose($r);

	}

	// create news rdf
	function build_news_rdf($rdf_push = false)
	{
		global $dbc, $orbicon_x;

		$rdf_filename = ($rdf_push) ? 'rss_top.'.$orbicon_x->ptr.'.xml' : 'rss.'.$orbicon_x->ptr.'.xml';
		$rdf_push_sql = ($rdf_push) ? ' AND (rss_push = 1)' : '';

		chmod_unlock(DOC_ROOT.'/site/mercury/'.$rdf_filename);
		$r = fopen(DOC_ROOT.'/site/mercury/'.$rdf_filename, 'wb');

		$rdf = '<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet href="'.ORBX_RSS_STYLESHEET.'" type="text/css"?>
<rdf:RDF xmlns="http://purl.org/rss/1.0/"
         xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
         xmlns:dc="http://purl.org/dc/elements/1.1/"
         xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
         xmlns:hr="http://www.w3.org/2000/08/w3c-synd/#"
         xmlns:h="http://www.w3.org/1999/xhtml">
	<channel rdf:about="'.ORBX_SITE_URL.'/site/mercury/'.$rdf_filename.'">
		<title>'.DOMAIN_NAME.'</title>
		<description>'.DOMAIN_DESC.'</description>
		<link>'.ORBX_SITE_URL.'/</link>
		<dc:date>'.date('Y-m-d').'</dc:date>
		<dc:language>'.$orbicon_x->ptr.'</dc:language>
		<dc:rights>Copyright '.date('Y').', '.DOMAIN_OWNER.'. All Rights Reserved.</dc:rights>
		<dc:publisher>'.DOMAIN_OWNER.'</dc:publisher>
		<dc:creator>'.DOMAIN_EMAIL.' ('.DOMAIN_NAME.')</dc:creator>
		<items>
			<rdf:Seq>';

		$r_ = $dbc->_db->query(sprintf('	SELECT 		*
											FROM 		'.TABLE_NEWS.'
											WHERE 		(live = 1) AND
														(language = %s) '.$rdf_push_sql.'
											ORDER BY 	date DESC
											LIMIT 		20', $dbc->_db->quote($orbicon_x->ptr)));
		$news = $dbc->_db->fetch_assoc($r_);

		while($news) {
			$_r = $dbc->_db->query(sprintf('	SELECT 		content
												FROM 		'.MAGISTER_CONTENTS.'
												WHERE 		(live = 1) AND
															(hidden = 0) AND
															(id = %s) AND
															(language = %s)
												LIMIT 		1', $dbc->_db->quote($news['intro']), $dbc->_db->quote($orbicon_x->ptr)));
			$_a = $dbc->_db->fetch_assoc($_r);

			$desc = strip_tags($_a['content']);
			$desc = trim(str_replace(array("\n", '<', '&nbsp;', '\''), array('', '&lt;', ' ', '&#039;'), $desc));

			$rdf .= '<rdf:li rdf:resource="'.url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.$news['permalink'], ORBX_SITE_URL.'/'.$orbicon_x->ptr.'/'.$news['permalink']).'"/>'."\n";

			$creator = $dbc->_db->query(sprintf('	SELECT 		first_name, last_name
													FROM 		'.TABLE_EDITORS.'
													WHERE 		(id = %s)
													LIMIT 		1', $dbc->_db->quote($news['editor'])));
			$creator = $dbc->_db->fetch_assoc($creator);
			$creator = utf8_html_entities($creator['first_name'].' '.$creator['last_name']);

			$url = url(ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'='.urlencode($news['permalink']), ORBX_SITE_URL.'/'.$orbicon_x->ptr.'/'.urlencode($news['permalink']));

			$items .= '<item rdf:about="'.$url.'">
	<title>'.utf8_html_entities($news['title']).'</title>
	<link>'.$url.'</link>
	<description>'.$desc.'</description>
	<dc:date>'.date('Y-m-d', $news['date']).'</dc:date>
	<dc:creator>'.$creator.'</dc:creator>
	<dc:subject>'.$news['category'].'</dc:subject>
</item>'."\n";
			$news = $dbc->_db->fetch_assoc($r_);
		}

		$rdf .= '</rdf:Seq>
		</items>
	</channel>';

		$rdf .= $items;

		$rdf .= '<rdf:Description rdf:about="'.ORBX_SITE_URL.'/">
      <rdfs:seeAlso rdf:resource="'.ORBX_SITE_URL.'/site/mercury/Overview-about.rdf"/>
   </rdf:Description>
</rdf:RDF>';

		/* Set a 64k buffer. */
		if(function_exists('stream_set_write_buffer')) {
			stream_set_write_buffer($r, 65535);
		}

		fwrite($r, $rdf);
		fclose($r);
	}
}
?>