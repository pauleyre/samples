<?php

require($_SERVER["DOCUMENT_ROOT"]."/classlib/public/classlib.php");

class HF extends ClassLib
{
	function GetPermalink($sInput)
	{
		$sInput = strip_tags(trim(strtolower($sInput)));
		$sInput = str_replace(array("\"", "=", "?", "&", "+", " ", "/", ":", "'", 'č', 'ć', 'ž', 'š', 'đ', 'Č', 'Ć', 'Š', 'Đ', 'Ž'), array("", "-", "", "-", "-", "-", "-", "-", "-", 'c', 'c', 'z', 's', 'dj', 'C', 'C', 'S', 'Dj', 'Z'), $sInput);
		$sInput = strtolower($sInput);
		return $sInput;
	}
	
	function check()
	{
		if(!isset($_SESSION["user"]) || !isset($_SESSION["pass"])) 
		{
			echo "<meta http-equiv=\"refresh\" content=\"0; URL=http://horoskop.laniste.net/login/\" />";
			die("redirecting...");
		}
	}

	function GetAdminLoggedIn()
	{
		if(!isset($_SESSION["user"]) || !isset($_SESSION["pass"])) {
			return FALSE;
		}
		return TRUE;
	}

	// * Start session
	function InitiateSession()
	{
		if(session_id() == '') {
			session_start();
		}
		$this -> CheckSessionFixation();
		$this -> CheckSessionHijack();
		$this -> SendSessionHeaders();
	}

	// * End session
	function EndSession()
	{
		session_destroy();
		header('Location: http://horoskop.laniste.net/');			
		exit();
	}

	// * Session fixation check
	function CheckSessionFixation()
	{
		if(!isset($_SESSION["SessionStarted"]))
		{
			session_regenerate_id();
			$_SESSION["SessionStarted"] = TRUE;
		}
	}

	// * Session hijack check
	function CheckSessionHijack()
	{
		if(isset($_SESSION["UserSessionID"]))
		{
			if($_SESSION["UserSessionID"] != md5($_SERVER["HTTP_USER_AGENT"].@$_SERVER["HTTP_ACCEPT_CHARSET"].$_SERVER["DOCUMENT_ROOT"].session_id())) {
				$this -> EndSession();
			}
		}
		else {
			$_SESSION["UserSessionID"] = md5($_SERVER["HTTP_USER_AGENT"].@$_SERVER["HTTP_ACCEPT_CHARSET"].$_SERVER["DOCUMENT_ROOT"].session_id());
		}
	}

	function SendSessionHeaders()
	{
		if(ini_get("session.cache_limiter") != "private") {
			ini_set("session.cache_limiter", "private");	// IE 6 bugfix
		}

		// * Bug in PHP 4, for now this won't work
		if(ini_get("session.use_trans_sid") != "0") {
			ini_set("session.use_trans_sid", 0);
		}
	}

	function GetMenuRight($sCategory = "", $nHidden = 0)
	{
		$this -> DB_Spoji();
		$rResult = $this -> DB_Upit("SELECT * FROM horoskop ORDER BY poredak ASC");
		$aArticle = mysql_fetch_array($rResult, MYSQL_ASSOC);
		$sMenuRight = "";

		while($aArticle)
		{
			$sMenuRight .= "<li><a href=\"http://horoskop.laniste.net/#".$aArticle["permalink"]."-".date('d-m-Y')."\" title=\"Pro&#269;itajte dnevni horoskop za znak &quot;".$aArticle["naziv"]."&quot;\">".$aArticle["naziv"]."</a></li>";
			$aArticle = mysql_fetch_array($rResult, MYSQL_ASSOC);
		}
		mysql_free_result($rResult);
		$this -> DB_Zatvori();
		return $sMenuRight;
	}

	function GetCategories()
	{
		$this -> DB_Spoji();
		$rResult = $this -> DB_Upit("SELECT * FROM horoskop ORDER BY poredak");
		$aCategory = mysql_fetch_array($rResult, MYSQL_ASSOC);
		(string) $sCategories = "";

		while($aCategory)
		{
			$sCategories .= "<option value=\"".$aCategory["permalink"]."\">".$aCategory["naziv"]."</option>";
			$aCategory = mysql_fetch_array($rResult, MYSQL_ASSOC);
		}
		mysql_free_result($rResult);
		$this -> DB_Zatvori();
		return $sCategories;
	}

	function SafeHTML($sInput)
	{
		define('XML_HTMLSAX3', $_SERVER['DOCUMENT_ROOT']."/administration/safehtml/classes/");
		require_once($_SERVER['DOCUMENT_ROOT'].'/administration/safehtml/classes/safehtml.php');
		$safehtml =& new safehtml();
		$sInput = $safehtml->parse($sInput);
		return $sInput;
	}

	function GetMetaTags()
	{
		(array) $aQuery = explode("/", $_GET["read"]);

		$this -> DB_Spoji();


		if($aQuery[0] == 'article')
		{
			$rResult = $this -> DB_Upit(sprintf('SELECT last_modified FROM magister_articles WHERE permalink = %s LIMIT 1', $this -> QuoteSmart($aQuery[1])));
			$aMeta = mysql_fetch_array($rResult, MYSQL_ASSOC);		
			$rfc_1123_date = gmdate('D, d M Y H:i:s T', $aMeta["last_modified"]);
		}
		else
		{
			$rResult = $this -> DB_Upit('SELECT live_time FROM magister_articles WHERE live = 1 AND hidden = 0 ORDER BY live_time DESC LIMIT 1');
			$aMeta = mysql_fetch_array($rResult, MYSQL_ASSOC);
			$rfc_1123_date = gmdate('D, d M Y H:i:s T', $aMeta["live_time"]);
		}

		(string) $sMeta = "<meta http-equiv=\"Last-Modified\" content=\"$rfc_1123_date\" />";

		$rResult = $this -> DB_Upit("SELECT naziv, permalink FROM horoskop ORDER BY poredak ASC");
		$aArticle = mysql_fetch_array($rResult, MYSQL_ASSOC);

		while($aArticle)
		{

			$feeds .= '<link rel="alternate" type="application/rss+xml" title="'.$aArticle['naziv'].' RSS feed" href="http://horoskop.laniste.net/rss/'.$aArticle['permalink'].'.xml" />
';
			$aArticle = mysql_fetch_array($rResult, MYSQL_ASSOC);
		}


		(string) $sMetatags = <<<EOF
{$sMeta}
<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />
<link rel="schema.DCTERMS" href="http://purl.org/dc/terms/" />
<meta name="DC.title" content="Horoskop" />
<meta name="DC.creator" content="Horoskop" />
<meta name="DC.description" content="Va&scaron; dnevni horoskop. Uklju&#269;uje pretplatu mail-om ili RSS feed-om." />
<meta name="DC.type" scheme="DCTERMS.DCMIType" content="Text" />
<meta name="DC.format" content="text/html" />
<meta name="DC.identifier" scheme="DCTERMS.URI" content="http://horoskop.laniste.net/" />
<meta name="description" content="Va&scaron; dnevni horoskop. Uklju&#269;uje pretplatu mail-om ili RSS feed-om." />
<meta name="author" content="Horoskop" />
<meta name="robots" content="index,follow" />
<meta http-equiv="content-language" content="hr" />
<meta http-equiv="content-style-type" content="text/css" />
<meta http-equiv="content-script-type" content="text/javascript" />
<meta http-equiv="site-enter" content="blendTrans(Duration=1)" />
<meta http-equiv="site-exit" content="blendTrans(Duration=1)" />
<meta name="abstract" content="baza, pitanja, odgovori, odgovora" />
<meta name="copyright" content="Copyright &copy; 2006 Laniste.net, All Rights Reserved" />
<meta name="distribution" content="Global" />
<meta name="doc-class" content="Living Document" />
<meta name="doc-rights" content="Public" />
<meta name="doc-type" content="Web Page" />
<meta name="generator" content="Orca CMS beta" />	
<meta name="MSSmartTagsPreventParsing" content="TRUE" />
<meta name="owner" content="Horoskop" />
<meta name="rating" content="GENERAL" />
<meta name="reply-to" content="techno@laniste.net (Pavle Gardijan)" />
<meta name="resource-type" content="document" />
<meta name="revisit-after" content="1 day" />
<script type="text/javascript" src="http://horoskop.laniste.net/javascript/safemail.js"></script>
<script type="text/javascript" src="http://horoskop.laniste.net/javascript/autoblink.js"></script>
<link rel="shortcut icon" href="http://horoskop.laniste.net/favicon.ico" />
<link rel="icon" href="http://horoskop.laniste.net/favicon.ico" type="image/ico" />
{$feeds}
<meta http-equiv="PICS-Label" content='(PICS-1.1
"http://vancouver-webpages.com/VWP1.0/" l gen true comment "VWP1.0" by "laniste@laniste.net" 
on "2006.05.15T15:00-0700" for "http://horoskop.laniste.net"
 r (P 0 S 0 V 0 Com 0 Tol -1 Env -3 SF 0 Edu -3 Can 0 MC -2 Gam -1 ))' />
<link rel="meta" href="http://horoskop.laniste.net/labels.rdf" type="application/rdf+xml" title="ICRA labels" />\n
EOF;
		return $sMetatags;
	}


	function LightHTML($sInput)
	{
		// * HTML tags for removal
		(array) $aForRemoval = array(
										0 => 'Č',
										1 => 'Ć',
										2 => 'Đ',
										3 => 'Š',
										4 => 'Ž',
										5 => 'č',
										6 => 'ć',
										7 => 'đ',
										8 => 'š',
										9 => 'ž'
									);
		// * HTML Replacements
		(array) $aForReplacement = array(
											0 => '&#268;',
											1 => '&#262;',
											2 => '&#272;',
											3 => '&#352;',
											4 => '&#381;',
											5 => '&#269;',
											6 => '&#263;',
											7 => '&#273;',
											8 => '&#353;',
											9 => '&#382;'
										);

		$sInput = str_replace($aForRemoval, $aForReplacement, $sInput);

		return $sInput;
	}


	function Serbian2Croatian($sInput)
	{
		// * HTML tags for removal
		(array) $aForRemoval = array(
										'precutati',
										'saradni',
										'Reagujete',
										'osecanja',
										'zahtevi',
										'deluju',
										'Izbegavajte',
										'finansijski',
										'Neko ',
										'namere ',
										'optimistizam',
										'»prvi utisak«',
										'znacaja ',
										'interesovanje',
										'pokazace',
										'svetlu',
										'lepim manirima',
										'utisak ',
										'razume ',
										'odrazava ',
										'finansijske',
										'saradnje ',
										'razumevanja ',
										'izbegavajte ',
										'precutkivanjem',
										'»vazih detalja«',
										'Prijace ',
										'prijace ',
										'resenje',
										'neko ',
										'uticaj',
										'poveravanje',
										'Sugestija:',
										'primedbu ',
										'dobronamernu ',
										'savet ',
										'verno ',
										'odslikava ',
										'precenjujete ',
										'licne ',
										'procena ',
										'savesti',
										'Podsticite',
										'podsticite',
'Sacekajte ',
'namere.',
'procenite ',
'redosled ',
'lepim',
'ulecete ',
'resiti ',
'nezni ',
'saobracaju',
'saradnji.',
'reci ',
'kritikuje ',
'uspesnim ',
'ucenjivanja ',
'osecaj ',
'osetite ',
'licnosti.',
'licnosti '

									);
		// * HTML Replacements
		(array) $aForReplacement = array(
										'prešutjeti',
										'suradni',
										'Reagirate',
										'osjecaji',
										'zahtjevi',
										'djeluju',
										'Izbjegavajte',
										'financijski',
										'Netko ',
										'namjere ',
										'optimizam',
										'»prvi dojam«',
										'znacenja ',
										'zanimanje',
										'pokazati ce',
										'svijetlu',
										'lijepim gestama',
										'dojam ',
										'razumije ',
										'odrazava ',
										'financijske',
										'suradnje ',
										'razumijevanja ',
										'izbjegavajte ',
										'presutkivanjem',
										'»vaznih detalja«',
										'Odgovarati ce ',
										'odgovarati ce ',
										'rjesenje',
										'netko ',
										'utjecaj',
										'povjeravanje',
										'Savjet:',
										'primjedbu ',
										'dobronamjernu ',
										'savjet ',
										'vjerno ',
										'oslikava ',
										'precjenjujete ',
										'vlastite ',
										'procjena ',
										'savjesti',
										'Poticite',
										'poticite',
'Pricekajte ',
'namjere.',
'procjenite ',
'redoslijed ',
'lijepim',
'ulijecete ',
'rjesiti ',
'njezni ',
'prometu',
'suradnji.',
'rijeci ',
'kritizira ',
'uspjesnim ',
'ucjenjivanja ',
'osjecaj ',
'osjetite ',
'osobnosti.',
'osobnosti '
										);

		$sInput = str_replace($aForRemoval, $aForReplacement, $sInput);

		return $this -> LightHTML($sInput);
	}

}



?>