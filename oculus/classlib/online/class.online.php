<?php

require(LIB_INC."upload/class.upload.php");

class Online extends Upload
{
	function OnlineBrojac($nTimeout = 60, $sIPLog = "ip.db")
	{
		(int) $nVrijeme = time();
		(int) $nVrijemeTimeouta = ($nVrijeme - $nTimeout);
		(string) $sNoviFile = "";
		(string) $sKorisnickiIP = $this -> GetIP();
		(array) $aIP = file($sIPLog);

		// * Odmah pridodaj trenutnog korisnika [početak]
		(int) $nMax = count($aIP);
		(int) $nZadnji = $nMax ++;
		(string) $aIP[$nZadnji] = "$sKorisnickiIP&$nVrijeme\n";
		// * Odmah pridodaj trenutnog korisnika [kraj]

		$rIP = fopen($sIPLog, "wb");

		for($i = 0; $i < count($aIP); $i ++)
		{
			$sPodaci = explode("&", $aIP[$i]);								// * Dobavi IP i timestamp

			if(!empty($aIP[$i]))											// * Array nije prazan?
			{
				if((($sPodaci[0] == $sKorisnickiIP) && ($i != $nZadnji))	// * Isti IP? (pazi da ne izbrisemo trenutnog posjetitelja)
				|| ($sPodaci[1] <= $nVrijemeTimeouta)) {					// * Vrijeme isteklo?
					$aIP[$i] = NULL;										// * Resetiraj array na $i indeksu
				}
			}
			$sNoviFile .= $aIP[$i];
		}

		fwrite($rIP, $sNoviFile);
		fclose($rIP);

		// * Izbroji ispoetka za najsvjeiji info
		return count(file($sIPLog));
	}

/*	function GetICQStatus($nUIN)
	{
		$rICQ = fsockopen("wwp.icq.com", 80, &$errno, &$errstr, 30);
		if(!$rICQ) {
			echo "$errstr ($errno)<br>n";
		}
		else
		{
			fputs($rICQ, "GET /scripts/online.dll?icq=$uin&img=5 HTTP/1.0nn");
			$do = "no";
			while(!feof($rICQ))
			{
				$line = fgets($rICQ, 128);
				$do = ($do == 'yes') ? 'yes' : (eregi("^GIF89", $line)) ? "yes" : "no";
				if($do == 'yes')
				{
					if(ereg("á7@ ±40", $line)) {
						return 'online'; 
					}
					else if(ereg("áw` ±40", $line)) {
						return 'offline';
					}
					else if(ereg("S³IÑ±èJ", $line)) { 
							return 'disabled';
					}
				}
			} 
			fclose($rICQ); 
		} 
		return 'unknown'; 
	}*/
}

?>