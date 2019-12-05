<?php

// create news rss
function rss2($dir, $q, $category = '')
{
	global $db;

	$rss_link = 'http://www.dekada.org/web/rss/' . basename($dir);

	$rss = '<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet href="http://www.w3.org/2000/08/w3c-synd/style.css" type="text/css"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
	<atom:link href="'.$rss_link.'" rel="self" type="application/rss+xml" />
	<title>Dekada</title>
	<link>http://www.dekada.org</link>
	<description>Pitajte bilo što ili pokažite znanje svojim odgovorima</description>
	<lastBuildDate>'.date('r').'</lastBuildDate>
	<generator>Dekada</generator>
	<language>hr</language>
	<copyright>Copyright '.date('Y').', Dekada</copyright>
	<managingEditor>info@dekada.org (Dekada)</managingEditor>
	<webMaster>info@dekada.org (Dekada)</webMaster>
	<docs>http://blogs.law.harvard.edu/tech/rss</docs>' . "\n";

	$r = $q->getQuestions($category, 10, 1);
	$q = $db->fetch_assoc($r);

	while($q) {
		$link = 'http://www.dekada.org/?' . urlencode($q['category']) . ",{$q['permalink']}&amp;d={$q['id']}";
		$desc = strip_tags($q['title']);
		$desc = trim(str_replace(array("\n", '<', '&nbsp;', '\''), array('', '&lt;', ' ', '&#039;'), $desc));

		$rss .= '<item>
	<title>'.$q['title'].'</title>
	<link>'.$link.'</link>
	<category>'.$q['category'].'</category>
	<description>'.$desc.'</description>
	<pubDate>'.date('r', $q['live_time']).'</pubDate>
	<guid isPermaLink="true">'.$link.'</guid>
	<author>info@dekada.org (Dekada)</author>
	<source url="'.$rss_link.'">Dekada</source>
</item>'."\n";
		$q = $db->fetch_assoc($r);
	}

	$rss .= '</channel></rss>';

	return file_put_contents($dir, $rss);
}

?>