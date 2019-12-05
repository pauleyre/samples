<?php

require(LIB_INC."vcard/class.vcard.php");
require("h.tornado.php");
require("ver.tornado.php");

class Tornado extends vCard
{
	// * Lovi botove
	function TornadoIndexTrap()
	{
		(string) $sLine = "";
		(string) $sIP = $this -> GetIP();
		(array) $aLog = array();

		$this -> TornadoCreateNewLog();

		echo "<html>
		<head><title>Welcome To The Pit</title></head>
		<body>
		<a href=\"http://{$_SERVER['SERVER_NAME']}/\">Home</a>";

			(int) $nBadBot = 0;

			$rFile = fopen(TORNADO_BLACKLIST_FILENAME, "rb");
			if(!is_resource($rFile)) {
				$this -> AddError(__FUNCTION__." : fopen fail ".TORNADO_BLACKLIST_FILENAME);
			}
			
			$sLine = fgets($rFile, 255);
			while($sLine)
			{
				$aLog = @explode(" ", $sLine);
				if(@ereg($aLog[0], $sIP)) {
					$nBadBot ++;
				}
				$sLine = fgets($rFile, 255);
			}
			if(!fclose($rFile)) {
				$this -> AddError(__FUNCTION__." : fclose fail ".TORNADO_BLACKLIST_FILENAME);
			}

			if($nBadBot == 0)
			{
				(int) $nVrijeme = time();
				(array) $aHeader = $this -> HeaderNorm(TORNADO_ADMIN_EMAIL, TORNADO_ALERT_SENDER_EMAIL, $_SERVER["SERVER_NAME"], "text/plain", "x", "iso-8859-2");
				(string) $sDatum = date("l, d-m-Y // H:i:s", $nVrijeme);
				(string) $sSubject = "{$_SERVER['SERVER_NAME']} : ROBOT ALERT";
				(string) $sMailBody = "Robot ušao na {$_SERVER['REQUEST_URI']} $sDatum\n";
				$sMailBody .= "IP adresa je $sIP, agent je {$_SERVER['HTTP_USER_AGENT']}\n";
				$sMailBody = $this -> UTF8_2_ISO885_9_HR($sMailBody);
				mail(TORNADO_ADMIN_EMAIL, $sSubject, $sMailBody, $aHeader["header"]);

				// * Dodaj u blacklist log
				$rBlaclist = fopen(TORNADO_BLACKLIST_FILENAME, "a+b");
				fwrite($rBlaclist, "$sIP - [$sDatum] \"{$_SERVER['REQUEST_METHOD']} {$_SERVER['REQUEST_URI']} {$_SERVER['SERVER_PROTOCOL']}\" {$_SERVER['HTTP_REFERER']} {$_SERVER['HTTP_USER_AGENT']}\n");
				fclose($rBlaclist);
			}

		echo "</body>
		</html>";
	}

	// * Odbacuje botove
	function TornadoGuard()
	{
		(int) $nBadBot = 0;
		(string) $sLine = "";
		(string) $sIP = $this -> GetIP();
		(array) $aLog = array();

		$this -> TornadoCreateNewLog();

		$rBlacklist = fopen(TORNADO_BLACKLIST_FILENAME, "rb");
		if(!is_resource($rBlacklist)) {
			$this -> AddError(__FUNCTION__." : resource fail ".TORNADO_BLACKLIST_FILENAME);
		}
		$sLine = fgets($rBlacklist, 255);

		while($sLine)
		{
			$aLog = explode(" ", $sLine);
			if(@ereg($aLog[0], $sIP)) {
				$nBadBot ++;
			}
			$sLine = fgets($rBlacklist, 255);
		}

		if(!fclose($rBlacklist)) {
			$this -> AddError(__FUNCTION__." : fclose fail ".TORNADO_BLACKLIST_FILENAME);		
		}

		// * Badbot, odbaci ga
		if($nBadBot > 0)
		{
			sleep(12);
			echo "<html>\n
			<head>\n
			<title>{$_SERVER['SERVER_NAME']} - Offline...</title>\n
			<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />
			</head>\n
			<body>\n
				<h1 style=\"text-align: center;\">Dobro došli...</h1>\n
				<p style=\"text-align: center;\">Stranice su trenutno nedostupne zbog indentificirane zlouporabe...</p>\n
				<p style=\"text-align: center;\">Ako mislite da je došlo do pogreške, kontaktirajte nas.<br />
			</body>\n
			</html>\n";
			exit();
		}
	}

	function TornadoCreateNewLog()
	{
		if(!is_file(TORNADO_BLACKLIST_FILENAME))
		{
			$this -> sFileSys_FileName = TORNADO_BLACKLIST_FILENAME;
			$this -> KreirajPrazanFile();
		}
	}

	function InstallTornado($sWWWDir)
	{
		// * Tornado log
		$this -> TornadoCreateNewLog();
		// * Tornado dir
		if(!mkdir($sWWWDir."tornado", 0644)) {
			$this -> AddError(__FUNCTION__." : mkdir fail ".$sWWWDir."tornado");
		}
		// * tornado/index.php
		if(!copy(LIB_INC."tornado/install/index.php", $sWWWDir."tornado/index.php")) {
			$this -> AddError(__FUNCTION__." : copy fail ".$sWWWDir."tornado/index.php");
		}
		// * tornado/tornado.gif
		if(!copy(LIB_INC."tornado/install/tornado.gif", $sWWWDir."tornado/tornado.gif")) {
			$this -> AddError(__FUNCTION__." : copy fail ".$sWWWDir."tornado/tornado.gif");
		}
		// * robots.txt
		(string) $sRobotsTXT = $sWWWDir."robots.txt";
		(string) $sRobotsContent = "Disallow: /tornado/\r\n";

		if(!is_file($sRobotsTXT))
		{
			$this -> sFileSys_FileName = $sRobotsTXT;
			$this -> KreirajPrazanFile();
			$sRobotsContent = "User-agent: *\r\n$sRobotsContent";
		}
		$rRobots = fopen($sRobotsTXT, "a+b");
		if(!is_resource($rRobots)) {
			$this -> AddError(__FUNCTION__." : fopen fail $sRobotsTXT");
		}
		if(fwrite($rRobots, $sRobotsContent) === FALSE) {
			$this -> AddError(__FUNCTION__." : fwrite fail $sRobotsTXT");
		}
		if(!fclose($rRobots)) {
			$this -> AddError(__FUNCTION__." : fclose fail $sRobotsTXT");
		}
	}

	function UninstallTornado($sWWWDir)
	{
		if(!unlink(TORNADO_BLACKLIST_FILENAME)) {
			$this -> AddError(__FUNCTION__." : unlink fail ".TORNADO_BLACKLIST_FILENAME);
		}
		if(!unlink($sWWWDir."tornado/tornado.gif")) {
			$this -> AddError(__FUNCTION__." : unlink fail ".$sWWWDir."tornado/tornado.gif");
		}
		if(!unlink($sWWWDir."tornado/index.php")) {
			$this -> AddError(__FUNCTION__." : unlink fail ".$sWWWDir."tornado/index.php");
		}
		if(!rmdir($sWWWDir."tornado")) {
			$this -> AddError(__FUNCTION__." : rmdir fail ".$sWWWDir."tornado");
		}
		(string) $sRobotsContent = file_get_contents($sWWWDir."robots.txt");
		$sRobotsContent = str_replace("Disallow: /tornado/\r\n", "", $sRobotsContent);
		$rNewRobots = fopen($sWWWDir."robots.txt", "wb");
		if(!is_resource($rNewRobots)) {
			$this -> AddError(__FUNCTION__." : resource handle fail ".$sWWWDir."robots.txt");
		}
		if(fwrite($rNewRobots, $sRobotsContent) === FALSE) {
			$this -> AddError(__FUNCTION__." : fwrite fail ".$sWWWDir."robots.txt");		
		}
		if(!fclose($rNewRobots)) {
			$this -> AddError(__FUNCTION__." : fclose fail ".$sWWWDir."robots.txt");
		}
	}

	function GetTornadoInstalled() {
		return file_exists(TORNADO_BLACKLIST_FILENAME);
	}
}

?>