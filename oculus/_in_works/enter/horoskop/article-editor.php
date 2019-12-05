<?php

	(object) $oArticleEditor = new HF;

	//ini_set("display_errors", 1);
	//error_reporting(E_ALL);

	if(isset($_POST["bSaveArticle"]))
	{
	$oArticleEditor -> DB_Spoji();
	$rResult = $oArticleEditor -> DB_Upit("SELECT * FROM horoskop ORDER BY poredak ASC");
	$aArticle = mysql_fetch_array($rResult, MYSQL_ASSOC);

	while($aArticle)
	{

		$dnevni = $_POST["horoskop_{$aArticle['permalink']}"];
		$dnevni = $oArticleEditor -> convert_smart_quotes($dnevni);
		$dnevni = trim(stripslashes($oArticleEditor -> Serbian2Croatian($dnevni)));

		(string) $sUpit = sprintf("UPDATE horoskop SET dnevni_horoskop=%s WHERE permalink=%s", $oArticleEditor -> QuoteSmart($dnevni), $oArticleEditor -> QuoteSmart($aArticle['permalink']));
		$oArticleEditor -> DB_Upit($sUpit);

		// * Generate RSS

		chmod("rss.xml", 0666);
		$r = fopen("rss/{$aArticle['permalink']}.xml", "wb");
		$sRSS = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
<rss version=\"2.0\">
	<channel>
		<title>Horoskop - ".$aArticle['naziv']."</title>
		<link>http://horoskop.laniste.net/</link>
		<description>Dnevni horoskop za znak &quot;".$aArticle['naziv']."&quot;</description>
		<lastBuildDate>".date("r")."</lastBuildDate>
		<generator>Orca CMS beta</generator>
		<language>hr</language>
		<copyright>Copyright 2006, Laniste.net</copyright>
		<managingEditor>laniste@laniste.net (Laniste.net)</managingEditor>
		<webMaster>laniste@laniste.net (Laniste.net)</webMaster>
		<docs>http://blogs.law.harvard.edu/tech/rss</docs>";
				$sRSS .= "<item>
		<title>".date("d.m.Y.")."</title>
		<link>http://horoskop.laniste.net/#".$aArticle['permalink']."-".date('d-m-Y')."</link>
		<description>".$dnevni."</description>
		<pubDate>".date("r")."</pubDate>
		<guid isPermaLink=\"true\">http://horoskop.laniste.net/#".$aArticle['permalink']."-".date('d-m-Y')."</guid>
		<author>astrolog@astrolook.com (Astrolook)</author>
		<source url=\"http://horoskop.laniste.net/rss/{$aArticle['permalink']}.xml\">Dnevni horoskop za znak &quot;".$aArticle['naziv']."&quot;</source>		
	</item>\n";
			$sRSS .= " </channel>
</rss>";
			fwrite($r, $sRSS);
			fclose($r);

		$aArticle = mysql_fetch_array($rResult, MYSQL_ASSOC);
	}

		$oArticleEditor -> DB_Zatvori();
	}

	// * Load article
	$oArticleEditor -> DB_Spoji();
	$rResult = $oArticleEditor -> DB_Upit('SELECT * FROM horoskop ORDER BY poredak ASC');
	$aArticle = mysql_fetch_array($rResult, MYSQL_ASSOC);

	while($aArticle)
	{
		$_POST["horoskop_{$aArticle['permalink']}"] = $aArticle['dnevni_horoskop'];
		$aArticle = mysql_fetch_array($rResult, MYSQL_ASSOC);
	}

	$oArticleEditor -> DB_Zatvori();

?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title>Article Editor</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<form action="" method="post" id="article">
<table align="center" style="width: 750px; background-color: #FFFFFF;">
  <tr>
      <td style="vertical-align: top;">
		<table style="width: 100%;">
          <tr> 
            <td colspan="2"><a href="http://horoskop.laniste.net/"><h3>horoskop.laniste.net</h3></a></td>
          </tr>
		  <tr> 
            <td colspan="2"><input type="submit" id="fetch_astrolook_data" name="fetch_astrolook_data" value="Dobavi sa Astrolooka" /></td>
          </tr>
          <tr> 
            <td colspan="2">HOROSKOP EDITOR</td>
          </tr>
<?php
	$oArticleEditor -> DB_Spoji();
	$rResult = $oArticleEditor -> DB_Upit("SELECT * FROM horoskop ORDER BY poredak ASC");
	$aArticle = mysql_fetch_array($rResult, MYSQL_ASSOC);

	if(isset($_POST['fetch_astrolook_data']))
	{
		require_once('mother_node.php');
		(object) $LanBot = new LanBotNode;
		// image fetch test
		$horoskop = $LanBot -> _lanbot_task_fetch_file('http://www.astrolook.com/dnevni.shtml');
		$horoskop = strip_tags($horoskop, '<font>');
		$horoskop = explode('<font class="htext">', $horoskop);

		$i = 1;
		foreach($horoskop as $key => $value)
		{
			$value = str_replace(array('OVAN', 'BIK', 'BLIZANCI', 'RAK', 'LAV', 'DEVICA', 'VAGA', 'ŠKORPIJA', '&Scaron;KORPIJA', 'STRELAC', 'JARAC', 'VODOLIJA', 'RIBE'), '', $value);
			$aAstroFetch[$i] = trim(strip_tags($oArticleEditor -> Serbian2Croatian($value)));
			$i ++;
		}
	}

	$b = 2;

	while($aArticle)
	{
		$_POST["horoskop_{$aArticle['permalink']}"] = (isset($_POST['fetch_astrolook_data'])) ? $aAstroFetch[$b] : $aArticle['dnevni_horoskop'];

?>
          <tr> 
            <td colspan="2"><b><?= $aArticle['naziv']; ?></b></td>
          </tr>
          <tr> 
            <td colspan="2"><textarea style="width: 500px; height: 150px;" id="horoskop_<?= $aArticle['permalink']; ?>" name="horoskop_<?= $aArticle['permalink']; ?>"><?= $_POST["horoskop_{$aArticle['permalink']}"]; ?></textarea></td>
          </tr>

<?php

		$aArticle = mysql_fetch_array($rResult, MYSQL_ASSOC);
		$b ++;
	}

	$oArticleEditor -> DB_Zatvori();

?>

          <tr> 
            <td colspan="2" style="text-align: center;"><hr /></td>
          </tr>
        </table>
        <table style="width: 100%;">
        <tr>
            <td style="height: 35px; text-align: center;"><input name="bSaveArticle" type="submit" id="bSaveArticle" value="Save" /></td>
            </tr>
      </table>	  </td>
  </tr>
</table>
</form>
</body>
</html>