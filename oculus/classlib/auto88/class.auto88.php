<?php

require(LIB_INC."backup/class.backup.php");

class Auto88 extends Backup
{
	var $sURI;
	var $nUkupnoFileova;
	var $Info;

	// * Izvrsi update
	/*function Auto88()
	{
		(object) $oTemp = new INI($this -> sURI."/public/update/orca/".$this -> Info);
		(array) $aInstallLog = $oTemp -> INIUcitajGrupu("SISTEM");
		$this -> SloziInstalaciju($aInstallLog);
	}

	// * Instaliraj update, file po file
	function SloziInstalaciju($aInst)
	{
		$this -> nUkupnoFileova = (count($xInst) - 1);
		foreach($aInst as $i => $xRed)
		{
			if(is_numeric($i)) {
				$this -> InstalirajFile($this -> sURI."/public/update/orca/".$xRed, $xRed, $i);
			}
		}
	}

	// * Najdji verziju, defaultna je lokalna verzija
	function UcitajVer($sFile = "")
	{
		if(empty($sFile)) {
			$sFile = $this -> Info;
		}
		else
		{
			(array) $aFile = file($sFile);
			if(empty($aFile)) {
				return -1;
			}
		}

		(float) $fVer = $this -> QuickINIVar("SISTEM", "ver", $sFile);
		return $fVer;
	}

	// * Logika update-a
	function NovaVerzija()
	{
		$this -> Info = DB_POSTAVKE;
		$nNajnovije = $this -> UcitajVer($this -> sURI."/public/update/orca/".$this -> Info);
		$nLokalnaVerzija = $this -> UcitajVer();

		if($nNajnovije == -1)
		{
			$this -> TaskMsgGUI($this -> sURI." : Server nedostupan.", 0);
			return;
		}
		// * Nema novije verzije, tj. ista verzija
		if($nLokalnaVerzija == $nNajnovije) {
			$this -> Reklama();
		}
		// * Izvrsi update, pronadjena novija verzija
		else if($nLokalnaVerzija < $nNajnovije)
		{
			$this -> UpdateWSInfo($nNajnovije);
			$this -> Auto88();
			$this -> TaskMsgGUI("Web shop nadograđen na verziju $nNajnovije.");
		}
		// * Ostali slucajevi, ?! ---> reklama
		else {
			$this -> Reklama();
		}
	}

	// * Instaliraj file po file
	function InstalirajFile($sFile, $sTruePath)
	{
		$rFile = fopen($sFile, "rb");
		(string) $sSadrzaj = "";

		while(!feof($rFile)) {
			$sSadrzaj .= fread($rFile, 8192);
		}

		fclose($rFile);

		$rFile = fopen($sTruePath, "wb");
		fwrite($rFile, $sSadrzaj);
		fclose($rFile);
	}

	// * Izbaci reklamu
	function Reklama() {
		$this -> TaskMsgGUI("Posjedujete najnoviju inačicu web shop-a.");
	}

	// * Update-aj lokalnu verziju
	function UpdateWSInfo($sNajnovije)
	{
		(object) $oTemp = new INI(DB_POSTAVKE);
		$oTemp -> INIPostaviVar("SISTEM", "ver", $sNajnovije);
		$oTemp -> INISave(TRUE);
		unset($oTemp);
	}*/
}

?>