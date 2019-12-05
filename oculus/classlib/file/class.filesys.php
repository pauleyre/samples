<?php

require(LIB_INC."hash/class.hash.php");

class FileSys extends Hash
{
	var $nFileSys_Umask;
	var $sFileSys_FileName;

	// * Kreiraj prazan file
	function KreirajPrazanFile()
	{
		$rNoviFile = fopen($this -> sFileSys_FileName, "wb");
		fclose($rNoviFile);
	}

	// *  Prikaži $nVelicina u čitljivom formatu
	function VelicinaResursa($nVelicina)
	{
		(int) $n = 0;
		(array) $aMjerneJedinice = array("B", "KB", "MB", "GB", "TB", "PB");

		while($nVelicina >= 1024)
		{
		   $nVelicina /= 1024;
		   $n ++;
		}

		return (round($nVelicina, 2)." $aMjerneJedinice[$n]");
	}

	// * Saznaj veliinu dokumenta
	function SaznajVelicinuDokumenta($sFile)
	{
		if(!is_file($sFile))
		{
			$this -> AddError(__FUNCTION__.$sFile." nije file.");
			return "N/A";
		}

		(int) $nVelicina = filesize($sFile);

		return $this -> VelicinaResursa($nVelicina);
	}

	function VelicinaMape($sMapa = ".")
	{
	  	(int) $nVelicina = 0;
  		$rMapa = dir($sMapa);
		$rDokument = $rMapa -> read();

  		while($rDokument !== FALSE)
		{
			if(($rDokument != ".") && ($rDokument != ".."))
			{
          		if(is_dir($sMapa.$rDokument))	{
					$nVelicina += $this -> VelicinaMape("$sMapa/$rDokument");
				}
				else {
					$nVelicina += filesize("$sMapa/$rDokument");
				}
			}
			$rDokument = $rMapa -> read();
		}
		$rMapa -> close();

		return $this -> VelicinaResursa($nVelicina);
	}

	// * Generiraj SFV checksum vrijednost za $sSadrzaj
	function SFVChecksum($sSadrzaj) {
		return (str_pad(strtoupper(dechex(crc32($sSadrzaj))), 8, "0", STR_PAD_LEFT));
	}

	function PHP_INI_Bytes($sVal)
	{
		(string) $sVal = trim($sVal);
		(string) $sZadnji = strtolower($sVal{strlen($sVal)-1});

		switch($sZadnji)
		{
			// The 'G' modifier is available since PHP 5.1.0
			case "g":
				$sVal *= 1024;
			case "m":
				$sVal *= 1024;
			case "k":
				$sVal *= 1024;
		}	
		return $sVal;
	}

	function CHM_OkljucajFile($nMod = 0666)
	{
		$this -> nFileSys_Umask = umask(0);
		return chmod($this -> sFileSys_FileName, $nMod);
	}

	function CHM_ZakljucajFile($nMod = 0644)
	{
		(bool) $bChmod = chmod($this -> sFileSys_FileName, $nMod);
		umask($this -> nFileSys_Umask);
		return $bChmod;
	}

	function GetExtension($sFilename) {
		return (strtolower(substr(strrchr(basename($sFilename), "."), 1)));
	}
}

?>